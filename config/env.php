<?php
$WIN_HOST = '192.168.1.106';   // ← Windows/XAMPP nueva IP
$UB_HOST  = '127.0.0.1';       // MySQL local Ubuntu

define('ENV_DB', [
  'local' => [
    'host'=>$UB_HOST, 'port'=>3306, 'db'=>'nibarra_db', 'user'=>'win', 'pass'=>'12345',
    'charset'=>'utf8mb4','collation'=>'utf8mb4_unicode_ci'
  ],
  'replica' => [
    'host'=>$WIN_HOST, 'port'=>3307, 'db'=>'nibarra_db', 'user'=>'win', 'pass'=>'12345',
    'charset'=>'utf8mb4','collation'=>'utf8mb4_unicode_ci'
  ],
]);

define('ENV_APP', [
  'BASE_URL'   => 'http://192.168.1.141/nibarra',  // ← Ubuntu nueva IP
  'ASSETS_URL' => '/nibarra/public/assets',
  'APP_ENV'    => 'prod'
]);
