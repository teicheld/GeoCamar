<?php
$servername = "localhost";
$username = "the_anarchist";
$password = "Aa,asdf;lkjasdf;lkj";
$dbname = "public_drop";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
?>
