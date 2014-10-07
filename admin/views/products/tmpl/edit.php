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
					if(sometask != 'products.cancel'){
						if(re_blank.test($('#jform_product_alias').val())){
							$('#jform_product_alias').val($('#jform_product_name').val().replace(/\W/g, '-').toLowerCase());
						}
						if(!document.formvalidator.isValid(someForm)){
							return false;
						}
					}
					<?php echo $this->form->getField('product_description')->save(); ?>
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
	<input type="hidden" name="product_id" value="<?php echo $this->form->getValue('product_id'); ?>" />
	<?php echo JHtml::_('form.token')."\n"; ?>
	<div id="editcell">
	    <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'base')); ?>
	    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'base', JText::_('COM_SHOP_FORM_LEGEND_BASIC', true)); ?>
	    <div class="row-fluid">
            <div class="span3">
                <fieldset class="adminform">
                    <?php foreach($this->form->getFieldset('base') as $field){ ?>
                        <div class="control-group">
                            <?php echo $field->label; ?>
                            <div class="controls"><?php echo $field->input; ?></div>
                        </div>
                    <?php } ?>
                </fieldset>
            </div>
            <div class="span3">
                <fieldset class="adminform">
                    <?php foreach($this->form->getFieldset('options') as $field){ ?>
                        <div class="control-group">
                            <?php echo $field->label; ?>
                            <div class="controls"><?php echo $field->input; ?></div>
                        </div>
                    <?php } ?>
                </fieldset>
            </div>
            <div class="span3">
                <fieldset class="adminform">
                    <?php foreach($this->form->getFieldset('params') as $field){ ?>
                        <div class="control-group">
                            <?php echo $field->label; ?>
                            <div class="controls"><?php echo $field->input; ?></div>
                        </div>
                    <?php } ?>
                </fieldset>
            </div>
            <div class="span3">
                <fieldset class="adminform">
                    <?php foreach($this->form->getFieldset('metadata') as $field){ ?>
                        <div class="control-group">
                            <?php echo $field->label; ?>
                            <div class="controls"><?php echo $field->input; ?></div>
                        </div>
                    <?php } ?>
                </fieldset>
            </div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
	    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'description', JText::_('COM_SHOP_FORM_LEGEND_HTML', true)); ?>
		<div class="row-fluid">
		    <div class="span12">
				<?php echo $this->form->getLabel('product_description'); ?>
				<div class="clr"></div>
				<?php echo $this->form->getInput('product_description'); ?>
		    </div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
	    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'images', JText::_('COM_SHOP_FORM_LEGEND_IMAGES', true)); ?>
		<div class="row-fluid">
		    <div class="span4">
		    </div>
		    <div class="span8">
		        <table class="table">
		        </table>
		    </div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
	    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'options', JText::_('COM_SHOP_FORM_LEGEND_OPTIONS', true)); ?>
		<div class="row-fluid">
		    <div class="span12">
		    </div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>
</form>
