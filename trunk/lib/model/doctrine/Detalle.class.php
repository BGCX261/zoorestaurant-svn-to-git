<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Detalle extends BaseDetalle implements JsonSerializable
{
	public function getJson($type = '') {
		$json = new JsonObject();
		$json
			->withValue('id', $this->getId())
			->withValue('cantidad', $this->getCantidad())
			->withObject('producto', $this->getProducto());
		return $json;
	}
}