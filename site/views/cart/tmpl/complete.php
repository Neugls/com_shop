<?php
// NO DIRECT ACCESS
defined('_JEXEC') or die('Restricted access');
$app = JFactory::getApplication();
$app->redirect(JRoute::_('index.php?option=com_shop&view=cart&layout=receipt&id='.$this->order_id), JText::_('COM_SHOP_MSG_SUCCESS_CHECKOUT_COMPLETE'), 'success');
