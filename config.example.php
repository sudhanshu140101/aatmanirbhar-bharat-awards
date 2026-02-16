<?php
/**
 * Database configuration - copy this file to config.php and set your values.
 * Do not commit config.php (it is in .gitignore).
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            if (php_sapi_name() === 'cli' || (defined('API_REQUEST') && API_REQUEST)) {
                throw $e;
            }
            $detail = $e->getMessage();
            $hint = 'Edit <strong>config.php</strong> (DB_HOST, DB_USER, DB_PASS, DB_NAME). If you see "Access denied", set DB_PASS to your MySQL password. If you see "Unknown database", run <strong>install.php</strong> once to create the database.';
            header('Content-Type: text/html; charset=utf-8');
            header('HTTP/1.1 503 Service Unavailable');
            echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Database Error</title></head><body style="font-family:sans-serif;max-width:560px;margin:40px auto;padding:24px;"><h1>Database Error</h1><p>' . htmlspecialchars($detail) . '</p><p>' . $hint . '</p><p><a href="install.php" style="color:#1a56db;">Run install.php to create database and tables</a></p></body></html>';
            exit;
        }
    }
    return $pdo;
}
