<?php
namespace Arevico\Core\Helper;
use Arevico\Core;

abstract class Metabox extends Core\Controller{

	/**
	 *
	 * @param WP_Post post the wp post object associated
	 */
	abstract public function render( $post );
	abstract public function registerMetabox();
	
	/** Fired */
	abstract public function save( $postId, $post, $update );

	/**
	 * Helper to stop CSRF and Check if the Post is Saving
	 *
	 * @param integer $postId
	 * @param string $nonceField
	 * @return void
	 */
	public function verify( $postId, $nonceField = '_arevico_nonce'){
		// Exits script depending on save status
		if ( wp_is_post_autosave( $postId ) || wp_is_post_revision( $postId ) || !$this->csrf() ) 
			return false;

		return true;
	}

	public function __contruct(){
		parent::__construct();
		// add_action('add_meta_boxes', array($this, 'registerMetabox'),10, 2);
		add_action('save_post', array($this, 'save'), 10, 3 );
	}
}
