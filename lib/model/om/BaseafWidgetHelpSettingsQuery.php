<?php


/**
 * Base class that represents a query for the 'af_widget_help_settings' table.
 *
 * 
 *
 * @method     afWidgetHelpSettingsQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     afWidgetHelpSettingsQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     afWidgetHelpSettingsQuery orderByWidgetHelpIsEnabled($order = Criteria::ASC) Order by the widget_help_is_enabled column
 * @method     afWidgetHelpSettingsQuery orderByPopupHelpIsEnabled($order = Criteria::ASC) Order by the popup_help_is_enabled column
 * @method     afWidgetHelpSettingsQuery orderByHelpType($order = Criteria::ASC) Order by the help_type column
 * @method     afWidgetHelpSettingsQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     afWidgetHelpSettingsQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     afWidgetHelpSettingsQuery groupById() Group by the id column
 * @method     afWidgetHelpSettingsQuery groupByUserId() Group by the user_id column
 * @method     afWidgetHelpSettingsQuery groupByWidgetHelpIsEnabled() Group by the widget_help_is_enabled column
 * @method     afWidgetHelpSettingsQuery groupByPopupHelpIsEnabled() Group by the popup_help_is_enabled column
 * @method     afWidgetHelpSettingsQuery groupByHelpType() Group by the help_type column
 * @method     afWidgetHelpSettingsQuery groupByCreatedAt() Group by the created_at column
 * @method     afWidgetHelpSettingsQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     afWidgetHelpSettingsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     afWidgetHelpSettingsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     afWidgetHelpSettingsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     afWidgetHelpSettings findOne(PropelPDO $con = null) Return the first afWidgetHelpSettings matching the query
 * @method     afWidgetHelpSettings findOneOrCreate(PropelPDO $con = null) Return the first afWidgetHelpSettings matching the query, or a new afWidgetHelpSettings object populated from the query conditions when no match is found
 *
 * @method     afWidgetHelpSettings findOneById(int $id) Return the first afWidgetHelpSettings filtered by the id column
 * @method     afWidgetHelpSettings findOneByUserId(int $user_id) Return the first afWidgetHelpSettings filtered by the user_id column
 * @method     afWidgetHelpSettings findOneByWidgetHelpIsEnabled(boolean $widget_help_is_enabled) Return the first afWidgetHelpSettings filtered by the widget_help_is_enabled column
 * @method     afWidgetHelpSettings findOneByPopupHelpIsEnabled(boolean $popup_help_is_enabled) Return the first afWidgetHelpSettings filtered by the popup_help_is_enabled column
 * @method     afWidgetHelpSettings findOneByHelpType(int $help_type) Return the first afWidgetHelpSettings filtered by the help_type column
 * @method     afWidgetHelpSettings findOneByCreatedAt(string $created_at) Return the first afWidgetHelpSettings filtered by the created_at column
 * @method     afWidgetHelpSettings findOneByUpdatedAt(string $updated_at) Return the first afWidgetHelpSettings filtered by the updated_at column
 *
 * @method     array findById(int $id) Return afWidgetHelpSettings objects filtered by the id column
 * @method     array findByUserId(int $user_id) Return afWidgetHelpSettings objects filtered by the user_id column
 * @method     array findByWidgetHelpIsEnabled(boolean $widget_help_is_enabled) Return afWidgetHelpSettings objects filtered by the widget_help_is_enabled column
 * @method     array findByPopupHelpIsEnabled(boolean $popup_help_is_enabled) Return afWidgetHelpSettings objects filtered by the popup_help_is_enabled column
 * @method     array findByHelpType(int $help_type) Return afWidgetHelpSettings objects filtered by the help_type column
 * @method     array findByCreatedAt(string $created_at) Return afWidgetHelpSettings objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return afWidgetHelpSettings objects filtered by the updated_at column
 *
 * @package    propel.generator.plugins.appFlowerPlugin.lib.model.om
 */
abstract class BaseafWidgetHelpSettingsQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseafWidgetHelpSettingsQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'propel', $modelName = 'afWidgetHelpSettings', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new afWidgetHelpSettingsQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    afWidgetHelpSettingsQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof afWidgetHelpSettingsQuery) {
			return $criteria;
		}
		$query = new afWidgetHelpSettingsQuery();
		if (null !== $modelAlias) {
			$query->setModelAlias($modelAlias);
		}
		if ($criteria instanceof Criteria) {
			$query->mergeWith($criteria);
		}
		return $query;
	}

	/**
	 * Find object by primary key
	 * Use instance pooling to avoid a database query if the object exists
	 * <code>
	 * $obj  = $c->findPk(12, $con);
	 * </code>
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    afWidgetHelpSettings|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = afWidgetHelpSettingsPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
			// the object is alredy in the instance pool
			return $obj;
		} else {
			// the object has not been requested yet, or the formatter is not an object formatter
			$criteria = $this->isKeepQuery() ? clone $this : $this;
			$stmt = $criteria
				->filterByPrimaryKey($key)
				->getSelectStatement($con);
			return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
		}
	}

	/**
	 * Find objects by primary key
	 * <code>
	 * $objs = $c->findPks(array(12, 56, 832), $con);
	 * </code>
	 * @param     array $keys Primary keys to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    PropelObjectCollection|array|mixed the list of results, formatted by the current formatter
	 */
	public function findPks($keys, $con = null)
	{	
		$criteria = $this->isKeepQuery() ? clone $this : $this;
		return $this
			->filterByPrimaryKeys($keys)
			->find($con);
	}

	/**
	 * Filter the query by primary key
	 *
	 * @param     mixed $key Primary key to use for the query
	 *
	 * @return    afWidgetHelpSettingsQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(afWidgetHelpSettingsPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    afWidgetHelpSettingsQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(afWidgetHelpSettingsPeer::ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the id column
	 * 
	 * @param     int|array $id The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afWidgetHelpSettingsQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(afWidgetHelpSettingsPeer::ID, $id, $comparison);
	}

	/**
	 * Filter the query on the user_id column
	 * 
	 * @param     int|array $userId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afWidgetHelpSettingsQuery The current query, for fluid interface
	 */
	public function filterByUserId($userId = null, $comparison = null)
	{
		if (is_array($userId)) {
			$useMinMax = false;
			if (isset($userId['min'])) {
				$this->addUsingAlias(afWidgetHelpSettingsPeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userId['max'])) {
				$this->addUsingAlias(afWidgetHelpSettingsPeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(afWidgetHelpSettingsPeer::USER_ID, $userId, $comparison);
	}

	/**
	 * Filter the query on the widget_help_is_enabled column
	 * 
	 * @param     boolean|string $widgetHelpIsEnabled The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afWidgetHelpSettingsQuery The current query, for fluid interface
	 */
	public function filterByWidgetHelpIsEnabled($widgetHelpIsEnabled = null, $comparison = null)
	{
		if (is_string($widgetHelpIsEnabled)) {
			$widget_help_is_enabled = in_array(strtolower($widgetHelpIsEnabled), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(afWidgetHelpSettingsPeer::WIDGET_HELP_IS_ENABLED, $widgetHelpIsEnabled, $comparison);
	}

	/**
	 * Filter the query on the popup_help_is_enabled column
	 * 
	 * @param     boolean|string $popupHelpIsEnabled The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afWidgetHelpSettingsQuery The current query, for fluid interface
	 */
	public function filterByPopupHelpIsEnabled($popupHelpIsEnabled = null, $comparison = null)
	{
		if (is_string($popupHelpIsEnabled)) {
			$popup_help_is_enabled = in_array(strtolower($popupHelpIsEnabled), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(afWidgetHelpSettingsPeer::POPUP_HELP_IS_ENABLED, $popupHelpIsEnabled, $comparison);
	}

	/**
	 * Filter the query on the help_type column
	 * 
	 * @param     int|array $helpType The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afWidgetHelpSettingsQuery The current query, for fluid interface
	 */
	public function filterByHelpType($helpType = null, $comparison = null)
	{
		if (is_array($helpType)) {
			$useMinMax = false;
			if (isset($helpType['min'])) {
				$this->addUsingAlias(afWidgetHelpSettingsPeer::HELP_TYPE, $helpType['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($helpType['max'])) {
				$this->addUsingAlias(afWidgetHelpSettingsPeer::HELP_TYPE, $helpType['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(afWidgetHelpSettingsPeer::HELP_TYPE, $helpType, $comparison);
	}

	/**
	 * Filter the query on the created_at column
	 * 
	 * @param     string|array $createdAt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afWidgetHelpSettingsQuery The current query, for fluid interface
	 */
	public function filterByCreatedAt($createdAt = null, $comparison = null)
	{
		if (is_array($createdAt)) {
			$useMinMax = false;
			if (isset($createdAt['min'])) {
				$this->addUsingAlias(afWidgetHelpSettingsPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($createdAt['max'])) {
				$this->addUsingAlias(afWidgetHelpSettingsPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(afWidgetHelpSettingsPeer::CREATED_AT, $createdAt, $comparison);
	}

	/**
	 * Filter the query on the updated_at column
	 * 
	 * @param     string|array $updatedAt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afWidgetHelpSettingsQuery The current query, for fluid interface
	 */
	public function filterByUpdatedAt($updatedAt = null, $comparison = null)
	{
		if (is_array($updatedAt)) {
			$useMinMax = false;
			if (isset($updatedAt['min'])) {
				$this->addUsingAlias(afWidgetHelpSettingsPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($updatedAt['max'])) {
				$this->addUsingAlias(afWidgetHelpSettingsPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(afWidgetHelpSettingsPeer::UPDATED_AT, $updatedAt, $comparison);
	}

	/**
	 * Exclude object from result
	 *
	 * @param     afWidgetHelpSettings $afWidgetHelpSettings Object to remove from the list of results
	 *
	 * @return    afWidgetHelpSettingsQuery The current query, for fluid interface
	 */
	public function prune($afWidgetHelpSettings = null)
	{
		if ($afWidgetHelpSettings) {
			$this->addUsingAlias(afWidgetHelpSettingsPeer::ID, $afWidgetHelpSettings->getId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseafWidgetHelpSettingsQuery
