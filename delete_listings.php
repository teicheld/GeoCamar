<?php
session_start();
?>
<html>
<body>
<?php
if ( isset($_SESSION["user"]) && isset($_POST["deleteListings"]) ) {
	include_once 'connect_to_database.php';
	$deleteListings = ($_POST["deleteListings"]);
	for ($i=0; $i<count($deleteListings); $i++) {
		$sql_image = "SELECT image
				FROM listings
				WHERE id=$deleteListings[$i]";
			if (!$result_image = mysqli_query($conn, $sql_image)) {
				echo "Error: " . $sql_delete . "<br>" . mysqli_error($conn);
			}
			$row = mysqli_fetch_assoc($result_image);
			$filename=$row["image"];
			exec("rm images/item/$filename");

		$sql_delete = "DELETE FROM listings
			WHERE id=$deleteListings[$i]";
			if (!$result = mysqli_query($conn, $sql_delete)) {
				echo "Error: " . $sql_delete . "<br>" . mysqli_error($conn);
			}
	}
} else {
	echo "error";
}
require 'my_trades.php';
?>
</body>
</html>	
