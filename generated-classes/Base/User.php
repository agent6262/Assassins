<?php

namespace Base;

use \CircuitPlayer as ChildCircuitPlayer;
use \CircuitPlayerQuery as ChildCircuitPlayerQuery;
use \Game as ChildGame;
use \GameQuery as ChildGameQuery;
use \LtsCircuitPlayer as ChildLtsCircuitPlayer;
use \LtsCircuitPlayerQuery as ChildLtsCircuitPlayerQuery;
use \LtsGame as ChildLtsGame;
use \LtsGameQuery as ChildLtsGameQuery;
use \PlayerGroup as ChildPlayerGroup;
use \PlayerGroupQuery as ChildPlayerGroupQuery;
use \Preference as ChildPreference;
use \PreferenceQuery as ChildPreferenceQuery;
use \Preset as ChildPreset;
use \PresetQuery as ChildPresetQuery;
use \User as ChildUser;
use \UserGame as ChildUserGame;
use \UserGameQuery as ChildUserGameQuery;
use \UserPreset as ChildUserPreset;
use \UserPresetQuery as ChildUserPresetQuery;
use \UserQuery as ChildUserQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\CircuitPlayerTableMap;
use Map\GameTableMap;
use Map\LtsCircuitPlayerTableMap;
use Map\LtsGameTableMap;
use Map\PlayerGroupTableMap;
use Map\UserGameTableMap;
use Map\UserPresetTableMap;
use Map\UserTableMap;
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
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'users' table.
 *
 * 
 *
 * @package    propel.generator..Base
 */
abstract class User implements ActiveRecordInterface 
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\UserTableMap';


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
     * The value for the email field.
     * 
     * @var        string
     */
    protected $email;

    /**
     * The value for the username field.
     * 
     * @var        string
     */
    protected $username;

    /**
     * The value for the real_name field.
     * 
     * @var        string
     */
    protected $real_name;

    /**
     * The value for the password field.
     * 
     * @var        string
     */
    protected $password;

    /**
     * The value for the money field.
     * 
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $money;

    /**
     * The value for the total_money field.
     * 
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $total_money;

    /**
     * The value for the verification_token field.
     * 
     * @var        string
     */
    protected $verification_token;

    /**
     * The value for the cookie_token field.
     * 
     * @var        string
     */
    protected $cookie_token;

    /**
     * The value for the active field.
     * 
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $active;

    /**
     * The value for the date_created field.
     * 
     * Note: this column has a database default value of: (expression) CURRENT_TIMESTAMP
     * @var        DateTime
     */
    protected $date_created;

    /**
     * The value for the verification_time field.
     * 
     * @var        DateTime
     */
    protected $verification_time;

    /**
     * @var        ChildPreference one-to-one related ChildPreference object
     */
    protected $singlePreference;

    /**
     * @var        ObjectCollection|ChildUserGame[] Collection to store aggregation of ChildUserGame objects.
     */
    protected $collUserGames;
    protected $collUserGamesPartial;

    /**
     * @var        ObjectCollection|ChildUserPreset[] Collection to store aggregation of ChildUserPreset objects.
     */
    protected $collUserPresets;
    protected $collUserPresetsPartial;

    /**
     * @var        ObjectCollection|ChildGame[] Collection to store aggregation of ChildGame objects.
     */
    protected $collOwnedGames;
    protected $collOwnedGamesPartial;

    /**
     * @var        ObjectCollection|ChildCircuitPlayer[] Collection to store aggregation of ChildCircuitPlayer objects.
     */
    protected $collCircuitPlayersRelatedByPlayerId;
    protected $collCircuitPlayersRelatedByPlayerIdPartial;

    /**
     * @var        ObjectCollection|ChildCircuitPlayer[] Collection to store aggregation of ChildCircuitPlayer objects.
     */
    protected $collCircuitPlayersRelatedByTargetId;
    protected $collCircuitPlayersRelatedByTargetIdPartial;

    /**
     * @var        ObjectCollection|ChildPlayerGroup[] Collection to store aggregation of ChildPlayerGroup objects.
     */
    protected $collPlayerGroups;
    protected $collPlayerGroupsPartial;

    /**
     * @var        ObjectCollection|ChildLtsGame[] Collection to store aggregation of ChildLtsGame objects.
     */
    protected $collLtsGames;
    protected $collLtsGamesPartial;

    /**
     * @var        ObjectCollection|ChildLtsCircuitPlayer[] Collection to store aggregation of ChildLtsCircuitPlayer objects.
     */
    protected $collLtsCircuitPlayersRelatedByPlayerId;
    protected $collLtsCircuitPlayersRelatedByPlayerIdPartial;

    /**
     * @var        ObjectCollection|ChildLtsCircuitPlayer[] Collection to store aggregation of ChildLtsCircuitPlayer objects.
     */
    protected $collLtsCircuitPlayersRelatedByTargetId;
    protected $collLtsCircuitPlayersRelatedByTargetIdPartial;

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
     * @var ObjectCollection|ChildUserGame[]
     */
    protected $userGamesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserPreset[]
     */
    protected $userPresetsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildGame[]
     */
    protected $ownedGamesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCircuitPlayer[]
     */
    protected $circuitPlayersRelatedByPlayerIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCircuitPlayer[]
     */
    protected $circuitPlayersRelatedByTargetIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerGroup[]
     */
    protected $playerGroupsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLtsGame[]
     */
    protected $ltsGamesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLtsCircuitPlayer[]
     */
    protected $ltsCircuitPlayersRelatedByPlayerIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLtsCircuitPlayer[]
     */
    protected $ltsCircuitPlayersRelatedByTargetIdScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->money = 0;
        $this->total_money = 0;
        $this->active = false;
    }

    /**
     * Initializes internal state of Base\User object.
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
     * Compares this with another <code>User</code> instance.  If
     * <code>obj</code> is an instance of <code>User</code>, delegates to
     * <code>equals(User)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|User The current object, for fluid interface
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
     * Get the [email] column value.
     * 
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the [username] column value.
     * 
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the [real_name] column value.
     * 
     * @return string
     */
    public function getRealName()
    {
        return $this->real_name;
    }

    /**
     * Get the [password] column value.
     * 
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the [money] column value.
     * 
     * @return int
     */
    public function getMoney()
    {
        return $this->money;
    }

    /**
     * Get the [total_money] column value.
     * 
     * @return int
     */
    public function getTotalMoney()
    {
        return $this->total_money;
    }

    /**
     * Get the [verification_token] column value.
     * 
     * @return string
     */
    public function getVerificationToken()
    {
        return $this->verification_token;
    }

    /**
     * Get the [cookie_token] column value.
     * 
     * @return string
     */
    public function getCookieToken()
    {
        return $this->cookie_token;
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
     * Get the [optionally formatted] temporal [date_created] column value.
     * 
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDateCreated($format = NULL)
    {
        if ($format === null) {
            return $this->date_created;
        } else {
            return $this->date_created instanceof \DateTimeInterface ? $this->date_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [verification_time] column value.
     * 
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getVerificationTime($format = NULL)
    {
        if ($format === null) {
            return $this->verification_time;
        } else {
            return $this->verification_time instanceof \DateTimeInterface ? $this->verification_time->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     * 
     * @param int $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[UserTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [email] column.
     * 
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[UserTableMap::COL_EMAIL] = true;
        }

        return $this;
    } // setEmail()

    /**
     * Set the value of [username] column.
     * 
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->username !== $v) {
            $this->username = $v;
            $this->modifiedColumns[UserTableMap::COL_USERNAME] = true;
        }

        return $this;
    } // setUsername()

    /**
     * Set the value of [real_name] column.
     * 
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setRealName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->real_name !== $v) {
            $this->real_name = $v;
            $this->modifiedColumns[UserTableMap::COL_REAL_NAME] = true;
        }

        return $this;
    } // setRealName()

    /**
     * Set the value of [password] column.
     * 
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[UserTableMap::COL_PASSWORD] = true;
        }

        return $this;
    } // setPassword()

    /**
     * Set the value of [money] column.
     * 
     * @param int $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setMoney($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->money !== $v) {
            $this->money = $v;
            $this->modifiedColumns[UserTableMap::COL_MONEY] = true;
        }

        return $this;
    } // setMoney()

    /**
     * Set the value of [total_money] column.
     * 
     * @param int $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setTotalMoney($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->total_money !== $v) {
            $this->total_money = $v;
            $this->modifiedColumns[UserTableMap::COL_TOTAL_MONEY] = true;
        }

        return $this;
    } // setTotalMoney()

    /**
     * Set the value of [verification_token] column.
     * 
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setVerificationToken($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->verification_token !== $v) {
            $this->verification_token = $v;
            $this->modifiedColumns[UserTableMap::COL_VERIFICATION_TOKEN] = true;
        }

        return $this;
    } // setVerificationToken()

    /**
     * Set the value of [cookie_token] column.
     * 
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setCookieToken($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->cookie_token !== $v) {
            $this->cookie_token = $v;
            $this->modifiedColumns[UserTableMap::COL_COOKIE_TOKEN] = true;
        }

        return $this;
    } // setCookieToken()

    /**
     * Sets the value of the [active] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * 
     * @param  boolean|integer|string $v The new value
     * @return $this|\User The current object (for fluent API support)
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
            $this->modifiedColumns[UserTableMap::COL_ACTIVE] = true;
        }

        return $this;
    } // setActive()

    /**
     * Sets the value of [date_created] column to a normalized version of the date/time value specified.
     * 
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\User The current object (for fluent API support)
     */
    public function setDateCreated($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date_created !== null || $dt !== null) {
            if ($this->date_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->date_created->format("Y-m-d H:i:s.u")) {
                $this->date_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_DATE_CREATED] = true;
            }
        } // if either are not null

        return $this;
    } // setDateCreated()

    /**
     * Sets the value of [verification_time] column to a normalized version of the date/time value specified.
     * 
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\User The current object (for fluent API support)
     */
    public function setVerificationTime($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->verification_time !== null || $dt !== null) {
            if ($this->verification_time === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->verification_time->format("Y-m-d H:i:s.u")) {
                $this->verification_time = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_VERIFICATION_TIME] = true;
            }
        } // if either are not null

        return $this;
    } // setVerificationTime()

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
            if ($this->money !== 0) {
                return false;
            }

            if ($this->total_money !== 0) {
                return false;
            }

            if ($this->active !== false) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UserTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UserTableMap::translateFieldName('Username', TableMap::TYPE_PHPNAME, $indexType)];
            $this->username = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : UserTableMap::translateFieldName('RealName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->real_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : UserTableMap::translateFieldName('Password', TableMap::TYPE_PHPNAME, $indexType)];
            $this->password = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : UserTableMap::translateFieldName('Money', TableMap::TYPE_PHPNAME, $indexType)];
            $this->money = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : UserTableMap::translateFieldName('TotalMoney', TableMap::TYPE_PHPNAME, $indexType)];
            $this->total_money = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : UserTableMap::translateFieldName('VerificationToken', TableMap::TYPE_PHPNAME, $indexType)];
            $this->verification_token = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : UserTableMap::translateFieldName('CookieToken', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cookie_token = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : UserTableMap::translateFieldName('Active', TableMap::TYPE_PHPNAME, $indexType)];
            $this->active = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : UserTableMap::translateFieldName('DateCreated', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->date_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : UserTableMap::translateFieldName('VerificationTime', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->verification_time = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 12; // 12 = UserTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\User'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildUserQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->singlePreference = null;

            $this->collUserGames = null;

            $this->collUserPresets = null;

            $this->collOwnedGames = null;

            $this->collCircuitPlayersRelatedByPlayerId = null;

            $this->collCircuitPlayersRelatedByTargetId = null;

            $this->collPlayerGroups = null;

            $this->collLtsGames = null;

            $this->collLtsCircuitPlayersRelatedByPlayerId = null;

            $this->collLtsCircuitPlayersRelatedByTargetId = null;

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
     * @see User::setDeleted()
     * @see User::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildUserQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
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
                UserTableMap::addInstanceToPool($this);
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

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \UserGameQuery::create()
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

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \UserPresetQuery::create()
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


            if ($this->singlePreference !== null) {
                if (!$this->singlePreference->isDeleted() && ($this->singlePreference->isNew() || $this->singlePreference->isModified())) {
                    $affectedRows += $this->singlePreference->save($con);
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

            if ($this->userPresetsScheduledForDeletion !== null) {
                if (!$this->userPresetsScheduledForDeletion->isEmpty()) {
                    \UserPresetQuery::create()
                        ->filterByPrimaryKeys($this->userPresetsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userPresetsScheduledForDeletion = null;
                }
            }

            if ($this->collUserPresets !== null) {
                foreach ($this->collUserPresets as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->ownedGamesScheduledForDeletion !== null) {
                if (!$this->ownedGamesScheduledForDeletion->isEmpty()) {
                    \GameQuery::create()
                        ->filterByPrimaryKeys($this->ownedGamesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->ownedGamesScheduledForDeletion = null;
                }
            }

            if ($this->collOwnedGames !== null) {
                foreach ($this->collOwnedGames as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->circuitPlayersRelatedByPlayerIdScheduledForDeletion !== null) {
                if (!$this->circuitPlayersRelatedByPlayerIdScheduledForDeletion->isEmpty()) {
                    \CircuitPlayerQuery::create()
                        ->filterByPrimaryKeys($this->circuitPlayersRelatedByPlayerIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->circuitPlayersRelatedByPlayerIdScheduledForDeletion = null;
                }
            }

            if ($this->collCircuitPlayersRelatedByPlayerId !== null) {
                foreach ($this->collCircuitPlayersRelatedByPlayerId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->circuitPlayersRelatedByTargetIdScheduledForDeletion !== null) {
                if (!$this->circuitPlayersRelatedByTargetIdScheduledForDeletion->isEmpty()) {
                    \CircuitPlayerQuery::create()
                        ->filterByPrimaryKeys($this->circuitPlayersRelatedByTargetIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->circuitPlayersRelatedByTargetIdScheduledForDeletion = null;
                }
            }

            if ($this->collCircuitPlayersRelatedByTargetId !== null) {
                foreach ($this->collCircuitPlayersRelatedByTargetId as $referrerFK) {
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

            if ($this->ltsGamesScheduledForDeletion !== null) {
                if (!$this->ltsGamesScheduledForDeletion->isEmpty()) {
                    \LtsGameQuery::create()
                        ->filterByPrimaryKeys($this->ltsGamesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->ltsGamesScheduledForDeletion = null;
                }
            }

            if ($this->collLtsGames !== null) {
                foreach ($this->collLtsGames as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->ltsCircuitPlayersRelatedByPlayerIdScheduledForDeletion !== null) {
                if (!$this->ltsCircuitPlayersRelatedByPlayerIdScheduledForDeletion->isEmpty()) {
                    \LtsCircuitPlayerQuery::create()
                        ->filterByPrimaryKeys($this->ltsCircuitPlayersRelatedByPlayerIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->ltsCircuitPlayersRelatedByPlayerIdScheduledForDeletion = null;
                }
            }

            if ($this->collLtsCircuitPlayersRelatedByPlayerId !== null) {
                foreach ($this->collLtsCircuitPlayersRelatedByPlayerId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->ltsCircuitPlayersRelatedByTargetIdScheduledForDeletion !== null) {
                if (!$this->ltsCircuitPlayersRelatedByTargetIdScheduledForDeletion->isEmpty()) {
                    \LtsCircuitPlayerQuery::create()
                        ->filterByPrimaryKeys($this->ltsCircuitPlayersRelatedByTargetIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->ltsCircuitPlayersRelatedByTargetIdScheduledForDeletion = null;
                }
            }

            if ($this->collLtsCircuitPlayersRelatedByTargetId !== null) {
                foreach ($this->collLtsCircuitPlayersRelatedByTargetId as $referrerFK) {
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

        $this->modifiedColumns[UserTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = '`email`';
        }
        if ($this->isColumnModified(UserTableMap::COL_USERNAME)) {
            $modifiedColumns[':p' . $index++]  = '`username`';
        }
        if ($this->isColumnModified(UserTableMap::COL_REAL_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`real_name`';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = '`password`';
        }
        if ($this->isColumnModified(UserTableMap::COL_MONEY)) {
            $modifiedColumns[':p' . $index++]  = '`money`';
        }
        if ($this->isColumnModified(UserTableMap::COL_TOTAL_MONEY)) {
            $modifiedColumns[':p' . $index++]  = '`total_money`';
        }
        if ($this->isColumnModified(UserTableMap::COL_VERIFICATION_TOKEN)) {
            $modifiedColumns[':p' . $index++]  = '`verification_token`';
        }
        if ($this->isColumnModified(UserTableMap::COL_COOKIE_TOKEN)) {
            $modifiedColumns[':p' . $index++]  = '`cookie_token`';
        }
        if ($this->isColumnModified(UserTableMap::COL_ACTIVE)) {
            $modifiedColumns[':p' . $index++]  = '`active`';
        }
        if ($this->isColumnModified(UserTableMap::COL_DATE_CREATED)) {
            $modifiedColumns[':p' . $index++]  = '`date_created`';
        }
        if ($this->isColumnModified(UserTableMap::COL_VERIFICATION_TIME)) {
            $modifiedColumns[':p' . $index++]  = '`verification_time`';
        }

        $sql = sprintf(
            'INSERT INTO `users` (%s) VALUES (%s)',
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
                    case '`email`':                        
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case '`username`':                        
                        $stmt->bindValue($identifier, $this->username, PDO::PARAM_STR);
                        break;
                    case '`real_name`':                        
                        $stmt->bindValue($identifier, $this->real_name, PDO::PARAM_STR);
                        break;
                    case '`password`':                        
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
                        break;
                    case '`money`':                        
                        $stmt->bindValue($identifier, $this->money, PDO::PARAM_INT);
                        break;
                    case '`total_money`':                        
                        $stmt->bindValue($identifier, $this->total_money, PDO::PARAM_INT);
                        break;
                    case '`verification_token`':                        
                        $stmt->bindValue($identifier, $this->verification_token, PDO::PARAM_STR);
                        break;
                    case '`cookie_token`':                        
                        $stmt->bindValue($identifier, $this->cookie_token, PDO::PARAM_STR);
                        break;
                    case '`active`':
                        $stmt->bindValue($identifier, (int) $this->active, PDO::PARAM_INT);
                        break;
                    case '`date_created`':                        
                        $stmt->bindValue($identifier, $this->date_created ? $this->date_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case '`verification_time`':                        
                        $stmt->bindValue($identifier, $this->verification_time ? $this->verification_time->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
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
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getEmail();
                break;
            case 2:
                return $this->getUsername();
                break;
            case 3:
                return $this->getRealName();
                break;
            case 4:
                return $this->getPassword();
                break;
            case 5:
                return $this->getMoney();
                break;
            case 6:
                return $this->getTotalMoney();
                break;
            case 7:
                return $this->getVerificationToken();
                break;
            case 8:
                return $this->getCookieToken();
                break;
            case 9:
                return $this->getActive();
                break;
            case 10:
                return $this->getDateCreated();
                break;
            case 11:
                return $this->getVerificationTime();
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

        if (isset($alreadyDumpedObjects['User'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->hashCode()] = true;
        $keys = UserTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getEmail(),
            $keys[2] => $this->getUsername(),
            $keys[3] => $this->getRealName(),
            $keys[4] => $this->getPassword(),
            $keys[5] => $this->getMoney(),
            $keys[6] => $this->getTotalMoney(),
            $keys[7] => $this->getVerificationToken(),
            $keys[8] => $this->getCookieToken(),
            $keys[9] => $this->getActive(),
            $keys[10] => $this->getDateCreated(),
            $keys[11] => $this->getVerificationTime(),
        );
        if ($result[$keys[10]] instanceof \DateTime) {
            $result[$keys[10]] = $result[$keys[10]]->format('c');
        }
        
        if ($result[$keys[11]] instanceof \DateTime) {
            $result[$keys[11]] = $result[$keys[11]]->format('c');
        }
        
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }
        
        if ($includeForeignObjects) {
            if (null !== $this->singlePreference) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'preference';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'preferences';
                        break;
                    default:
                        $key = 'Preference';
                }
        
                $result[$key] = $this->singlePreference->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
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
            if (null !== $this->collUserPresets) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userPresets';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_presetss';
                        break;
                    default:
                        $key = 'UserPresets';
                }
        
                $result[$key] = $this->collUserPresets->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collOwnedGames) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'games';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'gamess';
                        break;
                    default:
                        $key = 'OwnedGames';
                }
        
                $result[$key] = $this->collOwnedGames->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCircuitPlayersRelatedByPlayerId) {
                
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
        
                $result[$key] = $this->collCircuitPlayersRelatedByPlayerId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCircuitPlayersRelatedByTargetId) {
                
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
        
                $result[$key] = $this->collCircuitPlayersRelatedByTargetId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
            if (null !== $this->collLtsGames) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'ltsGames';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'lts_gamess';
                        break;
                    default:
                        $key = 'LtsGames';
                }
        
                $result[$key] = $this->collLtsGames->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collLtsCircuitPlayersRelatedByPlayerId) {
                
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
        
                $result[$key] = $this->collLtsCircuitPlayersRelatedByPlayerId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collLtsCircuitPlayersRelatedByTargetId) {
                
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
        
                $result[$key] = $this->collLtsCircuitPlayersRelatedByTargetId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\User
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\User
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setEmail($value);
                break;
            case 2:
                $this->setUsername($value);
                break;
            case 3:
                $this->setRealName($value);
                break;
            case 4:
                $this->setPassword($value);
                break;
            case 5:
                $this->setMoney($value);
                break;
            case 6:
                $this->setTotalMoney($value);
                break;
            case 7:
                $this->setVerificationToken($value);
                break;
            case 8:
                $this->setCookieToken($value);
                break;
            case 9:
                $this->setActive($value);
                break;
            case 10:
                $this->setDateCreated($value);
                break;
            case 11:
                $this->setVerificationTime($value);
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
        $keys = UserTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setEmail($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setUsername($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setRealName($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setPassword($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setMoney($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setTotalMoney($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setVerificationToken($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setCookieToken($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setActive($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setDateCreated($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setVerificationTime($arr[$keys[11]]);
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
     * @return $this|\User The current object, for fluid interface
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
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $criteria->add(UserTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $criteria->add(UserTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(UserTableMap::COL_USERNAME)) {
            $criteria->add(UserTableMap::COL_USERNAME, $this->username);
        }
        if ($this->isColumnModified(UserTableMap::COL_REAL_NAME)) {
            $criteria->add(UserTableMap::COL_REAL_NAME, $this->real_name);
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $criteria->add(UserTableMap::COL_PASSWORD, $this->password);
        }
        if ($this->isColumnModified(UserTableMap::COL_MONEY)) {
            $criteria->add(UserTableMap::COL_MONEY, $this->money);
        }
        if ($this->isColumnModified(UserTableMap::COL_TOTAL_MONEY)) {
            $criteria->add(UserTableMap::COL_TOTAL_MONEY, $this->total_money);
        }
        if ($this->isColumnModified(UserTableMap::COL_VERIFICATION_TOKEN)) {
            $criteria->add(UserTableMap::COL_VERIFICATION_TOKEN, $this->verification_token);
        }
        if ($this->isColumnModified(UserTableMap::COL_COOKIE_TOKEN)) {
            $criteria->add(UserTableMap::COL_COOKIE_TOKEN, $this->cookie_token);
        }
        if ($this->isColumnModified(UserTableMap::COL_ACTIVE)) {
            $criteria->add(UserTableMap::COL_ACTIVE, $this->active);
        }
        if ($this->isColumnModified(UserTableMap::COL_DATE_CREATED)) {
            $criteria->add(UserTableMap::COL_DATE_CREATED, $this->date_created);
        }
        if ($this->isColumnModified(UserTableMap::COL_VERIFICATION_TIME)) {
            $criteria->add(UserTableMap::COL_VERIFICATION_TIME, $this->verification_time);
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
        $criteria = ChildUserQuery::create();
        $criteria->add(UserTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \User (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setEmail($this->getEmail());
        $copyObj->setUsername($this->getUsername());
        $copyObj->setRealName($this->getRealName());
        $copyObj->setPassword($this->getPassword());
        $copyObj->setMoney($this->getMoney());
        $copyObj->setTotalMoney($this->getTotalMoney());
        $copyObj->setVerificationToken($this->getVerificationToken());
        $copyObj->setCookieToken($this->getCookieToken());
        $copyObj->setActive($this->getActive());
        $copyObj->setDateCreated($this->getDateCreated());
        $copyObj->setVerificationTime($this->getVerificationTime());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            $relObj = $this->getPreference();
            if ($relObj) {
                $copyObj->setPreference($relObj->copy($deepCopy));
            }

            foreach ($this->getUserGames() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserGame($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserPresets() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserPreset($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOwnedGames() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOwnedGame($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCircuitPlayersRelatedByPlayerId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCircuitPlayerRelatedByPlayerId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCircuitPlayersRelatedByTargetId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCircuitPlayerRelatedByTargetId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerGroups() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerGroup($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLtsGames() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLtsGame($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLtsCircuitPlayersRelatedByPlayerId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLtsCircuitPlayerRelatedByPlayerId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLtsCircuitPlayersRelatedByTargetId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLtsCircuitPlayerRelatedByTargetId($relObj->copy($deepCopy));
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
     * @return \User Clone of current object.
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
        if ('UserGame' == $relationName) {
            return $this->initUserGames();
        }
        if ('UserPreset' == $relationName) {
            return $this->initUserPresets();
        }
        if ('OwnedGame' == $relationName) {
            return $this->initOwnedGames();
        }
        if ('CircuitPlayerRelatedByPlayerId' == $relationName) {
            return $this->initCircuitPlayersRelatedByPlayerId();
        }
        if ('CircuitPlayerRelatedByTargetId' == $relationName) {
            return $this->initCircuitPlayersRelatedByTargetId();
        }
        if ('PlayerGroup' == $relationName) {
            return $this->initPlayerGroups();
        }
        if ('LtsGame' == $relationName) {
            return $this->initLtsGames();
        }
        if ('LtsCircuitPlayerRelatedByPlayerId' == $relationName) {
            return $this->initLtsCircuitPlayersRelatedByPlayerId();
        }
        if ('LtsCircuitPlayerRelatedByTargetId' == $relationName) {
            return $this->initLtsCircuitPlayersRelatedByTargetId();
        }
    }

    /**
     * Gets a single ChildPreference object, which is related to this object by a one-to-one relationship.
     *
     * @param  ConnectionInterface $con optional connection object
     * @return ChildPreference
     * @throws PropelException
     */
    public function getPreference(ConnectionInterface $con = null)
    {

        if ($this->singlePreference === null && !$this->isNew()) {
            $this->singlePreference = ChildPreferenceQuery::create()->findPk($this->getPrimaryKey(), $con);
        }

        return $this->singlePreference;
    }

    /**
     * Sets a single ChildPreference object as related to this object by a one-to-one relationship.
     *
     * @param  ChildPreference $v ChildPreference
     * @return $this|\User The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPreference(ChildPreference $v = null)
    {
        $this->singlePreference = $v;

        // Make sure that that the passed-in ChildPreference isn't already associated with this object
        if ($v !== null && $v->getUser(null, false) === null) {
            $v->setUser($this);
        }

        return $this;
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
     * If this ChildUser is new, it will return
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
                    ->filterByUser($this)
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
     * @return $this|ChildUser The current object (for fluent API support)
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
            $userGameRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collUserGames);
    }

    /**
     * Method called to associate a ChildUserGame object to this object
     * through the ChildUserGame foreign key attribute.
     *
     * @param  ChildUserGame $l ChildUserGame
     * @return $this|\User The current object (for fluent API support)
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
        $userGame->setUser($this);
    }

    /**
     * @param  ChildUserGame $userGame The ChildUserGame object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
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
            $userGame->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related UserGames from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUserGame[] List of ChildUserGame objects
     */
    public function getUserGamesJoinGame(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserGameQuery::create(null, $criteria);
        $query->joinWith('Game', $joinBehavior);

        return $this->getUserGames($query, $con);
    }

    /**
     * Clears out the collUserPresets collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserPresets()
     */
    public function clearUserPresets()
    {
        $this->collUserPresets = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserPresets collection loaded partially.
     */
    public function resetPartialUserPresets($v = true)
    {
        $this->collUserPresetsPartial = $v;
    }

    /**
     * Initializes the collUserPresets collection.
     *
     * By default this just sets the collUserPresets collection to an empty array (like clearcollUserPresets());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserPresets($overrideExisting = true)
    {
        if (null !== $this->collUserPresets && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserPresetTableMap::getTableMap()->getCollectionClassName();

        $this->collUserPresets = new $collectionClassName;
        $this->collUserPresets->setModel('\UserPreset');
    }

    /**
     * Gets an array of ChildUserPreset objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserPreset[] List of ChildUserPreset objects
     * @throws PropelException
     */
    public function getUserPresets(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserPresetsPartial && !$this->isNew();
        if (null === $this->collUserPresets || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserPresets) {
                // return empty collection
                $this->initUserPresets();
            } else {
                $collUserPresets = ChildUserPresetQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserPresetsPartial && count($collUserPresets)) {
                        $this->initUserPresets(false);

                        foreach ($collUserPresets as $obj) {
                            if (false == $this->collUserPresets->contains($obj)) {
                                $this->collUserPresets->append($obj);
                            }
                        }

                        $this->collUserPresetsPartial = true;
                    }

                    return $collUserPresets;
                }

                if ($partial && $this->collUserPresets) {
                    foreach ($this->collUserPresets as $obj) {
                        if ($obj->isNew()) {
                            $collUserPresets[] = $obj;
                        }
                    }
                }

                $this->collUserPresets = $collUserPresets;
                $this->collUserPresetsPartial = false;
            }
        }

        return $this->collUserPresets;
    }

    /**
     * Sets a collection of ChildUserPreset objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userPresets A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setUserPresets(Collection $userPresets, ConnectionInterface $con = null)
    {
        /** @var ChildUserPreset[] $userPresetsToDelete */
        $userPresetsToDelete = $this->getUserPresets(new Criteria(), $con)->diff($userPresets);

        
        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userPresetsScheduledForDeletion = clone $userPresetsToDelete;

        foreach ($userPresetsToDelete as $userPresetRemoved) {
            $userPresetRemoved->setUser(null);
        }

        $this->collUserPresets = null;
        foreach ($userPresets as $userPreset) {
            $this->addUserPreset($userPreset);
        }

        $this->collUserPresets = $userPresets;
        $this->collUserPresetsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserPreset objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserPreset objects.
     * @throws PropelException
     */
    public function countUserPresets(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserPresetsPartial && !$this->isNew();
        if (null === $this->collUserPresets || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserPresets) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserPresets());
            }

            $query = ChildUserPresetQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collUserPresets);
    }

    /**
     * Method called to associate a ChildUserPreset object to this object
     * through the ChildUserPreset foreign key attribute.
     *
     * @param  ChildUserPreset $l ChildUserPreset
     * @return $this|\User The current object (for fluent API support)
     */
    public function addUserPreset(ChildUserPreset $l)
    {
        if ($this->collUserPresets === null) {
            $this->initUserPresets();
            $this->collUserPresetsPartial = true;
        }

        if (!$this->collUserPresets->contains($l)) {
            $this->doAddUserPreset($l);

            if ($this->userPresetsScheduledForDeletion and $this->userPresetsScheduledForDeletion->contains($l)) {
                $this->userPresetsScheduledForDeletion->remove($this->userPresetsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUserPreset $userPreset The ChildUserPreset object to add.
     */
    protected function doAddUserPreset(ChildUserPreset $userPreset)
    {
        $this->collUserPresets[]= $userPreset;
        $userPreset->setUser($this);
    }

    /**
     * @param  ChildUserPreset $userPreset The ChildUserPreset object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeUserPreset(ChildUserPreset $userPreset)
    {
        if ($this->getUserPresets()->contains($userPreset)) {
            $pos = $this->collUserPresets->search($userPreset);
            $this->collUserPresets->remove($pos);
            if (null === $this->userPresetsScheduledForDeletion) {
                $this->userPresetsScheduledForDeletion = clone $this->collUserPresets;
                $this->userPresetsScheduledForDeletion->clear();
            }
            $this->userPresetsScheduledForDeletion[]= clone $userPreset;
            $userPreset->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related UserPresets from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUserPreset[] List of ChildUserPreset objects
     */
    public function getUserPresetsJoinPreset(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserPresetQuery::create(null, $criteria);
        $query->joinWith('Preset', $joinBehavior);

        return $this->getUserPresets($query, $con);
    }

    /**
     * Clears out the collOwnedGames collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addOwnedGames()
     */
    public function clearOwnedGames()
    {
        $this->collOwnedGames = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collOwnedGames collection loaded partially.
     */
    public function resetPartialOwnedGames($v = true)
    {
        $this->collOwnedGamesPartial = $v;
    }

    /**
     * Initializes the collOwnedGames collection.
     *
     * By default this just sets the collOwnedGames collection to an empty array (like clearcollOwnedGames());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOwnedGames($overrideExisting = true)
    {
        if (null !== $this->collOwnedGames && !$overrideExisting) {
            return;
        }

        $collectionClassName = GameTableMap::getTableMap()->getCollectionClassName();

        $this->collOwnedGames = new $collectionClassName;
        $this->collOwnedGames->setModel('\Game');
    }

    /**
     * Gets an array of ChildGame objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildGame[] List of ChildGame objects
     * @throws PropelException
     */
    public function getOwnedGames(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collOwnedGamesPartial && !$this->isNew();
        if (null === $this->collOwnedGames || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collOwnedGames) {
                // return empty collection
                $this->initOwnedGames();
            } else {
                $collOwnedGames = ChildGameQuery::create(null, $criteria)
                    ->filterByOwner($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collOwnedGamesPartial && count($collOwnedGames)) {
                        $this->initOwnedGames(false);

                        foreach ($collOwnedGames as $obj) {
                            if (false == $this->collOwnedGames->contains($obj)) {
                                $this->collOwnedGames->append($obj);
                            }
                        }

                        $this->collOwnedGamesPartial = true;
                    }

                    return $collOwnedGames;
                }

                if ($partial && $this->collOwnedGames) {
                    foreach ($this->collOwnedGames as $obj) {
                        if ($obj->isNew()) {
                            $collOwnedGames[] = $obj;
                        }
                    }
                }

                $this->collOwnedGames = $collOwnedGames;
                $this->collOwnedGamesPartial = false;
            }
        }

        return $this->collOwnedGames;
    }

    /**
     * Sets a collection of ChildGame objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $ownedGames A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setOwnedGames(Collection $ownedGames, ConnectionInterface $con = null)
    {
        /** @var ChildGame[] $ownedGamesToDelete */
        $ownedGamesToDelete = $this->getOwnedGames(new Criteria(), $con)->diff($ownedGames);

        
        $this->ownedGamesScheduledForDeletion = $ownedGamesToDelete;

        foreach ($ownedGamesToDelete as $ownedGameRemoved) {
            $ownedGameRemoved->setOwner(null);
        }

        $this->collOwnedGames = null;
        foreach ($ownedGames as $ownedGame) {
            $this->addOwnedGame($ownedGame);
        }

        $this->collOwnedGames = $ownedGames;
        $this->collOwnedGamesPartial = false;

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
    public function countOwnedGames(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collOwnedGamesPartial && !$this->isNew();
        if (null === $this->collOwnedGames || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOwnedGames) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOwnedGames());
            }

            $query = ChildGameQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOwner($this)
                ->count($con);
        }

        return count($this->collOwnedGames);
    }

    /**
     * Method called to associate a ChildGame object to this object
     * through the ChildGame foreign key attribute.
     *
     * @param  ChildGame $l ChildGame
     * @return $this|\User The current object (for fluent API support)
     */
    public function addOwnedGame(ChildGame $l)
    {
        if ($this->collOwnedGames === null) {
            $this->initOwnedGames();
            $this->collOwnedGamesPartial = true;
        }

        if (!$this->collOwnedGames->contains($l)) {
            $this->doAddOwnedGame($l);

            if ($this->ownedGamesScheduledForDeletion and $this->ownedGamesScheduledForDeletion->contains($l)) {
                $this->ownedGamesScheduledForDeletion->remove($this->ownedGamesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildGame $ownedGame The ChildGame object to add.
     */
    protected function doAddOwnedGame(ChildGame $ownedGame)
    {
        $this->collOwnedGames[]= $ownedGame;
        $ownedGame->setOwner($this);
    }

    /**
     * @param  ChildGame $ownedGame The ChildGame object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeOwnedGame(ChildGame $ownedGame)
    {
        if ($this->getOwnedGames()->contains($ownedGame)) {
            $pos = $this->collOwnedGames->search($ownedGame);
            $this->collOwnedGames->remove($pos);
            if (null === $this->ownedGamesScheduledForDeletion) {
                $this->ownedGamesScheduledForDeletion = clone $this->collOwnedGames;
                $this->ownedGamesScheduledForDeletion->clear();
            }
            $this->ownedGamesScheduledForDeletion[]= clone $ownedGame;
            $ownedGame->setOwner(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related OwnedGames from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildGame[] List of ChildGame objects
     */
    public function getOwnedGamesJoinAutoJoinGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildGameQuery::create(null, $criteria);
        $query->joinWith('AutoJoinGroup', $joinBehavior);

        return $this->getOwnedGames($query, $con);
    }

    /**
     * Clears out the collCircuitPlayersRelatedByPlayerId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCircuitPlayersRelatedByPlayerId()
     */
    public function clearCircuitPlayersRelatedByPlayerId()
    {
        $this->collCircuitPlayersRelatedByPlayerId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCircuitPlayersRelatedByPlayerId collection loaded partially.
     */
    public function resetPartialCircuitPlayersRelatedByPlayerId($v = true)
    {
        $this->collCircuitPlayersRelatedByPlayerIdPartial = $v;
    }

    /**
     * Initializes the collCircuitPlayersRelatedByPlayerId collection.
     *
     * By default this just sets the collCircuitPlayersRelatedByPlayerId collection to an empty array (like clearcollCircuitPlayersRelatedByPlayerId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCircuitPlayersRelatedByPlayerId($overrideExisting = true)
    {
        if (null !== $this->collCircuitPlayersRelatedByPlayerId && !$overrideExisting) {
            return;
        }

        $collectionClassName = CircuitPlayerTableMap::getTableMap()->getCollectionClassName();

        $this->collCircuitPlayersRelatedByPlayerId = new $collectionClassName;
        $this->collCircuitPlayersRelatedByPlayerId->setModel('\CircuitPlayer');
    }

    /**
     * Gets an array of ChildCircuitPlayer objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCircuitPlayer[] List of ChildCircuitPlayer objects
     * @throws PropelException
     */
    public function getCircuitPlayersRelatedByPlayerId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCircuitPlayersRelatedByPlayerIdPartial && !$this->isNew();
        if (null === $this->collCircuitPlayersRelatedByPlayerId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCircuitPlayersRelatedByPlayerId) {
                // return empty collection
                $this->initCircuitPlayersRelatedByPlayerId();
            } else {
                $collCircuitPlayersRelatedByPlayerId = ChildCircuitPlayerQuery::create(null, $criteria)
                    ->filterByPlayer($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCircuitPlayersRelatedByPlayerIdPartial && count($collCircuitPlayersRelatedByPlayerId)) {
                        $this->initCircuitPlayersRelatedByPlayerId(false);

                        foreach ($collCircuitPlayersRelatedByPlayerId as $obj) {
                            if (false == $this->collCircuitPlayersRelatedByPlayerId->contains($obj)) {
                                $this->collCircuitPlayersRelatedByPlayerId->append($obj);
                            }
                        }

                        $this->collCircuitPlayersRelatedByPlayerIdPartial = true;
                    }

                    return $collCircuitPlayersRelatedByPlayerId;
                }

                if ($partial && $this->collCircuitPlayersRelatedByPlayerId) {
                    foreach ($this->collCircuitPlayersRelatedByPlayerId as $obj) {
                        if ($obj->isNew()) {
                            $collCircuitPlayersRelatedByPlayerId[] = $obj;
                        }
                    }
                }

                $this->collCircuitPlayersRelatedByPlayerId = $collCircuitPlayersRelatedByPlayerId;
                $this->collCircuitPlayersRelatedByPlayerIdPartial = false;
            }
        }

        return $this->collCircuitPlayersRelatedByPlayerId;
    }

    /**
     * Sets a collection of ChildCircuitPlayer objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $circuitPlayersRelatedByPlayerId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setCircuitPlayersRelatedByPlayerId(Collection $circuitPlayersRelatedByPlayerId, ConnectionInterface $con = null)
    {
        /** @var ChildCircuitPlayer[] $circuitPlayersRelatedByPlayerIdToDelete */
        $circuitPlayersRelatedByPlayerIdToDelete = $this->getCircuitPlayersRelatedByPlayerId(new Criteria(), $con)->diff($circuitPlayersRelatedByPlayerId);

        
        $this->circuitPlayersRelatedByPlayerIdScheduledForDeletion = $circuitPlayersRelatedByPlayerIdToDelete;

        foreach ($circuitPlayersRelatedByPlayerIdToDelete as $circuitPlayerRelatedByPlayerIdRemoved) {
            $circuitPlayerRelatedByPlayerIdRemoved->setPlayer(null);
        }

        $this->collCircuitPlayersRelatedByPlayerId = null;
        foreach ($circuitPlayersRelatedByPlayerId as $circuitPlayerRelatedByPlayerId) {
            $this->addCircuitPlayerRelatedByPlayerId($circuitPlayerRelatedByPlayerId);
        }

        $this->collCircuitPlayersRelatedByPlayerId = $circuitPlayersRelatedByPlayerId;
        $this->collCircuitPlayersRelatedByPlayerIdPartial = false;

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
    public function countCircuitPlayersRelatedByPlayerId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCircuitPlayersRelatedByPlayerIdPartial && !$this->isNew();
        if (null === $this->collCircuitPlayersRelatedByPlayerId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCircuitPlayersRelatedByPlayerId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCircuitPlayersRelatedByPlayerId());
            }

            $query = ChildCircuitPlayerQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayer($this)
                ->count($con);
        }

        return count($this->collCircuitPlayersRelatedByPlayerId);
    }

    /**
     * Method called to associate a ChildCircuitPlayer object to this object
     * through the ChildCircuitPlayer foreign key attribute.
     *
     * @param  ChildCircuitPlayer $l ChildCircuitPlayer
     * @return $this|\User The current object (for fluent API support)
     */
    public function addCircuitPlayerRelatedByPlayerId(ChildCircuitPlayer $l)
    {
        if ($this->collCircuitPlayersRelatedByPlayerId === null) {
            $this->initCircuitPlayersRelatedByPlayerId();
            $this->collCircuitPlayersRelatedByPlayerIdPartial = true;
        }

        if (!$this->collCircuitPlayersRelatedByPlayerId->contains($l)) {
            $this->doAddCircuitPlayerRelatedByPlayerId($l);

            if ($this->circuitPlayersRelatedByPlayerIdScheduledForDeletion and $this->circuitPlayersRelatedByPlayerIdScheduledForDeletion->contains($l)) {
                $this->circuitPlayersRelatedByPlayerIdScheduledForDeletion->remove($this->circuitPlayersRelatedByPlayerIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildCircuitPlayer $circuitPlayerRelatedByPlayerId The ChildCircuitPlayer object to add.
     */
    protected function doAddCircuitPlayerRelatedByPlayerId(ChildCircuitPlayer $circuitPlayerRelatedByPlayerId)
    {
        $this->collCircuitPlayersRelatedByPlayerId[]= $circuitPlayerRelatedByPlayerId;
        $circuitPlayerRelatedByPlayerId->setPlayer($this);
    }

    /**
     * @param  ChildCircuitPlayer $circuitPlayerRelatedByPlayerId The ChildCircuitPlayer object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeCircuitPlayerRelatedByPlayerId(ChildCircuitPlayer $circuitPlayerRelatedByPlayerId)
    {
        if ($this->getCircuitPlayersRelatedByPlayerId()->contains($circuitPlayerRelatedByPlayerId)) {
            $pos = $this->collCircuitPlayersRelatedByPlayerId->search($circuitPlayerRelatedByPlayerId);
            $this->collCircuitPlayersRelatedByPlayerId->remove($pos);
            if (null === $this->circuitPlayersRelatedByPlayerIdScheduledForDeletion) {
                $this->circuitPlayersRelatedByPlayerIdScheduledForDeletion = clone $this->collCircuitPlayersRelatedByPlayerId;
                $this->circuitPlayersRelatedByPlayerIdScheduledForDeletion->clear();
            }
            $this->circuitPlayersRelatedByPlayerIdScheduledForDeletion[]= clone $circuitPlayerRelatedByPlayerId;
            $circuitPlayerRelatedByPlayerId->setPlayer(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related CircuitPlayersRelatedByPlayerId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCircuitPlayer[] List of ChildCircuitPlayer objects
     */
    public function getCircuitPlayersRelatedByPlayerIdJoinGame(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCircuitPlayerQuery::create(null, $criteria);
        $query->joinWith('Game', $joinBehavior);

        return $this->getCircuitPlayersRelatedByPlayerId($query, $con);
    }

    /**
     * Clears out the collCircuitPlayersRelatedByTargetId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCircuitPlayersRelatedByTargetId()
     */
    public function clearCircuitPlayersRelatedByTargetId()
    {
        $this->collCircuitPlayersRelatedByTargetId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCircuitPlayersRelatedByTargetId collection loaded partially.
     */
    public function resetPartialCircuitPlayersRelatedByTargetId($v = true)
    {
        $this->collCircuitPlayersRelatedByTargetIdPartial = $v;
    }

    /**
     * Initializes the collCircuitPlayersRelatedByTargetId collection.
     *
     * By default this just sets the collCircuitPlayersRelatedByTargetId collection to an empty array (like clearcollCircuitPlayersRelatedByTargetId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCircuitPlayersRelatedByTargetId($overrideExisting = true)
    {
        if (null !== $this->collCircuitPlayersRelatedByTargetId && !$overrideExisting) {
            return;
        }

        $collectionClassName = CircuitPlayerTableMap::getTableMap()->getCollectionClassName();

        $this->collCircuitPlayersRelatedByTargetId = new $collectionClassName;
        $this->collCircuitPlayersRelatedByTargetId->setModel('\CircuitPlayer');
    }

    /**
     * Gets an array of ChildCircuitPlayer objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCircuitPlayer[] List of ChildCircuitPlayer objects
     * @throws PropelException
     */
    public function getCircuitPlayersRelatedByTargetId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCircuitPlayersRelatedByTargetIdPartial && !$this->isNew();
        if (null === $this->collCircuitPlayersRelatedByTargetId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCircuitPlayersRelatedByTargetId) {
                // return empty collection
                $this->initCircuitPlayersRelatedByTargetId();
            } else {
                $collCircuitPlayersRelatedByTargetId = ChildCircuitPlayerQuery::create(null, $criteria)
                    ->filterByTarget($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCircuitPlayersRelatedByTargetIdPartial && count($collCircuitPlayersRelatedByTargetId)) {
                        $this->initCircuitPlayersRelatedByTargetId(false);

                        foreach ($collCircuitPlayersRelatedByTargetId as $obj) {
                            if (false == $this->collCircuitPlayersRelatedByTargetId->contains($obj)) {
                                $this->collCircuitPlayersRelatedByTargetId->append($obj);
                            }
                        }

                        $this->collCircuitPlayersRelatedByTargetIdPartial = true;
                    }

                    return $collCircuitPlayersRelatedByTargetId;
                }

                if ($partial && $this->collCircuitPlayersRelatedByTargetId) {
                    foreach ($this->collCircuitPlayersRelatedByTargetId as $obj) {
                        if ($obj->isNew()) {
                            $collCircuitPlayersRelatedByTargetId[] = $obj;
                        }
                    }
                }

                $this->collCircuitPlayersRelatedByTargetId = $collCircuitPlayersRelatedByTargetId;
                $this->collCircuitPlayersRelatedByTargetIdPartial = false;
            }
        }

        return $this->collCircuitPlayersRelatedByTargetId;
    }

    /**
     * Sets a collection of ChildCircuitPlayer objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $circuitPlayersRelatedByTargetId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setCircuitPlayersRelatedByTargetId(Collection $circuitPlayersRelatedByTargetId, ConnectionInterface $con = null)
    {
        /** @var ChildCircuitPlayer[] $circuitPlayersRelatedByTargetIdToDelete */
        $circuitPlayersRelatedByTargetIdToDelete = $this->getCircuitPlayersRelatedByTargetId(new Criteria(), $con)->diff($circuitPlayersRelatedByTargetId);

        
        $this->circuitPlayersRelatedByTargetIdScheduledForDeletion = $circuitPlayersRelatedByTargetIdToDelete;

        foreach ($circuitPlayersRelatedByTargetIdToDelete as $circuitPlayerRelatedByTargetIdRemoved) {
            $circuitPlayerRelatedByTargetIdRemoved->setTarget(null);
        }

        $this->collCircuitPlayersRelatedByTargetId = null;
        foreach ($circuitPlayersRelatedByTargetId as $circuitPlayerRelatedByTargetId) {
            $this->addCircuitPlayerRelatedByTargetId($circuitPlayerRelatedByTargetId);
        }

        $this->collCircuitPlayersRelatedByTargetId = $circuitPlayersRelatedByTargetId;
        $this->collCircuitPlayersRelatedByTargetIdPartial = false;

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
    public function countCircuitPlayersRelatedByTargetId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCircuitPlayersRelatedByTargetIdPartial && !$this->isNew();
        if (null === $this->collCircuitPlayersRelatedByTargetId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCircuitPlayersRelatedByTargetId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCircuitPlayersRelatedByTargetId());
            }

            $query = ChildCircuitPlayerQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTarget($this)
                ->count($con);
        }

        return count($this->collCircuitPlayersRelatedByTargetId);
    }

    /**
     * Method called to associate a ChildCircuitPlayer object to this object
     * through the ChildCircuitPlayer foreign key attribute.
     *
     * @param  ChildCircuitPlayer $l ChildCircuitPlayer
     * @return $this|\User The current object (for fluent API support)
     */
    public function addCircuitPlayerRelatedByTargetId(ChildCircuitPlayer $l)
    {
        if ($this->collCircuitPlayersRelatedByTargetId === null) {
            $this->initCircuitPlayersRelatedByTargetId();
            $this->collCircuitPlayersRelatedByTargetIdPartial = true;
        }

        if (!$this->collCircuitPlayersRelatedByTargetId->contains($l)) {
            $this->doAddCircuitPlayerRelatedByTargetId($l);

            if ($this->circuitPlayersRelatedByTargetIdScheduledForDeletion and $this->circuitPlayersRelatedByTargetIdScheduledForDeletion->contains($l)) {
                $this->circuitPlayersRelatedByTargetIdScheduledForDeletion->remove($this->circuitPlayersRelatedByTargetIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildCircuitPlayer $circuitPlayerRelatedByTargetId The ChildCircuitPlayer object to add.
     */
    protected function doAddCircuitPlayerRelatedByTargetId(ChildCircuitPlayer $circuitPlayerRelatedByTargetId)
    {
        $this->collCircuitPlayersRelatedByTargetId[]= $circuitPlayerRelatedByTargetId;
        $circuitPlayerRelatedByTargetId->setTarget($this);
    }

    /**
     * @param  ChildCircuitPlayer $circuitPlayerRelatedByTargetId The ChildCircuitPlayer object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeCircuitPlayerRelatedByTargetId(ChildCircuitPlayer $circuitPlayerRelatedByTargetId)
    {
        if ($this->getCircuitPlayersRelatedByTargetId()->contains($circuitPlayerRelatedByTargetId)) {
            $pos = $this->collCircuitPlayersRelatedByTargetId->search($circuitPlayerRelatedByTargetId);
            $this->collCircuitPlayersRelatedByTargetId->remove($pos);
            if (null === $this->circuitPlayersRelatedByTargetIdScheduledForDeletion) {
                $this->circuitPlayersRelatedByTargetIdScheduledForDeletion = clone $this->collCircuitPlayersRelatedByTargetId;
                $this->circuitPlayersRelatedByTargetIdScheduledForDeletion->clear();
            }
            $this->circuitPlayersRelatedByTargetIdScheduledForDeletion[]= clone $circuitPlayerRelatedByTargetId;
            $circuitPlayerRelatedByTargetId->setTarget(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related CircuitPlayersRelatedByTargetId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCircuitPlayer[] List of ChildCircuitPlayer objects
     */
    public function getCircuitPlayersRelatedByTargetIdJoinGame(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCircuitPlayerQuery::create(null, $criteria);
        $query->joinWith('Game', $joinBehavior);

        return $this->getCircuitPlayersRelatedByTargetId($query, $con);
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
     * If this ChildUser is new, it will return
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
                    ->filterByUser($this)
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
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setPlayerGroups(Collection $playerGroups, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerGroup[] $playerGroupsToDelete */
        $playerGroupsToDelete = $this->getPlayerGroups(new Criteria(), $con)->diff($playerGroups);

        
        $this->playerGroupsScheduledForDeletion = $playerGroupsToDelete;

        foreach ($playerGroupsToDelete as $playerGroupRemoved) {
            $playerGroupRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collPlayerGroups);
    }

    /**
     * Method called to associate a ChildPlayerGroup object to this object
     * through the ChildPlayerGroup foreign key attribute.
     *
     * @param  ChildPlayerGroup $l ChildPlayerGroup
     * @return $this|\User The current object (for fluent API support)
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
        $playerGroup->setUser($this);
    }

    /**
     * @param  ChildPlayerGroup $playerGroup The ChildPlayerGroup object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
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
            $playerGroup->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related PlayerGroups from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerGroup[] List of ChildPlayerGroup objects
     */
    public function getPlayerGroupsJoinGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerGroupQuery::create(null, $criteria);
        $query->joinWith('Group', $joinBehavior);

        return $this->getPlayerGroups($query, $con);
    }

    /**
     * Clears out the collLtsGames collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLtsGames()
     */
    public function clearLtsGames()
    {
        $this->collLtsGames = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collLtsGames collection loaded partially.
     */
    public function resetPartialLtsGames($v = true)
    {
        $this->collLtsGamesPartial = $v;
    }

    /**
     * Initializes the collLtsGames collection.
     *
     * By default this just sets the collLtsGames collection to an empty array (like clearcollLtsGames());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLtsGames($overrideExisting = true)
    {
        if (null !== $this->collLtsGames && !$overrideExisting) {
            return;
        }

        $collectionClassName = LtsGameTableMap::getTableMap()->getCollectionClassName();

        $this->collLtsGames = new $collectionClassName;
        $this->collLtsGames->setModel('\LtsGame');
    }

    /**
     * Gets an array of ChildLtsGame objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLtsGame[] List of ChildLtsGame objects
     * @throws PropelException
     */
    public function getLtsGames(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLtsGamesPartial && !$this->isNew();
        if (null === $this->collLtsGames || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLtsGames) {
                // return empty collection
                $this->initLtsGames();
            } else {
                $collLtsGames = ChildLtsGameQuery::create(null, $criteria)
                    ->filterByOwner($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collLtsGamesPartial && count($collLtsGames)) {
                        $this->initLtsGames(false);

                        foreach ($collLtsGames as $obj) {
                            if (false == $this->collLtsGames->contains($obj)) {
                                $this->collLtsGames->append($obj);
                            }
                        }

                        $this->collLtsGamesPartial = true;
                    }

                    return $collLtsGames;
                }

                if ($partial && $this->collLtsGames) {
                    foreach ($this->collLtsGames as $obj) {
                        if ($obj->isNew()) {
                            $collLtsGames[] = $obj;
                        }
                    }
                }

                $this->collLtsGames = $collLtsGames;
                $this->collLtsGamesPartial = false;
            }
        }

        return $this->collLtsGames;
    }

    /**
     * Sets a collection of ChildLtsGame objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $ltsGames A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setLtsGames(Collection $ltsGames, ConnectionInterface $con = null)
    {
        /** @var ChildLtsGame[] $ltsGamesToDelete */
        $ltsGamesToDelete = $this->getLtsGames(new Criteria(), $con)->diff($ltsGames);

        
        $this->ltsGamesScheduledForDeletion = $ltsGamesToDelete;

        foreach ($ltsGamesToDelete as $ltsGameRemoved) {
            $ltsGameRemoved->setOwner(null);
        }

        $this->collLtsGames = null;
        foreach ($ltsGames as $ltsGame) {
            $this->addLtsGame($ltsGame);
        }

        $this->collLtsGames = $ltsGames;
        $this->collLtsGamesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related LtsGame objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related LtsGame objects.
     * @throws PropelException
     */
    public function countLtsGames(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLtsGamesPartial && !$this->isNew();
        if (null === $this->collLtsGames || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLtsGames) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLtsGames());
            }

            $query = ChildLtsGameQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOwner($this)
                ->count($con);
        }

        return count($this->collLtsGames);
    }

    /**
     * Method called to associate a ChildLtsGame object to this object
     * through the ChildLtsGame foreign key attribute.
     *
     * @param  ChildLtsGame $l ChildLtsGame
     * @return $this|\User The current object (for fluent API support)
     */
    public function addLtsGame(ChildLtsGame $l)
    {
        if ($this->collLtsGames === null) {
            $this->initLtsGames();
            $this->collLtsGamesPartial = true;
        }

        if (!$this->collLtsGames->contains($l)) {
            $this->doAddLtsGame($l);

            if ($this->ltsGamesScheduledForDeletion and $this->ltsGamesScheduledForDeletion->contains($l)) {
                $this->ltsGamesScheduledForDeletion->remove($this->ltsGamesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildLtsGame $ltsGame The ChildLtsGame object to add.
     */
    protected function doAddLtsGame(ChildLtsGame $ltsGame)
    {
        $this->collLtsGames[]= $ltsGame;
        $ltsGame->setOwner($this);
    }

    /**
     * @param  ChildLtsGame $ltsGame The ChildLtsGame object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeLtsGame(ChildLtsGame $ltsGame)
    {
        if ($this->getLtsGames()->contains($ltsGame)) {
            $pos = $this->collLtsGames->search($ltsGame);
            $this->collLtsGames->remove($pos);
            if (null === $this->ltsGamesScheduledForDeletion) {
                $this->ltsGamesScheduledForDeletion = clone $this->collLtsGames;
                $this->ltsGamesScheduledForDeletion->clear();
            }
            $this->ltsGamesScheduledForDeletion[]= clone $ltsGame;
            $ltsGame->setOwner(null);
        }

        return $this;
    }

    /**
     * Clears out the collLtsCircuitPlayersRelatedByPlayerId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLtsCircuitPlayersRelatedByPlayerId()
     */
    public function clearLtsCircuitPlayersRelatedByPlayerId()
    {
        $this->collLtsCircuitPlayersRelatedByPlayerId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collLtsCircuitPlayersRelatedByPlayerId collection loaded partially.
     */
    public function resetPartialLtsCircuitPlayersRelatedByPlayerId($v = true)
    {
        $this->collLtsCircuitPlayersRelatedByPlayerIdPartial = $v;
    }

    /**
     * Initializes the collLtsCircuitPlayersRelatedByPlayerId collection.
     *
     * By default this just sets the collLtsCircuitPlayersRelatedByPlayerId collection to an empty array (like clearcollLtsCircuitPlayersRelatedByPlayerId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLtsCircuitPlayersRelatedByPlayerId($overrideExisting = true)
    {
        if (null !== $this->collLtsCircuitPlayersRelatedByPlayerId && !$overrideExisting) {
            return;
        }

        $collectionClassName = LtsCircuitPlayerTableMap::getTableMap()->getCollectionClassName();

        $this->collLtsCircuitPlayersRelatedByPlayerId = new $collectionClassName;
        $this->collLtsCircuitPlayersRelatedByPlayerId->setModel('\LtsCircuitPlayer');
    }

    /**
     * Gets an array of ChildLtsCircuitPlayer objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLtsCircuitPlayer[] List of ChildLtsCircuitPlayer objects
     * @throws PropelException
     */
    public function getLtsCircuitPlayersRelatedByPlayerId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLtsCircuitPlayersRelatedByPlayerIdPartial && !$this->isNew();
        if (null === $this->collLtsCircuitPlayersRelatedByPlayerId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLtsCircuitPlayersRelatedByPlayerId) {
                // return empty collection
                $this->initLtsCircuitPlayersRelatedByPlayerId();
            } else {
                $collLtsCircuitPlayersRelatedByPlayerId = ChildLtsCircuitPlayerQuery::create(null, $criteria)
                    ->filterByPlayer($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collLtsCircuitPlayersRelatedByPlayerIdPartial && count($collLtsCircuitPlayersRelatedByPlayerId)) {
                        $this->initLtsCircuitPlayersRelatedByPlayerId(false);

                        foreach ($collLtsCircuitPlayersRelatedByPlayerId as $obj) {
                            if (false == $this->collLtsCircuitPlayersRelatedByPlayerId->contains($obj)) {
                                $this->collLtsCircuitPlayersRelatedByPlayerId->append($obj);
                            }
                        }

                        $this->collLtsCircuitPlayersRelatedByPlayerIdPartial = true;
                    }

                    return $collLtsCircuitPlayersRelatedByPlayerId;
                }

                if ($partial && $this->collLtsCircuitPlayersRelatedByPlayerId) {
                    foreach ($this->collLtsCircuitPlayersRelatedByPlayerId as $obj) {
                        if ($obj->isNew()) {
                            $collLtsCircuitPlayersRelatedByPlayerId[] = $obj;
                        }
                    }
                }

                $this->collLtsCircuitPlayersRelatedByPlayerId = $collLtsCircuitPlayersRelatedByPlayerId;
                $this->collLtsCircuitPlayersRelatedByPlayerIdPartial = false;
            }
        }

        return $this->collLtsCircuitPlayersRelatedByPlayerId;
    }

    /**
     * Sets a collection of ChildLtsCircuitPlayer objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $ltsCircuitPlayersRelatedByPlayerId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setLtsCircuitPlayersRelatedByPlayerId(Collection $ltsCircuitPlayersRelatedByPlayerId, ConnectionInterface $con = null)
    {
        /** @var ChildLtsCircuitPlayer[] $ltsCircuitPlayersRelatedByPlayerIdToDelete */
        $ltsCircuitPlayersRelatedByPlayerIdToDelete = $this->getLtsCircuitPlayersRelatedByPlayerId(new Criteria(), $con)->diff($ltsCircuitPlayersRelatedByPlayerId);

        
        $this->ltsCircuitPlayersRelatedByPlayerIdScheduledForDeletion = $ltsCircuitPlayersRelatedByPlayerIdToDelete;

        foreach ($ltsCircuitPlayersRelatedByPlayerIdToDelete as $ltsCircuitPlayerRelatedByPlayerIdRemoved) {
            $ltsCircuitPlayerRelatedByPlayerIdRemoved->setPlayer(null);
        }

        $this->collLtsCircuitPlayersRelatedByPlayerId = null;
        foreach ($ltsCircuitPlayersRelatedByPlayerId as $ltsCircuitPlayerRelatedByPlayerId) {
            $this->addLtsCircuitPlayerRelatedByPlayerId($ltsCircuitPlayerRelatedByPlayerId);
        }

        $this->collLtsCircuitPlayersRelatedByPlayerId = $ltsCircuitPlayersRelatedByPlayerId;
        $this->collLtsCircuitPlayersRelatedByPlayerIdPartial = false;

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
    public function countLtsCircuitPlayersRelatedByPlayerId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLtsCircuitPlayersRelatedByPlayerIdPartial && !$this->isNew();
        if (null === $this->collLtsCircuitPlayersRelatedByPlayerId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLtsCircuitPlayersRelatedByPlayerId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLtsCircuitPlayersRelatedByPlayerId());
            }

            $query = ChildLtsCircuitPlayerQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayer($this)
                ->count($con);
        }

        return count($this->collLtsCircuitPlayersRelatedByPlayerId);
    }

    /**
     * Method called to associate a ChildLtsCircuitPlayer object to this object
     * through the ChildLtsCircuitPlayer foreign key attribute.
     *
     * @param  ChildLtsCircuitPlayer $l ChildLtsCircuitPlayer
     * @return $this|\User The current object (for fluent API support)
     */
    public function addLtsCircuitPlayerRelatedByPlayerId(ChildLtsCircuitPlayer $l)
    {
        if ($this->collLtsCircuitPlayersRelatedByPlayerId === null) {
            $this->initLtsCircuitPlayersRelatedByPlayerId();
            $this->collLtsCircuitPlayersRelatedByPlayerIdPartial = true;
        }

        if (!$this->collLtsCircuitPlayersRelatedByPlayerId->contains($l)) {
            $this->doAddLtsCircuitPlayerRelatedByPlayerId($l);

            if ($this->ltsCircuitPlayersRelatedByPlayerIdScheduledForDeletion and $this->ltsCircuitPlayersRelatedByPlayerIdScheduledForDeletion->contains($l)) {
                $this->ltsCircuitPlayersRelatedByPlayerIdScheduledForDeletion->remove($this->ltsCircuitPlayersRelatedByPlayerIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildLtsCircuitPlayer $ltsCircuitPlayerRelatedByPlayerId The ChildLtsCircuitPlayer object to add.
     */
    protected function doAddLtsCircuitPlayerRelatedByPlayerId(ChildLtsCircuitPlayer $ltsCircuitPlayerRelatedByPlayerId)
    {
        $this->collLtsCircuitPlayersRelatedByPlayerId[]= $ltsCircuitPlayerRelatedByPlayerId;
        $ltsCircuitPlayerRelatedByPlayerId->setPlayer($this);
    }

    /**
     * @param  ChildLtsCircuitPlayer $ltsCircuitPlayerRelatedByPlayerId The ChildLtsCircuitPlayer object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeLtsCircuitPlayerRelatedByPlayerId(ChildLtsCircuitPlayer $ltsCircuitPlayerRelatedByPlayerId)
    {
        if ($this->getLtsCircuitPlayersRelatedByPlayerId()->contains($ltsCircuitPlayerRelatedByPlayerId)) {
            $pos = $this->collLtsCircuitPlayersRelatedByPlayerId->search($ltsCircuitPlayerRelatedByPlayerId);
            $this->collLtsCircuitPlayersRelatedByPlayerId->remove($pos);
            if (null === $this->ltsCircuitPlayersRelatedByPlayerIdScheduledForDeletion) {
                $this->ltsCircuitPlayersRelatedByPlayerIdScheduledForDeletion = clone $this->collLtsCircuitPlayersRelatedByPlayerId;
                $this->ltsCircuitPlayersRelatedByPlayerIdScheduledForDeletion->clear();
            }
            $this->ltsCircuitPlayersRelatedByPlayerIdScheduledForDeletion[]= clone $ltsCircuitPlayerRelatedByPlayerId;
            $ltsCircuitPlayerRelatedByPlayerId->setPlayer(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related LtsCircuitPlayersRelatedByPlayerId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLtsCircuitPlayer[] List of ChildLtsCircuitPlayer objects
     */
    public function getLtsCircuitPlayersRelatedByPlayerIdJoinLtsGame(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLtsCircuitPlayerQuery::create(null, $criteria);
        $query->joinWith('LtsGame', $joinBehavior);

        return $this->getLtsCircuitPlayersRelatedByPlayerId($query, $con);
    }

    /**
     * Clears out the collLtsCircuitPlayersRelatedByTargetId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLtsCircuitPlayersRelatedByTargetId()
     */
    public function clearLtsCircuitPlayersRelatedByTargetId()
    {
        $this->collLtsCircuitPlayersRelatedByTargetId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collLtsCircuitPlayersRelatedByTargetId collection loaded partially.
     */
    public function resetPartialLtsCircuitPlayersRelatedByTargetId($v = true)
    {
        $this->collLtsCircuitPlayersRelatedByTargetIdPartial = $v;
    }

    /**
     * Initializes the collLtsCircuitPlayersRelatedByTargetId collection.
     *
     * By default this just sets the collLtsCircuitPlayersRelatedByTargetId collection to an empty array (like clearcollLtsCircuitPlayersRelatedByTargetId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLtsCircuitPlayersRelatedByTargetId($overrideExisting = true)
    {
        if (null !== $this->collLtsCircuitPlayersRelatedByTargetId && !$overrideExisting) {
            return;
        }

        $collectionClassName = LtsCircuitPlayerTableMap::getTableMap()->getCollectionClassName();

        $this->collLtsCircuitPlayersRelatedByTargetId = new $collectionClassName;
        $this->collLtsCircuitPlayersRelatedByTargetId->setModel('\LtsCircuitPlayer');
    }

    /**
     * Gets an array of ChildLtsCircuitPlayer objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLtsCircuitPlayer[] List of ChildLtsCircuitPlayer objects
     * @throws PropelException
     */
    public function getLtsCircuitPlayersRelatedByTargetId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLtsCircuitPlayersRelatedByTargetIdPartial && !$this->isNew();
        if (null === $this->collLtsCircuitPlayersRelatedByTargetId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLtsCircuitPlayersRelatedByTargetId) {
                // return empty collection
                $this->initLtsCircuitPlayersRelatedByTargetId();
            } else {
                $collLtsCircuitPlayersRelatedByTargetId = ChildLtsCircuitPlayerQuery::create(null, $criteria)
                    ->filterByTarget($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collLtsCircuitPlayersRelatedByTargetIdPartial && count($collLtsCircuitPlayersRelatedByTargetId)) {
                        $this->initLtsCircuitPlayersRelatedByTargetId(false);

                        foreach ($collLtsCircuitPlayersRelatedByTargetId as $obj) {
                            if (false == $this->collLtsCircuitPlayersRelatedByTargetId->contains($obj)) {
                                $this->collLtsCircuitPlayersRelatedByTargetId->append($obj);
                            }
                        }

                        $this->collLtsCircuitPlayersRelatedByTargetIdPartial = true;
                    }

                    return $collLtsCircuitPlayersRelatedByTargetId;
                }

                if ($partial && $this->collLtsCircuitPlayersRelatedByTargetId) {
                    foreach ($this->collLtsCircuitPlayersRelatedByTargetId as $obj) {
                        if ($obj->isNew()) {
                            $collLtsCircuitPlayersRelatedByTargetId[] = $obj;
                        }
                    }
                }

                $this->collLtsCircuitPlayersRelatedByTargetId = $collLtsCircuitPlayersRelatedByTargetId;
                $this->collLtsCircuitPlayersRelatedByTargetIdPartial = false;
            }
        }

        return $this->collLtsCircuitPlayersRelatedByTargetId;
    }

    /**
     * Sets a collection of ChildLtsCircuitPlayer objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $ltsCircuitPlayersRelatedByTargetId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setLtsCircuitPlayersRelatedByTargetId(Collection $ltsCircuitPlayersRelatedByTargetId, ConnectionInterface $con = null)
    {
        /** @var ChildLtsCircuitPlayer[] $ltsCircuitPlayersRelatedByTargetIdToDelete */
        $ltsCircuitPlayersRelatedByTargetIdToDelete = $this->getLtsCircuitPlayersRelatedByTargetId(new Criteria(), $con)->diff($ltsCircuitPlayersRelatedByTargetId);

        
        $this->ltsCircuitPlayersRelatedByTargetIdScheduledForDeletion = $ltsCircuitPlayersRelatedByTargetIdToDelete;

        foreach ($ltsCircuitPlayersRelatedByTargetIdToDelete as $ltsCircuitPlayerRelatedByTargetIdRemoved) {
            $ltsCircuitPlayerRelatedByTargetIdRemoved->setTarget(null);
        }

        $this->collLtsCircuitPlayersRelatedByTargetId = null;
        foreach ($ltsCircuitPlayersRelatedByTargetId as $ltsCircuitPlayerRelatedByTargetId) {
            $this->addLtsCircuitPlayerRelatedByTargetId($ltsCircuitPlayerRelatedByTargetId);
        }

        $this->collLtsCircuitPlayersRelatedByTargetId = $ltsCircuitPlayersRelatedByTargetId;
        $this->collLtsCircuitPlayersRelatedByTargetIdPartial = false;

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
    public function countLtsCircuitPlayersRelatedByTargetId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLtsCircuitPlayersRelatedByTargetIdPartial && !$this->isNew();
        if (null === $this->collLtsCircuitPlayersRelatedByTargetId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLtsCircuitPlayersRelatedByTargetId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLtsCircuitPlayersRelatedByTargetId());
            }

            $query = ChildLtsCircuitPlayerQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTarget($this)
                ->count($con);
        }

        return count($this->collLtsCircuitPlayersRelatedByTargetId);
    }

    /**
     * Method called to associate a ChildLtsCircuitPlayer object to this object
     * through the ChildLtsCircuitPlayer foreign key attribute.
     *
     * @param  ChildLtsCircuitPlayer $l ChildLtsCircuitPlayer
     * @return $this|\User The current object (for fluent API support)
     */
    public function addLtsCircuitPlayerRelatedByTargetId(ChildLtsCircuitPlayer $l)
    {
        if ($this->collLtsCircuitPlayersRelatedByTargetId === null) {
            $this->initLtsCircuitPlayersRelatedByTargetId();
            $this->collLtsCircuitPlayersRelatedByTargetIdPartial = true;
        }

        if (!$this->collLtsCircuitPlayersRelatedByTargetId->contains($l)) {
            $this->doAddLtsCircuitPlayerRelatedByTargetId($l);

            if ($this->ltsCircuitPlayersRelatedByTargetIdScheduledForDeletion and $this->ltsCircuitPlayersRelatedByTargetIdScheduledForDeletion->contains($l)) {
                $this->ltsCircuitPlayersRelatedByTargetIdScheduledForDeletion->remove($this->ltsCircuitPlayersRelatedByTargetIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildLtsCircuitPlayer $ltsCircuitPlayerRelatedByTargetId The ChildLtsCircuitPlayer object to add.
     */
    protected function doAddLtsCircuitPlayerRelatedByTargetId(ChildLtsCircuitPlayer $ltsCircuitPlayerRelatedByTargetId)
    {
        $this->collLtsCircuitPlayersRelatedByTargetId[]= $ltsCircuitPlayerRelatedByTargetId;
        $ltsCircuitPlayerRelatedByTargetId->setTarget($this);
    }

    /**
     * @param  ChildLtsCircuitPlayer $ltsCircuitPlayerRelatedByTargetId The ChildLtsCircuitPlayer object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeLtsCircuitPlayerRelatedByTargetId(ChildLtsCircuitPlayer $ltsCircuitPlayerRelatedByTargetId)
    {
        if ($this->getLtsCircuitPlayersRelatedByTargetId()->contains($ltsCircuitPlayerRelatedByTargetId)) {
            $pos = $this->collLtsCircuitPlayersRelatedByTargetId->search($ltsCircuitPlayerRelatedByTargetId);
            $this->collLtsCircuitPlayersRelatedByTargetId->remove($pos);
            if (null === $this->ltsCircuitPlayersRelatedByTargetIdScheduledForDeletion) {
                $this->ltsCircuitPlayersRelatedByTargetIdScheduledForDeletion = clone $this->collLtsCircuitPlayersRelatedByTargetId;
                $this->ltsCircuitPlayersRelatedByTargetIdScheduledForDeletion->clear();
            }
            $this->ltsCircuitPlayersRelatedByTargetIdScheduledForDeletion[]= clone $ltsCircuitPlayerRelatedByTargetId;
            $ltsCircuitPlayerRelatedByTargetId->setTarget(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related LtsCircuitPlayersRelatedByTargetId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLtsCircuitPlayer[] List of ChildLtsCircuitPlayer objects
     */
    public function getLtsCircuitPlayersRelatedByTargetIdJoinLtsGame(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLtsCircuitPlayerQuery::create(null, $criteria);
        $query->joinWith('LtsGame', $joinBehavior);

        return $this->getLtsCircuitPlayersRelatedByTargetId($query, $con);
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
        $collectionClassName = UserGameTableMap::getTableMap()->getCollectionClassName();

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
     * to the current object by way of the user_games cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
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
                    ->filterByUser($this);
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
     * to the current object by way of the user_games cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $games A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
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
     * to the current object by way of the user_games cross-reference table.
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
                    ->filterByUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collGames);
        }
    }

    /**
     * Associate a ChildGame to this object
     * through the user_games cross reference table.
     * 
     * @param ChildGame $game
     * @return ChildUser The current object (for fluent API support)
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
        $userGame = new ChildUserGame();

        $userGame->setGame($game);

        $userGame->setUser($this);

        $this->addUserGame($userGame);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$game->isUsersLoaded()) {
            $game->initUsers();
            $game->getUsers()->push($this);
        } elseif (!$game->getUsers()->contains($this)) {
            $game->getUsers()->push($this);
        }

    }

    /**
     * Remove game of this object
     * through the user_games cross reference table.
     * 
     * @param ChildGame $game
     * @return ChildUser The current object (for fluent API support)
     */
    public function removeGame(ChildGame $game)
    {
        if ($this->getGames()->contains($game)) { $userGame = new ChildUserGame();

            $userGame->setGame($game);
            if ($game->isUsersLoaded()) {
                //remove the back reference if available
                $game->getUsers()->removeObject($this);
            }

            $userGame->setUser($this);
            $this->removeUserGame(clone $userGame);
            $userGame->clear();

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
        $collectionClassName = UserPresetTableMap::getTableMap()->getCollectionClassName();

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
     * to the current object by way of the user_presets cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
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
                    ->filterByUser($this);
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
     * to the current object by way of the user_presets cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $presets A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
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
     * to the current object by way of the user_presets cross-reference table.
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
                    ->filterByUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collPresets);
        }
    }

    /**
     * Associate a ChildPreset to this object
     * through the user_presets cross reference table.
     * 
     * @param ChildPreset $preset
     * @return ChildUser The current object (for fluent API support)
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
        $userPreset = new ChildUserPreset();

        $userPreset->setPreset($preset);

        $userPreset->setUser($this);

        $this->addUserPreset($userPreset);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$preset->isUsersLoaded()) {
            $preset->initUsers();
            $preset->getUsers()->push($this);
        } elseif (!$preset->getUsers()->contains($this)) {
            $preset->getUsers()->push($this);
        }

    }

    /**
     * Remove preset of this object
     * through the user_presets cross reference table.
     * 
     * @param ChildPreset $preset
     * @return ChildUser The current object (for fluent API support)
     */
    public function removePreset(ChildPreset $preset)
    {
        if ($this->getPresets()->contains($preset)) { $userPreset = new ChildUserPreset();

            $userPreset->setPreset($preset);
            if ($preset->isUsersLoaded()) {
                //remove the back reference if available
                $preset->getUsers()->removeObject($this);
            }

            $userPreset->setUser($this);
            $this->removeUserPreset(clone $userPreset);
            $userPreset->clear();

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
        $this->email = null;
        $this->username = null;
        $this->real_name = null;
        $this->password = null;
        $this->money = null;
        $this->total_money = null;
        $this->verification_token = null;
        $this->cookie_token = null;
        $this->active = null;
        $this->date_created = null;
        $this->verification_time = null;
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
            if ($this->singlePreference) {
                $this->singlePreference->clearAllReferences($deep);
            }
            if ($this->collUserGames) {
                foreach ($this->collUserGames as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserPresets) {
                foreach ($this->collUserPresets as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOwnedGames) {
                foreach ($this->collOwnedGames as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCircuitPlayersRelatedByPlayerId) {
                foreach ($this->collCircuitPlayersRelatedByPlayerId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCircuitPlayersRelatedByTargetId) {
                foreach ($this->collCircuitPlayersRelatedByTargetId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerGroups) {
                foreach ($this->collPlayerGroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLtsGames) {
                foreach ($this->collLtsGames as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLtsCircuitPlayersRelatedByPlayerId) {
                foreach ($this->collLtsCircuitPlayersRelatedByPlayerId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLtsCircuitPlayersRelatedByTargetId) {
                foreach ($this->collLtsCircuitPlayersRelatedByTargetId as $o) {
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

        $this->singlePreference = null;
        $this->collUserGames = null;
        $this->collUserPresets = null;
        $this->collOwnedGames = null;
        $this->collCircuitPlayersRelatedByPlayerId = null;
        $this->collCircuitPlayersRelatedByTargetId = null;
        $this->collPlayerGroups = null;
        $this->collLtsGames = null;
        $this->collLtsCircuitPlayersRelatedByPlayerId = null;
        $this->collLtsCircuitPlayersRelatedByTargetId = null;
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
        return (string) $this->exportTo(UserTableMap::DEFAULT_STRING_FORMAT);
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
