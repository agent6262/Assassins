<?php

namespace Base;

use \Preset as ChildPreset;
use \PresetQuery as ChildPresetQuery;
use \Exception;
use \PDO;
use Map\PresetTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'presets' table.
 *
 * 
 *
 * @method     ChildPresetQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPresetQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildPresetQuery orderByRules($order = Criteria::ASC) Order by the rules column
 *
 * @method     ChildPresetQuery groupById() Group by the id column
 * @method     ChildPresetQuery groupByName() Group by the name column
 * @method     ChildPresetQuery groupByRules() Group by the rules column
 *
 * @method     ChildPresetQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPresetQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPresetQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPresetQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPresetQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPresetQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPresetQuery leftJoinUserPreset($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserPreset relation
 * @method     ChildPresetQuery rightJoinUserPreset($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserPreset relation
 * @method     ChildPresetQuery innerJoinUserPreset($relationAlias = null) Adds a INNER JOIN clause to the query using the UserPreset relation
 *
 * @method     ChildPresetQuery joinWithUserPreset($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserPreset relation
 *
 * @method     ChildPresetQuery leftJoinWithUserPreset() Adds a LEFT JOIN clause and with to the query using the UserPreset relation
 * @method     ChildPresetQuery rightJoinWithUserPreset() Adds a RIGHT JOIN clause and with to the query using the UserPreset relation
 * @method     ChildPresetQuery innerJoinWithUserPreset() Adds a INNER JOIN clause and with to the query using the UserPreset relation
 *
 * @method     ChildPresetQuery leftJoinPresetGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the PresetGroup relation
 * @method     ChildPresetQuery rightJoinPresetGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PresetGroup relation
 * @method     ChildPresetQuery innerJoinPresetGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the PresetGroup relation
 *
 * @method     ChildPresetQuery joinWithPresetGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PresetGroup relation
 *
 * @method     ChildPresetQuery leftJoinWithPresetGroup() Adds a LEFT JOIN clause and with to the query using the PresetGroup relation
 * @method     ChildPresetQuery rightJoinWithPresetGroup() Adds a RIGHT JOIN clause and with to the query using the PresetGroup relation
 * @method     ChildPresetQuery innerJoinWithPresetGroup() Adds a INNER JOIN clause and with to the query using the PresetGroup relation
 *
 * @method     ChildPresetQuery leftJoinSetting($relationAlias = null) Adds a LEFT JOIN clause to the query using the Setting relation
 * @method     ChildPresetQuery rightJoinSetting($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Setting relation
 * @method     ChildPresetQuery innerJoinSetting($relationAlias = null) Adds a INNER JOIN clause to the query using the Setting relation
 *
 * @method     ChildPresetQuery joinWithSetting($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Setting relation
 *
 * @method     ChildPresetQuery leftJoinWithSetting() Adds a LEFT JOIN clause and with to the query using the Setting relation
 * @method     ChildPresetQuery rightJoinWithSetting() Adds a RIGHT JOIN clause and with to the query using the Setting relation
 * @method     ChildPresetQuery innerJoinWithSetting() Adds a INNER JOIN clause and with to the query using the Setting relation
 *
 * @method     \UserPresetQuery|\PresetGroupQuery|\SettingQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPreset findOne(ConnectionInterface $con = null) Return the first ChildPreset matching the query
 * @method     ChildPreset findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPreset matching the query, or a new ChildPreset object populated from the query conditions when no match is found
 *
 * @method     ChildPreset findOneById(int $id) Return the first ChildPreset filtered by the id column
 * @method     ChildPreset findOneByName(string $name) Return the first ChildPreset filtered by the name column
 * @method     ChildPreset findOneByRules(string $rules) Return the first ChildPreset filtered by the rules column *

 * @method     ChildPreset requirePk($key, ConnectionInterface $con = null) Return the ChildPreset by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPreset requireOne(ConnectionInterface $con = null) Return the first ChildPreset matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPreset requireOneById(int $id) Return the first ChildPreset filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPreset requireOneByName(string $name) Return the first ChildPreset filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPreset requireOneByRules(string $rules) Return the first ChildPreset filtered by the rules column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPreset[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPreset objects based on current ModelCriteria
 * @method     ChildPreset[]|ObjectCollection findById(int $id) Return ChildPreset objects filtered by the id column
 * @method     ChildPreset[]|ObjectCollection findByName(string $name) Return ChildPreset objects filtered by the name column
 * @method     ChildPreset[]|ObjectCollection findByRules(string $rules) Return ChildPreset objects filtered by the rules column
 * @method     ChildPreset[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PresetQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\PresetQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'assassins', $modelName = '\\Preset', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPresetQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPresetQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPresetQuery) {
            return $criteria;
        }
        $query = new ChildPresetQuery();
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
     * @return ChildPreset|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PresetTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PresetTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPreset A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `name`, `rules` FROM `presets` WHERE `id` = :p0';
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
            /** @var ChildPreset $obj */
            $obj = new ChildPreset();
            $obj->hydrate($row);
            PresetTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPreset|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPresetQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PresetTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPresetQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PresetTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildPresetQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PresetTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PresetTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PresetTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildPresetQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PresetTableMap::COL_NAME, $name, $comparison);
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
     * @return $this|ChildPresetQuery The current query, for fluid interface
     */
    public function filterByRules($rules = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($rules)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PresetTableMap::COL_RULES, $rules, $comparison);
    }

    /**
     * Filter the query by a related \UserPreset object
     *
     * @param \UserPreset|ObjectCollection $userPreset the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPresetQuery The current query, for fluid interface
     */
    public function filterByUserPreset($userPreset, $comparison = null)
    {
        if ($userPreset instanceof \UserPreset) {
            return $this
                ->addUsingAlias(PresetTableMap::COL_ID, $userPreset->getPresetId(), $comparison);
        } elseif ($userPreset instanceof ObjectCollection) {
            return $this
                ->useUserPresetQuery()
                ->filterByPrimaryKeys($userPreset->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserPreset() only accepts arguments of type \UserPreset or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserPreset relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPresetQuery The current query, for fluid interface
     */
    public function joinUserPreset($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserPreset');

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
            $this->addJoinObject($join, 'UserPreset');
        }

        return $this;
    }

    /**
     * Use the UserPreset relation UserPreset object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserPresetQuery A secondary query class using the current class as primary query
     */
    public function useUserPresetQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserPreset($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserPreset', '\UserPresetQuery');
    }

    /**
     * Filter the query by a related \PresetGroup object
     *
     * @param \PresetGroup|ObjectCollection $presetGroup the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPresetQuery The current query, for fluid interface
     */
    public function filterByPresetGroup($presetGroup, $comparison = null)
    {
        if ($presetGroup instanceof \PresetGroup) {
            return $this
                ->addUsingAlias(PresetTableMap::COL_ID, $presetGroup->getPresetId(), $comparison);
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
     * @return $this|ChildPresetQuery The current query, for fluid interface
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
     * @return ChildPresetQuery The current query, for fluid interface
     */
    public function filterBySetting($setting, $comparison = null)
    {
        if ($setting instanceof \Setting) {
            return $this
                ->addUsingAlias(PresetTableMap::COL_ID, $setting->getPresetsId(), $comparison);
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
     * @return $this|ChildPresetQuery The current query, for fluid interface
     */
    public function joinSetting($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useSettingQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSetting($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Setting', '\SettingQuery');
    }

    /**
     * Filter the query by a related User object
     * using the user_presets table as cross reference
     *
     * @param User $user the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPresetQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserPresetQuery()
            ->filterByUser($user, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Group object
     * using the preset_groups table as cross reference
     *
     * @param Group $group the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPresetQuery The current query, for fluid interface
     */
    public function filterByGroup($group, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePresetGroupQuery()
            ->filterByGroup($group, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPreset $preset Object to remove from the list of results
     *
     * @return $this|ChildPresetQuery The current query, for fluid interface
     */
    public function prune($preset = null)
    {
        if ($preset) {
            $this->addUsingAlias(PresetTableMap::COL_ID, $preset->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the presets table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PresetTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PresetTableMap::clearInstancePool();
            PresetTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PresetTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PresetTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            PresetTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            PresetTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PresetQuery
