<?php

/**
 * Mozo form base class.
 *
 * @package    form
 * @subpackage mozo
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseMozoForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'nombre'        => new sfWidgetFormInput(),
      'apellido'      => new sfWidgetFormInput(),
      'id_restaurant' => new sfWidgetFormDoctrineChoice(array('model' => 'Restaurant', 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorDoctrineChoice(array('model' => 'Mozo', 'column' => 'id', 'required' => false)),
      'nombre'        => new sfValidatorString(array('max_length' => 255)),
      'apellido'      => new sfValidatorString(array('max_length' => 255)),
      'id_restaurant' => new sfValidatorDoctrineChoice(array('model' => 'Restaurant')),
    ));

    $this->widgetSchema->setNameFormat('mozo[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Mozo';
  }

}
