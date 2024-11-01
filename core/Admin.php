<?php
namespace Arevico\Core;

/**
 * Undocumented class
 */
abstract class Admin extends Common
{
	protected static $instance;
	protected $pageMap 		= array();

	/** @var Controller */
	protected $loadedPage 	= array('\Arevico\Core\Helper\Util', 'noop');

	/**
	 * Almost all plugins feature admin pages. This routes to the correct controller based on a page map
	 *
	 * @param array $pageMap 
	 * @return Controller return a instanciation of a controller or null
	 */
	public function loadAdminPage( ){
	
		if (!isset( $this->request->get['page'], $this->pageMap[$this->request->get['page']] ))
			return null;

		$callback 	= $this->pageMap[$this->request->get['page']];

		// Accept either an callback array or FQCN
		$fqcn 		= is_array($callback) ? $callback[0] : $callback;
		$method 	= is_array($callback) ? $callback[1] : 'render';
	
		$this->loadedPage = array($this->genesis->create($fqcn), $method);
	
		return $this->loadedPage[0]; // Return the instance so we can add it to the registry if we want to do that
	}

	/**
	 * Render the loaded adminpage
	 *
	 */
	public function determinePage( $screen ){
		if (!$this->loadAdminPage())
			return;

		if ( method_exists($this->loadedPage[0], 'registerAssets') )
			$this->loadedPage[0]->registerAssets();
	}	

	public function render(){
		return call_user_func($this->loadedPage);
	}

	public function  __construct(){
		add_action('current_screen', array($this, 'determinePage'));
	}

}