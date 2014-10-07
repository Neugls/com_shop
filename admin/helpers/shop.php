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
		JSubMenuHelper::addEntry(JText::_('COM_SHOP_SUBMENU_CARTS'), 'index.php?option=com_shop', $submenu == 'carts');
		JSubMenuHelper::addEntry(JText::_('COM_SHOP_SUBMENU_CUSTOMERS'), 'index.php?option=com_shop&amp;view=customers', $submenu == 'customers');
		JSubMenuHelper::addEntry(JText::_('COM_SHOP_SUBMENU_PRODUCTS'), 'index.php?option=com_shop&amp;view=products', $submenu == 'products');
	}
}
