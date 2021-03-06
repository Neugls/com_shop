<?php
/**
 * Shop Carts Admin Model
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

class ShopModelCarts extends JModelAdmin
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
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   11.1
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
		$row 	= $this->getTable('Cart');

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
		if($form = $this->loadForm('com_shop.carts', 'carts', array('control'=>'jform', 'load_data'=>$loadData))){
			return $form;
		}
		JError::raiseError(0, JText::sprintf('JLIB_FORM_INVALID_FORM_OBJECT', 'carts'));
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
		$row 	= $this->getTable('Cart');
		$id 	= $this->_getCid();
		
		$query->select("*");
		$query->from($row->getTableName());
		$query->where("{$row->getKeyName()} = {$id}");
		
		$db->setQuery($query);
		$this->_data = $db->loadAssoc();
		$ini = new JRegistry();
		$ini->loadString($this->_data['attribs']);
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
    	$row		= $this->getTable('Cart');
    	$sql		= $this->_db->getQuery(true);
    	$filter		= array();
    	if($search = addslashes($mainframe->getUserState($option.'.'.$scope.'.filter_search'))){
    		$filter[] = "`customer_name` LIKE '%{$search}%'";
    	}
    	if(!$ordering = $mainframe->getUserState($option.'.'.$scope.'.filter_order')){
    		$ordering = "`modified`";
    	}
    	if(!$order_dir = $mainframe->getUserState($option.'.'.$scope.'.filter_order_Dir')){
    		$order_dir = "ASC";
    	}
    	$sql->select("SQL_CALC_FOUND_ROWS o.*");
    	$sql->select("CONCAT_WS(' ', `customer_first`, `customer_last`) AS `customer_name`");
    	$sql->from("`{$row->getTableName()}` o");
    	$sql->join("left", "`#__shop_customers` c USING(`customer_id`)");
    	$sql->where("`cart_status` = 1");
		foreach($filter as $condition){
			$sql->where($condition);
		}
		$sql->order("{$ordering} {$order_dir}");
		$this->_data = $this->_getList($sql, $this->getState('limitstart'), $this->getState('limit'));

    	return $this->_data;
    }

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $name     The table name. Optional.
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
	 *
	 * @since   12.2
	 * @throws  Exception
	 */
	public function getTable($name = '', $prefix = 'Table', $options = array())
	{
		if(empty($name)) $name = 'Cart';
		return parent::getTable($name , $prefix, $options);
	}

	/**
	 * Method to delete one or more records.
	 *
	 * @param   array  &$pks  An array of record primary keys.
	 *
	 * @return  boolean  True if successful, false if an error occurs.
	 *
	 * @since   1.0
	 */
	public function delete(&$pks)
	{
		$db = $this->getDbo();
		$sql = $db->getQuery(true);
		$ids = implode(',', $pks);
		$sql->select("`item_id`");
		$sql->from("`#__shop_cart_items`");
		$sql->where("`cart_id` IN ({$ids})");
		$db->setQuery($sql);
		$keys = $db->loadColumn();
		$status = parent::delete($pks);
		if($status){
			$clause = "`item_id` IN (".implode(',',$keys).")";
			$sql->clear();
			$sql->delete("`#__shop_cart_items`");
			$sql->where($clause);
			$db->setQuery($sql);
			$db->execute();
			$sql->clear();
			$sql->delete("`#__shop_item_options`");
			$sql->where($clause);
			$db->setQuery($sql);
			$db->execute();
		}
		return $status;
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
		$row = $this->getTable('Cart');
		return JFactory::getApplication()->input->get($row->getKeyName(), 0, 'int');
	}
}
