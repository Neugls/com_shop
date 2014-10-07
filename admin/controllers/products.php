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
}
