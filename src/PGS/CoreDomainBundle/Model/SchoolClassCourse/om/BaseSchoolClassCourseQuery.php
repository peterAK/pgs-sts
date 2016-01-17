<?php

namespace PGS\CoreDomainBundle\Model\SchoolClassCourse\om;

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
use PGS\CoreDomainBundle\Model\User;
use PGS\CoreDomainBundle\Model\Course\Course;
use PGS\CoreDomainBundle\Model\Formula\Formula;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClass;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourse;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCoursePeer;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourseQuery;
use PGS\CoreDomainBundle\Model\SchoolClassCourseStudentBehavior\SchoolClassCourseStudentBehavior;
use PGS\CoreDomainBundle\Model\SchoolGradeLevel\SchoolGradeLevel;
use PGS\CoreDomainBundle\Model\SchoolTerm\SchoolTerm;
use PGS\CoreDomainBundle\Model\Score\Score;

/**
 * @method SchoolClassCourseQuery orderById($order = Criteria::ASC) Order by the id column
 * @method SchoolClassCourseQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method SchoolClassCourseQuery orderBySchoolClassId($order = Criteria::ASC) Order by the school_class_id column
 * @method SchoolClassCourseQuery orderByStartTime($order = Criteria::ASC) Order by the start_time column
 * @method SchoolClassCourseQuery orderByEndTime($order = Criteria::ASC) Order by the end_time column
 * @method SchoolClassCourseQuery orderByCourseId($order = Criteria::ASC) Order by the course_id column
 * @method SchoolClassCourseQuery orderBySchoolTermId($order = Criteria::ASC) Order by the school_term_id column
 * @method SchoolClassCourseQuery orderBySchoolGradeLevelId($order = Criteria::ASC) Order by the school_grade_level_id column
 * @method SchoolClassCourseQuery orderByPrimaryTeacherId($order = Criteria::ASC) Order by the primary_teacher_id column
 * @method SchoolClassCourseQuery orderBySecondaryTeacherId($order = Criteria::ASC) Order by the secondary_teacher_id column
 * @method SchoolClassCourseQuery orderByFormulaId($order = Criteria::ASC) Order by the formula_id column
 * @method SchoolClassCourseQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method SchoolClassCourseQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method SchoolClassCourseQuery groupById() Group by the id column
 * @method SchoolClassCourseQuery groupByName() Group by the name column
 * @method SchoolClassCourseQuery groupBySchoolClassId() Group by the school_class_id column
 * @method SchoolClassCourseQuery groupByStartTime() Group by the start_time column
 * @method SchoolClassCourseQuery groupByEndTime() Group by the end_time column
 * @method SchoolClassCourseQuery groupByCourseId() Group by the course_id column
 * @method SchoolClassCourseQuery groupBySchoolTermId() Group by the school_term_id column
 * @method SchoolClassCourseQuery groupBySchoolGradeLevelId() Group by the school_grade_level_id column
 * @method SchoolClassCourseQuery groupByPrimaryTeacherId() Group by the primary_teacher_id column
 * @method SchoolClassCourseQuery groupBySecondaryTeacherId() Group by the secondary_teacher_id column
 * @method SchoolClassCourseQuery groupByFormulaId() Group by the formula_id column
 * @method SchoolClassCourseQuery groupByCreatedAt() Group by the created_at column
 * @method SchoolClassCourseQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method SchoolClassCourseQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method SchoolClassCourseQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method SchoolClassCourseQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method SchoolClassCourseQuery leftJoinCourse($relationAlias = null) Adds a LEFT JOIN clause to the query using the Course relation
 * @method SchoolClassCourseQuery rightJoinCourse($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Course relation
 * @method SchoolClassCourseQuery innerJoinCourse($relationAlias = null) Adds a INNER JOIN clause to the query using the Course relation
 *
 * @method SchoolClassCourseQuery leftJoinSchoolClass($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolClass relation
 * @method SchoolClassCourseQuery rightJoinSchoolClass($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolClass relation
 * @method SchoolClassCourseQuery innerJoinSchoolClass($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolClass relation
 *
 * @method SchoolClassCourseQuery leftJoinSchoolTerm($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolTerm relation
 * @method SchoolClassCourseQuery rightJoinSchoolTerm($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolTerm relation
 * @method SchoolClassCourseQuery innerJoinSchoolTerm($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolTerm relation
 *
 * @method SchoolClassCourseQuery leftJoinSchoolGradeLevel($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolGradeLevel relation
 * @method SchoolClassCourseQuery rightJoinSchoolGradeLevel($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolGradeLevel relation
 * @method SchoolClassCourseQuery innerJoinSchoolGradeLevel($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolGradeLevel relation
 *
 * @method SchoolClassCourseQuery leftJoinPrimaryTeacher($relationAlias = null) Adds a LEFT JOIN clause to the query using the PrimaryTeacher relation
 * @method SchoolClassCourseQuery rightJoinPrimaryTeacher($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PrimaryTeacher relation
 * @method SchoolClassCourseQuery innerJoinPrimaryTeacher($relationAlias = null) Adds a INNER JOIN clause to the query using the PrimaryTeacher relation
 *
 * @method SchoolClassCourseQuery leftJoinSecondaryTeacher($relationAlias = null) Adds a LEFT JOIN clause to the query using the SecondaryTeacher relation
 * @method SchoolClassCourseQuery rightJoinSecondaryTeacher($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SecondaryTeacher relation
 * @method SchoolClassCourseQuery innerJoinSecondaryTeacher($relationAlias = null) Adds a INNER JOIN clause to the query using the SecondaryTeacher relation
 *
 * @method SchoolClassCourseQuery leftJoinFormula($relationAlias = null) Adds a LEFT JOIN clause to the query using the Formula relation
 * @method SchoolClassCourseQuery rightJoinFormula($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Formula relation
 * @method SchoolClassCourseQuery innerJoinFormula($relationAlias = null) Adds a INNER JOIN clause to the query using the Formula relation
 *
 * @method SchoolClassCourseQuery leftJoinSchoolClassCourseStudentBehavior($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolClassCourseStudentBehavior relation
 * @method SchoolClassCourseQuery rightJoinSchoolClassCourseStudentBehavior($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolClassCourseStudentBehavior relation
 * @method SchoolClassCourseQuery innerJoinSchoolClassCourseStudentBehavior($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolClassCourseStudentBehavior relation
 *
 * @method SchoolClassCourseQuery leftJoinScore($relationAlias = null) Adds a LEFT JOIN clause to the query using the Score relation
 * @method SchoolClassCourseQuery rightJoinScore($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Score relation
 * @method SchoolClassCourseQuery innerJoinScore($relationAlias = null) Adds a INNER JOIN clause to the query using the Score relation
 *
 * @method SchoolClassCourse findOne(PropelPDO $con = null) Return the first SchoolClassCourse matching the query
 * @method SchoolClassCourse findOneOrCreate(PropelPDO $con = null) Return the first SchoolClassCourse matching the query, or a new SchoolClassCourse object populated from the query conditions when no match is found
 *
 * @method SchoolClassCourse findOneByName(string $name) Return the first SchoolClassCourse filtered by the name column
 * @method SchoolClassCourse findOneBySchoolClassId(int $school_class_id) Return the first SchoolClassCourse filtered by the school_class_id column
 * @method SchoolClassCourse findOneByStartTime(string $start_time) Return the first SchoolClassCourse filtered by the start_time column
 * @method SchoolClassCourse findOneByEndTime(string $end_time) Return the first SchoolClassCourse filtered by the end_time column
 * @method SchoolClassCourse findOneByCourseId(int $course_id) Return the first SchoolClassCourse filtered by the course_id column
 * @method SchoolClassCourse findOneBySchoolTermId(int $school_term_id) Return the first SchoolClassCourse filtered by the school_term_id column
 * @method SchoolClassCourse findOneBySchoolGradeLevelId(int $school_grade_level_id) Return the first SchoolClassCourse filtered by the school_grade_level_id column
 * @method SchoolClassCourse findOneByPrimaryTeacherId(int $primary_teacher_id) Return the first SchoolClassCourse filtered by the primary_teacher_id column
 * @method SchoolClassCourse findOneBySecondaryTeacherId(int $secondary_teacher_id) Return the first SchoolClassCourse filtered by the secondary_teacher_id column
 * @method SchoolClassCourse findOneByFormulaId(int $formula_id) Return the first SchoolClassCourse filtered by the formula_id column
 * @method SchoolClassCourse findOneByCreatedAt(string $created_at) Return the first SchoolClassCourse filtered by the created_at column
 * @method SchoolClassCourse findOneByUpdatedAt(string $updated_at) Return the first SchoolClassCourse filtered by the updated_at column
 *
 * @method array findById(int $id) Return SchoolClassCourse objects filtered by the id column
 * @method array findByName(string $name) Return SchoolClassCourse objects filtered by the name column
 * @method array findBySchoolClassId(int $school_class_id) Return SchoolClassCourse objects filtered by the school_class_id column
 * @method array findByStartTime(string $start_time) Return SchoolClassCourse objects filtered by the start_time column
 * @method array findByEndTime(string $end_time) Return SchoolClassCourse objects filtered by the end_time column
 * @method array findByCourseId(int $course_id) Return SchoolClassCourse objects filtered by the course_id column
 * @method array findBySchoolTermId(int $school_term_id) Return SchoolClassCourse objects filtered by the school_term_id column
 * @method array findBySchoolGradeLevelId(int $school_grade_level_id) Return SchoolClassCourse objects filtered by the school_grade_level_id column
 * @method array findByPrimaryTeacherId(int $primary_teacher_id) Return SchoolClassCourse objects filtered by the primary_teacher_id column
 * @method array findBySecondaryTeacherId(int $secondary_teacher_id) Return SchoolClassCourse objects filtered by the secondary_teacher_id column
 * @method array findByFormulaId(int $formula_id) Return SchoolClassCourse objects filtered by the formula_id column
 * @method array findByCreatedAt(string $created_at) Return SchoolClassCourse objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return SchoolClassCourse objects filtered by the updated_at column
 */
abstract class BaseSchoolClassCourseQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseSchoolClassCourseQuery object.
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
            $modelName = 'PGS\\CoreDomainBundle\\Model\\SchoolClassCourse\\SchoolClassCourse';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new SchoolClassCourseQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   SchoolClassCourseQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return SchoolClassCourseQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof SchoolClassCourseQuery) {
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
     * @return   SchoolClassCourse|SchoolClassCourse[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SchoolClassCoursePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 SchoolClassCourse A model object, or null if the key is not found
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
     * @return                 SchoolClassCourse A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `school_class_id`, `start_time`, `end_time`, `course_id`, `school_term_id`, `school_grade_level_id`, `primary_teacher_id`, `secondary_teacher_id`, `formula_id`, `created_at`, `updated_at` FROM `school_class_course` WHERE `id` = :p0';
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
            $cls = SchoolClassCoursePeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            SchoolClassCoursePeer::addInstanceToPool($obj, (string) $key);
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
     * @return SchoolClassCourse|SchoolClassCourse[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|SchoolClassCourse[]|mixed the list of results, formatted by the current formatter
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
     * @return SchoolClassCourseQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SchoolClassCoursePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return SchoolClassCourseQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SchoolClassCoursePeer::ID, $keys, Criteria::IN);
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
     * @return SchoolClassCourseQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolClassCoursePeer::ID, $id, $comparison);
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
     * @return SchoolClassCourseQuery The current query, for fluid interface
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

        return $this->addUsingAlias(SchoolClassCoursePeer::NAME, $name, $comparison);
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
     * @return SchoolClassCourseQuery The current query, for fluid interface
     */
    public function filterBySchoolClassId($schoolClassId = null, $comparison = null)
    {
        if (is_array($schoolClassId)) {
            $useMinMax = false;
            if (isset($schoolClassId['min'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::SCHOOL_CLASS_ID, $schoolClassId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($schoolClassId['max'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::SCHOOL_CLASS_ID, $schoolClassId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolClassCoursePeer::SCHOOL_CLASS_ID, $schoolClassId, $comparison);
    }

    /**
     * Filter the query on the start_time column
     *
     * Example usage:
     * <code>
     * $query->filterByStartTime('2011-03-14'); // WHERE start_time = '2011-03-14'
     * $query->filterByStartTime('now'); // WHERE start_time = '2011-03-14'
     * $query->filterByStartTime(array('max' => 'yesterday')); // WHERE start_time < '2011-03-13'
     * </code>
     *
     * @param     mixed $startTime The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolClassCourseQuery The current query, for fluid interface
     */
    public function filterByStartTime($startTime = null, $comparison = null)
    {
        if (is_array($startTime)) {
            $useMinMax = false;
            if (isset($startTime['min'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::START_TIME, $startTime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($startTime['max'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::START_TIME, $startTime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolClassCoursePeer::START_TIME, $startTime, $comparison);
    }

    /**
     * Filter the query on the end_time column
     *
     * Example usage:
     * <code>
     * $query->filterByEndTime('2011-03-14'); // WHERE end_time = '2011-03-14'
     * $query->filterByEndTime('now'); // WHERE end_time = '2011-03-14'
     * $query->filterByEndTime(array('max' => 'yesterday')); // WHERE end_time < '2011-03-13'
     * </code>
     *
     * @param     mixed $endTime The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolClassCourseQuery The current query, for fluid interface
     */
    public function filterByEndTime($endTime = null, $comparison = null)
    {
        if (is_array($endTime)) {
            $useMinMax = false;
            if (isset($endTime['min'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::END_TIME, $endTime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($endTime['max'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::END_TIME, $endTime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolClassCoursePeer::END_TIME, $endTime, $comparison);
    }

    /**
     * Filter the query on the course_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCourseId(1234); // WHERE course_id = 1234
     * $query->filterByCourseId(array(12, 34)); // WHERE course_id IN (12, 34)
     * $query->filterByCourseId(array('min' => 12)); // WHERE course_id >= 12
     * $query->filterByCourseId(array('max' => 12)); // WHERE course_id <= 12
     * </code>
     *
     * @see       filterByCourse()
     *
     * @param     mixed $courseId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolClassCourseQuery The current query, for fluid interface
     */
    public function filterByCourseId($courseId = null, $comparison = null)
    {
        if (is_array($courseId)) {
            $useMinMax = false;
            if (isset($courseId['min'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::COURSE_ID, $courseId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($courseId['max'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::COURSE_ID, $courseId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolClassCoursePeer::COURSE_ID, $courseId, $comparison);
    }

    /**
     * Filter the query on the school_term_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySchoolTermId(1234); // WHERE school_term_id = 1234
     * $query->filterBySchoolTermId(array(12, 34)); // WHERE school_term_id IN (12, 34)
     * $query->filterBySchoolTermId(array('min' => 12)); // WHERE school_term_id >= 12
     * $query->filterBySchoolTermId(array('max' => 12)); // WHERE school_term_id <= 12
     * </code>
     *
     * @see       filterBySchoolTerm()
     *
     * @param     mixed $schoolTermId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolClassCourseQuery The current query, for fluid interface
     */
    public function filterBySchoolTermId($schoolTermId = null, $comparison = null)
    {
        if (is_array($schoolTermId)) {
            $useMinMax = false;
            if (isset($schoolTermId['min'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::SCHOOL_TERM_ID, $schoolTermId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($schoolTermId['max'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::SCHOOL_TERM_ID, $schoolTermId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolClassCoursePeer::SCHOOL_TERM_ID, $schoolTermId, $comparison);
    }

    /**
     * Filter the query on the school_grade_level_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySchoolGradeLevelId(1234); // WHERE school_grade_level_id = 1234
     * $query->filterBySchoolGradeLevelId(array(12, 34)); // WHERE school_grade_level_id IN (12, 34)
     * $query->filterBySchoolGradeLevelId(array('min' => 12)); // WHERE school_grade_level_id >= 12
     * $query->filterBySchoolGradeLevelId(array('max' => 12)); // WHERE school_grade_level_id <= 12
     * </code>
     *
     * @see       filterBySchoolGradeLevel()
     *
     * @param     mixed $schoolGradeLevelId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolClassCourseQuery The current query, for fluid interface
     */
    public function filterBySchoolGradeLevelId($schoolGradeLevelId = null, $comparison = null)
    {
        if (is_array($schoolGradeLevelId)) {
            $useMinMax = false;
            if (isset($schoolGradeLevelId['min'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, $schoolGradeLevelId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($schoolGradeLevelId['max'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, $schoolGradeLevelId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, $schoolGradeLevelId, $comparison);
    }

    /**
     * Filter the query on the primary_teacher_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPrimaryTeacherId(1234); // WHERE primary_teacher_id = 1234
     * $query->filterByPrimaryTeacherId(array(12, 34)); // WHERE primary_teacher_id IN (12, 34)
     * $query->filterByPrimaryTeacherId(array('min' => 12)); // WHERE primary_teacher_id >= 12
     * $query->filterByPrimaryTeacherId(array('max' => 12)); // WHERE primary_teacher_id <= 12
     * </code>
     *
     * @see       filterByPrimaryTeacher()
     *
     * @param     mixed $primaryTeacherId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolClassCourseQuery The current query, for fluid interface
     */
    public function filterByPrimaryTeacherId($primaryTeacherId = null, $comparison = null)
    {
        if (is_array($primaryTeacherId)) {
            $useMinMax = false;
            if (isset($primaryTeacherId['min'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::PRIMARY_TEACHER_ID, $primaryTeacherId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($primaryTeacherId['max'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::PRIMARY_TEACHER_ID, $primaryTeacherId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolClassCoursePeer::PRIMARY_TEACHER_ID, $primaryTeacherId, $comparison);
    }

    /**
     * Filter the query on the secondary_teacher_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySecondaryTeacherId(1234); // WHERE secondary_teacher_id = 1234
     * $query->filterBySecondaryTeacherId(array(12, 34)); // WHERE secondary_teacher_id IN (12, 34)
     * $query->filterBySecondaryTeacherId(array('min' => 12)); // WHERE secondary_teacher_id >= 12
     * $query->filterBySecondaryTeacherId(array('max' => 12)); // WHERE secondary_teacher_id <= 12
     * </code>
     *
     * @see       filterBySecondaryTeacher()
     *
     * @param     mixed $secondaryTeacherId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolClassCourseQuery The current query, for fluid interface
     */
    public function filterBySecondaryTeacherId($secondaryTeacherId = null, $comparison = null)
    {
        if (is_array($secondaryTeacherId)) {
            $useMinMax = false;
            if (isset($secondaryTeacherId['min'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::SECONDARY_TEACHER_ID, $secondaryTeacherId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($secondaryTeacherId['max'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::SECONDARY_TEACHER_ID, $secondaryTeacherId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolClassCoursePeer::SECONDARY_TEACHER_ID, $secondaryTeacherId, $comparison);
    }

    /**
     * Filter the query on the formula_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFormulaId(1234); // WHERE formula_id = 1234
     * $query->filterByFormulaId(array(12, 34)); // WHERE formula_id IN (12, 34)
     * $query->filterByFormulaId(array('min' => 12)); // WHERE formula_id >= 12
     * $query->filterByFormulaId(array('max' => 12)); // WHERE formula_id <= 12
     * </code>
     *
     * @see       filterByFormula()
     *
     * @param     mixed $formulaId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolClassCourseQuery The current query, for fluid interface
     */
    public function filterByFormulaId($formulaId = null, $comparison = null)
    {
        if (is_array($formulaId)) {
            $useMinMax = false;
            if (isset($formulaId['min'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::FORMULA_ID, $formulaId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($formulaId['max'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::FORMULA_ID, $formulaId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolClassCoursePeer::FORMULA_ID, $formulaId, $comparison);
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
     * @return SchoolClassCourseQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolClassCoursePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return SchoolClassCourseQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(SchoolClassCoursePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolClassCoursePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Course object
     *
     * @param   Course|PropelObjectCollection $course The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolClassCourseQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCourse($course, $comparison = null)
    {
        if ($course instanceof Course) {
            return $this
                ->addUsingAlias(SchoolClassCoursePeer::COURSE_ID, $course->getId(), $comparison);
        } elseif ($course instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SchoolClassCoursePeer::COURSE_ID, $course->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCourse() only accepts arguments of type Course or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Course relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SchoolClassCourseQuery The current query, for fluid interface
     */
    public function joinCourse($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Course');

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
            $this->addJoinObject($join, 'Course');
        }

        return $this;
    }

    /**
     * Use the Course relation Course object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\Course\CourseQuery A secondary query class using the current class as primary query
     */
    public function useCourseQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCourse($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Course', '\PGS\CoreDomainBundle\Model\Course\CourseQuery');
    }

    /**
     * Filter the query by a related SchoolClass object
     *
     * @param   SchoolClass|PropelObjectCollection $schoolClass The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolClassCourseQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolClass($schoolClass, $comparison = null)
    {
        if ($schoolClass instanceof SchoolClass) {
            return $this
                ->addUsingAlias(SchoolClassCoursePeer::SCHOOL_CLASS_ID, $schoolClass->getId(), $comparison);
        } elseif ($schoolClass instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SchoolClassCoursePeer::SCHOOL_CLASS_ID, $schoolClass->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return SchoolClassCourseQuery The current query, for fluid interface
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
     * Filter the query by a related SchoolTerm object
     *
     * @param   SchoolTerm|PropelObjectCollection $schoolTerm The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolClassCourseQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolTerm($schoolTerm, $comparison = null)
    {
        if ($schoolTerm instanceof SchoolTerm) {
            return $this
                ->addUsingAlias(SchoolClassCoursePeer::SCHOOL_TERM_ID, $schoolTerm->getId(), $comparison);
        } elseif ($schoolTerm instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SchoolClassCoursePeer::SCHOOL_TERM_ID, $schoolTerm->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySchoolTerm() only accepts arguments of type SchoolTerm or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SchoolTerm relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SchoolClassCourseQuery The current query, for fluid interface
     */
    public function joinSchoolTerm($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SchoolTerm');

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
            $this->addJoinObject($join, 'SchoolTerm');
        }

        return $this;
    }

    /**
     * Use the SchoolTerm relation SchoolTerm object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\SchoolTerm\SchoolTermQuery A secondary query class using the current class as primary query
     */
    public function useSchoolTermQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSchoolTerm($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SchoolTerm', '\PGS\CoreDomainBundle\Model\SchoolTerm\SchoolTermQuery');
    }

    /**
     * Filter the query by a related SchoolGradeLevel object
     *
     * @param   SchoolGradeLevel|PropelObjectCollection $schoolGradeLevel The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolClassCourseQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolGradeLevel($schoolGradeLevel, $comparison = null)
    {
        if ($schoolGradeLevel instanceof SchoolGradeLevel) {
            return $this
                ->addUsingAlias(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, $schoolGradeLevel->getId(), $comparison);
        } elseif ($schoolGradeLevel instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, $schoolGradeLevel->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySchoolGradeLevel() only accepts arguments of type SchoolGradeLevel or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SchoolGradeLevel relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SchoolClassCourseQuery The current query, for fluid interface
     */
    public function joinSchoolGradeLevel($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SchoolGradeLevel');

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
            $this->addJoinObject($join, 'SchoolGradeLevel');
        }

        return $this;
    }

    /**
     * Use the SchoolGradeLevel relation SchoolGradeLevel object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\SchoolGradeLevel\SchoolGradeLevelQuery A secondary query class using the current class as primary query
     */
    public function useSchoolGradeLevelQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSchoolGradeLevel($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SchoolGradeLevel', '\PGS\CoreDomainBundle\Model\SchoolGradeLevel\SchoolGradeLevelQuery');
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolClassCourseQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPrimaryTeacher($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(SchoolClassCoursePeer::PRIMARY_TEACHER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SchoolClassCoursePeer::PRIMARY_TEACHER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPrimaryTeacher() only accepts arguments of type User or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PrimaryTeacher relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SchoolClassCourseQuery The current query, for fluid interface
     */
    public function joinPrimaryTeacher($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PrimaryTeacher');

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
            $this->addJoinObject($join, 'PrimaryTeacher');
        }

        return $this;
    }

    /**
     * Use the PrimaryTeacher relation User object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\UserQuery A secondary query class using the current class as primary query
     */
    public function usePrimaryTeacherQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPrimaryTeacher($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PrimaryTeacher', '\PGS\CoreDomainBundle\Model\UserQuery');
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolClassCourseQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySecondaryTeacher($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(SchoolClassCoursePeer::SECONDARY_TEACHER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SchoolClassCoursePeer::SECONDARY_TEACHER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySecondaryTeacher() only accepts arguments of type User or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SecondaryTeacher relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SchoolClassCourseQuery The current query, for fluid interface
     */
    public function joinSecondaryTeacher($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SecondaryTeacher');

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
            $this->addJoinObject($join, 'SecondaryTeacher');
        }

        return $this;
    }

    /**
     * Use the SecondaryTeacher relation User object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useSecondaryTeacherQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSecondaryTeacher($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SecondaryTeacher', '\PGS\CoreDomainBundle\Model\UserQuery');
    }

    /**
     * Filter the query by a related Formula object
     *
     * @param   Formula|PropelObjectCollection $formula The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolClassCourseQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFormula($formula, $comparison = null)
    {
        if ($formula instanceof Formula) {
            return $this
                ->addUsingAlias(SchoolClassCoursePeer::FORMULA_ID, $formula->getId(), $comparison);
        } elseif ($formula instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SchoolClassCoursePeer::FORMULA_ID, $formula->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByFormula() only accepts arguments of type Formula or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Formula relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SchoolClassCourseQuery The current query, for fluid interface
     */
    public function joinFormula($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Formula');

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
            $this->addJoinObject($join, 'Formula');
        }

        return $this;
    }

    /**
     * Use the Formula relation Formula object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\Formula\FormulaQuery A secondary query class using the current class as primary query
     */
    public function useFormulaQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFormula($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Formula', '\PGS\CoreDomainBundle\Model\Formula\FormulaQuery');
    }

    /**
     * Filter the query by a related SchoolClassCourseStudentBehavior object
     *
     * @param   SchoolClassCourseStudentBehavior|PropelObjectCollection $schoolClassCourseStudentBehavior  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolClassCourseQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolClassCourseStudentBehavior($schoolClassCourseStudentBehavior, $comparison = null)
    {
        if ($schoolClassCourseStudentBehavior instanceof SchoolClassCourseStudentBehavior) {
            return $this
                ->addUsingAlias(SchoolClassCoursePeer::ID, $schoolClassCourseStudentBehavior->getSchoolClassCourseId(), $comparison);
        } elseif ($schoolClassCourseStudentBehavior instanceof PropelObjectCollection) {
            return $this
                ->useSchoolClassCourseStudentBehaviorQuery()
                ->filterByPrimaryKeys($schoolClassCourseStudentBehavior->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySchoolClassCourseStudentBehavior() only accepts arguments of type SchoolClassCourseStudentBehavior or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SchoolClassCourseStudentBehavior relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SchoolClassCourseQuery The current query, for fluid interface
     */
    public function joinSchoolClassCourseStudentBehavior($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SchoolClassCourseStudentBehavior');

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
            $this->addJoinObject($join, 'SchoolClassCourseStudentBehavior');
        }

        return $this;
    }

    /**
     * Use the SchoolClassCourseStudentBehavior relation SchoolClassCourseStudentBehavior object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\SchoolClassCourseStudentBehavior\SchoolClassCourseStudentBehaviorQuery A secondary query class using the current class as primary query
     */
    public function useSchoolClassCourseStudentBehaviorQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSchoolClassCourseStudentBehavior($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SchoolClassCourseStudentBehavior', '\PGS\CoreDomainBundle\Model\SchoolClassCourseStudentBehavior\SchoolClassCourseStudentBehaviorQuery');
    }

    /**
     * Filter the query by a related Score object
     *
     * @param   Score|PropelObjectCollection $score  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolClassCourseQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByScore($score, $comparison = null)
    {
        if ($score instanceof Score) {
            return $this
                ->addUsingAlias(SchoolClassCoursePeer::ID, $score->getSchoolClassCourseId(), $comparison);
        } elseif ($score instanceof PropelObjectCollection) {
            return $this
                ->useScoreQuery()
                ->filterByPrimaryKeys($score->getPrimaryKeys())
                ->endUse();
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
     * @return SchoolClassCourseQuery The current query, for fluid interface
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
     * @param   SchoolClassCourse $schoolClassCourse Object to remove from the list of results
     *
     * @return SchoolClassCourseQuery The current query, for fluid interface
     */
    public function prune($schoolClassCourse = null)
    {
        if ($schoolClassCourse) {
            $this->addUsingAlias(SchoolClassCoursePeer::ID, $schoolClassCourse->getId(), Criteria::NOT_EQUAL);
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
     * @return     SchoolClassCourseQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(SchoolClassCoursePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     SchoolClassCourseQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(SchoolClassCoursePeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     SchoolClassCourseQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(SchoolClassCoursePeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     SchoolClassCourseQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(SchoolClassCoursePeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     SchoolClassCourseQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(SchoolClassCoursePeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     SchoolClassCourseQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(SchoolClassCoursePeer::CREATED_AT);
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
