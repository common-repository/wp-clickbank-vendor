<?php
namespace Arevico\CB\View\Admin;
use Arevico\Core;
use Arevico\Core\Helper\Util;

class PA extends Core\View{
	protected $items = array();

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function render($output = true){
		$template = $this->getTemplate();
		$items 	  = $this->getItems();
		?>
		<div repeat-container="">
			<?php echo $template; ?>
			<?php echo $items; ?>
		</div>
		<?php
	}

	/**
	 * Render the template ID. @uses Self::getItem
	 *
	 * @param String $templateId
	 * @return void
	 */
	public function getTemplate($templateId = null){
		$templateId = $templateId ? "=\"{$templateId}\"" : '';
		$template 	= $this->getItem( array('id' => '{id}') );
		return "<div data-repeat-template{$templateId}>\r\n\t{$template}\r\n</div>";
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function getItems(){
		$html 	= '';
		$i 		= 0 ;

		foreach ($item as $item) {
			$repeatItem = $this->getItem($item);
			$html .= "<div repeat-item='{$i}'>\r\n\t{$repeatItem}\r\n</div>";
			$i++;
		}

		return $html;
	}

	/**
	 * Get single repeat instance
	 *
	 * @param [type] $item
	 * @return void
	 */
	public function getItem( $item ){
		Util::ArrayEntities($item);
		
		$html = "
		{$idField}
		<input type='text' value='$productId' name='o[productAccess][{$index}][productId]' placeholder='Product ID'>
		<input type='text' value='{$receipt}' name='o[productAccess][{$index}][receipt]' placeholder='receipt'>

		<select style='display:inline-block;' name='o[productAccess][{$index}][access]' id=''>
			<option " . selected(Util::val($item, 'access', '1'), '1', false) . "value='1'>Has Access</option>
			<option " . selected(Util::val($item, 'access', '1'), '0', false) . "value='0'>No Access</option>
		</select>

		<select name='o[productAccess][{$index}][recurring]' id=''>
			<option  " . selected(Util::val($item, 'recurring', '0'), '0', false) . "value='0'>Non Recurring</option>
			<option  " . selected(Util::val($item, 'recurring', '0'), '1', false) . "value='1'>Recurring</option>
		</select>

		<a href='#' data-repeat-delete class='button'> delete </a>
		";

		return $html;
	}

	public function __construct($items){

	}
}


?>
<!-- 
private function getElement($index = '{{id}}', $item = array()){
		$id 		= Util::escape($item, 'id');
		$productId 	= Util::escape($item, 'productId', '');
		$receipt 	= Util::escape($item, 'receipt', '');
		
		$idField 	= $id ? "<input type='hidden' name='o[productAccess][{$index}][id]' value='{$id}' / >" : '';
		$html =
		"<div>
		<input type='text' value='$productId' name='o[productAccess][{$index}][productId]' placeholder='Product ID'>
		<input type='text' value='{$receipt}' name='o[productAccess][{$index}][receipt]' placeholder='receipt'>

		<select style='display:inline-block;' name='o[productAccess][{$index}][access]' id=''>
			<option " . selected(Util::val($item, 'access', '1'), '1', false) . "value='1'>Has Access</option>
			<option " . selected(Util::val($item, 'access', '1'), '0', false) . "value='0'>No Access</option>
		</select>

		<select name='o[productAccess][{$index}][recurring]' id=''>
			<option  " . selected(Util::val($item, 'recurring', '0'), '0', false) . "value='0'>Non Recurring</option>
			<option  " . selected(Util::val($item, 'recurring', '0'), '1', false) . "value='1'>Recurring</option>
		</select>

		<a href='#' data-repeat-delete class='button'> delete </a>
		{$idField}
	</div>";

	return $html;
	}
-->