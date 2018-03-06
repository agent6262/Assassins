<?php

namespace Base;

use \CircuitPlayer as ChildCircuitPlayer;
use \CircuitPlayerQuery as ChildCircuitPlayerQuery;
use \Exception;
use \PDO;
use Map\CircuitPlayerTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'circuit_players' table.
 *
 * 
 *
 * @method     ChildCircuitPlayerQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildCircuitPlayerQuery orderByActive($order = Criteria::ASC) Order by the active column
 * @method     ChildCircuitPlayerQuery orderByGameId($order = Criteria::ASC) Order by the game_id column
 * @method     ChildCircuitPlayerQuery orderByPlayerId($order = Criteria::ASC) Order by the player_id column
 * @method     ChildCircuitPlayerQuery orderByTargetId($order = Criteria::ASC) Order by the target_id column
 * @method     ChildCircuitPlayerQuery orderByPay($order = Criteria::ASC) Order by the pay column
 * @method     ChildCircuitPlayerQuery orderByMoneySpent($order = Criteria::ASC) Order by the money_spent column
 * @method     ChildCircuitPlayerQuery orderByDateAssigned($order = Criteria::ASC) Order by the date_assigned column
 * @method     ChildCircuitPlayerQuery orderByDateCompleted($order = Criteria::ASC) Order by the date_completed column
 *
 * @method     ChildCircuitPlayerQuery groupById() Group by the id column
 * @method     ChildCircuitPlayerQuery groupByActive() Group by the active column
 * @method     ChildCircuitPlayerQuery groupByGameId() Group by the game_id column
 * @method     ChildCircuitPlayerQuery groupByPlayerId() Group by the player_id column
 * @method     ChildCircuitPlayerQuery groupByTargetId() Group by the target_id column
 * @method     ChildCircuitPlayerQuery groupByPay() Group by the pay column
 * @method     ChildCircuitPlayerQuery groupByMoneySpent() Group by the money_spent column
 * @method     ChildCircuitPlayerQuery groupByDateAssigned() Group by the date_assigned column
 * @method     ChildCircuitPlayerQuery groupByDateCompleted() Group by the date_completed column
 *
 * @method     ChildCircuitPlayerQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCircuitPlayerQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCircuitPlayerQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCircuitPlayerQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCircuitPlayerQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCircuitPlayerQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCircuitPlayerQuery leftJoinGame($relationAlias = null) Adds a LEFT JOIN clause to the query using the Game relation
 * @method     ChildCircuitPlayerQuery rightJoinGame($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Game relation
 * @method     ChildCircuitPlayerQuery innerJoinGame($relationAlias = null) Adds a INNER JOIN clause to the query using the Game relation
 *
 * @method     ChildCircuitPlayerQuery joinWithGame($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Game relation
 *
 * @method     ChildCircuitPlayerQuery leftJoinWithGame() Adds a LEFT JOIN clause and with to the query using the Game relation
 * @method     ChildCircuitPlayerQuery rightJoinWithGame() Adds a RIGHT JOIN clause and with to the query using the Game relation
 * @method     ChildCircuitPlayerQuery innerJoinWithGame() Adds a INNER JOIN clause and with to the query using the Game relation
 *
 * @method     ChildCircuitPlayerQuery leftJoinPlayer($relationAlias = null) Adds a LEFT JOIN clause to the query using the Player relation
 * @method     ChildCircuitPlayerQuery rightJoinPlayer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Player relation
 * @method     ChildCircuitPlayerQuery innerJoinPlayer($relationAlias = null) Adds a INNER JOIN clause to the query using the Player relation
 *
 * @method     ChildCircuitPlayerQuery joinWithPlayer($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Player relation
 *
 * @method     ChildCircuitPlayerQuery leftJoinWithPlayer() Adds a LEFT JOIN clause and with to the query using the Player relation
 * @method     ChildCircuitPlayerQuery rightJoinWithPlayer() Adds a RIGHT JOIN clause and with to the query using the Player relation
 * @method     ChildCircuitPlayerQuery innerJoinWithPlayer() Adds a INNER JOIN clause and with to the query using the Player relation
 *
 * @method     ChildCircuitPlayerQuery leftJoinTarget($relationAlias = null) Adds a LEFT JOIN clause to the query using the Target relation
 * @method     ChildCircuitPlayerQuery rightJoinTarget($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Target relation
 * @method     ChildCircuitPlayerQuery innerJoinTarget($relationAlias = null) Adds a INNER JOIN clause to the query using the Target relation
 *
 * @method     ChildCircuitPlayerQuery joinWithTarget($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Target relation
 *
 * @method     ChildCircuitPlayerQuery leftJoinWithTarget() Adds a LEFT JOIN clause and with to the query using the Target relation
 * @method     ChildCircuitPlayerQuery rightJoinWithTarget() Adds a RIGHT JOIN clause and with to the query using the Target relation
 * @method     ChildCircuitPlayerQuery innerJoinWithTarget() Adds a INNER JOIN clause and with to the query using the Target relation
 *
 * @method     \GameQuery|\UserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildCircuitPlayer findOne(ConnectionInterface $con = null) Return the first ChildCircuitPlayer matching the query
 * @method     ChildCircuitPlayer findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCircuitPlayer matching the query, or a new ChildCircuitPlayer object populated from the query conditions when no match is found
 *
 * @method     ChildCircuitPlayer findOneById(int $id) Return the first ChildCircuitPlayer filtered by the id column
 * @method     ChildCircuitPlayer findOneByActive(boolean $active) Return the first ChildCircuitPlayer filtered by the active column
 * @method     ChildCircuitPlayer findOneByGameId(int $game_id) Return the first ChildCircuitPlayer filtered by the game_id column
 * @method     ChildCircuitPlayer findOneByPlayerId(int $player_id) Return the first ChildCircuitPlayer filtered by the player_id column
 * @method     ChildCircuitPlayer findOneByTargetId(int $target_id) Return the first ChildCircuitPlayer filtered by the target_id column
 * @method     ChildCircuitPlayer findOneByPay(int $pay) Return the first ChildCircuitPlayer filtered by the pay column
 * @method     ChildCircuitPlayer findOneByMoneySpent(int $money_spent) Return the first ChildCircuitPlayer filtered by the money_spent column
 * @method     ChildCircuitPlayer findOneByDateAssigned(string $date_assigned) Return the first ChildCircuitPlayer filtered by the date_assigned column
 * @method     ChildCircuitPlayer findOneByDateCompleted(string $date_completed) Return the first ChildCircuitPlayer filtered by the date_completed column *

 * @method     ChildCircuitPlayer requirePk($key, ConnectionInterface $con = null) Return the ChildCircuitPlayer by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCircuitPlayer requireOne(ConnectionInterface $con = null) Return the first ChildCircuitPlayer matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCircuitPlayer requireOneById(int $id) Return the first ChildCircuitPlayer filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCircuitPlayer requireOneByActive(boolean $active) Return the first ChildCircuitPlayer filtered by the active column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCircuitPlayer requireOneByGameId(int $game_id) Return the first ChildCircuitPlayer filtered by the game_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCircuitPlayer requireOneByPlayerId(int $player_id) Return the first ChildCircuitPlayer filtered by the player_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCircuitPlayer requireOneByTargetId(int $target_id) Return the first ChildCircuitPlayer filtered by the target_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCircuitPlayer requireOneByPay(int $pay) Return the first ChildCircuitPlayer filtered by the pay column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCircuitPlayer requireOneByMoneySpent(int $money_spent) Return the first ChildCircuitPlayer filtered by the money_spent column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCircuitPlayer requireOneByDateAssigned(string $date_assigned) Return the first ChildCircuitPlayer filtered by the date_assigned column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCircuitPlayer requireOneByDateCompleted(string $date_completed) Return the first ChildCircuitPlayer filtered by the date_completed column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCircuitPlayer[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCircuitPlayer objects based on current ModelCriteria
 * @method     ChildCircuitPlayer[]|ObjectCollection findById(int $id) Return ChildCircuitPlayer objects filtered by the id column
 * @method     ChildCircuitPlayer[]|ObjectCollection findByActive(boolean $active) Return ChildCircuitPlayer objects filtered by the active column
 * @method     ChildCircuitPlayer[]|ObjectCollection findByGameId(int $game_id) Return ChildCircuitPlayer objects filtered by the game_id column
 * @method     ChildCircuitPlayer[]|ObjectCollection findByPlayerId(int $player_id) Return ChildCircuitPlayer objects filtered by the player_id column
 * @method     ChildCircuitPlayer[]|ObjectCollection findByTargetId(int $target_id) Return ChildCircuitPlayer objects filtered by the target_id column
 * @method     ChildCircuitPlayer[]|ObjectCollection findByPay(int $pay) Return ChildCircuitPlayer objects filtered by the pay column
 * @method     ChildCircuitPlayer[]|ObjectCollection findByMoneySpent(int $money_spent) Return ChildCircuitPlayer objects filtered by the money_spent column
 * @method     ChildCircuitPlayer[]|ObjectCollection findByDateAssigned(string $date_assigned) Return ChildCircuitPlayer objects filtered by the date_assigned column
 * @method     ChildCircuitPlayer[]|ObjectCollection findByDateCompleted(string $date_completed) Return ChildCircuitPlayer objects filtered by the date_completed column
 * @method     ChildCircuitPlayer[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CircuitPlayerQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\CircuitPlayerQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'assassins', $modelName = '\\CircuitPlayer', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCircuitPlayerQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCircuitPlayerQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCircuitPlayerQuery) {
            return $criteria;
        }
        $query = new ChildCircuitPlayerQuery();
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
     * @return ChildCircuitPlayer|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CircuitPlayerTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CircuitPlayerTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildCircuitPlayer A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `active`, `game_id`, `player_id`, `target_id`, `pay`, `money_spent`, `date_assigned`, `date_completed` FROM `circuit_players` WHERE `id` = :p0';
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
            /** @var ChildCircuitPlayer $obj */
            $obj = new ChildCircuitPlayer();
            $obj->hydrate($row);
            CircuitPlayerTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildCircuitPlayer|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildCircuitPlayerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CircuitPlayerTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCircuitPlayerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CircuitPlayerTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildCircuitPlayerQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CircuitPlayerTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CircuitPlayerTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CircuitPlayerTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildCircuitPlayerQuery The current query, for fluid interface
     */
    public function filterByActive($active = null, $comparison = null)
    {
        if (is_string($active)) {
            $active = in_array(strtolower($active), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CircuitPlayerTableMap::COL_ACTIVE, $active, $comparison);
    }

    /**
     * Filter the query on the game_id column
     *
     * Example usage:
     * <code>
     * $query->filterByGameId(1234); // WHERE game_id = 1234
     * $query->filterByGameId(array(12, 34)); // WHERE game_id IN (12, 34)
     * $query->filterByGameId(array('min' => 12)); // WHERE game_id > 12
     * </code>
     *
     * @see       filterByGame()
     *
     * @param     mixed $gameId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCircuitPlayerQuery The current query, for fluid interface
     */
    public function filterByGameId($gameId = null, $comparison = null)
    {
        if (is_array($gameId)) {
            $useMinMax = false;
            if (isset($gameId['min'])) {
                $this->addUsingAlias(CircuitPlayerTableMap::COL_GAME_ID, $gameId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($gameId['max'])) {
                $this->addUsingAlias(CircuitPlayerTableMap::COL_GAME_ID, $gameId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CircuitPlayerTableMap::COL_GAME_ID, $gameId, $comparison);
    }

    /**
     * Filter the query on the player_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPlayerId(1234); // WHERE player_id = 1234
     * $query->filterByPlayerId(array(12, 34)); // WHERE player_id IN (12, 34)
     * $query->filterByPlayerId(array('min' => 12)); // WHERE player_id > 12
     * </code>
     *
     * @see       filterByPlayer()
     *
     * @param     mixed $playerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCircuitPlayerQuery The current query, for fluid interface
     */
    public function filterByPlayerId($playerId = null, $comparison = null)
    {
        if (is_array($playerId)) {
            $useMinMax = false;
            if (isset($playerId['min'])) {
                $this->addUsingAlias(CircuitPlayerTableMap::COL_PLAYER_ID, $playerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($playerId['max'])) {
                $this->addUsingAlias(CircuitPlayerTableMap::COL_PLAYER_ID, $playerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CircuitPlayerTableMap::COL_PLAYER_ID, $playerId, $comparison);
    }

    /**
     * Filter the query on the target_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTargetId(1234); // WHERE target_id = 1234
     * $query->filterByTargetId(array(12, 34)); // WHERE target_id IN (12, 34)
     * $query->filterByTargetId(array('min' => 12)); // WHERE target_id > 12
     * </code>
     *
     * @see       filterByTarget()
     *
     * @param     mixed $targetId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCircuitPlayerQuery The current query, for fluid interface
     */
    public function filterByTargetId($targetId = null, $comparison = null)
    {
        if (is_array($targetId)) {
            $useMinMax = false;
            if (isset($targetId['min'])) {
                $this->addUsingAlias(CircuitPlayerTableMap::COL_TARGET_ID, $targetId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($targetId['max'])) {
                $this->addUsingAlias(CircuitPlayerTableMap::COL_TARGET_ID, $targetId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CircuitPlayerTableMap::COL_TARGET_ID, $targetId, $comparison);
    }

    /**
     * Filter the query on the pay column
     *
     * Example usage:
     * <code>
     * $query->filterByPay(1234); // WHERE pay = 1234
     * $query->filterByPay(array(12, 34)); // WHERE pay IN (12, 34)
     * $query->filterByPay(array('min' => 12)); // WHERE pay > 12
     * </code>
     *
     * @param     mixed $pay The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCircuitPlayerQuery The current query, for fluid interface
     */
    public function filterByPay($pay = null, $comparison = null)
    {
        if (is_array($pay)) {
            $useMinMax = false;
            if (isset($pay['min'])) {
                $this->addUsingAlias(CircuitPlayerTableMap::COL_PAY, $pay['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pay['max'])) {
                $this->addUsingAlias(CircuitPlayerTableMap::COL_PAY, $pay['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CircuitPlayerTableMap::COL_PAY, $pay, $comparison);
    }

    /**
     * Filter the query on the money_spent column
     *
     * Example usage:
     * <code>
     * $query->filterByMoneySpent(1234); // WHERE money_spent = 1234
     * $query->filterByMoneySpent(array(12, 34)); // WHERE money_spent IN (12, 34)
     * $query->filterByMoneySpent(array('min' => 12)); // WHERE money_spent > 12
     * </code>
     *
     * @param     mixed $moneySpent The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCircuitPlayerQuery The current query, for fluid interface
     */
    public function filterByMoneySpent($moneySpent = null, $comparison = null)
    {
        if (is_array($moneySpent)) {
            $useMinMax = false;
            if (isset($moneySpent['min'])) {
                $this->addUsingAlias(CircuitPlayerTableMap::COL_MONEY_SPENT, $moneySpent['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($moneySpent['max'])) {
                $this->addUsingAlias(CircuitPlayerTableMap::COL_MONEY_SPENT, $moneySpent['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CircuitPlayerTableMap::COL_MONEY_SPENT, $moneySpent, $comparison);
    }

    /**
     * Filter the query on the date_assigned column
     *
     * Example usage:
     * <code>
     * $query->filterByDateAssigned('2011-03-14'); // WHERE date_assigned = '2011-03-14'
     * $query->filterByDateAssigned('now'); // WHERE date_assigned = '2011-03-14'
     * $query->filterByDateAssigned(array('max' => 'yesterday')); // WHERE date_assigned > '2011-03-13'
     * </code>
     *
     * @param     mixed $dateAssigned The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCircuitPlayerQuery The current query, for fluid interface
     */
    public function filterByDateAssigned($dateAssigned = null, $comparison = null)
    {
        if (is_array($dateAssigned)) {
            $useMinMax = false;
            if (isset($dateAssigned['min'])) {
                $this->addUsingAlias(CircuitPlayerTableMap::COL_DATE_ASSIGNED, $dateAssigned['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateAssigned['max'])) {
                $this->addUsingAlias(CircuitPlayerTableMap::COL_DATE_ASSIGNED, $dateAssigned['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CircuitPlayerTableMap::COL_DATE_ASSIGNED, $dateAssigned, $comparison);
    }

    /**
     * Filter the query on the date_completed column
     *
     * Example usage:
     * <code>
     * $query->filterByDateCompleted('2011-03-14'); // WHERE date_completed = '2011-03-14'
     * $query->filterByDateCompleted('now'); // WHERE date_completed = '2011-03-14'
     * $query->filterByDateCompleted(array('max' => 'yesterday')); // WHERE date_completed > '2011-03-13'
     * </code>
     *
     * @param     mixed $dateCompleted The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCircuitPlayerQuery The current query, for fluid interface
     */
    public function filterByDateCompleted($dateCompleted = null, $comparison = null)
    {
        if (is_array($dateCompleted)) {
            $useMinMax = false;
            if (isset($dateCompleted['min'])) {
                $this->addUsingAlias(CircuitPlayerTableMap::COL_DATE_COMPLETED, $dateCompleted['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateCompleted['max'])) {
                $this->addUsingAlias(CircuitPlayerTableMap::COL_DATE_COMPLETED, $dateCompleted['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CircuitPlayerTableMap::COL_DATE_COMPLETED, $dateCompleted, $comparison);
    }

    /**
     * Filter the query by a related \Game object
     *
     * @param \Game|ObjectCollection $game The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCircuitPlayerQuery The current query, for fluid interface
     */
    public function filterByGame($game, $comparison = null)
    {
        if ($game instanceof \Game) {
            return $this
                ->addUsingAlias(CircuitPlayerTableMap::COL_GAME_ID, $game->getId(), $comparison);
        } elseif ($game instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CircuitPlayerTableMap::COL_GAME_ID, $game->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByGame() only accepts arguments of type \Game or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Game relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCircuitPlayerQuery The current query, for fluid interface
     */
    public function joinGame($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Game');

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
            $this->addJoinObject($join, 'Game');
        }

        return $this;
    }

    /**
     * Use the Game relation Game object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \GameQuery A secondary query class using the current class as primary query
     */
    public function useGameQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinGame($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Game', '\GameQuery');
    }

    /**
     * Filter the query by a related \User object
     *
     * @param \User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCircuitPlayerQuery The current query, for fluid interface
     */
    public function filterByPlayer($user, $comparison = null)
    {
        if ($user instanceof \User) {
            return $this
                ->addUsingAlias(CircuitPlayerTableMap::COL_PLAYER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CircuitPlayerTableMap::COL_PLAYER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPlayer() only accepts arguments of type \User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Player relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCircuitPlayerQuery The current query, for fluid interface
     */
    public function joinPlayer($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Player');

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
            $this->addJoinObject($join, 'Player');
        }

        return $this;
    }

    /**
     * Use the Player relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserQuery A secondary query class using the current class as primary query
     */
    public function usePlayerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Player', '\UserQuery');
    }

    /**
     * Filter the query by a related \User object
     *
     * @param \User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCircuitPlayerQuery The current query, for fluid interface
     */
    public function filterByTarget($user, $comparison = null)
    {
        if ($user instanceof \User) {
            return $this
                ->addUsingAlias(CircuitPlayerTableMap::COL_TARGET_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CircuitPlayerTableMap::COL_TARGET_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByTarget() only accepts arguments of type \User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Target relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCircuitPlayerQuery The current query, for fluid interface
     */
    public function joinTarget($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Target');

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
            $this->addJoinObject($join, 'Target');
        }

        return $this;
    }

    /**
     * Use the Target relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserQuery A secondary query class using the current class as primary query
     */
    public function useTargetQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTarget($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Target', '\UserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCircuitPlayer $circuitPlayer Object to remove from the list of results
     *
     * @return $this|ChildCircuitPlayerQuery The current query, for fluid interface
     */
    public function prune($circuitPlayer = null)
    {
        if ($circuitPlayer) {
            $this->addUsingAlias(CircuitPlayerTableMap::COL_ID, $circuitPlayer->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the circuit_players table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CircuitPlayerTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CircuitPlayerTableMap::clearInstancePool();
            CircuitPlayerTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CircuitPlayerTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CircuitPlayerTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            CircuitPlayerTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            CircuitPlayerTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // CircuitPlayerQuery
