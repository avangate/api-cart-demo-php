<?php

class mPriceOptionGroup {
	/**
	 * @var string
	 */
	var $Name = '';
	
	/**
	 * @var string
	 */
	var $Description = '';
	
	/**
	 * @var boolean
	 */
	var $Required = false;
	
	/**
	 * @var string
	 */
	var $Type;
	
	/**
	 * @var mPriceOptionGroupItem[]
	 */
	var $Options = array();
}