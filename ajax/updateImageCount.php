<?php
include("../includes/config.php");

if(isset($_POST["imageUrl"])) {
	$query = $db->prepare("UPDATE images SET clicks = clicks + 1 WHERE imageUrl=:imageUrl");
	$query->bindParam(":imageUrl", $_POST["imageUrl"]);

	$query->execute();
}
else {
	echo "No image URL passed to page";
}
?>