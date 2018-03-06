<?php

namespace Map;

use \CircuitPlayer;
use \CircuitPlayerQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'circuit_players' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class CircuitPlayerTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.CircuitPlayerTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'assassins';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'circuit_players';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\CircuitPlayer';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'CircuitPlayer';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 9;

    /**
     * the column name for the id field
     */
    const COL_ID = 'circuit_players.id';

    /**
     * the column name for the active field
     */
    const COL_ACTIVE = 'circuit_players.active';

    /**
     * the column name for the game_id field
     */
    const COL_GAME_ID = 'circuit_players.game_id';

    /**
     * the column name for the player_id field
     */
    const COL_PLAYER_ID = 'circuit_players.player_id';

    /**
     * the column name for the target_id field
     */
    const COL_TARGET_ID = 'circuit_players.target_id';

    /**
     * the column name for the pay field
     */
    const COL_PAY = 'circuit_players.pay';

    /**
     * the column name for the money_spent field
     */
    const COL_MONEY_SPENT = 'circuit_players.money_spent';

    /**
     * the column name for the date_assigned field
     */
    const COL_DATE_ASSIGNED = 'circuit_players.date_assigned';

    /**
     * the column name for the date_completed field
     */
    const COL_DATE_COMPLETED = 'circuit_players.date_completed';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Active', 'GameId', 'PlayerId', 'TargetId', 'Pay', 'MoneySpent', 'DateAssigned', 'DateCompleted', ),
        self::TYPE_CAMELNAME     => array('id', 'active', 'gameId', 'playerId', 'targetId', 'pay', 'moneySpent', 'dateAssigned', 'dateCompleted', ),
        self::TYPE_COLNAME       => array(CircuitPlayerTableMap::COL_ID, CircuitPlayerTableMap::COL_ACTIVE, CircuitPlayerTableMap::COL_GAME_ID, CircuitPlayerTableMap::COL_PLAYER_ID, CircuitPlayerTableMap::COL_TARGET_ID, CircuitPlayerTableMap::COL_PAY, CircuitPlayerTableMap::COL_MONEY_SPENT, CircuitPlayerTableMap::COL_DATE_ASSIGNED, CircuitPlayerTableMap::COL_DATE_COMPLETED, ),
        self::TYPE_FIELDNAME     => array('id', 'active', 'game_id', 'player_id', 'target_id', 'pay', 'money_spent', 'date_assigned', 'date_completed', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Active' => 1, 'GameId' => 2, 'PlayerId' => 3, 'TargetId' => 4, 'Pay' => 5, 'MoneySpent' => 6, 'DateAssigned' => 7, 'DateCompleted' => 8, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'active' => 1, 'gameId' => 2, 'playerId' => 3, 'targetId' => 4, 'pay' => 5, 'moneySpent' => 6, 'dateAssigned' => 7, 'dateCompleted' => 8, ),
        self::TYPE_COLNAME       => array(CircuitPlayerTableMap::COL_ID => 0, CircuitPlayerTableMap::COL_ACTIVE => 1, CircuitPlayerTableMap::COL_GAME_ID => 2, CircuitPlayerTableMap::COL_PLAYER_ID => 3, CircuitPlayerTableMap::COL_TARGET_ID => 4, CircuitPlayerTableMap::COL_PAY => 5, CircuitPlayerTableMap::COL_MONEY_SPENT => 6, CircuitPlayerTableMap::COL_DATE_ASSIGNED => 7, CircuitPlayerTableMap::COL_DATE_COMPLETED => 8, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'active' => 1, 'game_id' => 2, 'player_id' => 3, 'target_id' => 4, 'pay' => 5, 'money_spent' => 6, 'date_assigned' => 7, 'date_completed' => 8, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('circuit_players');
        $this->setPhpName('CircuitPlayer');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\CircuitPlayer');
        $this->setPackage('');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('active', 'Active', 'BOOLEAN', false, 1, true);
        $this->addForeignKey('game_id', 'GameId', 'INTEGER', 'games', 'id', false, null, null);
        $this->addForeignKey('player_id', 'PlayerId', 'INTEGER', 'users', 'id', true, null, null);
        $this->addForeignKey('target_id', 'TargetId', 'INTEGER', 'users', 'id', true, null, null);
        $this->addColumn('pay', 'Pay', 'INTEGER', false, null, 0);
        $this->addColumn('money_spent', 'MoneySpent', 'INTEGER', false, null, 0);
        $this->addColumn('date_assigned', 'DateAssigned', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('date_completed', 'DateCompleted', 'TIMESTAMP', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Game', '\\Game', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':game_id',
    1 => ':id',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('Player', '\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':player_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Target', '\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':target_id',
    1 => ':id',
  ),
), null, null, null, false);
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }
    
    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? CircuitPlayerTableMap::CLASS_DEFAULT : CircuitPlayerTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (CircuitPlayer object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = CircuitPlayerTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CircuitPlayerTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CircuitPlayerTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CircuitPlayerTableMap::OM_CLASS;
            /** @var CircuitPlayer $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CircuitPlayerTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();
    
        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = CircuitPlayerTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CircuitPlayerTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var CircuitPlayer $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CircuitPlayerTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(CircuitPlayerTableMap::COL_ID);
            $criteria->addSelectColumn(CircuitPlayerTableMap::COL_ACTIVE);
            $criteria->addSelectColumn(CircuitPlayerTableMap::COL_GAME_ID);
            $criteria->addSelectColumn(CircuitPlayerTableMap::COL_PLAYER_ID);
            $criteria->addSelectColumn(CircuitPlayerTableMap::COL_TARGET_ID);
            $criteria->addSelectColumn(CircuitPlayerTableMap::COL_PAY);
            $criteria->addSelectColumn(CircuitPlayerTableMap::COL_MONEY_SPENT);
            $criteria->addSelectColumn(CircuitPlayerTableMap::COL_DATE_ASSIGNED);
            $criteria->addSelectColumn(CircuitPlayerTableMap::COL_DATE_COMPLETED);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.active');
            $criteria->addSelectColumn($alias . '.game_id');
            $criteria->addSelectColumn($alias . '.player_id');
            $criteria->addSelectColumn($alias . '.target_id');
            $criteria->addSelectColumn($alias . '.pay');
            $criteria->addSelectColumn($alias . '.money_spent');
            $criteria->addSelectColumn($alias . '.date_assigned');
            $criteria->addSelectColumn($alias . '.date_completed');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(CircuitPlayerTableMap::DATABASE_NAME)->getTable(CircuitPlayerTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(CircuitPlayerTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(CircuitPlayerTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new CircuitPlayerTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a CircuitPlayer or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or CircuitPlayer object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CircuitPlayerTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \CircuitPlayer) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CircuitPlayerTableMap::DATABASE_NAME);
            $criteria->add(CircuitPlayerTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = CircuitPlayerQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            CircuitPlayerTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                CircuitPlayerTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the circuit_players table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return CircuitPlayerQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a CircuitPlayer or Criteria object.
     *
     * @param mixed               $criteria Criteria or CircuitPlayer object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CircuitPlayerTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from CircuitPlayer object
        }


        // Set the correct dbName
        $query = CircuitPlayerQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // CircuitPlayerTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
CircuitPlayerTableMap::buildTableMap();
