<?php
/**
 * Representa un campo de descripción
 * @author Oscar Paucara 2009-08-12
 *
 */
class AsyncSearchDisplayField extends AsyncRecordFinderField implements JsonSerializable {

	/**
	 * Constructor with properties initialization
	 *
	 * @param string $name
	 * @param string $label
	 */
	public function __construct($name, $label) {
		parent::__construct($name, $label);
	}
	
	/**
	 * Generate the show_field node 
	 * for the record_title node
	 *
	 * @param DOMDocument $doc
	 * @param record_title $parentNode
	 */
	public function generateXml($doc, $parentNode, $prefix = '') {
		$name = $prefix ? $prefix . '-' . $this->getName() : $this->getName(); 		
		$label = $doc->createElement('show_field');
		$label->setAttribute('name', $name);
		$label->setAttribute('label', $this->getLabel());
		$parentNode->appendChild($label);
	}
	
	public function getJson($type = ''){
		$json = new JsonObject();
		$json->withValue('name', $this->getName());
		$json->withValue('label', $this->getLabel());		
		return $json;
	}
}

?>
