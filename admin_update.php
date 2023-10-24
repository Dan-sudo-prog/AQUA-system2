<?php
@include 'components/connect.php';

if (isset($_GET['edit'])) {
    $post_id = $_GET['edit'];
} else {
    header('location: admin_page.php');
    exit(); // Exit to prevent further execution
}

if (isset($_POST['update_product'])) {
    $product_name = $_POST['product_name'];
    $product_type = $_POST['product_type'];
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = 'uploaded_img/' . $product_image;
    $harvest_method = $_POST['harvest_method'];
    $harvest_date = $_POST['harvest_date'];
    $storage_conditions = $_POST['storage_conditions'];
    $treatments_used = $_POST['treatments_used'];
    $pesticides_used = $_POST['pesticides_used'];
    $product_price = $_POST['price'];
    $location = $_POST['location'];

    if (empty($product_name) || empty($product_price)) {
        $message[] = 'Please fill out all required fields.';
    } else {
        try {
            $update_data = "UPDATE posts SET title=?, price=?";

            $params = [$product_name, $product_price];

            if (!empty($product_image)) {
                // Check if a new image is uploaded and update the image field if necessary
                $update_data .= ", image=?";
                $params[] = $product_image;
            }

            $update_data .= " WHERE post_id = ?";
            $params[] = $post_id;

            // Prepare and execute the SQL update statement
            $stmt = $conn->prepare($update_data);
            if ($stmt->execute($params)) {
                if (!empty($product_image)) {
                    // Move the uploaded image if necessary
                    move_uploaded_file($product_image_tmp_name, $product_image_folder);
                }
                header('location: admin_page.php');
                exit(); // Exit to prevent further execution
            } else {
                $message[] = 'Failed to update the product. Please try again later.';
            }
        } catch (PDOException $e) {
            $message[] = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php
if (isset($message)) {
    foreach ($message as $msg) {
        echo '<span class="message">' . $msg . '</span>';
    }
}
?>

<div class="container">

    <div class="admin-product-form-container centered">

        <?php
        try {
            // Prepare and execute a SQL query to fetch product data based on post_id
            $select = $conn->prepare("SELECT * FROM posts WHERE post_id = ?");
            $select->execute([$post_id]);
            $row = $select->fetch(PDO::FETCH_ASSOC);

            if ($row) {
        ?>

        <form action="" method="post" enctype="multipart/form-data">
            <h3 class="title">Update the product</h3>
            <input type="text" class="box" name="product_name" value="<?php echo $row['title']; ?>"
                   placeholder="Enter the product name">
            <input type="file" class="box" name="product_image" accept="image/png, image/jpeg, image/jpg">
            
            <label for="product_type" style="font-size: 16px;">Select product type/category:</label>
               <select name="product_type" id="product_type" class="box" required>
                   <option value="" disabled selected> Choose an item</option>
                   <option value="Grain &amp; Cereal foods">Grain &amp; Cereal foods</option>
                   <option value="Vegetables &amp; Legumes">Vegetables &amp; Legumes</option>
                   <option value="Poultry &amp; Diary Products">Poultry &amp; Diary Products</option>
                   <option value="Fruits &amp; Beverages">Fruits &amp; Beverages</option>
                   <option value="Meat &amp; Related Products">Meat &amp; Related Products</option>
                   <option value="Fresh Foods">Fresh Foods</option>
               </select>
            <input type="text" placeholder="Enter harvest method used" name="harvest_method" class="box">
            <input type="text" placeholder="Enter harvest/production date" name="harvest_date" class="box" required>
            <input type="text" placeholder="Enter product storage condition" name="storage_conditions" class="box" required>
            <input type="text" placeholder="Enter treatments used" name="treatments_used" class="box" required>
            <input type="text" placeholder="Enter pesticides used" name="pesticides_used" class="box">
            <input type="number" min="0" class="box" name="price" value="<?php echo $row['price']; ?>"
                   placeholder="Enter the product price">
            <input type="text" placeholder="Enter product location details" name="location" class="box" required>
            <input type="submit" value="Update product" name="update_product" class="btn">
            <a href="admin_page.php" class="btn">Go back</a>
        </form>

        <?php
            } else {
                echo "Product not found.";
            }
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }
        ?>

    </div>

</div>

</body>
</html>
