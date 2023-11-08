<?php
// Database connection settings
include 'components/connect.php';

   // Initialize user_id variable
    $user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : ''; 


if (isset($_POST['add_product'])) {
    // Input validation and sanitization (add your validation logic here)

    $product_type = $_POST['product_type'];
    $product_name = $_POST['product_name'];
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = 'uploaded_img/' . $product_image;
    $harvest_method = $_POST['harvest_method'];
    $harvest_date = $_POST['harvest_date'];
    $storage_conditions = $_POST['storage_conditions'];
    $treatments_used = $_POST['treatments_used'];
    $pesticides_used = $_POST['pesticides_used'];
    $price = $_POST['price'];
    $location = $_POST['location'];

    if (empty($product_name) || empty($price) || empty($product_image)) {
        $message[] = 'Please fill out all required fields.';
    } else {
        try {
            // Generate a unique ID
            $post_id = generate_unique_id();

            // Prepare the SQL statement
            $insert = "INSERT INTO posts(post_id, user_id, title, image, type, harvest_method, harvest_date, storage_conditions, treatments_used, pesticides_used, price, location) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $conn->prepare($insert);

            // Bind parameters and execute the statement
            $stmt->bindParam(1, $post_id);
            $stmt->bindParam(2, $user_id);
            $stmt->bindParam(3, $product_name);
            $stmt->bindParam(4, $product_image);
            $stmt->bindParam(5, $product_type);
            $stmt->bindParam(6, $harvest_method);
            $stmt->bindParam(7, $harvest_date);
            $stmt->bindParam(8, $storage_conditions);
            $stmt->bindParam(9, $treatments_used);
            $stmt->bindParam(10, $pesticides_used);
            $stmt->bindParam(11, $price);
            $stmt->bindParam(12, $location);

            if ($stmt->execute()) {
                move_uploaded_file($product_image_tmp_name, $product_image_folder);
                $message[] = 'New product added successfully.';
            } else {
                $message[] = 'Failed to add the product. Please try again later.';
            }
        } catch (PDOException $e) {
            $message[] = 'Database error: ' . $e->getMessage();
        }
    }
} else {

}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        // Prepare the SQL statement for deleting a record
        $delete = "DELETE FROM reviews WHERE post_id = :id; DELETE FROM posts WHERE post_id = :id;";
        $deleteStmt = $conn->prepare($delete);
        $deleteStmt->bindParam(':id', $id);
        
        if ($deleteStmt->execute()) {
            header('location: admin_page.php');
        } else {
            $message[] = 'Failed to delete the product. Please try again later.';
        }
    } catch (PDOException $e) {
        $message[] = 'Database error: ' . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin page</title>
   <!-- Include your CSS and other HTML head content -->
   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="styles.css">
</head>
<body>
<!-- Header section -->
   <?php include 'components/header.php'; ?>
   <!-- Header section ends -->
<?php
if (!empty($message)) {
    foreach ($message as $msg) {
        echo '<span class="message">' . $msg . '</span>';
    }
}
?>

   
   <div class="container">
      <!-- Content container -->

      

      <!-- Product display table -->
<div class="product-display">
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
            <td><img src="uploaded_img/<?php echo $row['image']; ?>" height="100" alt=""></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo '' ?></td>
            <td>$<?php echo $row['price']; ?>/-</td>
            <td>
               <!-- Edit and delete actions for each product -->
               <a href="admin_update.php?edit=<?php echo $row['post_id']; ?>" class="btn"><i class="fas fa-edit"></i> Edit</a>
               <a href="admin_page.php?delete=<?php echo $row['post_id']; ?>" class="btn"><i class="fas fa-trash"></i> Delete</a>
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
         <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
    <h3>Add a New Product</h3>
    <!-- Input fields for product details -->
    <div class="scroll-container">
        <label for="product_type" style="font-size: 16px;">
            <font style="color: red;">Note</font>: if you do not know the category of your product, click <a href="#">here</a> to know about categories.<br><br>Select product type/category:
        </label>
        <select name="product_type" id="product_type" class="box" required>
            <option value="" disabled selected> Choose a category</option>
            <option value="Grain &amp; Cereal foods">Grain &amp; Cereal foods</option>
            <option value="Vegetables &amp; Legumes">Vegetables &amp; Legumes</option>
            <option value="Poultry &amp; Dairy Products">Poultry &amp; Dairy Products</option>
            <option value="Fruits &amp; Beverages">Fruits &amp; Beverages</option>
            <option value="Meat &amp; Related Products">Meat &amp; Related Products</option>
            <option value="Fresh Foods">Fresh Foods</option>
            <option value="Fresh Foods">Animals and Poultry</option>
        </select>

        <input type="text" placeholder="Enter product name" name="product_name" class="box" required>
        <input type="file" accept="image/png, image/jpeg, image/jpg" name="product_image" class="box" required>

        <!-- Additional fields based on the selected category -->
        <div id="additionalFields">
            <!-- Fields for "Grain & Cereal foods" category -->
            <div id="grainFields" style="display: none;">
                <input type="text" placeholder="Enter packaging details" name="packaging" class="box">
            </div>

            <!-- Fields for "Vegetables & Legumes" category -->
            <div id="vegetablesFields" style="display: none;">
                <input type="text" placeholder="Enter harvest method used" name="harvest_method" class="box">
            </div>

            <!-- Fields for "Poultry & dairy products" category -->
            <div id="poultryFields" style="display: none;">
                <input type="text" placeholder="Enter health and welfare" name="health_and_welfare" class="box">
                <input type="text" placeholder="Enter breeding and rearing method used" name="breeding_method" class="box">
                <input type="text" placeholder="Enter cattle and dairy herd info(for cattle only)" name="animal_info" class="box">
            </div>

            <!-- Fields for "Fruits & beverages" category -->
            <div id="fruitsFields" style="display: none;">
                <input type="text" placeholder="Enter fruit type" name="fruit_type" class="box">
                <input type="text" placeholder="Enter harvest method used" name="harvest_method" class="box">
            </div>

            <!-- Fields for "meat & related products" category -->
            <div id="meatFields" style="display: none;">
                <input type="text" placeholder="Enter meat type" name="meat_type" class="box">
                <input type="text" placeholder="Enter harvest method used" name="harvest_method" class="box">
            </div>

            <!-- Fields for "Fresh foods and others" category -->
            <div id="freshFields" style="display: none;">
                <input type="text" placeholder="Enter food type" name="food_type" class="box">
                <input type="text" placeholder="Enter harvest method used" name="harvest_method" class="box">
            </div>

            <!-- You can add similar sections for other categories -->
        </div>

        <input type="text" placeholder="Enter harvest/production date" name="harvest_date" class="box" required>
        <input type="text" placeholder="Enter product storage condition" name ="storage_conditions" class="box" required>
        <input type="text" placeholder="Enter treatments used" name="treatments_used" class="box" required>
        <input type="text" placeholder="Enter pesticides used" name="pesticides_used" class="box">
        <input type="text" placeholder="Enter product price" name="price" class="box" required>
        <input type="text" placeholder="Enter product location details" name="location" class="box" required>
    </div>
    <!-- Submit button to add a new product -->
    <input type="submit" class="btn" name="add_product" value="Add Product">
</form>

<script>
    // JavaScript to show/hide additional fields based on the selected category
    const productType = document.getElementById("product_type");
    const additionalFields = document.getElementById("additionalFields");
    const grainFields = document.getElementById("grainFields");
    const vegetablesFields = document.getElementById("vegetablesFields");
    const poultryFields = document.getElementById("poultryFields");
    const fruitsFields = document.getElementById("fruitsFields");
    const meatFields = document.getElementById("meatFields");
    const freshFields = document.getElementById("freshFields");

    productType.addEventListener("change", function () {
        const selectedCategory = productType.value;

        // Reset the visibility of all additional fields
        grainFields.style.display = "none";
        vegetablesFields.style.display = "none";
        poultryFields.style.display = "none";
        fruitsFields.style.display = "none";
        meatFields.style.display = "none";
        freshFields.style.display = "none";

        // Show relevant additional fields based on the selected category
        if (selectedCategory === "Grain & Cereal foods") {
            grainFields.style.display = "block";
        } else if (selectedCategory === "Vegetables & Legumes") {
            vegetablesFields.style.display = "block";
        } else if (selectedCategory === "Poultry & Dairy Products") {
           poultryFields.style.display = "block";
        } else if (selectedCategory === "Fruits & Beverages") {
            fruitFields.style.display = "block";
        } else if (selectedCategory === "Meat & Related Products") {
            meatFields.style.display = "block";
        } else if (selectedCategory === "Vegetables & Legumes") {
            vegetablesFields.style.display = "block";
        }
        // Add similar conditions for other categories
        else {
            freshFields.style.display = "block";
        }
    });
</script>

      </div>
   </div>
   <!-- sweetalert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="script.js"></script>

<?php include 'components/alerts.php'; ?>
</body>
</html>