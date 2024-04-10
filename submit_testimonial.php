<?php
// Database connection parameters
include 'components/connect.php';
// Handle the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Access form fields using $_POST
    $user_name = filter_var($_POST['user_name'], FILTER_SANITIZE_STRING);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
    $testimonial = filter_var($_POST['testimonial'], FILTER_SANITIZE_STRING);

    try {
        // SQL insert statement
        $insertQuery = "INSERT INTO testimonials (`user_name`,`phone`, `location`, `text`) VALUES (:user_name, :phone, :location, :testimonial)";
        
        // Prepare the SQL statement
        $stmt = $conn->prepare($insertQuery);

        // Bind parameters
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':testimonial', $testimonial);

        // Execute the SQL statement
        $stmt->execute();

        // Respond with a success message
        $success_msg[] = "Testimonial submitted successfully.";
    } catch (PDOException $e) {
        // Handle any errors that occur during the database insertion
        echo "Error: " . $e->getMessage();
    }
} else {
    // Handle other request methods or errors here
    http_response_code(405); // Method Not Allowed
    echo "Method not allowed";
}

// Close the database connection
$conn = null;
?>
<<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Testimonial Submission</title>
    <link rel="stylesheet" href="./css/cdnjs/sweetalert2.min.css">
</head>
<body>
<script type="text/javascript">
    setTimeout(function() {
        window.location.href = "index.php";
    }, 2000); // 5000 milliseconds delay (2 seconds)
</script>
<script src="./js/cdnjs/sweetalert2.all.min.js"></script>
<?php include 'components/alerts.php'; ?>
</body>
</html>
