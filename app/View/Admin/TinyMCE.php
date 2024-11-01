<style>
	/* Vue Modal */
	.modal-background,.modal-wrapper{transition:all .3s ease;position:fixed;top:0;left:0;width:100%;height:100%}.modal-background{display:block;background-color:rgba(0,0,0,.5);z-index:10001}.modal-content,.modal-wrapper:before{vertical-align:middle;display:inline-block}.modal-wrapper{text-align:center;white-space:nowrap;z-index:10002}.modal-wrapper:before{content:'';height:100%}.modal-content{z-index:10003;position:relative;text-align:left;overflow:visible;padding:25px 25px 0;border-bottom:25px solid transparent;margin-left:5%;margin-right:5%;max-height:95%;background:#fff;cursor:auto;white-space:normal;border-radius:2px;box-shadow:0 2px 8px rgba(0,0,0,.33)}.modal-close{position:absolute;width:30px;height:30px;top:-15px;right:-15px;
		background:url(data:image/png;base64,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)}@media only screen and (max-width:1024px){.modal-wrapper .modal-content{margin-left:0;margin-right:0;max-height:98%;padding:10px 10px 0;border-bottom:10px solid transparent}}.modal-wrapper,.modal-wrapper *{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}.modal-enter,.modal-leave-active{opacity:0}.modal-enter .modal-content,.modal-leave-active .modal-content{-webkit-transform:scale(1.1);transform:scale(1.1)}

	[v-cloak]{display:none;}
</style>

<div id="arevico-vue">
	<modal ref="arv-modal" v-cloak>
		<h2>
			Insert Payment Link
		</h2>
		<!-- Tab 1 -->
			<div style="width:100;max-width:400px">
			Product ID: <input type="text" v-model="paymentLink.productid" style="max-width:600px" list="productIds"/>
				<datalist id="productIds">
					<option v-for="(item, index) in productIds" v-bind:value="item.meta_value"> {{ item.post_title }}</option>
				</datalist><br>&nbsp; <br>
				<div>
					Prefered Payment Method
					<select v-model="paymentLink.paymentmethod">
						<option value="">Automatic</option>
						<option value="pypl">PayPal</option>
					</select>
				</div>
				<div><input type="checkbox" true-value="1" false-value="0" v-model="paymentLink.newtab"> Open link in new tab</div>
				<div>
					Order Form: <input type="text" v-model="paymentLink.cbskin">
				</div>
				
			</div>

			<button @click="insertPayShortcode()"> - Insert - </button>
	</modal>
</div>


<script type="text/x-template" id="modal-template">
	<transition name="modal">
		<div v-show="visible" class="modal-wrapper">			
			<div class="modal-content">
				<slot></slot>		
				<a href="" class="modal-close" v-on:click.prevent="visible=false"></a>										
			</div>
			
			<a href="#" class="modal-background" v-on:click.prevent="visible=false"></a>	
		</div>

	</transition>
</script>
<script>
Vue.component('modal', {
	template: '#modal-template',
	
	data: function() {
		return {
			visible: false
		}
	},

	methods: {
		
		show: function(){
			this.visible=true
		},
		
		hide: function(){
			this.visible=false
		},
	}

});

var ArvCB = new Vue({
	el: "#arevico-vue",
	
	data: { 
		tab : 1,
		paymentLink:{
			productid:'',
			cbskin:'',	
			newtab:'',
			payment: ''
		},
		productIds: <?php echo $productIds; ?>
	},

	methods:{
		
		/**
		 * 
		 */
		insertPayShortcode: function(){
			var ed = tinyMCE.activeEditor;
			
			var attributes = ' productid="'+ this.paymentLink.productid + '" ';
			if (this.paymentLink.newtab) attributes = attributes + 'newtab="'+ this.paymentLink.newtab + '" '
			if (this.paymentLink.paymentmethod) attributes = attributes + 'paymentmethod="'+ this.paymentLink.paymentmethod + '" '
			if (this.paymentLink.cbskin) attributes = attributes + 'cbskin="'+ this.paymentLink.cbskin + '" '
			
			ed.execCommand( 'mceInsertContent', false, 
				'[pay ' + attributes +']' + ed.selection.getContent() + '[/pay]'
				)
			this.$refs['arv-modal'].hide();			
		}
	}
});

</script>