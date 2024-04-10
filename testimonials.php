<?php
  include 'components/connect.php'
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Testimonials</title>

  <link rel="stylesheet" type="text/css" href="./css/style.css">
  
  <style>
    /* Basic styling for the testimonials section */

body {
  font-family: Arial, sans-serif;
  line-height: 1.6;
  margin: 0;
  background-color: #f7f7f7;
  font-size: 16px;
}

/* Additional styling for the testimonials section */
.testimonials {
  padding: 50px 0;
  text-align: center;
}

.testimonials h2 {
  font-size: 2rem;
  margin-bottom: 20px;
}

.testimonial {
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 8px;
  background-color: #fff;
  margin-bottom: 20px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.testimonial p {
  font-size: 1.2rem;
  margin: 0;
  color: #555;
}

.testimonial .user-info {
  margin-top: 10px;
  font-style: italic;
  color: #888;
}

.container {
  max-width: 800px;
  margin: 0 auto;
}

</style>
<script src="./js/script.js" defer></script>
</head>
<body>
  <!-- header section starts  -->
<?php include 'components/header.php'; ?>
<!-- header section ends -->




  <section class="testimonials">
    <div class="container">
      <h2>What Our Users Say</h2>
      <?php
      // Set up CORS headers to allow cross-origin requests

      

      // Fetch testimonials data from the MySQL database
      $sql = "SELECT * FROM testimonials";
      $result = $conn->query($sql);

      if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
          echo "<div class=\"testimonial\">";
          echo '<p>' . $row["text"] . '</p>';
          echo '<div class="user-info">' . $row['user_name'] . ', ' . $row["location"] . '</div>';
          echo '</div>';
        }
      } else {
        echo 'No testimonials found.'; // Output a message if no testimonials found
      }

      ?>
    </div>
  </section>
</body>
</html>
