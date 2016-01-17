<?php

namespace PGS\CoreDomainBundle\Model\StudentReport\om;

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
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClass;
use PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudent;
use PGS\CoreDomainBundle\Model\Score\Score;
use PGS\CoreDomainBundle\Model\StudentReport\StudentReport;
use PGS\CoreDomainBundle\Model\StudentReport\StudentReportPeer;
use PGS\CoreDomainBundle\Model\StudentReport\StudentReportQuery;

/**
 * @method StudentReportQuery orderById($order = Criteria::ASC) Order by the id column
 * @method StudentReportQuery orderBySchoolClassId($order = Criteria::ASC) Order by the school_class_id column
 * @method StudentReportQuery orderBySchoolClassStudentId($order = Criteria::ASC) Order by the school_class_student_id column
 * @method StudentReportQuery orderByScoreId($order = Criteria::ASC) Order by the score_id column
 * @method StudentReportQuery orderByTerm1($order = Criteria::ASC) Order by the term1 column
 * @method StudentReportQuery orderByTerm2($order = Criteria::ASC) Order by the term2 column
 * @method StudentReportQuery orderByTerm3($order = Criteria::ASC) Order by the term3 column
 * @method StudentReportQuery orderByTerm4($order = Criteria::ASC) Order by the term4 column
 * @method StudentReportQuery orderByMidReport($order = Criteria::ASC) Order by the mid_report column
 * @method StudentReportQuery orderByFinalReport($order = Criteria::ASC) Order by the final_report column
 * @method StudentReportQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method StudentReportQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method StudentReportQuery groupById() Group by the id column
 * @method StudentReportQuery groupBySchoolClassId() Group by the school_class_id column
 * @method StudentReportQuery groupBySchoolClassStudentId() Group by the school_class_student_id column
 * @method StudentReportQuery groupByScoreId() Group by the score_id column
 * @method StudentReportQuery groupByTerm1() Group by the term1 column
 * @method StudentReportQuery groupByTerm2() Group by the term2 column
 * @method StudentReportQuery groupByTerm3() Group by the term3 column
 * @method StudentReportQuery groupByTerm4() Group by the term4 column
 * @method StudentReportQuery groupByMidReport() Group by the mid_report column
 * @method StudentReportQuery groupByFinalReport() Group by the final_report column
 * @method StudentReportQuery groupByCreatedAt() Group by the created_at column
 * @method StudentReportQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method StudentReportQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method StudentReportQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method StudentReportQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method StudentReportQuery leftJoinSchoolClass($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolClass relation
 * @method StudentReportQuery rightJoinSchoolClass($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolClass relation
 * @method StudentReportQuery innerJoinSchoolClass($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolClass relation
 *
 * @method StudentReportQuery leftJoinSchoolClassStudent($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolClassStudent relation
 * @method StudentReportQuery rightJoinSchoolClassStudent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolClassStudent relation
 * @method StudentReportQuery innerJoinSchoolClassStudent($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolClassStudent relation
 *
 * @method StudentReportQuery leftJoinScore($relationAlias = null) Adds a LEFT JOIN clause to the query using the Score relation
 * @method StudentReportQuery rightJoinScore($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Score relation
 * @method StudentReportQuery innerJoinScore($relationAlias = null) Adds a INNER JOIN clause to the query using the Score relation
 *
 * @method StudentReport findOne(PropelPDO $con = null) Return the first StudentReport matching the query
 * @method StudentReport findOneOrCreate(PropelPDO $con = null) Return the first StudentReport matching the query, or a new StudentReport object populated from the query conditions when no match is found
 *
 * @method StudentReport findOneBySchoolClassId(int $school_class_id) Return the first StudentReport filtered by the school_class_id column
 * @method StudentReport findOneBySchoolClassStudentId(int $school_class_student_id) Return the first StudentReport filtered by the school_class_student_id column
 * @method StudentReport findOneByScoreId(int $score_id) Return the first StudentReport filtered by the score_id column
 * @method StudentReport findOneByTerm1(int $term1) Return the first StudentReport filtered by the term1 column
 * @method StudentReport findOneByTerm2(int $term2) Return the first StudentReport filtered by the term2 column
 * @method StudentReport findOneByTerm3(int $term3) Return the first StudentReport filtered by the term3 column
 * @method StudentReport findOneByTerm4(int $term4) Return the first StudentReport filtered by the term4 column
 * @method StudentReport findOneByMidReport(int $mid_report) Return the first StudentReport filtered by the mid_report column
 * @method StudentReport findOneByFinalReport(int $final_report) Return the first StudentReport filtered by the final_report column
 * @method StudentReport findOneByCreatedAt(string $created_at) Return the first StudentReport filtered by the created_at column
 * @method StudentReport findOneByUpdatedAt(string $updated_at) Return the first StudentReport filtered by the updated_at column
 *
 * @method array findById(int $id) Return StudentReport objects filtered by the id column
 * @method array findBySchoolClassId(int $school_class_id) Return StudentReport objects filtered by the school_class_id column
 * @method array findBySchoolClassStudentId(int $school_class_student_id) Return StudentReport objects filtered by the school_class_student_id column
 * @method array findByScoreId(int $score_id) Return StudentReport objects filtered by the score_id column
 * @method array findByTerm1(int $term1) Return StudentReport objects filtered by the term1 column
 * @method array findByTerm2(int $term2) Return StudentReport objects filtered by the term2 column
 * @method array findByTerm3(int $term3) Return StudentReport objects filtered by the term3 column
 * @method array findByTerm4(int $term4) Return StudentReport objects filtered by the term4 column
 * @method array findByMidReport(int $mid_report) Return StudentReport objects filtered by the mid_report column
 * @method array findByFinalReport(int $final_report) Return StudentReport objects filtered by the final_report column
 * @method array findByCreatedAt(string $created_at) Return StudentReport objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return StudentReport objects filtered by the updated_at column
 */
abstract class BaseStudentReportQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseStudentReportQuery object.
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
            $modelName = 'PGS\\CoreDomainBundle\\Model\\StudentReport\\StudentReport';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new StudentReportQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   StudentReportQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return StudentReportQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof StudentReportQuery) {
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
     * @return   StudentReport|StudentReport[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = StudentReportPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(StudentReportPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 StudentReport A model object, or null if the key is not found
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
     * @return                 StudentReport A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `school_class_id`, `school_class_student_id`, `score_id`, `term1`, `term2`, `term3`, `term4`, `mid_report`, `final_report`, `created_at`, `updated_at` FROM `student_report` WHERE `id` = :p0';
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
            $cls = StudentReportPeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            StudentReportPeer::addInstanceToPool($obj, (string) $key);
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
     * @return StudentReport|StudentReport[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|StudentReport[]|mixed the list of results, formatted by the current formatter
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
     * @return StudentReportQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(StudentReportPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return StudentReportQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(StudentReportPeer::ID, $keys, Criteria::IN);
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
     * @return StudentReportQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(StudentReportPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(StudentReportPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentReportPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the school_class_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySchoolClassId(1234); // WHERE school_class_id = 1234
     * $query->filterBySchoolClassId(array(12, 34)); // WHERE school_class_id IN (12, 34)
     * $query->filterBySchoolClassId(array('min' => 12)); // WHERE school_class_id >= 12
     * $query->filterBySchoolClassId(array('max' => 12)); // WHERE school_class_id <= 12
     * </code>
     *
     * @see       filterBySchoolClass()
     *
     * @param     mixed $schoolClassId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentReportQuery The current query, for fluid interface
     */
    public function filterBySchoolClassId($schoolClassId = null, $comparison = null)
    {
        if (is_array($schoolClassId)) {
            $useMinMax = false;
            if (isset($schoolClassId['min'])) {
                $this->addUsingAlias(StudentReportPeer::SCHOOL_CLASS_ID, $schoolClassId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($schoolClassId['max'])) {
                $this->addUsingAlias(StudentReportPeer::SCHOOL_CLASS_ID, $schoolClassId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentReportPeer::SCHOOL_CLASS_ID, $schoolClassId, $comparison);
    }

    /**
     * Filter the query on the school_class_student_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySchoolClassStudentId(1234); // WHERE school_class_student_id = 1234
     * $query->filterBySchoolClassStudentId(array(12, 34)); // WHERE school_class_student_id IN (12, 34)
     * $query->filterBySchoolClassStudentId(array('min' => 12)); // WHERE school_class_student_id >= 12
     * $query->filterBySchoolClassStudentId(array('max' => 12)); // WHERE school_class_student_id <= 12
     * </code>
     *
     * @see       filterBySchoolClassStudent()
     *
     * @param     mixed $schoolClassStudentId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentReportQuery The current query, for fluid interface
     */
    public function filterBySchoolClassStudentId($schoolClassStudentId = null, $comparison = null)
    {
        if (is_array($schoolClassStudentId)) {
            $useMinMax = false;
            if (isset($schoolClassStudentId['min'])) {
                $this->addUsingAlias(StudentReportPeer::SCHOOL_CLASS_STUDENT_ID, $schoolClassStudentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($schoolClassStudentId['max'])) {
                $this->addUsingAlias(StudentReportPeer::SCHOOL_CLASS_STUDENT_ID, $schoolClassStudentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentReportPeer::SCHOOL_CLASS_STUDENT_ID, $schoolClassStudentId, $comparison);
    }

    /**
     * Filter the query on the score_id column
     *
     * Example usage:
     * <code>
     * $query->filterByScoreId(1234); // WHERE score_id = 1234
     * $query->filterByScoreId(array(12, 34)); // WHERE score_id IN (12, 34)
     * $query->filterByScoreId(array('min' => 12)); // WHERE score_id >= 12
     * $query->filterByScoreId(array('max' => 12)); // WHERE score_id <= 12
     * </code>
     *
     * @see       filterByScore()
     *
     * @param     mixed $scoreId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentReportQuery The current query, for fluid interface
     */
    public function filterByScoreId($scoreId = null, $comparison = null)
    {
        if (is_array($scoreId)) {
            $useMinMax = false;
            if (isset($scoreId['min'])) {
                $this->addUsingAlias(StudentReportPeer::SCORE_ID, $scoreId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($scoreId['max'])) {
                $this->addUsingAlias(StudentReportPeer::SCORE_ID, $scoreId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentReportPeer::SCORE_ID, $scoreId, $comparison);
    }

    /**
     * Filter the query on the term1 column
     *
     * Example usage:
     * <code>
     * $query->filterByTerm1(1234); // WHERE term1 = 1234
     * $query->filterByTerm1(array(12, 34)); // WHERE term1 IN (12, 34)
     * $query->filterByTerm1(array('min' => 12)); // WHERE term1 >= 12
     * $query->filterByTerm1(array('max' => 12)); // WHERE term1 <= 12
     * </code>
     *
     * @param     mixed $term1 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentReportQuery The current query, for fluid interface
     */
    public function filterByTerm1($term1 = null, $comparison = null)
    {
        if (is_array($term1)) {
            $useMinMax = false;
            if (isset($term1['min'])) {
                $this->addUsingAlias(StudentReportPeer::TERM1, $term1['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($term1['max'])) {
                $this->addUsingAlias(StudentReportPeer::TERM1, $term1['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentReportPeer::TERM1, $term1, $comparison);
    }

    /**
     * Filter the query on the term2 column
     *
     * Example usage:
     * <code>
     * $query->filterByTerm2(1234); // WHERE term2 = 1234
     * $query->filterByTerm2(array(12, 34)); // WHERE term2 IN (12, 34)
     * $query->filterByTerm2(array('min' => 12)); // WHERE term2 >= 12
     * $query->filterByTerm2(array('max' => 12)); // WHERE term2 <= 12
     * </code>
     *
     * @param     mixed $term2 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentReportQuery The current query, for fluid interface
     */
    public function filterByTerm2($term2 = null, $comparison = null)
    {
        if (is_array($term2)) {
            $useMinMax = false;
            if (isset($term2['min'])) {
                $this->addUsingAlias(StudentReportPeer::TERM2, $term2['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($term2['max'])) {
                $this->addUsingAlias(StudentReportPeer::TERM2, $term2['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentReportPeer::TERM2, $term2, $comparison);
    }

    /**
     * Filter the query on the term3 column
     *
     * Example usage:
     * <code>
     * $query->filterByTerm3(1234); // WHERE term3 = 1234
     * $query->filterByTerm3(array(12, 34)); // WHERE term3 IN (12, 34)
     * $query->filterByTerm3(array('min' => 12)); // WHERE term3 >= 12
     * $query->filterByTerm3(array('max' => 12)); // WHERE term3 <= 12
     * </code>
     *
     * @param     mixed $term3 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentReportQuery The current query, for fluid interface
     */
    public function filterByTerm3($term3 = null, $comparison = null)
    {
        if (is_array($term3)) {
            $useMinMax = false;
            if (isset($term3['min'])) {
                $this->addUsingAlias(StudentReportPeer::TERM3, $term3['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($term3['max'])) {
                $this->addUsingAlias(StudentReportPeer::TERM3, $term3['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentReportPeer::TERM3, $term3, $comparison);
    }

    /**
     * Filter the query on the term4 column
     *
     * Example usage:
     * <code>
     * $query->filterByTerm4(1234); // WHERE term4 = 1234
     * $query->filterByTerm4(array(12, 34)); // WHERE term4 IN (12, 34)
     * $query->filterByTerm4(array('min' => 12)); // WHERE term4 >= 12
     * $query->filterByTerm4(array('max' => 12)); // WHERE term4 <= 12
     * </code>
     *
     * @param     mixed $term4 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentReportQuery The current query, for fluid interface
     */
    public function filterByTerm4($term4 = null, $comparison = null)
    {
        if (is_array($term4)) {
            $useMinMax = false;
            if (isset($term4['min'])) {
                $this->addUsingAlias(StudentReportPeer::TERM4, $term4['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($term4['max'])) {
                $this->addUsingAlias(StudentReportPeer::TERM4, $term4['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentReportPeer::TERM4, $term4, $comparison);
    }

    /**
     * Filter the query on the mid_report column
     *
     * Example usage:
     * <code>
     * $query->filterByMidReport(1234); // WHERE mid_report = 1234
     * $query->filterByMidReport(array(12, 34)); // WHERE mid_report IN (12, 34)
     * $query->filterByMidReport(array('min' => 12)); // WHERE mid_report >= 12
     * $query->filterByMidReport(array('max' => 12)); // WHERE mid_report <= 12
     * </code>
     *
     * @param     mixed $midReport The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentReportQuery The current query, for fluid interface
     */
    public function filterByMidReport($midReport = null, $comparison = null)
    {
        if (is_array($midReport)) {
            $useMinMax = false;
            if (isset($midReport['min'])) {
                $this->addUsingAlias(StudentReportPeer::MID_REPORT, $midReport['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($midReport['max'])) {
                $this->addUsingAlias(StudentReportPeer::MID_REPORT, $midReport['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentReportPeer::MID_REPORT, $midReport, $comparison);
    }

    /**
     * Filter the query on the final_report column
     *
     * Example usage:
     * <code>
     * $query->filterByFinalReport(1234); // WHERE final_report = 1234
     * $query->filterByFinalReport(array(12, 34)); // WHERE final_report IN (12, 34)
     * $query->filterByFinalReport(array('min' => 12)); // WHERE final_report >= 12
     * $query->filterByFinalReport(array('max' => 12)); // WHERE final_report <= 12
     * </code>
     *
     * @param     mixed $finalReport The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentReportQuery The current query, for fluid interface
     */
    public function filterByFinalReport($finalReport = null, $comparison = null)
    {
        if (is_array($finalReport)) {
            $useMinMax = false;
            if (isset($finalReport['min'])) {
                $this->addUsingAlias(StudentReportPeer::FINAL_REPORT, $finalReport['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($finalReport['max'])) {
                $this->addUsingAlias(StudentReportPeer::FINAL_REPORT, $finalReport['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentReportPeer::FINAL_REPORT, $finalReport, $comparison);
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
     * @return StudentReportQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(StudentReportPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(StudentReportPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentReportPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return StudentReportQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(StudentReportPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(StudentReportPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentReportPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related SchoolClass object
     *
     * @param   SchoolClass|PropelObjectCollection $schoolClass The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StudentReportQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolClass($schoolClass, $comparison = null)
    {
        if ($schoolClass instanceof SchoolClass) {
            return $this
                ->addUsingAlias(StudentReportPeer::SCHOOL_CLASS_ID, $schoolClass->getId(), $comparison);
        } elseif ($schoolClass instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(StudentReportPeer::SCHOOL_CLASS_ID, $schoolClass->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySchoolClass() only accepts arguments of type SchoolClass or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SchoolClass relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StudentReportQuery The current query, for fluid interface
     */
    public function joinSchoolClass($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SchoolClass');

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
            $this->addJoinObject($join, 'SchoolClass');
        }

        return $this;
    }

    /**
     * Use the SchoolClass relation SchoolClass object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\SchoolClass\SchoolClassQuery A secondary query class using the current class as primary query
     */
    public function useSchoolClassQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSchoolClass($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SchoolClass', '\PGS\CoreDomainBundle\Model\SchoolClass\SchoolClassQuery');
    }

    /**
     * Filter the query by a related SchoolClassStudent object
     *
     * @param   SchoolClassStudent|PropelObjectCollection $schoolClassStudent The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StudentReportQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolClassStudent($schoolClassStudent, $comparison = null)
    {
        if ($schoolClassStudent instanceof SchoolClassStudent) {
            return $this
                ->addUsingAlias(StudentReportPeer::SCHOOL_CLASS_STUDENT_ID, $schoolClassStudent->getId(), $comparison);
        } elseif ($schoolClassStudent instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(StudentReportPeer::SCHOOL_CLASS_STUDENT_ID, $schoolClassStudent->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySchoolClassStudent() only accepts arguments of type SchoolClassStudent or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SchoolClassStudent relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StudentReportQuery The current query, for fluid interface
     */
    public function joinSchoolClassStudent($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SchoolClassStudent');

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
            $this->addJoinObject($join, 'SchoolClassStudent');
        }

        return $this;
    }

    /**
     * Use the SchoolClassStudent relation SchoolClassStudent object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudentQuery A secondary query class using the current class as primary query
     */
    public function useSchoolClassStudentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSchoolClassStudent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SchoolClassStudent', '\PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudentQuery');
    }

    /**
     * Filter the query by a related Score object
     *
     * @param   Score|PropelObjectCollection $score The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StudentReportQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByScore($score, $comparison = null)
    {
        if ($score instanceof Score) {
            return $this
                ->addUsingAlias(StudentReportPeer::SCORE_ID, $score->getId(), $comparison);
        } elseif ($score instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(StudentReportPeer::SCORE_ID, $score->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByScore() only accepts arguments of type Score or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Score relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StudentReportQuery The current query, for fluid interface
     */
    public function joinScore($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Score');

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
            $this->addJoinObject($join, 'Score');
        }

        return $this;
    }

    /**
     * Use the Score relation Score object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\Score\ScoreQuery A secondary query class using the current class as primary query
     */
    public function useScoreQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinScore($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Score', '\PGS\CoreDomainBundle\Model\Score\ScoreQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   StudentReport $studentReport Object to remove from the list of results
     *
     * @return StudentReportQuery The current query, for fluid interface
     */
    public function prune($studentReport = null)
    {
        if ($studentReport) {
            $this->addUsingAlias(StudentReportPeer::ID, $studentReport->getId(), Criteria::NOT_EQUAL);
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
     * @return     StudentReportQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(StudentReportPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     StudentReportQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(StudentReportPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     StudentReportQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(StudentReportPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     StudentReportQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(StudentReportPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     StudentReportQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(StudentReportPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     StudentReportQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(StudentReportPeer::CREATED_AT);
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
