<?php

/**
 * Mesa form.
 *
 * @package    form
 * @subpackage Mesa
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class MesaForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'numero'        => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'numero'        => new sfValidatorInteger(array('required' => false)),
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