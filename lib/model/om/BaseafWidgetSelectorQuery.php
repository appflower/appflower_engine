<?php


/**
 * Base class that represents a query for the 'af_widget_selector' table.
 *
 * 
 *
 * @method     afWidgetSelectorQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     afWidgetSelectorQuery orderByUrl($order = Criteria::ASC) Order by the url column
 * @method     afWidgetSelectorQuery orderByParams($order = Criteria::ASC) Order by the params column
 * @method     afWidgetSelectorQuery orderByCategoryId($order = Criteria::ASC) Order by the category_id column
 * @method     afWidgetSelectorQuery orderByPermission($order = Criteria::ASC) Order by the permission column
 *
 * @method     afWidgetSelectorQuery groupById() Group by the id column
 * @method     afWidgetSelectorQuery groupByUrl() Group by the url column
 * @method     afWidgetSelectorQuery groupByParams() Group by the params column
 * @method     afWidgetSelectorQuery groupByCategoryId() Group by the category_id column
 * @method     afWidgetSelectorQuery groupByPermission() Group by the permission column
 *
 * @method     afWidgetSelectorQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     afWidgetSelectorQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     afWidgetSelectorQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     afWidgetSelectorQuery leftJoinafWidgetCategory($relationAlias = null) Adds a LEFT JOIN clause to the query using the afWidgetCategory relation
 * @method     afWidgetSelectorQuery rightJoinafWidgetCategory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the afWidgetCategory relation
 * @method     afWidgetSelectorQuery innerJoinafWidgetCategory($relationAlias = null) Adds a INNER JOIN clause to the query using the afWidgetCategory relation
 *
 * @method     afWidgetSelector findOne(PropelPDO $con = null) Return the first afWidgetSelector matching the query
 * @method     afWidgetSelector findOneOrCreate(PropelPDO $con = null) Return the first afWidgetSelector matching the query, or a new afWidgetSelector object populated from the query conditions when no match is found
 *
 * @method     afWidgetSelector findOneById(int $id) Return the first afWidgetSelector filtered by the id column
 * @method     afWidgetSelector findOneByUrl(string $url) Return the first afWidgetSelector filtered by the url column
 * @method     afWidgetSelector findOneByParams(string $params) Return the first afWidgetSelector filtered by the params column
 * @method     afWidgetSelector findOneByCategoryId(int $category_id) Return the first afWidgetSelector filtered by the category_id column
 * @method     afWidgetSelector findOneByPermission(string $permission) Return the first afWidgetSelector filtered by the permission column
 *
 * @method     array findById(int $id) Return afWidgetSelector objects filtered by the id column
 * @method     array findByUrl(string $url) Return afWidgetSelector objects filtered by the url column
 * @method     array findByParams(string $params) Return afWidgetSelector objects filtered by the params column
 * @method     array findByCategoryId(int $category_id) Return afWidgetSelector objects filtered by the category_id column
 * @method     array findByPermission(string $permission) Return afWidgetSelector objects filtered by the permission column
 *
 * @package    propel.generator.plugins.appFlowerPlugin.lib.model.om
 */
abstract class BaseafWidgetSelectorQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseafWidgetSelectorQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'propel', $modelName = 'afWidgetSelector', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new afWidgetSelectorQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    afWidgetSelectorQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof afWidgetSelectorQuery) {
			return $criteria;
		}
		$query = new afWidgetSelectorQuery();
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
	 * @return    afWidgetSelector|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = afWidgetSelectorPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(afWidgetSelectorPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    afWidgetSelector A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `URL`, `PARAMS`, `CATEGORY_ID`, `PERMISSION` FROM `af_widget_selector` WHERE `ID` = :p0';
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
			$obj = new afWidgetSelector();
			$obj->hydrate($row);
			afWidgetSelectorPeer::addInstanceToPool($obj, (string) $row[0]);
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
	 * @return    afWidgetSelector|array|mixed the result, formatted by the current formatter
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
	 * @return    afWidgetSelectorQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(afWidgetSelectorPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    afWidgetSelectorQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(afWidgetSelectorPeer::ID, $keys, Criteria::IN);
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
	 * @return    afWidgetSelectorQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(afWidgetSelectorPeer::ID, $id, $comparison);
	}

	/**
	 * Filter the query on the url column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByUrl('fooValue');   // WHERE url = 'fooValue'
	 * $query->filterByUrl('%fooValue%'); // WHERE url LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $url The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afWidgetSelectorQuery The current query, for fluid interface
	 */
	public function filterByUrl($url = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($url)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $url)) {
				$url = str_replace('*', '%', $url);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(afWidgetSelectorPeer::URL, $url, $comparison);
	}

	/**
	 * Filter the query on the params column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByParams('fooValue');   // WHERE params = 'fooValue'
	 * $query->filterByParams('%fooValue%'); // WHERE params LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $params The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afWidgetSelectorQuery The current query, for fluid interface
	 */
	public function filterByParams($params = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($params)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $params)) {
				$params = str_replace('*', '%', $params);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(afWidgetSelectorPeer::PARAMS, $params, $comparison);
	}

	/**
	 * Filter the query on the category_id column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByCategoryId(1234); // WHERE category_id = 1234
	 * $query->filterByCategoryId(array(12, 34)); // WHERE category_id IN (12, 34)
	 * $query->filterByCategoryId(array('min' => 12)); // WHERE category_id > 12
	 * </code>
	 *
	 * @see       filterByafWidgetCategory()
	 *
	 * @param     mixed $categoryId The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afWidgetSelectorQuery The current query, for fluid interface
	 */
	public function filterByCategoryId($categoryId = null, $comparison = null)
	{
		if (is_array($categoryId)) {
			$useMinMax = false;
			if (isset($categoryId['min'])) {
				$this->addUsingAlias(afWidgetSelectorPeer::CATEGORY_ID, $categoryId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($categoryId['max'])) {
				$this->addUsingAlias(afWidgetSelectorPeer::CATEGORY_ID, $categoryId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(afWidgetSelectorPeer::CATEGORY_ID, $categoryId, $comparison);
	}

	/**
	 * Filter the query on the permission column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByPermission('fooValue');   // WHERE permission = 'fooValue'
	 * $query->filterByPermission('%fooValue%'); // WHERE permission LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $permission The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afWidgetSelectorQuery The current query, for fluid interface
	 */
	public function filterByPermission($permission = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($permission)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $permission)) {
				$permission = str_replace('*', '%', $permission);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(afWidgetSelectorPeer::PERMISSION, $permission, $comparison);
	}

	/**
	 * Filter the query by a related afWidgetCategory object
	 *
	 * @param     afWidgetCategory|PropelCollection $afWidgetCategory The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afWidgetSelectorQuery The current query, for fluid interface
	 */
	public function filterByafWidgetCategory($afWidgetCategory, $comparison = null)
	{
		if ($afWidgetCategory instanceof afWidgetCategory) {
			return $this
				->addUsingAlias(afWidgetSelectorPeer::CATEGORY_ID, $afWidgetCategory->getId(), $comparison);
		} elseif ($afWidgetCategory instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(afWidgetSelectorPeer::CATEGORY_ID, $afWidgetCategory->toKeyValue('PrimaryKey', 'Id'), $comparison);
		} else {
			throw new PropelException('filterByafWidgetCategory() only accepts arguments of type afWidgetCategory or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the afWidgetCategory relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    afWidgetSelectorQuery The current query, for fluid interface
	 */
	public function joinafWidgetCategory($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('afWidgetCategory');

		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		if ($previousJoin = $this->getPreviousJoin()) {
			$join->setPreviousJoin($previousJoin);
		}

		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'afWidgetCategory');
		}

		return $this;
	}

	/**
	 * Use the afWidgetCategory relation afWidgetCategory object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    afWidgetCategoryQuery A secondary query class using the current class as primary query
	 */
	public function useafWidgetCategoryQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinafWidgetCategory($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'afWidgetCategory', 'afWidgetCategoryQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     afWidgetSelector $afWidgetSelector Object to remove from the list of results
	 *
	 * @return    afWidgetSelectorQuery The current query, for fluid interface
	 */
	public function prune($afWidgetSelector = null)
	{
		if ($afWidgetSelector) {
			$this->addUsingAlias(afWidgetSelectorPeer::ID, $afWidgetSelector->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseafWidgetSelectorQuery