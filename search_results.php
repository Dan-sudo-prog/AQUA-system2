<?php
include 'components/connect.php';

// Initialize variables
$search_results = [];
$resultCount = 0;
$resultsPerPage = 10; // Number of results per page

try {
    if (isset($_GET['query'])) {
        // Validate and sanitize user input
        $search_query = filter_input(INPUT_GET, 'query', FILTER_SANITIZE_STRING);

        // Calculate current page number
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Calculate the offset of the SQL query
        $offset = ($currentPage - 1) * $resultsPerPage;

        // Prepare the SQL statement for searching
        $search_sql = "SELECT posts.*, users.name 
                       FROM posts 
                       INNER JOIN users ON posts.user_id = users.user_id
                       WHERE LOWER(posts.product_name) LIKE LOWER(:query) 
                       OR LOWER(posts.product_type) LIKE :query 
                       OR LOWER(users.name) LIKE LOWER(:query)
                       LIMIT :limit OFFSET :offset;";
        $stmt = $conn->prepare($search_sql);
        $search_param = '%' . $search_query . '%';
        $stmt->bindParam(':query', $search_param, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $resultsPerPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch and store search results
        $search_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Count the number of search results
        $resultCount = count($search_results);
    }
} catch (Exception $e) {
    // Log errors instead of displaying them
    error_log('Error: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" type="text/css" href="./css/fontawesome-free-6.5.1-web/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .search-container, .result {
            margin-bottom: 20px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .search-container .result h2 {
            font-size: 20px;
            font-weight: bold;
        }
        
        .search-container .result p {
            font-size: 16px;
            color: #777;
        }

        .search-container .result a {
            text-decoration: underline;
        }
        .result + .result {
            margin-top: 20px;
        }
        
        
    </style>
</head>
<body>
    <!-- Header section starts  -->
    <?php include 'components/header.php'; ?>
    <!-- Header section ends -->

    <div class="search-container">
        <h1>Search Results</h1>

        <!-- Display result count -->
        <p><?php echo $resultCount; ?> results found</p>

        <?php
        // Display search results in a loop
        foreach ($search_results as $result) {
            echo '<div class="result">';
            echo '<a href="view_post.php?get_id=' . $result['post_id'] . '"><h2>' . $result['product_name'] . '</h2></a>';
            echo '<p>Posted By: ' . $result['name'] . '</p>';
            echo '</div>';
        }

        // Pagination links
        $totalPages = ceil($resultCount / $resultsPerPage);
        if ($totalPages > 1) {
            echo '<div class="pagination">';
            if ($currentPage > 1) {
                echo '<a href="?query=' . $search_query . '&page=' . ($currentPage - 1) . '">Previous</a>';
            }
            // Add links for other pages
            for ($i = 1; $i <= $totalPages; $i++) {
                echo '<a href="?query=' . $search_query . '&page=' . $i . '">' . $i . '</a>';
            }
            echo '</div>';
        }
        ?>

    </div>

    <!-- Custom JavaScript file link -->
    <script src="./js/script.js"></script>
</body>
</html>
