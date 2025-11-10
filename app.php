<?php
// app.php
define('APP_NAME', 'Nibarra');
define('BASE_PATH', __DIR__);
define('PUBLIC_PATH', BASE_PATH . '/public');
define('VIEWS_PATH', BASE_PATH . '/views');

require_once BASE_PATH . '/config/env.php';       // BASE_URL / ASSETS
require_once BASE_PATH . '/config/database.php';  // ENV_DB

require_once BASE_PATH . '/core/Helpers.php';
