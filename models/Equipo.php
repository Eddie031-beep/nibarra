<?php
require_once BASE_PATH.'/core/DB.php';
class Equipo {
  public static function all(){
    return DB::pdo()->query("SELECT * FROM equipos ORDER BY created_at DESC")->fetchAll();
  }
  public static function create($d){
    $sql="INSERT INTO equipos(codigo,nombre,categoria,marca,modelo,nro_serie,ubicacion,fecha_compra,proveedor,costo,estado)
          VALUES(:codigo,:nombre,:categoria,:marca,:modelo,:nro_serie,:ubicacion,:fecha_compra,:proveedor,:costo,:estado)";
    DB::pdo()->prepare($sql)->execute($d);
    $id = DB::pdo()->lastInsertId();
    log_audit('equipos',$id,'insert',$d);
  }
  public static function updateById($id,$d){
    $sql="UPDATE equipos SET codigo=:codigo,nombre=:nombre,categoria=:categoria,marca=:marca,modelo=:modelo,
          nro_serie=:nro_serie,ubicacion=:ubicacion,fecha_compra=:fecha_compra,proveedor=:proveedor,costo=:costo,estado=:estado
          WHERE id=:id";
    $d['id']=$id; DB::pdo()->prepare($sql)->execute($d);
    log_audit('equipos',$id,'update',$d);
  }
  public static function delete($id){
    DB::pdo()->prepare("DELETE FROM equipos WHERE id=?")->execute([$id]);
    log_audit('equipos',$id,'delete',null);
  }
}
