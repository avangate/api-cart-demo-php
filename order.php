<?php
$cartProducts = array();
$cartPrices = array();

$title = 'Place Order';
$defaultCards = array();
$defaultCards['VISA']->CardNumber = '4111111111111111';
$defaultCards['VISA']->CCID = '212';
$defaultCards['VISA']->CardType = 'visa';
$defaultCards['VISA']->ExpirationMonth = '04';
$defaultCards['VISA']->ExpirationYear = '2014';
$defaultCards['VISA']->HolderName = 'api test visa';
$defaultCards['MAESTRO'] = clone ($defaultCards['VISA']);
$defaultCards['MAESTRO']->HolderName = 'api test mc';
$defaultCards['AMEX'] = new mCardPayment();
$defaultCards['AMEX']->CardNumber = '374200000000004';
$defaultCards['AMEX']->CCID = '212';
$defaultCards['AMEX']->CardType = 'amex';
$defaultCards['AMEX']->ExpirationMonth = '04';
$defaultCards['AMEX']->ExpirationYear = '2014';
$defaultCards['AMEX']->HolderName = 'api test amex';
$defaultCards['DISCOVERY'] = new mCardPayment();
$defaultCards['DISCOVERY']->CardNumber = '6011111111111117';
$defaultCards['DISCOVERY']->CCID = '212';
$defaultCards['DISCOVERY']->CardType = 'discover';
$defaultCards['DISCOVERY']->ExpirationMonth = '04';
$defaultCards['DISCOVERY']->ExpirationYear = '2014';
$defaultCards['DISCOVERY']->HolderName = 'api test discover';

try {
	$order = new mOrder();
	$order->RefNo = 0;
	$order->Status = 'FAILED';

	$mPayment = new mPaymentDetails();
	$mBilling = new mBillingDetails();

	$step = isset($_GET['step']) ? $_GET['step'] : '1';
	$paymentType = isset($_GET['pmethod']) ? $_GET['pmethod'] : null;

	$mPayment->Type = $paymentType;

	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		if (isset($_SESSION['BILLING_DETAILS'])) {
			$mBilling = $_SESSION['BILLING_DETAILS'];
		} else {
			$mBilling = new mBillingDetails();
		}

		if (isset ($_GET['status'])) {
			$status = $_GET['status'];
			$msg = isset($_GET['message']) ? urldecode($_GET['message']) : null;
		}

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
			if ($step == 1) {
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
// 				try {
					$_SESSION['BILLING_DETAILS'] = $mBilling;
					$c->setBillingDetails($mBilling);

					header ('HTTP/1.1 303 See Other');
					header ('Location: /order/?step=2');
					exit();
// 				} catch (SoapFault $e) {
// 					$errors[] = $e->getMessage();
// 				}
			}
			if ($step == 2) {
				$mPayment->Currency = $c->getCurrency();
				$mPayment->CustomerIP = $_SERVER['REMOTE_ADDR'];

				if ($mPayment->Type == 'CC') {
					$mCardPayment = new mCardPayment();
					$mCardPayment->CardNumber = @$_POST['card_number'];
					$mCardPayment->CCID = @$_POST['ccid'];
					$mCardPayment->CardType = @$_POST['card_type'];
					$mCardPayment->ExpirationMonth = @$_POST['date_month'];
					$mCardPayment->ExpirationYear = @$_POST['date_year'];
					$mCardPayment->HolderName = @$_POST['holder_name'];
					$mPayment->PaymentMethod = $mCardPayment;
				} elseif ($mPayment->Type == 'PAYPAL') {
					$mPayPalPayment = new mPaymentDetails();
				}

				try {
					d($c->setPaymentDetails($mPayment));
					$_SESSION['PAYMENT_DETAILS'] = $mPayment;
				} catch (SoapFault $e) {
					$errors[] = $e->getMessage();
				}
			}

			if (count($errors) <= 0) {
				if ($step == 2) {
	 				$order = $c->placeOrder();
	 				if ($order->RefNo > 0) {
	 					$c->emptyCart();
	 				}
				}
			} else {
				 d ($errors);
			}
		} catch (SoapFault $e) {
			_e ($e);
		}
		d ($order);
		header ('HTTP/1.1 303 See Other');
		if ($order instanceof mOrder && $order->RefNo > 0) {
			$c->emptyCart();
			header ('Location: /order/?refno=' . $order->RefNo . (isset($e) ? '&msg=' . urlencode($e->getMessage()) : ''));
		} else {
			header ('Location:' . $_SERVER['REQUEST_URI']);
		}
		exit;
	}
} catch (Exception $e) {
	_e ($e);
}

