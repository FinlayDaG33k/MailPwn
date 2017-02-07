<?php
	require("class.finlaydag33k.php");
	$finlaydag33k = new finlaydag33k();
	if($_POST){
		switch ($_POST['Action']) {
			case "Spam":
				$finlaydag33k->Spam($_POST,$config);
			case "Spoof":
				$finlaydag33k->Spoof($_POST,$config);
			case "checkBL":
				$finlaydag33k->checkBL($_POST,$config);
		}
	}else{
		switch($_GET['Action']){
			case "getDNS":
				$finlaydag33k->getDNSTable($_GET['hostname']);
		}
	}
 ?>
