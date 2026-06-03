<?php
// 1. Session Config
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['lifetime' => 0, 'path' => '/', 'secure' => false, 'httponly' => true, 'samesite' => 'Lax']);
    session_start();
}

// 2. Constants & DB
require_once 'config.php';
require_once 'db.php';