<?php

namespace Map;

use \User;
use \UserQuery;
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
 * This class defines the structure of the 'users' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class UserTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.UserTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'assassins';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'users';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\User';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'User';

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
    const COL_ID = 'users.id';

    /**
     * the column name for the email field
     */
    const COL_EMAIL = 'users.email';

    /**
     * the column name for the username field
     */
    const COL_USERNAME = 'users.username';

    /**
     * the column name for the real_name field
     */
    const COL_REAL_NAME = 'users.real_name';

    /**
     * the column name for the password field
     */
    const COL_PASSWORD = 'users.password';

    /**
     * the column name for the money field
     */
    const COL_MONEY = 'users.money';

    /**
     * the column name for the total_money field
     */
    const COL_TOTAL_MONEY = 'users.total_money';

    /**
     * the column name for the verification_token field
     */
    const COL_VERIFICATION_TOKEN = 'users.verification_token';

    /**
     * the column name for the cookie_token field
     */
    const COL_COOKIE_TOKEN = 'users.cookie_token';

    /**
     * the column name for the active field
     */
    const COL_ACTIVE = 'users.active';

    /**
     * the column name for the date_created field
     */
    const COL_DATE_CREATED = 'users.date_created';

    /**
     * the column name for the verification_time field
     */
    const COL_VERIFICATION_TIME = 'users.verification_time';

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
        self::TYPE_PHPNAME       => array('Id', 'Email', 'Username', 'RealName', 'Password', 'Money', 'TotalMoney', 'VerificationToken', 'CookieToken', 'Active', 'DateCreated', 'VerificationTime', ),
        self::TYPE_CAMELNAME     => array('id', 'email', 'username', 'realName', 'password', 'money', 'totalMoney', 'verificationToken', 'cookieToken', 'active', 'dateCreated', 'verificationTime', ),
        self::TYPE_COLNAME       => array(UserTableMap::COL_ID, UserTableMap::COL_EMAIL, UserTableMap::COL_USERNAME, UserTableMap::COL_REAL_NAME, UserTableMap::COL_PASSWORD, UserTableMap::COL_MONEY, UserTableMap::COL_TOTAL_MONEY, UserTableMap::COL_VERIFICATION_TOKEN, UserTableMap::COL_COOKIE_TOKEN, UserTableMap::COL_ACTIVE, UserTableMap::COL_DATE_CREATED, UserTableMap::COL_VERIFICATION_TIME, ),
        self::TYPE_FIELDNAME     => array('id', 'email', 'username', 'real_name', 'password', 'money', 'total_money', 'verification_token', 'cookie_token', 'active', 'date_created', 'verification_time', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Email' => 1, 'Username' => 2, 'RealName' => 3, 'Password' => 4, 'Money' => 5, 'TotalMoney' => 6, 'VerificationToken' => 7, 'CookieToken' => 8, 'Active' => 9, 'DateCreated' => 10, 'VerificationTime' => 11, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'email' => 1, 'username' => 2, 'realName' => 3, 'password' => 4, 'money' => 5, 'totalMoney' => 6, 'verificationToken' => 7, 'cookieToken' => 8, 'active' => 9, 'dateCreated' => 10, 'verificationTime' => 11, ),
        self::TYPE_COLNAME       => array(UserTableMap::COL_ID => 0, UserTableMap::COL_EMAIL => 1, UserTableMap::COL_USERNAME => 2, UserTableMap::COL_REAL_NAME => 3, UserTableMap::COL_PASSWORD => 4, UserTableMap::COL_MONEY => 5, UserTableMap::COL_TOTAL_MONEY => 6, UserTableMap::COL_VERIFICATION_TOKEN => 7, UserTableMap::COL_COOKIE_TOKEN => 8, UserTableMap::COL_ACTIVE => 9, UserTableMap::COL_DATE_CREATED => 10, UserTableMap::COL_VERIFICATION_TIME => 11, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'email' => 1, 'username' => 2, 'real_name' => 3, 'password' => 4, 'money' => 5, 'total_money' => 6, 'verification_token' => 7, 'cookie_token' => 8, 'active' => 9, 'date_created' => 10, 'verification_time' => 11, ),
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
        $this->setName('users');
        $this->setPhpName('User');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\User');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('email', 'Email', 'VARCHAR', true, 275, null);
        $this->addColumn('username', 'Username', 'VARCHAR', true, 25, null);
        $this->addColumn('real_name', 'RealName', 'VARCHAR', true, 255, null);
        $this->addColumn('password', 'Password', 'VARCHAR', true, 60, null);
        $this->addColumn('money', 'Money', 'INTEGER', false, null, 0);
        $this->addColumn('total_money', 'TotalMoney', 'INTEGER', false, null, 0);
        $this->addColumn('verification_token', 'VerificationToken', 'VARCHAR', true, 32, null);
        $this->addColumn('cookie_token', 'CookieToken', 'VARCHAR', false, 64, null);
        $this->addColumn('active', 'Active', 'BOOLEAN', false, 1, false);
        $this->addColumn('date_created', 'DateCreated', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('verification_time', 'VerificationTime', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Preference', '\\Preference', RelationMap::ONE_TO_ONE, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('UserGame', '\\UserGame', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'UserGames', false);
        $this->addRelation('UserPreset', '\\UserPreset', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'UserPresets', false);
        $this->addRelation('OwnedGame', '\\Game', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':owner_id',
    1 => ':id',
  ),
), null, null, 'OwnedGames', false);
        $this->addRelation('CircuitPlayerRelatedByPlayerId', '\\CircuitPlayer', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':player_id',
    1 => ':id',
  ),
), null, null, 'CircuitPlayersRelatedByPlayerId', false);
        $this->addRelation('CircuitPlayerRelatedByTargetId', '\\CircuitPlayer', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':target_id',
    1 => ':id',
  ),
), null, null, 'CircuitPlayersRelatedByTargetId', false);
        $this->addRelation('PlayerGroup', '\\PlayerGroup', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':player_id',
    1 => ':id',
  ),
), null, null, 'PlayerGroups', false);
        $this->addRelation('LtsGame', '\\LtsGame', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':owner_id',
    1 => ':id',
  ),
), null, null, 'LtsGames', false);
        $this->addRelation('LtsCircuitPlayerRelatedByPlayerId', '\\LtsCircuitPlayer', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':player_id',
    1 => ':id',
  ),
), null, null, 'LtsCircuitPlayersRelatedByPlayerId', false);
        $this->addRelation('LtsCircuitPlayerRelatedByTargetId', '\\LtsCircuitPlayer', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':target_id',
    1 => ':id',
  ),
), null, null, 'LtsCircuitPlayersRelatedByTargetId', false);
        $this->addRelation('Game', '\\Game', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'Games');
        $this->addRelation('Preset', '\\Preset', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'Presets');
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
        return $withPrefix ? UserTableMap::CLASS_DEFAULT : UserTableMap::OM_CLASS;
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
     * @return array           (User object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = UserTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = UserTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + UserTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = UserTableMap::OM_CLASS;
            /** @var User $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            UserTableMap::addInstanceToPool($obj, $key);
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
            $key = UserTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = UserTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var User $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                UserTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(UserTableMap::COL_ID);
            $criteria->addSelectColumn(UserTableMap::COL_EMAIL);
            $criteria->addSelectColumn(UserTableMap::COL_USERNAME);
            $criteria->addSelectColumn(UserTableMap::COL_REAL_NAME);
            $criteria->addSelectColumn(UserTableMap::COL_PASSWORD);
            $criteria->addSelectColumn(UserTableMap::COL_MONEY);
            $criteria->addSelectColumn(UserTableMap::COL_TOTAL_MONEY);
            $criteria->addSelectColumn(UserTableMap::COL_VERIFICATION_TOKEN);
            $criteria->addSelectColumn(UserTableMap::COL_COOKIE_TOKEN);
            $criteria->addSelectColumn(UserTableMap::COL_ACTIVE);
            $criteria->addSelectColumn(UserTableMap::COL_DATE_CREATED);
            $criteria->addSelectColumn(UserTableMap::COL_VERIFICATION_TIME);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.username');
            $criteria->addSelectColumn($alias . '.real_name');
            $criteria->addSelectColumn($alias . '.password');
            $criteria->addSelectColumn($alias . '.money');
            $criteria->addSelectColumn($alias . '.total_money');
            $criteria->addSelectColumn($alias . '.verification_token');
            $criteria->addSelectColumn($alias . '.cookie_token');
            $criteria->addSelectColumn($alias . '.active');
            $criteria->addSelectColumn($alias . '.date_created');
            $criteria->addSelectColumn($alias . '.verification_time');
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
        return Propel::getServiceContainer()->getDatabaseMap(UserTableMap::DATABASE_NAME)->getTable(UserTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(UserTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(UserTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new UserTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a User or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or User object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \User) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(UserTableMap::DATABASE_NAME);
            $criteria->add(UserTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = UserQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            UserTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                UserTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the users table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return UserQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a User or Criteria object.
     *
     * @param mixed               $criteria Criteria or User object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from User object
        }

        if ($criteria->containsKey(UserTableMap::COL_ID) && $criteria->keyContainsValue(UserTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.UserTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = UserQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // UserTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
UserTableMap::buildTableMap();
