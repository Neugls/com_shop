<?php
// NO DIRECT ACCESS
defined( '_JEXEC' ) or die( 'Restricted access' );

class TableShop extends JTable
{
	/** @var int Primary Key */
	var $image_id			= null;
	/** @var string Path To File */
	var $image_source		= null;
	/** @var string Path To File */
	var $image_full			= null;
	/** @var string Path To File */
	var $image_thumbnail	= null;
	/** @var string Alternate Text */
	var $image_alt			= null;
	/** @var string Title */
	var $image_title		= null;
	/** @var int Ordering */
	var $ordering			= null;
	/** @var string Product Key */
	var $product_id			= null;
	
	public function TableImages(& $db){
		parent::__construct('#__shop_images', 'image_id', $db);
	}
	
	public function bind($array, $ignore=''){
		return parent::bind($array, $ignore);
	}
	
	public function check(){
		// ASSIGN ORDERING IF NECESSARY
		if(is_null($this->ordering)){
			$this->ordering = $this->getNextOrder("product_id = ".$this->product_id);
		}
		return true;
	}
	
	public function store($updateNulls = false){
		if(!parent::store($updateNulls)){
			return false;
		}
		$this->reorder("product_id = ".$this->product_id);
		return true;
	}
}
