<?php
/**
 * Admin bootstrap - copy this file to config.php in this folder.
 * The main DB config is in the project root config.php (copy config.example.php to config.php there).
 */
session_start();
require dirname(__DIR__) . '/config.php';

function requireAdmin(): void {
    if (empty($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit;
    }
}
