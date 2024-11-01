<?php
/*
Plugin Name: WP ClickBank Vendor
Plugin URI: http://arevico.com/wp-clickbank-vendor-getting-started/
Description: Start selling your digital content, ebooks and software via ClickBank in minutes. WP ClickBank Vendor enables you to accept payments within minutes.
Version: 0.9.1
Author: Arevico
Author URI: http://arevico.com/
*/

namespace  Arevico\CB;
use Arevico\CB\Model;
use Arevico\Core;
use Arevico\Core\Helper\Util;
use Arevico\Core\Helper\HTTP;

require 'core/Helper/Polyfill.php';
require 'core/Autoload.php';

global $wpdb, $genesis;
Core\Autoload::get()->register(__NAMESPACE__, __DIR__ . '/app/'); 


//die ();
/* 1. Load all required framework stuff */
$registry       = new Core\Registry();
$genesis 		= new Core\Genesis();

/* 2. Load depenencies */
$registry->inject( $genesis );

$registry->register('Genesis', $genesis, true);
$registry->register('RequestVariables', $genesis->createRequestVariables(), true);

/* 3. Load Some stuff into the registyr*/
$registry->register('APP_NAMESPACE', __NAMESPACE__ );

$registry->register('PLUGIN_BASENAME',	 plugin_basename(__FILE__) );
$registry->register('PLUGIN_BASE',	 __DIR__ );

$registry->register('APP_PUBLIC', 	plugins_url('app/Public', __FILE__) );
$registry->register('CORE_PUBLIC', 	plugins_url('core/Public', __FILE__ )) ;

$doingAjax = defined( 'DOING_AJAX' ) && DOING_AJAX;
	
/* Load all aplication Logic
 ----------------------------------------------------- */
if ($doingAjax) {
	$ajax = $genesis->create( Ajax::fqcn() );
	$ajax->init();
	
} else {
	$common = $genesis->create( Common::fqcn() );
	$common->init();
}

if (is_admin() && !$doingAjax) {
    $admin = $genesis->create( Admin::fqcn() );
	$admin->init();
}

if (!is_admin() && !$doingAjax) {
    $front = $genesis->create( Front::fqcn() );
	$front->init();
}   

/* Activation and Deactivation Hooks
 ----------------------------------------------------- */
register_activation_hook(__FILE__, function( $networkWide ){
	global $genesis;
	$genesis->create( Activate::fqcn() )->activate($networkWide);
});

