<?php

/**
 * afPortalState filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 */
abstract class BaseafPortalStateFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id_xml'      => new sfWidgetFormFilterInput(),
      'user_id'     => new sfWidgetFormFilterInput(),
      'layout_type' => new sfWidgetFormFilterInput(),
      'content'     => new sfWidgetFormFilterInput(),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'id_xml'      => new sfValidatorPass(array('required' => false)),
      'user_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'layout_type' => new sfValidatorPass(array('required' => false)),
      'content'     => new sfValidatorPass(array('required' => false)),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('af_portal_state_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'afPortalState';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'id_xml'      => 'Text',
      'user_id'     => 'Number',
      'layout_type' => 'Text',
      'content'     => 'Text',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
    );
  }
}
