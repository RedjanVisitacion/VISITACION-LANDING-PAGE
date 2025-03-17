<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize & Validate Input
    $name = trim($_POST["name"]);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Check for empty fields
    if (empty($name) || empty($email) || empty($message)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.history.back();</script>";
        exit;
    }

    // Email Setup
    $to = "visitacionredjanphils@gmail.com";
    $subject = "New Contact Message from $name";
    $body = "Name: " . htmlspecialchars($name) . "\n";
    $body .= "Email: " . htmlspecialchars($email) . "\n\n";
    $body .= "Message:\n" . htmlspecialchars($message);

    $headers = "From: noreply@yourdomain.com\r\n"; // Use a domain email if possible
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Send Email
    if (mail($to, $subject, $body, $headers)) {
        echo "<script>alert('Your message has been sent successfully! Redirecting...'); 
              setTimeout(function() { window.location.href = 'index.html'; }, 2000); 
              </script>";
    } else {
        echo "<script>alert('Message sending failed. Please try again later.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
}
?>
