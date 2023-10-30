<?php

include 'components/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

   $location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);

   // Add client-side form validation if needed

   if (!empty($image)) {
      if ($image_size > 2000000) {
         $warning_msg[] = 'Image size is too large!';
      } else {
         move_uploaded_file($image_tmp_name, $image_folder);
      }
   } else {
      $rename = '';
   }

   //validate username
   if (strlen($name) < 4 || strlen($name) > 20 || !preg_match("/^[a-zA-Z0-9_ -]+$/", $name)) {
      echo "Invalid name. Name must be between 3 and 20 characters and contain only letters, numbers, underscores,spaces and hyphens.";
   }
   
   //validate phone number
   if (empty($phone) || strlen($phone) < 10 || substr($phone, 0, 1) !== '0') {
    $warning_msg[] = 'Invalid phone number! It must start with "0".';
   }


   //validate password
   if (empty($pass) || strlen($pass) < 5) {
      $warning_msg[] = 'Password should be at least 5 characters long!';
   }
   
   // Validate location 
   if (!preg_match("/^[a-zA-Z ,]+$/", $location)) {
      $warning_msg[] = 'Invalid location format!';
   }

   if ($pass === $c_pass) {
      // Hash the password after confirmation
      $pass = password_hash($pass, PASSWORD_DEFAULT);

      // Check if the email is already registered
      $verify_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $verify_email->execute([$email]);

      if ($verify_email->rowCount() > 0) {
         $warning_msg[] = 'Email already taken!';
      } else {
         $insert_user = $conn->prepare("INSERT INTO `users` (user_id, name, email, phone, password, profile_image, location) VALUES (?, ?, ?, ?, ?, ?, ?)");
         $insert_user->execute([$id, $name, $email, $phone, $pass, $rename, $location]);
         $success_msg[] = 'Registered successfully! You will be redirected in 5 seconds';
         $delay = 5;
         $targetUrl = 'login.php';
         header("refresh:$delay;url=$targetUrl");
      }
   } else {
      $warning_msg[] = 'Confirm password does not match!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>
   <!-- SweetAlert2 CDN links -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="styles.css">
</head>
<body>
   
<!-- Header section starts -->
<?php include 'components/header.php'; ?>
<!-- Header section ends -->

<section class="account-form">
   <form action="" method="post" enctype="multipart/form-data">
      <h3>Create an account!</h3>
      <!-- Display error messages if there are any -->
      <?php if (!empty($warning_msg)): ?>
      <div class="alert alert-danger">
          <ul>
              <?php foreach ($warning_msg as $warning): ?>
              <li><?php echo $warning; ?></li>
              <?php endforeach; ?>
          </ul>
      </div>
      <?php endif; ?>

      <!-- Display success message if registration is successful -->
      <?php if (!empty($success_msg)): ?>
      <div class="alert alert-success">
          <?php echo $success_msg; ?>
      </div>
      <?php endif; ?>
      <p class="placeholder">Enter your name/Organisation <span>*</span></p>
      <input type="text" name="name" required maxlength="50" placeholder="enter your name" class="box">
      <p class="placeholder">Enter email address<span></span></p>
      <input type="email" name="email" maxlength="50" placeholder="enter your email" class="box">
      <p class="placeholder">Enter phone number<span>*</span></p>
      <input type="number" name="phone" required maxlength="50" placeholder="enter your phone number" class="box">
      <p class="placeholder">Enter a password<span>*</span></p>
      <input type="password" name="pass" required maxlength="50" placeholder="enter your password" class="box">
      <p class="placeholder">Confirm password <span>*</span></p>
      <input type="password" name="c_pass" required maxlength="50" placeholder="confirm your password" class="box">
      <p class="placeholder">Profile pic</p>
      <input type="file" name="profile_image" class="box" accept="image/*">
      <p class="placeholder">Enter your location<span>*</span></p>
      <input type="location" name="location" required maxlength="50" placeholder="enter your location" class="box">
      <input type="checkbox" checked name="accept_terms" id="accept_terms"> I have read and accept the terms and conditions
      <p class="link">Already have an account? <a href="login.php">Login now</a></p>
      <input type="submit" value="register now" name="submit" class="btn">
      
   </form>
</section>
<!-- SweetAlert CDN link -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- Custom JavaScript file link -->
<script src="script.js"></script>

<?php include 'components/alerts.php'; ?>


</body>
</html>
