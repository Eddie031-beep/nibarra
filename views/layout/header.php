<?php
// views/layout/header.php
if (!defined('APP_NAME')) {
    require_once dirname(__DIR__, 2) . '/config/app.php';
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= APP_NAME ?> - Sistema de Mantenimiento</title>
  
  <!-- CORREGIDO PARA TU ESTRUCTURA -->
  <link rel="stylesheet" href="/nibarra/public/assets/css/style.css">
  <script defer src="/nibarra/public/assets/js/app.js"></script>
</head>
<body>
<header>
  <nav class="container">
    <div class="logo"><?= APP_NAME ?></div>
    <div class="nav-links">
      <a href="<?= BASE_URL ?>/">Inicio</a>
      <a href="<?= BASE_URL ?>/equipos">Equipos</a>
      <a href="<?= BASE_URL ?>/mantenimiento">Mantenimiento</a>
      <a href="<?= BASE_URL ?>/calendario">Calendario</a>
    </div>
    <div class="nav-links">
      <a href="<?= BASE_URL ?>/login">Login</a>
      <a href="<?= BASE_URL ?>/register">Register</a>
    </div>
  </nav>
</header>
<main class="container">
```

---

## 📁 ARCHIVOS QUE FALTAN CREAR

Basándome en tu estructura, necesitas crear:

### 1. **public/mantenimiento/** (carpeta completa)

Crea estas carpetas y archivos:
```
public/mantenimiento/
├── guardar.php
├── cambiar-estado.php
└── (otros archivos de mantenimiento)
```

### 2. **views/mantenimiento/** (ya existe pero falta contenido)
```
views/mantenimiento/
├── index.php
├── create.php
├── detalle.php
└── _card.php