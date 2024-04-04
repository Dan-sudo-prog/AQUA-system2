<?php session_start();
if (isset($_SESSION['user_id'])) {
	$link = 'all_posts.php';
} else {
	$link = 'index.php';
}
?>
<header class="header">
	<section class="flex top">
		<a href="#" class="logo"><img src="components/logo.webp" alt="logo"/></a>
		<form action="search_results.php" method="GET" class="form">
			<div class="search-bar-container">
				<input type="text" name="query" class="search-input" placeholder="Search item...">
				<button type="submit" class="search-button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="auto" class="fa fas  user-button">
                  <path fill="none" d="M0 0h24v24H0V0z"/>
                    <path d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 1 0-.7.7l.27.28v.79l4.25 4.25a1 1 0 0 0 1.41-1.41L15.5 14zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                    </svg>
                </button>
			</div>
		</form>
		<div class="menu-icon">&#9776;</div>
		<!-- <span class="top"> -->
		<!-- <div class="menu-container"> -->
		<ul>
			<li><a href="<?php echo $link; ?>" style="color: var(--white);">Home</a></li>
			<li class="dropdownMenu">
				<span class="dropdown" style="color: var(--white);">Categories</span>
				<div id="list" class="dropdown-content">
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
			<li><a href="news.php" style="color: var(--white);">News</a></li>
			<li><a href="market_prices.php" style="color: var(--white);">Market Prices</a></li>
			<li><a href="about.php" style="color: var(--white);">About Us</a></li>
		</ul>
		<ol class="navbar kul">
			<?php if (empty($_SESSION['user_id'])) { ?>
			<li><a href="login.php" style="color: var(--white);">Login</a>
			<li><a href="register.php" style="color: var(--white);">Register</a></li>
		<?php } else { ?>
			<li id="user-btn" class="user-btn" style="color: var(--white);">
            
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
              <path fill="none" d="M0 0h24v24H0V0z"/>
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
            
            
            </li>
		<!-- added for editiing -->
	<?php // } ?>
				<div class="profile">
		<?php 
					$select_profile = $conn->prepare("SELECT * FROM `users` WHERE user_id = ? LIMIT 1");
					$select_profile->execute([$_SESSION['user_id']]);
					if ($select_profile->rowCount() > 0) {
						$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
						if ($fetch_profile['profile_image'] != '') {
		?>
					<img src="uploaded_files/<?= $fetch_profile['profile_image']; ?>" alt="image" class="image">
		<?php
						} else {
		?>
							<div class="far fa-user"></div>
		<?php
						}
		?>
					<p><?= $fetch_profile['name']; ?></p>
					<a href="update.php" class="btn">Update Profile</a>
                    <a href="admin_page.php" class="btn">Manage Account</a>
					<a href="./components/logout.php" class="delete-btn" onclick="return confirm('Logout from this site?');">Logout</a>
		<?php
					}
		?>
				</div>
			
		<?php  } ?>
		</ol>
		<!-- </div> -->
		<!-- </span> -->
		
	</section>
</header>
<script type="text/javascript">
	document.addEventListener("DOMContentLoaded", function () {
		const dropDown = document.querySelector('ul .dropdownMenu .dropdown');
		const list = document.querySelector('.dropdown-content');
		var menuIcon = document.querySelector(".menu-icon");
		var menu = document.querySelector(".header ul");
		var login = document.querySelector(".header ol");
		menuIcon.addEventListener("click", function () {
			menu.style.display = menu.style.display === "block" ? "none" : "block";
			login.style.display = login.style.display === "flex" ? "none" : "flex";
		});
		dropDown.addEventListener("click", function() {
			list.style.display = list.style.display === "inline-flex" ? "none" : "inline-flex";
		});
	});
</script>