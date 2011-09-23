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
	 * Find object by primary key
	 * Use instance pooling to avoid a database query if the object exists
	 * <code>
	 * $obj  = $c->findPk(12, $con);
	 * </code>
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    afValidatorCache|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = afValidatorCachePeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @param     int|array $id The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
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
	 * @param     string $signature The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
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
	 * @param     string $path The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
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
	 * @param     string|array $createdAt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
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
	 * @param     string|array $updatedAt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
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
