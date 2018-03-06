<?php

namespace Base;

use \ActionLog as ChildActionLog;
use \ActionLogQuery as ChildActionLogQuery;
use \CircuitPlayer as ChildCircuitPlayer;
use \CircuitPlayerQuery as ChildCircuitPlayerQuery;
use \Game as ChildGame;
use \GameActionLog as ChildGameActionLog;
use \GameActionLogQuery as ChildGameActionLogQuery;
use \GameGroup as ChildGameGroup;
use \GameGroupQuery as ChildGameGroupQuery;
use \GamePlayerGroup as ChildGamePlayerGroup;
use \GamePlayerGroupQuery as ChildGamePlayerGroupQuery;
use \GameQuery as ChildGameQuery;
use \Group as ChildGroup;
use \GroupQuery as ChildGroupQuery;
use \PlayerGroup as ChildPlayerGroup;
use \PlayerGroupQuery as ChildPlayerGroupQuery;
use \User as ChildUser;
use \UserGame as ChildUserGame;
use \UserGameQuery as ChildUserGameQuery;
use \UserQuery as ChildUserQuery;
use \Exception;
use \PDO;
use Map\CircuitPlayerTableMap;
use Map\GameActionLogTableMap;
use Map\GameGroupTableMap;
use Map\GamePlayerGroupTableMap;
use Map\GameTableMap;
use Map\UserGameTableMap;
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
 * Base class that represents a row from the 'games' table.
 *
 * 
 *
 * @package    propel.generator..Base
 */
abstract class Game implements ActiveRecordInterface 
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\GameTableMap';


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
     * The value for the active field.
     * 
     * Note: this column has a database default value of: true
     * @var        boolean
     */
    protected $active;

    /**
     * The value for the owner_id field.
     * 
     * @var        int
     */
    protected $owner_id;

    /**
     * The value for the started field.
     * 
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $started;

    /**
     * The value for the paused field.
     * 
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $paused;

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
     * The value for the auto_join_group_id field.
     * 
     * @var        int
     */
    protected $auto_join_group_id;

    /**
     * The value for the auto_place field.
     * 
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $auto_place;

    /**
     * The value for the duplicate_game_on_complete field.
     * 
     * Note: this column has a database default value of: true
     * @var        boolean
     */
    protected $duplicate_game_on_complete;

    /**
     * @var        ChildUser
     */
    protected $aOwner;

    /**
     * @var        ChildGroup
     */
    protected $aAutoJoinGroup;

    /**
     * @var        ObjectCollection|ChildUserGame[] Collection to store aggregation of ChildUserGame objects.
     */
    protected $collUserGames;
    protected $collUserGamesPartial;

    /**
     * @var        ObjectCollection|ChildGameGroup[] Collection to store aggregation of ChildGameGroup objects.
     */
    protected $collGameGroups;
    protected $collGameGroupsPartial;

    /**
     * @var        ObjectCollection|ChildCircuitPlayer[] Collection to store aggregation of ChildCircuitPlayer objects.
     */
    protected $collCircuitPlayers;
    protected $collCircuitPlayersPartial;

    /**
     * @var        ObjectCollection|ChildGamePlayerGroup[] Collection to store aggregation of ChildGamePlayerGroup objects.
     */
    protected $collGamePlayerGroups;
    protected $collGamePlayerGroupsPartial;

    /**
     * @var        ObjectCollection|ChildGameActionLog[] Collection to store aggregation of ChildGameActionLog objects.
     */
    protected $collGameActionLogs;
    protected $collGameActionLogsPartial;

    /**
     * @var        ObjectCollection|ChildUser[] Cross Collection to store aggregation of ChildUser objects.
     */
    protected $collUsers;

    /**
     * @var bool
     */
    protected $collUsersPartial;

    /**
     * @var        ObjectCollection|ChildGroup[] Cross Collection to store aggregation of ChildGroup objects.
     */
    protected $collGroups;

    /**
     * @var bool
     */
    protected $collGroupsPartial;

    /**
     * @var        ObjectCollection|ChildPlayerGroup[] Cross Collection to store aggregation of ChildPlayerGroup objects.
     */
    protected $collPlayerGroups;

    /**
     * @var bool
     */
    protected $collPlayerGroupsPartial;

    /**
     * @var        ObjectCollection|ChildActionLog[] Cross Collection to store aggregation of ChildActionLog objects.
     */
    protected $collActionLogs;

    /**
     * @var bool
     */
    protected $collActionLogsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUser[]
     */
    protected $usersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildGroup[]
     */
    protected $groupsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerGroup[]
     */
    protected $playerGroupsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildActionLog[]
     */
    protected $actionLogsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserGame[]
     */
    protected $userGamesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildGameGroup[]
     */
    protected $gameGroupsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCircuitPlayer[]
     */
    protected $circuitPlayersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildGamePlayerGroup[]
     */
    protected $gamePlayerGroupsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildGameActionLog[]
     */
    protected $gameActionLogsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->active = true;
        $this->started = false;
        $this->paused = false;
        $this->invite = true;
        $this->request_invite = true;
        $this->auto_place = false;
        $this->duplicate_game_on_complete = true;
    }

    /**
     * Initializes internal state of Base\Game object.
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
     * Compares this with another <code>Game</code> instance.  If
     * <code>obj</code> is an instance of <code>Game</code>, delegates to
     * <code>equals(Game)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Game The current object, for fluid interface
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
     * Get the [active] column value.
     * 
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Get the [active] column value.
     * 
     * @return boolean
     */
    public function isActive()
    {
        return $this->getActive();
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
     * Get the [started] column value.
     * 
     * @return boolean
     */
    public function getStarted()
    {
        return $this->started;
    }

    /**
     * Get the [started] column value.
     * 
     * @return boolean
     */
    public function isStarted()
    {
        return $this->getStarted();
    }

    /**
     * Get the [paused] column value.
     * 
     * @return boolean
     */
    public function getPaused()
    {
        return $this->paused;
    }

    /**
     * Get the [paused] column value.
     * 
     * @return boolean
     */
    public function isPaused()
    {
        return $this->getPaused();
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
     * Get the [auto_join_group_id] column value.
     * 
     * @return int
     */
    public function getAutoJoinGroupId()
    {
        return $this->auto_join_group_id;
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
     * Get the [duplicate_game_on_complete] column value.
     * 
     * @return boolean
     */
    public function getDuplicateGameOnComplete()
    {
        return $this->duplicate_game_on_complete;
    }

    /**
     * Get the [duplicate_game_on_complete] column value.
     * 
     * @return boolean
     */
    public function isDuplicateGameOnComplete()
    {
        return $this->getDuplicateGameOnComplete();
    }

    /**
     * Set the value of [id] column.
     * 
     * @param int $v new value
     * @return $this|\Game The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[GameTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     * 
     * @param string $v new value
     * @return $this|\Game The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[GameTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Sets the value of the [active] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * 
     * @param  boolean|integer|string $v The new value
     * @return $this|\Game The current object (for fluent API support)
     */
    public function setActive($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->active !== $v) {
            $this->active = $v;
            $this->modifiedColumns[GameTableMap::COL_ACTIVE] = true;
        }

        return $this;
    } // setActive()

    /**
     * Set the value of [owner_id] column.
     * 
     * @param int $v new value
     * @return $this|\Game The current object (for fluent API support)
     */
    public function setOwnerId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->owner_id !== $v) {
            $this->owner_id = $v;
            $this->modifiedColumns[GameTableMap::COL_OWNER_ID] = true;
        }

        if ($this->aOwner !== null && $this->aOwner->getId() !== $v) {
            $this->aOwner = null;
        }

        return $this;
    } // setOwnerId()

    /**
     * Sets the value of the [started] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * 
     * @param  boolean|integer|string $v The new value
     * @return $this|\Game The current object (for fluent API support)
     */
    public function setStarted($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->started !== $v) {
            $this->started = $v;
            $this->modifiedColumns[GameTableMap::COL_STARTED] = true;
        }

        return $this;
    } // setStarted()

    /**
     * Sets the value of the [paused] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * 
     * @param  boolean|integer|string $v The new value
     * @return $this|\Game The current object (for fluent API support)
     */
    public function setPaused($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->paused !== $v) {
            $this->paused = $v;
            $this->modifiedColumns[GameTableMap::COL_PAUSED] = true;
        }

        return $this;
    } // setPaused()

    /**
     * Set the value of [rules] column.
     * 
     * @param string $v new value
     * @return $this|\Game The current object (for fluent API support)
     */
    public function setRules($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->rules !== $v) {
            $this->rules = $v;
            $this->modifiedColumns[GameTableMap::COL_RULES] = true;
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
     * @return $this|\Game The current object (for fluent API support)
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
            $this->modifiedColumns[GameTableMap::COL_INVITE] = true;
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
     * @return $this|\Game The current object (for fluent API support)
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
            $this->modifiedColumns[GameTableMap::COL_REQUEST_INVITE] = true;
        }

        return $this;
    } // setRequestInvite()

    /**
     * Set the value of [auto_join_group_id] column.
     * 
     * @param int $v new value
     * @return $this|\Game The current object (for fluent API support)
     */
    public function setAutoJoinGroupId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->auto_join_group_id !== $v) {
            $this->auto_join_group_id = $v;
            $this->modifiedColumns[GameTableMap::COL_AUTO_JOIN_GROUP_ID] = true;
        }

        if ($this->aAutoJoinGroup !== null && $this->aAutoJoinGroup->getId() !== $v) {
            $this->aAutoJoinGroup = null;
        }

        return $this;
    } // setAutoJoinGroupId()

    /**
     * Sets the value of the [auto_place] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * 
     * @param  boolean|integer|string $v The new value
     * @return $this|\Game The current object (for fluent API support)
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
            $this->modifiedColumns[GameTableMap::COL_AUTO_PLACE] = true;
        }

        return $this;
    } // setAutoPlace()

    /**
     * Sets the value of the [duplicate_game_on_complete] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * 
     * @param  boolean|integer|string $v The new value
     * @return $this|\Game The current object (for fluent API support)
     */
    public function setDuplicateGameOnComplete($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->duplicate_game_on_complete !== $v) {
            $this->duplicate_game_on_complete = $v;
            $this->modifiedColumns[GameTableMap::COL_DUPLICATE_GAME_ON_COMPLETE] = true;
        }

        return $this;
    } // setDuplicateGameOnComplete()

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
            if ($this->active !== true) {
                return false;
            }

            if ($this->started !== false) {
                return false;
            }

            if ($this->paused !== false) {
                return false;
            }

            if ($this->invite !== true) {
                return false;
            }

            if ($this->request_invite !== true) {
                return false;
            }

            if ($this->auto_place !== false) {
                return false;
            }

            if ($this->duplicate_game_on_complete !== true) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : GameTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : GameTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : GameTableMap::translateFieldName('Active', TableMap::TYPE_PHPNAME, $indexType)];
            $this->active = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : GameTableMap::translateFieldName('OwnerId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->owner_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : GameTableMap::translateFieldName('Started', TableMap::TYPE_PHPNAME, $indexType)];
            $this->started = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : GameTableMap::translateFieldName('Paused', TableMap::TYPE_PHPNAME, $indexType)];
            $this->paused = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : GameTableMap::translateFieldName('Rules', TableMap::TYPE_PHPNAME, $indexType)];
            $this->rules = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : GameTableMap::translateFieldName('Invite', TableMap::TYPE_PHPNAME, $indexType)];
            $this->invite = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : GameTableMap::translateFieldName('RequestInvite', TableMap::TYPE_PHPNAME, $indexType)];
            $this->request_invite = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : GameTableMap::translateFieldName('AutoJoinGroupId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->auto_join_group_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : GameTableMap::translateFieldName('AutoPlace', TableMap::TYPE_PHPNAME, $indexType)];
            $this->auto_place = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : GameTableMap::translateFieldName('DuplicateGameOnComplete', TableMap::TYPE_PHPNAME, $indexType)];
            $this->duplicate_game_on_complete = (null !== $col) ? (boolean) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 12; // 12 = GameTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Game'), 0, $e);
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
        if ($this->aAutoJoinGroup !== null && $this->auto_join_group_id !== $this->aAutoJoinGroup->getId()) {
            $this->aAutoJoinGroup = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(GameTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildGameQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aOwner = null;
            $this->aAutoJoinGroup = null;
            $this->collUserGames = null;

            $this->collGameGroups = null;

            $this->collCircuitPlayers = null;

            $this->collGamePlayerGroups = null;

            $this->collGameActionLogs = null;

            $this->collUsers = null;
            $this->collGroups = null;
            $this->collPlayerGroups = null;
            $this->collActionLogs = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Game::setDeleted()
     * @see Game::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(GameTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildGameQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(GameTableMap::DATABASE_NAME);
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
                GameTableMap::addInstanceToPool($this);
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

            if ($this->aAutoJoinGroup !== null) {
                if ($this->aAutoJoinGroup->isModified() || $this->aAutoJoinGroup->isNew()) {
                    $affectedRows += $this->aAutoJoinGroup->save($con);
                }
                $this->setAutoJoinGroup($this->aAutoJoinGroup);
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

            if ($this->usersScheduledForDeletion !== null) {
                if (!$this->usersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->usersScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \UserGameQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->usersScheduledForDeletion = null;
                }

            }

            if ($this->collUsers) {
                foreach ($this->collUsers as $user) {
                    if (!$user->isDeleted() && ($user->isNew() || $user->isModified())) {
                        $user->save($con);
                    }
                }
            }


            if ($this->groupsScheduledForDeletion !== null) {
                if (!$this->groupsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->groupsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \GameGroupQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->groupsScheduledForDeletion = null;
                }

            }

            if ($this->collGroups) {
                foreach ($this->collGroups as $group) {
                    if (!$group->isDeleted() && ($group->isNew() || $group->isModified())) {
                        $group->save($con);
                    }
                }
            }


            if ($this->playerGroupsScheduledForDeletion !== null) {
                if (!$this->playerGroupsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->playerGroupsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \GamePlayerGroupQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->playerGroupsScheduledForDeletion = null;
                }

            }

            if ($this->collPlayerGroups) {
                foreach ($this->collPlayerGroups as $playerGroup) {
                    if (!$playerGroup->isDeleted() && ($playerGroup->isNew() || $playerGroup->isModified())) {
                        $playerGroup->save($con);
                    }
                }
            }


            if ($this->actionLogsScheduledForDeletion !== null) {
                if (!$this->actionLogsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->actionLogsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \GameActionLogQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->actionLogsScheduledForDeletion = null;
                }

            }

            if ($this->collActionLogs) {
                foreach ($this->collActionLogs as $actionLog) {
                    if (!$actionLog->isDeleted() && ($actionLog->isNew() || $actionLog->isModified())) {
                        $actionLog->save($con);
                    }
                }
            }


            if ($this->userGamesScheduledForDeletion !== null) {
                if (!$this->userGamesScheduledForDeletion->isEmpty()) {
                    \UserGameQuery::create()
                        ->filterByPrimaryKeys($this->userGamesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userGamesScheduledForDeletion = null;
                }
            }

            if ($this->collUserGames !== null) {
                foreach ($this->collUserGames as $referrerFK) {
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

            if ($this->circuitPlayersScheduledForDeletion !== null) {
                if (!$this->circuitPlayersScheduledForDeletion->isEmpty()) {
                    \CircuitPlayerQuery::create()
                        ->filterByPrimaryKeys($this->circuitPlayersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->circuitPlayersScheduledForDeletion = null;
                }
            }

            if ($this->collCircuitPlayers !== null) {
                foreach ($this->collCircuitPlayers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->gamePlayerGroupsScheduledForDeletion !== null) {
                if (!$this->gamePlayerGroupsScheduledForDeletion->isEmpty()) {
                    \GamePlayerGroupQuery::create()
                        ->filterByPrimaryKeys($this->gamePlayerGroupsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->gamePlayerGroupsScheduledForDeletion = null;
                }
            }

            if ($this->collGamePlayerGroups !== null) {
                foreach ($this->collGamePlayerGroups as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->gameActionLogsScheduledForDeletion !== null) {
                if (!$this->gameActionLogsScheduledForDeletion->isEmpty()) {
                    \GameActionLogQuery::create()
                        ->filterByPrimaryKeys($this->gameActionLogsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->gameActionLogsScheduledForDeletion = null;
                }
            }

            if ($this->collGameActionLogs !== null) {
                foreach ($this->collGameActionLogs as $referrerFK) {
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

        $this->modifiedColumns[GameTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . GameTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(GameTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(GameTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(GameTableMap::COL_ACTIVE)) {
            $modifiedColumns[':p' . $index++]  = '`active`';
        }
        if ($this->isColumnModified(GameTableMap::COL_OWNER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`owner_id`';
        }
        if ($this->isColumnModified(GameTableMap::COL_STARTED)) {
            $modifiedColumns[':p' . $index++]  = '`started`';
        }
        if ($this->isColumnModified(GameTableMap::COL_PAUSED)) {
            $modifiedColumns[':p' . $index++]  = '`paused`';
        }
        if ($this->isColumnModified(GameTableMap::COL_RULES)) {
            $modifiedColumns[':p' . $index++]  = '`rules`';
        }
        if ($this->isColumnModified(GameTableMap::COL_INVITE)) {
            $modifiedColumns[':p' . $index++]  = '`invite`';
        }
        if ($this->isColumnModified(GameTableMap::COL_REQUEST_INVITE)) {
            $modifiedColumns[':p' . $index++]  = '`request_invite`';
        }
        if ($this->isColumnModified(GameTableMap::COL_AUTO_JOIN_GROUP_ID)) {
            $modifiedColumns[':p' . $index++]  = '`auto_join_group_id`';
        }
        if ($this->isColumnModified(GameTableMap::COL_AUTO_PLACE)) {
            $modifiedColumns[':p' . $index++]  = '`auto_place`';
        }
        if ($this->isColumnModified(GameTableMap::COL_DUPLICATE_GAME_ON_COMPLETE)) {
            $modifiedColumns[':p' . $index++]  = '`duplicate_game_on_complete`';
        }

        $sql = sprintf(
            'INSERT INTO `games` (%s) VALUES (%s)',
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
                    case '`active`':
                        $stmt->bindValue($identifier, (int) $this->active, PDO::PARAM_INT);
                        break;
                    case '`owner_id`':                        
                        $stmt->bindValue($identifier, $this->owner_id, PDO::PARAM_INT);
                        break;
                    case '`started`':
                        $stmt->bindValue($identifier, (int) $this->started, PDO::PARAM_INT);
                        break;
                    case '`paused`':
                        $stmt->bindValue($identifier, (int) $this->paused, PDO::PARAM_INT);
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
                    case '`auto_join_group_id`':                        
                        $stmt->bindValue($identifier, $this->auto_join_group_id, PDO::PARAM_INT);
                        break;
                    case '`auto_place`':
                        $stmt->bindValue($identifier, (int) $this->auto_place, PDO::PARAM_INT);
                        break;
                    case '`duplicate_game_on_complete`':
                        $stmt->bindValue($identifier, (int) $this->duplicate_game_on_complete, PDO::PARAM_INT);
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
        $pos = GameTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getActive();
                break;
            case 3:
                return $this->getOwnerId();
                break;
            case 4:
                return $this->getStarted();
                break;
            case 5:
                return $this->getPaused();
                break;
            case 6:
                return $this->getRules();
                break;
            case 7:
                return $this->getInvite();
                break;
            case 8:
                return $this->getRequestInvite();
                break;
            case 9:
                return $this->getAutoJoinGroupId();
                break;
            case 10:
                return $this->getAutoPlace();
                break;
            case 11:
                return $this->getDuplicateGameOnComplete();
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

        if (isset($alreadyDumpedObjects['Game'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Game'][$this->hashCode()] = true;
        $keys = GameTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getActive(),
            $keys[3] => $this->getOwnerId(),
            $keys[4] => $this->getStarted(),
            $keys[5] => $this->getPaused(),
            $keys[6] => $this->getRules(),
            $keys[7] => $this->getInvite(),
            $keys[8] => $this->getRequestInvite(),
            $keys[9] => $this->getAutoJoinGroupId(),
            $keys[10] => $this->getAutoPlace(),
            $keys[11] => $this->getDuplicateGameOnComplete(),
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
            if (null !== $this->aAutoJoinGroup) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'group';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'groups';
                        break;
                    default:
                        $key = 'AutoJoinGroup';
                }
        
                $result[$key] = $this->aAutoJoinGroup->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collUserGames) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userGames';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_gamess';
                        break;
                    default:
                        $key = 'UserGames';
                }
        
                $result[$key] = $this->collUserGames->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
            if (null !== $this->collCircuitPlayers) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'circuitPlayers';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'circuit_playerss';
                        break;
                    default:
                        $key = 'CircuitPlayers';
                }
        
                $result[$key] = $this->collCircuitPlayers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collGamePlayerGroups) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'gamePlayerGroups';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'game_player_groupss';
                        break;
                    default:
                        $key = 'GamePlayerGroups';
                }
        
                $result[$key] = $this->collGamePlayerGroups->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collGameActionLogs) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'gameActionLogs';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'game_action_logss';
                        break;
                    default:
                        $key = 'GameActionLogs';
                }
        
                $result[$key] = $this->collGameActionLogs->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Game
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = GameTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Game
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
                $this->setActive($value);
                break;
            case 3:
                $this->setOwnerId($value);
                break;
            case 4:
                $this->setStarted($value);
                break;
            case 5:
                $this->setPaused($value);
                break;
            case 6:
                $this->setRules($value);
                break;
            case 7:
                $this->setInvite($value);
                break;
            case 8:
                $this->setRequestInvite($value);
                break;
            case 9:
                $this->setAutoJoinGroupId($value);
                break;
            case 10:
                $this->setAutoPlace($value);
                break;
            case 11:
                $this->setDuplicateGameOnComplete($value);
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
        $keys = GameTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setActive($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setOwnerId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setStarted($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setPaused($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setRules($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setInvite($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setRequestInvite($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setAutoJoinGroupId($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setAutoPlace($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setDuplicateGameOnComplete($arr[$keys[11]]);
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
     * @return $this|\Game The current object, for fluid interface
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
        $criteria = new Criteria(GameTableMap::DATABASE_NAME);

        if ($this->isColumnModified(GameTableMap::COL_ID)) {
            $criteria->add(GameTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(GameTableMap::COL_NAME)) {
            $criteria->add(GameTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(GameTableMap::COL_ACTIVE)) {
            $criteria->add(GameTableMap::COL_ACTIVE, $this->active);
        }
        if ($this->isColumnModified(GameTableMap::COL_OWNER_ID)) {
            $criteria->add(GameTableMap::COL_OWNER_ID, $this->owner_id);
        }
        if ($this->isColumnModified(GameTableMap::COL_STARTED)) {
            $criteria->add(GameTableMap::COL_STARTED, $this->started);
        }
        if ($this->isColumnModified(GameTableMap::COL_PAUSED)) {
            $criteria->add(GameTableMap::COL_PAUSED, $this->paused);
        }
        if ($this->isColumnModified(GameTableMap::COL_RULES)) {
            $criteria->add(GameTableMap::COL_RULES, $this->rules);
        }
        if ($this->isColumnModified(GameTableMap::COL_INVITE)) {
            $criteria->add(GameTableMap::COL_INVITE, $this->invite);
        }
        if ($this->isColumnModified(GameTableMap::COL_REQUEST_INVITE)) {
            $criteria->add(GameTableMap::COL_REQUEST_INVITE, $this->request_invite);
        }
        if ($this->isColumnModified(GameTableMap::COL_AUTO_JOIN_GROUP_ID)) {
            $criteria->add(GameTableMap::COL_AUTO_JOIN_GROUP_ID, $this->auto_join_group_id);
        }
        if ($this->isColumnModified(GameTableMap::COL_AUTO_PLACE)) {
            $criteria->add(GameTableMap::COL_AUTO_PLACE, $this->auto_place);
        }
        if ($this->isColumnModified(GameTableMap::COL_DUPLICATE_GAME_ON_COMPLETE)) {
            $criteria->add(GameTableMap::COL_DUPLICATE_GAME_ON_COMPLETE, $this->duplicate_game_on_complete);
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
        $criteria = ChildGameQuery::create();
        $criteria->add(GameTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Game (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setActive($this->getActive());
        $copyObj->setOwnerId($this->getOwnerId());
        $copyObj->setStarted($this->getStarted());
        $copyObj->setPaused($this->getPaused());
        $copyObj->setRules($this->getRules());
        $copyObj->setInvite($this->getInvite());
        $copyObj->setRequestInvite($this->getRequestInvite());
        $copyObj->setAutoJoinGroupId($this->getAutoJoinGroupId());
        $copyObj->setAutoPlace($this->getAutoPlace());
        $copyObj->setDuplicateGameOnComplete($this->getDuplicateGameOnComplete());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getUserGames() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserGame($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getGameGroups() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addGameGroup($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCircuitPlayers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCircuitPlayer($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getGamePlayerGroups() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addGamePlayerGroup($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getGameActionLogs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addGameActionLog($relObj->copy($deepCopy));
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
     * @return \Game Clone of current object.
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
     * @return $this|\Game The current object (for fluent API support)
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
            $v->addOwnedGame($this);
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
                $this->aOwner->addOwnedGames($this);
             */
        }

        return $this->aOwner;
    }

    /**
     * Declares an association between this object and a ChildGroup object.
     *
     * @param  ChildGroup $v
     * @return $this|\Game The current object (for fluent API support)
     * @throws PropelException
     */
    public function setAutoJoinGroup(ChildGroup $v = null)
    {
        if ($v === null) {
            $this->setAutoJoinGroupId(NULL);
        } else {
            $this->setAutoJoinGroupId($v->getId());
        }

        $this->aAutoJoinGroup = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildGroup object, it will not be re-added.
        if ($v !== null) {
            $v->addAutoJoinedGame($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildGroup object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildGroup The associated ChildGroup object.
     * @throws PropelException
     */
    public function getAutoJoinGroup(ConnectionInterface $con = null)
    {
        if ($this->aAutoJoinGroup === null && ($this->auto_join_group_id !== null)) {
            $this->aAutoJoinGroup = ChildGroupQuery::create()->findPk($this->auto_join_group_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aAutoJoinGroup->addAutoJoinedGames($this);
             */
        }

        return $this->aAutoJoinGroup;
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
        if ('UserGame' == $relationName) {
            return $this->initUserGames();
        }
        if ('GameGroup' == $relationName) {
            return $this->initGameGroups();
        }
        if ('CircuitPlayer' == $relationName) {
            return $this->initCircuitPlayers();
        }
        if ('GamePlayerGroup' == $relationName) {
            return $this->initGamePlayerGroups();
        }
        if ('GameActionLog' == $relationName) {
            return $this->initGameActionLogs();
        }
    }

    /**
     * Clears out the collUserGames collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserGames()
     */
    public function clearUserGames()
    {
        $this->collUserGames = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserGames collection loaded partially.
     */
    public function resetPartialUserGames($v = true)
    {
        $this->collUserGamesPartial = $v;
    }

    /**
     * Initializes the collUserGames collection.
     *
     * By default this just sets the collUserGames collection to an empty array (like clearcollUserGames());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserGames($overrideExisting = true)
    {
        if (null !== $this->collUserGames && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserGameTableMap::getTableMap()->getCollectionClassName();

        $this->collUserGames = new $collectionClassName;
        $this->collUserGames->setModel('\UserGame');
    }

    /**
     * Gets an array of ChildUserGame objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGame is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserGame[] List of ChildUserGame objects
     * @throws PropelException
     */
    public function getUserGames(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserGamesPartial && !$this->isNew();
        if (null === $this->collUserGames || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserGames) {
                // return empty collection
                $this->initUserGames();
            } else {
                $collUserGames = ChildUserGameQuery::create(null, $criteria)
                    ->filterByGame($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserGamesPartial && count($collUserGames)) {
                        $this->initUserGames(false);

                        foreach ($collUserGames as $obj) {
                            if (false == $this->collUserGames->contains($obj)) {
                                $this->collUserGames->append($obj);
                            }
                        }

                        $this->collUserGamesPartial = true;
                    }

                    return $collUserGames;
                }

                if ($partial && $this->collUserGames) {
                    foreach ($this->collUserGames as $obj) {
                        if ($obj->isNew()) {
                            $collUserGames[] = $obj;
                        }
                    }
                }

                $this->collUserGames = $collUserGames;
                $this->collUserGamesPartial = false;
            }
        }

        return $this->collUserGames;
    }

    /**
     * Sets a collection of ChildUserGame objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userGames A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildGame The current object (for fluent API support)
     */
    public function setUserGames(Collection $userGames, ConnectionInterface $con = null)
    {
        /** @var ChildUserGame[] $userGamesToDelete */
        $userGamesToDelete = $this->getUserGames(new Criteria(), $con)->diff($userGames);

        
        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userGamesScheduledForDeletion = clone $userGamesToDelete;

        foreach ($userGamesToDelete as $userGameRemoved) {
            $userGameRemoved->setGame(null);
        }

        $this->collUserGames = null;
        foreach ($userGames as $userGame) {
            $this->addUserGame($userGame);
        }

        $this->collUserGames = $userGames;
        $this->collUserGamesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserGame objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserGame objects.
     * @throws PropelException
     */
    public function countUserGames(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserGamesPartial && !$this->isNew();
        if (null === $this->collUserGames || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserGames) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserGames());
            }

            $query = ChildUserGameQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGame($this)
                ->count($con);
        }

        return count($this->collUserGames);
    }

    /**
     * Method called to associate a ChildUserGame object to this object
     * through the ChildUserGame foreign key attribute.
     *
     * @param  ChildUserGame $l ChildUserGame
     * @return $this|\Game The current object (for fluent API support)
     */
    public function addUserGame(ChildUserGame $l)
    {
        if ($this->collUserGames === null) {
            $this->initUserGames();
            $this->collUserGamesPartial = true;
        }

        if (!$this->collUserGames->contains($l)) {
            $this->doAddUserGame($l);

            if ($this->userGamesScheduledForDeletion and $this->userGamesScheduledForDeletion->contains($l)) {
                $this->userGamesScheduledForDeletion->remove($this->userGamesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUserGame $userGame The ChildUserGame object to add.
     */
    protected function doAddUserGame(ChildUserGame $userGame)
    {
        $this->collUserGames[]= $userGame;
        $userGame->setGame($this);
    }

    /**
     * @param  ChildUserGame $userGame The ChildUserGame object to remove.
     * @return $this|ChildGame The current object (for fluent API support)
     */
    public function removeUserGame(ChildUserGame $userGame)
    {
        if ($this->getUserGames()->contains($userGame)) {
            $pos = $this->collUserGames->search($userGame);
            $this->collUserGames->remove($pos);
            if (null === $this->userGamesScheduledForDeletion) {
                $this->userGamesScheduledForDeletion = clone $this->collUserGames;
                $this->userGamesScheduledForDeletion->clear();
            }
            $this->userGamesScheduledForDeletion[]= clone $userGame;
            $userGame->setGame(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Game is new, it will return
     * an empty collection; or if this Game has previously
     * been saved, it will retrieve related UserGames from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Game.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUserGame[] List of ChildUserGame objects
     */
    public function getUserGamesJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserGameQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getUserGames($query, $con);
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
     * If this ChildGame is new, it will return
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
                    ->filterByGame($this)
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
     * @return $this|ChildGame The current object (for fluent API support)
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
            $gameGroupRemoved->setGame(null);
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
                ->filterByGame($this)
                ->count($con);
        }

        return count($this->collGameGroups);
    }

    /**
     * Method called to associate a ChildGameGroup object to this object
     * through the ChildGameGroup foreign key attribute.
     *
     * @param  ChildGameGroup $l ChildGameGroup
     * @return $this|\Game The current object (for fluent API support)
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
        $gameGroup->setGame($this);
    }

    /**
     * @param  ChildGameGroup $gameGroup The ChildGameGroup object to remove.
     * @return $this|ChildGame The current object (for fluent API support)
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
            $gameGroup->setGame(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Game is new, it will return
     * an empty collection; or if this Game has previously
     * been saved, it will retrieve related GameGroups from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Game.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildGameGroup[] List of ChildGameGroup objects
     */
    public function getGameGroupsJoinGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildGameGroupQuery::create(null, $criteria);
        $query->joinWith('Group', $joinBehavior);

        return $this->getGameGroups($query, $con);
    }

    /**
     * Clears out the collCircuitPlayers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCircuitPlayers()
     */
    public function clearCircuitPlayers()
    {
        $this->collCircuitPlayers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCircuitPlayers collection loaded partially.
     */
    public function resetPartialCircuitPlayers($v = true)
    {
        $this->collCircuitPlayersPartial = $v;
    }

    /**
     * Initializes the collCircuitPlayers collection.
     *
     * By default this just sets the collCircuitPlayers collection to an empty array (like clearcollCircuitPlayers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCircuitPlayers($overrideExisting = true)
    {
        if (null !== $this->collCircuitPlayers && !$overrideExisting) {
            return;
        }

        $collectionClassName = CircuitPlayerTableMap::getTableMap()->getCollectionClassName();

        $this->collCircuitPlayers = new $collectionClassName;
        $this->collCircuitPlayers->setModel('\CircuitPlayer');
    }

    /**
     * Gets an array of ChildCircuitPlayer objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGame is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCircuitPlayer[] List of ChildCircuitPlayer objects
     * @throws PropelException
     */
    public function getCircuitPlayers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCircuitPlayersPartial && !$this->isNew();
        if (null === $this->collCircuitPlayers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCircuitPlayers) {
                // return empty collection
                $this->initCircuitPlayers();
            } else {
                $collCircuitPlayers = ChildCircuitPlayerQuery::create(null, $criteria)
                    ->filterByGame($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCircuitPlayersPartial && count($collCircuitPlayers)) {
                        $this->initCircuitPlayers(false);

                        foreach ($collCircuitPlayers as $obj) {
                            if (false == $this->collCircuitPlayers->contains($obj)) {
                                $this->collCircuitPlayers->append($obj);
                            }
                        }

                        $this->collCircuitPlayersPartial = true;
                    }

                    return $collCircuitPlayers;
                }

                if ($partial && $this->collCircuitPlayers) {
                    foreach ($this->collCircuitPlayers as $obj) {
                        if ($obj->isNew()) {
                            $collCircuitPlayers[] = $obj;
                        }
                    }
                }

                $this->collCircuitPlayers = $collCircuitPlayers;
                $this->collCircuitPlayersPartial = false;
            }
        }

        return $this->collCircuitPlayers;
    }

    /**
     * Sets a collection of ChildCircuitPlayer objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $circuitPlayers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildGame The current object (for fluent API support)
     */
    public function setCircuitPlayers(Collection $circuitPlayers, ConnectionInterface $con = null)
    {
        /** @var ChildCircuitPlayer[] $circuitPlayersToDelete */
        $circuitPlayersToDelete = $this->getCircuitPlayers(new Criteria(), $con)->diff($circuitPlayers);

        
        $this->circuitPlayersScheduledForDeletion = $circuitPlayersToDelete;

        foreach ($circuitPlayersToDelete as $circuitPlayerRemoved) {
            $circuitPlayerRemoved->setGame(null);
        }

        $this->collCircuitPlayers = null;
        foreach ($circuitPlayers as $circuitPlayer) {
            $this->addCircuitPlayer($circuitPlayer);
        }

        $this->collCircuitPlayers = $circuitPlayers;
        $this->collCircuitPlayersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CircuitPlayer objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related CircuitPlayer objects.
     * @throws PropelException
     */
    public function countCircuitPlayers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCircuitPlayersPartial && !$this->isNew();
        if (null === $this->collCircuitPlayers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCircuitPlayers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCircuitPlayers());
            }

            $query = ChildCircuitPlayerQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGame($this)
                ->count($con);
        }

        return count($this->collCircuitPlayers);
    }

    /**
     * Method called to associate a ChildCircuitPlayer object to this object
     * through the ChildCircuitPlayer foreign key attribute.
     *
     * @param  ChildCircuitPlayer $l ChildCircuitPlayer
     * @return $this|\Game The current object (for fluent API support)
     */
    public function addCircuitPlayer(ChildCircuitPlayer $l)
    {
        if ($this->collCircuitPlayers === null) {
            $this->initCircuitPlayers();
            $this->collCircuitPlayersPartial = true;
        }

        if (!$this->collCircuitPlayers->contains($l)) {
            $this->doAddCircuitPlayer($l);

            if ($this->circuitPlayersScheduledForDeletion and $this->circuitPlayersScheduledForDeletion->contains($l)) {
                $this->circuitPlayersScheduledForDeletion->remove($this->circuitPlayersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildCircuitPlayer $circuitPlayer The ChildCircuitPlayer object to add.
     */
    protected function doAddCircuitPlayer(ChildCircuitPlayer $circuitPlayer)
    {
        $this->collCircuitPlayers[]= $circuitPlayer;
        $circuitPlayer->setGame($this);
    }

    /**
     * @param  ChildCircuitPlayer $circuitPlayer The ChildCircuitPlayer object to remove.
     * @return $this|ChildGame The current object (for fluent API support)
     */
    public function removeCircuitPlayer(ChildCircuitPlayer $circuitPlayer)
    {
        if ($this->getCircuitPlayers()->contains($circuitPlayer)) {
            $pos = $this->collCircuitPlayers->search($circuitPlayer);
            $this->collCircuitPlayers->remove($pos);
            if (null === $this->circuitPlayersScheduledForDeletion) {
                $this->circuitPlayersScheduledForDeletion = clone $this->collCircuitPlayers;
                $this->circuitPlayersScheduledForDeletion->clear();
            }
            $this->circuitPlayersScheduledForDeletion[]= $circuitPlayer;
            $circuitPlayer->setGame(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Game is new, it will return
     * an empty collection; or if this Game has previously
     * been saved, it will retrieve related CircuitPlayers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Game.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCircuitPlayer[] List of ChildCircuitPlayer objects
     */
    public function getCircuitPlayersJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCircuitPlayerQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getCircuitPlayers($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Game is new, it will return
     * an empty collection; or if this Game has previously
     * been saved, it will retrieve related CircuitPlayers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Game.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCircuitPlayer[] List of ChildCircuitPlayer objects
     */
    public function getCircuitPlayersJoinTarget(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCircuitPlayerQuery::create(null, $criteria);
        $query->joinWith('Target', $joinBehavior);

        return $this->getCircuitPlayers($query, $con);
    }

    /**
     * Clears out the collGamePlayerGroups collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addGamePlayerGroups()
     */
    public function clearGamePlayerGroups()
    {
        $this->collGamePlayerGroups = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collGamePlayerGroups collection loaded partially.
     */
    public function resetPartialGamePlayerGroups($v = true)
    {
        $this->collGamePlayerGroupsPartial = $v;
    }

    /**
     * Initializes the collGamePlayerGroups collection.
     *
     * By default this just sets the collGamePlayerGroups collection to an empty array (like clearcollGamePlayerGroups());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initGamePlayerGroups($overrideExisting = true)
    {
        if (null !== $this->collGamePlayerGroups && !$overrideExisting) {
            return;
        }

        $collectionClassName = GamePlayerGroupTableMap::getTableMap()->getCollectionClassName();

        $this->collGamePlayerGroups = new $collectionClassName;
        $this->collGamePlayerGroups->setModel('\GamePlayerGroup');
    }

    /**
     * Gets an array of ChildGamePlayerGroup objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGame is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildGamePlayerGroup[] List of ChildGamePlayerGroup objects
     * @throws PropelException
     */
    public function getGamePlayerGroups(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collGamePlayerGroupsPartial && !$this->isNew();
        if (null === $this->collGamePlayerGroups || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collGamePlayerGroups) {
                // return empty collection
                $this->initGamePlayerGroups();
            } else {
                $collGamePlayerGroups = ChildGamePlayerGroupQuery::create(null, $criteria)
                    ->filterByGame($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collGamePlayerGroupsPartial && count($collGamePlayerGroups)) {
                        $this->initGamePlayerGroups(false);

                        foreach ($collGamePlayerGroups as $obj) {
                            if (false == $this->collGamePlayerGroups->contains($obj)) {
                                $this->collGamePlayerGroups->append($obj);
                            }
                        }

                        $this->collGamePlayerGroupsPartial = true;
                    }

                    return $collGamePlayerGroups;
                }

                if ($partial && $this->collGamePlayerGroups) {
                    foreach ($this->collGamePlayerGroups as $obj) {
                        if ($obj->isNew()) {
                            $collGamePlayerGroups[] = $obj;
                        }
                    }
                }

                $this->collGamePlayerGroups = $collGamePlayerGroups;
                $this->collGamePlayerGroupsPartial = false;
            }
        }

        return $this->collGamePlayerGroups;
    }

    /**
     * Sets a collection of ChildGamePlayerGroup objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $gamePlayerGroups A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildGame The current object (for fluent API support)
     */
    public function setGamePlayerGroups(Collection $gamePlayerGroups, ConnectionInterface $con = null)
    {
        /** @var ChildGamePlayerGroup[] $gamePlayerGroupsToDelete */
        $gamePlayerGroupsToDelete = $this->getGamePlayerGroups(new Criteria(), $con)->diff($gamePlayerGroups);

        
        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->gamePlayerGroupsScheduledForDeletion = clone $gamePlayerGroupsToDelete;

        foreach ($gamePlayerGroupsToDelete as $gamePlayerGroupRemoved) {
            $gamePlayerGroupRemoved->setGame(null);
        }

        $this->collGamePlayerGroups = null;
        foreach ($gamePlayerGroups as $gamePlayerGroup) {
            $this->addGamePlayerGroup($gamePlayerGroup);
        }

        $this->collGamePlayerGroups = $gamePlayerGroups;
        $this->collGamePlayerGroupsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related GamePlayerGroup objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related GamePlayerGroup objects.
     * @throws PropelException
     */
    public function countGamePlayerGroups(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collGamePlayerGroupsPartial && !$this->isNew();
        if (null === $this->collGamePlayerGroups || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGamePlayerGroups) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getGamePlayerGroups());
            }

            $query = ChildGamePlayerGroupQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGame($this)
                ->count($con);
        }

        return count($this->collGamePlayerGroups);
    }

    /**
     * Method called to associate a ChildGamePlayerGroup object to this object
     * through the ChildGamePlayerGroup foreign key attribute.
     *
     * @param  ChildGamePlayerGroup $l ChildGamePlayerGroup
     * @return $this|\Game The current object (for fluent API support)
     */
    public function addGamePlayerGroup(ChildGamePlayerGroup $l)
    {
        if ($this->collGamePlayerGroups === null) {
            $this->initGamePlayerGroups();
            $this->collGamePlayerGroupsPartial = true;
        }

        if (!$this->collGamePlayerGroups->contains($l)) {
            $this->doAddGamePlayerGroup($l);

            if ($this->gamePlayerGroupsScheduledForDeletion and $this->gamePlayerGroupsScheduledForDeletion->contains($l)) {
                $this->gamePlayerGroupsScheduledForDeletion->remove($this->gamePlayerGroupsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildGamePlayerGroup $gamePlayerGroup The ChildGamePlayerGroup object to add.
     */
    protected function doAddGamePlayerGroup(ChildGamePlayerGroup $gamePlayerGroup)
    {
        $this->collGamePlayerGroups[]= $gamePlayerGroup;
        $gamePlayerGroup->setGame($this);
    }

    /**
     * @param  ChildGamePlayerGroup $gamePlayerGroup The ChildGamePlayerGroup object to remove.
     * @return $this|ChildGame The current object (for fluent API support)
     */
    public function removeGamePlayerGroup(ChildGamePlayerGroup $gamePlayerGroup)
    {
        if ($this->getGamePlayerGroups()->contains($gamePlayerGroup)) {
            $pos = $this->collGamePlayerGroups->search($gamePlayerGroup);
            $this->collGamePlayerGroups->remove($pos);
            if (null === $this->gamePlayerGroupsScheduledForDeletion) {
                $this->gamePlayerGroupsScheduledForDeletion = clone $this->collGamePlayerGroups;
                $this->gamePlayerGroupsScheduledForDeletion->clear();
            }
            $this->gamePlayerGroupsScheduledForDeletion[]= clone $gamePlayerGroup;
            $gamePlayerGroup->setGame(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Game is new, it will return
     * an empty collection; or if this Game has previously
     * been saved, it will retrieve related GamePlayerGroups from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Game.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildGamePlayerGroup[] List of ChildGamePlayerGroup objects
     */
    public function getGamePlayerGroupsJoinPlayerGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildGamePlayerGroupQuery::create(null, $criteria);
        $query->joinWith('PlayerGroup', $joinBehavior);

        return $this->getGamePlayerGroups($query, $con);
    }

    /**
     * Clears out the collGameActionLogs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addGameActionLogs()
     */
    public function clearGameActionLogs()
    {
        $this->collGameActionLogs = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collGameActionLogs collection loaded partially.
     */
    public function resetPartialGameActionLogs($v = true)
    {
        $this->collGameActionLogsPartial = $v;
    }

    /**
     * Initializes the collGameActionLogs collection.
     *
     * By default this just sets the collGameActionLogs collection to an empty array (like clearcollGameActionLogs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initGameActionLogs($overrideExisting = true)
    {
        if (null !== $this->collGameActionLogs && !$overrideExisting) {
            return;
        }

        $collectionClassName = GameActionLogTableMap::getTableMap()->getCollectionClassName();

        $this->collGameActionLogs = new $collectionClassName;
        $this->collGameActionLogs->setModel('\GameActionLog');
    }

    /**
     * Gets an array of ChildGameActionLog objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGame is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildGameActionLog[] List of ChildGameActionLog objects
     * @throws PropelException
     */
    public function getGameActionLogs(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collGameActionLogsPartial && !$this->isNew();
        if (null === $this->collGameActionLogs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collGameActionLogs) {
                // return empty collection
                $this->initGameActionLogs();
            } else {
                $collGameActionLogs = ChildGameActionLogQuery::create(null, $criteria)
                    ->filterByGame($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collGameActionLogsPartial && count($collGameActionLogs)) {
                        $this->initGameActionLogs(false);

                        foreach ($collGameActionLogs as $obj) {
                            if (false == $this->collGameActionLogs->contains($obj)) {
                                $this->collGameActionLogs->append($obj);
                            }
                        }

                        $this->collGameActionLogsPartial = true;
                    }

                    return $collGameActionLogs;
                }

                if ($partial && $this->collGameActionLogs) {
                    foreach ($this->collGameActionLogs as $obj) {
                        if ($obj->isNew()) {
                            $collGameActionLogs[] = $obj;
                        }
                    }
                }

                $this->collGameActionLogs = $collGameActionLogs;
                $this->collGameActionLogsPartial = false;
            }
        }

        return $this->collGameActionLogs;
    }

    /**
     * Sets a collection of ChildGameActionLog objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $gameActionLogs A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildGame The current object (for fluent API support)
     */
    public function setGameActionLogs(Collection $gameActionLogs, ConnectionInterface $con = null)
    {
        /** @var ChildGameActionLog[] $gameActionLogsToDelete */
        $gameActionLogsToDelete = $this->getGameActionLogs(new Criteria(), $con)->diff($gameActionLogs);

        
        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->gameActionLogsScheduledForDeletion = clone $gameActionLogsToDelete;

        foreach ($gameActionLogsToDelete as $gameActionLogRemoved) {
            $gameActionLogRemoved->setGame(null);
        }

        $this->collGameActionLogs = null;
        foreach ($gameActionLogs as $gameActionLog) {
            $this->addGameActionLog($gameActionLog);
        }

        $this->collGameActionLogs = $gameActionLogs;
        $this->collGameActionLogsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related GameActionLog objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related GameActionLog objects.
     * @throws PropelException
     */
    public function countGameActionLogs(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collGameActionLogsPartial && !$this->isNew();
        if (null === $this->collGameActionLogs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGameActionLogs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getGameActionLogs());
            }

            $query = ChildGameActionLogQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGame($this)
                ->count($con);
        }

        return count($this->collGameActionLogs);
    }

    /**
     * Method called to associate a ChildGameActionLog object to this object
     * through the ChildGameActionLog foreign key attribute.
     *
     * @param  ChildGameActionLog $l ChildGameActionLog
     * @return $this|\Game The current object (for fluent API support)
     */
    public function addGameActionLog(ChildGameActionLog $l)
    {
        if ($this->collGameActionLogs === null) {
            $this->initGameActionLogs();
            $this->collGameActionLogsPartial = true;
        }

        if (!$this->collGameActionLogs->contains($l)) {
            $this->doAddGameActionLog($l);

            if ($this->gameActionLogsScheduledForDeletion and $this->gameActionLogsScheduledForDeletion->contains($l)) {
                $this->gameActionLogsScheduledForDeletion->remove($this->gameActionLogsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildGameActionLog $gameActionLog The ChildGameActionLog object to add.
     */
    protected function doAddGameActionLog(ChildGameActionLog $gameActionLog)
    {
        $this->collGameActionLogs[]= $gameActionLog;
        $gameActionLog->setGame($this);
    }

    /**
     * @param  ChildGameActionLog $gameActionLog The ChildGameActionLog object to remove.
     * @return $this|ChildGame The current object (for fluent API support)
     */
    public function removeGameActionLog(ChildGameActionLog $gameActionLog)
    {
        if ($this->getGameActionLogs()->contains($gameActionLog)) {
            $pos = $this->collGameActionLogs->search($gameActionLog);
            $this->collGameActionLogs->remove($pos);
            if (null === $this->gameActionLogsScheduledForDeletion) {
                $this->gameActionLogsScheduledForDeletion = clone $this->collGameActionLogs;
                $this->gameActionLogsScheduledForDeletion->clear();
            }
            $this->gameActionLogsScheduledForDeletion[]= clone $gameActionLog;
            $gameActionLog->setGame(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Game is new, it will return
     * an empty collection; or if this Game has previously
     * been saved, it will retrieve related GameActionLogs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Game.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildGameActionLog[] List of ChildGameActionLog objects
     */
    public function getGameActionLogsJoinActionLog(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildGameActionLogQuery::create(null, $criteria);
        $query->joinWith('ActionLog', $joinBehavior);

        return $this->getGameActionLogs($query, $con);
    }

    /**
     * Clears out the collUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUsers()
     */
    public function clearUsers()
    {
        $this->collUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collUsers crossRef collection.
     *
     * By default this just sets the collUsers collection to an empty collection (like clearUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initUsers()
    {
        $collectionClassName = UserGameTableMap::getTableMap()->getCollectionClassName();

        $this->collUsers = new $collectionClassName;
        $this->collUsersPartial = true;
        $this->collUsers->setModel('\User');
    }

    /**
     * Checks if the collUsers collection is loaded.
     *
     * @return bool
     */
    public function isUsersLoaded()
    {
        return null !== $this->collUsers;
    }

    /**
     * Gets a collection of ChildUser objects related by a many-to-many relationship
     * to the current object by way of the user_games cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGame is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildUser[] List of ChildUser objects
     */
    public function getUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersPartial && !$this->isNew();
        if (null === $this->collUsers || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collUsers) {
                    $this->initUsers();
                }
            } else {

                $query = ChildUserQuery::create(null, $criteria)
                    ->filterByGame($this);
                $collUsers = $query->find($con);
                if (null !== $criteria) {
                    return $collUsers;
                }

                if ($partial && $this->collUsers) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collUsers as $obj) {
                        if (!$collUsers->contains($obj)) {
                            $collUsers[] = $obj;
                        }
                    }
                }

                $this->collUsers = $collUsers;
                $this->collUsersPartial = false;
            }
        }

        return $this->collUsers;
    }

    /**
     * Sets a collection of User objects related by a many-to-many relationship
     * to the current object by way of the user_games cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $users A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildGame The current object (for fluent API support)
     */
    public function setUsers(Collection $users, ConnectionInterface $con = null)
    {
        $this->clearUsers();
        $currentUsers = $this->getUsers();

        $usersScheduledForDeletion = $currentUsers->diff($users);

        foreach ($usersScheduledForDeletion as $toDelete) {
            $this->removeUser($toDelete);
        }

        foreach ($users as $user) {
            if (!$currentUsers->contains($user)) {
                $this->doAddUser($user);
            }
        }

        $this->collUsersPartial = false;
        $this->collUsers = $users;

        return $this;
    }

    /**
     * Gets the number of User objects related by a many-to-many relationship
     * to the current object by way of the user_games cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related User objects
     */
    public function countUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersPartial && !$this->isNew();
        if (null === $this->collUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUsers) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getUsers());
                }

                $query = ChildUserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByGame($this)
                    ->count($con);
            }
        } else {
            return count($this->collUsers);
        }
    }

    /**
     * Associate a ChildUser to this object
     * through the user_games cross reference table.
     * 
     * @param ChildUser $user
     * @return ChildGame The current object (for fluent API support)
     */
    public function addUser(ChildUser $user)
    {
        if ($this->collUsers === null) {
            $this->initUsers();
        }

        if (!$this->getUsers()->contains($user)) {
            // only add it if the **same** object is not already associated
            $this->collUsers->push($user);
            $this->doAddUser($user);
        }

        return $this;
    }

    /**
     * 
     * @param ChildUser $user
     */
    protected function doAddUser(ChildUser $user)
    {
        $userGame = new ChildUserGame();

        $userGame->setUser($user);

        $userGame->setGame($this);

        $this->addUserGame($userGame);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$user->isGamesLoaded()) {
            $user->initGames();
            $user->getGames()->push($this);
        } elseif (!$user->getGames()->contains($this)) {
            $user->getGames()->push($this);
        }

    }

    /**
     * Remove user of this object
     * through the user_games cross reference table.
     * 
     * @param ChildUser $user
     * @return ChildGame The current object (for fluent API support)
     */
    public function removeUser(ChildUser $user)
    {
        if ($this->getUsers()->contains($user)) { $userGame = new ChildUserGame();

            $userGame->setUser($user);
            if ($user->isGamesLoaded()) {
                //remove the back reference if available
                $user->getGames()->removeObject($this);
            }

            $userGame->setGame($this);
            $this->removeUserGame(clone $userGame);
            $userGame->clear();

            $this->collUsers->remove($this->collUsers->search($user));
            
            if (null === $this->usersScheduledForDeletion) {
                $this->usersScheduledForDeletion = clone $this->collUsers;
                $this->usersScheduledForDeletion->clear();
            }

            $this->usersScheduledForDeletion->push($user);
        }


        return $this;
    }

    /**
     * Clears out the collGroups collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addGroups()
     */
    public function clearGroups()
    {
        $this->collGroups = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collGroups crossRef collection.
     *
     * By default this just sets the collGroups collection to an empty collection (like clearGroups());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initGroups()
    {
        $collectionClassName = GameGroupTableMap::getTableMap()->getCollectionClassName();

        $this->collGroups = new $collectionClassName;
        $this->collGroupsPartial = true;
        $this->collGroups->setModel('\Group');
    }

    /**
     * Checks if the collGroups collection is loaded.
     *
     * @return bool
     */
    public function isGroupsLoaded()
    {
        return null !== $this->collGroups;
    }

    /**
     * Gets a collection of ChildGroup objects related by a many-to-many relationship
     * to the current object by way of the game_groups cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGame is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildGroup[] List of ChildGroup objects
     */
    public function getGroups(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collGroupsPartial && !$this->isNew();
        if (null === $this->collGroups || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collGroups) {
                    $this->initGroups();
                }
            } else {

                $query = ChildGroupQuery::create(null, $criteria)
                    ->filterByGame($this);
                $collGroups = $query->find($con);
                if (null !== $criteria) {
                    return $collGroups;
                }

                if ($partial && $this->collGroups) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collGroups as $obj) {
                        if (!$collGroups->contains($obj)) {
                            $collGroups[] = $obj;
                        }
                    }
                }

                $this->collGroups = $collGroups;
                $this->collGroupsPartial = false;
            }
        }

        return $this->collGroups;
    }

    /**
     * Sets a collection of Group objects related by a many-to-many relationship
     * to the current object by way of the game_groups cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $groups A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildGame The current object (for fluent API support)
     */
    public function setGroups(Collection $groups, ConnectionInterface $con = null)
    {
        $this->clearGroups();
        $currentGroups = $this->getGroups();

        $groupsScheduledForDeletion = $currentGroups->diff($groups);

        foreach ($groupsScheduledForDeletion as $toDelete) {
            $this->removeGroup($toDelete);
        }

        foreach ($groups as $group) {
            if (!$currentGroups->contains($group)) {
                $this->doAddGroup($group);
            }
        }

        $this->collGroupsPartial = false;
        $this->collGroups = $groups;

        return $this;
    }

    /**
     * Gets the number of Group objects related by a many-to-many relationship
     * to the current object by way of the game_groups cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Group objects
     */
    public function countGroups(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collGroupsPartial && !$this->isNew();
        if (null === $this->collGroups || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGroups) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getGroups());
                }

                $query = ChildGroupQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByGame($this)
                    ->count($con);
            }
        } else {
            return count($this->collGroups);
        }
    }

    /**
     * Associate a ChildGroup to this object
     * through the game_groups cross reference table.
     * 
     * @param ChildGroup $group
     * @return ChildGame The current object (for fluent API support)
     */
    public function addGroup(ChildGroup $group)
    {
        if ($this->collGroups === null) {
            $this->initGroups();
        }

        if (!$this->getGroups()->contains($group)) {
            // only add it if the **same** object is not already associated
            $this->collGroups->push($group);
            $this->doAddGroup($group);
        }

        return $this;
    }

    /**
     * 
     * @param ChildGroup $group
     */
    protected function doAddGroup(ChildGroup $group)
    {
        $gameGroup = new ChildGameGroup();

        $gameGroup->setGroup($group);

        $gameGroup->setGame($this);

        $this->addGameGroup($gameGroup);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$group->isGamesLoaded()) {
            $group->initGames();
            $group->getGames()->push($this);
        } elseif (!$group->getGames()->contains($this)) {
            $group->getGames()->push($this);
        }

    }

    /**
     * Remove group of this object
     * through the game_groups cross reference table.
     * 
     * @param ChildGroup $group
     * @return ChildGame The current object (for fluent API support)
     */
    public function removeGroup(ChildGroup $group)
    {
        if ($this->getGroups()->contains($group)) { $gameGroup = new ChildGameGroup();

            $gameGroup->setGroup($group);
            if ($group->isGamesLoaded()) {
                //remove the back reference if available
                $group->getGames()->removeObject($this);
            }

            $gameGroup->setGame($this);
            $this->removeGameGroup(clone $gameGroup);
            $gameGroup->clear();

            $this->collGroups->remove($this->collGroups->search($group));
            
            if (null === $this->groupsScheduledForDeletion) {
                $this->groupsScheduledForDeletion = clone $this->collGroups;
                $this->groupsScheduledForDeletion->clear();
            }

            $this->groupsScheduledForDeletion->push($group);
        }


        return $this;
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
     * Initializes the collPlayerGroups crossRef collection.
     *
     * By default this just sets the collPlayerGroups collection to an empty collection (like clearPlayerGroups());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPlayerGroups()
    {
        $collectionClassName = GamePlayerGroupTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerGroups = new $collectionClassName;
        $this->collPlayerGroupsPartial = true;
        $this->collPlayerGroups->setModel('\PlayerGroup');
    }

    /**
     * Checks if the collPlayerGroups collection is loaded.
     *
     * @return bool
     */
    public function isPlayerGroupsLoaded()
    {
        return null !== $this->collPlayerGroups;
    }

    /**
     * Gets a collection of ChildPlayerGroup objects related by a many-to-many relationship
     * to the current object by way of the game_player_groups cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGame is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildPlayerGroup[] List of ChildPlayerGroup objects
     */
    public function getPlayerGroups(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerGroupsPartial && !$this->isNew();
        if (null === $this->collPlayerGroups || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collPlayerGroups) {
                    $this->initPlayerGroups();
                }
            } else {

                $query = ChildPlayerGroupQuery::create(null, $criteria)
                    ->filterByGame($this);
                $collPlayerGroups = $query->find($con);
                if (null !== $criteria) {
                    return $collPlayerGroups;
                }

                if ($partial && $this->collPlayerGroups) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collPlayerGroups as $obj) {
                        if (!$collPlayerGroups->contains($obj)) {
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
     * Sets a collection of PlayerGroup objects related by a many-to-many relationship
     * to the current object by way of the game_player_groups cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $playerGroups A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildGame The current object (for fluent API support)
     */
    public function setPlayerGroups(Collection $playerGroups, ConnectionInterface $con = null)
    {
        $this->clearPlayerGroups();
        $currentPlayerGroups = $this->getPlayerGroups();

        $playerGroupsScheduledForDeletion = $currentPlayerGroups->diff($playerGroups);

        foreach ($playerGroupsScheduledForDeletion as $toDelete) {
            $this->removePlayerGroup($toDelete);
        }

        foreach ($playerGroups as $playerGroup) {
            if (!$currentPlayerGroups->contains($playerGroup)) {
                $this->doAddPlayerGroup($playerGroup);
            }
        }

        $this->collPlayerGroupsPartial = false;
        $this->collPlayerGroups = $playerGroups;

        return $this;
    }

    /**
     * Gets the number of PlayerGroup objects related by a many-to-many relationship
     * to the current object by way of the game_player_groups cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related PlayerGroup objects
     */
    public function countPlayerGroups(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerGroupsPartial && !$this->isNew();
        if (null === $this->collPlayerGroups || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerGroups) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getPlayerGroups());
                }

                $query = ChildPlayerGroupQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByGame($this)
                    ->count($con);
            }
        } else {
            return count($this->collPlayerGroups);
        }
    }

    /**
     * Associate a ChildPlayerGroup to this object
     * through the game_player_groups cross reference table.
     * 
     * @param ChildPlayerGroup $playerGroup
     * @return ChildGame The current object (for fluent API support)
     */
    public function addPlayerGroup(ChildPlayerGroup $playerGroup)
    {
        if ($this->collPlayerGroups === null) {
            $this->initPlayerGroups();
        }

        if (!$this->getPlayerGroups()->contains($playerGroup)) {
            // only add it if the **same** object is not already associated
            $this->collPlayerGroups->push($playerGroup);
            $this->doAddPlayerGroup($playerGroup);
        }

        return $this;
    }

    /**
     * 
     * @param ChildPlayerGroup $playerGroup
     */
    protected function doAddPlayerGroup(ChildPlayerGroup $playerGroup)
    {
        $gamePlayerGroup = new ChildGamePlayerGroup();

        $gamePlayerGroup->setPlayerGroup($playerGroup);

        $gamePlayerGroup->setGame($this);

        $this->addGamePlayerGroup($gamePlayerGroup);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$playerGroup->isGamesLoaded()) {
            $playerGroup->initGames();
            $playerGroup->getGames()->push($this);
        } elseif (!$playerGroup->getGames()->contains($this)) {
            $playerGroup->getGames()->push($this);
        }

    }

    /**
     * Remove playerGroup of this object
     * through the game_player_groups cross reference table.
     * 
     * @param ChildPlayerGroup $playerGroup
     * @return ChildGame The current object (for fluent API support)
     */
    public function removePlayerGroup(ChildPlayerGroup $playerGroup)
    {
        if ($this->getPlayerGroups()->contains($playerGroup)) { $gamePlayerGroup = new ChildGamePlayerGroup();

            $gamePlayerGroup->setPlayerGroup($playerGroup);
            if ($playerGroup->isGamesLoaded()) {
                //remove the back reference if available
                $playerGroup->getGames()->removeObject($this);
            }

            $gamePlayerGroup->setGame($this);
            $this->removeGamePlayerGroup(clone $gamePlayerGroup);
            $gamePlayerGroup->clear();

            $this->collPlayerGroups->remove($this->collPlayerGroups->search($playerGroup));
            
            if (null === $this->playerGroupsScheduledForDeletion) {
                $this->playerGroupsScheduledForDeletion = clone $this->collPlayerGroups;
                $this->playerGroupsScheduledForDeletion->clear();
            }

            $this->playerGroupsScheduledForDeletion->push($playerGroup);
        }


        return $this;
    }

    /**
     * Clears out the collActionLogs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addActionLogs()
     */
    public function clearActionLogs()
    {
        $this->collActionLogs = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collActionLogs crossRef collection.
     *
     * By default this just sets the collActionLogs collection to an empty collection (like clearActionLogs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initActionLogs()
    {
        $collectionClassName = GameActionLogTableMap::getTableMap()->getCollectionClassName();

        $this->collActionLogs = new $collectionClassName;
        $this->collActionLogsPartial = true;
        $this->collActionLogs->setModel('\ActionLog');
    }

    /**
     * Checks if the collActionLogs collection is loaded.
     *
     * @return bool
     */
    public function isActionLogsLoaded()
    {
        return null !== $this->collActionLogs;
    }

    /**
     * Gets a collection of ChildActionLog objects related by a many-to-many relationship
     * to the current object by way of the game_action_logs cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGame is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildActionLog[] List of ChildActionLog objects
     */
    public function getActionLogs(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collActionLogsPartial && !$this->isNew();
        if (null === $this->collActionLogs || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collActionLogs) {
                    $this->initActionLogs();
                }
            } else {

                $query = ChildActionLogQuery::create(null, $criteria)
                    ->filterByGame($this);
                $collActionLogs = $query->find($con);
                if (null !== $criteria) {
                    return $collActionLogs;
                }

                if ($partial && $this->collActionLogs) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collActionLogs as $obj) {
                        if (!$collActionLogs->contains($obj)) {
                            $collActionLogs[] = $obj;
                        }
                    }
                }

                $this->collActionLogs = $collActionLogs;
                $this->collActionLogsPartial = false;
            }
        }

        return $this->collActionLogs;
    }

    /**
     * Sets a collection of ActionLog objects related by a many-to-many relationship
     * to the current object by way of the game_action_logs cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $actionLogs A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildGame The current object (for fluent API support)
     */
    public function setActionLogs(Collection $actionLogs, ConnectionInterface $con = null)
    {
        $this->clearActionLogs();
        $currentActionLogs = $this->getActionLogs();

        $actionLogsScheduledForDeletion = $currentActionLogs->diff($actionLogs);

        foreach ($actionLogsScheduledForDeletion as $toDelete) {
            $this->removeActionLog($toDelete);
        }

        foreach ($actionLogs as $actionLog) {
            if (!$currentActionLogs->contains($actionLog)) {
                $this->doAddActionLog($actionLog);
            }
        }

        $this->collActionLogsPartial = false;
        $this->collActionLogs = $actionLogs;

        return $this;
    }

    /**
     * Gets the number of ActionLog objects related by a many-to-many relationship
     * to the current object by way of the game_action_logs cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related ActionLog objects
     */
    public function countActionLogs(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collActionLogsPartial && !$this->isNew();
        if (null === $this->collActionLogs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collActionLogs) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getActionLogs());
                }

                $query = ChildActionLogQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByGame($this)
                    ->count($con);
            }
        } else {
            return count($this->collActionLogs);
        }
    }

    /**
     * Associate a ChildActionLog to this object
     * through the game_action_logs cross reference table.
     * 
     * @param ChildActionLog $actionLog
     * @return ChildGame The current object (for fluent API support)
     */
    public function addActionLog(ChildActionLog $actionLog)
    {
        if ($this->collActionLogs === null) {
            $this->initActionLogs();
        }

        if (!$this->getActionLogs()->contains($actionLog)) {
            // only add it if the **same** object is not already associated
            $this->collActionLogs->push($actionLog);
            $this->doAddActionLog($actionLog);
        }

        return $this;
    }

    /**
     * 
     * @param ChildActionLog $actionLog
     */
    protected function doAddActionLog(ChildActionLog $actionLog)
    {
        $gameActionLog = new ChildGameActionLog();

        $gameActionLog->setActionLog($actionLog);

        $gameActionLog->setGame($this);

        $this->addGameActionLog($gameActionLog);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$actionLog->isGamesLoaded()) {
            $actionLog->initGames();
            $actionLog->getGames()->push($this);
        } elseif (!$actionLog->getGames()->contains($this)) {
            $actionLog->getGames()->push($this);
        }

    }

    /**
     * Remove actionLog of this object
     * through the game_action_logs cross reference table.
     * 
     * @param ChildActionLog $actionLog
     * @return ChildGame The current object (for fluent API support)
     */
    public function removeActionLog(ChildActionLog $actionLog)
    {
        if ($this->getActionLogs()->contains($actionLog)) { $gameActionLog = new ChildGameActionLog();

            $gameActionLog->setActionLog($actionLog);
            if ($actionLog->isGamesLoaded()) {
                //remove the back reference if available
                $actionLog->getGames()->removeObject($this);
            }

            $gameActionLog->setGame($this);
            $this->removeGameActionLog(clone $gameActionLog);
            $gameActionLog->clear();

            $this->collActionLogs->remove($this->collActionLogs->search($actionLog));
            
            if (null === $this->actionLogsScheduledForDeletion) {
                $this->actionLogsScheduledForDeletion = clone $this->collActionLogs;
                $this->actionLogsScheduledForDeletion->clear();
            }

            $this->actionLogsScheduledForDeletion->push($actionLog);
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
            $this->aOwner->removeOwnedGame($this);
        }
        if (null !== $this->aAutoJoinGroup) {
            $this->aAutoJoinGroup->removeAutoJoinedGame($this);
        }
        $this->id = null;
        $this->name = null;
        $this->active = null;
        $this->owner_id = null;
        $this->started = null;
        $this->paused = null;
        $this->rules = null;
        $this->invite = null;
        $this->request_invite = null;
        $this->auto_join_group_id = null;
        $this->auto_place = null;
        $this->duplicate_game_on_complete = null;
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
            if ($this->collUserGames) {
                foreach ($this->collUserGames as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGameGroups) {
                foreach ($this->collGameGroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCircuitPlayers) {
                foreach ($this->collCircuitPlayers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGamePlayerGroups) {
                foreach ($this->collGamePlayerGroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGameActionLogs) {
                foreach ($this->collGameActionLogs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUsers) {
                foreach ($this->collUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGroups) {
                foreach ($this->collGroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerGroups) {
                foreach ($this->collPlayerGroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collActionLogs) {
                foreach ($this->collActionLogs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collUserGames = null;
        $this->collGameGroups = null;
        $this->collCircuitPlayers = null;
        $this->collGamePlayerGroups = null;
        $this->collGameActionLogs = null;
        $this->collUsers = null;
        $this->collGroups = null;
        $this->collPlayerGroups = null;
        $this->collActionLogs = null;
        $this->aOwner = null;
        $this->aAutoJoinGroup = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(GameTableMap::DEFAULT_STRING_FORMAT);
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
