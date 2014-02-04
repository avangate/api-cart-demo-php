<?php
/* @var $c mCart */

function outputJson ($stuff) {
	for ($i = 0 ; $i <= ob_get_level(); $i++) {
		ob_end_clean();
	}
	header ('HTTP/1.1 200 OK');
	header ('Content-Type: application/json');
		
	echo json_encode($stuff);
	
	exit();
}

$prodId = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : 'getprice';

$priceOptions = array();
if (in_array($action, array ('add', 'getprice')) ) {
	foreach ($_POST as $key => $value) {
		if (!in_array($key, array('id', 'price', 'currency', 'quantity', 'language', 'country'))) {
			$priceOptions[$key] = $value;
		} 
		if ($key == 'id') {
			$prodId = $_POST['id'];
		}
		if ($key == 'price') {
			$price = $_POST['price'];
		}
		if ($key == 'currency') {
			$currency = $_POST['currency'];
		}
		if ($key == 'quantity') {
			$quantity = $_POST['quantity'];
		}
		if ($key == 'language') {
			$language = $_POST['language'];
		}
		if ($key == 'country') {
			$country = $_POST['country'];
		}
	}
}
foreach ($priceOptions as $key => $option) {
	if (is_array ($option)) { 
		$priceOptions[$key] = implode (',', $option);
	} 
}

$currency = !empty($currency) ? $currency : $c->getCurrency();
$quantity = !empty($quantity) ? $quantity : 1;
$quantity = isset($_GET['quantity']) ? $_GET['quantity'] : $quantity;

/* @var $c CSOAP_OrderAPI */
try {
	switch ($action) {
		case 'modq':
			$newPrice = $c->modifyQuantity ($prodId, $quantity);
			
			outputJson($newPrice); // break;
		case 'add' : // add to cart
			$newPrice = $c->addToCart ($prodId, $quantity, $priceOptions);
			
			header ('HTTP/1.1 301 Moved Permanentely');
			header ('Location: /list-products/');
			break;
		case 'getprice': // get a new price based on the quantity/price options change
			if (!is_null($prodId)) {
				$newPrice = $c->getPrice($prodId, $quantity, implode(',', $priceOptions), $currency);
			} else {
				$newPrice = 0;
			}
			outputJson($newPrice); // break;
			
			exit(); // break;
		case 'del': // remove from cart
			$c->removeFromCart ($prodId);
			
			header ('HTTP/1.1 301 Moved Permanentely');
			header ('Location: /list-products/');
			break;
		case 'set': //set stuff
			$country = isset($_POST['country']) ? $_POST['country'] : null;
			$currency = isset($_POST['currency']) ? $_POST['currency'] : null;

			try {
				if (!is_null($country)) $c->setCountry(strtoupper($country));
				if (!is_null($currency)) $c->setCurrency(strtoupper($currency));
			} catch (SoapFault $e) {
				$errors[] = $e->getMessage();
				outputJson(array('status'=>'nok', 'error' => implode("\n", $errors))); // break;
				break;
			}
			
			outputJson(array('status'=>'ok'));
			break;
		case 'emptycart': // remove from cart
			$c->emptyCart();
			header ('HTTP/1.1 303 See Other');
			header ('Location: /list-products/');
			break;
	}
	
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
exit();