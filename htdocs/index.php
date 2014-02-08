<?php
set_include_path(
	get_include_path() . PATH_SEPARATOR .
	realpath ('../')
);
error_reporting(E_ALL );
ini_set ('display_errors', 1);
include ('functions.php');
include ('config.inc.php');

// set_error_handler('exceptions_error_handler');
date_default_timezone_set('Europe/Bucharest');

$errors = array();
$iStart = microtime(true);

import ('lib');
import ('assets');

try {
	$includePath = $_SERVER['PATH_INFO'];
	if (substr($includePath, -1) == '/') {
		$includePath = substr($includePath, 0, -1);
	}
	if (substr($includePath, 0, 1) == '/') {
		$includePath = substr($includePath, 1);
	}
	if (!stristr($includePath, 'testtemplate')) {
		//ob_start();
		echo getErrorHeaderOutput (); // in the case of a fatal error we have this as fallback
		ob_start(); // 1

		include ('mcart.class.php');
		session_start();

		$SearchOptions = array();
		if (isset($_GET['page'])) {
			$SearchOptions['page']['size'] = 15;
			$SearchOptions['page']['number'] = (int) $_GET['page'];
		}
		if (isset ($_SESSION['CART'])) {
			$c = $_SESSION['CART'];
		} else {
			$c = new mCart();
		}
	}
} catch (Exception $e) {
	_e ($e);
}

if (array_key_exists('api', $_GET) && in_array($_GET['api'], array('soap', 'jsonrpc'))) {
	setcookie('api', $_GET['api']);
	header ('HTTP/1.1 303 See Other');
	header ('Location: /');
	exit();
}


$title = 'Not Found';
if (empty($includePath)) $includePath = 'list-products';

$includePath .= '.php';
$country = 'us';

try {
	$path = '../' . $includePath;
	if ( realpath($path) ) {
		@include ($includePath);
	} else {
		// 404
		for ($i = 0; $i <= ob_get_level(); $i++) {
			ob_end_clean();
		}
		header ('HTTP/1.1 404 Not Found');
		include ('templates/404.tpl.php');
		exit();
	}
} catch (SoapFault $e) {
	_e ($e);
	echo '<pre>';
	var_dump ($c->getClient()->__getLastResponseHeaders());
	exit();
} catch (Exception $e) {
	_e ($e);
	exit();
}
$iLevel = ob_get_level();
for ($i = 0 ; $i < $iLevel; $i ++ ){
	ob_end_clean(); // 0
}

$iExecTime = (microtime(true) - $iStart);
if (!stristr($includePath, 'testtemplate')) {
	include ('templates/main.tpl.php');
} else {
	echo $content;
}
$_SESSION['CART'] = $c;
