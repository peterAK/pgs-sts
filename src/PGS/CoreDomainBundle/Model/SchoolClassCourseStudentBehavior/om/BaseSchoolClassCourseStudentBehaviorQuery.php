<?php

namespace PGS\CoreDomainBundle\Model\SchoolClassCourseStudentBehavior\om;

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
use PGS\CoreDomainBundle\Model\Behavior\Behavior;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourse;
use PGS\CoreDomainBundle\Model\SchoolClassCourseStudentBehavior\SchoolClassCourseStudentBehavior;
use PGS\CoreDomainBundle\Model\SchoolClassCourseStudentBehavior\SchoolClassCourseStudentBehaviorPeer;
use PGS\CoreDomainBundle\Model\SchoolClassCourseStudentBehavior\SchoolClassCourseStudentBehaviorQuery;
use PGS\CoreDomainBundle\Model\Student\Student;

/**
 * @method SchoolClassCourseStudentBehaviorQuery orderById($order = Criteria::ASC) Order by the id column
 * @method SchoolClassCourseStudentBehaviorQuery orderByStudentId($order = Criteria::ASC) Order by the student_id column
 * @method SchoolClassCourseStudentBehaviorQuery orderByBehaviorId($order = Criteria::ASC) Order by the behavior_id column
 * @method SchoolClassCourseStudentBehaviorQuery orderBySchoolClassCourseId($order = Criteria::ASC) Order by the school_class_course_id column
 * @method SchoolClassCourseStudentBehaviorQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method SchoolClassCourseStudentBehaviorQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method SchoolClassCourseStudentBehaviorQuery groupById() Group by the id column
 * @method SchoolClassCourseStudentBehaviorQuery groupByStudentId() Group by the student_id column
 * @method SchoolClassCourseStudentBehaviorQuery groupByBehaviorId() Group by the behavior_id column
 * @method SchoolClassCourseStudentBehaviorQuery groupBySchoolClassCourseId() Group by the school_class_course_id column
 * @method SchoolClassCourseStudentBehaviorQuery groupByCreatedAt() Group by the created_at column
 * @method SchoolClassCourseStudentBehaviorQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method SchoolClassCourseStudentBehaviorQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method SchoolClassCourseStudentBehaviorQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method SchoolClassCourseStudentBehaviorQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method SchoolClassCourseStudentBehaviorQuery leftJoinStudent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Student relation
 * @method SchoolClassCourseStudentBehaviorQuery rightJoinStudent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Student relation
 * @method SchoolClassCourseStudentBehaviorQuery innerJoinStudent($relationAlias = null) Adds a INNER JOIN clause to the query using the Student relation
 *
 * @method SchoolClassCourseStudentBehaviorQuery leftJoinBehavior($relationAlias = null) Adds a LEFT JOIN clause to the query using the Behavior relation
 * @method SchoolClassCourseStudentBehaviorQuery rightJoinBehavior($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Behavior relation
 * @method SchoolClassCourseStudentBehaviorQuery innerJoinBehavior($relationAlias = null) Adds a INNER JOIN clause to the query using the Behavior relation
 *
 * @method SchoolClassCourseStudentBehaviorQuery leftJoinSchoolClassCourse($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolClassCourse relation
 * @method SchoolClassCourseStudentBehaviorQuery rightJoinSchoolClassCourse($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolClassCourse relation
 * @method SchoolClassCourseStudentBehaviorQuery innerJoinSchoolClassCourse($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolClassCourse relation
 *
 * @method SchoolClassCourseStudentBehavior findOne(PropelPDO $con = null) Return the first SchoolClassCourseStudentBehavior matching the query
 * @method SchoolClassCourseStudentBehavior findOneOrCreate(PropelPDO $con = null) Return the first SchoolClassCourseStudentBehavior matching the query, or a new SchoolClassCourseStudentBehavior object populated from the query conditions when no match is found
 *
 * @method SchoolClassCourseStudentBehavior findOneByStudentId(int $student_id) Return the first SchoolClassCourseStudentBehavior filtered by the student_id column
 * @method SchoolClassCourseStudentBehavior findOneByBehaviorId(int $behavior_id) Return the first SchoolClassCourseStudentBehavior filtered by the behavior_id column
 * @method SchoolClassCourseStudentBehavior findOneBySchoolClassCourseId(int $school_class_course_id) Return the first SchoolClassCourseStudentBehavior filtered by the school_class_course_id column
 * @method SchoolClassCourseStudentBehavior findOneByCreatedAt(string $created_at) Return the first SchoolClassCourseStudentBehavior filtered by the created_at column
 * @method SchoolClassCourseStudentBehavior findOneByUpdatedAt(string $updated_at) Return the first SchoolClassCourseStudentBehavior filtered by the updated_at column
 *
 * @method array findById(int $id) Return SchoolClassCourseStudentBehavior objects filtered by the id column
 * @method array findByStudentId(int $student_id) Return SchoolClassCourseStudentBehavior objects filtered by the student_id column
 * @method array findByBehaviorId(int $behavior_id) Return SchoolClassCourseStudentBehavior objects filtered by the behavior_id column
 * @method array findBySchoolClassCourseId(int $school_class_course_id) Return SchoolClassCourseStudentBehavior objects filtered by the school_class_course_id column
 * @method array findByCreatedAt(string $created_at) Return SchoolClassCourseStudentBehavior objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return SchoolClassCourseStudentBehavior objects filtered by the updated_at column
 */
abstract class BaseSchoolClassCourseStudentBehaviorQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseSchoolClassCourseStudentBehaviorQuery object.
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
            $modelName = 'PGS\\CoreDomainBundle\\Model\\SchoolClassCourseStudentBehavior\\SchoolClassCourseStudentBehavior';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new SchoolClassCourseStudentBehaviorQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   SchoolClassCourseStudentBehaviorQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return SchoolClassCourseStudentBehaviorQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof SchoolClassCourseStudentBehaviorQuery) {
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
     * @return   SchoolClassCourseStudentBehavior|SchoolClassCourseStudentBehavior[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SchoolClassCourseStudentBehaviorPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCourseStudentBehaviorPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 SchoolClassCourseStudentBehavior A model object, or null if the key is not found
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
     * @return                 SchoolClassCourseStudentBehavior A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `student_id`, `behavior_id`, `school_class_course_id`, `created_at`, `updated_at` FROM `school_class_course_student_behavior` WHERE `id` = :p0';
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
            $cls = SchoolClassCourseStudentBehaviorPeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            SchoolClassCourseStudentBehaviorPeer::addInstanceToPool($obj, (string) $key);
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
     * @return SchoolClassCourseStudentBehavior|SchoolClassCourseStudentBehavior[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|SchoolClassCourseStudentBehavior[]|mixed the list of results, formatted by the current formatter
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
     * @return SchoolClassCourseStudentBehaviorQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return SchoolClassCourseStudentBehaviorQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::ID, $keys, Criteria::IN);
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
     * @return SchoolClassCourseStudentBehaviorQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::ID, $id, $comparison);
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
     * @see       filterByStudent()
     *
     * @param     mixed $studentId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolClassCourseStudentBehaviorQuery The current query, for fluid interface
     */
    public function filterByStudentId($studentId = null, $comparison = null)
    {
        if (is_array($studentId)) {
            $useMinMax = false;
            if (isset($studentId['min'])) {
                $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::STUDENT_ID, $studentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($studentId['max'])) {
                $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::STUDENT_ID, $studentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::STUDENT_ID, $studentId, $comparison);
    }

    /**
     * Filter the query on the behavior_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBehaviorId(1234); // WHERE behavior_id = 1234
     * $query->filterByBehaviorId(array(12, 34)); // WHERE behavior_id IN (12, 34)
     * $query->filterByBehaviorId(array('min' => 12)); // WHERE behavior_id >= 12
     * $query->filterByBehaviorId(array('max' => 12)); // WHERE behavior_id <= 12
     * </code>
     *
     * @see       filterByBehavior()
     *
     * @param     mixed $behaviorId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolClassCourseStudentBehaviorQuery The current query, for fluid interface
     */
    public function filterByBehaviorId($behaviorId = null, $comparison = null)
    {
        if (is_array($behaviorId)) {
            $useMinMax = false;
            if (isset($behaviorId['min'])) {
                $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::BEHAVIOR_ID, $behaviorId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($behaviorId['max'])) {
                $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::BEHAVIOR_ID, $behaviorId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::BEHAVIOR_ID, $behaviorId, $comparison);
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
     * @return SchoolClassCourseStudentBehaviorQuery The current query, for fluid interface
     */
    public function filterBySchoolClassCourseId($schoolClassCourseId = null, $comparison = null)
    {
        if (is_array($schoolClassCourseId)) {
            $useMinMax = false;
            if (isset($schoolClassCourseId['min'])) {
                $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::SCHOOL_CLASS_COURSE_ID, $schoolClassCourseId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($schoolClassCourseId['max'])) {
                $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::SCHOOL_CLASS_COURSE_ID, $schoolClassCourseId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::SCHOOL_CLASS_COURSE_ID, $schoolClassCourseId, $comparison);
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
     * @return SchoolClassCourseStudentBehaviorQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return SchoolClassCourseStudentBehaviorQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Student object
     *
     * @param   Student|PropelObjectCollection $student The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolClassCourseStudentBehaviorQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStudent($student, $comparison = null)
    {
        if ($student instanceof Student) {
            return $this
                ->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::STUDENT_ID, $student->getId(), $comparison);
        } elseif ($student instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::STUDENT_ID, $student->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByStudent() only accepts arguments of type Student or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Student relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SchoolClassCourseStudentBehaviorQuery The current query, for fluid interface
     */
    public function joinStudent($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Student');

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
            $this->addJoinObject($join, 'Student');
        }

        return $this;
    }

    /**
     * Use the Student relation Student object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\Student\StudentQuery A secondary query class using the current class as primary query
     */
    public function useStudentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinStudent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Student', '\PGS\CoreDomainBundle\Model\Student\StudentQuery');
    }

    /**
     * Filter the query by a related Behavior object
     *
     * @param   Behavior|PropelObjectCollection $behavior The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolClassCourseStudentBehaviorQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByBehavior($behavior, $comparison = null)
    {
        if ($behavior instanceof Behavior) {
            return $this
                ->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::BEHAVIOR_ID, $behavior->getId(), $comparison);
        } elseif ($behavior instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::BEHAVIOR_ID, $behavior->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByBehavior() only accepts arguments of type Behavior or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Behavior relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SchoolClassCourseStudentBehaviorQuery The current query, for fluid interface
     */
    public function joinBehavior($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Behavior');

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
            $this->addJoinObject($join, 'Behavior');
        }

        return $this;
    }

    /**
     * Use the Behavior relation Behavior object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\Behavior\BehaviorQuery A secondary query class using the current class as primary query
     */
    public function useBehaviorQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinBehavior($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Behavior', '\PGS\CoreDomainBundle\Model\Behavior\BehaviorQuery');
    }

    /**
     * Filter the query by a related SchoolClassCourse object
     *
     * @param   SchoolClassCourse|PropelObjectCollection $schoolClassCourse The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolClassCourseStudentBehaviorQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolClassCourse($schoolClassCourse, $comparison = null)
    {
        if ($schoolClassCourse instanceof SchoolClassCourse) {
            return $this
                ->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::SCHOOL_CLASS_COURSE_ID, $schoolClassCourse->getId(), $comparison);
        } elseif ($schoolClassCourse instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::SCHOOL_CLASS_COURSE_ID, $schoolClassCourse->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return SchoolClassCourseStudentBehaviorQuery The current query, for fluid interface
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
     * @param   SchoolClassCourseStudentBehavior $schoolClassCourseStudentBehavior Object to remove from the list of results
     *
     * @return SchoolClassCourseStudentBehaviorQuery The current query, for fluid interface
     */
    public function prune($schoolClassCourseStudentBehavior = null)
    {
        if ($schoolClassCourseStudentBehavior) {
            $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::ID, $schoolClassCourseStudentBehavior->getId(), Criteria::NOT_EQUAL);
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
     * @return     SchoolClassCourseStudentBehaviorQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     SchoolClassCourseStudentBehaviorQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(SchoolClassCourseStudentBehaviorPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     SchoolClassCourseStudentBehaviorQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(SchoolClassCourseStudentBehaviorPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     SchoolClassCourseStudentBehaviorQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(SchoolClassCourseStudentBehaviorPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     SchoolClassCourseStudentBehaviorQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(SchoolClassCourseStudentBehaviorPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     SchoolClassCourseStudentBehaviorQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(SchoolClassCourseStudentBehaviorPeer::CREATED_AT);
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