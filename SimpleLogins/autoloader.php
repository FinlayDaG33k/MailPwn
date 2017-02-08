<script type="text/javascript">console.log("%cSTOP!\nThis is a browser feature intended for developers.\nIf someone told you to copy and paste something here to enable a feature or \"hack\" someone's account, it is a scam and could give them access to your browser!\nRead more at Wikipedia: https://en.wikipedia.org/wiki/Self-XSS", "color:red; font-size: 16pt");</script>
<?php
session_start();
if(!file_exists(DIRNAME(__FILE__) . '/lockfile')){
	echo "This appears to be a fresh install, please head over to <a href=\"SimpleLogins/install.php\">this</a> page to start the configuration";
	exit;
}else{
	// Check if the `usersystem.class.php` exists
	if(file_exists(DIRNAME(__FILE__) . '/simplelogins.class.php')){
		require(DIRNAME(__FILE__) . '/simplelogins.class.php');
		$SimpleLogins = new SimpleLogins();
	}else{
		?>
			<script type="text/javascript">console.log("[SimpleLogins] Could not find \"simplelogins.class.php\" at \"<?= htmlentities(DIRNAME(__FILE__)); ?>/simplelogins.class.php\". This should have come with this file!");</script>
		<?php
	}

	// Check if the `config.php` exists and if the installer is locked.
	if(file_exists(DIRNAME(__FILE__) . "/config.php")){
		require(DIRNAME(__FILE__) . "/config.php");
	}else{
		?>
			<script type="text/javascript">console.log("[SimpleLogins] Could not find \"config.php\" at \"<?= htmlentities(DIRNAME(__FILE__)); ?>/config.php\". This should have been generated while installing!");</script>
		<?php
	}

	if($sl_config['SQL']['SingleSession']){
		$SimpleLogins->Users->Check_Session($sl_config);
	}

	if($sl_config['Captcha']['Enabled']){
		echo $SimpleLogins->sl_Vars()['Captcha_script'];
	}

}
?>
