<!DOCTYPE html>
<html>
<head>
  <title>Anmelden</title>
  <link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
<div class="topnav">
	<a href="index.php">index</a>
</div>
<?php	include_once 'functions.php';
?>

<form action="send_registration_to_database.php" method="post"/><br>
	<input type="text" name="name" required placeholder="Name"/>
	<div class="inconspicuous">
		<a href="post_register_with_namesuggestions.php">Namengenerator</a> <br>
	</div>
	<input type="password" name="password" required placeholder="Passwort"/><br><br><br>
	<textarea name="pgp_pub_key" rows="20" cols="35" required placeholder="Öffentlicher PGP Schlüssel"/></textarea><br>
	<br><img id="captcha" src="/securimage/securimage_show.php" alt="CAPTCHA Image"/><br>
	<input type="text" name="captcha_code" size="35" maxlength="6" placeholder="Captcha deaktivated for testing purposes"/><br>
	<!--<input type="text" name="captcha_code" size="35" maxlength="6" required placeholder="Des Spammers Hürde ('Strg + R' fürn Neues)"/><br>-->
	<input type="submit" value="register" button class="button"></button>
</form>


</body>
</html>	

 `
