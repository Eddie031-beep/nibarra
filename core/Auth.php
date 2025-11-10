<?php
class Auth {
  public static function start(){ if(session_status()===PHP_SESSION_NONE) session_start(); }
  public static function check(){ self::start(); return isset($_SESSION['user']); }
  public static function user(){ self::start(); return $_SESSION['user'] ?? null; }
  public static function login($u){ self::start(); $_SESSION['user']=$u; }
  public static function logout(){ self::start(); session_destroy(); }
  public static function requireLogin(){
    if(!self::check()){ header('Location: '.ENV_APP['BASE_URL'].'/login'); exit; }
  }
}
