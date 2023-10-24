<?php
session_start(); // Start the session if not already started

include 'components/connect.php';

if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];
} else {
    $get_id = '';
    header('location: all_posts.php');
}

// Check if the user is logged in using $_SESSION['user_id'], and fallback to $_COOKIE['user_id'] if not set
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : (isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '');

if (isset($_POST['submit'])) {
    // Check if the user is logged in
    if (!empty($user_id)) { // Check if $user_id is not empty
    $id = create_unique_id();
    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING);
    $description = $_POST['description'];
    $description = filter_var($description, FILTER_SANITIZE_STRING);
    $rating = $_POST['rating'];
    $rating = filter_var($rating, FILTER_SANITIZE_STRING);
    $recommendation = $_POST['recommendations']; // Corrected variable name
    $recommendation = filter_var($recommendation, FILTER_SANITIZE_STRING);

    // Verify if the user has already added a review
    $verify_review = $conn->prepare("SELECT * FROM `reviews` WHERE post_id = ? AND user_id = ?");
    $verify_review->execute([$get_id, $user_id]);

    if ($verify_review->rowCount() > 0) {
        $warning_msg[] = 'Your review has already been added!';
    } else {
        // Add the review to the database
        $add_review = $conn->prepare("INSERT INTO `reviews` (review_id, post_id, user_id, rating, review_title, description, recommendations, date) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");

        if ($add_review->execute([$id, $get_id, $user_id, $rating, $title, $description, $recommendation])) {
            $success_msg[] = 'Review added!';
            $delay = 5;
            $targetUrl = 'view_post.php?get_id=' . $get_id;
            header("refresh:$delay;url=$targetUrl");
        } else {
            // Handle database error
            $error_msg[] = 'Failed to add the review. Please try again later.';
        }
    }
} else {
        // User is not logged in
        $warning_msg[] = 'Please log in to add a review.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <!-- ... Your HTML head content ... -->
   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="styles.css">
</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/header.php'; ?>
<!-- header section ends -->

<!-- add review section starts  -->

<section class="account-form">
   <form action="" method="post" id="review-form">
      <h3>post your review</h3>
      <div id="question-1" class="question">
         <p class="placeholder">Can you briefly tell us how was the product?<span>*</span></p>
         <input type="text" name="title" required maxlength="50" placeholder="Enter " class="box">
         <button type="button" id="next-1" class="btn">Next</button>
      </div>

      <div id="question-2" class="question" style="display: none;">
         <p class="placeholder">Describe your experience while using it...</p>
         <textarea name="description" class="box" placeholder="Enter description of your experience..." maxlength="1000" cols="30" rows="10"></textarea>
         <button type="button" id="prev+2" class="btn">Previous</button>
         <button type="button" id="next-2" class="btn">Next</button>
      </div>

      <div id="question-3" class="question" style="display: none;">
         <p class="placeholder">Take some time and rate the product! <span>*</span></p>
         <select name="rating" class="box" required>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
         </select>
         <button type="button" id="prev-3" class="btn">Previous</button>
         <button type="button" id="next-3" class="btn">Next</button>
      </div>

      <div id="question-4" class="question" style="display: none;">
         <p class="placeholder">How can the product be made better?</p>
         <textarea name="recommendations" class="box" placeholder="enter product recommendation" maxlength="1000" cols="30" rows="10"></textarea>
         <button type="button" id="prev-4" class="btn">Previous</button>
         <input type="submit" value="submit review" name="submit" class="btn">
         <a href="view_post.php?get_id=<?= $get_id; ?>" class="option-btn">go back</a>
      </div>
   </form>
</section>

<!-- add review section ends -->

<!-- sweetalert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="script.js"></script>

<script>
   // JavaScript code to show/hide questions
   const questions = document.querySelectorAll('.question');
   const nextButtons = document.querySelectorAll('[id^="next-"]');
   const prevButtons = document.querySelectorAll('[id^="prev-"]');

   for (let i = 0; i < nextButtons.length; i++) {
      const nextButton = nextButtons[i];
      const prevButton = prevButtons[i];
      const question = questions[i];
      const nextQuestion = questions[i + 1];
      const prevQuestion = questions[i - 1];

      nextButton.addEventListener('click', function () {
         question.style.display = 'none';
         if (nextQuestion) {
            nextQuestion.style.display = 'block';
         }
      });

      if (prevButton) {
         prevButton.addEventListener('click', function () {
            question.style.display = 'none';
            prevQuestion.style.display = 'block';
         });
      }
   }
</script>

<?php include 'components/alerts.php'; ?>

</body>
</html>

