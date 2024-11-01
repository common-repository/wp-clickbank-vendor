<?php
namespace Arevico\CB;

use Arevico\Core;

/**
 * Admin Entry Point
 */
class Ajax extends Core\Ajax{

	public function init(){
		add_action( 'init', 'Arevico\CB\Controller\PostType::registerProductPostType' );
	}

}