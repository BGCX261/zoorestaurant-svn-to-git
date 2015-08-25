<?php

/**
 * Stock form base class.
 *
 * @package    form
 * @subpackage stock
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseStockForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'       => new sfWidgetFormInputHidden(),
      'codigo'   => new sfWidgetFormInput(),
      'cantidad' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'       => new sfValidatorDoctrineChoice(array('model' => 'Stock', 'column' => 'id', 'required' => false)),
      'codigo'   => new sfValidatorString(array('max_length' => 30)),
      'cantidad' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('stock[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Stock';
  }

}
