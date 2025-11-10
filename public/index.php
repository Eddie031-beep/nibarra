<?php
// public/index.php
require_once dirname(__DIR__) . '/config/app.php';
require_once BASE_PATH . '/src/helpers/db.php';

// Rutas → vista a renderizar
$routes = [
    ''                => 'calendario/index',     // home
    'login'           => 'auth/login',
    'register'        => 'auth/register',
    'equipos'         => 'equipos/index',
    'equipos/crear'   => 'equipos/create',
    'mantenimiento'   => 'mantenimiento/index',
    'calendario'      => 'calendario/index',
];

$p = $_GET['p'] ?? '';
$view = $routes[$p] ?? null;

http_response_code($view ? 200 : 404);

// Layout
require VIEWS_PATH . '/layout/header.php';

if ($view) {
    $file = VIEWS_PATH . '/' . $view . '.php';
    if (is_file($file)) {
        require $file;
    } else {
        echo "<h2>Vista no encontrada: {$view}</h2>";
    }
} else {
    echo '<h2>404 - Página no encontrada</h2>';
    echo '<p>La ruta solicitada no existe.</p>';
}

require VIEWS_PATH . '/layout/footer.php';
