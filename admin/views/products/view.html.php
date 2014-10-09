<?php
/**
 * Shop Products Admin View
 * 
 * @package		Shop
 * @subpackage	Components
 * @license		GNU/GPL
 */

// NO DIRECT ACCESS
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class ShopViewProducts extends JViewLegacy
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
			ShopHelper::addSubmenu('products');
			JToolBarHelper::title(JText::_('COM_SHOP_VIEW_PRODUCTS_LIST_TITLE'), 'generic.png');
			JToolBarHelper::addNew('products.add', 'JTOOLBAR_NEW');
			JToolBarHelper::editList('products.edit', 'JTOOLBAR_EDIT', true);
			JToolBarHelper::deleteList(JText::_('COM_SHOP_MSG_DELETE_CONFIRM'), 'products.delete', 'JTOOLBAR_DELETE', true);
			JToolBarHelper::preferences('com_shop', '500');
			// GET DATA FROM THE MODEL
			$this->filter = $this->get('State');
			$this->items = $this->get('List');
			$this->page = $this->get('Pagination');
			break;
		default:
			$input->set('hidemainmenu', 1);
			JToolBarHelper::title(JText::_('COM_SHOP_VIEW_PRODUCTS_EDIT_TITLE'), 'generic.png');
			JToolBarHelper::apply('products.apply');
			JToolBarHelper::save('products.save');
			JToolBarHelper::save2new('products.save2new');
			JToolBarHelper::cancel('products.cancel');
			$this->form = $this->get('Form');
			$this->images = $this->get('ProductImages');
			break;
		}
		parent::display($tpl);
	}
}
