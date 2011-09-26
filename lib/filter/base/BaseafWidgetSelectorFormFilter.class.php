<?php

/**
 * afWidgetSelector filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 */
abstract class BaseafWidgetSelectorFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'url'         => new sfWidgetFormFilterInput(),
      'params'      => new sfWidgetFormFilterInput(),
      'category_id' => new sfWidgetFormPropelChoice(array('model' => 'afWidgetCategory', 'add_empty' => true)),
      'permission'  => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'url'         => new sfValidatorPass(array('required' => false)),
      'params'      => new sfValidatorPass(array('required' => false)),
      'category_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'afWidgetCategory', 'column' => 'id')),
      'permission'  => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('af_widget_selector_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'afWidgetSelector';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'url'         => 'Text',
      'params'      => 'Text',
      'category_id' => 'ForeignKey',
      'permission'  => 'Text',
    );
  }
}
