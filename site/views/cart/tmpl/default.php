<?php
// NO DIRECT ACCESS
defined('_JEXEC') or die('Restricted access');
// SET DOCUMENT HEAD FOR PAGE
$doc = JFactory::getDocument();
$doc->addStyleDeclaration(".qty-group span.qty { padding: 0 5px;font-size:12px;font-weight: bold; }");
$options = JComponentHelper::getParams('com_shop');
$subtotal = 0.00;
$i = 0;
$base = substr(JURI::base(), 0, -1);
?>

<div>
<h3><?php echo JText::_('COM_SHOP_CART_DEFAULT_TITLE'); ?></h3>
<!-- OUTPUT CUSTOM HTML HERE -->
<table class="table table-bordered table-striped">
    <thead>
        <th><?php echo JText::_('COM_SHOP_PRODUCT_NAME_LABEL'); ?></th>
        <th><?php echo JText::_('COM_SHOP_ITEM_SKU_LABEL'); ?></th>
        <th width="15%"><?php echo JText::_('COM_SHOP_ITEM_QUANTITY_LABEL'); ?></th>
        <th><?php echo JText::_('COM_SHOP_ITEM_PRICE_LABEL'); ?></th>
        <th width="1%">&nbsp;</th>
    </thead>
    <tbody>
        <?php foreach($this->items as $row){ ?>
        <tr>
            <td><?php echo $row->product_name; ?></td>
            <td><?php echo $row->item_sku; ?></td>
            <td>
                <div class="btn-group qty-group">
                    <a href="/index.php?option=com_shop&amp;task=cart.updateItem&amp;id=<?php echo $row->item_id; ?>&amp;<?php echo JSession::getFormToken(); ?>=1&amp;item_quantity=1" class="btn btn-mini"><i class="icon icon-plus-2"></i></a>
                    <a href="#" class="btn btn-mini"><span class="qty"><?php echo $row->item_quantity; ?></span></a>
                    <a href="/index.php?option=com_shop&amp;task=cart.updateItem&amp;id=<?php echo $row->item_id; ?>&amp;<?php echo JSession::getFormToken(); ?>=1&amp;item_quantity=-1" class="btn btn-mini"><i class="icon icon-minus-2"></i></a>
                </div>
                
            </td>
            <td>$<?php echo $row->line_price; ?></td>
            <td class="text-center"><a href="/index.php?option=com_shop&amp;task=cart.removeItem&amp;id=<?php echo $row->item_id; ?>&amp;<?php echo JSession::getFormToken(); ?>=1"><i class="icon icon-remove"></i></a></td>
        </tr>
        <?php $subtotal += $row->line_price; } ?>
        <tr>
            <th colspan="3" style="text-align: right;"><?php echo JText::_('COM_SHOP_CART_SUBTOTAL_LABEL'); ?></th>
            <td colspan="2">$<?php echo number_format($subtotal, 2); ?></td>
        </tr>
        </tr>
    </tbody>
</table>
<?php if(count($this->items)){ ?>
<form name="paypalcart" action="https://www.<?php echo $options->get('sandbox') ? 'sandbox.' : ''; ?>paypal.com/cgi-bin/webscr" method="POST">
    <input type="hidden" name="cmd" value="_cart" />
    <input type="hidden" name="upload" value="1" />
	<input type="hidden" name="business" value="<?php echo $options->get('business'); ?>" />
    <input type="hidden" name="currency_code" value="USD" />
    <input type="hidden" name="weight_unit" value="lbs" />
	<input type="hidden" name="custom" value="<?php echo $this->cart->cart_id; ?>" />
	<input type="hidden" name="cancel_return" value="<?php echo $base.JRoute::_('index.php?option=com_shop&view=cart&layout=default'); ?>" />
	<input type="hidden" name="return" value="<?php echo $base.JRoute::_('index.php?option=com_shop&view=cart&layout=complete'); ?>" />
	<input type="hidden" name="rm" value="2" />
	<input type="hidden" name="cbt" value="IMPORTANT!! PLEASE CLICK HERE TO COMPLETE THE TRANSACTION!!" />
    <?php foreach($this->items as $row){ ?>
    <?php $params = json_decode($row->params); ?>
    <input type="hidden" name="item_name_<?php echo $i + 1; ?>" value="<?php echo $row->product_name; ?>" />
    <input type="hidden" name="item_number_<?php echo $i + 1; ?>" value="<?php echo $row->item_sku; ?>" />
    <input type="hidden" name="weight_<?php echo $i + 1; ?>" value="<?php echo $row->item_weight; ?>" />
    <input type="hidden" name="quantity_<?php echo $i + 1; ?>" value="<?php echo $row->item_quantity;; ?>" />
    <input type="hidden" name="amount_<?php echo $i + 1; ?>" value="<?php echo $row->item_price; ?>" />
    <?php if($params->tax_exempt){ ?>
    <input type="hidden" name="tax_<?php echo $i + 1; ?>" value="0.00" />
    <?php } ?>
    <?php $i++; ?>
    <?php } ?>
    <p>
        <button type="input" class="btn btn-success pull-right"><?php echo JText::_('COM_SHOP_BUTTON_CHECKOUT'); ?> <i class="icon icon-credit"></i></button>
        <a href="<?php echo JRoute::_('index.php?option=com_shop&view=products&layout=default'); ?>" class="btn btn-primary"><?php echo JText::_('COM_SHOP_BUTTON_CONTINUE_SHOPPING'); ?> <i class="icon icon-basket"></i></a>
    </p>
</form>
<?php }else{ ?>
    <p>
        <a href="<?php echo JRoute::_('index.php?option=com_shop&view=products&layout=default'); ?>" class="btn btn-primary"><?php echo JText::_('COM_SHOP_BUTTON_CONTINUE_SHOPPING'); ?> <i class="icon icon-basket"></i></a>
    </p>
<?php } ?>
</div>
