<?php



/**
 * This class defines the structure of the 'af_widget_help_settings' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.plugins.appFlowerPlugin.lib.model.map
 */
class afWidgetHelpSettingsTableMap extends TableMap
{

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'plugins.appFlowerPlugin.lib.model.map.afWidgetHelpSettingsTableMap';

	/**
	 * Initialize the table attributes, columns and validators
	 * Relations are not initialized by this method since they are lazy loaded
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function initialize()
	{
		// attributes
		$this->setName('af_widget_help_settings');
		$this->setPhpName('afWidgetHelpSettings');
		$this->setClassname('afWidgetHelpSettings');
		$this->setPackage('plugins.appFlowerPlugin.lib.model');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addColumn('USER_ID', 'UserId', 'INTEGER', false, null, null);
		$this->addColumn('WIDGET_HELP_IS_ENABLED', 'WidgetHelpIsEnabled', 'BOOLEAN', false, 1, true);
		$this->addColumn('POPUP_HELP_IS_ENABLED', 'PopupHelpIsEnabled', 'BOOLEAN', false, 1, true);
		$this->addColumn('HELP_TYPE', 'HelpType', 'TINYINT', false, null, 1);
		$this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
		$this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
	} // buildRelations()

	/**
	 *
	 * Gets the list of behaviors registered for this table
	 *
	 * @return array Associative array (name => parameters) of behaviors
	 */
	public function getBehaviors()
	{
		return array(
			'symfony' => array('form' => 'true', 'filter' => 'true', ),
			'symfony_behaviors' => array(),
			'symfony_timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', ),
		);
	} // getBehaviors()

} // afWidgetHelpSettingsTableMap
