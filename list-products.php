<?php

$searchOptions['status'] = true;
try {
	$a = array();

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
