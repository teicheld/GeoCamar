<?php
session_start();
?>
<html>
<body>

<?php
require 'connect_to_database.php';
$name = $_POST["name"];
$password = $_POST["password"];
if ( empty( $name ) || empty( $password ))
	exit("the master doesnt allow emptyness!");
$sql = "SELECT * FROM users WHERE name='$name' AND password='$password'";
$result = mysqli_query($conn, $sql);
$fetched_result = mysqli_fetch_assoc($result); 
if ($result) {
	if ($fetched_result["name"] == $name) {
	$_SESSION["user"] = $name;
	} else {
		echo "login failed";
	}
} else {
	echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
require 'index.php';
?>
</body>
</html>	
