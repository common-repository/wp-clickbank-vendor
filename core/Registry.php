<?php
namespace Arevico\Core;

class Registry
{

    protected $objects          = array();
    protected $dependencies     = array();

	/* Registry
	 --------------------------------------------------------------- */
    /**
     * Register an object into the Registry
     *
     * @param string    $name           a key for the object.
     * @param string    $value          The object to store in the Registry
     * @param boolean   $dependency     If the specified object is a depency. Make sure that the key matches the method that inject it!
     * @example new Registry()->register($reg)
     */
    public function register($name, $value, $dependency = false)
    {
        $this->objects[$name] = $value;

        if ($dependency)
            $this->depencies[$name] = $value;

        return $value;
    }
    
	/**
     * Retrieve an object from the Registry
     *
     * @param string $name The key to query
     */
    public function get($name)
    {
        return isset($this->objects[$name]) ? $this->objects[$name] : null;
    }

    public function remove($name)
    {
        unset($this->object[$name], $this->depencies[$name]);
    }

	/* Dependency Injection
	 --------------------------------------------------------------- */
    /**
     * Inject depenencies
     *
     * @param object $target
     */
    public function inject($target)
    {
        foreach ($this->depencies as $key => $dependency) {
            $injectorMethod = array($target, "set{$key}");
            
			if (is_callable($injectorMethod)) 
                call_user_func($injectorMethod, $dependency);   
        }

		return $target;
    }

    public function __construct()
    {
        $this->register('Registry', $this, true);
    }

}