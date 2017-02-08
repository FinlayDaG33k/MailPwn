<?php
require(dirname(__FILE__) . "/inc/php/class.finlaydag33k.php");
require(dirname(__FILE__) . "/config.php");
$finlaydag33k = new finlaydag33k();

session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<?php
			include(dirname(__FILE__) . "/components/header.php");
			require(DIRNAME(__FILE__) . "/SimpleLogins/autoloader.php");
			?>
	</head>

	<body>
		<div class="container-fluid">
			<?php include(dirname(__FILE__) . "/components/navbar.php"); ?>
		</div>

		<div class="container-fluid">
			<?php
			 	if($_SESSION['Loggedon']){
					include("pages/main.php");
				}else{
					include("pages/login.php");
				}
			?>
		</div>
	</body>
</html>
