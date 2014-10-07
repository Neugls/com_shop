<?php
// NO DIRECT ACCESS
defined( '_JEXEC' ) or die( 'Restricted access' );

class TableAddresses extends JTable
{
	/** @var int Primary Key */
	var $address_id			= null;
	/** @var string Address Line 1 */
	var $address_line1		= null;
	/** @var string Address Line 2 */
	var $address_line2		= null;
	/** @var string Address City */
	var $address_city		= null;
	/** @var string Address State */
	var $address_state		= null;
	/** @var string Address Zip */
	var $address_zip		= null;
	/** @var string Phone Number */
	var $address_phone		= null;
	/** @var string Billing or Shipping */
	var $address_type		= null;
	/** @var int Customer ID */
	var $customer_id		= null;
	/** @var int User ID */
	var $id					= null;
	
	public function TableAddresses(& $db){
		parent::__construct('#__shop_addresses', 'address_id', $db);
	}
	
	public function bind($array, $ignore=''){
		return parent::bind($array, $ignore);
	}
	
	public function check(){
		return true;
	}
	
	public function store($updateNulls = false){
		if(!parent::store($updateNulls)){
			return false;
		}
		return true;
	}
}
