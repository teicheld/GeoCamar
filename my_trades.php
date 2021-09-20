<?php
session_start();
?>
<html>
<link rel="stylesheet" type="text/css" href="mystyle.css">
<body>
<?php
include_once 'connect_to_database.php';
include_once 'functions.php';
if (isset($_SESSION["user"])) {
?>	<div class="topnav">
		<a href="index.php">Index</a>
<?php		require 'message_counter_link.php';
?>	</div>

<?php	$user = $_SESSION["user"];
	$amountOfVendorsDealsInEscrow = ( count_deals_in_escrow($user, "vendor", true, $conn) + count_deals_in_escrow($user, "vendor", false, $conn) );
	$amountOfClientsDealsInEscrow = ( count_deals_in_escrow($user, "client", true, $conn) + count_deals_in_escrow($user, "client", false, $conn) );
?>	<div class="css_box_medium">
		<h3>Ich als Verkäufer:</h3>
		<dl>
		<dt><a href="home.php?role=vendor&status=no_payment">Angebote ohne Kunden (<?php echo count_my_offerings($user, $conn); ?>)</a></dt>
		<br>
		<dt>Angebote mit Kunden:</dt>
		<dd><a href="home.php?role=vendor&status=reserved">- reservierte Angebote (<?php echo count_reserved_offerings($user, $conn); ?>)</a></dd>
		<dd><a href="home.php?role=vendor&status=confirmed_payment">- Geld im Escrow (<?php echo $amountOfVendorsDealsInEscrow; ?>)</a></dd>
		<dd><a href="home.php?role=vendor&status=done">- abgeschlossene (<?php echo count_transacted_deals($user, "vendor", $conn); ?>)</a></dd>
		</dl>
	</div>
	<div class="css_box_medium">
		<h3>Ich als Käufer:</h3>
		<dl>
		<dt><a href="home.php?role=client&status=reserved">reservierte Angebote (<?php echo count_reserved_purchases($user, $conn); ?>)</a></dt>
		<br>
		<dt>bezahlte Handel:</dt>
		<dd><a href="home.php?role=client&status=confirmed_payment">- Geld im Escrow (<?php echo $amountOfClientsDealsInEscrow; ?>)</a></dd>
		<dd><a href="home.php?role=client&status=done">- abgeschlossene (<?php echo count_transacted_deals($user, "client", $conn); ?>)</a></dd>
		</dl>
	</div>
<?php
} else {
	require 'post_login.php';
}
?>
</body>
</html>	
