<?php
// config/config.php

// Base URL configuration
define('BASE_URL', 'http://localhost/dental');
define('BASE_PATH', 'C:/xampp/htdocs/dental');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
