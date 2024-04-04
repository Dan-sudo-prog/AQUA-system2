<?php session_start();
include 'components/connect.php';
if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// User input (email, phone, or name)
    $login_input = filter_input(INPUT_POST, 'login_input', FILTER_SANITIZE_STRING);

    $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
    
    // Define a variable to hold the database column name (email, phone, or name)
    $column_name = '';

    // Check if the user input is a valid email
    if (filter_var($login_input, FILTER_VALIDATE_EMAIL)) {
        $column_name = 'email';
    }
    // Check if the user input is a valid phone number (there may be need to customize this validation)
    elseif (preg_match("/^[0-9]{10}$/", $login_input)) {
        $column_name = 'phone';
    } else {
        // User input is treated as a name if it's not an email or phone
        $column_name = 'name';
    }

	try {
        // Query to retrieve user by the selected column
        $verify_user = $conn->prepare("SELECT * FROM `users` WHERE $column_name = ? LIMIT 1;");
        $verify_user->execute([$login_input]);

        // Check if the query returned any results
        if ($verify_user->rowCount() > 0) {
            // Fetch the user data
            $fetch = $verify_user->fetch(PDO::FETCH_ASSOC);
            $hashed_password = $fetch['password']; // Get hashed password from the database

            // Verify password and set session variables
            if (password_verify($pass, $hashed_password)) {
                //Authentication successful, set session variables
                $_SESSION['user_id'] = $fetch['user_id'];
                header("Location: all_posts.php");
                exit;
            } else {
                $error_msg[] = 'Incorrect password!';
            }
        } else {
            // No user found with the provided input
            $info_msg[] = 'User not found!';
        }
    } catch (PDOException $e) {
        echo "Login failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/cdnjs/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="./css/fontawesome-free-6.5.1-web/css/all.min.css">
    
</head>

<body>

    <!-- Login section starts -->
    <section class="account-form">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <h3>Welcome back!</h3>
            <p class="placeholder">Enter your email, phone, or name <span>*</span></p>
            <input type="text" name="login_input" required maxlength="50" placeholder="Enter your email, phone, or name"
                class="box">
            <p class="placeholder">Your password <span>*</span></p>
            <input type="password" name="pass" required maxlength="50" placeholder="Enter your password" class="box">
            <p class="link">Don't have an account? <a href="register.php">Register now</a></p>
            <button type="submit" name="submit" class="btn" id="showAlert">Login now</button>
            <p class="link"><a href="index.php">Back to Home</a></p>
        </form>
    </section>
    <!-- Login section ends -->

    <!-- SweetAlert CDN link -->
    <script src="./js/cdnjs/sweetalert2.all.min.js"></script>
    <?php include 'components/alerts.php'; ?>
    <!-- <script src="./js/alert.js"></script> -->
    <!-- Custom JS file link -->
    <script src="./js/script.js"></script>

    

</body>

</html>