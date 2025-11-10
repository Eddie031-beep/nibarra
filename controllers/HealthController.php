<?php
require_once BASE_PATH.'/core/DB.php';
require_once BASE_PATH.'/core/Response.php';
require_once BASE_PATH.'/core/Helpers.php';
require_once BASE_PATH.'/core/Auth.php';

class HealthController {
  public function replica(){
    Auth::start();
    $ok = DB::replicaPing();
    // Si piden JSON (ej. con ?json=1), devolvemos JSON
    if (isset($_GET['json'])) {
      return Response::json(['replica_ok' => $ok, 'host' => ENV_DB['replica']['host'], 'port' => ENV_DB['replica']['port']]);
    }
    // Vista simple (HTML)
    $host = ENV_DB['replica']['host'];
    $port = ENV_DB['replica']['port'];
    include VIEWS_PATH.'/layouts/header.php';
    include VIEWS_PATH.'/health/replica.php';
    include VIEWS_PATH.'/layouts/footer.php';
  }
}
