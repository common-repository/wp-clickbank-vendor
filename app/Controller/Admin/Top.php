<?php
namespace Arevico\CB\Controller\Admin;
use Arevico\Core;
use  Arevico\Core\Helper\Util;

/**
 * General Options
 * 
 * @version 1.0.0
 */
class Top extends Core\Controller{
	
	private $optionName = 'arvcbOptions';
	private $options 	= array();
	private $notice;
	
	/**
	 * Render
	 *
	 * @return void
	 */
	public function render(){
		$this->options = get_option( $this->optionName, array() );

		if (Util::isPost() )
			$this->post();
		
		$view 	= $this->genesis->view('Admin/top');
		$view->with('o', $this->options);
		$view->with('notice',	$this->notice);
		$view->render();
	}	

	public function assets(){
		parent::assets();
		wp_enqueue_script('arevico-repeat-field', $this->registry->get('CORE_PUBLIC') . '/vue/vue.js');
	}

	public function post(){
		$this->options = $this->postVar('o', array());
		update_option( $this->optionName, $this->options );	
		flush_rewrite_rules( true );
	}
	
	/* Getter / Setters
	 ----------------------------------------------------- */
	public function __construct(){
		parent::__construct();
	}
	
}