<?php
require_once BASE_PATH.'/core/DB.php';
class Usuario {
  public static function byEmail($email){
    $st=DB::pdo()->prepare("SELECT u.*, r.nombre AS rol_nombre FROM users u JOIN roles r ON r.id=u.role_id WHERE email=? LIMIT 1");
    $st->execute([$email]); return $st->fetch();
  }
}
