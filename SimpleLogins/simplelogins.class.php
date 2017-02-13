<?php
	class SimpleLogins{
		public function __construct(){
    	$this->Database = new Database;
			$this->Captcha = new Captcha;
			$this->Users = new Users;
  	}
		function sl_Vars(){
			require(DIRNAME(__FILE__) . "/config.php");
			if(!empty($_SERVER['HTTPS'])){
				$server_proto = "https://";
			}else{
				$server_proto = "http://";
			}
			$system_host = $_SERVER['HTTP_HOST'];

			if(!empty($_POST['cb'])){
				$system_dir = explode('?', $_POST['cb'], 2);
				if(count($system_dir) >= 2){
					$getsign = '&';
				}else{
					$getsign = '?';
				}
			}else{
				$system_dir = explode('?', $_SERVER['REQUEST_URI'], 2);
				if(count($system_dir) >= 2){
					$getsign = '&';
				}else{
					$getsign = '?';
				}
			}


			$sl_vars = 	array(
										"Server_proto" => $server_proto,
										"System_host" => htmlentities($system_host),
										"System_dir" => htmlentities($system_dir),
										"System_dir_get" => $_SERVER['REQUEST_URI'],
										"System_url" => htmlentities($server_proto . $system_host .$system_dir[0]. "SimpleLogins/system.php"),
										"GET_sign" => $getsign,
										"Captcha_form" => "<div class=\"g-recaptcha\" data-sitekey=\"".$sl_config['Captcha']['Sitekey']."\"></div>",
										"Captcha_script" => "<script src='https://www.google.com/recaptcha/api.js'></script>"
									);
			return $sl_vars;
		}
		function generateRandomString($length = 10) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $randomString;
		}
	}

	class Users{
		function Login($Username, $Password, $config){
			$SimpleLogins = new SimpleLogins();
			$conn = $SimpleLogins->Database->Initialize($config['SQL']['Host'],$config['SQL']['Username'],$config['SQL']['Password'],$config['SQL']['Database']);
			if(!$conn){
				return "No Conn";
			}else{
				$sql = "SELECT * FROM `".mysqli_real_escape_string($conn,$config['SQL']['Prefix'])."Users` WHERE `Username`='".mysqli_real_escape_string($conn,$Username)."' OR `Email`='".mysqli_real_escape_string($conn,$Username)."';";
				$sql_output = $conn->query($sql);
				if ($sql_output->num_rows > 0) {
					$user = $sql_output->fetch_assoc();
					if(password_verify($Password,$user['Password']) == true){
						session_start();
						session_regenerate_id();
						if($config['SQL']['SingleSession']){
							$sql = "UPDATE `".mysqli_real_escape_string($conn,$config['SQL']['Prefix'])."Users` SET `Session`='".mysqli_real_escape_string($conn,session_id())."' WHERE `Username`='".mysqli_real_escape_string($conn,$user['Username'])."';";
							if($conn->query($sql)){
								$_SESSION['Username'] = $user['Username'];
								$_SESSION['UID'] = $user['ID'];
								$_SESSION['Loggedon'] = 1;
								return 1;
							}else{
								return "Internal Error";
							}
						}
						return 1;
					}else{
						return "Invalid Credentials";
					}
				}else{
					return "Invalid Credentials";
				}
			}
		}
		function Logout(){
			session_start();
			session_destroy();
			session_regenerate_id();
		}
		function Change_password($POSTDATA, $config){
			if($POSTDATA['Newpassword'] == $POSTDATA['Newpasswordconfirm']){
				$SimpleLogins = new SimpleLogins();
				$conn = $SimpleLogins->Database->Initialize($config['SQL']['Host'],$config['SQL']['Username'],$config['SQL']['Password'],$config['SQL']['Database']);
				if(!$conn){
					return "No Conn";
				}else{
					session_start();
					$sql = "SELECT * FROM `".mysqli_real_escape_string($conn,$config['SQL']['Prefix'])."Users` WHERE `Username`='".mysqli_real_escape_string($conn,$_SESSION['Username'])."' AND `ID`='".mysqli_real_escape_string($conn,$_SESSION['UID'])."';";
					$sql_output = $conn->query($sql);
					if ($sql_output->num_rows > 0) {
						$pass_hash = password_hash($POSTDATA['NewPassword'],PASSWORD_DEFAULT);
						$sql = "UPDATE `".mysqli_real_escape_string($conn,$config['SQL']['Prefix'])."Users` SET `Password`='".mysqli_real_escape_string($conn,$pass_hash)."' WHERE `Username`='".mysqli_real_escape_string($conn,$_SESSION['Username'])."' AND `ID`='".mysqli_real_escape_string($conn,$_SESSION['UID'])."';";
						if($conn->query($sql)){
							return 1;
						}else{
							return "Internal Error";
						}
					}else{
						return "Invalid User";
					}
				}
			}else{
				return "No Pass Match";
			}
		}
		function Forgot_password($POSTDATA, $config){
			$SimpleLogins = new SimpleLogins();
			$conn = $SimpleLogins->Database->Initialize($config['SQL']['Host'],$config['SQL']['Username'],$config['SQL']['Password'],$config['SQL']['Database']);

			$sql = "SELECT * FROM `".mysqli_real_escape_string($conn,$config['SQL']['Prefix'])."Users` WHERE `Username`='".mysqli_real_escape_string($conn,$POSTDATA['Username'])."';";
			$sql_output = $conn->query($sql);
      if ($sql_output->num_rows > 0){
        require (DIRNAME(__FILE__).'/phpmailer/PHPMailerAutoload.php');
        $row = $sql_output->fetch_assoc();
        $token = $SimpleLogins->generateRandomString(16);
        $sql = "UPDATE `".mysqli_real_escape_string($conn,$config['SQL']['Prefix'])."Users` SET `Reset_hash` = '".mysqli_real_escape_string($conn,$token)."' WHERE `Username`='".mysqli_real_escape_string($conn,$POSTDATA['Username'])."';";
				if ($conn->query($sql) === TRUE) {
          //Create a new PHPMailer instance
          $mail = new PHPMailer;
          //Tell PHPMailer to use SMTP
          $mail->isSMTP();
          //Enable SMTP debugging
          // 0 = off (for production use)
          // 1 = client messages
          // 2 = client and server messages
          $mail->SMTPDebug = 0;
          //Ask for HTML-friendly debug output
        	$mail->Debugoutput = 'html';
          //Set the hostname of the mail server
          $mail->Host = $config['SMTP']['Host'];
          $mail->SMTPOptions = array(
            'ssl' => array(
            	'verify_peer' => false,
            	'verify_peer_name' => false,
            	'allow_self_signed' => true
          	)
          );
          // use
          // $mail->Host = gethostbyname('smtp.gmail.com');
          // if your network does not support SMTP over IPv6
          //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
          $mail->Port = 587;
          //Set the encryption system to use - ssl (deprecated) or tls
          $mail->SMTPSecure = 'tls';
          //Whether to use SMTP authentication
          $mail->SMTPAuth = true;
          //Username to use for SMTP authentication - use full email address for gmail
          $mail->Username = $config['SMTP']['Username'];
          //Password to use for SMTP authentication
          $mail->Password = $config['SMTP']['Password'];
          //Set who the message is to be sent from
					if(!empty($config['SMTP']['FROM']) && !empty($config['SMTP']['FROM_EMAIL'])){
          	$mail->setFrom($config['SMTP']['FROM_EMAIL'], $config['SMTP']['FROM']);
					}else{
						$mail->setFrom('noreply@'.htmlentities($_SERVER['HTTP_HOST']), 'SimpleLogins NoReply');
					}
          //Set an alternative reply-to address
					if(!empty($config['SMTP']['REPLYTO']) && !empty($config['SMTP']['REPLYTO_EMAIL'])){
          	$mail->addReplyTo($config['SMTP']['REPLYTO_EMAIL'], $config['SMTP']['REPLYTO']);
					}else{
						$mail->addReplyTo('webmaster@'.htmlentities($_SERVER['HTTP_HOST']), 'SimpleLogins NoReply');
					}
          //Set who the message is to be sent to
          $mail->addAddress(htmlentities($row['Email']), htmlentities($_POST['inputUsername']));
          //Set the subject line
          $mail->Subject = $config['SMTP']['FROM'] . ' Password Reset';
          //Read an HTML message body from an external file, convert referenced images to embedded,
          //convert HTML into a basic plain-text alternative body
          $resetlink = $SimpleLogins->sl_Vars()['Server_proto'] . $_SERVER['SERVER_NAME'] . "/?page=forgotpassword&username=" . $POSTDATA['Username'] . "&token=" .$token;
					$htmlmsg = file_get_contents('Mailtemplates/forgotpassword.html');
					$htmlmsg = str_replace("{USERNAME}",$row['Username'],$htmlmsg);
					$htmlmsg = str_replace("{SITENAME}",$config['SMTP']['FROM'],$htmlmsg);
					$htmlmsg = str_replace("{RESET_URL}",$resetlink,$htmlmsg);
					$mail->msgHTML($htmlmsg);
          //Replace the plain text body with one created manually
          $mail->AltBody = $config['SMTP']['FROM'] . ' Password Reset';
          //send the message, check for errors
          if (!$mail->send()) {
            //echo "Mailer Error: " . $mail->ErrorInfo;
            return "Internal Error";
          } else {
            return 1;
          }
        }else{
          return "Internal Error";
        }
      }else{
        return "Invalid Credentials";
      }
		}
		function Reset_password($POSTDATA, $config){
			if($POSTDATA['NewPassword'] !== $POSTDATA['NewPasswordConfirm']){
				return "No Pass Match";
			}else{
				$SimpleLogins = new SimpleLogins();
				$conn = $SimpleLogins->Database->Initialize($config['SQL']['Host'],$config['SQL']['Username'],$config['SQL']['Password'],$config['SQL']['Database']);
				$sql = "SELECT * FROM `".mysqli_real_escape_string($conn,$config['SQL']['Prefix'])."Users` WHERE `Username`='".mysqli_real_escape_string($conn,$POSTDATA['Username'])."' AND Reset_hash='".mysqli_real_escape_string($conn,$POSTDATA['Token'])."';";
				$sql_output = $conn->query($sql);
				if($sql_output->num_rows > 0){
					$sql = "UPDATE `".mysqli_real_escape_string($conn,$config['SQL']['Prefix'])."Users` SET `Password`='".password_hash($POSTDATA['NewPassword'],PASSWORD_DEFAULT)."', `Reset_hash`='' WHERE `Username`='".mysqli_real_escape_string($conn,$POSTDATA['Username'])."';";
					if($conn->query($sql)){
						return 1;
					}else{
						return "Interlan Error";
					}

				}else{
					return "Invalid Credentials";
				}
			}
		}
		function check_Resethash($DATA, $config){
			$SimpleLogins = new SimpleLogins();
			$conn = $SimpleLogins->Database->Initialize($config['SQL']['Host'],$config['SQL']['Username'],$config['SQL']['Password'],$config['SQL']['Database']);
			$sql = "SELECT * FROM `".mysqli_real_escape_string($conn,$config['SQL']['Prefix'])."Users` WHERE `Username`='".mysqli_real_escape_string($conn,$DATA['username'])."' AND Reset_hash='".mysqli_real_escape_string($conn,$DATA['token'])."';";
			$sql_output = $conn->query($sql);
			if($sql_output->num_rows > 0){
				return 1;
			}else{
				return 0;
			}
		}
		function Check_Session($config){
			$SimpleLogins = new SimpleLogins();
			$conn = $SimpleLogins->Database->Initialize($config['SQL']['Host'],$config['SQL']['Username'],$config['SQL']['Password'],$config['SQL']['Database']);
			if(!$conn){
				return "No_Conn";
			}else{
				session_start();
				$sql = "SELECT * FROM `".mysqli_real_escape_string($conn,$config['SQL']['Prefix'])."Users` WHERE `Username`='".mysqli_real_escape_string($conn,$_SESSION['Username'])."' AND `Session`='".mysqli_real_escape_string($conn,session_id())."';";
				$sql_output = $conn->query($sql);
				if ($sql_output->num_rows <= 0) {
					session_destroy();
					session_regenerate_id();
				}
			}
		}
	}
	class Database{
		function Initialize($host,$username,$password,$database){
			$conn = new mysqli($host, $username, $password, $database);
			// Check connection
			if ($conn->connect_error) {
    		die("Connection failed: " . $conn->connect_error);
			}
			return $conn;
		}

		function Initialize_No_Database($host,$username,$password){
			$conn = new mysqli($host, $username, $password);
			if ($conn->connect_error) {
    		die("Connection failed: " . $conn->connect_error);
			}
			return $conn;
		}
	}
	class Captcha{
		function Check($config,$g_response){
			$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$config['Captcha']['Secret']."&response=".$g_response);
			$responseKeys = json_decode($response,true);
			if(intval($responseKeys["success"]) !== 1) {
				return 0;
			} else {
				return 1;
			}
		}
	}
?>
