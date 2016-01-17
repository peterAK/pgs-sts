<?php

namespace PGS\CoreDomainBundle\Model\AcademicYear\om;

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
use PGS\CoreDomainBundle\Model\AcademicYear\AcademicYear;
use PGS\CoreDomainBundle\Model\AcademicYear\AcademicYearPeer;
use PGS\CoreDomainBundle\Model\AcademicYear\AcademicYearQuery;
use PGS\CoreDomainBundle\Model\SchoolYear\SchoolYear;

/**
 * @method AcademicYearQuery orderById($order = Criteria::ASC) Order by the id column
 * @method AcademicYearQuery orderByYearStart($order = Criteria::ASC) Order by the year_start column
 * @method AcademicYearQuery orderByYearEnd($order = Criteria::ASC) Order by the year_end column
 * @method AcademicYearQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method AcademicYearQuery orderByActive($order = Criteria::ASC) Order by the active column
 * @method AcademicYearQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method AcademicYearQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method AcademicYearQuery groupById() Group by the id column
 * @method AcademicYearQuery groupByYearStart() Group by the year_start column
 * @method AcademicYearQuery groupByYearEnd() Group by the year_end column
 * @method AcademicYearQuery groupByDescription() Group by the description column
 * @method AcademicYearQuery groupByActive() Group by the active column
 * @method AcademicYearQuery groupByCreatedAt() Group by the created_at column
 * @method AcademicYearQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method AcademicYearQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method AcademicYearQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method AcademicYearQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method AcademicYearQuery leftJoinSchoolYear($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolYear relation
 * @method AcademicYearQuery rightJoinSchoolYear($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolYear relation
 * @method AcademicYearQuery innerJoinSchoolYear($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolYear relation
 *
 * @method AcademicYear findOne(PropelPDO $con = null) Return the first AcademicYear matching the query
 * @method AcademicYear findOneOrCreate(PropelPDO $con = null) Return the first AcademicYear matching the query, or a new AcademicYear object populated from the query conditions when no match is found
 *
 * @method AcademicYear findOneByYearStart(int $year_start) Return the first AcademicYear filtered by the year_start column
 * @method AcademicYear findOneByYearEnd(int $year_end) Return the first AcademicYear filtered by the year_end column
 * @method AcademicYear findOneByDescription(string $description) Return the first AcademicYear filtered by the description column
 * @method AcademicYear findOneByActive(boolean $active) Return the first AcademicYear filtered by the active column
 * @method AcademicYear findOneByCreatedAt(string $created_at) Return the first AcademicYear filtered by the created_at column
 * @method AcademicYear findOneByUpdatedAt(string $updated_at) Return the first AcademicYear filtered by the updated_at column
 *
 * @method array findById(int $id) Return AcademicYear objects filtered by the id column
 * @method array findByYearStart(int $year_start) Return AcademicYear objects filtered by the year_start column
 * @method array findByYearEnd(int $year_end) Return AcademicYear objects filtered by the year_end column
 * @method array findByDescription(string $description) Return AcademicYear objects filtered by the description column
 * @method array findByActive(boolean $active) Return AcademicYear objects filtered by the active column
 * @method array findByCreatedAt(string $created_at) Return AcademicYear objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return AcademicYear objects filtered by the updated_at column
 */
abstract class BaseAcademicYearQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseAcademicYearQuery object.
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
            $modelName = 'PGS\\CoreDomainBundle\\Model\\AcademicYear\\AcademicYear';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new AcademicYearQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   AcademicYearQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return AcademicYearQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof AcademicYearQuery) {
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
     * @return   AcademicYear|AcademicYear[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = AcademicYearPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(AcademicYearPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 AcademicYear A model object, or null if the key is not found
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
     * @return                 AcademicYear A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `year_start`, `year_end`, `description`, `active`, `created_at`, `updated_at` FROM `academic_year` WHERE `id` = :p0';
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
            $cls = AcademicYearPeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            AcademicYearPeer::addInstanceToPool($obj, (string) $key);
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
     * @return AcademicYear|AcademicYear[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|AcademicYear[]|mixed the list of results, formatted by the current formatter
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
     * @return AcademicYearQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AcademicYearPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return AcademicYearQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AcademicYearPeer::ID, $keys, Criteria::IN);
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
     * @return AcademicYearQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AcademicYearPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AcademicYearPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AcademicYearPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the year_start column
     *
     * Example usage:
     * <code>
     * $query->filterByYearStart(1234); // WHERE year_start = 1234
     * $query->filterByYearStart(array(12, 34)); // WHERE year_start IN (12, 34)
     * $query->filterByYearStart(array('min' => 12)); // WHERE year_start >= 12
     * $query->filterByYearStart(array('max' => 12)); // WHERE year_start <= 12
     * </code>
     *
     * @param     mixed $yearStart The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AcademicYearQuery The current query, for fluid interface
     */
    public function filterByYearStart($yearStart = null, $comparison = null)
    {
        if (is_array($yearStart)) {
            $useMinMax = false;
            if (isset($yearStart['min'])) {
                $this->addUsingAlias(AcademicYearPeer::YEAR_START, $yearStart['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($yearStart['max'])) {
                $this->addUsingAlias(AcademicYearPeer::YEAR_START, $yearStart['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AcademicYearPeer::YEAR_START, $yearStart, $comparison);
    }

    /**
     * Filter the query on the year_end column
     *
     * Example usage:
     * <code>
     * $query->filterByYearEnd(1234); // WHERE year_end = 1234
     * $query->filterByYearEnd(array(12, 34)); // WHERE year_end IN (12, 34)
     * $query->filterByYearEnd(array('min' => 12)); // WHERE year_end >= 12
     * $query->filterByYearEnd(array('max' => 12)); // WHERE year_end <= 12
     * </code>
     *
     * @param     mixed $yearEnd The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AcademicYearQuery The current query, for fluid interface
     */
    public function filterByYearEnd($yearEnd = null, $comparison = null)
    {
        if (is_array($yearEnd)) {
            $useMinMax = false;
            if (isset($yearEnd['min'])) {
                $this->addUsingAlias(AcademicYearPeer::YEAR_END, $yearEnd['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($yearEnd['max'])) {
                $this->addUsingAlias(AcademicYearPeer::YEAR_END, $yearEnd['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AcademicYearPeer::YEAR_END, $yearEnd, $comparison);
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
     * @return AcademicYearQuery The current query, for fluid interface
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

        return $this->addUsingAlias(AcademicYearPeer::DESCRIPTION, $description, $comparison);
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
     * @return AcademicYearQuery The current query, for fluid interface
     */
    public function filterByActive($active = null, $comparison = null)
    {
        if (is_string($active)) {
            $active = in_array(strtolower($active), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(AcademicYearPeer::ACTIVE, $active, $comparison);
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
     * @return AcademicYearQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(AcademicYearPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(AcademicYearPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AcademicYearPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return AcademicYearQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(AcademicYearPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(AcademicYearPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AcademicYearPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related SchoolYear object
     *
     * @param   SchoolYear|PropelObjectCollection $schoolYear  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 AcademicYearQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolYear($schoolYear, $comparison = null)
    {
        if ($schoolYear instanceof SchoolYear) {
            return $this
                ->addUsingAlias(AcademicYearPeer::ID, $schoolYear->getAcademicYearId(), $comparison);
        } elseif ($schoolYear instanceof PropelObjectCollection) {
            return $this
                ->useSchoolYearQuery()
                ->filterByPrimaryKeys($schoolYear->getPrimaryKeys())
                ->endUse();
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
     * @return AcademicYearQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   AcademicYear $academicYear Object to remove from the list of results
     *
     * @return AcademicYearQuery The current query, for fluid interface
     */
    public function prune($academicYear = null)
    {
        if ($academicYear) {
            $this->addUsingAlias(AcademicYearPeer::ID, $academicYear->getId(), Criteria::NOT_EQUAL);
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
     * @return     AcademicYearQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(AcademicYearPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     AcademicYearQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(AcademicYearPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     AcademicYearQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(AcademicYearPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     AcademicYearQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(AcademicYearPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     AcademicYearQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(AcademicYearPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     AcademicYearQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(AcademicYearPeer::CREATED_AT);
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
