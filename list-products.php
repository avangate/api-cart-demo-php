<?php

//$SearchOptions['status'] = true;
$SearchOptions['NetworkCrosselling'] = true;
$SearchOptions['type'] = 'REGULAR';
try {
	$a = array();

//	var_dump ($c);die;
	$a = $c->searchProducts($SearchOptions);
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

$title = 'Product Listing';
