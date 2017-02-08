<?php
if(file_exists(DIRNAME(__FILE__) . '/lockfile')){
	echo "This page is locked.<br />Please remove the lock file to continue.";
	exit;
}else{
	if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['Password'] == $_POST['ConfirmPassword']){

		echo "Loading SimpleLogins Library... ";
		require(DIRNAME(__FILE__) . '/simplelogins.class.php');
		$SimpleLogins = new SimpleLogins();

		if($SimpleLogins){
			echo "OK<br />";
		}else{
			echo "Could not load the SimpleLogins Library!<br />Please review any errors above and try again!";
			exit;
		}

		$errors = array();

		echo "Opening Connection... ";
		$conn = $SimpleLogins->Database->Initialize_No_Database($_POST['SQL_Host'],$_POST['SQL_Username'],$_POST['SQL_Password'],$_POST['SQL_Database']);
		if($conn){
			echo "OK<br />";
		}else{
			echo "Could not connect to the MySQL server!<br />Please review any errors above and try again!";
			exit;
		}
		echo "Creating Database... ";
		$create_database = "CREATE DATABASE ".mysqli_real_escape_string($conn,$_POST['SQL_Database']).";";
		if($conn->query($create_database)){
			echo "OK<br />";
		}else{
			echo "FAIL<br />";
			array_push($errors,"Something went wrong while creating the Database `".htmlentities($_POST['SQL_Database'])."`: " . $conn->error);
		}
		echo "Selecting Database `".htmlentities($_POST['SQL_Database'])."`... ";

		if($conn->select_db($_POST['SQL_Database'])){
			echo "OK<br />";
		}else{
			array_push($errors,"Could not select Database `".htmlentities($_POST['Database'])."`: ".$conn->error);
		}
		echo "Creating Tables... ";
		$create_tables = "CREATE TABLE `".mysqli_real_escape_string($conn,$_POST['SQL_Prefix'])."Users` (
  											`ID` int(11) NOT NULL AUTO_INCREMENT,
  											`Username` varchar(64) NOT NULL,
  											`Email` varchar(64) NOT NULL,
  											`Password` varchar(255) NOT NULL,
  											`Session` varchar(64) NOT NULL,
												PRIMARY KEY (ID)
											) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
		if($conn->query($create_tables)){
			echo "OK<br />";
		}else{
			echo "FAIL<br />";
			array_push($errors,"Something went wrong while creating the table `".htmlentities($_POST['SQL_Prefix'])."Users`: " . $conn->error);
		}

		echo "Adding first user to tables... ";
		$add_user = "INSERT INTO `".mysqli_real_escape_string($conn,$_POST['SQL_Prefix'])."Users` (`ID`, `Username`, `Email`, `Password`, `Session`) VALUES (NULL, '".mysqli_real_escape_string($conn,$_POST['Username'])."', '".mysqli_real_escape_string($conn,$_POST['Email'])."', '".mysqli_real_escape_string($conn,password_hash($_POST['Password'],PASSWORD_DEFAULT))."', '');";
		if($conn->query($add_user)){
			echo "OK<br />";
		}else{
			echo "FAIL<br />";
			array_push($errors,"Could not create user `".htmlentities($_POST['Username'])."`:<br />" . $conn->error . "<br />");
		}
		echo "Writing Config... ";
		if($_POST['Singlelogin'] == "on"){
			$singlelogin = 1;
		}else{
			$singlelogin = 0;
		}
		if(!empty($_POST['captcha_Sitekey'])){
			$captcha_enabled = 1;
		}else{
			$captcha_enabled = 0;
		}
		$config = "PD9waHANCg0KJHNsX2NvbmZpZyA9IAlhcnJheSgNCgkJCQkJCQkJLy8gU1FMIFNldHRpbmdzDQoJCQkJCQkJCSJTUUwiID0+CWFycmF5KA0KCQkJCQkJCQkJCQkJCQkiSG9zdCIgPT4gIntIT1NUfSIsDQoJCQkJCQkJCQkJCQkJCSJVc2VybmFtZSIgPT4gIntVU0VSTkFNRX0iLA0KCQkJCQkJCQkJCQkJCQkiUGFzc3dvcmQiID0+ICJ7UEFTU1dPUkR9IiwNCgkJCQkJCQkJCQkJCQkJIkRhdGFiYXNlIiA9PiAie0RBVEFCQVNFfSIsDQoJCQkJCQkJCQkJCQkJCSJQcmVmaXgiID0+ICJ7UFJFRklYfSIsDQoJCQkJCQkJCQkJCQkJCSJTaW5nbGVTZXNzaW9uIiA9PiB7U0lOR0xFU0VTU0lPTn0NCgkJCQkJCQkJCQkJCQkpLA0KCQkJCQkJCQkiQ2FwdGNoYSIgPT4gCWFycmF5KA0KCQkJCQkJCQkJCQkJCQkJCSJFbmFibGVkIiA9PiB7Q0FQVENIQV9FTkFCTEVEfSwNCgkJCQkJCQkJCQkJCQkJCQkiU2l0ZWtleSIgPT4gIntDQVBUQ0hBX1NJVEVfS0VZfSIsDQoJCQkJCQkJCQkJCQkJCQkJIlNlY3JldCIgPT4gIntDQVBUQ0hBX1NFQ1JFVH0iDQoJCQkJCQkJCQkJCQkJCQkpDQoJCQkJCQkJKTs=";
		$config = base64_decode($config); 																		// Decode the config template
		$config = str_replace("{HOST}",$_POST['SQL_Host'],$config); 					// Replace {HOST} with the host entered by the user
		$config = str_replace("{USERNAME}",$_POST['SQL_Username'],$config); 	// Replace {USERNAME} with the Username entered by the user
		$config = str_replace("{PASSWORD}",$_POST['SQL_Password'],$config); 	// Replace {PASSWORD} with the Password entered by the user
		$config = str_replace("{DATABASE}",$_POST['SQL_Database'],$config); 	// Replace {DATABASE} with the Database entered by the user
		$config = str_replace("{PREFIX}",$_POST['SQL_Prefix'],$config); 			// Replace {PREFIX} with the Prefix entered by the user
		$config = str_replace("{SINGLESESSION}",$singlelogin,$config); 			// Replace {SINGLESESSION} with the preference entered by the user
		$config = str_replace("{CAPTCHA_ENABLED}",$captcha_enabled,$config); 			// Replace {PREFIX} with the captcha secret entered by the user
		$config = str_replace("{CAPTCHA_SITE_KEY}",$_POST['captcha_Sitekey'],$config); 			// Replace {CAPTCHA_ENABLED} if the Captcha sitekey is entered by the user
		$config = str_replace("{CAPTCHA_SECRET}",$_POST['captcha_Secret'],$config); 			// Replace {PREFIX} with the captcha secret entered by the user
		$file = fopen('config.php', 'w'); 																		// Open the config.php (or create it if it doesn exist)
		fwrite($file,$config);																								// Write the config to config.php
		fclose($file);																												// Close config.php

		echo "OK<br />";
		echo "Chmod config to 0775... ";
		chmod("config.php",775);
		echo "OK<br />";
		$lock = fopen('lockfile','w');																				// Create lock file to disable the installer
		fwrite($lock, "Please delete this file to enable the installer"); 		// Write a simple message with instructions
		fclose($lock);																												// Close the lock file

		echo "<br />";
		echo "<br />";

		if(!empty($errors)){
			?>
			Installation has finished, but there where errors during the process.<br />Please review them below before continueing:<br />
			<ul>
				<?php foreach($errors as $error){ ?>
					<li><?= htmlentities($error); ?></li>
				<?php } ?>
			</ul>
			<a href="/">Click me to return to your website's homepage!</a>
			<?php }else{ ?>
			Installation is complete without any detected errors!<br />
			Have a Nice day!<br />
			<a href="/">Click me to return to your website's homepage!</a>
			<?php
			}
	}else{
?>
		<h1>SimpleLogins installer</h1>
		<hr>
		Welcome to the installer for SimpleLogins.<br />
		This library will enable you to use logins without having to worry about most of the code.<br />
		<br />
		<?php if($_POST['Password'] !== $_POST['ConfirmPassword']){ ?>Passwords do not match!<?php } ?><br />
		<br />
		<br />
		<form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
			<h1>Database</h1>
			<hr>
			<label for="Host">MySQL Host</label>
			<input type="text" name="SQL_Host" value="<?php if(!empty($_POST['SQL_Host'])){ echo htmlentities($_POST['SQL_Host']); }else{ ?>localhost<?php } ?>"><br />
			<label for="Username">MySQL Username</label>
			<input type="text" name="SQL_Username" value="<?php if(!empty($_POST['SQL_Username'])){ echo htmlentities($_POST['SQL_Username']); }else{ ?>simplelogins<?php } ?>"><br />
			<label for="Password">MySQL Password</label>
			<input type="password" name="SQL_Password"><br />
			<label for="Database">MySQL Database</label>
			<input type="text" name="SQL_Database" value="simplelogins"><br />
			<label for="Prefix">Table Prefix</label>
			<input type="text" name="SQL_Prefix" value="sl_"><br />
			<input type="checkbox" name="Singlelogin" checked> Only allow one session per user<br>
			<br />
			<h1>Captcha</h1>
			<hr>
			Leave these settings empty if you do not want to use captcha!<br />
			<br />
			<label for="captcha_Sitekey">Sitekey</label>
			<input type="text" name="captcha_Sitekey"><br />
			<label for="captcha_Secret">Secret</label>
			<input type="password" name="captcha_Secret"><br />
			<h1>First User</h1>
			<hr>
			<label for="Username">Username</label>
			<input type="text" name="Username" value="admin"><br />
			<label for="Email">Email</label>
			<input type="text" name="Email" value="webmaster@<?= htmlentities($_SERVER['HTTP_HOST']); ?>"><br />
			<label for="Password">Password</label>
			<input type="password" name="Password"><br />
			<label for="Password">Confirm Password</label>
			<input type="password" name="ConfirmPassword"><br />
			<input type="submit" value="Install">
			<input type="reset" value="Reset form">

		</form>
<?php
	}
}
?>
