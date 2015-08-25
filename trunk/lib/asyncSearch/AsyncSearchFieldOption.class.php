<?php
/**
 * Representa una opcion del array $options
 * de la clase AsyncSearchFieldOptionList
 * @author Oscar Paucara 2009-08-12
 *
 */
class AsyncSearchFieldOption {
	/**
	 * An Id of an option
	 * @var int $id
	 */
	private $id;
	/**
	 * A description of an option
	 * @var int $id
	 */
	private $description;
	
	/**
	 * Constructor with properties initialization
	 *
	 * @param int $id
	 * @param string $name
	 */
	public function __construct($id, $name){
		$this->id = $id;
		$this->description = $name;
	}
	/**
	 * Returns the fieldOption Id
	 * @return $id
	 */
	public function getId() {
		return $this->id;
	}
	/**
	 * Returns the fieldOption description
	 * @return $description
	 */
	public function getDescription() {
		return $this->description;
	}
}

?>
