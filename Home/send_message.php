<?php
include '../components/connect.php'; // Database connection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you have a user authentication mechanism (e.g., sessions), get the user's ID.
    $senderName = $_POST['sender_name'];
    $messageText = $_POST['message_text'];

    $insertQuery = "INSERT INTO messages (name, message_text) VALUES (:name, :message_text)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bindParam(':name', $senderName);
    $stmt->bindParam(':message_text', $messageText);

    if ($stmt->execute()) {
        // Message sent successfully
        header('Location: users.php'); // Redirect to the user page
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}
