<?php
require_once BASE_PATH.'/core/DB.php';
require_once BASE_PATH.'/core/Response.php';
require_once BASE_PATH.'/core/Helpers.php';
require_once BASE_PATH.'/core/Auth.php';

class HealthController {
  
  public function replica(){
    Auth::start();
    
    $cfg = ENV_DB['replica'];
    $host = $cfg['host'];
    $port = $cfg['port'];
    $user = $cfg['user'];
    $db = $cfg['db'];
    
    // Test detallado
    $socketOk = false;
    $socketError = '';
    $pdoOk = false;
    $pdoError = '';
    $version = '';
    $serverTime = '';
    
    // 1. Test de socket (¿está abierto el puerto?)
    $fp = @fsockopen($host, $port, $errno, $errstr, 5);
    if ($fp) {
      $socketOk = true;
      fclose($fp);
    } else {
      $socketError = "$errstr ($errno)";
    }
    
    // 2. Test de PDO (¿podemos conectar a MySQL?)
    if ($socketOk) {
      try {
        $dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$cfg['charset']}";
        $pdo = new PDO($dsn, $user, $cfg['pass'], [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_TIMEOUT => 5
        ]);
        
        $pdoOk = true;
        $version = $pdo->query("SELECT VERSION()")->fetchColumn();
        $serverTime = $pdo->query("SELECT NOW()")->fetchColumn();
        
      } catch (PDOException $e) {
        $pdoError = $e->getMessage();
      }
    }
    
    // Si piden JSON (ej. con ?json=1), devolvemos JSON
    if (isset($_GET['json'])) {
      return Response::json([
        'replica_ok' => $pdoOk,
        'host' => $host,
        'port' => $port,
        'socket_ok' => $socketOk,
        'socket_error' => $socketError,
        'pdo_ok' => $pdoOk,
        'pdo_error' => $pdoError,
        'version' => $version,
        'server_time' => $serverTime
      ]);
    }
    
    // Vista HTML con diagnóstico completo
    include VIEWS_PATH.'/layouts/header.php';
    include VIEWS_PATH.'/health/replica.php';
    include VIEWS_PATH.'/layouts/footer.php';
  }
}