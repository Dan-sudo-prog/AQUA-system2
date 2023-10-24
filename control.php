<?php
include 'components/connect.php';

try{
    $userCountQuery = "SELECT COUNT(*) AS totalUsers FROM users;";
    $userCountResult = $conn->query($userCountQuery);
    $totalUsers = $userCountResult->fetch(PDO::FETCH_ASSOC)['totalUsers'];

    $postCountQuery = "SELECT COUNT(*) AS totalPosts FROM posts;";
    $postCountResult = $conn->query($postCountQuery);
    $totalPosts = $postCountResult->fetch(PDO::FETCH_ASSOC)['totalPosts'];
} catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin</title>
    <style type="text/css">
        th, td {
            padding: 5px 10px;
            text-align: left;
        }
    </style>
</head>
<body>
<h1>Admin Dashboard</h1>
<p>Total users: <?php echo $totalUsers; ?></p>
<p>Total posts: <?php echo $totalPosts; ?></p>

<!-- navigate to users page -->
<a href="users.php"><button>See Users</button></a>
</body>
</html>








<?php

// Sample admin page to manage user requests
if (isset($_GET['action']) && isset($_GET['request_id'])) {
    $action = $_GET['action'];
    $request_id = $_GET['request_id'];

   

    // Process the request based on the action (approve or reject)
    if ($action === 'approve') {
        // Update the request status to 'approved'
        $update_status = $conn->prepare("UPDATE user_requests SET status = 'approved' WHERE request_id = ?");
        $update_status->execute([$request_id]);

        // Redirect back to the admin page
        header('Location: control.php');
        exit;
    }

    
}

// Fetch and display user requests
$select_requests = $conn->prepare("SELECT user_requests.*, users.name, users.email, users.location FROM user_requests INNER JOIN users ON user_requests.user_id = users.user_id ORDER BY request_date DESC");
$select_requests->execute();

// Display user requests in a table
echo '<h1>Admin Page - Manage User Requests</h1>';
echo '<table>';
echo '<tr><th>Request ID</th><th>User Name</th><th>Email address</th><th>Location</th><th>Request Date</th><th>Status</th></tr>';

while ($row = $select_requests->fetch(PDO::FETCH_ASSOC)) {
    $request_id = $row['request_id'];
    $user_name = $row['name'];
    $user_email = $row['email'];
    $user_location = $row['location'];
    $request_date = $row['request_date'];
    $status = $row['status'];

    echo '<tr>';
    echo '<td>' . $request_id . '</td>';
    echo '<td>' . $user_name . '</td>';
    echo '<td>' . $user_email . '</td>';
    echo '<td>' . $user_location . '</td>';
    echo '<td>' . $request_date . '</td>';
    echo '<td>' . $status . '</td>';
    
echo '<td>';
// Check if the status is "approved" and display accordingly
if ($status === 'approved') {
    echo 'Approved';
} elseif ($status === 'rejected') {
    echo 'Rejected';
} else {
    // Display "Approve" and "Reject" links if the status is not approved or rejected
    echo '<a href="control.php?action=approve&request_id=' . $request_id . '">Approve</a> | ';
    echo '<a href="control.php?action=reject&request_id=' . $request_id . '">Reject</a>';
}

echo '</td>';
echo '</tr>';


}

echo '</table>';
?>
