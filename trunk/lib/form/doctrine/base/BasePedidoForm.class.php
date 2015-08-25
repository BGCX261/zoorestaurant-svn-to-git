<?php

/**
 * Pedido form base class.
 *
 * @package    form
 * @subpackage pedido
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BasePedidoForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'numero'    => new sfWidgetFormInput(),
      'fecha'     => new sfWidgetFormDateTime(),
      'id_estado' => new sfWidgetFormDoctrineChoice(array('model' => 'EstadoPedido', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorDoctrineChoice(array('model' => 'Pedido', 'column' => 'id', 'required' => false)),
      'numero'    => new sfValidatorInteger(array('required' => false)),
      'fecha'     => new sfValidatorDateTime(array('required' => false)),
      'id_estado' => new sfValidatorDoctrineChoice(array('model' => 'EstadoPedido', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('pedido[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Pedido';
  }

}
