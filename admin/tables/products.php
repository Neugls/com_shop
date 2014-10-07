<?php
// NO DIRECT ACCESS
defined( '_JEXEC' ) or die( 'Restricted access' );

class TableProducts extends JTable
{
	/** @var int Primary Key */
	var $product_id				= null;
	/** @var string Product Name */
	var $product_name			= null;
	/** @var string URL alias */
	var $product_alias			= null;
	/** @var string Product SKU */
	var $product_sku			= null;
	/** @var string Product Description */
	var $product_description	= null;
	/** @var float Product Price */
	var $product_price			= null;
	/** @var string Meta Keywords */
	var $meta_keywords			= null;
	/** @var string Meta Description */
	var $meta_description		= null;
	/** @var int */
	var $ordering				= null;
	/** @var int */
	var $state					= null;
	/** @var string JSON Data */
	var $params					= null;
	/** @var datetime */
	var $publish_up				= null;
	/** @var datetime */
	var $publish_down			= null;
	/** @var int */
	var $checked_out			= null;
	/** @var datetime */
	var $checked_out_time		= null;
	/** @var datetime */
	var $modified				= null;
	/** @var int */
	var $modified_by			= null;
	/** @var datetime */
	var $created				= null;
	/** @var int */
	var $created_by				= null;
	/** @var int */
	var $access					= null;
	/** @var int Category foreign key */
	var $catid					= null;
	
	public function __construct(& $db){
		parent::__construct('#__shop_products', 'product_id', $db);
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
		// ASSIGN ORDERING IF NECESSARY
		if(is_null($this->ordering)){
			$this->ordering = $this->getNextOrder();
		}
		return true;
	}
	
	public function store($updateNulls = false){
		if(!parent::store($updateNulls)){
			return false;
		}
		$this->reorder();
		return true;
	}
}
