<?php
class mBasicProduct  {		
	/**
	 * 
	 *
	 * @var integer
	 */
	var $ProductId = 0;
	
	/**
	 * 
	 *
	 * @var string
	 */
	var $ProductCode = '';
			
	/**
	 * 
	 *
	 * @var string
	 */
	var $ProductName = '';
	
	/**
	 * 
	 *
	 * @var string
	 */
	var $ProductVersion = '';
	
	/**
	 * 
	 *
	 * @var boolean
	 */
	var $ProductStatus = null;	
	
	/**
	 * 
	 *
	 * @var string
	 */
	var $ProductType = '';
		
	/**
	 * 
	 *
	 * @var string
	 */
	var $Currency = '';	
	
	/**
	 * 
	 *
	 * @var string
	 */
	var $DefaultCurrency = '';	
	
	/**
	 * 
	 *
	 * @var boolean
	 */
	var $GiftOption = null;	
	
	/**
	 * 
	 *
	 * @var integer
	 */
	var $IdGroup = 0;
	
	/**
	 * 
	 *
	 * @var string
	 */
	var $GroupName = '';
	
	/**
	 * 
	 *
	 * @var string
	 */
	var $ShortDescription = '';
	
	/**
	 * 
	 *
	 * @var string
	 */
	var $ProductImage = '';
	
	/**
	 * @var decimal
	 */
	var $Price = 0;
	
	/**
	 * 
	 *
	 * @var array
	 */
	var $Languages = array();
	/**
	*
	*/
	var $PriceIntervals;
	
	/**
	 *
	 */
	var $PriceType;
	
	/**
	 *
	 */
	var $PriceSchema;
}
