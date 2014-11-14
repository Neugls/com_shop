<?php
	JHtml::_('behavior.multiselect');
	JHtml::_('formbehavior.chosen', 'select');
	JHtml::_('bootstrap.tooltip');
	$user = JFactory::getUser();
	$user_id = $user->get('id');
	$sortFields = array();
	$sortFields['customer_name'] = JText::_('COM_SHOP_LIST_CUSTOMER_NAME_LABEL');
	$sortFields['cart_status'] = JText::_('COM_SHOP_LIST_CART_STATUS_LABEL');
	$sortFields['cart_checkout'] = JText::_('COM_SHOP_LIST_CART_CHECKOUT_LABEL');
?>
<script type="text/javascript">
//<![CDATA[
	Joomla.orderTable = function()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $this->filter->filter_order; ?>')
		{
			dirn = 'asc';
		}
		else
		{
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
//]]>
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="option" value="com_shop" />
	<input type="hidden" name="scope" value="" />
	<input type="hidden" name="task" value="shop.filter" />
	<input type="hidden" name="chosen" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->filter->filter_order; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter->filter_order_Dir; ?>" />
	<?php echo JHtml::_('form.token')."\n"; ?>
	<div id="filter-bar" class="btn-toolbar">
		<div class="btn-group pull-right">
			<?php echo $this->page->getLimitBox(); ?>
		</div>
		<div class="btn-group pull-right hidden-phone">
			<label for="directionTable" class="element-invisible"><?php echo JText::_('COM_SHOP_LIST_ORDERING_LABEL');?></label>
			<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
				<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
				<option value="asc" <?php if ($this->filter->filter_order_Dir == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
				<option value="desc" <?php if ($this->filter->filter_order_Dir == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
			</select>
		</div>
		<div class="btn-group pull-right">
			<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
			<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
				<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
				<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $this->filter->filter_order);?>
			</select>
		</div>
		<div class="btn-group pull-left">
			<input type="text" name="filter_search" id="filter-search_" class="input-large" placeholder="<?php echo JText::_('COM_SHOP_FILTER_SEARCH_LABEL'); ?>" value="<?php echo $this->filter->filter_search; ?>" />
		</div>
		<div class="btn-group pull-left">
			<input type="button" class="btn" name="submit_button" id="submit-button_" value="Go" onclick="document.forms.adminForm.task.value='filter';document.forms.adminForm.submit();"/>
			<input type="button" class="btn" name="reset_button" id="reset-button_" value="Reset" onclick="document.forms.adminForm.filter_search.value='';document.forms.adminForm.task.value='filter';document.forms.adminForm.submit();"/>
		</div>
	</div>
	<table class="table table-striped" id="data-table">
		<thead>
			<tr>
				<th width="5%">
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" />
				</th>
				<th class="title nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_SHOP_LIST_CUSTOMER_NAME_LABEL', 'customer_name', $this->filter->filter_order_Dir, $this->filter->filter_order, 'shop.filter'); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_SHOP_LIST_CART_STATUS_LABEL', 'cart_status', $this->filter->filter_order_Dir, $this->filter->filter_order, 'shop.filter'); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_SHOP_LIST_CART_CHECKOUT_LABEL', 'cart_checkout', $this->filter->filter_order_Dir, $this->filter->filter_order, 'shop.filter'); ?>
				</th>
				<th width="1%">
					<?php echo JHtml::_('grid.sort', 'COM_SHOP_LIST_ID_LABEL', 'shop_id', $this->filter->filter_order_Dir, $this->filter->filter_order, 'shop.filter'); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$k = 0;
		for($i=0; $i < count($this->items); $i++){
			$row		= $this->items[$i];
			$checked	= JHtml::_('grid.id', $i, $row->cart_id);
			$link		= JRoute::_('index.php?option=com_shop&task=carts.edit&shop_id='. $row->cart_id.'&'.JSession::getFormToken().'=1');
			$canCreate  = $user->authorise('core.create',     'com_shop');
			$canEdit    = $user->authorise('core.edit',       'com_shop');
			$canCheckin = $user->authorise('core.manage',     'com_checkin') || $row->checked_out == $user_id || $row->checked_out == 0;
			$canEditOwn = $user->authorise('core.edit.own',   'com_shop');
			$canChange  = $user->authorise('core.edit.state', 'com_shop') && $canCheckin;
			?>
			<tr class="row<?php echo $k; ?>" sortable-group-id="">
				<td align="center">
					<?php echo $checked; ?>
				</td>
				<td  class="nowrap">
					<?php
					if($row->checked_out){
						echo JHtml::_('jgrid.checkedout', $i, $row->editor, $row->checked_out_time, 'shop.', $canCheckin);
						echo "<span class=\"title\">".JText::_( $row->customer_name)."</span>";
					}else{
						if($canEdit || $canEditOwn){
							echo "<a href=\"{$link}\">" . htmlspecialchars($row->customer_name, ENT_QUOTES) . "</a>";
						}else{
							echo "<span class=\"title\">".JText::_( $row->customer_name)."</span>";
						}
					}
					?>
				</td>
				<td align="center">
					<?php echo $row->cart_status; ?>
				</td>
				<td>
					<?php echo $row->cart_checkout; ?>
				</td>
				<td>
					<?php echo $row->cart_id; ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->page->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>
</form>
