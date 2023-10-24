<?php
include 'components/connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle the form submission

    $title = $_POST['title'];
    $content = $_POST['content'];

    // Validate the input data (add your validation logic here)

    if (empty($title) || empty($content)) {
        $message = 'Please fill out all required fields.';
    } else {
        // Insert the news into the database using prepared statements
        $insert = "INSERT INTO news (title, content, date) VALUES (:title, :content, NOW())";
        $stmt = $conn->prepare($insert);

        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $message = 'News added successfully.';
            // Clear form fields if needed
            $title = $content = '';
        } else {
            $message = 'Failed to add news. Please try again later.';
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
   <title>Add News</title>
   <link rel="stylesheet" type="text/css" href="style.css">
   <link rel="stylesheet" type="text/css" href="styles.css">
   <!-- Include your CSS and other HTML head content -->
   <style type="text/css">
       /* Style the container for the "Add News" form */
.account-form {
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 20px;
    width: 80%;
    max-width: 600px;
    margin: 0 auto;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Style the form header */
.account-form h3 {
    font-size: 24px;
    margin-bottom: 20px;
    color: #333;
}

/* Style the form input fields */
.account-form .box {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 3px;
    font-size: 16px;
}

/* Style the textarea for the news description */
.account-form textarea {
    resize: vertical;
    height: 150px; /* Adjust the height as needed */
}

/* Style the submit button */
.account-form .btn {
    background-color: #007BFF;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 3px;
    font-size: 18px;
    cursor: pointer;
}

/* Style a link for going back */
.account-form .option-btn {
    display: inline-block;
    margin-top: 10px;
    color: #007BFF;
    text-decoration: none;
    font-size: 16px;
}

/* Style the link on hover */
.account-form .option-btn:hover {
    text-decoration: underline;
}

/* Style error messages */
.error-message {
    color: #ff0000;
    font-size: 14px;
}

/* Style success messages */
.success-message {
    color: #008000;
    font-size: 14px;
}

   </style>
</head>
<body>
<!-- header section starts  -->
<?php include 'components/header.php'; ?>
<!-- header section ends -->
   <div class="container">
      
      <?php if (isset($message)) { ?>
          <div class="message"><?php echo $message; ?></div>
      <?php } ?>

      <!-- Add a new news form -->
<div class="account-form">
    <form action="add_news.php" method="post">
        <h3>Add News</h3>
        <!-- Input fields for news details -->
        <input type="text" placeholder="Enter news title" name="title" class="box">
        <textarea name="description" placeholder="Enter news description" class="box"></textarea>
        <input type="submit" value="Submit News" name="submit" class="btn">
        <a href="news.php" class="option-btn">Go back to all news</a>
    </form>
</div>

   </div>

   <!-- Your footer content goes here -->
</body>
</html>
