<?php
namespace Arevico\CB\Controller\Admin;

use Arevico\Core;
use Arevico\Core\Helper\Util;
use Arevico\CB\Model;

/**
 * List / Delete or Initiate Add Customers
 * 
 * @version 1.0.0
 */
class Customers extends Core\Controller{

	/** @var string action in ['add', 'edit'] */
	protected $action;

	/** @var interger Items per page */ 
	protected $perPage 	= 10;
	
	/**  @var string A notice we want to pass trough the view */
	protected $notice 	= null;

	/**
	 * Render the view
	 * 
	 * @return void
	 */
	public function render(){
		$this->view = $view = $this->genesis->view('Admin/Customers');
		$this->processActions();

		$sortables 	= array(
			'id' 			=> 'id',
			'name' 			=> 'fullName',
			'registered' 	=> 	'registered'
		);

		$customers 	= Model\Customer::all();
		
		/* 1. Order By */
		$order 		= $this->getVar('order', null) === 'asc' ? 'asc' : 'desc';
		$orderBy 	= $this->getVar('orderby', 'id');
		
		if (isset($order, $orderBy, $sortables[$orderBy]) )
		$customers->orderBy("{$sortables[$orderBy]} $order");
		
		/* 2. Paginate */
		$paged = $this->getVar('paged', null);
		$paged = is_numeric($paged) ? ($paged - 1 ) * $this->perPage: 0;
		
		$table = new CustomersTable();
		$table->paginate($customers->total(), $this->perPage);
		
		$customers->limit("{$paged}, $this->perPage");

		/* 3. Retrieve items and render*/
		$table->setItems($customers->run() );

		$view->with('table', $table)->render();
	}

	/**
	 * Process all actions
	 *
	 * @return void
	 */
	public function processActions(){
		if (!$this->csrf())	
			return;

		$action = $this->requestVar('action', '');
		$id 	= $this->requestVar('id', null);

		// Delete Customer
		if ($action=='delete'){
			$customer = Model\Customer::get($id);
			$customer->delete();

			$this->view->with('notice', "Customer: <strong>{$customer->email}</strong> has been deleted!");
		} 
	}
	
	/* Getters & Setters & Assets
	 ----------------------------------------------------- */
	function __construct(){
		parent::__construct();
		$this->error = new \WP_Error();
	}
}