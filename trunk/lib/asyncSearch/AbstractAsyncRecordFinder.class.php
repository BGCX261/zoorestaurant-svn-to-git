<?php
/**
 * Clase que generará un XML
 * en base a los campos de busqueda, de titulos y descripción
 * definidos en sus propiedades
 * @author Oscar Paucara 2009-08-12
 *
 */

class AbstractAsyncRecordFinder {
	
	
	/**
	 * Contains the title fields to be shown in the record finder dialog
	 * @var array $titleFields
	 */
	protected $titleFields = array();
	/**
	 * Contains the description fields to be shown in the record finder dialog
	 * @var array $descriptionFields
	 */
	private $descriptionFields = array();
	
	
	
	/**
	 * Contains the search fields
	 * @var array $searchFields
	 */
	private $searchFields = array();
	
	
	/**
	 * De DOMDocument used for the XML generation
	 * @var DOMDocument
	 */
	private $doc;
	
	
	/**
	 * Adds a search field
	 * to the collection $searchFields
	 *
	 * @param AsyncSearchField $field
	 */
	public function addSearchField($asyncSearchField) {
		$this->searchFields[] = $asyncSearchField;
	}
	
	
	
	/**
	 * Adds a free text search field 
	 * @param mixed $field Could be string if is a property, or array if it's a field accessed through novigation  
	 * @param $label
	 */
	public function addSearchFieldFree($field, $label, $quantity = "") {
		if (gettype($field) == 'array') {
			if (count($field) == 0) {
				throw new InvalidArgumentException('Invalid field');
			}
			$searchField = new AsyncSearchFieldFree($field[count($field) - 1], $label);
			for ($i = count($field) - 2; $i >= 0; $i--) {
				$searchField = new AsyncSearchRelationField($field[$i], $searchField);
			}
		} else {
			$searchField = new AsyncSearchFieldFree($field, $label);			
		}
		$this->addSearchField($searchField);
	}
	
	
	/**
	 * Adds a display field
	 * to the collection $titleFields
	 *
	 * @param string $field the field name
	 * @param string $label the field label
	 */
	public function addTitleField($field, $label) {
		$this->titleFields[] = new AsyncSearchDisplayField($field, $label);
	}
	
	
	
	/**
	 * Adds a description field
	 * to the collection $titleFields
	 *
	 * @param string $field the field name
	 * @param string $label the field label
	 */
	public function addDescriptionField($field, $label) {
		$this->descriptionFields[] = new AsyncSearchDisplayField($field, $label);
	}
	
	
	/**
	 * Generates an entire XML document
	 *
	 * @param collection $records
	 * @return XML document
	 */
	public function generateXmlConfiguration(){
		$this->setDoc($doc = new DOMDocument());
		$root = $this->getDoc()->createElement('record_finder');
		$root = $this->getDoc()->appendChild($root);
		
		$title = $this->getDoc()->createElement('title');
		$title = $root->appendChild($title);
		$title->appendChild($doc->createTextNode($this->getTitle()));
		//$title->appendChild($doc->createTextNode("Titulo"));
		$config = $this->getDoc()->createElement('configuration');
		$config = $root->appendChild($config);
		$searchFieldTitle = $this->getDoc()->createElement('search_fields');
		$searchFieldTitle = $config->appendChild($searchFieldTitle);
		
		foreach ($this->searchFields as $searchField) {
			$searchField->generateXml($this->getDoc(), $searchFieldTitle );
		}
		$searchDiplayTitle = $this->getDoc()->createElement('display_fields');
		$searchDiplayTitle = $config->appendChild($searchDiplayTitle);
		$searchRecordTitle = $this->getDoc()->createElement('record_title');
		$searchRecordTitle = $searchDiplayTitle->appendChild($searchRecordTitle);
		
		foreach ($this->titleFields as $titleField) {
			$titleField->generateXml($this->getDoc(), $searchRecordTitle );
		}
		$searchRecordDescription = $this->getDoc()->createElement('record_description');
		$searchRecordDescription = $searchDiplayTitle->appendChild($searchRecordDescription);
		
		foreach ($this->descriptionFields as $descriptionField) {
			$descriptionField->generateXml($this->getDoc(), $searchRecordDescription );
		}
		
		$data = $this->getDoc()->createElement('data');
		$data = $root->appendChild($data);
		
		
		$mapper = new AsyncSearchRecordMapper();
		foreach ($this->searchFields as $searchField) {
			$mapper->addField($searchField);
		}
		foreach ($this->titleFields as $titleField) {
			$mapper->addField($titleField);
		}
		foreach ($this->descriptionFields as $descriptionField) {
			$mapper->addField($descriptionField);
		}
		
		$records = $this->getRecords();	
		foreach ($records as $record){
				$mapper->generateXml($this->getDoc(), $data, $record);
		}
		
		return $this->getDoc()->saveXML($root);
	}
	
	/**
	 * Returns the working document
	 * @return DOMDocument
	 */
	private function getDoc() {
		return $this->doc;
	}
	
	/**
	 * Set the working document
	 * @param DOMDocument $doc
	 */
	private function setDoc($doc) {
		$this->doc = $doc;
	}
	
	/*
	 * Devuelve en una coleccion los campos de búsqueda
	 */
	public function searchFields(){
		$collection = new SearchableReadonlyCollection();
		$collection->addAll($this->searchFields);
		return $collection;
	}
	
	/*
	 * Arma un json con los registros
	 * $records colleccion que viene de la query
	 *  @return json 
	 */
	public function generateJsonConfiguration($records){
		//$this->setDoc($doc = new DOMDocument());
		$json = new JsonObject();
		$json->withValue('record_finder', 'record_finder');
		
		$json->withValue('title', $this->getTitle());
		
		$collectionSearchFields = new SearchableReadonlyCollection();
		$collectionSearchFields->addAll($this->searchFields);
		$json->withObject('search_fields', $this->searchFields());
	
		$collectionTitles = new SearchableReadonlyCollection();
		$collectionTitles->addAll($this->titleFields);
		$json->withObject('record_title', $collectionTitles);
		
		$collectionDescriptions = new SearchableReadonlyCollection();
		$collectionDescriptions->addAll($this->descriptionFields);
		$json->withObject('record_description', $collectionDescriptions);
		
		$mapper = new AsyncSearchRecordMapper();
		foreach ($this->searchFields as $searchField) {
			$mapper->addField($searchField);
		}
		foreach ($this->titleFields as $titleField) {
			$mapper->addField($titleField);
		}
		foreach ($this->descriptionFields as $descriptionField) {
			$mapper->addField($descriptionField);
		}
		
		$jsonArray = new JsonArray();
		
		if($records != null) {
			foreach($records as $record) {
				$jsonObject = $mapper->generateJson($record);
				$jsonArray->addObject($jsonObject);
			}
		}
		$json->addArray('records', $jsonArray);
		return $json;
	}
	
	/*
	 * Devuelve en una coleccion los campos de título
	 */
	public function titleFields(){
		$collection = new SearchableReadonlyCollection();
		$collection->addAll($this->titleFields);
		return $collection;
	}
}

