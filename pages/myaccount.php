<div class="row">
	<div class="col-md-4">
		<div class="panel panel-info">
		  <div class="panel-heading">
		    <h3 class="panel-title">Change Password</h3>
		  </div>
		  <div class="panel-body">
				<form action="<?= htmlentities($SimpleLogins->sl_Vars()['System_url']); ?>" method="POST" class="form-horizontal">
				  <fieldset>
				    <div class="form-group">
				      <div class="col-lg-12">
				        <input class="form-control" name="Oldpassword" placeholder="Current Password" type="password">
				      </div>
				    </div>
						<div class="form-group">
				      <div class="col-lg-12">
				        <input class="form-control" name="Newpassword" placeholder="New Password" type="password">
				      </div>
				    </div>
						<div class="form-group">
				      <div class="col-lg-12">
				        <input class="form-control" name="Newpasswordconfirm" placeholder="Confirm New Password" type="password">
								<?php if($sl_config['Captcha']['Enabled']){echo $SimpleLogins->sl_Vars()['Captcha_form'];} ?>
				      </div>
				    </div>
						<div class="form-group">
      				<div class="col-lg-10">
								<input type="hidden" name="cb" value="<?= htmlentities($SimpleLogins->sl_Vars()['Server_proto'].$SimpleLogins->sl_Vars()['System_host'].$SimpleLogins->sl_Vars()['System_dir_get']); ?>">
								<input type="hidden" name="Action" value="ChangePassword">
        				<button type="reset" class="btn btn-default">Cancel</button>
        				<button type="submit" class="btn btn-primary">Submit</button>
      				</div>
    				</div>
					</fieldset>
				</form>
		  </div>
		</div>
	</div>
</div>
