<?php
session_start();
?>
<html>
<body>
<?php
include_once 'functions.php';
include_once 'connect_to_database.php';
require 'upload.php';
//require 'securimage/securimage.php';
//$securimage = new Securimage();

//if ($securimage->check($_POST['captcha_code']) == true && $_SESSION["imageListing"] != "error" ) {
//	$securimage = new Securimage();
if ($_SESSION["imageListing"] != "error" ) {
//	$securimage = new Securimage();

	$item = $_POST["item"];
	$quantity = $_POST["quantity"];
	$price = $_POST["price"];
	$price_btc = euro2btc($price);
	$vendor = $_POST["vendor"];
	$latitude = $_POST["latitude"];
	$longitude = $_POST["longitude"];
	$timestamp_euro2btc_request = time();
	$imageName = $_SESSION["imageListing"];
	$sql = "INSERT INTO listings (item, quantity, price, price_btc, timestamp_euro2btc_request, vendor, image, longitude, latitude) 
	VALUES ('$item', '$quantity', '$price', '$price_btc', '$timestamp_euro2btc_request', '$vendor', '$imageName', '$longitude', '$latitude')";
	if (mysqli_query($conn, $sql)) {
		echo "Der Artikel ist jetzt aufm Markt";
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
	require 'index.php';
/*} elseif ($securimage->check($_POST['captcha_code']) == false) { 
	echo "Captcha falsch eingegeben.";
	require 'post_listings.php';
*/
} elseif ($_SESSION["imageListing"] == "error") {
	echo "Die Bildhochladung war nicht erfolgreich.<br>";
	echo "Das zu erstellende Angebot wurde nicht veröffentlicht<br>";
	require 'post_listings.php';
}
?>
</body>
</html>	

