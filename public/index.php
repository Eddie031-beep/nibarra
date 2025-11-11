<?php
declare(strict_types=1);

// Activar errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');

try {
  require_once dirname(__DIR__).'/config/env.php';
  require_once dirname(__DIR__).'/app.php';
  require_once dirname(__DIR__).'/core/DB.php';
  require_once dirname(__DIR__).'/core/Auth.php';
  require_once dirname(__DIR__).'/core/Helpers.php';

  // Controllers
  require_once dirname(__DIR__).'/controllers/HealthController.php';
  require_once dirname(__DIR__).'/controllers/AuthController.php';
  require_once dirname(__DIR__).'/controllers/EquiposController.php';
  require_once dirname(__DIR__).'/controllers/CalendarioController.php';
  require_once dirname(__DIR__).'/controllers/MantenimientoController.php';
  
  // Verificar si existe el archivo antes de incluirlo
  $usuariosControllerPath = dirname(__DIR__).'/controllers/UsuariosController.php';
  if (file_exists($usuariosControllerPath)) {
    require_once $usuariosControllerPath;
  }

  // Normalizar ruta actual quitando el prefijo de BASE_URL (/nibarra)
  $basePath = parse_url(ENV_APP['BASE_URL'], PHP_URL_PATH) ?: '';
  $reqPath  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

  // Si BASE_URL es /nibarra, y REQUEST_URI es /nibarra/equipos => $route = /equipos
  $route = $reqPath;
  if ($basePath && str_starts_with($route, $basePath)) {
    $route = substr($route, strlen($basePath));
  }
  $route = $route === '' ? '/' : $route;

  // Para que header.php pueda saber qué está activo
  $GLOBALS['route'] = $route;

  // Métodos
  $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

  // Helper despachador
  function dispatch(string $method, string $route) {
    // GET
    if ($method==='GET' && $route==='/health/replica') { (new HealthController)->replica(); return; }
    if ($method==='GET' && ($route==='/' || $route==='/equipos')) { (new EquiposController)->index(); return; }
    if ($method==='GET' && $route==='/calendario') { (new CalendarioController)->index(); return; }
    if ($method==='GET' && $route==='/mantenimiento') { (new MantenimientoController)->index(); return; }
    
    // Solo cargar usuarios si la clase existe
    if ($method==='GET' && $route==='/usuarios') { 
      if (class_exists('UsuariosController')) {
        (new UsuariosController)->index(); 
      } else {
        http_response_code(404);
        echo "Módulo de usuarios no disponible";
      }
      return; 
    }
    
    if ($method==='GET' && $route==='/login') { (new AuthController)->loginView(); return; }
    if ($method==='GET' && $route==='/register') { (new AuthController)->registerView(); return; }
    if ($method==='GET' && $route==='/logout') { (new AuthController)->logout(); return; }

    // POST
    if ($method==='POST' && $route==='/login') { (new AuthController)->login(); return; }
    if ($method==='POST' && $route==='/register') { (new AuthController)->register(); return; }
    if ($method==='POST' && $route==='/equipos/store') { (new EquiposController)->store(); return; }
    if ($method==='POST' && preg_match('#^/equipos/delete/(\d+)$#',$route,$m)) { (new EquiposController)->destroy((int)$m[1]); return; }
    if ($method==='POST' && preg_match('#^/equipos/update/(\d+)$#',$route,$m)) { (new EquiposController)->update((int)$m[1]); return; }

    if ($method==='POST' && $route==='/mantenimiento/store') { (new MantenimientoController)->store(); return; }
    if ($method==='POST' && $route==='/mantenimiento/mover') { (new MantenimientoController)->mover(); return; }
    if ($method==='POST' && $route==='/mantenimiento/tareaToggle') { (new MantenimientoController)->tareaToggle(); return; }
    if ($method==='POST' && $route==='/mantenimiento/tareaNueva') { (new MantenimientoController)->tareaNueva(); return; }
    if ($method==='POST' && preg_match('#^/mantenimiento/delete/(\d+)$#',$route,$m)) { (new MantenimientoController)->destroy((int)$m[1]); return; }

    if ($method==='POST' && $route==='/calendario/store') { (new CalendarioController)->store(); return; }
    if ($method==='POST' && preg_match('#^/calendario/delete/(\d+)$#',$route,$m)) { (new CalendarioController)->destroy((int)$m[1]); return; }

    // Usuarios (solo si existe la clase)
    if (class_exists('UsuariosController')) {
      if ($method==='POST' && preg_match('#^/usuarios/update/(\d+)$#',$route,$m)) { (new UsuariosController)->update((int)$m[1]); return; }
      if ($method==='POST' && preg_match('#^/usuarios/delete/(\d+)$#',$route,$m)) { (new UsuariosController)->destroy((int)$m[1]); return; }
    }

    // 404 simple
    http_response_code(404);
    include dirname(__DIR__).'/views/layouts/header.php';
    echo '<section class="card" style="padding:16px"><h2>404</h2><p>Ruta no encontrada: <code>'.htmlspecialchars($route).'</code></p></section>';
    include dirname(__DIR__).'/views/layouts/footer.php';
  }

  dispatch($method, $route);

} catch (Throwable $e) {
  // Mostrar error detallado
  http_response_code(500);
  ?>
  <!DOCTYPE html>
  <html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Error - Nibarra</title>
    <style>
      body {
        font-family: monospace;
        background: #1a1a1a;
        color: #fff;
        padding: 20px;
      }
      .error-box {
        background: #2a2a2a;
        border: 2px solid #f44336;
        border-radius: 8px;
        padding: 20px;
        max-width: 1000px;
        margin: 0 auto;
      }
      h1 {
        color: #f44336;
        margin-top: 0;
      }
      .trace {
        background: #1a1a1a;
        padding: 15px;
        border-radius: 4px;
        overflow-x: auto;
        margin-top: 10px;
      }
      pre {
        margin: 0;
        white-space: pre-wrap;
      }
      .file {
        color: #4fc3f7;
      }
      .line {
        color: #ffeb3b;
      }
    </style>
  </head>
  <body>
    <div class="error-box">
      <h1>⚠️ Error 500 - Error del Servidor</h1>
      <p><strong>Mensaje:</strong> <?= htmlspecialchars($e->getMessage()) ?></p>
      <p><strong>Archivo:</strong> <span class="file"><?= htmlspecialchars($e->getFile()) ?></span></p>
      <p><strong>Línea:</strong> <span class="line"><?= htmlspecialchars($e->getLine()) ?></span></p>
      
      <h3>Stack Trace:</h3>
      <div class="trace">
        <pre><?= htmlspecialchars($e->getTraceAsString()) ?></pre>
      </div>

      <h3>Posibles soluciones:</h3>
      <ul>
        <li>Verifica que todos los archivos de controladores existan en <code>controllers/</code></li>
        <li>Asegúrate que el archivo <code>controllers/UsuariosController.php</code> existe</li>
        <li>Verifica que la carpeta <code>views/usuarios/</code> existe con el archivo <code>index.php</code></li>
        <li>Revisa los permisos de los archivos</li>
        <li>Verifica la configuración de la base de datos</li>
      </ul>
    </div>
  </body>
  </html>
  <?php
  exit;
}