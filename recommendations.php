<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Recommendations</title>
	<link rel="stylesheet" type="text/css" href="./css/fontawesome-free-6.5.1-web/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="./css/style.css">
</head>
<body>
<?php
include 'components/connect.php';
include 'components/header.php';

$select_recommendations = $conn->prepare("SELECT reviews.*, posts.post_id FROM `reviews` INNER JOIN posts ON posts.post_id = reviews.post_id WHERE posts.post_id = reviews.post_id");
$select_recommendations->execute();
$result = $select_recommendations->fetchAll(PDO::FETCH_ASSOC);

if ($select_recommendations->rowCount() > 0) {
	foreach ($result as $row) {

		echo $row['post_id'];
		echo $row['recommendations'];
		echo "\nThere are " . $select_recommendations->rowCount() . " recommendations";
	}
} else {
	echo "No recommendations found";
}

?>
<script src="js/script.js"></script>
</body>
</html>