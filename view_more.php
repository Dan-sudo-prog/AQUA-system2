<?php
// Include your database connection code here
include 'components/connect.php';

// Get the product ID from the URL
if (isset($_GET['get_id'])) {
    $post_id = $_GET['get_id'];

    try {
        // Query to retrieve product information from posts table
        $product_query = "SELECT * FROM posts WHERE post_id = :post_id";
        $product_statement = $conn->prepare($product_query);
        $product_statement->bindParam(':post_id', $post_id);
        $product_statement->execute();
        $product_data = $product_statement->fetch(PDO::FETCH_ASSOC);

        // Query to retrieve average ratings for the product from reviews table
        $ratings_query = "SELECT AVG(rating) AS avg_rating FROM reviews WHERE post_id = :post_id";
        $ratings_statement = $conn->prepare($ratings_query);
        $ratings_statement->bindParam(':post_id', $post_id);
        $ratings_statement->execute();
        $avg_rating_data = $ratings_statement->fetch(PDO::FETCH_ASSOC);
        $avg_rating = $avg_rating_data['avg_rating'];
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="styles.css">
   <style type="text/css">
       h1 {
        font-size: 25px;
        margin-bottom: 15px;
        padding-top: 10px;
       }
       h2 {
        font-size: 20px;
        margin-bottom: 15px;
       }
       p {
        font-size: 16px;
        margin-bottom: 15px;
        padding: 5px;
       }
       .details-container {
        padding: 10px 30px;
        margin: 30px;
        align-content: center;
        align-items: center;
        text-align: left;
        border: none;
        background-color: rgba(5, 200, 1, 0.2);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
       }
       a {
        margin-left: 30px;
       }
   </style>
</head>
<body>
    <!-- header section starts  -->
<?php include 'components/header.php'; ?>
<!-- header section ends -->
<div class="details-container">
    <h1>Product Details</h1>
    <h2><?php echo $product_data['title']; ?></h2>
    <p>Type: <?php echo $product_data['type']; ?></p>
    <p>Harvest Method: <?php echo $product_data['harvest_method']; ?></p>
    <p>Harvest Date: <?php echo $product_data['harvest_date']; ?></p>
    <p>Storage Conditions: <?php echo $product_data['storage_conditions']; ?></p>
    <p>Treatments Used: <?php echo $product_data['treatments_used']; ?></p>
    <p>Pesticides Used: <?php echo $product_data['pesticides_used']; ?></p>
    <p>Price: Ugx <?php echo $product_data['price']; ?></p>
    <p>Location: <?php echo $product_data['location']; ?></p>
    <p>Average Rating: <?php echo number_format($avg_rating, 2); ?></p>

<!-- You can add more details as needed -->
</div>
    <!-- Add a link to go back to the product listing -->
    <a href="view_post.php?get_id=<?= $post_id; ?>" class="inline-btn">Back to view post</a>

    <!-- custom js file link  -->
<script src="script.js"></script>
</body>
</html>
<?php
} else {
    echo "Product ID not provided.";
}
?>
