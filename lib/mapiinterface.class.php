<?php
interface mAPIInterface {
	public function authenticate ();

	/**
	 * Sets the global language
	 * 
	 * @param string $IsoLang
	 *
	 * @return void
	 */
	public function setLanguage ($IsoLang);

	/**
	 * Sets global country
	 * 
	 * @param string $IsoCountry
	 *
	 * @return void
	 */
	public function setCountry($IsoCountry);

	/**
	 * Sets the global currency
	 * 
	 * @param string $IsoCurrency
	 *
	 * @return void
	 */
	public function setCurrency($IsoCurrency);
	/**
	 * Sets the billing details
	 * 
	 * @param mBillingDetails $BillingDetails
	 *
	 * @return void
	 */
	public function setBillingDetails (mBillingDetails $BillingDetails);

	/**
	 * Sets the delivery details [optional]
	 * 
	 * @param mDeliveryDetails $DeliveryDetails
	 *
	 * @return void
	 */
	public function setDeliveryDetails (mDeliveryDetails $DeliveryDetails);

	/**
	 * Sets the payment details for the current order
	 * 
	 * @param mPaymentDetails $PaymentDetails
	 *
	 * @throws CSOAPServerFault_Orders
	 *
	 * @return void
	 */
	public function setPaymentDetails (mPaymentDetails $PaymentDetails);

	/**
	 * Adds a product to the current cart session
	 * 
	 * @param integer $IdProduct
	 * @param integer $Quantity
	 * @param string $PriceOptions
	 *
	 * @return boolean
	 */
	public function addProduct ($IdProduct, $iQuantity = 1, $aPriceOptions = null);

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
	public function deleteProduct ($IdProduct, $iQuantity = 1);

	/**
	 * Empties the current cart session
	 *
	 * @throws CSOAPServerFault_Orders
	 *
	 * @return boolean
	 */
	public function clearProducts ();

	/**
	 * Places an order
	 *
	 * @return mOrder
	 */
	public function placeOrder ();

	/**
	 * Returns the order reference number
	 * 
	 * @param string $RefNo
	 *
	 * @return string
	 */
	public function getOrderStatus ($RefNo);
	
	/**
	 * Get information about a product when its id is known
	 *
	 * 
	 * @param integer $IdProduct
	 *
	 * @return mProduct
	 */
	public function getProductById($IdProduct);
	
	/**
	 * Get information about a product when its code is known
	 * 
	 * @param string $ProductCode
	 *
	 * @return mProduct
	 */
	public function getProductByCode($ProductCode);
	
	
	/**
	 * @param string $ProductSKU
	 * @return mProduct[]
	 */
	public function getProductBySKU($ProductSKU);
	
	/**
	 * Returns a list of products
	 *
	 * 
	 * @param array $SearchOptions
	 *
	 * @return mBasicProduct[]
	 */
	public function searchProducts($SearchOptions = array());
	
	/**
	 * @var int $IdProduct
	 * @var int $Quantity
	 * @var array $PriceOptions
	 * @var string $Currency
	 * @return mPrice
	 */
	public function getPrice ($IdProduct, $Quantity = 1, $PriceOptions = '', $Currency = null);
	
	/**
	 * Returns the list of available currencies for the current vendor
	 * @param string $Hash
	 * @throws CSOAPServerFault_Merchants
	 * @return IsoCurrencyCodes array[string]
	 */
	public function getAvailableCurrencies ();
	
	/**
	 * Returns the list of available languages for the current vendor
	 * @param string $Hash
	 * @throws CSOAPServerFault_Merchants
	 * @return IsoLanguageCodes array[string]
	 */
	public function getAvailableLanguages ();
	/**
	 * Returns the list of available countries for the current vendor
	 * @param string $Hash
	 * @throws CSOAPServerFault_Merchants
	 * @return IsoCountryCodes array[string]
	 */
	public function getAvailableCountries ();
	
	/**
	 * @return mContents
	 */
	public function getContents ();
}