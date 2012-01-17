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
	 * @return    afSaveFilter|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = afSaveFilterPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(afSaveFilterPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    afSaveFilter A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `NAME`, `USER`, `PATH`, `TITLE`, `FILTER` FROM `af_save_filter` WHERE `ID` = :p0';
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
			$obj = new afSaveFilter();
			$obj->hydrate($row);
			afSaveFilterPeer::addInstanceToPool($obj, (string) $row[0]);
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
	 * @return    afSaveFilter|array|mixed the result, formatted by the current formatter
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
	 * Example usage:
	 * <code>
	 * $query->filterByPath('fooValue');   // WHERE path = 'fooValue'
	 * $query->filterByPath('%fooValue%'); // WHERE path LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $path The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
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
	 * Example usage:
	 * <code>
	 * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
	 * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $title The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
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
	 * Example usage:
	 * <code>
	 * $query->filterByFilter('fooValue');   // WHERE filter = 'fooValue'
	 * $query->filterByFilter('%fooValue%'); // WHERE filter LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $filter The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
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