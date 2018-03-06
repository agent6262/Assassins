<?php

namespace Map;

use \Game;
use \GameQuery;
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
 * This class defines the structure of the 'games' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class GameTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.GameTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'assassins';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'games';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Game';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Game';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 12;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 12;

    /**
     * the column name for the id field
     */
    const COL_ID = 'games.id';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'games.name';

    /**
     * the column name for the active field
     */
    const COL_ACTIVE = 'games.active';

    /**
     * the column name for the owner_id field
     */
    const COL_OWNER_ID = 'games.owner_id';

    /**
     * the column name for the started field
     */
    const COL_STARTED = 'games.started';

    /**
     * the column name for the paused field
     */
    const COL_PAUSED = 'games.paused';

    /**
     * the column name for the rules field
     */
    const COL_RULES = 'games.rules';

    /**
     * the column name for the invite field
     */
    const COL_INVITE = 'games.invite';

    /**
     * the column name for the request_invite field
     */
    const COL_REQUEST_INVITE = 'games.request_invite';

    /**
     * the column name for the auto_join_group_id field
     */
    const COL_AUTO_JOIN_GROUP_ID = 'games.auto_join_group_id';

    /**
     * the column name for the auto_place field
     */
    const COL_AUTO_PLACE = 'games.auto_place';

    /**
     * the column name for the duplicate_game_on_complete field
     */
    const COL_DUPLICATE_GAME_ON_COMPLETE = 'games.duplicate_game_on_complete';

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
        self::TYPE_PHPNAME       => array('Id', 'Name', 'Active', 'OwnerId', 'Started', 'Paused', 'Rules', 'Invite', 'RequestInvite', 'AutoJoinGroupId', 'AutoPlace', 'DuplicateGameOnComplete', ),
        self::TYPE_CAMELNAME     => array('id', 'name', 'active', 'ownerId', 'started', 'paused', 'rules', 'invite', 'requestInvite', 'autoJoinGroupId', 'autoPlace', 'duplicateGameOnComplete', ),
        self::TYPE_COLNAME       => array(GameTableMap::COL_ID, GameTableMap::COL_NAME, GameTableMap::COL_ACTIVE, GameTableMap::COL_OWNER_ID, GameTableMap::COL_STARTED, GameTableMap::COL_PAUSED, GameTableMap::COL_RULES, GameTableMap::COL_INVITE, GameTableMap::COL_REQUEST_INVITE, GameTableMap::COL_AUTO_JOIN_GROUP_ID, GameTableMap::COL_AUTO_PLACE, GameTableMap::COL_DUPLICATE_GAME_ON_COMPLETE, ),
        self::TYPE_FIELDNAME     => array('id', 'name', 'active', 'owner_id', 'started', 'paused', 'rules', 'invite', 'request_invite', 'auto_join_group_id', 'auto_place', 'duplicate_game_on_complete', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Name' => 1, 'Active' => 2, 'OwnerId' => 3, 'Started' => 4, 'Paused' => 5, 'Rules' => 6, 'Invite' => 7, 'RequestInvite' => 8, 'AutoJoinGroupId' => 9, 'AutoPlace' => 10, 'DuplicateGameOnComplete' => 11, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'name' => 1, 'active' => 2, 'ownerId' => 3, 'started' => 4, 'paused' => 5, 'rules' => 6, 'invite' => 7, 'requestInvite' => 8, 'autoJoinGroupId' => 9, 'autoPlace' => 10, 'duplicateGameOnComplete' => 11, ),
        self::TYPE_COLNAME       => array(GameTableMap::COL_ID => 0, GameTableMap::COL_NAME => 1, GameTableMap::COL_ACTIVE => 2, GameTableMap::COL_OWNER_ID => 3, GameTableMap::COL_STARTED => 4, GameTableMap::COL_PAUSED => 5, GameTableMap::COL_RULES => 6, GameTableMap::COL_INVITE => 7, GameTableMap::COL_REQUEST_INVITE => 8, GameTableMap::COL_AUTO_JOIN_GROUP_ID => 9, GameTableMap::COL_AUTO_PLACE => 10, GameTableMap::COL_DUPLICATE_GAME_ON_COMPLETE => 11, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'name' => 1, 'active' => 2, 'owner_id' => 3, 'started' => 4, 'paused' => 5, 'rules' => 6, 'invite' => 7, 'request_invite' => 8, 'auto_join_group_id' => 9, 'auto_place' => 10, 'duplicate_game_on_complete' => 11, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
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
        $this->setName('games');
        $this->setPhpName('Game');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\Game');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 32, null);
        $this->addColumn('active', 'Active', 'BOOLEAN', false, 1, true);
        $this->addForeignKey('owner_id', 'OwnerId', 'INTEGER', 'users', 'id', true, null, null);
        $this->addColumn('started', 'Started', 'BOOLEAN', false, 1, false);
        $this->addColumn('paused', 'Paused', 'BOOLEAN', false, 1, false);
        $this->addColumn('rules', 'Rules', 'LONGVARCHAR', false, null, null);
        $this->addColumn('invite', 'Invite', 'BOOLEAN', false, 1, true);
        $this->addColumn('request_invite', 'RequestInvite', 'BOOLEAN', false, 1, true);
        $this->addForeignKey('auto_join_group_id', 'AutoJoinGroupId', 'INTEGER', 'groups', 'id', false, null, null);
        $this->addColumn('auto_place', 'AutoPlace', 'BOOLEAN', false, 1, false);
        $this->addColumn('duplicate_game_on_complete', 'DuplicateGameOnComplete', 'BOOLEAN', false, 1, true);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Owner', '\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':owner_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('AutoJoinGroup', '\\Group', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':auto_join_group_id',
    1 => ':id',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('UserGame', '\\UserGame', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':game_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'UserGames', false);
        $this->addRelation('GameGroup', '\\GameGroup', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':game_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'GameGroups', false);
        $this->addRelation('CircuitPlayer', '\\CircuitPlayer', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':game_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'CircuitPlayers', false);
        $this->addRelation('GamePlayerGroup', '\\GamePlayerGroup', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':game_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'GamePlayerGroups', false);
        $this->addRelation('GameActionLog', '\\GameActionLog', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':game_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'GameActionLogs', false);
        $this->addRelation('User', '\\User', RelationMap::MANY_TO_MANY, array(), null, null, 'Users');
        $this->addRelation('Group', '\\Group', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'Groups');
        $this->addRelation('PlayerGroup', '\\PlayerGroup', RelationMap::MANY_TO_MANY, array(), null, null, 'PlayerGroups');
        $this->addRelation('ActionLog', '\\ActionLog', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'ActionLogs');
    } // buildRelations()
    /**
     * Method to invalidate the instance pool of all tables related to games     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        UserGameTableMap::clearInstancePool();
        GameGroupTableMap::clearInstancePool();
        CircuitPlayerTableMap::clearInstancePool();
        GamePlayerGroupTableMap::clearInstancePool();
        GameActionLogTableMap::clearInstancePool();
    }

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
        return $withPrefix ? GameTableMap::CLASS_DEFAULT : GameTableMap::OM_CLASS;
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
     * @return array           (Game object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = GameTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = GameTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + GameTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = GameTableMap::OM_CLASS;
            /** @var Game $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            GameTableMap::addInstanceToPool($obj, $key);
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
            $key = GameTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = GameTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Game $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                GameTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(GameTableMap::COL_ID);
            $criteria->addSelectColumn(GameTableMap::COL_NAME);
            $criteria->addSelectColumn(GameTableMap::COL_ACTIVE);
            $criteria->addSelectColumn(GameTableMap::COL_OWNER_ID);
            $criteria->addSelectColumn(GameTableMap::COL_STARTED);
            $criteria->addSelectColumn(GameTableMap::COL_PAUSED);
            $criteria->addSelectColumn(GameTableMap::COL_RULES);
            $criteria->addSelectColumn(GameTableMap::COL_INVITE);
            $criteria->addSelectColumn(GameTableMap::COL_REQUEST_INVITE);
            $criteria->addSelectColumn(GameTableMap::COL_AUTO_JOIN_GROUP_ID);
            $criteria->addSelectColumn(GameTableMap::COL_AUTO_PLACE);
            $criteria->addSelectColumn(GameTableMap::COL_DUPLICATE_GAME_ON_COMPLETE);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.active');
            $criteria->addSelectColumn($alias . '.owner_id');
            $criteria->addSelectColumn($alias . '.started');
            $criteria->addSelectColumn($alias . '.paused');
            $criteria->addSelectColumn($alias . '.rules');
            $criteria->addSelectColumn($alias . '.invite');
            $criteria->addSelectColumn($alias . '.request_invite');
            $criteria->addSelectColumn($alias . '.auto_join_group_id');
            $criteria->addSelectColumn($alias . '.auto_place');
            $criteria->addSelectColumn($alias . '.duplicate_game_on_complete');
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
        return Propel::getServiceContainer()->getDatabaseMap(GameTableMap::DATABASE_NAME)->getTable(GameTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(GameTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(GameTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new GameTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Game or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Game object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(GameTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Game) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(GameTableMap::DATABASE_NAME);
            $criteria->add(GameTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = GameQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            GameTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                GameTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the games table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return GameQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Game or Criteria object.
     *
     * @param mixed               $criteria Criteria or Game object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GameTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Game object
        }

        if ($criteria->containsKey(GameTableMap::COL_ID) && $criteria->keyContainsValue(GameTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.GameTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = GameQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // GameTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
GameTableMap::buildTableMap();
