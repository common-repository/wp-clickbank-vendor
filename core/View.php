<?php
namespace Arevico\Core;

/**
 * Render a template. Extend this class and override the render function if you want
 * to render anything other than a template
 *
 * 
 */
class View extends Helper\ClassHelper{
	/** @var Registry */ 
	protected $registry; 
	/** @var Genesis*/
	protected $genesis; 
	
	protected $output 			= '';
	protected $data 			= array();	
	protected $templatePath 	= '';

	/**
	 * Render 
	 *
	 * @param boolean $output wether or not to output the reuslt of the rendering
	 */
	public function render($output = true){
	   extract ($this->data); 	   // Locals added do not show up in xDebug ,unfortunately

	   ob_start();          /* Start Processing The View */
		   require $this->templatePath;
		   $this->output = ob_get_contents();
	   ob_end_clean();      /* Obtain The View Data */

		if ($output)
			echo $this->output;

	   return $this->output;	
	}

	/**
	 * Pass data that is imported imported into the local symbol table
	 *
	 * @param mixed $x either name or value alternated van be passed, or an array containing all symobls to me imported
	 */
	public function with( $name, $value = null ){
		$args = func_get_args();
		$l 	  = func_num_args();

		if ($l==1){
			$this->data = array_merge($this->data, $args[0]);
			return $this;
		}

		for ($i=0; $i <	 $l ; $i = $i + 2)
			$this->data[$args[$i]] = $args[$i+1];

		return $this;
	}

	/**
	 * Directly set the symbol table to be imported into the local scope
	 *
	 * @param array $data the local symbol table
	 */
	public function setData( $data ){
		$this->data = $data;
		return $this;
	}

	/**
	 * Set the Template Path to Render
	 *
	 * @param $path
	 */
	public function setTemplatePath( $path ){
		$path 	= \str_replace('\\', '/', $path);
		$path 	= $this->registry->get('PLUGIN_BASE') . "/app/View/{$path}.php";
		$this->templatePath = $path;
		return $this;
	}

	/**
	 * Make a new view and make sure all added variables are available in the new context
	 *
	 * @param CallbackString   $callback the callback
	 * @param array            $data The symbol table we want to transfer.
	 */
	public function section($name, $data = array() )
	{
		$view = $this->genesis->view($name);
		$view->setData( $data );
		$view->render();
	}

	public static function fqcn(){
		return get_called_class();
	}

	public function safe($name, $default=''){
		echo isset($this->data[$name]) ? htmlentities($this->data[$name]) : '';
	}

}