<?php

namespace Arevico\Core\Helper;
use Arevico\Core;

if(!class_exists('WP_List_Table', false)){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
/**
 * @property $items 	This is used to store the raw data you want to display. Generally you will set this property directly in the prepare_items() method.
 * @property $screen 	This can be used to store the current screen, when it's necessary to keep it consistent with the entire instance.
 * @property $_actions 	Stores cached bulk actions. This generally isn't manipulated directly.
 * see https://codex.wordpress.org/Class_Reference/WP_List_Table#Properties
 */
abstract class Table extends \WP_List_Table{

	/** 
	 * 
	 * We can't declare functions as abstract if they are not abstract in the base class, but I'll list them here for you to extend 
	 * get_columns() 
	 * get_sortable_columns()
	 * see https://codex.wordpress.org/<Class_></Class_>Reference/WP_List_Table#Methods
	 */
	public function get_columns(){
		return array();
	}
	public function get_bulk_actions(){
		return array(); //slug => title
	 }
		
	public function get_sortable_columns(){
		return array();
	}

	public function get_hidden_columns(){
		return array();
	}

	public function setItems( $items ){
		$this->items = $items; 
	}

	public function prepare_items( ){
		$this->_column_headers= array(
			$this->get_columns(),
			$this->get_hidden_columns(),
			$this->get_sortable_columns()
		);

	}

	public function column_default($column, $name){
		$column = (object)$column;

		if ( isset( $column->{$name} ))
			return htmlentities($column->{$name});
	}

	public function __construct($singular, $plural, $ajax = false){
		parent::__construct( array(
            'singular'  =>  $singular,     //singular name of the listed records
            'plural'    => $plural,    //plural name of the listed records
            'ajax'      => $ajax        //does this table support ajax?
        ) );

		$this->data = array();
	}

	public static function fqcn(){ return get_called_class(); }

	public function render(){
		$this->prepare_items();
		$this->display();
	}


	public function paginate($totalItems, $perPage){
		return $this->set_pagination_args( array(
			'total_items' 	=> $totalItems,
			'per_page' 		=> $perPage,
			'total_pages' 	=> ceil($totalItems/ $perPage)
		));
	}
}