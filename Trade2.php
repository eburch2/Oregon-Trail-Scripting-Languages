<?php
import "header.php";
import "classes.php";
import "commonUI.php";
#food 0 
#money is 1 but here results in no trade
#bait 2
#clothes 3
#wagonWheels 4
#wagonAxle 5
#wagonTongue 6
#oxen 7

?>
<meta http-equiv="refresh" content="0; url= <?php echo $_GET["source2"] ?>" />

<?php

$_SESSION["party"]->setItem($_GET["$itemID"], -$_GET["$itemQunt"]);
$_SESSION["party"]->setItem($_GET["$rewardID"]), $_GET["$rewardQunt"]);
	

endHTML();

?>
