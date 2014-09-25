<?php
/**
 * Shop Default View
 * 
 * @package		Shop
 * @subpackage	Components
 * @license		GNU/GPL
 */

// NO DIRECT ACCESS
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class ShopViewShop extends JViewLegacy
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
			JToolBarHelper::title(JText::_('COM_SHOP_VIEW_SHOP_LIST_TITLE'), 'generic.png');
			JToolBarHelper::addNew('shop.add', 'JTOOLBAR_NEW');
			JToolBarHelper::editList('shop.edit', 'JTOOLBAR_EDIT', true);
			JToolBarHelper::deleteList(JText::_('COM_SHOP_MSG_DELETE_CONFIRM'), 'shop.delete', 'JTOOLBAR_DELETE', true);
			JToolBarHelper::preferences('com_shop', '500');
			// GET DATA FROM THE MODEL
			$this->filter = $this->get('State');
			$this->items = $this->get('List');
			$this->page = $this->get('Pagination');
			break;
		default:
			$input->set('hidemainmenu', 1);
			JToolBarHelper::title(JText::_('COM_SHOP_VIEW_SHOP_EDIT_TITLE'), 'generic.png');
			JToolBarHelper::apply('shop.apply');
			JToolBarHelper::save('shop.save');
			JToolBarHelper::save2new('shop.save2new');
			JToolBarHelper::cancel('shop.cancel');
			$this->form = $this->get('Form');
			break;
		}
		parent::display($tpl);
	}
}
