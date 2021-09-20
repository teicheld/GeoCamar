<?php
session_start();
?>
<html>
<link rel="stylesheet" type="text/css" href="mystyle.css">
<body>
<?php
if ( isset($_SESSION["user"]) && isset($_GET["vendor"]) ) {
	include_once 'functions.php';
	include_once 'connect_to_database.php';
	$vendor = $_GET["vendor"];

	?>	<div class="topnav">
			<a href="index.php">Index</a>
			<a href="my_trades.php">Meine Handel</a>
	<?php           require 'message_counter_link.php';
	?>	</div>
<?php
	echo "<a href='get_reputation.php?target=$vendor'>ReputationS</a>ore: ".get_reputation_score($target = $vendor, $conn);
	?><h3>Verstecke von <?php echo $vendor; ?></h3>
	<br><a href="post_message.php?vendor=<?php echo $vendor; ?>">Sende eine Nachricht an den Verkäufer</a>
	<br><br><br>
	<?php
	include_once 'connect_to_database.php';


	   $sql = "SELECT id, item, quantity, price, vendor, image FROM listings WHERE vendor='$vendor' AND client IS NULL";
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) {
	  // output data of each row
		while($row = mysqli_fetch_assoc($result)) {
			?><div class="css_box_small">
				<?php echo $row["item"]; ?>
				<img src="images/item/<?php echo $row['image']; ?>" alt="no picture">
				<br><?php echo $row["quantity"]." gramm"; ?>
				<br><?php echo $row["price"]." Euro"; ?>
				<br><?php printf ("%.2f Gramm/Euro\n", $row["price"]/$row["quantity"]); ?>
				<form action="escrow.php" method="post">
				<input type="hidden" id="id" name="id" value="<?php echo $row["id"]; ?>">
				<input type="submit" value="buy" button class="button"></button>
				</form>
			</div>
		<?php
	  }
	} else {
	  echo "0 results";
	}
} else {
	require "post_login.php";
}

?>
</body>
</html>	
