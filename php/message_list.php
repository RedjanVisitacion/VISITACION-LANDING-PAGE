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
require __DIR__ . '/db.php';

$conn->query("CREATE TABLE IF NOT EXISTS messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  content TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

$user_id = (int)($_SESSION['user_id'] ?? 0);
$limit = 100;
$stmt = $conn->prepare('SELECT id, content, created_at FROM messages WHERE user_id = ? ORDER BY id DESC LIMIT ?');
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error (prepare).', 'hint' => $conn->error]);
    $conn->close();
    exit;
}
$stmt->bind_param('ii', $user_id, $limit);
if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error (execute).', 'hint' => $stmt->error]);
    $stmt->close();
    $conn->close();
    exit;
}
$res = $stmt->get_result();
$items = [];
while ($row = $res->fetch_assoc()) {
    $items[] = [
        'id' => (int)$row['id'],
        'content' => $row['content'],
        'created_at' => $row['created_at'],
    ];
}
$stmt->close();
$conn->close();
echo json_encode(['success' => true, 'items' => $items]);
?>
