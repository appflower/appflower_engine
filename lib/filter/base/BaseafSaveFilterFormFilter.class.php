<?php

/**
 * afSaveFilter filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 */
abstract class BaseafSaveFilterFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'   => new sfWidgetFormFilterInput(),
      'user'   => new sfWidgetFormFilterInput(),
      'path'   => new sfWidgetFormFilterInput(),
      'title'  => new sfWidgetFormFilterInput(),
      'filter' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'   => new sfValidatorPass(array('required' => false)),
      'user'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'path'   => new sfValidatorPass(array('required' => false)),
      'title'  => new sfValidatorPass(array('required' => false)),
      'filter' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('af_save_filter_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'afSaveFilter';
  }

  public function getFields()
  {
    return array(
      'id'     => 'Number',
      'name'   => 'Text',
      'user'   => 'Number',
      'path'   => 'Text',
      'title'  => 'Text',
      'filter' => 'Text',
    );
  }
}
