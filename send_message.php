<?php
include 'components/connect.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you have a user authentication mechanism (e.g., sessions), get the user's ID.
    $userId = $_SESSION['user_id'];
    $messageText = $_POST['message_text'];

    $insertQuery = "INSERT INTO messages (user_id, text) VALUES (:user_id, :message_text)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':message_text', $messageText);

    if ($stmt->execute()) {
        // Message sent successfully
        header('Location: users.php'); // Redirect to the user page
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}
