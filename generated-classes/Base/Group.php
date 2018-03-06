<?php

namespace Base;

use \Game as ChildGame;
use \GameGroup as ChildGameGroup;
use \GameGroupQuery as ChildGameGroupQuery;
use \GameQuery as ChildGameQuery;
use \Group as ChildGroup;
use \GroupQuery as ChildGroupQuery;
use \PlayerGroup as ChildPlayerGroup;
use \PlayerGroupQuery as ChildPlayerGroupQuery;
use \Preset as ChildPreset;
use \PresetGroup as ChildPresetGroup;
use \PresetGroupQuery as ChildPresetGroupQuery;
use \PresetQuery as ChildPresetQuery;
use \Setting as ChildSetting;
use \SettingQuery as ChildSettingQuery;
use \Exception;
use \PDO;
use Map\GameGroupTableMap;
use Map\GameTableMap;
use Map\GroupTableMap;
use Map\PlayerGroupTableMap;
use Map\PresetGroupTableMap;
use Map\SettingTableMap;
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
 * Base class that represents a row from the 'groups' table.
 *
 * 
 *
 * @package    propel.generator..Base
 */
abstract class Group implements ActiveRecordInterface 
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\GroupTableMap';


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
     * The value for the name field.
     * 
     * @var        string
     */
    protected $name;

    /**
     * The value for the permissions field.
     * 
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $permissions;

    /**
     * The value for the rank field.
     * 
     * @var        int
     */
    protected $rank;

    /**
     * @var        ObjectCollection|ChildGame[] Collection to store aggregation of ChildGame objects.
     */
    protected $collAutoJoinedGames;
    protected $collAutoJoinedGamesPartial;

    /**
     * @var        ObjectCollection|ChildGameGroup[] Collection to store aggregation of ChildGameGroup objects.
     */
    protected $collGameGroups;
    protected $collGameGroupsPartial;

    /**
     * @var        ObjectCollection|ChildPlayerGroup[] Collection to store aggregation of ChildPlayerGroup objects.
     */
    protected $collPlayerGroups;
    protected $collPlayerGroupsPartial;

    /**
     * @var        ObjectCollection|ChildPresetGroup[] Collection to store aggregation of ChildPresetGroup objects.
     */
    protected $collPresetGroups;
    protected $collPresetGroupsPartial;

    /**
     * @var        ObjectCollection|ChildSetting[] Collection to store aggregation of ChildSetting objects.
     */
    protected $collSettings;
    protected $collSettingsPartial;

    /**
     * @var        ObjectCollection|ChildGame[] Cross Collection to store aggregation of ChildGame objects.
     */
    protected $collGames;

    /**
     * @var bool
     */
    protected $collGamesPartial;

    /**
     * @var        ObjectCollection|ChildPreset[] Cross Collection to store aggregation of ChildPreset objects.
     */
    protected $collPresets;

    /**
     * @var bool
     */
    protected $collPresetsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildGame[]
     */
    protected $gamesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPreset[]
     */
    protected $presetsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildGame[]
     */
    protected $autoJoinedGamesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildGameGroup[]
     */
    protected $gameGroupsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerGroup[]
     */
    protected $playerGroupsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPresetGroup[]
     */
    protected $presetGroupsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSetting[]
     */
    protected $settingsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->permissions = 0;
    }

    /**
     * Initializes internal state of Base\Group object.
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
     * Compares this with another <code>Group</code> instance.  If
     * <code>obj</code> is an instance of <code>Group</code>, delegates to
     * <code>equals(Group)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Group The current object, for fluid interface
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
     * Get the [name] column value.
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [permissions] column value.
     * 
     * @return int
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * Get the [rank] column value.
     * 
     * @return int
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set the value of [id] column.
     * 
     * @param int $v new value
     * @return $this|\Group The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[GroupTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     * 
     * @param string $v new value
     * @return $this|\Group The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[GroupTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [permissions] column.
     * 
     * @param int $v new value
     * @return $this|\Group The current object (for fluent API support)
     */
    public function setPermissions($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->permissions !== $v) {
            $this->permissions = $v;
            $this->modifiedColumns[GroupTableMap::COL_PERMISSIONS] = true;
        }

        return $this;
    } // setPermissions()

    /**
     * Set the value of [rank] column.
     * 
     * @param int $v new value
     * @return $this|\Group The current object (for fluent API support)
     */
    public function setRank($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->rank !== $v) {
            $this->rank = $v;
            $this->modifiedColumns[GroupTableMap::COL_RANK] = true;
        }

        return $this;
    } // setRank()

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
            if ($this->permissions !== 0) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : GroupTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : GroupTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : GroupTableMap::translateFieldName('Permissions', TableMap::TYPE_PHPNAME, $indexType)];
            $this->permissions = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : GroupTableMap::translateFieldName('Rank', TableMap::TYPE_PHPNAME, $indexType)];
            $this->rank = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 4; // 4 = GroupTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Group'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(GroupTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildGroupQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collAutoJoinedGames = null;

            $this->collGameGroups = null;

            $this->collPlayerGroups = null;

            $this->collPresetGroups = null;

            $this->collSettings = null;

            $this->collGames = null;
            $this->collPresets = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Group::setDeleted()
     * @see Group::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(GroupTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildGroupQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(GroupTableMap::DATABASE_NAME);
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
                GroupTableMap::addInstanceToPool($this);
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

            if ($this->gamesScheduledForDeletion !== null) {
                if (!$this->gamesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->gamesScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \GameGroupQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->gamesScheduledForDeletion = null;
                }

            }

            if ($this->collGames) {
                foreach ($this->collGames as $game) {
                    if (!$game->isDeleted() && ($game->isNew() || $game->isModified())) {
                        $game->save($con);
                    }
                }
            }


            if ($this->presetsScheduledForDeletion !== null) {
                if (!$this->presetsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->presetsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \PresetGroupQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->presetsScheduledForDeletion = null;
                }

            }

            if ($this->collPresets) {
                foreach ($this->collPresets as $preset) {
                    if (!$preset->isDeleted() && ($preset->isNew() || $preset->isModified())) {
                        $preset->save($con);
                    }
                }
            }


            if ($this->autoJoinedGamesScheduledForDeletion !== null) {
                if (!$this->autoJoinedGamesScheduledForDeletion->isEmpty()) {
                    \GameQuery::create()
                        ->filterByPrimaryKeys($this->autoJoinedGamesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->autoJoinedGamesScheduledForDeletion = null;
                }
            }

            if ($this->collAutoJoinedGames !== null) {
                foreach ($this->collAutoJoinedGames as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->gameGroupsScheduledForDeletion !== null) {
                if (!$this->gameGroupsScheduledForDeletion->isEmpty()) {
                    \GameGroupQuery::create()
                        ->filterByPrimaryKeys($this->gameGroupsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->gameGroupsScheduledForDeletion = null;
                }
            }

            if ($this->collGameGroups !== null) {
                foreach ($this->collGameGroups as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerGroupsScheduledForDeletion !== null) {
                if (!$this->playerGroupsScheduledForDeletion->isEmpty()) {
                    \PlayerGroupQuery::create()
                        ->filterByPrimaryKeys($this->playerGroupsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playerGroupsScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerGroups !== null) {
                foreach ($this->collPlayerGroups as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->presetGroupsScheduledForDeletion !== null) {
                if (!$this->presetGroupsScheduledForDeletion->isEmpty()) {
                    \PresetGroupQuery::create()
                        ->filterByPrimaryKeys($this->presetGroupsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->presetGroupsScheduledForDeletion = null;
                }
            }

            if ($this->collPresetGroups !== null) {
                foreach ($this->collPresetGroups as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->settingsScheduledForDeletion !== null) {
                if (!$this->settingsScheduledForDeletion->isEmpty()) {
                    \SettingQuery::create()
                        ->filterByPrimaryKeys($this->settingsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->settingsScheduledForDeletion = null;
                }
            }

            if ($this->collSettings !== null) {
                foreach ($this->collSettings as $referrerFK) {
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

        $this->modifiedColumns[GroupTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . GroupTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(GroupTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(GroupTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(GroupTableMap::COL_PERMISSIONS)) {
            $modifiedColumns[':p' . $index++]  = '`permissions`';
        }
        if ($this->isColumnModified(GroupTableMap::COL_RANK)) {
            $modifiedColumns[':p' . $index++]  = '`rank`';
        }

        $sql = sprintf(
            'INSERT INTO `groups` (%s) VALUES (%s)',
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
                    case '`name`':                        
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`permissions`':                        
                        $stmt->bindValue($identifier, $this->permissions, PDO::PARAM_INT);
                        break;
                    case '`rank`':                        
                        $stmt->bindValue($identifier, $this->rank, PDO::PARAM_INT);
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
        $pos = GroupTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getName();
                break;
            case 2:
                return $this->getPermissions();
                break;
            case 3:
                return $this->getRank();
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

        if (isset($alreadyDumpedObjects['Group'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Group'][$this->hashCode()] = true;
        $keys = GroupTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getPermissions(),
            $keys[3] => $this->getRank(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }
        
        if ($includeForeignObjects) {
            if (null !== $this->collAutoJoinedGames) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'games';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'gamess';
                        break;
                    default:
                        $key = 'AutoJoinedGames';
                }
        
                $result[$key] = $this->collAutoJoinedGames->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collGameGroups) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'gameGroups';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'game_groupss';
                        break;
                    default:
                        $key = 'GameGroups';
                }
        
                $result[$key] = $this->collGameGroups->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerGroups) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerGroups';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_groupss';
                        break;
                    default:
                        $key = 'PlayerGroups';
                }
        
                $result[$key] = $this->collPlayerGroups->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPresetGroups) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'presetGroups';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'preset_groupss';
                        break;
                    default:
                        $key = 'PresetGroups';
                }
        
                $result[$key] = $this->collPresetGroups->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSettings) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'settings';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'settingss';
                        break;
                    default:
                        $key = 'Settings';
                }
        
                $result[$key] = $this->collSettings->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Group
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = GroupTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Group
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setPermissions($value);
                break;
            case 3:
                $this->setRank($value);
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
        $keys = GroupTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setPermissions($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setRank($arr[$keys[3]]);
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
     * @return $this|\Group The current object, for fluid interface
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
        $criteria = new Criteria(GroupTableMap::DATABASE_NAME);

        if ($this->isColumnModified(GroupTableMap::COL_ID)) {
            $criteria->add(GroupTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(GroupTableMap::COL_NAME)) {
            $criteria->add(GroupTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(GroupTableMap::COL_PERMISSIONS)) {
            $criteria->add(GroupTableMap::COL_PERMISSIONS, $this->permissions);
        }
        if ($this->isColumnModified(GroupTableMap::COL_RANK)) {
            $criteria->add(GroupTableMap::COL_RANK, $this->rank);
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
        $criteria = ChildGroupQuery::create();
        $criteria->add(GroupTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Group (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setPermissions($this->getPermissions());
        $copyObj->setRank($this->getRank());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getAutoJoinedGames() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAutoJoinedGame($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getGameGroups() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addGameGroup($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerGroups() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerGroup($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPresetGroups() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPresetGroup($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSettings() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSetting($relObj->copy($deepCopy));
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
     * @return \Group Clone of current object.
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('AutoJoinedGame' == $relationName) {
            return $this->initAutoJoinedGames();
        }
        if ('GameGroup' == $relationName) {
            return $this->initGameGroups();
        }
        if ('PlayerGroup' == $relationName) {
            return $this->initPlayerGroups();
        }
        if ('PresetGroup' == $relationName) {
            return $this->initPresetGroups();
        }
        if ('Setting' == $relationName) {
            return $this->initSettings();
        }
    }

    /**
     * Clears out the collAutoJoinedGames collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addAutoJoinedGames()
     */
    public function clearAutoJoinedGames()
    {
        $this->collAutoJoinedGames = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collAutoJoinedGames collection loaded partially.
     */
    public function resetPartialAutoJoinedGames($v = true)
    {
        $this->collAutoJoinedGamesPartial = $v;
    }

    /**
     * Initializes the collAutoJoinedGames collection.
     *
     * By default this just sets the collAutoJoinedGames collection to an empty array (like clearcollAutoJoinedGames());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAutoJoinedGames($overrideExisting = true)
    {
        if (null !== $this->collAutoJoinedGames && !$overrideExisting) {
            return;
        }

        $collectionClassName = GameTableMap::getTableMap()->getCollectionClassName();

        $this->collAutoJoinedGames = new $collectionClassName;
        $this->collAutoJoinedGames->setModel('\Game');
    }

    /**
     * Gets an array of ChildGame objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGroup is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildGame[] List of ChildGame objects
     * @throws PropelException
     */
    public function getAutoJoinedGames(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collAutoJoinedGamesPartial && !$this->isNew();
        if (null === $this->collAutoJoinedGames || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAutoJoinedGames) {
                // return empty collection
                $this->initAutoJoinedGames();
            } else {
                $collAutoJoinedGames = ChildGameQuery::create(null, $criteria)
                    ->filterByAutoJoinGroup($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAutoJoinedGamesPartial && count($collAutoJoinedGames)) {
                        $this->initAutoJoinedGames(false);

                        foreach ($collAutoJoinedGames as $obj) {
                            if (false == $this->collAutoJoinedGames->contains($obj)) {
                                $this->collAutoJoinedGames->append($obj);
                            }
                        }

                        $this->collAutoJoinedGamesPartial = true;
                    }

                    return $collAutoJoinedGames;
                }

                if ($partial && $this->collAutoJoinedGames) {
                    foreach ($this->collAutoJoinedGames as $obj) {
                        if ($obj->isNew()) {
                            $collAutoJoinedGames[] = $obj;
                        }
                    }
                }

                $this->collAutoJoinedGames = $collAutoJoinedGames;
                $this->collAutoJoinedGamesPartial = false;
            }
        }

        return $this->collAutoJoinedGames;
    }

    /**
     * Sets a collection of ChildGame objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $autoJoinedGames A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildGroup The current object (for fluent API support)
     */
    public function setAutoJoinedGames(Collection $autoJoinedGames, ConnectionInterface $con = null)
    {
        /** @var ChildGame[] $autoJoinedGamesToDelete */
        $autoJoinedGamesToDelete = $this->getAutoJoinedGames(new Criteria(), $con)->diff($autoJoinedGames);

        
        $this->autoJoinedGamesScheduledForDeletion = $autoJoinedGamesToDelete;

        foreach ($autoJoinedGamesToDelete as $autoJoinedGameRemoved) {
            $autoJoinedGameRemoved->setAutoJoinGroup(null);
        }

        $this->collAutoJoinedGames = null;
        foreach ($autoJoinedGames as $autoJoinedGame) {
            $this->addAutoJoinedGame($autoJoinedGame);
        }

        $this->collAutoJoinedGames = $autoJoinedGames;
        $this->collAutoJoinedGamesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Game objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Game objects.
     * @throws PropelException
     */
    public function countAutoJoinedGames(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collAutoJoinedGamesPartial && !$this->isNew();
        if (null === $this->collAutoJoinedGames || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAutoJoinedGames) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAutoJoinedGames());
            }

            $query = ChildGameQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByAutoJoinGroup($this)
                ->count($con);
        }

        return count($this->collAutoJoinedGames);
    }

    /**
     * Method called to associate a ChildGame object to this object
     * through the ChildGame foreign key attribute.
     *
     * @param  ChildGame $l ChildGame
     * @return $this|\Group The current object (for fluent API support)
     */
    public function addAutoJoinedGame(ChildGame $l)
    {
        if ($this->collAutoJoinedGames === null) {
            $this->initAutoJoinedGames();
            $this->collAutoJoinedGamesPartial = true;
        }

        if (!$this->collAutoJoinedGames->contains($l)) {
            $this->doAddAutoJoinedGame($l);

            if ($this->autoJoinedGamesScheduledForDeletion and $this->autoJoinedGamesScheduledForDeletion->contains($l)) {
                $this->autoJoinedGamesScheduledForDeletion->remove($this->autoJoinedGamesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildGame $autoJoinedGame The ChildGame object to add.
     */
    protected function doAddAutoJoinedGame(ChildGame $autoJoinedGame)
    {
        $this->collAutoJoinedGames[]= $autoJoinedGame;
        $autoJoinedGame->setAutoJoinGroup($this);
    }

    /**
     * @param  ChildGame $autoJoinedGame The ChildGame object to remove.
     * @return $this|ChildGroup The current object (for fluent API support)
     */
    public function removeAutoJoinedGame(ChildGame $autoJoinedGame)
    {
        if ($this->getAutoJoinedGames()->contains($autoJoinedGame)) {
            $pos = $this->collAutoJoinedGames->search($autoJoinedGame);
            $this->collAutoJoinedGames->remove($pos);
            if (null === $this->autoJoinedGamesScheduledForDeletion) {
                $this->autoJoinedGamesScheduledForDeletion = clone $this->collAutoJoinedGames;
                $this->autoJoinedGamesScheduledForDeletion->clear();
            }
            $this->autoJoinedGamesScheduledForDeletion[]= $autoJoinedGame;
            $autoJoinedGame->setAutoJoinGroup(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Group is new, it will return
     * an empty collection; or if this Group has previously
     * been saved, it will retrieve related AutoJoinedGames from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Group.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildGame[] List of ChildGame objects
     */
    public function getAutoJoinedGamesJoinOwner(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildGameQuery::create(null, $criteria);
        $query->joinWith('Owner', $joinBehavior);

        return $this->getAutoJoinedGames($query, $con);
    }

    /**
     * Clears out the collGameGroups collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addGameGroups()
     */
    public function clearGameGroups()
    {
        $this->collGameGroups = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collGameGroups collection loaded partially.
     */
    public function resetPartialGameGroups($v = true)
    {
        $this->collGameGroupsPartial = $v;
    }

    /**
     * Initializes the collGameGroups collection.
     *
     * By default this just sets the collGameGroups collection to an empty array (like clearcollGameGroups());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initGameGroups($overrideExisting = true)
    {
        if (null !== $this->collGameGroups && !$overrideExisting) {
            return;
        }

        $collectionClassName = GameGroupTableMap::getTableMap()->getCollectionClassName();

        $this->collGameGroups = new $collectionClassName;
        $this->collGameGroups->setModel('\GameGroup');
    }

    /**
     * Gets an array of ChildGameGroup objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGroup is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildGameGroup[] List of ChildGameGroup objects
     * @throws PropelException
     */
    public function getGameGroups(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collGameGroupsPartial && !$this->isNew();
        if (null === $this->collGameGroups || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collGameGroups) {
                // return empty collection
                $this->initGameGroups();
            } else {
                $collGameGroups = ChildGameGroupQuery::create(null, $criteria)
                    ->filterByGroup($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collGameGroupsPartial && count($collGameGroups)) {
                        $this->initGameGroups(false);

                        foreach ($collGameGroups as $obj) {
                            if (false == $this->collGameGroups->contains($obj)) {
                                $this->collGameGroups->append($obj);
                            }
                        }

                        $this->collGameGroupsPartial = true;
                    }

                    return $collGameGroups;
                }

                if ($partial && $this->collGameGroups) {
                    foreach ($this->collGameGroups as $obj) {
                        if ($obj->isNew()) {
                            $collGameGroups[] = $obj;
                        }
                    }
                }

                $this->collGameGroups = $collGameGroups;
                $this->collGameGroupsPartial = false;
            }
        }

        return $this->collGameGroups;
    }

    /**
     * Sets a collection of ChildGameGroup objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $gameGroups A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildGroup The current object (for fluent API support)
     */
    public function setGameGroups(Collection $gameGroups, ConnectionInterface $con = null)
    {
        /** @var ChildGameGroup[] $gameGroupsToDelete */
        $gameGroupsToDelete = $this->getGameGroups(new Criteria(), $con)->diff($gameGroups);

        
        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->gameGroupsScheduledForDeletion = clone $gameGroupsToDelete;

        foreach ($gameGroupsToDelete as $gameGroupRemoved) {
            $gameGroupRemoved->setGroup(null);
        }

        $this->collGameGroups = null;
        foreach ($gameGroups as $gameGroup) {
            $this->addGameGroup($gameGroup);
        }

        $this->collGameGroups = $gameGroups;
        $this->collGameGroupsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related GameGroup objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related GameGroup objects.
     * @throws PropelException
     */
    public function countGameGroups(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collGameGroupsPartial && !$this->isNew();
        if (null === $this->collGameGroups || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGameGroups) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getGameGroups());
            }

            $query = ChildGameGroupQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGroup($this)
                ->count($con);
        }

        return count($this->collGameGroups);
    }

    /**
     * Method called to associate a ChildGameGroup object to this object
     * through the ChildGameGroup foreign key attribute.
     *
     * @param  ChildGameGroup $l ChildGameGroup
     * @return $this|\Group The current object (for fluent API support)
     */
    public function addGameGroup(ChildGameGroup $l)
    {
        if ($this->collGameGroups === null) {
            $this->initGameGroups();
            $this->collGameGroupsPartial = true;
        }

        if (!$this->collGameGroups->contains($l)) {
            $this->doAddGameGroup($l);

            if ($this->gameGroupsScheduledForDeletion and $this->gameGroupsScheduledForDeletion->contains($l)) {
                $this->gameGroupsScheduledForDeletion->remove($this->gameGroupsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildGameGroup $gameGroup The ChildGameGroup object to add.
     */
    protected function doAddGameGroup(ChildGameGroup $gameGroup)
    {
        $this->collGameGroups[]= $gameGroup;
        $gameGroup->setGroup($this);
    }

    /**
     * @param  ChildGameGroup $gameGroup The ChildGameGroup object to remove.
     * @return $this|ChildGroup The current object (for fluent API support)
     */
    public function removeGameGroup(ChildGameGroup $gameGroup)
    {
        if ($this->getGameGroups()->contains($gameGroup)) {
            $pos = $this->collGameGroups->search($gameGroup);
            $this->collGameGroups->remove($pos);
            if (null === $this->gameGroupsScheduledForDeletion) {
                $this->gameGroupsScheduledForDeletion = clone $this->collGameGroups;
                $this->gameGroupsScheduledForDeletion->clear();
            }
            $this->gameGroupsScheduledForDeletion[]= clone $gameGroup;
            $gameGroup->setGroup(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Group is new, it will return
     * an empty collection; or if this Group has previously
     * been saved, it will retrieve related GameGroups from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Group.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildGameGroup[] List of ChildGameGroup objects
     */
    public function getGameGroupsJoinGame(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildGameGroupQuery::create(null, $criteria);
        $query->joinWith('Game', $joinBehavior);

        return $this->getGameGroups($query, $con);
    }

    /**
     * Clears out the collPlayerGroups collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerGroups()
     */
    public function clearPlayerGroups()
    {
        $this->collPlayerGroups = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerGroups collection loaded partially.
     */
    public function resetPartialPlayerGroups($v = true)
    {
        $this->collPlayerGroupsPartial = $v;
    }

    /**
     * Initializes the collPlayerGroups collection.
     *
     * By default this just sets the collPlayerGroups collection to an empty array (like clearcollPlayerGroups());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerGroups($overrideExisting = true)
    {
        if (null !== $this->collPlayerGroups && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerGroupTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerGroups = new $collectionClassName;
        $this->collPlayerGroups->setModel('\PlayerGroup');
    }

    /**
     * Gets an array of ChildPlayerGroup objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGroup is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerGroup[] List of ChildPlayerGroup objects
     * @throws PropelException
     */
    public function getPlayerGroups(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerGroupsPartial && !$this->isNew();
        if (null === $this->collPlayerGroups || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerGroups) {
                // return empty collection
                $this->initPlayerGroups();
            } else {
                $collPlayerGroups = ChildPlayerGroupQuery::create(null, $criteria)
                    ->filterByGroup($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerGroupsPartial && count($collPlayerGroups)) {
                        $this->initPlayerGroups(false);

                        foreach ($collPlayerGroups as $obj) {
                            if (false == $this->collPlayerGroups->contains($obj)) {
                                $this->collPlayerGroups->append($obj);
                            }
                        }

                        $this->collPlayerGroupsPartial = true;
                    }

                    return $collPlayerGroups;
                }

                if ($partial && $this->collPlayerGroups) {
                    foreach ($this->collPlayerGroups as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerGroups[] = $obj;
                        }
                    }
                }

                $this->collPlayerGroups = $collPlayerGroups;
                $this->collPlayerGroupsPartial = false;
            }
        }

        return $this->collPlayerGroups;
    }

    /**
     * Sets a collection of ChildPlayerGroup objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerGroups A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildGroup The current object (for fluent API support)
     */
    public function setPlayerGroups(Collection $playerGroups, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerGroup[] $playerGroupsToDelete */
        $playerGroupsToDelete = $this->getPlayerGroups(new Criteria(), $con)->diff($playerGroups);

        
        $this->playerGroupsScheduledForDeletion = $playerGroupsToDelete;

        foreach ($playerGroupsToDelete as $playerGroupRemoved) {
            $playerGroupRemoved->setGroup(null);
        }

        $this->collPlayerGroups = null;
        foreach ($playerGroups as $playerGroup) {
            $this->addPlayerGroup($playerGroup);
        }

        $this->collPlayerGroups = $playerGroups;
        $this->collPlayerGroupsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PlayerGroup objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PlayerGroup objects.
     * @throws PropelException
     */
    public function countPlayerGroups(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerGroupsPartial && !$this->isNew();
        if (null === $this->collPlayerGroups || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerGroups) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerGroups());
            }

            $query = ChildPlayerGroupQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGroup($this)
                ->count($con);
        }

        return count($this->collPlayerGroups);
    }

    /**
     * Method called to associate a ChildPlayerGroup object to this object
     * through the ChildPlayerGroup foreign key attribute.
     *
     * @param  ChildPlayerGroup $l ChildPlayerGroup
     * @return $this|\Group The current object (for fluent API support)
     */
    public function addPlayerGroup(ChildPlayerGroup $l)
    {
        if ($this->collPlayerGroups === null) {
            $this->initPlayerGroups();
            $this->collPlayerGroupsPartial = true;
        }

        if (!$this->collPlayerGroups->contains($l)) {
            $this->doAddPlayerGroup($l);

            if ($this->playerGroupsScheduledForDeletion and $this->playerGroupsScheduledForDeletion->contains($l)) {
                $this->playerGroupsScheduledForDeletion->remove($this->playerGroupsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerGroup $playerGroup The ChildPlayerGroup object to add.
     */
    protected function doAddPlayerGroup(ChildPlayerGroup $playerGroup)
    {
        $this->collPlayerGroups[]= $playerGroup;
        $playerGroup->setGroup($this);
    }

    /**
     * @param  ChildPlayerGroup $playerGroup The ChildPlayerGroup object to remove.
     * @return $this|ChildGroup The current object (for fluent API support)
     */
    public function removePlayerGroup(ChildPlayerGroup $playerGroup)
    {
        if ($this->getPlayerGroups()->contains($playerGroup)) {
            $pos = $this->collPlayerGroups->search($playerGroup);
            $this->collPlayerGroups->remove($pos);
            if (null === $this->playerGroupsScheduledForDeletion) {
                $this->playerGroupsScheduledForDeletion = clone $this->collPlayerGroups;
                $this->playerGroupsScheduledForDeletion->clear();
            }
            $this->playerGroupsScheduledForDeletion[]= clone $playerGroup;
            $playerGroup->setGroup(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Group is new, it will return
     * an empty collection; or if this Group has previously
     * been saved, it will retrieve related PlayerGroups from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Group.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerGroup[] List of ChildPlayerGroup objects
     */
    public function getPlayerGroupsJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerGroupQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getPlayerGroups($query, $con);
    }

    /**
     * Clears out the collPresetGroups collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPresetGroups()
     */
    public function clearPresetGroups()
    {
        $this->collPresetGroups = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPresetGroups collection loaded partially.
     */
    public function resetPartialPresetGroups($v = true)
    {
        $this->collPresetGroupsPartial = $v;
    }

    /**
     * Initializes the collPresetGroups collection.
     *
     * By default this just sets the collPresetGroups collection to an empty array (like clearcollPresetGroups());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPresetGroups($overrideExisting = true)
    {
        if (null !== $this->collPresetGroups && !$overrideExisting) {
            return;
        }

        $collectionClassName = PresetGroupTableMap::getTableMap()->getCollectionClassName();

        $this->collPresetGroups = new $collectionClassName;
        $this->collPresetGroups->setModel('\PresetGroup');
    }

    /**
     * Gets an array of ChildPresetGroup objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGroup is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPresetGroup[] List of ChildPresetGroup objects
     * @throws PropelException
     */
    public function getPresetGroups(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPresetGroupsPartial && !$this->isNew();
        if (null === $this->collPresetGroups || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPresetGroups) {
                // return empty collection
                $this->initPresetGroups();
            } else {
                $collPresetGroups = ChildPresetGroupQuery::create(null, $criteria)
                    ->filterByGroup($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPresetGroupsPartial && count($collPresetGroups)) {
                        $this->initPresetGroups(false);

                        foreach ($collPresetGroups as $obj) {
                            if (false == $this->collPresetGroups->contains($obj)) {
                                $this->collPresetGroups->append($obj);
                            }
                        }

                        $this->collPresetGroupsPartial = true;
                    }

                    return $collPresetGroups;
                }

                if ($partial && $this->collPresetGroups) {
                    foreach ($this->collPresetGroups as $obj) {
                        if ($obj->isNew()) {
                            $collPresetGroups[] = $obj;
                        }
                    }
                }

                $this->collPresetGroups = $collPresetGroups;
                $this->collPresetGroupsPartial = false;
            }
        }

        return $this->collPresetGroups;
    }

    /**
     * Sets a collection of ChildPresetGroup objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $presetGroups A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildGroup The current object (for fluent API support)
     */
    public function setPresetGroups(Collection $presetGroups, ConnectionInterface $con = null)
    {
        /** @var ChildPresetGroup[] $presetGroupsToDelete */
        $presetGroupsToDelete = $this->getPresetGroups(new Criteria(), $con)->diff($presetGroups);

        
        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->presetGroupsScheduledForDeletion = clone $presetGroupsToDelete;

        foreach ($presetGroupsToDelete as $presetGroupRemoved) {
            $presetGroupRemoved->setGroup(null);
        }

        $this->collPresetGroups = null;
        foreach ($presetGroups as $presetGroup) {
            $this->addPresetGroup($presetGroup);
        }

        $this->collPresetGroups = $presetGroups;
        $this->collPresetGroupsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PresetGroup objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PresetGroup objects.
     * @throws PropelException
     */
    public function countPresetGroups(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPresetGroupsPartial && !$this->isNew();
        if (null === $this->collPresetGroups || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPresetGroups) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPresetGroups());
            }

            $query = ChildPresetGroupQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGroup($this)
                ->count($con);
        }

        return count($this->collPresetGroups);
    }

    /**
     * Method called to associate a ChildPresetGroup object to this object
     * through the ChildPresetGroup foreign key attribute.
     *
     * @param  ChildPresetGroup $l ChildPresetGroup
     * @return $this|\Group The current object (for fluent API support)
     */
    public function addPresetGroup(ChildPresetGroup $l)
    {
        if ($this->collPresetGroups === null) {
            $this->initPresetGroups();
            $this->collPresetGroupsPartial = true;
        }

        if (!$this->collPresetGroups->contains($l)) {
            $this->doAddPresetGroup($l);

            if ($this->presetGroupsScheduledForDeletion and $this->presetGroupsScheduledForDeletion->contains($l)) {
                $this->presetGroupsScheduledForDeletion->remove($this->presetGroupsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPresetGroup $presetGroup The ChildPresetGroup object to add.
     */
    protected function doAddPresetGroup(ChildPresetGroup $presetGroup)
    {
        $this->collPresetGroups[]= $presetGroup;
        $presetGroup->setGroup($this);
    }

    /**
     * @param  ChildPresetGroup $presetGroup The ChildPresetGroup object to remove.
     * @return $this|ChildGroup The current object (for fluent API support)
     */
    public function removePresetGroup(ChildPresetGroup $presetGroup)
    {
        if ($this->getPresetGroups()->contains($presetGroup)) {
            $pos = $this->collPresetGroups->search($presetGroup);
            $this->collPresetGroups->remove($pos);
            if (null === $this->presetGroupsScheduledForDeletion) {
                $this->presetGroupsScheduledForDeletion = clone $this->collPresetGroups;
                $this->presetGroupsScheduledForDeletion->clear();
            }
            $this->presetGroupsScheduledForDeletion[]= clone $presetGroup;
            $presetGroup->setGroup(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Group is new, it will return
     * an empty collection; or if this Group has previously
     * been saved, it will retrieve related PresetGroups from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Group.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPresetGroup[] List of ChildPresetGroup objects
     */
    public function getPresetGroupsJoinPreset(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPresetGroupQuery::create(null, $criteria);
        $query->joinWith('Preset', $joinBehavior);

        return $this->getPresetGroups($query, $con);
    }

    /**
     * Clears out the collSettings collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSettings()
     */
    public function clearSettings()
    {
        $this->collSettings = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collSettings collection loaded partially.
     */
    public function resetPartialSettings($v = true)
    {
        $this->collSettingsPartial = $v;
    }

    /**
     * Initializes the collSettings collection.
     *
     * By default this just sets the collSettings collection to an empty array (like clearcollSettings());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSettings($overrideExisting = true)
    {
        if (null !== $this->collSettings && !$overrideExisting) {
            return;
        }

        $collectionClassName = SettingTableMap::getTableMap()->getCollectionClassName();

        $this->collSettings = new $collectionClassName;
        $this->collSettings->setModel('\Setting');
    }

    /**
     * Gets an array of ChildSetting objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGroup is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildSetting[] List of ChildSetting objects
     * @throws PropelException
     */
    public function getSettings(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collSettingsPartial && !$this->isNew();
        if (null === $this->collSettings || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSettings) {
                // return empty collection
                $this->initSettings();
            } else {
                $collSettings = ChildSettingQuery::create(null, $criteria)
                    ->filterByAutoJoinGroup($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSettingsPartial && count($collSettings)) {
                        $this->initSettings(false);

                        foreach ($collSettings as $obj) {
                            if (false == $this->collSettings->contains($obj)) {
                                $this->collSettings->append($obj);
                            }
                        }

                        $this->collSettingsPartial = true;
                    }

                    return $collSettings;
                }

                if ($partial && $this->collSettings) {
                    foreach ($this->collSettings as $obj) {
                        if ($obj->isNew()) {
                            $collSettings[] = $obj;
                        }
                    }
                }

                $this->collSettings = $collSettings;
                $this->collSettingsPartial = false;
            }
        }

        return $this->collSettings;
    }

    /**
     * Sets a collection of ChildSetting objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $settings A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildGroup The current object (for fluent API support)
     */
    public function setSettings(Collection $settings, ConnectionInterface $con = null)
    {
        /** @var ChildSetting[] $settingsToDelete */
        $settingsToDelete = $this->getSettings(new Criteria(), $con)->diff($settings);

        
        $this->settingsScheduledForDeletion = $settingsToDelete;

        foreach ($settingsToDelete as $settingRemoved) {
            $settingRemoved->setAutoJoinGroup(null);
        }

        $this->collSettings = null;
        foreach ($settings as $setting) {
            $this->addSetting($setting);
        }

        $this->collSettings = $settings;
        $this->collSettingsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Setting objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Setting objects.
     * @throws PropelException
     */
    public function countSettings(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collSettingsPartial && !$this->isNew();
        if (null === $this->collSettings || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSettings) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSettings());
            }

            $query = ChildSettingQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByAutoJoinGroup($this)
                ->count($con);
        }

        return count($this->collSettings);
    }

    /**
     * Method called to associate a ChildSetting object to this object
     * through the ChildSetting foreign key attribute.
     *
     * @param  ChildSetting $l ChildSetting
     * @return $this|\Group The current object (for fluent API support)
     */
    public function addSetting(ChildSetting $l)
    {
        if ($this->collSettings === null) {
            $this->initSettings();
            $this->collSettingsPartial = true;
        }

        if (!$this->collSettings->contains($l)) {
            $this->doAddSetting($l);

            if ($this->settingsScheduledForDeletion and $this->settingsScheduledForDeletion->contains($l)) {
                $this->settingsScheduledForDeletion->remove($this->settingsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildSetting $setting The ChildSetting object to add.
     */
    protected function doAddSetting(ChildSetting $setting)
    {
        $this->collSettings[]= $setting;
        $setting->setAutoJoinGroup($this);
    }

    /**
     * @param  ChildSetting $setting The ChildSetting object to remove.
     * @return $this|ChildGroup The current object (for fluent API support)
     */
    public function removeSetting(ChildSetting $setting)
    {
        if ($this->getSettings()->contains($setting)) {
            $pos = $this->collSettings->search($setting);
            $this->collSettings->remove($pos);
            if (null === $this->settingsScheduledForDeletion) {
                $this->settingsScheduledForDeletion = clone $this->collSettings;
                $this->settingsScheduledForDeletion->clear();
            }
            $this->settingsScheduledForDeletion[]= $setting;
            $setting->setAutoJoinGroup(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Group is new, it will return
     * an empty collection; or if this Group has previously
     * been saved, it will retrieve related Settings from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Group.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildSetting[] List of ChildSetting objects
     */
    public function getSettingsJoinPreset(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSettingQuery::create(null, $criteria);
        $query->joinWith('Preset', $joinBehavior);

        return $this->getSettings($query, $con);
    }

    /**
     * Clears out the collGames collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addGames()
     */
    public function clearGames()
    {
        $this->collGames = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collGames crossRef collection.
     *
     * By default this just sets the collGames collection to an empty collection (like clearGames());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initGames()
    {
        $collectionClassName = GameGroupTableMap::getTableMap()->getCollectionClassName();

        $this->collGames = new $collectionClassName;
        $this->collGamesPartial = true;
        $this->collGames->setModel('\Game');
    }

    /**
     * Checks if the collGames collection is loaded.
     *
     * @return bool
     */
    public function isGamesLoaded()
    {
        return null !== $this->collGames;
    }

    /**
     * Gets a collection of ChildGame objects related by a many-to-many relationship
     * to the current object by way of the game_groups cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGroup is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildGame[] List of ChildGame objects
     */
    public function getGames(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collGamesPartial && !$this->isNew();
        if (null === $this->collGames || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collGames) {
                    $this->initGames();
                }
            } else {

                $query = ChildGameQuery::create(null, $criteria)
                    ->filterByGroup($this);
                $collGames = $query->find($con);
                if (null !== $criteria) {
                    return $collGames;
                }

                if ($partial && $this->collGames) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collGames as $obj) {
                        if (!$collGames->contains($obj)) {
                            $collGames[] = $obj;
                        }
                    }
                }

                $this->collGames = $collGames;
                $this->collGamesPartial = false;
            }
        }

        return $this->collGames;
    }

    /**
     * Sets a collection of Game objects related by a many-to-many relationship
     * to the current object by way of the game_groups cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $games A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildGroup The current object (for fluent API support)
     */
    public function setGames(Collection $games, ConnectionInterface $con = null)
    {
        $this->clearGames();
        $currentGames = $this->getGames();

        $gamesScheduledForDeletion = $currentGames->diff($games);

        foreach ($gamesScheduledForDeletion as $toDelete) {
            $this->removeGame($toDelete);
        }

        foreach ($games as $game) {
            if (!$currentGames->contains($game)) {
                $this->doAddGame($game);
            }
        }

        $this->collGamesPartial = false;
        $this->collGames = $games;

        return $this;
    }

    /**
     * Gets the number of Game objects related by a many-to-many relationship
     * to the current object by way of the game_groups cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Game objects
     */
    public function countGames(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collGamesPartial && !$this->isNew();
        if (null === $this->collGames || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGames) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getGames());
                }

                $query = ChildGameQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByGroup($this)
                    ->count($con);
            }
        } else {
            return count($this->collGames);
        }
    }

    /**
     * Associate a ChildGame to this object
     * through the game_groups cross reference table.
     * 
     * @param ChildGame $game
     * @return ChildGroup The current object (for fluent API support)
     */
    public function addGame(ChildGame $game)
    {
        if ($this->collGames === null) {
            $this->initGames();
        }

        if (!$this->getGames()->contains($game)) {
            // only add it if the **same** object is not already associated
            $this->collGames->push($game);
            $this->doAddGame($game);
        }

        return $this;
    }

    /**
     * 
     * @param ChildGame $game
     */
    protected function doAddGame(ChildGame $game)
    {
        $gameGroup = new ChildGameGroup();

        $gameGroup->setGame($game);

        $gameGroup->setGroup($this);

        $this->addGameGroup($gameGroup);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$game->isGroupsLoaded()) {
            $game->initGroups();
            $game->getGroups()->push($this);
        } elseif (!$game->getGroups()->contains($this)) {
            $game->getGroups()->push($this);
        }

    }

    /**
     * Remove game of this object
     * through the game_groups cross reference table.
     * 
     * @param ChildGame $game
     * @return ChildGroup The current object (for fluent API support)
     */
    public function removeGame(ChildGame $game)
    {
        if ($this->getGames()->contains($game)) { $gameGroup = new ChildGameGroup();

            $gameGroup->setGame($game);
            if ($game->isGroupsLoaded()) {
                //remove the back reference if available
                $game->getGroups()->removeObject($this);
            }

            $gameGroup->setGroup($this);
            $this->removeGameGroup(clone $gameGroup);
            $gameGroup->clear();

            $this->collGames->remove($this->collGames->search($game));
            
            if (null === $this->gamesScheduledForDeletion) {
                $this->gamesScheduledForDeletion = clone $this->collGames;
                $this->gamesScheduledForDeletion->clear();
            }

            $this->gamesScheduledForDeletion->push($game);
        }


        return $this;
    }

    /**
     * Clears out the collPresets collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPresets()
     */
    public function clearPresets()
    {
        $this->collPresets = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collPresets crossRef collection.
     *
     * By default this just sets the collPresets collection to an empty collection (like clearPresets());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPresets()
    {
        $collectionClassName = PresetGroupTableMap::getTableMap()->getCollectionClassName();

        $this->collPresets = new $collectionClassName;
        $this->collPresetsPartial = true;
        $this->collPresets->setModel('\Preset');
    }

    /**
     * Checks if the collPresets collection is loaded.
     *
     * @return bool
     */
    public function isPresetsLoaded()
    {
        return null !== $this->collPresets;
    }

    /**
     * Gets a collection of ChildPreset objects related by a many-to-many relationship
     * to the current object by way of the preset_groups cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGroup is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildPreset[] List of ChildPreset objects
     */
    public function getPresets(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPresetsPartial && !$this->isNew();
        if (null === $this->collPresets || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collPresets) {
                    $this->initPresets();
                }
            } else {

                $query = ChildPresetQuery::create(null, $criteria)
                    ->filterByGroup($this);
                $collPresets = $query->find($con);
                if (null !== $criteria) {
                    return $collPresets;
                }

                if ($partial && $this->collPresets) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collPresets as $obj) {
                        if (!$collPresets->contains($obj)) {
                            $collPresets[] = $obj;
                        }
                    }
                }

                $this->collPresets = $collPresets;
                $this->collPresetsPartial = false;
            }
        }

        return $this->collPresets;
    }

    /**
     * Sets a collection of Preset objects related by a many-to-many relationship
     * to the current object by way of the preset_groups cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $presets A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildGroup The current object (for fluent API support)
     */
    public function setPresets(Collection $presets, ConnectionInterface $con = null)
    {
        $this->clearPresets();
        $currentPresets = $this->getPresets();

        $presetsScheduledForDeletion = $currentPresets->diff($presets);

        foreach ($presetsScheduledForDeletion as $toDelete) {
            $this->removePreset($toDelete);
        }

        foreach ($presets as $preset) {
            if (!$currentPresets->contains($preset)) {
                $this->doAddPreset($preset);
            }
        }

        $this->collPresetsPartial = false;
        $this->collPresets = $presets;

        return $this;
    }

    /**
     * Gets the number of Preset objects related by a many-to-many relationship
     * to the current object by way of the preset_groups cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Preset objects
     */
    public function countPresets(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPresetsPartial && !$this->isNew();
        if (null === $this->collPresets || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPresets) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getPresets());
                }

                $query = ChildPresetQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByGroup($this)
                    ->count($con);
            }
        } else {
            return count($this->collPresets);
        }
    }

    /**
     * Associate a ChildPreset to this object
     * through the preset_groups cross reference table.
     * 
     * @param ChildPreset $preset
     * @return ChildGroup The current object (for fluent API support)
     */
    public function addPreset(ChildPreset $preset)
    {
        if ($this->collPresets === null) {
            $this->initPresets();
        }

        if (!$this->getPresets()->contains($preset)) {
            // only add it if the **same** object is not already associated
            $this->collPresets->push($preset);
            $this->doAddPreset($preset);
        }

        return $this;
    }

    /**
     * 
     * @param ChildPreset $preset
     */
    protected function doAddPreset(ChildPreset $preset)
    {
        $presetGroup = new ChildPresetGroup();

        $presetGroup->setPreset($preset);

        $presetGroup->setGroup($this);

        $this->addPresetGroup($presetGroup);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$preset->isGroupsLoaded()) {
            $preset->initGroups();
            $preset->getGroups()->push($this);
        } elseif (!$preset->getGroups()->contains($this)) {
            $preset->getGroups()->push($this);
        }

    }

    /**
     * Remove preset of this object
     * through the preset_groups cross reference table.
     * 
     * @param ChildPreset $preset
     * @return ChildGroup The current object (for fluent API support)
     */
    public function removePreset(ChildPreset $preset)
    {
        if ($this->getPresets()->contains($preset)) { $presetGroup = new ChildPresetGroup();

            $presetGroup->setPreset($preset);
            if ($preset->isGroupsLoaded()) {
                //remove the back reference if available
                $preset->getGroups()->removeObject($this);
            }

            $presetGroup->setGroup($this);
            $this->removePresetGroup(clone $presetGroup);
            $presetGroup->clear();

            $this->collPresets->remove($this->collPresets->search($preset));
            
            if (null === $this->presetsScheduledForDeletion) {
                $this->presetsScheduledForDeletion = clone $this->collPresets;
                $this->presetsScheduledForDeletion->clear();
            }

            $this->presetsScheduledForDeletion->push($preset);
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
        $this->id = null;
        $this->name = null;
        $this->permissions = null;
        $this->rank = null;
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
            if ($this->collAutoJoinedGames) {
                foreach ($this->collAutoJoinedGames as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGameGroups) {
                foreach ($this->collGameGroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerGroups) {
                foreach ($this->collPlayerGroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPresetGroups) {
                foreach ($this->collPresetGroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSettings) {
                foreach ($this->collSettings as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGames) {
                foreach ($this->collGames as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPresets) {
                foreach ($this->collPresets as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collAutoJoinedGames = null;
        $this->collGameGroups = null;
        $this->collPlayerGroups = null;
        $this->collPresetGroups = null;
        $this->collSettings = null;
        $this->collGames = null;
        $this->collPresets = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(GroupTableMap::DEFAULT_STRING_FORMAT);
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
