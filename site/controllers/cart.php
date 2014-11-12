<?php
/**
 * Shop Cart Controller
 *
 * @package		Shop
 * @subpackage	Component
 * @license		GNU/GPL
 */

// NO DIRECT ACCESS
defined( '_JEXEC' ) or die( 'Restricted access' );

class ShopControllerCart extends JControllerLegacy
{
	/**
	 * Method to add an item to the cart
	 *
	 * @return	boolean
	 */
	public function addToCart()
	{
		// CHECK FOR REQUEST FORGERIES
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$params = JComponentHelper::getParams('com_shop');
		$user = JFactory::getUser();
		if($user->get('guest') == 1 && $params->get('anonymous') == 0){
		    $this->setRedirect(JRoute::_('index.php?option=com_users&view=login'), JText::_('COM_SHOP_MSG_REGISTRATION_REQUIRED'), 'info');
		    return true;
		}
		$model = $this->getModel('Cart');
		if($model->addToCart()){
		    $this->setRedirect(JRoute::_('index.php?option=com_shop&view=cart&layout=default'), JText::_('COM_SHOP_MSG_SUCCESS_PRODUCT_ADDED_TO_CART'), 'success');
		    return true;
		}else{
		    $this->setRedirect(JRoute::_('index.php?option=com_shop&view=product&layout=detail&id='.$slug), $model->getError(), 'error');
		    return false;
		}
	}
	
	/**
	 * Method to update an item quantity in the shopping cart
	 *
	 * @return	boolean
	 */
	public function updateItem()
	{
		// CHECK FOR REQUEST FORGERIES
		JSession::checkToken('get') or die(JText::_('JINVALID_TOKEN'));
		
		$params = JComponentHelper::getParams('com_shop');
		$user = JFactory::getUser();
		if($user->get('guest') == 1 && $params->get('anonymous') == 0){
		    $this->setRedirect(JRoute::_('index.php?option=com_users&view=login'), JText::_('COM_SHOP_MSG_REGISTRATION_REQUIRED'), 'info');
		    return true;
		}
		$model = $this->getModel('Cart');
		if($model->updateItem()){
		    $this->setRedirect(JRoute::_('index.php?option=com_shop&view=cart&layout=default'), JText::_('COM_SHOP_MSG_SUCCESS_CART_QTY_UPDATED'), 'success');
		    return true;
		}else{
		    $this->setRedirect(JRoute::_('index.php?option=com_shop&view=cart&layout=default'), $model->getError(), 'error');
		    return false;
		}
	}
	/**
	 * Method to remove an item from the cart
	 *
	 * @return	boolean
	 */
	public function removeItem()
	{
		// CHECK FOR REQUEST FORGERIES
		JSession::checkToken('get') or die(JText::_('JINVALID_TOKEN'));
		
		$params = JComponentHelper::getParams('com_shop');
		$user = JFactory::getUser();
		if($user->get('guest') == 1 && $params->get('anonymous') == 0){
		    $this->setRedirect(JRoute::_('index.php?option=com_users&view=login'), JText::_('COM_SHOP_MSG_REGISTRATION_REQUIRED'), 'info');
		    return true;
		}
		$model = $this->getModel('Cart');
		if($model->removeItem()){
		    $this->setRedirect(JRoute::_('index.php?option=com_shop&view=cart&layout=default'), JText::_('COM_SHOP_MSG_SUCCESS_PRODUCT_REMOVED_FROM_CART'), 'success');
		    return true;
		}else{
		    $this->setRedirect(JRoute::_('index.php?option=com_shop&view=cart&layout=default'), $model->getError(), 'error');
		    return false;
		}
	}
}
