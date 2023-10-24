
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
        .policy {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Style the team member's name and role */
        .policy h2 {
            font-size: 30px;
            color: #333;
        }

        .policy p, .policy ul li, .policy ol li {
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
        <div class="policy">
            <h2>Privacy Policy</h2>            
            <ol>
                <li>Introduction</li>
                <p>This Privacy Policy outlines how AQUA (Agricultural Product Quality Assessment System) collects, uses, maintains, and discloses information collected from users of our website and associated services. We are committed to protecting your privacy and ensuring the security of your personal information.</p>
                <li>Information We Collect</li>
                <p>We may collect personal and non-personal information when you visit our website or use our services. This information may include:
                    <ul>
                        <li>Personal identification information (such as name, email address, and contact information) voluntarily provided by users.</li>
                        <li>Non-personal information, including browser type, operating system, and usage data, collected automatically when you interact with our website.</li>
                    </ul>
                </p>
                <li>How We Use Your Information</li>
                <p>We collect and use personal information for the following purposes:
                    <ul>
                        <li>To personalize user experience.</li>
                        <li>To improve our website and services.</li>
                        <li>To send periodic emails regarding updates, news, and other relevant information.</li>
                    </ul>
                </p>
                <li>How We Protect Your Information</li>
                <p>We employ appropriate data collection, storage, and processing practices, as well as security measures, to protect against unauthorized access, alteration, disclosure, or destruction of your personal information.</p>
                <li>Sharing Your Personal Information</li>
                <p>We do not sell, trade, or rent users' personal identification information to others. We may share generic aggregated demographic information not linked to any personal identification information with our partners and advertisers.</p>
                <li>Third-party Websites</li>
                <p>Users may find content or links to third-party websites on our site. We do not control the content or links that appear on these sites and are not responsible for the practices employed by websites linked to or from our site. Browsing and interaction on any other website, including those linked to our site, are subject to their terms and policies.</p>
                <li>Changes to This Privacy Policy</li>
                <p>AQUA has the discretion to update this Privacy Policy at any time. When we do, we will revise the updated date at the bottom of this page. We encourage users to check this page regularly for any changes and to stay informed about how we are protecting the personal information we collect. You acknowledge and agree that it is your responsibility to review this Privacy Policy periodically and become aware of any modifications.</p>
                <li>Contact Us</li>
                <p>If you have any questions about this Privacy Policy or your dealings with this website, please contact us at <a href="mailto: aquasystems@gmail.com">aquasystems@gmail.com</a>.</p>
            </ol>
        </div>
        <div class="policy">
            <h2 id="terms">Terms of Service</h2>
            <ol>
                <li>Acceptance of Terms</li>
                <p>By using the AQUA website and services, you agree to comply with and be bound by these Terms of Use. If you do not agree to these terms, please refrain from using our website.</p>
                <li>Use of Website</li>
                <p>You are granted a non-exclusive, non-transferable, revocable license to access and use our website and services for personal, non-commercial use. You agree not to use the site for any unlawful purpose or in violation of these Terms of Use.</p>
                <li>User Registration</li>
                <p>Some features of our website may require user registration. When registering, you agree to provide accurate, current, and complete information. You are responsible for maintaining the confidentiality of your account credentials.</p>
                <li>Content Ownership</li>
                <p>All content on our website, including text, graphics, logos, images, and software, is the property of AQUA and is protected by applicable copyright and trademark laws. You may not reproduce, distribute, modify, or republish any content without our prior written consent.</p>
                <li>Privacy</li>
                <p>Your use of our website is also governed by our Privacy Policy.</p>
                <li>Limitation of Liability</li>
                <p>AQUA and its affiliates shall not be liable for any indirect, incidental, consequential, or punitive damages arising from your use of our website or services.</p>
                <li>Changes to Terms of Use</li>
                <p>We reserve the right to revise these Terms of Use at any time without prior notice. By continuing to use our website, you agree to be bound by the most current version of these terms.</p>
                <li>Contact Us</li>
                <p>If you have any questions about these Terms of Use, please contact us at <a href="mailto: aquasystems@gmail.com">aquasystems@gmail.com</a>.</p>
            </ol>
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
