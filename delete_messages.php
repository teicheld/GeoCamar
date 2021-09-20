<?php
session_start();
?>
<html>
<head>
</head>
<body>

<?php
if ( isset($_SESSION["user"]) && isset($_POST["deleteMessages"]) ) {
	require 'connect_to_database.php';

	$deleteMessages = ($_POST["deleteMessages"]);
	for ($i=0; $i<count($deleteMessages); $i++) {
	   $sql = "DELETE FROM messages
		   WHERE id=$deleteMessages[$i]";
	   $result = mysqli_query($conn, $sql);
	   if ($result) {
	   } else {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	   }
	}
	require 'get_messages.php';
} else {
	echo "error";
}
?>
</body>
</html>	
