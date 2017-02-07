<script src="//<?= $_SERVER['SERVER_NAME']; ?>/inc/js/jquery-cookie.js"></script>
<script>
$(document).ready(function () {
	var cookies = document.cookie.split(';');
	for(var i=0;i<cookies.length;i++) {
		var currentCookie = $.trim(cookies[i]);
		if(currentCookie.indexOf("collapsed_")==0) {
			currentCookie = currentCookie.split('=');
			if($.cookie(currentCookie[0]) == "true"){
				currentCookie = currentCookie[0].split('_');
				$("#" + currentCookie[1]).removeClass('in');
			}
		}
	}
	document.cookie = cookies.join(";");
	$('.panel-body').on('hidden.bs.collapse', function (e) {
    $.cookie("collapsed_"+e.currentTarget.id, true); // Set the cookie to true
		$("#" + currentCookie[1]).removeClass('fa-chevron-down');
	});
	$('.panel-body').on('show.bs.collapse', function (e) {
    $.cookie("collapsed_"+e.currentTarget.id, false);  // Set the cookie to false
	});
});
</script>

<div class="row">
	<div class="col-md-4">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">Spammer <a data-toggle="collapse" data-target="#spammer"><button type="button" class="pull-right btn btn-xs btn-info"><i class="fa fa-bars" aria-hidden="true"></i></button></a></h3>
			</div>
			<div id="spammer" class="panel-body collapse in">
				<?php include("pages/spammer.php"); ?>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">Spoofer <a data-toggle="collapse" data-target="#spoofer"><button type="button" class="pull-right btn btn-xs btn-info"><i class="fa fa-bars" aria-hidden="true"></i></button></a></h3>
			</div>
			<div id="spoofer" class="panel-body collapse in">
				<?php include("pages/spoofer.php"); ?>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">DNS Lookup <a data-toggle="collapse" data-target="#dnslookup"><button type="button" class="pull-right btn btn-xs btn-info"><i class="fa fa-bars" aria-hidden="true"></i></button></a></h3>
			</div>
			<div id="dnslookup" class="panel-body collapse in">
				<?php include("pages/getdns.php"); ?>
			</div>
		</div>
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">Check Blacklists <a data-toggle="collapse" data-target="#mxtest"><button type="button" class="pull-right btn btn-xs btn-info"><i class="fa fa-bars" aria-hidden="true"></i></button></a></h3>
				</div>
				<div id="mxtest" class="panel-body collapse in">
					<?php include("pages/checkbl.php"); ?>
				</div>
			</div>
	</div>
</div>
<div class="row">

</div>
