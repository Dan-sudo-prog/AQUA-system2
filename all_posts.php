<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>all posts</title>
   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="styles.css">

   <?php
include 'components/connect.php';

// Check if the user is logged in and retrieve the id
$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';

?>

   <!-- Custom CSS file link -->

</head>
<body>
   
<!-- Header section starts  -->
<?php include 'components/header.php'; ?>
<!-- Header section ends -->
<!-- Category section starts here -->
<section class="all-posts">
   <div class="heading"><h1>Categories</h1></div>
   <div class="box-container">
      <div class="box">
         <img src="images/grain.jpeg" class="image">
         <h3 class="title"><a href="search_results.php?query=Legumes &amp; Grain foods">Legumes &amp; Grain foods</a></h3>
      </div>
      <div class="box">
         <img src="images/legumes.png" class="image">
         <h3 class="title"><a href="search_results.php?query=Vegetables">Vegetables</a></h3>
      </div>
      <div class="box">
         <img src="images/diary.png" class="image">
         <h3 class="title"><a href="search_results.php?query=Poultry">Poultry Products</a></h3>
      </div>
      <div class="box">
         <img src="images/foods.png" class="image">
         <h3 class="title"><a href="search_results.php?query=Dairy Products">Dairy Products</a></h3>
      </div>
      <div class="box">
         <img src="images/fruits.png" class="image">
         <h3 class="title"><a href="search_results.php?query=Fruits">Fruits</a></h3>
      </div>
      <div class="box">
         <img src="images/meat.png" class="image">
         <h3 class="title"><a href="search_results.php?query=Meat">Meat</a></h3>
      </div>
      <div class="box">
         <img src="images/foods.png" class="image">
         <h3 class="title"><a href="search_results.php?query=Fresh Foods">Fresh Foods</a></h3>
      </div>
      <div class="box">
         <img src="images/foods.png" class="image">
         <h3 class="title"><a href="search_results.php?query=Animals">Animals</a></h3>
      </div>
      <!-- Add more category boxes here -->
   </div>
</section>
<!-- Category section ends here -->

<!-- View all posts section starts  -->
<section class="all-posts">
   <div class="heading"><h1>Featured Products</h1></div>
   <div class="box-container">
      <?php
      // Define the number of posts per page and get the current page from the query string
$postsPerPage = 9;
if (isset($_GET['page'])) {
    $currentPage = intval($_GET['page']);
} else {
    $currentPage = 1; // Default to the first page
}

// Calculate the offset for the SQL query
$offset = ($currentPage - 1) * $postsPerPage;

// Query to select posts with pagination
$select_posts = $conn->prepare("SELECT * FROM `posts` LIMIT :limit OFFSET :offset");
$select_posts->bindParam(':limit', $postsPerPage, PDO::PARAM_INT);
$select_posts->bindParam(':offset', $offset, PDO::PARAM_INT);
$select_posts->execute();

      if ($select_posts->rowCount() > 0) {
         while ($fetch_post = $select_posts->fetch(PDO::FETCH_ASSOC)) {
            $post_id = $fetch_post['post_id'];

            // Query to count reviews for the post
            $count_reviews = $conn->prepare("SELECT * FROM `reviews` WHERE post_id = ?");
            $count_reviews->execute([$post_id]);
            $total_reviews = $count_reviews->rowCount();
      ?>
            <div class="box">
   <?php
  $imagePath = "uploaded_img/" . $fetch_post['image'];
if (file_exists($imagePath)) {
    echo '<img src="' . $imagePath . '" alt="" class="image">';
} else {
    echo '<p>Image not found</p>';
}

   ?>
   <h3 class="title"><?= $fetch_post['title']; ?></h3>
   <p class="total-reviews"><i class="fas fa-star"></i> <span><?= $total_reviews; ?></span></p>
   <a href="view_post.php?get_id=<?= $post_id; ?>" class="inline-btn">view post</a>
</div>

      <?php
         }
      // Add "Next" button if there are more posts
    $nextPage = $currentPage + 1;
    echo '<a href="?page=' . $nextPage . '">Next</a>';
} else {
    echo '<p class="empty">No posts added yet!</p>';
}
?>
   </div>
</section>
<!-- View all posts section ends -->
<!-- <section>
   <h2>Send Message</h2>
<form method="post" action="send_message.php">
    <input type="text" name="sender_name" placeholder="Your name" required>
    <textarea name="message_text" placeholder="Type your message" required></textarea>
    <button type="submit">Send</button>
</form>
</section> -->

<?php include 'components/footer.php'; ?>

<!-- SweetAlert CDN link -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- Custom JavaScript file link -->
<script src="script.js"></script>

<?php include 'components/alerts.php'; ?>

</body>
</html>
