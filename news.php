  <?php
include 'components/connect.php';

// Check if the user is logged in and retrieve the user_id
$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Page</title> <!-- Links to your CSS file -->
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="styles.css">
    <style type="text/css">
        /* Define your styles for the news page here */
.news_container {
    max-width: 100%;
    margin: 0 auto;
    padding: 20px;
}

.news_container h1 {
    text-align: center;
    font-size: 24px;
    margin-bottom: 20px;
}

.news-list {
    border: 1px solid #ccc;
    padding: 10px;
}

/* Style individual news articles */
.news-article {
    /*border: 1px solid #ddd;*/
    padding: 10px;
    margin-bottom: 10px;
}

.news-title {
    font-size: 18px;
    font-weight: bold;
}

.news-content {
    font-size: 16px;
}



    </style>
</head>
<body>
    <!-- Include your website header -->
    <?php include 'components/header.php'; ?>

    <div class="news_container">
        <h1>Latest News</h1>
        <div class="news-list">
            <?php
            // Include your PHP code here to fetch and display news articles
            include 'get_news.php'; // Create this file to retrieve news articles from the database
            ?>
        </div>
    </div>
 <!-- sweetalert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="script.js"></script>
    <!-- Include your website footer -->
    <?php include 'components/footer.php'; ?>
</body>
</html>
