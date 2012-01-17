<?php


/**
 * Base class that represents a query for the 'af_widget_setting' table.
 *
 * 
 *
 * @method     afWidgetSettingQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     afWidgetSettingQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     afWidgetSettingQuery orderByUser($order = Criteria::ASC) Order by the user column
 * @method     afWidgetSettingQuery orderBySetting($order = Criteria::ASC) Order by the setting column
 *
 * @method     afWidgetSettingQuery groupById() Group by the id column
 * @method     afWidgetSettingQuery groupByName() Group by the name column
 * @method     afWidgetSettingQuery groupByUser() Group by the user column
 * @method     afWidgetSettingQuery groupBySetting() Group by the setting column
 *
 * @method     afWidgetSettingQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     afWidgetSettingQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     afWidgetSettingQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     afWidgetSetting findOne(PropelPDO $con = null) Return the first afWidgetSetting matching the query
 * @method     afWidgetSetting findOneOrCreate(PropelPDO $con = null) Return the first afWidgetSetting matching the query, or a new afWidgetSetting object populated from the query conditions when no match is found
 *
 * @method     afWidgetSetting findOneById(int $id) Return the first afWidgetSetting filtered by the id column
 * @method     afWidgetSetting findOneByName(string $name) Return the first afWidgetSetting filtered by the name column
 * @method     afWidgetSetting findOneByUser(int $user) Return the first afWidgetSetting filtered by the user column
 * @method     afWidgetSetting findOneBySetting(string $setting) Return the first afWidgetSetting filtered by the setting column
 *
 * @method     array findById(int $id) Return afWidgetSetting objects filtered by the id column
 * @method     array findByName(string $name) Return afWidgetSetting objects filtered by the name column
 * @method     array findByUser(int $user) Return afWidgetSetting objects filtered by the user column
 * @method     array findBySetting(string $setting) Return afWidgetSetting objects filtered by the setting column
 *
 * @package    propel.generator.plugins.appFlowerPlugin.lib.model.om
 */
abstract class BaseafWidgetSettingQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseafWidgetSettingQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'propel', $modelName = 'afWidgetSetting', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new afWidgetSettingQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    afWidgetSettingQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof afWidgetSettingQuery) {
			return $criteria;
		}
		$query = new afWidgetSettingQuery();
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
	 * @return    afWidgetSetting|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = afWidgetSettingPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(afWidgetSettingPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    afWidgetSetting A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `NAME`, `USER`, `SETTING` FROM `af_widget_setting` WHERE `ID` = :p0';
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
			$obj = new afWidgetSetting();
			$obj->hydrate($row);
			afWidgetSettingPeer::addInstanceToPool($obj, (string) $row[0]);
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
	 * @return    afWidgetSetting|array|mixed the result, formatted by the current formatter
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
	 * @return    afWidgetSettingQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(afWidgetSettingPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    afWidgetSettingQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(afWidgetSettingPeer::ID, $keys, Criteria::IN);
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
	 * @return    afWidgetSettingQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(afWidgetSettingPeer::ID, $id, $comparison);
	}

	/**
	 * Filter the query on the name column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
	 * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $name The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afWidgetSettingQuery The current query, for fluid interface
	 */
	public function filterByName($name = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($name)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $name)) {
				$name = str_replace('*', '%', $name);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(afWidgetSettingPeer::NAME, $name, $comparison);
	}

	/**
	 * Filter the query on the user column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByUser(1234); // WHERE user = 1234
	 * $query->filterByUser(array(12, 34)); // WHERE user IN (12, 34)
	 * $query->filterByUser(array('min' => 12)); // WHERE user > 12
	 * </code>
	 *
	 * @param     mixed $user The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afWidgetSettingQuery The current query, for fluid interface
	 */
	public function filterByUser($user = null, $comparison = null)
	{
		if (is_array($user)) {
			$useMinMax = false;
			if (isset($user['min'])) {
				$this->addUsingAlias(afWidgetSettingPeer::USER, $user['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($user['max'])) {
				$this->addUsingAlias(afWidgetSettingPeer::USER, $user['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(afWidgetSettingPeer::USER, $user, $comparison);
	}

	/**
	 * Filter the query on the setting column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterBySetting('fooValue');   // WHERE setting = 'fooValue'
	 * $query->filterBySetting('%fooValue%'); // WHERE setting LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $setting The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afWidgetSettingQuery The current query, for fluid interface
	 */
	public function filterBySetting($setting = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($setting)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $setting)) {
				$setting = str_replace('*', '%', $setting);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(afWidgetSettingPeer::SETTING, $setting, $comparison);
	}

	/**
	 * Exclude object from result
	 *
	 * @param     afWidgetSetting $afWidgetSetting Object to remove from the list of results
	 *
	 * @return    afWidgetSettingQuery The current query, for fluid interface
	 */
	public function prune($afWidgetSetting = null)
	{
		if ($afWidgetSetting) {
			$this->addUsingAlias(afWidgetSettingPeer::ID, $afWidgetSetting->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseafWidgetSettingQuery