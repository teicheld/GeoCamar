<?php



////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////

function sql_select_listings($id, $conn) {
	$sqllistings = "select * 
		from listings 
		where id = '$id'";
	if ( !$resultlistings = mysqli_query($conn, $sqllistings) ) {
		echo "error1: " . $sqllistings . "<br>" . mysqli_error($conn);
		return "error";
	} else {
		$rowlistings = mysqli_fetch_assoc($resultlistings); 
		return $rowlistings;
	}
}

////////////////////////////////////////////////////////////////////////////


function sql_select_escrow($id, $conn) {
	$sqlescrow = "select * 
		from escrow_keys
		where id = '$id'";
	if ( !$resultescrow = mysqli_query($conn, $sqlescrow) ) {
		echo "error1: " . $sqlescrow . "<br>" . mysqli_error($conn);
		return "error";
	} else {
		$rowescrow = mysqli_fetch_assoc($resultescrow); 
		return $rowescrow;
	}	
}

////////////////////////////////////////////////////////////////////////////

function namegenerator($amount, $conn) {
	$sqlsuggestion_namescount = "select count(name)
					from suggestion_names"; 
	if ( !$resultsuggestion_namescount = mysqli_query($conn, $sqlsuggestion_namescount) ) {
		echo "error1: " . $sqlsuggestion_namescount . "<br>" . mysqli_error($conn);
		return "error";
	} else {
		$rowsuggestion_namescount = mysqli_fetch_assoc($resultsuggestion_namescount); 
	}
	echo "<br>";
?>		<h4>Namensvorschläge</h4>
<?php 
	for ($i = 0; $i < $amount; $i++) {
		$id = rand(1, $rowsuggestion_namescount['count(name)']);
		$sqlsuggestion_names = "select name
			from suggestion_names 
			where id = '$id'";
		if ( !$resultsuggestion_names = mysqli_query($conn, $sqlsuggestion_names) ) {
			echo "error1: " . $sqlsuggestion_names . "<br>" . mysqli_error($conn);
			return "error";
		} else {
			$rowsuggestion_names = mysqli_fetch_assoc($resultsuggestion_names); 
			$names[$i] = $rowsuggestion_names["name"];
			?> <span style="color: yellow"><?php echo $names[$i]; ?></span><br> <?php 
		} 
	}
	return $names;
}

////////////////////////////////////////////////////////////////////////////


function sql_select_users_names($conn) {
	$sqllistings = "select name 
		from users";
	if ( !$resultlistings = mysqli_query($conn, $sqllistings) ) {
		echo "error1: " . $sqllistings . "<br>" . mysqli_error($conn);
		return "error";
	} else {
		$rowlistings = mysqli_fetch_all($resultlistings); 
		return $rowlistings;
	}
}

////////////////////////////////////////////////////////////////////////////////////

function count_reserved_purchases($user, $conn) {
	$sql_unconfirmed = "select count(id)
		from listings where client = '$user' and status = 'unconfirmed_payment'";
	$sql_no_payment = "select count(id)
		from listings where client = '$user' and status = 'no_payment'";
	if (!$result_no_payment = mysqli_query($conn, $sql_no_payment)) {
		echo "error: ".$sql."<br>".mysqli_error($conn);
	}
	if (!$result_unconfirmed = mysqli_query($conn, $sql_unconfirmed)) {
		echo "error: ".$sql."<br>".mysqli_error($conn);
	}
		$row_no_payment = mysqli_fetch_assoc($result_no_payment); 
		$row_unconfirmed = mysqli_fetch_assoc($result_unconfirmed); 
		return ( $row_unconfirmed['count(id)'] + $row_no_payment['count(id)'] );
}

//////////////////////////////////////////////////////////////////////////////////////


function are_unpaid_purchases($user, $conn) {
	
	$sql = "SELECT id
		FROM listings 
		WHERE client = '$user' AND status = 'no_payment'";
	if ( !$result = mysqli_query($conn, $sql) ) {
		echo "error1: " . $sql . "<br>" . mysqli_error($conn);
		return "error";
	} else {
		// and here the listings are generated
		if (mysqli_num_rows($result) > 0) {
			return true;	
		} else {
			return false;	
		}
	}
}

////////////////////////////////////////////////////////////////////////////////////


function print_unpaid_purchases($user, $conn) {
	//selfcall at cancel order button
	if (isset($_post["id"])) {
		cancel_order($_post['id'], $conn);
	}
	$sql = "select id, item, quantity, price, vendor, image, timestamp_double_client_prevention	
		from listings where client = '$user' and status = 'no_payment'";
	if ( !$result = mysqli_query($conn, $sql) ) {
		echo "error1: " . $sql . "<br>" . mysqli_error($conn);
		return "error";
	} else {
		// and here the listings are generated
		if (mysqli_num_rows($result) > 0) {
		  // output data of each row
			while($row = mysqli_fetch_assoc($result)) {

			//when is the relaunch of the blocked listings?  
			$configvars = parse_ini_file("config.txt");
			$preventiontime = $configvars["preventiontime"];
			$secsincetsdcp = time() - $row["timestamp_double_client_prevention"];
			$secleft = $preventiontime - $secsincetsdcp;


		?>              <br>
				<div class="css_box_small">
		<?php                   echo $row["item"]; ?>
					<img src="images/item/<?php echo $row['image']; ?>" alt="item pic">
					<br><?php echo $row["quantity"]." gramm"; ?>
					<br><?php echo $row["price"]." euro"; ?>
					<br><?php printf ("%.2f euro/gramm\n", $row["price"]/$row["quantity"]); ?>
					<br><a href="vendor_shop.php?vendor=<?php echo $row["vendor"]; ?>"><?php echo $row["vendor"]; ?></a>
					<form action="escrow.php" method="post">
					<input type="hidden" id="id" name="id" value="<?php echo $row["id"]; ?>">
					<input type="submit" value="bezahlen" button class="button"></button>
					</form>
					<form action="" method="post">
					<input type="hidden" id="id" name="id" value="<?php echo $row["id"]; ?>">
					<input type="submit" value="kauf abbrechen" button class="button"></button>
					<br><?php echo "autoabbruch in ".$secleft." sekunden"; ?>
					</form>
				</div>
			<?php
			}
		} else {
			echo "Es sind keine reservierten Angebote in der Datenbank";
		}
	}
}



//////////////////////////////////////////////////////////////////////////////////////


function are_unconfirmed_purchases($user, $conn) {
	
	$sql = "SELECT id	
		FROM listings
		WHERE client = '$user' AND status = 'unconfirmed_payment'";
	if ( !$result = mysqli_query($conn, $sql) ) {
		echo "error1: " . $sql . "<br>" . mysqli_error($conn);
		return "error";
	} else {
		// and here the listings are generated
		if (mysqli_num_rows($result) > 0) {
			return true;	
		} else {
			return false;	
		}
	}
}


//////////////////////////////////////////////////////////////////////////////////////

function print_unconfirmed_purchases($user, $conn) {
	
	$sql = "select id, item, quantity, price, vendor, image	
		from listings where client = '$user' and status = 'unconfirmed_payment'";
	if ( !$result = mysqli_query($conn, $sql) ) {
		echo "error1: " . $sql . "<br>" . mysqli_error($conn);
		return "error";
	} else {
		// and here the listings are generated
		if (mysqli_num_rows($result) > 0) {
			echo '<div class="css_box_long">  Transaktionen mit weniger als 6 Blockchainbesteatigungen.  </div>';
		  // output data of each row
			while($row = mysqli_fetch_assoc($result)) {

		?>              <br>
				<div class="css_box_small">
		<?php                   echo $row["item"]; ?>
					<img src="images/item/<?php echo $row['image']; ?>" alt="item pic">
					<br><?php echo $row["quantity"]." gramm"; ?>
					<br><?php echo $row["price"]." euro"; ?>
					<br><?php printf ("%.2f euro/gramm\n", $row["price"]/$row["quantity"]); ?>
					<br><a href="vendor_shop.php?vendor=<?php echo $row["vendor"]; ?>"><?php echo $row["vendor"]; ?></a>
					<form action="escrow.php" method="post">
					<input type="hidden" id="id" name="id" value="<?php echo $row["id"]; ?>">
					<input type="submit" value="Uebersicht" button class="button"></button>
					</form>

				</div>
			<?php
			}
		} else {
			echo "Es sind keine reservierten Angebote in der Datenbank";
		}
	}
}


//////////////////////////////////////////////////////////////////////////////////////

function cancel_order($id, $conn) {
	$sqlupdatelistings = "update listings
		set timestamp_double_client_prevention = 0, client = null
		where id = '$id'";
	$resultupdatelistings = mysqli_query($conn, $sqlupdatelistings);
	if (!$resultupdatelistings) {
		echo "errorupdate: ".$sqlupdatelistings."<br>".mysqli_error($conn);
	}
	//update rowlistings
	$sqlselectlistings = "select *
		from listings
		where id = '$id'";
	if ( !$resultlistings = mysqli_query($conn, $sqlselectlistings) ) {
		echo "errorselect: ".$sqlselectlistings."<br>".mysqli_error($conn);
		return "error";
	} else {
		$rowlistings = mysqli_fetch_assoc($resultlistings);
		return $rowlistings;
	}
}


//////////////////////////////////////////////////////////////////////////////////////

function are_deals_in_escrow($user, $role, $issetDoc, $conn) {
	if ("vendor" == $role) {
		if ($issetDoc) {
			$sql_listings = "SELECT id, item, price, quantity, client, documentation_link, image 
				FROM listings
				WHERE vendor = '$user' AND status = 'confirmed_payment' AND documentation_link IS NOT NULL";
		} else {
			$sql_listings = "select id, item, price, quantity, client, documentation_link, image 
				FROM listings 
				WHERE vendor = '$user' AND status = 'confirmed_payment' AND documentation_link IS NULL";
		}
	} elseif ("client" == $role) {
		if ($issetDoc) {
			$sql_listings = "SELECT id, item, price, quantity, vendor, documentation_link, image 
				FROM listings 
				WHERE client = '$user' AND status = 'confirmed_payment' AND documentation_link IS NOT NULL";
		} else {
			$sql_listings = "SELECT id, item, price, quantity, vendor, image 
				FROM listings 
				WHERE client = '$user' AND status = 'confirmed_payment' AND documentation_link IS NULL";
			}
	}
	if ($result_listings = mysqli_query($conn, $sql_listings)) {
		if (mysqli_num_rows($result_listings) > 0) {
			return true;
		} else {
			return false;
		}
	} else {
		echo "Error: " . $sql_listings . "<br>" . mysqli_error($conn);
	}
}



////////////////////////////////////////////////////////////////////////////////////

function count_reserved_offerings($user, $conn) {
	$sqlNoPayment = "SELECT COUNT(id)
			FROM listings 
			WHERE vendor='$user' AND client IS NOT NULL AND status = 'no_payment'";
	$sqlUnconfirmedPayment = "SELECT COUNT(id)
			FROM listings 
			WHERE vendor='$user' AND client IS NOT NULL AND status = 'unconfirmed_payment'";
	if ($resultNoPayment = mysqli_query($conn, $sqlNoPayment)) {
		$rowNoPayment = mysqli_fetch_assoc($resultNoPayment);
	} else {
		echo "Error: ".$sqlNoPayment."<br>".mysqli_error($conn);
	}

	if ($resultUnconfirmedPayment = mysqli_query($conn, $sqlUnconfirmedPayment)) {
		$rowUnconfirmedPayment = mysqli_fetch_assoc($resultUnconfirmedPayment); 
	} else {
		echo "Error: ".$sqlUnconfirmedPayment."<br>".mysqli_error($conn);
	}

	if ($resultNoPayment && $resultUnconfirmedPayment) {
		return ( $rowNoPayment['COUNT(id)'] + $rowUnconfirmedPayment['COUNT(id)'] );
	}
}


////////////////////////////////////////////////////////////////////////////////////

function print_reserved_offerings($user, $conn) {
	$sql_no_payment = "SELECT id, item, price, quantity, image, client, status, timestamp_double_client_prevention
			FROM listings 
			WHERE vendor='$user' AND client IS NOT NULL AND status = 'no_payment'";

	$sql_unconfirmed_payment = "SELECT id, item, price, quantity, image, client, status
			FROM listings 
			WHERE vendor='$user' AND client IS NOT NULL AND status = 'unconfirmed_payment'";

	$result_no_payment = mysqli_query($conn, $sql_no_payment);
	$result_unconfirmed_payment = mysqli_query($conn, $sql_unconfirmed_payment);
/*
	if (mysqli_num_rows($result_no_payment) > 0 || mysqli_num_rows($result_unconfirmed_payment) > 0) {
?>		<div class="css_box_long">
			<h3>Meine reservierten Angebote:</h3>
			<i>Nichtbazahlte und jene, mit weniger als 6 Blockchain-bestätigungen.</i>
		</div>
<?php	}
 */
	if ($result_no_payment) {
		if (mysqli_num_rows($result_no_payment) > 0 ) {
			echo "<h4>Reservierte Angebote, da der Kunde eventuell gleich bezahlt:</h4>";
			while($row_no = mysqli_fetch_assoc($result_no_payment)) {
			
				//when is the relaunch of the blocked listings?
				$configVars = parse_ini_file("config.txt");
				$preventionTime = $configVars["preventionTime"];
				$secSinceTsDCP = time() - $row_no["timestamp_double_client_prevention"];
				$secLeft = $preventionTime - $secSinceTsDCP;
				
		?>      	<br>
				<div class="css_box_small">
		<?php   		echo $row["item"]; 
		?>                      <img src="images/item/<?php echo $row_no['image']; ?>" alt="item pic">
					<br><?php echo $row_no["quantity"]." gramm"; ?>
					<br><?php echo $row_no["price"]." Euro"; ?>
					<br><?php printf ("%.2f Euro/Gramm\n", $row_no["price"]/$row_no["quantity"]); ?>
					<br><?php echo "Status: ".$row_no["status"]; ?>
					<br><?php echo "Rervierung noch ".$secLeft." Sekunden"; ?>
				</div>
		<?php   }
		} else {
			//echo "<br><i>Keine reservierten Angebote.</i><br>";
		}
	} else {
		echo "Error: ".$sql_no_payment."<br>".mysqli_error($conn);
	}

	if ($result_unconfirmed_payment) {
		if (mysqli_num_rows($result_unconfirmed_payment) > 0) {
			echo "<h4>Verkaufte Angebote, welche in weniger als 6 Bloecken besteatigt sind:</h4>";
			while($row_uncon = mysqli_fetch_assoc($result_unconfirmed_payment)) {
	?>	        	<br>
				<div class="css_box_small">
	<?php                   	echo $row["item"]; 
	?>                              <img src="images/item/<?php echo $row_uncon['image']; ?>" alt="item pic">
						<br><?php echo $row_uncon["quantity"]." gramm"; ?>
						<br><?php echo $row_uncon["price"]." Euro"; ?>
						<br><?php printf ("%.2f Euro/Gramm\n", $row_uncon["price"]/$row_uncon["quantity"]); ?>
						<br>Kunde: <a href="vendor_shop.php?vendor=<?php echo $row_uncon["client"]; ?>"><?php echo $row_uncon["client"]; ?></a>
						<br><?php echo "Status: ".$row_uncon["status"]; ?>
					</div>
	<?php		}
		} else {
			echo "<br><i>Keine verkauften Angebote, deren zugehörige Transaktionen in weniger als 6 Bloecken vorhanden sind.</i><br>";
			}
	} else {
		echo "Error: ".$sql_unconfirmed_payment."<br>".mysqli_error($conn);
	}
}

////////////////////////////////////////////////////////////////////////////////////

function count_deals_in_escrow($user, $role, $issetDoc, $conn) {
	if ("vendor" == $role) {
		if ($issetDoc) {
			$sql_listings = "SELECT COUNT(id) 
				FROM listings 
				WHERE vendor='$user' AND status = 'confirmed_payment' AND documentation_link IS NOT NULL";
		} else {
			$sql_listings = "SELECT COUNT(id) 
				FROM listings 
				WHERE vendor='$user' AND status = 'confirmed_payment' AND documentation_link IS NULL";
		}
	} elseif ("client" == $role) {
		if ($issetDoc) {
			$sql_listings = "SELECT COUNT(id)
				FROM listings 
				WHERE client='$user' AND status = 'confirmed_payment' AND documentation_link IS NOT NULL";
		} else {
			$sql_listings = "SELECT COUNT(id) 
				FROM listings 
				WHERE client='$user' AND status = 'confirmed_payment' AND documentation_link IS NULL";
		}
	}
	if ($result_listings = mysqli_query($conn, $sql_listings)) {
		$row_listings = mysqli_fetch_assoc($result_listings);
		return $row_listings['COUNT(id)'];
	} else {
		echo "Error: " . $sql_listings . "<br>" . mysqli_error($conn);
	}
}




//////////////////////////////////////////////////////////////////////////////////////

function print_deals_done($user, $role, $conn) {
	if ("vendor" == $role) {
		$sql_listings = "SELECT id, item, price, quantity, client, image 
			FROM listings 
			WHERE vendor = '$user' AND status = 'done'";
	} elseif ("client" == $role) {
		$sql_listings = "SELECT id, item, price, quantity, vendor, image 
			FROM listings 
			WHERE client = '$user' AND status = 'done'";
	}
	if ($result_listings = mysqli_query($conn, $sql_listings)) {
		if (mysqli_num_rows($result_listings) > 0) {
		echo "<h4>Folgende Handel sind abgeschlossen</h4>";
			while($row_listings = mysqli_fetch_assoc($result_listings)) {
?>				<div class="css_box_small">
					<h4><?php echo $row_listings["item"]; ?></h4>                                                                             
					<img src="images/item/<?php echo $row_listings['image']; ?>" alt="item pic">
					<?php echo $row_listings["quantity"]."gramm/".$row_listings["price"]."Euro"; ?>                                             
<?php					if ("vendor" == $role) {
?>						<br>Kunde: 
						<a href="vendor_shop.php?vendor=<?php echo $row_listings["client"]; ?>">
							<?php echo $row_listings["client"]; ?>
						</a>
<?php					} elseif ("client" == $role) {
?>						<br>Verkäufer: 
						<a href="vendor_shop.php?vendor=<?php echo $row_listings["vendor"]; ?>">
							<?php echo $row_listings["vendor"]; ?>
						</a>
<?php					}
?>				</div>
<?php			}
		} else {
		echo "<h4>Keine Abgeschlossenen Handel</h4>";
		}
	} else {
		echo "Error: " . $sql_listings . "<br>" . mysqli_error($conn);
	}
}



//////////////////////////////////////////////////////////////////////////////////////

function count_transacted_deals($user, $role, $conn) {
	if ("vendor" == $role) {
		$sql = "SELECT COUNT(id)
			FROM listings 
			WHERE vendor = '$user' AND status = 'done'";
	} elseif ("client" == $role) {
		$sql = "SELECT COUNT(id)
			FROM listings 
			WHERE client = '$user' AND status = 'done'";
	}
	if ($result = mysqli_query($conn, $sql)) {
		$row = mysqli_fetch_assoc($result); 
		return $row['COUNT(id)'];
	} else {
		echo "Error: ".$sql."<br>".mysqli_error($conn);
	}
}


//////////////////////////////////////////////////////////////////////////////////////

function alert_if_client_is_waiting_for_documentation_of_the_hidden_place($user, $conn) {
	$role = "vendor";
	$issetDoc = false;
	if (are_deals_in_escrow($user, $role, $issetDoc, $conn)) {
		echo "Ein Kunde hat bezahlt und wartet auf die ";
?>		<a href="home.php?role=vendor&status=confirmed_payment">Offenbarung</a>
<?php		echo "des Ortes";
	}
}

//////////////////////////////////////////////////////////////////////////////////////



function print_deals_in_escrow($user, $role, $issetDoc, $conn) {
	if ("vendor" == $role) {
		if ($issetDoc) {
			$sql_listings = "SELECT id, item, price, quantity, client, documentation_link, image 
				FROM listings 
				WHERE vendor = '$user' AND status = 'confirmed_payment' AND documentation_link IS NOT NULL";

			echo '<div class="css_box_long"><h4>Bei folgenden Artikeln ist die Dokumentation <u>bereits</u> an den Kunden verschickt.</h4>'.
				'<h5>Warte, bis die Gelder im Escrow durch den Kunden oder durch die automatische Freigabe freigegeben werden</h5></div>';
		} else {
			$sql_listings = "SELECT id, item, price, quantity, client, documentation_link, image 
				FROM listings 
				WHERE vendor = '$user' AND status = 'confirmed_payment' AND documentation_link IS NULL";

			echo '<div class="css_box_long"><h4>Bei folgenden Artikeln ist die Dokumentation des Ortes <u>nicht</u> an den Kunden verschickt</h4><h5>Der Kunde wird erst das Geld im Escrow an Sie freigeben, wenn er die Ware gefunden hat.</h5></div>';
		}
	} elseif ("client" == $role) {
		if ($issetDoc) {
			$sql_listings = "SELECT id, item, price, quantity, vendor, documentation_link, image 
				FROM listings 
				WHERE client = '$user' AND status = 'confirmed_payment' AND documentation_link IS NOT NULL";

			echo '<div class="css_box_long"><h4>Bei folgenden Artikeln wartet der Verkaeufer auf eine Freigabe des Geldes aus dem Escrow <u>durch mich</u> :</h4></div>';
		} else {
			$sql_listings = "SELECT id, item, price, quantity, vendor, documentation_link, image 
				FROM listings 
				WHERE client = '$user' AND status = 'confirmed_payment' AND documentation_link IS NULL";

			echo '<div class="css_box_long"><h4>Bei folgenden Artikeln warte ich auf die Offenbarung des Ortes durch den Verkeaufer. Ich werde diese unter "Nachrichten" in der Navigationsleiste oben finden.</h4></div>';
			}
	}
	if ($result_listings = mysqli_query($conn, $sql_listings)) {
		if (mysqli_num_rows($result_listings) > 0) {
		// output data of each row
			while($row_listings = mysqli_fetch_assoc($result_listings)) {
				$listingId=$row_listings["id"];
				$sql_escrow_id = "SELECT address
					FROM escrow_keys
					WHERE id='$listingId'";
				$result_escrow_id = mysqli_query($conn, $sql_escrow_id);
				if ($result_escrow_id) {
					$row_escrow_id = mysqli_fetch_assoc($result_escrow_id);
					$escrow_address = $row_escrow_id["address"];
					$file_6confirmations = fopen("https://blockchain.info/q/addressbalance/$escrow_address?confirmations=6", "r");
					$balance_escrow_address = fgets($file_6confirmations);
				} else {
					echo "Error: " . $sql_escrow_id . "<br>" . mysqli_error($conn);
				}
				?><div class="css_box_medium"><?php
					?><h4><?php echo $row_listings["item"]; ?></h4>                                                                             
					<img src="images/item/<?php echo $row_listings['image']; ?>" alt="no picture"><br>
					<?php echo $row_listings["quantity"]."gramm/".$row_listings["price"]."Euro"; ?>                                             
					<br>Kontostand im 
					<a href="https://live.blockcypher.com/btc-testnet/address/<?php echo $escrow_address; ?>" target="_blank">Escrow</a>
					: <?php echo $balance_escrow_address." Satoshi"; ?>
<?php					if ("vendor" == $role) {
?>						<br>Kunde: <a href="vendor_shop.php?vendor=<?php echo $row_listings["client"]; ?>"><?php echo $row_listings["client"]; ?></a>
						<a href="get_pgp_key.php?public=<?php echo $row_listings["client"]; ?>" target="_blank"><sup><small>pgp_pub</small></sup></a><br>
<?php						if (!$issetDoc){
?>							<form action="send_documentation.php" method="post">
								<textarea name="documentationLink" rows="8" cols="20" required placeholder="security hint: pgp(link=onionshare(documentation_files))"></textarea><br>
								<input type="hidden" name="listingId" value="<?php echo $row_listings["id"]; ?>">
								<input type="submit" value="sende Link an Kunde" button class="button"></button>
							</form>
<?php						}
					} elseif ("client" == $role) {
?>						<br>Verkäufer: 
						<a href="vendor_shop.php?vendor=<?php echo $row_listings["vendor"]; ?>">
							<?php echo $row_listings["vendor"]; ?>
						</a>
<?php						if (!NULL == $row_listings['documentation_link']) {
?>							<form action="confirm_receipt.php" method="post">
								<input type="hidden" name="listingId" value="<?php echo $row_listings["id"]; ?>">
								<input type="submit" value="Befreie Escrow" button class="button"></button>
							</form>
<?php						} else {
							echo "<br>Warte auf den Dokumentationslink des Verkeaufers";
							echo "<br>Autoabbruch in XX:XX Stunden.";
						}
					}
?>				</div>
<?php			}
		} else {
			echo "Es sind keine mit ihrem Handel verlinkte Transaktionen im Escrowdienst";
		}
	} else {
		echo "Error: " . $sql_listings . "<br>" . mysqli_error($conn);
	}
}

//////////////////////////////////////////////////////////////////////////////////////

function get_pgp_key($target, $conn) {
	$sql = "SELECT pgp_pub_key 
		FROM users
		WHERE name = '$target'";
	if ($result = mysqli_query($conn, $sql)) {
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			return $pgp_key = $row["pgp_pub_key"];
		} else {
			echo "NO FUCKING KEY FOUND!";
		}
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
}

//////////////////////////////////////////////////////////////////////////////////////

function get_reputation_score($target, $conn) {
	$scoreSum = 0;
	$sql = "SELECT rating 
		FROM reputations
		WHERE target = '$target'";
	if ($result = mysqli_query($conn, $sql)) {
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$score = $row['rating'] + $score;
			}
		} else {
		}
		return $score;
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
}


//////////////////////////////////////////////////////////////////////////////////////


function send_reputation($creator, $target, $rating, $message, $itemId, $conn) {
	$itemName = get_item_name($itemId, $conn);
	$message = $itemName . "<br><br>" . $message;
	$sql = "INSERT INTO reputations (creator, target, score, message )
		VALUES ('$creator', '$target', '$score', '$message')";
	if (mysqli_query($conn, $sql)) {
		echo '<div class="css_box_long">Ihre Erfahrung wurde dem Ruf des Haendlers angehangen.</div>';
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
}



//////////////////////////////////////////////////////////////////////////////////////

function get_item_name($listingId, $conn) {
	$sql = "SELECT item
		FROM listings 
		WHERE id = '$listingId'";
	if ($result = mysqli_query($conn, $sql)) {
		$row = mysqli_fetch_assoc($result); 
		return $row['item'];
	} else {
		echo "Error: ".$sql."<br>".mysqli_error($conn);
	}
}


//////////////////////////////////////////////////////////////////////////////////////


function get_vendor($listingId, $conn) {
	$sql = "SELECT vendor
		FROM listings 
		WHERE id = '$listingId'";
	if ($result = mysqli_query($conn, $sql)) {
		$row = mysqli_fetch_assoc($result); 
		return $row['vendor'];
	} else {
		echo "Error: ".$sql."<br>".mysqli_error($conn);
	}
}


//////////////////////////////////////////////////////////////////////////////////////

function get_client($listingId, $conn) {
	$sql = "SELECT client
		FROM listings 
		WHERE id = '$listingId'";
	if ($result = mysqli_query($conn, $sql)) {
		$row = mysqli_fetch_assoc($result); 
		return $row['client'];
	} else {
		echo "Error: ".$sql."<br>".mysqli_error($conn);
	}
}


//////////////////////////////////////////////////////////////////////////////////////

function count_my_offerings($user, $conn) {
	$sql = "SELECT COUNT(id)
			FROM listings 
			WHERE vendor='$user' AND client IS NULL";
	if ($result = mysqli_query($conn, $sql)) {
		$row = mysqli_fetch_assoc($result); 
		return $row['COUNT(id)'];
	} else {
		echo "Error: ".$sql."<br>".mysqli_error($conn);
	}
}
//////////////////////////////////////////////////////////////////////////////////////


function send_message($creator, $recipient, $topic, $message, $conn) {
	$sql = "INSERT INTO messages (creator, recipient, topic, message)
		VALUES ('$creator', '$recipient', '$topic', '$message')";
	if (mysqli_query($conn, $sql)) {
		echo '<div class="css_box_medium">Nachricht versendet</div>';
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
}



//////////////////////////////////////////////////////////////////////////////////////




function print_my_offerings($user, $conn) {
	$sql = "SELECT id, item, price, quantity, image
			FROM listings 
			WHERE vendor='$user' AND client IS NULL";

	if ($result = mysqli_query($conn, $sql)) {
		if (mysqli_num_rows($result) > 0) {
			?><h2>Meine Angebote:</h2><?php
	?>              <form action="delete_listings.php" method="post">
			<input type="submit" value="lösch die Ausgewählten" button class="button"></button><br>
	<?php           // output data of each row
			while($row = mysqli_fetch_assoc($result)) {
	?>              <br>
				<div class="css_box_small">
	<?php                           echo $row["item"]; 
	?>                              <img src="images/item/<?php echo $row['image']; ?>" alt="item pic">
					<br><?php echo $row["quantity"]." gramm"; ?>
					<br><?php echo $row["price"]." Euro"; ?>
					<br><?php printf ("%.2f Euro/Gramm\n", $row["price"]/$row["quantity"]); ?>
					<input type="checkbox" value="<?php echo $row["id"]; ?>" name="deleteListings[]">
				</div>
	<?php           }
	?>              </form>
	<?php   } else {
			echo "0 results";
		}
	} else {
		echo "Error: ".$sql."<br>".mysqli_error($conn);
	}
}

//////////////////////////////////////////////////////////////////////////////////////



function sql_UPDATE_listings_SET_timestamp_euro2btc_request($id, $priceBtc, $conn) { 
	$timestampEuro2btcRequest = time();
	$sqlUpdateListings = "UPDATE listings
		SET timestamp_euro2btc_request = '$timestampEuro2btcRequest', price_btc = '$priceBtc'
		WHERE id = '$id'";
	$resultUpdateListings = mysqli_query($conn, $sqlUpdateListings);
	if (!$resultUpdateListings) {
		echo "ErrorUPDATE: ".$sqlUpdateListings."<br>".mysqli_error($conn);
	}
	//update rowListings
	$sqlSelectListings = "SELECT *
		FROM listings
		WHERE id = '$id'";
	if ( !$resultListings = mysqli_query($conn, $sqlSelectListings) ) {
		echo "ErrorSELECT: ".$sqlSelectListings."<br>".mysqli_error($conn);
		return "error";
	} else {
		$rowListings = mysqli_fetch_assoc($resultListings);
		return $rowListings;
	}
}


////////////////////////////////////////////////////////////////////////////


function sql_UPDATE_listings_SET_timestamp_double_client_prevention($id, $conn) {
	$timestampDoubleClientPrevention = time();
	$sqlUpdateListings = "UPDATE listings
		SET timestamp_double_client_prevention = '$timestampDoubleClientPrevention'
		WHERE id = '$id'";
	$resultUpdateListings = mysqli_query($conn, $sqlUpdateListings);
	if (!$resultUpdateListings) {
		echo "ErrorUPDATE: ".$sqlUpdateListings."<br>".mysqli_error($conn);
	}
	//update rowListings
	$sqlSelectListings = "SELECT *
		FROM listings
		WHERE id = '$id'";
	if ( !$resultListings = mysqli_query($conn, $sqlSelectListings) ) {
		echo "ErrorSELECT: ".$sqlSelectListings."<br>".mysqli_error($conn);
		return "error";
	} else {
		$rowListings = mysqli_fetch_assoc($resultListings);
		return $rowListings;
	}

}


////////////////////////////////////////////////////////////////////////////


function sql_free_old_unpaid_reservations($conn) {
	$configVars = parse_ini_file("config.txt");
	$preventionTime = $configVars["preventionTime"];
	$now = time();
	$sqlUpdateListings = "UPDATE listings
			SET client = NULL
			WHERE timestamp_double_client_prevention < ('$now' - '$preventionTime') AND status = 'no_payment'";
	$resultUpdateListings = mysqli_query($conn, $sqlUpdateListings);
	if (!$resultUpdateListings) {
		echo "ErrorUPDATE: ".$sqlUpdateListings."<br>".mysqli_error($conn);
	}
	//update rowListings
	$sqlSelectListings = "SELECT *
		FROM listings";
	if ( !$resultListings = mysqli_query($conn, $sqlSelectListings) ) {
		echo "ErrorSELECT: ".$sqlSelectListings."<br>".mysqli_error($conn);
		return "error";
	} else {
		$rowListings = mysqli_fetch_assoc($resultListings);
		return $rowListings;
	}

}
////////////////////////////////////////////////////////////////////////////

function stop_timer_escrow_to_client($id, $conn) {
	$sqlUpdateListings = "UPDATE listings
		SET timestamp_escrow_to_client = NULL 
		WHERE id = '$id'";
	$resultUpdateListings = mysqli_query($conn, $sqlUpdateListings);
	if (!$resultUpdateListings) {
		echo "ErrorUPDATE: ".$sqlUpdateListings."<br>".mysqli_error($conn); }
}

////////////////////////////////////////////////////////////////////////////

function start_timer_escrow_to_client($id, $conn) {
	$time = time();
	$sqlUpdateListings = "UPDATE listings
		SET timestamp_escrow_to_client = '$time' 
		WHERE id = '$id'";
	$resultUpdateListings = mysqli_query($conn, $sqlUpdateListings);
	if (!$resultUpdateListings) {
		echo "ErrorUPDATE: ".$sqlUpdateListings."<br>".mysqli_error($conn); }
}

////////////////////////////////////////////////////////////////////////////

function start_timer_escrow_to_vendor($id, $conn) {
	$time = time();
	$sqlUpdateListings = "UPDATE listings
		SET timestamp_escrow_to_vendor = '$time' 
		WHERE id = '$id'";
	$resultUpdateListings = mysqli_query($conn, $sqlUpdateListings);
	if (!$resultUpdateListings) {
		echo "ErrorUPDATE: ".$sqlUpdateListings."<br>".mysqli_error($conn); }
}
////////////////////////////////////////////////////////////////////////////

function sql_UPDATE_listings_SET_payment($id, $client, $status, $conn) {
	$sqlUpdateListings = "UPDATE listings
		SET client = '$client', status = '$status' 
		WHERE id = '$id'";
	$resultUpdateListings = mysqli_query($conn, $sqlUpdateListings);
	if (!$resultUpdateListings) {
		echo "ErrorUPDATE: ".$sqlUpdateListings."<br>".mysqli_error($conn); }
	//update rowListings
	$sqlSelectListings = "SELECT *
		FROM listings
		WHERE id = '$id'";
	if ( !$resultListings = mysqli_query($conn, $sqlSelectListings) ) {
		echo "ErrorSELECT: ".$sqlSelectListings."<br>".mysqli_error($conn);
		return "error";
	} else {
		$rowListings = mysqli_fetch_assoc($resultListings);
		return $rowListings;
	}
}


////////////////////////////////////////////////////////////////////////////


function euro2btc ($priceEuro) {
	$filePriceBtc = fopen("https://blockchain.info/tobtc?currency=EUR&value=$priceEuro", "r");
	return fgets($filePriceBtc);
}


////////////////////////////////////////////////////////////////////////////

function generate_qr_code($text) {
		include 'phpqrcode/qrlib.php';
		$text = "bitcoin:".$escrowAddress;		//e.g:    bitcoin:tb1qkxqu6hzw6weke746z843mrfllz4h5mhhtwnwlh?amount=1.234
		$path = 'images/qr/';
		$fileQrCode = $path.$text.".png";
		$ecc = 'L';
		$pixel_Size = 9;
		$frame_Size = 10;
		QRcode::png($text, $fileQrCode, $ecc, $pixel_Size, $frame_size);
		return $fileQrCode;
}

////////////////////////////////////////////////////////////////////////////


function request_payment($priceBtc, $escrowAddress, $status) {
	if ("no_payment" == $status) {	
?>			<img src="status_is_no_payment.png" alt="no_image">
		<div class="css_box_long">
<?php				//$qrCode = generate_qr_code($escrowAddress);	
				$qrText = "bitcoin:$escrowAddress?amount=$priceBtc";
				$path = 'images/qr/';
				$qrCodeNew = $path.$escrowAddress.".png";
				exec ("qrencode '$qrText' -o $qrCodeNew");
			echo "Send \"$priceBtc\" Bitcoin to the escrow address \"$escrowAddress\" and reload this page.<br><br>".
				"<br><img src='".$qrCodeNew."'><br>";
?>			</div>
<?php		} elseif ("unconfirmed_payment" == $status) {
?>			<img src="status_is_unconfirmed_payment.png" alt="status_is_unconfirmed_payment">
		<div class="css_box_long">
			<h3>order details:</h3>
				<a href="https://live.blockcypher.com/btc-testnet/address/<?php echo $escrowAddress; ?>" target="_blank">Die Transaktion</a>
<?php				echo "ist wahrgenommen, es wird aber noch gewartet, bis sie in 6 Bloecke gemeiselt ist. Der Status dieses Handels ist jederzeit unter \"Mein Zuhause\" in der Navigationsleiste zu finden.<br>";
?>			</div>
<?php		} elseif ("confirmed_payment" == $status) {
?>			<img src="status_is_confirmed_payment.png" alt="status_is_confirmed_payment">
		<div class="css_box_long">
			<h3>order details:</h3>
<?php				echo "Payment arrived the escrow service. The vendor has now been invited to send the documentation of the place to you.<br>".				"The payment gets held by the escrow service until you confirm recivement or 48 hours passed if you dont expand.";
?>			</div>
<?php		}
}

////////////////////////////////////////////////////////////////////

function isset_documentationlink($id, $conn) {
	$sql = "SELECT documentation_link FROM listings WHERE id = '$id'";
	if (!$result = mysqli_query($conn, $sql)) {
		echo "Error: ".$sql."<br>".mysqli_error($conn);
	}
	$row = mysqli_fetch_assoc($result);
	if (NULL == $row["documentation_link"]) {
		return false;
	} else {
		return true;
	}
}

////////////////////////////////////////////////////////////////////

function print_time_until_autorelease($id, $conn) {
	$sql = "SELECT timestamp_escrow_to_client, timestamp_escrow_to_vendor FROM listings WHERE id = '$id'";
	if (!$result = mysqli_query($conn, $sql)) {
		echo "Error: ".$sql."<br>".mysqli_error($conn);
	}
	$row = mysqli_fetch_assoc($result);
	$configvars = parse_ini_file("config.txt");
	$lifetimeUndocumented = $configvars["undocumentedTime"];
	$deadlineUndocumented = $lifetimeUndocumented + $row["timestamp_escrow_to_client"];
	$lifetimeUnconfirmed = $configvars["unconfirmedTime"];
	$deadlineUnconfirmed = $lifetimeUnconfirmed + $row["timestamp_escrow_to_vendor"];
	$secleftUndocumented = $deadlineUndocumented - time();
	$secleftUnconfirmed = $deadlineUnconfirmed - time();
	if (isset_documentationlink($id, $conn)) {
		echo "$secleftUnconfirmed until autorelease";
	} else {
		echo "$secleftUndocumented until trade cancellation";
	}
}


////////////////////////////////////////////////////////////////////

function print_item($id, $conn) {
	$sql = "SELECT id, item, quantity, price, vendor, image FROM listings WHERE id = '$id'";
	if (!$result = mysqli_query($conn, $sql)) {
		echo "Error: ".$sql."<br>".mysqli_error($conn);
	}
	// and here the listings are generated
	if (mysqli_num_rows($result) > 0) {
	  // output data of each row
		while($row = mysqli_fetch_assoc($result)) {

	?>              <br>
			<div class="css_box_small">
	<?php                   echo $row["item"]; ?>
				<br><img src="images/item/<?php echo $row['image']; ?>" alt="no picture">
				<br><?php echo $row["quantity"]." Einheit(en)"; ?>
				<br><?php echo $row["price"]." Euro"; ?>
				<br><?php printf ("%.2f Euro/Einheit\n", $row["price"]/$row["quantity"]); ?>
	<br><a href="vendor_shop.php?vendor=<?php echo $row["vendor"]; ?>"><?php echo $row["vendor"]; ?></a>(<a href="get_reputation.php?target=<?php echo $row["vendor"] ?>"><?php echo get_reputation_score($row["vendor"], $conn) ?></a>)
			</div>
		<?php
	  }
	} else {
			echo "Keine Angebote auf dem Markt.";
	}
}
////////////////////////////////////////////////////////////////////

function print_listings($user, $conn) {
	if ( isset($user) ) {
		$sql = "SELECT id, item, quantity, price, vendor, image FROM listings WHERE vendor != '$user' AND client IS NULL";
	} else {
		$sql = "SELECT id, item, quantity, price, vendor, image FROM listings WHERE client IS NULL";
	}
	if (!$result = mysqli_query($conn, $sql)) {
		echo "Error: ".$sql."<br>".mysqli_error($conn);
	}
	// and here the listings are generated
	if (mysqli_num_rows($result) > 0) {
	  // output data of each row
		while($row = mysqli_fetch_assoc($result)) {

	?>              <br>
			<div class="css_box_small">
	<?php                   echo $row["item"]; ?>
				<br><img src="images/item/<?php echo $row['image']; ?>" alt="no picture">
				<br><?php echo $row["quantity"]; ?>
				<br><?php echo $row["price"]." Euro"; ?>
				<br><?php printf ("%.2f Euro/Einheit\n", $row["price"]/$row["quantity"]); ?>
	<br><a href="vendor_shop.php?vendor=<?php echo $row["vendor"]; ?>"><?php echo $row["vendor"]; ?></a>(<a href="get_reputation.php?target=<?php echo $row["vendor"] ?>"><?php echo get_reputation_score($row["vendor"], $conn) ?></a>)
				<form action="escrow.php" method="post">
				<input type="hidden" id="id" name="id" value="<?php echo $row["id"]; ?>">
				<input type="submit" value="kaufen" button class="button"></button>
				</form>
			</div>
		<?php
	  }
	} else {
		if (isset($user)) {
			echo "Keine Angebote andere Händler auf dem Markt.";
		} else {
			echo "Keine Angebote auf dem Markt.";
		}
	}
}
////////////////////////////////////////////////////////////////////

function is_reputation_written($conn, $listingId) {
	$sql = "SELECT listingId
		FROM reputations
		WHERE listingId = '$listingId'";

	if ( !$result = mysqli_query($conn, $sql) ) {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	} else {
		if (mysqli_num_rows($result) > 0) {
			return true;
		} else {
			return false;
		}
	}
}


////////////////////////////////////////////////////////////////////

function in_array_mysqli_fetch_all($needle, $arrayOfHaystacks) {
	for ($i = 0; $i < count($arrayOfHaystacks); $i++) {
		$haystack[$i] = $arrayOfHaystacks[$i][0];
	}
	if (in_array("$needle", $haystack)) {
		return 1;
	} else {
		return 0;
	}
}




////////////////////////////////////////////////////////////////////

function sql_SELECT_waypoints($conn) {
	$sqlListings = "SELECT item, quantity, price, vendor, latitude, longitude
		FROM listings 
		WHERE client IS NULL";
	if ( !$resultListings = mysqli_query($conn, $sqlListings) ) {
		echo "Error1: " . $sqlListings . "<br>" . mysqli_error($conn);
		return "error";
	} else {
		return $resultListings; 
	}
}
//////////////////////////////////////////////////////////////////////////////////////
//this function only asks for the balance, if the database has no balance written
function write_accountbalances($user, $conn) {
	if ( isset($user) ) {
		//todo: write balance only once, instead for two tables seperatly: (listings and escrow_keys)
		$sqlListingsPaid = "SELECT id
			FROM listings 
			WHERE vendor = '$user' AND status = 'confirmed_payment'";
		if ( !$resultListingsPaid = mysqli_query($conn, $sqlListingsPaid) ) {
			echo "Error: " . $sqlListingsPaid . "<br>" . mysqli_error($conn);
		} else {
			while ($rowListingsPaid = mysqli_fetch_assoc($resultListingsPaid)) {
				$rowEscrow = sql_select_escrow($rowListingsPaid['id'], $conn);
				if ( !isset($rowEscrow['balance']) ) {
					$escrowAddress = $rowEscrow['address'];
					$file6confirmations = fopen("https://blockchain.info/q/addressbalance/$escrowAddress?confirmations=6", "r");
					$balanceEscrowAddress = fgets($file6confirmations);
					$id = $rowListingsPaid['id'];
					$sql = "UPDATE escrow_keys 
					SET balance = '$balanceEscrowAddress'
					WHERE id = '$id'"; 
					if (mysqli_query($conn, $sql)) {
						//echo "update succsessful: <br>id: $id <br>balance: $balanceEscrowAddress<br><br>";
					} else {
						echo "Error: " . $sql . "<br>" . mysqli_error($conn);
					}
				}
			}
		}
	}
}
////////////////////////////////////////////////////////////////////////////

function print_account_balance($user, $conn) {
	$sql = "SELECT balance
                FROM escrow_keys WHERE owner = '$user'";
        if ($result = mysqli_query($conn, $sql)) {
		$balanceSum = 0;
		while ($row = mysqli_fetch_assoc($result)) {
                        $balanceSum += $row['balance'];
                }
        } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
	echo "Kontostand: " . $balanceSum . " Satoshi";
}

////////////////////////////////////////////////////////////////////////////


function offer_gpx_download($filepath, $conn) {
	export_gpx($filepath, $conn);
	echo "<br><a href='$filepath'>Lade die ungefeahre Position aller Angebote fuer die Integration in dein GPS herunter.</a>";
}

////////////////////////////////////////////////////////////////////////////

function foo($a, $b) {
	echo $a;
}
////////////////////////////////////////////////////////////////////////////

function export_gpx($filepath, $conn) {
	function create_waypoint($name, $lat, $lon) {
		return "\n\t<wpt lat=\"$lat\" lon=\"$lon\">\n\t\t<name>$name</name>\n\t</wpt>\n";
	}

	$fp_gpx = fopen($filepath, 'w');
	$gpx_open = '<?xml version="1.0" encoding="UTF-8" ?>
<gpx xmlns="http://www.topografix.com/GPX/1/1" version="1.1" creator="the anachist" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.topografix.com/GPX/1/1 http://www.topografix.com/GPX/1/1/gpx.xsd ">';
	$gpx_close = '</gpx>';
	fwrite($fp_gpx, $gpx_open);
	$resultListings = sql_SELECT_waypoints($conn);
	while($row = mysqli_fetch_assoc($resultListings)) {
		$waypoint = create_waypoint( $row['item'], $row['latitude'], $row['longitude']);
		fwrite($fp_gpx, $waypoint);
	}
	fwrite($fp_gpx, $gpx_close);
	fclose($fp_gpx);
	
}

////////////////////////////////////////////////////////////////////////////
?>
