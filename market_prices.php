<?php
// Include your PDO database connection settings (e.g., connect.php)
include 'components/connect.php';

// Check if the PDO connection is established
if (!$conn) {
    die("Database connection failed.");
}

// Define a SQL query to select market price data
$sql = "SELECT * FROM market_prices";

try {
    // Prepare and execute the query
    $stmt = $conn->query($sql);

    // Fetch all rows as an associative array
    $marketPrices = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <!-- ... Your HTML head content ... -->
   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="styles.css">
   <style type="text/css">
      /* Style the table container */
.market-price {
    margin: 20px auto;
    padding: 20px;
    max-width: 1200px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

/* Style the table header */
.market-price table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
}

.market-price th {
    background-color: #009E00;
    color: #fff;
    padding: 10px;
    text-align: left;
    font-size: 20px;
}

/* Style the table rows */
.market-price tr:nth-child(even) {
    background-color: #f2f2f2;
}
.market-price td {
   font-size: 16px;
}


.market-price td, .market-price th {
    padding: 10px;
    border: 1px solid #ddd;
}

/* Style links within the table */
.market-price a {
    color: #007BFF;
    text-decoration: none;
    font-weight: bold;
}

.market-price a:hover {
    text-decoration: underline;
}
   </style>
</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/header.php'; ?>
<!-- header section ends -->

<!-- Market price display section starts  -->

<section class="market-price">
   <h2 style="font-size: 25px;">Market Prices</h2>
   <table>
      <thead>
         <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Date</th>
         </tr>
      </thead>
      <tbody>
         <?php foreach ($marketPrices as $marketPrice) { ?>
            <tr>
               <td><?php echo $marketPrice['product']; ?></td>
               <td><?php echo $marketPrice['price']; ?></td>
               <td><?php echo $marketPrice['date']; ?></td>
            </tr>
         <?php } ?>
      </tbody>
   </table>
</section>

<!-- Market price display section ends -->

<!-- sweetalert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="script.js"></script>

<?php include 'components/alerts.php'; ?>

</body>
</html>
