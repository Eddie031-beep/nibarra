<?php
require_once BASE_PATH.'/core/DB.php';

class Mantenimiento {
  // Devuelve columnas por estado con progreso manual
  public static function porEstado(){
    $sql = "SELECT m.*, e.nombre AS equipo_nombre,
                 COALESCE(tt.total, 0) total_tareas,
                 COALESCE(m.progreso, 0) AS pct
          FROM mantenimientos m
          JOIN equipos e ON e.id = m.equipo_id
          LEFT JOIN (
             SELECT mantenimiento_id, COUNT(*) total 
             FROM mantenimiento_tareas 
             GROUP BY mantenimiento_id
          ) tt ON tt.mantenimiento_id = m.id
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
    
    // Si se mueve a completado, poner progreso en 100%
    $progreso_update = '';
    if($nuevo_estado === 'completado'){
      $progreso_update = ', progreso = 100';
    } elseif($nuevo_estado === 'pendiente'){
      $progreso_update = ', progreso = 0';
    }
    
    $st = DB::pdo()->prepare("UPDATE mantenimientos SET estado = ?, updated_at = NOW() $progreso_update WHERE id = ?");
    $st->execute([$nuevo_estado, $id]);
    
    log_audit('mantenimientos', $id, 'update', ['estado' => $nuevo_estado]);
    return true;
  }
  
  // NUEVO: Actualizar progreso manualmente
  public static function actualizarProgreso($id, $progreso){
    $progreso = max(0, min(100, (int)$progreso)); // Limitar entre 0-100
    
    $st = DB::pdo()->prepare("UPDATE mantenimientos SET progreso = ?, updated_at = NOW() WHERE id = ?");
    $st->execute([$progreso, $id]);
    
    log_audit('mantenimientos', $id, 'update', ['progreso' => $progreso]);
    
    // Si llega a 100%, mover automáticamente a completado
    if($progreso >= 100){
      self::mover($id, 'completado');
    }
    
    return true;
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