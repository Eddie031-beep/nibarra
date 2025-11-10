<?php
require_once BASE_PATH.'/models/Mantenimiento.php';
class MantenimientoController {
  public function index(){ Auth::requireLogin(); $cols=Mantenimiento::porEstado(); view('mantenimiento/index', compact('cols')); }
  public function mover(){ Auth::requireLogin(); Mantenimiento::mover((int)post('id'), post('estado')); Response::json(['ok'=>true]); }
  public function tareaToggle(){ Auth::requireLogin(); $mid=Mantenimiento::toggleTarea((int)post('tarea_id'), (int)post('hecho')); Response::json(['ok'=>true,'mantenimiento_id'=>$mid]); }
  public function tareaNueva(){ Auth::requireLogin(); Mantenimiento::crearTarea((int)post('mantenimiento_id'), post('titulo')); Response::json(['ok'=>true]); }
}
