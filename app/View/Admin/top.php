<?php
/**
 * Render the main option views
 * 
 * @version 1.0.0
 * 
 * @param array $o Options
 */
use Arevico\Core\Helper\Util;

?>


<div class="wrap arevico" id="arevico" v-cloak>
	<!-- <h1>ClickBank Options</h1> -->
	<h2 class="nav-tab-wrapper">
		<a v-bind:class="{ 'nav-tab-active': tab=='ClickBank' }" v-on:click.prevent="tab='ClickBank'" href="#" class="nav-tab" >ClickBank</a>
	</h2>
	<br />

	<form action="" method="POST" clas="grid-1000">

		<div v-show="tab=='ClickBank'">
			<div class="row">
				<div class="col2 label">Secret Key</div>
				<div class="col10">
					<input type="text" name="o[cb][secretKey]" value="<?php Util::safe($o, 'cb->secretKey'); ?>" />
					<div class="explain">
						See the <a href="https://arevico.com/wp-clickbank-vendor-getting-started/" target="_new">getting started guide</a> !
					</div>
				</div>
			</div>
		
			<!-- IPN Url-->
			<div class="row">
				<div class="col2 label">IPN Url</div>
				<div class="col10">
					<div style="font-weight:bold;">
						<?php echo home_url() . '/ipn/cb/'; ?>
					</div>
					<div>
						<div class="explain">Paste this url on <a href="http://clickbank.com" target="_new">clickbank.com</a> under "My Site" -> "Advanced Tools" -> "Instant Notification URL",<br /> make sure encryption is not checked and IPN Version 6 is selected.</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col2 label">Download Page Prefix</div>
				<div class="col10">
					<input type="text" name="o[slug]" value="<?php Util::safe($o, 'slug'); ?>" />
					<div class="explain">WordPress requires a slug for custom post types, defaults to: download </div>
				</div>
			</div>		

			<!-- Vendor Name -->
			<div class="row">
				<div class="col2 label"> Vendor </div>
				<div class="col10">
					<input type="text" name="o[cb][vendor]" value="<?php Util::safe($o, 'cb->vendor'); ?>" />
					<div class="explain">
						By entering the vendor, we will display the ClickBank 'Trust Badge' automatically on pages containing a payment link.
					</div>
				</div>
			</div>		

			<div class="row">
				<div class="col2 label"> Google E-Commerce </div>
				<div class="col10">
					<input type="checkbox" name="o[cb][ga]" id="" value="1" <?php checked(Util::val($o, 'cb->ga'),1); ?>/> Track sales in Google Analytics
					<div class="explain">Make sure the main <a target="_new" href="https://support.google.com/analytics/answer/1008080">Google Analytics Tracking code</a> is added.</div>

				</div>
			</div>		

			<div class="row">
				<div class="col2 label"> Test Data </div>
				<div class="col10">
					<input type="checkbox" name="o[cb][allowTest]" id="" value="1" <?php checked(Util::val($o, 'cb->allowTest'),1); ?>/> Enable Test Transactions
				</div>
			</div>		

			<div class="row">
				<div class="col2 label"> Administrator </div>
				<div class="col10">
					<input type="checkbox" name="o[cb][allowAdmin]" id="" value="1" <?php checked(Util::val($o, 'cb->allowAdmin'),1); ?>/> Allow administrator to view download pages without login
				</div>
			</div>		

		</div>


				
		<!-- Save Button -->
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
	</form>
</div>


<script>
var App = new Vue({
	el: '#arevico',
	data: {
		tab: 'ClickBank'
	}
})
</script>