<?php
class DB {
  private static $pdo;
  public static function pdo() {
    if (!self::$pdo) {
      $cfgs = require BASE_PATH.'/config/database.php';
      $c = $cfgs['local']; // Escribimos en local (replica es a nivel de motor)
      $dsn="mysql:host={$c['host']};port={$c['port']};dbname={$c['db']};charset={$c['charset']}";
      self::$pdo = new PDO($dsn, $c['user'], $c['pass'], [
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC
      ]);
    }
    return self::$pdo;
  }
  public static function replicaPing(): bool {
    try {
    $cfgs = require BASE_PATH.'/config/database.php';
    $r = $cfgs['replica'];
    $dsn="mysql:host={$r['host']};port={$r['port']};dbname={$r['db']};charset={$r['charset']}";
    $pdo = new PDO($dsn, $r['user'], $r['pass']);
    return (int)$pdo->query("SELECT 1")->fetchColumn()===1;
    } catch(Throwable $e){ return false; }
  }
}
