<?php

include 'components/connect.php';

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
      $success_msg[] = 'Username updated!';
   }

   if (!empty($phone)) {
      $update_phone = $conn->prepare("UPDATE `users` SET phone = ? WHERE user_id = ?");
      $update_phone->execute([$phone, $user_id]);
      $success_msg[] = 'Phone number updated!';
   }

   if (!empty($email)) {
      $verify_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $verify_email->execute([$email]);
      if ($verify_email->rowCount() > 0) {
         $warning_msg[] = 'Email already taken!';
      } else {
         $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE user_id = ?");
         $update_email->execute([$email, $user_id]);
         $success_msg[] = 'Email updated!';
      }
   }

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = create_unique_id() . '.' . $ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_files/' . $rename;

   if (!empty($image)) {
      if ($image_size > 2000000) {
         $warning_msg[] = 'Image size is too large!';
      } else {
         $update_image = $conn->prepare("UPDATE `users` SET profile_image = ? WHERE user_id = ?");
         $update_image->execute([$rename, $user_id]);
         move_uploaded_file($image_tmp_name, $image_folder);
         if ($fetch_user['profile_image'] != '') {
            unlink('uploaded_files/' . $fetch_user['profile_image']);
         }
         $success_msg[] = 'Image updated!';
      }
   }
}

if (isset($_POST['delete_image'])) {
   $select_old_pic = $conn->prepare("SELECT * FROM `users` WHERE user_id = ? LIMIT 1");
   $select_old_pic->execute([$user_id]);
   $fetch_old_pic = $select_old_pic->fetch(PDO::FETCH_ASSOC);

   if ($fetch_old_pic['image'] == '') {
      $warning_msg[] = 'Image already deleted!';
   } else {
      $update_old_pic = $conn->prepare("UPDATE `users` SET image = ? WHERE user_id = ?");
      $update_old_pic->execute(['', $user_id]);
      if ($fetch_old_pic['image'] != '') {
         unlink('uploaded_files/' . $fetch_old_pic['image']);
      }
      $success_msg[] = 'Image deleted!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update profile</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="styles.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/header.php'; ?>
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
      <?php if (!empty($fetch_profile['image'])) { ?>
         <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="" class="image">
         <input type="submit" value="delete image" name="delete_image" class="delete-btn" onclick="return confirm('delete this image?');">
      <?php }; ?>
      <p class="placeholder">profile pic</p>
      <input type="file" name="image" class="box" accept="image/*">
      <input type="submit" value="update now" name="submit" class="btn">
   </form>
</section>

<!-- update section ends -->

<!-- sweetalert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="script.js"></script>

<?php include 'components/alerts.php'; ?>

</body>
</html>
