<?php
require_once dirname(__DIR__).'/app.php';
require_once BASE_PATH.'/core/Response.php';
require_once BASE_PATH.'/core/Auth.php';

$routes = [
  ['GET','/','EquiposController@index'],
  ['GET','/equipos','EquiposController@index'],
  ['POST','/equipos/store','EquiposController@store'],
  ['POST','/equipos/update/{id}','EquiposController@update'],
  ['POST','/equipos/delete/{id}','EquiposController@destroy'],

  ['GET','/calendario','CalendarioController@index'],
  ['POST','/calendario/store','CalendarioController@store'],
  ['POST','/calendario/delete/{id}','CalendarioController@destroy'],

  ['GET','/mantenimiento','MantenimientoController@index'],
  ['POST','/mantenimiento/mover','MantenimientoController@mover'],
  ['POST','/mantenimiento/avance','MantenimientoController@avance'],
  ['POST','/mantenimiento/tareaToggle','MantenimientoController@tareaToggle'],
  ['POST','/mantenimiento/tareaNueva','MantenimientoController@tareaNueva'],

  ['GET','/login','AuthController@loginView'],
  ['POST','/login','AuthController@login'],
  ['GET','/logout','AuthController@logout'],

  ['GET','/health/replica','EquiposController@replicaHealth'], // evidencia réplica (B)
];

// Resolver ruta
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
foreach ($routes as [$m,$path,$handler]) {
  $regex = '#^'.preg_replace('#\{[a-zA-Z_]+\}#','([a-zA-Z0-9_-]+)',$path).'$#';
  if ($m===$method && preg_match($regex, $uri, $matches)) {
    [$ctrl,$act]=explode('@',$handler);
    require_once BASE_PATH."/controllers/$ctrl.php";
    $c = new $ctrl;
    array_shift($matches);
    return call_user_func_array([$c,$act], $matches);
  }
}
Response::status(404,'Not Found');
