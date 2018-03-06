<?php

namespace Base;

use \LtsActionLog as ChildLtsActionLog;
use \LtsActionLogQuery as ChildLtsActionLogQuery;
use \Exception;
use \PDO;
use Map\LtsActionLogTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'lts_action_logs' table.
 *
 * 
 *
 * @method     ChildLtsActionLogQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildLtsActionLogQuery orderByDate($order = Criteria::ASC) Order by the date column
 * @method     ChildLtsActionLogQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method     ChildLtsActionLogQuery orderByAction($order = Criteria::ASC) Order by the action column
 *
 * @method     ChildLtsActionLogQuery groupById() Group by the id column
 * @method     ChildLtsActionLogQuery groupByDate() Group by the date column
 * @method     ChildLtsActionLogQuery groupByType() Group by the type column
 * @method     ChildLtsActionLogQuery groupByAction() Group by the action column
 *
 * @method     ChildLtsActionLogQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLtsActionLogQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLtsActionLogQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLtsActionLogQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildLtsActionLogQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildLtsActionLogQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildLtsActionLogQuery leftJoinLtsGameActionLog($relationAlias = null) Adds a LEFT JOIN clause to the query using the LtsGameActionLog relation
 * @method     ChildLtsActionLogQuery rightJoinLtsGameActionLog($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LtsGameActionLog relation
 * @method     ChildLtsActionLogQuery innerJoinLtsGameActionLog($relationAlias = null) Adds a INNER JOIN clause to the query using the LtsGameActionLog relation
 *
 * @method     ChildLtsActionLogQuery joinWithLtsGameActionLog($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the LtsGameActionLog relation
 *
 * @method     ChildLtsActionLogQuery leftJoinWithLtsGameActionLog() Adds a LEFT JOIN clause and with to the query using the LtsGameActionLog relation
 * @method     ChildLtsActionLogQuery rightJoinWithLtsGameActionLog() Adds a RIGHT JOIN clause and with to the query using the LtsGameActionLog relation
 * @method     ChildLtsActionLogQuery innerJoinWithLtsGameActionLog() Adds a INNER JOIN clause and with to the query using the LtsGameActionLog relation
 *
 * @method     \LtsGameActionLogQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLtsActionLog findOne(ConnectionInterface $con = null) Return the first ChildLtsActionLog matching the query
 * @method     ChildLtsActionLog findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLtsActionLog matching the query, or a new ChildLtsActionLog object populated from the query conditions when no match is found
 *
 * @method     ChildLtsActionLog findOneById(int $id) Return the first ChildLtsActionLog filtered by the id column
 * @method     ChildLtsActionLog findOneByDate(string $date) Return the first ChildLtsActionLog filtered by the date column
 * @method     ChildLtsActionLog findOneByType(int $type) Return the first ChildLtsActionLog filtered by the type column
 * @method     ChildLtsActionLog findOneByAction(string $action) Return the first ChildLtsActionLog filtered by the action column *

 * @method     ChildLtsActionLog requirePk($key, ConnectionInterface $con = null) Return the ChildLtsActionLog by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLtsActionLog requireOne(ConnectionInterface $con = null) Return the first ChildLtsActionLog matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLtsActionLog requireOneById(int $id) Return the first ChildLtsActionLog filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLtsActionLog requireOneByDate(string $date) Return the first ChildLtsActionLog filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLtsActionLog requireOneByType(int $type) Return the first ChildLtsActionLog filtered by the type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLtsActionLog requireOneByAction(string $action) Return the first ChildLtsActionLog filtered by the action column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLtsActionLog[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLtsActionLog objects based on current ModelCriteria
 * @method     ChildLtsActionLog[]|ObjectCollection findById(int $id) Return ChildLtsActionLog objects filtered by the id column
 * @method     ChildLtsActionLog[]|ObjectCollection findByDate(string $date) Return ChildLtsActionLog objects filtered by the date column
 * @method     ChildLtsActionLog[]|ObjectCollection findByType(int $type) Return ChildLtsActionLog objects filtered by the type column
 * @method     ChildLtsActionLog[]|ObjectCollection findByAction(string $action) Return ChildLtsActionLog objects filtered by the action column
 * @method     ChildLtsActionLog[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LtsActionLogQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\LtsActionLogQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'assassins', $modelName = '\\LtsActionLog', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLtsActionLogQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLtsActionLogQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLtsActionLogQuery) {
            return $criteria;
        }
        $query = new ChildLtsActionLogQuery();
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
     * @return ChildLtsActionLog|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LtsActionLogTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = LtsActionLogTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildLtsActionLog A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `date`, `type`, `action` FROM `lts_action_logs` WHERE `id` = :p0';
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
            /** @var ChildLtsActionLog $obj */
            $obj = new ChildLtsActionLog();
            $obj->hydrate($row);
            LtsActionLogTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildLtsActionLog|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildLtsActionLogQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LtsActionLogTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLtsActionLogQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LtsActionLogTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildLtsActionLogQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(LtsActionLogTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(LtsActionLogTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LtsActionLogTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildLtsActionLogQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(LtsActionLogTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(LtsActionLogTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LtsActionLogTableMap::COL_DATE, $date, $comparison);
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
     * @return $this|ChildLtsActionLogQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (is_array($type)) {
            $useMinMax = false;
            if (isset($type['min'])) {
                $this->addUsingAlias(LtsActionLogTableMap::COL_TYPE, $type['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($type['max'])) {
                $this->addUsingAlias(LtsActionLogTableMap::COL_TYPE, $type['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LtsActionLogTableMap::COL_TYPE, $type, $comparison);
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
     * @return $this|ChildLtsActionLogQuery The current query, for fluid interface
     */
    public function filterByAction($action = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($action)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LtsActionLogTableMap::COL_ACTION, $action, $comparison);
    }

    /**
     * Filter the query by a related \LtsGameActionLog object
     *
     * @param \LtsGameActionLog|ObjectCollection $ltsGameActionLog the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLtsActionLogQuery The current query, for fluid interface
     */
    public function filterByLtsGameActionLog($ltsGameActionLog, $comparison = null)
    {
        if ($ltsGameActionLog instanceof \LtsGameActionLog) {
            return $this
                ->addUsingAlias(LtsActionLogTableMap::COL_ID, $ltsGameActionLog->getActionId(), $comparison);
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
     * @return $this|ChildLtsActionLogQuery The current query, for fluid interface
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
     * Filter the query by a related LtsGame object
     * using the lts_game_action_logs table as cross reference
     *
     * @param LtsGame $ltsGame the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLtsActionLogQuery The current query, for fluid interface
     */
    public function filterByLtsGame($ltsGame, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useLtsGameActionLogQuery()
            ->filterByLtsGame($ltsGame, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLtsActionLog $ltsActionLog Object to remove from the list of results
     *
     * @return $this|ChildLtsActionLogQuery The current query, for fluid interface
     */
    public function prune($ltsActionLog = null)
    {
        if ($ltsActionLog) {
            $this->addUsingAlias(LtsActionLogTableMap::COL_ID, $ltsActionLog->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the lts_action_logs table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LtsActionLogTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LtsActionLogTableMap::clearInstancePool();
            LtsActionLogTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(LtsActionLogTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LtsActionLogTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            LtsActionLogTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            LtsActionLogTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // LtsActionLogQuery
