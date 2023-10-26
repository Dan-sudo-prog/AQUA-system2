<!--
*Welcome to Agricultural products quality Assessment System home page
*Authors are Group 12 members
-->

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>index - AQUA System</title>
   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="styles.css">
   <style type="text/css">
      .home_container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 10px;
      }
      /* Style for welcome section of the home page*/
      .welcome {
         height: 85vh;
         background: linear-gradient(to left, rgba(255,255,255,0), rgba(255,255,255,1)), url('images/home.jpg');
         background-repeat: no-repeat;
         background-position: center;
         background-size: cover;
         padding: 20rem 5rem;
      }

      /*style for the pop up window of the testimonial form*/
      .popup {
          width: 1000px;
          background: rgba(200, 200, 255, 0.95);
          border-radius: 6px;
          position: absolute;
          left: 50%;
          transform: translate(-50%,-50%) scale(0.1);
          text-align: center;
          padding: 0 30px 30px;
          color: #333;
          visibility: hidden;
          transition: transform 0.4s, top 0.4s;
      }
      .open-popup{
          visibility: visible;
          transform: translate(-50%,-50%) scale(1);
      }

      .popup h2 {
          font-size: 38px;
          font-weight: 500;
          margin: 30px 0 10px;
}
.popup button {
          width: 30%;
    margin-top: 20px;
    margin-right: 20px;
    padding: 10px;
    background: #6fd649;
    color: #fff;
    outline: none;
    font-size: 18px;
    border-radius: 4px;
    cursor: pointer;
    box-shadow: 0 5px 5px rgba(0, 0, 0, 0.2);
}
.popup input, .popup textarea {
   width: 80%;
    margin-top: 20px;
    padding: 10px;
    background: #f1f1f1;
    outline: none;
    font-size: 18px;
    border-radius: 4px;
    box-shadow: 0 5px 5px rgba(0, 0, 0, 0.2);
}

.popup label {
   display: none;
}
/*End of style*/

/*footer styles*/
footer .home_container nav ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

footer .home_container nav li {
  display: inline-block;
  margin-right: 20px;
  font-size: 20px;
  color: purple;
}

footer .home_container nav a {
  text-decoration: none;
}
/*End of footer styles*/


.all-posts .container p {
   font-size: 16px;
}




 /* Style the slideshow container */
    .slideshow-container {
    position: relative;
    max-width: 1200px;
    margin: 0 auto;
    height: 500px;
    }
    
    /* Hide slides by default */
    .mySlides {
    display: none;

    }
    
    /* Style the slide content */
    .slide-content {
    font-size: 40px;
    color: var(--white);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100%;
    width: 100%;
    }
    
    /* Style the navigation buttons */
    .prev, .next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    padding: 16px;
    color: white;
    background-color: rgba(0, 0, 0, 0);
    }
    .prev:hover, .next:hover {
      background-color: rgba(0, 0, 0, 0.5);
    }
    
    .prev {
        left: 0;
    }
    .next {
        right: 0;
    }
   </style>

   <?php
include 'components/connect.php';

// Check if the user is logged in and retrieve the user_id
$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';

?>

   <!-- Custom CSS file link -->

</head>
<body>
   
<!-- Header section starts  -->
<?php include 'components/header.php'; ?>
<!-- Header section ends -->
<!-- Category section starts here -->

<!-- Welcome section of the home page -->
<section class="welcome">
      <div class="container">
         <h1 style="font-size: 5rem; color: darkolivegreen; font-weight: bolder;">Welcome to Agricultural Products <br/> Quality Assessment System</h1><br>
         <h3 style="font-size: 3rem; color: darkolivegreen; font-weight: bolder;">Discover and Review the Quality of Agricultural Products</h3>
      </div>
   </section>

<!-- Categories Section -->
<section class="all-posts" style="padding: 10rem; background-color: linen;">
   <div class="heading"><h1 style="margin-left: 5rem;">Categories</h1></div>
   <div class="box-container">
      <div class="box">
         <img src="images/grain.jpeg" class="image">
         <h3 class="title"><a href="search_results.php?query=Grain">Grain &amp; Cereal foods</a></h3>
      </div>
      <div class="box">
         <img src="images/legumes.png" class="image">
         <h3 class="title"><a href="search_results.php?query=Vegetables">Vegetables &amp; Legumes</a></h3>
      </div>
      <div class="box">
         <img src="images/diary.png" class="image">
         <h3 class="title"><a href="search_results.php?query=Poultry">Poultry &amp; Diary Products</a></h3>
      </div>
      <div class="box">
         <img src="images/fruits.png" class="image">
         <h3 class="title"><a href="search_results.php?query=Fruits">Fruits &amp; Beverages</a></h3>
      </div>
      <div class="box">
         <img src="images/meat.png" class="image">
         <h3 class="title"><a href="search_results.php?query=Meat">Meat &amp; Related Products</a></h3>
      </div>
      <div class="box">
         <img src="images/foods.png" class="image">
         <h3 class="title"><a href="search_results.php?query=Fresh Foods">Fresh Foods</a></h3>
      </div>
      
   </div>
</section>
<!-- Category section ends here -->

<!-- View all posts section starts  -->
<section class="all-posts" style="padding: 10rem; background-color: lightblue;">
   <div class="heading"><h1 style="margin-left: 5rem;">Featured Products</h1></div>
   <div class="box-container">
      <?php
      // Query to select posts
      $select_posts = $conn->prepare("SELECT * FROM `posts`");
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
      } else {
         echo '<p class="empty">no posts added yet!</p>';
      }
      ?>
   </div>
</section>
<!-- View all posts section ends -->

<!-- Slide show section -->
<section class="all-posts" style="padding: 10rem; background-color: cyan;">
      <div class="heading">
         <h1 style="margin-left: 5rem;">How it Works</h1>
      </div>
      <div class="slideshow-container">
    
        <div class="mySlides fade" style="background-image: url('new/images5.jpeg'); background-repeat: no-repeat; background-size: contain; background-attachment: fixed; width: 100%; height: 600px; background-position: center;">
            <div class="slide-content">
                <!-- Content for Slide 1 -->
                <p>Farmers can list their products on our platform.</p>
            </div>
        </div>

        <div class="mySlides fade"  style="background-image: url('new/images10.jpeg'); background-repeat: no-repeat; background-size: contain; background-attachment: fixed; width: 100%; height: 600px; background-position: center;">
            <div class="slide-content">
                <!-- Content for Slide 2 -->
                <p>Users can browse products, rate them, and write reviews.</p>
            </div>
        </div>
        <div class="mySlides fade" style="background-image: url('new/images6.jpeg'); background-repeat: no-repeat; background-size: contain; background-attachment: fixed; width: 100%; height: 600px; background-position: center;">
            <div class="slide-content">
                <!-- Content for Slide 2 -->
                <p>Make informed decisions for your farming needs.</p>
            </div>
        </div>
        <div class="mySlides fade" style="background-image: url('new/images3.jpeg'); background-repeat: no-repeat; background-size: contain; background-attachment: fixed; width: 100%; height: 600px; background-position: center;">
            <div class="slide-content">
                <!-- Content for Slide 2 -->
                <p>Take part in quality assessment for your preferences</p>
            </div>
        </div>
        

        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1)">&#10095;</a>
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
   <!-- End of slide show section -->

   <!-- Section for testimonials -->
   <section class="all-posts" style="padding: 10rem; background-color: lightgrey;">
         <div class="heading"><h1 style="margin-left: 5rem;">What our Users Say</h1></div>
         <div class="home_container"></div>
         <p style="font-size: 20px; padding: 2rem 5rem;">Know what others say about us</p>
            <p style="font-size: 20px; padding: 2rem 5rem;">Help us improve by writing a testimonial to us if you have not yet done so.</p>
            <div style="padding: 2rem 5rem;"><button onclick="openPopup();" style="font-size: 20px; background-color: limegreen; border: none; padding: 10px; border-radius: 5px; color: var(--white);">Write a testimonial</button>
            <a href="testimonials.php" style="font-size: 20px; background-color: limegreen; border: none; padding: 10px; border-radius: 5px; color: var(--white);">View testimonials</a></div>
            <div id="popup" class="popup">
               <h2>Write a Testimonial</h2>
               <form method="post" action="submit_testimonial.php" id="testimonial-form">
                  <label for="name">Name:</label>
                  <input type="text" name="user" placeholder="Write your name" required>
                  <label for="location">Location</label>
                  <input type="text" name="location" placeholder="Write your location" required>
                  <label for="testimonial">Testimonial</label>
                  <textarea name="testimonial" placeholder="Write your testimonial here..." cols="50" rows="4" required></textarea><br>
                  <button>Submit</button><button onclick="closePopup();">Close</button>
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
      </div>
   </section>
   <!-- End of section -->

<!--Section for Call for cooperation -->
<section class="all-posts" style="font-size: 16px; text-align: center; height: 30vh; background-color: lightskyblue; padding:  10rem;">
   <div class="home-container">
      <h2>Join us now to discover more and rate agricultural products!</h2>      
   </div>
</section>

<!-- Footer section -->
<footer style="font-size: 16px; text-align: center; background-color: cyan;">
      <div class="home_container">
            <p style="">Contact Us: <a href="mailto: aquasystems@gmail.com">aquasystems@gmail.com</a></p><br>
            <nav>
            <ul>
                  <li><a href="policy.php">Privacy Policy</a></li>|
                  <li><a href="policy.php#terms">Terms of Service</a></li>|
                  <li><a href="about.php">About Us</a></li>
            </ul>
            </nav><br/><br/>
            
      </div>
      <?php include 'components/footer.php'; ?>
   </footer>



<!-- SweetAlert CDN link -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- Custom JavaScript file link -->
<script src="script.js"></script>

<?php include 'components/alerts.php'; ?>

<marquee>Still under development.</marquee>
</body>
</html>
