<?php
namespace Arevico\CB\Controller\Admin;
use Arevico\Core;

use Arevico\CB\Model;
use Arevico\Core\Helper\Util;

/**
 * Confirm the deletion of a user and redirect to the proper page
 * 
 * @version 1.0.0
 */
class CustomerDelete extends Core\Controller{

	/**
	 * @var Model\Customer
	 */
	protected $customer;

	public function render(){
		$this->customer = Model\Customer::get( $this->getVar('id', null) );
		if (!$this->customer)
			wp_die('Customer Doesn\'t Exists!');

		$view = $this->genesis->view('Admin/CustomerDelete');
		$view->with(array(
			'customer' 	=> $this->customer
		));

		$view->render();
	}

	public function __construct(){
	}
	
	
}