<?php
namespace Arevico\CB\View\Admin;
use Arevico\Core;
use Arevico\Core\View;
use Arevico\Core\Helper\Util;

/**
 * Render ProductAccess Template and Items
 * 
 * @version 1.0.0
 * 
 */
class ProductAccessTemplates extends View{

	public function getTemplate(){
		$template = $this->getElement();
		echo "<div style='display:none;'><div data-repeat-template='productaccess'>{$template}</div></div>";
	}

	/* Construct seperate items
	 ----------------------------------------------------- */
	public	function item($index, $item){
		$item = (array)$item;
		$idAttribute = Util::isEmpty($item, 'id') ? '' : "data-repeat-id='{$item['id']}' ";
		$elements 	 = $this->getElement($index, $item);
		echo "<div data-repeat-item='{$index}' {$idAttribute}>{$elements}</div>";
	}

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
	

}