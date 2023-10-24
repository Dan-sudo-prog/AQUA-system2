<?php
// Database connection parameters
include 'components/connect.php';

// Handle the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Access form fields using $_POST
    $user = $_POST['user'];
    $location = $_POST['location'];
    $testimonial = $_POST['testimonial'];

    try {
        // SQL insert statement
        $insertQuery = "INSERT INTO testimonials (`user`, `location`, `text`) VALUES (:user, :location, :testimonial)";
        
        // Prepare the SQL statement
        $stmt = $conn->prepare($insertQuery);

        // Bind parameters
        $stmt->bindParam(':user', $user);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':testimonial', $testimonial);

        // Execute the SQL statement
        $stmt->execute();

        // Respond with a success message
        echo "Testimonial submitted successfully.";
        echo "Redirecting in 5 seconds.....";
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
<script type="text/javascript">
    setTimeout(function() {
        window.location.href = "index.php";
    }, 5000);//5000 miliseconds delay (5 seconds)
</script>
