<?php
// Ruta base del proyecto (evita redefinición)
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

// Credenciales Ubuntu (local, 3306)
define('UB_HOST', '127.0.0.1');
define('UB_PORT', 3306);
define('UB_DB',   'nibarra_db');
define('UB_USER', 'win');
define('UB_PASS', '12345');

// Credenciales Windows (XAMPP, 3307)
define('WIN_HOST', '192.168.1.108');
define('WIN_PORT', 3307);
define('WIN_DB',   'nibarra_db');
define('WIN_USER', 'win');
define('WIN_PASS', '12345');
