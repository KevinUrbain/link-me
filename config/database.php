<?php
require_once 'config.php';

try {
    $pdo = new PDO(DSN, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
} catch (PDOException $e) {
    date_default_timezone_set('UTC');
    $date = date("Y-m-d H:i:s");
    $errorMessage = "ERROR MSG : {$e->getMessage()}, ERROR CODE : {$e->getCode()}, DATE : {$date}" . PHP_EOL;
    $destination = ROOT . '/logs/error_log.txt';
    error_log($errorMessage, 3, $destination);
    die();
}
