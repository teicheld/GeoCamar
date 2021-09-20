<?php
session_start();
?>
<html>
<link rel="stylesheet" type="text/css" href="mystyle.css">
<body>
<?php
include_once 'functions.php';
require 'connect_to_database.php';
if (isset($_SESSION["user"])) {
	if (false == $isIndexLoaded) {
?>		<div class="topnav">
			<a href="index.php">Index</a>
			<a href="my_trades.php">Meine Handel</a>
<?php			require 'message_counter_link.php';
?>		</div>
<?php	}

	$user = $_SESSION["user"];
	$role = $_GET["role"];
	$status = $_GET["status"];

	if ("vendor" == $role) {
?>		<div class="css_box_medium">
<?php			echo "<h3>Ich als  Verkäufer</h3>";
?>		</div>
<?php		if ("no_payment" == $status) {
			print_my_offerings($user, $conn);
		}
		if ("reserved" == $status) {
			sql_free_old_unpaid_reservations($conn);
			print_reserved_offerings($user, $conn);
		}
		if ("confirmed_payment" == $status) {
			$issetDoc = true;
			if (are_deals_in_escrow($user, $role, $issetDoc, $conn)) {
				print_deals_in_escrow($user, $role, $issetDoc, $conn);
			} else {
				$noDealsWithDoc = true;
			}
			$issetDoc = false;
			if (are_deals_in_escrow($user, $role, $issetDoc, $conn)) {
				print_deals_in_escrow($user, $role, $issetDoc, $conn);
			} else {
				$noDealsWithoutDoc = true;
			}
		}
		if ("done" == $status) {
			print_deals_done($user, $role, $conn);
			////- abgeschlossene Handel
		}
	}
	if ("client" == $role) {
		echo '<div class="css_box_medium"><h3>Ich als Käufer</h3></div>';
		if ("reserved" == $status) {
			if (are_unpaid_purchases($user, $conn)) {
				print_unpaid_purchases($user, $conn);
			} else {
				$noUnpaid = true;
			}
			if (are_unconfirmed_purchases($user, $conn)) {
				print_unconfirmed_purchases($user, $conn);
			} else {
				$noUnconfirmed = true;
			}
		}
		if ("confirmed_payment" == $status) {
			$issetDoc = true;
			if (are_deals_in_escrow($user, $role, $issetDoc, $conn)) { 
				print_deals_in_escrow($user, $role, $issetDoc, $conn);
			} else {
				$noDealsWithDoc = true;
			}
			$issetDoc = false;
			if (are_deals_in_escrow($user, $role, $issetDoc, $conn)) {
				print_deals_in_escrow($user, $role, $issetDoc, $conn);
			} else {
				$noDealsWithoutDoc = true;
			}
		} elseif ("done" == $status) {
			print_deals_done($user, $role, $conn);
		}
	}
				$noUnconfirmed = true;
	if ( ($noDealsWithoutDoc && $noDealsWithDoc) || ($noUnpaid && $noUnconfirmed) ) {
		echo "Es sind keine derartige Eintreage in der Datenbank";
	}

} else {
	require 'post_login.php';
}
?>
</body>
</html>	
