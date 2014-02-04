<?php
class mJsonRPCClient implements mAPIInterface {
	static $calls = 0;
	static $url = ORDER_RPC_URL;
	
	private $AccountCode;
	private $FiscalCode;
	private $SecretKey;
	
	private $aRequests = array();
	private $aResponses = array();

	private $sessionID;
	protected $sessionStart;
	
	private $typeConversion = array();
	
	private $curl;
	
	public function __construct () {
		$this->connect ();
		$this->typeConversion = array (
			'CSOAP_Order' => 'mOrder',
			'CSOAP_Price' => 'mPrice',
			'CSOAP_CartItem' => 'mCartItem',
			'CSOAP_ProductDataTypeProductsListItem' => 'mBasicProduct',
			'CSOAP_ProductDataTypeProductCompleteInfo' => 'mProduct',
			'CSOAP_ProductDataTypePriceOptionsGroupItem' => 'mPriceOptionGroup',
			'CSOAP_ProductDataTypePriceOptionsGroupItemOptions' => 'mPriceOptionOption',
		);
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
	
	public function __destruct () {
		$this->disconnect ();
	}
	
	public function __wakeup() {
		$this->connRect();
	}
	
	private function disconnect () {
		curl_close($this->curl);
	}
	
	public function getCalls () {
		return self::$calls;
	}
	
	private function connect () {
		$this->curl = curl_init(self::$url);
		curl_setopt( $this->curl, CURLOPT_POST, 1);
		curl_setopt( $this->curl, CURLOPT_RETURNTRANSFER, 1);
// 		curl_setopt( $this->curl, CURLOPT_HEADER, 1);
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

	public function setTypeConversion ($a) {
		$this->typeConversion = $a;
	}
	
	static private function getRequestObject ($sMethodName, $aParams) {
		$oRequest = new mJsonRPCRequest();
		$oRequest->method = $sMethodName;
		$oRequest->params = $aParams;
		$oRequest->id = self::$calls;
		
		return $oRequest;
	}
	
	public function authenticate () {
		// there is no authenticated soap session saved - we need to login
		$this->sessionStart	= date('Y-m-d H:i:s');
		$string		= strlen($this->AccountCode) . $this->AccountCode . strlen($this->sessionStart) . $this->sessionStart;
		$hash		= hash_hmac('md5', $string, $this->SecretKey);

		$this->sessionID	= $this->login ($this->AccountCode, $this->sessionStart, $hash);
		if ($_SERVER['REMOTE_ADDR']) {
			$this->setClientIP ($_SERVER['REMOTE_ADDR']);
		}
		if (!is_null($this->sessionID)) {
			return $this->sessionID;
		} else {
			return null;
		}
	}
	
	private function callRPC (mJsonRPCRequest $oRequest, $bDebug = false) {
		curl_setopt( $this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
		$sRequest = mJsonRPCRequest::encode($oRequest);
		curl_setopt( $this->curl, CURLOPT_POSTFIELDS, $sRequest);
		
		$this->aRequests[$oRequest->id] = $oRequest; 
		
		$sResponse = curl_exec ($this->curl);
		if (!empty($sResponse)) {
			$oResponse = mJsonRPCResponse::decode ($sResponse);
			self::$calls++;

			if (!is_null($oResponse->error)) {
				throw new ErrorException($oResponse->error);
			}
			
			if ($bDebug) {
				d ( $oResponse );
			}
			$this->aResponses[$oRequest->id] = $oResponse; 
			return $oResponse->result;
		}
		//throw new ErrorException ('Empty response for ['.$oRequest->method.'] method');
	}
	
	public function login ($sAccountCode, $sDateStart, $sSecretHash) {
		return $this->callRPC(self::getRequestObject(__FUNCTION__, array($sAccountCode, $sDateStart, $sSecretHash)));
	}

	/**
	 * Sets the client IP
	 * @param string $IP
	 * @return void
	 */
	public function setClientIP ($IP) {
		$params = array($this->sessionID, $IP);
		return $this->callRPC(self::getRequestObject(__FUNCTION__, $params));
	}
	
	/**
	 * Sets the global language
	 * @param string $IsoLang
	 * @return void
	 */
	public function setLanguage ($IsoLang) {
		$params = array($this->sessionID, $IsoLang);
		return $this->callRPC(self::getRequestObject(__FUNCTION__, $params));
	}

	/**
	 * Sets global country
	 * @param string $IsoCountry
	 * @return void
	 */
	public function setCountry($IsoCountry) {
		$params = array($this->sessionID, $IsoCountry);
		return $this->callRPC(self::getRequestObject(__FUNCTION__, $params));
	}

	/**
	 * Sets the global currency
	 * @param string $IsoCurrency
	 * @return void
	 */
	public function setCurrency($IsoCurrency) {
		$params = array($this->sessionID, $IsoCurrency);
		return $this->callRPC(self::getRequestObject(__FUNCTION__, $params));
	}
	/**
	 * Sets the billing details
	 * @param mBillingDetails $BillingDetails
	 * @return void
	 */
	public function setBillingDetails (mBillingDetails $BillingDetails) {
		$params = array($this->sessionID, $BillingDetails);
		return $this->callRPC(self::getRequestObject(__FUNCTION__, $params));
	}

	/**
	 * Sets the delivery details [optional]
	 * @param mDeliveryDetails $DeliveryDetails
	 * @return void
	 */
	public function setDeliveryDetails (mDeliveryDetails $DeliveryDetails) {
		$params = array($this->sessionID, $DeliveryDetails);
		return $this->callRPC(self::getRequestObject(__FUNCTION__, $params));
	}

	/**
	 * Sets the payment details for the current order
	 * @param mPaymentDetails $PaymentDetails
	 * @throws CSOAPServerFault_Orders
	 * @return void
	 */
	public function setPaymentDetails (mPaymentDetails $PaymentDetails) {
		$params = array($this->sessionID, $PaymentDetails);
		return $this->callRPC(self::getRequestObject(__FUNCTION__, $params));
	}

	/**
	 * Adds a product to the current cart session
	 * @param integer $IdProduct
	 * @param int $iQuantity
	 * @param string $aPriceOptions
	 * @return boolean
	 */
	public function addProduct ($IdProduct, $iQuantity = 1, $aPriceOptions = null) {
		$params = array($this->sessionID, $IdProduct, $iQuantity, $aPriceOptions);
		return $this->callRPC(self::getRequestObject(__FUNCTION__, $params));
	}

	/**
	 * Deletes a product or subtracts a quantity from the current cart session
	 * @param integer $IdProduct
	 * @param integer $iQuantity
	 * @throws CSOAPServerFault_Orders
	 * @return boolean
	 */
	public function deleteProduct ($IdProduct, $iQuantity = 1) {
		$params = array($this->sessionID, $IdProduct, $iQuantity);
		return $this->callRPC(self::getRequestObject(__FUNCTION__, $params));
	}

	/**
	 * Empties the current cart session
	 * @throws CSOAPServerFault_Orders
	 * @return boolean
	 */
	public function clearProducts () {
		$params = array($this->sessionID);
		return $this->callRPC(self::getRequestObject(__FUNCTION__, $params));
	}

	/**
	 * Places an order
	 * @return mOrder
	 */
	public function placeOrder () {
		$params = array($this->sessionID);
		return $this->callRPC(self::getRequestObject(__FUNCTION__, $params));
	}

	/**
	 * Returns the order reference number
	 * @param string $RefNo
	 * @return mOrder
	 */
	public function getOrder ($RefNo) {
		$params = array($this->sessionID, $RefNo);
		return $this->callRPC(self::getRequestObject(__FUNCTION__, $params));
	}
	
	/**
	 * Returns the order reference number
	 * @param string $RefNo
	 * @return string
	 */
	public function getOrderStatus ($RefNo) {
		$params = array($this->sessionID, $RefNo);
		return $this->callRPC(self::getRequestObject(__FUNCTION__, $params));
	}
	
	/**
	 * Get information about a product when its id is known
	 * @param integer $IdProduct
	 * @return mProduct
	 */
	public function getProductById($IdProduct) {
		$params = array($this->sessionID, $IdProduct);
		return $this->callRPC(self::getRequestObject(__FUNCTION__, $params));
	}
	
	/**
	 * Get information about a product when its code is known
	 * @param string $ProductCode
	 * @return mProduct
	 */
	public function getProductByCode($ProductCode) {
		$params = array($this->sessionID, $ProductCode);
		return $this->callRPC(self::getRequestObject(__FUNCTION__, $params));
	}
	
	
	/**
	 * @param string $ProductSKU
	 * @return mProduct[]
	 */
	public function getProductBySKU($ProductSKU) {
		$params = array($this->sessionID, $ProductSKU);
		return $this->callRPC(self::getRequestObject(__FUNCTION__, $params));
	}
	
	/**
	 * Returns a list of products
	 * @param array $SearchOptions
	 * @return mBasicProduct[]
	 */
	public function searchProducts($SearchOptions = array()) {
		$params = array($this->sessionID, $SearchOptions);
		return $this->callRPC(self::getRequestObject(__FUNCTION__, $params));
	}
	
	/**
	 * @var int $IdProduct
	 * @var int $Quantity
	 * @var array $PriceOptions
	 * @var string $Currency
	 * @return mPrice
	 */
	public function getPrice ($IdProduct, $Quantity = 1, $PriceOptions = array(), $Currency = null) {
		$params = array($this->sessionID, $IdProduct, $Quantity, $PriceOptions, $Currency);
		return $this->callRPC(self::getRequestObject(__FUNCTION__, $params));
	}
	
	/**
	 * Returns the list of available currencies for the current vendor
	 * @throws CSOAPServerFault_Merchants
	 * @return IsoCurrencyCodes array[string]
	 */
	public function getAvailableCurrencies () {
		$params = array($this->sessionID);
		return $this->callRPC(self::getRequestObject(__FUNCTION__, $params));
	}
	
	/**
	 * Returns the list of available languages for the current vendor
	 * @throws CSOAPServerFault_Merchants
	 * @return IsoLanguageCodes array[string]
	 */
	public function getAvailableLanguages () {
		$params = array($this->sessionID);
		return $this->callRPC(self::getRequestObject(__FUNCTION__, $params));
	}
	/**
	 * Returns the list of available countries for the current vendor
	 * @throws CSOAPServerFault_Merchants
	 * @return IsoCountryCodes array[string]
	 */
	public function getAvailableCountries () {
		$params = array($this->sessionID);
		return $this->callRPC(self::getRequestObject(__FUNCTION__, $params));
	}
	
	/**
	 * @return mContents
	 */
	public function getContents () {
		$params = array($this->sessionID);
		return $this->callRPC(self::getRequestObject(__FUNCTION__, $params));
	}
}