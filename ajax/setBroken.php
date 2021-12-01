<?php
include("../includes/config.php");

if(isset($_POST["src"])) {
	$query = $db->prepare("UPDATE images SET broken = 1 WHERE imageUrl=:src");
	$query->bindParam(":src", $_POST["src"]);

	$query->execute();
}
else {
	echo "No src passed to page";
}
?>