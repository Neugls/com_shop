<?php
/**
 * Shop Helper
 * 
 * @package		Shop
 * @subpackage	Components
 * @license		GNU/GPL
 */

// NO DIRECT ACCESS
defined( '_JEXEC' ) or die( 'Restricted access' );

abstract Class ShopHelper {
	public static function addSubmenu($submenu){
		// ADD SUBMENU TABS
		//JSubMenuHelper::addEntry(JText::_('COM_SHOP_SUBMENU_SECONDARY'), 'index.php?option=com_shop', $submenu == 'shop');
	}
}
