<?php
import ('assets');
/* @param parent CSOAP_OrderAPI */
class mSOAPClient extends SoapClient implements mAPIInterface {
	static $calls;
	
	private $AccountCode;
	private $FiscalCode;
	private $SecretKey;

	private $sessionID;
	protected $sessionStart;
	
	private $aRequests = array();
	private $aResponses = array();
	
	public function __construct ($wsdlUrl, $options = array()) {
		self::$calls = 0;
		if (USE_PROXY) {
			$options = array_merge (
				array (
					'proxy_host' => 'proxy.avangate.local',
					'proxy_port' => 8080,
					'proxy_login' => 'marius.orcsik',
				),
				$options
			);
		}
		$mOptions = array_merge(
			array (
				'location' => ORDER_SOAP_URL,
				'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
				'cache_wsdl' => WSDL_CACHE_NONE,
				'classmap' => array (
					'Order' => 'mOrder',
					'Price' => 'mPrice',
					'CartItem' => 'mCartItem',
					'SimpleProduct' => 'mBasicProduct',
					'ProductInfo' => 'mProduct',
					'PriceOptionsGroupItem' => 'mPriceOptionGroup',
					'PriceOptionsGroupItemOptions' => 'mPriceOptionOption',
				)
			), 
			$options
		);

		return parent::__construct ($wsdlUrl, $mOptions);
	}
	
	public function getCalls () {
		return self::$calls;
	}
	
	public function getAPIRequests () {
		return $this->aRequests;
	}

	public function getAPIRequest ($id) {
		return (array_key_exists($id, $this->aRequests) ? $this->aRequests[$id] : null);
	}

	public function getAPIResponses () {
		return $this->aResponses;
	}

	public function getAPIResponse ($id) {
		return (array_key_exists($id, $this->aResponses) ? $this->aResponses[$id] : null);
	}
	
	public function setSessionId ($SessionId) {
		$this->sessionID = $SessionId;
	}

	public function setSessionStart ($SessionStart) {
		$this->sessionStart = $SessionStart;
	}
	
	public function setAccountCode ($s) {
		$this->AccountCode = $s;
	}
	public function setFiscalCode ($code) {
		$this->FiscalCode = $code;
	}
	public function setSecretKey ($s) {
		$this->SecretKey = $s;
	}
	
	public function authenticate () {
		try {
			// there is no authenticated soap session saved - we need to login
			$this->sessionStart	= date('Y-m-d H:i:s');
			$string		= strlen($this->AccountCode) . $this->AccountCode . strlen($this->sessionStart) . $this->sessionStart;
			$hash		= hash_hmac('md5', $string, $this->SecretKey);

			self::$calls += 1;
			$this->sessionID	= parent::login ($this->AccountCode, $this->sessionStart, $hash);
			if ($_SERVER['REMOTE_ADDR']) {
				self::$calls += 1;
				parent::setClientIP ($this->sessionID,  $_SERVER['REMOTE_ADDR']);
			}
		} catch ( SoapFault $e ) {
			// some problem authenticating 
			_e($e);
		}
		
		if (!is_null($this->sessionID)) {
			return $this->sessionID;
		} else {
			return null;
		}
	}

	/**
	 * Sets the global language
	 * @param string $IsoLang
	 * @return void
	 */
	public function setLanguage ($IsoLang){
		self::$calls += 1;
		return parent::setLanguage($this->sessionID, $IsoLang);
	}

	/**
	 * Sets global country
	 * @param string $IsoCountry
	 * @return void
	 */
	public function setCountry($IsoCountry) {
		self::$calls += 1;
		return parent::setCountry ($this->sessionID, $IsoCountry);
	}

	/**
	 * Sets the global currency
	 * @param string $IsoCurrency
	 * @return void
	 */
	public function setCurrency($IsoCurrency) {
		self::$calls += 1;
		return parent::setCurrency ($this->sessionID, strtoupper($IsoCurrency));
	}

	/**
	 * Sets the billing details
	 * @param mBillingDetails $BillingDetails
	 * @return void
	 */
	public function setBillingDetails (mBillingDetails $BillingDetails) {
		self::$calls += 1;
		return parent::setBillingDetails ($this->sessionID, $BillingDetails);
	}

	/**
	 * Sets the delivery details [optional]
	 * @param mDeliveryDetails $DeliveryDetails
	 * @return void
	 */
	public function setDeliveryDetails (mDeliveryDetails $DeliveryDetails) {
		self::$calls += 1;
		return parent::setDeliveryDetails ($this->sessionID, $DeliveryDetails);
	}

	/**
	 * Sets the payment details for the current order
	 * @param mPaymentDetails $PaymentDetails
	 * @throws CSOAPServerFault_Orders
	 * @return void
	 */
	public function setPaymentDetails (mPaymentDetails $PaymentDetails) {
		self::$calls += 1;
		return parent::setPaymentDetails ($this->sessionID, $PaymentDetails);
	}

	/**
	 * Adds a product to the current cart session
	 * @param integer $IdProduct
	 * @param int $iQuantity
	 * @param array $aPriceOptions
	 * @internal param int $Quantity
	 * @internal param string $PriceOptions
	 * @return boolean
	 */
	public function addProduct ($IdProduct, $iQuantity = 1, $aPriceOptions = null) {
		self::$calls += 1;
		return parent::addProduct ($this->sessionID, $IdProduct, $iQuantity, $aPriceOptions);
	}

	/**
	 * Deletes a product or subtracts a quantity from the current cart session
	 * 
	 * @param integer $IdProduct
	 * @param integer $iQuantity
	 * @throws CSOAPServerFault_Orders
	 * @return boolean
	 */
	public function deleteProduct ($IdProduct, $iQuantity = 1) {
		self::$calls += 1;
		return parent::deleteProduct ($this->sessionID, $IdProduct, $iQuantity);
	}

	/**
	 * Empties the current cart session
	 * @throws CSOAPServerFault_Orders
	 * @return boolean
	 */
	public function clearProducts () {
		self::$calls += 1;
		return parent::clearProducts($this->sessionID);
	}

	/**
	 * Places an order
	 * @return mOrder
	 */
	public function placeOrder () {
		self::$calls += 1;
		return parent::placeOrder($this->sessionID);
	}

	/**
	 * Returns the order reference number
	 * @param string $RefNo
	 * @return string
	 */
	public function getOrderStatus ($RefNo) {
		self::$calls += 1;
		return parent::getOrderStatus ($this->sessionID, $RefNo);
	}
	
	/**
	 * Get information about a product when its id is known
	 * @param integer $IdProduct
	 * @return mProduct
	 */
	public function getProductById($IdProduct){
		self::$calls += 1;
		$product = parent::getProductById ($this->sessionID, $IdProduct);
		$product->Id = $product->ProductId;

		return $product;
	}
	
	/**
	 * Get information about a product when its code is known
	 * @param string $ProductCode
	 * @return mProduct
	 */
	public function getProductByCode($ProductCode) {
		self::$calls += 1;
		$product = parent::getProductByCode ($this->sessionID, $ProductCode);
		$product->Id = $product->ProductId;
		return $product;
	}
	
	
	/**
	 * @param string $ProductSKU
	 * @return mProduct[]
	 */
	public function getProductBySKU($ProductSKU) {
		self::$calls += 1;
		$product = parent::getProductBySKU ($this->sessionID, $ProductSKU);
		$product->Id = $product->ProductId;
		return $product;
	}
	
	/**
	 * Returns a list of products
	 * @param array $SearchOptions
	 * @return mBasicProduct[]
	 */
	public function searchProducts($SearchOptions = array()) {
		self::$calls += 1;
		if (!array_key_exists('PageSize', $SearchOptions)) {
			$SearchOptions['PageSize'] = 5;
		}
		$retProducts = array();
		foreach(parent::searchProducts($this->sessionID, $SearchOptions) as $product) {
			$product->Id = $product->ProductId;
			$retProducts[] = $product;
		}
		return $retProducts;
	}

	/**
	 * @param int $IdProduct
	 * @param int $Quantity
	 * @param array $PriceOptions
	 * @param string $Currency
	 * @return mPrice
	 */
	public function getPrice ($IdProduct, $Quantity = 1, $PriceOptions = array(), $Currency = null) {
		self::$calls += 1;
		return parent::getPrice($this->sessionID, $IdProduct, $Quantity, $PriceOptions, $Currency);
	}
	
	/**
	 * Returns the list of available currencies for the current vendor
	 * @throws CSOAPServerFault_Merchants
	 * @return IsoCurrencyCodes array[string]
	 */
	public function getAvailableCurrencies () {
		self::$calls += 1;
		return parent::getAvailableCurrencies ($this->sessionID);
	}
	
	/**
	 * Returns the list of available languages for the current vendor
	 * @throws CSOAPServerFault_Merchants
	 * @return IsoLanguageCodes array[string]
	 */
	public function getAvailableLanguages () {
		self::$calls += 1;
		return parent::getAvailableLanguages ($this->sessionID);
	}
	/**
	 * Returns the list of available countries for the current vendor
	 * @throws CSOAPServerFault_Merchants
	 * @return IsoCountryCodes array[string]
	 */
	public function getAvailableCountries () {
		self::$calls += 1;
		return parent::getAvailableCountries ($this->sessionID);
	}

	/**
	 * @return mContents
	 */
	public function getContents () {
		self::$calls += 1;
		return parent::getContents ($this->sessionID);
	}
}
