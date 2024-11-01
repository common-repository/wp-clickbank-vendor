<?php
namespace Arevico\CB\Model;

use Arevico\Core;

/**
 * Contains details if customers has access to a specific product
 * 
 * @version 1.0.0
 * 
 * @property mixed $id
 * @property mixed $customerId
 * @property mixed $receipt
 * @property mixed $datePurchased
 * 
 */
class ProductAccess extends Core\DBModel{

	protected $fields = array(
		"id",
		"customerId",
		"productId",
		"receipt",
		"datePurchased",
		"access",
		"recurring"
	);

	/**
	 * Undocumented function
	 *
	 * @param int $id 
	 * @return ProductAccess
	 */	
	public static function listByCustomer( $id ){
		global $wpdb; 
		$id = preg_replace("/[^0-9,.]/", "", $id);
		return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cb_access WHERE customerId='{$id}'", ARRAY_A);
	}
	
	/**
	 * Enable product access
	 *
	 * @param integer $customerId
	 * @param integer $item
	 * @return void
	 */
	public static function enableAccess($customerId, $item){
		global $wpdb;
		return $wpdb->update($this->getTable(), 
			array('access' => true), 
			array(
				'customerId' => $customerId, 
				'productId'  => $item) 
			);	
	}

	/**
	 * Disable product access
	 *
	 * @param integer $customerId
	 * @param integer $item
	 * @return void
	 */
	public static function disableAccess($customerId, $item){
		global $wpdb;

		return $wpdb->update($this->getTable(), 
			array('access' => false), 
			array(
				'customerId' => $customerId, 
				'productId'  => $item) 
			);
	}

	/* Constructors, Getters and Settingss
	 ----------------------------------------------------- */
	public function __construct( $productAccess = array()){
		parent::__construct('cb_access');
		$this->setData($productAccess);
	}	


}