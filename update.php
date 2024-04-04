<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update profile</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="./css/cdnjs/sweetalert2.min.css">
   <link rel="stylesheet" type="text/css" href="./css/fontawesome-free-6.5.1-web/css/all.min.css">
   <link rel="stylesheet" href="./css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php
 include 'components/connect.php';
 include 'components/header.php'; ?>
<?php
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
if (isset($_POST['submit'])) {
   $select_user = $conn->prepare("SELECT * FROM `users` WHERE user_id = ? LIMIT 1");
   $select_user->execute([$user_id]);
   $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);

   if (!empty($name)) {
      $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE user_id = ?");
      $update_name->execute([$name, $user_id]);
      $success_msg['name'] = 'Username updated!';
      header('location: update.php');
      exit;
   }

   if (!empty($phone)) {
      $update_phone = $conn->prepare("UPDATE `users` SET phone = ? WHERE user_id = ?");
      $update_phone->execute([$phone, $user_id]);
      $success_msg['phone'] = 'Phone number updated!';
      header('location: update.php');
      exit;
   }

   if (!empty($email)) {
      $verify_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $verify_email->execute([$email]);
      if ($verify_email->rowCount() > 0) {
         $warning_msg['email'] = 'Email already taken!';
      } else {
         $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE user_id = ?");
         $update_email->execute([$email, $user_id]);
         $success_msg['email'] = 'Email updated!';
         header('location: update.php');
         exit;
      }
   }

   $profile_image = $_FILES['profile_image']['name'];
   $ext = pathinfo($profile_image, PATHINFO_EXTENSION);
   $rename = create_unique_id() . '.' . $ext;
   $image_size = $_FILES['profile_image']['size'];
   $image_tmp_name = $_FILES['profile_image']['tmp_name'];
   $image_folder = 'uploaded_files/' . $rename;

   if (!empty($profile_image)) {
      if ($image_size > 2000000) {
         $warning_msg['profile_image'] = 'Image size is too large!';
      } else {
         $update_image = $conn->prepare("UPDATE `users` SET profile_image = ? WHERE user_id = ?");
         $update_image->execute([$rename, $user_id]);
         move_uploaded_file($image_tmp_name, $image_folder);
         if ($fetch_user['profile_image'] != '') {
            unlink('uploaded_files/' . $fetch_user['profile_image']);
         }
         $success_msg['profile_image'] = 'Image updated!';
         header('location: update.php');
         exit;
      }
   }
}

if (isset($_POST['delete_image'])) {
   $select_old_pic = $conn->prepare("SELECT * FROM `users` WHERE user_id = ? LIMIT 1");
   $select_old_pic->execute([$user_id]);
   $fetch_old_pic = $select_old_pic->fetch(PDO::FETCH_ASSOC);

   if ($fetch_old_pic['profile_image'] == '') {
      $warning_msg['profile_image'] = 'Image already deleted!';
   } else {
      $update_old_pic = $conn->prepare("UPDATE `users` SET profile_image = ? WHERE user_id = ?");
      $update_old_pic->execute(['', $user_id]);
      if ($fetch_old_pic['profile_image'] != '') {
         unlink('uploaded_files/' . $fetch_old_pic['profile_image']);
      }
      $success_msg['delete_image'] = 'Image deleted!';
      // $delay = 2;
      // $targetURL = 'update.php';
      // header("refresh:$delay;url=$targetURL");
      header('location: update.php');
      exit;
   }
}

?>

<!-- header section ends -->

<!-- update section starts  -->

<section class="account-form">
   <form action="" method="post" enctype="multipart/form-data">
      <h3>update your profile!</h3>
      <p class="placeholder">your name</p>
      <input type="text" name="name" maxlength="50" placeholder="<?= $fetch_profile['name']; ?>" class="box">
      <p class="placeholder">your email</p>
      <input type="email" name="email" maxlength="50" placeholder="<?= $fetch_profile['email']; ?>" class="box">
      <p class="placeholder">Your Phone number</p>
      <input type="number" name="phone" maxlength="50" placeholder="<?= $fetch_profile['phone']; ?>" class="box">
      <?php if (!empty($fetch_profile['profile_image'])) { ?>
         <img src="uploaded_files/<?= $fetch_profile['profile_image']; ?>" alt="" class="image">
         <input type="submit" value="Delete image" name="delete_image" class="delete-btn" onclick="return confirm('Delete this image?');">
      <?php } else { ?>
      <p class="placeholder">profile pic</p>
      <input type="file" name="profile_image" class="box" accept="image/*">
   <?php }; ?>
   <input type="submit" value="update now" name="submit" class="btn">
   </form>
</section>

<!-- update section ends -->

<!-- sweetalert cdn link  -->

<script src="./js/cdnjs/sweetalert2.all.min.js"></script>
<?php include './components/alerts.php'; ?>

<!-- custom js file link  -->
<script src="./js/script.js"></script>



</body>
</html>
