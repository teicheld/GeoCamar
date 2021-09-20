<?php
session_start();
?>
<html>
<body>

<?php
include_once 'connect_to_database.php';
include_once 'functions.php';
$user = $_SESSION["user"];

if ( isset($_SESSION["user"]) && isset($_POST["listingId"]) ) {
	include_once 'connect_to_database.php';
	$listingId = $_POST["listingId"];
	$documentationLink = $_POST["documentationLink"];
	$sql = "UPDATE listings 
		SET documentation_link = '$documentationLink' 
		WHERE id = '$listingId'"; 
	if (mysqli_query($conn, $sql)) {
		$creator = 'System';
		$recipient = get_client($_POST["listingId"], $conn);
		$item_name = get_item_name($listingId, $conn);
		$topic = 'Schuhe an, Schaufel dabei...';
		$message = "Der Verkaeufer $user hat Ihnen den Link zur Dokumentation des Versteckten Ortes geschickt, andem Ihre Ware ist ($item_name). Oeffnen Sie den Link nur ueber den Tor-Browser. Geben Sie das Geld im Escrow nach dem Fund der Ware an den Verkeaufer frei unter \"Meine Handel -> Ich als Kaeufer -> bezahlte Handel: -Geld im Escrow\"
Der Link lautet: $documentationLink";
		send_message($creator, $recipient, $topic, $message, $conn);
		start_timer_escrow_to_vendor($listingId, $conn);
		stop_timer_escrow_to_client($listingId, $conn);
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
	require 'my_trades.php';
} else {
	require 'post_login.php';
}
?>
</body>
</html>	
