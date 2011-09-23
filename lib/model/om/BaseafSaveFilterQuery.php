<?php


/**
 * Base class that represents a query for the 'af_save_filter' table.
 *
 * 
 *
 * @method     afSaveFilterQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     afSaveFilterQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     afSaveFilterQuery orderByUser($order = Criteria::ASC) Order by the user column
 * @method     afSaveFilterQuery orderByPath($order = Criteria::ASC) Order by the path column
 * @method     afSaveFilterQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     afSaveFilterQuery orderByFilter($order = Criteria::ASC) Order by the filter column
 *
 * @method     afSaveFilterQuery groupById() Group by the id column
 * @method     afSaveFilterQuery groupByName() Group by the name column
 * @method     afSaveFilterQuery groupByUser() Group by the user column
 * @method     afSaveFilterQuery groupByPath() Group by the path column
 * @method     afSaveFilterQuery groupByTitle() Group by the title column
 * @method     afSaveFilterQuery groupByFilter() Group by the filter column
 *
 * @method     afSaveFilterQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     afSaveFilterQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     afSaveFilterQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     afSaveFilter findOne(PropelPDO $con = null) Return the first afSaveFilter matching the query
 * @method     afSaveFilter findOneOrCreate(PropelPDO $con = null) Return the first afSaveFilter matching the query, or a new afSaveFilter object populated from the query conditions when no match is found
 *
 * @method     afSaveFilter findOneById(int $id) Return the first afSaveFilter filtered by the id column
 * @method     afSaveFilter findOneByName(string $name) Return the first afSaveFilter filtered by the name column
 * @method     afSaveFilter findOneByUser(int $user) Return the first afSaveFilter filtered by the user column
 * @method     afSaveFilter findOneByPath(string $path) Return the first afSaveFilter filtered by the path column
 * @method     afSaveFilter findOneByTitle(string $title) Return the first afSaveFilter filtered by the title column
 * @method     afSaveFilter findOneByFilter(string $filter) Return the first afSaveFilter filtered by the filter column
 *
 * @method     array findById(int $id) Return afSaveFilter objects filtered by the id column
 * @method     array findByName(string $name) Return afSaveFilter objects filtered by the name column
 * @method     array findByUser(int $user) Return afSaveFilter objects filtered by the user column
 * @method     array findByPath(string $path) Return afSaveFilter objects filtered by the path column
 * @method     array findByTitle(string $title) Return afSaveFilter objects filtered by the title column
 * @method     array findByFilter(string $filter) Return afSaveFilter objects filtered by the filter column
 *
 * @package    propel.generator.plugins.appFlowerPlugin.lib.model.om
 */
abstract class BaseafSaveFilterQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseafSaveFilterQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'propel', $modelName = 'afSaveFilter', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new afSaveFilterQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    afSaveFilterQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof afSaveFilterQuery) {
			return $criteria;
		}
		$query = new afSaveFilterQuery();
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
	 * @return    afSaveFilter|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = afSaveFilterPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    afSaveFilterQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(afSaveFilterPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    afSaveFilterQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(afSaveFilterPeer::ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the id column
	 * 
	 * @param     int|array $id The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afSaveFilterQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(afSaveFilterPeer::ID, $id, $comparison);
	}

	/**
	 * Filter the query on the name column
	 * 
	 * @param     string $name The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afSaveFilterQuery The current query, for fluid interface
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
		return $this->addUsingAlias(afSaveFilterPeer::NAME, $name, $comparison);
	}

	/**
	 * Filter the query on the user column
	 * 
	 * @param     int|array $user The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afSaveFilterQuery The current query, for fluid interface
	 */
	public function filterByUser($user = null, $comparison = null)
	{
		if (is_array($user)) {
			$useMinMax = false;
			if (isset($user['min'])) {
				$this->addUsingAlias(afSaveFilterPeer::USER, $user['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($user['max'])) {
				$this->addUsingAlias(afSaveFilterPeer::USER, $user['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(afSaveFilterPeer::USER, $user, $comparison);
	}

	/**
	 * Filter the query on the path column
	 * 
	 * @param     string $path The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afSaveFilterQuery The current query, for fluid interface
	 */
	public function filterByPath($path = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($path)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $path)) {
				$path = str_replace('*', '%', $path);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(afSaveFilterPeer::PATH, $path, $comparison);
	}

	/**
	 * Filter the query on the title column
	 * 
	 * @param     string $title The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afSaveFilterQuery The current query, for fluid interface
	 */
	public function filterByTitle($title = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($title)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $title)) {
				$title = str_replace('*', '%', $title);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(afSaveFilterPeer::TITLE, $title, $comparison);
	}

	/**
	 * Filter the query on the filter column
	 * 
	 * @param     string $filter The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afSaveFilterQuery The current query, for fluid interface
	 */
	public function filterByFilter($filter = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($filter)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $filter)) {
				$filter = str_replace('*', '%', $filter);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(afSaveFilterPeer::FILTER, $filter, $comparison);
	}

	/**
	 * Exclude object from result
	 *
	 * @param     afSaveFilter $afSaveFilter Object to remove from the list of results
	 *
	 * @return    afSaveFilterQuery The current query, for fluid interface
	 */
	public function prune($afSaveFilter = null)
	{
		if ($afSaveFilter) {
			$this->addUsingAlias(afSaveFilterPeer::ID, $afSaveFilter->getId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseafSaveFilterQuery
