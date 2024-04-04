<?php
include 'components/connect.php';

// Handle User Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addUser'])) {
        // Add a new user
        $id = create_unique_id();
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
        $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
        $pass = $_POST['pass']; // Don't sanitize before hashing
        $c_pass = $_POST['c_pass']; // Don't sanitize before comparison

        // Validate uploaded image
        $image = $_FILES['profile_image']['name'];
        $image_size = $_FILES['profile_image']['size'];
        $image_tmp_name = $_FILES['profile_image']['tmp_name'];
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = create_unique_id() . '.' . $ext;
        $image_folder = 'uploaded_files/' . $rename;

        // Add client-side form validation
        if (!empty($image)) {
            if ($image_size > 2000000) {
                $warning_msg[] = 'Image size is too large!';
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
            }
        } else {
            $rename = '';
        }

        // Begin a transaction
        $conn->beginTransaction();

        try {
            // Hash the password after confirmation
            if ($pass === $c_pass) {
                $pass = password_hash($pass, PASSWORD_DEFAULT);

                // Check if the email is already registered
                $verify_email = $conn->prepare("SELECT * FROM `admins` UNION SELECT * FROM `users` WHERE email = ?");
                $verify_email->execute([$email]);

                if ($verify_email->rowCount() > 0) {
                    $warning_msg[] = 'Email already taken!';
                } else {
                    $insert_user = $conn->prepare("INSERT INTO `admins` (user_id, name, email, phone, password, profile_image) VALUES (?, ?, ?, ?, ?, ?)");
                    $insert_user->execute([$id, $name, $email, $phone, $pass, $rename]);
                    $success_msg[] = 'New Admin successfully Added!';
                }
            } else {
                $warning_msg[] = 'Confirm password does not match!';
            }

            // Commit the transaction
            $conn->commit();
        } catch (PDOException $e) {
            // An error occurred, rollback the transaction
            $conn->rollBack();

            // Log the error and display a user-friendly message
            error_log('Error: ' . $e->getMessage());
            $warning_msg[] = 'An error occurred. Please try again later.';
        }
    }

    if (isset($_POST['deleteUser'])) {
        // Delete a user
        $userId = $_POST['deleteUser'];

        // Begin a transaction
        $conn->beginTransaction();

        try {
            // Now, you can safely delete the user
            $deleteUserQuery = "DELETE FROM reviews WHERE post_id = :id; DELETE FROM posts WHERE post_id = :id; DELETE FROM users WHERE user_id = :id;";
            $stmt = $conn->prepare($deleteUserQuery);
            $stmt->bindParam(':id', $userId);

            if ($stmt->execute()) {
                $success_msg[] = "User deleted successfully.";
                // Refresh the page after 5 seconds (you can adjust the delay)
                header("refresh:5;url=users.php");
            } else {
                $warning_msg[] = "Error: " . $stmt->errorInfo()[2];
            }

            // Commit the transaction
            $conn->commit();
        } catch (PDOException $e) {
            // An error occurred, rollback the transaction
            $conn->rollBack();

            // Log the error and display a user-friendly message
            error_log('Error: ' . $e->getMessage());
            $warning_msg[] = 'An error occurred. Please try again later.';
        }
    }
}

// Fetch User Data
$selectQuery = "SELECT * FROM users;";
$result = $conn->query($selectQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <link rel="stylesheet" href="./css/cdnjs/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="style.css"> <!-- Add your custom styles -->
</head>
<body>
    <h1>User Management</h1>

    <!-- Add User Form -->
    <h2>Add User</h2>
    <form method="post" action="" enctype="multipart/form-data">
        <input type="text" name="name" required maxlength="50" placeholder="Enter name" class="box"><br>
        <input type="email" name="email" required maxlength="50" placeholder="Enter email" class="box"><br>
        <input type="text" name="phone" required maxlength="50" placeholder="Enter phone number" class="box"><br>
        <input type="password" name="pass" required maxlength="50" placeholder="Enter password" class="box"><br>
        <input type="password" name="c_pass" required maxlength="50" placeholder="Confirm password" class="box"><br>
        <p class="placeholder">profile pic</p>
        <input type="file" name="profile_image" class="box" accept="image/*">
        <button type="submit" name="addUser">Add User</button>
    </form>

    <!-- List of Users -->
    <h2>User List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="deleteUser" value="<?php echo $row['user_id']; ?>">
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- Received Messages -->
    <h2>Received Messages</h2>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Reply</th>
        </tr>
        <?php
        // Fetch and display messages for admins
        $selectQuery = "SELECT users.name, users.email, messages.message_text
                        FROM users
                        INNER JOIN messages ON users.user_id = messages.user_id";
        $result = $conn->query($selectQuery);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)):
        ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['message_text']; ?></td>
                <td>
                    <form method="post" action="reply_message.php">
                        <input type="hidden" name="user_email" value="<?php echo $row['email']; ?>">
                        <textarea name="admin_reply" placeholder="Type your reply" required></textarea><br>
                        <button type="submit">Reply</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

<script src="./js/cdnjs/sweetalert2.all.min.js"></script>
<?php include './components/alerts.php'; ?>
</body>
</html>

<?php
$conn = null;
?>
