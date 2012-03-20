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
if (stristr($includePath, 'order')) {
?>	
<link rel="stylesheet" href="/styles/smoothness/jquery-ui.css" media="screen" />
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
		<div id="small-cart"><?php include ('templates/cart-small.tpl.php') ?> </div>
		<h1><a href="/"><img src="/images/logo.png" alt="Logo" /></a></h1>
		<div id="locale-change"> <?php include ('templates/locale.tpl.php') ?> </div>
	</div>
</div>
<br/>
<div id="main-content">
<?php
	$templatePath = realpath('../templates/' . str_replace('.php', '.tpl.php', $includePath));
	if (!$templatePath) {
		throw new Exception ('404');
	}
	include ($templatePath);
?>
</div>
<?php 
} catch (Exception $e) {
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
	Session ID : <?php echo $c->getSessionId(); ?><br/>
	SOAP Calls : <?php echo $c->getSoapCalls(); ?><br/>
	<div>Execution time : <?php echo number_format($iExecTime, 5, ',', ' '); ?> seconds.</div>
</div>
</body>
</html>
