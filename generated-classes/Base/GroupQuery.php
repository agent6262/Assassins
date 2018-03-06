<?php

namespace Base;

use \Group as ChildGroup;
use \GroupQuery as ChildGroupQuery;
use \Exception;
use \PDO;
use Map\GroupTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'groups' table.
 *
 * 
 *
 * @method     ChildGroupQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildGroupQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildGroupQuery orderByPermissions($order = Criteria::ASC) Order by the permissions column
 * @method     ChildGroupQuery orderByRank($order = Criteria::ASC) Order by the rank column
 *
 * @method     ChildGroupQuery groupById() Group by the id column
 * @method     ChildGroupQuery groupByName() Group by the name column
 * @method     ChildGroupQuery groupByPermissions() Group by the permissions column
 * @method     ChildGroupQuery groupByRank() Group by the rank column
 *
 * @method     ChildGroupQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildGroupQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildGroupQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildGroupQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildGroupQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildGroupQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildGroupQuery leftJoinAutoJoinedGame($relationAlias = null) Adds a LEFT JOIN clause to the query using the AutoJoinedGame relation
 * @method     ChildGroupQuery rightJoinAutoJoinedGame($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AutoJoinedGame relation
 * @method     ChildGroupQuery innerJoinAutoJoinedGame($relationAlias = null) Adds a INNER JOIN clause to the query using the AutoJoinedGame relation
 *
 * @method     ChildGroupQuery joinWithAutoJoinedGame($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the AutoJoinedGame relation
 *
 * @method     ChildGroupQuery leftJoinWithAutoJoinedGame() Adds a LEFT JOIN clause and with to the query using the AutoJoinedGame relation
 * @method     ChildGroupQuery rightJoinWithAutoJoinedGame() Adds a RIGHT JOIN clause and with to the query using the AutoJoinedGame relation
 * @method     ChildGroupQuery innerJoinWithAutoJoinedGame() Adds a INNER JOIN clause and with to the query using the AutoJoinedGame relation
 *
 * @method     ChildGroupQuery leftJoinGameGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the GameGroup relation
 * @method     ChildGroupQuery rightJoinGameGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GameGroup relation
 * @method     ChildGroupQuery innerJoinGameGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the GameGroup relation
 *
 * @method     ChildGroupQuery joinWithGameGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the GameGroup relation
 *
 * @method     ChildGroupQuery leftJoinWithGameGroup() Adds a LEFT JOIN clause and with to the query using the GameGroup relation
 * @method     ChildGroupQuery rightJoinWithGameGroup() Adds a RIGHT JOIN clause and with to the query using the GameGroup relation
 * @method     ChildGroupQuery innerJoinWithGameGroup() Adds a INNER JOIN clause and with to the query using the GameGroup relation
 *
 * @method     ChildGroupQuery leftJoinPlayerGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerGroup relation
 * @method     ChildGroupQuery rightJoinPlayerGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerGroup relation
 * @method     ChildGroupQuery innerJoinPlayerGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerGroup relation
 *
 * @method     ChildGroupQuery joinWithPlayerGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerGroup relation
 *
 * @method     ChildGroupQuery leftJoinWithPlayerGroup() Adds a LEFT JOIN clause and with to the query using the PlayerGroup relation
 * @method     ChildGroupQuery rightJoinWithPlayerGroup() Adds a RIGHT JOIN clause and with to the query using the PlayerGroup relation
 * @method     ChildGroupQuery innerJoinWithPlayerGroup() Adds a INNER JOIN clause and with to the query using the PlayerGroup relation
 *
 * @method     ChildGroupQuery leftJoinPresetGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the PresetGroup relation
 * @method     ChildGroupQuery rightJoinPresetGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PresetGroup relation
 * @method     ChildGroupQuery innerJoinPresetGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the PresetGroup relation
 *
 * @method     ChildGroupQuery joinWithPresetGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PresetGroup relation
 *
 * @method     ChildGroupQuery leftJoinWithPresetGroup() Adds a LEFT JOIN clause and with to the query using the PresetGroup relation
 * @method     ChildGroupQuery rightJoinWithPresetGroup() Adds a RIGHT JOIN clause and with to the query using the PresetGroup relation
 * @method     ChildGroupQuery innerJoinWithPresetGroup() Adds a INNER JOIN clause and with to the query using the PresetGroup relation
 *
 * @method     ChildGroupQuery leftJoinSetting($relationAlias = null) Adds a LEFT JOIN clause to the query using the Setting relation
 * @method     ChildGroupQuery rightJoinSetting($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Setting relation
 * @method     ChildGroupQuery innerJoinSetting($relationAlias = null) Adds a INNER JOIN clause to the query using the Setting relation
 *
 * @method     ChildGroupQuery joinWithSetting($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Setting relation
 *
 * @method     ChildGroupQuery leftJoinWithSetting() Adds a LEFT JOIN clause and with to the query using the Setting relation
 * @method     ChildGroupQuery rightJoinWithSetting() Adds a RIGHT JOIN clause and with to the query using the Setting relation
 * @method     ChildGroupQuery innerJoinWithSetting() Adds a INNER JOIN clause and with to the query using the Setting relation
 *
 * @method     \GameQuery|\GameGroupQuery|\PlayerGroupQuery|\PresetGroupQuery|\SettingQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildGroup findOne(ConnectionInterface $con = null) Return the first ChildGroup matching the query
 * @method     ChildGroup findOneOrCreate(ConnectionInterface $con = null) Return the first ChildGroup matching the query, or a new ChildGroup object populated from the query conditions when no match is found
 *
 * @method     ChildGroup findOneById(int $id) Return the first ChildGroup filtered by the id column
 * @method     ChildGroup findOneByName(string $name) Return the first ChildGroup filtered by the name column
 * @method     ChildGroup findOneByPermissions(int $permissions) Return the first ChildGroup filtered by the permissions column
 * @method     ChildGroup findOneByRank(int $rank) Return the first ChildGroup filtered by the rank column *

 * @method     ChildGroup requirePk($key, ConnectionInterface $con = null) Return the ChildGroup by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGroup requireOne(ConnectionInterface $con = null) Return the first ChildGroup matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildGroup requireOneById(int $id) Return the first ChildGroup filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGroup requireOneByName(string $name) Return the first ChildGroup filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGroup requireOneByPermissions(int $permissions) Return the first ChildGroup filtered by the permissions column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGroup requireOneByRank(int $rank) Return the first ChildGroup filtered by the rank column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildGroup[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildGroup objects based on current ModelCriteria
 * @method     ChildGroup[]|ObjectCollection findById(int $id) Return ChildGroup objects filtered by the id column
 * @method     ChildGroup[]|ObjectCollection findByName(string $name) Return ChildGroup objects filtered by the name column
 * @method     ChildGroup[]|ObjectCollection findByPermissions(int $permissions) Return ChildGroup objects filtered by the permissions column
 * @method     ChildGroup[]|ObjectCollection findByRank(int $rank) Return ChildGroup objects filtered by the rank column
 * @method     ChildGroup[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class GroupQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\GroupQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'assassins', $modelName = '\\Group', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildGroupQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildGroupQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildGroupQuery) {
            return $criteria;
        }
        $query = new ChildGroupQuery();
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
     * @return ChildGroup|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(GroupTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = GroupTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildGroup A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `name`, `permissions`, `rank` FROM `groups` WHERE `id` = :p0';
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
            /** @var ChildGroup $obj */
            $obj = new ChildGroup();
            $obj->hydrate($row);
            GroupTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildGroup|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildGroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(GroupTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildGroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(GroupTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildGroupQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(GroupTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(GroupTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GroupTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildGroupQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GroupTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the permissions column
     *
     * Example usage:
     * <code>
     * $query->filterByPermissions(1234); // WHERE permissions = 1234
     * $query->filterByPermissions(array(12, 34)); // WHERE permissions IN (12, 34)
     * $query->filterByPermissions(array('min' => 12)); // WHERE permissions > 12
     * </code>
     *
     * @param     mixed $permissions The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGroupQuery The current query, for fluid interface
     */
    public function filterByPermissions($permissions = null, $comparison = null)
    {
        if (is_array($permissions)) {
            $useMinMax = false;
            if (isset($permissions['min'])) {
                $this->addUsingAlias(GroupTableMap::COL_PERMISSIONS, $permissions['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($permissions['max'])) {
                $this->addUsingAlias(GroupTableMap::COL_PERMISSIONS, $permissions['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GroupTableMap::COL_PERMISSIONS, $permissions, $comparison);
    }

    /**
     * Filter the query on the rank column
     *
     * Example usage:
     * <code>
     * $query->filterByRank(1234); // WHERE rank = 1234
     * $query->filterByRank(array(12, 34)); // WHERE rank IN (12, 34)
     * $query->filterByRank(array('min' => 12)); // WHERE rank > 12
     * </code>
     *
     * @param     mixed $rank The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGroupQuery The current query, for fluid interface
     */
    public function filterByRank($rank = null, $comparison = null)
    {
        if (is_array($rank)) {
            $useMinMax = false;
            if (isset($rank['min'])) {
                $this->addUsingAlias(GroupTableMap::COL_RANK, $rank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rank['max'])) {
                $this->addUsingAlias(GroupTableMap::COL_RANK, $rank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GroupTableMap::COL_RANK, $rank, $comparison);
    }

    /**
     * Filter the query by a related \Game object
     *
     * @param \Game|ObjectCollection $game the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupQuery The current query, for fluid interface
     */
    public function filterByAutoJoinedGame($game, $comparison = null)
    {
        if ($game instanceof \Game) {
            return $this
                ->addUsingAlias(GroupTableMap::COL_ID, $game->getAutoJoinGroupId(), $comparison);
        } elseif ($game instanceof ObjectCollection) {
            return $this
                ->useAutoJoinedGameQuery()
                ->filterByPrimaryKeys($game->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAutoJoinedGame() only accepts arguments of type \Game or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AutoJoinedGame relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildGroupQuery The current query, for fluid interface
     */
    public function joinAutoJoinedGame($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AutoJoinedGame');

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
            $this->addJoinObject($join, 'AutoJoinedGame');
        }

        return $this;
    }

    /**
     * Use the AutoJoinedGame relation Game object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \GameQuery A secondary query class using the current class as primary query
     */
    public function useAutoJoinedGameQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAutoJoinedGame($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AutoJoinedGame', '\GameQuery');
    }

    /**
     * Filter the query by a related \GameGroup object
     *
     * @param \GameGroup|ObjectCollection $gameGroup the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupQuery The current query, for fluid interface
     */
    public function filterByGameGroup($gameGroup, $comparison = null)
    {
        if ($gameGroup instanceof \GameGroup) {
            return $this
                ->addUsingAlias(GroupTableMap::COL_ID, $gameGroup->getGroupId(), $comparison);
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
     * @return $this|ChildGroupQuery The current query, for fluid interface
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
     * Filter the query by a related \PlayerGroup object
     *
     * @param \PlayerGroup|ObjectCollection $playerGroup the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupQuery The current query, for fluid interface
     */
    public function filterByPlayerGroup($playerGroup, $comparison = null)
    {
        if ($playerGroup instanceof \PlayerGroup) {
            return $this
                ->addUsingAlias(GroupTableMap::COL_ID, $playerGroup->getGroupId(), $comparison);
        } elseif ($playerGroup instanceof ObjectCollection) {
            return $this
                ->usePlayerGroupQuery()
                ->filterByPrimaryKeys($playerGroup->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlayerGroup() only accepts arguments of type \PlayerGroup or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildGroupQuery The current query, for fluid interface
     */
    public function joinPlayerGroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerGroup');

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
            $this->addJoinObject($join, 'PlayerGroup');
        }

        return $this;
    }

    /**
     * Use the PlayerGroup relation PlayerGroup object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PlayerGroupQuery A secondary query class using the current class as primary query
     */
    public function usePlayerGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerGroup', '\PlayerGroupQuery');
    }

    /**
     * Filter the query by a related \PresetGroup object
     *
     * @param \PresetGroup|ObjectCollection $presetGroup the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupQuery The current query, for fluid interface
     */
    public function filterByPresetGroup($presetGroup, $comparison = null)
    {
        if ($presetGroup instanceof \PresetGroup) {
            return $this
                ->addUsingAlias(GroupTableMap::COL_ID, $presetGroup->getGroupId(), $comparison);
        } elseif ($presetGroup instanceof ObjectCollection) {
            return $this
                ->usePresetGroupQuery()
                ->filterByPrimaryKeys($presetGroup->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPresetGroup() only accepts arguments of type \PresetGroup or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PresetGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildGroupQuery The current query, for fluid interface
     */
    public function joinPresetGroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PresetGroup');

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
            $this->addJoinObject($join, 'PresetGroup');
        }

        return $this;
    }

    /**
     * Use the PresetGroup relation PresetGroup object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PresetGroupQuery A secondary query class using the current class as primary query
     */
    public function usePresetGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPresetGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PresetGroup', '\PresetGroupQuery');
    }

    /**
     * Filter the query by a related \Setting object
     *
     * @param \Setting|ObjectCollection $setting the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupQuery The current query, for fluid interface
     */
    public function filterBySetting($setting, $comparison = null)
    {
        if ($setting instanceof \Setting) {
            return $this
                ->addUsingAlias(GroupTableMap::COL_ID, $setting->getAutoJoinGroupId(), $comparison);
        } elseif ($setting instanceof ObjectCollection) {
            return $this
                ->useSettingQuery()
                ->filterByPrimaryKeys($setting->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySetting() only accepts arguments of type \Setting or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Setting relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildGroupQuery The current query, for fluid interface
     */
    public function joinSetting($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Setting');

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
            $this->addJoinObject($join, 'Setting');
        }

        return $this;
    }

    /**
     * Use the Setting relation Setting object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SettingQuery A secondary query class using the current class as primary query
     */
    public function useSettingQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSetting($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Setting', '\SettingQuery');
    }

    /**
     * Filter the query by a related Game object
     * using the game_groups table as cross reference
     *
     * @param Game $game the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupQuery The current query, for fluid interface
     */
    public function filterByGame($game, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useGameGroupQuery()
            ->filterByGame($game, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Preset object
     * using the preset_groups table as cross reference
     *
     * @param Preset $preset the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupQuery The current query, for fluid interface
     */
    public function filterByPreset($preset, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePresetGroupQuery()
            ->filterByPreset($preset, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildGroup $group Object to remove from the list of results
     *
     * @return $this|ChildGroupQuery The current query, for fluid interface
     */
    public function prune($group = null)
    {
        if ($group) {
            $this->addUsingAlias(GroupTableMap::COL_ID, $group->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the groups table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GroupTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            GroupTableMap::clearInstancePool();
            GroupTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(GroupTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(GroupTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            GroupTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            GroupTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // GroupQuery
