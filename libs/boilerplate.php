<?php
include ('libs/functions.php');
include ('libs/config.inc.php');

set_error_handler('exceptions_error_handler');

date_default_timezone_set('Europe/Bucharest');

$errors = array();
$iStart = microtime(true);

import ('libs');
import ('assets');
createEmptyCart();

// ob_start();
echo getErrorHeaderOutput (); // in the case of a fatal error we have this as fallback
ob_start();

$SearchOptions = array();
if (isset($_GET['page'])) {
	$SearchOptions['page']['size'] = 15;
	$SearchOptions['page']['number'] = (int) $_GET['page'];
}

$c = new mSOAPClient ( WSDL_URL );
$c->setAccountCode(MCODE);
$c->setSecretKey(KEY);

try {
	$c->authenticate();
} catch (SoapFault $e) {
	d ($e);
}