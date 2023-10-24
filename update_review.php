<?php

include 'components/connect.php';

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:all_posts.php');
}

if(isset($_POST['submit'])){

   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);
   $recommendation = $_POST['recommendations'];
   $recommendation = filter_var($recommendation, FILTER_SANITIZE_STRING);
   $rating = $_POST['rating'];
   $rating = filter_var($rating, FILTER_SANITIZE_STRING);

   $update_review = $conn->prepare("UPDATE `reviews` SET rating = ?, review_title = ?, description = ?, recommendations = ? WHERE review_id = ?");
   $update_review->execute([$rating, $title, $description, $recommendation, $get_id]);

   $success_msg[] = 'Review updated!';
   $delay = 5;
   $targetUrl = 'view_post.php?get_id=' . $fetch_review['post_id'];
   header("refresh:$delay;url=$targetUrl");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update review</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="styles.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/header.php'; ?>
<!-- header section ends -->

<!-- update reviews section starts  -->

<section class="account-form">

   <?php
      $select_review = $conn->prepare("SELECT * FROM `reviews` WHERE review_id = ? LIMIT 1");
      $select_review->execute([$get_id]);
      if($select_review->rowCount() > 0){
         while($fetch_review = $select_review->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post">
      <h3>edit your review</h3>
      <p class="placeholder">Can you briefly tell us how was the product?<span>*</span></p>
      <input type="text" name="title" required maxlength="50" placeholder="enter review title" class="box" value="<?= $fetch_review['review_title']; ?>">
      <p class="placeholder">Describe your experience while using it...</p>
      <textarea name="description" class="box" placeholder="enter review description" maxlength="1000" cols="30" rows="10"><?= $fetch_review['description']; ?></textarea>
      <p class="placeholder">review rating <span>*</span></p>
      <select name="rating" class="box" required>
         <option value="<?= $fetch_review['rating']; ?>"><?= $fetch_review['rating']; ?></option>
         <option value="1">1</option>
         <option value="2">2</option>
         <option value="3">3</option>
         <option value="4">4</option>
         <option value="5">5</option>
      </select>
      <p class="placeholder">How can the product be made better?</p>
         <textarea name="recommendations" class="box" placeholder="enter product recommendation" maxlength="1000" cols="30" rows="10"></textarea>
      <input type="submit" value="update review" name="submit" class="btn">
      <a href="view_post.php?get_id=<?= $fetch_review['post_id']; ?>" class="option-btn">go back</a>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">something went wrong!</p>';
      }
   ?>

</section>

<!-- update reviews section ends -->














<!-- sweetalert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="script.js"></script>

<?php include 'components/alerts.php'; ?>

</body>
</html>