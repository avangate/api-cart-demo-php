<?php
class mOrder {
	/**
	 * Reference number of the order
	 * @var string
	 */
	public $RefNo;
	/**
	 * Status of the order
	 * @var string [COMPLETE, AUTHRECEIVED, PENDING, PENDINGCASH, CANCELED, REVERSED, TEST, REFUND, CASHED, DELIVERED, FINISHED]
	 */
	public $Status;
	
	/**
	 * Auto-renewal status of the order 
	 * @var bool
	 */
	public $AutoRenewal;
	public $Error;
}
