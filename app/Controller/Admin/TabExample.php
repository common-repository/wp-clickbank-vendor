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
class TabExample extends Core\Controller{

	public function render(){
		$this->genesis->view('Tabs')->render();
	}

	public function assets(){
		parent::assets();
		wp_enqueue_script('arevico-repeat-field', $this->registry->get('CORE_PUBLIC') . '/vue/vue.js');
	}	

	public function __construct(){
		parent::__construct();
		$this->error = new Error();
	}
	
}