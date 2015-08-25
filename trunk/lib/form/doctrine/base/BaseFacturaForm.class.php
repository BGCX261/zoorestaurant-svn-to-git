<?php

/**
 * Factura form base class.
 *
 * @package    form
 * @subpackage factura
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseFacturaForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'      => new sfWidgetFormInputHidden(),
      'numero'  => new sfWidgetFormInput(),
      'propina' => new sfWidgetFormInput(),
      'fecha'   => new sfWidgetFormDateTime(),
      'id_mesa' => new sfWidgetFormDoctrineChoice(array('model' => 'Mesa', 'add_empty' => false)),
      'id_caja' => new sfWidgetFormDoctrineChoice(array('model' => 'Caja', 'add_empty' => false)),
      'total'   => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'      => new sfValidatorDoctrineChoice(array('model' => 'Factura', 'column' => 'id', 'required' => false)),
      'numero'  => new sfValidatorString(array('max_length' => 255)),
      'propina' => new sfValidatorNumber(array('required' => false)),
      'fecha'   => new sfValidatorDateTime(array('required' => false)),
      'id_mesa' => new sfValidatorDoctrineChoice(array('model' => 'Mesa')),
      'id_caja' => new sfValidatorDoctrineChoice(array('model' => 'Caja')),
      'total'   => new sfValidatorNumber(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('factura[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Factura';
  }

}
