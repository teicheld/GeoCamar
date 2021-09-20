<?php
session_start();
?>
<html>
<head>
	<link rel="stylesheet" href="mystyle.css">
</head>
<body>
<?php
if ( isset($_SESSION["user"]) && isset($_POST["listingId"]) ) {
	$user = $_SESSION["user"];
	$listingId = $_POST["listingId"];
	include_once 'functions.php';
	include_once 'connect_to_database.php';

	if ( !isset($_POST["release"]) ) {

		/* ///the post manipulation attack protection -> he is not able to edit foreign listing specifications;//
		sqlControl = "SELECT client 
			FROM listings 
			WHERE id = '$listingId'";
		if ($resultControl = mysqli_query($conn, $sqlControl)) {
			rowListingsControl = mysqli_fetch_assoc($resultControl)) {
			if ($rowListingsControl["client"] == $_SESSION["user"]) {
				//access garanted
			} else {
				//access denied
		} else {
			echo "Error: " . $sqlControl . "<br>" . mysqli_error($conn);
		}
		 */
		echo "Achtung! Nachdem das Geld freigegeben ist, haben Sie keine Chance mehr, zu streiten.";
	?>	<form action="" method="post">
		<input type="hidden" id="listingsId" name="listingId" value=<?php echo $listingId; ?>>
			<input type="hidden" id="release" name="release" value=true>
			<input type="submit" value="Der Handel ist abgeschlossen" button class="button"></button>
		</form>
	<?php	
		//set expected get vars for home.php
		$_GET["me"] = "client";
		$_GET["status"] = "confirmed_payment";
		require 'home.php';
//>>>>>>>>>>>>>>>>>>>>2nd page, after the button>>>>>>>>>>>>>>>>>>>>>>>>
	} elseif (isset($_POST["release"])) {
		if (true == $_POST["release"]) {
			$time = time();
			$sqlListings = "UPDATE listings 
				SET status = 'done' 
				WHERE id = '$listingId'"; 
			if (mysqli_query($conn, $sqlListings)) {
			} else {

			}
			$rowListings = sql_select_listings($listingId, $conn);
			$vendor = $rowListings["vendor"];
			$sqlEscrow = "UPDATE escrow_keys 
				SET owner = '$vendor' 
				WHERE id = '$listingId'"; 
			if (mysqli_query($conn, $sqlEscrow)) {
				$creator = 'system';
				$recipient = $vendor;
				$topic = 'Handel abgeschlossen';
				$item = get_item_name($listingId, $conn);
				$message = "Der Artikel \"$item\" ist verkauft. Das Geld im Escrow ist Ihrem Konto gutgeschrieben. Klicken Sie auf Ihren Kontostand um das Geld zu ueberweisen.";
				send_message($creator, $recipient, $topic, $message, $conn);

				echo "Escrow freigegeben";
				include 'post_reputation.php';
			} else {
			  echo "Error: " . $sqlEscrow . "<br>" . mysqli_error($conn);
			}
		}
	}
} else {
	require 'post_login.php';
}
?>
</body>
</html>	
