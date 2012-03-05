<?php
class mCart extends stdClass {
	/**
	 * @var CSOAP_OrderAPI
	 */
	private $Client;
	private $Items = array();
	
	private $TotalPrice = 0;
	
	private $Country = 'DE';
	private $Currency = 'EUR';
	private $Language = 'EN';
	
	private $SessionID = null;
	private $SessionStart = null;
		
	public function __construct () {
		$this->connect();
		if (is_null($this->SessionID)) {
			$this->SessionID = $this->Client->authenticate();
		}
		$this->SessionStart = time();
		$this->Client->setSessionStart($this->SessionStart);
	}
	
	private function connect () {
		$this->Client = new mSOAPClient( WSDL_URL );
		$this->Client->setAccountCode(MCODE);
		$this->Client->setSecretKey(KEY);
		
 		if (!is_null($this->SessionStart)) {
			$this->Client->setSessionId($this->SessionID);
			$this->Client->setSessionStart($this->SessionStart);
			
			if (!is_null($this->Language)) {
				$this->Client->setLanguage(strtolower($this->Language));
			}
			if (!is_null($this->Country)) {
				$this->Client->setCountry(strtolower($this->Country));
			}
			if (!is_null($this->Currency)) {
				$this->Client->setCurrency(strtolower($this->Currency));
			}
		}
//			} else {
// 			d ($this, $_SESSION);
// 			$this->SessionStart = time();
// 			$this->SessionID = $this->Client->authenticate();
// 		}
	}
	
	public function getClient () {
		return $this->Client;
	}
	
	public function getSessionId () {
		return $this->SessionID;
	}
	
	public function getSoapCalls () {
		return mSOAPClient::$calls;
	}
	
	public function addToCart ($prodId, $quantity = 1, $priceOptions = '', $newPrice =  0) {
		$this->Client->addProduct($prodId, $quantity, implode(',', $priceOptions));
		$newPrice = $this->Client->getPrice($prodId, $quantity, implode(',',$priceOptions), $this->getCurrency());
		
		$mProd = new mProduct();
		$mProd->ProductId  =  $prodId;
		
		$this->Items[$prodId] = array (
					'QUANTITY' => $quantity,
					'PRICEOPTIONS' =>$priceOptions,
					'PRICE' => $newPrice->NetPrice,
					'CURRENCY' => $newPrice->NetCurrency
		);
		$this->TotalPrice += $newPrice->NetPrice;
		
		return $newPrice;
	}

	public function __sleep() {
		return array(
			'Items',
			'TotalPrice',
			'Country',
			'Currency',
			'Language',
			'SessionStart',
			'SessionID'
		);
	}
	public function __wakeup() {
		try {
			$this->connect();
		} catch (SoapFault $e) {
			if (stristr($e->getMessage(), 'Invalid hash provided')) {
				$this->SessionID = null;
			}
		}
	}
	
	public function modifyQuantity($prodId, $quantity) {
		if ($quantity > 0) {
			$this->Client->addProduct($prodId, $quantity);
		} else {
			$this->Client->deleteProduct($prodId, $quantity);
		}
		return $this->Client->getPrice($prodId, $quantity, implode(',',$this->Items[$prodId]['PRICEOPTIONS']), $this->getCurrency());
	}
	
	public function removeFromCart ($prodId) {
		$this->Client->deleteProduct($prodId);
		$this->TotalPrice -= $this->Items[$prodId]['PRICE'];
		unset ($this->Items[$prodId]);
	}
	
	public function emptyCart () {
		$this->Client->clearProducts();
		$this->Items = array();
		$this->TotalPrice = 0;
		session_destroy();
	}
	
	public function getTotalPrice () {
		return $this->TotalPrice;
	}

	public function getTotalQuantity () {
		$q = 0 ;
		foreach ($this->Items as $iProd => $aData) {
			$q += $aData['QUANTITY'];
		}
		return $q;
	}
	
	public function getItems () {
		return $this->Items;
	}
	
	public function getItemsQuantity () {
		$quantity = 0;
		foreach ($this->Items as $idProd => $prodData) {
			$quantity += $prodData['QUANTITY'];
		}
		
		return $quantity;
	}
	
	public function getItemQuantity ($idProduct) {
		return isset($this->Items[$idProduct]['QUANTITY']) ? $this->Items[$idProduct]['QUANTITY'] : null;
	}
	
	public function getItemPrice ($idProduct) {
		return isset($this->Items[$idProduct]['PRICE']) ? $this->Items[$idProduct]['PRICE'] : null;
	}
	
	public function getItemPriceOptions ($idProduct) {
		return isset($this->Items[$idProduct]['PRICEOPTIONS']) ? $this->Items[$idProduct]['PRICEOPTIONS'] : null;
	}
	
	public function getCountry () {
		return $this->Country;
	}
	
	public function getCurrency () {
		return $this->Currency;
	}

	/**
	 * Sets the global language
	 * 
	 * @param string $IsoLang
	 *
	 * @return void
	 */
	public function setLanguage ($IsoLang){
		$this->Client->setLanguage(strtolower($IsoLang));
		$this->Language = $IsoLang;
		return true;
	}

	/**
	 * Sets global country
	 * 
	 * @param string $IsoCountry
	 *
	 * @return void
	 */
	public function setCountry($IsoCountry) {
		$this->Client->setCountry (strtolower($IsoCountry));
		$this->Country = $IsoCountry;
		return true;
	}

	/**
	 * Sets the global currency
	 * 
	 * @param string $IsoCurrency
	 *
	 * @return void
	 */
	public function setCurrency($IsoCurrency) {
		$this->Client->setCurrency (strtolower($IsoCurrency));
		$this->Currency = $IsoCurrency;
		return true;
	}

	/**
	 * Sets the billing details
	 * 
	 * @param mBillingDetails $BillingDetails
	 *
	 * @return void
	 */
	public function setBillingDetails (mBillingDetails $BillingDetails) {
		return $this->Client->setBillingDetails ($BillingDetails);
	}

	/**
	 * Sets the delivery details [optional]
	 * 
	 * @param mDeliveryDetails $DeliveryDetails
	 *
	 * @return void
	 */
	public function setDeliveryDetails (mDeliveryDetails $DeliveryDetails) {
		return $this->Client->setDeliveryDetails ($DeliveryDetails);
	}

	/**
	 * Sets the payment details for the current order
	 * 
	 * @param mPaymentDetails $PaymentDetails
	 *
	 * @throws CSOAPServerFault_Orders
	 *
	 * @return void
	 */
	public function setPaymentDetails (mPaymentDetails $PaymentDetails) {
		return $this->Client->setPaymentDetails ($PaymentDetails);
	}

	/**
	 * Adds a product to the current cart session
	 * 
	 * @param integer $IdProduct
	 * @param integer $Quantity
	 * @param string $PriceOptions
	 *
	 * @return boolean
	 */
	public function addProduct ($IdProduct, $iQuantity = 1, $aPriceOptions = null) {
		return $this->Client->addProduct ($IdProduct, $iQuantity, $aPriceOptions);
	}

	/**
	 * Deletes a product or subtracts a quantity from the current cart session
	 * 
	 * @param integer $IdProduct
	 * @param integer $iQuantity
	 *
	 * @throws CSOAPServerFault_Orders
	 *
	 * @return boolean
	 */
	public function deleteProduct ($IdProduct, $iQuantity = 1) {
		return $this->Client->deleteProduct ($IdProduct, $iQuantity);
	}

	/**
	 * Empties the current cart session
	 *
	 * @throws CSOAPServerFault_Orders
	 *
	 * @return boolean
	 */
	public function clearProducts () {
		return $this->Client->clearProducts();
	}

	/**
	 * Places an order
	 *
	 * @return mOrder
	 */
	public function placeOrder () {
		return $this->Client->placeOrder();
	}

	/**
	 * Returns the order reference number
	 * 
	 * @param string $RefNo
	 *
	 * @return string
	 */
	public function getOrderStatus ($RefNo) {
		return $this->Client->getOrderStatus ($RefNo);
	}
	/**
	 * Get information about a product when its id is known
	 *
	 *
	 * @param integer $IdProduct
	 *
	 * @return mProduct
	 */
	public function getProductById($IdProduct){
		$product = $this->Client->getProductById($IdProduct);
		if (empty($product->Price)) {
			// this is a probably a flat scheme price product - so we try to get the price based on the default pricing options
			$product->Price = $this->getDefaultPrice ($product, $this->getCurrency());
		}
		return $product;
	}
	
	/**
	 * Get information about a product when its code is known
	 * 
	 * @param string $ProductCode
	 *
	 * @return mProduct
	 */
	public function getProductByCode($ProductCode) {
		return $this->Client->getProductByCode ($ProductCode);
	}
	
	/**
	 *
	 * Returns the price for a product based on it's default pricing options
	 * @param mProduct $product
	 * @return decimal
	 */
	protected function getDefaultPrice (mProduct $product, $IsoCurrency = null) {
		$defaultPriceOptions = array();
		try {
			foreach ($product->PriceOptions as $iKey => $OptionGroup) {
				foreach ($OptionGroup->Options as $iOptionKey => $Option) {
					if ($Option->Default) {
						$defaultPriceOptions[] = $Option->Value;
					}
				}
			}
			$oPrice = $this->Client->getPrice($product->ProductId, 1, implode (',', $defaultPriceOptions), $IsoCurrency);
			$product->Price = $oPrice->NetPrice;
		} catch (SoapFault $e) {
			//
			_e ($e);
		}
		return $product->Price;
	}
	
	/**
	 * Returns a list of products
	 *
	 * 
	 * @param array $SearchOptions
	 *
	 * @return mBasicProduct[]
	 */
	public function searchProducts($SearchOptions = array()) {
		$products = $this->Client->searchProducts ($SearchOptions);

		/* @var $prodData mBasicProduct */
		foreach ($products as $idProduct => $prodData) {
			if (is_null($prodData->Price)) {
				$fullProduct = $this->Client->getProductById($prodData->ProductId);
				$prodData->Price = $this->getDefaultPrice($fullProduct, $this->getCurrency());
			}
		}
		return $products;
	}
	
	/**
	 * @var int $IdProduct
	 * @var int $Quantity
	 * @var array $PriceOptions
	 * @var string $Currency
	 * @return mPrice
	 */
	public function getPrice ($IdProduct, $Quantity = 1, $PriceOptions = '', $Currency = null) {
		return $this->Client->getPrice($IdProduct, $Quantity, $PriceOptions, $Currency);
	}
	
	/**
	 * Returns the list of available currencies for the current vendor
	 * @param string $Hash
	 * @throws CSOAPServerFault_Merchants
	 * @return IsoCurrencyCodes array[string]
	 */
	public function getAvailableCurrencies () {
		return $this->Client->getAvailableCurrencies ();
	}
	
	/**
	 * Returns the list of available languages for the current vendor
	 * @param string $Hash
	 * @throws CSOAPServerFault_Merchants
	 * @return IsoLanguageCodes array[string]
	 */
	public function getAvailableLanguages () {
		return $this->Client->getAvailableLanguages ();
	}

	/**
	 * Returns the list of available countries for the current vendor
	 * @param string $Hash
	 * @throws CSOAPServerFault_Merchants
	 * @return IsoCountryCodes array[string]
	 */
	public function getAvailableCountries () {
		return $this->Client->getAvailableCountries ();
	}
	
	public function getContents() {
		return $this->Client->getContents ();
	}
}