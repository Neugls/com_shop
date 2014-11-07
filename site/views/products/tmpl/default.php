<?php
	// NO DIRECT ACCESS
	defined('_JEXEC') or die('Restricted access');
	// SET DOCUMENT HEAD FOR PAGE
	$doc = JFactory::getDocument();
	$chunks = array_chunk($this->items, 4);
?>

<?php foreach($chunks as $row){ ?>
<div class="row-fluid">
	<?php foreach($row as $product){ ?>
	<div class="thumbnail span3">
		<a href="<?php echo JRoute::_('index.php?option=com_shop&view=products&layout=detail&id='.$product->product_slug); ?>">
			<img src="<?php echo $product->image_thumbnail; ?>" alt="<?php echo $product->image_alt; ?>" title="<?php echo $product->image_title; ?>" />
			<h5><?php echo $product->product_name; ?></h5>
		</a>
	</div>
	<?php } ?>
</div>
<?php } ?>
