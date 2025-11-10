<?php
function view($path, $vars=[]){
  extract($vars);
  include VIEWS_PATH.'/layouts/header.php';
  include VIEWS_PATH.'/'.$path.'.php';
  include VIEWS_PATH.'/layouts/footer.php';
}
function redirect($path){ header('Location: '.ENV_APP['BASE_URL'].$path); exit; }
function post($k,$d=null){ return $_POST[$k]??$d; }
function safe($s){ return htmlspecialchars($s??'',ENT_QUOTES,'UTF-8'); }

function log_audit($tabla,$registro_id,$accion,$detalle=null){
  try{
    $u = Auth::user();
    $st = DB::pdo()->prepare("INSERT INTO audit_logs(usuario_id,tabla,registro_id,accion,detalle) VALUES(?,?,?,?,?)");
    $st->execute([ $u['id'] ?? null, $tabla, (string)$registro_id, $accion, $detalle ? json_encode($detalle, JSON_UNESCAPED_UNICODE) : null ]);
  }catch(Throwable $e){ /* no romper flujo si falla log */ }
}
