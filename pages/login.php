<div class="row">
    <div class="col-md-8 col-md-offset-3">
			<form action="<?= htmlentities($SimpleLogins->sl_Vars()['System_url']); ?>" method="post">
				<legend>Please login to continue</legend>
				<fieldset>
					<div class="col-lg-10">
	        	<input class="form-control" name="Username" placeholder="Username" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;" type="text">
	      	</div>
					<div class="col-lg-10">
	        	<input class="form-control" name="Password" placeholder="Password" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;" type="password">
						<?php if($sl_config['Captcha']['Enabled']){echo $SimpleLogins->sl_Vars()['Captcha_form'];} ?>
					</div>
					<div class="form-group">
	      		<div class="col-lg-10 col-lg-offset-2">
							<input type="hidden" name="Action" value="Login">
							<input type="hidden" name="cb" value="<?= htmlentities($SimpleLogins->sl_Vars()['Server_proto'].$SimpleLogins->sl_Vars()['System_host']); ?>">
	        		<button type="reset" class="btn btn-default">Reset</button>
	        		<button type="submit" class="btn btn-primary">Login</button>
	      		</div>
	    		</div>
				</fieldset>
			</form>
			<a href="?page=forgotpassword">Forgot Password?</a>
		</div>
</div>
