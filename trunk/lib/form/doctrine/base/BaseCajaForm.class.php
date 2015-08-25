<?php

/**
 * Caja form base class.
 *
 * @package    form
 * @subpackage caja
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseCajaForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'id_factura'    => new sfWidgetFormInput(),
      'fecha_abierta' => new sfWidgetFormDateTime(),
      'fecha_cerrada' => new sfWidgetFormDateTime(),
      'id_estado'     => new sfWidgetFormDoctrineChoice(array('model' => 'EstadoCaja', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorDoctrineChoice(array('model' => 'Caja', 'column' => 'id', 'required' => false)),
      'id_factura'    => new sfValidatorInteger(array('required' => false)),
      'fecha_abierta' => new sfValidatorDateTime(array('required' => false)),
      'fecha_cerrada' => new sfValidatorDateTime(array('required' => false)),
      'id_estado'     => new sfValidatorDoctrineChoice(array('model' => 'EstadoCaja', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('caja[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Caja';
  }

}
