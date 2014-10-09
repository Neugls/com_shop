<?php
/**
 * Shop Images Admin Controller
 *
 * @package		Shop
 * @subpackage	Components
 *
 * @copyright	Copyright (C) 2007 - 2014 Subtext Productions. All rights reserved.
 * @license		GNU/GPL
 */

// NO DIRECT ACCESS
defined( '_JEXEC' ) or die( 'Restricted access' );

// PRIVILEGE CHECK
if(!JFactory::getUser()->authorise('core.manage', 'com_shop')){
	return JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));
}

// IMPORT BASE CONTROLLER LIBRARY
require_once('base.php');

class ShopControllerImages extends ShopControllerBase
{
	/**
	 * Constructor (registers additional tasks to methods)
	 *
	 * @param   array   $config     An array of configuration options
	 *
	 * @return void
	 */
	public function __construct($config = array())
	{
		$this->view_item = 'products';
		$this->view_list = 'products';
		parent::__construct($config);
	}

	/**
	 * Removes an item.
	 *
	 * @return  string JSON formatted data
	 *
	 * @since   1.0
	 */
	public function delete()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		// Get items to remove from the request.
		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($cid);

			// Remove the items.
			$result = $model->delete($cid);
			if ($result)
			{
				$this->setMessage(JText::plural($this->text_prefix . '_N_ITEMS_DELETED', count($cid)));
			}
			else
			{
				$this->setMessage($model->getError());
			}
			
			if(!$this->get("message")) $this->setMessage(JText::_('COM_SHOP_MSG_IMAGE_DELETE_ERROR'), 'error');
		}
		$response = array(
		    "status"        => $result,
		    "task"          => $this->getTask(),
		    "targets"       => $cid,
		    "message"       => $this->get("message"),
		    "message_type"  => $this->get("messageType")
		);
		header('Content-Type: application/json');
		echo json_encode($response);
        JFactory::getApplication()->close();
	}

	/**
	 * Function that allows child controller access to model data
	 * after the data has been saved.
	 *
	 * @param   JModelLegacy  $model      The data model object.
	 * @param   array         $validData  The validated data.
	 *
	 * @return  void
	 *
	 * @since   12.2
	 */
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
	    if($validData['product_id']){
	        $model = $this->getModel();
	        $table = $model->getTable();
	        $table->reorder("product_id = ".$validData['product_id']);
	    }
	}

	/**
	 * A convenience method for filtering lists.
	 *
	 * @return  void
	 */
	public function filter()
	{
		$model = $this->getModel();
		$this->setRedirect(JRoute::_("index.php?option=com_shop&view=".$this->view_list, false));
		return true;
	}
}
