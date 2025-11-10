<?php
require_once dirname(__DIR__,2).'/src/helpers/db.php';

$codigo    = trim($_POST['codigo']    ?? '');
$nombre    = trim($_POST['nombre']    ?? '');
$categoria = trim($_POST['categoria'] ?? '');
$estado    = $_POST['estado'] ?? 'operativo';

// Validar estado permitido por ENUM
$allowed = ['operativo','fuera_de_servicio','baja'];
if (!in_array($estado, $allowed, true)) {
    $estado = 'operativo';
}

// Insert en Ubuntu
$db = db_ubuntu();
$sql = "INSERT INTO equipos (codigo,nombre,categoria,estado,created_at)
        VALUES (?,?,?,?, NOW())";
$stmt = $db->prepare($sql);
$stmt->execute([$codigo,$nombre,$categoria,$estado]);

// Encolar para Windows (si usas sync por JSON)
require_once dirname(__DIR__,2).'/src/helpers/sync.php';
sync_exec(
  "INSERT INTO equipos (codigo,nombre,categoria,estado,created_at) VALUES (?,?,?,?,NOW())",
  [$codigo,$nombre,$categoria,$estado]
);

header("Location: /equipos/create.php?ok=1");
exit;
