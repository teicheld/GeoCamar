
<?php
session_start();
?>
<html>
<title>local_crypto_market_pgp_keys</title>
<link rel="stylesheet" type="text/css" href="mystyle.css">
<body>

<?php
if ( isset($_SESSION["user"]) && isset($_GET["public"]) ) {
	require 'connect_to_database.php';
	$owner = $_GET["public"];
	$sql_pgp_pub_keys= "SELECT pgp_pub_key FROM users WHERE name='$owner'";
	$result = mysqli_query($conn, $sql_pgp_pub_keys);
	$row = mysqli_fetch_assoc($result);
	?><textarea rows="10" cols="68"><?php echo $row['pgp_pub_key']; ?></textarea><?php
} else { 
	echo "try hacking me again. Maybe with all variables set...";
}
?>
</body>
</html>	
