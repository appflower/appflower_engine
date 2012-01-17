<?php


/**
 * Base class that represents a query for the 'sf_combine_server' table.
 *
 * 
 *
 * @method     sfCombineServerQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     sfCombineServerQuery orderByOnline($order = Criteria::ASC) Order by the online column
 *
 * @method     sfCombineServerQuery groupById() Group by the id column
 * @method     sfCombineServerQuery groupByOnline() Group by the online column
 *
 * @method     sfCombineServerQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     sfCombineServerQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     sfCombineServerQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     sfCombineServer findOne(PropelPDO $con = null) Return the first sfCombineServer matching the query
 * @method     sfCombineServer findOneOrCreate(PropelPDO $con = null) Return the first sfCombineServer matching the query, or a new sfCombineServer object populated from the query conditions when no match is found
 *
 * @method     sfCombineServer findOneById(int $id) Return the first sfCombineServer filtered by the id column
 * @method     sfCombineServer findOneByOnline(boolean $online) Return the first sfCombineServer filtered by the online column
 *
 * @method     array findById(int $id) Return sfCombineServer objects filtered by the id column
 * @method     array findByOnline(boolean $online) Return sfCombineServer objects filtered by the online column
 *
 * @package    propel.generator.plugins.appFlowerPlugin.lib.model.om
 */
abstract class BasesfCombineServerQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BasesfCombineServerQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'propel', $modelName = 'sfCombineServer', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new sfCombineServerQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    sfCombineServerQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof sfCombineServerQuery) {
			return $criteria;
		}
		$query = new sfCombineServerQuery();
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
	 * @return    sfCombineServer|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = sfCombineServerPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(sfCombineServerPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    sfCombineServer A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `ONLINE` FROM `sf_combine_server` WHERE `ID` = :p0';
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
			$obj = new sfCombineServer();
			$obj->hydrate($row);
			sfCombineServerPeer::addInstanceToPool($obj, (string) $row[0]);
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
	 * @return    sfCombineServer|array|mixed the result, formatted by the current formatter
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
	 * @return    sfCombineServerQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(sfCombineServerPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    sfCombineServerQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(sfCombineServerPeer::ID, $keys, Criteria::IN);
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
	 * @return    sfCombineServerQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(sfCombineServerPeer::ID, $id, $comparison);
	}

	/**
	 * Filter the query on the online column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByOnline(true); // WHERE online = true
	 * $query->filterByOnline('yes'); // WHERE online = true
	 * </code>
	 *
	 * @param     boolean|string $online The value to use as filter.
	 *              Non-boolean arguments are converted using the following rules:
	 *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    sfCombineServerQuery The current query, for fluid interface
	 */
	public function filterByOnline($online = null, $comparison = null)
	{
		if (is_string($online)) {
			$online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(sfCombineServerPeer::ONLINE, $online, $comparison);
	}

	/**
	 * Exclude object from result
	 *
	 * @param     sfCombineServer $sfCombineServer Object to remove from the list of results
	 *
	 * @return    sfCombineServerQuery The current query, for fluid interface
	 */
	public function prune($sfCombineServer = null)
	{
		if ($sfCombineServer) {
			$this->addUsingAlias(sfCombineServerPeer::ID, $sfCombineServer->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BasesfCombineServerQuery