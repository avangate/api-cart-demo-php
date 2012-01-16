<?php ob_start(); ?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $title; ?></title>
<meta charset="utf-8" />
<link rel="stylesheet" href="style.css" media="screen" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js" ></script>
</head>
<body>
<?php
try { 
	$templateFile = basename ($_SERVER['PHP_SELF']);
	include (realpath('templates/' . str_replace('.php', '.tpl.php', $templateFile))); 
} catch (Exception $e) {
	// 404
	_e ($e);
?>
	<h2>Page not found</h2>
<?php 
}
?>
<!-- Execution time : <?php echo number_format($iExecTime, 5, ',', ' '); ?> ms. -->
<?php if (count ($errors) > 0) { ?> <!-- ERRORS: <?php echo implode("\n", $errors); ?> --> <?php } ?>
<p><?php echo $_SESSION['CART_DEMO']['SESSSID'];?></p>
</body>
</html>