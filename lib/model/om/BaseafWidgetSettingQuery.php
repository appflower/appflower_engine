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
	 * Find object by primary key
	 * Use instance pooling to avoid a database query if the object exists
	 * <code>
	 * $obj  = $c->findPk(12, $con);
	 * </code>
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    afWidgetSetting|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = afWidgetSettingPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @param     int|array $id The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
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
	 * @param     string $name The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
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
	 * @param     int|array $user The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
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
	 * @param     string $setting The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
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
