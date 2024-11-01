<?php
/**
 *
 * @param $items
 */

$items = isset($items) ? $items : array();
?>

<div class="arevico wrap" id="arevico">
	<?php wp_nonce_field(-1, '_arevico_nonce', false); ?>

	<div>
		<i>Make sure the product Ids match those on ClickBank.com</i>
	</div>
		<a href="#" v-on:click="productIds.unshift({meta_id:'', meta_value:''})"> + Add Product ID </a>   

		<div v-for="(item, index) in productIds">
			<input type="hidden" v-bind:name="'arvcb[products][' + index +'][meta_id]'" v-model="item.meta_id">
			<input type="hidden" v-bind:name="'arvcb[products][' + index +'][meta_key]'" value="_arvcb_product">
			<input type="text" v-bind:name="'arvcb[products][' + index +'][meta_value]'" v-model="item.meta_value">

			<a href="#" v-on:click="del(item, index)"> remove </a>
		</div>
		
		<div v-for="(item, index) in deleted">
			<input type="hidden" name="arvcb[deleted][]" id="" v-bind:value="item.id">           
		</div>
</div>



<script>
var App = new Vue({
	el: '#arevico',

	data:{
		deleted: [],
		productIds: <?php echo json_encode($productIds); ?>,
	},

	methods:{
		del: function(item, index){
			if (item.meta_id)
				this.deleted.push( {'id': item.meta_id } );

			this.productIds.splice(index, 1)
		}
	}

})
</script>
