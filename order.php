<?php
$cartProducts = array();
$cartPrices = array();

$title = 'Place Order';
try {
	$order = new mOrder();
	$order->RefNo = 0;
	$order->Status = 'FAILED';

	$mPayment = new mPaymentDetails();
	$step = isset($_GET['step']) ? $_GET['step'] : '1';

	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		
		$paymentType = isset($_GET['pmethod']) ? $_GET['pmethod'] : null;
		$mPayment->Type = $paymentType;
		
		if (isset($_SESSION['BILLING_DETAILS'])) {
			$mBilling = $_SESSION['BILLING_DETAILS'];
		} else {
			$mBilling = new mBillingDetails();
		}
		
		if (isset ($_GET['status'])) {
			$status = $_GET['status'];
			$msg = isset($_GET['message']) ? urldecode($_GET['message']) : null;
		}
		
		foreach ($c->getItems() as $idProduct => $data) {
			$cartProducts[$idProduct] = $c->getProductById($idProduct);
			$cartPrices[$idProduct] = $c->getProductById($idProduct);
		}
		
		// get session contents
		$AvangateCartContents = $c->getContents();
		
		if (isset($_GET['refNo'])) {
			$refNo = (int)$_GET['refNo'];
			if ($refNo > 0) {
				$status = $c->getOrderStatus($refNo);
				$c->emptyCart();
			} else {
				$status = 'NOK';
			}
			$msg = isset($_GET['message']) ? urldecode($_GET['message']) : null;
		} else {
			$refNo = null;
		}
	} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {

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
				
// 				$c->setPaymentDetails($mPayment);
				$c->setBillingDetails($mBilling);
				$_SESSION['BILLING_DETAILS'] = $mBilling;
			} catch (SoapFault $e) {
				$errors[] = $e->getMessage();
			}

			if (count($errors) <= 0) {
// 				$order = $c->placeOrder();
// 				if ($order->RefNo > 0) {
// 					$c->emptyCart();
// 				}
			} else {
				// ($errors);
			}
		} catch (SoapFault $e) {
			_e ($e);
		}
		
		header ('HTTP/1.1 303 See Other');
		if ($order instanceof mOrder && $order->RefNo > 0) {
			$c->emptyCart();
			header ('Location: /order/?refno=' . $order->RefNo . (isset($e) ? '&msg=' . urlencode($e->getMessage()) : ''));
		} else {
// 			header ('Location: /order/?' . (isset($e) ? 'msg=' . urlencode($e->getMessage()) : 'msg=Unknown+Error'));
			header ('Location: /order/?step=2');
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

