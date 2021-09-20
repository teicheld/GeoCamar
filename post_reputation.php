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
?>			<div class="css_box_medium">
			<form action="send_reputation.php" method="post">
				<h3>Bewerte <?php echo $vendor; ?></h3>
				<input type="radio" id="-5_points" name="rating" value=-5>
				<label for="-5_points">-5, dreckiger Spammer</label><br>
				<input type="radio" id="-1_points" name="rating" value=-1>
				<label for="-1_points">-1, unprofessionell</label><br>
				<input type="radio" id="0_Points" name="rating" value=0>
				<label for="0_points">0, neutral</label><br>
				<input type="radio" id="1_points" name="rating" value=1>
				<label for="1_points">+1, zufriedenstellend</label><br>
				<input type="radio" id="2_points" name="rating" value=2>
				<label for="2_points">+2, ueberragend</label><br>
				</div>
				<br><br>
				
				<textarea name="message" rows="9" cols="38" placeholder="Meine Erfahrung mit <?php echo $vendor; ?>"></textarea>
				<input type="hidden" name="target" value="<?php echo $vendor; ?>">
				<input type="hidden" name="listingId" value="<?php echo $listingId; ?>">
				<br>
				<input type="submit" value="veroeffentliche Bewertung" class="button">
			</form>
<?php	} else {
		require 'post_login.php';
	}
?>
</body>
</html>	
