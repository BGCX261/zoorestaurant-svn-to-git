<?php

/**
 * ParrillaLibre form base class.
 *
 * @package    form
 * @subpackage parrilla_libre
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseParrillaLibreForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'codigo'        => new sfWidgetFormInput(),
      'nombre'        => new sfWidgetFormInput(),
      'descripcion'   => new sfWidgetFormInput(),
      'precio'        => new sfWidgetFormInput(),
      'id_restaurant' => new sfWidgetFormDoctrineChoice(array('model' => 'Restaurant', 'add_empty' => false)),
      'type'          => new sfWidgetFormInput(),
      'capacidad'     => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorDoctrineChoice(array('model' => 'ParrillaLibre', 'column' => 'id', 'required' => false)),
      'codigo'        => new sfValidatorString(array('max_length' => 30)),
      'nombre'        => new sfValidatorString(array('max_length' => 255)),
      'descripcion'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'precio'        => new sfValidatorNumber(),
      'id_restaurant' => new sfValidatorDoctrineChoice(array('model' => 'Restaurant')),
      'type'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'capacidad'     => new sfValidatorString(array('max_length' => 10, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('parrilla_libre[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ParrillaLibre';
  }

}
