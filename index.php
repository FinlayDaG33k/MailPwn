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
					$disallowed_paths = array('header', 'footer');
					if (!empty($_GET['page'])) {
						$tmp_page = basename($_GET['page']);
						if (!in_array($tmp_action, $disallowed_paths) && file_exists("pages/{$tmp_page}.php")) {
							$page = $tmp_page;
						} else {
							$page = 'error';
						}
					}else{
    				$page = 'main';
					}
					include("pages/".$page . ".php");
				}else{
					include("pages/login.php");
				}
			?>
		</div>
	</body>
</html>
