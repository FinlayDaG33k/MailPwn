<?php
require(dirname(__FILE__) . "/inc/php/class.finlaydag33k.php");
require(dirname(__FILE__) . "/config.php");
$finlaydag33k = new finlaydag33k();

session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include(dirname(__FILE__) . "/components/header.php"); ?>
	</head>

	<body>
		<div class="container-fluid">
			<?php include(dirname(__FILE__) . "/components/navbar.php"); ?>
		</div>

		<div class="container-fluid">
			<?php include("pages/main.php"); ?>
		</div>
	</body>
</html>
