<?php
/**
 * Shop Products Admin Controller
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

class ShopControllerProducts extends ShopControllerBase
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	public function __construct($config = array())
	{
		$this->view_item = 'products';
		$this->view_list = 'products';
		parent::__construct($config);
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
	
	/**
	 * Function that allows child controller access to model data
	 * after the data has been saved.
	 *
	 * @param   JModelForm  $model      The data model object.
	 * @param   array       $validData  The validated data.
	 *
	 * @return  void
	 */
	protected function postSaveHook(JModelForm $model, $validData = array())
	{
		$id = $model->getState($this->context . '.id');
		if(!$model->uploadImages($id)){
			JFactory::getApplication()->enqueueMessage(JText::_('COM_RETAIL_MSG_ERROR_IMAGE_UPLOAD'), 'warning');
		}
	}
	
}
