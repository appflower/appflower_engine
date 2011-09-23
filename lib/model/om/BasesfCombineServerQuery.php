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
	 * Find object by primary key
	 * Use instance pooling to avoid a database query if the object exists
	 * <code>
	 * $obj  = $c->findPk(12, $con);
	 * </code>
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    sfCombineServer|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = sfCombineServerPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @param     int|array $id The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
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
	 * @param     boolean|string $online The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    sfCombineServerQuery The current query, for fluid interface
	 */
	public function filterByOnline($online = null, $comparison = null)
	{
		if (is_string($online)) {
			$online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
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
