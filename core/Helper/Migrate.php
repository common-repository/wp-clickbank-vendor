<?php
namespace Arevico\Core\Helper;

/**
 * @property array 	$upgradePath 	upgrade path version => string|array queries
 * @property array 	$createQueries 	All create table queries 	
 * @property string $dbVersioName 	The field where we store our migration info
 */
class Migrate extends ClassHelper{

	protected $dbVersion;

	/**
	 * The queries required to move up one version. read as key "1=>2"
	 * 
	 * @example 1 => array( 'alter x', 'alter y', ...)
	 * 
	 * @var array
	 */
	protected $upgradePath 		= array();  
	protected $createQueries 	= array();
	protected $dbVersionName 	= '';
	protected $registry;

	/**
	 * Migrate the database to the latest version
	 *
	 * @return void
	 */
	public function migrate($bump = true){
		$this->createTables();

		$dbVersion = get_option($this->dbVersionName, null);
		if ( ($dbVersion !== null) && $dbVersion < $this->pluginDBVersion)
			$this->upgrade($this->pluginDBVersion, $dbVersion);
		
		if ($bump)
			update_option($this->dbVersionName, $this->pluginDBVersion);
	}

	/**
	 * Perform an delta update
	 *
	 * @param integer $pluginDBVersion
	 * @param integer $dbVersion
	 * @return void
	 */
	public function upgrade($pluginDBVersion, $dbVersion){
		global $wpdb;

		for ($i = $dbVersion; $i <= $pluginDBVersion; $i++) { 
			if (!isset($this->upgradePath[$i]) )
				continue;

			$queries 	= $this->upgradePath[$i];
			$queries 	= is_string($queries) ? array($queries) : $queries;
			
			foreach ($queries as $query) /** Run all create if exist statements */
				$wpdb->query( $this->prepareQuery($query) );
		}			
	}

	/**
	 * Create the initial tables
	 *
	 * @return void
	 */
	public function createTables(){
		global $wpdb;
		foreach ($this->createQueries as $query) /** Run all create if exist statements */
			$wpdb->query( $this->prepareQuery($query) );
	}

	/**
	 * Replace some placeholders
	 *
	 * @param string $sql
	 * @return void
	 */
	public function prepareQuery($sql){
		global $wpdb;
		$sql 	= str_replace('CREATE TABLE IF NOT EXISTS `', "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}", $sql);
		$sql 	= str_replace('ALTER TABLE `', "ALTER TABLE `{$wpdb->prefix}", $sql);
		$sql 	= str_replace('{prefix}', $wpdb->prefix, $sql);
		return $sql;
	}


	public function drop(){
		global $wpdb;
		$tables = \func_get_args();
		
		foreach ($tables as &$table) 
			$table = "{$wpdb->prefix}{$table}";

		$tables = implode(', ', $tables);
		$wpdb->query("DROP TABLE IF EXISTS {$tables};");
	}

	public function setRegistry( $registry ){
		$this->registry = $registry;
	}
}