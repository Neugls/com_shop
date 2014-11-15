<?php
// NO DIRECT ACCESS
defined('_JEXEC') or die('Restricted access');
$order_date = new JDate($this->order->cart_checkout, 'UTC');
?>
<h1>Order Receipt</h1>
<time><?php echo $order_date->format("F j, Y h:iA e"); ?></time>
<table class="table table-bordered table-striped">
    <thead>
        <th><?php echo JText::_('COM_SHOP_PRODUCT_NAME_LABEL'); ?></th>
        <th><?php echo JText::_('COM_SHOP_ITEM_SKU_LABEL'); ?></th>
        <th width="15%"><?php echo JText::_('COM_SHOP_ITEM_QUANTITY_LABEL'); ?></th>
        <th><?php echo JText::_('COM_SHOP_ITEM_PRICE_LABEL'); ?></th>
    </thead>
    <tbody>
        <?php foreach($this->items as $row){ ?>
        <tr>
            <td><?php echo $row->product_name; ?></td>
            <td><?php echo $row->item_sku; ?></td>
            <td><?php echo $row->item_quantity; ?></td>
            <td>$<?php echo $row->line_price; ?></td>
        </tr>
        <?php } ?>
        <tr>
            <th colspan="3" style="text-align: right;"><?php echo JText::_('COM_SHOP_CART_SUBTOTAL_LABEL'); ?></th>
            <td>$<?php echo number_format($this->order->cart_subtotal, 2); ?></td>
        </tr>
        <tr>
            <th colspan="3" style="text-align: right;"><?php echo JText::_('COM_SHOP_CART_TAX_LABEL'); ?></th>
            <td>$<?php echo number_format($this->order->cart_tax, 2); ?></td>
        </tr>
        <tr>
            <th colspan="3" style="text-align: right;"><?php echo JText::_('COM_SHOP_CART_SHIPPING_LABEL'); ?></th>
            <td>$<?php echo number_format($this->order->cart_shipping, 2); ?></td>
        </tr>
        <tr>
            <th colspan="3" style="text-align: right;"><?php echo JText::_('COM_SHOP_CART_TOTAL_LABEL'); ?></th>
            <td>$<?php echo number_format($this->order->cart_total, 2); ?></td>
        </tr>
    </tbody>
</table>
