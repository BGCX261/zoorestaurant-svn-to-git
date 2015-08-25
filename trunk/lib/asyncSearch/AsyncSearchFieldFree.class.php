<?php
/**
 * Representa un campo de b�squeda unico dentro del buscador.
 * @author Oscar Paucara 2009-08-12
 */
class AsyncSearchFieldFree extends AsyncSearchField {

	/**
	 * Constructor with properties initialization
	 * @param string $name
	 * @param string $label
	 */
	public function __construct($name, $label) {
		parent::__construct($name, $label);
	}
	
	/**
	 * Generate the search field node 
	 * of a free type
	 * for the search_fields node
	 *
	 * @param DOMDocument $doc
	 * @param search_fields $parentNode
	 */
	public function generateXml($doc, $parentNode, $prefix = '') {
		$name = $prefix ? $prefix . '-' . $this->getName() : $this->getName(); 		
		$label = $doc->createElement('search_field');
		$label->setAttribute('type', $this->getType());
		$label->setAttribute('name', $name);
		$label->setAttribute('label', $this->getLabel());
		$label->setAttribute('quantity', "");
		$parentNode->appendChild($label);
	}
	
	public function getType() {
		return 'free';		
	}
	
}

?>
