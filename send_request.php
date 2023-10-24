<?php
include "components/connect.php";
$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';

// Check if the user is logged in
    if (!empty($user_id)) {
        // Insert a request into the 'user_requests' table
        $insert_request = $conn->prepare("INSERT INTO user_requests (user_id) VALUES (?)");
        $stmt = $insert_request; // Corrected this line
        $stmt->bindParam(1, $user_id);
        if ($stmt->execute()) {
            echo "<p>Sent successfully";
        } else {
            echo 'An error occurred.';
        }
    } else {
        // User is not logged in, display a login message or redirect to the login page
        echo '<p>Please <a href="login.php">login</a> to send a request to be added as a farmer.</p>';
    }

?>
