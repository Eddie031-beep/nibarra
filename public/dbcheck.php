<?php
require_once dirname(__DIR__).'/core/DB.php';
header('Content-Type: text/plain; charset=utf-8');
try {
  $pdo = DB::pdo();
  echo "OK Ubuntu (3306) -> VERSION(): ".$pdo->query('SELECT VERSION()')->fetchColumn()."\n";
} catch (Throwable $e) {
  echo "ERROR Ubuntu (3306): ".$e->getMessage()."\n";
}
echo "Replica Windows (".ENV_DB['replica']['host'].":".ENV_DB['replica']['port']."): ";
echo DB::replicaPing() ? "OK\n" : "NO CONECTA\n";
