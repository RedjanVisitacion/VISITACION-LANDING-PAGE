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
  password_plain VARCHAR(255) DEFAULT NULL,
  role VARCHAR(20) NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
@$conn->query("ALTER TABLE users ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT 'user' AFTER password_hash");
@ $conn->query("ALTER TABLE users ADD COLUMN password_plain VARCHAR(255) DEFAULT NULL AFTER password_hash");
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';
if ($username === '' || $email === '' || $password === '' || $confirm === '') {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email.']);
    exit;
}
if ($password !== $confirm) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
    exit;
}
$check = $conn->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
if (!$check) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error (prepare check).', 'hint' => $conn->error]);
    exit;
}
$check->bind_param('ss', $username, $email);
if (!$check->execute()) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error (execute check).', 'hint' => $check->error]);
    $check->close();
    $conn->close();
    exit;
}
$check->store_result();
if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Username or email already exists.']);
    $check->close();
    $conn->close();
    exit;
}
$check->close();
$hash = password_hash($password, PASSWORD_DEFAULT);
$role = 'user';
$stmt = $conn->prepare('INSERT INTO users (username, email, password_hash, password_plain, role) VALUES (?, ?, ?, ?, ?)');
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error (prepare insert).', 'hint' => $conn->error]);
    exit;
}
$stmt->bind_param('sssss', $username, $email, $hash, $password, $role);
$ok = $stmt->execute();
if ($ok) {
    echo json_encode(['success' => true, 'message' => 'Registration successful.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Registration failed.', 'hint' => $stmt->error]);
}
$stmt->close();
$conn->close();
?>
