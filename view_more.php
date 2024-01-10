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
    <link rel="stylesheet" type="text/css" href="./css/style.css">
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
        background-color: rgba(255, 255, 255, 0.5);
        box-shadow:0 5px 10px rgba(0, 0, 0, 0.5);
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
    <h2>Category: <?php echo $product_data['product_type']; ?></h2>
    <p>Name: <?php echo $product_data['product_name']; ?></p>
    <p>Description: <?php echo $product_data['description']; ?></p>
    <p id="varietal_information">Varietal Information: <?php echo $product_data['varietal_information']; ?></p>
    <p id="origin">Origin: <?php echo $product_data['origin']; ?></p>
    <p id="health">Health: <?php echo $product_data['health']; ?></p>
    <p id="harvest_method">Harvest Method: <?php echo $product_data['harvest_method']; ?></p>
    <p id="production_method">Production Method: <?php echo $product_data['production_method']; ?></p>
    <p id="breeding_method">Breeding Method: <?php echo $product_data['breeding_method']; ?></p>
    <p id="harvest_date">Harvest Date: <?php echo $product_data['harvest_date']; ?></p>
    <p id="production_date">Production Date: <?php echo $product_data['production_date']; ?></p>
    <p id="storage_conditions">Storage Conditions: <?php echo $product_data['storage_conditions']; ?></p>
    <p id="preservation_practices">Preservation Pracices: <?php echo $product_data['preservation_practices']; ?></p>
    <p id="packaging">Packaging Information: <?php echo $product_data['packaging']; ?></p>
    <p id="preharvest_treatments">Pre-Harvest Treatments Used: <?php echo $product_data['preharvest_treatments']; ?></p>
    <p id="postharvest_treatments">Post-Harvest Treatments Used: <?php echo $product_data['postharvest_treatments']; ?></p>
    <p id="vaccination_info">Vaccination Information: <?php echo $product_data['vaccination_info']; ?></p>
    <p id="treatment_info">Treatment Information: <?php echo $product_data['treatment_info']; ?></p>
    <p>Price: Ugx <?php echo $product_data['price']; ?></p>
    <p>Location: <?php echo $product_data['location']; ?></p>
    <p>Average Rating: <?php echo number_format($avg_rating, 2); ?></p>

<!-- You can add more details as needed -->
</div>
    <!-- Add a link to go back to the product listing -->
    <a href="view_post.php?get_id=<?= $post_id; ?>" class="inline-btn">Back to view post</a>

    <!-- custom js file link  -->
<!-- Add this script inside the head section or at the end of the body before the closing tag -->
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {
        // Array of field elements to be checked and hidden
        const fieldsToHide = [
            'varietal_information', 'origin', 'health', 'harvest_method',
            'production_method', 'breeding_method', 'harvest_date', 'production_date',
            'storage_conditions','preservation_practices','packaging', 'preharvest_treatments', 'postharvest_treatments','vaccination_info','treatment_info'
        ];

        // Convert PHP variables to JavaScript variables using json_encode
        const productData = <?php echo json_encode($product_data); ?>;

        // Loop through the fields and hide/show them based on the data
        fieldsToHide.forEach(fieldName => {
            const fieldValue = productData[fieldName];
            const fieldElement = document.getElementById(fieldName);

            if (fieldValue === null || fieldValue.trim() === '') {
                if (fieldElement) {
                    fieldElement.style.display = 'none';
                }
            } else {
                if (fieldElement) {
                    fieldElement.style.display = 'block';
                }
            }
        });
    });
</script>

<script src="./js/script.js"></script>
</body>
</html>
<?php
} else {
    echo "Product ID not provided.";
}
?>
