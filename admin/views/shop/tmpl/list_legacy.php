<?php
	defined('_JEXEC') or die('Restricted access');
	JHtml::_('behavior.modal');
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.formvalidation');
	$uri = JURI::getInstance();
	$base = $uri->root();
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="option" value="com_shop" />
	<input type="hidden" name="scope" value="" />
	<input type="hidden" name="task" value="shop.filter" />
	<input type="hidden" name="chosen" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->filter->filter_order; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter->filter_order_Dir; ?>" />
	<?php echo JHTML::_('form.token')."\n"; ?>
	<table class="table table-bordered">
		<tr>
			<td width="100%">
				<label for=""><?php echo JText::_('COM_SHOP_FILTER_SEARCH_LABEL'); ?></label>
				<input type="text" name="filter_search" id="filter-search_" class="input-small" value="<?php echo $this->filter->filter_search; ?>" />
				<input type="button" name="submit_button" id="submit-button_" value="Go" onclick="document.forms.adminForm.task.value='filter';document.forms.adminForm.submit();"/>
				<input type="button" name="reset_button" id="reset-button_" value="Reset" onclick="document.forms.adminForm.filter_search.value='';document.forms.adminForm.task.value='filter';document.forms.adminForm.submit();"/>
			</td>
		</tr>
	</table>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="5">
					<?php echo JText::_('Num'); ?>
				</th>
				<th width="5">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this);" />
				</th>
				<th class="title" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_SHOP_LIST_SHOP_NAME_LABEL', 'shop_name', $this->filter->filter_order_Dir, $this->filter->filter_order, 'shop.filter'); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_SHOP_LIST_PUBLISHED_LABEL', 'published', $this->filter->filter_order_Dir, $this->filter->filter_order, 'shop.filter'); ?>
				</th>
				<th width="10%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_SHOP_LIST_ORDERING_LABEL', 'ordering', $this->filter->filter_order_Dir, $this->filter->filter_order, 'shop.filter');?>
					<?php echo JHTML::_('grid.order', $this->items, 'filesave.png', 'shop.saveorder'); ?>
				</th>
				<th nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_SHOP_LIST_ACCESS_LABEL', 's.access', $this->filter->filter_order_Dir, $this->filter->filter_order, 'shop.filter'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_SHOP_LIST_DESCRIPTION_LABEL'); ?>
				</th>
				<th width="1%">
					<?php echo JText::_('COM_SHOP_LIST_ID_LABEL'); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$k = 0;
		for($i=0; $i < count($this->items); $i++){
			$row		= $this->items[$i];
			$checked	= JHTML::_('grid.id', $i, $row->shop_id);
			$link		= JRoute::_('index.php?option=com_shop&task=shop.edit&shop_id='. $row->shop_id.'&'.JSession::getFormToken().'=1');
			?>
			<tr class="row<?php echo $k; ?>">
				<td>
					<?php echo $this->page->getRowOffset($i); ?>
				</td>
				<td align="center">
					<?php echo $checked; ?>
				</td>
				<td  nowrap="nowrap">
					<?php
					if(JTable::isCheckedOut(JFactory::getUser()->get('id'), $row->checked_out)){
						echo JHTML::_('grid.checkedout', $row, $i, 'shop_id');
						echo JText::_( $row->shop_name);
					}else{
						echo "<a href=\"{$link}\">" . htmlspecialchars($row->shop_name, ENT_QUOTES) . "</a>";
					}
					?>
				</td>
				<td align="center">
					<?php echo JHtml::_('jgrid.published', $row->published, $i, 'shop.', true, 'cb'); ?>
				</td>
				<td class="order">
					<span><?php echo $this->page->orderUpIcon( $i, ($this->page->getRowOffset($i) > 1), 'shop.orderup', 'Move Up'); ?></span>
					<span><?php echo $this->page->orderDownIcon( $i, count($this->items), true, 'shop.orderdown', 'Move Down'); ?></span>
					<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
				</td>
				<td align="center">
					<?php echo $row->access; ?>
				</td>
				<td>
					<?php echo implode(" ", array_splice(explode(" ", strip_tags($row->shop_description)), 0, 55)); ?>
				</td>
				<td>
					<?php echo $row->shop_id; ?>
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
