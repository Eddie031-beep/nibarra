<?php
// Ajusta aquí SOLO la IP de Windows si es .105 / .108, etc.
$WIN_HOST = '192.168.1.108';
$UB_HOST  = '127.0.0.1';

define('ENV_DB', [
  'local' => [ // Ubuntu MySQL (escritura)
    'host'=>$UB_HOST,'port'=>3306,'db'=>'nibarra_db','user'=>'win','pass'=>'12345',
    'charset'=>'utf8mb4','collation'=>'utf8mb4_unicode_ci'
  ],
  'replica' => [ // Windows XAMPP MariaDB (lectura/verificación)
    'host'=>$WIN_HOST,'port'=>3307,'db'=>'nibarra_db','user'=>'win','pass'=>'12345',
    'charset'=>'utf8mb4','collation'=>'utf8mb4_unicode_ci'
  ],
]);

define('ENV_APP', [
  'BASE_URL'   => 'http://192.168.1.142/nibarra/public',
  'ASSETS_URL' => '/nibarra/public/assets', // no usamos assets externos, va inline; se deja por si
  'APP_ENV'    => 'prod'
]);
