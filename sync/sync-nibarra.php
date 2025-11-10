<?php
/**
 * Recorre /sync/pending/*.json y reintenta ejecutar en Windows.
 * Si se aplica con éxito, borra el archivo.
 */
require_once __DIR__ . '/../src/helpers/sync.php';
require_once __DIR__ . '/../src/helpers/db.php';

$pendingDir = __DIR__ . '/pending';
if (!is_dir($pendingDir)) {
    echo "No hay carpeta pending (nada que hacer)\n";
    exit(0);
}

$files = glob($pendingDir . '/*.json');
if (!$files) {
    echo "Cola vacía.\n";
    exit(0);
}

$ok = 0; $fail = 0;
foreach ($files as $f) {
    $p = json_decode(file_get_contents($f), true);
    if (!is_array($p) || empty($p['sql'])) {
        echo "Archivo inválido: $f\n";
        @unlink($f);
        continue;
    }
    try {
        $pdoWin = db_windows();
        $stmt   = $pdoWin->prepare($p['sql']);
        $stmt->execute($p['params'] ?? []);
        @unlink($f);
        $ok++;
    } catch (Throwable $e) {
        $fail++;
        error_log('[SYNC-RETRY] falló ' . basename($f) . ': ' . $e->getMessage());
    }
}

echo "Procesados OK=$ok, FAIL=$fail\n";
