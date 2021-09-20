<?php
session_start();
?>
<html>
<body>
<?php
require 'connect_to_database.php';
if (isset($_SESSION["user"])) {
	$user = $_SESSION["user"];
	$sql = "SELECT balance 
		FROM escrow_keys WHERE owner = '$user'";
	if ($result = mysqli_query($conn, $sql)) {
		$balaceSum = 0;
		while ($row = mysqli_fetch_assoc($result)) {
			$balanceSum += $row['balance'];
		}
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
	echo "account balance: " . $balanceSum . " Satoshi";
} else {
	require 'login.php';
}
?>

</body>
</html>	
