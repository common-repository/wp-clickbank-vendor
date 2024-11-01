<?php
namespace Arevico\CB\Model;

use Arevico\Core;

/**
 * Get information about a specific product
 * 
 * @version 1.0.0
 *  
 */
class ProductInformation extends Core\DBModel{

	public static $primaryKey = 'meta_id';	

	public static function listAll(){
		global $wpdb;
		$sql 	= "SELECT * FROM {$wpdb->postmeta} pm 
							JOIN {$wpdb->posts} p ON pm.post_id = p.ID
				  WHERE meta_key='_arvcb_product'";

		return $wpdb->get_results($sql, OBJECT) ;
	}
	
	/**
	 * Get product ID associated with the current post
	 *
	 * @param integer $postId
	 * @return void
	 */
	public static function listByPost($postId){
		global $wpdb;
		$sql 	= $wpdb->prepare("SELECT * FROM {$wpdb->postmeta} WHERE post_id = %s AND meta_key='_arvcb_product'", $postId);
		return $wpdb->get_results($sql, OBJECT) ;
	}

	public static function massUpdate($productMeta, $postId){
		foreach ($productMeta as $meta) {
			$meta = new ProductInformation($meta);
			$meta->meta_key 	= '_arvcb_product';
			$meta->post_id 		= $postId;
			$meta->save();
		}

	}

	/* Genesis
	 ----------------------------------------------------- */
	public function __construct($data = array()){
		$this->setData($data);
		global $wpdb;
		parent::__construct($wpdb->postmeta, false);
	}

}