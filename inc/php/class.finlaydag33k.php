<?php
	class finlaydag33k{
		function generateRandomString($length = 8) {
		  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		  $charactersLength = strlen($characters);
		  $randomString = '';
		  for ($i = 0; $i < $length; $i++) {
		    $randomString .= $characters[rand(0, $charactersLength - 1)];
		  }
		  return $randomString;
		}
		function getDNS($hostname){
			return dns_get_record($hostname, DNS_A);
		}

		function getDNSTable($hostname){
			$result = dns_get_record($hostname, DNS_ALL);
			if($result){
				?>
					<table class="table table-striped table-hover ">
	  				<thead>
	    				<tr>
	      				<th>Host</th>
	      				<th>Type</th>
	      				<th>IP/Target</th>
	    				</tr>
	  				</thead>
						<tbody>
							<?php
							foreach($result as $entry => $value){
								?>
								<tr>
									<td><?= htmlentities($value["host"]); ?></td>
									<td><?= htmlentities($value["type"]); ?></td>
									<td>
										<?php
											switch($value["type"]){
												case "A":
													echo htmlentities($value["ip"]);
													break;
												case "NS":
													echo htmlentities($value["target"]);
													break;
												case "MX":
													$mx_result = $this->getDNS($value["target"]);
													echo htmlentities($value["target"]) . " (".$mx_result[0]["ip"].")";
													break;
												case "SOA":
													echo $value['mname'] . " (".htmlentities($value["rname"]).")";
													break;
												case "AAAA":
													echo htmlentities($value["ipv6"]);
													break;
												case "CNAME":
													$CNAME_result = $this->getDNS($value["target"]);
													echo htmlentities($value["target"]) . " (".$CNAME_result[0]["ip"].")";
													break;
												default:
													echo "Unknown";
													break;
											}
										?>
									</td>
								</tr>
								<?php
							}
							?>
						</tbody>
					</table>
				<?php
			}else{
				echo "No Results";
			}
		}
		function Spam($POSTDATA,$config){
			if(!empty($POSTDATA['mailto'])){
				$error_count = 0;
				echo "Setting Name...";
				if(empty($POSTDATA['name'])){
					echo "No name set, Randomizing one for you... ";
					$POSTDATA['name'] = $this->generateRandomString(16);
					echo "OK<br />";
					echo "Result: " . $POSTDATA['name'];
				}else{
					echo "OK";
				}
				echo "<br />";
				echo "Setting Address... ";
				if(empty($POSTDATA['addy'])){
					echo "No Address set, Randomizing one for you... ";
					$POSTDATA['addy'] = $POSTDATA['name'].'@'.$this->generateRandomString(16).'.com';
					echo "OK<br />";
					echo "Result: " . $POSTDATA['addy'];
				}else{
					echo "OK";
				}
				echo "<br />";
				echo "Setting Subject... ";
				if(empty($POSTDATA['subject'])){
					echo "No Subject set, Randomizing one for you... ";
					$POSTDATA['subject'] = $this->generateRandomString(16);
					echo "OK<br />";
					echo "Result: " . $POSTDATA['subject'];
				}else{
					echo "OK";
				}
				echo "<br />";
				echo "Checking X-Mailer... ";
				if(!in_array($POSTDATA['xmailer'],$config)){
					echo "Invalid X-Mailer. Defaulting to \"Gmail\"... ";
					$POSTDATA['xmailer'] = "Gmail";
					echo "OK";
	      }else{
					echo "OK";
				}
				echo "<br />";
				echo "Checking Message... ";
				if(empty($POSTDATA['message'])){
					echo "No Message set, Randomizing one for you... ";
					$POSTDATA['message'] = $this->generateRandomString(16);
					echo "OK<br />";
					echo "Result: " . $POSTDATA['message'];
				}else{
					echo "OK";
				}
				echo "<br />";

				if($POSTDATA['textorhtml'] == 'text'){ // text?
	        if($POSTDATA['xmailer'] == 'none'){
	        	$headers = 	'From: '.$POSTDATA['name'].' <'.$POSTDATA['addy'].'>' . "\r\n" .
	                    	'Reply-To: '.$POSTDATA['reply'];
	        }else{
						$headers = 	'From: '.$POSTDATA['name'].' <'.$POSTDATA['addy'].'>' . "\r\n" .
		                    'Reply-To: '.$POSTDATA['reply'] . "\r\n" .
		                    'X-Mailer: ' . $POSTDATA['xmailer'];
					}
	      }elseif($POSTDATA['textorhtml'] == 'html'){
					$headers = 	'MIME-Version: 1.0' . "\r\n" .
					          	'Content-Type: text/html; charset=ISO-8859-1' . "\r\n" .
					            'From: '.$POSTDATA['name'].' <'.$POSTDATA['addy'].'>' . "\r\n" .
					            'Reply-To: ' . $POSTDATA['reply'] . "\r\n" .
					            'X-Mailer: ' . $POSTDATA['xmailer'];
					if($xmailer == 'none'){
					  $headers = 	'MIME-Version: 1.0' . "\r\n" .
					             	'Content-Type: text/html; charset=ISO-8859-1' . "\r\n" .
					             	'From: '.$from['name'].' <'.$from['addy'].'>' . "\r\n" .
					              'Reply-To: ' . $reply;
					}
				}

				$mail_count = 0;

				for($i = 0;$i < $POSTDATA['amount'];$i++){
					if(mail($POSTDATA['mailto'], $POSTDATA['subject'], $POSTDATA['message'], $headers)){
						$mail_count++;
					}
				}

				if($mail_count >= 1){
					echo $mail_count . " Mails has been send to ".$POSTDATA['mailto']."!";
				}else{
					echo "Something may have gone wrong...<br />";
				}

			}else{
				echo "Target email can not be empty!";
			}
		}
		function Spoof($POSTDATA,$config){
			if(!empty($POSTDATA['mailto'])){
				$error_count = 0;
				echo "Setting Name...";
				if(empty($POSTDATA['name'])){
					echo "No name set, Randomizing one for you... ";
					$POSTDATA['name'] = $this->generateRandomString(16);
					echo "OK<br />";
					echo "Result: " . $POSTDATA['name'];
				}else{
					echo "OK";
				}
				echo "<br />";
				echo "Setting Address... ";
				if(empty($POSTDATA['addy'])){
					echo "No Address set, Randomizing one for you... ";
					$POSTDATA['addy'] = $POSTDATA['name'].'@'.$this->generateRandomString(16).'.com';
					echo "OK<br />";
					echo "Result: " . $POSTDATA['addy'];
				}else{
					echo "OK";
				}
				echo "<br />";
				echo "Setting Subject... ";
				if(empty($POSTDATA['subject'])){
					echo "No Subject set, Randomizing one for you... ";
					$POSTDATA['subject'] = $this->generateRandomString(16);
					echo "OK<br />";
					echo "Result: " . $POSTDATA['subject'];
				}else{
					echo "OK";
				}
				echo "<br />";
				echo "Checking X-Mailer... ";
				if(!in_array($POSTDATA['xmailer'],$config)){
					echo "Invalid X-Mailer. Defaulting to \"Gmail\"... ";
					$POSTDATA['xmailer'] = "Gmail";
					echo "OK";
	      }else{
					echo "OK";
				}
				echo "<br />";
				echo "Checking Message... ";
				if(empty($POSTDATA['message'])){
					echo "No Message set, Randomizing one for you... ";
					$POSTDATA['message'] = $this->generateRandomString(16);
					echo "OK<br />";
					echo "Result: " . $POSTDATA['message'];
				}else{
					echo "OK";
				}
				echo "<br />";

				if($POSTDATA['textorhtml'] == 'text'){ // text?
	        if($POSTDATA['xmailer'] == 'none'){
	        	$headers = 	'From: '.$POSTDATA['name'].' <'.$POSTDATA['addy'].'>' . "\r\n" .
	                    	'Reply-To: '.$POSTDATA['reply'];
	        }else{
						$headers = 	'From: '.$POSTDATA['name'].' <'.$POSTDATA['addy'].'>' . "\r\n" .
		                    'Reply-To: '.$POSTDATA['reply'] . "\r\n" .
		                    'X-Mailer: ' . $POSTDATA['xmailer'];
					}
	      }elseif($POSTDATA['textorhtml'] == 'html'){
					$headers = 	'MIME-Version: 1.0' . "\r\n" .
					          	'Content-Type: text/html; charset=ISO-8859-1' . "\r\n" .
					            'From: '.$POSTDATA['name'].' <'.$POSTDATA['addy'].'>' . "\r\n" .
					            'Reply-To: ' . $POSTDATA['reply'] . "\r\n" .
					            'X-Mailer: ' . $POSTDATA['xmailer'];
					if($xmailer == 'none'){
					  $headers = 	'MIME-Version: 1.0' . "\r\n" .
					             	'Content-Type: text/html; charset=ISO-8859-1' . "\r\n" .
					             	'From: '.$from['name'].' <'.$from['addy'].'>' . "\r\n" .
					              'Reply-To: ' . $reply;
					}
				}

				if(mail($POSTDATA['mailto'], $POSTDATA['subject'], $POSTDATA['message'], $headers)){
					echo "Spoofed mail has been send to ".$POSTDATA['mailto']."!";
				}else{
					echo "Something may have gone wrong...";
				}

			}else{
				echo "Target email can not be empty!";
			}
		}
	}
