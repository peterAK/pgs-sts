<?php

namespace PGS\CoreDomainBundle\Model\Organization\om;

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
use PGS\CoreDomainBundle\Model\Organization\OrganizationArchive;
use PGS\CoreDomainBundle\Model\Organization\OrganizationArchivePeer;
use PGS\CoreDomainBundle\Model\Organization\OrganizationArchiveQuery;

/**
 * @method OrganizationArchiveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method OrganizationArchiveQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method OrganizationArchiveQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method OrganizationArchiveQuery orderByUrl($order = Criteria::ASC) Order by the url column
 * @method OrganizationArchiveQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method OrganizationArchiveQuery orderByExcerpt($order = Criteria::ASC) Order by the excerpt column
 * @method OrganizationArchiveQuery orderByGovermentLicense($order = Criteria::ASC) Order by the goverment_license column
 * @method OrganizationArchiveQuery orderByEstablishAt($order = Criteria::ASC) Order by the establish_at column
 * @method OrganizationArchiveQuery orderByAddress1($order = Criteria::ASC) Order by the address1 column
 * @method OrganizationArchiveQuery orderByAddress2($order = Criteria::ASC) Order by the address2 column
 * @method OrganizationArchiveQuery orderByCity($order = Criteria::ASC) Order by the city column
 * @method OrganizationArchiveQuery orderByStateId($order = Criteria::ASC) Order by the state_id column
 * @method OrganizationArchiveQuery orderByZipcode($order = Criteria::ASC) Order by the zipcode column
 * @method OrganizationArchiveQuery orderByCountryId($order = Criteria::ASC) Order by the country_id column
 * @method OrganizationArchiveQuery orderByPhone($order = Criteria::ASC) Order by the phone column
 * @method OrganizationArchiveQuery orderByFax($order = Criteria::ASC) Order by the fax column
 * @method OrganizationArchiveQuery orderByMobile($order = Criteria::ASC) Order by the mobile column
 * @method OrganizationArchiveQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method OrganizationArchiveQuery orderByWebsite($order = Criteria::ASC) Order by the website column
 * @method OrganizationArchiveQuery orderByLogo($order = Criteria::ASC) Order by the logo column
 * @method OrganizationArchiveQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method OrganizationArchiveQuery orderByConfirmation($order = Criteria::ASC) Order by the confirmation column
 * @method OrganizationArchiveQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 * @method OrganizationArchiveQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method OrganizationArchiveQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method OrganizationArchiveQuery orderByArchivedAt($order = Criteria::ASC) Order by the archived_at column
 *
 * @method OrganizationArchiveQuery groupById() Group by the id column
 * @method OrganizationArchiveQuery groupByUserId() Group by the user_id column
 * @method OrganizationArchiveQuery groupByName() Group by the name column
 * @method OrganizationArchiveQuery groupByUrl() Group by the url column
 * @method OrganizationArchiveQuery groupByDescription() Group by the description column
 * @method OrganizationArchiveQuery groupByExcerpt() Group by the excerpt column
 * @method OrganizationArchiveQuery groupByGovermentLicense() Group by the goverment_license column
 * @method OrganizationArchiveQuery groupByEstablishAt() Group by the establish_at column
 * @method OrganizationArchiveQuery groupByAddress1() Group by the address1 column
 * @method OrganizationArchiveQuery groupByAddress2() Group by the address2 column
 * @method OrganizationArchiveQuery groupByCity() Group by the city column
 * @method OrganizationArchiveQuery groupByStateId() Group by the state_id column
 * @method OrganizationArchiveQuery groupByZipcode() Group by the zipcode column
 * @method OrganizationArchiveQuery groupByCountryId() Group by the country_id column
 * @method OrganizationArchiveQuery groupByPhone() Group by the phone column
 * @method OrganizationArchiveQuery groupByFax() Group by the fax column
 * @method OrganizationArchiveQuery groupByMobile() Group by the mobile column
 * @method OrganizationArchiveQuery groupByEmail() Group by the email column
 * @method OrganizationArchiveQuery groupByWebsite() Group by the website column
 * @method OrganizationArchiveQuery groupByLogo() Group by the logo column
 * @method OrganizationArchiveQuery groupByStatus() Group by the status column
 * @method OrganizationArchiveQuery groupByConfirmation() Group by the confirmation column
 * @method OrganizationArchiveQuery groupBySortableRank() Group by the sortable_rank column
 * @method OrganizationArchiveQuery groupByCreatedAt() Group by the created_at column
 * @method OrganizationArchiveQuery groupByUpdatedAt() Group by the updated_at column
 * @method OrganizationArchiveQuery groupByArchivedAt() Group by the archived_at column
 *
 * @method OrganizationArchiveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method OrganizationArchiveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method OrganizationArchiveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method OrganizationArchive findOne(PropelPDO $con = null) Return the first OrganizationArchive matching the query
 * @method OrganizationArchive findOneOrCreate(PropelPDO $con = null) Return the first OrganizationArchive matching the query, or a new OrganizationArchive object populated from the query conditions when no match is found
 *
 * @method OrganizationArchive findOneByUserId(int $user_id) Return the first OrganizationArchive filtered by the user_id column
 * @method OrganizationArchive findOneByName(string $name) Return the first OrganizationArchive filtered by the name column
 * @method OrganizationArchive findOneByUrl(string $url) Return the first OrganizationArchive filtered by the url column
 * @method OrganizationArchive findOneByDescription(string $description) Return the first OrganizationArchive filtered by the description column
 * @method OrganizationArchive findOneByExcerpt(string $excerpt) Return the first OrganizationArchive filtered by the excerpt column
 * @method OrganizationArchive findOneByGovermentLicense(string $goverment_license) Return the first OrganizationArchive filtered by the goverment_license column
 * @method OrganizationArchive findOneByEstablishAt(string $establish_at) Return the first OrganizationArchive filtered by the establish_at column
 * @method OrganizationArchive findOneByAddress1(string $address1) Return the first OrganizationArchive filtered by the address1 column
 * @method OrganizationArchive findOneByAddress2(string $address2) Return the first OrganizationArchive filtered by the address2 column
 * @method OrganizationArchive findOneByCity(string $city) Return the first OrganizationArchive filtered by the city column
 * @method OrganizationArchive findOneByStateId(int $state_id) Return the first OrganizationArchive filtered by the state_id column
 * @method OrganizationArchive findOneByZipcode(string $zipcode) Return the first OrganizationArchive filtered by the zipcode column
 * @method OrganizationArchive findOneByCountryId(int $country_id) Return the first OrganizationArchive filtered by the country_id column
 * @method OrganizationArchive findOneByPhone(string $phone) Return the first OrganizationArchive filtered by the phone column
 * @method OrganizationArchive findOneByFax(string $fax) Return the first OrganizationArchive filtered by the fax column
 * @method OrganizationArchive findOneByMobile(string $mobile) Return the first OrganizationArchive filtered by the mobile column
 * @method OrganizationArchive findOneByEmail(string $email) Return the first OrganizationArchive filtered by the email column
 * @method OrganizationArchive findOneByWebsite(string $website) Return the first OrganizationArchive filtered by the website column
 * @method OrganizationArchive findOneByLogo(string $logo) Return the first OrganizationArchive filtered by the logo column
 * @method OrganizationArchive findOneByStatus(int $status) Return the first OrganizationArchive filtered by the status column
 * @method OrganizationArchive findOneByConfirmation(int $confirmation) Return the first OrganizationArchive filtered by the confirmation column
 * @method OrganizationArchive findOneBySortableRank(int $sortable_rank) Return the first OrganizationArchive filtered by the sortable_rank column
 * @method OrganizationArchive findOneByCreatedAt(string $created_at) Return the first OrganizationArchive filtered by the created_at column
 * @method OrganizationArchive findOneByUpdatedAt(string $updated_at) Return the first OrganizationArchive filtered by the updated_at column
 * @method OrganizationArchive findOneByArchivedAt(string $archived_at) Return the first OrganizationArchive filtered by the archived_at column
 *
 * @method array findById(int $id) Return OrganizationArchive objects filtered by the id column
 * @method array findByUserId(int $user_id) Return OrganizationArchive objects filtered by the user_id column
 * @method array findByName(string $name) Return OrganizationArchive objects filtered by the name column
 * @method array findByUrl(string $url) Return OrganizationArchive objects filtered by the url column
 * @method array findByDescription(string $description) Return OrganizationArchive objects filtered by the description column
 * @method array findByExcerpt(string $excerpt) Return OrganizationArchive objects filtered by the excerpt column
 * @method array findByGovermentLicense(string $goverment_license) Return OrganizationArchive objects filtered by the goverment_license column
 * @method array findByEstablishAt(string $establish_at) Return OrganizationArchive objects filtered by the establish_at column
 * @method array findByAddress1(string $address1) Return OrganizationArchive objects filtered by the address1 column
 * @method array findByAddress2(string $address2) Return OrganizationArchive objects filtered by the address2 column
 * @method array findByCity(string $city) Return OrganizationArchive objects filtered by the city column
 * @method array findByStateId(int $state_id) Return OrganizationArchive objects filtered by the state_id column
 * @method array findByZipcode(string $zipcode) Return OrganizationArchive objects filtered by the zipcode column
 * @method array findByCountryId(int $country_id) Return OrganizationArchive objects filtered by the country_id column
 * @method array findByPhone(string $phone) Return OrganizationArchive objects filtered by the phone column
 * @method array findByFax(string $fax) Return OrganizationArchive objects filtered by the fax column
 * @method array findByMobile(string $mobile) Return OrganizationArchive objects filtered by the mobile column
 * @method array findByEmail(string $email) Return OrganizationArchive objects filtered by the email column
 * @method array findByWebsite(string $website) Return OrganizationArchive objects filtered by the website column
 * @method array findByLogo(string $logo) Return OrganizationArchive objects filtered by the logo column
 * @method array findByStatus(int $status) Return OrganizationArchive objects filtered by the status column
 * @method array findByConfirmation(int $confirmation) Return OrganizationArchive objects filtered by the confirmation column
 * @method array findBySortableRank(int $sortable_rank) Return OrganizationArchive objects filtered by the sortable_rank column
 * @method array findByCreatedAt(string $created_at) Return OrganizationArchive objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return OrganizationArchive objects filtered by the updated_at column
 * @method array findByArchivedAt(string $archived_at) Return OrganizationArchive objects filtered by the archived_at column
 */
abstract class BaseOrganizationArchiveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseOrganizationArchiveQuery object.
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
            $modelName = 'PGS\\CoreDomainBundle\\Model\\Organization\\OrganizationArchive';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new OrganizationArchiveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   OrganizationArchiveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return OrganizationArchiveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof OrganizationArchiveQuery) {
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
     * @return   OrganizationArchive|OrganizationArchive[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = OrganizationArchivePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(OrganizationArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 OrganizationArchive A model object, or null if the key is not found
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
     * @return                 OrganizationArchive A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `user_id`, `name`, `url`, `description`, `excerpt`, `goverment_license`, `establish_at`, `address1`, `address2`, `city`, `state_id`, `zipcode`, `country_id`, `phone`, `fax`, `mobile`, `email`, `website`, `logo`, `status`, `confirmation`, `sortable_rank`, `created_at`, `updated_at`, `archived_at` FROM `organization_archive` WHERE `id` = :p0';
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
            $cls = OrganizationArchivePeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            OrganizationArchivePeer::addInstanceToPool($obj, (string) $key);
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
     * @return OrganizationArchive|OrganizationArchive[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|OrganizationArchive[]|mixed the list of results, formatted by the current formatter
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(OrganizationArchivePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return OrganizationArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(OrganizationArchivePeer::ID, $keys, Criteria::IN);
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(OrganizationArchivePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(OrganizationArchivePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrganizationArchivePeer::ID, $id, $comparison);
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(OrganizationArchivePeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(OrganizationArchivePeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrganizationArchivePeer::USER_ID, $userId, $comparison);
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(OrganizationArchivePeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE url = 'fooValue'
     * $query->filterByUrl('%fooValue%'); // WHERE url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $url The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return OrganizationArchiveQuery The current query, for fluid interface
     */
    public function filterByUrl($url = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $url)) {
                $url = str_replace('*', '%', $url);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(OrganizationArchivePeer::URL, $url, $comparison);
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(OrganizationArchivePeer::DESCRIPTION, $description, $comparison);
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(OrganizationArchivePeer::EXCERPT, $excerpt, $comparison);
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(OrganizationArchivePeer::GOVERMENT_LICENSE, $govermentLicense, $comparison);
    }

    /**
     * Filter the query on the establish_at column
     *
     * Example usage:
     * <code>
     * $query->filterByEstablishAt('2011-03-14'); // WHERE establish_at = '2011-03-14'
     * $query->filterByEstablishAt('now'); // WHERE establish_at = '2011-03-14'
     * $query->filterByEstablishAt(array('max' => 'yesterday')); // WHERE establish_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $establishAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return OrganizationArchiveQuery The current query, for fluid interface
     */
    public function filterByEstablishAt($establishAt = null, $comparison = null)
    {
        if (is_array($establishAt)) {
            $useMinMax = false;
            if (isset($establishAt['min'])) {
                $this->addUsingAlias(OrganizationArchivePeer::ESTABLISH_AT, $establishAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($establishAt['max'])) {
                $this->addUsingAlias(OrganizationArchivePeer::ESTABLISH_AT, $establishAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrganizationArchivePeer::ESTABLISH_AT, $establishAt, $comparison);
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(OrganizationArchivePeer::ADDRESS1, $address1, $comparison);
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(OrganizationArchivePeer::ADDRESS2, $address2, $comparison);
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(OrganizationArchivePeer::CITY, $city, $comparison);
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
     */
    public function filterByStateId($stateId = null, $comparison = null)
    {
        if (is_array($stateId)) {
            $useMinMax = false;
            if (isset($stateId['min'])) {
                $this->addUsingAlias(OrganizationArchivePeer::STATE_ID, $stateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($stateId['max'])) {
                $this->addUsingAlias(OrganizationArchivePeer::STATE_ID, $stateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrganizationArchivePeer::STATE_ID, $stateId, $comparison);
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(OrganizationArchivePeer::ZIPCODE, $zipcode, $comparison);
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
     */
    public function filterByCountryId($countryId = null, $comparison = null)
    {
        if (is_array($countryId)) {
            $useMinMax = false;
            if (isset($countryId['min'])) {
                $this->addUsingAlias(OrganizationArchivePeer::COUNTRY_ID, $countryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($countryId['max'])) {
                $this->addUsingAlias(OrganizationArchivePeer::COUNTRY_ID, $countryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrganizationArchivePeer::COUNTRY_ID, $countryId, $comparison);
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(OrganizationArchivePeer::PHONE, $phone, $comparison);
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(OrganizationArchivePeer::FAX, $fax, $comparison);
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(OrganizationArchivePeer::MOBILE, $mobile, $comparison);
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(OrganizationArchivePeer::EMAIL, $email, $comparison);
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(OrganizationArchivePeer::WEBSITE, $website, $comparison);
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(OrganizationArchivePeer::LOGO, $logo, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * @param     mixed $status The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return OrganizationArchiveQuery The current query, for fluid interface
     * @throws PropelException - if the value is not accepted by the enum.
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_scalar($status)) {
            $status = OrganizationArchivePeer::getSqlValueForEnum(OrganizationArchivePeer::STATUS, $status);
        } elseif (is_array($status)) {
            $convertedValues = array();
            foreach ($status as $value) {
                $convertedValues[] = OrganizationArchivePeer::getSqlValueForEnum(OrganizationArchivePeer::STATUS, $value);
            }
            $status = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrganizationArchivePeer::STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the confirmation column
     *
     * @param     mixed $confirmation The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return OrganizationArchiveQuery The current query, for fluid interface
     * @throws PropelException - if the value is not accepted by the enum.
     */
    public function filterByConfirmation($confirmation = null, $comparison = null)
    {
        if (is_scalar($confirmation)) {
            $confirmation = OrganizationArchivePeer::getSqlValueForEnum(OrganizationArchivePeer::CONFIRMATION, $confirmation);
        } elseif (is_array($confirmation)) {
            $convertedValues = array();
            foreach ($confirmation as $value) {
                $convertedValues[] = OrganizationArchivePeer::getSqlValueForEnum(OrganizationArchivePeer::CONFIRMATION, $value);
            }
            $confirmation = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrganizationArchivePeer::CONFIRMATION, $confirmation, $comparison);
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(OrganizationArchivePeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(OrganizationArchivePeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrganizationArchivePeer::SORTABLE_RANK, $sortableRank, $comparison);
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(OrganizationArchivePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(OrganizationArchivePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrganizationArchivePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(OrganizationArchivePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(OrganizationArchivePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrganizationArchivePeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return OrganizationArchiveQuery The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(OrganizationArchivePeer::ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(OrganizationArchivePeer::ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrganizationArchivePeer::ARCHIVED_AT, $archivedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   OrganizationArchive $organizationArchive Object to remove from the list of results
     *
     * @return OrganizationArchiveQuery The current query, for fluid interface
     */
    public function prune($organizationArchive = null)
    {
        if ($organizationArchive) {
            $this->addUsingAlias(OrganizationArchivePeer::ID, $organizationArchive->getId(), Criteria::NOT_EQUAL);
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
     * @return     OrganizationArchiveQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(OrganizationArchivePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     OrganizationArchiveQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(OrganizationArchivePeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     OrganizationArchiveQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(OrganizationArchivePeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     OrganizationArchiveQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(OrganizationArchivePeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     OrganizationArchiveQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(OrganizationArchivePeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     OrganizationArchiveQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(OrganizationArchivePeer::CREATED_AT);
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
