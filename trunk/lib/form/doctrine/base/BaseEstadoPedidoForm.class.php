<?php

/**
 * EstadoPedido form base class.
 *
 * @package    form
 * @subpackage estado_pedido
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseEstadoPedidoForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'codigo'        => new sfWidgetFormInput(),
      'nombre'        => new sfWidgetFormInput(),
      'id_restaurant' => new sfWidgetFormDoctrineChoice(array('model' => 'Restaurant', 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorDoctrineChoice(array('model' => 'EstadoPedido', 'column' => 'id', 'required' => false)),
      'codigo'        => new sfValidatorString(array('max_length' => 30)),
      'nombre'        => new sfValidatorString(array('max_length' => 100)),
      'id_restaurant' => new sfValidatorDoctrineChoice(array('model' => 'Restaurant')),
    ));

    $this->widgetSchema->setNameFormat('estado_pedido[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'EstadoPedido';
  }

}
