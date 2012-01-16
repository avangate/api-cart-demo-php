<?php
include ('libs/boilerplate.php');

$prodId = (int) $_GET['id'];

try {
// 	$c->authenticate();
	
	$defaultCountry = getCartCountry();
	$defaultCurrency = getCartCurrency();
	try {
		$c->setCountry($defaultCountry);
		$c->setCurrency($defaultCurrency);
	} catch (SoapFault $e) {
		$errors[] = $e->getMessage();
	}
	$prod = $c->getProductById ($prodId);
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

$title = 'Product Details: ' . strip_tags($prod->ProductName);

include ('templates/main.tpl.php');
