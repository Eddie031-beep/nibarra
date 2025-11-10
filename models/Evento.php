<?php
require_once BASE_PATH.'/core/DB.php';
class Evento {
  public static function byMonth($y,$m){
    $st=DB::pdo()->prepare("SELECT * FROM eventos WHERE YEAR(fecha)=? AND MONTH(fecha)=? ORDER BY fecha ASC");
    $st->execute([$y,$m]); return $st->fetchAll();
  }
  public static function create($t,$d,$f,$equipo_id=null){
    $st=DB::pdo()->prepare("INSERT INTO eventos(titulo,detalle,fecha,equipo_id) VALUES(?,?,?,?)");
    $st->execute([$t,$d,$f,$equipo_id]);
  }
  public static function delete($id){ DB::pdo()->prepare("DELETE FROM eventos WHERE id=?")->execute([$id]); }
}
