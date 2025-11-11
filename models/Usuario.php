<?php
require_once BASE_PATH.'/core/DB.php';

class Usuario {
  public static function byEmail($email){
    try {
      $st = DB::pdo()->prepare("SELECT u.*, r.nombre AS rol_nombre FROM users u JOIN roles r ON r.id=u.role_id WHERE email=? LIMIT 1");
      $st->execute([$email]); 
      return $st->fetch();
    } catch (Throwable $e) {
      error_log("Error en byEmail: " . $e->getMessage());
      return false;
    }
  }
  
  public static function all(){
    try {
      return DB::pdo()->query("SELECT u.*, r.nombre AS rol_nombre FROM users u JOIN roles r ON r.id=u.role_id ORDER BY u.created_at DESC")->fetchAll();
    } catch (Throwable $e) {
      error_log("Error en all: " . $e->getMessage());
      return [];
    }
  }
  
  public static function create($d){
    try {
      $sql = "INSERT INTO users(nombre,email,password,role_id) VALUES(:nombre,:email,:password,:role_id)";
      DB::pdo()->prepare($sql)->execute($d);
      $id = DB::pdo()->lastInsertId();
      log_audit('users', $id, 'insert', $d);
      return $id;
    } catch (Throwable $e) {
      error_log("Error en create: " . $e->getMessage());
      throw $e;
    }
  }
  
  public static function updateById($id, $d){
    try {
      $sql = "UPDATE users SET nombre=:nombre, email=:email, role_id=:role_id WHERE id=:id";
      $d['id'] = $id; 
      DB::pdo()->prepare($sql)->execute($d);
      log_audit('users', $id, 'update', $d);
    } catch (Throwable $e) {
      error_log("Error en updateById: " . $e->getMessage());
      throw $e;
    }
  }
  
  public static function delete($id){
    try {
      DB::pdo()->prepare("DELETE FROM users WHERE id=?")->execute([$id]);
      log_audit('users', $id, 'delete', null);
    } catch (Throwable $e) {
      error_log("Error en delete: " . $e->getMessage());
      throw $e;
    }
  }
  
  public static function byId($id){
    try {
      $st = DB::pdo()->prepare("SELECT u.*, r.nombre AS rol_nombre FROM users u JOIN roles r ON r.id=u.role_id WHERE u.id=? LIMIT 1");
      $st->execute([$id]); 
      return $st->fetch();
    } catch (Throwable $e) {
      error_log("Error en byId: " . $e->getMessage());
      return false;
    }
  }
}