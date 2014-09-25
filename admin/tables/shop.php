<?php
// NO DIRECT ACCESS
defined( '_JEXEC' ) or die( 'Restricted access' );

class TableShop extends JTable
{
	/** @var int Primary Key */
	var $shop_id			= null;
	/** @var string shop Name */
	var $shop_name		= null;
	/** @var string URL alias */
	var $shop_alias		= null;
	/** @var string shop Description */
	var $shop_description= null;
	/** @var string Parameters */
	var $attribs			= null;
	/** @var string Meta Description */
	var $meta_description	= null;
	/** @var string Meta Keywords */
	var $meta_keywords		= null;
	/** @var int */
	var $ordering			= null;
	/** @var int */
	var $published			= null;
	/** @var int */
	var $checked_out		= null;
	/** @var time */
	var $checked_out_time	= null;
	/** @var time */
	var $modified			= null;
	/** @var int */
	var $modified_by		= null;
	/** @var int */
	var $access				= null;
	
	public function TableShop(& $db){
		parent::__construct('#__shop', 'shop_id', $db);
	}
	
	public function bind($array, $ignore=''){
		if(key_exists('params', $array)){
			if(is_array($array['params'])){
				$registry = new JRegistry();
				$registry->loadArray($array['params']);
				$array['attribs'] = $registry->toString();
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
