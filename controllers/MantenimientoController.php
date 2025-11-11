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
      'creado_por' => Auth::user()['id'] ?? null
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