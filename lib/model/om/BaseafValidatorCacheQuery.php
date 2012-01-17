<?php


/**
 * Base class that represents a query for the 'af_validator_cache' table.
 *
 * 
 *
 * @method     afValidatorCacheQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     afValidatorCacheQuery orderBySignature($order = Criteria::ASC) Order by the signature column
 * @method     afValidatorCacheQuery orderByPath($order = Criteria::ASC) Order by the path column
 * @method     afValidatorCacheQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     afValidatorCacheQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     afValidatorCacheQuery groupById() Group by the id column
 * @method     afValidatorCacheQuery groupBySignature() Group by the signature column
 * @method     afValidatorCacheQuery groupByPath() Group by the path column
 * @method     afValidatorCacheQuery groupByCreatedAt() Group by the created_at column
 * @method     afValidatorCacheQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     afValidatorCacheQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     afValidatorCacheQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     afValidatorCacheQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     afValidatorCache findOne(PropelPDO $con = null) Return the first afValidatorCache matching the query
 * @method     afValidatorCache findOneOrCreate(PropelPDO $con = null) Return the first afValidatorCache matching the query, or a new afValidatorCache object populated from the query conditions when no match is found
 *
 * @method     afValidatorCache findOneById(int $id) Return the first afValidatorCache filtered by the id column
 * @method     afValidatorCache findOneBySignature(string $signature) Return the first afValidatorCache filtered by the signature column
 * @method     afValidatorCache findOneByPath(string $path) Return the first afValidatorCache filtered by the path column
 * @method     afValidatorCache findOneByCreatedAt(string $created_at) Return the first afValidatorCache filtered by the created_at column
 * @method     afValidatorCache findOneByUpdatedAt(string $updated_at) Return the first afValidatorCache filtered by the updated_at column
 *
 * @method     array findById(int $id) Return afValidatorCache objects filtered by the id column
 * @method     array findBySignature(string $signature) Return afValidatorCache objects filtered by the signature column
 * @method     array findByPath(string $path) Return afValidatorCache objects filtered by the path column
 * @method     array findByCreatedAt(string $created_at) Return afValidatorCache objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return afValidatorCache objects filtered by the updated_at column
 *
 * @package    propel.generator.plugins.appFlowerPlugin.lib.model.om
 */
abstract class BaseafValidatorCacheQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseafValidatorCacheQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'propel', $modelName = 'afValidatorCache', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new afValidatorCacheQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    afValidatorCacheQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof afValidatorCacheQuery) {
			return $criteria;
		}
		$query = new afValidatorCacheQuery();
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
	 * @return    afValidatorCache|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = afValidatorCachePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(afValidatorCachePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    afValidatorCache A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `SIGNATURE`, `PATH`, `CREATED_AT`, `UPDATED_AT` FROM `af_validator_cache` WHERE `ID` = :p0';
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
			$obj = new afValidatorCache();
			$obj->hydrate($row);
			afValidatorCachePeer::addInstanceToPool($obj, (string) $row[0]);
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
	 * @return    afValidatorCache|array|mixed the result, formatted by the current formatter
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
	 * @return    afValidatorCacheQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(afValidatorCachePeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    afValidatorCacheQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(afValidatorCachePeer::ID, $keys, Criteria::IN);
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
	 * @return    afValidatorCacheQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(afValidatorCachePeer::ID, $id, $comparison);
	}

	/**
	 * Filter the query on the signature column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterBySignature('fooValue');   // WHERE signature = 'fooValue'
	 * $query->filterBySignature('%fooValue%'); // WHERE signature LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $signature The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afValidatorCacheQuery The current query, for fluid interface
	 */
	public function filterBySignature($signature = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($signature)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $signature)) {
				$signature = str_replace('*', '%', $signature);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(afValidatorCachePeer::SIGNATURE, $signature, $comparison);
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
	 * @return    afValidatorCacheQuery The current query, for fluid interface
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
		return $this->addUsingAlias(afValidatorCachePeer::PATH, $path, $comparison);
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
	 * @return    afValidatorCacheQuery The current query, for fluid interface
	 */
	public function filterByCreatedAt($createdAt = null, $comparison = null)
	{
		if (is_array($createdAt)) {
			$useMinMax = false;
			if (isset($createdAt['min'])) {
				$this->addUsingAlias(afValidatorCachePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($createdAt['max'])) {
				$this->addUsingAlias(afValidatorCachePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(afValidatorCachePeer::CREATED_AT, $createdAt, $comparison);
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
	 * @return    afValidatorCacheQuery The current query, for fluid interface
	 */
	public function filterByUpdatedAt($updatedAt = null, $comparison = null)
	{
		if (is_array($updatedAt)) {
			$useMinMax = false;
			if (isset($updatedAt['min'])) {
				$this->addUsingAlias(afValidatorCachePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($updatedAt['max'])) {
				$this->addUsingAlias(afValidatorCachePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(afValidatorCachePeer::UPDATED_AT, $updatedAt, $comparison);
	}

	/**
	 * Exclude object from result
	 *
	 * @param     afValidatorCache $afValidatorCache Object to remove from the list of results
	 *
	 * @return    afValidatorCacheQuery The current query, for fluid interface
	 */
	public function prune($afValidatorCache = null)
	{
		if ($afValidatorCache) {
			$this->addUsingAlias(afValidatorCachePeer::ID, $afValidatorCache->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseafValidatorCacheQuery