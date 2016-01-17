<?php

namespace PGS\CoreDomainBundle\Model\Formula\om;

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
use PGS\CoreDomainBundle\Model\Formula\Formula;
use PGS\CoreDomainBundle\Model\Formula\FormulaPeer;
use PGS\CoreDomainBundle\Model\Formula\FormulaQuery;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourse;

/**
 * @method FormulaQuery orderById($order = Criteria::ASC) Order by the id column
 * @method FormulaQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method FormulaQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method FormulaQuery orderByFinalExamPoint($order = Criteria::ASC) Order by the final_exam_point column
 * @method FormulaQuery orderByDailyExamPoint($order = Criteria::ASC) Order by the daily_exam_point column
 * @method FormulaQuery orderByMidExamPoint($order = Criteria::ASC) Order by the mid_exam_point column
 * @method FormulaQuery orderByActivityPoint($order = Criteria::ASC) Order by the activity_point column
 * @method FormulaQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method FormulaQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method FormulaQuery groupById() Group by the id column
 * @method FormulaQuery groupByName() Group by the name column
 * @method FormulaQuery groupByDescription() Group by the description column
 * @method FormulaQuery groupByFinalExamPoint() Group by the final_exam_point column
 * @method FormulaQuery groupByDailyExamPoint() Group by the daily_exam_point column
 * @method FormulaQuery groupByMidExamPoint() Group by the mid_exam_point column
 * @method FormulaQuery groupByActivityPoint() Group by the activity_point column
 * @method FormulaQuery groupByCreatedAt() Group by the created_at column
 * @method FormulaQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method FormulaQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method FormulaQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method FormulaQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method FormulaQuery leftJoinSchoolClassCourse($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolClassCourse relation
 * @method FormulaQuery rightJoinSchoolClassCourse($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolClassCourse relation
 * @method FormulaQuery innerJoinSchoolClassCourse($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolClassCourse relation
 *
 * @method Formula findOne(PropelPDO $con = null) Return the first Formula matching the query
 * @method Formula findOneOrCreate(PropelPDO $con = null) Return the first Formula matching the query, or a new Formula object populated from the query conditions when no match is found
 *
 * @method Formula findOneByName(string $name) Return the first Formula filtered by the name column
 * @method Formula findOneByDescription(string $description) Return the first Formula filtered by the description column
 * @method Formula findOneByFinalExamPoint(string $final_exam_point) Return the first Formula filtered by the final_exam_point column
 * @method Formula findOneByDailyExamPoint(string $daily_exam_point) Return the first Formula filtered by the daily_exam_point column
 * @method Formula findOneByMidExamPoint(string $mid_exam_point) Return the first Formula filtered by the mid_exam_point column
 * @method Formula findOneByActivityPoint(string $activity_point) Return the first Formula filtered by the activity_point column
 * @method Formula findOneByCreatedAt(string $created_at) Return the first Formula filtered by the created_at column
 * @method Formula findOneByUpdatedAt(string $updated_at) Return the first Formula filtered by the updated_at column
 *
 * @method array findById(int $id) Return Formula objects filtered by the id column
 * @method array findByName(string $name) Return Formula objects filtered by the name column
 * @method array findByDescription(string $description) Return Formula objects filtered by the description column
 * @method array findByFinalExamPoint(string $final_exam_point) Return Formula objects filtered by the final_exam_point column
 * @method array findByDailyExamPoint(string $daily_exam_point) Return Formula objects filtered by the daily_exam_point column
 * @method array findByMidExamPoint(string $mid_exam_point) Return Formula objects filtered by the mid_exam_point column
 * @method array findByActivityPoint(string $activity_point) Return Formula objects filtered by the activity_point column
 * @method array findByCreatedAt(string $created_at) Return Formula objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Formula objects filtered by the updated_at column
 */
abstract class BaseFormulaQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseFormulaQuery object.
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
            $modelName = 'PGS\\CoreDomainBundle\\Model\\Formula\\Formula';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new FormulaQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   FormulaQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return FormulaQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof FormulaQuery) {
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
     * @return   Formula|Formula[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = FormulaPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(FormulaPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Formula A model object, or null if the key is not found
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
     * @return                 Formula A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `description`, `final_exam_point`, `daily_exam_point`, `mid_exam_point`, `activity_point`, `created_at`, `updated_at` FROM `formula` WHERE `id` = :p0';
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
            $cls = FormulaPeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            FormulaPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Formula|Formula[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Formula[]|mixed the list of results, formatted by the current formatter
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
     * @return FormulaQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(FormulaPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return FormulaQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(FormulaPeer::ID, $keys, Criteria::IN);
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
     * @return FormulaQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(FormulaPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(FormulaPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FormulaPeer::ID, $id, $comparison);
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
     * @return FormulaQuery The current query, for fluid interface
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

        return $this->addUsingAlias(FormulaPeer::NAME, $name, $comparison);
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
     * @return FormulaQuery The current query, for fluid interface
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

        return $this->addUsingAlias(FormulaPeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the final_exam_point column
     *
     * Example usage:
     * <code>
     * $query->filterByFinalExamPoint(1234); // WHERE final_exam_point = 1234
     * $query->filterByFinalExamPoint(array(12, 34)); // WHERE final_exam_point IN (12, 34)
     * $query->filterByFinalExamPoint(array('min' => 12)); // WHERE final_exam_point >= 12
     * $query->filterByFinalExamPoint(array('max' => 12)); // WHERE final_exam_point <= 12
     * </code>
     *
     * @param     mixed $finalExamPoint The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FormulaQuery The current query, for fluid interface
     */
    public function filterByFinalExamPoint($finalExamPoint = null, $comparison = null)
    {
        if (is_array($finalExamPoint)) {
            $useMinMax = false;
            if (isset($finalExamPoint['min'])) {
                $this->addUsingAlias(FormulaPeer::FINAL_EXAM_POINT, $finalExamPoint['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($finalExamPoint['max'])) {
                $this->addUsingAlias(FormulaPeer::FINAL_EXAM_POINT, $finalExamPoint['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FormulaPeer::FINAL_EXAM_POINT, $finalExamPoint, $comparison);
    }

    /**
     * Filter the query on the daily_exam_point column
     *
     * Example usage:
     * <code>
     * $query->filterByDailyExamPoint(1234); // WHERE daily_exam_point = 1234
     * $query->filterByDailyExamPoint(array(12, 34)); // WHERE daily_exam_point IN (12, 34)
     * $query->filterByDailyExamPoint(array('min' => 12)); // WHERE daily_exam_point >= 12
     * $query->filterByDailyExamPoint(array('max' => 12)); // WHERE daily_exam_point <= 12
     * </code>
     *
     * @param     mixed $dailyExamPoint The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FormulaQuery The current query, for fluid interface
     */
    public function filterByDailyExamPoint($dailyExamPoint = null, $comparison = null)
    {
        if (is_array($dailyExamPoint)) {
            $useMinMax = false;
            if (isset($dailyExamPoint['min'])) {
                $this->addUsingAlias(FormulaPeer::DAILY_EXAM_POINT, $dailyExamPoint['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dailyExamPoint['max'])) {
                $this->addUsingAlias(FormulaPeer::DAILY_EXAM_POINT, $dailyExamPoint['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FormulaPeer::DAILY_EXAM_POINT, $dailyExamPoint, $comparison);
    }

    /**
     * Filter the query on the mid_exam_point column
     *
     * Example usage:
     * <code>
     * $query->filterByMidExamPoint(1234); // WHERE mid_exam_point = 1234
     * $query->filterByMidExamPoint(array(12, 34)); // WHERE mid_exam_point IN (12, 34)
     * $query->filterByMidExamPoint(array('min' => 12)); // WHERE mid_exam_point >= 12
     * $query->filterByMidExamPoint(array('max' => 12)); // WHERE mid_exam_point <= 12
     * </code>
     *
     * @param     mixed $midExamPoint The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FormulaQuery The current query, for fluid interface
     */
    public function filterByMidExamPoint($midExamPoint = null, $comparison = null)
    {
        if (is_array($midExamPoint)) {
            $useMinMax = false;
            if (isset($midExamPoint['min'])) {
                $this->addUsingAlias(FormulaPeer::MID_EXAM_POINT, $midExamPoint['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($midExamPoint['max'])) {
                $this->addUsingAlias(FormulaPeer::MID_EXAM_POINT, $midExamPoint['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FormulaPeer::MID_EXAM_POINT, $midExamPoint, $comparison);
    }

    /**
     * Filter the query on the activity_point column
     *
     * Example usage:
     * <code>
     * $query->filterByActivityPoint(1234); // WHERE activity_point = 1234
     * $query->filterByActivityPoint(array(12, 34)); // WHERE activity_point IN (12, 34)
     * $query->filterByActivityPoint(array('min' => 12)); // WHERE activity_point >= 12
     * $query->filterByActivityPoint(array('max' => 12)); // WHERE activity_point <= 12
     * </code>
     *
     * @param     mixed $activityPoint The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FormulaQuery The current query, for fluid interface
     */
    public function filterByActivityPoint($activityPoint = null, $comparison = null)
    {
        if (is_array($activityPoint)) {
            $useMinMax = false;
            if (isset($activityPoint['min'])) {
                $this->addUsingAlias(FormulaPeer::ACTIVITY_POINT, $activityPoint['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($activityPoint['max'])) {
                $this->addUsingAlias(FormulaPeer::ACTIVITY_POINT, $activityPoint['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FormulaPeer::ACTIVITY_POINT, $activityPoint, $comparison);
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
     * @return FormulaQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(FormulaPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(FormulaPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FormulaPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return FormulaQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(FormulaPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(FormulaPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FormulaPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related SchoolClassCourse object
     *
     * @param   SchoolClassCourse|PropelObjectCollection $schoolClassCourse  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FormulaQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolClassCourse($schoolClassCourse, $comparison = null)
    {
        if ($schoolClassCourse instanceof SchoolClassCourse) {
            return $this
                ->addUsingAlias(FormulaPeer::ID, $schoolClassCourse->getFormulaId(), $comparison);
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
     * @return FormulaQuery The current query, for fluid interface
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
     * @param   Formula $formula Object to remove from the list of results
     *
     * @return FormulaQuery The current query, for fluid interface
     */
    public function prune($formula = null)
    {
        if ($formula) {
            $this->addUsingAlias(FormulaPeer::ID, $formula->getId(), Criteria::NOT_EQUAL);
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
     * @return     FormulaQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(FormulaPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     FormulaQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(FormulaPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     FormulaQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(FormulaPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     FormulaQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(FormulaPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     FormulaQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(FormulaPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     FormulaQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(FormulaPeer::CREATED_AT);
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
