<?php
ini_set("SMTP", "smtp.gmail.com");
ini_set("smtp_port", "465");
ini_set("sendmail_from", "reubenmuhind@gmail.com");

$to = 'mrreuben72@gmail.com';
$subject = 'Test Email';
$message = 'This is a test email sent from PHP.';
$headers = 'From: reubenmuhind@gmail.com' . "\r\n" .
    'Reply-To: reubenmuhind@gmail.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

if (mail($to, $subject, $message, $headers)) {
    echo 'Email sent successfully';
} else {
    echo 'Email sending failed';
}

// Rest of your code
?>
