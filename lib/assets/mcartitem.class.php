<?php
class mCartItem {
	/**
	 * @var integer
	 */
	public $IdProduct;
	
	/**
	 * @var CSOAP_ProductDataTypeProductsListItem
	 */
	public $Product;
	
	/**
	 * @var integer
	 */
	public $Quantity;
	
	/**
	 * @var string
	 */
	public $PriceOptions;
	
	/**
	 * @var string
	 */
//	public $Currency; // ?
	
	/**
	 * @var mPrice
	 */
	public $Price; // ?
}