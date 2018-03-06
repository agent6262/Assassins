<?php

namespace Base;

use \Game as ChildGame;
use \GameQuery as ChildGameQuery;
use \Exception;
use \PDO;
use Map\GameTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'games' table.
 *
 * 
 *
 * @method     ChildGameQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildGameQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildGameQuery orderByActive($order = Criteria::ASC) Order by the active column
 * @method     ChildGameQuery orderByOwnerId($order = Criteria::ASC) Order by the owner_id column
 * @method     ChildGameQuery orderByStarted($order = Criteria::ASC) Order by the started column
 * @method     ChildGameQuery orderByPaused($order = Criteria::ASC) Order by the paused column
 * @method     ChildGameQuery orderByRules($order = Criteria::ASC) Order by the rules column
 * @method     ChildGameQuery orderByInvite($order = Criteria::ASC) Order by the invite column
 * @method     ChildGameQuery orderByRequestInvite($order = Criteria::ASC) Order by the request_invite column
 * @method     ChildGameQuery orderByAutoJoinGroupId($order = Criteria::ASC) Order by the auto_join_group_id column
 * @method     ChildGameQuery orderByAutoPlace($order = Criteria::ASC) Order by the auto_place column
 * @method     ChildGameQuery orderByDuplicateGameOnComplete($order = Criteria::ASC) Order by the duplicate_game_on_complete column
 *
 * @method     ChildGameQuery groupById() Group by the id column
 * @method     ChildGameQuery groupByName() Group by the name column
 * @method     ChildGameQuery groupByActive() Group by the active column
 * @method     ChildGameQuery groupByOwnerId() Group by the owner_id column
 * @method     ChildGameQuery groupByStarted() Group by the started column
 * @method     ChildGameQuery groupByPaused() Group by the paused column
 * @method     ChildGameQuery groupByRules() Group by the rules column
 * @method     ChildGameQuery groupByInvite() Group by the invite column
 * @method     ChildGameQuery groupByRequestInvite() Group by the request_invite column
 * @method     ChildGameQuery groupByAutoJoinGroupId() Group by the auto_join_group_id column
 * @method     ChildGameQuery groupByAutoPlace() Group by the auto_place column
 * @method     ChildGameQuery groupByDuplicateGameOnComplete() Group by the duplicate_game_on_complete column
 *
 * @method     ChildGameQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildGameQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildGameQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildGameQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildGameQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildGameQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildGameQuery leftJoinOwner($relationAlias = null) Adds a LEFT JOIN clause to the query using the Owner relation
 * @method     ChildGameQuery rightJoinOwner($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Owner relation
 * @method     ChildGameQuery innerJoinOwner($relationAlias = null) Adds a INNER JOIN clause to the query using the Owner relation
 *
 * @method     ChildGameQuery joinWithOwner($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Owner relation
 *
 * @method     ChildGameQuery leftJoinWithOwner() Adds a LEFT JOIN clause and with to the query using the Owner relation
 * @method     ChildGameQuery rightJoinWithOwner() Adds a RIGHT JOIN clause and with to the query using the Owner relation
 * @method     ChildGameQuery innerJoinWithOwner() Adds a INNER JOIN clause and with to the query using the Owner relation
 *
 * @method     ChildGameQuery leftJoinAutoJoinGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the AutoJoinGroup relation
 * @method     ChildGameQuery rightJoinAutoJoinGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AutoJoinGroup relation
 * @method     ChildGameQuery innerJoinAutoJoinGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the AutoJoinGroup relation
 *
 * @method     ChildGameQuery joinWithAutoJoinGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the AutoJoinGroup relation
 *
 * @method     ChildGameQuery leftJoinWithAutoJoinGroup() Adds a LEFT JOIN clause and with to the query using the AutoJoinGroup relation
 * @method     ChildGameQuery rightJoinWithAutoJoinGroup() Adds a RIGHT JOIN clause and with to the query using the AutoJoinGroup relation
 * @method     ChildGameQuery innerJoinWithAutoJoinGroup() Adds a INNER JOIN clause and with to the query using the AutoJoinGroup relation
 *
 * @method     ChildGameQuery leftJoinUserGame($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserGame relation
 * @method     ChildGameQuery rightJoinUserGame($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserGame relation
 * @method     ChildGameQuery innerJoinUserGame($relationAlias = null) Adds a INNER JOIN clause to the query using the UserGame relation
 *
 * @method     ChildGameQuery joinWithUserGame($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserGame relation
 *
 * @method     ChildGameQuery leftJoinWithUserGame() Adds a LEFT JOIN clause and with to the query using the UserGame relation
 * @method     ChildGameQuery rightJoinWithUserGame() Adds a RIGHT JOIN clause and with to the query using the UserGame relation
 * @method     ChildGameQuery innerJoinWithUserGame() Adds a INNER JOIN clause and with to the query using the UserGame relation
 *
 * @method     ChildGameQuery leftJoinGameGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the GameGroup relation
 * @method     ChildGameQuery rightJoinGameGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GameGroup relation
 * @method     ChildGameQuery innerJoinGameGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the GameGroup relation
 *
 * @method     ChildGameQuery joinWithGameGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the GameGroup relation
 *
 * @method     ChildGameQuery leftJoinWithGameGroup() Adds a LEFT JOIN clause and with to the query using the GameGroup relation
 * @method     ChildGameQuery rightJoinWithGameGroup() Adds a RIGHT JOIN clause and with to the query using the GameGroup relation
 * @method     ChildGameQuery innerJoinWithGameGroup() Adds a INNER JOIN clause and with to the query using the GameGroup relation
 *
 * @method     ChildGameQuery leftJoinCircuitPlayer($relationAlias = null) Adds a LEFT JOIN clause to the query using the CircuitPlayer relation
 * @method     ChildGameQuery rightJoinCircuitPlayer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CircuitPlayer relation
 * @method     ChildGameQuery innerJoinCircuitPlayer($relationAlias = null) Adds a INNER JOIN clause to the query using the CircuitPlayer relation
 *
 * @method     ChildGameQuery joinWithCircuitPlayer($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CircuitPlayer relation
 *
 * @method     ChildGameQuery leftJoinWithCircuitPlayer() Adds a LEFT JOIN clause and with to the query using the CircuitPlayer relation
 * @method     ChildGameQuery rightJoinWithCircuitPlayer() Adds a RIGHT JOIN clause and with to the query using the CircuitPlayer relation
 * @method     ChildGameQuery innerJoinWithCircuitPlayer() Adds a INNER JOIN clause and with to the query using the CircuitPlayer relation
 *
 * @method     ChildGameQuery leftJoinGamePlayerGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the GamePlayerGroup relation
 * @method     ChildGameQuery rightJoinGamePlayerGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GamePlayerGroup relation
 * @method     ChildGameQuery innerJoinGamePlayerGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the GamePlayerGroup relation
 *
 * @method     ChildGameQuery joinWithGamePlayerGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the GamePlayerGroup relation
 *
 * @method     ChildGameQuery leftJoinWithGamePlayerGroup() Adds a LEFT JOIN clause and with to the query using the GamePlayerGroup relation
 * @method     ChildGameQuery rightJoinWithGamePlayerGroup() Adds a RIGHT JOIN clause and with to the query using the GamePlayerGroup relation
 * @method     ChildGameQuery innerJoinWithGamePlayerGroup() Adds a INNER JOIN clause and with to the query using the GamePlayerGroup relation
 *
 * @method     ChildGameQuery leftJoinGameActionLog($relationAlias = null) Adds a LEFT JOIN clause to the query using the GameActionLog relation
 * @method     ChildGameQuery rightJoinGameActionLog($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GameActionLog relation
 * @method     ChildGameQuery innerJoinGameActionLog($relationAlias = null) Adds a INNER JOIN clause to the query using the GameActionLog relation
 *
 * @method     ChildGameQuery joinWithGameActionLog($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the GameActionLog relation
 *
 * @method     ChildGameQuery leftJoinWithGameActionLog() Adds a LEFT JOIN clause and with to the query using the GameActionLog relation
 * @method     ChildGameQuery rightJoinWithGameActionLog() Adds a RIGHT JOIN clause and with to the query using the GameActionLog relation
 * @method     ChildGameQuery innerJoinWithGameActionLog() Adds a INNER JOIN clause and with to the query using the GameActionLog relation
 *
 * @method     \UserQuery|\GroupQuery|\UserGameQuery|\GameGroupQuery|\CircuitPlayerQuery|\GamePlayerGroupQuery|\GameActionLogQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildGame findOne(ConnectionInterface $con = null) Return the first ChildGame matching the query
 * @method     ChildGame findOneOrCreate(ConnectionInterface $con = null) Return the first ChildGame matching the query, or a new ChildGame object populated from the query conditions when no match is found
 *
 * @method     ChildGame findOneById(int $id) Return the first ChildGame filtered by the id column
 * @method     ChildGame findOneByName(string $name) Return the first ChildGame filtered by the name column
 * @method     ChildGame findOneByActive(boolean $active) Return the first ChildGame filtered by the active column
 * @method     ChildGame findOneByOwnerId(int $owner_id) Return the first ChildGame filtered by the owner_id column
 * @method     ChildGame findOneByStarted(boolean $started) Return the first ChildGame filtered by the started column
 * @method     ChildGame findOneByPaused(boolean $paused) Return the first ChildGame filtered by the paused column
 * @method     ChildGame findOneByRules(string $rules) Return the first ChildGame filtered by the rules column
 * @method     ChildGame findOneByInvite(boolean $invite) Return the first ChildGame filtered by the invite column
 * @method     ChildGame findOneByRequestInvite(boolean $request_invite) Return the first ChildGame filtered by the request_invite column
 * @method     ChildGame findOneByAutoJoinGroupId(int $auto_join_group_id) Return the first ChildGame filtered by the auto_join_group_id column
 * @method     ChildGame findOneByAutoPlace(boolean $auto_place) Return the first ChildGame filtered by the auto_place column
 * @method     ChildGame findOneByDuplicateGameOnComplete(boolean $duplicate_game_on_complete) Return the first ChildGame filtered by the duplicate_game_on_complete column *

 * @method     ChildGame requirePk($key, ConnectionInterface $con = null) Return the ChildGame by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGame requireOne(ConnectionInterface $con = null) Return the first ChildGame matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildGame requireOneById(int $id) Return the first ChildGame filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGame requireOneByName(string $name) Return the first ChildGame filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGame requireOneByActive(boolean $active) Return the first ChildGame filtered by the active column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGame requireOneByOwnerId(int $owner_id) Return the first ChildGame filtered by the owner_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGame requireOneByStarted(boolean $started) Return the first ChildGame filtered by the started column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGame requireOneByPaused(boolean $paused) Return the first ChildGame filtered by the paused column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGame requireOneByRules(string $rules) Return the first ChildGame filtered by the rules column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGame requireOneByInvite(boolean $invite) Return the first ChildGame filtered by the invite column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGame requireOneByRequestInvite(boolean $request_invite) Return the first ChildGame filtered by the request_invite column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGame requireOneByAutoJoinGroupId(int $auto_join_group_id) Return the first ChildGame filtered by the auto_join_group_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGame requireOneByAutoPlace(boolean $auto_place) Return the first ChildGame filtered by the auto_place column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGame requireOneByDuplicateGameOnComplete(boolean $duplicate_game_on_complete) Return the first ChildGame filtered by the duplicate_game_on_complete column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildGame[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildGame objects based on current ModelCriteria
 * @method     ChildGame[]|ObjectCollection findById(int $id) Return ChildGame objects filtered by the id column
 * @method     ChildGame[]|ObjectCollection findByName(string $name) Return ChildGame objects filtered by the name column
 * @method     ChildGame[]|ObjectCollection findByActive(boolean $active) Return ChildGame objects filtered by the active column
 * @method     ChildGame[]|ObjectCollection findByOwnerId(int $owner_id) Return ChildGame objects filtered by the owner_id column
 * @method     ChildGame[]|ObjectCollection findByStarted(boolean $started) Return ChildGame objects filtered by the started column
 * @method     ChildGame[]|ObjectCollection findByPaused(boolean $paused) Return ChildGame objects filtered by the paused column
 * @method     ChildGame[]|ObjectCollection findByRules(string $rules) Return ChildGame objects filtered by the rules column
 * @method     ChildGame[]|ObjectCollection findByInvite(boolean $invite) Return ChildGame objects filtered by the invite column
 * @method     ChildGame[]|ObjectCollection findByRequestInvite(boolean $request_invite) Return ChildGame objects filtered by the request_invite column
 * @method     ChildGame[]|ObjectCollection findByAutoJoinGroupId(int $auto_join_group_id) Return ChildGame objects filtered by the auto_join_group_id column
 * @method     ChildGame[]|ObjectCollection findByAutoPlace(boolean $auto_place) Return ChildGame objects filtered by the auto_place column
 * @method     ChildGame[]|ObjectCollection findByDuplicateGameOnComplete(boolean $duplicate_game_on_complete) Return ChildGame objects filtered by the duplicate_game_on_complete column
 * @method     ChildGame[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class GameQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\GameQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'assassins', $modelName = '\\Game', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildGameQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildGameQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildGameQuery) {
            return $criteria;
        }
        $query = new ChildGameQuery();
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
     * @return ChildGame|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(GameTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = GameTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildGame A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `name`, `active`, `owner_id`, `started`, `paused`, `rules`, `invite`, `request_invite`, `auto_join_group_id`, `auto_place`, `duplicate_game_on_complete` FROM `games` WHERE `id` = :p0';
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
            /** @var ChildGame $obj */
            $obj = new ChildGame();
            $obj->hydrate($row);
            GameTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildGame|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildGameQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(GameTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildGameQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(GameTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildGameQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(GameTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(GameTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GameTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGameQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GameTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the active column
     *
     * Example usage:
     * <code>
     * $query->filterByActive(true); // WHERE active = true
     * $query->filterByActive('yes'); // WHERE active = true
     * </code>
     *
     * @param     boolean|string $active The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGameQuery The current query, for fluid interface
     */
    public function filterByActive($active = null, $comparison = null)
    {
        if (is_string($active)) {
            $active = in_array(strtolower($active), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(GameTableMap::COL_ACTIVE, $active, $comparison);
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
     * @return $this|ChildGameQuery The current query, for fluid interface
     */
    public function filterByOwnerId($ownerId = null, $comparison = null)
    {
        if (is_array($ownerId)) {
            $useMinMax = false;
            if (isset($ownerId['min'])) {
                $this->addUsingAlias(GameTableMap::COL_OWNER_ID, $ownerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ownerId['max'])) {
                $this->addUsingAlias(GameTableMap::COL_OWNER_ID, $ownerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GameTableMap::COL_OWNER_ID, $ownerId, $comparison);
    }

    /**
     * Filter the query on the started column
     *
     * Example usage:
     * <code>
     * $query->filterByStarted(true); // WHERE started = true
     * $query->filterByStarted('yes'); // WHERE started = true
     * </code>
     *
     * @param     boolean|string $started The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGameQuery The current query, for fluid interface
     */
    public function filterByStarted($started = null, $comparison = null)
    {
        if (is_string($started)) {
            $started = in_array(strtolower($started), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(GameTableMap::COL_STARTED, $started, $comparison);
    }

    /**
     * Filter the query on the paused column
     *
     * Example usage:
     * <code>
     * $query->filterByPaused(true); // WHERE paused = true
     * $query->filterByPaused('yes'); // WHERE paused = true
     * </code>
     *
     * @param     boolean|string $paused The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGameQuery The current query, for fluid interface
     */
    public function filterByPaused($paused = null, $comparison = null)
    {
        if (is_string($paused)) {
            $paused = in_array(strtolower($paused), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(GameTableMap::COL_PAUSED, $paused, $comparison);
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
     * @return $this|ChildGameQuery The current query, for fluid interface
     */
    public function filterByRules($rules = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($rules)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GameTableMap::COL_RULES, $rules, $comparison);
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
     * @return $this|ChildGameQuery The current query, for fluid interface
     */
    public function filterByInvite($invite = null, $comparison = null)
    {
        if (is_string($invite)) {
            $invite = in_array(strtolower($invite), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(GameTableMap::COL_INVITE, $invite, $comparison);
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
     * @return $this|ChildGameQuery The current query, for fluid interface
     */
    public function filterByRequestInvite($requestInvite = null, $comparison = null)
    {
        if (is_string($requestInvite)) {
            $requestInvite = in_array(strtolower($requestInvite), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(GameTableMap::COL_REQUEST_INVITE, $requestInvite, $comparison);
    }

    /**
     * Filter the query on the auto_join_group_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAutoJoinGroupId(1234); // WHERE auto_join_group_id = 1234
     * $query->filterByAutoJoinGroupId(array(12, 34)); // WHERE auto_join_group_id IN (12, 34)
     * $query->filterByAutoJoinGroupId(array('min' => 12)); // WHERE auto_join_group_id > 12
     * </code>
     *
     * @see       filterByAutoJoinGroup()
     *
     * @param     mixed $autoJoinGroupId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGameQuery The current query, for fluid interface
     */
    public function filterByAutoJoinGroupId($autoJoinGroupId = null, $comparison = null)
    {
        if (is_array($autoJoinGroupId)) {
            $useMinMax = false;
            if (isset($autoJoinGroupId['min'])) {
                $this->addUsingAlias(GameTableMap::COL_AUTO_JOIN_GROUP_ID, $autoJoinGroupId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($autoJoinGroupId['max'])) {
                $this->addUsingAlias(GameTableMap::COL_AUTO_JOIN_GROUP_ID, $autoJoinGroupId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GameTableMap::COL_AUTO_JOIN_GROUP_ID, $autoJoinGroupId, $comparison);
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
     * @return $this|ChildGameQuery The current query, for fluid interface
     */
    public function filterByAutoPlace($autoPlace = null, $comparison = null)
    {
        if (is_string($autoPlace)) {
            $autoPlace = in_array(strtolower($autoPlace), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(GameTableMap::COL_AUTO_PLACE, $autoPlace, $comparison);
    }

    /**
     * Filter the query on the duplicate_game_on_complete column
     *
     * Example usage:
     * <code>
     * $query->filterByDuplicateGameOnComplete(true); // WHERE duplicate_game_on_complete = true
     * $query->filterByDuplicateGameOnComplete('yes'); // WHERE duplicate_game_on_complete = true
     * </code>
     *
     * @param     boolean|string $duplicateGameOnComplete The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGameQuery The current query, for fluid interface
     */
    public function filterByDuplicateGameOnComplete($duplicateGameOnComplete = null, $comparison = null)
    {
        if (is_string($duplicateGameOnComplete)) {
            $duplicateGameOnComplete = in_array(strtolower($duplicateGameOnComplete), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(GameTableMap::COL_DUPLICATE_GAME_ON_COMPLETE, $duplicateGameOnComplete, $comparison);
    }

    /**
     * Filter the query by a related \User object
     *
     * @param \User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildGameQuery The current query, for fluid interface
     */
    public function filterByOwner($user, $comparison = null)
    {
        if ($user instanceof \User) {
            return $this
                ->addUsingAlias(GameTableMap::COL_OWNER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(GameTableMap::COL_OWNER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildGameQuery The current query, for fluid interface
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
     * Filter the query by a related \Group object
     *
     * @param \Group|ObjectCollection $group The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildGameQuery The current query, for fluid interface
     */
    public function filterByAutoJoinGroup($group, $comparison = null)
    {
        if ($group instanceof \Group) {
            return $this
                ->addUsingAlias(GameTableMap::COL_AUTO_JOIN_GROUP_ID, $group->getId(), $comparison);
        } elseif ($group instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(GameTableMap::COL_AUTO_JOIN_GROUP_ID, $group->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAutoJoinGroup() only accepts arguments of type \Group or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AutoJoinGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildGameQuery The current query, for fluid interface
     */
    public function joinAutoJoinGroup($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AutoJoinGroup');

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
            $this->addJoinObject($join, 'AutoJoinGroup');
        }

        return $this;
    }

    /**
     * Use the AutoJoinGroup relation Group object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \GroupQuery A secondary query class using the current class as primary query
     */
    public function useAutoJoinGroupQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAutoJoinGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AutoJoinGroup', '\GroupQuery');
    }

    /**
     * Filter the query by a related \UserGame object
     *
     * @param \UserGame|ObjectCollection $userGame the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGameQuery The current query, for fluid interface
     */
    public function filterByUserGame($userGame, $comparison = null)
    {
        if ($userGame instanceof \UserGame) {
            return $this
                ->addUsingAlias(GameTableMap::COL_ID, $userGame->getGameId(), $comparison);
        } elseif ($userGame instanceof ObjectCollection) {
            return $this
                ->useUserGameQuery()
                ->filterByPrimaryKeys($userGame->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserGame() only accepts arguments of type \UserGame or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserGame relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildGameQuery The current query, for fluid interface
     */
    public function joinUserGame($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserGame');

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
            $this->addJoinObject($join, 'UserGame');
        }

        return $this;
    }

    /**
     * Use the UserGame relation UserGame object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserGameQuery A secondary query class using the current class as primary query
     */
    public function useUserGameQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserGame($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserGame', '\UserGameQuery');
    }

    /**
     * Filter the query by a related \GameGroup object
     *
     * @param \GameGroup|ObjectCollection $gameGroup the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGameQuery The current query, for fluid interface
     */
    public function filterByGameGroup($gameGroup, $comparison = null)
    {
        if ($gameGroup instanceof \GameGroup) {
            return $this
                ->addUsingAlias(GameTableMap::COL_ID, $gameGroup->getGameId(), $comparison);
        } elseif ($gameGroup instanceof ObjectCollection) {
            return $this
                ->useGameGroupQuery()
                ->filterByPrimaryKeys($gameGroup->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByGameGroup() only accepts arguments of type \GameGroup or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the GameGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildGameQuery The current query, for fluid interface
     */
    public function joinGameGroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('GameGroup');

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
            $this->addJoinObject($join, 'GameGroup');
        }

        return $this;
    }

    /**
     * Use the GameGroup relation GameGroup object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \GameGroupQuery A secondary query class using the current class as primary query
     */
    public function useGameGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinGameGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'GameGroup', '\GameGroupQuery');
    }

    /**
     * Filter the query by a related \CircuitPlayer object
     *
     * @param \CircuitPlayer|ObjectCollection $circuitPlayer the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGameQuery The current query, for fluid interface
     */
    public function filterByCircuitPlayer($circuitPlayer, $comparison = null)
    {
        if ($circuitPlayer instanceof \CircuitPlayer) {
            return $this
                ->addUsingAlias(GameTableMap::COL_ID, $circuitPlayer->getGameId(), $comparison);
        } elseif ($circuitPlayer instanceof ObjectCollection) {
            return $this
                ->useCircuitPlayerQuery()
                ->filterByPrimaryKeys($circuitPlayer->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCircuitPlayer() only accepts arguments of type \CircuitPlayer or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CircuitPlayer relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildGameQuery The current query, for fluid interface
     */
    public function joinCircuitPlayer($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CircuitPlayer');

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
            $this->addJoinObject($join, 'CircuitPlayer');
        }

        return $this;
    }

    /**
     * Use the CircuitPlayer relation CircuitPlayer object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \CircuitPlayerQuery A secondary query class using the current class as primary query
     */
    public function useCircuitPlayerQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCircuitPlayer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CircuitPlayer', '\CircuitPlayerQuery');
    }

    /**
     * Filter the query by a related \GamePlayerGroup object
     *
     * @param \GamePlayerGroup|ObjectCollection $gamePlayerGroup the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGameQuery The current query, for fluid interface
     */
    public function filterByGamePlayerGroup($gamePlayerGroup, $comparison = null)
    {
        if ($gamePlayerGroup instanceof \GamePlayerGroup) {
            return $this
                ->addUsingAlias(GameTableMap::COL_ID, $gamePlayerGroup->getGameId(), $comparison);
        } elseif ($gamePlayerGroup instanceof ObjectCollection) {
            return $this
                ->useGamePlayerGroupQuery()
                ->filterByPrimaryKeys($gamePlayerGroup->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByGamePlayerGroup() only accepts arguments of type \GamePlayerGroup or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the GamePlayerGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildGameQuery The current query, for fluid interface
     */
    public function joinGamePlayerGroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('GamePlayerGroup');

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
            $this->addJoinObject($join, 'GamePlayerGroup');
        }

        return $this;
    }

    /**
     * Use the GamePlayerGroup relation GamePlayerGroup object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \GamePlayerGroupQuery A secondary query class using the current class as primary query
     */
    public function useGamePlayerGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinGamePlayerGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'GamePlayerGroup', '\GamePlayerGroupQuery');
    }

    /**
     * Filter the query by a related \GameActionLog object
     *
     * @param \GameActionLog|ObjectCollection $gameActionLog the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGameQuery The current query, for fluid interface
     */
    public function filterByGameActionLog($gameActionLog, $comparison = null)
    {
        if ($gameActionLog instanceof \GameActionLog) {
            return $this
                ->addUsingAlias(GameTableMap::COL_ID, $gameActionLog->getGameId(), $comparison);
        } elseif ($gameActionLog instanceof ObjectCollection) {
            return $this
                ->useGameActionLogQuery()
                ->filterByPrimaryKeys($gameActionLog->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByGameActionLog() only accepts arguments of type \GameActionLog or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the GameActionLog relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildGameQuery The current query, for fluid interface
     */
    public function joinGameActionLog($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('GameActionLog');

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
            $this->addJoinObject($join, 'GameActionLog');
        }

        return $this;
    }

    /**
     * Use the GameActionLog relation GameActionLog object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \GameActionLogQuery A secondary query class using the current class as primary query
     */
    public function useGameActionLogQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinGameActionLog($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'GameActionLog', '\GameActionLogQuery');
    }

    /**
     * Filter the query by a related User object
     * using the user_games table as cross reference
     *
     * @param User $user the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGameQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserGameQuery()
            ->filterByUser($user, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Group object
     * using the game_groups table as cross reference
     *
     * @param Group $group the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGameQuery The current query, for fluid interface
     */
    public function filterByGroup($group, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useGameGroupQuery()
            ->filterByGroup($group, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PlayerGroup object
     * using the game_player_groups table as cross reference
     *
     * @param PlayerGroup $playerGroup the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGameQuery The current query, for fluid interface
     */
    public function filterByPlayerGroup($playerGroup, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useGamePlayerGroupQuery()
            ->filterByPlayerGroup($playerGroup, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related ActionLog object
     * using the game_action_logs table as cross reference
     *
     * @param ActionLog $actionLog the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGameQuery The current query, for fluid interface
     */
    public function filterByActionLog($actionLog, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useGameActionLogQuery()
            ->filterByActionLog($actionLog, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildGame $game Object to remove from the list of results
     *
     * @return $this|ChildGameQuery The current query, for fluid interface
     */
    public function prune($game = null)
    {
        if ($game) {
            $this->addUsingAlias(GameTableMap::COL_ID, $game->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the games table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GameTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            GameTableMap::clearInstancePool();
            GameTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(GameTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(GameTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            GameTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            GameTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // GameQuery
