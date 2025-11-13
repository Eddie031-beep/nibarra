<?php
// config/env.php
$WIN_HOST = '172.20.10.3';   // <- IP de Windows (XAMPP)
$UB_HOST  = '127.0.0.1';     // MySQL local en Ubuntu

define('ENV_DB', [
  'local' => [ // Ubuntu (escritura)
    'host'=>$UB_HOST, 'port'=>3306, 'db'=>'nibarra_db', 'user'=>'win', 'pass'=>'12345',
    'charset'=>'utf8mb4','collation'=>'utf8mb4_unicode_ci'
  ],
  'replica' => [ // Windows XAMPP (lectura/verificación)
    'host'=>$WIN_HOST, 'port'=>3307, 'db'=>'nibarra_db', 'user'=>'win', 'pass'=>'12345',
    'charset'=>'utf8mb4','collation'=>'utf8mb4_unicode_ci'
  ],
]);

define('ENV_APP', [
  'BASE_URL'   => 'http://172.20.10.4/nibarra',  // o /nibarra/public según tu routing
  'ASSETS_URL' => '/nibarra/public/assets',
  'APP_ENV'    => 'prod'
]);
