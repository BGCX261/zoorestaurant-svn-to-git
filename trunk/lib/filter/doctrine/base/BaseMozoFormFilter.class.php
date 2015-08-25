<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Mozo filter form base class.
 *
 * @package    filters
 * @subpackage Mozo *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseMozoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'nombre'        => new sfWidgetFormFilterInput(),
      'apellido'      => new sfWidgetFormFilterInput(),
      'id_restaurant' => new sfWidgetFormDoctrineChoice(array('model' => 'Restaurant', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'nombre'        => new sfValidatorPass(array('required' => false)),
      'apellido'      => new sfValidatorPass(array('required' => false)),
      'id_restaurant' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Restaurant', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('mozo_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Mozo';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'nombre'        => 'Text',
      'apellido'      => 'Text',
      'id_restaurant' => 'ForeignKey',
    );
  }
}