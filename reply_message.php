<?php
include 'components/connect.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userEmail = $_POST['user_email'];
    $adminReply = $_POST['admin_reply'];

    // Find the user by email or user ID
    $selectUserQuery = "SELECT user_id FROM users WHERE email = :email";
    $stmt = $conn->prepare($selectUserQuery);
    $stmt->bindParam(':email', $userEmail);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $userId = $row['user_id'];

        // Insert the admin reply into the messages table
        $insertReplyQuery = "INSERT INTO messages (user_id, message_text) VALUES (:user_id, :message_text)";
        $stmt = $conn->prepare($insertReplyQuery);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':message_text', $adminReply);

        if ($stmt->execute()) {
            // Reply sent successfully
            header('Location: users.php'); // Redirect to the user page
        } else {
            echo "Error: " . $stmt->errorInfo()[2];
        }
    } else {
        echo "User not found.";
    }
}
