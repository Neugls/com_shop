<?php
	defined('_JEXEC') or die('Restricted access');
	JHtml::_('behavior.modal');
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.formvalidation');
    $saveOrderingUrl = 'index.php?option=com_shop&task=images.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'image-table', 'adminForm', 'asc', $saveOrderingUrl);
    $doc = JFactory::getDocument();
    $doc->addStyleDeclaration('div.thumb { width: 20%; float: left; }');
?>

<script type="text/javascript">
//<![CDATA[
	(function() {
		var $, UIView;
		$ = jQuery;
		UIView = (function() {
		    // CONSTRUCTOR METHOD
			function UIView() {
				$('div.product-thumbnails a.thumbnail').on('click', $.proxy(this.updatePreview, this));
				$('div.btn-toolbar button.action').on('click', $.proxy(this.handleAction, this));
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
		
			UIView.prototype.updatePreview = function(evt) {
				// EVENT METHOD
				evt.preventDefault();
				var target = $(evt.delegateTarget);
				$('#product-preview').attr('src', target.data('src')); 
			}
		
			UIView.prototype.handleAction = function(evt) {
				// EVENT METHOD
				evt.preventDefault();
				var target = $(evt.delegateTarget);
				var task = target.data('task');
				var nonce = $('#nonce-token').prop('name');
				var selected = $('input[name="cid[]"]:checked').map(function(){ return this.value; }).get();
				var postData = {};
				if(selected.length < 1){
			        $('#system-message-container').append('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><h4 class="alert-heading">Error</h4><p><?php echo JText::_("COM_SHOP_NO_ITEM_SELECTED"); ?></p></div>');
				    return false;
				}
				switch(task){
				case "images.edit":
				    break;
				case "images.delete":
				    postData["option"] = "com_shop";
				    postData["tmpl"] = "component";
				    postData["cid"] = selected;
				    postData["task"] = task;
				    postData[nonce] = "1";
				    window.console.log(postData);
				    $.post("<?php echo JRoute::_('index.php'); ?>", postData, this.receiveActionResponse, "json" );
				    break;
				}
			}
		
			UIView.prototype.receiveActionResponse = function(data) {
				// AJAX METHOD
				if(typeof data == "object"){
				    var message;
				    var message_type;
				    var alert_type;
				    if(data.status){
				        message = data.message;
				        message_type = "Success";
				        alert_type = "success";
				        for(var i=0; i < data.targets.length; i++){
				            var mark = 'tr[data-id="'+data.targets[i]+'"]';
				            $(mark).remove();
				        }
				    }
				}else{
				    // THERE WAS AN ERROR
				    message = "<?php echo JText::_('COM_SHOP_MSG_ERROR_IMAGE_DELETE'); ?>";
				    message_type = "Warning";
				    alert_type = "error";
				}
			    $('#system-message-container').append('<div class="alert alert-'+alert_type+'"><button type="button" class="close" data-dismiss="alert">&times;</button><h4 class="alert-heading">'+message_type+'</h4><p>'+message+'</p></div>');
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
	<input type="hidden" name="<?php echo JSession::getFormToken(); ?>" value="1" id="nonce-token" />
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
		        <div class="well">
                <?php foreach($this->form->getFieldset('images') as $field) echo $field->renderField(); ?>
                </div>
                <?php if(isset($this->images[0])){ ?>
                    <p><img src="/<?php echo $this->images[0]->image_full; ?>" id="product-preview" alt="" title="" /></p>
                    <div class="product-thumbnails">
                        <?php foreach($this->images as $thumb){ ?>
                        <div class="thumb">
                            <a href="#" class="thumbnail" data-src="/<?php echo $thumb->image_full; ?>">
                                <img src="/<?php echo $thumb->image_thumbnail; ?>" alt="<?php echo $thumb->image_alt; ?>" title="" />
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                <?php } ?>
		    </div>
		    <div class="span8">
		        <div class="btn-toolbar"><button class="btn btn-mini btn-danger action" data-task="images.delete"><i class="icon-delete"></i> <?php echo JText::_('COM_SHOP_IMAGE_ACTION_LABEL'); ?></button></div>
		        <table class="table table-striped" id="image-table">
		            <thead>
		                <tr>
		                    <th class="center"><i class="icon-menu-2"></i></th>
		                    <th class="center"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" /></th>
		                    <th><?php echo JText::_('COM_SHOP_IMAGE_THUMBNAIL_LABEL'); ?></th>
		                    <th><?php echo JText::_('COM_SHOP_IMAGE_SOURCE_LABEL'); ?></th>
		                </tr>
		            </thead>
		            <tbody>
                    <?php $i=0;foreach($this->images as $img){ ?>
                    <?php $checked	= JHtml::_('grid.id', $i, $img->image_id); $i++; ?>
                        <tr data-id="<?php echo $img->image_id; ?>">
                            <td class="order center ">
                                <span class="sortable-handler"><i class="icon-menu"></i></span>
						        <input type="text" style="display:none" name="order[]" size="5" value="<?php echo $img->ordering;?>" class="width-20 text-area-order " />
                            </td>
                            <td class="center"><?php echo $checked; ?></td>
                            <td><img src="/<?php echo $img->image_thumbnail; ?>" title="" alt="" /></td>
                            <td><?php echo $img->image_source; ?></td>
                        </tr>
                    <?php } ?>
		            </tbody>
		        </table>
		    </div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
	    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'options', JText::_('COM_SHOP_FORM_LEGEND_OPTIONS', true)); ?>
		<div class="row-fluid">
		    <div class="span4">
		    	<fieldset class="well">
		        <div class="btn-toolbar"><button class="btn btn-mini btn-success action" data-task="option.save"><i class="icon-apply"></i> <?php echo JText::_('COM_SHOP_OPTION_ACTION_LABEL'); ?></button></div>
		    	<?php
		    	/*
		    	foreach($this->optionsForm->getFieldset('base') as $field){
		    		echo $field->renderField();
		    	}
		    	foreach($this->optionsForm->getFieldset('hidden') as $field){
		    		echo $field->renderField();
		    	}
		    	*/
		    	?>
		    	</fieldset>
		    </div>
		    <div class="span8">
		    </div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>
</form>
