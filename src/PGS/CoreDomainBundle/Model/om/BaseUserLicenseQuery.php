<?php

namespace PGS\CoreDomainBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Glorpen\Propel\PropelBundle\Dispatcher\EventDispatcherProxy;
use Glorpen\Propel\PropelBundle\Events\QueryEvent;
use PGS\CoreDomainBundle\Model\License;
use PGS\CoreDomainBundle\Model\LicensePayment;
use PGS\CoreDomainBundle\Model\User;
use PGS\CoreDomainBundle\Model\UserLicense;
use PGS\CoreDomainBundle\Model\UserLicensePeer;
use PGS\CoreDomainBundle\Model\UserLicenseQuery;

/**
 * @method UserLicenseQuery orderById($order = Criteria::ASC) Order by the id column
 * @method UserLicenseQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method UserLicenseQuery orderByLicenseId($order = Criteria::ASC) Order by the license_id column
 * @method UserLicenseQuery orderByLicenseCode($order = Criteria::ASC) Order by the license_code column
 * @method UserLicenseQuery orderByRegisterDate($order = Criteria::ASC) Order by the register_date column
 * @method UserLicenseQuery orderByRenewalDate($order = Criteria::ASC) Order by the renewal_date column
 * @method UserLicenseQuery orderByDays($order = Criteria::ASC) Order by the days column
 * @method UserLicenseQuery orderByLicensePaymentId($order = Criteria::ASC) Order by the license_payment_id column
 * @method UserLicenseQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method UserLicenseQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method UserLicenseQuery groupById() Group by the id column
 * @method UserLicenseQuery groupByUserId() Group by the user_id column
 * @method UserLicenseQuery groupByLicenseId() Group by the license_id column
 * @method UserLicenseQuery groupByLicenseCode() Group by the license_code column
 * @method UserLicenseQuery groupByRegisterDate() Group by the register_date column
 * @method UserLicenseQuery groupByRenewalDate() Group by the renewal_date column
 * @method UserLicenseQuery groupByDays() Group by the days column
 * @method UserLicenseQuery groupByLicensePaymentId() Group by the license_payment_id column
 * @method UserLicenseQuery groupByCreatedAt() Group by the created_at column
 * @method UserLicenseQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method UserLicenseQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method UserLicenseQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method UserLicenseQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method UserLicenseQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method UserLicenseQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method UserLicenseQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method UserLicenseQuery leftJoinLicense($relationAlias = null) Adds a LEFT JOIN clause to the query using the License relation
 * @method UserLicenseQuery rightJoinLicense($relationAlias = null) Adds a RIGHT JOIN clause to the query using the License relation
 * @method UserLicenseQuery innerJoinLicense($relationAlias = null) Adds a INNER JOIN clause to the query using the License relation
 *
 * @method UserLicenseQuery leftJoinLicensePayment($relationAlias = null) Adds a LEFT JOIN clause to the query using the LicensePayment relation
 * @method UserLicenseQuery rightJoinLicensePayment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LicensePayment relation
 * @method UserLicenseQuery innerJoinLicensePayment($relationAlias = null) Adds a INNER JOIN clause to the query using the LicensePayment relation
 *
 * @method UserLicense findOne(PropelPDO $con = null) Return the first UserLicense matching the query
 * @method UserLicense findOneOrCreate(PropelPDO $con = null) Return the first UserLicense matching the query, or a new UserLicense object populated from the query conditions when no match is found
 *
 * @method UserLicense findOneByUserId(int $user_id) Return the first UserLicense filtered by the user_id column
 * @method UserLicense findOneByLicenseId(int $license_id) Return the first UserLicense filtered by the license_id column
 * @method UserLicense findOneByLicenseCode(string $license_code) Return the first UserLicense filtered by the license_code column
 * @method UserLicense findOneByRegisterDate(string $register_date) Return the first UserLicense filtered by the register_date column
 * @method UserLicense findOneByRenewalDate(string $renewal_date) Return the first UserLicense filtered by the renewal_date column
 * @method UserLicense findOneByDays(int $days) Return the first UserLicense filtered by the days column
 * @method UserLicense findOneByLicensePaymentId(int $license_payment_id) Return the first UserLicense filtered by the license_payment_id column
 * @method UserLicense findOneByCreatedAt(string $created_at) Return the first UserLicense filtered by the created_at column
 * @method UserLicense findOneByUpdatedAt(string $updated_at) Return the first UserLicense filtered by the updated_at column
 *
 * @method array findById(int $id) Return UserLicense objects filtered by the id column
 * @method array findByUserId(int $user_id) Return UserLicense objects filtered by the user_id column
 * @method array findByLicenseId(int $license_id) Return UserLicense objects filtered by the license_id column
 * @method array findByLicenseCode(string $license_code) Return UserLicense objects filtered by the license_code column
 * @method array findByRegisterDate(string $register_date) Return UserLicense objects filtered by the register_date column
 * @method array findByRenewalDate(string $renewal_date) Return UserLicense objects filtered by the renewal_date column
 * @method array findByDays(int $days) Return UserLicense objects filtered by the days column
 * @method array findByLicensePaymentId(int $license_payment_id) Return UserLicense objects filtered by the license_payment_id column
 * @method array findByCreatedAt(string $created_at) Return UserLicense objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return UserLicense objects filtered by the updated_at column
 */
abstract class BaseUserLicenseQuery extends ModelCriteria
{
    // archivable behavior
    protected $archiveOnDelete = true;

    /**
     * Initializes internal state of BaseUserLicenseQuery object.
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
            $modelName = 'PGS\\CoreDomainBundle\\Model\\UserLicense';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new UserLicenseQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   UserLicenseQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return UserLicenseQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof UserLicenseQuery) {
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
     * @return   UserLicense|UserLicense[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserLicensePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(UserLicensePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 UserLicense A model object, or null if the key is not found
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
     * @return                 UserLicense A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `user_id`, `license_id`, `license_code`, `register_date`, `renewal_date`, `days`, `license_payment_id`, `created_at`, `updated_at` FROM `user_license` WHERE `id` = :p0';
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
            $cls = UserLicensePeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            UserLicensePeer::addInstanceToPool($obj, (string) $key);
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
     * @return UserLicense|UserLicense[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|UserLicense[]|mixed the list of results, formatted by the current formatter
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
     * @return UserLicenseQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserLicensePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return UserLicenseQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserLicensePeer::ID, $keys, Criteria::IN);
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
     * @return UserLicenseQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UserLicensePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserLicensePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserLicensePeer::ID, $id, $comparison);
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
     * @see       filterByUser()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserLicenseQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(UserLicensePeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(UserLicensePeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserLicensePeer::USER_ID, $userId, $comparison);
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
     * @see       filterByLicense()
     *
     * @param     mixed $licenseId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserLicenseQuery The current query, for fluid interface
     */
    public function filterByLicenseId($licenseId = null, $comparison = null)
    {
        if (is_array($licenseId)) {
            $useMinMax = false;
            if (isset($licenseId['min'])) {
                $this->addUsingAlias(UserLicensePeer::LICENSE_ID, $licenseId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($licenseId['max'])) {
                $this->addUsingAlias(UserLicensePeer::LICENSE_ID, $licenseId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserLicensePeer::LICENSE_ID, $licenseId, $comparison);
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
     * @return UserLicenseQuery The current query, for fluid interface
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

        return $this->addUsingAlias(UserLicensePeer::LICENSE_CODE, $licenseCode, $comparison);
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
     * @return UserLicenseQuery The current query, for fluid interface
     */
    public function filterByRegisterDate($registerDate = null, $comparison = null)
    {
        if (is_array($registerDate)) {
            $useMinMax = false;
            if (isset($registerDate['min'])) {
                $this->addUsingAlias(UserLicensePeer::REGISTER_DATE, $registerDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($registerDate['max'])) {
                $this->addUsingAlias(UserLicensePeer::REGISTER_DATE, $registerDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserLicensePeer::REGISTER_DATE, $registerDate, $comparison);
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
     * @return UserLicenseQuery The current query, for fluid interface
     */
    public function filterByRenewalDate($renewalDate = null, $comparison = null)
    {
        if (is_array($renewalDate)) {
            $useMinMax = false;
            if (isset($renewalDate['min'])) {
                $this->addUsingAlias(UserLicensePeer::RENEWAL_DATE, $renewalDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($renewalDate['max'])) {
                $this->addUsingAlias(UserLicensePeer::RENEWAL_DATE, $renewalDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserLicensePeer::RENEWAL_DATE, $renewalDate, $comparison);
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
     * @return UserLicenseQuery The current query, for fluid interface
     */
    public function filterByDays($days = null, $comparison = null)
    {
        if (is_array($days)) {
            $useMinMax = false;
            if (isset($days['min'])) {
                $this->addUsingAlias(UserLicensePeer::DAYS, $days['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($days['max'])) {
                $this->addUsingAlias(UserLicensePeer::DAYS, $days['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserLicensePeer::DAYS, $days, $comparison);
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
     * @see       filterByLicensePayment()
     *
     * @param     mixed $licensePaymentId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserLicenseQuery The current query, for fluid interface
     */
    public function filterByLicensePaymentId($licensePaymentId = null, $comparison = null)
    {
        if (is_array($licensePaymentId)) {
            $useMinMax = false;
            if (isset($licensePaymentId['min'])) {
                $this->addUsingAlias(UserLicensePeer::LICENSE_PAYMENT_ID, $licensePaymentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($licensePaymentId['max'])) {
                $this->addUsingAlias(UserLicensePeer::LICENSE_PAYMENT_ID, $licensePaymentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserLicensePeer::LICENSE_PAYMENT_ID, $licensePaymentId, $comparison);
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
     * @return UserLicenseQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(UserLicensePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(UserLicensePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserLicensePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return UserLicenseQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(UserLicensePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(UserLicensePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserLicensePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserLicenseQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(UserLicensePeer::USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserLicensePeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type User or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserLicenseQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\PGS\CoreDomainBundle\Model\UserQuery');
    }

    /**
     * Filter the query by a related License object
     *
     * @param   License|PropelObjectCollection $license The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserLicenseQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByLicense($license, $comparison = null)
    {
        if ($license instanceof License) {
            return $this
                ->addUsingAlias(UserLicensePeer::LICENSE_ID, $license->getId(), $comparison);
        } elseif ($license instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserLicensePeer::LICENSE_ID, $license->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByLicense() only accepts arguments of type License or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the License relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserLicenseQuery The current query, for fluid interface
     */
    public function joinLicense($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('License');

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
            $this->addJoinObject($join, 'License');
        }

        return $this;
    }

    /**
     * Use the License relation License object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\LicenseQuery A secondary query class using the current class as primary query
     */
    public function useLicenseQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLicense($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'License', '\PGS\CoreDomainBundle\Model\LicenseQuery');
    }

    /**
     * Filter the query by a related LicensePayment object
     *
     * @param   LicensePayment|PropelObjectCollection $licensePayment The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserLicenseQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByLicensePayment($licensePayment, $comparison = null)
    {
        if ($licensePayment instanceof LicensePayment) {
            return $this
                ->addUsingAlias(UserLicensePeer::LICENSE_PAYMENT_ID, $licensePayment->getId(), $comparison);
        } elseif ($licensePayment instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserLicensePeer::LICENSE_PAYMENT_ID, $licensePayment->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByLicensePayment() only accepts arguments of type LicensePayment or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LicensePayment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserLicenseQuery The current query, for fluid interface
     */
    public function joinLicensePayment($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LicensePayment');

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
            $this->addJoinObject($join, 'LicensePayment');
        }

        return $this;
    }

    /**
     * Use the LicensePayment relation LicensePayment object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\LicensePaymentQuery A secondary query class using the current class as primary query
     */
    public function useLicensePaymentQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLicensePayment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LicensePayment', '\PGS\CoreDomainBundle\Model\LicensePaymentQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   UserLicense $userLicense Object to remove from the list of results
     *
     * @return UserLicenseQuery The current query, for fluid interface
     */
    public function prune($userLicense = null)
    {
        if ($userLicense) {
            $this->addUsingAlias(UserLicensePeer::ID, $userLicense->getId(), Criteria::NOT_EQUAL);
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
        // archivable behavior

        if ($this->archiveOnDelete) {
            $this->archive($con);
        } else {
            $this->archiveOnDelete = true;
        }

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
     * @return     UserLicenseQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(UserLicensePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     UserLicenseQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserLicensePeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     UserLicenseQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserLicensePeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     UserLicenseQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(UserLicensePeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     UserLicenseQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserLicensePeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     UserLicenseQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserLicensePeer::CREATED_AT);
    }
    // archivable behavior

    /**
     * Copy the data of the objects satisfying the query into UserLicenseArchive archive objects.
     * The archived objects are then saved.
     * If any of the objects has already been archived, the archived object
     * is updated and not duplicated.
     * Warning: This termination methods issues 2n+1 queries.
     *
     * @param      PropelPDO $con	Connection to use.
     * @param      Boolean $useLittleMemory	Whether or not to use PropelOnDemandFormatter to retrieve objects.
     *               Set to false if the identity map matters.
     *               Set to true (default) to use less memory.
     *
     * @return     int the number of archived objects
     * @throws     PropelException
     */
    public function archive($con = null, $useLittleMemory = true)
    {
        $totalArchivedObjects = 0;
        $criteria = clone $this;
        // prepare the query
        $criteria->setWith(array());
        if ($useLittleMemory) {
            $criteria->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);
        }
        if ($con === null) {
            $con = Propel::getConnection(UserLicensePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $con->beginTransaction();
        try {
            // archive all results one by one
            foreach ($criteria->find($con) as $object) {
                $object->archive($con);
                $totalArchivedObjects++;
            }
            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }

        return $totalArchivedObjects;
    }

    /**
     * Enable/disable auto-archiving on delete for the next query.
     *
     * @param boolean $archiveOnDelete True if the query must archive deleted objects, false otherwise.
     */
    public function setArchiveOnDelete($archiveOnDelete)
    {
        $this->archiveOnDelete = $archiveOnDelete;
    }

    /**
     * Delete records matching the current query without archiving them.
     *
     * @param      PropelPDO $con	Connection to use.
     *
     * @return integer the number of deleted rows
     */
    public function deleteWithoutArchive($con = null)
    {
        $this->archiveOnDelete = false;

        return $this->delete($con);
    }

    /**
     * Delete all records without archiving them.
     *
     * @param      PropelPDO $con	Connection to use.
     *
     * @return integer the number of deleted rows
     */
    public function deleteAllWithoutArchive($con = null)
    {
        $this->archiveOnDelete = false;

        return $this->deleteAll($con);
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
