<?php
/**
 * View to Add / Edit A User
 *
 * @version 1.0.0
 *
 * @param array $customer customer basic information to render
 * @param array $productAccess an array of all products to render which a user has access to
 */
namespace Arevico\CB\View\Admin;

use Arevico\Core\Helper\Util;
use Arevico\Core\View as View;

$action     = isset($action) ? $action : 'add';
$idField    = isset($id) ? 'id='. $id : '';
$page       = $action == 'add' ? 'arvcb-customer-add' : 'arvcb-customer-edit';

?>
<div class="wrap arevico" id="arevico" v-cloak>

    <form method="POST" action="admin.php?page=<?php echo $page?>&<?php echo $idField;?>">

    <?php if (isset($notice)) : ?>
        <div class="arevico-notice fade-out">
            <?php echo $notice; ?>
        </div>
    <?php endif; ?>

    <h1 class="wp-heading-inline"><?php echo ucfirst($action);?> Customer</h1>
    <a href="?page=arvcb-customer-add" class="page-title-action">Add New</a>

    <div class="grid-1000">
        <?php wp_nonce_field(-1, '_arevico_nonce', false); ?>

        <!-- Email -->      
        <div class="row">
            <div class="col2 label">Email</div>
            <div class="col10">
                <input type="email" name="o[customer][email]" value="<?php Util::safe($customer, 'email'); ?>">
                <?php $error->render('email'); ?>
            </div>  
        </div>

        <!-- First Name -->     
        <div class="row">
            <div class="col2 label">First Name</div>
            <div class="col10">
                <input type="text" name="o[customer][firstName]" value="<?php Util::safe($customer, 'firstName'); ?>">
            </div>  
        </div>

        <!-- Last Name -->      
        <div class="row">
            <div class="col2 label">Last Name</div>
            <div class="col10">
                <input type="text" name="o[customer][lastName]" value="<?php Util::safe($customer, 'lastName'); ?>">
            </div>  
        </div>

        <!-- Full Name -->      
        <div class="row">
            <div class="col2 label">Full Name</div>
            <div class="col10">
                <input type="text" name="o[customer][fullName]" value="<?php Util::safe($customer, 'fullName'); ?>">
            </div>  
        </div>

        <!-- Password -->
        <!-- <div class="row">
            <div class="col2 label">Password</div>
            <div class="col10">
                <input type="password" name="o[customer][password]" autocomplete="new-password" value="<?php //Util::safe($customer, 'password'); ?>">
                <div class="explain">
                <?php if ($action == 'add') : ?>
                    <strong>Tip:</strong> When creating a new password, enter the receipt of the first product purchased!
                <?php elseif ($action == 'edit') : ?>
                    <strong>Tip:</strong> Leave empty when you don't want to update the password.
                <?php endif; ?>
                </div>
            </div>  
        </div> -->
    </div> <!-- end Grid-->

    <!-- Product Access Details -->
    <div id="product-access-repeat">
        <h3 class="wp-heading-inline">Product Access</h3>
        <a href="#" class="page-title-action" v-on:click="ProductAccess.unshift({id:'', access:1, recurring:0 })">Add New</a>
  
    <div v-for="(item, index) in ProductAccess" v-cloak>

        <input  v-bind:name="'o[productAccess][' + index + '][id]'" type="hidden"  v-bind:value="item.id"/>
        <input placeholder="receipt" v-bind:name="'o[productAccess][' + index + '][receipt]'" type="text" v-model="item.receipt" />
        <input placeholder="Product" v-bind:name="'o[productAccess][' + index + '][productId]'" type="text" v-model="item.productId" list="productIds"/>

        <select v-bind:name="'o[productAccess][' + index + '][access]'" style="" id="" v-model="item.access">
            <option value="1">Access</option>
            <option value="0">No Access</option>
        </select>

        <select v-bind:name="'o[productAccess][' + index + '][recurring]'" style="" id="" v-model="item.recurring">
            <option value="1">Recurring Payment</option>
            <option value="0">One - Time Payment</option>
        </select>
        
        <a href="" v-on:click.stop.prevent="del(item, index)"> Delete </a>
    </div>
    <!-- /Product access -->

    <!-- Save Button -->
    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
        <a href="admin.php?page=<?php echo $page?>&<?php echo $idField;?>">cancel</a>
    </p>

    </div>

    <div v-for="item in deleted">
        <input type="hidden" name="o[deleted][]" v-bind:value="item.id">
    </div>
</form>

	<datalist id="productIds">
		<option v-for="(items, index) in productIds" v-bind:value="items.meta_value">{{ items.post_title }}</option>
	</datalist/>
</div>

<script>
    var RepeatPA = new Vue({
        el: "#arevico",
        data:{
            deleted:        [],
            ProductAccess:  <?php echo json_encode($productAccess); ?>,
			productIds:	<?php echo $productIds; ?>
        },

        methods:{
                /**
                 * 
                 */
                del: function(item, index){
                    this.ProductAccess.splice(index, 1); 

                    if (item.id != '')
                        this.deleted.push({id:item.id})    
                }

            }

    })
</script>
