<?php
/**
 * Shop Model
 * 
 * @package		Shop
 * @subpackage	Component
 * @license		GNU/GPL
 */

// CHECK TO ENSURE THIS FILE IS INCLUDED IN JOOMLA!
defined('_JEXEC') or die();

// REQUIRE THE BASE MODEL
jimport( 'joomla.application.component.model' );

class ShopModelProducts extends JModelLegacy
{
 	function __construct(){
		parent::__construct();
		$user = JFactory::getUser();
		$this->setState('levels', implode(',', array_unique($user->getAuthorisedViewLevels())));
  	}

	/**
	 * Method to get a single record.
	 *
	 * @return  mixed    Object on success, false on failure.
	 *
	 * @since   1.0
	 */
    public function getData()
    {
		$id 	= JFactory::getApplication()->input->get('id', 0, 'int');
    	$levels	= explode(',', $this->getState('levels'));
    	$table	= $this->getTable();
		if($id){
			$this->_data = $table->getData($id);
			$params = new JRegistry();
			$params->loadString($this->_data->params);
			$this->_data->params = $params;
        	if(in_array($this->_data->access, $levels)) return $this->_data;
		}
		JError::raiseError(404, JText::_('COM_SHOP_MSG_ERROR_404'));
		return false;
    }

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   1.0
	 */
    public function getList()
    {
    	$levels	= $this->getState('levels');
    	$sql	= $this->_db->getQuery(true);
    	
    	$sql->select("p.*");
    	$sql->select("CONCAT_WS(':', p.product_id, p.product_alias) AS product_slug");
    	$sql->select("image_thumbnail, image_alt, image_title");
    	$sql->from("`#__shop_products` p");
    	$sql->join("left", "`#__shop_images` i USING(`product_id`)");
    	$sql->where("`state` = 1");
    	$sql->where("(`publish_up` >= NOW() OR `publish_up` = '0000-00-00 00:00:00')");
    	$sql->where("(`publish_down` <= NOW() OR `publish_down` = '0000-00-00 00:00:00')");
    	$sql->where("i.`ordering` = 1");
    	$sql->where("`access` IN ({$levels})");
    	$sql->order("p.`ordering` ASC");
		$this->_db->setQuery($sql);
		$this->_data = $this->_db->loadObjectList();
		
		return $this->_data;
    }

	/**
	 * Method to get an array of image data objects for a specified product.
	 *
	 * @return  mixed    Array on success, false on failure.
	 *
	 * @since   1.0
	 */
    public function getImages()
    {
    	$levels	= $this->getState('levels');
    	$sql	= $this->_db->getQuery(true);
		$id 	= JFactory::getApplication()->input->get('id', 0, 'int');
		
    	$sql->select("*");
    	$sql->from("`#__shop_images`");
    	$sql->where("`product_id` = {$id}");
    	$sql->order("`ordering` ASC");
    	
    	$this->_db->setQuery($sql);
    	return $this->_db->loadObjectList();
    }

	/**
	 * Method to get an multi-dimensional array of data in chunks corresponding to
	 * unique keys for each set of options. Data is contained in std class objects.
	 *
	 * @return  mixed    Array on success, false on failure.
	 *
	 * @since   1.0
	 */
    public function getOptions()
    {
    	$levels	= $this->getState('levels');
    	$sql	= $this->_db->getQuery(true);
		$id 	= JFactory::getApplication()->input->get('id', 0, 'int');
		
    	$sql->select("*");
    	$sql->from("`#__shop_options`");
    	$sql->where("`product_id` = {$id}");
    	$sql->order("`ordering` ASC");
    	
    	$this->_db->setQuery($sql);
    	$list = $this->_db->loadObjectList();
    	$options = array();
    	$option = array();
    	if(count($list)){
			$option_key = $list[0]->option_key;
			foreach($list as $data){
				if($option_key != $data->option_key){
					$options[] = $option;
					$option = array();
					$option_key = $data->option_key;
				}
				$option[] = $data;
			}
			$options[] = $option;
    	}
    	return $options;
    }
}
