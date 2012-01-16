<?php
import ('lib');
import ('assets');
class mCart extends stdClass {
	private $soapClient;
		
	public function __construct () {
		session_name('CART');
		session_start();
		if (!isset ($_SESSION['CART_DEMO'])) {
			$_SESSION['CART_DEMO'] = array (
				'CART' => array (
					'PRODUCTS' => array(),
					'PRICE' => 0,
				),
				'COUNTRY' => 'gb',
				'CURRENCY' => 'EUR',
				'SESSSTART'=> null,
				'SESSSID'=> null,
			);

		}
		
		$this->soapClient =  new mSOAPClient ( "http://git.avangate.marius/api/order/?wsdl" );
		$c->setAccountCode(MCODE);
		$c->setSecretKey(KEY);
	}
	
	public function __destruct () {
		session_unset();
		session_destroy();
	}
	
	function addToCart ($prodId, $quantity = 1, $priceOptions = '', $newPrice =  0) {
		$mProd = new mProduct();
		$mProd->ProductId  =  $prodId;
		$_SESSION['CART_DEMO']['CART']['PRODUCTS'][$prodId] = array (
			'QUANTITY' => $quantity,
			'PRICEOPTIONS' =>$priceOptions,
			'PRICE' => $newPrice
		);
		$_SESSION['CART_DEMO']['CART']['PRICE'] += $newPrice;
	}

	function getCartPrice () {
		return $_SESSION['CART_DEMO']['CART']['PRICE'];
	}

	function getCartQuantities () {
		$q = 0 ;
		foreach ($_SESSION['CART_DEMO']['CART']['PRODUCTS'] as $iProd => $aData) {
			$q += $aData['QUANTITY'];
		}
		return $q;
	}
	function getCartItemsNumber () {
		return count ($_SESSION['CART_DEMO']['CART']['PRODUCTS']);
	}

	function getCartItems () {
		return $_SESSION['CART_DEMO']['CART']['PRODUCTS'];
	}
	function getCartProductQuantity($idProduct) {
		return $_SESSION['CART_DEMO']['CART']['PRODUCTS'][$idProduct]['QUANTITY'];
	}
	function getCartProductPrice($idProduct) {
		return $_SESSION['CART_DEMO']['CART']['PRODUCTS'][$idProduct]['PRICE'];
	}
	function setCartCountry ($c){
		$_SESSION['CART_DEMO']['COUNTRY'] = $c;
	}
	function setCartCurrency ($c){
		$_SESSION['CART_DEMO']['CURRENCY'] = $c;
	}
	function getCartCountry () {
		return $_SESSION['CART_DEMO']['COUNTRY'];
	}
	function getCartCurrency () {
		return $_SESSION['CART_DEMO']['CURRENCY'];
	}
}