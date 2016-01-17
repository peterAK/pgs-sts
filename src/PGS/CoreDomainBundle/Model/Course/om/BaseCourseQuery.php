<?php

namespace PGS\CoreDomainBundle\Model\Course\om;

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
use PGS\CoreDomainBundle\Model\Course\Course;
use PGS\CoreDomainBundle\Model\Course\CoursePeer;
use PGS\CoreDomainBundle\Model\Course\CourseQuery;
use PGS\CoreDomainBundle\Model\CourseObjective\CourseObjective;
use PGS\CoreDomainBundle\Model\GradeLevel\GradeLevel;
use PGS\CoreDomainBundle\Model\Qualification\Qualification;
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourse;
use PGS\CoreDomainBundle\Model\Subject\Subject;

/**
 * @method CourseQuery orderById($order = Criteria::ASC) Order by the id column
 * @method CourseQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method CourseQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method CourseQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method CourseQuery orderBySchoolId($order = Criteria::ASC) Order by the school_id column
 * @method CourseQuery orderBySubjectId($order = Criteria::ASC) Order by the subject_id column
 * @method CourseQuery orderByGradeLevelId($order = Criteria::ASC) Order by the grade_level_id column
 * @method CourseQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 * @method CourseQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method CourseQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method CourseQuery groupById() Group by the id column
 * @method CourseQuery groupByCode() Group by the code column
 * @method CourseQuery groupByName() Group by the name column
 * @method CourseQuery groupByDescription() Group by the description column
 * @method CourseQuery groupBySchoolId() Group by the school_id column
 * @method CourseQuery groupBySubjectId() Group by the subject_id column
 * @method CourseQuery groupByGradeLevelId() Group by the grade_level_id column
 * @method CourseQuery groupBySortableRank() Group by the sortable_rank column
 * @method CourseQuery groupByCreatedAt() Group by the created_at column
 * @method CourseQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method CourseQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method CourseQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method CourseQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method CourseQuery leftJoinSchool($relationAlias = null) Adds a LEFT JOIN clause to the query using the School relation
 * @method CourseQuery rightJoinSchool($relationAlias = null) Adds a RIGHT JOIN clause to the query using the School relation
 * @method CourseQuery innerJoinSchool($relationAlias = null) Adds a INNER JOIN clause to the query using the School relation
 *
 * @method CourseQuery leftJoinSubject($relationAlias = null) Adds a LEFT JOIN clause to the query using the Subject relation
 * @method CourseQuery rightJoinSubject($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Subject relation
 * @method CourseQuery innerJoinSubject($relationAlias = null) Adds a INNER JOIN clause to the query using the Subject relation
 *
 * @method CourseQuery leftJoinGradeLevel($relationAlias = null) Adds a LEFT JOIN clause to the query using the GradeLevel relation
 * @method CourseQuery rightJoinGradeLevel($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GradeLevel relation
 * @method CourseQuery innerJoinGradeLevel($relationAlias = null) Adds a INNER JOIN clause to the query using the GradeLevel relation
 *
 * @method CourseQuery leftJoinCourseObjective($relationAlias = null) Adds a LEFT JOIN clause to the query using the CourseObjective relation
 * @method CourseQuery rightJoinCourseObjective($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CourseObjective relation
 * @method CourseQuery innerJoinCourseObjective($relationAlias = null) Adds a INNER JOIN clause to the query using the CourseObjective relation
 *
 * @method CourseQuery leftJoinQualification($relationAlias = null) Adds a LEFT JOIN clause to the query using the Qualification relation
 * @method CourseQuery rightJoinQualification($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Qualification relation
 * @method CourseQuery innerJoinQualification($relationAlias = null) Adds a INNER JOIN clause to the query using the Qualification relation
 *
 * @method CourseQuery leftJoinSchoolClassCourse($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolClassCourse relation
 * @method CourseQuery rightJoinSchoolClassCourse($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolClassCourse relation
 * @method CourseQuery innerJoinSchoolClassCourse($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolClassCourse relation
 *
 * @method Course findOne(PropelPDO $con = null) Return the first Course matching the query
 * @method Course findOneOrCreate(PropelPDO $con = null) Return the first Course matching the query, or a new Course object populated from the query conditions when no match is found
 *
 * @method Course findOneByCode(string $code) Return the first Course filtered by the code column
 * @method Course findOneByName(string $name) Return the first Course filtered by the name column
 * @method Course findOneByDescription(string $description) Return the first Course filtered by the description column
 * @method Course findOneBySchoolId(int $school_id) Return the first Course filtered by the school_id column
 * @method Course findOneBySubjectId(int $subject_id) Return the first Course filtered by the subject_id column
 * @method Course findOneByGradeLevelId(int $grade_level_id) Return the first Course filtered by the grade_level_id column
 * @method Course findOneBySortableRank(int $sortable_rank) Return the first Course filtered by the sortable_rank column
 * @method Course findOneByCreatedAt(string $created_at) Return the first Course filtered by the created_at column
 * @method Course findOneByUpdatedAt(string $updated_at) Return the first Course filtered by the updated_at column
 *
 * @method array findById(int $id) Return Course objects filtered by the id column
 * @method array findByCode(string $code) Return Course objects filtered by the code column
 * @method array findByName(string $name) Return Course objects filtered by the name column
 * @method array findByDescription(string $description) Return Course objects filtered by the description column
 * @method array findBySchoolId(int $school_id) Return Course objects filtered by the school_id column
 * @method array findBySubjectId(int $subject_id) Return Course objects filtered by the subject_id column
 * @method array findByGradeLevelId(int $grade_level_id) Return Course objects filtered by the grade_level_id column
 * @method array findBySortableRank(int $sortable_rank) Return Course objects filtered by the sortable_rank column
 * @method array findByCreatedAt(string $created_at) Return Course objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Course objects filtered by the updated_at column
 */
abstract class BaseCourseQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseCourseQuery object.
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
            $modelName = 'PGS\\CoreDomainBundle\\Model\\Course\\Course';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new CourseQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   CourseQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return CourseQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CourseQuery) {
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
     * @return   Course|Course[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CoursePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(CoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Course A model object, or null if the key is not found
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
     * @return                 Course A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `code`, `name`, `description`, `school_id`, `subject_id`, `grade_level_id`, `sortable_rank`, `created_at`, `updated_at` FROM `course` WHERE `id` = :p0';
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
            $cls = CoursePeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            CoursePeer::addInstanceToPool($obj, (string) $key);
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
     * @return Course|Course[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Course[]|mixed the list of results, formatted by the current formatter
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
     * @return CourseQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CoursePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CoursePeer::ID, $keys, Criteria::IN);
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
     * @return CourseQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CoursePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CoursePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CoursePeer::ID, $id, $comparison);
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
     * @return CourseQuery The current query, for fluid interface
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

        return $this->addUsingAlias(CoursePeer::CODE, $code, $comparison);
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
     * @return CourseQuery The current query, for fluid interface
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

        return $this->addUsingAlias(CoursePeer::NAME, $name, $comparison);
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
     * @return CourseQuery The current query, for fluid interface
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

        return $this->addUsingAlias(CoursePeer::DESCRIPTION, $description, $comparison);
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
     * @return CourseQuery The current query, for fluid interface
     */
    public function filterBySchoolId($schoolId = null, $comparison = null)
    {
        if (is_array($schoolId)) {
            $useMinMax = false;
            if (isset($schoolId['min'])) {
                $this->addUsingAlias(CoursePeer::SCHOOL_ID, $schoolId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($schoolId['max'])) {
                $this->addUsingAlias(CoursePeer::SCHOOL_ID, $schoolId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CoursePeer::SCHOOL_ID, $schoolId, $comparison);
    }

    /**
     * Filter the query on the subject_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySubjectId(1234); // WHERE subject_id = 1234
     * $query->filterBySubjectId(array(12, 34)); // WHERE subject_id IN (12, 34)
     * $query->filterBySubjectId(array('min' => 12)); // WHERE subject_id >= 12
     * $query->filterBySubjectId(array('max' => 12)); // WHERE subject_id <= 12
     * </code>
     *
     * @see       filterBySubject()
     *
     * @param     mixed $subjectId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function filterBySubjectId($subjectId = null, $comparison = null)
    {
        if (is_array($subjectId)) {
            $useMinMax = false;
            if (isset($subjectId['min'])) {
                $this->addUsingAlias(CoursePeer::SUBJECT_ID, $subjectId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($subjectId['max'])) {
                $this->addUsingAlias(CoursePeer::SUBJECT_ID, $subjectId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CoursePeer::SUBJECT_ID, $subjectId, $comparison);
    }

    /**
     * Filter the query on the grade_level_id column
     *
     * Example usage:
     * <code>
     * $query->filterByGradeLevelId(1234); // WHERE grade_level_id = 1234
     * $query->filterByGradeLevelId(array(12, 34)); // WHERE grade_level_id IN (12, 34)
     * $query->filterByGradeLevelId(array('min' => 12)); // WHERE grade_level_id >= 12
     * $query->filterByGradeLevelId(array('max' => 12)); // WHERE grade_level_id <= 12
     * </code>
     *
     * @see       filterByGradeLevel()
     *
     * @param     mixed $gradeLevelId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function filterByGradeLevelId($gradeLevelId = null, $comparison = null)
    {
        if (is_array($gradeLevelId)) {
            $useMinMax = false;
            if (isset($gradeLevelId['min'])) {
                $this->addUsingAlias(CoursePeer::GRADE_LEVEL_ID, $gradeLevelId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($gradeLevelId['max'])) {
                $this->addUsingAlias(CoursePeer::GRADE_LEVEL_ID, $gradeLevelId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CoursePeer::GRADE_LEVEL_ID, $gradeLevelId, $comparison);
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
     * @return CourseQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(CoursePeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(CoursePeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CoursePeer::SORTABLE_RANK, $sortableRank, $comparison);
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
     * @return CourseQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(CoursePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CoursePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CoursePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return CourseQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(CoursePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CoursePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CoursePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related School object
     *
     * @param   School|PropelObjectCollection $school The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CourseQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchool($school, $comparison = null)
    {
        if ($school instanceof School) {
            return $this
                ->addUsingAlias(CoursePeer::SCHOOL_ID, $school->getId(), $comparison);
        } elseif ($school instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CoursePeer::SCHOOL_ID, $school->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return CourseQuery The current query, for fluid interface
     */
    public function joinSchool($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useSchoolQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSchool($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'School', '\PGS\CoreDomainBundle\Model\School\SchoolQuery');
    }

    /**
     * Filter the query by a related Subject object
     *
     * @param   Subject|PropelObjectCollection $subject The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CourseQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySubject($subject, $comparison = null)
    {
        if ($subject instanceof Subject) {
            return $this
                ->addUsingAlias(CoursePeer::SUBJECT_ID, $subject->getId(), $comparison);
        } elseif ($subject instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CoursePeer::SUBJECT_ID, $subject->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySubject() only accepts arguments of type Subject or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Subject relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function joinSubject($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Subject');

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
            $this->addJoinObject($join, 'Subject');
        }

        return $this;
    }

    /**
     * Use the Subject relation Subject object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\Subject\SubjectQuery A secondary query class using the current class as primary query
     */
    public function useSubjectQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSubject($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Subject', '\PGS\CoreDomainBundle\Model\Subject\SubjectQuery');
    }

    /**
     * Filter the query by a related GradeLevel object
     *
     * @param   GradeLevel|PropelObjectCollection $gradeLevel The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CourseQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByGradeLevel($gradeLevel, $comparison = null)
    {
        if ($gradeLevel instanceof GradeLevel) {
            return $this
                ->addUsingAlias(CoursePeer::GRADE_LEVEL_ID, $gradeLevel->getId(), $comparison);
        } elseif ($gradeLevel instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CoursePeer::GRADE_LEVEL_ID, $gradeLevel->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByGradeLevel() only accepts arguments of type GradeLevel or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the GradeLevel relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function joinGradeLevel($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('GradeLevel');

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
            $this->addJoinObject($join, 'GradeLevel');
        }

        return $this;
    }

    /**
     * Use the GradeLevel relation GradeLevel object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\GradeLevel\GradeLevelQuery A secondary query class using the current class as primary query
     */
    public function useGradeLevelQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinGradeLevel($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'GradeLevel', '\PGS\CoreDomainBundle\Model\GradeLevel\GradeLevelQuery');
    }

    /**
     * Filter the query by a related CourseObjective object
     *
     * @param   CourseObjective|PropelObjectCollection $courseObjective  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CourseQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCourseObjective($courseObjective, $comparison = null)
    {
        if ($courseObjective instanceof CourseObjective) {
            return $this
                ->addUsingAlias(CoursePeer::ID, $courseObjective->getCourseId(), $comparison);
        } elseif ($courseObjective instanceof PropelObjectCollection) {
            return $this
                ->useCourseObjectiveQuery()
                ->filterByPrimaryKeys($courseObjective->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCourseObjective() only accepts arguments of type CourseObjective or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CourseObjective relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function joinCourseObjective($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CourseObjective');

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
            $this->addJoinObject($join, 'CourseObjective');
        }

        return $this;
    }

    /**
     * Use the CourseObjective relation CourseObjective object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\CourseObjective\CourseObjectiveQuery A secondary query class using the current class as primary query
     */
    public function useCourseObjectiveQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCourseObjective($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CourseObjective', '\PGS\CoreDomainBundle\Model\CourseObjective\CourseObjectiveQuery');
    }

    /**
     * Filter the query by a related Qualification object
     *
     * @param   Qualification|PropelObjectCollection $qualification  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CourseQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByQualification($qualification, $comparison = null)
    {
        if ($qualification instanceof Qualification) {
            return $this
                ->addUsingAlias(CoursePeer::ID, $qualification->getCourseId(), $comparison);
        } elseif ($qualification instanceof PropelObjectCollection) {
            return $this
                ->useQualificationQuery()
                ->filterByPrimaryKeys($qualification->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByQualification() only accepts arguments of type Qualification or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Qualification relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function joinQualification($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Qualification');

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
            $this->addJoinObject($join, 'Qualification');
        }

        return $this;
    }

    /**
     * Use the Qualification relation Qualification object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\Qualification\QualificationQuery A secondary query class using the current class as primary query
     */
    public function useQualificationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinQualification($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Qualification', '\PGS\CoreDomainBundle\Model\Qualification\QualificationQuery');
    }

    /**
     * Filter the query by a related SchoolClassCourse object
     *
     * @param   SchoolClassCourse|PropelObjectCollection $schoolClassCourse  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CourseQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolClassCourse($schoolClassCourse, $comparison = null)
    {
        if ($schoolClassCourse instanceof SchoolClassCourse) {
            return $this
                ->addUsingAlias(CoursePeer::ID, $schoolClassCourse->getCourseId(), $comparison);
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
     * @return CourseQuery The current query, for fluid interface
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
     * @param   Course $course Object to remove from the list of results
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function prune($course = null)
    {
        if ($course) {
            $this->addUsingAlias(CoursePeer::ID, $course->getId(), Criteria::NOT_EQUAL);
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
     * @return CourseQuery The current query, for fluid interface
     */
    public function inList($scope = null)
    {

        CoursePeer::sortableApplyScopeCriteria($this, $scope, 'addUsingAlias');

        return $this;
    }

    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     * @param int $scope Scope to determine which objects node to return

     *
     * @return    CourseQuery The current query, for fluid interface
     */
    public function filterByRank($rank, $scope = null)
    {


        return $this
            ->inList($scope)
            ->addUsingAlias(CoursePeer::RANK_COL, $rank, Criteria::EQUAL);
    }

    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    CourseQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(CoursePeer::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(CoursePeer::RANK_COL));
                break;
            default:
                throw new PropelException('CourseQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param int $scope Scope to determine which objects node to return
     * @param     PropelPDO $con optional connection
     *
     * @return    Course
     */
    public function findOneByRank($rank, $scope = null, PropelPDO $con = null)
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
    public function findList($scope = null, $con = null)
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
    public function getMaxRank($scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(CoursePeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . CoursePeer::RANK_COL . ')');

        CoursePeer::sortableApplyScopeCriteria($this, $scope);
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
            $con = Propel::getConnection(CoursePeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . CoursePeer::RANK_COL . ')');
        CoursePeer::sortableApplyScopeCriteria($this, $scope);
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
            $con = Propel::getConnection(CoursePeer::DATABASE_NAME);
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
     * @return     CourseQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(CoursePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     CourseQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(CoursePeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     CourseQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(CoursePeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     CourseQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(CoursePeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     CourseQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(CoursePeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     CourseQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(CoursePeer::CREATED_AT);
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
