<?php

namespace PGS\CoreDomainBundle\Model\Principal\om;

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
use PGS\CoreDomainBundle\Model\Principal\PrincipalArchive;
use PGS\CoreDomainBundle\Model\Principal\PrincipalArchivePeer;
use PGS\CoreDomainBundle\Model\Principal\PrincipalArchiveQuery;

/**
 * @method PrincipalArchiveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PrincipalArchiveQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method PrincipalArchiveQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method PrincipalArchiveQuery orderByNameSlug($order = Criteria::ASC) Order by the name_slug column
 * @method PrincipalArchiveQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PrincipalArchiveQuery orderByExcerpt($order = Criteria::ASC) Order by the excerpt column
 * @method PrincipalArchiveQuery orderByGovermentLicense($order = Criteria::ASC) Order by the goverment_license column
 * @method PrincipalArchiveQuery orderByJoinAt($order = Criteria::ASC) Order by the join_at column
 * @method PrincipalArchiveQuery orderByAddress1($order = Criteria::ASC) Order by the address1 column
 * @method PrincipalArchiveQuery orderByAddress2($order = Criteria::ASC) Order by the address2 column
 * @method PrincipalArchiveQuery orderByCity($order = Criteria::ASC) Order by the city column
 * @method PrincipalArchiveQuery orderByZipcode($order = Criteria::ASC) Order by the zipcode column
 * @method PrincipalArchiveQuery orderByCountryId($order = Criteria::ASC) Order by the country_id column
 * @method PrincipalArchiveQuery orderByStateId($order = Criteria::ASC) Order by the state_id column
 * @method PrincipalArchiveQuery orderByPhone($order = Criteria::ASC) Order by the phone column
 * @method PrincipalArchiveQuery orderByFax($order = Criteria::ASC) Order by the fax column
 * @method PrincipalArchiveQuery orderByMobile($order = Criteria::ASC) Order by the mobile column
 * @method PrincipalArchiveQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method PrincipalArchiveQuery orderByWebsite($order = Criteria::ASC) Order by the website column
 * @method PrincipalArchiveQuery orderByLogo($order = Criteria::ASC) Order by the logo column
 * @method PrincipalArchiveQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method PrincipalArchiveQuery orderByIsPrincipal($order = Criteria::ASC) Order by the is_principal column
 * @method PrincipalArchiveQuery orderByConfirmation($order = Criteria::ASC) Order by the confirmation column
 * @method PrincipalArchiveQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 * @method PrincipalArchiveQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PrincipalArchiveQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PrincipalArchiveQuery orderByArchivedAt($order = Criteria::ASC) Order by the archived_at column
 *
 * @method PrincipalArchiveQuery groupById() Group by the id column
 * @method PrincipalArchiveQuery groupByUserId() Group by the user_id column
 * @method PrincipalArchiveQuery groupByName() Group by the name column
 * @method PrincipalArchiveQuery groupByNameSlug() Group by the name_slug column
 * @method PrincipalArchiveQuery groupByDescription() Group by the description column
 * @method PrincipalArchiveQuery groupByExcerpt() Group by the excerpt column
 * @method PrincipalArchiveQuery groupByGovermentLicense() Group by the goverment_license column
 * @method PrincipalArchiveQuery groupByJoinAt() Group by the join_at column
 * @method PrincipalArchiveQuery groupByAddress1() Group by the address1 column
 * @method PrincipalArchiveQuery groupByAddress2() Group by the address2 column
 * @method PrincipalArchiveQuery groupByCity() Group by the city column
 * @method PrincipalArchiveQuery groupByZipcode() Group by the zipcode column
 * @method PrincipalArchiveQuery groupByCountryId() Group by the country_id column
 * @method PrincipalArchiveQuery groupByStateId() Group by the state_id column
 * @method PrincipalArchiveQuery groupByPhone() Group by the phone column
 * @method PrincipalArchiveQuery groupByFax() Group by the fax column
 * @method PrincipalArchiveQuery groupByMobile() Group by the mobile column
 * @method PrincipalArchiveQuery groupByEmail() Group by the email column
 * @method PrincipalArchiveQuery groupByWebsite() Group by the website column
 * @method PrincipalArchiveQuery groupByLogo() Group by the logo column
 * @method PrincipalArchiveQuery groupByStatus() Group by the status column
 * @method PrincipalArchiveQuery groupByIsPrincipal() Group by the is_principal column
 * @method PrincipalArchiveQuery groupByConfirmation() Group by the confirmation column
 * @method PrincipalArchiveQuery groupBySortableRank() Group by the sortable_rank column
 * @method PrincipalArchiveQuery groupByCreatedAt() Group by the created_at column
 * @method PrincipalArchiveQuery groupByUpdatedAt() Group by the updated_at column
 * @method PrincipalArchiveQuery groupByArchivedAt() Group by the archived_at column
 *
 * @method PrincipalArchiveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PrincipalArchiveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PrincipalArchiveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PrincipalArchive findOne(PropelPDO $con = null) Return the first PrincipalArchive matching the query
 * @method PrincipalArchive findOneOrCreate(PropelPDO $con = null) Return the first PrincipalArchive matching the query, or a new PrincipalArchive object populated from the query conditions when no match is found
 *
 * @method PrincipalArchive findOneByUserId(int $user_id) Return the first PrincipalArchive filtered by the user_id column
 * @method PrincipalArchive findOneByName(string $name) Return the first PrincipalArchive filtered by the name column
 * @method PrincipalArchive findOneByNameSlug(string $name_slug) Return the first PrincipalArchive filtered by the name_slug column
 * @method PrincipalArchive findOneByDescription(string $description) Return the first PrincipalArchive filtered by the description column
 * @method PrincipalArchive findOneByExcerpt(string $excerpt) Return the first PrincipalArchive filtered by the excerpt column
 * @method PrincipalArchive findOneByGovermentLicense(string $goverment_license) Return the first PrincipalArchive filtered by the goverment_license column
 * @method PrincipalArchive findOneByJoinAt(string $join_at) Return the first PrincipalArchive filtered by the join_at column
 * @method PrincipalArchive findOneByAddress1(string $address1) Return the first PrincipalArchive filtered by the address1 column
 * @method PrincipalArchive findOneByAddress2(string $address2) Return the first PrincipalArchive filtered by the address2 column
 * @method PrincipalArchive findOneByCity(string $city) Return the first PrincipalArchive filtered by the city column
 * @method PrincipalArchive findOneByZipcode(string $zipcode) Return the first PrincipalArchive filtered by the zipcode column
 * @method PrincipalArchive findOneByCountryId(int $country_id) Return the first PrincipalArchive filtered by the country_id column
 * @method PrincipalArchive findOneByStateId(int $state_id) Return the first PrincipalArchive filtered by the state_id column
 * @method PrincipalArchive findOneByPhone(string $phone) Return the first PrincipalArchive filtered by the phone column
 * @method PrincipalArchive findOneByFax(string $fax) Return the first PrincipalArchive filtered by the fax column
 * @method PrincipalArchive findOneByMobile(string $mobile) Return the first PrincipalArchive filtered by the mobile column
 * @method PrincipalArchive findOneByEmail(string $email) Return the first PrincipalArchive filtered by the email column
 * @method PrincipalArchive findOneByWebsite(string $website) Return the first PrincipalArchive filtered by the website column
 * @method PrincipalArchive findOneByLogo(string $logo) Return the first PrincipalArchive filtered by the logo column
 * @method PrincipalArchive findOneByStatus(int $status) Return the first PrincipalArchive filtered by the status column
 * @method PrincipalArchive findOneByIsPrincipal(boolean $is_principal) Return the first PrincipalArchive filtered by the is_principal column
 * @method PrincipalArchive findOneByConfirmation(int $confirmation) Return the first PrincipalArchive filtered by the confirmation column
 * @method PrincipalArchive findOneBySortableRank(int $sortable_rank) Return the first PrincipalArchive filtered by the sortable_rank column
 * @method PrincipalArchive findOneByCreatedAt(string $created_at) Return the first PrincipalArchive filtered by the created_at column
 * @method PrincipalArchive findOneByUpdatedAt(string $updated_at) Return the first PrincipalArchive filtered by the updated_at column
 * @method PrincipalArchive findOneByArchivedAt(string $archived_at) Return the first PrincipalArchive filtered by the archived_at column
 *
 * @method array findById(int $id) Return PrincipalArchive objects filtered by the id column
 * @method array findByUserId(int $user_id) Return PrincipalArchive objects filtered by the user_id column
 * @method array findByName(string $name) Return PrincipalArchive objects filtered by the name column
 * @method array findByNameSlug(string $name_slug) Return PrincipalArchive objects filtered by the name_slug column
 * @method array findByDescription(string $description) Return PrincipalArchive objects filtered by the description column
 * @method array findByExcerpt(string $excerpt) Return PrincipalArchive objects filtered by the excerpt column
 * @method array findByGovermentLicense(string $goverment_license) Return PrincipalArchive objects filtered by the goverment_license column
 * @method array findByJoinAt(string $join_at) Return PrincipalArchive objects filtered by the join_at column
 * @method array findByAddress1(string $address1) Return PrincipalArchive objects filtered by the address1 column
 * @method array findByAddress2(string $address2) Return PrincipalArchive objects filtered by the address2 column
 * @method array findByCity(string $city) Return PrincipalArchive objects filtered by the city column
 * @method array findByZipcode(string $zipcode) Return PrincipalArchive objects filtered by the zipcode column
 * @method array findByCountryId(int $country_id) Return PrincipalArchive objects filtered by the country_id column
 * @method array findByStateId(int $state_id) Return PrincipalArchive objects filtered by the state_id column
 * @method array findByPhone(string $phone) Return PrincipalArchive objects filtered by the phone column
 * @method array findByFax(string $fax) Return PrincipalArchive objects filtered by the fax column
 * @method array findByMobile(string $mobile) Return PrincipalArchive objects filtered by the mobile column
 * @method array findByEmail(string $email) Return PrincipalArchive objects filtered by the email column
 * @method array findByWebsite(string $website) Return PrincipalArchive objects filtered by the website column
 * @method array findByLogo(string $logo) Return PrincipalArchive objects filtered by the logo column
 * @method array findByStatus(int $status) Return PrincipalArchive objects filtered by the status column
 * @method array findByIsPrincipal(boolean $is_principal) Return PrincipalArchive objects filtered by the is_principal column
 * @method array findByConfirmation(int $confirmation) Return PrincipalArchive objects filtered by the confirmation column
 * @method array findBySortableRank(int $sortable_rank) Return PrincipalArchive objects filtered by the sortable_rank column
 * @method array findByCreatedAt(string $created_at) Return PrincipalArchive objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PrincipalArchive objects filtered by the updated_at column
 * @method array findByArchivedAt(string $archived_at) Return PrincipalArchive objects filtered by the archived_at column
 */
abstract class BasePrincipalArchiveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePrincipalArchiveQuery object.
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
            $modelName = 'PGS\\CoreDomainBundle\\Model\\Principal\\PrincipalArchive';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new PrincipalArchiveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PrincipalArchiveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PrincipalArchiveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PrincipalArchiveQuery) {
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
     * @return   PrincipalArchive|PrincipalArchive[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PrincipalArchivePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PrincipalArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PrincipalArchive A model object, or null if the key is not found
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
     * @return                 PrincipalArchive A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `user_id`, `name`, `name_slug`, `description`, `excerpt`, `goverment_license`, `join_at`, `address1`, `address2`, `city`, `zipcode`, `country_id`, `state_id`, `phone`, `fax`, `mobile`, `email`, `website`, `logo`, `status`, `is_principal`, `confirmation`, `sortable_rank`, `created_at`, `updated_at`, `archived_at` FROM `principal_archive` WHERE `id` = :p0';
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
            $cls = PrincipalArchivePeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            PrincipalArchivePeer::addInstanceToPool($obj, (string) $key);
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
     * @return PrincipalArchive|PrincipalArchive[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PrincipalArchive[]|mixed the list of results, formatted by the current formatter
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
     * @return PrincipalArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PrincipalArchivePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PrincipalArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PrincipalArchivePeer::ID, $keys, Criteria::IN);
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
     * @return PrincipalArchiveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PrincipalArchivePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PrincipalArchivePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PrincipalArchivePeer::ID, $id, $comparison);
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
     * @return PrincipalArchiveQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(PrincipalArchivePeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(PrincipalArchivePeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PrincipalArchivePeer::USER_ID, $userId, $comparison);
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
     * @return PrincipalArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PrincipalArchivePeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the name_slug column
     *
     * Example usage:
     * <code>
     * $query->filterByNameSlug('fooValue');   // WHERE name_slug = 'fooValue'
     * $query->filterByNameSlug('%fooValue%'); // WHERE name_slug LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nameSlug The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PrincipalArchiveQuery The current query, for fluid interface
     */
    public function filterByNameSlug($nameSlug = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nameSlug)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $nameSlug)) {
                $nameSlug = str_replace('*', '%', $nameSlug);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PrincipalArchivePeer::NAME_SLUG, $nameSlug, $comparison);
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
     * @return PrincipalArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PrincipalArchivePeer::DESCRIPTION, $description, $comparison);
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
     * @return PrincipalArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PrincipalArchivePeer::EXCERPT, $excerpt, $comparison);
    }

    /**
     * Filter the query on the goverment_license column
     *
     * Example usage:
     * <code>
     * $query->filterByGovermentLicense('fooValue');   // WHERE goverment_license = 'fooValue'
     * $query->filterByGovermentLicense('%fooValue%'); // WHERE goverment_license LIKE '%fooValue%'
     * </code>
     *
     * @param     string $govermentLicense The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PrincipalArchiveQuery The current query, for fluid interface
     */
    public function filterByGovermentLicense($govermentLicense = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($govermentLicense)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $govermentLicense)) {
                $govermentLicense = str_replace('*', '%', $govermentLicense);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PrincipalArchivePeer::GOVERMENT_LICENSE, $govermentLicense, $comparison);
    }

    /**
     * Filter the query on the join_at column
     *
     * Example usage:
     * <code>
     * $query->filterByJoinAt('2011-03-14'); // WHERE join_at = '2011-03-14'
     * $query->filterByJoinAt('now'); // WHERE join_at = '2011-03-14'
     * $query->filterByJoinAt(array('max' => 'yesterday')); // WHERE join_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $joinAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PrincipalArchiveQuery The current query, for fluid interface
     */
    public function filterByJoinAt($joinAt = null, $comparison = null)
    {
        if (is_array($joinAt)) {
            $useMinMax = false;
            if (isset($joinAt['min'])) {
                $this->addUsingAlias(PrincipalArchivePeer::JOIN_AT, $joinAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($joinAt['max'])) {
                $this->addUsingAlias(PrincipalArchivePeer::JOIN_AT, $joinAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PrincipalArchivePeer::JOIN_AT, $joinAt, $comparison);
    }

    /**
     * Filter the query on the address1 column
     *
     * Example usage:
     * <code>
     * $query->filterByAddress1('fooValue');   // WHERE address1 = 'fooValue'
     * $query->filterByAddress1('%fooValue%'); // WHERE address1 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $address1 The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PrincipalArchiveQuery The current query, for fluid interface
     */
    public function filterByAddress1($address1 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($address1)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $address1)) {
                $address1 = str_replace('*', '%', $address1);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PrincipalArchivePeer::ADDRESS1, $address1, $comparison);
    }

    /**
     * Filter the query on the address2 column
     *
     * Example usage:
     * <code>
     * $query->filterByAddress2('fooValue');   // WHERE address2 = 'fooValue'
     * $query->filterByAddress2('%fooValue%'); // WHERE address2 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $address2 The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PrincipalArchiveQuery The current query, for fluid interface
     */
    public function filterByAddress2($address2 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($address2)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $address2)) {
                $address2 = str_replace('*', '%', $address2);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PrincipalArchivePeer::ADDRESS2, $address2, $comparison);
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
     * @return PrincipalArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PrincipalArchivePeer::CITY, $city, $comparison);
    }

    /**
     * Filter the query on the zipcode column
     *
     * Example usage:
     * <code>
     * $query->filterByZipcode('fooValue');   // WHERE zipcode = 'fooValue'
     * $query->filterByZipcode('%fooValue%'); // WHERE zipcode LIKE '%fooValue%'
     * </code>
     *
     * @param     string $zipcode The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PrincipalArchiveQuery The current query, for fluid interface
     */
    public function filterByZipcode($zipcode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($zipcode)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $zipcode)) {
                $zipcode = str_replace('*', '%', $zipcode);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PrincipalArchivePeer::ZIPCODE, $zipcode, $comparison);
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
     * @return PrincipalArchiveQuery The current query, for fluid interface
     */
    public function filterByCountryId($countryId = null, $comparison = null)
    {
        if (is_array($countryId)) {
            $useMinMax = false;
            if (isset($countryId['min'])) {
                $this->addUsingAlias(PrincipalArchivePeer::COUNTRY_ID, $countryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($countryId['max'])) {
                $this->addUsingAlias(PrincipalArchivePeer::COUNTRY_ID, $countryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PrincipalArchivePeer::COUNTRY_ID, $countryId, $comparison);
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
     * @return PrincipalArchiveQuery The current query, for fluid interface
     */
    public function filterByStateId($stateId = null, $comparison = null)
    {
        if (is_array($stateId)) {
            $useMinMax = false;
            if (isset($stateId['min'])) {
                $this->addUsingAlias(PrincipalArchivePeer::STATE_ID, $stateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($stateId['max'])) {
                $this->addUsingAlias(PrincipalArchivePeer::STATE_ID, $stateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PrincipalArchivePeer::STATE_ID, $stateId, $comparison);
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
     * @return PrincipalArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PrincipalArchivePeer::PHONE, $phone, $comparison);
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
     * @return PrincipalArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PrincipalArchivePeer::FAX, $fax, $comparison);
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
     * @return PrincipalArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PrincipalArchivePeer::MOBILE, $mobile, $comparison);
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
     * @return PrincipalArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PrincipalArchivePeer::EMAIL, $email, $comparison);
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
     * @return PrincipalArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PrincipalArchivePeer::WEBSITE, $website, $comparison);
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
     * @return PrincipalArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PrincipalArchivePeer::LOGO, $logo, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * @param     mixed $status The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PrincipalArchiveQuery The current query, for fluid interface
     * @throws PropelException - if the value is not accepted by the enum.
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_scalar($status)) {
            $status = PrincipalArchivePeer::getSqlValueForEnum(PrincipalArchivePeer::STATUS, $status);
        } elseif (is_array($status)) {
            $convertedValues = array();
            foreach ($status as $value) {
                $convertedValues[] = PrincipalArchivePeer::getSqlValueForEnum(PrincipalArchivePeer::STATUS, $value);
            }
            $status = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PrincipalArchivePeer::STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the is_principal column
     *
     * Example usage:
     * <code>
     * $query->filterByIsPrincipal(true); // WHERE is_principal = true
     * $query->filterByIsPrincipal('yes'); // WHERE is_principal = true
     * </code>
     *
     * @param     boolean|string $isPrincipal The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PrincipalArchiveQuery The current query, for fluid interface
     */
    public function filterByIsPrincipal($isPrincipal = null, $comparison = null)
    {
        if (is_string($isPrincipal)) {
            $isPrincipal = in_array(strtolower($isPrincipal), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PrincipalArchivePeer::IS_PRINCIPAL, $isPrincipal, $comparison);
    }

    /**
     * Filter the query on the confirmation column
     *
     * @param     mixed $confirmation The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PrincipalArchiveQuery The current query, for fluid interface
     * @throws PropelException - if the value is not accepted by the enum.
     */
    public function filterByConfirmation($confirmation = null, $comparison = null)
    {
        if (is_scalar($confirmation)) {
            $confirmation = PrincipalArchivePeer::getSqlValueForEnum(PrincipalArchivePeer::CONFIRMATION, $confirmation);
        } elseif (is_array($confirmation)) {
            $convertedValues = array();
            foreach ($confirmation as $value) {
                $convertedValues[] = PrincipalArchivePeer::getSqlValueForEnum(PrincipalArchivePeer::CONFIRMATION, $value);
            }
            $confirmation = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PrincipalArchivePeer::CONFIRMATION, $confirmation, $comparison);
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
     * @return PrincipalArchiveQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(PrincipalArchivePeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(PrincipalArchivePeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PrincipalArchivePeer::SORTABLE_RANK, $sortableRank, $comparison);
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
     * @return PrincipalArchiveQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PrincipalArchivePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PrincipalArchivePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PrincipalArchivePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PrincipalArchiveQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PrincipalArchivePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PrincipalArchivePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PrincipalArchivePeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return PrincipalArchiveQuery The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(PrincipalArchivePeer::ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(PrincipalArchivePeer::ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PrincipalArchivePeer::ARCHIVED_AT, $archivedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   PrincipalArchive $principalArchive Object to remove from the list of results
     *
     * @return PrincipalArchiveQuery The current query, for fluid interface
     */
    public function prune($principalArchive = null)
    {
        if ($principalArchive) {
            $this->addUsingAlias(PrincipalArchivePeer::ID, $principalArchive->getId(), Criteria::NOT_EQUAL);
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
