<?php
session_start();
?>
<html>
<head>
  <title>Verkaufen</title>
  <link rel="stylesheet" href="mystyle.css">
</head>
<body>
<div class="topnav">
	<a href="index.php">Index</a>
        <?php require 'message_counter_link.php'; ?>
</div>
<h4>Ich habe etwas versteckt und moechte es anbieten.</h4>
<?php
if (isset($_SESSION["user"])) {
	?>

	<form action="send_listing_to_database.php" method="post" enctype="multipart/form-data">
		<input type="text" name="item" required placeholder="Name"><br>
		<input type="text" name="quantity" required placeholder="Menge"><br>
		<input type="text" name="price" required placeholder="Preis in Euro"><br>
		<span style="color: green">ungefähre GPS Koordinaten in Dezimalgrad:</span><br>
		<input type="text" name="latitude" required placeholder="latitude Bsp.: 51.12345">
		<input type="text" name="longitude" required placeholder="longitude Bsp.: 10.12345"><br>
		<span style="color: green">Foto:(max 200kb)</span>
		<input type="file" name="fileToUpload" id="fileToUpload">
		<input type="hidden" name="vendor" value=<?php echo $_SESSION["user"]; ?>><br>
		<br><img id="captcha" src="/securimage/securimage_show.php" alt="CAPTCHA Image"/><br>
		<input type="text" name="captcha_code" size="35" maxlength="6" placeholder="deaktivated for testing purose"/><br> <!--set to required-->
		<!--<input type="text" name="captcha_code" size="35" maxlength="6" placeholder="Des Spammers Hürde ('Strg + R' fürn Neues)"/><br> <!--set to required-->-->
		<input type="submit" value="Auf den Markt setzen" button class="button"></button>
	</form>
	<?php
} else {
	echo "sign in first";
	require 'post_login.php';
}
?>
</body>
</html>	
