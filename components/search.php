<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        
/* CSS for Search Bar */
form .search-bar-container {
    display: flex !important;
    align-items: center !important;
    margin-bottom: 20px !important;
    margin-top: 20px !important;
}

form .search-bar-container .search-input {
    padding: 5px 10px !important;
    border: 1px solid #ccc !important;
    border-radius: 10px !important;
    font-size: 16px !important;
    width: 250px !important;
    outline: none !important;
}

form .search-bar-container .search-button {
    background-color: green; !important;
    color: #fff !important;
    border: none;
    border-radius: 10px !important;
    padding: 5px 10px !important;
    margin-left: 10px !important;
    cursor: pointer !important;
    font-size: 16px !important;
}

/* Style the button on hover */
.search-button:hover {
    background-color: #0056b3;
}

/* Adjust styles for smaller screens or mobile devices */
@media screen and (max-width: 768px) {
    .search-input {
        width: 100%;
    }

    .search-button {
        width: 100%;
        margin-left: 0;
        margin-top: 10px;
    }
}

    </style>
</head>
<body>


<form action="search_results.php" method="GET">
   <div class="search-bar-container">
      <input type="text" name="query" class="search-input" placeholder="Search products...">
      <button type="submit" class="search-button">Search</button>
   </div>
</form>
</body>
</html>