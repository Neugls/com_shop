<?php
	// NO DIRECT ACCESS
	defined('_JEXEC') or die('Restricted access');
	JHtml::_('behavior.formvalidation');
	// SET DOCUMENT HEAD FOR PAGE
	$doc = JFactory::getDocument();
    $doc->addStyleDeclaration('div.thumb { width: 25%; float: left; }');
    $default_sku = array();
    $default_price = 0;
    foreach($this->options as $options){
    	$default_sku[] = $options[0]->option_sku;
    	$default_price += $options[0]->option_price;
    }
?>
<script type="text/javascript">
//<![CDATA[
	(function() {
		var $, UIView;
		$ = jQuery;
		UIView = (function() {
		    // CONSTRUCTOR METHOD
			function UIView() {
				this.product_sku = '<?php echo $this->data->product_sku; ?>';
				this.product_price = parseFloat(<?php echo $this->data->product_price; ?>);
				$('div.product-thumbnails a.thumbnail').on('click', $.proxy(this.updatePreview, this));
				$('select.product-option').on('change', $.proxy(this.updatePrice, this));
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
			}
		
			UIView.prototype.updatePreview = function(evt) {
				// EVENT METHOD
				evt.preventDefault();
				var target = $(evt.delegateTarget);
				$('#product-preview').attr('src', target.data('src')); 
			}
		
			UIView.prototype.updatePrice = function(evt) {
				// EVENT METHOD
				var target = $(evt.delegateTarget);
				var sku = this.product_sku;
				var price = this.product_price;
				
				$('select.product-option').each(function(i){
					var option = $(this).find(':selected');
					sku += option.data('sku');
					price += parseFloat(option.data('price'));
				});
				$('#jform_product_sku').val(sku);
				$('#product-price').html('$' + String(this.numberFormat(price, 2, '.', ',')));
			}
		
			UIView.prototype.numberFormat = function(number, decimals, dec_point, thousands_sep)
			{
				number = (number + '')
					.replace(/[^0-9+\-Ee.]/g, '');
				var n = !isFinite(+number) ? 0 : +number,
					prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
					sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
					dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
					s = '',
					toFixedFix = function (n, prec) {
						var k = Math.pow(10, prec);
						return '' + (Math.round(n * k) / k)
							.toFixed(prec);
					};
				// Fix for IE parseFloat(0.55).toFixed(0) = 0;
				s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
					.split('.');
				if (s[0].length > 3) {
					s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
				}
				if ((s[1] || '')
					.length < prec) {
					s[1] = s[1] || '';
					s[1] += new Array(prec - s[1].length + 1)
						.join('0');
				}
				return s.join(dec);
			}		
		
		
		
		
		
		
		
		
		
			return UIView;
		})();
	
		$(function() {
			return new UIView();
		});
	}).call(this);

//]]>
</script>
<div class="row-fluid">
	<div class="span6">
	<?php if(isset($this->images[0])){ ?>
		<p><img src="/<?php echo $this->images[0]->image_full; ?>" id="product-preview" alt="" title="" /></p>
		<div class="product-thumbnails">
			<?php if(count($this->images) > 1){ foreach($this->images as $thumb){ ?>
			<div class="thumb">
				<a href="#" class="thumbnail" data-src="/<?php echo $thumb->image_full; ?>">
					<img src="/<?php echo $thumb->image_thumbnail; ?>" alt="<?php echo $thumb->image_alt; ?>" title="" />
				</a>
			</div>
			<?php }} ?>
		</div>
	<?php } ?>
	</div>
	<div class="span6">
		<h1><?php echo $this->data->product_name; ?></h1>
		<?php echo $this->data->product_description; ?>
		<form name="shop" id="shop-product" action="" method="post">
			<input type="hidden" name="option" value="com_shop" />
			<input type="hidden" name="task" value="cart.addToCart" />
			<input type="hidden" name="jform[product_id]" value="<?php echo $this->data->product_id; ?>" />
			<input type="hidden" name="<?php echo JSession::getFormToken(); ?>" value="1" />
			<?php foreach($this->options as $options){ ?>
			<div class="control-group">
				<label for="jform_option-<?php echo $options[0]->option_key; ?>" class="control-label"><?php echo ucfirst($options[0]->option_key); ?></label>
				<div class="controls">
					<select name="jform[options][<?php echo $options[0]->option_key; ?>]" class="product-option" id="jform_option-<?php echo $options[0]->option_key; ?>">
						<?php foreach($options as $option){ ?>
						<option data-price="<?php echo $option->option_price; ?>" data-sku="<?php echo $option->option_sku; ?>" value="<?php echo $option->option_id; ?>"><?php echo $option->option_value; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<?php } ?>
			<div class="control-group">
				<label for="jform_product_sku"><?php echo JText::_('COM_SHOP_PRODUCT_SKU_LABEL'); ?></label>
				<div class="controls">
					<input type="text" name="jform[product_sku]" id="jform_product_sku" readonly value="<?php echo $this->data->product_sku.implode('',$default_sku); ?>" />
				</div>
			</div>
			<div class="btn-group">
				<input type="submit" class="btn btn-primary"  value="<?php echo JText::_('COM_SHOP_BUTTON_ADD_TO_CART'); ?>">
				<button type="button" class="btn btn-primary" id="product-price">$<?php echo number_format($this->data->product_price + $default_price, 2); ?></button>
			</div>
		</form>
	</div>
</div>
