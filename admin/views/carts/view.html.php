<?php
/**
 * Shop Carts Admin View
 * 
 * @package		Shop
 * @subpackage	Components
 * @license		GNU/GPL
 */

// NO DIRECT ACCESS
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class ShopViewCarts extends JViewLegacy
{
	/**
	 * Shop view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		$input = JFactory::getApplication()->input;
		$layout = $input->get->get('layout', 'list', 'string');
		$this->setLayout($layout);
		switch($layout){
		case "list":
			ShopHelper::addSubmenu('carts');
			JToolBarHelper::title(JText::_('COM_SHOP_VIEW_CARTS_LIST_TITLE'), 'generic.png');
			JToolBarHelper::editList('carts.edit', 'JTOOLBAR_EDIT', true);
			JToolBarHelper::deleteList(JText::_('COM_SHOP_MSG_DELETE_CONFIRM'), 'carts.delete', 'JTOOLBAR_DELETE', true);
			JToolBarHelper::preferences('com_shop', '500');
			// GET DATA FROM THE MODEL
			$this->filter = $this->get('State');
			$this->items = $this->get('List');
			$this->page = $this->get('Pagination');
			break;
		default:
			$input->set('hidemainmenu', 1);
			JToolBarHelper::title(JText::_('COM_SHOP_VIEW_CARTS_EDIT_TITLE'), 'generic.png');
			JToolBarHelper::apply('carts.apply');
			JToolBarHelper::save('carts.save');
			JToolBarHelper::cancel('carts.cancel');
			$this->form = $this->get('Form');
			break;
		}
		parent::display($tpl);
	}
}
