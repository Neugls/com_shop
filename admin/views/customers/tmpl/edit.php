<?php
	defined('_JEXEC') or die('Restricted access');
	JHtml::_('behavior.modal');
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.formvalidation');
?>

<script type="text/javascript">
//<![CDATA[
	(function() {
		var $, UIView;
		$ = jQuery;
		UIView = (function() {
			function UIView() {
				// CONSTRUCTOR METHOD
				document.formvalidator.setHandler('uint', function(value){
					re_uint = /^\d+$/;
					return re_uint.test(value);
				});
				document.formvalidator.setHandler('string', function(value){
					re_string = /^([\w\d\s-_\.,&'#\u00E0-\u00FC]+)?$/;
					return re_string.test(value);
				});
				document.formvalidator.setHandler('cmd', function(value){
					re_cmd = /^([\w-_]+)$/;
					return re_cmd.test(value);
				});
				Joomla.submitbutton = function (sometask){
					var someForm = document.forms.adminForm;
					var re_blank = /^(\W*)$/;
					if(sometask != 'customers.cancel'){
						if(!document.formvalidator.isValid(someForm)){
							return false;
						}
					}
					someForm.task.value = sometask;
					someForm.submit();
				}
			}
		
			UIView.prototype.sampleMethod = function() {
				// OBJECT METHOD
			}

			return UIView;
		})();
	
		$(function() {
			return new UIView();
		});
	}).call(this);

//]]>
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<input type="hidden" name="option" value="com_shop" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="chosen" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="hidemainmenu" value="0" />
	<input type="hidden" name="shop_id" value="<?php echo $this->form->getValue('shop_id'); ?>" />
	<?php echo JHTML::_('form.token')."\n"; ?>
	<div id="editcell">
		<div class="span9 pull-left">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_SHOP_FORM_LEGEND_BASIC'); ?></legend>
				<?php foreach($this->form->getFieldset('base') as $field){ ?>
					<div class="control-group">
						<?php echo $field->label; ?>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
				<?php } ?>
			</fieldset>
		</div>
		<div class="span3 pull-left">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_SHOP_FORM_LEGEND_OPTIONS'); ?></legend>
				<?php foreach($this->form->getFieldset('options') as $field){ ?>
					<div class="control-group">
						<?php echo $field->label; ?>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
				<?php } ?>
			</fieldset>
		</div>
	</div>
</form>
