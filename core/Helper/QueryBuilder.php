<?php
namespace Arevico\Core\Helper;

class QueryBuilder {
	public $query= array(
		'SELECT' 	=> '',
		'FROM' 		=> '',
		'WHERE' 	=> '',
		'GROUP BY' 	=> '',
		'HAVING' 	=> '',
		'ORDER BY' 	=> '',
		'LIMIT' 	=> ''
	);	

	public $results = array();

	/* Utilities
	 ----------------------------------------------------- */
	 public function total(){
		$total = $this->copy()
			->select('count(*) as rows')
			->orderBy('')
			->limit('')
			->run();
			
		return empty($total) ? 0 : $total[0]->rows;
	 }
	 
	/**
	 * Reset Object
	 *
	 * @return QueryBuilder
	 */
	public function reset(){
		$this->results = array();

		foreach ($this->query as &$part)
			$part = '';

		return $this;
	}
	
	public function sql(){
		$sql = '';

		foreach ($this->query as $part => $value)
			if (!empty($value)) 
				$sql .= "{$part} $value ";
		
		return $sql . ';';
	}

	public function run($type = OBJECT){
		global $wpdb;
		return $this->results = $wpdb->get_results($this->sql(), $type);
	}

	/* Getters & setters
	 ----------------------------------------------------- */
	 /**
	  * Set Query
	  *
	  * @param string $part
	  * @param string $value
	  * @param boolean $overwrite
	  * @return QueryBuilder
	  */
	 public function set($part, $value, $overwrite = true){
		if ($overwrite){
			$this->query[$part] = $value;

		} else {
			$this->query[$part] .= $value;
		}

		return $this;		 
	 }
	 
	 /**
	  * @return QueryBuilder
	  */
	 public function select($value, $overwrite = true){
		 return $this->set('SELECT', $value, $overwrite);
	 }

	 /**
	  * @return QueryBuilder
	  */	 
	 public function from($value, $overwrite = true){
		 return $this->set('FROM', $value, $overwrite);
	 }

	 /**
	  * @return QueryBuilder
	  */
	 public function where($value, $overwrite = true){
		 return $this->set('WHERE', $value, $overwrite);
	 }
	 
	 /**
	  * @return QueryBuilder
	  */
	 public function limit($value, $overwrite = true){
		 return $this->set('LIMIT', $value, $overwrite);
	 }

	 /**
	  * @return QueryBuilder
	  */
	 public function groupBy($value, $overwrite = true){
		 return $this->set('GROUP BY', $value, $overwrite);
	 }
	 
	 /**
	  * @return QueryBuilder
	  */
	 public function having($value, $overwrite = true){
		 return $this->set('HAVING', $value, $overwrite);
	 }

	 /**
	  * @return QueryBuilder
	  */
	 public function orderBy($value, $overwrite = true){
		 return $this->set('ORDER BY', $value, $overwrite);
	 }

	 public function get_results($o = OBJECT_K){
		 global $wpdb;
		 return $wpdb->get_results( $this->sql(), $o );
	 }
	 /**
	  * Undocumented function
	  *
	  * @return QueryBuilder
	  */
	 public function copy(){
		$db = new self();
		$db->query = $this->query;
		return $db;
	 }
	 
	 /**
	  * Return value of query part
	  *
	  * @param string $part
	  * @return string
	  */
	 public function get($part){
		return $this->query[$part];
	 }
}