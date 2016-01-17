<?php

namespace PGS\CoreDomainBundle\Model\Score\om;

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
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourse;
use PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudent;
use PGS\CoreDomainBundle\Model\Score\Score;
use PGS\CoreDomainBundle\Model\Score\ScorePeer;
use PGS\CoreDomainBundle\Model\Score\ScoreQuery;
use PGS\CoreDomainBundle\Model\StudentReport\StudentReport;

/**
 * @method ScoreQuery orderById($order = Criteria::ASC) Order by the id column
 * @method ScoreQuery orderByHomework($order = Criteria::ASC) Order by the homework column
 * @method ScoreQuery orderByDailyExam($order = Criteria::ASC) Order by the daily_exam column
 * @method ScoreQuery orderByMidExam($order = Criteria::ASC) Order by the mid_exam column
 * @method ScoreQuery orderByFinalExam($order = Criteria::ASC) Order by the final_exam column
 * @method ScoreQuery orderBySchoolClassStudentId($order = Criteria::ASC) Order by the school_class_student_id column
 * @method ScoreQuery orderBySchoolClassCourseId($order = Criteria::ASC) Order by the school_class_course_id column
 * @method ScoreQuery orderByStudentId($order = Criteria::ASC) Order by the student_id column
 * @method ScoreQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method ScoreQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method ScoreQuery groupById() Group by the id column
 * @method ScoreQuery groupByHomework() Group by the homework column
 * @method ScoreQuery groupByDailyExam() Group by the daily_exam column
 * @method ScoreQuery groupByMidExam() Group by the mid_exam column
 * @method ScoreQuery groupByFinalExam() Group by the final_exam column
 * @method ScoreQuery groupBySchoolClassStudentId() Group by the school_class_student_id column
 * @method ScoreQuery groupBySchoolClassCourseId() Group by the school_class_course_id column
 * @method ScoreQuery groupByStudentId() Group by the student_id column
 * @method ScoreQuery groupByCreatedAt() Group by the created_at column
 * @method ScoreQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method ScoreQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ScoreQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ScoreQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ScoreQuery leftJoinSchoolClassStudent($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolClassStudent relation
 * @method ScoreQuery rightJoinSchoolClassStudent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolClassStudent relation
 * @method ScoreQuery innerJoinSchoolClassStudent($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolClassStudent relation
 *
 * @method ScoreQuery leftJoinSchoolClassCourse($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolClassCourse relation
 * @method ScoreQuery rightJoinSchoolClassCourse($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolClassCourse relation
 * @method ScoreQuery innerJoinSchoolClassCourse($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolClassCourse relation
 *
 * @method ScoreQuery leftJoinStudentReport($relationAlias = null) Adds a LEFT JOIN clause to the query using the StudentReport relation
 * @method ScoreQuery rightJoinStudentReport($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StudentReport relation
 * @method ScoreQuery innerJoinStudentReport($relationAlias = null) Adds a INNER JOIN clause to the query using the StudentReport relation
 *
 * @method Score findOne(PropelPDO $con = null) Return the first Score matching the query
 * @method Score findOneOrCreate(PropelPDO $con = null) Return the first Score matching the query, or a new Score object populated from the query conditions when no match is found
 *
 * @method Score findOneByHomework(string $homework) Return the first Score filtered by the homework column
 * @method Score findOneByDailyExam(string $daily_exam) Return the first Score filtered by the daily_exam column
 * @method Score findOneByMidExam(string $mid_exam) Return the first Score filtered by the mid_exam column
 * @method Score findOneByFinalExam(string $final_exam) Return the first Score filtered by the final_exam column
 * @method Score findOneBySchoolClassStudentId(int $school_class_student_id) Return the first Score filtered by the school_class_student_id column
 * @method Score findOneBySchoolClassCourseId(int $school_class_course_id) Return the first Score filtered by the school_class_course_id column
 * @method Score findOneByStudentId(int $student_id) Return the first Score filtered by the student_id column
 * @method Score findOneByCreatedAt(string $created_at) Return the first Score filtered by the created_at column
 * @method Score findOneByUpdatedAt(string $updated_at) Return the first Score filtered by the updated_at column
 *
 * @method array findById(int $id) Return Score objects filtered by the id column
 * @method array findByHomework(string $homework) Return Score objects filtered by the homework column
 * @method array findByDailyExam(string $daily_exam) Return Score objects filtered by the daily_exam column
 * @method array findByMidExam(string $mid_exam) Return Score objects filtered by the mid_exam column
 * @method array findByFinalExam(string $final_exam) Return Score objects filtered by the final_exam column
 * @method array findBySchoolClassStudentId(int $school_class_student_id) Return Score objects filtered by the school_class_student_id column
 * @method array findBySchoolClassCourseId(int $school_class_course_id) Return Score objects filtered by the school_class_course_id column
 * @method array findByStudentId(int $student_id) Return Score objects filtered by the student_id column
 * @method array findByCreatedAt(string $created_at) Return Score objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Score objects filtered by the updated_at column
 */
abstract class BaseScoreQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseScoreQuery object.
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
            $modelName = 'PGS\\CoreDomainBundle\\Model\\Score\\Score';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new ScoreQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   ScoreQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ScoreQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ScoreQuery) {
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
     * @return   Score|Score[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ScorePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ScorePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Score A model object, or null if the key is not found
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
     * @return                 Score A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `homework`, `daily_exam`, `mid_exam`, `final_exam`, `school_class_student_id`, `school_class_course_id`, `student_id`, `created_at`, `updated_at` FROM `score` WHERE `id` = :p0';
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
            $cls = ScorePeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            ScorePeer::addInstanceToPool($obj, (string) $key);
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
     * @return Score|Score[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Score[]|mixed the list of results, formatted by the current formatter
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
     * @return ScoreQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ScorePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ScoreQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ScorePeer::ID, $keys, Criteria::IN);
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
     * @return ScoreQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ScorePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ScorePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ScorePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the homework column
     *
     * Example usage:
     * <code>
     * $query->filterByHomework(1234); // WHERE homework = 1234
     * $query->filterByHomework(array(12, 34)); // WHERE homework IN (12, 34)
     * $query->filterByHomework(array('min' => 12)); // WHERE homework >= 12
     * $query->filterByHomework(array('max' => 12)); // WHERE homework <= 12
     * </code>
     *
     * @param     mixed $homework The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ScoreQuery The current query, for fluid interface
     */
    public function filterByHomework($homework = null, $comparison = null)
    {
        if (is_array($homework)) {
            $useMinMax = false;
            if (isset($homework['min'])) {
                $this->addUsingAlias(ScorePeer::HOMEWORK, $homework['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($homework['max'])) {
                $this->addUsingAlias(ScorePeer::HOMEWORK, $homework['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ScorePeer::HOMEWORK, $homework, $comparison);
    }

    /**
     * Filter the query on the daily_exam column
     *
     * Example usage:
     * <code>
     * $query->filterByDailyExam(1234); // WHERE daily_exam = 1234
     * $query->filterByDailyExam(array(12, 34)); // WHERE daily_exam IN (12, 34)
     * $query->filterByDailyExam(array('min' => 12)); // WHERE daily_exam >= 12
     * $query->filterByDailyExam(array('max' => 12)); // WHERE daily_exam <= 12
     * </code>
     *
     * @param     mixed $dailyExam The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ScoreQuery The current query, for fluid interface
     */
    public function filterByDailyExam($dailyExam = null, $comparison = null)
    {
        if (is_array($dailyExam)) {
            $useMinMax = false;
            if (isset($dailyExam['min'])) {
                $this->addUsingAlias(ScorePeer::DAILY_EXAM, $dailyExam['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dailyExam['max'])) {
                $this->addUsingAlias(ScorePeer::DAILY_EXAM, $dailyExam['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ScorePeer::DAILY_EXAM, $dailyExam, $comparison);
    }

    /**
     * Filter the query on the mid_exam column
     *
     * Example usage:
     * <code>
     * $query->filterByMidExam(1234); // WHERE mid_exam = 1234
     * $query->filterByMidExam(array(12, 34)); // WHERE mid_exam IN (12, 34)
     * $query->filterByMidExam(array('min' => 12)); // WHERE mid_exam >= 12
     * $query->filterByMidExam(array('max' => 12)); // WHERE mid_exam <= 12
     * </code>
     *
     * @param     mixed $midExam The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ScoreQuery The current query, for fluid interface
     */
    public function filterByMidExam($midExam = null, $comparison = null)
    {
        if (is_array($midExam)) {
            $useMinMax = false;
            if (isset($midExam['min'])) {
                $this->addUsingAlias(ScorePeer::MID_EXAM, $midExam['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($midExam['max'])) {
                $this->addUsingAlias(ScorePeer::MID_EXAM, $midExam['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ScorePeer::MID_EXAM, $midExam, $comparison);
    }

    /**
     * Filter the query on the final_exam column
     *
     * Example usage:
     * <code>
     * $query->filterByFinalExam(1234); // WHERE final_exam = 1234
     * $query->filterByFinalExam(array(12, 34)); // WHERE final_exam IN (12, 34)
     * $query->filterByFinalExam(array('min' => 12)); // WHERE final_exam >= 12
     * $query->filterByFinalExam(array('max' => 12)); // WHERE final_exam <= 12
     * </code>
     *
     * @param     mixed $finalExam The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ScoreQuery The current query, for fluid interface
     */
    public function filterByFinalExam($finalExam = null, $comparison = null)
    {
        if (is_array($finalExam)) {
            $useMinMax = false;
            if (isset($finalExam['min'])) {
                $this->addUsingAlias(ScorePeer::FINAL_EXAM, $finalExam['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($finalExam['max'])) {
                $this->addUsingAlias(ScorePeer::FINAL_EXAM, $finalExam['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ScorePeer::FINAL_EXAM, $finalExam, $comparison);
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
     * @return ScoreQuery The current query, for fluid interface
     */
    public function filterBySchoolClassStudentId($schoolClassStudentId = null, $comparison = null)
    {
        if (is_array($schoolClassStudentId)) {
            $useMinMax = false;
            if (isset($schoolClassStudentId['min'])) {
                $this->addUsingAlias(ScorePeer::SCHOOL_CLASS_STUDENT_ID, $schoolClassStudentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($schoolClassStudentId['max'])) {
                $this->addUsingAlias(ScorePeer::SCHOOL_CLASS_STUDENT_ID, $schoolClassStudentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ScorePeer::SCHOOL_CLASS_STUDENT_ID, $schoolClassStudentId, $comparison);
    }

    /**
     * Filter the query on the school_class_course_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySchoolClassCourseId(1234); // WHERE school_class_course_id = 1234
     * $query->filterBySchoolClassCourseId(array(12, 34)); // WHERE school_class_course_id IN (12, 34)
     * $query->filterBySchoolClassCourseId(array('min' => 12)); // WHERE school_class_course_id >= 12
     * $query->filterBySchoolClassCourseId(array('max' => 12)); // WHERE school_class_course_id <= 12
     * </code>
     *
     * @see       filterBySchoolClassCourse()
     *
     * @param     mixed $schoolClassCourseId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ScoreQuery The current query, for fluid interface
     */
    public function filterBySchoolClassCourseId($schoolClassCourseId = null, $comparison = null)
    {
        if (is_array($schoolClassCourseId)) {
            $useMinMax = false;
            if (isset($schoolClassCourseId['min'])) {
                $this->addUsingAlias(ScorePeer::SCHOOL_CLASS_COURSE_ID, $schoolClassCourseId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($schoolClassCourseId['max'])) {
                $this->addUsingAlias(ScorePeer::SCHOOL_CLASS_COURSE_ID, $schoolClassCourseId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ScorePeer::SCHOOL_CLASS_COURSE_ID, $schoolClassCourseId, $comparison);
    }

    /**
     * Filter the query on the student_id column
     *
     * Example usage:
     * <code>
     * $query->filterByStudentId(1234); // WHERE student_id = 1234
     * $query->filterByStudentId(array(12, 34)); // WHERE student_id IN (12, 34)
     * $query->filterByStudentId(array('min' => 12)); // WHERE student_id >= 12
     * $query->filterByStudentId(array('max' => 12)); // WHERE student_id <= 12
     * </code>
     *
     * @param     mixed $studentId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ScoreQuery The current query, for fluid interface
     */
    public function filterByStudentId($studentId = null, $comparison = null)
    {
        if (is_array($studentId)) {
            $useMinMax = false;
            if (isset($studentId['min'])) {
                $this->addUsingAlias(ScorePeer::STUDENT_ID, $studentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($studentId['max'])) {
                $this->addUsingAlias(ScorePeer::STUDENT_ID, $studentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ScorePeer::STUDENT_ID, $studentId, $comparison);
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
     * @return ScoreQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(ScorePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ScorePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ScorePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return ScoreQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(ScorePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ScorePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ScorePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related SchoolClassStudent object
     *
     * @param   SchoolClassStudent|PropelObjectCollection $schoolClassStudent The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ScoreQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolClassStudent($schoolClassStudent, $comparison = null)
    {
        if ($schoolClassStudent instanceof SchoolClassStudent) {
            return $this
                ->addUsingAlias(ScorePeer::SCHOOL_CLASS_STUDENT_ID, $schoolClassStudent->getId(), $comparison);
        } elseif ($schoolClassStudent instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ScorePeer::SCHOOL_CLASS_STUDENT_ID, $schoolClassStudent->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return ScoreQuery The current query, for fluid interface
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
     * Filter the query by a related SchoolClassCourse object
     *
     * @param   SchoolClassCourse|PropelObjectCollection $schoolClassCourse The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ScoreQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolClassCourse($schoolClassCourse, $comparison = null)
    {
        if ($schoolClassCourse instanceof SchoolClassCourse) {
            return $this
                ->addUsingAlias(ScorePeer::SCHOOL_CLASS_COURSE_ID, $schoolClassCourse->getId(), $comparison);
        } elseif ($schoolClassCourse instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ScorePeer::SCHOOL_CLASS_COURSE_ID, $schoolClassCourse->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return ScoreQuery The current query, for fluid interface
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
     * Filter the query by a related StudentReport object
     *
     * @param   StudentReport|PropelObjectCollection $studentReport  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ScoreQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStudentReport($studentReport, $comparison = null)
    {
        if ($studentReport instanceof StudentReport) {
            return $this
                ->addUsingAlias(ScorePeer::ID, $studentReport->getScoreId(), $comparison);
        } elseif ($studentReport instanceof PropelObjectCollection) {
            return $this
                ->useStudentReportQuery()
                ->filterByPrimaryKeys($studentReport->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByStudentReport() only accepts arguments of type StudentReport or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the StudentReport relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ScoreQuery The current query, for fluid interface
     */
    public function joinStudentReport($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('StudentReport');

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
            $this->addJoinObject($join, 'StudentReport');
        }

        return $this;
    }

    /**
     * Use the StudentReport relation StudentReport object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\StudentReport\StudentReportQuery A secondary query class using the current class as primary query
     */
    public function useStudentReportQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinStudentReport($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'StudentReport', '\PGS\CoreDomainBundle\Model\StudentReport\StudentReportQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Score $score Object to remove from the list of results
     *
     * @return ScoreQuery The current query, for fluid interface
     */
    public function prune($score = null)
    {
        if ($score) {
            $this->addUsingAlias(ScorePeer::ID, $score->getId(), Criteria::NOT_EQUAL);
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
     * @return     ScoreQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(ScorePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ScoreQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(ScorePeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ScoreQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(ScorePeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ScoreQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(ScorePeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     ScoreQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(ScorePeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ScoreQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(ScorePeer::CREATED_AT);
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
