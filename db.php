<?php
require 'env.php';
require 'config.php';

$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

try{
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    // This forces PDO to throw exceptions if a query fails
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // This ensures data is fetched as associative arrays by default
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    //echo 1;
    }catch(PDOException $e){
    error_log($e->getMessage());
    die("DB con Failed, check the logs");
}