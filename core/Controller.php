<?php
namespace Arevico\Core;

/**
 * @todo add verify nonce functions
 * @property Genesis $genesis
 */
abstract class Controller extends Helper\ClassHelper
{
	public function registerAssets(){
		add_action('admin_enqueue_scripts', array($this, 'assets'));	
	}
	/** May be overriden */
	public function assets(){
		wp_enqueue_style('arevico-admin-test-css', $this->registry->get('CORE_PUBLIC') . '/admin/style.css' );
		wp_enqueue_style('arevico-admin-grid-css', $this->registry->get('CORE_PUBLIC') . '/grid/table.css' );
	 } 
	
	public function csrf($nonceField = '_arevico_nonce'){
		return wp_verify_nonce( $this->requestVar($nonceField, ''));
	}

	public function __construct(){

	}
}


