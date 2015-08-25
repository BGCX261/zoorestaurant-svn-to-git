<?php
/**
 * Representa un campo de b�squeda de varias opciones,
 * dentro del buscador.
 * @author Oscar Paucara 2009-08-12
 *
 */
class AsyncSearchFieldOptionList extends AsyncSearchField {
	/**
	 * Contains the list options
	 * @var array $options
	 */
	private $options = array();
	/**
	 * Contains the description field
	 * @var string $optionDescriptionFieldName
	 */
	private $optionDescriptionFieldName;
	
	/**
	 * Constructor with properties initialization
	 * @param string $name
	 * @param string $label
	 * @param string $description
	 */
	public  function __construct($name, $label ,$description){
		parent::__construct($name, $label);
		$this->optionDescriptionFieldName = $description;
	}
	
	/**
	 * Generate the search field node 
	 * of a option_list type
	 * for the search_fields node
	 *
	 * @param DOMDocument $doc
	 * @param search_fields $parentNode
	 */
	public function generateXml($doc, $parentNode, $prefix = '') {
		$name = $prefix ? $prefix . '-' . $this->getName() : $this->getName(); 		
		$tag = $doc->createElement('search_field');
		$tag->setAttribute('type', 'option_list');
		$tag->setAttribute('name', $name);
		$tag->setAttribute('label', $this->getLabel());
		
		$tagChild = $doc->createElement('option_list');
		$tag->appendChild($tagChild);
		
		foreach ($this->options as $option) {
			$tagOption = $doc->createElement('option');
			$tagId = $doc->createElement('id');
			$text = $doc->createTextNode($option->getId());
			$text = $tagId->appendChild($text);
			$tagDesc = $doc->createElement('description');
			$textDesc = $doc->createTextNode($option->getDescription());
			$text = $tagDesc->appendChild($textDesc);
			$tagOption->appendChild($tagId);
			$tagOption->appendChild($tagDesc);
			$tagChild->appendChild($tagOption);
		}
		
		$parentNode->appendChild($tag);
	}
	
	/**
	 * Set a group of options
	 * on collection $options
	 * using AsyncSearchFieldOption instances
	 *
	 * @param collection $optionsList
	 */
	public function setOptions($optionsList) {
		$property = $this->optionDescriptionFieldName;
		foreach ($optionsList as $option) {
			$this->options[count($this->options)] = new AsyncSearchFieldOption($option->getId(), $option->$property);
		}
	}
}
?>
