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
												`Reset_hash` varchar(16) NULL,
												PRIMARY KEY (ID)
											) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
		if($conn->query($create_tables)){
			echo "OK<br />";
		}else{
			echo "FAIL<br />";
			array_push($errors,"Something went wrong while creating the table `".htmlentities($_POST['SQL_Prefix'])."Users`: " . $conn->error);
		}

		echo "Adding first user to tables... ";
		$add_user = "INSERT INTO `".mysqli_real_escape_string($conn,$_POST['SQL_Prefix'])."Users` (`ID`, `Username`, `Email`, `Password`, `Session`,`Reset_hash`) VALUES (NULL, '".mysqli_real_escape_string($conn,$_POST['Username'])."', '".mysqli_real_escape_string($conn,$_POST['Email'])."', '".mysqli_real_escape_string($conn,password_hash($_POST['Password'],PASSWORD_DEFAULT))."', '',NULL);";
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
		if(!empty($_POST['captcha_Sitekey']) && !empty($_POST['captcha_Secret'])){
			$captcha_enabled = 1;
		}else{
			$captcha_enabled = 0;
		}
		$config = "PD9waHANCiRzbF9jb25maWcgPSAJYXJyYXkoDQoJCQkJCQkJCS8vIFNRTCBTZXR0aW5ncw0KCQkJCQkJCQkiU1FMIiA9PglhcnJheSgNCgkJCQkJCQkJCQkJCQkJIkhvc3QiID0+ICJ7SE9TVH0iLA0KCQkJCQkJCQkJCQkJCQkiVXNlcm5hbWUiID0+ICJ7VVNFUk5BTUV9IiwNCgkJCQkJCQkJCQkJCQkJIlBhc3N3b3JkIiA9PiAie1BBU1NXT1JEfSIsDQoJCQkJCQkJCQkJCQkJCSJEYXRhYmFzZSIgPT4gIntEQVRBQkFTRX0iLA0KCQkJCQkJCQkJCQkJCQkiUHJlZml4IiA9PiAie1BSRUZJWH0iLA0KCQkJCQkJCQkJCQkJCQkiU2luZ2xlU2Vzc2lvbiIgPT4ge1NJTkdMRVNFU1NJT059DQoJCQkJCQkJCQkJCQkJKSwNCgkJCQkJCQkJIkNhcHRjaGEiID0+IAlhcnJheSgNCgkJCQkJCQkJCQkJCQkJCQkiRW5hYmxlZCIgPT4ge0NBUFRDSEFfRU5BQkxFRH0sDQoJCQkJCQkJCQkJCQkJCQkJIlNpdGVrZXkiID0+ICJ7Q0FQVENIQV9TSVRFX0tFWX0iLA0KCQkJCQkJCQkJCQkJCQkJCSJTZWNyZXQiID0+ICJ7Q0FQVENIQV9TRUNSRVR9Ig0KCQkJCQkJCQkJCQkJCQkJKSwNCgkJCQkJCQkJIlNNVFAiID0+CWFycmF5KA0KCQkJCQkJCQkJCQkJCQkiSG9zdCIgPT4gIntTTVRQX0hPU1R9IiwNCgkJCQkJCQkJCQkJCQkJIlVzZXJuYW1lIiA9PiAie1NNVFBfVVNFUk5BTUV9IiwNCgkJCQkJCQkJCQkJCQkJIlBhc3N3b3JkIiA9PiAie1NNVFBfUEFTU1dPUkR9IiwNCgkJCQkJCQkJCQkJCQkJIkZST00iID0+ICJ7U01UUF9GUk9NfSIsDQoJCQkJCQkJCQkJCQkJCSJGUk9NX0VNQUlMIiA9PiAie1NNVFBfRlJPTV9FTUFJTH0iLA0KCQkJCQkJCQkJCQkJCQkiUkVQTFlUTyIgPT4gIntTTVRQX1JFUExZVE99IiwNCgkJCQkJCQkJCQkJCQkJIlJFUExZVE9fRU1BSUwiID0+ICJ7U01UUF9SRVBMWVRPX0VNQUlMfSINCgkJCQkJCQkJCQkJCQkpDQoJCQkJCQkJKTs=";
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
		$config = str_replace("{SMTP_HOST}",$_POST['SMTP_Host'],$config); 			// Replace {SMTP_HOST} with the SMTP host entered by the user
		$config = str_replace("{SMTP_USERNAME}",$_POST['SMTP_Username'],$config); 			// Replace {SMTP_USERNAME} with the SMTP Username entered by the user
		$config = str_replace("{SMTP_PASSWORD}",$_POST['SMTP_Password'],$config); 			// Replace {SMTP_PASSWORD} with the SMTP Password entered by the user
		$config = str_replace("{SMTP_FROM}",$_POST['SMTP_From'],$config); 			// Replace {SMTP_FROM} with the SMTP From entered by the user
		$config = str_replace("{SMTP_FROM_EMAIL}",$_POST['SMTP_FromEmail'],$config); 			// Replace {SMTP_FROM} with the SMTP From Email entered by the user
		$config = str_replace("{SMTP_REPLYTO}",$_POST['SMTP_Replyto'],$config); 			// Replace {SMTP_REPLYTO} with the SMTP host entered by the user
		$config = str_replace("{SMTP_REPLYTO_EMAIL}",$_POST['SMTP_ReplytoEmail'],$config); 			// Replace {SMTP_REPLYTO} with the SMTP host entered by the user
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
			<h1>SMTP Settings</h1>
			<hr>
			Leave these settings empty if you do not want to use SMTP (used for Password resets)!<br />
			<label for="SMTP_Host">Host</label>
			<input type="text" name="SMTP_Host"><br />
			<label for="SMTP_Username">Username</label>
			<input type="text" name="SMTP_Username"><br />
			<label for="SMTP_Password">Password</label>
			<input type="password" name="SMTP_Password"><br />
			<label for="SMTP_From">From</label>
			<input type="text" name="SMTP_From"><br />
			<label for="SMTP_From">From Email</label>
			<input type="text" name="SMTP_FromEmail"><br />
			<label for="SMTP_Replyto">Reply-To</label>
			<input type="text" name="SMTP_Replyto"><br />
			<label for="SMTP_Replyto">Reply-To Email</label>
			<input type="text" name="SMTP_ReplytoEmail"><br />
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
