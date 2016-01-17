<?php

namespace PGS\CoreDomainBundle\Model\StudentCondition\om;

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
use PGS\CoreDomainBundle\Model\Condition\Condition;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealth;
use PGS\CoreDomainBundle\Model\StudentCondition\StudentCondition;
use PGS\CoreDomainBundle\Model\StudentCondition\StudentConditionPeer;
use PGS\CoreDomainBundle\Model\StudentCondition\StudentConditionQuery;

/**
 * @method StudentConditionQuery orderBySchoolHealthId($order = Criteria::ASC) Order by the school_health_id column
 * @method StudentConditionQuery orderByConditionId($order = Criteria::ASC) Order by the condition_id column
 * @method StudentConditionQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method StudentConditionQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method StudentConditionQuery groupBySchoolHealthId() Group by the school_health_id column
 * @method StudentConditionQuery groupByConditionId() Group by the condition_id column
 * @method StudentConditionQuery groupByCreatedAt() Group by the created_at column
 * @method StudentConditionQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method StudentConditionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method StudentConditionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method StudentConditionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method StudentConditionQuery leftJoinSchoolHealth($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolHealth relation
 * @method StudentConditionQuery rightJoinSchoolHealth($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolHealth relation
 * @method StudentConditionQuery innerJoinSchoolHealth($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolHealth relation
 *
 * @method StudentConditionQuery leftJoinCondition($relationAlias = null) Adds a LEFT JOIN clause to the query using the Condition relation
 * @method StudentConditionQuery rightJoinCondition($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Condition relation
 * @method StudentConditionQuery innerJoinCondition($relationAlias = null) Adds a INNER JOIN clause to the query using the Condition relation
 *
 * @method StudentCondition findOne(PropelPDO $con = null) Return the first StudentCondition matching the query
 * @method StudentCondition findOneOrCreate(PropelPDO $con = null) Return the first StudentCondition matching the query, or a new StudentCondition object populated from the query conditions when no match is found
 *
 * @method StudentCondition findOneBySchoolHealthId(int $school_health_id) Return the first StudentCondition filtered by the school_health_id column
 * @method StudentCondition findOneByConditionId(int $condition_id) Return the first StudentCondition filtered by the condition_id column
 * @method StudentCondition findOneByCreatedAt(string $created_at) Return the first StudentCondition filtered by the created_at column
 * @method StudentCondition findOneByUpdatedAt(string $updated_at) Return the first StudentCondition filtered by the updated_at column
 *
 * @method array findBySchoolHealthId(int $school_health_id) Return StudentCondition objects filtered by the school_health_id column
 * @method array findByConditionId(int $condition_id) Return StudentCondition objects filtered by the condition_id column
 * @method array findByCreatedAt(string $created_at) Return StudentCondition objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return StudentCondition objects filtered by the updated_at column
 */
abstract class BaseStudentConditionQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseStudentConditionQuery object.
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
            $modelName = 'PGS\\CoreDomainBundle\\Model\\StudentCondition\\StudentCondition';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new StudentConditionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   StudentConditionQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return StudentConditionQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof StudentConditionQuery) {
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array $key Primary key to use for the query
                         A Primary key composition: [$school_health_id, $condition_id]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   StudentCondition|StudentCondition[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = StudentConditionPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(StudentConditionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 StudentCondition A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `school_health_id`, `condition_id`, `created_at`, `updated_at` FROM `student_condition` WHERE `school_health_id` = :p0 AND `condition_id` = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $cls = StudentConditionPeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            StudentConditionPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return StudentCondition|StudentCondition[]|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|StudentCondition[]|mixed the list of results, formatted by the current formatter
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
     * @return StudentConditionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(StudentConditionPeer::SCHOOL_HEALTH_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(StudentConditionPeer::CONDITION_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return StudentConditionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(StudentConditionPeer::SCHOOL_HEALTH_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(StudentConditionPeer::CONDITION_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the school_health_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySchoolHealthId(1234); // WHERE school_health_id = 1234
     * $query->filterBySchoolHealthId(array(12, 34)); // WHERE school_health_id IN (12, 34)
     * $query->filterBySchoolHealthId(array('min' => 12)); // WHERE school_health_id >= 12
     * $query->filterBySchoolHealthId(array('max' => 12)); // WHERE school_health_id <= 12
     * </code>
     *
     * @see       filterBySchoolHealth()
     *
     * @param     mixed $schoolHealthId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentConditionQuery The current query, for fluid interface
     */
    public function filterBySchoolHealthId($schoolHealthId = null, $comparison = null)
    {
        if (is_array($schoolHealthId)) {
            $useMinMax = false;
            if (isset($schoolHealthId['min'])) {
                $this->addUsingAlias(StudentConditionPeer::SCHOOL_HEALTH_ID, $schoolHealthId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($schoolHealthId['max'])) {
                $this->addUsingAlias(StudentConditionPeer::SCHOOL_HEALTH_ID, $schoolHealthId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentConditionPeer::SCHOOL_HEALTH_ID, $schoolHealthId, $comparison);
    }

    /**
     * Filter the query on the condition_id column
     *
     * Example usage:
     * <code>
     * $query->filterByConditionId(1234); // WHERE condition_id = 1234
     * $query->filterByConditionId(array(12, 34)); // WHERE condition_id IN (12, 34)
     * $query->filterByConditionId(array('min' => 12)); // WHERE condition_id >= 12
     * $query->filterByConditionId(array('max' => 12)); // WHERE condition_id <= 12
     * </code>
     *
     * @see       filterByCondition()
     *
     * @param     mixed $conditionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentConditionQuery The current query, for fluid interface
     */
    public function filterByConditionId($conditionId = null, $comparison = null)
    {
        if (is_array($conditionId)) {
            $useMinMax = false;
            if (isset($conditionId['min'])) {
                $this->addUsingAlias(StudentConditionPeer::CONDITION_ID, $conditionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($conditionId['max'])) {
                $this->addUsingAlias(StudentConditionPeer::CONDITION_ID, $conditionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentConditionPeer::CONDITION_ID, $conditionId, $comparison);
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
     * @return StudentConditionQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(StudentConditionPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(StudentConditionPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentConditionPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return StudentConditionQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(StudentConditionPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(StudentConditionPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentConditionPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related SchoolHealth object
     *
     * @param   SchoolHealth|PropelObjectCollection $schoolHealth The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StudentConditionQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolHealth($schoolHealth, $comparison = null)
    {
        if ($schoolHealth instanceof SchoolHealth) {
            return $this
                ->addUsingAlias(StudentConditionPeer::SCHOOL_HEALTH_ID, $schoolHealth->getId(), $comparison);
        } elseif ($schoolHealth instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(StudentConditionPeer::SCHOOL_HEALTH_ID, $schoolHealth->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySchoolHealth() only accepts arguments of type SchoolHealth or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SchoolHealth relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StudentConditionQuery The current query, for fluid interface
     */
    public function joinSchoolHealth($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SchoolHealth');

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
            $this->addJoinObject($join, 'SchoolHealth');
        }

        return $this;
    }

    /**
     * Use the SchoolHealth relation SchoolHealth object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealthQuery A secondary query class using the current class as primary query
     */
    public function useSchoolHealthQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSchoolHealth($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SchoolHealth', '\PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealthQuery');
    }

    /**
     * Filter the query by a related Condition object
     *
     * @param   Condition|PropelObjectCollection $condition The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StudentConditionQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCondition($condition, $comparison = null)
    {
        if ($condition instanceof Condition) {
            return $this
                ->addUsingAlias(StudentConditionPeer::CONDITION_ID, $condition->getId(), $comparison);
        } elseif ($condition instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(StudentConditionPeer::CONDITION_ID, $condition->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCondition() only accepts arguments of type Condition or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Condition relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StudentConditionQuery The current query, for fluid interface
     */
    public function joinCondition($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Condition');

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
            $this->addJoinObject($join, 'Condition');
        }

        return $this;
    }

    /**
     * Use the Condition relation Condition object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\Condition\ConditionQuery A secondary query class using the current class as primary query
     */
    public function useConditionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCondition($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Condition', '\PGS\CoreDomainBundle\Model\Condition\ConditionQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   StudentCondition $studentCondition Object to remove from the list of results
     *
     * @return StudentConditionQuery The current query, for fluid interface
     */
    public function prune($studentCondition = null)
    {
        if ($studentCondition) {
            $this->addCond('pruneCond0', $this->getAliasedColName(StudentConditionPeer::SCHOOL_HEALTH_ID), $studentCondition->getSchoolHealthId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(StudentConditionPeer::CONDITION_ID), $studentCondition->getConditionId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
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
     * @return     StudentConditionQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(StudentConditionPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     StudentConditionQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(StudentConditionPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     StudentConditionQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(StudentConditionPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     StudentConditionQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(StudentConditionPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     StudentConditionQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(StudentConditionPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     StudentConditionQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(StudentConditionPeer::CREATED_AT);
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
