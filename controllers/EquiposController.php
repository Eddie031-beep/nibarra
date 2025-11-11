<?php
require_once BASE_PATH.'/models/Equipo.php';
require_once BASE_PATH.'/core/Permisos.php';

class EquiposController {
  public function index(){ 
    Auth::requireLogin(); 
    $equipos=Equipo::all(); 
    view('equipos/index', compact('equipos')); 
  }
  
  public function store(){
    Auth::requireLogin();
    Permisos::requireCrear(); // ✅ Verificar permiso de creación
    
    $d=[
      'codigo'=>post('codigo'), 
      'nombre'=>post('nombre'),
      'categoria'=>post('categoria'), 
      'marca'=>post('marca'), 
      'modelo'=>post('modelo'),
      'nro_serie'=>post('nro_serie'), 
      'ubicacion'=>post('ubicacion'),
      'fecha_compra'=>post('fecha_compra')?:null, 
      'proveedor'=>post('proveedor'),
      'costo'=>post('costo')!==''?post('costo'):null, 
      'estado'=>post('estado')?:'operativo'
    ];
    
    Equipo::create($d); 
    redirect('/equipos');
  }
  
  public function update($id){
    Auth::requireLogin();
    Permisos::requireEditar(); // ✅ Verificar permiso de edición
    
    $d=[
      'codigo'=>post('codigo'), 
      'nombre'=>post('nombre'),
      'categoria'=>post('categoria'), 
      'marca'=>post('marca'), 
      'modelo'=>post('modelo'),
      'nro_serie'=>post('nro_serie'), 
      'ubicacion'=>post('ubicacion'),
      'fecha_compra'=>post('fecha_compra')?:null, 
      'proveedor'=>post('proveedor'),
      'costo'=>post('costo')!==''?post('costo'):null, 
      'estado'=>post('estado')?:'operativo'
    ];
    
    Equipo::updateById($id,$d); 
    redirect('/equipos');
  }
  
  public function destroy($id){ 
    Auth::requireLogin(); 
    Permisos::requireEliminar(); // ✅ Solo admin puede eliminar
    
    Equipo::delete($id); 
    redirect('/equipos'); 
  }
  
  public function replicaHealth(){ 
    Response::json(['replica_ok'=> DB::replicaPing()]); 
  }
}