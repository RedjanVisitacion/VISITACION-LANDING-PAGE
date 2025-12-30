<?php
// Use Philippine time across PHP runtime
@date_default_timezone_set('Asia/Manila');
$DB_HOST = 'sql100.infinityfree.com';
$DB_USER = 'if0_40772113';
$DB_PASS = 'PWevVotDArm8U7h';
$DB_NAME = 'if0_40772113_redjan';

if (!extension_loaded('mysqli')) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Server configuration error: MySQLi extension is not enabled.']);
    exit;
}
if (function_exists('mysqli_report')) { mysqli_report(MYSQLI_REPORT_OFF); }

$hosts = [$DB_HOST];
$alt1 = preg_replace('/\.infinityfree\.com$/', '.epizy.com', $DB_HOST);
if ($alt1 && !in_array($alt1, $hosts, true)) { $hosts[] = $alt1; }
$alt2 = preg_replace('/\.infinityfree\.com$/', '.byetcluster.com', $DB_HOST);
if ($alt2 && !in_array($alt2, $hosts, true)) { $hosts[] = $alt2; }

$localHosts = ['127.0.0.1', 'localhost'];
$conn = null;
$lastErr = '';

foreach (array_merge($hosts, $localHosts) as $h) {
    $user = $DB_USER;
    $pass = $DB_PASS;
    if (in_array($h, $localHosts, true)) { $user = 'root'; $pass = ''; }

    $tmp = null;
    try {
        $tmp = new mysqli($h, $user, $pass, $DB_NAME);
    } catch (Throwable $ex) {
        $lastErr = $ex->getMessage();
        $tmp = null;
    }
    if ($tmp && !$tmp->connect_error) { $conn = $tmp; break; }

    if ($tmp && $tmp->connect_error) {
        $err = $tmp->connect_error;
        $lastErr = $err;
        if (in_array($h, $localHosts, true) && (stripos($err, 'Unknown database') !== false || stripos($err, "doesn't exist") !== false)) {
            $init = null;
            try {
                $init = new mysqli($h, $user, $pass);
            } catch (Throwable $ex) {
                $lastErr = $ex->getMessage();
                $init = null;
            }
            if ($init && !$init->connect_error) {
                @$init->query("CREATE DATABASE IF NOT EXISTS `$DB_NAME` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $init->close();
                $tmp2 = null;
                try {
                    $tmp2 = new mysqli($h, $user, $pass, $DB_NAME);
                } catch (Throwable $ex) {
                    $lastErr = $ex->getMessage();
                    $tmp2 = null;
                }
                if ($tmp2 && !$tmp2->connect_error) { $conn = $tmp2; break; }
                if ($tmp2 && $tmp2->connect_error) { $lastErr = $tmp2->connect_error; }
            }
        }
    }
}

if (!$conn || $conn->connect_error) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database connection failed.', 'hint' => $lastErr]);
    exit;
}
$conn->set_charset('utf8mb4');
// Ensure MySQL session timezone is also Asia/Manila for CURRENT_TIMESTAMP and selects
@ $conn->query("SET time_zone = '+08:00'");
?>
