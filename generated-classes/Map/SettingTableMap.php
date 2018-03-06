<?php

namespace Map;

use \Setting;
use \SettingQuery;
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
 * This class defines the structure of the 'settings' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class SettingTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.SettingTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'assassins';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'settings';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Setting';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Setting';

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
     * the column name for the presets_id field
     */
    const COL_PRESETS_ID = 'settings.presets_id';

    /**
     * the column name for the invite field
     */
    const COL_INVITE = 'settings.invite';

    /**
     * the column name for the request_invite field
     */
    const COL_REQUEST_INVITE = 'settings.request_invite';

    /**
     * the column name for the auto_join_group_id field
     */
    const COL_AUTO_JOIN_GROUP_ID = 'settings.auto_join_group_id';

    /**
     * the column name for the auto_place field
     */
    const COL_AUTO_PLACE = 'settings.auto_place';

    /**
     * the column name for the duplicate_game_on_complete field
     */
    const COL_DUPLICATE_GAME_ON_COMPLETE = 'settings.duplicate_game_on_complete';

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
        self::TYPE_PHPNAME       => array('PresetsId', 'Invite', 'RequestInvite', 'AutoJoinGroupId', 'AutoPlace', 'DuplicateGameOnComplete', ),
        self::TYPE_CAMELNAME     => array('presetsId', 'invite', 'requestInvite', 'autoJoinGroupId', 'autoPlace', 'duplicateGameOnComplete', ),
        self::TYPE_COLNAME       => array(SettingTableMap::COL_PRESETS_ID, SettingTableMap::COL_INVITE, SettingTableMap::COL_REQUEST_INVITE, SettingTableMap::COL_AUTO_JOIN_GROUP_ID, SettingTableMap::COL_AUTO_PLACE, SettingTableMap::COL_DUPLICATE_GAME_ON_COMPLETE, ),
        self::TYPE_FIELDNAME     => array('presets_id', 'invite', 'request_invite', 'auto_join_group_id', 'auto_place', 'duplicate_game_on_complete', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('PresetsId' => 0, 'Invite' => 1, 'RequestInvite' => 2, 'AutoJoinGroupId' => 3, 'AutoPlace' => 4, 'DuplicateGameOnComplete' => 5, ),
        self::TYPE_CAMELNAME     => array('presetsId' => 0, 'invite' => 1, 'requestInvite' => 2, 'autoJoinGroupId' => 3, 'autoPlace' => 4, 'duplicateGameOnComplete' => 5, ),
        self::TYPE_COLNAME       => array(SettingTableMap::COL_PRESETS_ID => 0, SettingTableMap::COL_INVITE => 1, SettingTableMap::COL_REQUEST_INVITE => 2, SettingTableMap::COL_AUTO_JOIN_GROUP_ID => 3, SettingTableMap::COL_AUTO_PLACE => 4, SettingTableMap::COL_DUPLICATE_GAME_ON_COMPLETE => 5, ),
        self::TYPE_FIELDNAME     => array('presets_id' => 0, 'invite' => 1, 'request_invite' => 2, 'auto_join_group_id' => 3, 'auto_place' => 4, 'duplicate_game_on_complete' => 5, ),
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
        $this->setName('settings');
        $this->setPhpName('Setting');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\Setting');
        $this->setPackage('');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('presets_id', 'PresetsId', 'INTEGER' , 'presets', 'id', true, null, null);
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
        $this->addRelation('AutoJoinGroup', '\\Group', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':auto_join_group_id',
    1 => ':id',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('Preset', '\\Preset', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':presets_id',
    1 => ':id',
  ),
), 'CASCADE', null, null, false);
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('PresetsId', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('PresetsId', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('PresetsId', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('PresetsId', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('PresetsId', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('PresetsId', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('PresetsId', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? SettingTableMap::CLASS_DEFAULT : SettingTableMap::OM_CLASS;
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
     * @return array           (Setting object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = SettingTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SettingTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SettingTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SettingTableMap::OM_CLASS;
            /** @var Setting $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SettingTableMap::addInstanceToPool($obj, $key);
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
            $key = SettingTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SettingTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Setting $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SettingTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(SettingTableMap::COL_PRESETS_ID);
            $criteria->addSelectColumn(SettingTableMap::COL_INVITE);
            $criteria->addSelectColumn(SettingTableMap::COL_REQUEST_INVITE);
            $criteria->addSelectColumn(SettingTableMap::COL_AUTO_JOIN_GROUP_ID);
            $criteria->addSelectColumn(SettingTableMap::COL_AUTO_PLACE);
            $criteria->addSelectColumn(SettingTableMap::COL_DUPLICATE_GAME_ON_COMPLETE);
        } else {
            $criteria->addSelectColumn($alias . '.presets_id');
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
        return Propel::getServiceContainer()->getDatabaseMap(SettingTableMap::DATABASE_NAME)->getTable(SettingTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(SettingTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(SettingTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new SettingTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Setting or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Setting object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(SettingTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Setting) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SettingTableMap::DATABASE_NAME);
            $criteria->add(SettingTableMap::COL_PRESETS_ID, (array) $values, Criteria::IN);
        }

        $query = SettingQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SettingTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SettingTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the settings table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return SettingQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Setting or Criteria object.
     *
     * @param mixed               $criteria Criteria or Setting object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SettingTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Setting object
        }


        // Set the correct dbName
        $query = SettingQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // SettingTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
SettingTableMap::buildTableMap();
