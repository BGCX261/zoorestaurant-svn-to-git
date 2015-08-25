<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Producto extends BaseProducto implements JsonSerializable
{

	public function getJson($type = '') {
		$json = new JsonObject();
		$json
			->withValue('id', $this->getId())
			->withValue('codigo', $this->getCodigo())
			->withValue('nombre', $this->getNombre())
			->withValue('descripcion', $this->getDescripcion())
			->withValue('precio', $this->getPrecio());
			
		return $json;
		
	}
	
	public function equals($producto) {
		if( get_class($this) == get_class($producto) ) {
			if($this->getId() == $producto->getId()) {
				return true;
			}
		}
		return false;
	}
	
}