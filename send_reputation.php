<?php
session_start();
?>
<html>
<title>GeoCamar</title>
<link rel="stylesheet" type="text/css" href="mystyle.css">
<body>

<?php
include_once 'connect_to_database.php';
include_once 'functions.php';
$listingId = $_POST["listingId"];
if ( isset($_SESSION["user"]) AND isset($_POST["target"]) AND !is_reputation_written($conn, $listingId) ) {
	$creator = $_SESSION["user"];
	$target = $_POST["target"];
	$message = $_POST["message"];
	$rating = $_POST["rating"];
	$sql = "INSERT INTO reputations (creator, target, rating, message, listingId)
	VALUES ('$creator', '$target', '$rating', '$message', '$listingId')";
	if (mysqli_query($conn, $sql)) {
		echo "Reputation is public";
	} else {
	  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
	require 'index.php';
} elseif (is_reputation_written($conn, $listingId)) {
	echo "this trade is already reputed";
} else {
	echo "not enough variables set";
	require "login.php";
}
?>
</body>
</html>	
