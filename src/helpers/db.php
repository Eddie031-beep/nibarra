<?php
/**
 * Helper de conexiones PDO para el proyecto nibarra
 */
require_once __DIR__ . '/../../config/config.php';

/**
 * Crea una conexión PDO MySQL.
 */
function pdo_mysql_connect(string $host, int $port, string $db, string $user, string $pass): PDO {
    $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    return new PDO($dsn, $user, $pass, $opt);
}

/**
 * Conexión a la BD local de Ubuntu (3306).
 */
function db_ubuntu(): PDO {
    return pdo_mysql_connect(UB_HOST, UB_PORT, UB_DB, UB_USER, UB_PASS);
}

/**
 * Conexión a la BD de Windows (XAMPP, 3307).
 */
function db_windows(): PDO {
    return pdo_mysql_connect(WIN_HOST, WIN_PORT, WIN_DB, WIN_USER, WIN_PASS);
}

/**
 * Retorna par de conexiones (útil para sincronización manual).
 */
function db_pair(): array {
    return [
        'ubuntu'  => db_ubuntu(),
        'windows' => db_windows(),
    ];
}

