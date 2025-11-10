<?php
/**
 * Sistema de Sincronización Bidireccional
 * Ubuntu (local) ⇄ Windows (remoto)
 * 
 * Características:
 * - Intenta sincronizar en tiempo real
 * - Si falla, encola para reintento posterior
 * - Soporte para transacciones
 * - Log detallado de operaciones
 */

require_once __DIR__ . '/db.php';

/**
 * Directorio donde se guardan las operaciones pendientes
 */
function sync_pending_dir(): string {
    $dir = dirname(__DIR__, 2) . '/sync/pending';
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    return $dir;
}

/**
 * Directorio para logs de sincronización
 */
function sync_log_dir(): string {
    $dir = dirname(__DIR__, 2) . '/sync/logs';
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    return $dir;
}

/**
 * Registrar en log de sincronización
 */
function sync_log(string $message, string $level = 'INFO'): void {
    $logFile = sync_log_dir() . '/sync_' . date('Y-m-d') . '.log';
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] [$level] $message\n";
    @file_put_contents($logFile, $logEntry, FILE_APPEND);
}

/**
 * Ejecuta SQL en Windows con manejo inteligente de errores
 * 
 * @param string $sql    Consulta SQL parametrizada
 * @param array  $params Parámetros para la consulta
 * @param bool   $useTransaction Usar transacción (para múltiples operaciones)
 * @return array Resultado con estado y detalles
 */
function sync_exec(string $sql, array $params = [], bool $useTransaction = false): array {
    $startTime = microtime(true);
    
    // 1) Intentar ejecutar en Windows (servidor remoto)
    try {
        $pdoWin = db_windows();
        
        if ($useTransaction) {
            $pdoWin->beginTransaction();
        }
        
        $stmt = $pdoWin->prepare($sql);
        $result = $stmt->execute($params);
        
        if ($useTransaction) {
            $pdoWin->commit();
        }
        
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        sync_log("✓ Sincronización exitosa en {$duration}ms | SQL: " . substr($sql, 0, 100), 'SUCCESS');
        
        return [
            'ok' => true,
            'mode' => 'applied',
            'error' => null,
            'duration_ms' => $duration,
            'affected_rows' => $stmt->rowCount()
        ];
        
    } catch (PDOException $e) {
        if ($useTransaction && isset($pdoWin)) {
            $pdoWin->rollBack();
        }
        
        $errorMsg = $e->getMessage();
        sync_log("✗ Error en sincronización: $errorMsg | SQL: " . substr($sql, 0, 100), 'ERROR');
        
        // 2) Si falla, encolar para reintento
        try {
            $queueResult = sync_queue($sql, $params);
            
            if ($queueResult['ok']) {
                sync_log("→ Operación encolada: {$queueResult['file']}", 'WARNING');
                return [
                    'ok' => false,
                    'mode' => 'queued',
                    'error' => $errorMsg,
                    'queue_file' => $queueResult['file']
                ];
            } else {
                return [
                    'ok' => false,
                    'mode' => 'error',
                    'error' => "Sync failed: $errorMsg | Queue failed: {$queueResult['error']}"
                ];
            }
            
        } catch (Throwable $e2) {
            sync_log("✗ Error crítico al encolar: " . $e2->getMessage(), 'CRITICAL');
            return [
                'ok' => false,
                'mode' => 'error',
                'error' => "Sync: $errorMsg | Queue: " . $e2->getMessage()
            ];
        }
    }
}

/**
 * Encolar operación para reintento posterior
 */
function sync_queue(string $sql, array $params): array {
    try {
        $dir = sync_pending_dir();
        
        $payload = [
            'sql' => $sql,
            'params' => $params,
            'timestamp' => date('c'),
            'attempts' => 0,
            'created_by' => $_SERVER['REMOTE_ADDR'] ?? 'system'
        ];
        
        $filename = 'sync_' . date('Ymd_His') . '_' . uniqid() . '.json';
        $filepath = $dir . '/' . $filename;
        
        $written = file_put_contents(
            $filepath, 
            json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
        
        if ($written === false) {
            throw new Exception("No se pudo escribir archivo de cola");
        }
        
        @chmod($filepath, 0664);
        
        return [
            'ok' => true,
            'file' => $filename,
            'path' => $filepath
        ];
        
    } catch (Throwable $e) {
        return [
            'ok' => false,
            'error' => $e->getMessage()
        ];
    }
}

/**
 * Ejecutar múltiples operaciones en transacción
 * 
 * @param array $operations Array de ['sql' => string, 'params' => array]
 * @return array Resultado de la operación
 */
function sync_batch(array $operations): array {
    $startTime = microtime(true);
    
    try {
        $pdoWin = db_windows();
        $pdoWin->beginTransaction();
        
        $successCount = 0;
        
        foreach ($operations as $op) {
            $stmt = $pdoWin->prepare($op['sql']);
            $stmt->execute($op['params'] ?? []);
            $successCount++;
        }
        
        $pdoWin->commit();
        
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        sync_log("✓ Batch de $successCount operaciones en {$duration}ms", 'SUCCESS');
        
        return [
            'ok' => true,
            'mode' => 'applied',
            'operations' => $successCount,
            'duration_ms' => $duration
        ];
        
    } catch (PDOException $e) {
        if (isset($pdoWin)) {
            $pdoWin->rollBack();
        }
        
        sync_log("✗ Error en batch: " . $e->getMessage(), 'ERROR');
        
        // Encolar todas las operaciones
        $queuedCount = 0;
        foreach ($operations as $op) {
            $result = sync_queue($op['sql'], $op['params'] ?? []);
            if ($result['ok']) $queuedCount++;
        }
        
        return [
            'ok' => false,
            'mode' => 'queued',
            'error' => $e->getMessage(),
            'queued_operations' => $queuedCount
        ];
    }
}

/**
 * Verificar estado de la conexión a Windows
 */
function sync_check_connection(): array {
    try {
        $pdoWin = db_windows();
        $result = $pdoWin->query("SELECT 1 as test, NOW() as server_time")->fetch();
        
        return [
            'ok' => true,
            'connected' => true,
            'server_time' => $result['server_time']
        ];
        
    } catch (PDOException $e) {
        return [
            'ok' => false,
            'connected' => false,
            'error' => $e->getMessage()
        ];
    }
}

/**
 * Obtener estadísticas de la cola de sincronización
 */
function sync_queue_stats(): array {
    $dir = sync_pending_dir();
    $files = glob($dir . '/*.json');
    
    $stats = [
        'total' => count($files),
        'oldest' => null,
        'newest' => null,
        'total_size_kb' => 0
    ];
    
    if (!empty($files)) {
        $times = array_map('filemtime', $files);
        $stats['oldest'] = date('Y-m-d H:i:s', min($times));
        $stats['newest'] = date('Y-m-d H:i:s', max($times));
        
        foreach ($files as $file) {
            $stats['total_size_kb'] += filesize($file);
        }
        $stats['total_size_kb'] = round($stats['total_size_kb'] / 1024, 2);
    }
    
    return $stats;
}

/**
 * Procesar cola de sincronización (ejecutar manualmente o via cron)
 * 
 * @param int $limit Límite de archivos a procesar
 * @return array Resultado del procesamiento
 */
function sync_process_queue(int $limit = 50): array {
    $dir = sync_pending_dir();
    $files = glob($dir . '/*.json');
    
    if (empty($files)) {
        return [
            'ok' => true,
            'processed' => 0,
            'successful' => 0,
            'failed' => 0,
            'message' => 'Cola vacía'
        ];
    }
    
    // Ordenar por fecha (más antiguos primero)
    usort($files, function($a, $b) {
        return filemtime($a) - filemtime($b);
    });
    
    $files = array_slice($files, 0, $limit);
    
    $successful = 0;
    $failed = 0;
    
    foreach ($files as $file) {
        $payload = json_decode(file_get_contents($file), true);
        
        if (!is_array($payload) || empty($payload['sql'])) {
            @unlink($file);
            continue;
        }
        
        // Incrementar intentos
        $payload['attempts'] = ($payload['attempts'] ?? 0) + 1;
        
        // Si ya intentó más de 5 veces, mover a carpeta de fallidos
        if ($payload['attempts'] > 5) {
            $failedDir = dirname($dir) . '/failed';
            if (!is_dir($failedDir)) {
                @mkdir($failedDir, 0775, true);
            }
            @rename($file, $failedDir . '/' . basename($file));
            $failed++;
            sync_log("✗ Operación movida a fallidos después de 5 intentos: " . basename($file), 'ERROR');
            continue;
        }
        
        try {
            $pdoWin = db_windows();
            $stmt = $pdoWin->prepare($payload['sql']);
            $stmt->execute($payload['params'] ?? []);
            
            @unlink($file);
            $successful++;
            sync_log("✓ Reintento exitoso: " . basename($file), 'SUCCESS');
            
        } catch (PDOException $e) {
            // Actualizar archivo con nuevo intento
            file_put_contents($file, json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            $failed++;
            sync_log("✗ Reintento fallido (intento {$payload['attempts']}): " . basename($file) . " - " . $e->getMessage(), 'ERROR');
        }
    }
    
    return [
        'ok' => true,
        'processed' => count($files),
        'successful' => $successful,
        'failed' => $failed,
        'message' => "Procesados: $successful exitosos, $failed fallidos"
    ];
}