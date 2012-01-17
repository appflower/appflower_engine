<?php


/**
 * Base class that represents a query for the 'af_widget_category' table.
 *
 * 
 *
 * @method     afWidgetCategoryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     afWidgetCategoryQuery orderByModule($order = Criteria::ASC) Order by the module column
 * @method     afWidgetCategoryQuery orderByName($order = Criteria::ASC) Order by the name column
 *
 * @method     afWidgetCategoryQuery groupById() Group by the id column
 * @method     afWidgetCategoryQuery groupByModule() Group by the module column
 * @method     afWidgetCategoryQuery groupByName() Group by the name column
 *
 * @method     afWidgetCategoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     afWidgetCategoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     afWidgetCategoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     afWidgetCategoryQuery leftJoinafWidgetSelector($relationAlias = null) Adds a LEFT JOIN clause to the query using the afWidgetSelector relation
 * @method     afWidgetCategoryQuery rightJoinafWidgetSelector($relationAlias = null) Adds a RIGHT JOIN clause to the query using the afWidgetSelector relation
 * @method     afWidgetCategoryQuery innerJoinafWidgetSelector($relationAlias = null) Adds a INNER JOIN clause to the query using the afWidgetSelector relation
 *
 * @method     afWidgetCategory findOne(PropelPDO $con = null) Return the first afWidgetCategory matching the query
 * @method     afWidgetCategory findOneOrCreate(PropelPDO $con = null) Return the first afWidgetCategory matching the query, or a new afWidgetCategory object populated from the query conditions when no match is found
 *
 * @method     afWidgetCategory findOneById(int $id) Return the first afWidgetCategory filtered by the id column
 * @method     afWidgetCategory findOneByModule(string $module) Return the first afWidgetCategory filtered by the module column
 * @method     afWidgetCategory findOneByName(string $name) Return the first afWidgetCategory filtered by the name column
 *
 * @method     array findById(int $id) Return afWidgetCategory objects filtered by the id column
 * @method     array findByModule(string $module) Return afWidgetCategory objects filtered by the module column
 * @method     array findByName(string $name) Return afWidgetCategory objects filtered by the name column
 *
 * @package    propel.generator.plugins.appFlowerPlugin.lib.model.om
 */
abstract class BaseafWidgetCategoryQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseafWidgetCategoryQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'propel', $modelName = 'afWidgetCategory', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new afWidgetCategoryQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    afWidgetCategoryQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof afWidgetCategoryQuery) {
			return $criteria;
		}
		$query = new afWidgetCategoryQuery();
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
	 * @return    afWidgetCategory|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = afWidgetCategoryPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(afWidgetCategoryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    afWidgetCategory A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `MODULE`, `NAME` FROM `af_widget_category` WHERE `ID` = :p0';
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
			$obj = new afWidgetCategory();
			$obj->hydrate($row);
			afWidgetCategoryPeer::addInstanceToPool($obj, (string) $row[0]);
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
	 * @return    afWidgetCategory|array|mixed the result, formatted by the current formatter
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
	 * @return    afWidgetCategoryQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(afWidgetCategoryPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    afWidgetCategoryQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(afWidgetCategoryPeer::ID, $keys, Criteria::IN);
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
	 * @return    afWidgetCategoryQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(afWidgetCategoryPeer::ID, $id, $comparison);
	}

	/**
	 * Filter the query on the module column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByModule('fooValue');   // WHERE module = 'fooValue'
	 * $query->filterByModule('%fooValue%'); // WHERE module LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $module The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afWidgetCategoryQuery The current query, for fluid interface
	 */
	public function filterByModule($module = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($module)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $module)) {
				$module = str_replace('*', '%', $module);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(afWidgetCategoryPeer::MODULE, $module, $comparison);
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
	 * @return    afWidgetCategoryQuery The current query, for fluid interface
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
		return $this->addUsingAlias(afWidgetCategoryPeer::NAME, $name, $comparison);
	}

	/**
	 * Filter the query by a related afWidgetSelector object
	 *
	 * @param     afWidgetSelector $afWidgetSelector  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afWidgetCategoryQuery The current query, for fluid interface
	 */
	public function filterByafWidgetSelector($afWidgetSelector, $comparison = null)
	{
		if ($afWidgetSelector instanceof afWidgetSelector) {
			return $this
				->addUsingAlias(afWidgetCategoryPeer::ID, $afWidgetSelector->getCategoryId(), $comparison);
		} elseif ($afWidgetSelector instanceof PropelCollection) {
			return $this
				->useafWidgetSelectorQuery()
				->filterByPrimaryKeys($afWidgetSelector->getPrimaryKeys())
				->endUse();
		} else {
			throw new PropelException('filterByafWidgetSelector() only accepts arguments of type afWidgetSelector or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the afWidgetSelector relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    afWidgetCategoryQuery The current query, for fluid interface
	 */
	public function joinafWidgetSelector($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('afWidgetSelector');

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
			$this->addJoinObject($join, 'afWidgetSelector');
		}

		return $this;
	}

	/**
	 * Use the afWidgetSelector relation afWidgetSelector object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    afWidgetSelectorQuery A secondary query class using the current class as primary query
	 */
	public function useafWidgetSelectorQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinafWidgetSelector($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'afWidgetSelector', 'afWidgetSelectorQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     afWidgetCategory $afWidgetCategory Object to remove from the list of results
	 *
	 * @return    afWidgetCategoryQuery The current query, for fluid interface
	 */
	public function prune($afWidgetCategory = null)
	{
		if ($afWidgetCategory) {
			$this->addUsingAlias(afWidgetCategoryPeer::ID, $afWidgetCategory->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseafWidgetCategoryQuery