<?php
namespace Arevico\CB\Controller\Admin;

use Arevico\Core;
use Arevico\CB\View as View;

use Arevico\Core\Helper\Util;
use Arevico\Core\Helper\Error;
use Arevico\CB\Model;

/**
 * Allows the admin to add or edit a new customer
 * 
 * @version 1.0.0
 * 
 * @property Core\Genesis $genesis
 */
class CustomerEdit extends Core\Controller{

	/** @var Error */
	protected $error;

	/**	
	 * @var Model\Customer
	 */
	protected $currentCustomer = array();

	/**	@var array $currentCustomer an object which the view can use */
	protected $newCustomer 	   = array();

	/** Posted data by the admin */
	protected $productAccess;
	protected $newProductAccess;

	/** We need to pass the delete items back to the view if validation fails */
	protected $productAccessToDelete = array();

	/** Indicated if the password was changed*/
	protected $passwordChanged = false;

	/** @var View */
	protected $view;
	
	/**
	 * Render edit customer view
	 *
	 * @return void
	 */
	public function render(){
		// * Get Customer
		$id = $this->getVar('id', null);
		$this->currentCustomer 	= Model\Customer::get( $id);
		$this->newCustomer 		= $this->postVar('o->customer', array());
		
		if (!$this->currentCustomer)
		wp_die('User not found!');
		
		$this->view = $view = $this->genesis->view('Admin/CustomerEdit');

		/** Get ProductAccess*/
		$this->productAccess 		 = Model\ProductAccess::listByCustomer($id);

		$this->productAccessToDelete = $this->postVar('o[deleted]', array());
		$this->newProductAccess 	 = $this->postVar('o[productAccess]', array());
		
		if (Util::isPost())	
			$this->save($view);

		$this->setViewData($view);
		$view->render();
	}

	/**
	 * Pass data to the view
	 *
	 * @param View $view
	 * @return void
	 */
	private function setViewData($view){
		$view->with(
			'action', 'edit',
			'id', $this->currentCustomer->id,
			'customer', Util::isPost() ? $this->newCustomer : $this->currentCustomer->data,
		
			'productAccess', 		Util::isPost() ? $this->newProductAccess : $this->productAccess,
			'productAccessToDelete', $this->productAccessToDelete,
			'error', 			$this->error,

			'productIds', 		json_encode(Model\ProductInformation::listAll())
		);	
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function save($view){
		global $wpdb;

		/* 1. Validate */
		if (!$this->validateCustomer($this->newCustomer) )
			return false;

		// * 1. Update Customer 
		$this->newCustomer 	=	$customer 	= new Model\Customer($this->newCustomer);	
		$customer->id 		= $this->currentCustomer->id;
		$this->processPassword($customer);
		$customer->save();
	
		// * 2. Update Product Access
		Model\ProductAccess::massDelete($this->productAccessToDelete);

		foreach ($this->newProductAccess as &$productAccess){
			$productAccess = new Model\ProductAccess($productAccess);
			$productAccess->customerId = $this->currentCustomer->id;
			$productAccess = $productAccess->save()->getData();
		}

		$this->view->with('notice', 'Customer is successfully updated!');
	}
	

	/* Validate & Sanitize
	 ----------------------------------------------------- */
	/**
	 * If a new password is provided, we process it and save it. Indicate that password has changed
	 *
	 * @param Model\Customer $customer
	 * @return void
	 */
	 public function processPassword($customer){

	 }

	/**
	 * Validate customer data
	 *
	 * @param array $customer
	 * @return Helper\Error
	 */
	public function validateCustomer($customer){
		$customer = (object)$customer;
		$this->error = new Error();

		if (Util::isEmpty($customer, 'email')){
			$this->error->add('email', 'Email address cannot be empty!');

		} else{
			if (Model\Customer::otherExistsByEmail($this->currentCustomer->id, $customer->email))
				$this->error->add('email', 'User with this email already exists!');
		}
		return !$this->error->hasError();
	}

	/* Construct / Get / Set
	 ----------------------------------------------------- */	
	public function assets(){
		parent::assets();
		wp_enqueue_script('arv-vue', $this->registry->get('CORE_PUBLIC') . '/vue/vue.js' );
		// wp_enqueue_script('arv-vue', $this->registry->get('CORE_PUBLIC') . '/vue/vue.runtime.min.js' );

	}	

	public function __construct(){
		parent::__construct();
		$this->error 	= new Error();
	}
}
