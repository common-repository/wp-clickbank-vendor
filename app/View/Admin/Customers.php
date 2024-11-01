<?php
/**
 *  Render The customer overview
 * 
 * @version 1.0.0
 * 
 * @property \Arevico\Core\Table $table;
 * @property string $notice
 */
?>
<div class="wrap arevico">
	
	<?php if (isset($notice)) :?>
		<div class="arevico-notice fade-out">
			<?php echo $notice; ?>
		</div>
	<?php endif; ?>

	<h1 class="wp-heading-inline">Customers</h1>
	<a href="?page=arvcb-customer-add" class="page-title-action">Add New</a>

	<?php $table->render(); ?>
</div>