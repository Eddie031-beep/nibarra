<?php
require_once __DIR__ . '/../helpers/sync.php';

// Sanitizar input básico
$nombre  = trim($_POST['nombre']  ?? '');
$modelo  = trim($_POST['modelo']  ?? '');
$serial  = trim($_POST['serial']  ?? '');
$ubic    = trim($_POST['ubicacion'] ?? '');

if ($nombre === '' || $modelo === '' || $serial === '') {
  http_response_code(422);
  exit('Faltan campos obligatorios.');
}

$sql = "INSERT INTO equipos (nombre, modelo, numero_serie, ubicacion, created_at)
        VALUES (?, ?, ?, ?, NOW())";

$res = sync_exec($sql, [$nombre, $modelo, $serial, $ubic]);

if ($res['ok']) {
  header('Location: /nibarra/views/equipos/index.php?ok=1');
  exit;
}

http_response_code(500);
echo "Error al guardar en ambas BD. Detalle: " . htmlspecialchars($res['error']);
