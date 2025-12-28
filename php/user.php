<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: ../html/Login.html'); exit; }
$username = htmlspecialchars($_SESSION['username'] ?? '', ENT_QUOTES, 'UTF-8');
$role = htmlspecialchars($_SESSION['role'] ?? 'user', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <div class="wrapper">
    <h1>User Dashboard</h1>
    <p>Welcome, <?php echo $username; ?>.</p>
    <p>Your role: <?php echo $role; ?></p>
    <div class="register-link">
      <?php if ($role === 'admin'): ?>
        <p><a href="admin.php">Go to Admin Dashboard</a></p>
      <?php endif; ?>
      <p><a href="logout.php">Logout</a></p>
    </div>
  </div>
</body>
</html>
