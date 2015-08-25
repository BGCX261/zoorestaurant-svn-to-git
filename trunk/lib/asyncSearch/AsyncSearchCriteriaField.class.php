<?php
/**
 * 
 * @author Luciano Sifredo
 * Campo que se utiliza en el record finder dinamico
 * Cada cierta cantidad de caracteres efectua los request
 *
 */
class AsyncSearchCriteriaField extends AsyncSearchField implements JsonSerializable {
	
	
	/**
	 * @var $searchCriteria SearchCriteria
	 * Criterio de busqueda
	 */
	private $searchCriteria;
	
	/**
	 * @var $quantity int
	 * Cada cuantos caracteres hace el request 
	 */
	private $quantity;
	
	/*
	 * Constructor
	 */
	public function __construct($name, $label, $quantity) {
		$this->quantity = $quantity;
		parent::__construct($name, $label);
	}
	
	/*
	 * devuelve el criterio de busqueda
	 */
	public function getSearchCriteria() {
		return $this->searchCriteria;
	}
	
	/*
	 * setea el criterio de busqueda
	 */	
	public function setSearchCriteria($searchCriteria) {
		$this->searchCriteria = $searchCriteria;
	}
	
	/*
	 * Genera el xml para el campo
	 */
	public function generateXML($doc, $parentNode, $prefix = ''){
		$name = $prefix ? $prefix . '-' . $this->getName() : $this->getName(); 		
		$label = $doc->createElement('search_field');
		$label->setAttribute('type', $this->getType());
		$label->setAttribute('name', $name);
		$label->setAttribute('label', $this->getLabel());
		$label->setAttribute('quantity', $this->getQuantity());
		$parentNode->appendChild($label);
	}
	
	/**
	 * Devuelve la cantidad de caracteres 
	 *
	 */
	public function getQuantity() {
		return $this->quantity;		
	}
	
	/*
	 * Devuelve el tipo
	 */
	public function getType() {
		return 'free';		
	}
	
	/*
	 * Devuelve el json
	 */
	public function getJson($type = ''){
		$json = new JsonObject();
		
		$json->withValue('type', $this->getType());
		$json->withValue('name', $this->getName());
		$json->withValue('label', $this->getLabel());		
		
		return $json;
	}
	
}