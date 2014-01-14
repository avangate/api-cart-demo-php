<?php
class mProduct extends mBasicProduct {
	/**
	 * @var string
	 */
	var $LongDescription = '';
	
	/**
	 * @var string
	 */
	var $SystemRequirements = '';
	
	/**
	 * @var array
	 */
	var $Platforms = array();
	
	/**
	 * @var string
	 */
	var $URLImage = '';
	
	/**
	 * @var string
	 */
	var $TrialURL = '';
	
	/**
	 * @var string
	 */
	var $TrialDescription = '';	
	
	/**
	 * @var CSOAP_ProductDataTypePriceOptionsGroupItem[]
	 */
	var $PriceOptions = array();	
	
	/**
	 * @var array
	 */
	var $AdditionalInfo = array();	
}