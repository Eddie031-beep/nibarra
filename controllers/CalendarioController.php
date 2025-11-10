<?php
require_once BASE_PATH.'/models/CalendarioEvento.php';
class CalendarioController {
  public function index(){
    Auth::requireLogin();
    $y=(int)($_GET['y']??date('Y')); $m=(int)($_GET['m']??date('n'));
    $eventos=CalendarioEvento::byMonth($y,$m);
    view('calendario/index', compact('y','m','eventos'));
  }
  public function store(){
    Auth::requireLogin();
    $all_day = post('all_day') ? 1 : 0;
    $inicio  = post('inicio'); $fin = post('fin')?:null;
    $d = [
      'titulo'=>post('titulo'),
      'inicio'=>$inicio,
      'fin'=>$all_day ? null : $fin,
      'all_day'=>$all_day,
      'color'=>post('color')?:null,
      'mantenimiento_id'=>post('mantenimiento_id')?:null,
      'creado_por'=>Auth::user()['id'] ?? null
    ];
    CalendarioEvento::create($d);
    redirect('/calendario?y='.urlencode(post('y')).'&m='.urlencode(post('m')));
  }
  public function destroy($id){ Auth::requireLogin(); CalendarioEvento::delete($id); redirect('/calendario'); }
}
