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
session_start();
if (!isset($_SESSION['user_id'])) { echo json_encode(['success' => false, 'message' => 'Unauthorized.']); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['success' => false, 'message' => 'Invalid request.']); exit; }
require __DIR__ . '/db.php';

$conn->query("CREATE TABLE IF NOT EXISTS messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  content TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

$user_id = (int)($_SESSION['user_id'] ?? 0);
$content = trim($_POST['content'] ?? '');
if ($content === '') { echo json_encode(['success' => false, 'message' => 'Message cannot be empty.']); $conn->close(); exit; }
if (mb_strlen($content) > 2000) { $content = mb_substr($content, 0, 2000); }

$stmt = $conn->prepare('INSERT INTO messages (user_id, content) VALUES (?, ?)');
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error (prepare).', 'hint' => $conn->error]);
    $conn->close();
    exit;
}
$stmt->bind_param('is', $user_id, $content);
$ok = $stmt->execute();
if ($ok) {
    echo json_encode(['success' => true, 'message' => 'Message sent.', 'id' => $stmt->insert_id]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to send message.', 'hint' => $stmt->error]);
}
$stmt->close();
$conn->close();
?>
