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
	 * Find object by primary key
	 * Use instance pooling to avoid a database query if the object exists
	 * <code>
	 * $obj  = $c->findPk(12, $con);
	 * </code>
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    afWidgetSelector|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = afWidgetSelectorPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @param     int|array $id The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
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
	 * @param     string $url The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
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
	 * @param     string $params The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
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
	 * @param     int|array $categoryId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
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
	 * @param     string $permission The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
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
	 * @param     afWidgetCategory $afWidgetCategory  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afWidgetSelectorQuery The current query, for fluid interface
	 */
	public function filterByafWidgetCategory($afWidgetCategory, $comparison = null)
	{
		return $this
			->addUsingAlias(afWidgetSelectorPeer::CATEGORY_ID, $afWidgetCategory->getId(), $comparison);
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
