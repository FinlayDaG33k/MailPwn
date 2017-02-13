<div class="row">
    <div class="col-md-8 col-md-offset-3">
			<?php
				if(!empty($_GET['username']) && !empty($_GET['token'])){
					if($SimpleLogins->Users->check_Resethash($_GET,$sl_config)){
						?>
							<form action="<?= htmlentities($SimpleLogins->sl_Vars()['System_url']); ?>" method="post">
								<legend>Enter your new password to reset your password</legend>
								<fieldset>
									<div class="col-lg-10">
					        	<input class="form-control" name="NewPassword" placeholder="New Password" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;" type="password">
									</div>
									<div class="col-lg-10">
					        	<input class="form-control" name="NewPasswordConfirm" placeholder="Confirm New Password" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;" type="password">
										<?php if($sl_config['Captcha']['Enabled']){echo $SimpleLogins->sl_Vars()['Captcha_form'];} ?>
									</div>
									<div class="form-group">
					      		<div class="col-lg-10 col-lg-offset-2">
											<input type="hidden" name="Action" value="Resetpassword">
											<input type="hidden" name="Token" value="<?= htmlentities($_GET['token']); ?>">
											<input type="hidden" name="Username" value="<?= htmlentities($_GET['username']); ?>">
											<input type="hidden" name="cb" value="<?= htmlentities($SimpleLogins->sl_Vars()['Server_proto'].$SimpleLogins->sl_Vars()['System_host'] . $SimpleLogins->sl_Vars()['System_dir_get']); ?>">
					        		<button type="reset" class="btn btn-default">Reset</button>
					        		<button type="submit" class="btn btn-primary">Submit</button>
					      		</div>
					    		</div>
								</fieldset>
							</form>
						<?php
					}else{
						echo "Invalid username/token combination!";
					}
				}else{
					?>
						<form action="<?= htmlentities($SimpleLogins->sl_Vars()['System_url']); ?>" method="post">
							<legend>Enter your username to send a reset link</legend>
							<fieldset>
								<div class="col-lg-10">
				        	<input class="form-control" name="Username" placeholder="Username" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;" type="text">
									<?php if($sl_config['Captcha']['Enabled']){echo $SimpleLogins->sl_Vars()['Captcha_form'];} ?>
								</div>
								<div class="form-group">
				      		<div class="col-lg-10 col-lg-offset-2">
										<input type="hidden" name="Action" value="Forgotpassword">
										<input type="hidden" name="cb" value="<?= htmlentities($SimpleLogins->sl_Vars()['Server_proto'].$SimpleLogins->sl_Vars()['System_host'] . $SimpleLogins->sl_Vars()['System_dir_get']); ?>">
				        		<button type="reset" class="btn btn-default">Reset</button>
				        		<button type="submit" class="btn btn-primary">Submit</button>
				      		</div>
				    		</div>
							</fieldset>
						</form>
			<?php } ?>
		</div>
</div>
