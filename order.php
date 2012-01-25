<?php
include ('libs/boilerplate.php');

$cartProducts = array();
$cartPrices = array();
try {
// 	$c->authenticate();
	$order = new mOrder();
	$order->RefNo = 0;
	$order->Status = 'FAILED';
	
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		if (isset($_GET['refno'])) {
			$refNo = $_GET['refno'];
			if ($refNo > 0) {
				$status = $c->getOrderStatus($refNo);
			} else {
				$status = 'FAILED';
			}
			$msg = isset($_GET['msg']) ? urldecode($_GET['msg']) : null;
		}
		
		foreach ($c->getItems() as $idProduct => $data) {
			$cartProducts[$idProduct] = $c->getProductById($idProduct);
			$cartPrices[$idProduct] = $c->getProductById($idProduct);
		}
	} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$mPayment = new mPaymentDetails();
			$mPayment->Currency = 'EUR';
			$mPayment->CustomerIP = $_SERVER['REMOTE_ADDR'];
			$mPayment->Type = 'CCVISAMC';
			$mPayment->PaymentMethod = new mCardPayment();
			$mPayment->PaymentMethod->CCID = $_POST['ccid'];
			$mPayment->PaymentMethod->CardNumber = $_POST['card_number'];
			$mPayment->PaymentMethod->CardType = 'VISA';
			$mPayment->PaymentMethod->ExpirationMonth = $_POST['date_month'];
			$mPayment->PaymentMethod->ExpirationYear = $_POST['date_year'];
			$mPayment->PaymentMethod->HolderName = $_POST['holder_name'];

			$mBilling = new mBillingDetails();
			$mBilling->Address = $_POST['address'];
			$mBilling->City = $_POST['city'];
// 			$mBilling->Company = $_POST['company'];
// 			$mBilling->FiscalCode = $_POST['fiscal_code'];
			$mBilling->Country = $_POST['country_code'];
			$mBilling->Email = $_POST['email'];
			$mBilling->FirstName = $_POST['first_name'];
			$mBilling->LastName = $_POST['last_name'];
			$mBilling->PostalCode = $_POST['postal_code'];
			$mBilling->State = $_POST['state'];
			try {
				
				$c->setPaymentDetails($mPayment);
				$c->setBillingDetails($mBilling);
			} catch (SoapFault $e) {
				$errors[] = $e->getMessage();
			}

			if (count($errors) <= 0) {
				$order = $c->placeOrder();
				if ($order->RefNo > 0) {
					$c->emptyCart();
				}
			} else {
				d ($errors);
			}
		} catch (SoapFault $e) {
			_e ($e);
		}
		
		header ('HTTP/1.1 303 See Other');
		if ($order instanceof mOrder) {
			header ('Location: /order.php?refno=' . $order->RefNo . (isset($e) ? '&msg=' . urlencode($e->getMessage()) : ''));
		} else {
			header ('Location: /order.php?' . (isset($e) ? 'msg=' . urlencode($e->getMessage()) : 'msg=Unknown+Error'));
		}
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
for ($i = 0 ; $i <  $iLevel -1; $i ++ ){
	$errors[] = ob_get_clean();
}
ob_end_clean();

$iExecTime = (microtime(true) - $iStart);

$title = 'Place Order';
include ('templates/main.tpl.php');