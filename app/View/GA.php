<?php
/**	
 * Render Google Analytics request on download Page
 * 
 * @param Transaction $transaction
 */
 ?>
<script>
ga('ecommerce:addTransaction', {
	'id': 		'<?php echo $transaction->receipt; ?>',                     // Transaction ID. Required.
	'revenue': 	'<?php echo $transaction->transaction->totalAccountAmount; ?>'
});

<?php foreach ($transaction->transaction->lineItems as $item): ?>
	ga('ecommerce:addItem', {
	'id':			'<?php echo $transaction->receipt; ?>',      // Transaction ID. Required.
	'name': 		'<?php echo htmlentities($item->productTitle); ?>',    // Product name. Required.
	'price': 		'<?php echo $item->accountAmount; ?>',                 // Unit price.
	'quantity': 	'<?php echo $item->quantity; ?>'                   // Quantity.
	});
<?php endforeach; ?>
</script>