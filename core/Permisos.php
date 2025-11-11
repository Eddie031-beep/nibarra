<?php
/**
 * Sistema de permisos basado en roles
 * - admin: Todos los permisos
 * - tecnico: Puede crear, editar y gestionar mantenimientos
 * - visor: Solo puede ver (lectura)
 */

class Permisos {
  
  /**
   * Verifica si el usuario actual puede crear
   */
  public static function puedeCrear(): bool {
    $user = Auth::user();
    if (!$user) return false;
    
    return in_array($user['rol'], ['admin', 'tecnico']);
  }
  
  /**
   * Verifica si el usuario actual puede editar
   */
  public static function puedeEditar(): bool {
    $user = Auth::user();
    if (!$user) return false;
    
    return in_array($user['rol'], ['admin', 'tecnico']);
  }
  
  /**
   * Verifica si el usuario actual puede eliminar
   */
  public static function puedeEliminar(): bool {
    $user = Auth::user();
    if (!$user) return false;
    
    // Solo admin puede eliminar
    return $user['rol'] === 'admin';
  }
  
  /**
   * Verifica si el usuario actual es admin
   */
  public static function esAdmin(): bool {
    $user = Auth::user();
    if (!$user) return false;
    
    return $user['rol'] === 'admin';
  }
  
  /**
   * Verifica si el usuario actual es técnico
   */
  public static function esTecnico(): bool {
    $user = Auth::user();
    if (!$user) return false;
    
    return $user['rol'] === 'tecnico';
  }
  
  /**
   * Verifica si el usuario actual es visor
   */
  public static function esVisor(): bool {
    $user = Auth::user();
    if (!$user) return false;
    
    return $user['rol'] === 'visor';
  }
  
  /**
   * Requiere permiso de creación, redirecciona si no tiene
   */
  public static function requireCrear() {
    if (!self::puedeCrear()) {
      http_response_code(403);
      die('
        <!DOCTYPE html>
        <html>
        <head>
          <title>Acceso Denegado</title>
          <style>
            body {
              font-family: system-ui;
              background: #0b1220;
              color: #e5e7eb;
              display: flex;
              align-items: center;
              justify-content: center;
              min-height: 100vh;
              margin: 0;
            }
            .error-box {
              background: #0f172a;
              border: 1px solid #ef4444;
              border-radius: 1rem;
              padding: 2rem;
              max-width: 500px;
              text-align: center;
            }
            h1 { color: #ef4444; margin: 0 0 1rem 0; }
            p { margin: 0 0 1.5rem 0; line-height: 1.6; }
            a {
              display: inline-block;
              padding: 0.75rem 1.5rem;
              background: #4f46e5;
              color: white;
              text-decoration: none;
              border-radius: 0.5rem;
              font-weight: 600;
            }
          </style>
        </head>
        <body>
          <div class="error-box">
            <h1>🚫 Acceso Denegado</h1>
            <p>No tienes permisos para crear recursos. Solo usuarios <strong>Admin</strong> y <strong>Técnico</strong> pueden realizar esta acción.</p>
            <p>Tu rol actual: <strong>' . (Auth::user()['rol'] ?? 'Desconocido') . '</strong></p>
            <a href="' . ENV_APP['BASE_URL'] . '/equipos">← Volver</a>
          </div>
        </body>
        </html>
      ');
    }
  }
  
  /**
   * Requiere permiso de edición, redirecciona si no tiene
   */
  public static function requireEditar() {
    if (!self::puedeEditar()) {
      http_response_code(403);
      die('
        <!DOCTYPE html>
        <html>
        <head>
          <title>Acceso Denegado</title>
          <style>
            body {
              font-family: system-ui;
              background: #0b1220;
              color: #e5e7eb;
              display: flex;
              align-items: center;
              justify-content: center;
              min-height: 100vh;
              margin: 0;
            }
            .error-box {
              background: #0f172a;
              border: 1px solid #ef4444;
              border-radius: 1rem;
              padding: 2rem;
              max-width: 500px;
              text-align: center;
            }
            h1 { color: #ef4444; margin: 0 0 1rem 0; }
            p { margin: 0 0 1.5rem 0; line-height: 1.6; }
            a {
              display: inline-block;
              padding: 0.75rem 1.5rem;
              background: #4f46e5;
              color: white;
              text-decoration: none;
              border-radius: 0.5rem;
              font-weight: 600;
            }
          </style>
        </head>
        <body>
          <div class="error-box">
            <h1>🚫 Acceso Denegado</h1>
            <p>No tienes permisos para editar recursos. Solo usuarios <strong>Admin</strong> y <strong>Técnico</strong> pueden realizar esta acción.</p>
            <p>Tu rol actual: <strong>' . (Auth::user()['rol'] ?? 'Desconocido') . '</strong></p>
            <a href="' . ENV_APP['BASE_URL'] . '/equipos">← Volver</a>
          </div>
        </body>
        </html>
      ');
    }
  }
  
  /**
   * Requiere permiso de eliminación, redirecciona si no tiene
   */
  public static function requireEliminar() {
    if (!self::puedeEliminar()) {
      http_response_code(403);
      die('
        <!DOCTYPE html>
        <html>
        <head>
          <title>Acceso Denegado</title>
          <style>
            body {
              font-family: system-ui;
              background: #0b1220;
              color: #e5e7eb;
              display: flex;
              align-items: center;
              justify-content: center;
              min-height: 100vh;
              margin: 0;
            }
            .error-box {
              background: #0f172a;
              border: 1px solid #ef4444;
              border-radius: 1rem;
              padding: 2rem;
              max-width: 500px;
              text-align: center;
            }
            h1 { color: #ef4444; margin: 0 0 1rem 0; }
            p { margin: 0 0 1.5rem 0; line-height: 1.6; }
            a {
              display: inline-block;
              padding: 0.75rem 1.5rem;
              background: #4f46e5;
              color: white;
              text-decoration: none;
              border-radius: 0.5rem;
              font-weight: 600;
            }
          </style>
        </head>
        <body>
          <div class="error-box">
            <h1>🚫 Acceso Denegado</h1>
            <p>No tienes permisos para eliminar recursos. Solo usuarios <strong>Admin</strong> pueden realizar esta acción.</p>
            <p>Tu rol actual: <strong>' . (Auth::user()['rol'] ?? 'Desconocido') . '</strong></p>
            <a href="' . ENV_APP['BASE_URL'] . '/equipos">← Volver</a>
          </div>
        </body>
        </html>
      ');
    }
  }
}