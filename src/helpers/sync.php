<?php
/**
 * Sincronización hacia Windows: intenta ejecutar SQL en Windows;
 * si falla, encola un .json en /sync/pending para reintentar luego.
 */
require_once __DIR__ . '/db.php';

function sync_pending_dir(): string {
    return dirname(__DIR__, 2) . '/sync/pending';
}

/**
 * Ejecuta SQL en Windows; en caso de fallo, encola.
 * @param string $sql    Consulta parametrizada
 * @param array  $params Parámetros en el mismo orden que el SQL
 * @return array ['ok'=>bool, 'mode'=>'applied'|'queued', 'error'=>string|null]
 */
function sync_exec(string $sql, array $params): array {
    // 1) Intentar contra Windows
    try {
        $pdoWin = db_windows();
        $stmt   = $pdoWin->prepare($sql);
        $stmt->execute($params);
        return ['ok'=>true, 'mode'=>'applied', 'error'=>null];
    } catch (Throwable $e) {
        // 2) Si falla, encola en /sync/pending
        try {
            $dir = sync_pending_dir();
            if (!is_dir($dir)) {
                @mkdir($dir, 0775, true);
            }
            $payload = [
                'sql'    => $sql,
                'params' => $params,
                'ts'     => date('c'),
            ];
            $fname = $dir . '/' . uniqid('sync_', true) . '.json';
            file_put_contents($fname, json_encode($payload, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
            // asegurar permisos para que cron/php-fpm lo puedan tocar
            @chmod($fname, 0664);
            return ['ok'=>false, 'mode'=>'queued', 'error'=>$e->getMessage()];
        } catch (Throwable $e2) {
            return ['ok'=>false, 'mode'=>'error', 'error'=>$e->getMessage() . ' | enqueue: ' . $e2->getMessage()];
        }
    }
}
