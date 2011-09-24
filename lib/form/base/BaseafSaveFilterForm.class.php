<?php

/**
 * afSaveFilter form base class.
 *
 * @method afSaveFilter getObject() Returns the current form's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
abstract class BaseafSaveFilterForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'     => new sfWidgetFormInputHidden(),
      'name'   => new sfWidgetFormInputText(),
      'user'   => new sfWidgetFormInputText(),
      'path'   => new sfWidgetFormInputText(),
      'title'  => new sfWidgetFormInputText(),
      'filter' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'     => new sfValidatorPropelChoice(array('model' => 'afSaveFilter', 'column' => 'id', 'required' => false)),
      'name'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'user'   => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'path'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'title'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'filter' => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('af_save_filter[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'afSaveFilter';
  }


}
