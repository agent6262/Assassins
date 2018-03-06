<?php

namespace Base;

use \LtsActionLog as ChildLtsActionLog;
use \LtsActionLogQuery as ChildLtsActionLogQuery;
use \LtsCircuitPlayer as ChildLtsCircuitPlayer;
use \LtsCircuitPlayerQuery as ChildLtsCircuitPlayerQuery;
use \LtsGame as ChildLtsGame;
use \LtsGameActionLog as ChildLtsGameActionLog;
use \LtsGameActionLogQuery as ChildLtsGameActionLogQuery;
use \LtsGameQuery as ChildLtsGameQuery;
use \User as ChildUser;
use \UserQuery as ChildUserQuery;
use \Exception;
use \PDO;
use Map\LtsCircuitPlayerTableMap;
use Map\LtsGameActionLogTableMap;
use Map\LtsGameTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'lts_games' table.
 *
 * 
 *
 * @package    propel.generator..Base
 */
abstract class LtsGame implements ActiveRecordInterface 
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\LtsGameTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     * 
     * @var        int
     */
    protected $id;

    /**
     * The value for the owner_id field.
     * 
     * @var        int
     */
    protected $owner_id;

    /**
     * The value for the rules field.
     * 
     * @var        string
     */
    protected $rules;

    /**
     * The value for the invite field.
     * 
     * Note: this column has a database default value of: true
     * @var        boolean
     */
    protected $invite;

    /**
     * The value for the request_invite field.
     * 
     * Note: this column has a database default value of: true
     * @var        boolean
     */
    protected $request_invite;

    /**
     * The value for the auto_place field.
     * 
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $auto_place;

    /**
     * @var        ChildUser
     */
    protected $aOwner;

    /**
     * @var        ObjectCollection|ChildLtsCircuitPlayer[] Collection to store aggregation of ChildLtsCircuitPlayer objects.
     */
    protected $collLtsCircuitPlayers;
    protected $collLtsCircuitPlayersPartial;

    /**
     * @var        ObjectCollection|ChildLtsGameActionLog[] Collection to store aggregation of ChildLtsGameActionLog objects.
     */
    protected $collLtsGameActionLogs;
    protected $collLtsGameActionLogsPartial;

    /**
     * @var        ObjectCollection|ChildLtsActionLog[] Cross Collection to store aggregation of ChildLtsActionLog objects.
     */
    protected $collLtsActionLogs;

    /**
     * @var bool
     */
    protected $collLtsActionLogsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLtsActionLog[]
     */
    protected $ltsActionLogsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLtsCircuitPlayer[]
     */
    protected $ltsCircuitPlayersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLtsGameActionLog[]
     */
    protected $ltsGameActionLogsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->invite = true;
        $this->request_invite = true;
        $this->auto_place = false;
    }

    /**
     * Initializes internal state of Base\LtsGame object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>LtsGame</code> instance.  If
     * <code>obj</code> is an instance of <code>LtsGame</code>, delegates to
     * <code>equals(LtsGame)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|LtsGame The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));
        
        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }
        
        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     * 
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [owner_id] column value.
     * 
     * @return int
     */
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /**
     * Get the [rules] column value.
     * 
     * @return string
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Get the [invite] column value.
     * 
     * @return boolean
     */
    public function getInvite()
    {
        return $this->invite;
    }

    /**
     * Get the [invite] column value.
     * 
     * @return boolean
     */
    public function isInvite()
    {
        return $this->getInvite();
    }

    /**
     * Get the [request_invite] column value.
     * 
     * @return boolean
     */
    public function getRequestInvite()
    {
        return $this->request_invite;
    }

    /**
     * Get the [request_invite] column value.
     * 
     * @return boolean
     */
    public function isRequestInvite()
    {
        return $this->getRequestInvite();
    }

    /**
     * Get the [auto_place] column value.
     * 
     * @return boolean
     */
    public function getAutoPlace()
    {
        return $this->auto_place;
    }

    /**
     * Get the [auto_place] column value.
     * 
     * @return boolean
     */
    public function isAutoPlace()
    {
        return $this->getAutoPlace();
    }

    /**
     * Set the value of [id] column.
     * 
     * @param int $v new value
     * @return $this|\LtsGame The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[LtsGameTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [owner_id] column.
     * 
     * @param int $v new value
     * @return $this|\LtsGame The current object (for fluent API support)
     */
    public function setOwnerId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->owner_id !== $v) {
            $this->owner_id = $v;
            $this->modifiedColumns[LtsGameTableMap::COL_OWNER_ID] = true;
        }

        if ($this->aOwner !== null && $this->aOwner->getId() !== $v) {
            $this->aOwner = null;
        }

        return $this;
    } // setOwnerId()

    /**
     * Set the value of [rules] column.
     * 
     * @param string $v new value
     * @return $this|\LtsGame The current object (for fluent API support)
     */
    public function setRules($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->rules !== $v) {
            $this->rules = $v;
            $this->modifiedColumns[LtsGameTableMap::COL_RULES] = true;
        }

        return $this;
    } // setRules()

    /**
     * Sets the value of the [invite] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * 
     * @param  boolean|integer|string $v The new value
     * @return $this|\LtsGame The current object (for fluent API support)
     */
    public function setInvite($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->invite !== $v) {
            $this->invite = $v;
            $this->modifiedColumns[LtsGameTableMap::COL_INVITE] = true;
        }

        return $this;
    } // setInvite()

    /**
     * Sets the value of the [request_invite] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * 
     * @param  boolean|integer|string $v The new value
     * @return $this|\LtsGame The current object (for fluent API support)
     */
    public function setRequestInvite($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->request_invite !== $v) {
            $this->request_invite = $v;
            $this->modifiedColumns[LtsGameTableMap::COL_REQUEST_INVITE] = true;
        }

        return $this;
    } // setRequestInvite()

    /**
     * Sets the value of the [auto_place] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * 
     * @param  boolean|integer|string $v The new value
     * @return $this|\LtsGame The current object (for fluent API support)
     */
    public function setAutoPlace($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->auto_place !== $v) {
            $this->auto_place = $v;
            $this->modifiedColumns[LtsGameTableMap::COL_AUTO_PLACE] = true;
        }

        return $this;
    } // setAutoPlace()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->invite !== true) {
                return false;
            }

            if ($this->request_invite !== true) {
                return false;
            }

            if ($this->auto_place !== false) {
                return false;
            }

        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : LtsGameTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : LtsGameTableMap::translateFieldName('OwnerId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->owner_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : LtsGameTableMap::translateFieldName('Rules', TableMap::TYPE_PHPNAME, $indexType)];
            $this->rules = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : LtsGameTableMap::translateFieldName('Invite', TableMap::TYPE_PHPNAME, $indexType)];
            $this->invite = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : LtsGameTableMap::translateFieldName('RequestInvite', TableMap::TYPE_PHPNAME, $indexType)];
            $this->request_invite = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : LtsGameTableMap::translateFieldName('AutoPlace', TableMap::TYPE_PHPNAME, $indexType)];
            $this->auto_place = (null !== $col) ? (boolean) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 6; // 6 = LtsGameTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\LtsGame'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aOwner !== null && $this->owner_id !== $this->aOwner->getId()) {
            $this->aOwner = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LtsGameTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildLtsGameQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aOwner = null;
            $this->collLtsCircuitPlayers = null;

            $this->collLtsGameActionLogs = null;

            $this->collLtsActionLogs = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see LtsGame::setDeleted()
     * @see LtsGame::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(LtsGameTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildLtsGameQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(LtsGameTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                LtsGameTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aOwner !== null) {
                if ($this->aOwner->isModified() || $this->aOwner->isNew()) {
                    $affectedRows += $this->aOwner->save($con);
                }
                $this->setOwner($this->aOwner);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->ltsActionLogsScheduledForDeletion !== null) {
                if (!$this->ltsActionLogsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->ltsActionLogsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \LtsGameActionLogQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->ltsActionLogsScheduledForDeletion = null;
                }

            }

            if ($this->collLtsActionLogs) {
                foreach ($this->collLtsActionLogs as $ltsActionLog) {
                    if (!$ltsActionLog->isDeleted() && ($ltsActionLog->isNew() || $ltsActionLog->isModified())) {
                        $ltsActionLog->save($con);
                    }
                }
            }


            if ($this->ltsCircuitPlayersScheduledForDeletion !== null) {
                if (!$this->ltsCircuitPlayersScheduledForDeletion->isEmpty()) {
                    foreach ($this->ltsCircuitPlayersScheduledForDeletion as $ltsCircuitPlayer) {
                        // need to save related object because we set the relation to null
                        $ltsCircuitPlayer->save($con);
                    }
                    $this->ltsCircuitPlayersScheduledForDeletion = null;
                }
            }

            if ($this->collLtsCircuitPlayers !== null) {
                foreach ($this->collLtsCircuitPlayers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->ltsGameActionLogsScheduledForDeletion !== null) {
                if (!$this->ltsGameActionLogsScheduledForDeletion->isEmpty()) {
                    \LtsGameActionLogQuery::create()
                        ->filterByPrimaryKeys($this->ltsGameActionLogsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->ltsGameActionLogsScheduledForDeletion = null;
                }
            }

            if ($this->collLtsGameActionLogs !== null) {
                foreach ($this->collLtsGameActionLogs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[LtsGameTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . LtsGameTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(LtsGameTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(LtsGameTableMap::COL_OWNER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`owner_id`';
        }
        if ($this->isColumnModified(LtsGameTableMap::COL_RULES)) {
            $modifiedColumns[':p' . $index++]  = '`rules`';
        }
        if ($this->isColumnModified(LtsGameTableMap::COL_INVITE)) {
            $modifiedColumns[':p' . $index++]  = '`invite`';
        }
        if ($this->isColumnModified(LtsGameTableMap::COL_REQUEST_INVITE)) {
            $modifiedColumns[':p' . $index++]  = '`request_invite`';
        }
        if ($this->isColumnModified(LtsGameTableMap::COL_AUTO_PLACE)) {
            $modifiedColumns[':p' . $index++]  = '`auto_place`';
        }

        $sql = sprintf(
            'INSERT INTO `lts_games` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':                        
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`owner_id`':                        
                        $stmt->bindValue($identifier, $this->owner_id, PDO::PARAM_INT);
                        break;
                    case '`rules`':                        
                        $stmt->bindValue($identifier, $this->rules, PDO::PARAM_STR);
                        break;
                    case '`invite`':
                        $stmt->bindValue($identifier, (int) $this->invite, PDO::PARAM_INT);
                        break;
                    case '`request_invite`':
                        $stmt->bindValue($identifier, (int) $this->request_invite, PDO::PARAM_INT);
                        break;
                    case '`auto_place`':
                        $stmt->bindValue($identifier, (int) $this->auto_place, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = LtsGameTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getOwnerId();
                break;
            case 2:
                return $this->getRules();
                break;
            case 3:
                return $this->getInvite();
                break;
            case 4:
                return $this->getRequestInvite();
                break;
            case 5:
                return $this->getAutoPlace();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['LtsGame'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['LtsGame'][$this->hashCode()] = true;
        $keys = LtsGameTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getOwnerId(),
            $keys[2] => $this->getRules(),
            $keys[3] => $this->getInvite(),
            $keys[4] => $this->getRequestInvite(),
            $keys[5] => $this->getAutoPlace(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }
        
        if ($includeForeignObjects) {
            if (null !== $this->aOwner) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'user';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'users';
                        break;
                    default:
                        $key = 'Owner';
                }
        
                $result[$key] = $this->aOwner->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collLtsCircuitPlayers) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'ltsCircuitPlayers';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'lts_circuit_playerss';
                        break;
                    default:
                        $key = 'LtsCircuitPlayers';
                }
        
                $result[$key] = $this->collLtsCircuitPlayers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collLtsGameActionLogs) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'ltsGameActionLogs';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'lts_game_action_logss';
                        break;
                    default:
                        $key = 'LtsGameActionLogs';
                }
        
                $result[$key] = $this->collLtsGameActionLogs->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\LtsGame
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = LtsGameTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\LtsGame
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setOwnerId($value);
                break;
            case 2:
                $this->setRules($value);
                break;
            case 3:
                $this->setInvite($value);
                break;
            case 4:
                $this->setRequestInvite($value);
                break;
            case 5:
                $this->setAutoPlace($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = LtsGameTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setOwnerId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setRules($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setInvite($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setRequestInvite($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setAutoPlace($arr[$keys[5]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\LtsGame The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(LtsGameTableMap::DATABASE_NAME);

        if ($this->isColumnModified(LtsGameTableMap::COL_ID)) {
            $criteria->add(LtsGameTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(LtsGameTableMap::COL_OWNER_ID)) {
            $criteria->add(LtsGameTableMap::COL_OWNER_ID, $this->owner_id);
        }
        if ($this->isColumnModified(LtsGameTableMap::COL_RULES)) {
            $criteria->add(LtsGameTableMap::COL_RULES, $this->rules);
        }
        if ($this->isColumnModified(LtsGameTableMap::COL_INVITE)) {
            $criteria->add(LtsGameTableMap::COL_INVITE, $this->invite);
        }
        if ($this->isColumnModified(LtsGameTableMap::COL_REQUEST_INVITE)) {
            $criteria->add(LtsGameTableMap::COL_REQUEST_INVITE, $this->request_invite);
        }
        if ($this->isColumnModified(LtsGameTableMap::COL_AUTO_PLACE)) {
            $criteria->add(LtsGameTableMap::COL_AUTO_PLACE, $this->auto_place);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildLtsGameQuery::create();
        $criteria->add(LtsGameTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }
        
    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \LtsGame (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setOwnerId($this->getOwnerId());
        $copyObj->setRules($this->getRules());
        $copyObj->setInvite($this->getInvite());
        $copyObj->setRequestInvite($this->getRequestInvite());
        $copyObj->setAutoPlace($this->getAutoPlace());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getLtsCircuitPlayers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLtsCircuitPlayer($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLtsGameActionLogs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLtsGameActionLog($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \LtsGame Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildUser object.
     *
     * @param  ChildUser $v
     * @return $this|\LtsGame The current object (for fluent API support)
     * @throws PropelException
     */
    public function setOwner(ChildUser $v = null)
    {
        if ($v === null) {
            $this->setOwnerId(NULL);
        } else {
            $this->setOwnerId($v->getId());
        }

        $this->aOwner = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildUser object, it will not be re-added.
        if ($v !== null) {
            $v->addLtsGame($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildUser object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildUser The associated ChildUser object.
     * @throws PropelException
     */
    public function getOwner(ConnectionInterface $con = null)
    {
        if ($this->aOwner === null && ($this->owner_id !== null)) {
            $this->aOwner = ChildUserQuery::create()->findPk($this->owner_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aOwner->addLtsGames($this);
             */
        }

        return $this->aOwner;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('LtsCircuitPlayer' == $relationName) {
            return $this->initLtsCircuitPlayers();
        }
        if ('LtsGameActionLog' == $relationName) {
            return $this->initLtsGameActionLogs();
        }
    }

    /**
     * Clears out the collLtsCircuitPlayers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLtsCircuitPlayers()
     */
    public function clearLtsCircuitPlayers()
    {
        $this->collLtsCircuitPlayers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collLtsCircuitPlayers collection loaded partially.
     */
    public function resetPartialLtsCircuitPlayers($v = true)
    {
        $this->collLtsCircuitPlayersPartial = $v;
    }

    /**
     * Initializes the collLtsCircuitPlayers collection.
     *
     * By default this just sets the collLtsCircuitPlayers collection to an empty array (like clearcollLtsCircuitPlayers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLtsCircuitPlayers($overrideExisting = true)
    {
        if (null !== $this->collLtsCircuitPlayers && !$overrideExisting) {
            return;
        }

        $collectionClassName = LtsCircuitPlayerTableMap::getTableMap()->getCollectionClassName();

        $this->collLtsCircuitPlayers = new $collectionClassName;
        $this->collLtsCircuitPlayers->setModel('\LtsCircuitPlayer');
    }

    /**
     * Gets an array of ChildLtsCircuitPlayer objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildLtsGame is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLtsCircuitPlayer[] List of ChildLtsCircuitPlayer objects
     * @throws PropelException
     */
    public function getLtsCircuitPlayers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLtsCircuitPlayersPartial && !$this->isNew();
        if (null === $this->collLtsCircuitPlayers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLtsCircuitPlayers) {
                // return empty collection
                $this->initLtsCircuitPlayers();
            } else {
                $collLtsCircuitPlayers = ChildLtsCircuitPlayerQuery::create(null, $criteria)
                    ->filterByLtsGame($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collLtsCircuitPlayersPartial && count($collLtsCircuitPlayers)) {
                        $this->initLtsCircuitPlayers(false);

                        foreach ($collLtsCircuitPlayers as $obj) {
                            if (false == $this->collLtsCircuitPlayers->contains($obj)) {
                                $this->collLtsCircuitPlayers->append($obj);
                            }
                        }

                        $this->collLtsCircuitPlayersPartial = true;
                    }

                    return $collLtsCircuitPlayers;
                }

                if ($partial && $this->collLtsCircuitPlayers) {
                    foreach ($this->collLtsCircuitPlayers as $obj) {
                        if ($obj->isNew()) {
                            $collLtsCircuitPlayers[] = $obj;
                        }
                    }
                }

                $this->collLtsCircuitPlayers = $collLtsCircuitPlayers;
                $this->collLtsCircuitPlayersPartial = false;
            }
        }

        return $this->collLtsCircuitPlayers;
    }

    /**
     * Sets a collection of ChildLtsCircuitPlayer objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $ltsCircuitPlayers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildLtsGame The current object (for fluent API support)
     */
    public function setLtsCircuitPlayers(Collection $ltsCircuitPlayers, ConnectionInterface $con = null)
    {
        /** @var ChildLtsCircuitPlayer[] $ltsCircuitPlayersToDelete */
        $ltsCircuitPlayersToDelete = $this->getLtsCircuitPlayers(new Criteria(), $con)->diff($ltsCircuitPlayers);

        
        $this->ltsCircuitPlayersScheduledForDeletion = $ltsCircuitPlayersToDelete;

        foreach ($ltsCircuitPlayersToDelete as $ltsCircuitPlayerRemoved) {
            $ltsCircuitPlayerRemoved->setLtsGame(null);
        }

        $this->collLtsCircuitPlayers = null;
        foreach ($ltsCircuitPlayers as $ltsCircuitPlayer) {
            $this->addLtsCircuitPlayer($ltsCircuitPlayer);
        }

        $this->collLtsCircuitPlayers = $ltsCircuitPlayers;
        $this->collLtsCircuitPlayersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related LtsCircuitPlayer objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related LtsCircuitPlayer objects.
     * @throws PropelException
     */
    public function countLtsCircuitPlayers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLtsCircuitPlayersPartial && !$this->isNew();
        if (null === $this->collLtsCircuitPlayers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLtsCircuitPlayers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLtsCircuitPlayers());
            }

            $query = ChildLtsCircuitPlayerQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByLtsGame($this)
                ->count($con);
        }

        return count($this->collLtsCircuitPlayers);
    }

    /**
     * Method called to associate a ChildLtsCircuitPlayer object to this object
     * through the ChildLtsCircuitPlayer foreign key attribute.
     *
     * @param  ChildLtsCircuitPlayer $l ChildLtsCircuitPlayer
     * @return $this|\LtsGame The current object (for fluent API support)
     */
    public function addLtsCircuitPlayer(ChildLtsCircuitPlayer $l)
    {
        if ($this->collLtsCircuitPlayers === null) {
            $this->initLtsCircuitPlayers();
            $this->collLtsCircuitPlayersPartial = true;
        }

        if (!$this->collLtsCircuitPlayers->contains($l)) {
            $this->doAddLtsCircuitPlayer($l);

            if ($this->ltsCircuitPlayersScheduledForDeletion and $this->ltsCircuitPlayersScheduledForDeletion->contains($l)) {
                $this->ltsCircuitPlayersScheduledForDeletion->remove($this->ltsCircuitPlayersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildLtsCircuitPlayer $ltsCircuitPlayer The ChildLtsCircuitPlayer object to add.
     */
    protected function doAddLtsCircuitPlayer(ChildLtsCircuitPlayer $ltsCircuitPlayer)
    {
        $this->collLtsCircuitPlayers[]= $ltsCircuitPlayer;
        $ltsCircuitPlayer->setLtsGame($this);
    }

    /**
     * @param  ChildLtsCircuitPlayer $ltsCircuitPlayer The ChildLtsCircuitPlayer object to remove.
     * @return $this|ChildLtsGame The current object (for fluent API support)
     */
    public function removeLtsCircuitPlayer(ChildLtsCircuitPlayer $ltsCircuitPlayer)
    {
        if ($this->getLtsCircuitPlayers()->contains($ltsCircuitPlayer)) {
            $pos = $this->collLtsCircuitPlayers->search($ltsCircuitPlayer);
            $this->collLtsCircuitPlayers->remove($pos);
            if (null === $this->ltsCircuitPlayersScheduledForDeletion) {
                $this->ltsCircuitPlayersScheduledForDeletion = clone $this->collLtsCircuitPlayers;
                $this->ltsCircuitPlayersScheduledForDeletion->clear();
            }
            $this->ltsCircuitPlayersScheduledForDeletion[]= $ltsCircuitPlayer;
            $ltsCircuitPlayer->setLtsGame(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this LtsGame is new, it will return
     * an empty collection; or if this LtsGame has previously
     * been saved, it will retrieve related LtsCircuitPlayers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in LtsGame.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLtsCircuitPlayer[] List of ChildLtsCircuitPlayer objects
     */
    public function getLtsCircuitPlayersJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLtsCircuitPlayerQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getLtsCircuitPlayers($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this LtsGame is new, it will return
     * an empty collection; or if this LtsGame has previously
     * been saved, it will retrieve related LtsCircuitPlayers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in LtsGame.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLtsCircuitPlayer[] List of ChildLtsCircuitPlayer objects
     */
    public function getLtsCircuitPlayersJoinTarget(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLtsCircuitPlayerQuery::create(null, $criteria);
        $query->joinWith('Target', $joinBehavior);

        return $this->getLtsCircuitPlayers($query, $con);
    }

    /**
     * Clears out the collLtsGameActionLogs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLtsGameActionLogs()
     */
    public function clearLtsGameActionLogs()
    {
        $this->collLtsGameActionLogs = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collLtsGameActionLogs collection loaded partially.
     */
    public function resetPartialLtsGameActionLogs($v = true)
    {
        $this->collLtsGameActionLogsPartial = $v;
    }

    /**
     * Initializes the collLtsGameActionLogs collection.
     *
     * By default this just sets the collLtsGameActionLogs collection to an empty array (like clearcollLtsGameActionLogs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLtsGameActionLogs($overrideExisting = true)
    {
        if (null !== $this->collLtsGameActionLogs && !$overrideExisting) {
            return;
        }

        $collectionClassName = LtsGameActionLogTableMap::getTableMap()->getCollectionClassName();

        $this->collLtsGameActionLogs = new $collectionClassName;
        $this->collLtsGameActionLogs->setModel('\LtsGameActionLog');
    }

    /**
     * Gets an array of ChildLtsGameActionLog objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildLtsGame is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLtsGameActionLog[] List of ChildLtsGameActionLog objects
     * @throws PropelException
     */
    public function getLtsGameActionLogs(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLtsGameActionLogsPartial && !$this->isNew();
        if (null === $this->collLtsGameActionLogs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLtsGameActionLogs) {
                // return empty collection
                $this->initLtsGameActionLogs();
            } else {
                $collLtsGameActionLogs = ChildLtsGameActionLogQuery::create(null, $criteria)
                    ->filterByLtsGame($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collLtsGameActionLogsPartial && count($collLtsGameActionLogs)) {
                        $this->initLtsGameActionLogs(false);

                        foreach ($collLtsGameActionLogs as $obj) {
                            if (false == $this->collLtsGameActionLogs->contains($obj)) {
                                $this->collLtsGameActionLogs->append($obj);
                            }
                        }

                        $this->collLtsGameActionLogsPartial = true;
                    }

                    return $collLtsGameActionLogs;
                }

                if ($partial && $this->collLtsGameActionLogs) {
                    foreach ($this->collLtsGameActionLogs as $obj) {
                        if ($obj->isNew()) {
                            $collLtsGameActionLogs[] = $obj;
                        }
                    }
                }

                $this->collLtsGameActionLogs = $collLtsGameActionLogs;
                $this->collLtsGameActionLogsPartial = false;
            }
        }

        return $this->collLtsGameActionLogs;
    }

    /**
     * Sets a collection of ChildLtsGameActionLog objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $ltsGameActionLogs A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildLtsGame The current object (for fluent API support)
     */
    public function setLtsGameActionLogs(Collection $ltsGameActionLogs, ConnectionInterface $con = null)
    {
        /** @var ChildLtsGameActionLog[] $ltsGameActionLogsToDelete */
        $ltsGameActionLogsToDelete = $this->getLtsGameActionLogs(new Criteria(), $con)->diff($ltsGameActionLogs);

        
        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->ltsGameActionLogsScheduledForDeletion = clone $ltsGameActionLogsToDelete;

        foreach ($ltsGameActionLogsToDelete as $ltsGameActionLogRemoved) {
            $ltsGameActionLogRemoved->setLtsGame(null);
        }

        $this->collLtsGameActionLogs = null;
        foreach ($ltsGameActionLogs as $ltsGameActionLog) {
            $this->addLtsGameActionLog($ltsGameActionLog);
        }

        $this->collLtsGameActionLogs = $ltsGameActionLogs;
        $this->collLtsGameActionLogsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related LtsGameActionLog objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related LtsGameActionLog objects.
     * @throws PropelException
     */
    public function countLtsGameActionLogs(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLtsGameActionLogsPartial && !$this->isNew();
        if (null === $this->collLtsGameActionLogs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLtsGameActionLogs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLtsGameActionLogs());
            }

            $query = ChildLtsGameActionLogQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByLtsGame($this)
                ->count($con);
        }

        return count($this->collLtsGameActionLogs);
    }

    /**
     * Method called to associate a ChildLtsGameActionLog object to this object
     * through the ChildLtsGameActionLog foreign key attribute.
     *
     * @param  ChildLtsGameActionLog $l ChildLtsGameActionLog
     * @return $this|\LtsGame The current object (for fluent API support)
     */
    public function addLtsGameActionLog(ChildLtsGameActionLog $l)
    {
        if ($this->collLtsGameActionLogs === null) {
            $this->initLtsGameActionLogs();
            $this->collLtsGameActionLogsPartial = true;
        }

        if (!$this->collLtsGameActionLogs->contains($l)) {
            $this->doAddLtsGameActionLog($l);

            if ($this->ltsGameActionLogsScheduledForDeletion and $this->ltsGameActionLogsScheduledForDeletion->contains($l)) {
                $this->ltsGameActionLogsScheduledForDeletion->remove($this->ltsGameActionLogsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildLtsGameActionLog $ltsGameActionLog The ChildLtsGameActionLog object to add.
     */
    protected function doAddLtsGameActionLog(ChildLtsGameActionLog $ltsGameActionLog)
    {
        $this->collLtsGameActionLogs[]= $ltsGameActionLog;
        $ltsGameActionLog->setLtsGame($this);
    }

    /**
     * @param  ChildLtsGameActionLog $ltsGameActionLog The ChildLtsGameActionLog object to remove.
     * @return $this|ChildLtsGame The current object (for fluent API support)
     */
    public function removeLtsGameActionLog(ChildLtsGameActionLog $ltsGameActionLog)
    {
        if ($this->getLtsGameActionLogs()->contains($ltsGameActionLog)) {
            $pos = $this->collLtsGameActionLogs->search($ltsGameActionLog);
            $this->collLtsGameActionLogs->remove($pos);
            if (null === $this->ltsGameActionLogsScheduledForDeletion) {
                $this->ltsGameActionLogsScheduledForDeletion = clone $this->collLtsGameActionLogs;
                $this->ltsGameActionLogsScheduledForDeletion->clear();
            }
            $this->ltsGameActionLogsScheduledForDeletion[]= clone $ltsGameActionLog;
            $ltsGameActionLog->setLtsGame(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this LtsGame is new, it will return
     * an empty collection; or if this LtsGame has previously
     * been saved, it will retrieve related LtsGameActionLogs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in LtsGame.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLtsGameActionLog[] List of ChildLtsGameActionLog objects
     */
    public function getLtsGameActionLogsJoinLtsActionLog(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLtsGameActionLogQuery::create(null, $criteria);
        $query->joinWith('LtsActionLog', $joinBehavior);

        return $this->getLtsGameActionLogs($query, $con);
    }

    /**
     * Clears out the collLtsActionLogs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLtsActionLogs()
     */
    public function clearLtsActionLogs()
    {
        $this->collLtsActionLogs = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collLtsActionLogs crossRef collection.
     *
     * By default this just sets the collLtsActionLogs collection to an empty collection (like clearLtsActionLogs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initLtsActionLogs()
    {
        $collectionClassName = LtsGameActionLogTableMap::getTableMap()->getCollectionClassName();

        $this->collLtsActionLogs = new $collectionClassName;
        $this->collLtsActionLogsPartial = true;
        $this->collLtsActionLogs->setModel('\LtsActionLog');
    }

    /**
     * Checks if the collLtsActionLogs collection is loaded.
     *
     * @return bool
     */
    public function isLtsActionLogsLoaded()
    {
        return null !== $this->collLtsActionLogs;
    }

    /**
     * Gets a collection of ChildLtsActionLog objects related by a many-to-many relationship
     * to the current object by way of the lts_game_action_logs cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildLtsGame is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildLtsActionLog[] List of ChildLtsActionLog objects
     */
    public function getLtsActionLogs(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLtsActionLogsPartial && !$this->isNew();
        if (null === $this->collLtsActionLogs || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collLtsActionLogs) {
                    $this->initLtsActionLogs();
                }
            } else {

                $query = ChildLtsActionLogQuery::create(null, $criteria)
                    ->filterByLtsGame($this);
                $collLtsActionLogs = $query->find($con);
                if (null !== $criteria) {
                    return $collLtsActionLogs;
                }

                if ($partial && $this->collLtsActionLogs) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collLtsActionLogs as $obj) {
                        if (!$collLtsActionLogs->contains($obj)) {
                            $collLtsActionLogs[] = $obj;
                        }
                    }
                }

                $this->collLtsActionLogs = $collLtsActionLogs;
                $this->collLtsActionLogsPartial = false;
            }
        }

        return $this->collLtsActionLogs;
    }

    /**
     * Sets a collection of LtsActionLog objects related by a many-to-many relationship
     * to the current object by way of the lts_game_action_logs cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $ltsActionLogs A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildLtsGame The current object (for fluent API support)
     */
    public function setLtsActionLogs(Collection $ltsActionLogs, ConnectionInterface $con = null)
    {
        $this->clearLtsActionLogs();
        $currentLtsActionLogs = $this->getLtsActionLogs();

        $ltsActionLogsScheduledForDeletion = $currentLtsActionLogs->diff($ltsActionLogs);

        foreach ($ltsActionLogsScheduledForDeletion as $toDelete) {
            $this->removeLtsActionLog($toDelete);
        }

        foreach ($ltsActionLogs as $ltsActionLog) {
            if (!$currentLtsActionLogs->contains($ltsActionLog)) {
                $this->doAddLtsActionLog($ltsActionLog);
            }
        }

        $this->collLtsActionLogsPartial = false;
        $this->collLtsActionLogs = $ltsActionLogs;

        return $this;
    }

    /**
     * Gets the number of LtsActionLog objects related by a many-to-many relationship
     * to the current object by way of the lts_game_action_logs cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related LtsActionLog objects
     */
    public function countLtsActionLogs(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLtsActionLogsPartial && !$this->isNew();
        if (null === $this->collLtsActionLogs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLtsActionLogs) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getLtsActionLogs());
                }

                $query = ChildLtsActionLogQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByLtsGame($this)
                    ->count($con);
            }
        } else {
            return count($this->collLtsActionLogs);
        }
    }

    /**
     * Associate a ChildLtsActionLog to this object
     * through the lts_game_action_logs cross reference table.
     * 
     * @param ChildLtsActionLog $ltsActionLog
     * @return ChildLtsGame The current object (for fluent API support)
     */
    public function addLtsActionLog(ChildLtsActionLog $ltsActionLog)
    {
        if ($this->collLtsActionLogs === null) {
            $this->initLtsActionLogs();
        }

        if (!$this->getLtsActionLogs()->contains($ltsActionLog)) {
            // only add it if the **same** object is not already associated
            $this->collLtsActionLogs->push($ltsActionLog);
            $this->doAddLtsActionLog($ltsActionLog);
        }

        return $this;
    }

    /**
     * 
     * @param ChildLtsActionLog $ltsActionLog
     */
    protected function doAddLtsActionLog(ChildLtsActionLog $ltsActionLog)
    {
        $ltsGameActionLog = new ChildLtsGameActionLog();

        $ltsGameActionLog->setLtsActionLog($ltsActionLog);

        $ltsGameActionLog->setLtsGame($this);

        $this->addLtsGameActionLog($ltsGameActionLog);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$ltsActionLog->isLtsGamesLoaded()) {
            $ltsActionLog->initLtsGames();
            $ltsActionLog->getLtsGames()->push($this);
        } elseif (!$ltsActionLog->getLtsGames()->contains($this)) {
            $ltsActionLog->getLtsGames()->push($this);
        }

    }

    /**
     * Remove ltsActionLog of this object
     * through the lts_game_action_logs cross reference table.
     * 
     * @param ChildLtsActionLog $ltsActionLog
     * @return ChildLtsGame The current object (for fluent API support)
     */
    public function removeLtsActionLog(ChildLtsActionLog $ltsActionLog)
    {
        if ($this->getLtsActionLogs()->contains($ltsActionLog)) { $ltsGameActionLog = new ChildLtsGameActionLog();

            $ltsGameActionLog->setLtsActionLog($ltsActionLog);
            if ($ltsActionLog->isLtsGamesLoaded()) {
                //remove the back reference if available
                $ltsActionLog->getLtsGames()->removeObject($this);
            }

            $ltsGameActionLog->setLtsGame($this);
            $this->removeLtsGameActionLog(clone $ltsGameActionLog);
            $ltsGameActionLog->clear();

            $this->collLtsActionLogs->remove($this->collLtsActionLogs->search($ltsActionLog));
            
            if (null === $this->ltsActionLogsScheduledForDeletion) {
                $this->ltsActionLogsScheduledForDeletion = clone $this->collLtsActionLogs;
                $this->ltsActionLogsScheduledForDeletion->clear();
            }

            $this->ltsActionLogsScheduledForDeletion->push($ltsActionLog);
        }


        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aOwner) {
            $this->aOwner->removeLtsGame($this);
        }
        $this->id = null;
        $this->owner_id = null;
        $this->rules = null;
        $this->invite = null;
        $this->request_invite = null;
        $this->auto_place = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collLtsCircuitPlayers) {
                foreach ($this->collLtsCircuitPlayers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLtsGameActionLogs) {
                foreach ($this->collLtsGameActionLogs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLtsActionLogs) {
                foreach ($this->collLtsActionLogs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collLtsCircuitPlayers = null;
        $this->collLtsGameActionLogs = null;
        $this->collLtsActionLogs = null;
        $this->aOwner = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(LtsGameTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preSave')) {
            return parent::preSave($con);
        }
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postSave')) {
            parent::postSave($con);
        }
    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preInsert')) {
            return parent::preInsert($con);
        }
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postInsert')) {
            parent::postInsert($con);
        }
    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preUpdate')) {
            return parent::preUpdate($con);
        }
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postUpdate')) {
            parent::postUpdate($con);
        }
    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postDelete')) {
            parent::postDelete($con);
        }
    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
