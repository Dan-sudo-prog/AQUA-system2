<?php include 'components/connect.php';?>
<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Home</title>
   <link rel="stylesheet" type="text/css" href="./css/fontawesome-free-6.5.1-web/css/all.min.css">
   <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include 'components/header.php'; ?>
<div id="container">
   <?php
      $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
   ?>
<div id="body">
<section class="welcome">
   <div class="container">
      <h1 style="color: darkolivegreen; font-weight: bolder;">Welcome to Agricultural Products <br/> Quality Assessment System</h1>
      <h3 style="color: darkolivegreen; font-weight: bolder;">Discover and Review the Quality of Agricultural Products</h3>
   </div>
</section>

<section class="all-posts">
   <div class="heading"><h1 style="">Categories</h1></div>
   <div class="box-container">
      <div class="box">
         <img src="images/legumes.png" class="image">
         <h3 class="title"><a href="search_results.php?query=Legumes">Legumes</a></h3>
      </div>
      <div class="box">
         <img src="images/grain.jpeg" class="image">
         <h3 class="title"><a href="search_results.php?query=Grain Foods">Grain Foods</a></h3>
      </div>
      <div class="box">
         <img src="images/vegetables.png" class="image">
         <h3 class="title"><a href="search_results.php?query=Vegetables">Vegetables</a></h3>
      </div>
      <div class="box">
         <img src="images/dairy.png" class="image">
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
         <img src="images/OIP.jpeg" class="image">
         <h3 class="title"><a href="search_results.php?query=Animals">Animals</a></h3>
      </div>
      <div class="box">
         <img src="images/birds.png" class="image">
         <h3 class="title"><a href="search_results.php?query=Birds">Birds</a></h3>
      </div>
      <!-- Add more category boxes here -->
   </div>
</section>

<!-- View all posts section starts  -->
<section class="all-posts" style="background-color: lightblue;">
            <div class="heading">
                <h1 style="">Featured Products</h1>
            </div>
            <div class="box-container">
                <?php
      // Define the number of posts per page and get the current page from the query string
$postsPerPage = 12;
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
  $imagePath = "uploaded_img/" . $fetch_post['product_image'];
if (file_exists($imagePath)) {
    echo '<img src="' . $imagePath . '" alt="" class="image">';
} else {
    echo '<p>Image not found</p>';
}

   ?>
   <h3 class="title"><?= $fetch_post['product_name']; ?></h3>
   <p class="total-reviews"><i class="fas fa-star"></i> <span><?= $total_reviews; ?></span></p>
   <a href="view_post.php?get_id=<?= $post_id; ?>" class="inline-btn">view post</a>
</div>

      <?php
         }
            // Add "Next" button if there are more posts
            $nextPage = $currentPage + 1;
            echo '<a class="nextPage" href="?page=' . $nextPage . '">Next</a>';
            if($currentPage > 1) {
               $prevPage = $currentPage - 1;
               echo '<a class="prevPage" href="?page=' . $prevPage . '">Previous</a>';
            }
         } else {
            echo '<p class="empty">No posts</p>';
            if($currentPage > 1) {
               $prevPage = $currentPage - 1;
               echo '<a class="prevPage" href="?page=' . $prevPage . '">Previous</a>';
            }
         }
      ?>
            </div>
        </section>

<!-- View all posts section ends -->
<section class="all-posts" style="background-color: cyan;">
   <div class="heading">
      <h1 style="">How it Works</h1>
   </div>
   <div class="slideshow-container">
    
      <div class="mySlides" style="background-image: url('images/images5.jpeg');">
         <div class="slide-content">
            <!-- Content for Slide 1 -->
            <p>Farmers can list their products on our platform.</p>
         </div>
      </div>

      <div class="mySlides"  style="background-image: url('images/images10.jpeg');">
         <div class="slide-content">
            <!-- Content for Slide 2 -->
            <p>Users can browse products, rate them, and write reviews.</p>
         </div>
      </div>
      <div class="mySlides" style="background-image: url('images/images6.jpeg');">
         <div class="slide-content">
            <!-- Content for Slide 2 -->
            <p>Make informed decisions for your farming needs.</p>
         </div>
      </div>
      <div class="mySlides" style="background-image: url('images/images3.jpeg');">
         <div class="slide-content">
            <!-- Content for Slide 2 -->
            <p>Take part in quality assessment for your preferences</p>
         </div>
      </div>
      <button class="prev" onclick="plusSlides(-1)"><i class="fas fa-chevron-left"></i></button>
      <button class="next" onclick="plusSlides(1)"><i class="fas fa-chevron-right"></i></button>
   </div>
   <script>
    let slideIndex = 0;
    showSlides();
    
    function plusSlides(n) {
    showSlides(slideIndex += n);
    }
    
    function showSlides() {
    let i;
    const slides = document.getElementsByClassName("mySlides");
    
    if (slideIndex >= slides.length) {
    slideIndex = 0;
    }
    if (slideIndex < 0) {
    slideIndex = slides.length - 1;
    }
    
    for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
    }
    
    slides[slideIndex].style.display = "block";
    
    // Automatically advance slides every 3 seconds (adjust the time interval as needed)
    setTimeout(function() {
    plusSlides(1);
    }, 3000);
    }
    
   </script>
</section>

<section class="all-posts" style="background-color: lightgrey;">
   <div class="heading"><h1>What our Users Say</h1></div>
      <div class="">
      </div>
      <div class="r5">
         <p>Know what others say about us</p>
         <p>Help us improve by writing a testimonial to us if you have not yet done so.</p>
      </div>

      <div class="testimonial">
         <button onclick="openPopup();">Write a testimonial</button>
         <a href="testimonials.php">View testimonials</a>
      </div>
      <div id="popup" class="popup">
         <h2>Write a Testimonial</h2>
         <form method="post" action="submit_testimonial.php" id="testimonial-form">
            <label for="name">Name:</label>
               <input type="text" name="user_name" placeholder="Write your name..." required>
            <label for="phone">Phone Number:</label>
               <input type="text" name="phone" placeholder="Enter your phone number..." required>
            <label for="location">Location</label>
               <input type="text" name="location" placeholder="Enter your location here..." required>
            <label for="testimonial">Testimonial</label>
               <textarea name="testimonial" placeholder="Write your testimonial here..." cols="50" rows="4" required></textarea><br>
            <button type="submit" name="submit" >Submit</button>
            <button onclick="closePopup();">Close</button>
            <script type="text/javascript">
                     let popup = document.getElementById("popup");
                     function openPopup() {
                              popup.classList.add("open-popup");
                     }
                     function closePopup() {
                              popup.classList.remove("open-popup");
                     }
            </script>
         </form>
      </div>
</section>
</div>




<section class="all-posts bottom">
   <div class="">
      <h2>Join us now to discover more and rate agricultural products!</h2>      
   </div>
</section>


<footer id="footer">
   <div class="footer">
      <p style="">Contact Us: <a href="mailto: aquasystems@gmail.com">aquasystems@gmail.com</a></p><br>
      <nav>
         <ul>
            <li><a href="policy.php">Privacy Policy</a></li>|
            <li><a href="policy.php#terms">Terms of Service</a></li>|
            <li><a href="about.php">About Us</a></li>
         </ul>
      </nav><br/>
   </div>   
   <div class="footer">
        <p>&copy; <?php echo date("Y"); ?> Agricultural products Quality Assessment System. All Rights Reserved.</p>
    </div>
</footer>
</div>
<script type="text/javascript" src="js/script.js"></script>

</body>
</html>