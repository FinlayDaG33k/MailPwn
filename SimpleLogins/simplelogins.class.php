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
			$system_dir = explode('?', $_SERVER['REQUEST_URI'], 2);

			$sl_vars = 	array(
										"Server_proto" => $server_proto,
										"System_host" => htmlentities($system_host),
										"System_dir" => htmlentities($system_dir),
										"System_url" => htmlentities($server_proto . $system_host .$system_dir[0]. "SimpleLogins/system.php"),
										"Captcha_form" => "<div class=\"g-recaptcha\" data-sitekey=\"".$sl_config['Captcha']['Sitekey']."\"></div>",
										"Captcha_script" => "<script src='https://www.google.com/recaptcha/api.js'></script>"
									);
			return $sl_vars;
		}
	}

	class Users{
		function Login($Username, $Password, $config){
			$SimpleLogins = new SimpleLogins();
			$conn = $SimpleLogins->Database->Initialize($config['SQL']['Host'],$config['SQL']['Username'],$config['SQL']['Password'],$config['SQL']['Database']);
			if(!$conn){
				return "No_Conn";
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
		function Change_password(){

		}
		function Reset_password(){}
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
