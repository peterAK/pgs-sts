<?php

namespace PGS\CoreDomainBundle\Model\StudentMedical\om;

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
use PGS\CoreDomainBundle\Model\Medical\Medical;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealth;
use PGS\CoreDomainBundle\Model\StudentMedical\StudentMedical;
use PGS\CoreDomainBundle\Model\StudentMedical\StudentMedicalPeer;
use PGS\CoreDomainBundle\Model\StudentMedical\StudentMedicalQuery;

/**
 * @method StudentMedicalQuery orderBySchoolHealthId($order = Criteria::ASC) Order by the school_health_id column
 * @method StudentMedicalQuery orderByMedicalId($order = Criteria::ASC) Order by the medical_id column
 * @method StudentMedicalQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method StudentMedicalQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method StudentMedicalQuery groupBySchoolHealthId() Group by the school_health_id column
 * @method StudentMedicalQuery groupByMedicalId() Group by the medical_id column
 * @method StudentMedicalQuery groupByCreatedAt() Group by the created_at column
 * @method StudentMedicalQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method StudentMedicalQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method StudentMedicalQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method StudentMedicalQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method StudentMedicalQuery leftJoinSchoolHealth($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolHealth relation
 * @method StudentMedicalQuery rightJoinSchoolHealth($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolHealth relation
 * @method StudentMedicalQuery innerJoinSchoolHealth($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolHealth relation
 *
 * @method StudentMedicalQuery leftJoinMedical($relationAlias = null) Adds a LEFT JOIN clause to the query using the Medical relation
 * @method StudentMedicalQuery rightJoinMedical($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Medical relation
 * @method StudentMedicalQuery innerJoinMedical($relationAlias = null) Adds a INNER JOIN clause to the query using the Medical relation
 *
 * @method StudentMedical findOne(PropelPDO $con = null) Return the first StudentMedical matching the query
 * @method StudentMedical findOneOrCreate(PropelPDO $con = null) Return the first StudentMedical matching the query, or a new StudentMedical object populated from the query conditions when no match is found
 *
 * @method StudentMedical findOneBySchoolHealthId(int $school_health_id) Return the first StudentMedical filtered by the school_health_id column
 * @method StudentMedical findOneByMedicalId(int $medical_id) Return the first StudentMedical filtered by the medical_id column
 * @method StudentMedical findOneByCreatedAt(string $created_at) Return the first StudentMedical filtered by the created_at column
 * @method StudentMedical findOneByUpdatedAt(string $updated_at) Return the first StudentMedical filtered by the updated_at column
 *
 * @method array findBySchoolHealthId(int $school_health_id) Return StudentMedical objects filtered by the school_health_id column
 * @method array findByMedicalId(int $medical_id) Return StudentMedical objects filtered by the medical_id column
 * @method array findByCreatedAt(string $created_at) Return StudentMedical objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return StudentMedical objects filtered by the updated_at column
 */
abstract class BaseStudentMedicalQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseStudentMedicalQuery object.
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
            $modelName = 'PGS\\CoreDomainBundle\\Model\\StudentMedical\\StudentMedical';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new StudentMedicalQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   StudentMedicalQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return StudentMedicalQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof StudentMedicalQuery) {
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
                         A Primary key composition: [$school_health_id, $medical_id]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   StudentMedical|StudentMedical[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = StudentMedicalPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(StudentMedicalPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 StudentMedical A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `school_health_id`, `medical_id`, `created_at`, `updated_at` FROM `student_medical` WHERE `school_health_id` = :p0 AND `medical_id` = :p1';
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
            $cls = StudentMedicalPeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            StudentMedicalPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return StudentMedical|StudentMedical[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|StudentMedical[]|mixed the list of results, formatted by the current formatter
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
     * @return StudentMedicalQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(StudentMedicalPeer::SCHOOL_HEALTH_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(StudentMedicalPeer::MEDICAL_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return StudentMedicalQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(StudentMedicalPeer::SCHOOL_HEALTH_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(StudentMedicalPeer::MEDICAL_ID, $key[1], Criteria::EQUAL);
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
     * @return StudentMedicalQuery The current query, for fluid interface
     */
    public function filterBySchoolHealthId($schoolHealthId = null, $comparison = null)
    {
        if (is_array($schoolHealthId)) {
            $useMinMax = false;
            if (isset($schoolHealthId['min'])) {
                $this->addUsingAlias(StudentMedicalPeer::SCHOOL_HEALTH_ID, $schoolHealthId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($schoolHealthId['max'])) {
                $this->addUsingAlias(StudentMedicalPeer::SCHOOL_HEALTH_ID, $schoolHealthId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentMedicalPeer::SCHOOL_HEALTH_ID, $schoolHealthId, $comparison);
    }

    /**
     * Filter the query on the medical_id column
     *
     * Example usage:
     * <code>
     * $query->filterByMedicalId(1234); // WHERE medical_id = 1234
     * $query->filterByMedicalId(array(12, 34)); // WHERE medical_id IN (12, 34)
     * $query->filterByMedicalId(array('min' => 12)); // WHERE medical_id >= 12
     * $query->filterByMedicalId(array('max' => 12)); // WHERE medical_id <= 12
     * </code>
     *
     * @see       filterByMedical()
     *
     * @param     mixed $medicalId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentMedicalQuery The current query, for fluid interface
     */
    public function filterByMedicalId($medicalId = null, $comparison = null)
    {
        if (is_array($medicalId)) {
            $useMinMax = false;
            if (isset($medicalId['min'])) {
                $this->addUsingAlias(StudentMedicalPeer::MEDICAL_ID, $medicalId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($medicalId['max'])) {
                $this->addUsingAlias(StudentMedicalPeer::MEDICAL_ID, $medicalId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentMedicalPeer::MEDICAL_ID, $medicalId, $comparison);
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
     * @return StudentMedicalQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(StudentMedicalPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(StudentMedicalPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentMedicalPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return StudentMedicalQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(StudentMedicalPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(StudentMedicalPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentMedicalPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related SchoolHealth object
     *
     * @param   SchoolHealth|PropelObjectCollection $schoolHealth The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StudentMedicalQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolHealth($schoolHealth, $comparison = null)
    {
        if ($schoolHealth instanceof SchoolHealth) {
            return $this
                ->addUsingAlias(StudentMedicalPeer::SCHOOL_HEALTH_ID, $schoolHealth->getId(), $comparison);
        } elseif ($schoolHealth instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(StudentMedicalPeer::SCHOOL_HEALTH_ID, $schoolHealth->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return StudentMedicalQuery The current query, for fluid interface
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
     * Filter the query by a related Medical object
     *
     * @param   Medical|PropelObjectCollection $medical The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StudentMedicalQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByMedical($medical, $comparison = null)
    {
        if ($medical instanceof Medical) {
            return $this
                ->addUsingAlias(StudentMedicalPeer::MEDICAL_ID, $medical->getId(), $comparison);
        } elseif ($medical instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(StudentMedicalPeer::MEDICAL_ID, $medical->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByMedical() only accepts arguments of type Medical or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Medical relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StudentMedicalQuery The current query, for fluid interface
     */
    public function joinMedical($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Medical');

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
            $this->addJoinObject($join, 'Medical');
        }

        return $this;
    }

    /**
     * Use the Medical relation Medical object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\Medical\MedicalQuery A secondary query class using the current class as primary query
     */
    public function useMedicalQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMedical($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Medical', '\PGS\CoreDomainBundle\Model\Medical\MedicalQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   StudentMedical $studentMedical Object to remove from the list of results
     *
     * @return StudentMedicalQuery The current query, for fluid interface
     */
    public function prune($studentMedical = null)
    {
        if ($studentMedical) {
            $this->addCond('pruneCond0', $this->getAliasedColName(StudentMedicalPeer::SCHOOL_HEALTH_ID), $studentMedical->getSchoolHealthId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(StudentMedicalPeer::MEDICAL_ID), $studentMedical->getMedicalId(), Criteria::NOT_EQUAL);
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
     * @return     StudentMedicalQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(StudentMedicalPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     StudentMedicalQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(StudentMedicalPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     StudentMedicalQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(StudentMedicalPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     StudentMedicalQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(StudentMedicalPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     StudentMedicalQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(StudentMedicalPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     StudentMedicalQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(StudentMedicalPeer::CREATED_AT);
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
