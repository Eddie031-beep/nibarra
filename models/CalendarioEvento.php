<?php
require_once BASE_PATH.'/core/DB.php';

class CalendarioEvento {
  public static function byMonth($y,$m){
    $start = sprintf('%04d-%02d-01 00:00:00',$y,$m);
    $end   = date('Y-m-d H:i:s', strtotime("$start +1 month"));
    $st=DB::pdo()->prepare("SELECT * FROM calendario_eventos WHERE inicio >= ? AND inicio < ? ORDER BY inicio ASC");
    $st->execute([$start,$end]); 
    return $st->fetchAll();
  }
  
  public static function create($d){
    $sql="INSERT INTO calendario_eventos(titulo,inicio,fin,all_day,color,mantenimiento_id,creado_por)
          VALUES(:titulo,:inicio,:fin,:all_day,:color,:mantenimiento_id,:creado_por)";
    DB::pdo()->prepare($sql)->execute($d);
    log_audit('calendario_eventos',DB::pdo()->lastInsertId(),'insert',$d);
  }
  
  public static function update($id, $d){
    $sql="UPDATE calendario_eventos 
          SET titulo=:titulo, inicio=:inicio, fin=:fin, all_day=:all_day, 
              color=:color, mantenimiento_id=:mantenimiento_id 
          WHERE id=:id";
    $d['id'] = $id;
    DB::pdo()->prepare($sql)->execute($d);
    log_audit('calendario_eventos', $id, 'update', $d);
  }
  
  public static function delete($id){
    DB::pdo()->prepare("DELETE FROM calendario_eventos WHERE id=?")->execute([$id]);
    log_audit('calendario_eventos',$id,'delete',null);
  }
}