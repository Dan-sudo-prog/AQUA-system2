<?php
// Database connection settings
include 'components/connect.php';

// Initialize user_id variable
$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';

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

    if (empty($product_type) || empty($product_name) || empty($product_image) || empty($price) || empty($location)) {
        $message[] = 'Please fill out all fields.';
    } else {
        try {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Generate a unique ID
            $post_id = generate_unique_id();

            // Prepare the SQL statement
            $insert = "INSERT INTO posts(post_id, user_id, product_type, product_name, product_image, price, location";

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
                        'postharvest_treatments',
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
                        'postharvest_treatments',
                    ];
                    break;
                case 'Dairy':
                    $additionalFields = [
                        'varietal_information',
                        'production_method',
                        'production_date',
                        'storage_conditions',
                        'packaging',
                    ];
                    break;
                case 'Meat':
                    $additionalFields = [
                        'description',
                        'varietal_information',
                        'preservation_practices'
                    ];
                    break;
                case 'Animals':
                case 'Birds':
                    $additionalFields = [
                        'description',
                        'varietal_information',
                        'health',
                        'breeding_method',
                        'vaccination_info',
                        'treatment_info',
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

            $insert .= ") VALUES(:post_id, :user_id, :product_type, :product_name, :product_image, :price, :location";

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
            $stmt->bindParam(':price', $price, PDO::PARAM_INT);
            $stmt->bindParam(':location', $location, PDO::PARAM_STR);

            // Bind additional parameters to the prepared statement
            foreach ($additionalFields as $field) {
                $$field = isset($_POST[$field]) ? $_POST[$field] : null;
                $stmt->bindParam(":$field", $$field, PDO::PARAM_STR);
            }
// var_dump($_POST);
// var_dump($_FILES);
// echo $stmt->queryString;
// exit;
            // Execute the statement
            if ($stmt->execute()) {
                move_uploaded_file($product_image_tmp_name, $product_image_folder);
                $message[] = 'New product added successfully.';
            } else {
                $message[] = 'Failed to add the product. Please try again later.';
                error_log('Database Error: ' . implode(', ', $stmt->errorInfo()));
            }

            // Unset the statement to release resources
            unset($stmt);
        } catch (PDOException $e) {
            $message[] = 'Database error: ' . $e->getMessage();
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
            header('location: admin_page.php');
        } else {
            $message[] = 'Failed to delete the product. Please try again later.';
        }
    } catch (PDOException $e) {
        $message[] = 'Database error: ' . $e->getMessage();
    }
}
?>