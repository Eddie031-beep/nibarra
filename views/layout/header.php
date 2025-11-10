<?php
// views/layout/header.php
if (!defined('APP_NAME')) {
    require_once dirname(__DIR__, 2) . '/config/app.php';
}

// Detectar página actual para marcar activa
$current_page = $_GET['p'] ?? '';
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= APP_NAME ?> - Sistema de Mantenimiento</title>
  
  <!-- CSS CORREGIDO - Ruta absoluta desde public -->
  <link rel="stylesheet" href="/nibarra/public/css/style.css">
  
  <!-- FullCalendar CSS para el calendario -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css">
  
  <!-- Protección contra copia (Requisito D) -->
  <style>
    /* Deshabilitar selección de texto */
    * {
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }
    /* Permitir selección solo en inputs */
    input, textarea {
      -webkit-user-select: text !important;
      -moz-user-select: text !important;
      -ms-user-select: text !important;
      user-select: text !important;
    }
    /* Deshabilitar clic derecho */
    body {
      -webkit-touch-callout: none;
    }
  </style>
</head>
<body>
<header>
  <nav class="container">
    <div class="logo">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:middle;margin-right:8px">
        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
      </svg>
      <?= APP_NAME ?>
    </div>
    <div class="nav-links">
      <a href="/nibarra/public/" class="<?= $current_page === '' ? 'active' : '' ?>">
        <span>🏠</span> Inicio
      </a>
      <a href="/nibarra/public/equipos" class="<?= strpos($current_page, 'equipos') === 0 ? 'active' : '' ?>">
        <span>⚙️</span> Equipos
      </a>
      <a href="/nibarra/public/mantenimiento" class="<?= strpos($current_page, 'mantenimiento') === 0 ? 'active' : '' ?>">
        <span>🔧</span> Mantenimiento
      </a>
      <a href="/nibarra/public/calendario" class="<?= $current_page === 'calendario' ? 'active' : '' ?>">
        <span>📅</span> Calendario
      </a>
    </div>
    <div class="nav-links">
      <a href="/nibarra/public/login" class="<?= $current_page === 'login' ? 'active' : '' ?>">Login</a>
      <a href="/nibarra/public/register" class="<?= $current_page === 'register' ? 'active' : '' ?>">Register</a>
    </div>
  </nav>
</header>
<main class="container">

<script>
// Protección contra copia (Requisito D)
document.addEventListener('DOMContentLoaded', function() {
  // Deshabilitar clic derecho
  document.addEventListener('contextmenu', e => e.preventDefault());
  
  // Deshabilitar atajos de teclado comunes
  document.addEventListener('keydown', e => {
    if (e.ctrlKey && (e.key === 'u' || e.key === 's' || e.key === 'c' || e.key === 'p')) {
      e.preventDefault();
      return false;
    }
    if (e.key === 'F12') {
      e.preventDefault();
      return false;
    }
  });
  
  // Detectar DevTools (solo advertencia)
  const devtools = /./;
  devtools.toString = function() {
    console.warn('⚠️ Sistema protegido - Nibarra Maintenance System');
  };
  console.log('%c', devtools);
});
</script>