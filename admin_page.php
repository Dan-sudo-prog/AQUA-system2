


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin page</title>
   <!-- Include your CSS and other HTML head content -->
   <link rel="stylesheet" href="./css/cdnjs/sweetalert2.min.css">
   <link rel="stylesheet" type="text/css" href="./css/fontawesome-free-6.5.1-web/css/all.min.css">
   <link rel="stylesheet" type="text/css" href="./css/style.css">
</head>
<style type="text/css">
.product-display{
   margin:2rem 0;
}

.product-display .product-display-table{
   width: 100%;
   text-align: center;
}

.product-display .product-display-table thead{
   background: var(--bg-color);
}

.product-display .product-display-table th{
   padding:1rem;
   font-size: 2rem;
}


.product-display .product-display-table td{
   padding:1rem;
   font-size: 2rem;
   border-bottom: var(--border);
}

.product-display .product-display-table .btn:first-child{
   margin-top: 0;
}

.product-display .product-display-table .btn:last-child{
   background: crimson;
}

.product-display .product-display-table .btn:last-child:hover{
   background: var(--black);
}

.container{
   max-width: 1200px;
   padding:2rem;
   margin:0 auto;
}

.admin-product-form-container.centered{
   display: flex;
   align-items: center;
   justify-content: center;
   min-height: 100vh;
   
}

.admin-product-form-container form{
   max-width: 60rem;
   margin:0 auto;
   padding:2rem;
   border-radius: .5rem;
   background: var(--bg-color);
}

.admin-product-form-container form h3{
   text-transform: uppercase;
   color:var(--black);
   margin-bottom: 1rem;
   text-align: center;
   font-size: 2.5rem;
}

.admin-product-form-container form .box{
   width: 100%;
   border-radius: .5rem;
   padding:1.2rem 1.5rem;
   font-size: 1.7rem;
   margin:1rem 0;
   background: var(--white);
   text-transform: none;
}


.scroll-container {
    overflow-y: scroll;
    height: 60vh;
}
.note {
    font-size: 2rem;
}


@media (max-width:991px){

   html{
      font-size: 55%;
   }

}

@media (max-width:768px){

   .product-display{
      overflow-y:scroll;
   }

   .product-display .product-display-table{
      width: 80rem;
   }

}

@media (max-width:450px){

   html{
      font-size: 50%;
   }

}


</style>
<body>
<?php
include "components/connect.php";
include "components/header.php";
// Initialize user_id variable
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input validation and sanitization (add your validation logic here)

    $product_type = $_POST['product_type'];
    $product_name = $_POST['product_name'];
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = 'uploaded_img/' . $product_image;
    $description = $_POST['description'];
    $varietal_information = $_POST['varietal_information'];
    $origin = $_POST['origin'];
    $health = $_POST['health'];
    $harvest_method = $_POST['harvest_method'];
    $production_method = $_POST['production_method'];
    $breeding_method = $_POST['breeding_method'];
    $harvest_date = $_POST['harvest_date'];
    $production_date = $_POST['production_date'];
    $storage_conditions = $_POST['storage_conditions'];
    $preservation_practices = $_POST['preservation_practices'];
    $packaging = $_POST['packaging'];
    $preharvest_treatments = $_POST['preharvest_treatments'];
    $postharvest_treatments = $_POST['postharvest_treatments'];
    $vaccination_info = $_POST['vaccination_info'];
    $treatment_info = $_POST['treatment_info'];
    $price = $_POST['price'];
    $location = $_POST['location'];

    if (empty($product_type) || empty($product_name) || empty($product_image) ||empty($description) || empty($price) || empty($location)) {
        $warning_msg[] = 'Please fill out all fields.';
    } else {
        try {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Generate a unique ID
            $post_id = generate_unique_id();

            // Prepare the SQL statement
            $insert = "INSERT INTO posts(post_id, user_id, product_type, product_name, product_image, description, price, location";

            // Capture additional fields based on the selected category
            switch ($product_type) {
                case 'Legumes':
                    $additionalFields = [
                        'varietal_information',
                        'origin',
                        'harvest_method',
                        'harvest_date',
                        'storage_conditions',
                        'preharvest_treatments',
                        'postharvest_treatments'
                    ];
                    break;
                case 'Grain Foods':
                case 'Vegetables':
                case 'Fruits':
                case 'Fresh Foods':
                    $additionalFields = [
                        'varietal_information',
                        'origin',
                        'harvest_method',
                        'harvest_date',
                        'storage_conditions',
                        'preharvest_treatments',
                        'postharvest_treatments'
                    ];
                    break;
                case 'Dairy':
                    $additionalFields = [
                        'varietal_information',
                        'production_method',
                        'production_date',
                        'storage_conditions',
                        'packaging'
                    ];
                    break;
                case 'Meat':
                    $additionalFields = [
                        'varietal_information',
                        'preservation_practices'
                    ];
                    break;
                case 'Animals':
                case 'Birds':
                    $additionalFields = [
                        'varietal_information',
                        'health',
                        'breeding_method',
                        'vaccination_info',
                        'treatment_info'
                    ];
                    break;
                // Add cases for other categories if needed
                default:
                    $additionalFields = [];
                    break;
            }

            // Add additional fields to the SQL statement
            foreach ($additionalFields as $field) {
                $insert .= ", $field";
            }

            $insert .= ") VALUES(:post_id, :user_id, :product_type, :product_name, :product_image, :description, :price, :location";

            // Add additional parameters to the SQL statement
            foreach ($additionalFields as $field) {
                $insert .= ", :$field";
            }

            $insert .= ")";

            // Prepare the SQL statement
            $stmt = $conn->prepare($insert);

            if (!$stmt) {
                die('Error in statement preparation: ' . $conn->errorInfo());
            }

            // Bind parameters
            $stmt->bindParam(':post_id', $post_id, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt->bindParam(':product_type', $product_type, PDO::PARAM_STR);
            $stmt->bindParam(':product_name', $product_name, PDO::PARAM_STR);
            $stmt->bindParam(':product_image', $product_image, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);            
            $stmt->bindParam(':price', $price, PDO::PARAM_INT);
            $stmt->bindParam(':location', $location, PDO::PARAM_STR);

            // Bind additional parameters to the prepared statement
            foreach ($additionalFields as $field) {
                $$field = isset($_POST[$field]) ? $_POST[$field] : null;
                $stmt->bindParam(":$field", $$field, PDO::PARAM_STR);
            }

            // Execute the statement
            if ($stmt->execute()) {
                move_uploaded_file($product_image_tmp_name, $product_image_folder);
                $success_msg[] = 'New product added successfully.';
                $stmt->closeCursor();
            } else {
                $error_msg[] = 'Failed to add the product. Please try again later.';
                error_log('Database Error: ' . implode(', ', $stmt->errorInfo()));
            }

            // Unset the statement to release resources
            unset($stmt);
        } catch (PDOException $e) {
            $warning_msg[] = 'Database error: ' . $e->getMessage();
        }
    }

} else {
    // Handle other actions if needed
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        // Prepare the SQL statement for deleting a record
        $delete = "DELETE FROM reviews WHERE post_id = :id; DELETE FROM posts WHERE post_id = :id;";
        $deleteStmt = $conn->prepare($delete);
        $deleteStmt->bindParam(':id', $id);
        
        if ($deleteStmt->execute()) {
            $deleteStmt->closeCursor();
        } else {
            $error_msg[] = 'Failed to delete the product. Please try again later.';
        }
    } catch (PDOException $e) {
        $warning_msg[] = 'Database error: ' . $e->getMessage();
    }
}
?>
   
   <div class="container">
      <!-- Content container -->

    

      <!-- Product display table -->
<div class="product-display">
    <a href="#add_product" class="btn">Add New Product</a>
   <table class="product-display-table">
      <thead>
         <tr>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Recommendations</th>
            <th>Product Price</th>
            <th>Action</th>
         </tr>
      </thead>
      <!-- Loop through products and display them in rows -->
      <?php
      try {
          // Prepare and execute a SQL query to fetch product data
          $select = $conn->prepare("SELECT * from posts WHERE user_id = ?;");
          $select->bindParam(1, $user_id);
          $select->execute();
          $result = $select->fetchAll(PDO::FETCH_ASSOC);

          foreach ($result as $row) { 
      ?>
         <tr>
            <td><img src="uploaded_img/<?php echo $row['product_image']; ?>" height="100" alt=""></td>
            <td><?php echo $row['product_name']; ?></td>
            <td><?php echo '<a href="recommendations.php" class="btn">View Information</a>' ?></td>
            <td>Ugx <?php echo $row['price']; ?>/-</td>
            <td>
               <!-- Edit and delete actions for each product -->
               <a href="admin_update.php?edit=<?php echo $row['post_id']; ?>" class="btn"><i class="fas fa-edit"></i> Edit</a>
               <a href="<?php echo $_SERVER['PHP_SELF']?>?delete=<?php echo $row['post_id']; ?>" class="btn"><i class="fas fa-trash"></i> Delete</a>
               <a href="view_post.php?get_id=<?php echo $row['post_id']; ?>" class="btn"><i class="fas fa-eye"></i>View post</a>
            </td>
         </tr>
      <?php 
          }
      } catch (PDOException $e) {
          echo "Database error: " . $e->getMessage();
      }
      ?>
   </table>
</div>
<!-- Add a new product form -->
      <div class="admin-product-form-container">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" class="admin-page-form" id="myForm">
    <h3 id="add_product">Add a New Product</h3>
    <!-- Input fields for product details -->
    <div class="scroll-container">
        <label for="product_type" class="note">
            <font style="color: red;">Note</font>: If you need assistance in filling in the information, click <a href="#">here</a> to get more information.<br><br>Select product type/category:
        </label>
        <select name="product_type" id="product_type" class="box">
            <option value="" disabled selected> Choose a category</option>
            <option value="Legumes">Legumes</option>
            <option value="Grain Foods">Grain Foods</option>
            <option value="Vegetables">Vegetables</option>
            <option value="Dairy">Dairy Products</option>
            <option value="Fruits">Fruits</option>
            <option value="Meat">Meat</option>
            <option value="Fresh Foods">Fresh Foods</option>
            <option value="Animals">Animals</option>
            <option value="Birds">Birds</option>
        </select>

        <input type="text" placeholder="Enter product name" name="product_name" class="box">
        <input type="file" accept="image/png, image/jpeg, image/jpg" name="product_image" class="box">
        <input type="text" placeholder="Describe your product breifly" name="description" class="box">

        <!-- Additional fields based on the selected category -->
        <div id="additionalFields">
            <input type="text" placeholder="Enter varietal information" name="varietal_information" id="varietal_information" class="box" style="display: none;">
            <input type="text" placeholder="Enter region where it was grown" name="origin" id="origin" class="box" style="display: none;">
            <input type="text" placeholder="Enter health and welfare information" id="health" name="health" class="box" style="display: none;">
            <input type="text" placeholder="Enter harvest method used" id="harvest_method" name="harvest_method" class="box" style="display: none;">
            <input type="text" placeholder="Enter production method" id="production_method" name="production_method" class="box" style="display: none;">
            <input type="text" placeholder="Enter breeding and rearing method used" id="breeding_method" name="breeding_method" class="box" style="display: none;">
            <input type="text" placeholder="Enter harvest date(YYYY-MM-DD)" id="harvest_date" name="harvest_date" class="box" style="display: none;">
            <input type="text" placeholder="Enter production date(YYYY-MM-DD)" id="production_date" name="production_date" class="box" style="display: none;">
            <input type="text" placeholder="Enter product storage condition" name="storage_conditions" id="storage_conditions" class="box" style="display: none;">
            <input type="text" placeholder="Enter preservation practices" id="preservation_practices" name="preservation_practices" class="box" style="display: none;">
            <input type="text" placeholder="Enter packaging details" id="packaging" name="packaging" class="box" style="display: none;">
            <textarea placeholder="Enter pre-harvest treatment information" name="preharvest_treatments" id="preharvest_treatments" class="box" style="display: none;"></textarea>
            <textarea placeholder="Enter post-harvest treatments used" name="postharvest_treatments" id="postharvest_treatments" class="box" style="display: none;"></textarea>
            <input type="text" placeholder="Enter information about vaccinations used" name="vaccination_info" id="vaccination_info" class="box" style="display: none;">
            <input type="text" placeholder="Enter information about Treatments used" name="treatment_info" id="treatment_info" class="box" style="display: none;">

        </div>
        <input type="text" placeholder="Enter product price" name="price" class="box">
        <input type="text" placeholder="Enter product location details" name="location" class="box">
    </div>
    <!-- Submit button to add a new product -->
    <button class="btn" id="submitBtn">Add Product</button>
</form>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const productType = document.getElementById("product_type");
    const varietal_information = document.getElementById("varietal_information");
    const origin = document.getElementById("origin");
    const health = document.getElementById("health");
    const harvest_method = document.getElementById("harvest_method");
    const production_method = document.getElementById("production_method");
    const breeding_method = document.getElementById("breeding_method");
    const harvest_date = document.getElementById("harvest_date");
    const production_date = document.getElementById("production_date");
    const storage_conditions = document.getElementById("storage_conditions");
    const preservation_practices = document.getElementById("preservation_practices");
    const packaging = document.getElementById("packaging");
    const preharvest_treatments = document.getElementById("preharvest_treatments");
    const postharvest_treatments = document.getElementById("postharvest_treatments");
    const vaccination_info = document.getElementById("vaccination_info");
    const treatment_info = document.getElementById("treatment_info");

    if (productType) {
        productType.addEventListener("change", function () {
            const selectedCategory = productType.value;
            console.log("Selected Category:", selectedCategory);

            const elementsToCheck = [
                varietal_information, origin, health, harvest_method, production_method,
                breeding_method, harvest_date, production_date, storage_conditions,
                preservation_practices, packaging, preharvest_treatments,
                postharvest_treatments, vaccination_info, treatment_info
            ];

            // Hide all elements initially
            elementsToCheck.forEach(element => {
                if (element) {
                    element.style.display = "none";
                }
            });

            // Show specific elements based on the selected category
            switch (selectedCategory) {
                case "Legumes":
                    showElements([varietal_information, origin, harvest_method, harvest_date, storage_conditions, packaging, preharvest_treatments, postharvest_treatments]);
                    break;
                case "Grain Foods":
                    showElements([varietal_information, origin, harvest_method, harvest_date, storage_conditions, packaging, preharvest_treatments, postharvest_treatments]);
                    break;
                case "Vegetables":
                    showElements([varietal_information, origin, harvest_method, harvest_date, storage_conditions, preharvest_treatments, postharvest_treatments]);
                    break;
                case "Dairy":
                    showElements([production_method, production_date, storage_conditions, packaging]);
                    break;
                case "Fruits":
                    showElements([varietal_information, harvest_method, harvest_date, storage_conditions, preharvest_treatments, postharvest_treatments]);
                    break;
                case "Meat":
                    showElements([varietal_information, preservation_practices]);
                    break;
                case "Animals":
                    showElements([varietal_information, health, breeding_method, vaccination_info, postharvest_treatments]);
                    break;
                case "Birds":
                    showElements([varietal_information, health, breeding_method, vaccination_info, treatment_info]);
                    break;
                default:
                    showElements([origin, harvest_method, harvest_date, storage_conditions, preharvest_treatments, postharvest_treatments]);
                    break;
            }
        });
    } else {
        console.error("Element with ID 'product_type' not found.");
    }

    function showElements(elements) {
        elements.forEach(element => {
            if (element) {
                element.style.display = "block";
            }
        });
    }
});

</script>

      </div>
   </div>
   <!-- sweetalert cdn link  -->
<script src="./js/cdnjs/sweetalert2.all.min.js"></script>

<!-- custom js file link  -->
<script src="./js/script.js"></script>

<?php include 'components/alerts.php'; ?>
</body>
</html>