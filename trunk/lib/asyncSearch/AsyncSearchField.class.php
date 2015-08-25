<?php
/**
 * Esta clase representa un campo de b�squeda dentro del buscador.
 * @author Oscar Paucara 2009-08-12
 *
 */
abstract class AsyncSearchField extends AsyncRecordFinderField {

	/**
	 * Constructor con inicializaci�n de propiedades
	 *
	 * @param string $name
	 * @param string $label
	 */
	public function __construct($name, $label) {
		parent::__construct($name, $label);
	}
	
}

?>
