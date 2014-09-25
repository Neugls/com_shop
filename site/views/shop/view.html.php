<?php
/**
 * Shop Default Site View
 
 * @package		Shop
 * @subpackage	Components
 * @license		GNU/GPL
 */

// REQUIRE THE BASE VIEW
jimport( 'joomla.application.component.view');

class ShopViewShop extends JViewLegacy
{
	function display($tpl = null)
	{
		$this->data = $this->get('Data');
		$this->items = $this->get('List');
		parent::display($tpl);
	}
}
