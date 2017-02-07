<script>
$(document).ready(function() {
	$( "#checkBL" ).submit(function( event ) {
		$.ajax({
   		url: '<?php if($_SERVER['HTTPS']){echo "https://";}else{echo "http://";} echo $_SERVER['SERVER_NAME']; ?>/inc/php/submit.php',
			data: $('#checkBL').serialize(),
			async: false,
			success: function(data) {
      	$( "div.checkBL_result" ).html(data);
   		},
   		type: "POST"
		});
		event.preventDefault();
	});
});
</script>

<form id="checkBL" class="form-horizontal">
	<fieldset class="center" bgcolor="#EEDFCC">
		<input type="text" name="ip" placeholder="IP Adress" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%;" class="form-control"/>
		<input type="hidden" name="Action" value="checkBL" class="form-control">
		<br />
		<input type="submit" value="Send" class="btn btn-primary"/>
	</fieldset>
</form>
<br />
<div class="checkBL_result"></div>
