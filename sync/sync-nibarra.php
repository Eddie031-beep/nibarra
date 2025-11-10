<?php
/**
 * Procesador de Cola de Sincronización
 * Ejecutar manualmente: php sync/sync-nibarra.php
 * O configurar en cron: */5 * * * * * php /var/www/nibarra/sync/sync-nibarra.php
 */

require_once __DIR__ . '/../src/helpers/sync.php';

echo "\n";
echo "═══════════════════════════════════════════\n";
echo "  🔄 PROCESADOR DE SINCRONIZACIÓN NIBARRA\n";
echo "═══════════════════════════════════════════\n";
echo "Hora de ejecución: " . date('Y-m-d H:i:s') . "\n\n";

// 1. Verificar conexión a Windows
echo "📡 Verificando conexión al servidor Windows...\n";
$connectionCheck = sync_check_connection();

if (!$connectionCheck['connected']) {
    echo "❌ ERROR: No se puede conectar al servidor Windows\n";
    echo "   Detalles: {$connectionCheck['error']}\n";
    echo "   Host: " . WIN_HOST . ":" . WIN_PORT . "\n\n";
    echo "⚠️  Las operaciones permanecerán en cola hasta que se restablezca la conexión.\n";
    exit(1);
}

echo "✅ Conexión exitosa\n";
echo "   Servidor: " . WIN_HOST . ":" . WIN_PORT . "\n";
echo "   Hora del servidor: {$connectionCheck['server_time']}\n\n";

// 2. Obtener estadísticas de la cola
echo "📊 Estadísticas de la cola:\n";
$stats = sync_queue_stats();
echo "   Total en cola: {$stats['total']} operaciones\n";
echo "   Tamaño total: {$stats['total_size_kb']} KB\n";

if ($stats['total'] == 0) {
    echo "   ✅ Cola vacía - No hay operaciones pendientes\n\n";
    exit(0);
}

echo "   Más antigua: {$stats['oldest']}\n";
echo "   Más reciente: {$stats['newest']}\n\n";

// 3. Procesar la cola
echo "⚙️  Procesando cola de sincronización...\n";
$result = sync_process_queue(100); // Procesar hasta 100 operaciones

echo "\n";
echo "═══════════════════════════════════════════\n";
echo "  📋 RESULTADO DEL PROCESAMIENTO\n";
echo "═══════════════════════════════════════════\n";
echo "Procesadas: {$result['processed']}\n";
echo "Exitosas:   {$result['successful']} ✅\n";
echo "Fallidas:   {$result['failed']} ❌\n";
echo "\n";

if ($result['successful'] > 0) {
    echo "✅ {$result['successful']} operaciones sincronizadas correctamente\n";
}

if ($result['failed'] > 0) {
    echo "⚠️  {$result['failed']} operaciones fallaron y se reintentarán más tarde\n";
}

// 4. Estadísticas finales
$finalStats = sync_queue_stats();
if ($finalStats['total'] > 0) {
    echo "\n⏳ Quedan {$finalStats['total']} operaciones pendientes\n";
} else {
    echo "\n🎉 Todas las operaciones han sido sincronizadas\n";
}

echo "\n═══════════════════════════════════════════\n";
echo "  Fin del procesamiento\n";
echo "═══════════════════════════════════════════\n\n";

exit(0);