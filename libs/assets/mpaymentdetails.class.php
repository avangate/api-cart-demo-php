<?php
/**
 * 
 * Details for a payment
 * @author Marius Orcsi <marius.orcsik@avangate.com>
 *
 */
class mPaymentDetails {
	/**
	 * String identifier for the payment method
	 * @var string
	 */
	public $Type;
	
	/**
	 * ISO currency string
	 * @var string
	 */
	public $Currency;
	
	/**
	 * The method type of the current order
	 * @var CSOAP_PaymentMethod
	 */
	public $PaymentMethod;
	
	/**
	 * @var string
	 */
	public $CustomerIP;
}