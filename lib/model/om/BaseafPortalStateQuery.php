<?php


/**
 * Base class that represents a query for the 'af_portal_state' table.
 *
 * 
 *
 * @method     afPortalStateQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     afPortalStateQuery orderByIdXml($order = Criteria::ASC) Order by the id_xml column
 * @method     afPortalStateQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     afPortalStateQuery orderByLayoutType($order = Criteria::ASC) Order by the layout_type column
 * @method     afPortalStateQuery orderByContent($order = Criteria::ASC) Order by the content column
 * @method     afPortalStateQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     afPortalStateQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     afPortalStateQuery groupById() Group by the id column
 * @method     afPortalStateQuery groupByIdXml() Group by the id_xml column
 * @method     afPortalStateQuery groupByUserId() Group by the user_id column
 * @method     afPortalStateQuery groupByLayoutType() Group by the layout_type column
 * @method     afPortalStateQuery groupByContent() Group by the content column
 * @method     afPortalStateQuery groupByCreatedAt() Group by the created_at column
 * @method     afPortalStateQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     afPortalStateQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     afPortalStateQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     afPortalStateQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     afPortalState findOne(PropelPDO $con = null) Return the first afPortalState matching the query
 * @method     afPortalState findOneOrCreate(PropelPDO $con = null) Return the first afPortalState matching the query, or a new afPortalState object populated from the query conditions when no match is found
 *
 * @method     afPortalState findOneById(int $id) Return the first afPortalState filtered by the id column
 * @method     afPortalState findOneByIdXml(string $id_xml) Return the first afPortalState filtered by the id_xml column
 * @method     afPortalState findOneByUserId(int $user_id) Return the first afPortalState filtered by the user_id column
 * @method     afPortalState findOneByLayoutType(string $layout_type) Return the first afPortalState filtered by the layout_type column
 * @method     afPortalState findOneByContent(string $content) Return the first afPortalState filtered by the content column
 * @method     afPortalState findOneByCreatedAt(string $created_at) Return the first afPortalState filtered by the created_at column
 * @method     afPortalState findOneByUpdatedAt(string $updated_at) Return the first afPortalState filtered by the updated_at column
 *
 * @method     array findById(int $id) Return afPortalState objects filtered by the id column
 * @method     array findByIdXml(string $id_xml) Return afPortalState objects filtered by the id_xml column
 * @method     array findByUserId(int $user_id) Return afPortalState objects filtered by the user_id column
 * @method     array findByLayoutType(string $layout_type) Return afPortalState objects filtered by the layout_type column
 * @method     array findByContent(string $content) Return afPortalState objects filtered by the content column
 * @method     array findByCreatedAt(string $created_at) Return afPortalState objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return afPortalState objects filtered by the updated_at column
 *
 * @package    propel.generator.plugins.appFlowerPlugin.lib.model.om
 */
abstract class BaseafPortalStateQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseafPortalStateQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'propel', $modelName = 'afPortalState', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new afPortalStateQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    afPortalStateQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof afPortalStateQuery) {
			return $criteria;
		}
		$query = new afPortalStateQuery();
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
	 * @return    afPortalState|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = afPortalStatePeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    afPortalStateQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(afPortalStatePeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    afPortalStateQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(afPortalStatePeer::ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the id column
	 * 
	 * @param     int|array $id The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afPortalStateQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(afPortalStatePeer::ID, $id, $comparison);
	}

	/**
	 * Filter the query on the id_xml column
	 * 
	 * @param     string $idXml The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afPortalStateQuery The current query, for fluid interface
	 */
	public function filterByIdXml($idXml = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($idXml)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $idXml)) {
				$idXml = str_replace('*', '%', $idXml);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(afPortalStatePeer::ID_XML, $idXml, $comparison);
	}

	/**
	 * Filter the query on the user_id column
	 * 
	 * @param     int|array $userId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afPortalStateQuery The current query, for fluid interface
	 */
	public function filterByUserId($userId = null, $comparison = null)
	{
		if (is_array($userId)) {
			$useMinMax = false;
			if (isset($userId['min'])) {
				$this->addUsingAlias(afPortalStatePeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userId['max'])) {
				$this->addUsingAlias(afPortalStatePeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(afPortalStatePeer::USER_ID, $userId, $comparison);
	}

	/**
	 * Filter the query on the layout_type column
	 * 
	 * @param     string $layoutType The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afPortalStateQuery The current query, for fluid interface
	 */
	public function filterByLayoutType($layoutType = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($layoutType)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $layoutType)) {
				$layoutType = str_replace('*', '%', $layoutType);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(afPortalStatePeer::LAYOUT_TYPE, $layoutType, $comparison);
	}

	/**
	 * Filter the query on the content column
	 * 
	 * @param     string $content The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afPortalStateQuery The current query, for fluid interface
	 */
	public function filterByContent($content = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($content)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $content)) {
				$content = str_replace('*', '%', $content);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(afPortalStatePeer::CONTENT, $content, $comparison);
	}

	/**
	 * Filter the query on the created_at column
	 * 
	 * @param     string|array $createdAt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afPortalStateQuery The current query, for fluid interface
	 */
	public function filterByCreatedAt($createdAt = null, $comparison = null)
	{
		if (is_array($createdAt)) {
			$useMinMax = false;
			if (isset($createdAt['min'])) {
				$this->addUsingAlias(afPortalStatePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($createdAt['max'])) {
				$this->addUsingAlias(afPortalStatePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(afPortalStatePeer::CREATED_AT, $createdAt, $comparison);
	}

	/**
	 * Filter the query on the updated_at column
	 * 
	 * @param     string|array $updatedAt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    afPortalStateQuery The current query, for fluid interface
	 */
	public function filterByUpdatedAt($updatedAt = null, $comparison = null)
	{
		if (is_array($updatedAt)) {
			$useMinMax = false;
			if (isset($updatedAt['min'])) {
				$this->addUsingAlias(afPortalStatePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($updatedAt['max'])) {
				$this->addUsingAlias(afPortalStatePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(afPortalStatePeer::UPDATED_AT, $updatedAt, $comparison);
	}

	/**
	 * Exclude object from result
	 *
	 * @param     afPortalState $afPortalState Object to remove from the list of results
	 *
	 * @return    afPortalStateQuery The current query, for fluid interface
	 */
	public function prune($afPortalState = null)
	{
		if ($afPortalState) {
			$this->addUsingAlias(afPortalStatePeer::ID, $afPortalState->getId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseafPortalStateQuery
