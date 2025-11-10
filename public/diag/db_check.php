<?php
require_once __DIR__ . '/../../src/helpers/db.php';

header('Content-Type: text/plain; charset=utf-8');

function ping(PDO $pdo) {
  return $pdo->query("SELECT @@hostname host, @@port port")->fetch();
}

echo "[UBUNTU]\n";
try {
  $r = ping(db_ubuntu());
  echo "  OK  => {$r['host']}:{$r['port']}\n";
} catch (Throwable $e) {
  echo "  FAIL => " . $e->getMessage() . "\n";
}

echo "\n[WINDOWS]\n";
try {
  $r = ping(db_windows());
  echo "  OK  => {$r['host']}:{$r['port']}\n";
} catch (Throwable $e) {
  echo "  FAIL => " . $e->getMessage() . "\n";
}
