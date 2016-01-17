<?php

namespace PGS\CoreDomainBundle\Model\SchoolTerm\om;

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
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourse;
use PGS\CoreDomainBundle\Model\SchoolTerm\SchoolTerm;
use PGS\CoreDomainBundle\Model\SchoolTerm\SchoolTermPeer;
use PGS\CoreDomainBundle\Model\SchoolTerm\SchoolTermQuery;
use PGS\CoreDomainBundle\Model\SchoolYear\SchoolYear;
use PGS\CoreDomainBundle\Model\Term\Term;

/**
 * @method SchoolTermQuery orderById($order = Criteria::ASC) Order by the id column
 * @method SchoolTermQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method SchoolTermQuery orderBySchoolId($order = Criteria::ASC) Order by the school_id column
 * @method SchoolTermQuery orderBySchoolYearId($order = Criteria::ASC) Order by the school_year_id column
 * @method SchoolTermQuery orderByTermId($order = Criteria::ASC) Order by the term_id column
 * @method SchoolTermQuery orderByDateStart($order = Criteria::ASC) Order by the date_start column
 * @method SchoolTermQuery orderByDateEnd($order = Criteria::ASC) Order by the date_end column
 * @method SchoolTermQuery orderByActive($order = Criteria::ASC) Order by the active column
 * @method SchoolTermQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 * @method SchoolTermQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method SchoolTermQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method SchoolTermQuery groupById() Group by the id column
 * @method SchoolTermQuery groupByCode() Group by the code column
 * @method SchoolTermQuery groupBySchoolId() Group by the school_id column
 * @method SchoolTermQuery groupBySchoolYearId() Group by the school_year_id column
 * @method SchoolTermQuery groupByTermId() Group by the term_id column
 * @method SchoolTermQuery groupByDateStart() Group by the date_start column
 * @method SchoolTermQuery groupByDateEnd() Group by the date_end column
 * @method SchoolTermQuery groupByActive() Group by the active column
 * @method SchoolTermQuery groupBySortableRank() Group by the sortable_rank column
 * @method SchoolTermQuery groupByCreatedAt() Group by the created_at column
 * @method SchoolTermQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method SchoolTermQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method SchoolTermQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method SchoolTermQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method SchoolTermQuery leftJoinSchool($relationAlias = null) Adds a LEFT JOIN clause to the query using the School relation
 * @method SchoolTermQuery rightJoinSchool($relationAlias = null) Adds a RIGHT JOIN clause to the query using the School relation
 * @method SchoolTermQuery innerJoinSchool($relationAlias = null) Adds a INNER JOIN clause to the query using the School relation
 *
 * @method SchoolTermQuery leftJoinTerm($relationAlias = null) Adds a LEFT JOIN clause to the query using the Term relation
 * @method SchoolTermQuery rightJoinTerm($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Term relation
 * @method SchoolTermQuery innerJoinTerm($relationAlias = null) Adds a INNER JOIN clause to the query using the Term relation
 *
 * @method SchoolTermQuery leftJoinSchoolYear($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolYear relation
 * @method SchoolTermQuery rightJoinSchoolYear($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolYear relation
 * @method SchoolTermQuery innerJoinSchoolYear($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolYear relation
 *
 * @method SchoolTermQuery leftJoinSchoolClassCourse($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolClassCourse relation
 * @method SchoolTermQuery rightJoinSchoolClassCourse($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolClassCourse relation
 * @method SchoolTermQuery innerJoinSchoolClassCourse($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolClassCourse relation
 *
 * @method SchoolTerm findOne(PropelPDO $con = null) Return the first SchoolTerm matching the query
 * @method SchoolTerm findOneOrCreate(PropelPDO $con = null) Return the first SchoolTerm matching the query, or a new SchoolTerm object populated from the query conditions when no match is found
 *
 * @method SchoolTerm findOneByCode(string $code) Return the first SchoolTerm filtered by the code column
 * @method SchoolTerm findOneBySchoolId(int $school_id) Return the first SchoolTerm filtered by the school_id column
 * @method SchoolTerm findOneBySchoolYearId(int $school_year_id) Return the first SchoolTerm filtered by the school_year_id column
 * @method SchoolTerm findOneByTermId(int $term_id) Return the first SchoolTerm filtered by the term_id column
 * @method SchoolTerm findOneByDateStart(string $date_start) Return the first SchoolTerm filtered by the date_start column
 * @method SchoolTerm findOneByDateEnd(string $date_end) Return the first SchoolTerm filtered by the date_end column
 * @method SchoolTerm findOneByActive(boolean $active) Return the first SchoolTerm filtered by the active column
 * @method SchoolTerm findOneBySortableRank(int $sortable_rank) Return the first SchoolTerm filtered by the sortable_rank column
 * @method SchoolTerm findOneByCreatedAt(string $created_at) Return the first SchoolTerm filtered by the created_at column
 * @method SchoolTerm findOneByUpdatedAt(string $updated_at) Return the first SchoolTerm filtered by the updated_at column
 *
 * @method array findById(int $id) Return SchoolTerm objects filtered by the id column
 * @method array findByCode(string $code) Return SchoolTerm objects filtered by the code column
 * @method array findBySchoolId(int $school_id) Return SchoolTerm objects filtered by the school_id column
 * @method array findBySchoolYearId(int $school_year_id) Return SchoolTerm objects filtered by the school_year_id column
 * @method array findByTermId(int $term_id) Return SchoolTerm objects filtered by the term_id column
 * @method array findByDateStart(string $date_start) Return SchoolTerm objects filtered by the date_start column
 * @method array findByDateEnd(string $date_end) Return SchoolTerm objects filtered by the date_end column
 * @method array findByActive(boolean $active) Return SchoolTerm objects filtered by the active column
 * @method array findBySortableRank(int $sortable_rank) Return SchoolTerm objects filtered by the sortable_rank column
 * @method array findByCreatedAt(string $created_at) Return SchoolTerm objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return SchoolTerm objects filtered by the updated_at column
 */
abstract class BaseSchoolTermQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseSchoolTermQuery object.
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
            $modelName = 'PGS\\CoreDomainBundle\\Model\\SchoolTerm\\SchoolTerm';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new SchoolTermQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   SchoolTermQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return SchoolTermQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof SchoolTermQuery) {
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
     * @return   SchoolTerm|SchoolTerm[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SchoolTermPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(SchoolTermPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 SchoolTerm A model object, or null if the key is not found
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
     * @return                 SchoolTerm A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `code`, `school_id`, `school_year_id`, `term_id`, `date_start`, `date_end`, `active`, `sortable_rank`, `created_at`, `updated_at` FROM `school_term` WHERE `id` = :p0';
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
            $cls = SchoolTermPeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            SchoolTermPeer::addInstanceToPool($obj, (string) $key);
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
     * @return SchoolTerm|SchoolTerm[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|SchoolTerm[]|mixed the list of results, formatted by the current formatter
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
     * @return SchoolTermQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SchoolTermPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return SchoolTermQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SchoolTermPeer::ID, $keys, Criteria::IN);
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
     * @return SchoolTermQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SchoolTermPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SchoolTermPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolTermPeer::ID, $id, $comparison);
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
     * @return SchoolTermQuery The current query, for fluid interface
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

        return $this->addUsingAlias(SchoolTermPeer::CODE, $code, $comparison);
    }

    /**
     * Filter the query on the school_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySchoolId(1234); // WHERE school_id = 1234
     * $query->filterBySchoolId(array(12, 34)); // WHERE school_id IN (12, 34)
     * $query->filterBySchoolId(array('min' => 12)); // WHERE school_id >= 12
     * $query->filterBySchoolId(array('max' => 12)); // WHERE school_id <= 12
     * </code>
     *
     * @see       filterBySchool()
     *
     * @param     mixed $schoolId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolTermQuery The current query, for fluid interface
     */
    public function filterBySchoolId($schoolId = null, $comparison = null)
    {
        if (is_array($schoolId)) {
            $useMinMax = false;
            if (isset($schoolId['min'])) {
                $this->addUsingAlias(SchoolTermPeer::SCHOOL_ID, $schoolId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($schoolId['max'])) {
                $this->addUsingAlias(SchoolTermPeer::SCHOOL_ID, $schoolId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolTermPeer::SCHOOL_ID, $schoolId, $comparison);
    }

    /**
     * Filter the query on the school_year_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySchoolYearId(1234); // WHERE school_year_id = 1234
     * $query->filterBySchoolYearId(array(12, 34)); // WHERE school_year_id IN (12, 34)
     * $query->filterBySchoolYearId(array('min' => 12)); // WHERE school_year_id >= 12
     * $query->filterBySchoolYearId(array('max' => 12)); // WHERE school_year_id <= 12
     * </code>
     *
     * @see       filterBySchoolYear()
     *
     * @param     mixed $schoolYearId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolTermQuery The current query, for fluid interface
     */
    public function filterBySchoolYearId($schoolYearId = null, $comparison = null)
    {
        if (is_array($schoolYearId)) {
            $useMinMax = false;
            if (isset($schoolYearId['min'])) {
                $this->addUsingAlias(SchoolTermPeer::SCHOOL_YEAR_ID, $schoolYearId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($schoolYearId['max'])) {
                $this->addUsingAlias(SchoolTermPeer::SCHOOL_YEAR_ID, $schoolYearId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolTermPeer::SCHOOL_YEAR_ID, $schoolYearId, $comparison);
    }

    /**
     * Filter the query on the term_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTermId(1234); // WHERE term_id = 1234
     * $query->filterByTermId(array(12, 34)); // WHERE term_id IN (12, 34)
     * $query->filterByTermId(array('min' => 12)); // WHERE term_id >= 12
     * $query->filterByTermId(array('max' => 12)); // WHERE term_id <= 12
     * </code>
     *
     * @see       filterByTerm()
     *
     * @param     mixed $termId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolTermQuery The current query, for fluid interface
     */
    public function filterByTermId($termId = null, $comparison = null)
    {
        if (is_array($termId)) {
            $useMinMax = false;
            if (isset($termId['min'])) {
                $this->addUsingAlias(SchoolTermPeer::TERM_ID, $termId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($termId['max'])) {
                $this->addUsingAlias(SchoolTermPeer::TERM_ID, $termId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolTermPeer::TERM_ID, $termId, $comparison);
    }

    /**
     * Filter the query on the date_start column
     *
     * Example usage:
     * <code>
     * $query->filterByDateStart('2011-03-14'); // WHERE date_start = '2011-03-14'
     * $query->filterByDateStart('now'); // WHERE date_start = '2011-03-14'
     * $query->filterByDateStart(array('max' => 'yesterday')); // WHERE date_start < '2011-03-13'
     * </code>
     *
     * @param     mixed $dateStart The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolTermQuery The current query, for fluid interface
     */
    public function filterByDateStart($dateStart = null, $comparison = null)
    {
        if (is_array($dateStart)) {
            $useMinMax = false;
            if (isset($dateStart['min'])) {
                $this->addUsingAlias(SchoolTermPeer::DATE_START, $dateStart['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateStart['max'])) {
                $this->addUsingAlias(SchoolTermPeer::DATE_START, $dateStart['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolTermPeer::DATE_START, $dateStart, $comparison);
    }

    /**
     * Filter the query on the date_end column
     *
     * Example usage:
     * <code>
     * $query->filterByDateEnd('2011-03-14'); // WHERE date_end = '2011-03-14'
     * $query->filterByDateEnd('now'); // WHERE date_end = '2011-03-14'
     * $query->filterByDateEnd(array('max' => 'yesterday')); // WHERE date_end < '2011-03-13'
     * </code>
     *
     * @param     mixed $dateEnd The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolTermQuery The current query, for fluid interface
     */
    public function filterByDateEnd($dateEnd = null, $comparison = null)
    {
        if (is_array($dateEnd)) {
            $useMinMax = false;
            if (isset($dateEnd['min'])) {
                $this->addUsingAlias(SchoolTermPeer::DATE_END, $dateEnd['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateEnd['max'])) {
                $this->addUsingAlias(SchoolTermPeer::DATE_END, $dateEnd['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolTermPeer::DATE_END, $dateEnd, $comparison);
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
     * @return SchoolTermQuery The current query, for fluid interface
     */
    public function filterByActive($active = null, $comparison = null)
    {
        if (is_string($active)) {
            $active = in_array(strtolower($active), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SchoolTermPeer::ACTIVE, $active, $comparison);
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
     * @return SchoolTermQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(SchoolTermPeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(SchoolTermPeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolTermPeer::SORTABLE_RANK, $sortableRank, $comparison);
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
     * @return SchoolTermQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(SchoolTermPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(SchoolTermPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolTermPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return SchoolTermQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(SchoolTermPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(SchoolTermPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolTermPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related School object
     *
     * @param   School|PropelObjectCollection $school The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolTermQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchool($school, $comparison = null)
    {
        if ($school instanceof School) {
            return $this
                ->addUsingAlias(SchoolTermPeer::SCHOOL_ID, $school->getId(), $comparison);
        } elseif ($school instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SchoolTermPeer::SCHOOL_ID, $school->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySchool() only accepts arguments of type School or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the School relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SchoolTermQuery The current query, for fluid interface
     */
    public function joinSchool($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('School');

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
            $this->addJoinObject($join, 'School');
        }

        return $this;
    }

    /**
     * Use the School relation School object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\School\SchoolQuery A secondary query class using the current class as primary query
     */
    public function useSchoolQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSchool($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'School', '\PGS\CoreDomainBundle\Model\School\SchoolQuery');
    }

    /**
     * Filter the query by a related Term object
     *
     * @param   Term|PropelObjectCollection $term The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolTermQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTerm($term, $comparison = null)
    {
        if ($term instanceof Term) {
            return $this
                ->addUsingAlias(SchoolTermPeer::TERM_ID, $term->getId(), $comparison);
        } elseif ($term instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SchoolTermPeer::TERM_ID, $term->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByTerm() only accepts arguments of type Term or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Term relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SchoolTermQuery The current query, for fluid interface
     */
    public function joinTerm($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Term');

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
            $this->addJoinObject($join, 'Term');
        }

        return $this;
    }

    /**
     * Use the Term relation Term object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\Term\TermQuery A secondary query class using the current class as primary query
     */
    public function useTermQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTerm($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Term', '\PGS\CoreDomainBundle\Model\Term\TermQuery');
    }

    /**
     * Filter the query by a related SchoolYear object
     *
     * @param   SchoolYear|PropelObjectCollection $schoolYear The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolTermQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolYear($schoolYear, $comparison = null)
    {
        if ($schoolYear instanceof SchoolYear) {
            return $this
                ->addUsingAlias(SchoolTermPeer::SCHOOL_YEAR_ID, $schoolYear->getId(), $comparison);
        } elseif ($schoolYear instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SchoolTermPeer::SCHOOL_YEAR_ID, $schoolYear->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySchoolYear() only accepts arguments of type SchoolYear or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SchoolYear relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SchoolTermQuery The current query, for fluid interface
     */
    public function joinSchoolYear($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SchoolYear');

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
            $this->addJoinObject($join, 'SchoolYear');
        }

        return $this;
    }

    /**
     * Use the SchoolYear relation SchoolYear object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\SchoolYear\SchoolYearQuery A secondary query class using the current class as primary query
     */
    public function useSchoolYearQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSchoolYear($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SchoolYear', '\PGS\CoreDomainBundle\Model\SchoolYear\SchoolYearQuery');
    }

    /**
     * Filter the query by a related SchoolClassCourse object
     *
     * @param   SchoolClassCourse|PropelObjectCollection $schoolClassCourse  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolTermQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolClassCourse($schoolClassCourse, $comparison = null)
    {
        if ($schoolClassCourse instanceof SchoolClassCourse) {
            return $this
                ->addUsingAlias(SchoolTermPeer::ID, $schoolClassCourse->getSchoolTermId(), $comparison);
        } elseif ($schoolClassCourse instanceof PropelObjectCollection) {
            return $this
                ->useSchoolClassCourseQuery()
                ->filterByPrimaryKeys($schoolClassCourse->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySchoolClassCourse() only accepts arguments of type SchoolClassCourse or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SchoolClassCourse relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SchoolTermQuery The current query, for fluid interface
     */
    public function joinSchoolClassCourse($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SchoolClassCourse');

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
            $this->addJoinObject($join, 'SchoolClassCourse');
        }

        return $this;
    }

    /**
     * Use the SchoolClassCourse relation SchoolClassCourse object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourseQuery A secondary query class using the current class as primary query
     */
    public function useSchoolClassCourseQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSchoolClassCourse($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SchoolClassCourse', '\PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourseQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   SchoolTerm $schoolTerm Object to remove from the list of results
     *
     * @return SchoolTermQuery The current query, for fluid interface
     */
    public function prune($schoolTerm = null)
    {
        if ($schoolTerm) {
            $this->addUsingAlias(SchoolTermPeer::ID, $schoolTerm->getId(), Criteria::NOT_EQUAL);
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

    // sortable behavior

    /**
     * Returns the objects in a certain list, from the list scope
     *
     * @param int $scope Scope to determine which objects node to return
     *
     * @return SchoolTermQuery The current query, for fluid interface
     */
    public function inList($scope)
    {

        SchoolTermPeer::sortableApplyScopeCriteria($this, $scope, 'addUsingAlias');

        return $this;
    }

    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     * @param int $scope Scope to determine which objects node to return

     *
     * @return    SchoolTermQuery The current query, for fluid interface
     */
    public function filterByRank($rank, $scope)
    {


        return $this
            ->inList($scope)
            ->addUsingAlias(SchoolTermPeer::RANK_COL, $rank, Criteria::EQUAL);
    }

    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    SchoolTermQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(SchoolTermPeer::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(SchoolTermPeer::RANK_COL));
                break;
            default:
                throw new PropelException('SchoolTermQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param int $scope Scope to determine which objects node to return
     * @param     PropelPDO $con optional connection
     *
     * @return    SchoolTerm
     */
    public function findOneByRank($rank, $scope, PropelPDO $con = null)
    {

        return $this
            ->filterByRank($rank, $scope)
            ->findOne($con);
    }

    /**
     * Returns a list of objects
     *
     * @param int $scope Scope to determine which objects node to return

     * @param      PropelPDO $con	Connection to use.
     *
     * @return     mixed the list of results, formatted by the current formatter
     */
    public function findList($scope, $con = null)
    {


        return $this
            ->inList($scope)
            ->orderByRank()
            ->find($con);
    }

    /**
     * Get the highest rank
     *
     * @param int $scope Scope to determine which objects node to return

     * @param     PropelPDO optional connection
     *
     * @return    integer highest position
     */
    public function getMaxRank($scope, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolTermPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . SchoolTermPeer::RANK_COL . ')');

        SchoolTermPeer::sortableApplyScopeCriteria($this, $scope);
        $stmt = $this->doSelect($con);

        return $stmt->fetchColumn();
    }

    /**
     * Get the highest rank by a scope with a array format.
     *
     * @param     int $scope		The scope value as scalar type or array($value1, ...).

     * @param     PropelPDO optional connection
     *
     * @return    integer highest position
     */
    public function getMaxRankArray($scope, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolTermPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . SchoolTermPeer::RANK_COL . ')');
        SchoolTermPeer::sortableApplyScopeCriteria($this, $scope);
        $stmt = $this->doSelect($con);

        return $stmt->fetchColumn();
    }

    /**
     * Reorder a set of sortable objects based on a list of id/position
     * Beware that there is no check made on the positions passed
     * So incoherent positions will result in an incoherent list
     *
     * @param     array     $order id => rank pairs
     * @param     PropelPDO $con   optional connection
     *
     * @return    boolean true if the reordering took place, false if a database problem prevented it
     */
    public function reorder(array $order, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolTermPeer::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $ids = array_keys($order);
            $objects = $this->findPks($ids, $con);
            foreach ($objects as $object) {
                $pk = $object->getPrimaryKey();
                if ($object->getSortableRank() != $order[$pk]) {
                    $object->setSortableRank($order[$pk]);
                    $object->save($con);
                }
            }
            $con->commit();

            return true;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     SchoolTermQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(SchoolTermPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     SchoolTermQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(SchoolTermPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     SchoolTermQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(SchoolTermPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     SchoolTermQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(SchoolTermPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     SchoolTermQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(SchoolTermPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     SchoolTermQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(SchoolTermPeer::CREATED_AT);
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
