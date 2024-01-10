<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin page</title>
   <!-- Include your CSS and other HTML head content -->
   <link rel="stylesheet" type="text/css" href="./css/style.css">
</head>
<style type="text/css">
.product-display{
   margin:2rem 0;
}

.product-display .product-display-table{
   width: 100%;
   text-align: center;
}

.product-display .product-display-table thead{
   background: var(--bg-color);
}

.product-display .product-display-table th{
   padding:1rem;
   font-size: 2rem;
}


.product-display .product-display-table td{
   padding:1rem;
   font-size: 2rem;
   border-bottom: var(--border);
}

.product-display .product-display-table .btn:first-child{
   margin-top: 0;
}

.product-display .product-display-table .btn:last-child{
   background: crimson;
}

.product-display .product-display-table .btn:last-child:hover{
   background: var(--black);
}

.container{
   max-width: 1200px;
   padding:2rem;
   margin:0 auto;
}

.admin-product-form-container.centered{
   display: flex;
   align-items: center;
   justify-content: center;
   min-height: 100vh;
   
}

.admin-product-form-container form{
   max-width: 60rem;
   margin:0 auto;
   padding:2rem;
   border-radius: .5rem;
   background: var(--bg-color);
}

.admin-product-form-container form h3{
   text-transform: uppercase;
   color:var(--black);
   margin-bottom: 1rem;
   text-align: center;
   font-size: 2.5rem;
}

.admin-product-form-container form .box{
   width: 100%;
   border-radius: .5rem;
   padding:1.2rem 1.5rem;
   font-size: 1.7rem;
   margin:1rem 0;
   background: var(--white);
   text-transform: none;
}
.btn{
   display: block;
   width: 100%;
   cursor: pointer;
   border-radius: .5rem;
   margin-top: 1rem;
   font-size: 1.7rem;
   padding:1rem 3rem;
   background: var(--orange);
   color:var(--white);
   text-align: center;
}

.btn:hover{
   background: var(--black);
}

.scroll-container {
    overflow-y: scroll;
    height: 60vh;
}
.note {
    font-size: 2rem;
}

.message{
   display: block;
   background: var(--bg-color);
   padding:1.5rem 1rem;
   font-size: 2rem;
   color:var(--black);
   margin-bottom: 2rem;
   text-align: center;
}

.container{
   max-width: 1200px;
   padding:2rem;
   margin:0 auto;
}

/* Add this section at the end of your existing styles */
@media only screen and (max-width: 768px) {
  .product-display .product-display-table {
    display: block;
    overflow-x: auto;
    white-space: nowrap;
  }

  .product-display .product-display-table thead,
  .product-display .product-display-table tbody,
  .product-display .product-display-table tr,
  .product-display .product-display-table th,
  .product-display .product-display-table td {
    display: block;
  }

  .product-display .product-display-table thead tr {
    position: absolute;
    top: -9999px;
    left: -9999px;
  }

  .product-display .product-display-table tr {
    margin-bottom: 15px;
    border: 1px solid #ccc;
  }

  .product-display .product-display-table td {
    border: none;
    border-bottom: 1px solid #eee;
    position: relative;
    padding-left: 50%;
  }

  .product-display .product-display-table td:before {
    content: attr(data-th) ": ";
    font-weight: bold;
    position: absolute;
    left: 5px;
    width: 45%;
    white-space: nowrap;
  }
}



</style>
<body>
<?php
if (!empty($message)) {
    foreach ($message as $msg) {
        echo '<span class="message">' . $msg . '</span>';
    }
}
?>

   
   <div class="container">
      <!-- Content container -->

      

      <!-- Product display table -->
<div class="product-display">
   <table class="product-display-table">
      <thead>
         <tr>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Recommendations</th>
            <th>Product Price</th>
            <th>Action</th>
         </tr>
      </thead>
      <!-- Loop through products and display them in rows -->
      <?php
      @include "components/connect.php";
      try {
          // Prepare and execute a SQL query to fetch product data
          $select = $conn->prepare("SELECT * from posts WHERE user_id = ?;");
          $select->bindParam(1, $user_id);
          $select->execute();
          $result = $select->fetchAll(PDO::FETCH_ASSOC);

          foreach ($result as $row) { 
      ?>
         <tr>
            <td><img src="uploaded_img/<?php echo $row['product_image']; ?>" height="100" alt=""></td>
            <td><?php echo $row['product_name']; ?></td>
            <td><?php echo '' ?></td>
            <td>$<?php echo $row['price']; ?>/-</td>
            <td>
               <!-- Edit and delete actions for each product -->
               <a href="admin_update.php?edit=<?php echo $row['post_id']; ?>" class="btn"><i class="fas fa-edit"></i> Edit</a>
               <a href="admin_page.php?delete=<?php echo $row['post_id']; ?>" class="btn"><i class="fas fa-trash"></i> Delete</a>
               <a href="view_post.php?get_id=<?php echo $row['post_id']; ?>" class="btn"><i class="fas fa-eye"></i>View post</a>
            </td>
         </tr>
      <?php 
          }
      } catch (PDOException $e) {
          echo "Database error: " . $e->getMessage();
      }
      ?>
   </table>
</div>
<!-- Add a new product form -->
      <div class="admin-product-form-container">
<form action="admin_page_submisson.php" method="post" enctype="multipart/form-data" class="admin-page-form">
    <h3>Add a New Product</h3>
    <!-- Input fields for product details -->
    <div class="scroll-container">
        <label for="product_type" class="note">
            <font style="color: red;">Note</font>: if you do not know the category of your product, click <a href="#">here</a> to know about categories.<br><br>Select product type/category:
        </label>
        <select name="product_type" id="product_type" class="box">
            <option value="" disabled selected> Choose a category</option>
            <option value="Legumes">Legumes</option>
            <option value="Grain Foods">Grain Foods</option>
            <option value="Vegetables">Vegetables</option>
            <option value="Dairy">Dairy Products</option>
            <option value="Fruits">Fruits</option>
            <option value="Meat">Meat</option>
            <option value="Fresh Foods">Fresh Foods</option>
            <option value="Animals">Animals</option>
            <option value="Birds">Birds</option>
        </select>

        <input type="text" placeholder="Enter product name" name="product_name" class="box">
        <input type="file" accept="image/png, image/jpeg, image/jpg" name="product_image" class="box">
        <input type="text" placeholder="Describe your product breifly" name="description" class="box">

        <!-- Additional fields based on the selected category -->
        <div id="additionalFields">
            <input type="text" placeholder="Enter varietal information" name="varietal_information" id="varietal_information" class="box" style="display: none;">
            <input type="text" placeholder="Enter region where it was grown" name="origin" id="origin" class="box" style="display: none;">
            <input type="text" placeholder="Enter health and welfare information" id="health" name="health" class="box" style="display: none;">
            <input type="text" placeholder="Enter harvest method used" id="harvest_method" name="harvest_method" class="box" style="display: none;">
            <input type="text" placeholder="Enter production method" id="production_method" name="production_method" class="box" style="display: none;">
            <input type="text" placeholder="Enter breeding and rearing method used" id="breeding_method" name="breeding_method" class="box" style="display: none;">
            <input type="text" placeholder="Enter harvest date" id="harvest_date" name="harvest_date" class="box" style="display: none;">
            <input type="text" placeholder="Enter production date" id="production_date" name="production_date" class="box" style="display: none;">
            <input type="text" placeholder="Enter product storage condition" name="storage_conditions" id="storage_conditions" class="box" style="display: none;">
            <input type="text" placeholder="Enter preservation practices" id="preservation_practices" name="preservation_practices" class="box" style="display: none;">
            <input type="text" placeholder="Enter packaging details" id="packaging" name="packaging" class="box" style="display: none;">
            <textarea placeholder="Enter pre-harvest treatment information" name="preharvest_treatments" id="preharvest_treatments" class="box" style="display: none;"></textarea>
            <textarea placeholder="Enter post-harvest treatments used" name="postharvest_treatments" id="postharvest_treatments" class="box" style="display: none;"></textarea>
            <input type="text" placeholder="Enter information about vaccinations used" name="vaccination_info" id="vaccination_info" class="box" style="display: none;">
            <input type="text" placeholder="Enter information about Treatments used" name="treatment_info" id="treatment_info" class="box" style="display: none;">

        </div>
        <input type="text" placeholder="Enter product price" name="price" class="box">
        <input type="text" placeholder="Enter product location details" name="location" class="box">
    </div>
    <!-- Submit button to add a new product -->
    <button class="btn">Add Product</button>
</form>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const productType = document.getElementById("product_type");
    const varietal_information = document.getElementById("varietal_information");
    const origin = document.getElementById("origin");
    const health = document.getElementById("health");
    const harvest_method = document.getElementById("harvest_method");
    const production_method = document.getElementById("production_method");
    const breeding_method = document.getElementById("breeding_method");
    const harvest_date = document.getElementById("harvest_date");
    const production_date = document.getElementById("production_date");
    const storage_conditions = document.getElementById("storage_conditions");
    const preservation_practices = document.getElementById("preservation_practices");
    const packaging = document.getElementById("packaging");
    const preharvest_treatments = document.getElementById("preharvest_treatments");
    const postharvest_treatments = document.getElementById("postharvest_treatments");
    const vaccination_info = document.getElementById("vaccination_info");
    const treatment_info = document.getElementById("treatment_info");

    if (productType) {
        productType.addEventListener("change", function () {
            const selectedCategory = productType.value;
            console.log("Selected Category:", selectedCategory);

            const elementsToCheck = [
                varietal_information, origin, health, harvest_method, production_method,
                breeding_method, harvest_date, production_date, storage_conditions,
                preservation_practices, packaging, preharvest_treatments,
                postharvest_treatments, vaccination_info, treatment_info
            ];

            // Hide all elements initially
            elementsToCheck.forEach(element => {
                if (element) {
                    element.style.display = "none";
                }
            });

            // Show specific elements based on the selected category
            switch (selectedCategory) {
                case "Legumes":
                    showElements([varietal_information, origin, harvest_method, harvest_date, storage_conditions, packaging, preharvest_treatments, postharvest_treatments]);
                    break;
                case "Grain Foods":
                    showElements([varietal_information, origin, harvest_method, harvest_date, storage_conditions, packaging, preharvest_treatments, postharvest_treatments]);
                    break;
                case "Vegetables":
                    showElements([varietal_information, origin, harvest_method, harvest_date, storage_conditions, preharvest_treatments, postharvest_treatments]);
                    break;
                case "Dairy":
                    showElements([production_method, production_date, storage_conditions, packaging]);
                    break;
                case "Fruits":
                    showElements([varietal_information, harvest_method, harvest_date, storage_conditions, preharvest_treatments, postharvest_treatments]);
                    break;
                case "Meat":
                    showElements([varietal_information, preservation_practices]);
                    break;
                case "Animals":
                    showElements([varietal_information, health, breeding_method, vaccination_info, postharvest_treatments]);
                    break;
                case "Birds":
                    showElements([varietal_information, health, breeding_method, vaccination_info, treatment_info]);
                    break;
                default:
                    showElements([origin, harvest_method, harvest_date, storage_conditions, preharvest_treatments, postharvest_treatments]);
                    break;
            }
        });
    } else {
        console.error("Element with ID 'product_type' not found.");
    }

    function showElements(elements) {
        elements.forEach(element => {
            if (element) {
                element.style.display = "block";
            }
        });
    }
});

</script>

      </div>
   </div>
   <!-- sweetalert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="./js/script.js"></script>

<?php include 'components/alerts.php'; ?>
</body>
</html>