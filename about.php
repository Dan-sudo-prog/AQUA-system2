
   <?php
include 'components/connect.php';

// Check if the user is logged in and retrieve the user_id
$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="styles.css">
    <style>
       
        

        /* Style the main content container */
        body {
            line-height: 2;
        }
        .container {
            max-width: 1500px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Style the team member cards */
        .about {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Style the team member's name and role */
        .about h2 {
            font-size: 30px;
            color: #333;
        }

        .about p, ul li, ol li {
            font-size: 16px;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Header section starts  -->
<?php include 'components/header.php'; ?>
<!-- Header section ends -->
    
    

    <!-- Main content container -->
    <div class="container">
        <div class="about">
            <h2>About Us</h2>
            <p>Welcome to AQUA - Agricultural Product Quality Assessment System</p>
        </div>
        <div class="about">
            <h2>Our Mission</h2>
            <p>At AQUA, our mission is to empower farmers, consumers, and stakeholders within the agricultural industry with a powerful tool to assess, evaluate, and improve the quality of agricultural products. We believe that high-quality agricultural products are not only essential for consumer health but also crucial for the growth and competitiveness of Uganda's agricultural sector.</p>
        </div>
        <div class="about">
            <h2>Why AQUA?</h2>
            <p>In recent years, Uganda's agricultural products have faced significant challenges in both local and international markets. Quality issues, particularly the presence of harmful substances like aflatoxins, have led to product rejections and bans in export markets. This has not only cost the country valuable revenue but also posed health risks to consumers.
            </p>
            <p>Recognizing the pressing need for a solution, AQUA was born. We are committed to addressing the quality challenges faced by Uganda's agricultural industry by involving consumers in the assessment of agricultural products. By bridging the gap between producers and consumers, we aim to ensure that agricultural products meet the highest quality standards, are safe for consumption, and align with consumer preferences.</p>
        </div>
        <div class="about">
            <h2>Our Objectives</h2>
            <ul>
                <li>Design and develop a system that assesses the quality of agricultural products using consumer reviews.</li>
                <li>Analyze findings and generate user requirements for the AQUA system.</li>
                <li>Design the Agricultural Product Quality Assessment system based on user requirements.</li>
                <li>Develop the AQUA system to meet the identified needs.</li>
                <li>Test and validate the system to ensure accuracy and user satisfaction.</li>
            </ul>
        </div>
        <div class="about">
            <h2>Why Choose AQUA?</h2>
            <ol>
                <li><b>Empowering Farmers</b>: AQUA provides valuable insights to farmers, helping them understand consumer preferences and areas for improvement in their production practices.</li>
                <li><b>Consumer-Centric</b>: We focus on understanding consumer preferences and perceptions of quality attributes to ensure that agricultural products meet consumer demands.</li>
                <li><b>Enhancing Competitiveness</b>: By improving the quality of agricultural products, we enhance the competitiveness of Ugandan products in domestic and international markets.</li>
            </ol>
        </div>
        <div class="about">
            <h2>Join Us in this Mission</h2>
            <p>We invite farmers, consumers, and all stakeholders in Uganda's agricultural industry to join us on this mission to enhance the quality of agricultural products. Together, we can safeguard consumer health, improve market competitiveness, and promote sustainable agricultural practices.</p>
            <p>Thank you for being part of the AQUA community, where quality matters!</p>
        </div>

        
    </div>

    <!-- Include footer here -->
    <footer>
        <!-- Footer content goes here -->
        <?php include 'components/footer.php'; ?>
    </footer>
    <!-- sweetalert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="script.js"></script>
</body>
</html>
