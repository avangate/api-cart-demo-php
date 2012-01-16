<?php
/**
 * 
 * Payment made by card
 * @author Marius Orcsik <marius.orcsik@avangate.com>
 *
 */
class mCardPayment extends mPaymentMethod {
	/**
	 * @var string
	 */
	public $CardNumber;
	
	/**
	 * Card type
	 * @var string [VISA, MASTERCARD, MAESTRO] 
	 */
	public $CardType;
	
	/**
	 * Expiration year on the card
	 * @var string
	 */
	public $ExpirationYear;
	
	/**
	 * Expiration month on the card
	 * @var string
	 */
	public $ExpirationMonth;
	
	/**
	 * 
	 * CVV2/CVC2 code on the card
	 * @var string
	 */
	public $CCID;
	
	/**
	 * Card holder name
	 * @var string
	 */
	public $HolderName;
}