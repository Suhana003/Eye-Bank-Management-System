<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Email details
    $to = "your-email@example.com"; // Replace with your email address
    $headers = "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Sending email
    if (mail($to, $subject, $message, $headers)) {
        echo "<p>Message sent successfully!</p>";
    } else {
        echo "<p>Failed to send message. Please try again later.</p>";
    }
}
?>
<a href="contact.html">Back to Contact Page</a>
