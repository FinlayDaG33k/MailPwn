<script>
$(document).ready(function() {
	$( "#getDNS" ).submit(function( event ) {
		$.ajax({
   		url: '<?php if($_SERVER['HTTPS']){echo "https://";}else{echo "http://";} echo $_SERVER['SERVER_NAME']; ?>/inc/php/submit.php?'+$('#getDNS').serialize()+'',
			success: function(data) {
      	$( "div.getDNS_result" ).html(data);
   		},
   		type: 'GET'
		});
		event.preventDefault();
	});
});
</script>

<form id="getDNS" class="form-horizontal">
	<fieldset class="center" bgcolor="#EEDFCC">
		<input type="text" name="hostname" placeholder="Hostname" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%;" class="form-control"/>
		<input type="hidden" name="Action" value="getDNS" class="form-control">
		<br />
		<input type="submit" value="Send" class="btn btn-primary"/>
	</fieldset>
</form>
<br />
<div class="getDNS_result"></div>
