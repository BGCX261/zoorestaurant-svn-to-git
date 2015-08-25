<?php
/*
 * Crea el buscador con todos los registros
 */

class AsyncRecordFinder extends AbstractAsyncRecordFinder {
	
	
	/**
	 * The title to be shown in the record finder dialog
	 * @var $title
	 */
	private $title;
	
	/*
	 * coleccion de registros
	 */
	private $records;
	
	/**
	 * Constructor with properties initialization
	 * @param string $title
	 */
	public  function __construct($title, $records) {
		$this->title = $title;
		$this->records = $records;
	}

	/*
	 * Devuelve el titulo  
	 *
	 */
	public function getTitle() {
		return $this->title;
	}
	
	/*
	 * Devuelve la coleccion de registros que se van a mostrar
	 */
	public function getRecords() {
		return $this->records;
	}
	
}

?>
