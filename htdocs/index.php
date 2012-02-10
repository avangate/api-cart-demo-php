<?php
set_include_path(
	get_include_path() . PATH_SEPARATOR .
	realpath ('../')
);

include ('functions.php');
include ('config.inc.php');

set_error_handler('exceptions_error_handler');
date_default_timezone_set('Europe/Bucharest');

$errors = array();
$iStart = microtime(true);

import ('lib');
import ('assets');

ob_start();
echo getErrorHeaderOutput (); // in the case of a fatal error we have this as fallback
ob_start(); // 1

try {
	include ('mcart.class.php');
	session_start();
} catch (SoapFault $e) {
	if ($e->getMessage() == 'Invalid hash provided') {
		session_destroy();
		session_start();
	}
	_e ($e);
}

$SearchOptions = array();
if (isset($_GET['page'])) {
	$SearchOptions['page']['size'] = 15;
	$SearchOptions['page']['number'] = (int) $_GET['page'];
}
try {
	if (isset ($_SESSION['CART'])) {
		$c = $_SESSION['CART'];
	} else {
		$c = new mCart();
		$_SESSION['CART'] = $c;
	}
} catch (SoapFault $e) {
	_e ($e);
}

$includePath = $_SERVER['SCRIPT_URL'];
if (substr($includePath, -1) == '/') {
	$includePath = substr($includePath, 0, -1);
}
if (substr($includePath, 0, 1) == '/') {
	$includePath = substr($includePath, 1);
}

if (empty($includePath)) $includePath = 'list-products';

$includePath .= '.php';

try {
	$path = '../' . $includePath;
	if ( realpath($path) ) {
		include ($includePath);
	} else {
		// 404
		for ($i = 0; $i <= ob_get_level(); $i++) {
			ob_end_clean();
		}
		header ('HTTP/1.1 404 Not Found');
		include ('templates/404.tpl.php');
		exit();
	}
} catch (Exception $e) {
	_e ($e);
	exit();
}
$iLevel = ob_get_level();
for ($i = 0 ; $i < $iLevel - 2; $i ++ ){
	$errors[] = ob_get_clean();
}
$iLevel = ob_get_level();
for ($i = 0 ; $i < $iLevel; $i ++ ){
	ob_end_clean(); // 0
}

$iExecTime = (microtime(true) - $iStart);
include ('templates/main.tpl.php');
// d ($_SESSION);

