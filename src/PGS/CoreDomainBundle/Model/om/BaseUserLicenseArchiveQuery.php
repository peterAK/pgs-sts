<?php

namespace PGS\CoreDomainBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \PDO;
use \Propel;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Glorpen\Propel\PropelBundle\Dispatcher\EventDispatcherProxy;
use Glorpen\Propel\PropelBundle\Events\QueryEvent;
use PGS\CoreDomainBundle\Model\UserLicenseArchive;
use PGS\CoreDomainBundle\Model\UserLicenseArchivePeer;
use PGS\CoreDomainBundle\Model\UserLicenseArchiveQuery;

/**
 * @method UserLicenseArchiveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method UserLicenseArchiveQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method UserLicenseArchiveQuery orderByLicenseId($order = Criteria::ASC) Order by the license_id column
 * @method UserLicenseArchiveQuery orderByLicenseCode($order = Criteria::ASC) Order by the license_code column
 * @method UserLicenseArchiveQuery orderByRegisterDate($order = Criteria::ASC) Order by the register_date column
 * @method UserLicenseArchiveQuery orderByRenewalDate($order = Criteria::ASC) Order by the renewal_date column
 * @method UserLicenseArchiveQuery orderByDays($order = Criteria::ASC) Order by the days column
 * @method UserLicenseArchiveQuery orderByLicensePaymentId($order = Criteria::ASC) Order by the license_payment_id column
 * @method UserLicenseArchiveQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method UserLicenseArchiveQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method UserLicenseArchiveQuery orderByArchivedAt($order = Criteria::ASC) Order by the archived_at column
 *
 * @method UserLicenseArchiveQuery groupById() Group by the id column
 * @method UserLicenseArchiveQuery groupByUserId() Group by the user_id column
 * @method UserLicenseArchiveQuery groupByLicenseId() Group by the license_id column
 * @method UserLicenseArchiveQuery groupByLicenseCode() Group by the license_code column
 * @method UserLicenseArchiveQuery groupByRegisterDate() Group by the register_date column
 * @method UserLicenseArchiveQuery groupByRenewalDate() Group by the renewal_date column
 * @method UserLicenseArchiveQuery groupByDays() Group by the days column
 * @method UserLicenseArchiveQuery groupByLicensePaymentId() Group by the license_payment_id column
 * @method UserLicenseArchiveQuery groupByCreatedAt() Group by the created_at column
 * @method UserLicenseArchiveQuery groupByUpdatedAt() Group by the updated_at column
 * @method UserLicenseArchiveQuery groupByArchivedAt() Group by the archived_at column
 *
 * @method UserLicenseArchiveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method UserLicenseArchiveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method UserLicenseArchiveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method UserLicenseArchive findOne(PropelPDO $con = null) Return the first UserLicenseArchive matching the query
 * @method UserLicenseArchive findOneOrCreate(PropelPDO $con = null) Return the first UserLicenseArchive matching the query, or a new UserLicenseArchive object populated from the query conditions when no match is found
 *
 * @method UserLicenseArchive findOneByUserId(int $user_id) Return the first UserLicenseArchive filtered by the user_id column
 * @method UserLicenseArchive findOneByLicenseId(int $license_id) Return the first UserLicenseArchive filtered by the license_id column
 * @method UserLicenseArchive findOneByLicenseCode(string $license_code) Return the first UserLicenseArchive filtered by the license_code column
 * @method UserLicenseArchive findOneByRegisterDate(string $register_date) Return the first UserLicenseArchive filtered by the register_date column
 * @method UserLicenseArchive findOneByRenewalDate(string $renewal_date) Return the first UserLicenseArchive filtered by the renewal_date column
 * @method UserLicenseArchive findOneByDays(int $days) Return the first UserLicenseArchive filtered by the days column
 * @method UserLicenseArchive findOneByLicensePaymentId(int $license_payment_id) Return the first UserLicenseArchive filtered by the license_payment_id column
 * @method UserLicenseArchive findOneByCreatedAt(string $created_at) Return the first UserLicenseArchive filtered by the created_at column
 * @method UserLicenseArchive findOneByUpdatedAt(string $updated_at) Return the first UserLicenseArchive filtered by the updated_at column
 * @method UserLicenseArchive findOneByArchivedAt(string $archived_at) Return the first UserLicenseArchive filtered by the archived_at column
 *
 * @method array findById(int $id) Return UserLicenseArchive objects filtered by the id column
 * @method array findByUserId(int $user_id) Return UserLicenseArchive objects filtered by the user_id column
 * @method array findByLicenseId(int $license_id) Return UserLicenseArchive objects filtered by the license_id column
 * @method array findByLicenseCode(string $license_code) Return UserLicenseArchive objects filtered by the license_code column
 * @method array findByRegisterDate(string $register_date) Return UserLicenseArchive objects filtered by the register_date column
 * @method array findByRenewalDate(string $renewal_date) Return UserLicenseArchive objects filtered by the renewal_date column
 * @method array findByDays(int $days) Return UserLicenseArchive objects filtered by the days column
 * @method array findByLicensePaymentId(int $license_payment_id) Return UserLicenseArchive objects filtered by the license_payment_id column
 * @method array findByCreatedAt(string $created_at) Return UserLicenseArchive objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return UserLicenseArchive objects filtered by the updated_at column
 * @method array findByArchivedAt(string $archived_at) Return UserLicenseArchive objects filtered by the archived_at column
 */
abstract class BaseUserLicenseArchiveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseUserLicenseArchiveQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'default';
        }
        if (null === $modelName) {
            $modelName = 'PGS\\CoreDomainBundle\\Model\\UserLicenseArchive';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new UserLicenseArchiveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   UserLicenseArchiveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return UserLicenseArchiveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof UserLicenseArchiveQuery) {
            return $criteria;
        }
        $query = new static(null, null, $modelAlias);

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
     * @param     PropelPDO $con an optional connection object
     *
     * @return   UserLicenseArchive|UserLicenseArchive[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserLicenseArchivePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(UserLicenseArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 UserLicenseArchive A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 UserLicenseArchive A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `user_id`, `license_id`, `license_code`, `register_date`, `renewal_date`, `days`, `license_payment_id`, `created_at`, `updated_at`, `archived_at` FROM `user_license_archive` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $cls = UserLicenseArchivePeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            UserLicenseArchivePeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return UserLicenseArchive|UserLicenseArchive[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|UserLicenseArchive[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return UserLicenseArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserLicenseArchivePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return UserLicenseArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserLicenseArchivePeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserLicenseArchiveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UserLicenseArchivePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserLicenseArchivePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserLicenseArchivePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id >= 12
     * $query->filterByUserId(array('max' => 12)); // WHERE user_id <= 12
     * </code>
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserLicenseArchiveQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(UserLicenseArchivePeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(UserLicenseArchivePeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserLicenseArchivePeer::USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the license_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLicenseId(1234); // WHERE license_id = 1234
     * $query->filterByLicenseId(array(12, 34)); // WHERE license_id IN (12, 34)
     * $query->filterByLicenseId(array('min' => 12)); // WHERE license_id >= 12
     * $query->filterByLicenseId(array('max' => 12)); // WHERE license_id <= 12
     * </code>
     *
     * @param     mixed $licenseId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserLicenseArchiveQuery The current query, for fluid interface
     */
    public function filterByLicenseId($licenseId = null, $comparison = null)
    {
        if (is_array($licenseId)) {
            $useMinMax = false;
            if (isset($licenseId['min'])) {
                $this->addUsingAlias(UserLicenseArchivePeer::LICENSE_ID, $licenseId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($licenseId['max'])) {
                $this->addUsingAlias(UserLicenseArchivePeer::LICENSE_ID, $licenseId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserLicenseArchivePeer::LICENSE_ID, $licenseId, $comparison);
    }

    /**
     * Filter the query on the license_code column
     *
     * Example usage:
     * <code>
     * $query->filterByLicenseCode('fooValue');   // WHERE license_code = 'fooValue'
     * $query->filterByLicenseCode('%fooValue%'); // WHERE license_code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $licenseCode The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserLicenseArchiveQuery The current query, for fluid interface
     */
    public function filterByLicenseCode($licenseCode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($licenseCode)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $licenseCode)) {
                $licenseCode = str_replace('*', '%', $licenseCode);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserLicenseArchivePeer::LICENSE_CODE, $licenseCode, $comparison);
    }

    /**
     * Filter the query on the register_date column
     *
     * Example usage:
     * <code>
     * $query->filterByRegisterDate('2011-03-14'); // WHERE register_date = '2011-03-14'
     * $query->filterByRegisterDate('now'); // WHERE register_date = '2011-03-14'
     * $query->filterByRegisterDate(array('max' => 'yesterday')); // WHERE register_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $registerDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserLicenseArchiveQuery The current query, for fluid interface
     */
    public function filterByRegisterDate($registerDate = null, $comparison = null)
    {
        if (is_array($registerDate)) {
            $useMinMax = false;
            if (isset($registerDate['min'])) {
                $this->addUsingAlias(UserLicenseArchivePeer::REGISTER_DATE, $registerDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($registerDate['max'])) {
                $this->addUsingAlias(UserLicenseArchivePeer::REGISTER_DATE, $registerDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserLicenseArchivePeer::REGISTER_DATE, $registerDate, $comparison);
    }

    /**
     * Filter the query on the renewal_date column
     *
     * Example usage:
     * <code>
     * $query->filterByRenewalDate('2011-03-14'); // WHERE renewal_date = '2011-03-14'
     * $query->filterByRenewalDate('now'); // WHERE renewal_date = '2011-03-14'
     * $query->filterByRenewalDate(array('max' => 'yesterday')); // WHERE renewal_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $renewalDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserLicenseArchiveQuery The current query, for fluid interface
     */
    public function filterByRenewalDate($renewalDate = null, $comparison = null)
    {
        if (is_array($renewalDate)) {
            $useMinMax = false;
            if (isset($renewalDate['min'])) {
                $this->addUsingAlias(UserLicenseArchivePeer::RENEWAL_DATE, $renewalDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($renewalDate['max'])) {
                $this->addUsingAlias(UserLicenseArchivePeer::RENEWAL_DATE, $renewalDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserLicenseArchivePeer::RENEWAL_DATE, $renewalDate, $comparison);
    }

    /**
     * Filter the query on the days column
     *
     * Example usage:
     * <code>
     * $query->filterByDays(1234); // WHERE days = 1234
     * $query->filterByDays(array(12, 34)); // WHERE days IN (12, 34)
     * $query->filterByDays(array('min' => 12)); // WHERE days >= 12
     * $query->filterByDays(array('max' => 12)); // WHERE days <= 12
     * </code>
     *
     * @param     mixed $days The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserLicenseArchiveQuery The current query, for fluid interface
     */
    public function filterByDays($days = null, $comparison = null)
    {
        if (is_array($days)) {
            $useMinMax = false;
            if (isset($days['min'])) {
                $this->addUsingAlias(UserLicenseArchivePeer::DAYS, $days['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($days['max'])) {
                $this->addUsingAlias(UserLicenseArchivePeer::DAYS, $days['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserLicenseArchivePeer::DAYS, $days, $comparison);
    }

    /**
     * Filter the query on the license_payment_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLicensePaymentId(1234); // WHERE license_payment_id = 1234
     * $query->filterByLicensePaymentId(array(12, 34)); // WHERE license_payment_id IN (12, 34)
     * $query->filterByLicensePaymentId(array('min' => 12)); // WHERE license_payment_id >= 12
     * $query->filterByLicensePaymentId(array('max' => 12)); // WHERE license_payment_id <= 12
     * </code>
     *
     * @param     mixed $licensePaymentId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserLicenseArchiveQuery The current query, for fluid interface
     */
    public function filterByLicensePaymentId($licensePaymentId = null, $comparison = null)
    {
        if (is_array($licensePaymentId)) {
            $useMinMax = false;
            if (isset($licensePaymentId['min'])) {
                $this->addUsingAlias(UserLicenseArchivePeer::LICENSE_PAYMENT_ID, $licensePaymentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($licensePaymentId['max'])) {
                $this->addUsingAlias(UserLicenseArchivePeer::LICENSE_PAYMENT_ID, $licensePaymentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserLicenseArchivePeer::LICENSE_PAYMENT_ID, $licensePaymentId, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserLicenseArchiveQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(UserLicenseArchivePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(UserLicenseArchivePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserLicenseArchivePeer::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserLicenseArchiveQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(UserLicenseArchivePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(UserLicenseArchivePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserLicenseArchivePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the archived_at column
     *
     * Example usage:
     * <code>
     * $query->filterByArchivedAt('2011-03-14'); // WHERE archived_at = '2011-03-14'
     * $query->filterByArchivedAt('now'); // WHERE archived_at = '2011-03-14'
     * $query->filterByArchivedAt(array('max' => 'yesterday')); // WHERE archived_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $archivedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserLicenseArchiveQuery The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(UserLicenseArchivePeer::ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(UserLicenseArchivePeer::ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserLicenseArchivePeer::ARCHIVED_AT, $archivedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   UserLicenseArchive $userLicenseArchive Object to remove from the list of results
     *
     * @return UserLicenseArchiveQuery The current query, for fluid interface
     */
    public function prune($userLicenseArchive = null)
    {
        if ($userLicenseArchive) {
            $this->addUsingAlias(UserLicenseArchivePeer::ID, $userLicenseArchive->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Code to execute before every SELECT statement
     *
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePreSelect(PropelPDO $con)
    {
        // event behavior
        EventDispatcherProxy::trigger('query.select.pre', new QueryEvent($this));

        return $this->preSelect($con);
    }

    /**
     * Code to execute before every DELETE statement
     *
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePreDelete(PropelPDO $con)
    {
        EventDispatcherProxy::trigger(array('delete.pre','query.delete.pre'), new QueryEvent($this));
        // event behavior
        // placeholder, issue #5

        return $this->preDelete($con);
    }

    /**
     * Code to execute after every DELETE statement
     *
     * @param     int $affectedRows the number of deleted rows
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePostDelete($affectedRows, PropelPDO $con)
    {
        // event behavior
        EventDispatcherProxy::trigger(array('delete.post','query.delete.post'), new QueryEvent($this));

        return $this->postDelete($affectedRows, $con);
    }

    /**
     * Code to execute before every UPDATE statement
     *
     * @param     array $values The associative array of columns and values for the update
     * @param     PropelPDO $con The connection object used by the query
     * @param     boolean $forceIndividualSaves If false (default), the resulting call is a BasePeer::doUpdate(), otherwise it is a series of save() calls on all the found objects
     */
    protected function basePreUpdate(&$values, PropelPDO $con, $forceIndividualSaves = false)
    {
        // event behavior
        EventDispatcherProxy::trigger(array('update.pre', 'query.update.pre'), new QueryEvent($this));

        return $this->preUpdate($values, $con, $forceIndividualSaves);
    }

    /**
     * Code to execute after every UPDATE statement
     *
     * @param     int $affectedRows the number of updated rows
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePostUpdate($affectedRows, PropelPDO $con)
    {
        // event behavior
        EventDispatcherProxy::trigger(array('update.post', 'query.update.post'), new QueryEvent($this));

        return $this->postUpdate($affectedRows, $con);
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     UserLicenseArchiveQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(UserLicenseArchivePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     UserLicenseArchiveQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserLicenseArchivePeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     UserLicenseArchiveQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserLicenseArchivePeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     UserLicenseArchiveQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(UserLicenseArchivePeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     UserLicenseArchiveQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserLicenseArchivePeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     UserLicenseArchiveQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserLicenseArchivePeer::CREATED_AT);
    }
    // extend behavior
    public function setFormatter($formatter)
    {
        if (is_string($formatter) && $formatter === \ModelCriteria::FORMAT_ON_DEMAND) {
            $formatter = '\Glorpen\Propel\PropelBundle\Formatter\PropelOnDemandFormatter';
        }

        return parent::setFormatter($formatter);
    }
}
