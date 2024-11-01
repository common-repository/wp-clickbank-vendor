<?php
namespace Arevico\CB\Model;

use Arevico\Core;
use Arevico\Core\Helper;
use Arevico\CB\Model;

/**
 * Customer Representation
 * 
 * @var integer $id
 * @var string $fullName
 * @var string $firstName
 * @var string $firstName
 * @var string $lastName
 * @var string $email
 * @var string $password
 * @var string $registered
 */
class Customer extends Core\DBModel{

	/**
	 * 
	 * @return Helper\QueryBuilder
	 */	
	public static function all(){
		global $wpdb;
		
		$q = new Helper\QueryBuilder();	

		return $q->select('customer.*')
					->from("{$wpdb->prefix}cb_customers customer");
	}
		
	/** 
	 * @var ProductAccess[]
	 * */
	public $productAccess 	= null;

	/**
	 * Indicating if we just created the user or if we retrieved it`
	 *
	 * @var boolean
	 */
	public $isNew 	= false;

	/* Constructor / Genesis Methods
	 ----------------------------------------------------- */
	/**
	 * Return a new customer object when the receipt and id matches an entry
	 *
	 * @param string $receipt
	 * @param string $id
	 * @return Customer
	 */
	public static function getByReceiptItem($receipt, $id){
		global $wpdb;
		$sql 	= $wpdb->prepare("SELECT * 	FROM {$wpdb->prefix}cb_customers customer 
											JOIN {$wpdb->prefix}cb_access access ON customer.id = access.customerId
								  WHERE access.receipt=%s and access.productId=%s", $receipt, $id);
		$result = $wpdb->get_results($sql, OBJECT);

		if (empty($result))
			return null;
		
		$customer 	= new Customer();
		$customer->setData($result[0]);
		return $customer;
	}

	public static function otherExistsByEmail($id, $email){
		global $wpdb;
		$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}cb_customers WHERE email=%s AND NOT id=%s", $email, $id));
		return !empty($results);
	}

	public function setProductAccess($productAccess){
		$this->productAccess = $productAccess;
	}

	public function isNew(){
		return $this->isNew;
	}	

	public function getProductAccess(){
		if ($this->productAccess)
			return $this->productAccess;

		return $this->productAccess = ProductAccess::listByCustomer($this->id);
	}

	public function hasAccess($productId){
		$access = $this->getProductAccess();		
	}
		
	public function __construct($data = array()){
		parent::__construct("cb_customers");
		$this->setData($data);
	}

	public static function listTestCustomers(){
		global $wpdb;
		$class 		= get_called_class();
	
		/** @var self  */
		$instance 	= new $class();
		$table 		= $instance->getTable();

		$sql 	= "SELECT * FROM {$table} WHERE isTest=\"1\"";
		return($wpdb->get_results($sql));
	}

	public function delete(){
		global $wpdb;

		/* Delete Foreign Keys */		
		Model\ProductAccess::deleteBy('customerId', $this->id);
		Model\Transaction::deleteBy('customerId', $this->id);

		/* Delete Cusomer*/
		parent::delete();
	}
}
