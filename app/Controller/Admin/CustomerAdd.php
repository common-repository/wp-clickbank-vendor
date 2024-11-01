<?php
namespace Arevico\CB\Controller\Admin;
use Arevico\Core;
use Arevico\Core\Helper\Util;
use Arevico\Core\Helper\Error;

use Arevico\CB\Model;
use Arevico\CB\Service;

/**
 * Allows the admin to add a new customer
 * 
 * @version 1.0.0
 */
class CustomerAdd extends Core\Controller{

	/** @var Error $error */
	protected $error = null;

	/* New customer and product access option, posted by the admin */
	protected $newCustomer 		= array();
	protected $newProductAccess = array();

	/**
	 * render view
	 *
	 * @return void
	 */
	public function render(){
		$view 			= $this->genesis->view('Admin/CustomerEdit');

		$productIds 	= 		$all = Model\ProductInformation::listAll();
		
		if (Util::isPost())
			$this->save($view);

		$view->with('customer', 				$this->newCustomer )
			 ->with('productAccess', 			$this->newProductAccess)
			 ->with('productAccessToDelete', 	array())
			 ->with('productIds', 				\json_encode($productIds))
			 ->with('error', 			$this->error);

		$view->render();	
	}

	/**
	 * Save stuff
	 *
	 * @param View $view
	 * @return void
	 */
	public function save($view){
		
		/* 1. Get Data */
		$newCustomer 			= $this->newCustomer 		= $this->postvar('o->customer', array() );
		$this->newProductAccess 	= $this->postvar('o->productAccess', array() );

		/* 2. Validate */
		if (!$this->validateCustomer($newCustomer))
			return $this->error;

		$newCustomer = array_merge($newCustomer,
		array(
			'registered' 	=> substr(time(),0,20)
		));

		$newCustomer = new Model\Customer($newCustomer);
		$newCustomer->save();
	
		 foreach ($this->newProductAccess as &$access){ 
			 $access = $this->saveProductAccess($access, $newCustomer->id);
			 
		 }
		
		$view->with('notice', 'User was successfully added!');
		$view->with('action', 'edit');
		$view->with('id',  $newCustomer->id);

		return true;	
	}

	private function saveProductAccess($productAccess, $customerId){
		$productAccess['datePurchased'] = time(); // @todo Review this
		$productAccess = new Model\ProductAccess($productAccess);

		$productAccess->customerId = $customerId;
		return $productAccess->save()->getData();
	}

	/* Validate
	 ----------------------------------------------------- */
	/**
	 * Validate customer data
	 *
	 * @param array $customer
	 * @return Helper\Error
	 */
	public function validateCustomer($customer){
		$this->error = new Error();
		if (Util::isEmpty($customer,'email'))
			$this->error->add('email', 'Email address cannot be empty');

		if (Model\Customer::getBy('email', Util::val($customer, 'email')))
			$this->error->add('email', 'Email already exists!');
	
		return !$this->error->hasError();
	}

	/* Construct / Get / Set
	 ----------------------------------------------------- */	
	public function assets(){
		parent::assets();
	//	wp_enqueue_script('arevico-repeat-field', $this->registry->get('APP_PUBLIC') . '/zen-repeater.min.js', array('jquery') );
		wp_enqueue_script('arv-vue', $this->registry->get('CORE_PUBLIC') . '/vue/vue.js' );
	
}	

	public function __construct(){
		parent::__construct();
		$this->error = new Error();
	}
	
}