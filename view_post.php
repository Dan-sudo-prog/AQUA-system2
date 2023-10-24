<?php

include 'components/connect.php';

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:all_posts.php');
}







if(isset($_POST['delete_review'])){

   $delete_id = $_POST['delete_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $verify_delete = $conn->prepare("SELECT * FROM `reviews` WHERE review_id = ?");
   $verify_delete->execute([$delete_id]);
   
   if($verify_delete->rowCount() > 0){
      $delete_review = $conn->prepare("DELETE FROM `reviews` WHERE review_id = ?");
      $delete_review->execute([$delete_id]);
      $success_msg[] = 'Review deleted!';
   }else{  
      $warning_msg[] = 'Review already deleted!';
   }

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>view post</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="styles.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/header.php'; ?>
<!-- header section ends -->

<!-- view posts section starts  -->

<section class="view-post">

   <div class="heading">
      <h1>post details</h1>
      <a href="all_posts.php" class="inline-option-btn" style="margin-top: 0;">all posts</a></div>





   <?php
   function getRatingCount($rating)
{
    global $conn, $get_id;
    $select_ratings_count = $conn->prepare("SELECT COUNT(*) as count FROM `reviews` WHERE post_id = ? AND rating = ?");
    $select_ratings_count->execute([$get_id, $rating]);
    $count = $select_ratings_count->fetch(PDO::FETCH_ASSOC);
    return $count['count'];
}
      $select_post = $conn->prepare("SELECT * FROM `posts` WHERE post_id = ? LIMIT 1");
      $select_post->execute([$get_id]);
      if($select_post->rowCount() > 0){
         while($fetch_post = $select_post->fetch(PDO::FETCH_ASSOC)){

        $total_ratings = 0;
        $rating_1 = 0;
        $rating_2 = 0;
        $rating_3 = 0;
        $rating_4 = 0;
        $rating_5 = 0;

        $select_ratings = $conn->prepare("SELECT * FROM `reviews` WHERE post_id = ?");
        



        $select_ratings->execute([$fetch_post['post_id']]);
        $total_reivews = $select_ratings->rowCount();
        while($fetch_rating = $select_ratings->fetch(PDO::FETCH_ASSOC)){
            $total_ratings += $fetch_rating['rating'];
            if($fetch_rating['rating'] == 1){
               $rating_1 += $fetch_rating['rating'];
               
            }
            if($fetch_rating['rating'] == 2){
               $rating_2 += $fetch_rating['rating'];
            }
            if($fetch_rating['rating'] == 3){
               $rating_3 += $fetch_rating['rating'];
            }
            if($fetch_rating['rating'] == 4){
               $rating_4 += $fetch_rating['rating'];
            }
            if($fetch_rating['rating'] == 5){
               $rating_5 += $fetch_rating['rating'];
            }
        }

        if($total_reivews != 0){
            $average = round($total_ratings / $total_reivews, 1);
        }else{
            $average = 0;
        }
        
   ?>
   








<?php

// Modify the SQL query to retrieve post and owner details using JOIN
$select_post = $conn->prepare("SELECT posts.*, users.name AS owner_name, users.profile_image AS owner_profile_pic
                               FROM posts
                               INNER JOIN users ON posts.user_id = users.user_id
                               WHERE posts.post_id = ?");
$select_post->execute([$get_id]);

if ($select_post->rowCount() > 0) {
    $fetch_post = $select_post->fetch(PDO::FETCH_ASSOC);
    // Extract owner details from the fetched data
    $owner_name = $fetch_post['owner_name'];
    $owner_profile_pic = $fetch_post['owner_profile_pic'];

    
    // Display the owner details along with the post
    ?>
    <div style="display: flex; flex-direction: row; align-content: center; align-items: center; float: right; margin-left: 10px;">
            <!-- Display owner's profile picture -->
            <?php if ($owner_profile_pic != '') { ?>
                <img src="uploaded_files/<?= $owner_profile_pic; ?>" alt="Owner's Profile Pic" style="width: 80px; height: 80px; border-radius: 100px; border: 1px solid black; margin-right: 10px;">
            <?php } else { ?>
                <!-- Display a default image or initial if no profile picture is available -->
                <div class="image" style="margin-right: 10px"><?= substr($owner_name, 0, 1); ?></div>
            <?php } ?>
                
            <div class="flex">
                <!-- Display owner's name and other details -->
                <div class="user-detail">
                    <h2><?= $owner_name; ?></h2>
                    <span style="font-size: 13px;">Owner</span>
                </div>
              <?php
// Check if the user is logged in
if (!empty($user_id)) {
    // User is logged in, show the link to view farmer details
    echo '<a href="user_details.php?get_id=<?=$get_id;?>" id="viewFarmerLink" style="margin-top: 0; font-size: 15px;">View About Farmer</a>';
} else {
    // User is not logged in, show the link with a disabled attribute
    echo '<a href="#" id="viewFarmerLink" style="margin-top: 0; font-size: 15px;" onclick="showLoginMessage(event); return false;">View About Farmer</a>';
}
?>
      
<!-- JavaScript to display a login message when the link is clicked -->
<script>
    function showLoginMessage(event) {
        event.preventDefault();
        alert('You need to be logged in to view farmer details.');
    }

    document.addEventListener("DOMContentLoaded", function() {
        const viewFarmerLink = document.getElementById('viewFarmerLink');

        // Add a click event listener to the link
        viewFarmerLink.addEventListener('click', function(event) {
            // Check if the user is logged in
            if (!<?php echo json_encode(!empty($user_id)); ?>) {
                // User is not logged in, prevent the default link behavior
                event.preventDefault();
            }
        });
    });
</script>



            </div>
             

    </div>
    <?php
} else {
    echo '<p class="empty">Post is missing!</p>';
}
?>








      <div class="row">
         <div class="col">
         <img src="uploaded_img/<?= $fetch_post['image']; ?>" alt="" class="image">
         <h3 class="title"><?= $fetch_post['title']; ?></h3>
         <a href="view_more.php?get_id=<?= $fetch_post['post_id']; ?>" class="inline-btn fas fa-eye">View Assessment Details</a>
      </div>
      <div class="col">
         <div class="flex">
            <div class="total-reviews">
               <h3><?= $average; ?><i class="fas fa-star"></i></h3>
               <p><?= $total_reivews; ?> reviews</p>
            </div>
            <div class="total-ratings">
               <p>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <span><?= getRatingCount(5); ?></span>
               </p>
               <p>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <span><?= getRatingCount(4); ?></span>
               </p>
               <p>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <span><?= getRatingCount(3); ?></span>
               </p>
               <p>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <span><?= getRatingCount(2); ?></span>
               </p>
               <p>
                  <i class="fas fa-star"></i>
                  <span><?= getRatingCount(1); ?></span>
               </p>
            </div>
         </div>

      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">post is missing!</p>';
      }
   ?>









</section>

<!-- view posts section ends -->

<!-- reviews section starts  -->

<section class="reviews-container">

   <div class="heading"><h1>user's reviews</h1> <a href="add_review.php?get_id=<?= $get_id; ?>" class="inline-btn" style="margin-top: 0;">add review</a></div>

   <div class="box-container">

   <?php
      $select_reviews = $conn->prepare("SELECT * FROM `reviews` WHERE post_id = ?");
      $select_reviews->execute([$get_id]);
      if($select_reviews->rowCount() > 0){
         while($fetch_review = $select_reviews->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box" <?php if($fetch_review['user_id'] == $user_id){echo 'style="order: -1;"';}; ?>>
      <?php
         $select_user = $conn->prepare("SELECT * FROM `users` WHERE user_id = ?");
         $select_user->execute([$fetch_review['user_id']]);
         while($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="user">
   <?php
   $imagePath = "uploaded_files/" . $fetch_user['profile_image'];
   if (file_exists($imagePath)) {
   ?>
      <img src="<?= $imagePath; ?>" alt="">
   <?php
   } else {
      echo '<h3>' . substr($fetch_user['name'], 0, 1) . '</h3>';
   }
   ?>   
   <div>
      <p><?= $fetch_user['name']; ?></p>
      <span><?= $fetch_review['date']; ?></span>
   </div>
</div>


      <?php }; ?>
      <div class="ratings">
         <?php if($fetch_review['rating'] == 1){ ?>
            <p style="background:var(--red);"><i class="fas fa-star"></i> <span><?= $fetch_review['rating']; ?></span></p>
         <?php }; ?> 
         <?php if($fetch_review['rating'] == 2){ ?>
            <p style="background:var(--orange);"><i class="fas fa-star"></i> <span><?= $fetch_review['rating']; ?></span></p>
         <?php }; ?>
         <?php if($fetch_review['rating'] == 3){ ?>
            <p style="background:var(--orange);"><i class="fas fa-star"></i> <span><?= $fetch_review['rating']; ?></span></p>
         <?php }; ?>   
         <?php if($fetch_review['rating'] == 4){ ?>
            <p style="background:var(--main-color);"><i class="fas fa-star"></i> <span><?= $fetch_review['rating']; ?></span></p>
         <?php }; ?>
         <?php if($fetch_review['rating'] == 5){ ?>
            <p style="background:var(--main-color);"><i class="fas fa-star"></i> <span><?= $fetch_review['rating']; ?></span></p>
         <?php }; ?>
      </div>
      <h3 class="title"><?= $fetch_review['review_title']; ?></h3>
      <?php if($fetch_review['description'] != ''){ ?>
         <p class="description"><?= $fetch_review['description']; ?></p>
      <?php }; ?>  
      <?php if($fetch_review['user_id'] == $user_id){ ?>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="delete_id" value="<?= $fetch_review['review_id']; ?>">
            <a href="update_review.php?get_id=<?= $fetch_review['review_id']; ?>" class="inline-option-btn">edit review</a>
            <input type="submit" value="delete review" class="inline-delete-btn" name="delete_review" onclick="return confirm('delete this review?');">
         </form>
      <?php }; ?>   
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no reviews added yet!</p>';
      }
   ?>

   </div>
 
</section>

<!-- reviews section ends -->













<!-- sweetalert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="script.js"></script>

<?php include 'components/alerts.php'; ?>

</body>
</html>