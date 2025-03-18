<?php
// Get input values safely (with fallback for missing data)
$name = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$message_text = isset($_POST['message']) ? $_POST['message'] : '';

// Database Connection (PostgreSQL)
$conn = pg_connect("host=localhost port=5432 dbname=your_dbname user=your_username password=your_password");

// Check connection
if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Insert Query (Use placeholders for security)
$query = "INSERT INTO message (email, name, message_text) VALUES ($1, $2, $3)";
$result = pg_query_params($conn, $query, array($email, $name, $message_text));

if ($result) {
    echo "Submitted Successfully...";
} else {
    echo "Error: " . pg_last_error($conn);
}

// Close connection
pg_close($conn);
?>
