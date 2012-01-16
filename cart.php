<?php
include ('libs/boilerplate.php');

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
//		if ($key == 'language') {
//			$language = $_POST['language'];
//		}
//		if ($key == 'country') {
//			$country = $_POST['country'];
//		}
	}
}
foreach ($priceOptions as $key => $option) {
	if (is_array ($option)) { 
		$priceOptions[$key] = implode (',', $option);
	} 
}

$currency = !empty($currency) ? $currency : getCartCurrency();
$quantity = !empty($quantity) ? $quantity : 1;
$quantity = isset($_GET['quantity']) ? $_GET['quantity'] : $quantity;

/* @var $c CSOAP_OrderAPI */
try {
	switch ($action) {
		case 'add' : // add to cart
			$c->addProduct($prodId, $quantity, implode(',', $priceOptions));
			$newPrice = $c->getPrice($prodId, $quantity, implode(',',$priceOptions), $currency);
			addToCart($prodId, $quantity, $priceOptions, $newPrice->FinalPrice, $newPrice->FinalCurrency);
			
			header ('HTTP/1.1 301 Moved Permanentely');
			header ('Location: /list-products.php');
			break;
		case 'getprice': // get a new price based on the quantity/price options change
			$newPrice = $c->getPrice($prodId, $quantity, implode(',', $priceOptions), $currency);
			$iLevel = ob_get_level();
			for ($i = 0 ; $i < $iLevel; $i ++ ) { 
				ob_end_clean();
			}
			header ('HTTP/1.1 200 OK');
			header ('Content-Type: application/json');
			echo json_encode($newPrice);
			exit(); // break;
		case 'del': // remove from cart
			$c->deleteProduct($prodId, $quantity);
			break;
		case 'set': // remove from cart
			$country = $_POST['country'];
			$currency = $_POST['currency'];
			
			try {
				$c->setCountry($country);
				$c->setCurrency($currency);
			} catch (SoapFault $e) {
				$errors[] = $e->getMessage();
				header ('HTTP/1.1 200 OK');
				header ('Content-Type: application/json');
				echo json_encode(array('status'=>'nok', 'error' => implode("\n", $errors)));
				break;
			}
			
			setCartCountry ($country);
			setCartCurrency ($currency);
			
			header ('HTTP/1.1 200 OK');
			header ('Content-Type: application/json');
			echo json_encode(array('status'=>'ok'));
			break;
		case 'emptycart': // remove from cart
			emptyCart();
			$c->clearProducts();
			header ('HTTP/1.1 303 See Other');
			header ('Location: /list-products.php');
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
$iLevel = ob_get_level();
for ($i = 0 ; $i <  $iLevel - 1; $i ++ ){
	$errors[] = ob_get_clean();
}
ob_end_clean();

$iExecTime = (microtime(true) - $iStart);
