<?php
// NO DIRECT ACCESS
defined( '_JEXEC' ) or die( 'Restricted access' );

class TableCustomers extends JTable
{
	/** @var int Primary Key */
	var $customer_id		= null;
	/** @var string First Name */
	var $customer_first		= null;
	/** @var string Last Name */
	var $customer_last		= null;
	/** @var string Session ID */
	var $customer_session	= null;
	/** @var int */
	var $checked_out		= null;
	/** @var time */
	var $checked_out_time	= null;
	/** @var datetime */
	var $created				= null;
	/** @var int */
	var $created_by				= null;
	/** @var string JSON data */
	var $params				= null;
	/** @var int User ID */
	var $id					= null;
	
	public function TableCustomers(& $db){
		parent::__construct('#__shop_customers', 'customer_id', $db);
	}
	
	public function bind($array, $ignore=''){
		if(key_exists('params', $array)){
			if(is_array($array['params'])){
				$registry = new JRegistry();
				$registry->loadArray($array['params']);
				$array['params'] = $registry->toString();
			}
		}
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
