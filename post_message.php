<?php
session_start();
?>
<html>
<head>
        <link rel="stylesheet" href="mystyle.css">
</head>
<body>
	<div class="topnav">
		<a href="index.php">Index</a>
		<a href="post_listing.php">Erstelle ein Angebot</a>
		<a href="my_trades.php">Meine Handel</a>
                <?php require 'message_counter_link.php'; ?>
	</div>
<?php	if (isset($_SESSION["user"])) {
		$user = $_SESSION["user"];
		$vendor = $_GET["vendor"];
?>	
			<form action="send_message.php" method="post">
				<h3>Benachrichtige <?php echo $vendor; ?><a href="get_pgp_key.php?public=<?php echo $vendor;?>" target="_blank"></h3>
				<p>Schauste ihren/seinen PGP Schlüssel.</a></p>
				Betreff:<input type="text" name="topic"><br>
				<textarea name="message" rows="15" cols="48"></textarea>
				<input type="hidden" name="recipient" value=<?php echo $vendor; ?>>
				<br>
				<input type="submit" value="send message" class="button">
			</form>
<?php	} else {
		require 'post_login.php';
	}
?>
</body>
</html>	
