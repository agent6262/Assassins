<?php

namespace Map;

use \LtsGame;
use \LtsGameQuery;
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
 * This class defines the structure of the 'lts_games' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class LtsGameTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.LtsGameTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'assassins';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'lts_games';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\LtsGame';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'LtsGame';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 6;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 6;

    /**
     * the column name for the id field
     */
    const COL_ID = 'lts_games.id';

    /**
     * the column name for the owner_id field
     */
    const COL_OWNER_ID = 'lts_games.owner_id';

    /**
     * the column name for the rules field
     */
    const COL_RULES = 'lts_games.rules';

    /**
     * the column name for the invite field
     */
    const COL_INVITE = 'lts_games.invite';

    /**
     * the column name for the request_invite field
     */
    const COL_REQUEST_INVITE = 'lts_games.request_invite';

    /**
     * the column name for the auto_place field
     */
    const COL_AUTO_PLACE = 'lts_games.auto_place';

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
        self::TYPE_PHPNAME       => array('Id', 'OwnerId', 'Rules', 'Invite', 'RequestInvite', 'AutoPlace', ),
        self::TYPE_CAMELNAME     => array('id', 'ownerId', 'rules', 'invite', 'requestInvite', 'autoPlace', ),
        self::TYPE_COLNAME       => array(LtsGameTableMap::COL_ID, LtsGameTableMap::COL_OWNER_ID, LtsGameTableMap::COL_RULES, LtsGameTableMap::COL_INVITE, LtsGameTableMap::COL_REQUEST_INVITE, LtsGameTableMap::COL_AUTO_PLACE, ),
        self::TYPE_FIELDNAME     => array('id', 'owner_id', 'rules', 'invite', 'request_invite', 'auto_place', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'OwnerId' => 1, 'Rules' => 2, 'Invite' => 3, 'RequestInvite' => 4, 'AutoPlace' => 5, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'ownerId' => 1, 'rules' => 2, 'invite' => 3, 'requestInvite' => 4, 'autoPlace' => 5, ),
        self::TYPE_COLNAME       => array(LtsGameTableMap::COL_ID => 0, LtsGameTableMap::COL_OWNER_ID => 1, LtsGameTableMap::COL_RULES => 2, LtsGameTableMap::COL_INVITE => 3, LtsGameTableMap::COL_REQUEST_INVITE => 4, LtsGameTableMap::COL_AUTO_PLACE => 5, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'owner_id' => 1, 'rules' => 2, 'invite' => 3, 'request_invite' => 4, 'auto_place' => 5, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
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
        $this->setName('lts_games');
        $this->setPhpName('LtsGame');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\LtsGame');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('owner_id', 'OwnerId', 'INTEGER', 'users', 'id', true, null, null);
        $this->addColumn('rules', 'Rules', 'LONGVARCHAR', false, null, null);
        $this->addColumn('invite', 'Invite', 'BOOLEAN', false, 1, true);
        $this->addColumn('request_invite', 'RequestInvite', 'BOOLEAN', false, 1, true);
        $this->addColumn('auto_place', 'AutoPlace', 'BOOLEAN', false, 1, false);
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
        $this->addRelation('LtsCircuitPlayer', '\\LtsCircuitPlayer', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':game_id',
    1 => ':id',
  ),
), null, null, 'LtsCircuitPlayers', false);
        $this->addRelation('LtsGameActionLog', '\\LtsGameActionLog', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':game_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'LtsGameActionLogs', false);
        $this->addRelation('LtsActionLog', '\\LtsActionLog', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'LtsActionLogs');
    } // buildRelations()
    /**
     * Method to invalidate the instance pool of all tables related to lts_games     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        LtsGameActionLogTableMap::clearInstancePool();
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
        return $withPrefix ? LtsGameTableMap::CLASS_DEFAULT : LtsGameTableMap::OM_CLASS;
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
     * @return array           (LtsGame object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = LtsGameTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = LtsGameTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + LtsGameTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = LtsGameTableMap::OM_CLASS;
            /** @var LtsGame $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            LtsGameTableMap::addInstanceToPool($obj, $key);
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
            $key = LtsGameTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = LtsGameTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var LtsGame $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                LtsGameTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(LtsGameTableMap::COL_ID);
            $criteria->addSelectColumn(LtsGameTableMap::COL_OWNER_ID);
            $criteria->addSelectColumn(LtsGameTableMap::COL_RULES);
            $criteria->addSelectColumn(LtsGameTableMap::COL_INVITE);
            $criteria->addSelectColumn(LtsGameTableMap::COL_REQUEST_INVITE);
            $criteria->addSelectColumn(LtsGameTableMap::COL_AUTO_PLACE);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.owner_id');
            $criteria->addSelectColumn($alias . '.rules');
            $criteria->addSelectColumn($alias . '.invite');
            $criteria->addSelectColumn($alias . '.request_invite');
            $criteria->addSelectColumn($alias . '.auto_place');
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
        return Propel::getServiceContainer()->getDatabaseMap(LtsGameTableMap::DATABASE_NAME)->getTable(LtsGameTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(LtsGameTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(LtsGameTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new LtsGameTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a LtsGame or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or LtsGame object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(LtsGameTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \LtsGame) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(LtsGameTableMap::DATABASE_NAME);
            $criteria->add(LtsGameTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = LtsGameQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            LtsGameTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                LtsGameTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the lts_games table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return LtsGameQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a LtsGame or Criteria object.
     *
     * @param mixed               $criteria Criteria or LtsGame object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LtsGameTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from LtsGame object
        }

        if ($criteria->containsKey(LtsGameTableMap::COL_ID) && $criteria->keyContainsValue(LtsGameTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.LtsGameTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = LtsGameQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // LtsGameTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
LtsGameTableMap::buildTableMap();
