<?php
declare(strict_types=1);

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
  require_once dirname(__DIR__).'/controllers/ChatbotController.php';
  require_once dirname(__DIR__).'/controllers/DashboardController.php';
  require_once dirname(__DIR__).'/controllers/FacturaController.php';
  
  $usuariosControllerPath = dirname(__DIR__).'/controllers/UsuariosController.php';
  
  $usuariosControllerPath = dirname(__DIR__).'/controllers/UsuariosController.php';
  if (file_exists($usuariosControllerPath)) {
    require_once $usuariosControllerPath;
  }

  $basePath = parse_url(ENV_APP['BASE_URL'], PHP_URL_PATH) ?: '';
  $reqPath  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

  $route = $reqPath;
  if ($basePath && str_starts_with($route, $basePath)) {
    $route = substr($route, strlen($basePath));
  }
  $route = $route === '' ? '/' : $route;

  $GLOBALS['route'] = $route;
  $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

  function dispatch(string $method, string $route) {
    // ============================================
    // RUTAS GET
    // ============================================
    
    if ($method==='GET' && $route==='/health/replica') { 
      (new HealthController)->replica(); 
      return; 
    }
    
    if ($method==='GET' && ($route==='/' || $route==='/equipos')) { 
      (new EquiposController)->index(); 
      return; 
    }
    
    if ($method==='GET' && $route==='/dashboard') { 
      (new DashboardController)->index(); 
      return; 
    }
    
    if ($method==='GET' && $route==='/calendario') { 
      (new CalendarioController)->index(); 
      return; 
    }
    
    if ($method==='GET' && $route==='/mantenimiento') { 
      (new MantenimientoController)->index(); 
      return; 
    }
    
    if ($method==='GET' && preg_match('#^/mantenimiento/obtener/(\d+)$#',$route,$m)) { 
      (new MantenimientoController)->obtener((int)$m[1]); 
      return; 
    }
    
    if ($method==='GET' && $route==='/usuarios') { 
      if (class_exists('UsuariosController')) {
        (new UsuariosController)->index(); 
      } else {
        http_response_code(404);
        echo "Módulo de usuarios no disponible";
      }
      return; 
    }
    
    if ($method==='GET' && $route==='/login') { 
      (new AuthController)->loginView(); 
      return; 
    }
    
    if ($method==='GET' && $route==='/register') { 
      (new AuthController)->registerView(); 
      return; 
    }
    
    if ($method==='GET' && $route==='/logout') { 
      (new AuthController)->logout(); 
      return; 
    }

    // ============================================
    // RUTAS POST
    // ============================================
    
    // AUTH
    if ($method==='POST' && $route==='/login') { 
      (new AuthController)->login(); 
      return; 
    }
    
    if ($method==='POST' && $route==='/register') { 
      (new AuthController)->register(); 
      return; 
    }
    
    // EQUIPOS
    if ($method==='POST' && $route==='/equipos/store') { 
      (new EquiposController)->store(); 
      return; 
    }
    
    if ($method==='POST' && preg_match('#^/equipos/delete/(\d+)$#',$route,$m)) { 
      (new EquiposController)->destroy((int)$m[1]); 
      return; 
    }
    
    if ($method==='POST' && preg_match('#^/equipos/update/(\d+)$#',$route,$m)) { 
      (new EquiposController)->update((int)$m[1]); 
      return; 
    }

    // ============================================
    // RUTAS DE FACTURAS
    // ============================================

    if ($method==='GET' && $route==='/facturas') { 
      (new FacturaController)->index(); 
      return; 
    }

    if ($method==='GET' && preg_match('#^/facturas/ver/(\d+)$#',$route,$m)) { 
      (new FacturaController)->ver((int)$m[1]); 
      return; 
    }

    if ($method==='GET' && preg_match('#^/facturas/pdf/(\d+)$#',$route,$m)) { 
      (new FacturaController)->descargarPDF((int)$m[1]); 
      return; 
    }

    if ($method==='POST' && $route==='/facturas/generar') { 
      (new FacturaController)->generar(); 
      return; 
    }

    // ⭐ NUEVA RUTA PARA ACTUALIZAR FACTURA
    if ($method==='POST' && $route==='/facturas/actualizar') { 
      (new FacturaController)->actualizar(); 
      return; 
    }

    if ($method==='POST' && $route==='/facturas/actualizar-estado') { 
      (new FacturaController)->actualizarEstado(); 
      return; 
    }

    if ($method==='POST' && preg_match('#^/facturas/delete/(\d+)$#',$route,$m)) { 
      (new FacturaController)->destroy((int)$m[1]); 
      return; 
    }
    
    // MANTENIMIENTO
    if ($method==='POST' && $route==='/mantenimiento/store') { 
      (new MantenimientoController)->store(); 
      return; 
    }
    
    if ($method==='POST' && $route==='/mantenimiento/mover') { 
      (new MantenimientoController)->mover(); 
      return; 
    }
    
    if ($method==='POST' && $route==='/mantenimiento/tareaToggle') { 
      (new MantenimientoController)->tareaToggle(); 
      return; 
    }
    
    if ($method==='POST' && $route==='/mantenimiento/tareaNueva') { 
      (new MantenimientoController)->tareaNueva(); 
      return; 
    }
    
    if ($method==='POST' && $route==='/mantenimiento/tareaEliminar') { 
      (new MantenimientoController)->tareaEliminar(); 
      return; 
    }
    
    if ($method==='POST' && preg_match('#^/mantenimiento/delete/(\d+)$#',$route,$m)) { 
      (new MantenimientoController)->destroy((int)$m[1]); 
      return; 
    }

    // CALENDARIO ⭐⭐⭐ RUTAS CRÍTICAS PARA EDICIÓN ⭐⭐⭐
    if ($method==='POST' && $route==='/calendario/store') { 
      (new CalendarioController)->store(); 
      return; 
    }
    
    if ($method==='POST' && preg_match('#^/calendario/update/(\d+)$#',$route,$m)) { 
      (new CalendarioController)->update((int)$m[1]); 
      return; 
    }
    
    if ($method==='POST' && preg_match('#^/calendario/delete/(\d+)$#',$route,$m)) { 
      (new CalendarioController)->destroy((int)$m[1]); 
      return; 
    }

    // CHATBOT
    if ($method==='POST' && $route==='/chatbot/query') { 
      (new ChatbotController)->query(); 
      return; 
    }

    // USUARIOS
    if (class_exists('UsuariosController')) {
      if ($method==='POST' && preg_match('#^/usuarios/update/(\d+)$#',$route,$m)) { 
        (new UsuariosController)->update((int)$m[1]); 
        return; 
      }
      
      if ($method==='POST' && preg_match('#^/usuarios/delete/(\d+)$#',$route,$m)) { 
        (new UsuariosController)->destroy((int)$m[1]); 
        return; 
      }
    }

    // Después de /mantenimiento/tareaNueva
if ($method==='POST' && $route==='/mantenimiento/actualizarProgreso') { 
  (new MantenimientoController)->actualizarProgreso(); 
  return; 
}

    // ============================================
    // 404 - RUTA NO ENCONTRADA
    // ============================================
    http_response_code(404);
    include dirname(__DIR__).'/views/layouts/header.php';
    echo '<section class="card" style="padding:16px">';
    echo '<h2>404 - Ruta no encontrada</h2>';
    echo '<p>La ruta <code>'.htmlspecialchars($route, ENT_QUOTES, 'UTF-8').'</code> no existe.</p>';
    echo '<p><strong>Método:</strong> '.$method.'</p>';
    echo '<p><a href="'.ENV_APP['BASE_URL'].'/equipos">← Volver a Equipos</a></p>';
    echo '</section>';
    include dirname(__DIR__).'/views/layouts/footer.php';
  }

  dispatch($method, $route);

} catch (Throwable $e) {
  http_response_code(500);
  
  function safe_error_output($value) {
    if (is_string($value)) {
      return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
  }
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
      <p><strong>Mensaje:</strong> <?= safe_error_output($e->getMessage()) ?></p>
      <p><strong>Archivo:</strong> <span class="file"><?= safe_error_output($e->getFile()) ?></span></p>
      <p><strong>Línea:</strong> <span class="line"><?= safe_error_output($e->getLine()) ?></span></p>
      
      <h3>Stack Trace:</h3>
      <div class="trace">
        <pre><?= safe_error_output($e->getTraceAsString()) ?></pre>
      </div>
      
      <div style="margin-top: 20px">
        <a href="<?= ENV_APP['BASE_URL'] ?? '' ?>/equipos" style="color: #4fc3f7; text-decoration: none;">← Volver a Equipos</a>
      </div>
    </div>
  </body>
  </html>
  <?php
  exit;
}