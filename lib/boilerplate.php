<?php
include ('functions.php');
include ('config.inc.php');

set_error_handler('exceptions_error_handler');

date_default_timezone_set('Europe/Bucharest');

$errors = array();
$iStart = microtime(true);

import ('libs');
import ('assets');

include ('mcart.class.php');
session_start();

// ob_start();
echo getErrorHeaderOutput (); // in the case of a fatal error we have this as fallback
ob_start();

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
} catch (Exception $e) {
	_e ($e);
}
