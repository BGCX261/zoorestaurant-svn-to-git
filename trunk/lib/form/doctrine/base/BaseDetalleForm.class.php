<?php

/**
 * Detalle form base class.
 *
 * @package    form
 * @subpackage detalle
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseDetalleForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'id_producto' => new sfWidgetFormDoctrineChoice(array('model' => 'Producto', 'add_empty' => true)),
      'cantidad'    => new sfWidgetFormInput(),
      'id_pedido'   => new sfWidgetFormDoctrineChoice(array('model' => 'Pedido', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => 'Detalle', 'column' => 'id', 'required' => false)),
      'id_producto' => new sfValidatorDoctrineChoice(array('model' => 'Producto', 'required' => false)),
      'cantidad'    => new sfValidatorInteger(array('required' => false)),
      'id_pedido'   => new sfValidatorDoctrineChoice(array('model' => 'Pedido', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('detalle[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Detalle';
  }

}
