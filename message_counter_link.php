<?php
session_start();
?>
<html>
<body>
<?php
include_once 'connect_to_database.php';
if (isset($_SESSION["user"])) {
	$user = $_SESSION["user"];
	$sqlMessages = "SELECT COUNT(id) FROM messages WHERE recipient='$user'";
	$resultMessages = mysqli_query($conn, $sqlMessages);
	$printableResultMessages = mysqli_fetch_assoc($resultMessages);
	$countTotal=($printableResultMessages["COUNT(id)"]);
	?> <a href="get_messages.php">Nachrichten:<?php echo " ".$countTotal; ?></a><?php
} else {
	require 'login.php';
}
?>

</body>
</html>	
