<?php
require_once BASE_PATH.'/models/Usuario.php';
class AuthController {
  public function loginView(){
    if(Auth::check()) return redirect('/');
    include VIEWS_PATH.'/auth/login.php';
  }
  public function login(){
    $email = post('email'); $pass = post('password');
    $user = Usuario::byEmail($email);
    if($user && password_verify($pass, $user['password'])){
      Auth::login(['id'=>$user['id'],'email'=>$user['email'],'nombre'=>$user['nombre'],'rol'=>$user['rol_nombre']]);
      return redirect('/equipos');
    }
    $error="Credenciales inválidas";
    include VIEWS_PATH.'/auth/login.php';
  }
  public function logout(){ Auth::logout(); redirect('/login'); }
}
