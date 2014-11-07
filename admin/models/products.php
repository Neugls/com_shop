<?php
/**
 * Shop Products Admin Model
 * 
 * @package		Shop
 * @subpackage	Components
 *
 * @copyright	Copyright (C) 2007 - 2014 Subtext Productions. All rights reserved.
 * @license		GNU/GPL
 */

// CHECK TO ENSURE THIS FILE IS INCLUDED IN JOOMLA!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.modeladmin' );

class ShopModelProducts extends JModelAdmin
{
    /**
     * Database records data
     *
     * @var mixed This may be an object or array depending on context.
     */
    var $_data			= null;

    /**
     * Total number of records retrieved
     *
     * @var integer
     */
     var $_total		= null;

    /**
     * Pagination object
     *
     * @var object
     */
     var $_pagination	= null;

	/**
	 * Constructor
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   1.0
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->populateState();
	}
	
    /**
     * Retrieves the Item data
     *
     * @return	object	A stdClass object containing the data for a single record.
     *
     * @since 1.0
     */
    public function getData()
    {
		$id 	= $this->_getCid();
		$row 	= $this->getTable();

		$row->load($id);
		$this->_data = $row;

        return $this->_data;
    }

	/**
	 * Method for getting the form from the model.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   1.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		if($form = $this->loadForm('com_shop.products', 'products', array('control'=>'jform', 'load_data'=>$loadData))){
			return $form;
		}
		JError::raiseError(0, JText::sprintf('JLIB_FORM_INVALID_FORM_OBJECT', 'products'));
		return null;
	}

	/**
	 * Method for getting the form from the model.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   1.0
	 */
	public function getOptionsForm($data = array(), $loadData = true)
	{
		if($form = $this->loadForm('com_shop.options', 'options', array('control'=>'productoptions', 'load_data'=>$loadData))){
			return $form;
		}
		JError::raiseError(0, JText::sprintf('JLIB_FORM_INVALID_FORM_OBJECT', 'options'));
		return null;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  array    Load default data based on cid.
	 *
	 * @since   1.0
	 */
	protected function loadFormData()
	{
		$db		= $this->getDbo();
		$query 	= $db->getQuery(true);
		$row 	= $this->getTable();
		$id 	= $this->_getCid();
		
		$query->select("*");
		$query->from($row->getTableName());
		$query->where("{$row->getKeyName()} = {$id}");
		
		$db->setQuery($query);
		$this->_data = $db->loadAssoc();
		$ini = new JRegistry();
		$ini->loadString($this->_data['params']);
		$this->_data['params'] = $ini->toArray();

		return $this->_data;
	}
    /**
     * Method to retrieve the item data list
     *
     * @return	array	Array of objects containing the data from the database.
     *
     * @since	1.0
     */
    public function getList()
    {
    	$mainframe	= JFactory::getApplication();
		$option		= $mainframe->input->get('option', 'com_shop');
    	$scope		= $this->getName();
    	$row		= $this->getTable();
    	$sql		= $this->_db->getQuery(true);
    	$filter		= array();
    	if($search = addslashes($mainframe->getUserState($option.'.'.$scope.'.filter_search'))){
    		$filter[] = "`product_name` LIKE '%{$search}%'";
    	}
    	if(!$ordering = $mainframe->getUserState($option.'.'.$scope.'.filter_order')){
    		$ordering = "`ordering`";
    	}
    	if(!$order_dir = $mainframe->getUserState($option.'.'.$scope.'.filter_order_Dir')){
    		$order_dir = "ASC";
    	}
    	$sql->select("SQL_CALC_FOUND_ROWS p.*");
    	$sql->select("u.`name` AS `editor`");
    	$sql->from("`{$row->getTableName()}` p");
    	$sql->join("left", "`#__users` u ON p.`checked_out` = u.`id`");
		foreach($filter as $condition){
			$sql->where($condition);
		}
		$sql->order("{$ordering} {$order_dir}");
		$this->_data = $this->_getList($sql, $this->getState('limitstart'), $this->getState('limit'));

    	return $this->_data;
    }
    
    /**
     * Method to retrieve image data for a selected product
     *
     * @return array An array of data objects
     *
     * @since 1.0
     */
    function getProductImages(){
    	$id		= $this->_getCid();
    	$db		= $this->getDbo();
    	$sql	= $db->getQuery(true);
    	
    	$sql->select("*");
    	$sql->from("#__shop_images");
    	$sql->where("product_id = {$id}");
    	$sql->order("ordering ASC");
    	
    	$db->setQuery($sql);
    	return $db->loadObjectList();
    }
    
	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * @return  void
	 *
	 * @note    Calling getState in this method will result in recursion.
	 * @since   1.0
	 */
	 protected function populateState()
	 {
	 	$app	= JFactory::getApplication();
	 	$option	= $app->input->get('option', 'com_shop', 'CMD');
	 	$scope	= $this->getName();

  		$this->setState('limit', $app->getUserStateFromRequest($option.'.'.$scope.'.limit', 'limit', $app->getCfg('list_limit'), 'int'));
  		$this->setState('limitstart', $app->getUserStateFromRequest($option.'.'.$scope.'.limitstart', 'limitstart', 0, 'int'));
  		$this->setState('filter_search', $app->getUserStateFromRequest($option.'.'.$scope.'.filter_search', 'filter_search', '', 'string'));
  		$this->setState('filter_order', $app->getUserStateFromRequest($option.'.'.$scope.'.filter_order', 'filter_order', 'ordering', 'cmd'));
  		$this->setState('filter_order_Dir', $app->getUserStateFromRequest($option.'.'.$scope.'.filter_order_Dir', 'filter_order_Dir', 'asc', 'string'));
	 }

    /**
     * Method to retrieve a JPagination object
     *
     * @return	object	a JPagination object
     *
     * @since	1.0
     */
    public function getPagination()
    {
    	$this->_db->setQuery("SELECT FOUND_ROWS()");
    	$this->_total = $this->_db->loadResult();
    	jimport('joomla.html.pagination');
    	$this->_pagination = new JPagination($this->_total, $this->getState('limitstart'), $this->getState('limit'));
    
    	return $this->_pagination;
    }
    
	/**
	 * A utility method for retrieving an item Id
	 *
	 * @return	int	the primary key
	 *
	 * @since	1.0
	 */
	protected function _getCid(){
		$row = $this->getTable();
		return JFactory::getApplication()->input->get($row->getKeyName(), 0, 'int');
	}
	
    /**
     * Upload any product images set in the product edit form
     *
     * @param   int     $product_id     The primary key of the intended product
     *
     * @return  bool
     *
     * @since   1.0
     */
    public function uploadImages($product_id){
    	if(!(int)$product_id){
    		throw new UnexpectedValueException(JText::_('COM_SHOP_MSG_ERROR_INVALID_DATA'));
    		return false;
    	}
    	jimport('joomla.filesystem.file');
    	$input = JFactory::getApplication()->input;
    	$files = $input->files->get('jform');
    	if(is_array($files['images'])){
    		foreach($files['images'] as $original){
    			switch($original['error']){
    			case 0:
    			// UPLOAD THE IMAGE
    				$upload = $original['tmp_name'];
    				$target = JPATH_ROOT."/images/shop/originals/".$original['name'];
    				if(!JFile::upload($upload, $target)){ 
    					return false;
    				}
    				$options = JComponentHelper::getParams('com_shop');
    				
    				$this->createProductImage($target, "/images/shop/full/", $options->get('img_width'), $options->get('img_height'));
    				$this->createProductImage($target, "/images/shop/thumbnails/", $options->get('thumb_width'), $options->get('thumb_height'));
    				$table = $this->getTable('Images');
    				$table->bind(array(
    					'product_id' => $product_id,
    					'image_source' => "images/shop/originals/".$original['name'],
    					'image_full' => "images/shop/full/".$original['name'],
    					'image_thumbnail' => "images/shop/thumbnails/".$original['name']
    				));
    				$table->check();
    				$table->store();
    				break;
    			case 4:
    			// NO IMAGE WAS SET DO NOTHING
    				break;
    			default:
    			// THERE WAS A FILE UPLOAD ERROR
    				return false;
    				break;
    			}
    		}
    	}
    	return true;
    }
    
    /**
     * Create a cropped and resized image from the uploaded original
     *
     * @param   string  $src    Path to source file
     * @param   string  $dest   Path to destination file
     * @param   int     $width  Path to source file
     * @param   int     $height Path to destination file
     *
     * @return bool
     */
    protected function createProductImage($src, $dest, $width, $height){
    	$original = new JImage($src);
    	//$thumb = $original->cropResize($width, $height, true);
    	$org_width = $original->getWidth();
    	$org_height = $original->getHeight();
    	if(($org_width / $width) < ($org_height / $height)){
    		$original->resize($width, 0, false);
    	}else{
    		$original->resize(0, $height, false);
    	}
    	$thumb = $original->crop($width, $height, null, null, true);
		$filename = pathinfo($original->getPath(), PATHINFO_FILENAME);
		$extension = pathinfo($original->getPath(), PATHINFO_EXTENSION);
		if(!$thumb->toFile(JPATH_ROOT.$dest.$filename.".".$extension)){
			return false;
		}
		$original->destroy();
		$thumb->destroy();
		return true;
    }
}
