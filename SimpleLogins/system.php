<?php
	require(DIRNAME(__FILE__) . "/autoloader.php");
	if($_POST){
		if($sl_config['Captcha']['Enabled']){
			if(!$SimpleLogins->Captcha->Check($sl_config,$_POST['g-recaptcha-response'])){
				header("Location: " . $_POST['cb'] . $SimpleLogins->sl_Vars()['GET_sign']."success=false&reason=invalid_captcha");
				exit;
			}
		}

		switch($_POST['Action']){
			case "Login":
				switch($SimpleLogins->Users->Login($_POST['Username'],$_POST['Password'],$sl_config)){
					case 1:
						header("Location: " . $_POST['cb'] . $SimpleLogins->sl_Vars()['GET_sign']."success=true");
						break;
					case "Invalid Credentials":
						header("Location: " . $_POST['cb'] . $SimpleLogins->sl_Vars()['GET_sign']."success=false&reason=invalid_credentials");
						break;
					case "Internal Error":
						header("Location: " . $_POST['cb'] . $SimpleLogins->sl_Vars()['GET_sign']."success=false&reason=internal_error");
						break;
				}
				break;
			case "ChangePassword":
				switch($SimpleLogins->Users->Change_password($_POST,$sl_config)){
					case 1:
						header("Location: " . $_POST['cb'] . $SimpleLogins->sl_Vars()['GET_sign']."success=true");
						break;
					case "No Pass Match":
						header("Location: " . $_POST['cb'] . $SimpleLogins->sl_Vars()['GET_sign']."success=false&reason=no_pass_match");
						break;
					case "Internal Error":
						header("Location: " . $_POST['cb'] . $SimpleLogins->sl_Vars()['GET_sign']."success=false&reason=internal_error");
						break;
				}
				break;
			case "Forgotpassword":
				switch($SimpleLogins->Users->Forgot_password($_POST,$sl_config)){
					case 1:
						header("Location: " . $_POST['cb'] . $SimpleLogins->sl_Vars()['GET_sign']."success=true");
						break;
					case "Invalid Credentials":
						header("Location: " . $_POST['cb'] . $SimpleLogins->sl_Vars()['GET_sign']."success=false&reason=invalid_credentials");
						break;
					case "Internal Error":
						header("Location: " . $_POST['cb'] . $SimpleLogins->sl_Vars()['GET_sign']."success=false&reason=internal_error");
						break;
				}
				break;
			case "Resetpassword":
				switch($SimpleLogins->Users->Reset_password($_POST,$sl_config)){
					case 1:
						header("Location: " . $_POST['cb'] . $SimpleLogins->sl_Vars()['GET_sign']."success=true");
						break;
					case "No Pass Match":
						header("Location: " . $_POST['cb'] . $SimpleLogins->sl_Vars()['GET_sign']."success=false&reason=no_pass_match");
						break;
					case "Invalid Credentials":
						header("Location: " . $_POST['cb'] . $SimpleLogins->sl_Vars()['GET_sign']."success=false&reason=invalid_credentials");
						break;
					case "Internal Error":
						header("Location: " . $_POST['cb'] . $SimpleLogins->sl_Vars()['GET_sign']."success=false&reason=internal_error");
						break;
				}
				break;
		}


	}elseif($_GET){
		switch(strtolower($_GET['Action'])){
			case "logout":
				$SimpleLogins->Users->Logout();
				break;
		}
	}else{
		echo "Invalid HTTP Method.";
	}
