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
    Permisos::requireCrear(); // ✅ Solo admin y técnico
    
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
    
    // Crear evento en calendario automáticamente
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
  
  $sql = "SELECT m.*, e.nombre AS equipo_nombre
          FROM mantenimientos m
          JOIN equipos e ON e.id = m.equipo_id
          WHERE m.id = ?";
  
  $mant = DB::pdo()->prepare($sql)->execute([$id])->fetch();
  
  if (!$mant) {
    http_response_code(404);
    Response::json(['error' => 'No encontrado']);
    return;
  }
  
  // Obtener tareas
  $tareas = DB::pdo()->prepare(
    "SELECT * FROM mantenimiento_tareas WHERE mantenimiento_id = ? ORDER BY orden"
  )->execute([$id])->fetchAll();
  
  $mant['tareas'] = $tareas;
  
  Response::json($mant);
}
  public function mover(){ 
    Auth::requireLogin();
    Permisos::requireEditar(); // ✅ Solo admin y técnico
    
    Mantenimiento::mover((int)post('id'), post('estado')); 
    Response::json(['ok'=>true]); 
  }
  
  public function tareaToggle(){ 
    Auth::requireLogin();
    Permisos::requireEditar(); // ✅ Solo admin y técnico
    
    $mid = Mantenimiento::toggleTarea((int)post('tarea_id'), (int)post('hecho')); 
    Response::json(['ok'=>true,'mantenimiento_id'=>$mid]); 
  }
  
  public function tareaNueva(){ 
    Auth::requireLogin();
    Permisos::requireEditar(); // ✅ Solo admin y técnico
    
    Mantenimiento::crearTarea((int)post('mantenimiento_id'), post('titulo')); 
    Response::json(['ok'=>true]); 
  }
  
  public function destroy($id){
    Auth::requireLogin();
    Permisos::requireEliminar(); // ✅ Solo admin
    
    Mantenimiento::delete($id);
    redirect('/mantenimiento');
  }
}
