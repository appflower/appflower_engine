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
	 * @return    afPortalState|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = afPortalStatePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(afPortalStatePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    afPortalState A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `ID_XML`, `USER_ID`, `LAYOUT_TYPE`, `CONTENT`, `CREATED_AT`, `UPDATED_AT` FROM `af_portal_state` WHERE `ID` = :p0';
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
			$obj = new afPortalState();
			$obj->hydrate($row);
			afPortalStatePeer::addInstanceToPool($obj, (string) $row[0]);
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
	 * @return    afPortalState|array|mixed the result, formatted by the current formatter
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
	 * Example usage:
	 * <code>
	 * $query->filterByIdXml('fooValue');   // WHERE id_xml = 'fooValue'
	 * $query->filterByIdXml('%fooValue%'); // WHERE id_xml LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $idXml The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
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
	 * Example usage:
	 * <code>
	 * $query->filterByUserId(1234); // WHERE user_id = 1234
	 * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
	 * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
	 * </code>
	 *
	 * @param     mixed $userId The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
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
	 * Example usage:
	 * <code>
	 * $query->filterByLayoutType('fooValue');   // WHERE layout_type = 'fooValue'
	 * $query->filterByLayoutType('%fooValue%'); // WHERE layout_type LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $layoutType The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
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
	 * Example usage:
	 * <code>
	 * $query->filterByContent('fooValue');   // WHERE content = 'fooValue'
	 * $query->filterByContent('%fooValue%'); // WHERE content LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $content The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
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