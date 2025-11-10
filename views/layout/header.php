<?php
// views/layout/header.php
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= APP_NAME ?></title>
  <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/app.css">
  <script defer src="<?= ASSETS_URL ?>/js/app.js"></script>
  <style>
    /* fallback mínimo por si aún no creas app.css */
    body{font-family:system-ui,Segoe UI,Roboto,Arial,sans-serif;margin:0;background:#0b0d10;color:#e8e8ea}
    header{background:#11161a;border-bottom:1px solid #222;padding:12px 16px;position:sticky;top:0}
    a{color:#72b7ff;text-decoration:none}
    nav a{margin-right:14px}
    .container{max-width:1100px;margin:24px auto;padding:0 16px}
    .btn{background:#2c7be5;color:#fff;padding:.55rem .9rem;border-radius:.5rem;border:0;cursor:pointer}
    .card{background:#121519;border:1px solid #1f2329;border-radius:12px;padding:16px}
  </style>
</head>
<body>
<header>
  <nav class="container">
    <strong><?= APP_NAME ?></strong>
    &nbsp;|&nbsp;
    <a href="<?= BASE_URL ?>/">Inicio</a>
    <a href="<?= BASE_URL ?>/equipos">Equipos</a>
    <a href="<?= BASE_URL ?>/mantenimiento">Mantenimiento</a>
    <a href="<?= BASE_URL ?>/calendario">Calendario</a>
    <span style="float:right">
      <a href="<?= BASE_URL ?>/login">Login</a>
      &nbsp;|&nbsp;
      <a href="<?= BASE_URL ?>/register">Register</a>
    </span>
  </nav>
</header>
<main class="container">
