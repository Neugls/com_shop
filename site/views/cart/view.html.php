<?php
/**
 * Shop Default Site View
 
 * @package		Shop
 * @subpackage	Components
 * @license		GNU/GPL
 */

// REQUIRE THE BASE VIEW
jimport( 'joomla.application.component.view');

class ShopViewCart extends JViewLegacy
{
	function display($tpl = null)
	{
	    $this->items = $this->get('List');
	    $this->cart = $this->get('Data');
		parent::display($tpl);
	}
}
