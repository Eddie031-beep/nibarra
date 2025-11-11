<?php
require_once BASE_PATH.'/models/Usuario.php';
class UsuariosController {
  public function index(){ 
    Auth::requireLogin(); 
    $usuarios = Usuario::all(); 
    view('usuarios/index', compact('usuarios')); 
  }
  
  public function update($id){
    Auth::requireLogin();
    $d=[
      'nombre'=>post('nombre'),
      'email'=>post('email'),
      'role_id'=>(int)post('role_id')
    ];
    Usuario::updateById($id,$d); 
    redirect('/usuarios');
  }
  
  public function destroy($id){ 
    Auth::requireLogin(); 
    Usuario::delete($id); 
    redirect('/usuarios'); 
  }
}