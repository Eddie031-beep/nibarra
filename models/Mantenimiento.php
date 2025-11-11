<?php
require_once BASE_PATH.'/core/DB.php';

class Mantenimiento {
  // Devuelve columnas por estado con % calculado por tareas
  public static function porEstado(){
    $sql = "SELECT m.*, e.nombre AS equipo_nombre,
                 COALESCE(tt.total, 0) total_tareas,
                 COALESCE(th.hechas, 0) hechas_tareas,
                 CASE 
                   WHEN COALESCE(tt.total, 0) = 0 THEN 0
                   ELSE ROUND(100.0 * COALESCE(th.hechas, 0) / tt.total) 
                 END AS pct
          FROM mantenimientos m
          JOIN equipos e ON e.id = m.equipo_id
          LEFT JOIN (
             SELECT mantenimiento_id, COUNT(*) total 
             FROM mantenimiento_tareas 
             GROUP BY mantenimiento_id
          ) tt ON tt.mantenimiento_id = m.id
          LEFT JOIN (
             SELECT mantenimiento_id, COUNT(*) hechas 
             FROM mantenimiento_tareas 
             WHERE hecho = 1 
             GROUP BY mantenimiento_id
          ) th ON th.mantenimiento_id = m.id
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
    
    $st = DB::pdo()->prepare("UPDATE mantenimientos SET estado = ?, updated_at = NOW() WHERE id = ?");
    $st->execute([$nuevo_estado, $id]);
    
    log_audit('mantenimientos', $id, 'update', ['estado' => $nuevo_estado]);
    return true;
  }
  
  public static function toggleTarea($tarea_id, $hecho){
    $st = DB::pdo()->prepare("UPDATE mantenimiento_tareas SET hecho = ? WHERE id = ?");
    $st->execute([(int)$hecho, $tarea_id]);
    
    // Obtener el ID del mantenimiento asociado
    $mid = DB::pdo()->query("SELECT mantenimiento_id FROM mantenimiento_tareas WHERE id = " . (int)$tarea_id)->fetchColumn();
    
    log_audit('mantenimiento_tareas', $tarea_id, 'update', ['hecho' => $hecho]);
    
    return $mid;
  }
  
  public static function crearTarea($mantenimiento_id, $titulo){
    // Obtener el orden más alto actual
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
}