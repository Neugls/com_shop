<?php
// NO DIRECT ACCESS
defined( '_JEXEC' ) or die( 'Restricted access' );

class TableOptions extends JTable
{
	/** @var int Primary Key */
	var $option_id			= null;
	/** @var string Option Key */
	var $option_key			= null;
	/** @var string Option Value */
	var $option_value		= null;
	/** @var float Option Price */
	var $option_price		= null;
	/** @var float Option Price */
	var $option_weight		= null;
	/** @var string Unique Product Identifier */
	var $option_sku			= null;
	/** @var int Product ID */
	var $product_id			= null;
	
	public function TableOptions(& $db){
		parent::__construct('#__shop_options', 'option_id', $db);
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
