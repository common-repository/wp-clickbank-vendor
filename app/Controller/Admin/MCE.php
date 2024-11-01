<?php
//? https://codex.wordpress.org/Plugin_API/Filter_Reference/mce_external_plugins
namespace Arevico\CB\Controller\Admin;
use Arevico\Core;
use Arevico\CB\Model;

/**
 * Editor Buttons
 * 
 * @version 1.0.0
 */
class MCE extends Core\Controller{

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function assets(){
		wp_enqueue_script('arevico-vue', $this->registry->get('CORE_PUBLIC') . '/vue/vue.js' );
	}

	public function registerMCEPlugin( $plugins ){
		add_action( 'admin_footer',  array($this, 'tinyHTML'));
		$plugin_array['my_button_script'] = $this->registry->get('APP_PUBLIC') . '/tinymce.js';
		return $plugin_array;
	}
	
	public function registerMCEToolbar( $buttons ){
    	array_push( $buttons, "button_eek", "arvcbPayment" );
    	return $buttons;
	}

	public function register(){
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) 
            return;
	
		add_filter( 'mce_external_plugins', 	array( $this, 'registerMCEPlugin' ) );
		add_filter( 'mce_buttons', 				array( $this, 'registerMCEToolbar' ) );
	}

	public function tinyHTML(){
		$all = Model\ProductInformation::listAll();

		return $this->genesis->view('Admin/TinyMCE')->with(
			'productIds', json_encode($all)
		)->render();
	}

	
	public function __construct(){

	}
}