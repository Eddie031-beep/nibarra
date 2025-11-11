<?php
/**
 * DEBUG SCRIPT - Guardar como: public/debug-replica.php
 * Acceder: http://192.168.1.142/nibarra/debug-replica.php
 */

// Cargar configuración
require_once dirname(__DIR__).'/config/env.php';

// Configuración de réplica
$cfg = ENV_DB['replica'];
$host = $cfg['host'];
$port = $cfg['port'];
$user = $cfg['user'];
$pass = $cfg['pass'];
$db   = $cfg['db'];

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Réplica - Nibarra</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            background: #0a0e1a;
            color: #e0e0e0;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        h1 {
            color: #00ff88;
            border-bottom: 2px solid #00ff88;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        h2 {
            color: #00ccff;
            margin: 30px 0 15px 0;
            font-size: 1.2rem;
        }
        .test {
            background: #1a1f2e;
            border-left: 4px solid #00ccff;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .success {
            border-left-color: #00ff88;
            background: #0a2818;
        }
        .error {
            border-left-color: #ff4444;
            background: #2a0808;
        }
        .warning {
            border-left-color: #ffaa00;
            background: #2a1a00;
        }
        .icon {
            font-size: 1.5rem;
            margin-right: 10px;
        }
        pre {
            background: #000;
            color: #0f0;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            margin: 10px 0;
            border: 1px solid #333;
        }
        .info {
            background: #1a1f2e;
            padding: 10px 15px;
            border-radius: 4px;
            margin: 10px 0;
            font-size: 0.9rem;
        }
        .info strong {
            color: #00ccff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            background: #1a1f2e;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #2a2f3e;
        }
        th {
            background: #0a0e1a;
            color: #00ccff;
            font-weight: bold;
        }
        .cmd {
            background: #000;
            color: #ffaa00;
            padding: 8px 12px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            display: inline-block;
            margin: 5px 0;
        }
        .timestamp {
            color: #888;
            font-size: 0.85rem;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 DEBUG - Diagnóstico Completo de Réplica</h1>
        <div class="timestamp">⏰ <?= date('Y-m-d H:i:s') ?></div>

        <h2>📋 Configuración Cargada</h2>
        <div class="info">
            <table>
                <tr><th>Parámetro</th><th>Valor</th></tr>
                <tr><td><strong>Host</strong></td><td><?= htmlspecialchars($host) ?></td></tr>
                <tr><td><strong>Port</strong></td><td><?= htmlspecialchars($port) ?></td></tr>
                <tr><td><strong>Usuario</strong></td><td><?= htmlspecialchars($user) ?></td></tr>
                <tr><td><strong>Contraseña</strong></td><td><?= str_repeat('*', strlen($pass)) ?> (<?= strlen($pass) ?> caracteres)</td></tr>
                <tr><td><strong>Base de Datos</strong></td><td><?= htmlspecialchars($db) ?></td></tr>
                <tr><td><strong>Charset</strong></td><td><?= htmlspecialchars($cfg['charset']) ?></td></tr>
            </table>
        </div>

        <h2>🌐 Test 1: Resolución DNS</h2>
        <?php
        $ip = gethostbyname($host);
        if ($ip === $host && !filter_var($host, FILTER_VALIDATE_IP)) {
            echo '<div class="test warning">';
            echo '<span class="icon">⚠️</span>';
            echo "<strong>Advertencia:</strong> No se pudo resolver '$host' a una IP<br>";
            echo "Usando el nombre tal cual: $host";
            echo '</div>';
        } else {
            echo '<div class="test success">';
            echo '<span class="icon">✅</span>';
            echo "<strong>Host resuelto:</strong> $host → $ip";
            echo '</div>';
        }
        ?>

        <h2>🔌 Test 2: Conectividad de Red (Socket)</h2>
        <?php
        $start = microtime(true);
        $fp = @fsockopen($host, $port, $errno, $errstr, 10);
        $time = round((microtime(true) - $start) * 1000, 2);
        
        if ($fp) {
            fclose($fp);
            echo '<div class="test success">';
            echo '<span class="icon">✅</span>';
            echo "<strong>Puerto $port está ABIERTO y accesible</strong><br>";
            echo "Tiempo de respuesta: {$time}ms";
            echo '</div>';
            $socketOk = true;
        } else {
            echo '<div class="test error">';
            echo '<span class="icon">❌</span>';
            echo "<strong>NO se puede conectar al puerto $port</strong><br>";
            echo "Error #$errno: $errstr<br>";
            echo "Tiempo de timeout: {$time}ms";
            echo '</div>';
            $socketOk = false;
            
            echo '<div class="info">';
            echo '<strong>💡 Posibles causas:</strong><br>';
            echo '1. MySQL no está corriendo en Windows<br>';
            echo '2. MySQL está usando otro puerto (no 3307)<br>';
            echo '3. Firewall de Windows está bloqueando el puerto<br>';
            echo '4. La IP ' . htmlspecialchars($host) . ' es incorrecta<br><br>';
            echo '<strong>Verifica en Windows:</strong><br>';
            echo '<span class="cmd">netstat -ano | findstr :' . htmlspecialchars($port) . '</span>';
            echo '</div>';
        }
        ?>

        <?php if ($socketOk): ?>
        <h2>🔐 Test 3: Autenticación MySQL</h2>
        <?php
        try {
            $dsn = "mysql:host={$host};port={$port};charset={$cfg['charset']}";
            echo '<div class="info">';
            echo '<strong>DSN:</strong> ' . htmlspecialchars($dsn);
            echo '</div>';
            
            $start = microtime(true);
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 10,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
            $time = round((microtime(true) - $start) * 1000, 2);
            
            echo '<div class="test success">';
            echo '<span class="icon">✅</span>';
            echo "<strong>Autenticación EXITOSA</strong><br>";
            echo "Conexión establecida en {$time}ms";
            echo '</div>';
            $authOk = true;
            
        } catch (PDOException $e) {
            echo '<div class="test error">';
            echo '<span class="icon">❌</span>';
            echo "<strong>Error de autenticación</strong><br>";
            echo "Código: " . htmlspecialchars($e->getCode()) . "<br>";
            echo "Mensaje: " . htmlspecialchars($e->getMessage());
            echo '</div>';
            $authOk = false;
            
            echo '<div class="info">';
            echo '<strong>💡 Solución:</strong><br>';
            echo 'En MySQL de Windows ejecuta:<br>';
            echo '<pre>CREATE USER IF NOT EXISTS \'' . htmlspecialchars($user) . '\'@\'%\' IDENTIFIED BY \'' . htmlspecialchars($pass) . '\';
GRANT ALL PRIVILEGES ON *.* TO \'' . htmlspecialchars($user) . '\'@\'%\' WITH GRANT OPTION;
FLUSH PRIVILEGES;</pre>';
            echo '</div>';
        }
        ?>

        <?php if ($authOk): ?>
        <h2>📊 Test 4: Información del Servidor</h2>
        <?php
        try {
            $version = $pdo->query("SELECT VERSION()")->fetchColumn();
            $currentUser = $pdo->query("SELECT CURRENT_USER()")->fetchColumn();
            $serverTime = $pdo->query("SELECT NOW()")->fetchColumn();
            $uptime = $pdo->query("SHOW GLOBAL STATUS LIKE 'Uptime'")->fetch(PDO::FETCH_ASSOC);
            
            echo '<div class="test success">';
            echo '<span class="icon">✅</span>';
            echo '<strong>Información del servidor MySQL:</strong><br>';
            echo '<table>';
            echo '<tr><th>Parámetro</th><th>Valor</th></tr>';
            echo '<tr><td>Versión</td><td>' . htmlspecialchars($version) . '</td></tr>';
            echo '<tr><td>Usuario actual</td><td>' . htmlspecialchars($currentUser) . '</td></tr>';
            echo '<tr><td>Hora del servidor</td><td>' . htmlspecialchars($serverTime) . '</td></tr>';
            echo '<tr><td>Uptime</td><td>' . (int)($uptime['Value'] / 60) . ' minutos</td></tr>';
            echo '</table>';
            echo '</div>';
            
        } catch (PDOException $e) {
            echo '<div class="test error">';
            echo '<span class="icon">❌</span>';
            echo "Error: " . htmlspecialchars($e->getMessage());
            echo '</div>';
        }
        ?>

        <h2>🗄️ Test 5: Acceso a Base de Datos</h2>
        <?php
        try {
            $pdo->exec("USE `{$db}`");
            echo '<div class="test success">';
            echo '<span class="icon">✅</span>';
            echo "<strong>Base de datos '{$db}' ACCESIBLE</strong>";
            echo '</div>';
            
            // Listar tablas
            $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            if (!empty($tables)) {
                echo '<div class="info">';
                echo '<strong>Tablas encontradas (' . count($tables) . '):</strong><br>';
                echo '<table>';
                echo '<tr><th>#</th><th>Tabla</th><th>Registros</th></tr>';
                foreach ($tables as $i => $table) {
                    $count = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
                    echo '<tr><td>' . ($i + 1) . '</td><td>' . htmlspecialchars($table) . '</td><td>' . number_format($count) . '</td></tr>';
                }
                echo '</table>';
                echo '</div>';
            }
            
        } catch (PDOException $e) {
            echo '<div class="test error">';
            echo '<span class="icon">❌</span>';
            echo "<strong>No se puede acceder a la base de datos '{$db}'</strong><br>";
            echo "Error: " . htmlspecialchars($e->getMessage());
            echo '</div>';
            
            echo '<div class="info">';
            echo '<strong>💡 La base de datos no existe. Créala:</strong><br>';
            echo '<pre>CREATE DATABASE IF NOT EXISTS `' . htmlspecialchars($db) . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;</pre>';
            echo '</div>';
        }
        ?>

        <h2>⚡ Test 6: Prueba de Escritura/Lectura</h2>
        <?php
        try {
            // Crear tabla temporal
            $pdo->exec("CREATE TABLE IF NOT EXISTS _test_connection (
                id INT AUTO_INCREMENT PRIMARY KEY,
                test_data VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
            
            // Insertar dato
            $testValue = 'Test_' . time();
            $stmt = $pdo->prepare("INSERT INTO _test_connection (test_data) VALUES (?)");
            $stmt->execute([$testValue]);
            
            // Leer dato
            $result = $pdo->query("SELECT test_data, created_at FROM _test_connection ORDER BY id DESC LIMIT 1")->fetch();
            
            // Limpiar
            $pdo->exec("DROP TABLE _test_connection");
            
            if ($result && $result['test_data'] === $testValue) {
                echo '<div class="test success">';
                echo '<span class="icon">✅</span>';
                echo '<strong>Prueba de escritura/lectura EXITOSA</strong><br>';
                echo "Dato escrito: {$testValue}<br>";
                echo "Dato leído: {$result['test_data']}<br>";
                echo "Timestamp: {$result['created_at']}";
                echo '</div>';
            }
            
        } catch (PDOException $e) {
            echo '<div class="test error">';
            echo '<span class="icon">❌</span>';
            echo '<strong>Error en prueba de escritura/lectura</strong><br>';
            echo "Error: " . htmlspecialchars($e->getMessage());
            echo '</div>';
        }
        ?>
        <?php endif; ?>
        <?php endif; ?>

        <h2>📝 Resumen Final</h2>
        <?php
        $allGood = isset($socketOk) && $socketOk && isset($authOk) && $authOk;
        if ($allGood) {
            echo '<div class="test success">';
            echo '<span class="icon">🎉</span>';
            echo '<strong>¡TODO ESTÁ FUNCIONANDO CORRECTAMENTE!</strong><br>';
            echo 'La réplica en Windows está completamente operativa.';
            echo '</div>';
        } else {
            echo '<div class="test error">';
            echo '<span class="icon">❌</span>';
            echo '<strong>La réplica NO está funcionando</strong><br>';
            echo 'Revisa los errores arriba y sigue las recomendaciones.';
            echo '</div>';
        }
        ?>

        <div style="margin-top: 30px; padding: 15px; background: #1a1f2e; border-radius: 4px;">
            <strong>🔗 Enlaces útiles:</strong><br>
            <a href="<?= ENV_APP['BASE_URL'] ?>/health/replica" style="color: #00ccff;">Ver diagnóstico principal</a> | 
            <a href="<?= ENV_APP['BASE_URL'] ?>/equipos" style="color: #00ccff;">Volver a Equipos</a> | 
            <a href="javascript:location.reload()" style="color: #00ff88;">🔄 Actualizar</a>
        </div>

        <div class="timestamp">
            Script ejecutado en <?= round((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000, 2) ?>ms
        </div>
    </div>
</body>
</html>