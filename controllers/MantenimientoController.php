<?php
require_once BASE_PATH.'/models/Mantenimiento.php';
require_once BASE_PATH.'/core/Permisos.php';

class MantenimientoController {
  public function index(){ 
    Auth::requireLogin(); 
    $cols = Mantenimiento::porEstado(); 
    view('mantenimiento/index', compact('cols')); 
  }
  
  public function store(){
    Auth::requireLogin();
    Permisos::requireCrear();
    
    $equipo_id = (int)post('equipo_id');
    $titulo = post('titulo');
    $descripcion = post('descripcion', '');
    $tipo = post('tipo', 'preventivo');
    $prioridad = post('prioridad', 'media');
    $fecha_programada = post('fecha_programada');
    $costo_estimado = post('costo_estimado') !== '' ? (float)post('costo_estimado') : null;
    
    $d = [
      'equipo_id' => $equipo_id,
      'titulo' => $titulo,
      'descripcion' => $descripcion,
      'tipo' => $tipo,
      'prioridad' => $prioridad,
      'fecha_programada' => $fecha_programada,
      'costo_estimado' => $costo_estimado,
      'estado' => 'pendiente',
      'tecnico_id' => Auth::user()['id'] ?? null
    ];
    
    $mantenimiento_id = Mantenimiento::create($d);
    
    if ($mantenimiento_id && $fecha_programada) {
      require_once BASE_PATH.'/models/CalendarioEvento.php';
      
      $color_prioridad = [
        'baja' => '#10b981',
        'media' => '#f59e0b', 
        'alta' => '#ef4444',
        'critica' => '#7f1d1d'
      ];
      
      CalendarioEvento::create([
        'titulo' => "🔧 " . $titulo,
        'inicio' => $fecha_programada,
        'fin' => null,
        'all_day' => 0,
        'color' => $color_prioridad[$prioridad] ?? '#3b82f6',
        'mantenimiento_id' => $mantenimiento_id,
        'creado_por' => Auth::user()['id'] ?? null
      ]);
    }
    
    redirect('/mantenimiento');
  }
  
  public function obtener($id) {
    Auth::requireLogin();
    
    try {
      $pdo = DB::pdo();
      
      $sql = "SELECT m.*, e.nombre AS equipo_nombre, e.codigo AS equipo_codigo
              FROM mantenimientos m
              JOIN equipos e ON e.id = m.equipo_id
              WHERE m.id = ?";
      
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$id]);
      $mant = $stmt->fetch();
      
      if (!$mant) {
        http_response_code(404);
        Response::json(['error' => 'Mantenimiento no encontrado']);
        return;
      }
      
      $sql_tareas = "SELECT * FROM mantenimiento_tareas 
                     WHERE mantenimiento_id = ? 
                     ORDER BY orden, id";
      
      $stmt_tareas = $pdo->prepare($sql_tareas);
      $stmt_tareas->execute([$id]);
      $tareas = $stmt_tareas->fetchAll();
      
      $mant['tareas'] = $tareas;
      $mant['total_tareas'] = count($tareas);
      $mant['progreso'] = (int)($mant['progreso'] ?? 0);
      
      Response::json($mant);
      
    } catch (Throwable $e) {
      error_log("Error en obtener mantenimiento: " . $e->getMessage());
      http_response_code(500);
      Response::json(['error' => 'Error al obtener datos: ' . $e->getMessage()]);
    }
  }
  public function mover(){ 
  Auth::requireLogin();
  Permisos::requireEditar();
  
  $id = (int)post('id');
  $estado = post('estado');
  
  $success = Mantenimiento::mover($id, $estado);
  
  // Si se movió a completado, verificar si se generó factura
  if($success && $estado === 'completado'){
    try {
      require_once BASE_PATH.'/models/Factura.php';
      $factura = Factura::obtenerPorMantenimiento($id);
      
      if($factura){
        Response::json([
          'ok' => true,
          'factura_generada' => true,
          'factura_numero' => $factura['numero_factura'],
          'mensaje' => '✅ Mantenimiento completado y factura generada'
        ]);
        return;
      }
    } catch (Exception $e) {
      error_log("Error al verificar factura: " . $e->getMessage());
    }
  }
  
  Response::json(['ok' => $success]); 
}
  
  // NUEVO: Endpoint para actualizar progreso
  public function actualizarProgreso(){
    Auth::requireLogin();
    Permisos::requireEditar();
    
    $id = (int)post('mantenimiento_id');
    $progreso = (int)post('progreso');
    
    Mantenimiento::actualizarProgreso($id, $progreso);
    Response::json(['ok'=>true, 'progreso'=>$progreso]);
  }
  
  public function tareaToggle(){ 
    Auth::requireLogin();
    Permisos::requireEditar();
    
    $mid = Mantenimiento::toggleTarea((int)post('tarea_id'), (int)post('hecho')); 
    Response::json(['ok'=>true,'mantenimiento_id'=>$mid]); 
  }
  
  public function tareaNueva(){ 
    Auth::requireLogin();
    Permisos::requireEditar();
    
    Mantenimiento::crearTarea((int)post('mantenimiento_id'), post('titulo')); 
    Response::json(['ok'=>true]); 
  }
  
  public function tareaEliminar(){
    Auth::requireLogin();
    Permisos::requireEditar();
    
    $tarea_id = (int)post('tarea_id');
    
    try {
      $pdo = DB::pdo();
      
      $stmt = $pdo->prepare("SELECT mantenimiento_id FROM mantenimiento_tareas WHERE id = ?");
      $stmt->execute([$tarea_id]);
      $mantenimiento_id = $stmt->fetchColumn();
      
      $stmt = $pdo->prepare("DELETE FROM mantenimiento_tareas WHERE id = ?");
      $stmt->execute([$tarea_id]);
      
      log_audit('mantenimiento_tareas', $tarea_id, 'delete', ['mantenimiento_id' => $mantenimiento_id]);
      
      Response::json(['ok'=>true, 'mantenimiento_id'=>$mantenimiento_id]);
    } catch (Throwable $e) {
      error_log("Error al eliminar tarea: " . $e->getMessage());
      Response::json(['ok'=>false, 'error'=>$e->getMessage()], 500);
    }
  }
  
  public function destroy($id){
    Auth::requireLogin();
    Permisos::requireEliminar();
    
    Mantenimiento::delete($id);
    redirect('/mantenimiento');
  }
}

function editarDesdePreview() {
  if (currentEquipoData) {
    editarEquipo(currentEquipoData.id, currentEquipoData);
    cerrarPreviewEquipo();
  }
}