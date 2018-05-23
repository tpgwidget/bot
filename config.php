<?php
require_once __DIR__.'/vendor/autoload.php';

// Load env file
$dotenv = new \Dotenv\Dotenv(__DIR__);
$dotenv->load();

// Connect to database
try {
    $db = new \PDO('mysql:host='.getenv('DB_HOST').';dbname='.getenv('DB_NAME').';charset=utf8', getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
} catch (Exception $e) {
    error_log('['.date('Y-m-d H:i:s') . '] TPGbot SQL Error : ' . $e->getMessage());
    die();
}
