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
	require 'connect_to_database.php';

	$sql_messages = "SELECT id, creator, topic, message FROM messages WHERE recipient='$user'";
	$result = mysqli_query($conn, $sql_messages);

	if (mysqli_num_rows($result) > 0) {
	  // output data of each row
	  while($row = mysqli_fetch_assoc($result)) {
	?> 
		<div class="css_box_long">
			<form action="delete_messages.php" method="post">
			<input type="checkbox" value="<?php echo $row["id"]; ?>" name="deleteMessages[]">
			<?php echo "From: ".$row["creator"]."<br>";?>
			<?php echo "Betreff: ".$row["topic"]."<br>";?>
			<textarea rows="10" cols="68">
<?php
			echo $row["message"];
			?>
			</textarea>
		</div>
		<?php
		  }
	?><input type="submit" value="delete selected">
			</form>
	<?php
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
