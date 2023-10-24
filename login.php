<?php
include 'components/connect.php';

if (isset($_POST['submit'])) {
    $login_input = $_POST['login_input']; // User input (email, phone, or name)
    $login_input = filter_var($login_input, FILTER_SANITIZE_STRING);
    $pass = $_POST['pass'];
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    // Define a variable to hold the database column name (email, phone, or name)
    $column_name = '';

    // Check if the user input is a valid email
    if (filter_var($login_input, FILTER_VALIDATE_EMAIL)) {
        $column_name = 'email';
    }
    // Check if the user input is a valid phone number (you may need to customize this validation)
    elseif (preg_match("/^[0-9]{10}$/", $login_input)) {
        $column_name = 'phone';
    } else {
        // User input is treated as a name if it's not an email or phone
        $column_name = 'name';
    }

    // Query to retrieve user by the selected column
    $verify_user = $conn->prepare("SELECT * FROM `users` WHERE $column_name = ? LIMIT 1");
    $verify_user->execute([$login_input]);

    if ($verify_user->rowCount() > 0) {
        $fetch = $verify_user->fetch(PDO::FETCH_ASSOC);
        $hashed_password = $fetch['password']; // Get hashed password from the database
        if (password_verify($pass, $hashed_password)) {
            setcookie('user_id', $fetch['user_id'], time() + 60 * 60 * 24 * 30, '/');
            header('location: all_posts.php');
            exit; // Important to exit after redirect
        } else {
            $warning_msg[] = 'Incorrect password!';
        }
    } else {
        $warning_msg[] = 'User not found!';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="styles.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/header.php'; ?>
<!-- header section ends -->

<!-- login section starts  -->
<section class="account-form">
   <form action="" method="post" enctype="multipart/form-data">
      <h3>welcome back!</h3>
      <p class="placeholder">Enter your email, phone, or name <span>*</span></p>
      <input type="text" name="login_input" required maxlength="50" placeholder="Enter your email, phone, or name" class="box">
      <p class="placeholder">your password <span>*</span></p>
      <input type="password" name="pass" required maxlength="50" placeholder="enter your password" class="box">
      <p class="link">don't have an account? <a href="register.php">register now</a></p>
      <input type="submit" value="login now" name="submit" class="btn">
   </form>
</section>
<!-- login section ends -->

<!-- sweetalert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="script.js"></script>

<?php include 'components/alerts.php'; ?>

</body>
</html>
