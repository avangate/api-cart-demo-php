<?php
class mPrice {
	/**
	 * Net price
	 * @var float
	 */
	var $NetPrice = 0;

	/**
	 * Currency requested by vendor
	 * @var string
	 */
	var $NetCurrency = '';

	/**
	 * Gross price calculated
	 * @var float
	 */
	var $FinalPrice = 0;

	/**
	 * The final currency (order currency)
	 * @var string
	 */
	var $FinalCurrency = '';
}