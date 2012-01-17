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
	 * Find object by primary key.
	 * Propel uses the instance pool to skip the database if the object exists.
	 * Go fast if the query is untouched.
	 *
	 * <code>
	 * $obj  = $c->findPk(12, $con);
	 * </code>
	 *
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    afWidgetHelpSettings|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = afWidgetHelpSettingsPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(afWidgetHelpSettingsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
		$this->basePreSelect($con);
		if ($this->formatter || $this->modelAlias || $this->with || $this->select
		 || $this->selectColumns || $this->asColumns || $this->selectModifiers
		 || $this->map || $this->having || $this->joins) {
			return $this->findPkComplex($key, $con);
		} else {
			return $this->findPkSimple($key, $con);
		}
	}

	/**
	 * Find object by primary key using raw SQL to go fast.
	 * Bypass doSelect() and the object formatter by using generated code.
	 *
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con A connection object
	 *
	 * @return    afWidgetHelpSettings A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `USER_ID`, `WIDGET_HELP_IS_ENABLED`, `POPUP_HELP_IS_ENABLED`, `HELP_TYPE`, `CREATED_AT`, `UPDATED_AT` FROM `af_widget_help_settings` WHERE `ID` = :p0';
		try {
			$stmt = $con->prepare($sql);
			$stmt->bindValue(':p0', $key, PDO::PARAM_INT);
			$stmt->execute();
		} catch (Exception $e) {
			Propel::log($e->getMessage(), Propel::LOG_ERR);
			throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
		}
		$obj = null;
		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$obj = new afWidgetHelpSettings();
			$obj->hydrate($row);
			afWidgetHelpSettingsPeer::addInstanceToPool($obj, (string) $row[0]);
		}
		$stmt->closeCursor();

		return $obj;
	}

	/**
	 * Find object by primary key.
	 *
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con A connection object
	 *
	 * @return    afWidgetHelpSettings|array|mixed the result, formatted by the current formatter
	 */
	protected function findPkComplex($key, $con)
	{
		// As the query uses a PK condition, no limit(1) is necessary.
		$criteria = $this->isKeepQuery() ? clone $this : $this;
		$stmt = $criteria
			->filterByPrimaryKey($key)
			->doSelect($con);
		return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
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
		if ($con === null) {
			$con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
		}
		$this->basePreSelect($con);
		$criteria = $this->isKeepQuery() ? clone $this : $this;
		$stmt = $criteria
			->filterByPrimaryKeys($keys)
			->doSelect($con);
		return $criteria->getFormatter()->init($criteria)->format($stmt);
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
	 * Example usage:
	 * <code>
	 * $query->filterById(1234); // WHERE id = 1234
	 * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
	 * $query->filterById(array('min' => 12)); // WHERE id > 12
	 * </code>
	 *
	 * @param     mixed $id The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
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
	 * Example usage:
	 * <code>
	 * $query->filterByUserId(1234); // WHERE user_id = 1234
	 * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
	 * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
	 * </code>
	 *
	 * @param     mixed $userId The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
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
	 * Example usage:
	 * <code>
	 * $query->filterByWidgetHelpIsEnabled(true); // WHERE widget_help_is_enabled = true
	 * $query->filterByWidgetHelpIsEnabled('yes'); // WHERE widget_help_is_enabled = true
	 * </code>
	 *
	 * @param     boolean|string $widgetHelpIsEnabled The value to use as filter.
	 *              Non-boolean arguments are converted using the following rules:
	 *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afWidgetHelpSettingsQuery The current query, for fluid interface
	 */
	public function filterByWidgetHelpIsEnabled($widgetHelpIsEnabled = null, $comparison = null)
	{
		if (is_string($widgetHelpIsEnabled)) {
			$widget_help_is_enabled = in_array(strtolower($widgetHelpIsEnabled), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(afWidgetHelpSettingsPeer::WIDGET_HELP_IS_ENABLED, $widgetHelpIsEnabled, $comparison);
	}

	/**
	 * Filter the query on the popup_help_is_enabled column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByPopupHelpIsEnabled(true); // WHERE popup_help_is_enabled = true
	 * $query->filterByPopupHelpIsEnabled('yes'); // WHERE popup_help_is_enabled = true
	 * </code>
	 *
	 * @param     boolean|string $popupHelpIsEnabled The value to use as filter.
	 *              Non-boolean arguments are converted using the following rules:
	 *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afWidgetHelpSettingsQuery The current query, for fluid interface
	 */
	public function filterByPopupHelpIsEnabled($popupHelpIsEnabled = null, $comparison = null)
	{
		if (is_string($popupHelpIsEnabled)) {
			$popup_help_is_enabled = in_array(strtolower($popupHelpIsEnabled), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(afWidgetHelpSettingsPeer::POPUP_HELP_IS_ENABLED, $popupHelpIsEnabled, $comparison);
	}

	/**
	 * Filter the query on the help_type column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByHelpType(1234); // WHERE help_type = 1234
	 * $query->filterByHelpType(array(12, 34)); // WHERE help_type IN (12, 34)
	 * $query->filterByHelpType(array('min' => 12)); // WHERE help_type > 12
	 * </code>
	 *
	 * @param     mixed $helpType The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
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
	 * Example usage:
	 * <code>
	 * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
	 * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
	 * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
	 * </code>
	 *
	 * @param     mixed $createdAt The value to use as filter.
	 *              Values can be integers (unix timestamps), DateTime objects, or strings.
	 *              Empty strings are treated as NULL.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
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
	 * Example usage:
	 * <code>
	 * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
	 * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
	 * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
	 * </code>
	 *
	 * @param     mixed $updatedAt The value to use as filter.
	 *              Values can be integers (unix timestamps), DateTime objects, or strings.
	 *              Empty strings are treated as NULL.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
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