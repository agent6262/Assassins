<?php

namespace Base;

use \ActionLog as ChildActionLog;
use \ActionLogQuery as ChildActionLogQuery;
use \Exception;
use \PDO;
use Map\ActionLogTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'action_logs' table.
 *
 * 
 *
 * @method     ChildActionLogQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildActionLogQuery orderByDate($order = Criteria::ASC) Order by the date column
 * @method     ChildActionLogQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method     ChildActionLogQuery orderByAction($order = Criteria::ASC) Order by the action column
 *
 * @method     ChildActionLogQuery groupById() Group by the id column
 * @method     ChildActionLogQuery groupByDate() Group by the date column
 * @method     ChildActionLogQuery groupByType() Group by the type column
 * @method     ChildActionLogQuery groupByAction() Group by the action column
 *
 * @method     ChildActionLogQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildActionLogQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildActionLogQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildActionLogQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildActionLogQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildActionLogQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildActionLogQuery leftJoinGameActionLog($relationAlias = null) Adds a LEFT JOIN clause to the query using the GameActionLog relation
 * @method     ChildActionLogQuery rightJoinGameActionLog($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GameActionLog relation
 * @method     ChildActionLogQuery innerJoinGameActionLog($relationAlias = null) Adds a INNER JOIN clause to the query using the GameActionLog relation
 *
 * @method     ChildActionLogQuery joinWithGameActionLog($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the GameActionLog relation
 *
 * @method     ChildActionLogQuery leftJoinWithGameActionLog() Adds a LEFT JOIN clause and with to the query using the GameActionLog relation
 * @method     ChildActionLogQuery rightJoinWithGameActionLog() Adds a RIGHT JOIN clause and with to the query using the GameActionLog relation
 * @method     ChildActionLogQuery innerJoinWithGameActionLog() Adds a INNER JOIN clause and with to the query using the GameActionLog relation
 *
 * @method     \GameActionLogQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildActionLog findOne(ConnectionInterface $con = null) Return the first ChildActionLog matching the query
 * @method     ChildActionLog findOneOrCreate(ConnectionInterface $con = null) Return the first ChildActionLog matching the query, or a new ChildActionLog object populated from the query conditions when no match is found
 *
 * @method     ChildActionLog findOneById(int $id) Return the first ChildActionLog filtered by the id column
 * @method     ChildActionLog findOneByDate(string $date) Return the first ChildActionLog filtered by the date column
 * @method     ChildActionLog findOneByType(int $type) Return the first ChildActionLog filtered by the type column
 * @method     ChildActionLog findOneByAction(string $action) Return the first ChildActionLog filtered by the action column *

 * @method     ChildActionLog requirePk($key, ConnectionInterface $con = null) Return the ChildActionLog by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActionLog requireOne(ConnectionInterface $con = null) Return the first ChildActionLog matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildActionLog requireOneById(int $id) Return the first ChildActionLog filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActionLog requireOneByDate(string $date) Return the first ChildActionLog filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActionLog requireOneByType(int $type) Return the first ChildActionLog filtered by the type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActionLog requireOneByAction(string $action) Return the first ChildActionLog filtered by the action column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildActionLog[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildActionLog objects based on current ModelCriteria
 * @method     ChildActionLog[]|ObjectCollection findById(int $id) Return ChildActionLog objects filtered by the id column
 * @method     ChildActionLog[]|ObjectCollection findByDate(string $date) Return ChildActionLog objects filtered by the date column
 * @method     ChildActionLog[]|ObjectCollection findByType(int $type) Return ChildActionLog objects filtered by the type column
 * @method     ChildActionLog[]|ObjectCollection findByAction(string $action) Return ChildActionLog objects filtered by the action column
 * @method     ChildActionLog[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ActionLogQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\ActionLogQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'assassins', $modelName = '\\ActionLog', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildActionLogQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildActionLogQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildActionLogQuery) {
            return $criteria;
        }
        $query = new ChildActionLogQuery();
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
     * @return ChildActionLog|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ActionLogTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ActionLogTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildActionLog A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `date`, `type`, `action` FROM `action_logs` WHERE `id` = :p0';
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
            /** @var ChildActionLog $obj */
            $obj = new ChildActionLog();
            $obj->hydrate($row);
            ActionLogTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildActionLog|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildActionLogQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ActionLogTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildActionLogQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ActionLogTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildActionLogQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ActionLogTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ActionLogTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActionLogTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE date > '2011-03-13'
     * </code>
     *
     * @param     mixed $date The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActionLogQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(ActionLogTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(ActionLogTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActionLogTableMap::COL_DATE, $date, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * Example usage:
     * <code>
     * $query->filterByType(1234); // WHERE type = 1234
     * $query->filterByType(array(12, 34)); // WHERE type IN (12, 34)
     * $query->filterByType(array('min' => 12)); // WHERE type > 12
     * </code>
     *
     * @param     mixed $type The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActionLogQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (is_array($type)) {
            $useMinMax = false;
            if (isset($type['min'])) {
                $this->addUsingAlias(ActionLogTableMap::COL_TYPE, $type['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($type['max'])) {
                $this->addUsingAlias(ActionLogTableMap::COL_TYPE, $type['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActionLogTableMap::COL_TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the action column
     *
     * Example usage:
     * <code>
     * $query->filterByAction('fooValue');   // WHERE action = 'fooValue'
     * $query->filterByAction('%fooValue%', Criteria::LIKE); // WHERE action LIKE '%fooValue%'
     * </code>
     *
     * @param     string $action The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActionLogQuery The current query, for fluid interface
     */
    public function filterByAction($action = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($action)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActionLogTableMap::COL_ACTION, $action, $comparison);
    }

    /**
     * Filter the query by a related \GameActionLog object
     *
     * @param \GameActionLog|ObjectCollection $gameActionLog the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildActionLogQuery The current query, for fluid interface
     */
    public function filterByGameActionLog($gameActionLog, $comparison = null)
    {
        if ($gameActionLog instanceof \GameActionLog) {
            return $this
                ->addUsingAlias(ActionLogTableMap::COL_ID, $gameActionLog->getActionId(), $comparison);
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
     * @return $this|ChildActionLogQuery The current query, for fluid interface
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
     * Filter the query by a related Game object
     * using the game_action_logs table as cross reference
     *
     * @param Game $game the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildActionLogQuery The current query, for fluid interface
     */
    public function filterByGame($game, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useGameActionLogQuery()
            ->filterByGame($game, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildActionLog $actionLog Object to remove from the list of results
     *
     * @return $this|ChildActionLogQuery The current query, for fluid interface
     */
    public function prune($actionLog = null)
    {
        if ($actionLog) {
            $this->addUsingAlias(ActionLogTableMap::COL_ID, $actionLog->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the action_logs table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ActionLogTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ActionLogTableMap::clearInstancePool();
            ActionLogTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ActionLogTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ActionLogTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            ActionLogTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            ActionLogTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ActionLogQuery
