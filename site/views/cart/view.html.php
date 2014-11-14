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
		$layout = $this->getLayout();
		switch($layout){
		case "receipt":
			break;
		case "complete":
			$this->order_id = $this->get('PayPalData');
			break;
		default:
	    	$this->items = $this->get('List');
	    	$this->cart = $this->get('Data');
	    	break;
	    }
		parent::display($tpl);
	}
}
