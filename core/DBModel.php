<?php
namespace Arevico\Core;

/**
 *
 * 
 */
abstract class DBModel extends Model
{
	public static $primaryKey = 'id';

	/**
	 *
	 * @var string
	 */
	public $table;
	
	protected $fields 	  	= array();
	protected $checkboxes 	= array();	
	
	/**
	 * Data and public properties associated with the model
	 *
	 * @var stdClass
	 */
	public  $data;

	/* Constructors, Getters, Setters
	 ----------------------------------------------------- */
	 public static function listAll(){
		global $wpdb;
		
		$class 		= get_called_class();

		/** @var DBModel */
		$instance 	= new $class();
	
		return $wpdb->get_results("SELECT * FROM {$instance->getTable()};", OBJECT);;
	}

	 /**
	  * Get a single item from the database by a column field
	  *
	  * @param string $column the column to match
	  * @param string $value the value to match
	  */
	public static function getBy($column, $value){
		global $wpdb;

		$class 		= get_called_class();

		/** @var DBModel */
		$instance 	= new $class();
	
		$sql 	 = $wpdb->prepare("SELECT * FROM {$instance->getTable()} WHERE {$column}=%s LIMIT 1", $value);
		$results = $wpdb->get_results($sql, OBJECT);

		if (empty($results))
			return null;	

		$results = $results[0];

		$instance = new $class();
		$instance->setData($results);
		return $instance;		
	}

	/**
	 * Insert or save the model
	 * 
	 * @return DBModel
	 */
	public function save(){
		global $wpdb;
		$class = get_called_class();

		$primaryKey = $class::$primaryKey;
		
		if (isset($this->data[$primaryKey]) && $this->data[$primaryKey] != ''){
			$wpdb->update("{$this->getTable()}", $this->data, array( $primaryKey => $this->data[$primaryKey])) ;
		
		} else {
			$wpdb->insert("{$this->table}", $this->data);
			$this->data[$primaryKey] = $wpdb->insert_id;
		}

		return $this;
	}

	public function delete(){
		global $wpdb;
		$class  	=  get_called_class();
		$primaryKey = $class::$primaryKey;
		$id 		= $this->data[$primaryKey];

		$wpdb->query( $wpdb->prepare(
			"DELETE FROM {$this->getTable()} WHERE {$primaryKey}=%s", $id)
		);
	}
	
	/**
	 * Delete entry by a foreign key
	 *
	 * @param string $column
	 * @param string|array $value
	 * @return void
	 */
	public static function deleteBy($column, $value){
		global $wpdb;
		$class 		= get_called_class();
		$instance 	= new $class();

		$sql 		= $wpdb->prepare("DELETE FROM {$instance->getTable()} WHERE {$column}=%s", $value);
		return $wpdb->query($sql);
	}

	public static function massDelete( $ids){
		if (empty($ids))
			return;
			
		global $wpdb;
		$class 			= get_called_class();
		$instance 		= new $class();
		$primaryKey 	= $class::$primaryKey;
		$ids 	= esc_sql( $ids );
		$ids 	= implode("','", $ids);
		return $wpdb->query("DELETE FROM {$instance->getTable()} WHERE {$primaryKey} IN ('{$ids}')");
	}

	public static function get($id){
		return self::getBy('id', $id);
	}
	
	/* Getters / Setters
	----------------------------------------------------- */
	public function getTable(){
		return $this->table;
	}

	public function __unset($name){
		unset($this->data[$name]);
	}

	public function __get($key){
		return $this->data[$key];
	}

	public function __set($key, $value){
		$this->data[$key] = $value;
	}
	public function remove($key){
		if (isset($this->data[$key]))
			unset($this->data[$key]);
	}

	public function __isset($key)
	{
		return array_key_exists($key, $this->data);
	}

	public function setData($data){
		$this->data = (array)$data;
	}

	/**
	 * Return the data array
	 *
	 * @return Array
	 */
	public function getData(){
		return $this->data;
	}

	public function getFields(){
		return $this->fields;
	}

	public function __construct($table, $prefix = true){	
		global $wpdb;
		$this->table = $prefix ? "{$wpdb->prefix}{$table}" : $table;
	}
}