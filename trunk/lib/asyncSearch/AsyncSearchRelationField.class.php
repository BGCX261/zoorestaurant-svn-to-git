<?php
/**
 * Provides the abillity of search by a field of a related object.
 * v.g. If you have an order, and the order is related to a patient
 * type, you could search by the name of the patient type.
 * It's a kind of decorator.
 * 
 * @author Matías Castilla 2010-01-27
 *
 */
class AsyncSearchRelationField extends AsyncRecordFinderField {
	
	/**
	 * @var AsyncRecordFinderField The field in the relation
	 * 
	 */
	private $field;
	
	/**
	 * Constructor with properties initialization.
	 * @param string $name
	 * @param sting $label
	 * @param AsyncRecordFinderField $field
	 * @return unknown_type
	 */
	public function __construct($name, $field) {
		//TODO Refactor: label is not necessary in this class
		parent::__construct($name, '');
		$this->setField($field);
	}
	
	/**
	 * Extract the value of the field from a record.
	 * @param Doctrine_Record $record
	 * @return mixed
	 */
	public function getValue($record) {
		if (!$this->getField()) {
			throw new IllegalStateException('Not field defined por relation ' . $this->getName());
		}
		$getter = 'get' . ucfirst($this->getName());
		return $this->getField()->getValue($record->__call($getter, array()));
	}
	
	/**
	 * Generate the search field node 
	 * of a option_list type
	 * for the search_fields node
	 *
	 * @param DOMDocument $doc
	 * @param DOMElement $parentNode
	 */
	public function generateXml($doc, $parentNode, $prefix = '') {
		$name = $prefix ? $prefix . '-' . $this->getName() : $this->getName(); 		
		return $this->getField()->generateXml($doc, $parentNode, $name);
	}
	
	/**
	 * @return AsyncRecordFinderField
	 */
	public function getField() {
		return $this->field;
	}

	/**
	 * @param AsyncRecordFinderField $field the field to set
	 */
	public function setField($field) {
		$this->field = $field;
	}

	/**
	 * (non-PHPdoc)
	 * @see asyncSearch/AsyncRecordFinderField#completeName()
	 */
	public function completeName() {
		return $this->getName() . '-' . $this->getField()->getName();
	}
	
	
}