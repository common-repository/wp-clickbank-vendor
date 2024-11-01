<?php
namespace Arevico\CB\Controller\Admin;
use Arevico\Core;
use  Arevico\Core\Helper\Util;

/**
 * Overview of all customers
 * 
 * @version 1.0.0
 */
class CustomersTable extends Core\Helper\Table{

	/**
	 * Implement searchboxes here
	 *
	 * @param [type] $which
	 * @return void
	 */
	public function display_tablenav($which){
		if ($which=='bottom') return;
	}

	public function get_columns( ){
		return array(
			'name' 			=> __('Name'),
			'email'			=> __('Email'),
			'firstName'		=> __('First Name'),
			'lastName'		=> __('Last Name'),
			'registered' 	=> __('Registered')
		);
	}

	public function column_name($item){
		$name = Util::escape($item, 'fullName');
		$name = empty($name) ? '&nbsp;' : $name;
		  $actions = array(
				'edit'      => "<a href=\"admin.php?page=arvcb-customer-edit&id={$item->id}\">Edit</a>",
				'delete'    => "<a href=\"admin.php?page=arvcb-customer-delete&id={$item->id}\">Delete</a>",

			);
		?>
		<strong>
			<a href="admin.php?page=arvcb-customer-edit&id=<?php Util::safe($item, 'id'); ?>"><?php echo $name; ?></a></strong>
		<?php
			echo $this->row_actions($actions);
	}

	public function column_registered($item){
		$timestamp = Util::val($item, 'registered');
		if (is_numeric($timestamp))
			echo date('F j, Y',  $timestamp);
	}
	
	public function get_sortable_columns(){
		return array(
			'id' 		 => array('id', false),
			'name' 		 => array('name', false),
			'registered' => array('registered', false)
		);
	}

	public function column_email( $item ){
		$email = Util::escape($item, 'email');
		echo "<a href=\"mailto:{$email}\">{$email}</a>";
	}

	function __construct(){
		parent::__construct( __( 'Customer'),  __( 'Customers'));

	}

}