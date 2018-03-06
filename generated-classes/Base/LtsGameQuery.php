<?php

namespace Base;

use \LtsGame as ChildLtsGame;
use \LtsGameQuery as ChildLtsGameQuery;
use \Exception;
use \PDO;
use Map\LtsGameTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'lts_games' table.
 *
 * 
 *
 * @method     ChildLtsGameQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildLtsGameQuery orderByOwnerId($order = Criteria::ASC) Order by the owner_id column
 * @method     ChildLtsGameQuery orderByRules($order = Criteria::ASC) Order by the rules column
 * @method     ChildLtsGameQuery orderByInvite($order = Criteria::ASC) Order by the invite column
 * @method     ChildLtsGameQuery orderByRequestInvite($order = Criteria::ASC) Order by the request_invite column
 * @method     ChildLtsGameQuery orderByAutoPlace($order = Criteria::ASC) Order by the auto_place column
 *
 * @method     ChildLtsGameQuery groupById() Group by the id column
 * @method     ChildLtsGameQuery groupByOwnerId() Group by the owner_id column
 * @method     ChildLtsGameQuery groupByRules() Group by the rules column
 * @method     ChildLtsGameQuery groupByInvite() Group by the invite column
 * @method     ChildLtsGameQuery groupByRequestInvite() Group by the request_invite column
 * @method     ChildLtsGameQuery groupByAutoPlace() Group by the auto_place column
 *
 * @method     ChildLtsGameQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLtsGameQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLtsGameQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLtsGameQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildLtsGameQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildLtsGameQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildLtsGameQuery leftJoinOwner($relationAlias = null) Adds a LEFT JOIN clause to the query using the Owner relation
 * @method     ChildLtsGameQuery rightJoinOwner($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Owner relation
 * @method     ChildLtsGameQuery innerJoinOwner($relationAlias = null) Adds a INNER JOIN clause to the query using the Owner relation
 *
 * @method     ChildLtsGameQuery joinWithOwner($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Owner relation
 *
 * @method     ChildLtsGameQuery leftJoinWithOwner() Adds a LEFT JOIN clause and with to the query using the Owner relation
 * @method     ChildLtsGameQuery rightJoinWithOwner() Adds a RIGHT JOIN clause and with to the query using the Owner relation
 * @method     ChildLtsGameQuery innerJoinWithOwner() Adds a INNER JOIN clause and with to the query using the Owner relation
 *
 * @method     ChildLtsGameQuery leftJoinLtsCircuitPlayer($relationAlias = null) Adds a LEFT JOIN clause to the query using the LtsCircuitPlayer relation
 * @method     ChildLtsGameQuery rightJoinLtsCircuitPlayer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LtsCircuitPlayer relation
 * @method     ChildLtsGameQuery innerJoinLtsCircuitPlayer($relationAlias = null) Adds a INNER JOIN clause to the query using the LtsCircuitPlayer relation
 *
 * @method     ChildLtsGameQuery joinWithLtsCircuitPlayer($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the LtsCircuitPlayer relation
 *
 * @method     ChildLtsGameQuery leftJoinWithLtsCircuitPlayer() Adds a LEFT JOIN clause and with to the query using the LtsCircuitPlayer relation
 * @method     ChildLtsGameQuery rightJoinWithLtsCircuitPlayer() Adds a RIGHT JOIN clause and with to the query using the LtsCircuitPlayer relation
 * @method     ChildLtsGameQuery innerJoinWithLtsCircuitPlayer() Adds a INNER JOIN clause and with to the query using the LtsCircuitPlayer relation
 *
 * @method     ChildLtsGameQuery leftJoinLtsGameActionLog($relationAlias = null) Adds a LEFT JOIN clause to the query using the LtsGameActionLog relation
 * @method     ChildLtsGameQuery rightJoinLtsGameActionLog($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LtsGameActionLog relation
 * @method     ChildLtsGameQuery innerJoinLtsGameActionLog($relationAlias = null) Adds a INNER JOIN clause to the query using the LtsGameActionLog relation
 *
 * @method     ChildLtsGameQuery joinWithLtsGameActionLog($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the LtsGameActionLog relation
 *
 * @method     ChildLtsGameQuery leftJoinWithLtsGameActionLog() Adds a LEFT JOIN clause and with to the query using the LtsGameActionLog relation
 * @method     ChildLtsGameQuery rightJoinWithLtsGameActionLog() Adds a RIGHT JOIN clause and with to the query using the LtsGameActionLog relation
 * @method     ChildLtsGameQuery innerJoinWithLtsGameActionLog() Adds a INNER JOIN clause and with to the query using the LtsGameActionLog relation
 *
 * @method     \UserQuery|\LtsCircuitPlayerQuery|\LtsGameActionLogQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLtsGame findOne(ConnectionInterface $con = null) Return the first ChildLtsGame matching the query
 * @method     ChildLtsGame findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLtsGame matching the query, or a new ChildLtsGame object populated from the query conditions when no match is found
 *
 * @method     ChildLtsGame findOneById(int $id) Return the first ChildLtsGame filtered by the id column
 * @method     ChildLtsGame findOneByOwnerId(int $owner_id) Return the first ChildLtsGame filtered by the owner_id column
 * @method     ChildLtsGame findOneByRules(string $rules) Return the first ChildLtsGame filtered by the rules column
 * @method     ChildLtsGame findOneByInvite(boolean $invite) Return the first ChildLtsGame filtered by the invite column
 * @method     ChildLtsGame findOneByRequestInvite(boolean $request_invite) Return the first ChildLtsGame filtered by the request_invite column
 * @method     ChildLtsGame findOneByAutoPlace(boolean $auto_place) Return the first ChildLtsGame filtered by the auto_place column *

 * @method     ChildLtsGame requirePk($key, ConnectionInterface $con = null) Return the ChildLtsGame by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLtsGame requireOne(ConnectionInterface $con = null) Return the first ChildLtsGame matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLtsGame requireOneById(int $id) Return the first ChildLtsGame filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLtsGame requireOneByOwnerId(int $owner_id) Return the first ChildLtsGame filtered by the owner_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLtsGame requireOneByRules(string $rules) Return the first ChildLtsGame filtered by the rules column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLtsGame requireOneByInvite(boolean $invite) Return the first ChildLtsGame filtered by the invite column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLtsGame requireOneByRequestInvite(boolean $request_invite) Return the first ChildLtsGame filtered by the request_invite column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLtsGame requireOneByAutoPlace(boolean $auto_place) Return the first ChildLtsGame filtered by the auto_place column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLtsGame[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLtsGame objects based on current ModelCriteria
 * @method     ChildLtsGame[]|ObjectCollection findById(int $id) Return ChildLtsGame objects filtered by the id column
 * @method     ChildLtsGame[]|ObjectCollection findByOwnerId(int $owner_id) Return ChildLtsGame objects filtered by the owner_id column
 * @method     ChildLtsGame[]|ObjectCollection findByRules(string $rules) Return ChildLtsGame objects filtered by the rules column
 * @method     ChildLtsGame[]|ObjectCollection findByInvite(boolean $invite) Return ChildLtsGame objects filtered by the invite column
 * @method     ChildLtsGame[]|ObjectCollection findByRequestInvite(boolean $request_invite) Return ChildLtsGame objects filtered by the request_invite column
 * @method     ChildLtsGame[]|ObjectCollection findByAutoPlace(boolean $auto_place) Return ChildLtsGame objects filtered by the auto_place column
 * @method     ChildLtsGame[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LtsGameQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\LtsGameQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'assassins', $modelName = '\\LtsGame', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLtsGameQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLtsGameQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLtsGameQuery) {
            return $criteria;
        }
        $query = new ChildLtsGameQuery();
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
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildLtsGame|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LtsGameTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = LtsGameTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLtsGame A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `owner_id`, `rules`, `invite`, `request_invite`, `auto_place` FROM `lts_games` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);            
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildLtsGame $obj */
            $obj = new ChildLtsGame();
            $obj->hydrate($row);
            LtsGameTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildLtsGame|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildLtsGameQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LtsGameTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLtsGameQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LtsGameTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildLtsGameQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(LtsGameTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(LtsGameTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LtsGameTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the owner_id column
     *
     * Example usage:
     * <code>
     * $query->filterByOwnerId(1234); // WHERE owner_id = 1234
     * $query->filterByOwnerId(array(12, 34)); // WHERE owner_id IN (12, 34)
     * $query->filterByOwnerId(array('min' => 12)); // WHERE owner_id > 12
     * </code>
     *
     * @see       filterByOwner()
     *
     * @param     mixed $ownerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLtsGameQuery The current query, for fluid interface
     */
    public function filterByOwnerId($ownerId = null, $comparison = null)
    {
        if (is_array($ownerId)) {
            $useMinMax = false;
            if (isset($ownerId['min'])) {
                $this->addUsingAlias(LtsGameTableMap::COL_OWNER_ID, $ownerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ownerId['max'])) {
                $this->addUsingAlias(LtsGameTableMap::COL_OWNER_ID, $ownerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LtsGameTableMap::COL_OWNER_ID, $ownerId, $comparison);
    }

    /**
     * Filter the query on the rules column
     *
     * Example usage:
     * <code>
     * $query->filterByRules('fooValue');   // WHERE rules = 'fooValue'
     * $query->filterByRules('%fooValue%', Criteria::LIKE); // WHERE rules LIKE '%fooValue%'
     * </code>
     *
     * @param     string $rules The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLtsGameQuery The current query, for fluid interface
     */
    public function filterByRules($rules = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($rules)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LtsGameTableMap::COL_RULES, $rules, $comparison);
    }

    /**
     * Filter the query on the invite column
     *
     * Example usage:
     * <code>
     * $query->filterByInvite(true); // WHERE invite = true
     * $query->filterByInvite('yes'); // WHERE invite = true
     * </code>
     *
     * @param     boolean|string $invite The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLtsGameQuery The current query, for fluid interface
     */
    public function filterByInvite($invite = null, $comparison = null)
    {
        if (is_string($invite)) {
            $invite = in_array(strtolower($invite), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(LtsGameTableMap::COL_INVITE, $invite, $comparison);
    }

    /**
     * Filter the query on the request_invite column
     *
     * Example usage:
     * <code>
     * $query->filterByRequestInvite(true); // WHERE request_invite = true
     * $query->filterByRequestInvite('yes'); // WHERE request_invite = true
     * </code>
     *
     * @param     boolean|string $requestInvite The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLtsGameQuery The current query, for fluid interface
     */
    public function filterByRequestInvite($requestInvite = null, $comparison = null)
    {
        if (is_string($requestInvite)) {
            $requestInvite = in_array(strtolower($requestInvite), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(LtsGameTableMap::COL_REQUEST_INVITE, $requestInvite, $comparison);
    }

    /**
     * Filter the query on the auto_place column
     *
     * Example usage:
     * <code>
     * $query->filterByAutoPlace(true); // WHERE auto_place = true
     * $query->filterByAutoPlace('yes'); // WHERE auto_place = true
     * </code>
     *
     * @param     boolean|string $autoPlace The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLtsGameQuery The current query, for fluid interface
     */
    public function filterByAutoPlace($autoPlace = null, $comparison = null)
    {
        if (is_string($autoPlace)) {
            $autoPlace = in_array(strtolower($autoPlace), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(LtsGameTableMap::COL_AUTO_PLACE, $autoPlace, $comparison);
    }

    /**
     * Filter the query by a related \User object
     *
     * @param \User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLtsGameQuery The current query, for fluid interface
     */
    public function filterByOwner($user, $comparison = null)
    {
        if ($user instanceof \User) {
            return $this
                ->addUsingAlias(LtsGameTableMap::COL_OWNER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LtsGameTableMap::COL_OWNER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByOwner() only accepts arguments of type \User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Owner relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLtsGameQuery The current query, for fluid interface
     */
    public function joinOwner($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Owner');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Owner');
        }

        return $this;
    }

    /**
     * Use the Owner relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserQuery A secondary query class using the current class as primary query
     */
    public function useOwnerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOwner($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Owner', '\UserQuery');
    }

    /**
     * Filter the query by a related \LtsCircuitPlayer object
     *
     * @param \LtsCircuitPlayer|ObjectCollection $ltsCircuitPlayer the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLtsGameQuery The current query, for fluid interface
     */
    public function filterByLtsCircuitPlayer($ltsCircuitPlayer, $comparison = null)
    {
        if ($ltsCircuitPlayer instanceof \LtsCircuitPlayer) {
            return $this
                ->addUsingAlias(LtsGameTableMap::COL_ID, $ltsCircuitPlayer->getGameId(), $comparison);
        } elseif ($ltsCircuitPlayer instanceof ObjectCollection) {
            return $this
                ->useLtsCircuitPlayerQuery()
                ->filterByPrimaryKeys($ltsCircuitPlayer->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLtsCircuitPlayer() only accepts arguments of type \LtsCircuitPlayer or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LtsCircuitPlayer relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLtsGameQuery The current query, for fluid interface
     */
    public function joinLtsCircuitPlayer($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LtsCircuitPlayer');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'LtsCircuitPlayer');
        }

        return $this;
    }

    /**
     * Use the LtsCircuitPlayer relation LtsCircuitPlayer object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \LtsCircuitPlayerQuery A secondary query class using the current class as primary query
     */
    public function useLtsCircuitPlayerQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLtsCircuitPlayer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LtsCircuitPlayer', '\LtsCircuitPlayerQuery');
    }

    /**
     * Filter the query by a related \LtsGameActionLog object
     *
     * @param \LtsGameActionLog|ObjectCollection $ltsGameActionLog the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLtsGameQuery The current query, for fluid interface
     */
    public function filterByLtsGameActionLog($ltsGameActionLog, $comparison = null)
    {
        if ($ltsGameActionLog instanceof \LtsGameActionLog) {
            return $this
                ->addUsingAlias(LtsGameTableMap::COL_ID, $ltsGameActionLog->getGameId(), $comparison);
        } elseif ($ltsGameActionLog instanceof ObjectCollection) {
            return $this
                ->useLtsGameActionLogQuery()
                ->filterByPrimaryKeys($ltsGameActionLog->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLtsGameActionLog() only accepts arguments of type \LtsGameActionLog or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LtsGameActionLog relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLtsGameQuery The current query, for fluid interface
     */
    public function joinLtsGameActionLog($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LtsGameActionLog');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'LtsGameActionLog');
        }

        return $this;
    }

    /**
     * Use the LtsGameActionLog relation LtsGameActionLog object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \LtsGameActionLogQuery A secondary query class using the current class as primary query
     */
    public function useLtsGameActionLogQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLtsGameActionLog($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LtsGameActionLog', '\LtsGameActionLogQuery');
    }

    /**
     * Filter the query by a related LtsActionLog object
     * using the lts_game_action_logs table as cross reference
     *
     * @param LtsActionLog $ltsActionLog the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLtsGameQuery The current query, for fluid interface
     */
    public function filterByLtsActionLog($ltsActionLog, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useLtsGameActionLogQuery()
            ->filterByLtsActionLog($ltsActionLog, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLtsGame $ltsGame Object to remove from the list of results
     *
     * @return $this|ChildLtsGameQuery The current query, for fluid interface
     */
    public function prune($ltsGame = null)
    {
        if ($ltsGame) {
            $this->addUsingAlias(LtsGameTableMap::COL_ID, $ltsGame->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the lts_games table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LtsGameTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LtsGameTableMap::clearInstancePool();
            LtsGameTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LtsGameTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LtsGameTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            LtsGameTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            LtsGameTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // LtsGameQuery
