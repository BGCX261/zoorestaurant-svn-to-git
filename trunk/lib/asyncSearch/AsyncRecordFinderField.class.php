<?php
/**
 * La clase abstracta que define las propiedades
 * label y name
 * @author Oscar Paucara 2009-08-12
 *
 */
abstract class AsyncRecordFinderField {

	/**
	 * Es la etiqueta que se muestra delante del campo
	 * @var string
	 */
	private $label;
	
	/**
	 * Nombre de la propiedad del registro a la que hace referencia el campo
	 * @var string
	 */
	private $name;
	
	/**
	 * Constructor with properties initialization
	 * @param string $name
	 * @param string $label
	 */
	public function __construct($name, $label) {
		$this->name = $name;
		$this->label = $label;
	}
	
	abstract public function generateXml($doc, $parentNode, $prefix = '');
	
	/**
	 * Returns the property label
	 * @return $label
	 */
	public function getLabel() {
		return $this->label;
	}
	
	/**
	 * Returns the property name
	 * @return $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Extract the value of the field from a record.
	 * @param Doctrine_Record $record
	 * @return mixed
	 */
	public function getValue($record) {
		$getter = 'get' . ucfirst($this->getName());
		return $record->__call($getter, array());
	}
	
	/**
	 * Returns the fully qualified name
	 * @return string
	 */
	public function completeName() {
		return $this->getName();
	}

}

?>
