<?php

/**
 * Mesa form base class.
 *
 * @package    form
 * @subpackage mesa
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseMesaForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'numero'        => new sfWidgetFormInput(),
      'id_estado'     => new sfWidgetFormDoctrineChoice(array('model' => 'EstadoMesa', 'add_empty' => true)),
      'id_mozo'       => new sfWidgetFormDoctrineChoice(array('model' => 'Mozo', 'add_empty' => true)),
      'id_pedido'     => new sfWidgetFormDoctrineChoice(array('model' => 'Pedido', 'add_empty' => true)),
      'fecha_abierta' => new sfWidgetFormDateTime(),
      'fecha_cerrada' => new sfWidgetFormDateTime(),
      'id_restaurant' => new sfWidgetFormDoctrineChoice(array('model' => 'Restaurant', 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorDoctrineChoice(array('model' => 'Mesa', 'column' => 'id', 'required' => false)),
      'numero'        => new sfValidatorInteger(array('required' => false)),
      'id_estado'     => new sfValidatorDoctrineChoice(array('model' => 'EstadoMesa', 'required' => false)),
      'id_mozo'       => new sfValidatorDoctrineChoice(array('model' => 'Mozo', 'required' => false)),
      'id_pedido'     => new sfValidatorDoctrineChoice(array('model' => 'Pedido', 'required' => false)),
      'fecha_abierta' => new sfValidatorDateTime(array('required' => false)),
      'fecha_cerrada' => new sfValidatorDateTime(array('required' => false)),
      'id_restaurant' => new sfValidatorDoctrineChoice(array('model' => 'Restaurant')),
    ));

    $this->widgetSchema->setNameFormat('mesa[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Mesa';
  }

}
