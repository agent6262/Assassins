<?php

namespace Base;

use \User as ChildUser;
use \UserQuery as ChildUserQuery;
use \Exception;
use \PDO;
use Map\UserTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'users' table.
 *
 * 
 *
 * @method     ChildUserQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildUserQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildUserQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method     ChildUserQuery orderByRealName($order = Criteria::ASC) Order by the real_name column
 * @method     ChildUserQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method     ChildUserQuery orderByMoney($order = Criteria::ASC) Order by the money column
 * @method     ChildUserQuery orderByTotalMoney($order = Criteria::ASC) Order by the total_money column
 * @method     ChildUserQuery orderByVerificationToken($order = Criteria::ASC) Order by the verification_token column
 * @method     ChildUserQuery orderByCookieToken($order = Criteria::ASC) Order by the cookie_token column
 * @method     ChildUserQuery orderByActive($order = Criteria::ASC) Order by the active column
 * @method     ChildUserQuery orderByDateCreated($order = Criteria::ASC) Order by the date_created column
 * @method     ChildUserQuery orderByVerificationTime($order = Criteria::ASC) Order by the verification_time column
 *
 * @method     ChildUserQuery groupById() Group by the id column
 * @method     ChildUserQuery groupByEmail() Group by the email column
 * @method     ChildUserQuery groupByUsername() Group by the username column
 * @method     ChildUserQuery groupByRealName() Group by the real_name column
 * @method     ChildUserQuery groupByPassword() Group by the password column
 * @method     ChildUserQuery groupByMoney() Group by the money column
 * @method     ChildUserQuery groupByTotalMoney() Group by the total_money column
 * @method     ChildUserQuery groupByVerificationToken() Group by the verification_token column
 * @method     ChildUserQuery groupByCookieToken() Group by the cookie_token column
 * @method     ChildUserQuery groupByActive() Group by the active column
 * @method     ChildUserQuery groupByDateCreated() Group by the date_created column
 * @method     ChildUserQuery groupByVerificationTime() Group by the verification_time column
 *
 * @method     ChildUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUserQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUserQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUserQuery leftJoinPreference($relationAlias = null) Adds a LEFT JOIN clause to the query using the Preference relation
 * @method     ChildUserQuery rightJoinPreference($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Preference relation
 * @method     ChildUserQuery innerJoinPreference($relationAlias = null) Adds a INNER JOIN clause to the query using the Preference relation
 *
 * @method     ChildUserQuery joinWithPreference($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Preference relation
 *
 * @method     ChildUserQuery leftJoinWithPreference() Adds a LEFT JOIN clause and with to the query using the Preference relation
 * @method     ChildUserQuery rightJoinWithPreference() Adds a RIGHT JOIN clause and with to the query using the Preference relation
 * @method     ChildUserQuery innerJoinWithPreference() Adds a INNER JOIN clause and with to the query using the Preference relation
 *
 * @method     ChildUserQuery leftJoinUserGame($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserGame relation
 * @method     ChildUserQuery rightJoinUserGame($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserGame relation
 * @method     ChildUserQuery innerJoinUserGame($relationAlias = null) Adds a INNER JOIN clause to the query using the UserGame relation
 *
 * @method     ChildUserQuery joinWithUserGame($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserGame relation
 *
 * @method     ChildUserQuery leftJoinWithUserGame() Adds a LEFT JOIN clause and with to the query using the UserGame relation
 * @method     ChildUserQuery rightJoinWithUserGame() Adds a RIGHT JOIN clause and with to the query using the UserGame relation
 * @method     ChildUserQuery innerJoinWithUserGame() Adds a INNER JOIN clause and with to the query using the UserGame relation
 *
 * @method     ChildUserQuery leftJoinUserPreset($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserPreset relation
 * @method     ChildUserQuery rightJoinUserPreset($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserPreset relation
 * @method     ChildUserQuery innerJoinUserPreset($relationAlias = null) Adds a INNER JOIN clause to the query using the UserPreset relation
 *
 * @method     ChildUserQuery joinWithUserPreset($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserPreset relation
 *
 * @method     ChildUserQuery leftJoinWithUserPreset() Adds a LEFT JOIN clause and with to the query using the UserPreset relation
 * @method     ChildUserQuery rightJoinWithUserPreset() Adds a RIGHT JOIN clause and with to the query using the UserPreset relation
 * @method     ChildUserQuery innerJoinWithUserPreset() Adds a INNER JOIN clause and with to the query using the UserPreset relation
 *
 * @method     ChildUserQuery leftJoinOwnedGame($relationAlias = null) Adds a LEFT JOIN clause to the query using the OwnedGame relation
 * @method     ChildUserQuery rightJoinOwnedGame($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OwnedGame relation
 * @method     ChildUserQuery innerJoinOwnedGame($relationAlias = null) Adds a INNER JOIN clause to the query using the OwnedGame relation
 *
 * @method     ChildUserQuery joinWithOwnedGame($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OwnedGame relation
 *
 * @method     ChildUserQuery leftJoinWithOwnedGame() Adds a LEFT JOIN clause and with to the query using the OwnedGame relation
 * @method     ChildUserQuery rightJoinWithOwnedGame() Adds a RIGHT JOIN clause and with to the query using the OwnedGame relation
 * @method     ChildUserQuery innerJoinWithOwnedGame() Adds a INNER JOIN clause and with to the query using the OwnedGame relation
 *
 * @method     ChildUserQuery leftJoinCircuitPlayerRelatedByPlayerId($relationAlias = null) Adds a LEFT JOIN clause to the query using the CircuitPlayerRelatedByPlayerId relation
 * @method     ChildUserQuery rightJoinCircuitPlayerRelatedByPlayerId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CircuitPlayerRelatedByPlayerId relation
 * @method     ChildUserQuery innerJoinCircuitPlayerRelatedByPlayerId($relationAlias = null) Adds a INNER JOIN clause to the query using the CircuitPlayerRelatedByPlayerId relation
 *
 * @method     ChildUserQuery joinWithCircuitPlayerRelatedByPlayerId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CircuitPlayerRelatedByPlayerId relation
 *
 * @method     ChildUserQuery leftJoinWithCircuitPlayerRelatedByPlayerId() Adds a LEFT JOIN clause and with to the query using the CircuitPlayerRelatedByPlayerId relation
 * @method     ChildUserQuery rightJoinWithCircuitPlayerRelatedByPlayerId() Adds a RIGHT JOIN clause and with to the query using the CircuitPlayerRelatedByPlayerId relation
 * @method     ChildUserQuery innerJoinWithCircuitPlayerRelatedByPlayerId() Adds a INNER JOIN clause and with to the query using the CircuitPlayerRelatedByPlayerId relation
 *
 * @method     ChildUserQuery leftJoinCircuitPlayerRelatedByTargetId($relationAlias = null) Adds a LEFT JOIN clause to the query using the CircuitPlayerRelatedByTargetId relation
 * @method     ChildUserQuery rightJoinCircuitPlayerRelatedByTargetId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CircuitPlayerRelatedByTargetId relation
 * @method     ChildUserQuery innerJoinCircuitPlayerRelatedByTargetId($relationAlias = null) Adds a INNER JOIN clause to the query using the CircuitPlayerRelatedByTargetId relation
 *
 * @method     ChildUserQuery joinWithCircuitPlayerRelatedByTargetId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CircuitPlayerRelatedByTargetId relation
 *
 * @method     ChildUserQuery leftJoinWithCircuitPlayerRelatedByTargetId() Adds a LEFT JOIN clause and with to the query using the CircuitPlayerRelatedByTargetId relation
 * @method     ChildUserQuery rightJoinWithCircuitPlayerRelatedByTargetId() Adds a RIGHT JOIN clause and with to the query using the CircuitPlayerRelatedByTargetId relation
 * @method     ChildUserQuery innerJoinWithCircuitPlayerRelatedByTargetId() Adds a INNER JOIN clause and with to the query using the CircuitPlayerRelatedByTargetId relation
 *
 * @method     ChildUserQuery leftJoinPlayerGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerGroup relation
 * @method     ChildUserQuery rightJoinPlayerGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerGroup relation
 * @method     ChildUserQuery innerJoinPlayerGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerGroup relation
 *
 * @method     ChildUserQuery joinWithPlayerGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerGroup relation
 *
 * @method     ChildUserQuery leftJoinWithPlayerGroup() Adds a LEFT JOIN clause and with to the query using the PlayerGroup relation
 * @method     ChildUserQuery rightJoinWithPlayerGroup() Adds a RIGHT JOIN clause and with to the query using the PlayerGroup relation
 * @method     ChildUserQuery innerJoinWithPlayerGroup() Adds a INNER JOIN clause and with to the query using the PlayerGroup relation
 *
 * @method     ChildUserQuery leftJoinLtsGame($relationAlias = null) Adds a LEFT JOIN clause to the query using the LtsGame relation
 * @method     ChildUserQuery rightJoinLtsGame($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LtsGame relation
 * @method     ChildUserQuery innerJoinLtsGame($relationAlias = null) Adds a INNER JOIN clause to the query using the LtsGame relation
 *
 * @method     ChildUserQuery joinWithLtsGame($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the LtsGame relation
 *
 * @method     ChildUserQuery leftJoinWithLtsGame() Adds a LEFT JOIN clause and with to the query using the LtsGame relation
 * @method     ChildUserQuery rightJoinWithLtsGame() Adds a RIGHT JOIN clause and with to the query using the LtsGame relation
 * @method     ChildUserQuery innerJoinWithLtsGame() Adds a INNER JOIN clause and with to the query using the LtsGame relation
 *
 * @method     ChildUserQuery leftJoinLtsCircuitPlayerRelatedByPlayerId($relationAlias = null) Adds a LEFT JOIN clause to the query using the LtsCircuitPlayerRelatedByPlayerId relation
 * @method     ChildUserQuery rightJoinLtsCircuitPlayerRelatedByPlayerId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LtsCircuitPlayerRelatedByPlayerId relation
 * @method     ChildUserQuery innerJoinLtsCircuitPlayerRelatedByPlayerId($relationAlias = null) Adds a INNER JOIN clause to the query using the LtsCircuitPlayerRelatedByPlayerId relation
 *
 * @method     ChildUserQuery joinWithLtsCircuitPlayerRelatedByPlayerId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the LtsCircuitPlayerRelatedByPlayerId relation
 *
 * @method     ChildUserQuery leftJoinWithLtsCircuitPlayerRelatedByPlayerId() Adds a LEFT JOIN clause and with to the query using the LtsCircuitPlayerRelatedByPlayerId relation
 * @method     ChildUserQuery rightJoinWithLtsCircuitPlayerRelatedByPlayerId() Adds a RIGHT JOIN clause and with to the query using the LtsCircuitPlayerRelatedByPlayerId relation
 * @method     ChildUserQuery innerJoinWithLtsCircuitPlayerRelatedByPlayerId() Adds a INNER JOIN clause and with to the query using the LtsCircuitPlayerRelatedByPlayerId relation
 *
 * @method     ChildUserQuery leftJoinLtsCircuitPlayerRelatedByTargetId($relationAlias = null) Adds a LEFT JOIN clause to the query using the LtsCircuitPlayerRelatedByTargetId relation
 * @method     ChildUserQuery rightJoinLtsCircuitPlayerRelatedByTargetId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LtsCircuitPlayerRelatedByTargetId relation
 * @method     ChildUserQuery innerJoinLtsCircuitPlayerRelatedByTargetId($relationAlias = null) Adds a INNER JOIN clause to the query using the LtsCircuitPlayerRelatedByTargetId relation
 *
 * @method     ChildUserQuery joinWithLtsCircuitPlayerRelatedByTargetId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the LtsCircuitPlayerRelatedByTargetId relation
 *
 * @method     ChildUserQuery leftJoinWithLtsCircuitPlayerRelatedByTargetId() Adds a LEFT JOIN clause and with to the query using the LtsCircuitPlayerRelatedByTargetId relation
 * @method     ChildUserQuery rightJoinWithLtsCircuitPlayerRelatedByTargetId() Adds a RIGHT JOIN clause and with to the query using the LtsCircuitPlayerRelatedByTargetId relation
 * @method     ChildUserQuery innerJoinWithLtsCircuitPlayerRelatedByTargetId() Adds a INNER JOIN clause and with to the query using the LtsCircuitPlayerRelatedByTargetId relation
 *
 * @method     \PreferenceQuery|\UserGameQuery|\UserPresetQuery|\GameQuery|\CircuitPlayerQuery|\PlayerGroupQuery|\LtsGameQuery|\LtsCircuitPlayerQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUser findOne(ConnectionInterface $con = null) Return the first ChildUser matching the query
 * @method     ChildUser findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUser matching the query, or a new ChildUser object populated from the query conditions when no match is found
 *
 * @method     ChildUser findOneById(int $id) Return the first ChildUser filtered by the id column
 * @method     ChildUser findOneByEmail(string $email) Return the first ChildUser filtered by the email column
 * @method     ChildUser findOneByUsername(string $username) Return the first ChildUser filtered by the username column
 * @method     ChildUser findOneByRealName(string $real_name) Return the first ChildUser filtered by the real_name column
 * @method     ChildUser findOneByPassword(string $password) Return the first ChildUser filtered by the password column
 * @method     ChildUser findOneByMoney(int $money) Return the first ChildUser filtered by the money column
 * @method     ChildUser findOneByTotalMoney(int $total_money) Return the first ChildUser filtered by the total_money column
 * @method     ChildUser findOneByVerificationToken(string $verification_token) Return the first ChildUser filtered by the verification_token column
 * @method     ChildUser findOneByCookieToken(string $cookie_token) Return the first ChildUser filtered by the cookie_token column
 * @method     ChildUser findOneByActive(boolean $active) Return the first ChildUser filtered by the active column
 * @method     ChildUser findOneByDateCreated(string $date_created) Return the first ChildUser filtered by the date_created column
 * @method     ChildUser findOneByVerificationTime(string $verification_time) Return the first ChildUser filtered by the verification_time column *

 * @method     ChildUser requirePk($key, ConnectionInterface $con = null) Return the ChildUser by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOne(ConnectionInterface $con = null) Return the first ChildUser matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser requireOneById(int $id) Return the first ChildUser filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByEmail(string $email) Return the first ChildUser filtered by the email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByUsername(string $username) Return the first ChildUser filtered by the username column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByRealName(string $real_name) Return the first ChildUser filtered by the real_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPassword(string $password) Return the first ChildUser filtered by the password column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByMoney(int $money) Return the first ChildUser filtered by the money column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByTotalMoney(int $total_money) Return the first ChildUser filtered by the total_money column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByVerificationToken(string $verification_token) Return the first ChildUser filtered by the verification_token column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByCookieToken(string $cookie_token) Return the first ChildUser filtered by the cookie_token column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByActive(boolean $active) Return the first ChildUser filtered by the active column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByDateCreated(string $date_created) Return the first ChildUser filtered by the date_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByVerificationTime(string $verification_time) Return the first ChildUser filtered by the verification_time column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 * @method     ChildUser[]|ObjectCollection findById(int $id) Return ChildUser objects filtered by the id column
 * @method     ChildUser[]|ObjectCollection findByEmail(string $email) Return ChildUser objects filtered by the email column
 * @method     ChildUser[]|ObjectCollection findByUsername(string $username) Return ChildUser objects filtered by the username column
 * @method     ChildUser[]|ObjectCollection findByRealName(string $real_name) Return ChildUser objects filtered by the real_name column
 * @method     ChildUser[]|ObjectCollection findByPassword(string $password) Return ChildUser objects filtered by the password column
 * @method     ChildUser[]|ObjectCollection findByMoney(int $money) Return ChildUser objects filtered by the money column
 * @method     ChildUser[]|ObjectCollection findByTotalMoney(int $total_money) Return ChildUser objects filtered by the total_money column
 * @method     ChildUser[]|ObjectCollection findByVerificationToken(string $verification_token) Return ChildUser objects filtered by the verification_token column
 * @method     ChildUser[]|ObjectCollection findByCookieToken(string $cookie_token) Return ChildUser objects filtered by the cookie_token column
 * @method     ChildUser[]|ObjectCollection findByActive(boolean $active) Return ChildUser objects filtered by the active column
 * @method     ChildUser[]|ObjectCollection findByDateCreated(string $date_created) Return ChildUser objects filtered by the date_created column
 * @method     ChildUser[]|ObjectCollection findByVerificationTime(string $verification_time) Return ChildUser objects filtered by the verification_time column
 * @method     ChildUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UserQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\UserQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'assassins', $modelName = '\\User', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUserQuery) {
            return $criteria;
        }
        $query = new ChildUserQuery();
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = UserTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `email`, `username`, `real_name`, `password`, `money`, `total_money`, `verification_token`, `cookie_token`, `active`, `date_created`, `verification_time` FROM `users` WHERE `id` = :p0';
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
            /** @var ChildUser $obj */
            $obj = new ChildUser();
            $obj->hydrate($row);
            UserTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the username column
     *
     * Example usage:
     * <code>
     * $query->filterByUsername('fooValue');   // WHERE username = 'fooValue'
     * $query->filterByUsername('%fooValue%', Criteria::LIKE); // WHERE username LIKE '%fooValue%'
     * </code>
     *
     * @param     string $username The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByUsername($username = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_USERNAME, $username, $comparison);
    }

    /**
     * Filter the query on the real_name column
     *
     * Example usage:
     * <code>
     * $query->filterByRealName('fooValue');   // WHERE real_name = 'fooValue'
     * $query->filterByRealName('%fooValue%', Criteria::LIKE); // WHERE real_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $realName The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByRealName($realName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($realName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_REAL_NAME, $realName, $comparison);
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%', Criteria::LIKE); // WHERE password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $password The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PASSWORD, $password, $comparison);
    }

    /**
     * Filter the query on the money column
     *
     * Example usage:
     * <code>
     * $query->filterByMoney(1234); // WHERE money = 1234
     * $query->filterByMoney(array(12, 34)); // WHERE money IN (12, 34)
     * $query->filterByMoney(array('min' => 12)); // WHERE money > 12
     * </code>
     *
     * @param     mixed $money The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByMoney($money = null, $comparison = null)
    {
        if (is_array($money)) {
            $useMinMax = false;
            if (isset($money['min'])) {
                $this->addUsingAlias(UserTableMap::COL_MONEY, $money['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($money['max'])) {
                $this->addUsingAlias(UserTableMap::COL_MONEY, $money['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_MONEY, $money, $comparison);
    }

    /**
     * Filter the query on the total_money column
     *
     * Example usage:
     * <code>
     * $query->filterByTotalMoney(1234); // WHERE total_money = 1234
     * $query->filterByTotalMoney(array(12, 34)); // WHERE total_money IN (12, 34)
     * $query->filterByTotalMoney(array('min' => 12)); // WHERE total_money > 12
     * </code>
     *
     * @param     mixed $totalMoney The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByTotalMoney($totalMoney = null, $comparison = null)
    {
        if (is_array($totalMoney)) {
            $useMinMax = false;
            if (isset($totalMoney['min'])) {
                $this->addUsingAlias(UserTableMap::COL_TOTAL_MONEY, $totalMoney['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($totalMoney['max'])) {
                $this->addUsingAlias(UserTableMap::COL_TOTAL_MONEY, $totalMoney['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_TOTAL_MONEY, $totalMoney, $comparison);
    }

    /**
     * Filter the query on the verification_token column
     *
     * Example usage:
     * <code>
     * $query->filterByVerificationToken('fooValue');   // WHERE verification_token = 'fooValue'
     * $query->filterByVerificationToken('%fooValue%', Criteria::LIKE); // WHERE verification_token LIKE '%fooValue%'
     * </code>
     *
     * @param     string $verificationToken The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByVerificationToken($verificationToken = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($verificationToken)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_VERIFICATION_TOKEN, $verificationToken, $comparison);
    }

    /**
     * Filter the query on the cookie_token column
     *
     * Example usage:
     * <code>
     * $query->filterByCookieToken('fooValue');   // WHERE cookie_token = 'fooValue'
     * $query->filterByCookieToken('%fooValue%', Criteria::LIKE); // WHERE cookie_token LIKE '%fooValue%'
     * </code>
     *
     * @param     string $cookieToken The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByCookieToken($cookieToken = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($cookieToken)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_COOKIE_TOKEN, $cookieToken, $comparison);
    }

    /**
     * Filter the query on the active column
     *
     * Example usage:
     * <code>
     * $query->filterByActive(true); // WHERE active = true
     * $query->filterByActive('yes'); // WHERE active = true
     * </code>
     *
     * @param     boolean|string $active The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByActive($active = null, $comparison = null)
    {
        if (is_string($active)) {
            $active = in_array(strtolower($active), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserTableMap::COL_ACTIVE, $active, $comparison);
    }

    /**
     * Filter the query on the date_created column
     *
     * Example usage:
     * <code>
     * $query->filterByDateCreated('2011-03-14'); // WHERE date_created = '2011-03-14'
     * $query->filterByDateCreated('now'); // WHERE date_created = '2011-03-14'
     * $query->filterByDateCreated(array('max' => 'yesterday')); // WHERE date_created > '2011-03-13'
     * </code>
     *
     * @param     mixed $dateCreated The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByDateCreated($dateCreated = null, $comparison = null)
    {
        if (is_array($dateCreated)) {
            $useMinMax = false;
            if (isset($dateCreated['min'])) {
                $this->addUsingAlias(UserTableMap::COL_DATE_CREATED, $dateCreated['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateCreated['max'])) {
                $this->addUsingAlias(UserTableMap::COL_DATE_CREATED, $dateCreated['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_DATE_CREATED, $dateCreated, $comparison);
    }

    /**
     * Filter the query on the verification_time column
     *
     * Example usage:
     * <code>
     * $query->filterByVerificationTime('2011-03-14'); // WHERE verification_time = '2011-03-14'
     * $query->filterByVerificationTime('now'); // WHERE verification_time = '2011-03-14'
     * $query->filterByVerificationTime(array('max' => 'yesterday')); // WHERE verification_time > '2011-03-13'
     * </code>
     *
     * @param     mixed $verificationTime The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByVerificationTime($verificationTime = null, $comparison = null)
    {
        if (is_array($verificationTime)) {
            $useMinMax = false;
            if (isset($verificationTime['min'])) {
                $this->addUsingAlias(UserTableMap::COL_VERIFICATION_TIME, $verificationTime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($verificationTime['max'])) {
                $this->addUsingAlias(UserTableMap::COL_VERIFICATION_TIME, $verificationTime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_VERIFICATION_TIME, $verificationTime, $comparison);
    }

    /**
     * Filter the query by a related \Preference object
     *
     * @param \Preference|ObjectCollection $preference the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByPreference($preference, $comparison = null)
    {
        if ($preference instanceof \Preference) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $preference->getUserId(), $comparison);
        } elseif ($preference instanceof ObjectCollection) {
            return $this
                ->usePreferenceQuery()
                ->filterByPrimaryKeys($preference->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPreference() only accepts arguments of type \Preference or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Preference relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinPreference($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Preference');

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
            $this->addJoinObject($join, 'Preference');
        }

        return $this;
    }

    /**
     * Use the Preference relation Preference object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PreferenceQuery A secondary query class using the current class as primary query
     */
    public function usePreferenceQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPreference($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Preference', '\PreferenceQuery');
    }

    /**
     * Filter the query by a related \UserGame object
     *
     * @param \UserGame|ObjectCollection $userGame the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByUserGame($userGame, $comparison = null)
    {
        if ($userGame instanceof \UserGame) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $userGame->getUserId(), $comparison);
        } elseif ($userGame instanceof ObjectCollection) {
            return $this
                ->useUserGameQuery()
                ->filterByPrimaryKeys($userGame->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserGame() only accepts arguments of type \UserGame or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserGame relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinUserGame($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserGame');

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
            $this->addJoinObject($join, 'UserGame');
        }

        return $this;
    }

    /**
     * Use the UserGame relation UserGame object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserGameQuery A secondary query class using the current class as primary query
     */
    public function useUserGameQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserGame($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserGame', '\UserGameQuery');
    }

    /**
     * Filter the query by a related \UserPreset object
     *
     * @param \UserPreset|ObjectCollection $userPreset the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByUserPreset($userPreset, $comparison = null)
    {
        if ($userPreset instanceof \UserPreset) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $userPreset->getUserId(), $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
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
     * Filter the query by a related \Game object
     *
     * @param \Game|ObjectCollection $game the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByOwnedGame($game, $comparison = null)
    {
        if ($game instanceof \Game) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $game->getOwnerId(), $comparison);
        } elseif ($game instanceof ObjectCollection) {
            return $this
                ->useOwnedGameQuery()
                ->filterByPrimaryKeys($game->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOwnedGame() only accepts arguments of type \Game or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OwnedGame relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinOwnedGame($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OwnedGame');

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
            $this->addJoinObject($join, 'OwnedGame');
        }

        return $this;
    }

    /**
     * Use the OwnedGame relation Game object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \GameQuery A secondary query class using the current class as primary query
     */
    public function useOwnedGameQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOwnedGame($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OwnedGame', '\GameQuery');
    }

    /**
     * Filter the query by a related \CircuitPlayer object
     *
     * @param \CircuitPlayer|ObjectCollection $circuitPlayer the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByCircuitPlayerRelatedByPlayerId($circuitPlayer, $comparison = null)
    {
        if ($circuitPlayer instanceof \CircuitPlayer) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $circuitPlayer->getPlayerId(), $comparison);
        } elseif ($circuitPlayer instanceof ObjectCollection) {
            return $this
                ->useCircuitPlayerRelatedByPlayerIdQuery()
                ->filterByPrimaryKeys($circuitPlayer->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCircuitPlayerRelatedByPlayerId() only accepts arguments of type \CircuitPlayer or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CircuitPlayerRelatedByPlayerId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinCircuitPlayerRelatedByPlayerId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CircuitPlayerRelatedByPlayerId');

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
            $this->addJoinObject($join, 'CircuitPlayerRelatedByPlayerId');
        }

        return $this;
    }

    /**
     * Use the CircuitPlayerRelatedByPlayerId relation CircuitPlayer object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \CircuitPlayerQuery A secondary query class using the current class as primary query
     */
    public function useCircuitPlayerRelatedByPlayerIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCircuitPlayerRelatedByPlayerId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CircuitPlayerRelatedByPlayerId', '\CircuitPlayerQuery');
    }

    /**
     * Filter the query by a related \CircuitPlayer object
     *
     * @param \CircuitPlayer|ObjectCollection $circuitPlayer the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByCircuitPlayerRelatedByTargetId($circuitPlayer, $comparison = null)
    {
        if ($circuitPlayer instanceof \CircuitPlayer) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $circuitPlayer->getTargetId(), $comparison);
        } elseif ($circuitPlayer instanceof ObjectCollection) {
            return $this
                ->useCircuitPlayerRelatedByTargetIdQuery()
                ->filterByPrimaryKeys($circuitPlayer->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCircuitPlayerRelatedByTargetId() only accepts arguments of type \CircuitPlayer or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CircuitPlayerRelatedByTargetId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinCircuitPlayerRelatedByTargetId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CircuitPlayerRelatedByTargetId');

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
            $this->addJoinObject($join, 'CircuitPlayerRelatedByTargetId');
        }

        return $this;
    }

    /**
     * Use the CircuitPlayerRelatedByTargetId relation CircuitPlayer object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \CircuitPlayerQuery A secondary query class using the current class as primary query
     */
    public function useCircuitPlayerRelatedByTargetIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCircuitPlayerRelatedByTargetId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CircuitPlayerRelatedByTargetId', '\CircuitPlayerQuery');
    }

    /**
     * Filter the query by a related \PlayerGroup object
     *
     * @param \PlayerGroup|ObjectCollection $playerGroup the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByPlayerGroup($playerGroup, $comparison = null)
    {
        if ($playerGroup instanceof \PlayerGroup) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $playerGroup->getPlayerId(), $comparison);
        } elseif ($playerGroup instanceof ObjectCollection) {
            return $this
                ->usePlayerGroupQuery()
                ->filterByPrimaryKeys($playerGroup->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlayerGroup() only accepts arguments of type \PlayerGroup or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinPlayerGroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerGroup');

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
            $this->addJoinObject($join, 'PlayerGroup');
        }

        return $this;
    }

    /**
     * Use the PlayerGroup relation PlayerGroup object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PlayerGroupQuery A secondary query class using the current class as primary query
     */
    public function usePlayerGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerGroup', '\PlayerGroupQuery');
    }

    /**
     * Filter the query by a related \LtsGame object
     *
     * @param \LtsGame|ObjectCollection $ltsGame the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByLtsGame($ltsGame, $comparison = null)
    {
        if ($ltsGame instanceof \LtsGame) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $ltsGame->getOwnerId(), $comparison);
        } elseif ($ltsGame instanceof ObjectCollection) {
            return $this
                ->useLtsGameQuery()
                ->filterByPrimaryKeys($ltsGame->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLtsGame() only accepts arguments of type \LtsGame or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LtsGame relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinLtsGame($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LtsGame');

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
            $this->addJoinObject($join, 'LtsGame');
        }

        return $this;
    }

    /**
     * Use the LtsGame relation LtsGame object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \LtsGameQuery A secondary query class using the current class as primary query
     */
    public function useLtsGameQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLtsGame($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LtsGame', '\LtsGameQuery');
    }

    /**
     * Filter the query by a related \LtsCircuitPlayer object
     *
     * @param \LtsCircuitPlayer|ObjectCollection $ltsCircuitPlayer the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByLtsCircuitPlayerRelatedByPlayerId($ltsCircuitPlayer, $comparison = null)
    {
        if ($ltsCircuitPlayer instanceof \LtsCircuitPlayer) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $ltsCircuitPlayer->getPlayerId(), $comparison);
        } elseif ($ltsCircuitPlayer instanceof ObjectCollection) {
            return $this
                ->useLtsCircuitPlayerRelatedByPlayerIdQuery()
                ->filterByPrimaryKeys($ltsCircuitPlayer->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLtsCircuitPlayerRelatedByPlayerId() only accepts arguments of type \LtsCircuitPlayer or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LtsCircuitPlayerRelatedByPlayerId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinLtsCircuitPlayerRelatedByPlayerId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LtsCircuitPlayerRelatedByPlayerId');

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
            $this->addJoinObject($join, 'LtsCircuitPlayerRelatedByPlayerId');
        }

        return $this;
    }

    /**
     * Use the LtsCircuitPlayerRelatedByPlayerId relation LtsCircuitPlayer object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \LtsCircuitPlayerQuery A secondary query class using the current class as primary query
     */
    public function useLtsCircuitPlayerRelatedByPlayerIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLtsCircuitPlayerRelatedByPlayerId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LtsCircuitPlayerRelatedByPlayerId', '\LtsCircuitPlayerQuery');
    }

    /**
     * Filter the query by a related \LtsCircuitPlayer object
     *
     * @param \LtsCircuitPlayer|ObjectCollection $ltsCircuitPlayer the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByLtsCircuitPlayerRelatedByTargetId($ltsCircuitPlayer, $comparison = null)
    {
        if ($ltsCircuitPlayer instanceof \LtsCircuitPlayer) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $ltsCircuitPlayer->getTargetId(), $comparison);
        } elseif ($ltsCircuitPlayer instanceof ObjectCollection) {
            return $this
                ->useLtsCircuitPlayerRelatedByTargetIdQuery()
                ->filterByPrimaryKeys($ltsCircuitPlayer->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLtsCircuitPlayerRelatedByTargetId() only accepts arguments of type \LtsCircuitPlayer or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LtsCircuitPlayerRelatedByTargetId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinLtsCircuitPlayerRelatedByTargetId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LtsCircuitPlayerRelatedByTargetId');

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
            $this->addJoinObject($join, 'LtsCircuitPlayerRelatedByTargetId');
        }

        return $this;
    }

    /**
     * Use the LtsCircuitPlayerRelatedByTargetId relation LtsCircuitPlayer object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \LtsCircuitPlayerQuery A secondary query class using the current class as primary query
     */
    public function useLtsCircuitPlayerRelatedByTargetIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLtsCircuitPlayerRelatedByTargetId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LtsCircuitPlayerRelatedByTargetId', '\LtsCircuitPlayerQuery');
    }

    /**
     * Filter the query by a related Game object
     * using the user_games table as cross reference
     *
     * @param Game $game the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByGame($game, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserGameQuery()
            ->filterByGame($game, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Preset object
     * using the user_presets table as cross reference
     *
     * @param Preset $preset the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByPreset($preset, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserPresetQuery()
            ->filterByPreset($preset, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUser $user Object to remove from the list of results
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function prune($user = null)
    {
        if ($user) {
            $this->addUsingAlias(UserTableMap::COL_ID, $user->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the users table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserTableMap::clearInstancePool();
            UserTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            UserTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            UserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // UserQuery
