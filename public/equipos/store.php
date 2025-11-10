<?php
// public/equipos/store.php
require_once dirname(__DIR__,2).'/src/helpers/db.php';
require_once dirname(__DIR__,2).'/src/helpers/sync.php';

// Validar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /nibarra/public/equipos');
    exit;
}

// Sanitizar y validar datos
$codigo      = strtoupper(trim($_POST['codigo'] ?? ''));
$nombre      = trim($_POST['nombre'] ?? '');
$categoria   = trim($_POST['categoria'] ?? '');
$marca       = trim($_POST['marca'] ?? '');
$modelo      = trim($_POST['modelo'] ?? '');
$nro_serie   = trim($_POST['nro_serie'] ?? '');
$ubicacion   = trim($_POST['ubicacion'] ?? '');
$fecha_compra = !empty($_POST['fecha_compra']) ? $_POST['fecha_compra'] : null;
$proveedor   = trim($_POST['proveedor'] ?? '');
$costo       = !empty($_POST['costo']) ? floatval($_POST['costo']) : null;
$estado      = $_POST['estado'] ?? 'operativo';

// Validaciones
$errors = [];

if (empty($codigo)) {
    $errors[] = 'El código es obligatorio';
} elseif (!preg_match('/^[A-Z]{2}-[0-9]{3,}$/', $codigo)) {
    $errors[] = 'Formato de código inválido (use XX-###)';
}

if (empty($nombre)) {
    $errors[] = 'El nombre es obligatorio';
}

$allowed_estados = ['operativo','fuera_de_servicio','baja'];
if (!in_array($estado, $allowed_estados, true)) {
    $errors[] = 'Estado no válido';
}

if (!empty($errors)) {
    header('Location: /nibarra/public/equipos/crear?error=' . urlencode(implode(', ', $errors)));
    exit;
}

try {
    // Verificar si el código ya existe
    $db = db_ubuntu();
    $check = $db->prepare("SELECT id FROM equipos WHERE codigo = ?");
    $check->execute([$codigo]);
    
    if ($check->fetch()) {
        header('Location: /nibarra/public/equipos/crear?error=' . urlencode('El código ya existe'));
        exit;
    }
    
    // Insert en Ubuntu (BD local)
    $sql = "INSERT INTO equipos (
        codigo, nombre, categoria, marca, modelo, nro_serie, 
        ubicacion, fecha_compra, proveedor, costo, estado, created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        $codigo, $nombre, $categoria, $marca, $modelo, $nro_serie,
        $ubicacion, $fecha_compra, $proveedor, $costo, $estado
    ]);
    
    $equipoId = $db->lastInsertId();
    
    // Sincronizar con Windows (replicación)
    $syncResult = sync_exec($sql, [
        $codigo, $nombre, $categoria, $marca, $modelo, $nro_serie,
        $ubicacion, $fecha_compra, $proveedor, $costo, $estado
    ]);
    
    // Log de auditoría
    try {
        $logSql = "INSERT INTO audit_logs (usuario_id, tabla, registro_id, accion, detalle, created_at) 
                   VALUES (1, 'equipos', ?, 'insert', ?, NOW())";
        $logStmt = $db->prepare($logSql);
        $logStmt->execute([
            $equipoId,
            json_encode([
                'codigo' => $codigo,
                'nombre' => $nombre,
                'sync_mode' => $syncResult['mode']
            ])
        ]);
    } catch (Exception $e) {
        // Log opcional, no detener el proceso
        error_log("Error en audit_logs: " . $e->getMessage());
    }
    
    // Mensaje de éxito
    $successMsg = 'Equipo creado correctamente';
    if ($syncResult['mode'] === 'queued') {
        $successMsg .= ' (sincronización pendiente con servidor remoto)';
    }
    
    header('Location: /nibarra/public/equipos?success=' . urlencode($successMsg));
    exit;
    
} catch (PDOException $e) {
    error_log("Error al crear equipo: " . $e->getMessage());
    header('Location: /nibarra/public/equipos/crear?error=' . urlencode('Error en la base de datos: ' . $e->getMessage()));
    exit;
}