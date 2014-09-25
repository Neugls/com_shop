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

class ShopModelShop extends JModelLegacy
{
 	function __construct(){
		parent::__construct();
		$user = JFactory::getUser();
		$this->setState('levels', implode(',', array_unique($user->getAuthorisedViewLevels())));
  	}
    /**
     * Retrieve data for a single item
     * @return object A stdClass object containing the data for a single record.
     */
    public function getData()
    {
    	$levels	= $this->getState('levels');
		$id 	= JFactory::getApplication()->input->get('id', 0, 'int');
		$sql	= "SELECT * FROM `#__shop` ".
		"WHERE `shop_id` = {$id} ".
		"AND `published` = 1 ".
		"AND `access` IN ({$levels}) LIMIT 1";
		if($id){
			$this->_db->setQuery($sql);
			$this->_data = $this->_db->loadObject();
			$params = new JRegistry();
			$params->loadString($this->_data->attribs);
			$this->_data->params = $params;
		}else{
			JError::raiseError(404, JText::_('COM_SHOP_MSG_ERROR_404'));
			return false;
		}

        return $this->_data;
    }
    /**
     * Retrieve a list of all data items.
     * @return array An array of stdClass objects containing data for each record.
     */
    public function getList()
    {
    	$levels	= $this->getState('levels');
		$sql	= "SELECT * FROM `#__shop` ".
		"WHERE `published` = 1 ".
		"AND `access` IN ({$levels}) LIMIT 1";
		$this->_db->setQuery($sql);
		$this->_data = $this->_db->loadObjectList();
		
		return $this->_data;
    }
}
