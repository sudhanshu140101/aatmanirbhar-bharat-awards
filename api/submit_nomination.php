<?php
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

define('API_REQUEST', true);
require dirname(__DIR__) . '/config.php';

$required = ['category', 'company_name', 'founder_name', 'email', 'mobile', 'business_category', 'description'];
$input = array_merge($_POST, [
    'category' => $_POST['category'] ?? '',
    'company_name' => $_POST['company_name'] ?? '',
    'founder_name' => $_POST['founder_name'] ?? '',
    'email' => $_POST['email'] ?? '',
    'mobile' => preg_replace('/\D/', '', $_POST['mobile'] ?? ''),
    'business_category' => $_POST['business_category'] ?? '',
    'revenue' => trim($_POST['revenue'] ?? ''),
    'net_profit' => trim($_POST['net_profit'] ?? ''),
    'net_worth' => trim($_POST['net_worth'] ?? ''),
    'credit_facilities' => trim($_POST['credit_facilities'] ?? ''),
    'udyam_registration' => trim($_POST['udyam_registration'] ?? ''),
    'description' => trim($_POST['description'] ?? ''),
    'terms_accept' => isset($_POST['terms_accept']) ? 1 : 0,
]);

foreach ($required as $key) {
    if (!isset($input[$key]) || (is_string($input[$key]) && trim($input[$key]) === '')) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Missing or invalid required field: ' . $key]);
        exit;
    }
}

if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid email']);
    exit;
}

if (strlen($input['mobile']) !== 10 || !ctype_digit($input['mobile'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid 10-digit mobile']);
    exit;
}


$categoryName = null;
$categoryCode = trim($input['category']);
$allowedCategoryFallback = [
    'innovation' => 'Aatmanirbhar Innovation Award',
    'startup' => 'Startup of the Year',
    'growth' => 'MSME Growth Champion',
    'women' => 'Women Entrepreneur Excellence',
    'sustainable' => 'Sustainable Business Impact',
    'tech' => 'Tech & Digital Transformation',
    'export' => 'Export & Global Impact',
    'social' => 'Social Innovation & Community',
    'young' => 'Young Entrepreneur Rising Star',
    'rural' => 'Best Rural Enterprise',
];
try {
    $pdo = db();
    $stmt = $pdo->prepare("SELECT name FROM award_categories WHERE code = ? LIMIT 1");
    $stmt->execute([$categoryCode]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $categoryName = $row ? $row['name'] : null;
    if ($categoryName === null && isset($allowedCategoryFallback[$categoryCode])) {
        $categoryName = $allowedCategoryFallback[$categoryCode];
    }
} catch (Throwable $e) {
    if (isset($allowedCategoryFallback[$categoryCode])) {
        $categoryName = $allowedCategoryFallback[$categoryCode];
    }
}
if ($categoryName === null || $categoryName === '') {
    if (isset($allowedCategoryFallback[$categoryCode])) {
        $categoryName = $allowedCategoryFallback[$categoryCode];
    } else {
        $byName = array_flip($allowedCategoryFallback);
        if (isset($byName[$categoryCode])) {
            $categoryName = $categoryCode;
        }
    }
}
if ($categoryName === null || $categoryName === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid category']);
    exit;
}

$allowedBusiness = ['micro', 'small', 'medium', 'listed', 'startup'];
if (!in_array($input['business_category'], $allowedBusiness, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid business category']);
    exit;
}

if ($input['terms_accept'] !== 1) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Terms must be accepted']);
    exit;
}

$amountFields = ['revenue', 'net_profit', 'net_worth', 'credit_facilities'];
foreach ($amountFields as $f) {
    if ($input[$f] !== '' && !preg_match('/^\d+(\.\d+)?$/', $input[$f])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid amount: ' . $f]);
        exit;
    }
}

try {
    $pdo = db();
    $sql = "INSERT INTO nominations (category, company_name, founder_name, email, mobile, business_category, revenue, net_profit, net_worth, credit_facilities, udyam_registration, description, terms_accept) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $categoryName,
        $input['company_name'],
        $input['founder_name'],
        $input['email'],
        $input['mobile'],
        $input['business_category'],
        $input['revenue'] ?: null,
        $input['net_profit'] ?: null,
        $input['net_worth'] ?: null,
        $input['credit_facilities'] ?: null,
        $input['udyam_registration'] ?: null,
        $input['description'],
        1,
    ]);
    echo json_encode(['success' => true, 'id' => (int) $pdo->lastInsertId()]);
} catch (PDOException $e) {
    $code = (strpos($e->getMessage(), 'Access denied') !== false || strpos($e->getMessage(), 'Unknown database') !== false) ? 503 : 500;
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $code === 503 ? 'Database not set up. Run install.php and check config.php.' : 'Submission failed']);
}
