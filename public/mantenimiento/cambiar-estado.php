<?php
// public/mantenimiento/cambiar-estado.php
header('Content-Type: application/json');
require_once dirname(__DIR__,2).'/src/helpers/db.php';
require_once dirname(__DIR__,2).'/src/helpers/sync.php';

// Leer JSON del body
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id']) || !isset($input['estado'])) {
    echo json_encode(['ok' => false, 'error' => 'Datos incompletos']);
    exit;
}

$id = intval($input['id']);
$nuevoEstado = $input['estado'];

$estadosValidos = ['pendiente', 'en_progreso', 'completado', 'cancelado'];
if (!in_array($nuevoEstado, $estadosValidos, true)) {
    echo json_encode(['ok' => false, 'error' => 'Estado no válido']);
    exit;
}

try {
    $db = db_ubuntu();
    
    // Actualizar estado
    $sql = "UPDATE mantenimientos SET estado = ?, updated_at = NOW() WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$nuevoEstado, $id]);
    
    // Si se completa, registrar fecha de cierre
    if ($nuevoEstado === 'completado') {
        $sqlClose = "UPDATE mantenimientos SET fecha_cierre = NOW() WHERE id = ?";
        $db->prepare($sqlClose)->execute([$id]);
        
        sync_exec($sqlClose, [$id]);
    }
    
    // Si se inicia, registrar fecha de inicio
    if ($nuevoEstado === 'en_progreso') {
        $sqlStart = "UPDATE mantenimientos SET fecha_inicio = NOW() WHERE id = ? AND fecha_inicio IS NULL";
        $db->prepare($sqlStart)->execute([$id]);
        
        sync_exec($sqlStart, [$id]);
    }
    
    // Sincronizar con Windows
    sync_exec($sql, [$nuevoEstado, $id]);
    
    // Log de auditoría
    try {
        $logSql = "INSERT INTO audit_logs (usuario_id, tabla, registro_id, accion, detalle, created_at) 
                   VALUES (1, 'mantenimientos', ?, 'update', ?, NOW())";
        $logStmt = $db->prepare($logSql);
        $logStmt->execute([
            $id,
            json_encode(['cambio_estado' => $nuevoEstado])
        ]);
    } catch (Exception $e) {
        error_log("Error en audit_logs: " . $e->getMessage());
    }
    
    echo json_encode(['ok' => true, 'mensaje' => 'Estado actualizado']);
    
} catch (PDOException $e) {
    error_log("Error al cambiar estado: " . $e->getMessage());
    echo json_encode(['ok' => false, 'error' => 'Error en la base de datos']);
}