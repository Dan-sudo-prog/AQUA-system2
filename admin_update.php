<?php
@include 'components/connect.php';

if (isset($_GET['edit'])) {
    $post_id = $_GET['edit'];
} else {
    header('location: admin_page.php');
    exit(); // Exit to prevent further execution
}

if (isset($_POST['update_product'])) {
    // $product_type = $_POST['product_type'];
    // $product_name = $_POST['product_name'];
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = 'uploaded_img/' . $product_image;
    $description = $_POST['description'];
    // $varietal_information = $_POST['varietal_information'];
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

    if (empty($product_type) || empty($location)) {
        $message[] = 'Please fill out all required fields.';
    } else {
        try {
            $update_data = "UPDATE posts SET product_image=?, description=?, varietal_information=?, origin=?, health=?, harvest_method=?, production_method=?, breeding_method=?, harvest_date=?, production_date=?, storage_conditions=?, preservation_practices=?, packaging=?, preharvest_treatments=?, postharvest_treatments=?, vaccination_info=?, treatment_info=?, price=?, location=?";

            $params = [$product_image, $description, $varietal_information, $origin, $health, $harvest_method, $production_method, $breeding_method, $harvest_date, $production_date, $storage_conditions, $preservation_practices, $packaging, $preharvest_treatments, $postharvest_treatments, $vaccination_info, $treatment_info, $price, $location];

            if (!empty($product_image)) {
                // Check if a new image is uploaded and update the image field if necessary
                $update_data .= ", product_image=?";
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
   <link rel="stylesheet" type="text/css" href="./css/style.css">
   <style type="text/css">
       body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 800px;
    margin: 20px auto;
    background-color: #fff;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.admin-product-form-container {
    text-align: center;
}

.title {
    color: #333;
}

.message {
    display: block;
    color: #ff0000;
    margin: 10px 0;
}

.box {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

select.box {
    height: 36px;
}

.btn {
    background-color: #4caf50;
    color: white;
    padding: 10px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
}

.btn:hover {
    background-color: #45a049;
}

/* Style for the additional fields */
#additionalFields {
    margin-top: 20px;
}

#legumeFields,
#grainFields,
#vegetablesFields,
#dairyFields,
#fruitsFields,
#meatFields,
#freshFields,
#animalFields,
#birdFields {
    display: none;
}

/* Add more specific styles for each category as needed */

   </style>
</head>
<body>

<?php
// include "components/header.php";
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
        <select name="product_type" id="product_type" class="box">
            <option value="" disabled selected> Choose a category</option>
            <option value="<?php echo $row['product_type']; ?>"><?php echo $row['product_type']; ?></option>
        </select>

        <!-- <input type="text" placeholder="Enter product name" name="product_name" class="box"> -->
        <input type="file" accept="image/png, image/jpeg, image/jpg" name="product_image" class="box" required>

        <!-- Additional fields based on the selected category -->
        <div id="additionalFields">
            <!-- Fields for "Legumes" category -->
            <div id="legumeFields" style="display: none;">
                <input type="text" name="varietal_information" class="box" value="<?php echo $row['varietal_information']; ?>">
                <input type="text" name="origin" class="box" value="<?php echo $row['origin']; ?>">
                <input type="text" name="packaging" class="box" value="<?php echo $row['packaging']; ?>">
                <input type="text" name="harvest_method" class="box" value="<?php echo $row['harvest_method']; ?>">
                <input type="text" class="box" value="<?php echo $row['harvest_date']; ?>">
                <input type="text" name ="storage_conditions" class="box" value="<?php echo $row['storage_conditions']; ?>">
                <textarea name="preharvest_treatments" class="box" value="<?php echo $row['preharvest_treatments']; ?>"></textarea>
                <textarea name="postharvest_treatments" class="box" value="<?php echo $row['postharvest_treatments']; ?>"></textarea>
            </div>

            <!-- Fields for "Grain foods" category -->
            <div id="grainFields" style="display: none;">
                <input type="text" class="box" value="<?php echo $row['varietal_information']; ?>">
                <input type="text" name="origin" class="box" value="<?php echo $row['origin']; ?>">
                <input type="text" name="packaging" class="box" value="<?php echo $row['packaging']; ?>">
                <input type="text" name="harvest_method" class="box" value="<?php echo $row['harvest_method']; ?>">
                <input type="text" name="harvest_date" class="box" value="<?php echo $row['harvest_date']; ?>">
                <input type="text" name ="storage_conditions" class="box" value="<?php echo $row['storage_conditions']; ?>">
                <textarea name="preharvest_treatments" class="box" value="<?php echo $row['preharvest_treatments']; ?>"></textarea>
                <textarea name="postharvest_treatments" class="box" value="<?php echo $row['postharvest_treatments']; ?>"></textarea>
            </div>

            <!-- Fields for "Vegetables" category -->
            <div id="vegetablesFields" style="display: none;">
                <input type="text" name="varietal_information" class="box" value="<?php echo $row['varietal_information']; ?>">
                <input type="text" name="origin" class="box" value="<?php echo $row['origin']; ?>">
                <input type="text" name="harvest_method" class="box" value="<?php echo $row['harvest_method']; ?>">
                <input type="text" name="harvest_date" class="box" value="<?php echo $row['harvest_date']; ?>">
                <input type="text" name="storage_conditions" class="box" value="<?php echo $row['storage_conditions']; ?>">
                <textarea name="preharvest_treatments" class="box"><?php echo $row['preharvest_treatments']; ?></textarea>
                <textarea name="postharvest_treatments" class="box"><?php echo $row['postharvest_treatments']; ?></textarea>
            </div>

            <!-- Feilds for "Dairy" category -->
            <div id="dairyFields" style="display: none;">
                <input type="text" value="<?php echo $row['varietal_information']; ?>" name="varietal_information" class="box">
                <input type="text" value="<?php echo $row['production_method']; ?>" name="production_method" class="box">
                <input type="text" value="<?php echo $row['production_date']; ?>" name="production_date" class="box">
                <input type="text" value="<?php echo $row['storage_conditions']; ?>" name="storage_conditions" class="box">
                <input type="text" value="<?php echo $row['packaging']; ?>" name="packaging" class="box">
            </div>

            <!-- Fields for "Fruits" category -->
            <div id="fruitsFields" style="display: none;">
                <input type="text" value="<?php echo $row['varietal_information']; ?>" name="varietal_information" class="box">
                <input type="text" value="<?php echo $row['origin']; ?>" name="origin" class="box">
                <input type="text" value="<?php echo $row['harvest_method']; ?>" name="harvest_method" class="box">
                <input type="text" value="<?php echo $row['harvest_date']; ?>" name="harvest_date" class="box">
                <input type="text" value="<?php echo $row['storage_conditions']; ?>" name="storage_conditions" class="box">
                <textarea value="preharvest_treatments" name="preharvest_treatments" class="box"></textarea>
                <textarea name="postharvest_treatments" class="box"><?php echo $row['postharvest_treatments']; ?></textarea>
            </div>

            <!-- Fields for "meat" category -->
            <div id="meatFields" style="display: none;">
                <input type="text" placeholder="Enter varietal information about the meat" name="varietal_information" class="box">
                <input type="text" placeholder="Enter preservation practices" name="preservation_practices" class="box">
                <input type="text" placeholder="Describe your product breifly" name="description" class="box">
            </div>

            <!-- Fields for "Fresh foods" category -->
            <div id="freshFields" style="display: none;">
                <input type="text" placeholder="Describe your food specifications" name="description" class="box">
                <input type="text" placeholder="Enter harvest method used" name="harvest_method" class="box">
                <input type="text" placeholder="Enter harvest date" name="harvest_date" class="box">
                <input type="text" placeholder="Enter storage information" name="storage_conditions" class="box">
                <textarea placeholder="Enter pre-harvest treatment information" name="preharvest_treatments" class="box"></textarea>
                <textarea placeholder="Enter post_harvest treatments used" name="postharvest_treatments" class="box"></textarea>
            </div>

            <!-- Fields for "Animals" category -->
            <div id="animalFields" style="display: none;">
                <input type="text" placeholder="Describe about the breeds" name="description" class="box">
                <input type="text" placeholder="Enter varietal information" name="varietal_information" class="box">
                <input type="text" placeholder="Enter health and welfare information" name="health" class="box">
                <input type="text" placeholder="Enter breeding and rearing method used" name="breeding_method" class="box">
                <input type="text" placeholder="Enter information about vaccinations used" name="vaccination_info" class="box">
                <input type="text" placeholder="Enter information about Treatments used" name="treatment_info" class="box">
            </div>

            <!-- Fields for "Birds" category -->
            <div id="birdFields" style="display: none;">
                <input type="text" placeholder="Describe about the breeds" name="description" class="box">
                <input type="text" placeholder="Enter varietal information" name="varietal_information" class="box">
                <input type="text" placeholder="Enter health and welfare information" name="health" class="box">
                <input type="text" placeholder="Enter breeding and rearing method used" name="breeding_method" class="box">
                <input type="text" placeholder="Enter information about vaccinations used" name="vaccination_info" class="box">
                <input type="text" placeholder="Enter information about Treatments used" name="treatment_info" class="box">
            </div>

            <!-- You can add similar sections for other categories -->
        </div>

        
        
        
        <input type="text" value="<?php echo $row['price']; ?>" name="price" class="box">
        <input type="text" value="<?php echo $row['location']; ?>" name="location" class="box">
            <input type="submit" value="Update product" name="update_product" class="btn">
            <a href="admin_page.php" class="btn">Go back</a>


<script>
    // JavaScript to show/hide additional fields based on the selected category
    const productType = document.getElementById("product_type");
    const additionalFields = document.getElementById("additionalFields");
    const legumeFields = document.getElementById("legumeFields");
    const grainFields = document.getElementById("grainFields");
    const vegetablesFields = document.getElementById("vegetablesFields");
    const dairyFields = document.getElementById("dairyFields");
    const fruitsFields = document.getElementById("fruitsFields");
    const meatFields = document.getElementById("meatFields");
    const freshFields = document.getElementById("freshFields");
    const animalFields = document.getElementById("animalFields");
    const birdFields = document.getElementById("birdFields");

    productType.addEventListener("change", function () {
        const selectedCategory = productType.value;

        // Reset the visibility of all additional fields
        grainFields.style.display = "none";
        vegetablesFields.style.display = "none";
        dairyFields.style.display = "none";
        fruitsFields.style.display = "none";
        meatFields.style.display = "none";
        freshFields.style.display = "none";
        animalFields.style.display = "none";

        // Show relevant additional fields based on the selected category
        if (selectedCategory === "Legumes") {
            legumeFields.style.display = "block";
        } else if (selectedCategory === "Grain Foods") {
            grainFields.style.display = "block";
        } else if (selectedCategory === "Vegetables") {
            vegetablesFields.style.display = "block";
        } else if (selectedCategory === "Dairy") {
            dairyFields.style.display = "block";
        }else if (selectedCategory === "Fruits") {
            fruitsFields.style.display = "block";
        } else if (selectedCategory === "Meat") {
            meatFields.style.display = "block";
        } else if (selectedCategory === "Animals") {
            animalFields.style.display = "block";
        } else if (selectedCategory === "Birds") {
            birdFields.style.display = "block";
        }
        // Add similar conditions for other categories
        else {
            freshFields.style.display = "block";
        }
    });
</script>

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
