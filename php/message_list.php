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
// Use same session name and cookie settings as other pages
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
require __DIR__ . '/db.php';

// Ensure two-way messages table exists
$conn->query("CREATE TABLE IF NOT EXISTS messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sender_id INT NOT NULL,
  receiver_id INT NOT NULL,
  content TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (sender_id),
  INDEX (receiver_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
// Migrate from legacy schema if needed
$hasSender = false; $hasUser = false;
if ($rs0 = $conn->query("SHOW COLUMNS FROM messages LIKE 'sender_id'")) { $hasSender = ($rs0->num_rows > 0); $rs0->close(); }
if ($rs1 = $conn->query("SHOW COLUMNS FROM messages LIKE 'user_id'")) { $hasUser = ($rs1->num_rows > 0); $rs1->close(); }
if (!$hasSender) {
    @$conn->query("ALTER TABLE messages ADD COLUMN sender_id INT NULL AFTER id");
    @$conn->query("ALTER TABLE messages ADD COLUMN receiver_id INT NULL AFTER sender_id");
    // Determine admin id for backfill
    $adminId = 0; $rr = $conn->query("SELECT id FROM users WHERE role='admin' LIMIT 1");
    if ($rr && $row = $rr->fetch_assoc()) { $adminId = (int)$row['id']; }
    if ($rr) { $rr->close(); }
    if ($hasUser) {
        if ($adminId > 0) {
            @$conn->query("UPDATE messages SET sender_id = user_id, receiver_id = $adminId WHERE sender_id IS NULL OR receiver_id IS NULL");
        } else {
            @$conn->query("UPDATE messages SET sender_id = user_id, receiver_id = user_id WHERE sender_id IS NULL OR receiver_id IS NULL");
        }
        @$conn->query("ALTER TABLE messages DROP COLUMN user_id");
    }
    @$conn->query("ALTER TABLE messages MODIFY sender_id INT NOT NULL");
    @$conn->query("ALTER TABLE messages MODIFY receiver_id INT NOT NULL");
    @$conn->query("ALTER TABLE messages ADD INDEX(sender_id)");
    @$conn->query("ALTER TABLE messages ADD INDEX(receiver_id)");
}

$me = (int)($_SESSION['user_id'] ?? 0);
$myRole = $_SESSION['role'] ?? 'user';
$with = isset($_GET['with']) ? (int)$_GET['with'] : 0;

// Determine partner
if ($myRole !== 'admin') {
    // For users, default partner is admin
    $rs = $conn->query("SELECT id, username FROM users WHERE role='admin' LIMIT 1");
    $adminId = 0; $adminName = 'Admin';
    if ($rs && $row = $rs->fetch_assoc()) { $adminId = (int)$row['id']; $adminName = $row['username']; }
    if ($rs) { $rs->close(); }
    if ($adminId === 0) { echo json_encode(['success' => false, 'message' => 'Admin account not found.']); $conn->close(); exit; }
    $with = $adminId;
    $withName = $adminName;
} else {
    // Admin: if no partner specified, pick most recent conversation partner
    if ($with === 0) {
        $rs = $conn->query("SELECT CASE WHEN sender_id = $me THEN receiver_id ELSE sender_id END AS other_id
                             FROM messages WHERE sender_id = $me OR receiver_id = $me
                             ORDER BY id DESC LIMIT 1");
        if ($rs && $row = $rs->fetch_assoc()) { $with = (int)$row['other_id']; }
        if ($rs) { $rs->close(); }
    }
    if ($with === 0) {
        // fallback: first regular user
        $rs = $conn->query("SELECT id, username FROM users WHERE role='user' ORDER BY id ASC LIMIT 1");
        if ($rs && $row = $rs->fetch_assoc()) { $with = (int)$row['id']; $withName = $row['username']; }
        if ($rs) { $rs->close(); }
    }
    if (!isset($withName)) {
        $rs = $conn->query("SELECT username FROM users WHERE id = $with LIMIT 1");
        if ($rs && $row = $rs->fetch_assoc()) { $withName = $row['username']; }
        if ($rs) { $rs->close(); }
    }
}

$limit = 200;
$stmt = $conn->prepare('SELECT id, sender_id, receiver_id, content, created_at
                        FROM messages
                        WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
                        ORDER BY id ASC
                        LIMIT ?');
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error (prepare).', 'hint' => $conn->error]);
    $conn->close();
    exit;
}
$stmt->bind_param('iiiii', $me, $with, $with, $me, $limit);
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
    $dt = null; $iso = null;
    try {
        $dt = new DateTime($row['created_at'], new DateTimeZone('Asia/Manila'));
        $iso = $dt->format(DateTime::ATOM);
    } catch (Throwable $e) {
        $iso = null;
    }
    $items[] = [
        'id' => (int)$row['id'],
        'sender_id' => (int)$row['sender_id'],
        'receiver_id' => (int)$row['receiver_id'],
        'content' => $row['content'],
        'created_at' => $row['created_at'],
        'created_at_iso' => $iso,
    ];
}
$stmt->close();
$conn->close();
echo json_encode(['success' => true, 'items' => $items, 'me_id' => $me, 'with_id' => $with, 'with_username' => $withName ?? null]);
?>
