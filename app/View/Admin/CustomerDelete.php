<?php 
/**
 * Confirmation before deleting a customer
 * 
 * @version 1.0.0
 */
$nonce = wp_create_nonce( -1 );
?>
<form action="<?php echo admin_url("admin.php?page=arvcb-customers"); ?>" method="post">
	<div class="wrap arevico">
		<h1>Deleting Customer</h1>	
		<p>
			You are about to delete te following customer and all his or her transactions. This actions is not reversible.
		</p>
		<p>
			<strong style="display:inline-block;width:100px;">Full Name:</strong><?php echo $customer->fullName;?><br />
			<strong style="display:inline-block;width:100px;">First Name:</strong><?php echo $customer->firstName;?><br />
			<strong style="display:inline-block;width:100px;">Last Name:</strong><?php echo $customer->lastName;?><br />
			<strong style="display:inline-block;width:100px;">Email:</strong><?php echo $customer->email;?><br />
			<strong style="display:inline-block;width:100px;">ID:</strong><?php  echo $customer->id;?><br />
		</p>
		<p>
			<strong>Are you sure you want to do this?</strong>
		</p>
	
		<input type="hidden" name="action" value="delete">
		<input type="hidden" name="id" value="<?php echo $customer->id; ?>">
		<?php wp_nonce_field( -1, '_arevico_nonce', false); ?>
		
		<button type="submit">Delete Customer</button> or <a href="<?php echo admin_url( 'admin.php?page=arvcb-customers'); ?>">Cancel</a>
	</div>
</form>