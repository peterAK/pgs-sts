<?php

namespace PGS\CoreDomainBundle\Model\SchoolClass\om;

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
use PGS\CoreDomainBundle\Model\GradeLevel\GradeLevel;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClass;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClassI18n;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClassPeer;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClassQuery;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourse;
use PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudent;
use PGS\CoreDomainBundle\Model\StudentReport\StudentReport;

/**
 * @method SchoolClassQuery orderById($order = Criteria::ASC) Order by the id column
 * @method SchoolClassQuery orderByGradeLevelId($order = Criteria::ASC) Order by the grade_level_id column
 * @method SchoolClassQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method SchoolClassQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method SchoolClassQuery groupById() Group by the id column
 * @method SchoolClassQuery groupByGradeLevelId() Group by the grade_level_id column
 * @method SchoolClassQuery groupByCreatedAt() Group by the created_at column
 * @method SchoolClassQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method SchoolClassQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method SchoolClassQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method SchoolClassQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method SchoolClassQuery leftJoinGradeLevel($relationAlias = null) Adds a LEFT JOIN clause to the query using the GradeLevel relation
 * @method SchoolClassQuery rightJoinGradeLevel($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GradeLevel relation
 * @method SchoolClassQuery innerJoinGradeLevel($relationAlias = null) Adds a INNER JOIN clause to the query using the GradeLevel relation
 *
 * @method SchoolClassQuery leftJoinSchoolClassCourse($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolClassCourse relation
 * @method SchoolClassQuery rightJoinSchoolClassCourse($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolClassCourse relation
 * @method SchoolClassQuery innerJoinSchoolClassCourse($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolClassCourse relation
 *
 * @method SchoolClassQuery leftJoinSchoolClassStudent($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolClassStudent relation
 * @method SchoolClassQuery rightJoinSchoolClassStudent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolClassStudent relation
 * @method SchoolClassQuery innerJoinSchoolClassStudent($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolClassStudent relation
 *
 * @method SchoolClassQuery leftJoinStudentReport($relationAlias = null) Adds a LEFT JOIN clause to the query using the StudentReport relation
 * @method SchoolClassQuery rightJoinStudentReport($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StudentReport relation
 * @method SchoolClassQuery innerJoinStudentReport($relationAlias = null) Adds a INNER JOIN clause to the query using the StudentReport relation
 *
 * @method SchoolClassQuery leftJoinSchoolClassI18n($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolClassI18n relation
 * @method SchoolClassQuery rightJoinSchoolClassI18n($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolClassI18n relation
 * @method SchoolClassQuery innerJoinSchoolClassI18n($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolClassI18n relation
 *
 * @method SchoolClass findOne(PropelPDO $con = null) Return the first SchoolClass matching the query
 * @method SchoolClass findOneOrCreate(PropelPDO $con = null) Return the first SchoolClass matching the query, or a new SchoolClass object populated from the query conditions when no match is found
 *
 * @method SchoolClass findOneByGradeLevelId(int $grade_level_id) Return the first SchoolClass filtered by the grade_level_id column
 * @method SchoolClass findOneByCreatedAt(string $created_at) Return the first SchoolClass filtered by the created_at column
 * @method SchoolClass findOneByUpdatedAt(string $updated_at) Return the first SchoolClass filtered by the updated_at column
 *
 * @method array findById(int $id) Return SchoolClass objects filtered by the id column
 * @method array findByGradeLevelId(int $grade_level_id) Return SchoolClass objects filtered by the grade_level_id column
 * @method array findByCreatedAt(string $created_at) Return SchoolClass objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return SchoolClass objects filtered by the updated_at column
 */
abstract class BaseSchoolClassQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseSchoolClassQuery object.
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
            $modelName = 'PGS\\CoreDomainBundle\\Model\\SchoolClass\\SchoolClass';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new SchoolClassQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   SchoolClassQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return SchoolClassQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof SchoolClassQuery) {
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
     * @return   SchoolClass|SchoolClass[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SchoolClassPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(SchoolClassPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 SchoolClass A model object, or null if the key is not found
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
     * @return                 SchoolClass A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `grade_level_id`, `created_at`, `updated_at` FROM `school_class` WHERE `id` = :p0';
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
            $cls = SchoolClassPeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            SchoolClassPeer::addInstanceToPool($obj, (string) $key);
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
     * @return SchoolClass|SchoolClass[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|SchoolClass[]|mixed the list of results, formatted by the current formatter
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
     * @return SchoolClassQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SchoolClassPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return SchoolClassQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SchoolClassPeer::ID, $keys, Criteria::IN);
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
     * @return SchoolClassQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SchoolClassPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SchoolClassPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolClassPeer::ID, $id, $comparison);
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
     * @return SchoolClassQuery The current query, for fluid interface
     */
    public function filterByGradeLevelId($gradeLevelId = null, $comparison = null)
    {
        if (is_array($gradeLevelId)) {
            $useMinMax = false;
            if (isset($gradeLevelId['min'])) {
                $this->addUsingAlias(SchoolClassPeer::GRADE_LEVEL_ID, $gradeLevelId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($gradeLevelId['max'])) {
                $this->addUsingAlias(SchoolClassPeer::GRADE_LEVEL_ID, $gradeLevelId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolClassPeer::GRADE_LEVEL_ID, $gradeLevelId, $comparison);
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
     * @return SchoolClassQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(SchoolClassPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(SchoolClassPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolClassPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return SchoolClassQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(SchoolClassPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(SchoolClassPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolClassPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related GradeLevel object
     *
     * @param   GradeLevel|PropelObjectCollection $gradeLevel The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolClassQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByGradeLevel($gradeLevel, $comparison = null)
    {
        if ($gradeLevel instanceof GradeLevel) {
            return $this
                ->addUsingAlias(SchoolClassPeer::GRADE_LEVEL_ID, $gradeLevel->getId(), $comparison);
        } elseif ($gradeLevel instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SchoolClassPeer::GRADE_LEVEL_ID, $gradeLevel->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return SchoolClassQuery The current query, for fluid interface
     */
    public function joinGradeLevel($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useGradeLevelQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinGradeLevel($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'GradeLevel', '\PGS\CoreDomainBundle\Model\GradeLevel\GradeLevelQuery');
    }

    /**
     * Filter the query by a related SchoolClassCourse object
     *
     * @param   SchoolClassCourse|PropelObjectCollection $schoolClassCourse  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolClassQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolClassCourse($schoolClassCourse, $comparison = null)
    {
        if ($schoolClassCourse instanceof SchoolClassCourse) {
            return $this
                ->addUsingAlias(SchoolClassPeer::ID, $schoolClassCourse->getSchoolClassId(), $comparison);
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
     * @return SchoolClassQuery The current query, for fluid interface
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
     * Filter the query by a related SchoolClassStudent object
     *
     * @param   SchoolClassStudent|PropelObjectCollection $schoolClassStudent  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolClassQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolClassStudent($schoolClassStudent, $comparison = null)
    {
        if ($schoolClassStudent instanceof SchoolClassStudent) {
            return $this
                ->addUsingAlias(SchoolClassPeer::ID, $schoolClassStudent->getSchoolClassId(), $comparison);
        } elseif ($schoolClassStudent instanceof PropelObjectCollection) {
            return $this
                ->useSchoolClassStudentQuery()
                ->filterByPrimaryKeys($schoolClassStudent->getPrimaryKeys())
                ->endUse();
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
     * @return SchoolClassQuery The current query, for fluid interface
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
     * Filter the query by a related StudentReport object
     *
     * @param   StudentReport|PropelObjectCollection $studentReport  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolClassQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStudentReport($studentReport, $comparison = null)
    {
        if ($studentReport instanceof StudentReport) {
            return $this
                ->addUsingAlias(SchoolClassPeer::ID, $studentReport->getSchoolClassId(), $comparison);
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
     * @return SchoolClassQuery The current query, for fluid interface
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
     * Filter the query by a related SchoolClassI18n object
     *
     * @param   SchoolClassI18n|PropelObjectCollection $schoolClassI18n  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolClassQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolClassI18n($schoolClassI18n, $comparison = null)
    {
        if ($schoolClassI18n instanceof SchoolClassI18n) {
            return $this
                ->addUsingAlias(SchoolClassPeer::ID, $schoolClassI18n->getId(), $comparison);
        } elseif ($schoolClassI18n instanceof PropelObjectCollection) {
            return $this
                ->useSchoolClassI18nQuery()
                ->filterByPrimaryKeys($schoolClassI18n->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySchoolClassI18n() only accepts arguments of type SchoolClassI18n or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SchoolClassI18n relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SchoolClassQuery The current query, for fluid interface
     */
    public function joinSchoolClassI18n($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SchoolClassI18n');

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
            $this->addJoinObject($join, 'SchoolClassI18n');
        }

        return $this;
    }

    /**
     * Use the SchoolClassI18n relation SchoolClassI18n object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\SchoolClass\SchoolClassI18nQuery A secondary query class using the current class as primary query
     */
    public function useSchoolClassI18nQuery($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        return $this
            ->joinSchoolClassI18n($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SchoolClassI18n', '\PGS\CoreDomainBundle\Model\SchoolClass\SchoolClassI18nQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   SchoolClass $schoolClass Object to remove from the list of results
     *
     * @return SchoolClassQuery The current query, for fluid interface
     */
    public function prune($schoolClass = null)
    {
        if ($schoolClass) {
            $this->addUsingAlias(SchoolClassPeer::ID, $schoolClass->getId(), Criteria::NOT_EQUAL);
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

    // i18n behavior

    /**
     * Adds a JOIN clause to the query using the i18n relation
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    SchoolClassQuery The current query, for fluid interface
     */
    public function joinI18n($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $relationName = $relationAlias ? $relationAlias : 'SchoolClassI18n';

        return $this
            ->joinSchoolClassI18n($relationAlias, $joinType)
            ->addJoinCondition($relationName, $relationName . '.Locale = ?', $locale);
    }

    /**
     * Adds a JOIN clause to the query and hydrates the related I18n object.
     * Shortcut for $c->joinI18n($locale)->with()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    SchoolClassQuery The current query, for fluid interface
     */
    public function joinWithI18n($locale = 'en_US', $joinType = Criteria::LEFT_JOIN)
    {
        $this
            ->joinI18n($locale, null, $joinType)
            ->with('SchoolClassI18n');
        $this->with['SchoolClassI18n']->setIsWithOneToMany(false);

        return $this;
    }

    /**
     * Use the I18n relation query object
     *
     * @see       useQuery()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    SchoolClassI18nQuery A secondary query class using the current class as primary query
     */
    public function useI18nQuery($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinI18n($locale, $relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SchoolClassI18n', 'PGS\CoreDomainBundle\Model\SchoolClass\SchoolClassI18nQuery');
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     SchoolClassQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(SchoolClassPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     SchoolClassQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(SchoolClassPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     SchoolClassQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(SchoolClassPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     SchoolClassQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(SchoolClassPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     SchoolClassQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(SchoolClassPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     SchoolClassQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(SchoolClassPeer::CREATED_AT);
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
