<?php

class mPayPalPayment extends mAutoRenewalPayment {
	/**
	 * The email used to log in to PayPal
	 * @var string
	 */
	public $Email;
	
	/**
	 * The currency sent to PayPal
	 * @var string
	 */
	public $Currency;

}