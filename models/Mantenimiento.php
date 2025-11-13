<?php
require_once BASE_PATH.'/core/DB.php';

class Mantenimiento {
  // Devuelve columnas por estado con progreso manual
  // MODIFICADO: Excluye mantenimientos completados que ya tienen factura
  public static function porEstado(){
    $sql = "SELECT m.*, e.nombre AS equipo_nombre,
                 COALESCE(tt.total, 0) total_tareas,
                 COALESCE(m.progreso, 0) AS pct,
                 f.id as factura_id
          FROM mantenimientos m
          JOIN equipos e ON e.id = m.equipo_id
          LEFT JOIN (
             SELECT mantenimiento_id, COUNT(*) total 
             FROM mantenimiento_tareas 
             GROUP BY mantenimiento_id
          ) tt ON tt.mantenimiento_id = m.id
          LEFT JOIN facturas f ON f.mantenimiento_id = m.id
          WHERE NOT (m.estado = 'completado' AND f.id IS NOT NULL)
          ORDER BY m.updated_at DESC";
    
    $rows = DB::pdo()->query($sql)->fetchAll();
    
    // Agrupar por estado
    $cols = [
      'pendiente' => [], 
      'en_progreso' => [], 
      'completado' => [], 
      'cancelado' => []
    ];
    
    foreach($rows as $r) { 
      $estado = $r['estado'] ?? 'pendiente';
      if(isset($cols[$estado])){
        $cols[$estado][] = $r; 
      }
    }
    
    return $cols;
  }
      
  public static function mover($id, $nuevo_estado){
      $estados_validos = ['pendiente', 'en_progreso', 'completado', 'cancelado'];
      
      if(!in_array($nuevo_estado, $estados_validos)){
        return false;
      }
      
      $pdo = DB::pdo();
      
      // Si se mueve a completado, poner progreso en 100% y guardar costo_real
      if($nuevo_estado === 'completado'){
        // Obtener el costo estimado para usarlo como costo_real si no se especificó
        $stmt = $pdo->prepare("SELECT costo_estimado, costo_real FROM mantenimientos WHERE id = ?");
        $stmt->execute([$id]);
        $mant = $stmt->fetch();
        
        // Si no hay costo_real, usar el costo_estimado
        $costo_real = $mant['costo_real'] ?? $mant['costo_estimado'] ?? 0;
        
        $pdo->prepare("UPDATE mantenimientos 
                      SET estado = ?, 
                          progreso = 100, 
                          costo_real = ?,
                          updated_at = NOW() 
                      WHERE id = ?")
            ->execute([$nuevo_estado, $costo_real, $id]);
        
        log_audit('mantenimientos', $id, 'update', [
          'estado' => $nuevo_estado, 
          'progreso' => 100,
          'costo_real' => $costo_real
        ]);
        
        // Generar factura automáticamente
        try {
          require_once BASE_PATH.'/models/Factura.php';
          $factura = Factura::crearDesdeMantenimiento($id);
          return ['ok' => true, 'factura_generada' => true, 'factura_numero' => $factura['numero_factura']];
        } catch (Exception $e) {
          error_log("Error al generar factura para mantenimiento {$id}: " . $e->getMessage());
          return ['ok' => true, 'factura_generada' => false];
        }
      }
      
      // Si se mueve a pendiente, resetear progreso a 0
      elseif($nuevo_estado === 'pendiente'){
        $pdo->prepare("UPDATE mantenimientos SET estado = ?, progreso = 0, updated_at = NOW() WHERE id = ?")
            ->execute([$nuevo_estado, $id]);
      }
      
      // Para otros estados, solo actualizar estado
      else {
        $pdo->prepare("UPDATE mantenimientos SET estado = ?, updated_at = NOW() WHERE id = ?")
            ->execute([$nuevo_estado, $id]);
      }
      
      log_audit('mantenimientos', $id, 'update', ['estado' => $nuevo_estado]);
      
      return ['ok' => true];
    }
  
  // NUEVO: Actualizar progreso manualmente
  public static function actualizarProgreso($id, $progreso){
    $progreso = max(0, min(100, (int)$progreso)); // Limitar entre 0-100
    
    $pdo = DB::pdo();
    $st = $pdo->prepare("UPDATE mantenimientos SET progreso = ?, updated_at = NOW() WHERE id = ?");
    $st->execute([$progreso, $id]);
    
    log_audit('mantenimientos', $id, 'update', ['progreso' => $progreso]);
    
    // Si llega a 100%, mover automáticamente a completado y generar factura
    if($progreso >= 100){
      return self::mover($id, 'completado');
    }
    
    return ['ok' => true];
  }
  
  // Las tareas ya NO afectan el progreso
  public static function toggleTarea($tarea_id, $hecho){
    $st = DB::pdo()->prepare("UPDATE mantenimiento_tareas SET hecho = ? WHERE id = ?");
    $st->execute([(int)$hecho, $tarea_id]);
    
    $mid = DB::pdo()->query("SELECT mantenimiento_id FROM mantenimiento_tareas WHERE id = " . (int)$tarea_id)->fetchColumn();
    
    log_audit('mantenimiento_tareas', $tarea_id, 'update', ['hecho' => $hecho]);
    
    return $mid;
  }
  
  public static function crearTarea($mantenimiento_id, $titulo){
    $max_orden = DB::pdo()->query(
      "SELECT COALESCE(MAX(orden), 0) FROM mantenimiento_tareas WHERE mantenimiento_id = " . (int)$mantenimiento_id
    )->fetchColumn();
    
    $st = DB::pdo()->prepare(
      "INSERT INTO mantenimiento_tareas(mantenimiento_id, titulo, hecho, orden) VALUES(?, ?, 0, ?)"
    );
    $st->execute([$mantenimiento_id, $titulo, $max_orden + 1]);
    
    $tarea_id = DB::pdo()->lastInsertId();
    
    log_audit('mantenimiento_tareas', $tarea_id, 'insert', [
      'mantenimiento_id' => $mantenimiento_id,
      'titulo' => $titulo
    ]);
    
    return $tarea_id;
  }
  
  public static function create($d){
    $sql = "INSERT INTO mantenimientos (
              equipo_id, titulo, descripcion, tipo, prioridad, fecha_programada,
              costo_estimado, estado, tecnico_id, progreso
            ) VALUES (
              :equipo_id, :titulo, :descripcion, :tipo, :prioridad, :fecha_programada,
              :costo_estimado, :estado, :tecnico_id, 0
            )";

    $pdo = DB::pdo();
    $st  = $pdo->prepare($sql);

    $equipo_id       = (int)($d['equipo_id'] ?? 0);
    $titulo          = trim($d['titulo'] ?? '');
    $descripcion     = trim($d['descripcion'] ?? '');
    $tipo            = $d['tipo'] ?? 'preventivo';
    $prioridad       = $d['prioridad'] ?? 'media';
    $fecha_programada= ($d['fecha_programada'] ?? '') ?: null;
    $costo_estimado  = ($d['costo_estimado']   ?? '') === '' ? null : $d['costo_estimado'];
    $estado          = $d['estado'] ?? 'pendiente';
    $tecnico_id      = isset($d['tecnico_id']) && $d['tecnico_id'] !== '' ? (int)$d['tecnico_id'] : null;

    $st->bindValue(':equipo_id', $equipo_id, \PDO::PARAM_INT);
    $st->bindValue(':titulo', $titulo, \PDO::PARAM_STR);
    $st->bindValue(':descripcion', $descripcion, \PDO::PARAM_STR);
    $st->bindValue(':tipo', $tipo, \PDO::PARAM_STR);
    $st->bindValue(':prioridad', $prioridad, \PDO::PARAM_STR);

    if ($fecha_programada === null) $st->bindValue(':fecha_programada', null, \PDO::PARAM_NULL);
    else                            $st->bindValue(':fecha_programada', $fecha_programada, \PDO::PARAM_STR);

    if ($costo_estimado === null)   $st->bindValue(':costo_estimado', null, \PDO::PARAM_NULL);
    else                            $st->bindValue(':costo_estimado', $costo_estimado, \PDO::PARAM_STR);

    $st->bindValue(':estado', $estado, \PDO::PARAM_STR);

    if ($tecnico_id === null)       $st->bindValue(':tecnico_id', null, \PDO::PARAM_NULL);
    else                            $st->bindValue(':tecnico_id', $tecnico_id, \PDO::PARAM_INT);

    $st->execute();

    $id = $pdo->lastInsertId();
    log_audit('mantenimientos', $id, 'insert', [
      'equipo_id'=>$equipo_id,'titulo'=>$titulo,'tipo'=>$tipo,'prioridad'=>$prioridad,
      'fecha_programada'=>$fecha_programada,'costo_estimado'=>$costo_estimado,
      'estado'=>$estado,'tecnico_id'=>$tecnico_id
    ]);
    return $id;
  }
  
  public static function delete($id){
    DB::pdo()->prepare("DELETE FROM mantenimientos WHERE id=?")->execute([$id]);
    log_audit('mantenimientos', $id, 'delete', null);
  }
}