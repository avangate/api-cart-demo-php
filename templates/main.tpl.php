<?php
try {
	ob_start();
	header ('HTTP/1.1 200 OK');
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $title; ?></title>
<meta charset="utf-8" />
<link rel="stylesheet" href="/styles/style.css" media="screen" />
<link rel="shortcut icon" href="/images/favicon.ico" />
<script type="text/javascript" src="/scripts/jquery.min.js" ></script>
<?php
if (true || stristr($includePath, 'order')) {
?>
<link rel="stylesheet" href="/styles/vader/jquery-ui.css" media="screen" />
<script type="text/javascript" src="/scripts/jquery-ui.min.js" ></script>
<?php
}
?>
<script type="text/javascript">
	$(document).ready(function () {
		$('body').height (Math.max ($(document).height(), $(window).height()));
		$(window).bind ('resize', function (e) {
			console.debug ('resize');
			$('body').height (Math.max ($(document).height(), $(window).height()));
		});
	});
</script>
</head>
<body>
<div id="header">
	<div>
		<?php if (count($errors) >= 1) { ?><div class="error"><?php echo implode ('<br/>', $errors); ?></div><?php } ?>
		<div id="small-cart" style="margin-top:4px"><?php include ('templates/cart-small.tpl.php') ?> </div>
		<h1><a href="/"><img src="/images/logo.png" alt="Logo" /></a></h1>
	</div>
</div>
<div id="main-content">
<?php
/*/
?> <div id="locale-change"> <?php include ('templates/locale.tpl.php') ?> </div><?php
/**/
	$templatePath = realpath('../templates/' . str_replace('.php', '.tpl.php', $includePath));
	if (!$templatePath) {
		throw new Exception ('404');
	}
	include ($templatePath);
?>
</div>
<?php
} catch (Exception $e) {
	ob_end_clean();
	ob_start();
	if ($e->getMessage() == '404') {
		header ('HTTP/1.1 404 Not Found');
	// 404
?>
	<h2 style="color:#900;margin:1.2em;text-align:right;">Page not found</h2>
	<div style="margin:1.2em;text-align:left">Please see if you misspelled the URL.</div>
<?php
	} else {
		header ('HTTP/1.1 500 Server Error');
?>
	<h2 style="color:#900;margin:1.2em;text-align:right;"><?php  echo $e->getMessage() ?></h2>
	<div style="margin:1.2em;text-align:left"><pre><?php echo $e->getTraceAsString()?></pre></div>
<?php
	}
	ob_flush();
}
?>
<?php if (count ($errors) > 0) { ?> <!-- ERRORS: <?php echo implode("\n", $errors); ?> --> <?php } ?>
<div id="footer">
	Session ID : <strong style="font-family:monospace"><?php echo $c->getSessionId(); ?></strong><br/>
	Session Type : <strong><?php echo ($c->getClient() instanceof mJsonRPCClient) ? 'Json-RPC' : 'SOAP'; ?></strong><br/>
	API Calls : <strong><?php echo $c->getAPICalls(); ?></strong><br/>
	<div>Execution time : <strong><?php echo number_format($iExecTime, 5, ',', ' '); ?></strong> seconds.</div>
	<pre style="font-family: fixed">
<?php foreach ($c->getAPIRequests() as $id => $oRequest) { ?>
<div id="<?php echo $id ?>">
<?php echo json_encode($oRequest); ?>
<br/>
<?php echo json_encode($c->getAPIResponse($id)); ?>
</div>
<?php } ?>
	</pre>
</div>
<script type="text/javascript">
	$(document).ready (function () {
		$('#locale_form').children('select').change(function (e) {
			var req = {};
			$('#locale_form').children('select').each (function () {
				var varname = $(this).attr('name');
				var value = $(this).val();
				req[varname] = value;
			});
			var status =  $('<img src="/images/waiting.gif" />');
			$.ajax({
				url : '/cart/?&action=set',
				type : 'post',
				dataType : 'json',
				data : req,
				beforeSend: function (data) {
					status.appendTo('locale-select');
				},
				success: function (data) {
					console.debug (status, data);
					//document.location.reload(true);
				},
				complete : function (data) {
					console.debug (status, data);
					//document.location.reload(true);
//				    $('#locale_select').remove(status);
				}
			});
			document.location.reload(true);
		});
	});
</script>
</body>
</html>
