<?php
$prodId = (int) $_GET['id'];

try {
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

$title = 'Product Details: ' . strip_tags($prod->ProductName);

