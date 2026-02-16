<?php
require 'config.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username === '' || $password === '') {
        $error = 'Enter username and password';
    } else {
        $stmt = db()->prepare("SELECT id, password FROM admin_users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $row = $stmt->fetch();
        if ($row && password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = (int) $row['id'];
            header('Location: index.php');
            exit;
        }
        $error = 'Invalid credentials';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Anek+Devanagari:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Anek Devanagari', sans-serif; background: #F5F1E8; color: #1A2B4D; }
        .gov-bg { background: linear-gradient(135deg, #1A2B4D 0%, #2C3E5F 50%, #1A2B4D 100%); }
        .gov-button { background-color: #1A2B4D; color: #F5F1E8; border: 2px solid #D4AF37; transition: all 0.3s ease; }
        .gov-button:hover { background-color: #D4AF37; color: #1A2B4D; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 gov-bg">
    <div class="w-full max-w-md bg-white rounded-xl shadow-xl border-l-4 border-yellow-500 p-8">
        <h1 class="text-2xl font-bold text-blue-900 mb-6 text-center">Admin Login</h1>
        <?php if ($error): ?>
        <p class="text-red-600 text-sm mb-4 text-center"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post" class="space-y-4">
            <div>
                <label for="username" class="block text-sm font-bold text-blue-900 mb-1">User ID</label>
                <input type="text" id="username" name="username" required autocomplete="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" class="w-full px-4 py-3 rounded-md border-2 border-gray-300 focus:border-yellow-500 focus:outline-none">
            </div>
            <div>
                <label for="password" class="block text-sm font-bold text-blue-900 mb-1">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password" class="w-full px-4 py-3 rounded-md border-2 border-gray-300 focus:border-yellow-500 focus:outline-none">
            </div>
            <button type="submit" class="w-full gov-button px-4 py-3 font-bold rounded-md">Login</button>
        </form>
    </div>
</body>
</html>
