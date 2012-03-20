<?php
$cartProducts = array();
try {
	$cartProducts = $c->getContents();
} catch (SoapFault $e) {
	if ($e->getMessage() == 'Invalid hash provided') {
		session_destroy();
	}
	_e ($e);
	exit();
} catch (Exception $e) {
	_e ($e);
	exit();
}
$iLevel = ob_get_level();
for ($i = 0 ; $i <  $iLevel -1; $i ++ ){
	$errors[] = ob_get_clean();
}
ob_end_clean();

$iExecTime = (microtime(true) - $iStart);
$title = 'View Cart';
include ('templates/main.tpl.php');