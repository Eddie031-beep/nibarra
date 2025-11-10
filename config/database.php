<?php
return [
  'windows' => [
    'driver'   => 'mysql',
    'host'     => '192.168.1.105',   // IP de tu Windows (host)
    'port'     => 3307,              // XAMPP MariaDB
    'database' => 'nibarra_db',
    'username' => 'win',
    'password' => '12345',
    'charset'  => 'utf8mb4',
    'collation'=> 'utf8mb4_unicode_ci',
  ],
  'ubuntu' => [
    'driver'   => 'mysql',
    'host'     => '127.0.0.1',       // VM Ubuntu local
    'port'     => 3306,              // MySQL 8.0
    'database' => 'nibarra_db',
    'username' => 'win',
    'password' => '12345',
    'charset'  => 'utf8mb4',
    'collation'=> 'utf8mb4_unicode_ci',
  ],
];
