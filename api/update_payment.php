<?php
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false]);
    exit;
}

session_start();
if (empty($_SESSION['admin_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false]);
    exit;
}

require dirname(__DIR__) . '/config.php';

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$verified = isset($_POST['verified']) ? (int) $_POST['verified'] : 0;
$verified = $verified ? 1 : 0;

if ($id < 1) {
    http_response_code(400);
    echo json_encode(['success' => false]);
    exit;
}

try {
    $stmt = db()->prepare("UPDATE nominations SET payment_verified = ? WHERE id = ?");
    $stmt->execute([$verified, $id]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false]);
}
