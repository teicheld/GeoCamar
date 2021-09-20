<?php
session_start();
?>
<html>
<head>
	<link rel="stylesheet" href="mystyle.css">
</head>
<body>
<?php
include_once 'connect_to_database.php';
include_once 'functions.php';

if ( isset($_SESSION["user"]) && isset($_POST["id"]) ) {
	?><div class="topnav">
	<a href="index.php">Index</a>
	<a href="my_trades.php">Meine Handel</a>
	<?php require 'message_counter_link.php'; ?>
	<?php print_account_balance($user, $conn); ?>
	</div><?php
	$user = $_SESSION["user"];
	$id = $_POST["id"];


	//////////////////////////////////////set variables/////////////////////////////////////////////////
	$rowListings = sql_SELECT_listings($id, $conn);
	$rowEscrow = sql_SELECT_escrow($id, $conn);
	
	//get escrow_balance
	$escrowAddress = $rowEscrow['address'];
	$address_json = file_get_contents("https://api.blockcypher.com/v1/btc/test3/addrs/$escrowAddress/full?limit=50");
	$address_json = json_decode($address_json);
	$balance_0confirmations = $address_json->unconfirmed_balance;
	$balance_6confirmations = $address_json->balance;

	sql_UPDATE_listings_SET_timestamp_double_client_prevention($id, $conn);		//fresh dont get loadet in index.php

	$priceEuro = $rowListings["price"];
	//opsolete btc price?
	$refreshPeriode = (60 * 60 * 24);	//24 hours
	$livedTime = (time() - $rowListings["timestamp_euro2btc_request"]);
	if ($livedTime > $refreshPeriode) {
		$priceBtc = euro2btc($priceEuro);
		$rowListings = sql_UPDATE_listings_SET_timestamp_euro2btc_request($id, $priceBtc, $conn);
	}

	//status set
	if ( 0 == $balance_6confirmations && 0 == $balance_0confirmations) {
		$status = "no_payment";
		//todo: clean up client behaftete unpaid listings which are old for listing in index.php
	} elseif ( 0 == $balance_6confirmations && 0 < $balance_0confirmations) {
		$status = "unconfirmed_payment";
	} elseif ( 0 < $balance_6confirmations && 0 < $balance_0confirmations) {
		$status = "confirmed_payment";
	}

///////////////////////////client interaction/////////////////////////////////////////////////////
	
	$itemName = get_item_name($id, $conn);
	echo "<div class='css_box_long'><h3>Der Kaufprozess des Artikels \"$itemName\" </h3></div>";
	request_payment($rowListings["price_btc"], $escrowAddress, $status);
	print_item($id, $conn);
	$rowListings = sql_UPDATE_listings_SET_payment($id, $user, $status, $conn);
	start_timer_escrow_to_client($id, $conn);
?>	<br><a href="post_message.php?vendor=<?php echo $rowListings["vendor"];?>">message the vendor</a>
<?php

} elseif ( !isset($_SESSION["user"]) ) {
	require 'post_login.php';
} elseif ( !isset($_POST["id"]) ) {
	require 'index.php';
}
fclose($file_0confirmations);
fclose($file_6confirmations);
?>
</body>
</html>	
