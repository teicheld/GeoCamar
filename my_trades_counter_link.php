<?php
session_start();
?>
<html>
<body>
<?php
include_once 'connect_to_database.php';
if (isset($_SESSION["user"])) {
	$user = $_SESSION["user"];
	$sqlCountListingsSold = "SELECT COUNT(id) FROM listings WHERE vendor = '$user' AND status = 'confirmed_payment' AND documentation_link IS NULL";
	$resultCountListingsSold = mysqli_query($conn, $sqlCountListingsSold);
	$printableResultCountListingsSold = mysqli_fetch_assoc($resultCountListingsSold);
	$countSold = $printableResultCountListingsSold["COUNT(id)"];

	?> <a href="my_trades.php">Mein Zuhause<?php echo ": ".$countSold; ?></a><?php
} else {
	require 'login.php';
}
?>

</body>
</html>	
