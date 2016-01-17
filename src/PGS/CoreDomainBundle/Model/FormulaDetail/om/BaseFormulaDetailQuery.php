<?php

namespace PGS\CoreDomainBundle\Model\FormulaDetail\om;

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
use PGS\CoreDomainBundle\Model\FormulaDetail\FormulaDetail;
use PGS\CoreDomainBundle\Model\FormulaDetail\FormulaDetailPeer;
use PGS\CoreDomainBundle\Model\FormulaDetail\FormulaDetailQuery;

/**
 * @method FormulaDetailQuery orderById($order = Criteria::ASC) Order by the id column
 * @method FormulaDetailQuery orderByFormulaId($order = Criteria::ASC) Order by the formula_id column
 * @method FormulaDetailQuery orderByFinalExamPoint($order = Criteria::ASC) Order by the final_exam_point column
 * @method FormulaDetailQuery orderByDailyExamPoint($order = Criteria::ASC) Order by the daily_exam_point column
 * @method FormulaDetailQuery orderByMidExamPoint($order = Criteria::ASC) Order by the mid_exam_point column
 * @method FormulaDetailQuery orderByActivityPoint($order = Criteria::ASC) Order by the activity_point column
 * @method FormulaDetailQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method FormulaDetailQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method FormulaDetailQuery groupById() Group by the id column
 * @method FormulaDetailQuery groupByFormulaId() Group by the formula_id column
 * @method FormulaDetailQuery groupByFinalExamPoint() Group by the final_exam_point column
 * @method FormulaDetailQuery groupByDailyExamPoint() Group by the daily_exam_point column
 * @method FormulaDetailQuery groupByMidExamPoint() Group by the mid_exam_point column
 * @method FormulaDetailQuery groupByActivityPoint() Group by the activity_point column
 * @method FormulaDetailQuery groupByCreatedAt() Group by the created_at column
 * @method FormulaDetailQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method FormulaDetailQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method FormulaDetailQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method FormulaDetailQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method FormulaDetailQuery leftJoinFormulaRelatedByFormulaId($relationAlias = null) Adds a LEFT JOIN clause to the query using the FormulaRelatedByFormulaId relation
 * @method FormulaDetailQuery rightJoinFormulaRelatedByFormulaId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FormulaRelatedByFormulaId relation
 * @method FormulaDetailQuery innerJoinFormulaRelatedByFormulaId($relationAlias = null) Adds a INNER JOIN clause to the query using the FormulaRelatedByFormulaId relation
 *
 * @method FormulaDetailQuery leftJoinFormulaRelatedByFormulaDetailId($relationAlias = null) Adds a LEFT JOIN clause to the query using the FormulaRelatedByFormulaDetailId relation
 * @method FormulaDetailQuery rightJoinFormulaRelatedByFormulaDetailId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FormulaRelatedByFormulaDetailId relation
 * @method FormulaDetailQuery innerJoinFormulaRelatedByFormulaDetailId($relationAlias = null) Adds a INNER JOIN clause to the query using the FormulaRelatedByFormulaDetailId relation
 *
 * @method FormulaDetail findOne(PropelPDO $con = null) Return the first FormulaDetail matching the query
 * @method FormulaDetail findOneOrCreate(PropelPDO $con = null) Return the first FormulaDetail matching the query, or a new FormulaDetail object populated from the query conditions when no match is found
 *
 * @method FormulaDetail findOneByFormulaId(int $formula_id) Return the first FormulaDetail filtered by the formula_id column
 * @method FormulaDetail findOneByFinalExamPoint(string $final_exam_point) Return the first FormulaDetail filtered by the final_exam_point column
 * @method FormulaDetail findOneByDailyExamPoint(string $daily_exam_point) Return the first FormulaDetail filtered by the daily_exam_point column
 * @method FormulaDetail findOneByMidExamPoint(string $mid_exam_point) Return the first FormulaDetail filtered by the mid_exam_point column
 * @method FormulaDetail findOneByActivityPoint(string $activity_point) Return the first FormulaDetail filtered by the activity_point column
 * @method FormulaDetail findOneByCreatedAt(string $created_at) Return the first FormulaDetail filtered by the created_at column
 * @method FormulaDetail findOneByUpdatedAt(string $updated_at) Return the first FormulaDetail filtered by the updated_at column
 *
 * @method array findById(int $id) Return FormulaDetail objects filtered by the id column
 * @method array findByFormulaId(int $formula_id) Return FormulaDetail objects filtered by the formula_id column
 * @method array findByFinalExamPoint(string $final_exam_point) Return FormulaDetail objects filtered by the final_exam_point column
 * @method array findByDailyExamPoint(string $daily_exam_point) Return FormulaDetail objects filtered by the daily_exam_point column
 * @method array findByMidExamPoint(string $mid_exam_point) Return FormulaDetail objects filtered by the mid_exam_point column
 * @method array findByActivityPoint(string $activity_point) Return FormulaDetail objects filtered by the activity_point column
 * @method array findByCreatedAt(string $created_at) Return FormulaDetail objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return FormulaDetail objects filtered by the updated_at column
 */
abstract class BaseFormulaDetailQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseFormulaDetailQuery object.
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
            $modelName = 'PGS\\CoreDomainBundle\\Model\\FormulaDetail\\FormulaDetail';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new FormulaDetailQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   FormulaDetailQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return FormulaDetailQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof FormulaDetailQuery) {
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
     * @return   FormulaDetail|FormulaDetail[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = FormulaDetailPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(FormulaDetailPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 FormulaDetail A model object, or null if the key is not found
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
     * @return                 FormulaDetail A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `formula_id`, `final_exam_point`, `daily_exam_point`, `mid_exam_point`, `activity_point`, `created_at`, `updated_at` FROM `formula_detail` WHERE `id` = :p0';
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
            $cls = FormulaDetailPeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            FormulaDetailPeer::addInstanceToPool($obj, (string) $key);
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
     * @return FormulaDetail|FormulaDetail[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|FormulaDetail[]|mixed the list of results, formatted by the current formatter
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
     * @return FormulaDetailQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(FormulaDetailPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return FormulaDetailQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(FormulaDetailPeer::ID, $keys, Criteria::IN);
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
     * @return FormulaDetailQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(FormulaDetailPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(FormulaDetailPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FormulaDetailPeer::ID, $id, $comparison);
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
     * @see       filterByFormulaRelatedByFormulaId()
     *
     * @param     mixed $formulaId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FormulaDetailQuery The current query, for fluid interface
     */
    public function filterByFormulaId($formulaId = null, $comparison = null)
    {
        if (is_array($formulaId)) {
            $useMinMax = false;
            if (isset($formulaId['min'])) {
                $this->addUsingAlias(FormulaDetailPeer::FORMULA_ID, $formulaId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($formulaId['max'])) {
                $this->addUsingAlias(FormulaDetailPeer::FORMULA_ID, $formulaId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FormulaDetailPeer::FORMULA_ID, $formulaId, $comparison);
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
     * @return FormulaDetailQuery The current query, for fluid interface
     */
    public function filterByFinalExamPoint($finalExamPoint = null, $comparison = null)
    {
        if (is_array($finalExamPoint)) {
            $useMinMax = false;
            if (isset($finalExamPoint['min'])) {
                $this->addUsingAlias(FormulaDetailPeer::FINAL_EXAM_POINT, $finalExamPoint['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($finalExamPoint['max'])) {
                $this->addUsingAlias(FormulaDetailPeer::FINAL_EXAM_POINT, $finalExamPoint['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FormulaDetailPeer::FINAL_EXAM_POINT, $finalExamPoint, $comparison);
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
     * @return FormulaDetailQuery The current query, for fluid interface
     */
    public function filterByDailyExamPoint($dailyExamPoint = null, $comparison = null)
    {
        if (is_array($dailyExamPoint)) {
            $useMinMax = false;
            if (isset($dailyExamPoint['min'])) {
                $this->addUsingAlias(FormulaDetailPeer::DAILY_EXAM_POINT, $dailyExamPoint['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dailyExamPoint['max'])) {
                $this->addUsingAlias(FormulaDetailPeer::DAILY_EXAM_POINT, $dailyExamPoint['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FormulaDetailPeer::DAILY_EXAM_POINT, $dailyExamPoint, $comparison);
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
     * @return FormulaDetailQuery The current query, for fluid interface
     */
    public function filterByMidExamPoint($midExamPoint = null, $comparison = null)
    {
        if (is_array($midExamPoint)) {
            $useMinMax = false;
            if (isset($midExamPoint['min'])) {
                $this->addUsingAlias(FormulaDetailPeer::MID_EXAM_POINT, $midExamPoint['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($midExamPoint['max'])) {
                $this->addUsingAlias(FormulaDetailPeer::MID_EXAM_POINT, $midExamPoint['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FormulaDetailPeer::MID_EXAM_POINT, $midExamPoint, $comparison);
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
     * @return FormulaDetailQuery The current query, for fluid interface
     */
    public function filterByActivityPoint($activityPoint = null, $comparison = null)
    {
        if (is_array($activityPoint)) {
            $useMinMax = false;
            if (isset($activityPoint['min'])) {
                $this->addUsingAlias(FormulaDetailPeer::ACTIVITY_POINT, $activityPoint['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($activityPoint['max'])) {
                $this->addUsingAlias(FormulaDetailPeer::ACTIVITY_POINT, $activityPoint['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FormulaDetailPeer::ACTIVITY_POINT, $activityPoint, $comparison);
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
     * @return FormulaDetailQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(FormulaDetailPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(FormulaDetailPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FormulaDetailPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return FormulaDetailQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(FormulaDetailPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(FormulaDetailPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FormulaDetailPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Formula object
     *
     * @param   Formula|PropelObjectCollection $formula The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FormulaDetailQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFormulaRelatedByFormulaId($formula, $comparison = null)
    {
        if ($formula instanceof Formula) {
            return $this
                ->addUsingAlias(FormulaDetailPeer::FORMULA_ID, $formula->getId(), $comparison);
        } elseif ($formula instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FormulaDetailPeer::FORMULA_ID, $formula->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByFormulaRelatedByFormulaId() only accepts arguments of type Formula or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FormulaRelatedByFormulaId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FormulaDetailQuery The current query, for fluid interface
     */
    public function joinFormulaRelatedByFormulaId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FormulaRelatedByFormulaId');

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
            $this->addJoinObject($join, 'FormulaRelatedByFormulaId');
        }

        return $this;
    }

    /**
     * Use the FormulaRelatedByFormulaId relation Formula object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\Formula\FormulaQuery A secondary query class using the current class as primary query
     */
    public function useFormulaRelatedByFormulaIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFormulaRelatedByFormulaId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FormulaRelatedByFormulaId', '\PGS\CoreDomainBundle\Model\Formula\FormulaQuery');
    }

    /**
     * Filter the query by a related Formula object
     *
     * @param   Formula|PropelObjectCollection $formula  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FormulaDetailQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFormulaRelatedByFormulaDetailId($formula, $comparison = null)
    {
        if ($formula instanceof Formula) {
            return $this
                ->addUsingAlias(FormulaDetailPeer::ID, $formula->getFormulaDetailId(), $comparison);
        } elseif ($formula instanceof PropelObjectCollection) {
            return $this
                ->useFormulaRelatedByFormulaDetailIdQuery()
                ->filterByPrimaryKeys($formula->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFormulaRelatedByFormulaDetailId() only accepts arguments of type Formula or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FormulaRelatedByFormulaDetailId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FormulaDetailQuery The current query, for fluid interface
     */
    public function joinFormulaRelatedByFormulaDetailId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FormulaRelatedByFormulaDetailId');

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
            $this->addJoinObject($join, 'FormulaRelatedByFormulaDetailId');
        }

        return $this;
    }

    /**
     * Use the FormulaRelatedByFormulaDetailId relation Formula object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\Formula\FormulaQuery A secondary query class using the current class as primary query
     */
    public function useFormulaRelatedByFormulaDetailIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFormulaRelatedByFormulaDetailId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FormulaRelatedByFormulaDetailId', '\PGS\CoreDomainBundle\Model\Formula\FormulaQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   FormulaDetail $formulaDetail Object to remove from the list of results
     *
     * @return FormulaDetailQuery The current query, for fluid interface
     */
    public function prune($formulaDetail = null)
    {
        if ($formulaDetail) {
            $this->addUsingAlias(FormulaDetailPeer::ID, $formulaDetail->getId(), Criteria::NOT_EQUAL);
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
     * @return     FormulaDetailQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(FormulaDetailPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     FormulaDetailQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(FormulaDetailPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     FormulaDetailQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(FormulaDetailPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     FormulaDetailQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(FormulaDetailPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     FormulaDetailQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(FormulaDetailPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     FormulaDetailQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(FormulaDetailPeer::CREATED_AT);
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
