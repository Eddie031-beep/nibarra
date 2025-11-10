<?php
declare(strict_types=1);

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
  if ($method==='GET' && $route==='/login') { (new AuthController)->loginView(); return; }
  if ($method==='GET' && $route==='/logout') { (new AuthController)->logout(); return; }

  // POST
  if ($method==='POST' && $route==='/login') { (new AuthController)->login(); return; }
  if ($method==='POST' && $route==='/equipos/store') { (new EquiposController)->store(); return; }
  if ($method==='POST' && preg_match('#^/equipos/delete/(\d+)$#',$route,$m)) { (new EquiposController)->destroy((int)$m[1]); return; }
  if ($method==='POST' && preg_match('#^/equipos/update/(\d+)$#',$route,$m)) { (new EquiposController)->update((int)$m[1]); return; }

  if ($method==='POST' && $route==='/mantenimiento/mover') { (new MantenimientoController)->mover(); return; }
  if ($method==='POST' && $route==='/mantenimiento/tareaToggle') { (new MantenimientoController)->tareaToggle(); return; }
  if ($method==='POST' && $route==='/mantenimiento/tareaNueva') { (new MantenimientoController)->tareaNueva(); return; }

  if ($method==='POST' && $route==='/calendario/store') { (new CalendarioController)->store(); return; }
  if ($method==='POST' && preg_match('#^/calendario/delete/(\d+)$#',$route,$m)) { (new CalendarioController)->destroy((int)$m[1]); return; }

  // 404 simple
  http_response_code(404);
  include dirname(__DIR__).'/views/layouts/header.php';
  echo '<section class="card" style="padding:16px"><h2>404</h2><p>Ruta no encontrada: <code>'.htmlspecialchars($route).'</code></p></section>';
  include dirname(__DIR__).'/views/layouts/footer.php';
}

dispatch($method, $route);
