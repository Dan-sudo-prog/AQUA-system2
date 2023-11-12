<?php
if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
   $link = 'all_posts.php';
} else {
   $link = 'index.php';
}
?>
<header class="header">
   <section class="flex">
      <a href="all_posts.php" class="logo"><img src="components/logo.webp" alt="Logo."/></a>
      <span class="top">
         <ul>
            <li><a href="<?php echo $link; ?>" style="color: var(--white);" onmouseover="this.style.color='green';" onmouseout="this.style.color='white';">Home</a></li>
            <li class="dropdownMenu">
               <span class="dropdown" style="color: var(--white);" onmouseover="this.style.color='green';" onmouseout="this.style.color='white';">Categories</span>
               <div class="dropdown-content">
                  <a href="search_results.php?query=Legumes">Legumes</a>
                  <a href="search_results.php?query=Grain Foods">Grain Foods</a>
                  <a href="search_results.php?query=Vegetables">Vegetables</a>
                  <a href="search_results.php?query=Dairy">Dairy Products</a>
                  <a href="search_results.php?query=Fruits">Fruits</a>
                  <a href="search_results.php?query=Meat">Meat</a>
                  <a href="search_results.php?query=Fresh Foods">Fresh Foods</a>
                  <a href="search_results.php?query=Animals">Animals</a>
                  <a href="search_results.php?query=Birds">Birds</a>
               </div>
            </li>
            <li><a href="news.php" style="color: var(--white);" onmouseover="this.style.color='green';" onmouseout="this.style.color='white';">News</a></li>
            <li><a href="market_prices.php" style="color: var(--white);" onmouseover="this.style.color='green';" onmouseout="this.style.color='white';">Market Prices</a></li>
            <li><a href="about.php" style="color: var(--white);" onmouseover="this.style.color='green';" onmouseout="this.style.color='white';">About Us</a></li>
         </ul>
      </span>

      <!-- Search Form in index.php -->
      <?php include 'components/search.php'; ?>

      <nav class="navbar">
         <?php
            if($user_id == ''){ // Check if the user is not logged in
         ?>
            <a href="login.php" style="color: var(--white); background-color: rgba(0, 0, 0, 0.0);" onmouseover="this.style.color='green';" onmouseout="this.style.color='white';">Login</a>
            <a href="register.php" style="color: var(--white); background-color: rgba(0, 0, 0, 0.0);" onmouseover="this.style.color='green';" onmouseout="this.style.color='white';">Register</a>
         <?php
            }
         ?>

         <?php
            if($user_id != ''){
         ?>
         <div id="user-btn" class="far fa-user"></div>
         <?php }; ?>
      </nav>

      <?php
         if($user_id != ''){
            $isApproved = false; // Initialize a variable to track approval status

            // Check if the user is in the user_requests table with status "approved"
            $check_approval = $conn->prepare("SELECT status FROM user_requests WHERE user_id = ? AND status = 'approved'");
            $check_approval->execute([$user_id]);

            if ($check_approval->rowCount() > 0) {
               $isApproved = true;
            }

            echo '<div class="profile">';
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE user_id = ? LIMIT 1");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
               
               if($fetch_profile['profile_image'] != ''){ ?>
                  <img src="uploaded_files/<?= $fetch_profile['profile_image']; ?>" alt="" class="image">
               <?php }; ?>   
               <p><?= $fetch_profile['name']; ?></p>
               
               <?php
               if ($isApproved) {
                   echo '<a href="admin_update.php" class="btn">Manage Account</a>';
               } else echo '<a href="send_request.php" class="btn">Get Farmer Privileges</a>';
               ?>
               <a href="update.php" class="btn">update profile</a>
               <a href="components/logout.php" class="delete-btn" onclick="return confirm('logout from this website?');">logout</a>
            <?php } else { ?>
               <div class="flex-btn">
                  <p>please login or register!</p>
                  <a href="login.php" class="inline-option-btn">login</a>
                  <a href="register.php" class="inline-option-btn">register</a>
               </div>
            <?php };
            echo '</div>';
         }
      ?>

   </section>
</header>
