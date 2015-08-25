<?php
/**
 * Se encarga de crear los nodos de cada registro
 * de los resultados, en el nodo data
 * @author Oscar Paucara 2009-08-12
 *
 */
class AsyncSearchRecordMapper {
	/**
	 * An Id of an option
	 * @var array $id
	 */
	private $fields = array();
	
	/**
	 * Generate the record node for
	 * the data node
	 * @param DOMDocument $doc
	 * @param DOMNode $parentNode
	 * @param Object $record
	 */
	public function generateXml($doc, $parentNode, $record) {
		$tagRecord = $doc->createElement('record');
		$tagRecord->setAttribute('id', $record->getId());
		$previous = array();
		foreach ($this->fields as $field) {
			if (!in_array($field->completeName(), $previous)){
				$tag = $doc->createElement($field->completeName());
				$text = $doc->createTextNode($field->getValue($record));
				$tag->appendChild($text);
				$tagRecord->appendChild($tag);
			}
			$previous[count($previous)] = $field->getName();
		}
		$text = $parentNode->appendChild($tagRecord);
	}
	
	/**
	 * Adds a field to the
	 * collection $fields
	 *
	 * @param AsyncRecordFinderField $asyncRecordFinderField
	 */
	public function addField($asyncRecordFinderField){
		$this->fields[count($this->fields)] = $asyncRecordFinderField;
	}
	
	
	
	/**
	 * Genera el json con los registros que vienen de la coleccion 
	 *
	 */
	public function generateJson($record) {
               $json = new JsonObject();
               $json->addValue('id', $record->getId());
               $previous = array();
               foreach ($this->fields as $field) {
                       if (!in_array($field->completeName(), $previous)){
                               $json->addValue($field->completeName(), $field->getValue($record));
                       }
                       $previous[count($previous)] = $field->getName();
               }
               return $json;
    }
	
	
}

?>