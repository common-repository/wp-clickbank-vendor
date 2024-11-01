<?php
namespace Arevico\CB;

use Arevico\Core\Helper\Migrate;
use Arevico\Core\Helper\Util;

/**
 * Activate the plugin
 * 
 * @version 1.0.0
 * 
 * @property array 	$upgradePath 	upgrade path version => string|array queries
 * @property array 	$createQueries 	All create table queries 	
 * @property string $dbVersionName 	The field where we store our migration info
 */	
class Activate extends Migrate{

	protected $dbVersionName = 'arevico_cb_db';
	protected $pluginDBVersion = 1; 

	/**
	 * 
	 *
	 * @var array
	 */
	protected $upgradePath = array(
	
	);

	protected $createQueries = array(

				/**
				 * The main customer table
				 */
		0 =>	"CREATE TABLE IF NOT EXISTS `cb_customers` (
					`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
					`fullName` VARCHAR(255) NULL,
					`firstName` VARCHAR(255) NULL,
					`lastName` VARCHAR(255) NULL,
					`email` VARCHAR(255) NULL,
					`password` VARCHAR(255) NULL,
					`registered` VARCHAR(20) NULL DEFAULT '',
					`isTest` TINYINT(1) NULL DEFAULT 0,

					PRIMARY KEY (`id`),
					UNIQUE KEY `arv_cb_customers_uq` (`email` ASC))
				ENGINE = InnoDB
				DEFAULT CHARACTER SET = utf8;",

				/**
				 * Access table
				 */
		1 => 	"CREATE TABLE IF NOT EXISTS `cb_access` (
					`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
					`customerId` BIGINT(20) NULL,
					`productId` VARCHAR(255) NULL,
					`receipt` VARCHAR(255) NULL,

					`datePurchased` VARCHAR(255) NULL,
					`access` TINYINT(1) NULL DEFAULT 1,
					`recurring` TINYINT(1) NULL DEFAULT 0,
		
					PRIMARY KEY (`id`),
					UNIQUE KEY `arv_cb_access_uq` (`productId` ASC, `receipt` ASC))

				ENGINE = InnoDB
				DEFAULT CHARACTER SET = utf8;",
		
				/**
				 * Transaction table
				 */
		2 => 	"CREATE TABLE IF NOT EXISTS `cb_transactions` (
					`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
					`customerId` BIGINT(20) UNSIGNED NOT NULL,
					`transactionTime` TEXT NULL,
					`receipt` VARCHAR(255) NULL ,
					`transactionType` VARCHAR(255) NULL ,
					`currency` VARCHAR(255) NULL,
					`totalOrderAmount` VARCHAR(255) NULL,
					`totalTaxAmount` VARCHAR(255) NULL,
					`transaction` MEDIUMBLOB NULL,

					PRIMARY KEY (`id`),
					UNIQUE KEY `arv_cb_customers_uq` (`customerId`, `receipt` ASC))

				ENGINE = InnoDB
				DEFAULT CHARACTER SET = utf8;",

	);
	
	/**
	 * Activation Event
	 *
	 * @param boolean $networkWide
	 * @return void
	 */
	public function activate($networkWide){
		// $this->drop('cb_transaction', 'cb_access', 'cb_customers', 'cb_transactions'); // For Testing
		$this->migrate();

		if ($this->isFirstTimeActivation()){
			$this->populateDefaults();
			$this->insertFirstDownloadPage();			
		}

		flush_rewrite_rules( true );		
	}

	private function isFirstTimeActivation(){
		$this->options = $options = get_option( 'arvcbOptions', null );

		return $options === null;
	}	

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private function populateDefaults(){
		$default = array ( 
			'cb' => array (
				'secretKey' => Util::randomString(20), 
				'vendor' 	=> 'arevico', 
				'allowTest' => '1', 
				'allowAdmin' => '1'), 
			'slug' => 'download');
		
		update_option( 'arvcbOptions', $default);
	}


	private function insertFirstDownloadPage(){
		$post = array (
			'post_author' => '1',
			'post_type'   => 'download-pages',
			'post_content' => '<p style="text-align: center;"><strong>Thank you for purchasing \'Example Product\'. This page contains important details regarding your purchase.</strong></p>
		  You can download the product by clicking here
		  <p style="text-align: center;"><span style="color: #ff0000;">[<em>note to administrator: insert downloadable files via Add Media</em>]</span></p>
		  You can use this page to pitch additional products or collect email addresses as well.
		  
		  If you are note sure how to get started with selling online with this product, please read this guide: "<a href="http://arevico.com/wp-clickbank-vendor-getting-started/">Getting Started With WP ClickBank Vendor" </a>first.
		  
		  Frequently Asked Question:
		  <div>
		  <div>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolorem, ducimus consequatur impedit hic illo quas placeat eius libero expedita, harum sapiente mollitia et quasi laborum nisi suscipit. Blanditiis, consequatur deserunt?</div>
		  </div>
		  <h3>How Do I Install This Product?</h3>
		  <div>
		  <div>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolorem, ducimus consequatur impedit hic illo quas placeat eius libero expedita, harum sapiente mollitia et quasi laborum nisi suscipit. Blanditiis, consequatur deserunt?</div>
		  </div>
		  <h3>What about this?</h3>
		  <div>
		  <div>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolorem, ducimus consequatur impedit hic illo quas placeat eius libero expedita, harum sapiente mollitia et quasi laborum nisi suscipit. Blanditiis, consequatur deserunt?</div>
		  </div>
		  <h3>What About That?</h3>
		  <div>
		  <div>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolorem, ducimus consequatur impedit hic illo quas placeat eius libero expedita, harum sapiente mollitia et quasi laborum nisi suscipit. Blanditiis, consequatur deserunt?</div>
		  </div>',
			'post_title' => 'Example - Thank You Page',
			'post_status' => 'publish',
			'comment_status' => 'open',
			'post_password' => '',
			'post_name' => 'example-thank-you-page',
		);
		
		$postId = wp_insert_post( $post );
		add_post_meta($postId,'_arvcb_product', '1'); 
	}
}	