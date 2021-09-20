<?php
session_start();
?>
<html>
<title>GeoCamar</title>
<link rel="stylesheet" type="text/css" href="mystyle.css">
<body>
<?php
$isIndexLoaded = true;
$user = $_SESSION['user'];
include_once 'connect_to_database.php';
include_once 'functions.php';
if (are_deals_in_escrow($user, "vendor", $issetdoc, $conn)) {
	echo '<div class="css_box_long"><a href="home.php?role=vendor&status=confirmed_payment">Ein Kunde hat bezahlt und wartet auf die Offenbarung des versteckten Ortes</a></div>';
}
sql_free_old_unpaid_reservations($conn);
write_accountbalances($user, $conn);
?>
<div class="topnav">
	<?php 
	if (isset($_SESSION["user"])) {
		$user = $_SESSION['user'];
		?>
		<a href="post_listing.php">Erstelle ein Angebot</a>
		<a href="my_trades.php">Meine Handel</a>
		<?php require 'message_counter_link.php'; ?>
		<?php print_account_balance($user, $conn); ?>
		<a href="logout.php">abmelden</a>
		<?php
	} else {
		?>
		<a href="post_login.php">anmelden</a>
		<a href="post_register.php">registrieren</a>
		<a href="about.html">ueber</a>
		<?php
	}
	?>
</div>
<?php							/////////todo: check timestamp_double_client_prevention and change the client value to NULL if the time is over
							////////todo: vendor bond
//							/////// todo: ratings
//							/////// todo: network of trust
//							//todo: open orders client
print_listings($user, $conn);
export_gpx("listings.gpx", $conn);
?>
<br><a href="listings.gpx">Lade die ungefaehre Positionen aller Angebote fuer die Integration in dein GPS herunter</a>
</body>
</html>	
