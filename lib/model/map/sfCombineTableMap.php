<?php



/**
 * This class defines the structure of the 'sf_combine' table.
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
class sfCombineTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'plugins.appFlowerPlugin.lib.model.map.sfCombineTableMap';

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
		$this->setName('sf_combine');
		$this->setPhpName('sfCombine');
		$this->setClassname('sfCombine');
		$this->setPackage('plugins.appFlowerPlugin.lib.model');
		$this->setUseIdGenerator(false);
		// columns
		$this->addPrimaryKey('ASSETS_KEY', 'AssetsKey', 'VARCHAR', true, 32, null);
		$this->addColumn('FILES', 'Files', 'LONGVARCHAR', true, null, null);
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
		);
	} // getBehaviors()

} // sfCombineTableMap
