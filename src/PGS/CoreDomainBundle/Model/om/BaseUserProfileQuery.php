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
use PGS\CoreDomainBundle\Model\Country;
use PGS\CoreDomainBundle\Model\State;
use PGS\CoreDomainBundle\Model\User;
use PGS\CoreDomainBundle\Model\UserProfile;
use PGS\CoreDomainBundle\Model\UserProfilePeer;
use PGS\CoreDomainBundle\Model\UserProfileQuery;
use PGS\CoreDomainBundle\Model\Principal\Principal;

/**
 * @method UserProfileQuery orderByPrefix($order = Criteria::ASC) Order by the prefix column
 * @method UserProfileQuery orderByPrincipalId($order = Criteria::ASC) Order by the principal_id column
 * @method UserProfileQuery orderByNickName($order = Criteria::ASC) Order by the nick_name column
 * @method UserProfileQuery orderByFirstName($order = Criteria::ASC) Order by the first_name column
 * @method UserProfileQuery orderByMiddleName($order = Criteria::ASC) Order by the middle_name column
 * @method UserProfileQuery orderByLastName($order = Criteria::ASC) Order by the last_name column
 * @method UserProfileQuery orderByPhone($order = Criteria::ASC) Order by the phone column
 * @method UserProfileQuery orderByMobile($order = Criteria::ASC) Order by the mobile column
 * @method UserProfileQuery orderByAddress($order = Criteria::ASC) Order by the address column
 * @method UserProfileQuery orderByBusinessAddress($order = Criteria::ASC) Order by the business_address column
 * @method UserProfileQuery orderByOccupation($order = Criteria::ASC) Order by the occupation column
 * @method UserProfileQuery orderByCity($order = Criteria::ASC) Order by the city column
 * @method UserProfileQuery orderByStateId($order = Criteria::ASC) Order by the state_id column
 * @method UserProfileQuery orderByZip($order = Criteria::ASC) Order by the zip column
 * @method UserProfileQuery orderByCountryId($order = Criteria::ASC) Order by the country_id column
 * @method UserProfileQuery orderByActivePreferences($order = Criteria::ASC) Order by the active_preferences column
 * @method UserProfileQuery orderByComplete($order = Criteria::ASC) Order by the complete column
 * @method UserProfileQuery orderById($order = Criteria::ASC) Order by the id column
 *
 * @method UserProfileQuery groupByPrefix() Group by the prefix column
 * @method UserProfileQuery groupByPrincipalId() Group by the principal_id column
 * @method UserProfileQuery groupByNickName() Group by the nick_name column
 * @method UserProfileQuery groupByFirstName() Group by the first_name column
 * @method UserProfileQuery groupByMiddleName() Group by the middle_name column
 * @method UserProfileQuery groupByLastName() Group by the last_name column
 * @method UserProfileQuery groupByPhone() Group by the phone column
 * @method UserProfileQuery groupByMobile() Group by the mobile column
 * @method UserProfileQuery groupByAddress() Group by the address column
 * @method UserProfileQuery groupByBusinessAddress() Group by the business_address column
 * @method UserProfileQuery groupByOccupation() Group by the occupation column
 * @method UserProfileQuery groupByCity() Group by the city column
 * @method UserProfileQuery groupByStateId() Group by the state_id column
 * @method UserProfileQuery groupByZip() Group by the zip column
 * @method UserProfileQuery groupByCountryId() Group by the country_id column
 * @method UserProfileQuery groupByActivePreferences() Group by the active_preferences column
 * @method UserProfileQuery groupByComplete() Group by the complete column
 * @method UserProfileQuery groupById() Group by the id column
 *
 * @method UserProfileQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method UserProfileQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method UserProfileQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method UserProfileQuery leftJoinState($relationAlias = null) Adds a LEFT JOIN clause to the query using the State relation
 * @method UserProfileQuery rightJoinState($relationAlias = null) Adds a RIGHT JOIN clause to the query using the State relation
 * @method UserProfileQuery innerJoinState($relationAlias = null) Adds a INNER JOIN clause to the query using the State relation
 *
 * @method UserProfileQuery leftJoinCountry($relationAlias = null) Adds a LEFT JOIN clause to the query using the Country relation
 * @method UserProfileQuery rightJoinCountry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Country relation
 * @method UserProfileQuery innerJoinCountry($relationAlias = null) Adds a INNER JOIN clause to the query using the Country relation
 *
 * @method UserProfileQuery leftJoinPrincipal($relationAlias = null) Adds a LEFT JOIN clause to the query using the Principal relation
 * @method UserProfileQuery rightJoinPrincipal($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Principal relation
 * @method UserProfileQuery innerJoinPrincipal($relationAlias = null) Adds a INNER JOIN clause to the query using the Principal relation
 *
 * @method UserProfileQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method UserProfileQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method UserProfileQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method UserProfile findOne(PropelPDO $con = null) Return the first UserProfile matching the query
 * @method UserProfile findOneOrCreate(PropelPDO $con = null) Return the first UserProfile matching the query, or a new UserProfile object populated from the query conditions when no match is found
 *
 * @method UserProfile findOneByPrefix(string $prefix) Return the first UserProfile filtered by the prefix column
 * @method UserProfile findOneByPrincipalId(int $principal_id) Return the first UserProfile filtered by the principal_id column
 * @method UserProfile findOneByNickName(string $nick_name) Return the first UserProfile filtered by the nick_name column
 * @method UserProfile findOneByFirstName(string $first_name) Return the first UserProfile filtered by the first_name column
 * @method UserProfile findOneByMiddleName(string $middle_name) Return the first UserProfile filtered by the middle_name column
 * @method UserProfile findOneByLastName(string $last_name) Return the first UserProfile filtered by the last_name column
 * @method UserProfile findOneByPhone(string $phone) Return the first UserProfile filtered by the phone column
 * @method UserProfile findOneByMobile(string $mobile) Return the first UserProfile filtered by the mobile column
 * @method UserProfile findOneByAddress(string $address) Return the first UserProfile filtered by the address column
 * @method UserProfile findOneByBusinessAddress(string $business_address) Return the first UserProfile filtered by the business_address column
 * @method UserProfile findOneByOccupation(string $occupation) Return the first UserProfile filtered by the occupation column
 * @method UserProfile findOneByCity(string $city) Return the first UserProfile filtered by the city column
 * @method UserProfile findOneByStateId(int $state_id) Return the first UserProfile filtered by the state_id column
 * @method UserProfile findOneByZip(string $zip) Return the first UserProfile filtered by the zip column
 * @method UserProfile findOneByCountryId(int $country_id) Return the first UserProfile filtered by the country_id column
 * @method UserProfile findOneByActivePreferences(string $active_preferences) Return the first UserProfile filtered by the active_preferences column
 * @method UserProfile findOneByComplete(boolean $complete) Return the first UserProfile filtered by the complete column
 *
 * @method array findByPrefix(string $prefix) Return UserProfile objects filtered by the prefix column
 * @method array findByPrincipalId(int $principal_id) Return UserProfile objects filtered by the principal_id column
 * @method array findByNickName(string $nick_name) Return UserProfile objects filtered by the nick_name column
 * @method array findByFirstName(string $first_name) Return UserProfile objects filtered by the first_name column
 * @method array findByMiddleName(string $middle_name) Return UserProfile objects filtered by the middle_name column
 * @method array findByLastName(string $last_name) Return UserProfile objects filtered by the last_name column
 * @method array findByPhone(string $phone) Return UserProfile objects filtered by the phone column
 * @method array findByMobile(string $mobile) Return UserProfile objects filtered by the mobile column
 * @method array findByAddress(string $address) Return UserProfile objects filtered by the address column
 * @method array findByBusinessAddress(string $business_address) Return UserProfile objects filtered by the business_address column
 * @method array findByOccupation(string $occupation) Return UserProfile objects filtered by the occupation column
 * @method array findByCity(string $city) Return UserProfile objects filtered by the city column
 * @method array findByStateId(int $state_id) Return UserProfile objects filtered by the state_id column
 * @method array findByZip(string $zip) Return UserProfile objects filtered by the zip column
 * @method array findByCountryId(int $country_id) Return UserProfile objects filtered by the country_id column
 * @method array findByActivePreferences(string $active_preferences) Return UserProfile objects filtered by the active_preferences column
 * @method array findByComplete(boolean $complete) Return UserProfile objects filtered by the complete column
 * @method array findById(int $id) Return UserProfile objects filtered by the id column
 */
abstract class BaseUserProfileQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseUserProfileQuery object.
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
            $modelName = 'PGS\\CoreDomainBundle\\Model\\UserProfile';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new UserProfileQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   UserProfileQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return UserProfileQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof UserProfileQuery) {
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
     * @return   UserProfile|UserProfile[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserProfilePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(UserProfilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 UserProfile A model object, or null if the key is not found
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
     * @return                 UserProfile A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `prefix`, `principal_id`, `nick_name`, `first_name`, `middle_name`, `last_name`, `phone`, `mobile`, `address`, `business_address`, `occupation`, `city`, `state_id`, `zip`, `country_id`, `active_preferences`, `complete`, `id` FROM `user_profile` WHERE `id` = :p0';
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
            $cls = UserProfilePeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            UserProfilePeer::addInstanceToPool($obj, (string) $key);
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
     * @return UserProfile|UserProfile[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|UserProfile[]|mixed the list of results, formatted by the current formatter
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
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserProfilePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserProfilePeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the prefix column
     *
     * Example usage:
     * <code>
     * $query->filterByPrefix('fooValue');   // WHERE prefix = 'fooValue'
     * $query->filterByPrefix('%fooValue%'); // WHERE prefix LIKE '%fooValue%'
     * </code>
     *
     * @param     string $prefix The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function filterByPrefix($prefix = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($prefix)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $prefix)) {
                $prefix = str_replace('*', '%', $prefix);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserProfilePeer::PREFIX, $prefix, $comparison);
    }

    /**
     * Filter the query on the principal_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPrincipalId(1234); // WHERE principal_id = 1234
     * $query->filterByPrincipalId(array(12, 34)); // WHERE principal_id IN (12, 34)
     * $query->filterByPrincipalId(array('min' => 12)); // WHERE principal_id >= 12
     * $query->filterByPrincipalId(array('max' => 12)); // WHERE principal_id <= 12
     * </code>
     *
     * @see       filterByPrincipal()
     *
     * @param     mixed $principalId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function filterByPrincipalId($principalId = null, $comparison = null)
    {
        if (is_array($principalId)) {
            $useMinMax = false;
            if (isset($principalId['min'])) {
                $this->addUsingAlias(UserProfilePeer::PRINCIPAL_ID, $principalId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($principalId['max'])) {
                $this->addUsingAlias(UserProfilePeer::PRINCIPAL_ID, $principalId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserProfilePeer::PRINCIPAL_ID, $principalId, $comparison);
    }

    /**
     * Filter the query on the nick_name column
     *
     * Example usage:
     * <code>
     * $query->filterByNickName('fooValue');   // WHERE nick_name = 'fooValue'
     * $query->filterByNickName('%fooValue%'); // WHERE nick_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nickName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function filterByNickName($nickName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nickName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $nickName)) {
                $nickName = str_replace('*', '%', $nickName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserProfilePeer::NICK_NAME, $nickName, $comparison);
    }

    /**
     * Filter the query on the first_name column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstName('fooValue');   // WHERE first_name = 'fooValue'
     * $query->filterByFirstName('%fooValue%'); // WHERE first_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $firstName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function filterByFirstName($firstName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $firstName)) {
                $firstName = str_replace('*', '%', $firstName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserProfilePeer::FIRST_NAME, $firstName, $comparison);
    }

    /**
     * Filter the query on the middle_name column
     *
     * Example usage:
     * <code>
     * $query->filterByMiddleName('fooValue');   // WHERE middle_name = 'fooValue'
     * $query->filterByMiddleName('%fooValue%'); // WHERE middle_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $middleName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function filterByMiddleName($middleName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($middleName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $middleName)) {
                $middleName = str_replace('*', '%', $middleName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserProfilePeer::MIDDLE_NAME, $middleName, $comparison);
    }

    /**
     * Filter the query on the last_name column
     *
     * Example usage:
     * <code>
     * $query->filterByLastName('fooValue');   // WHERE last_name = 'fooValue'
     * $query->filterByLastName('%fooValue%'); // WHERE last_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function filterByLastName($lastName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $lastName)) {
                $lastName = str_replace('*', '%', $lastName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserProfilePeer::LAST_NAME, $lastName, $comparison);
    }

    /**
     * Filter the query on the phone column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone('fooValue');   // WHERE phone = 'fooValue'
     * $query->filterByPhone('%fooValue%'); // WHERE phone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $phone The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function filterByPhone($phone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $phone)) {
                $phone = str_replace('*', '%', $phone);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserProfilePeer::PHONE, $phone, $comparison);
    }

    /**
     * Filter the query on the mobile column
     *
     * Example usage:
     * <code>
     * $query->filterByMobile('fooValue');   // WHERE mobile = 'fooValue'
     * $query->filterByMobile('%fooValue%'); // WHERE mobile LIKE '%fooValue%'
     * </code>
     *
     * @param     string $mobile The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function filterByMobile($mobile = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mobile)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $mobile)) {
                $mobile = str_replace('*', '%', $mobile);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserProfilePeer::MOBILE, $mobile, $comparison);
    }

    /**
     * Filter the query on the address column
     *
     * Example usage:
     * <code>
     * $query->filterByAddress('fooValue');   // WHERE address = 'fooValue'
     * $query->filterByAddress('%fooValue%'); // WHERE address LIKE '%fooValue%'
     * </code>
     *
     * @param     string $address The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function filterByAddress($address = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($address)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $address)) {
                $address = str_replace('*', '%', $address);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserProfilePeer::ADDRESS, $address, $comparison);
    }

    /**
     * Filter the query on the business_address column
     *
     * Example usage:
     * <code>
     * $query->filterByBusinessAddress('fooValue');   // WHERE business_address = 'fooValue'
     * $query->filterByBusinessAddress('%fooValue%'); // WHERE business_address LIKE '%fooValue%'
     * </code>
     *
     * @param     string $businessAddress The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function filterByBusinessAddress($businessAddress = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($businessAddress)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $businessAddress)) {
                $businessAddress = str_replace('*', '%', $businessAddress);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserProfilePeer::BUSINESS_ADDRESS, $businessAddress, $comparison);
    }

    /**
     * Filter the query on the occupation column
     *
     * Example usage:
     * <code>
     * $query->filterByOccupation('fooValue');   // WHERE occupation = 'fooValue'
     * $query->filterByOccupation('%fooValue%'); // WHERE occupation LIKE '%fooValue%'
     * </code>
     *
     * @param     string $occupation The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function filterByOccupation($occupation = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($occupation)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $occupation)) {
                $occupation = str_replace('*', '%', $occupation);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserProfilePeer::OCCUPATION, $occupation, $comparison);
    }

    /**
     * Filter the query on the city column
     *
     * Example usage:
     * <code>
     * $query->filterByCity('fooValue');   // WHERE city = 'fooValue'
     * $query->filterByCity('%fooValue%'); // WHERE city LIKE '%fooValue%'
     * </code>
     *
     * @param     string $city The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function filterByCity($city = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($city)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $city)) {
                $city = str_replace('*', '%', $city);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserProfilePeer::CITY, $city, $comparison);
    }

    /**
     * Filter the query on the state_id column
     *
     * Example usage:
     * <code>
     * $query->filterByStateId(1234); // WHERE state_id = 1234
     * $query->filterByStateId(array(12, 34)); // WHERE state_id IN (12, 34)
     * $query->filterByStateId(array('min' => 12)); // WHERE state_id >= 12
     * $query->filterByStateId(array('max' => 12)); // WHERE state_id <= 12
     * </code>
     *
     * @see       filterByState()
     *
     * @param     mixed $stateId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function filterByStateId($stateId = null, $comparison = null)
    {
        if (is_array($stateId)) {
            $useMinMax = false;
            if (isset($stateId['min'])) {
                $this->addUsingAlias(UserProfilePeer::STATE_ID, $stateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($stateId['max'])) {
                $this->addUsingAlias(UserProfilePeer::STATE_ID, $stateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserProfilePeer::STATE_ID, $stateId, $comparison);
    }

    /**
     * Filter the query on the zip column
     *
     * Example usage:
     * <code>
     * $query->filterByZip('fooValue');   // WHERE zip = 'fooValue'
     * $query->filterByZip('%fooValue%'); // WHERE zip LIKE '%fooValue%'
     * </code>
     *
     * @param     string $zip The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function filterByZip($zip = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($zip)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $zip)) {
                $zip = str_replace('*', '%', $zip);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserProfilePeer::ZIP, $zip, $comparison);
    }

    /**
     * Filter the query on the country_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCountryId(1234); // WHERE country_id = 1234
     * $query->filterByCountryId(array(12, 34)); // WHERE country_id IN (12, 34)
     * $query->filterByCountryId(array('min' => 12)); // WHERE country_id >= 12
     * $query->filterByCountryId(array('max' => 12)); // WHERE country_id <= 12
     * </code>
     *
     * @see       filterByCountry()
     *
     * @param     mixed $countryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function filterByCountryId($countryId = null, $comparison = null)
    {
        if (is_array($countryId)) {
            $useMinMax = false;
            if (isset($countryId['min'])) {
                $this->addUsingAlias(UserProfilePeer::COUNTRY_ID, $countryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($countryId['max'])) {
                $this->addUsingAlias(UserProfilePeer::COUNTRY_ID, $countryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserProfilePeer::COUNTRY_ID, $countryId, $comparison);
    }

    /**
     * Filter the query on the active_preferences column
     *
     * Example usage:
     * <code>
     * $query->filterByActivePreferences('fooValue');   // WHERE active_preferences = 'fooValue'
     * $query->filterByActivePreferences('%fooValue%'); // WHERE active_preferences LIKE '%fooValue%'
     * </code>
     *
     * @param     string $activePreferences The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function filterByActivePreferences($activePreferences = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($activePreferences)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $activePreferences)) {
                $activePreferences = str_replace('*', '%', $activePreferences);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserProfilePeer::ACTIVE_PREFERENCES, $activePreferences, $comparison);
    }

    /**
     * Filter the query on the complete column
     *
     * Example usage:
     * <code>
     * $query->filterByComplete(true); // WHERE complete = true
     * $query->filterByComplete('yes'); // WHERE complete = true
     * </code>
     *
     * @param     boolean|string $complete The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function filterByComplete($complete = null, $comparison = null)
    {
        if (is_string($complete)) {
            $complete = in_array(strtolower($complete), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserProfilePeer::COMPLETE, $complete, $comparison);
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
     * @see       filterByUser()
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UserProfilePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserProfilePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserProfilePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query by a related State object
     *
     * @param   State|PropelObjectCollection $state The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserProfileQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByState($state, $comparison = null)
    {
        if ($state instanceof State) {
            return $this
                ->addUsingAlias(UserProfilePeer::STATE_ID, $state->getId(), $comparison);
        } elseif ($state instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserProfilePeer::STATE_ID, $state->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByState() only accepts arguments of type State or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the State relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function joinState($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('State');

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
            $this->addJoinObject($join, 'State');
        }

        return $this;
    }

    /**
     * Use the State relation State object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\StateQuery A secondary query class using the current class as primary query
     */
    public function useStateQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinState($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'State', '\PGS\CoreDomainBundle\Model\StateQuery');
    }

    /**
     * Filter the query by a related Country object
     *
     * @param   Country|PropelObjectCollection $country The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserProfileQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCountry($country, $comparison = null)
    {
        if ($country instanceof Country) {
            return $this
                ->addUsingAlias(UserProfilePeer::COUNTRY_ID, $country->getId(), $comparison);
        } elseif ($country instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserProfilePeer::COUNTRY_ID, $country->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCountry() only accepts arguments of type Country or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Country relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function joinCountry($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Country');

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
            $this->addJoinObject($join, 'Country');
        }

        return $this;
    }

    /**
     * Use the Country relation Country object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\CountryQuery A secondary query class using the current class as primary query
     */
    public function useCountryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCountry($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Country', '\PGS\CoreDomainBundle\Model\CountryQuery');
    }

    /**
     * Filter the query by a related Principal object
     *
     * @param   Principal|PropelObjectCollection $principal The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserProfileQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPrincipal($principal, $comparison = null)
    {
        if ($principal instanceof Principal) {
            return $this
                ->addUsingAlias(UserProfilePeer::PRINCIPAL_ID, $principal->getId(), $comparison);
        } elseif ($principal instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserProfilePeer::PRINCIPAL_ID, $principal->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPrincipal() only accepts arguments of type Principal or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Principal relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function joinPrincipal($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Principal');

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
            $this->addJoinObject($join, 'Principal');
        }

        return $this;
    }

    /**
     * Use the Principal relation Principal object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\Principal\PrincipalQuery A secondary query class using the current class as primary query
     */
    public function usePrincipalQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPrincipal($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Principal', '\PGS\CoreDomainBundle\Model\Principal\PrincipalQuery');
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserProfileQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(UserProfilePeer::ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserProfilePeer::ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = 'LEFT JOIN')
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
    public function useUserQuery($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\PGS\CoreDomainBundle\Model\UserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   UserProfile $userProfile Object to remove from the list of results
     *
     * @return UserProfileQuery The current query, for fluid interface
     */
    public function prune($userProfile = null)
    {
        if ($userProfile) {
            $this->addUsingAlias(UserProfilePeer::ID, $userProfile->getId(), Criteria::NOT_EQUAL);
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

    // extend behavior
    public function setFormatter($formatter)
    {
        if (is_string($formatter) && $formatter === \ModelCriteria::FORMAT_ON_DEMAND) {
            $formatter = '\Glorpen\Propel\PropelBundle\Formatter\PropelOnDemandFormatter';
        }

        return parent::setFormatter($formatter);
    }
}
