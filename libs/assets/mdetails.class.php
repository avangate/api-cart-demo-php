<?php
/**
 * Purchase details for an order
 * @author Marius Orcsik <marius.orcsik@avangate.com>
 *
 */
abstract class mDetails {
	
	/**
	 * @var string
	 */
	public $FirstName;
	
	/**
	 * @var string
	 */
	public $LastName;
	
	/**
	 * @var string
	 */
	public $Company;
	
	/**
	 * @var string
	 */
	public $Email;
	
	/**
	 * @var string
	 */
	public $Address;
	
	/**
	 * @var string
	 */
	public $City;
	
	/**
	 * @var string
	 */
	public $PostalCode;
	
	/**
	 * ISO country code
	 * @var string
	 */
	public $Country;
	
	/**
	 * @var string
	 */
	public $State;
}