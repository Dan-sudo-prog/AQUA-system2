<?php

include 'components/connect.php';

// Define an array to store validation errors
$validation_errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   // Step 1: Validate Name/Organization
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   if (strlen($name) < 4 || strlen($name) > 20 || !preg_match("/^[a-zA-Z0-9_ -]+$/", $name)) {
      $validation_errors['name'] = 'Invalid name. Name must be between 3 and 20 characters and contain only letters, numbers, underscores, spaces, and hyphens.';
   }

   // Step 2: Validate Email
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $validation_errors['email'] = 'Invalid email address!';
   }

   // Step 3: Validate Phone Number
   $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
   if (empty($phone) || strlen($phone) !== 10 || substr($phone, 0, 1) !== '0') {
      $validation_errors['phone'] = 'Invalid phone number! It must start with "0" And should have 10 digits.';
   }

   // Step 4: Validate Password
   $pass = $_POST['pass'];
   if (empty($pass) || strlen($pass) < 5) {
      $validation_errors['pass'] = 'Password should be at least 5 characters long!';
   }

   // Step 5: Confirm Password
   $c_pass = $_POST['c_pass'];
   if ($pass !== $c_pass) {
      $validation_errors['c_pass'] = 'Confirm password does not match!';
   }

   // Step 6: Validate Location
   $location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
   if (!preg_match("/^[a-zA-Z ,]+$/", $location)) {
      $validation_errors['location'] = 'Invalid location format!';
   }

   // Step 7: Check for Image Size
   $image = $_FILES['profile_image']['name'];
   $image_size = $_FILES['profile_image']['size'];
   $image_tmp_name = $_FILES['profile_image']['tmp_name'];
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = create_unique_id() . '.' . $ext;
   $image_folder = 'uploaded_files/' . $rename;

   if (!empty($image)) {
      if ($image_size > 2000000) {
         $validation_errors['image'] = 'Image size is too large!';
      } else {
         move_uploaded_file($image_tmp_name, $image_folder);
      }
   } else {
      $rename = '';
   }

   // Step 8: If there are no validation errors and terms are checked, proceed with registration
   if (empty($validation_errors) && isset($_POST['accept_terms'])) {
      $id = create_unique_id();
      $pass = password_hash($pass, PASSWORD_DEFAULT);

      $verify_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $verify_email->execute([$email]);

      if ($verify_email->rowCount() > 0) {
         $validation_errors['email'] = 'Email already taken!';
      } else {
         $insert_user = $conn->prepare("INSERT INTO `users` (user_id, name, email, phone, password, profile_image, location) VALUES (?, ?, ?, ?, ?, ?, ?)");
         $insert_user->execute([$id, $name, $email, $phone, $pass, $rename, $location]);
         $success_msg[] = 'Registered successfully! Redirecting to Login page...';
         $delay = 5;
         $targetUrl = 'login.php';
         header("refresh:$delay;url=$targetUrl");
      }
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
      <!-- Display validation errors for each field -->
      <?php if (!empty($validation_errors)): ?>
      <div class="alert alert-danger">
          <ul>
              <?php foreach ($validation_errors as $field => $error): ?>
              <li><?php echo $error; ?></li>
              <?php endforeach; ?>
          </ul>
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
      <input type="checkbox" name="accept_terms" id="accept_terms"> I have read and accept the terms and conditions
      <p class="link">Already have an account? <a href="login.php">Login now</a></p>
      
      <input type="submit" value="register now" name="submit" id="submitBtn" class="btn">
      <script>
         document.addEventListener('DOMContentLoaded', function () {
            document.querySelector('form').addEventListener('submit', function (e) {
               if (!document.getElementById('accept_terms').checked) {
                  e.preventDefault(); // Prevent the form submission
                  Swal.fire({
                  icon: 'error',
                  title: 'Terms and Conditions',
                  text: 'Please accept the terms and conditions to proceed.',
                  });
               }
            });
         });
      </script>


   </form>
</section>

<!-- SweetAlert CDN link -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- Custom JavaScript file link -->
<script src="script.js"></script>

<?php include 'components/alerts.php'; ?>
</body>
</html>
