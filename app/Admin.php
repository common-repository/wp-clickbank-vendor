<?php
namespace Arevico\CB;
use Arevico\Core;

/**
 * Main Admin Class
 * 
 * @version 1.0.0
 * 
 * @property Core\Genesis 	$genesis  	The factory method (genesis chamber)
 * @property Core\Registry 	$registry 	The registry
 * @property array 			$pageMap 	A mapping of slugs to Controllers (FQCN)
 */
class Admin extends Core\Admin{
	protected $options = array();

	protected $pageMap = array(
		"wp-cb-vendor-top" 			=> 'Arevico\CB\Controller\Admin\Top',
		"arvcb-customers" 			=> 'Arevico\CB\Controller\Admin\Customers',
		"arvcb-customer-add"		=> 'Arevico\CB\Controller\Admin\CustomerAdd',
		"arvcb-customer-edit"		=> 'Arevico\CB\Controller\Admin\CustomerEdit',
		"arvcb-customer-delete"		=> 'Arevico\CB\Controller\Admin\CustomerDelete',
		// "arvcb-customer-tab"		=> 'Arevico\CB\Controller\Admin\TabExample',
	);

	/**
	 * Register all our admin pages
	 *
	 * @return void
	 */
	public function registerMenus(){
		add_menu_page( 'WP ClickBank Vendor', 'WP ClickBank', 'manage_options', 'wp-cb-vendor-top', array($this, 'render')); 
		add_submenu_page( 'wp-cb-vendor-top', 'Customers', 'Customers', 'manage_options', 'arvcb-customers', array($this, 'render')); 
		
		/* Register Pages w/o Menu */
		add_submenu_page( null, 'Add Customer', 'Add', 'manage_options', 'arvcb-customer-add', array($this, 'render'));		
		add_submenu_page( null, 'Edit Customer', 'Edit', 'manage_options', 'arvcb-customer-edit', array($this, 'render'));
		add_submenu_page( null, 'Delete Customer', 'Delete', 'manage_options', 'arvcb-customer-delete', array($this, 'render'));
		add_submenu_page( 'wp-cb-vendor-top', 'Download Pages', 'Download Pages', 'manage_options', 'edit.php?post_type=download-pages');		
	}

	public function registerMetaboxes(){
		/** @var Controller\Metabox\Product */
		$productSelector 	= $this->genesis->create('Arevico\CB\Controller\Metabox\Product' );
		add_action('admin_enqueue_scripts', array($productSelector, 'assets'))	;
	}

	public function registerTinyMCEPlugins(){
		$MCE = $this->genesis->create(__NAMESPACE__ . '\Controller\Admin\MCE');
		$MCE->registerAssets();
		$MCE->register();
	}

	public function addActionLinks($links){
		   return array_merge( $links, array(
			'<a target="_new" href="http://arevico.com/wp-clickbank-vendor-getting-started/">Getting Started!</a>'
		   ));		   
	}
	
	/* Getters / Setters
	----------------------------------------------------- */
	public function __construct(){
		parent::__construct();	
		add_action('admin_menu', 			array($this, 'registerMenus') );
		$this->options = get_option('arvcbOptions', array());
	}
	
	/**
	 * Initialize the admin interface
	 * 
	 */
	public function determinePage($screen){
		parent::determinePage($screen);
	}

	public function init(){
		add_action( 'init', 			 array( $this, 'registerTinyMCEPlugins' ) );
		add_action( 'load-post.php',     array( $this, 'registerMetaboxes' ) );
		add_action( 'load-post-new.php', array( $this, 'registerMetaboxes' ) );		
		add_filter( 'plugin_action_links_' . $this->registry->get('PLUGIN_BASENAME'), array($this, 'addActionLinks') );	
	}

}