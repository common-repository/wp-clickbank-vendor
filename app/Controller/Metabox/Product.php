<?php
namespace Arevico\CB\Controller\Metabox;
use Arevico\Core;
use Arevico\CB\Model;

/**
 * Render the metabox and update DownloadPage / ProductID Bindings
 * 
 * @version 1.0.0
 */
class Product extends Core\Helper\Metabox{

	/**
	 * Render the metaboxes
	 *
	 * @param WP_Post $post
	 * @return void
	 */
	public function render( $post ){
		$productIds 	= Model\ProductInformation::listByPost($post->ID);
		$view 			= $this->genesis->view('Metabox/Product');

		$view->with('productIds', $productIds);
		$view->render();
	}

	/**
	 * Save post hook
	 *
	 * @param integer $postId
	 * @param WP_Post $post
	 * @return void
	 */
	public function save( $postId, $post, $update ){
		global $wpdb;

		if ( !$this->verify($postId) )
			return ;

		$insertModify 	= $this->postVar('arvcb->products' ,array());
		$deleted 		= $this->postVar('arvcb->deleted', array());
			
		Model\ProductInformation::massUpdate($insertModify, $postId);
		Model\ProductInformation::massDelete( $deleted);
	}

	public function registerMetabox(){
		add_meta_box('arevico-cb-product', 'ClickBank Product', array($this, 'render'), 'download-pages', 'side');
	}

	/* Getters / Setters
	 ----------------------------------------------------- */
	public function assets(){
		parent::assets();
		wp_enqueue_script('arevico-vue', $this->registry->get('CORE_PUBLIC') . '/vue/vue.js');
	}

	public function __construct(){
		parent::__construct();
		add_action('add_meta_boxes', array($this, 'registerMetabox'),10, 2);
		add_action('save_post', array($this, 'save'), 10, 3 );
	}
}