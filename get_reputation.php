<?php
session_start();
?>
<html>
<link rel="stylesheet" type="text/css" href="mystyle.css">
<body>
<div class="topnav">
	<a href="index.php">Index</a>
	<a href="my_trades.php">Meine Handel</a>
</div>
<?php
if ( isset($_SESSION["user"]) ) {
	$user = $_SESSION["user"];
	$target = $_GET["target"];
	require 'connect_to_database.php';

	$sql_messages = "SELECT id, creator, rating, message FROM reputations WHERE target = '$target'";
	$result = mysqli_query($conn, $sql_messages);

	if (mysqli_num_rows($result) > 0) {
	  // output data of each row
		while($row = mysqli_fetch_assoc($result)) {
			$creator = $row['creator'];
			$link_to_home_of_creator = "<a href=\"vendor_shop.php?vendor=$creator\">$creator</a>";
?> 
			<div class="css_box_medium">
<?php				echo "Rating: ".$row['rating']."<br>";
				echo "From: ".$link_to_home_of_creator; 
				echo "<br>";
				echo $row["message"];
?>
			</div>
			<br><br>
<?php		}
	} else {
		echo "0 results";
	}

} else {
	echo "not logged in, you idiot";
	require 'post_login.php';
}
?>
</body>
</html>	
