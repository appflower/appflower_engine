<?php


/**
 * Base class that represents a query for the 'sf_combine' table.
 *
 * 
 *
 * @method     sfCombineQuery orderByAssetsKey($order = Criteria::ASC) Order by the assets_key column
 * @method     sfCombineQuery orderByFiles($order = Criteria::ASC) Order by the files column
 *
 * @method     sfCombineQuery groupByAssetsKey() Group by the assets_key column
 * @method     sfCombineQuery groupByFiles() Group by the files column
 *
 * @method     sfCombineQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     sfCombineQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     sfCombineQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     sfCombine findOne(PropelPDO $con = null) Return the first sfCombine matching the query
 * @method     sfCombine findOneOrCreate(PropelPDO $con = null) Return the first sfCombine matching the query, or a new sfCombine object populated from the query conditions when no match is found
 *
 * @method     sfCombine findOneByAssetsKey(string $assets_key) Return the first sfCombine filtered by the assets_key column
 * @method     sfCombine findOneByFiles(string $files) Return the first sfCombine filtered by the files column
 *
 * @method     array findByAssetsKey(string $assets_key) Return sfCombine objects filtered by the assets_key column
 * @method     array findByFiles(string $files) Return sfCombine objects filtered by the files column
 *
 * @package    propel.generator.plugins.appFlowerPlugin.lib.model.om
 */
abstract class BasesfCombineQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BasesfCombineQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'propel', $modelName = 'sfCombine', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new sfCombineQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    sfCombineQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof sfCombineQuery) {
			return $criteria;
		}
		$query = new sfCombineQuery();
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
	 * @return    sfCombine|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = sfCombinePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(sfCombinePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    sfCombine A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ASSETS_KEY`, `FILES` FROM `sf_combine` WHERE `ASSETS_KEY` = :p0';
		try {
			$stmt = $con->prepare($sql);
			$stmt->bindValue(':p0', $key, PDO::PARAM_STR);
			$stmt->execute();
		} catch (Exception $e) {
			Propel::log($e->getMessage(), Propel::LOG_ERR);
			throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
		}
		$obj = null;
		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$obj = new sfCombine();
			$obj->hydrate($row);
			sfCombinePeer::addInstanceToPool($obj, (string) $row[0]);
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
	 * @return    sfCombine|array|mixed the result, formatted by the current formatter
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
	 * @return    sfCombineQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(sfCombinePeer::ASSETS_KEY, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    sfCombineQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(sfCombinePeer::ASSETS_KEY, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the assets_key column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByAssetsKey('fooValue');   // WHERE assets_key = 'fooValue'
	 * $query->filterByAssetsKey('%fooValue%'); // WHERE assets_key LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $assetsKey The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    sfCombineQuery The current query, for fluid interface
	 */
	public function filterByAssetsKey($assetsKey = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($assetsKey)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $assetsKey)) {
				$assetsKey = str_replace('*', '%', $assetsKey);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(sfCombinePeer::ASSETS_KEY, $assetsKey, $comparison);
	}

	/**
	 * Filter the query on the files column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByFiles('fooValue');   // WHERE files = 'fooValue'
	 * $query->filterByFiles('%fooValue%'); // WHERE files LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $files The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    sfCombineQuery The current query, for fluid interface
	 */
	public function filterByFiles($files = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($files)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $files)) {
				$files = str_replace('*', '%', $files);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(sfCombinePeer::FILES, $files, $comparison);
	}

	/**
	 * Exclude object from result
	 *
	 * @param     sfCombine $sfCombine Object to remove from the list of results
	 *
	 * @return    sfCombineQuery The current query, for fluid interface
	 */
	public function prune($sfCombine = null)
	{
		if ($sfCombine) {
			$this->addUsingAlias(sfCombinePeer::ASSETS_KEY, $sfCombine->getAssetsKey(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BasesfCombineQuery