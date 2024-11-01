<?php
namespace Arevico\CB\Controller;
use Arevico\Core;
use Arevico\Core\Helper\Util;
use Arevico\CB\Model;

/**
 * Register Download Page PostType
 * 
 * @version 1.0.0
 */
class PostType
{
	/**
	 * Register a place to add our Download Pages
	 *
	 * @return void
	 */
	public static function registerProductPostType(){
		$options = get_option( 'arvcbOptions', array());

		$labels = array(
			'name'               => _x( 'Download Page', 'post type general name', 'your-plugin-textdomain' ),
			'singular_name'      => _x( 'Product', 'post type singular name', 'your-plugin-textdomain' ),
			'menu_name'          => _x( 'download page', 'admin menu', 'your-plugin-textdomain' ),
			'name_admin_bar'     => _x( 'Product', 'add new on admin bar', 'your-plugin-textdomain' ),
			'add_new'            => _x( 'Add New', 'Download Page', 'your-plugin-textdomain' ),
			'add_new_item'       => __( 'Add New Product', 'your-plugin-textdomain' ),
			'new_item'           => __( 'New Product', 'your-plugin-textdomain' ),
			'edit_item'          => __( 'Edit Product', 'your-plugin-textdomain' ),
			'view_item'          => __( 'View Product', 'your-plugin-textdomain' ),
			'all_items'          => __( 'Download Pages', 'your-plugin-textdomain' ),
			'search_items'       => __( 'Search download page', 'your-plugin-textdomain' ),
			'parent_item_colon'  => __( 'Parent download page:', 'your-plugin-textdomain' ),
			'not_found'          => __( 'No download page found.', 'your-plugin-textdomain' ),
			'not_found_in_trash' => __( 'No download page found in Trash.', 'your-plugin-textdomain' )
		);

		// https://stackoverflow.com/questions/19747096/remove-url-structure-for-custom-post-type
		$args = array(
			'pages' 			 => false,
			'labels'             => $labels,
			'description'        => __( 'Description.', 'your-plugin-textdomain' ),

			// 'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'exclude_from_search'=> true,
			'with_front' 		 => false,
//			'show_in_menu'       => false,
			'show_in_menu' 		 => 'edit.php?post_type=download-pages',			
			'query_var'          => true,
			'rewrite'            => array( 'slug' => Util::val($options, 'slug', 'download') ),

			// 'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false, 
			'menu_position'      => 5,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'post-formats')
		);
 
		register_post_type( 'download-pages', $args );	
	}
}
