<?php
session_start();
?>
<html>
<title>GeoCamar</title>
<link rel="stylesheet" type="text/css" href="mystyle.css">
<body>

<?php
if ( isset($_SESSION["user"]) && isset($_POST["message"]) ) {
	include_once 'connect_to_database.php';
	$recipient = $_POST["recipient"];
	$message = $_POST["message"];
	$topic = $_POST["topic"];
	$user = $_SESSION["user"];
	$sqlMessages = "INSERT INTO messages (creator, recipient, topic, message)
	VALUES ('$user', '$recipient', '$topic', '$message')";
	if (mysqli_query($conn, $sqlMessages)) {
		echo "Nachricht versendet";
	} else {
		echo "Error: " . $sqlMessages . "<br>" . mysqli_error($conn);
	}
	require 'index.php';
} else {
	require "login.php";
}
?>
</body>
</html>	
