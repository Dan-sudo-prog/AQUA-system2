<?php
// include 'components/connect.php';
// This file gets news infromation from the database
try {

    // Define SQL query to retrieve news articles
    $sql = "SELECT * FROM news ORDER BY publication_date DESC";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Execute the SQL statement
    $stmt->execute();

    // Fetch all news articles as an associative array
    $newsArticles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // You can now use $newsArticles to display news articles on the page

    foreach ($newsArticles as $article) {
        echo "<h2>{$article['title']}</h2>";
        echo "<p>{$article['content']}</p>";
        echo "<p>Published on: {$article['publication_date']}</p>";
    }
} catch (PDOException $e) {
    // Handle database connection or query errors here
    echo "Error: " . $e->getMessage();
}

?>
