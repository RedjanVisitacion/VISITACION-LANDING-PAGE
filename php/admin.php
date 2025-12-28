<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: ../html/Login.html'); exit; }
if (($_SESSION['role'] ?? '') !== 'admin') { header('Location: user.php'); exit; }
$username = htmlspecialchars($_SESSION['username'] ?? '', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <div class="wrapper">
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?php echo $username; ?>.</p>
    <div class="register-link">
      <p><a href="user.php">Go to User Dashboard</a></p>
      <p><a href="logout.php">Logout</a></p>
    </div>
  </div>
</body>
</html>
