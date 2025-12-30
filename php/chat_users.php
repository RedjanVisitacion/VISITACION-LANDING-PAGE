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
session_start();
if (!isset($_SESSION['user_id'])) { echo json_encode(['success' => false, 'message' => 'Unauthorized.']); exit; }
if (($_SESSION['role'] ?? 'user') !== 'admin') { echo json_encode(['success' => false, 'message' => 'Forbidden.']); exit; }

require __DIR__ . '/db.php';
$me = (int)($_SESSION['user_id'] ?? 0);

// Build list of users (non-admin) ordered by most recent message with admin
$sql = "SELECT u.id, u.username, COALESCE(MAX(m.id),0) AS last_id
        FROM users u
        LEFT JOIN messages m ON ((m.sender_id = u.id AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = u.id))
        WHERE u.id <> ? AND (u.role = 'user' OR u.role IS NULL)
        GROUP BY u.id, u.username
        ORDER BY last_id DESC, u.username ASC
        LIMIT 200";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error (prepare).', 'hint' => $conn->error]);
    $conn->close();
    exit;
}
$stmt->bind_param('iii', $me, $me, $me);
if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error (execute).', 'hint' => $stmt->error]);
    $stmt->close();
    $conn->close();
    exit;
}
$res = $stmt->get_result();
$users = [];
while ($row = $res->fetch_assoc()) {
    $uname = (string)($row['username'] ?? 'User');
    $users[] = [
        'id' => (int)$row['id'],
        'username' => $uname,
        'avatar' => strtoupper(substr($uname, 0, 1)),
    ];
}
$stmt->close();
$conn->close();
echo json_encode(['success' => true, 'users' => $users]);
