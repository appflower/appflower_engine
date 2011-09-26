<?php

/**
 * sfCombine form base class.
 *
 * @method sfCombine getObject() Returns the current form's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
abstract class BasesfCombineForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'assets_key' => new sfWidgetFormInputHidden(),
      'files'      => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'assets_key' => new sfValidatorPropelChoice(array('model' => 'sfCombine', 'column' => 'assets_key', 'required' => false)),
      'files'      => new sfValidatorString(),
    ));

    $this->widgetSchema->setNameFormat('sf_combine[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfCombine';
  }


}
