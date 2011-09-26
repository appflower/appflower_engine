<?php

/**
 * afWidgetCategory filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 */
abstract class BaseafWidgetCategoryFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'module' => new sfWidgetFormFilterInput(),
      'name'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'module' => new sfValidatorPass(array('required' => false)),
      'name'   => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('af_widget_category_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'afWidgetCategory';
  }

  public function getFields()
  {
    return array(
      'id'     => 'Number',
      'module' => 'Text',
      'name'   => 'Text',
    );
  }
}
