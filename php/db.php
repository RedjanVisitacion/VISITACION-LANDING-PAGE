<?php
$DB_HOST = 'sql100.infinityfree.com';
$DB_USER = 'if0_40772113';
$DB_PASS = 'PWevVotDArm8U7h';
$DB_NAME = 'if0_40772113_redjan';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}
$conn->set_charset('utf8mb4');
?>
