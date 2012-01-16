<?php
import ('assets');
/* @var parent CSOAP_OrderAPI */
class mSOAPClient extends SoapClient {
	private $AccountCode;
	private $FiscalCode;
	private $SecretKey;

	protected $sessionID;
	protected $sessionStart;
	
	public function __construct ($wsdlUrl, $options = array()) {
		$mOptions = array_merge(
			array (
				'location' => substr ($wsdlUrl, 0, -5),
// 				'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
				'cache_wsdl' => WSDL_CACHE_NONE,
				'classmap' => array (
					'CSOAP_Order' => 'mOrder',
					'CSOAP_Price' => 'mPrice',
					'CSOAP_ProductDataTypeProductsListItem' => 'mBasicProduct',
					'CSOAP_ProductDataTypeProductCompleteInfo' => 'mProduct',
					'CSOAP_ProductDataTypePriceOptionsGroupItem' => 'mPriceOptionGroup',
					'CSOAP_ProductDataTypePriceOptionsGroupItemOptions' => 'mPriceOptionOption',
				)
			), 
			$options
		);
		return parent::__construct ($wsdlUrl, $mOptions);
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
			if (!isset($_SESSION['CART_DEMO']['SESSSID']) || is_null($_SESSION['CART_DEMO']['SESSSID'])) {
				// there is no authenticated soap session saved - we need to login
				$this->sessionStart	= date('Y-m-d H:i:s');
				$string		= strlen($this->AccountCode) . $this->AccountCode . strlen($this->sessionStart) . $this->sessionStart;
				$hash		= hmac($this->SecretKey, $string);
				
				$this->sessionID	= parent::login ($this->AccountCode, $this->sessionStart, $hash);
				if ($_SERVER['REMOTE_ADDR']) {
					parent::setClientIP ($this->sessionID, $_SERVER['REMOTE_ADDR']);
				}
			} else {
				// we logged at a previous time - so we load the data in our soap wrapper
				$this->sessionStart	= $_SESSION['CART_DEMO']['SESSSTART'];
				$this->sessionID	= $_SESSION['CART_DEMO']['SESSSID'];
			}
			if (!is_null($this->sessionID)) {
				// succsessful login
				$_SESSION['CART_DEMO']['SESSSTART']	= $this->sessionStart;
				$_SESSION['CART_DEMO']['SESSSID']	= $this->sessionID;
			} 
		} catch ( SoapFault $e ) {
			// some problem authenticating 
		}
		
		if (!is_null($this->sessionID)) {
// 			parent::setFiscalCode ($this->sessionID, VATID);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Sets the global language
	 * 
	 * @param string $IsoLang
	 *
	 * @return void
	 */
	public function setLanguage ($IsoLang){
		return parent::setLanguage($this->sessionID, $IsoLang);
	}

	/**
	 * Sets global country
	 * 
	 * @param string $IsoCountry
	 *
	 * @return void
	 */
	public function setCountry($IsoCountry) {
		return parent::setCountry ($this->sessionID, $IsoCountry);
	}

	/**
	 * Sets the global currency
	 * 
	 * @param string $IsoCurrency
	 *
	 * @return void
	 */
	public function setCurrency($IsoCurrency) {
		return parent::setCurrency ($this->sessionID, $IsoCurrency);
	}

	/**
	 * Sets the billing details
	 * 
	 * @param mBillingDetails $BillingDetails
	 *
	 * @return void
	 */
	public function setBillingDetails (mBillingDetails $BillingDetails) {
		return parent::setBillingDetails ($this->sessionID, $BillingDetails);
	}

	/**
	 * Sets the delivery details [optional]
	 * 
	 * @param mDeliveryDetails $DeliveryDetails
	 *
	 * @return void
	 */
	public function setDeliveryDetails (mDeliveryDetails $DeliveryDetails) {
		return parent::setDeliveryDetails ($this->sessionID, $DeliveryDetails);
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
		return parent::setPaymentDetails ($this->sessionID, $PaymentDetails);
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
	public function addProduct ($IdProduct, $Quantity, $PriceOptions = null) {
		return parent::addProduct ($this->sessionID, $IdProduct, $Quantity, $PriceOptions);
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
	public function deleteProduct ($IdProduct, $iQuantity) {
		return parent::deleteProduct ($this->sessionID, $IdProduct, $iQuantity);
	}

	/**
	 * Empties the current cart session
	 *
	 * @throws CSOAPServerFault_Orders
	 *
	 * @return boolean
	 */
	public function clearProducts () {
		return parent::clearProducts($this->sessionID);
	}

	/**
	 * Places an order
	 *
	 * @return mOrder
	 */
	public function placeOrder () {
		return parent::placeOrder($this->sessionID);
	}

	/**
	 * Returns the order reference number
	 * 
	 * @param string $RefNo
	 *
	 * @return string
	 */
	public function getOrderStatus ($RefNo) {
		return parent::getOrderStatus ($this->sessionID, $RefNo);
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
		$product = parent::getProductById ($this->sessionID, $IdProduct);
		if (empty($product->Price)) {
			// this is a probably a flat scheme price product - so we try to get the price based on the default pricing options
			$product->Price = $this->getDefaultPrice ($product);
		}
		return $product;
	}
	
	/**
	 * 
	 * Returns the price for a product based on it's default pricing options
	 * @param mProduct $product
	 * @return decimal
	 */
	protected function getDefaultPrice (mProduct $product) {
		$defaultPriceOptions = array();
		try {
			foreach ($product->PriceOptions as $iKey => $OptionGroup) {
				foreach ($OptionGroup->Options as $iOptionKey => $Option) {
					if ($Option->Default) {
						$defaultPriceOptions[] = $Option->Value;
					}
				}
			}
			if ($product->ProductId == 4451538) d ($defaultPriceOptions);
			$oPrice = $this->getPrice($product->ProductId, 1, implode (',', $defaultPriceOptions), getCartCurrency());
			$product->Price = $oPrice->FinalPrice;
		} catch (SoapFault $e) {
			//
				_e ($e);
		}
		return $product->Price;
	} 
	
	/**
	 * Get information about a product when its code is known
	 * 
	 * @param string $ProductCode
	 *
	 * @return mProduct
	 */
	public function getProductByCode($ProductCode) {
		return parent::getProductByCode ($this->sessionID, $ProductCode);
	}
	
	
	/**
	 *
	 * 
	 * @param string $ProductSKU
	 * @return mProduct[]
	 */
	public function getProductBySKU($ProductSKU) {
		return parent::getProductBySKU ($this->sessionID, $ProductSKU);
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
		$products = parent::searchProducts($this->sessionID, $SearchOptions);
		
		/* @var $prodData mBasicProduct */
		foreach ($products as $idProduct => $prodData) {
			if (is_null($prodData->Price)) {
				$fullProduct = $this->getProductById($prodData->ProductId);
				$prodData->Price = $this->getDefaultPrice($fullProduct);
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
		return parent::getPrice($this->sessionID, $IdProduct, $Quantity, $PriceOptions, $Currency);
	}
}
