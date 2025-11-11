<?php
require_once BASE_PATH.'/models/Usuario.php';

class AuthController {
  public function loginView(){
    if(Auth::check()) return redirect('/');
    include VIEWS_PATH.'/auth/login.php';
  }
  
  public function login(){
    $email = post('email'); 
    $pass = post('password');
    $user = Usuario::byEmail($email);
    
    if($user && password_verify($pass, $user['password'])){
      Auth::login([
        'id'=>$user['id'],
        'email'=>$user['email'],
        'nombre'=>$user['nombre'],
        'rol'=>$user['rol_nombre']
      ]);
      return redirect('/equipos');
    }
    
    $error = "Credenciales inválidas";
    include VIEWS_PATH.'/auth/login.php';
  }
  
  public function logout(){ 
    Auth::logout(); 
    redirect('/login'); 
  }
  
  public function registerView(){
    if(Auth::check()) return redirect('/');
    // Inicializar variables para evitar errores
    $error = null;
    $success = null;
    include VIEWS_PATH.'/auth/register.php';
  }
  
  public function register(){
    $error = null;
    $success = null;
    
    try {
      // Validaciones
      $nombre = post('nombre');
      $email = post('email');
      $password = post('password');
      $password_confirm = post('password_confirm');
      $role_id = (int)post('role_id', 3); // Por defecto visor
      
      if(empty($nombre) || empty($email) || empty($password)){
        $error = "Todos los campos son requeridos";
      } elseif($password !== $password_confirm){
        $error = "Las contraseñas no coinciden";
      } elseif(strlen($password) < 6){
        $error = "La contraseña debe tener al menos 6 caracteres";
      } elseif(Usuario::byEmail($email)){
        $error = "Este email ya está registrado";
      } else {
        // Crear usuario
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        Usuario::create([
          'nombre' => $nombre,
          'email' => $email,
          'password' => $hashed,
          'role_id' => $role_id
        ]);
        $success = "✓ Cuenta creada exitosamente. Ya puedes iniciar sesión.";
      }
    } catch (Throwable $e) {
      $error = "Error al crear la cuenta: " . $e->getMessage();
    }
    
    include VIEWS_PATH.'/auth/register.php';
  }
}