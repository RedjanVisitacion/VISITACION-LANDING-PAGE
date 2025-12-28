<?php
ini_set('display_errors', '0');
error_reporting(0);
header('Content-Type: application/json');
header('Cache-Control: no-store');
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
$seed = $conn->prepare('INSERT INTO users (username, email, password_hash, role) SELECT ?, ?, ?, ? WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = ? LIMIT 1)');
$admin_role = 'admin';
$seed->bind_param('sssss', $admin_username, $admin_email, $admin_password_hash, $admin_role, $admin_username);
$seed->execute();
$seed->close();
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
if ($username === '' || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Username and password are required.']);
    exit;
}
$stmt = $conn->prepare('SELECT id, username, password_hash, role FROM users WHERE username = ? LIMIT 1');
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
