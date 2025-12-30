<?php
ini_set('display_errors', '0');
error_reporting(E_ALL);
register_shutdown_function(function(){
    $e = error_get_last();
    if ($e && (!headers_sent())) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Server fatal error', 'hint' => $e['message']]);
    }
});
header('Content-Type: application/json');
header('Cache-Control: no-store');
// Ensure consistent and persistent session cookies across the app
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
$domain = $_SERVER['HTTP_HOST'] ?? '';
@session_name('RPSVSESSID');
if (PHP_VERSION_ID >= 70300) {
    session_set_cookie_params([
        'lifetime' => 86400 * 7,
        'path' => '/',
        'domain' => $domain,
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
} else {
    session_set_cookie_params(86400 * 7, '/; samesite=Lax', $domain, $secure, true);
}
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}
require __DIR__ . '/db.php';
$conn->query("CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role VARCHAR(20) NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
@$conn->query("ALTER TABLE users ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT 'user' AFTER password_hash");
// Seed default admin if not exists
$admin_username = 'rpsv_codes';
$admin_password_hash = password_hash('RedjanPhil09', PASSWORD_DEFAULT);
$admin_email = 'admin@example.com';
$admin_role = 'admin';
$chk = $conn->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
if ($chk) {
    $chk->bind_param('s', $admin_username);
    $chk->execute();
    $chk->store_result();
    if ($chk->num_rows === 0) {
        $ins = $conn->prepare('INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)');
        if ($ins) {
            $ins->bind_param('ssss', $admin_username, $admin_email, $admin_password_hash, $admin_role);
            $ins->execute();
            $ins->close();
        }
    }
    $chk->close();
}
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
if ($username === '' || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Username and password are required.']);
    exit;
}
$stmt = $conn->prepare('SELECT id, username, password_hash, role FROM users WHERE username = ? LIMIT 1');
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error (prepare).', 'hint' => $conn->error]);
    exit;
}
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->store_result();
$id = $f_username = $hash = $role = null;
$stmt->bind_result($id, $f_username, $hash, $role);
$userFound = $stmt->fetch();
if ($userFound && password_verify($password, $hash)) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_regenerate_id(true);
    $_SESSION['user_id'] = $id;
    $_SESSION['username'] = $f_username;
    $_SESSION['role'] = $role;
    echo json_encode(['success' => true, 'message' => 'Login successful.', 'role' => $role]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
}
$stmt->close();
$conn->close();
?>
