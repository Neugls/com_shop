<?php
/**
 * Shop Default Site View
 
 * @package		Shop
 * @subpackage	Components
 * @license		GNU/GPL
 */

// REQUIRE THE BASE VIEW
jimport( 'joomla.application.component.view');

class ShopViewProducts extends JViewLegacy
{
	function display($tpl = null)
	{
		$layout = $this->getLayout();
		switch($layout){
		case "detail":
			$this->data = $this->get('Data');
			$this->images = $this->get('Images');
			$this->options = $this->get('Options');
			break;
		default:
			$this->items = $this->get('List');
			break;
		}
		parent::display($tpl);
	}
}
