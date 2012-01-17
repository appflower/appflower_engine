<?php



/**
 * This class defines the structure of the 'af_widget_selector' table.
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
class afWidgetSelectorTableMap extends TableMap
{

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'plugins.appFlowerPlugin.lib.model.map.afWidgetSelectorTableMap';

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
		$this->setName('af_widget_selector');
		$this->setPhpName('afWidgetSelector');
		$this->setClassname('afWidgetSelector');
		$this->setPackage('plugins.appFlowerPlugin.lib.model');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addColumn('URL', 'Url', 'VARCHAR', false, 128, null);
		$this->addColumn('PARAMS', 'Params', 'VARCHAR', false, 255, null);
		$this->addForeignKey('CATEGORY_ID', 'CategoryId', 'INTEGER', 'af_widget_category', 'ID', false, null, null);
		$this->addColumn('PERMISSION', 'Permission', 'VARCHAR', false, 255, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
		$this->addRelation('afWidgetCategory', 'afWidgetCategory', RelationMap::MANY_TO_ONE, array('category_id' => 'id', ), 'CASCADE', null);
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
		);
	} // getBehaviors()

} // afWidgetSelectorTableMap
