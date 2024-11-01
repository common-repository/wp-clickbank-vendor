<?php
namespace Arevico\Core;

class Genesis
{

	/** @var Registry*/
	protected $registry;

	/**
	 *
	 * @param string|fqcn $name if a fully qualified class name is supplied, the class is loaded, else it is used as a template string
	 * @return View view a new instance of a view object
	 */
	public function view($name){	
		return class_exists($name) ? $this->create($name) : $this->create(View::fqcn() )->setTemplatePath($name);
	}

	/**
	 * Generic Factory Method
	 *
	 * @todo add support for action to run after injection and instantiation occurs
	 */
	public function create($name /* ,... */){
		$genesisMethod 	= array($name, 'create');
		$arguments 		= array_slice(func_get_args(), 1);
		$instance   	= is_callable($genesisMethod) ? call_user_func_array($genesisMethod, $arguments) :  new $name();

		if (!$instance) /* Instantiation was not correct, so we can't inject dependcies*/
			return $instance;
			
		$instance   	= $this->registry->inject( $instance );
		return $instance;
	}

	/**
	 * Set registery
	 *
	 * @param Registry $registry
	 * @return void
	 */
	public function setRegistry($registry){
		$this->registry = $registry;
	}

	public function createRequestVariables(){
		$request = array (
			'request' 	=> stripslashes_deep( $_REQUEST),
			'get' 		=> stripslashes_deep( $_GET),
			'post' 		=> stripslashes_deep( $_POST),
			'cookie'	=> stripslashes_deep( $_COOKIE)
		);

		return (object)$request;
	}
}
