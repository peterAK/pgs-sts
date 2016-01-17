<?php

namespace PGS\CoreDomainBundle\Model\School\om;

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
use PGS\CoreDomainBundle\Model\School\SchoolArchive;
use PGS\CoreDomainBundle\Model\School\SchoolArchivePeer;
use PGS\CoreDomainBundle\Model\School\SchoolArchiveQuery;

/**
 * @method SchoolArchiveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method SchoolArchiveQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method SchoolArchiveQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method SchoolArchiveQuery orderByLevelId($order = Criteria::ASC) Order by the level_id column
 * @method SchoolArchiveQuery orderByNickName($order = Criteria::ASC) Order by the nick_name column
 * @method SchoolArchiveQuery orderByAddress($order = Criteria::ASC) Order by the address column
 * @method SchoolArchiveQuery orderByCity($order = Criteria::ASC) Order by the city column
 * @method SchoolArchiveQuery orderByStateId($order = Criteria::ASC) Order by the state_id column
 * @method SchoolArchiveQuery orderByZip($order = Criteria::ASC) Order by the zip column
 * @method SchoolArchiveQuery orderByCountryId($order = Criteria::ASC) Order by the country_id column
 * @method SchoolArchiveQuery orderByOrganizationId($order = Criteria::ASC) Order by the organization_id column
 * @method SchoolArchiveQuery orderByPhone($order = Criteria::ASC) Order by the phone column
 * @method SchoolArchiveQuery orderByFax($order = Criteria::ASC) Order by the fax column
 * @method SchoolArchiveQuery orderByMobile($order = Criteria::ASC) Order by the mobile column
 * @method SchoolArchiveQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method SchoolArchiveQuery orderByWebsite($order = Criteria::ASC) Order by the website column
 * @method SchoolArchiveQuery orderByLogo($order = Criteria::ASC) Order by the logo column
 * @method SchoolArchiveQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method SchoolArchiveQuery orderByExcerpt($order = Criteria::ASC) Order by the excerpt column
 * @method SchoolArchiveQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method SchoolArchiveQuery orderByConfirmation($order = Criteria::ASC) Order by the confirmation column
 * @method SchoolArchiveQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 * @method SchoolArchiveQuery orderByArchivedAt($order = Criteria::ASC) Order by the archived_at column
 * @method SchoolArchiveQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method SchoolArchiveQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method SchoolArchiveQuery groupById() Group by the id column
 * @method SchoolArchiveQuery groupByCode() Group by the code column
 * @method SchoolArchiveQuery groupByName() Group by the name column
 * @method SchoolArchiveQuery groupByLevelId() Group by the level_id column
 * @method SchoolArchiveQuery groupByNickName() Group by the nick_name column
 * @method SchoolArchiveQuery groupByAddress() Group by the address column
 * @method SchoolArchiveQuery groupByCity() Group by the city column
 * @method SchoolArchiveQuery groupByStateId() Group by the state_id column
 * @method SchoolArchiveQuery groupByZip() Group by the zip column
 * @method SchoolArchiveQuery groupByCountryId() Group by the country_id column
 * @method SchoolArchiveQuery groupByOrganizationId() Group by the organization_id column
 * @method SchoolArchiveQuery groupByPhone() Group by the phone column
 * @method SchoolArchiveQuery groupByFax() Group by the fax column
 * @method SchoolArchiveQuery groupByMobile() Group by the mobile column
 * @method SchoolArchiveQuery groupByEmail() Group by the email column
 * @method SchoolArchiveQuery groupByWebsite() Group by the website column
 * @method SchoolArchiveQuery groupByLogo() Group by the logo column
 * @method SchoolArchiveQuery groupByDescription() Group by the description column
 * @method SchoolArchiveQuery groupByExcerpt() Group by the excerpt column
 * @method SchoolArchiveQuery groupByStatus() Group by the status column
 * @method SchoolArchiveQuery groupByConfirmation() Group by the confirmation column
 * @method SchoolArchiveQuery groupBySortableRank() Group by the sortable_rank column
 * @method SchoolArchiveQuery groupByArchivedAt() Group by the archived_at column
 * @method SchoolArchiveQuery groupByCreatedAt() Group by the created_at column
 * @method SchoolArchiveQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method SchoolArchiveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method SchoolArchiveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method SchoolArchiveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method SchoolArchive findOne(PropelPDO $con = null) Return the first SchoolArchive matching the query
 * @method SchoolArchive findOneOrCreate(PropelPDO $con = null) Return the first SchoolArchive matching the query, or a new SchoolArchive object populated from the query conditions when no match is found
 *
 * @method SchoolArchive findOneByCode(string $code) Return the first SchoolArchive filtered by the code column
 * @method SchoolArchive findOneByName(string $name) Return the first SchoolArchive filtered by the name column
 * @method SchoolArchive findOneByLevelId(int $level_id) Return the first SchoolArchive filtered by the level_id column
 * @method SchoolArchive findOneByNickName(string $nick_name) Return the first SchoolArchive filtered by the nick_name column
 * @method SchoolArchive findOneByAddress(string $address) Return the first SchoolArchive filtered by the address column
 * @method SchoolArchive findOneByCity(string $city) Return the first SchoolArchive filtered by the city column
 * @method SchoolArchive findOneByStateId(int $state_id) Return the first SchoolArchive filtered by the state_id column
 * @method SchoolArchive findOneByZip(string $zip) Return the first SchoolArchive filtered by the zip column
 * @method SchoolArchive findOneByCountryId(int $country_id) Return the first SchoolArchive filtered by the country_id column
 * @method SchoolArchive findOneByOrganizationId(int $organization_id) Return the first SchoolArchive filtered by the organization_id column
 * @method SchoolArchive findOneByPhone(string $phone) Return the first SchoolArchive filtered by the phone column
 * @method SchoolArchive findOneByFax(string $fax) Return the first SchoolArchive filtered by the fax column
 * @method SchoolArchive findOneByMobile(string $mobile) Return the first SchoolArchive filtered by the mobile column
 * @method SchoolArchive findOneByEmail(string $email) Return the first SchoolArchive filtered by the email column
 * @method SchoolArchive findOneByWebsite(string $website) Return the first SchoolArchive filtered by the website column
 * @method SchoolArchive findOneByLogo(string $logo) Return the first SchoolArchive filtered by the logo column
 * @method SchoolArchive findOneByDescription(string $description) Return the first SchoolArchive filtered by the description column
 * @method SchoolArchive findOneByExcerpt(string $excerpt) Return the first SchoolArchive filtered by the excerpt column
 * @method SchoolArchive findOneByStatus(int $status) Return the first SchoolArchive filtered by the status column
 * @method SchoolArchive findOneByConfirmation(int $confirmation) Return the first SchoolArchive filtered by the confirmation column
 * @method SchoolArchive findOneBySortableRank(int $sortable_rank) Return the first SchoolArchive filtered by the sortable_rank column
 * @method SchoolArchive findOneByArchivedAt(string $archived_at) Return the first SchoolArchive filtered by the archived_at column
 * @method SchoolArchive findOneByCreatedAt(string $created_at) Return the first SchoolArchive filtered by the created_at column
 * @method SchoolArchive findOneByUpdatedAt(string $updated_at) Return the first SchoolArchive filtered by the updated_at column
 *
 * @method array findById(int $id) Return SchoolArchive objects filtered by the id column
 * @method array findByCode(string $code) Return SchoolArchive objects filtered by the code column
 * @method array findByName(string $name) Return SchoolArchive objects filtered by the name column
 * @method array findByLevelId(int $level_id) Return SchoolArchive objects filtered by the level_id column
 * @method array findByNickName(string $nick_name) Return SchoolArchive objects filtered by the nick_name column
 * @method array findByAddress(string $address) Return SchoolArchive objects filtered by the address column
 * @method array findByCity(string $city) Return SchoolArchive objects filtered by the city column
 * @method array findByStateId(int $state_id) Return SchoolArchive objects filtered by the state_id column
 * @method array findByZip(string $zip) Return SchoolArchive objects filtered by the zip column
 * @method array findByCountryId(int $country_id) Return SchoolArchive objects filtered by the country_id column
 * @method array findByOrganizationId(int $organization_id) Return SchoolArchive objects filtered by the organization_id column
 * @method array findByPhone(string $phone) Return SchoolArchive objects filtered by the phone column
 * @method array findByFax(string $fax) Return SchoolArchive objects filtered by the fax column
 * @method array findByMobile(string $mobile) Return SchoolArchive objects filtered by the mobile column
 * @method array findByEmail(string $email) Return SchoolArchive objects filtered by the email column
 * @method array findByWebsite(string $website) Return SchoolArchive objects filtered by the website column
 * @method array findByLogo(string $logo) Return SchoolArchive objects filtered by the logo column
 * @method array findByDescription(string $description) Return SchoolArchive objects filtered by the description column
 * @method array findByExcerpt(string $excerpt) Return SchoolArchive objects filtered by the excerpt column
 * @method array findByStatus(int $status) Return SchoolArchive objects filtered by the status column
 * @method array findByConfirmation(int $confirmation) Return SchoolArchive objects filtered by the confirmation column
 * @method array findBySortableRank(int $sortable_rank) Return SchoolArchive objects filtered by the sortable_rank column
 * @method array findByArchivedAt(string $archived_at) Return SchoolArchive objects filtered by the archived_at column
 * @method array findByCreatedAt(string $created_at) Return SchoolArchive objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return SchoolArchive objects filtered by the updated_at column
 */
abstract class BaseSchoolArchiveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseSchoolArchiveQuery object.
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
            $modelName = 'PGS\\CoreDomainBundle\\Model\\School\\SchoolArchive';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new SchoolArchiveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   SchoolArchiveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return SchoolArchiveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof SchoolArchiveQuery) {
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
     * @return   SchoolArchive|SchoolArchive[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SchoolArchivePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(SchoolArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 SchoolArchive A model object, or null if the key is not found
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
     * @return                 SchoolArchive A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `code`, `name`, `level_id`, `nick_name`, `address`, `city`, `state_id`, `zip`, `country_id`, `organization_id`, `phone`, `fax`, `mobile`, `email`, `website`, `logo`, `description`, `excerpt`, `status`, `confirmation`, `sortable_rank`, `archived_at`, `created_at`, `updated_at` FROM `school_archive` WHERE `id` = :p0';
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
            $cls = SchoolArchivePeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            SchoolArchivePeer::addInstanceToPool($obj, (string) $key);
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
     * @return SchoolArchive|SchoolArchive[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|SchoolArchive[]|mixed the list of results, formatted by the current formatter
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
     * @return SchoolArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SchoolArchivePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return SchoolArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SchoolArchivePeer::ID, $keys, Criteria::IN);
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
     * @return SchoolArchiveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SchoolArchivePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SchoolArchivePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolArchivePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE code = 'fooValue'
     * $query->filterByCode('%fooValue%'); // WHERE code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $code The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolArchiveQuery The current query, for fluid interface
     */
    public function filterByCode($code = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($code)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $code)) {
                $code = str_replace('*', '%', $code);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolArchivePeer::CODE, $code, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolArchiveQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolArchivePeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the level_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLevelId(1234); // WHERE level_id = 1234
     * $query->filterByLevelId(array(12, 34)); // WHERE level_id IN (12, 34)
     * $query->filterByLevelId(array('min' => 12)); // WHERE level_id >= 12
     * $query->filterByLevelId(array('max' => 12)); // WHERE level_id <= 12
     * </code>
     *
     * @param     mixed $levelId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolArchiveQuery The current query, for fluid interface
     */
    public function filterByLevelId($levelId = null, $comparison = null)
    {
        if (is_array($levelId)) {
            $useMinMax = false;
            if (isset($levelId['min'])) {
                $this->addUsingAlias(SchoolArchivePeer::LEVEL_ID, $levelId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($levelId['max'])) {
                $this->addUsingAlias(SchoolArchivePeer::LEVEL_ID, $levelId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolArchivePeer::LEVEL_ID, $levelId, $comparison);
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
     * @return SchoolArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(SchoolArchivePeer::NICK_NAME, $nickName, $comparison);
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
     * @return SchoolArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(SchoolArchivePeer::ADDRESS, $address, $comparison);
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
     * @return SchoolArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(SchoolArchivePeer::CITY, $city, $comparison);
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
     * @param     mixed $stateId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolArchiveQuery The current query, for fluid interface
     */
    public function filterByStateId($stateId = null, $comparison = null)
    {
        if (is_array($stateId)) {
            $useMinMax = false;
            if (isset($stateId['min'])) {
                $this->addUsingAlias(SchoolArchivePeer::STATE_ID, $stateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($stateId['max'])) {
                $this->addUsingAlias(SchoolArchivePeer::STATE_ID, $stateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolArchivePeer::STATE_ID, $stateId, $comparison);
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
     * @return SchoolArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(SchoolArchivePeer::ZIP, $zip, $comparison);
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
     * @param     mixed $countryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolArchiveQuery The current query, for fluid interface
     */
    public function filterByCountryId($countryId = null, $comparison = null)
    {
        if (is_array($countryId)) {
            $useMinMax = false;
            if (isset($countryId['min'])) {
                $this->addUsingAlias(SchoolArchivePeer::COUNTRY_ID, $countryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($countryId['max'])) {
                $this->addUsingAlias(SchoolArchivePeer::COUNTRY_ID, $countryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolArchivePeer::COUNTRY_ID, $countryId, $comparison);
    }

    /**
     * Filter the query on the organization_id column
     *
     * Example usage:
     * <code>
     * $query->filterByOrganizationId(1234); // WHERE organization_id = 1234
     * $query->filterByOrganizationId(array(12, 34)); // WHERE organization_id IN (12, 34)
     * $query->filterByOrganizationId(array('min' => 12)); // WHERE organization_id >= 12
     * $query->filterByOrganizationId(array('max' => 12)); // WHERE organization_id <= 12
     * </code>
     *
     * @param     mixed $organizationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolArchiveQuery The current query, for fluid interface
     */
    public function filterByOrganizationId($organizationId = null, $comparison = null)
    {
        if (is_array($organizationId)) {
            $useMinMax = false;
            if (isset($organizationId['min'])) {
                $this->addUsingAlias(SchoolArchivePeer::ORGANIZATION_ID, $organizationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($organizationId['max'])) {
                $this->addUsingAlias(SchoolArchivePeer::ORGANIZATION_ID, $organizationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolArchivePeer::ORGANIZATION_ID, $organizationId, $comparison);
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
     * @return SchoolArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(SchoolArchivePeer::PHONE, $phone, $comparison);
    }

    /**
     * Filter the query on the fax column
     *
     * Example usage:
     * <code>
     * $query->filterByFax('fooValue');   // WHERE fax = 'fooValue'
     * $query->filterByFax('%fooValue%'); // WHERE fax LIKE '%fooValue%'
     * </code>
     *
     * @param     string $fax The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolArchiveQuery The current query, for fluid interface
     */
    public function filterByFax($fax = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fax)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $fax)) {
                $fax = str_replace('*', '%', $fax);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolArchivePeer::FAX, $fax, $comparison);
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
     * @return SchoolArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(SchoolArchivePeer::MOBILE, $mobile, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%'); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolArchiveQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $email)) {
                $email = str_replace('*', '%', $email);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolArchivePeer::EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the website column
     *
     * Example usage:
     * <code>
     * $query->filterByWebsite('fooValue');   // WHERE website = 'fooValue'
     * $query->filterByWebsite('%fooValue%'); // WHERE website LIKE '%fooValue%'
     * </code>
     *
     * @param     string $website The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolArchiveQuery The current query, for fluid interface
     */
    public function filterByWebsite($website = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($website)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $website)) {
                $website = str_replace('*', '%', $website);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolArchivePeer::WEBSITE, $website, $comparison);
    }

    /**
     * Filter the query on the logo column
     *
     * Example usage:
     * <code>
     * $query->filterByLogo('fooValue');   // WHERE logo = 'fooValue'
     * $query->filterByLogo('%fooValue%'); // WHERE logo LIKE '%fooValue%'
     * </code>
     *
     * @param     string $logo The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolArchiveQuery The current query, for fluid interface
     */
    public function filterByLogo($logo = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($logo)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $logo)) {
                $logo = str_replace('*', '%', $logo);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolArchivePeer::LOGO, $logo, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolArchiveQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $description)) {
                $description = str_replace('*', '%', $description);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolArchivePeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the excerpt column
     *
     * Example usage:
     * <code>
     * $query->filterByExcerpt('fooValue');   // WHERE excerpt = 'fooValue'
     * $query->filterByExcerpt('%fooValue%'); // WHERE excerpt LIKE '%fooValue%'
     * </code>
     *
     * @param     string $excerpt The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolArchiveQuery The current query, for fluid interface
     */
    public function filterByExcerpt($excerpt = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($excerpt)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $excerpt)) {
                $excerpt = str_replace('*', '%', $excerpt);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolArchivePeer::EXCERPT, $excerpt, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * @param     mixed $status The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolArchiveQuery The current query, for fluid interface
     * @throws PropelException - if the value is not accepted by the enum.
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_scalar($status)) {
            $status = SchoolArchivePeer::getSqlValueForEnum(SchoolArchivePeer::STATUS, $status);
        } elseif (is_array($status)) {
            $convertedValues = array();
            foreach ($status as $value) {
                $convertedValues[] = SchoolArchivePeer::getSqlValueForEnum(SchoolArchivePeer::STATUS, $value);
            }
            $status = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolArchivePeer::STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the confirmation column
     *
     * @param     mixed $confirmation The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolArchiveQuery The current query, for fluid interface
     * @throws PropelException - if the value is not accepted by the enum.
     */
    public function filterByConfirmation($confirmation = null, $comparison = null)
    {
        if (is_scalar($confirmation)) {
            $confirmation = SchoolArchivePeer::getSqlValueForEnum(SchoolArchivePeer::CONFIRMATION, $confirmation);
        } elseif (is_array($confirmation)) {
            $convertedValues = array();
            foreach ($confirmation as $value) {
                $convertedValues[] = SchoolArchivePeer::getSqlValueForEnum(SchoolArchivePeer::CONFIRMATION, $value);
            }
            $confirmation = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolArchivePeer::CONFIRMATION, $confirmation, $comparison);
    }

    /**
     * Filter the query on the sortable_rank column
     *
     * Example usage:
     * <code>
     * $query->filterBySortableRank(1234); // WHERE sortable_rank = 1234
     * $query->filterBySortableRank(array(12, 34)); // WHERE sortable_rank IN (12, 34)
     * $query->filterBySortableRank(array('min' => 12)); // WHERE sortable_rank >= 12
     * $query->filterBySortableRank(array('max' => 12)); // WHERE sortable_rank <= 12
     * </code>
     *
     * @param     mixed $sortableRank The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolArchiveQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(SchoolArchivePeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(SchoolArchivePeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolArchivePeer::SORTABLE_RANK, $sortableRank, $comparison);
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
     * @return SchoolArchiveQuery The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(SchoolArchivePeer::ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(SchoolArchivePeer::ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolArchivePeer::ARCHIVED_AT, $archivedAt, $comparison);
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
     * @return SchoolArchiveQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(SchoolArchivePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(SchoolArchivePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolArchivePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return SchoolArchiveQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(SchoolArchivePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(SchoolArchivePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolArchivePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   SchoolArchive $schoolArchive Object to remove from the list of results
     *
     * @return SchoolArchiveQuery The current query, for fluid interface
     */
    public function prune($schoolArchive = null)
    {
        if ($schoolArchive) {
            $this->addUsingAlias(SchoolArchivePeer::ID, $schoolArchive->getId(), Criteria::NOT_EQUAL);
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
     * @return     SchoolArchiveQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(SchoolArchivePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     SchoolArchiveQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(SchoolArchivePeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     SchoolArchiveQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(SchoolArchivePeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     SchoolArchiveQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(SchoolArchivePeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     SchoolArchiveQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(SchoolArchivePeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     SchoolArchiveQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(SchoolArchivePeer::CREATED_AT);
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
