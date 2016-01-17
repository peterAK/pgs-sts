<?php

namespace PGS\CoreDomainBundle\Model\SchoolHealth\om;

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
use PGS\CoreDomainBundle\Model\Application\Application;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealth;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealthPeer;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealthQuery;
use PGS\CoreDomainBundle\Model\Student\Student;
use PGS\CoreDomainBundle\Model\StudentCondition\StudentCondition;
use PGS\CoreDomainBundle\Model\StudentMedical\StudentMedical;

/**
 * @method SchoolHealthQuery orderById($order = Criteria::ASC) Order by the id column
 * @method SchoolHealthQuery orderByApplicationId($order = Criteria::ASC) Order by the application_id column
 * @method SchoolHealthQuery orderByStudentName($order = Criteria::ASC) Order by the student_name column
 * @method SchoolHealthQuery orderByEmergencyPhysicianName($order = Criteria::ASC) Order by the emergency_physician_name column
 * @method SchoolHealthQuery orderByEmergencyRelationship($order = Criteria::ASC) Order by the emergency_relationship column
 * @method SchoolHealthQuery orderByEmergencyPhone($order = Criteria::ASC) Order by the emergency_phone column
 * @method SchoolHealthQuery orderByAllergies($order = Criteria::ASC) Order by the allergies column
 * @method SchoolHealthQuery orderByAllergiesYes($order = Criteria::ASC) Order by the allergies_yes column
 * @method SchoolHealthQuery orderByAllergiesAction($order = Criteria::ASC) Order by the allergies_action column
 * @method SchoolHealthQuery orderByConditionChoice($order = Criteria::ASC) Order by the condition_choice column
 * @method SchoolHealthQuery orderByConditionExp($order = Criteria::ASC) Order by the condition_exp column
 * @method SchoolHealthQuery orderByStudentPsychological($order = Criteria::ASC) Order by the student_psychological column
 * @method SchoolHealthQuery orderByPsychologicalExp($order = Criteria::ASC) Order by the psychological_exp column
 * @method SchoolHealthQuery orderByStudentAware($order = Criteria::ASC) Order by the student_aware column
 * @method SchoolHealthQuery orderByAwareExp($order = Criteria::ASC) Order by the aware_exp column
 * @method SchoolHealthQuery orderByStudentAbility($order = Criteria::ASC) Order by the student_ability column
 * @method SchoolHealthQuery orderByStudentMedicine($order = Criteria::ASC) Order by the student_medicine column
 * @method SchoolHealthQuery orderByMedicalEmergencyName($order = Criteria::ASC) Order by the medical_emergency_name column
 * @method SchoolHealthQuery orderByMedicalEmergencyPhone($order = Criteria::ASC) Order by the medical_emergency_phone column
 * @method SchoolHealthQuery orderByMedicalEmergencyAddress($order = Criteria::ASC) Order by the medical_emergency_address column
 * @method SchoolHealthQuery orderByParentStatementName($order = Criteria::ASC) Order by the parent_statement_name column
 * @method SchoolHealthQuery orderByStudentStatementName($order = Criteria::ASC) Order by the student_statement_name column
 * @method SchoolHealthQuery orderByParentSignature($order = Criteria::ASC) Order by the parent_signature column
 * @method SchoolHealthQuery orderByDateOfSignature($order = Criteria::ASC) Order by the date_of_signature column
 * @method SchoolHealthQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method SchoolHealthQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method SchoolHealthQuery groupById() Group by the id column
 * @method SchoolHealthQuery groupByApplicationId() Group by the application_id column
 * @method SchoolHealthQuery groupByStudentName() Group by the student_name column
 * @method SchoolHealthQuery groupByEmergencyPhysicianName() Group by the emergency_physician_name column
 * @method SchoolHealthQuery groupByEmergencyRelationship() Group by the emergency_relationship column
 * @method SchoolHealthQuery groupByEmergencyPhone() Group by the emergency_phone column
 * @method SchoolHealthQuery groupByAllergies() Group by the allergies column
 * @method SchoolHealthQuery groupByAllergiesYes() Group by the allergies_yes column
 * @method SchoolHealthQuery groupByAllergiesAction() Group by the allergies_action column
 * @method SchoolHealthQuery groupByConditionChoice() Group by the condition_choice column
 * @method SchoolHealthQuery groupByConditionExp() Group by the condition_exp column
 * @method SchoolHealthQuery groupByStudentPsychological() Group by the student_psychological column
 * @method SchoolHealthQuery groupByPsychologicalExp() Group by the psychological_exp column
 * @method SchoolHealthQuery groupByStudentAware() Group by the student_aware column
 * @method SchoolHealthQuery groupByAwareExp() Group by the aware_exp column
 * @method SchoolHealthQuery groupByStudentAbility() Group by the student_ability column
 * @method SchoolHealthQuery groupByStudentMedicine() Group by the student_medicine column
 * @method SchoolHealthQuery groupByMedicalEmergencyName() Group by the medical_emergency_name column
 * @method SchoolHealthQuery groupByMedicalEmergencyPhone() Group by the medical_emergency_phone column
 * @method SchoolHealthQuery groupByMedicalEmergencyAddress() Group by the medical_emergency_address column
 * @method SchoolHealthQuery groupByParentStatementName() Group by the parent_statement_name column
 * @method SchoolHealthQuery groupByStudentStatementName() Group by the student_statement_name column
 * @method SchoolHealthQuery groupByParentSignature() Group by the parent_signature column
 * @method SchoolHealthQuery groupByDateOfSignature() Group by the date_of_signature column
 * @method SchoolHealthQuery groupByCreatedAt() Group by the created_at column
 * @method SchoolHealthQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method SchoolHealthQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method SchoolHealthQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method SchoolHealthQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method SchoolHealthQuery leftJoinApplication($relationAlias = null) Adds a LEFT JOIN clause to the query using the Application relation
 * @method SchoolHealthQuery rightJoinApplication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Application relation
 * @method SchoolHealthQuery innerJoinApplication($relationAlias = null) Adds a INNER JOIN clause to the query using the Application relation
 *
 * @method SchoolHealthQuery leftJoinStudent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Student relation
 * @method SchoolHealthQuery rightJoinStudent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Student relation
 * @method SchoolHealthQuery innerJoinStudent($relationAlias = null) Adds a INNER JOIN clause to the query using the Student relation
 *
 * @method SchoolHealthQuery leftJoinStudentCondition($relationAlias = null) Adds a LEFT JOIN clause to the query using the StudentCondition relation
 * @method SchoolHealthQuery rightJoinStudentCondition($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StudentCondition relation
 * @method SchoolHealthQuery innerJoinStudentCondition($relationAlias = null) Adds a INNER JOIN clause to the query using the StudentCondition relation
 *
 * @method SchoolHealthQuery leftJoinStudentMedical($relationAlias = null) Adds a LEFT JOIN clause to the query using the StudentMedical relation
 * @method SchoolHealthQuery rightJoinStudentMedical($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StudentMedical relation
 * @method SchoolHealthQuery innerJoinStudentMedical($relationAlias = null) Adds a INNER JOIN clause to the query using the StudentMedical relation
 *
 * @method SchoolHealth findOne(PropelPDO $con = null) Return the first SchoolHealth matching the query
 * @method SchoolHealth findOneOrCreate(PropelPDO $con = null) Return the first SchoolHealth matching the query, or a new SchoolHealth object populated from the query conditions when no match is found
 *
 * @method SchoolHealth findOneByApplicationId(int $application_id) Return the first SchoolHealth filtered by the application_id column
 * @method SchoolHealth findOneByStudentName(string $student_name) Return the first SchoolHealth filtered by the student_name column
 * @method SchoolHealth findOneByEmergencyPhysicianName(string $emergency_physician_name) Return the first SchoolHealth filtered by the emergency_physician_name column
 * @method SchoolHealth findOneByEmergencyRelationship(string $emergency_relationship) Return the first SchoolHealth filtered by the emergency_relationship column
 * @method SchoolHealth findOneByEmergencyPhone(string $emergency_phone) Return the first SchoolHealth filtered by the emergency_phone column
 * @method SchoolHealth findOneByAllergies(boolean $allergies) Return the first SchoolHealth filtered by the allergies column
 * @method SchoolHealth findOneByAllergiesYes(string $allergies_yes) Return the first SchoolHealth filtered by the allergies_yes column
 * @method SchoolHealth findOneByAllergiesAction(string $allergies_action) Return the first SchoolHealth filtered by the allergies_action column
 * @method SchoolHealth findOneByConditionChoice(string $condition_choice) Return the first SchoolHealth filtered by the condition_choice column
 * @method SchoolHealth findOneByConditionExp(string $condition_exp) Return the first SchoolHealth filtered by the condition_exp column
 * @method SchoolHealth findOneByStudentPsychological(boolean $student_psychological) Return the first SchoolHealth filtered by the student_psychological column
 * @method SchoolHealth findOneByPsychologicalExp(string $psychological_exp) Return the first SchoolHealth filtered by the psychological_exp column
 * @method SchoolHealth findOneByStudentAware(boolean $student_aware) Return the first SchoolHealth filtered by the student_aware column
 * @method SchoolHealth findOneByAwareExp(string $aware_exp) Return the first SchoolHealth filtered by the aware_exp column
 * @method SchoolHealth findOneByStudentAbility(boolean $student_ability) Return the first SchoolHealth filtered by the student_ability column
 * @method SchoolHealth findOneByStudentMedicine(string $student_medicine) Return the first SchoolHealth filtered by the student_medicine column
 * @method SchoolHealth findOneByMedicalEmergencyName(string $medical_emergency_name) Return the first SchoolHealth filtered by the medical_emergency_name column
 * @method SchoolHealth findOneByMedicalEmergencyPhone(string $medical_emergency_phone) Return the first SchoolHealth filtered by the medical_emergency_phone column
 * @method SchoolHealth findOneByMedicalEmergencyAddress(string $medical_emergency_address) Return the first SchoolHealth filtered by the medical_emergency_address column
 * @method SchoolHealth findOneByParentStatementName(string $parent_statement_name) Return the first SchoolHealth filtered by the parent_statement_name column
 * @method SchoolHealth findOneByStudentStatementName(string $student_statement_name) Return the first SchoolHealth filtered by the student_statement_name column
 * @method SchoolHealth findOneByParentSignature(string $parent_signature) Return the first SchoolHealth filtered by the parent_signature column
 * @method SchoolHealth findOneByDateOfSignature(string $date_of_signature) Return the first SchoolHealth filtered by the date_of_signature column
 * @method SchoolHealth findOneByCreatedAt(string $created_at) Return the first SchoolHealth filtered by the created_at column
 * @method SchoolHealth findOneByUpdatedAt(string $updated_at) Return the first SchoolHealth filtered by the updated_at column
 *
 * @method array findById(int $id) Return SchoolHealth objects filtered by the id column
 * @method array findByApplicationId(int $application_id) Return SchoolHealth objects filtered by the application_id column
 * @method array findByStudentName(string $student_name) Return SchoolHealth objects filtered by the student_name column
 * @method array findByEmergencyPhysicianName(string $emergency_physician_name) Return SchoolHealth objects filtered by the emergency_physician_name column
 * @method array findByEmergencyRelationship(string $emergency_relationship) Return SchoolHealth objects filtered by the emergency_relationship column
 * @method array findByEmergencyPhone(string $emergency_phone) Return SchoolHealth objects filtered by the emergency_phone column
 * @method array findByAllergies(boolean $allergies) Return SchoolHealth objects filtered by the allergies column
 * @method array findByAllergiesYes(string $allergies_yes) Return SchoolHealth objects filtered by the allergies_yes column
 * @method array findByAllergiesAction(string $allergies_action) Return SchoolHealth objects filtered by the allergies_action column
 * @method array findByConditionChoice(string $condition_choice) Return SchoolHealth objects filtered by the condition_choice column
 * @method array findByConditionExp(string $condition_exp) Return SchoolHealth objects filtered by the condition_exp column
 * @method array findByStudentPsychological(boolean $student_psychological) Return SchoolHealth objects filtered by the student_psychological column
 * @method array findByPsychologicalExp(string $psychological_exp) Return SchoolHealth objects filtered by the psychological_exp column
 * @method array findByStudentAware(boolean $student_aware) Return SchoolHealth objects filtered by the student_aware column
 * @method array findByAwareExp(string $aware_exp) Return SchoolHealth objects filtered by the aware_exp column
 * @method array findByStudentAbility(boolean $student_ability) Return SchoolHealth objects filtered by the student_ability column
 * @method array findByStudentMedicine(string $student_medicine) Return SchoolHealth objects filtered by the student_medicine column
 * @method array findByMedicalEmergencyName(string $medical_emergency_name) Return SchoolHealth objects filtered by the medical_emergency_name column
 * @method array findByMedicalEmergencyPhone(string $medical_emergency_phone) Return SchoolHealth objects filtered by the medical_emergency_phone column
 * @method array findByMedicalEmergencyAddress(string $medical_emergency_address) Return SchoolHealth objects filtered by the medical_emergency_address column
 * @method array findByParentStatementName(string $parent_statement_name) Return SchoolHealth objects filtered by the parent_statement_name column
 * @method array findByStudentStatementName(string $student_statement_name) Return SchoolHealth objects filtered by the student_statement_name column
 * @method array findByParentSignature(string $parent_signature) Return SchoolHealth objects filtered by the parent_signature column
 * @method array findByDateOfSignature(string $date_of_signature) Return SchoolHealth objects filtered by the date_of_signature column
 * @method array findByCreatedAt(string $created_at) Return SchoolHealth objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return SchoolHealth objects filtered by the updated_at column
 */
abstract class BaseSchoolHealthQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseSchoolHealthQuery object.
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
            $modelName = 'PGS\\CoreDomainBundle\\Model\\SchoolHealth\\SchoolHealth';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new SchoolHealthQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   SchoolHealthQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return SchoolHealthQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof SchoolHealthQuery) {
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
     * @return   SchoolHealth|SchoolHealth[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SchoolHealthPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(SchoolHealthPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 SchoolHealth A model object, or null if the key is not found
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
     * @return                 SchoolHealth A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `application_id`, `student_name`, `emergency_physician_name`, `emergency_relationship`, `emergency_phone`, `allergies`, `allergies_yes`, `allergies_action`, `condition_choice`, `condition_exp`, `student_psychological`, `psychological_exp`, `student_aware`, `aware_exp`, `student_ability`, `student_medicine`, `medical_emergency_name`, `medical_emergency_phone`, `medical_emergency_address`, `parent_statement_name`, `student_statement_name`, `parent_signature`, `date_of_signature`, `created_at`, `updated_at` FROM `school_health` WHERE `id` = :p0';
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
            $cls = SchoolHealthPeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            SchoolHealthPeer::addInstanceToPool($obj, (string) $key);
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
     * @return SchoolHealth|SchoolHealth[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|SchoolHealth[]|mixed the list of results, formatted by the current formatter
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
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SchoolHealthPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SchoolHealthPeer::ID, $keys, Criteria::IN);
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
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SchoolHealthPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SchoolHealthPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolHealthPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the application_id column
     *
     * Example usage:
     * <code>
     * $query->filterByApplicationId(1234); // WHERE application_id = 1234
     * $query->filterByApplicationId(array(12, 34)); // WHERE application_id IN (12, 34)
     * $query->filterByApplicationId(array('min' => 12)); // WHERE application_id >= 12
     * $query->filterByApplicationId(array('max' => 12)); // WHERE application_id <= 12
     * </code>
     *
     * @see       filterByApplication()
     *
     * @param     mixed $applicationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByApplicationId($applicationId = null, $comparison = null)
    {
        if (is_array($applicationId)) {
            $useMinMax = false;
            if (isset($applicationId['min'])) {
                $this->addUsingAlias(SchoolHealthPeer::APPLICATION_ID, $applicationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($applicationId['max'])) {
                $this->addUsingAlias(SchoolHealthPeer::APPLICATION_ID, $applicationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolHealthPeer::APPLICATION_ID, $applicationId, $comparison);
    }

    /**
     * Filter the query on the student_name column
     *
     * Example usage:
     * <code>
     * $query->filterByStudentName('fooValue');   // WHERE student_name = 'fooValue'
     * $query->filterByStudentName('%fooValue%'); // WHERE student_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $studentName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByStudentName($studentName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($studentName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $studentName)) {
                $studentName = str_replace('*', '%', $studentName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolHealthPeer::STUDENT_NAME, $studentName, $comparison);
    }

    /**
     * Filter the query on the emergency_physician_name column
     *
     * Example usage:
     * <code>
     * $query->filterByEmergencyPhysicianName('fooValue');   // WHERE emergency_physician_name = 'fooValue'
     * $query->filterByEmergencyPhysicianName('%fooValue%'); // WHERE emergency_physician_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $emergencyPhysicianName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByEmergencyPhysicianName($emergencyPhysicianName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($emergencyPhysicianName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $emergencyPhysicianName)) {
                $emergencyPhysicianName = str_replace('*', '%', $emergencyPhysicianName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolHealthPeer::EMERGENCY_PHYSICIAN_NAME, $emergencyPhysicianName, $comparison);
    }

    /**
     * Filter the query on the emergency_relationship column
     *
     * Example usage:
     * <code>
     * $query->filterByEmergencyRelationship('fooValue');   // WHERE emergency_relationship = 'fooValue'
     * $query->filterByEmergencyRelationship('%fooValue%'); // WHERE emergency_relationship LIKE '%fooValue%'
     * </code>
     *
     * @param     string $emergencyRelationship The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByEmergencyRelationship($emergencyRelationship = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($emergencyRelationship)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $emergencyRelationship)) {
                $emergencyRelationship = str_replace('*', '%', $emergencyRelationship);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolHealthPeer::EMERGENCY_RELATIONSHIP, $emergencyRelationship, $comparison);
    }

    /**
     * Filter the query on the emergency_phone column
     *
     * Example usage:
     * <code>
     * $query->filterByEmergencyPhone('fooValue');   // WHERE emergency_phone = 'fooValue'
     * $query->filterByEmergencyPhone('%fooValue%'); // WHERE emergency_phone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $emergencyPhone The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByEmergencyPhone($emergencyPhone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($emergencyPhone)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $emergencyPhone)) {
                $emergencyPhone = str_replace('*', '%', $emergencyPhone);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolHealthPeer::EMERGENCY_PHONE, $emergencyPhone, $comparison);
    }

    /**
     * Filter the query on the allergies column
     *
     * Example usage:
     * <code>
     * $query->filterByAllergies(true); // WHERE allergies = true
     * $query->filterByAllergies('yes'); // WHERE allergies = true
     * </code>
     *
     * @param     boolean|string $allergies The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByAllergies($allergies = null, $comparison = null)
    {
        if (is_string($allergies)) {
            $allergies = in_array(strtolower($allergies), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SchoolHealthPeer::ALLERGIES, $allergies, $comparison);
    }

    /**
     * Filter the query on the allergies_yes column
     *
     * Example usage:
     * <code>
     * $query->filterByAllergiesYes('fooValue');   // WHERE allergies_yes = 'fooValue'
     * $query->filterByAllergiesYes('%fooValue%'); // WHERE allergies_yes LIKE '%fooValue%'
     * </code>
     *
     * @param     string $allergiesYes The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByAllergiesYes($allergiesYes = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($allergiesYes)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $allergiesYes)) {
                $allergiesYes = str_replace('*', '%', $allergiesYes);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolHealthPeer::ALLERGIES_YES, $allergiesYes, $comparison);
    }

    /**
     * Filter the query on the allergies_action column
     *
     * Example usage:
     * <code>
     * $query->filterByAllergiesAction('fooValue');   // WHERE allergies_action = 'fooValue'
     * $query->filterByAllergiesAction('%fooValue%'); // WHERE allergies_action LIKE '%fooValue%'
     * </code>
     *
     * @param     string $allergiesAction The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByAllergiesAction($allergiesAction = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($allergiesAction)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $allergiesAction)) {
                $allergiesAction = str_replace('*', '%', $allergiesAction);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolHealthPeer::ALLERGIES_ACTION, $allergiesAction, $comparison);
    }

    /**
     * Filter the query on the condition_choice column
     *
     * Example usage:
     * <code>
     * $query->filterByConditionChoice('fooValue');   // WHERE condition_choice = 'fooValue'
     * $query->filterByConditionChoice('%fooValue%'); // WHERE condition_choice LIKE '%fooValue%'
     * </code>
     *
     * @param     string $conditionChoice The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByConditionChoice($conditionChoice = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($conditionChoice)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $conditionChoice)) {
                $conditionChoice = str_replace('*', '%', $conditionChoice);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolHealthPeer::CONDITION_CHOICE, $conditionChoice, $comparison);
    }

    /**
     * Filter the query on the condition_exp column
     *
     * Example usage:
     * <code>
     * $query->filterByConditionExp('fooValue');   // WHERE condition_exp = 'fooValue'
     * $query->filterByConditionExp('%fooValue%'); // WHERE condition_exp LIKE '%fooValue%'
     * </code>
     *
     * @param     string $conditionExp The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByConditionExp($conditionExp = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($conditionExp)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $conditionExp)) {
                $conditionExp = str_replace('*', '%', $conditionExp);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolHealthPeer::CONDITION_EXP, $conditionExp, $comparison);
    }

    /**
     * Filter the query on the student_psychological column
     *
     * Example usage:
     * <code>
     * $query->filterByStudentPsychological(true); // WHERE student_psychological = true
     * $query->filterByStudentPsychological('yes'); // WHERE student_psychological = true
     * </code>
     *
     * @param     boolean|string $studentPsychological The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByStudentPsychological($studentPsychological = null, $comparison = null)
    {
        if (is_string($studentPsychological)) {
            $studentPsychological = in_array(strtolower($studentPsychological), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SchoolHealthPeer::STUDENT_PSYCHOLOGICAL, $studentPsychological, $comparison);
    }

    /**
     * Filter the query on the psychological_exp column
     *
     * Example usage:
     * <code>
     * $query->filterByPsychologicalExp('fooValue');   // WHERE psychological_exp = 'fooValue'
     * $query->filterByPsychologicalExp('%fooValue%'); // WHERE psychological_exp LIKE '%fooValue%'
     * </code>
     *
     * @param     string $psychologicalExp The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByPsychologicalExp($psychologicalExp = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($psychologicalExp)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $psychologicalExp)) {
                $psychologicalExp = str_replace('*', '%', $psychologicalExp);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolHealthPeer::PSYCHOLOGICAL_EXP, $psychologicalExp, $comparison);
    }

    /**
     * Filter the query on the student_aware column
     *
     * Example usage:
     * <code>
     * $query->filterByStudentAware(true); // WHERE student_aware = true
     * $query->filterByStudentAware('yes'); // WHERE student_aware = true
     * </code>
     *
     * @param     boolean|string $studentAware The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByStudentAware($studentAware = null, $comparison = null)
    {
        if (is_string($studentAware)) {
            $studentAware = in_array(strtolower($studentAware), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SchoolHealthPeer::STUDENT_AWARE, $studentAware, $comparison);
    }

    /**
     * Filter the query on the aware_exp column
     *
     * Example usage:
     * <code>
     * $query->filterByAwareExp('fooValue');   // WHERE aware_exp = 'fooValue'
     * $query->filterByAwareExp('%fooValue%'); // WHERE aware_exp LIKE '%fooValue%'
     * </code>
     *
     * @param     string $awareExp The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByAwareExp($awareExp = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($awareExp)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $awareExp)) {
                $awareExp = str_replace('*', '%', $awareExp);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolHealthPeer::AWARE_EXP, $awareExp, $comparison);
    }

    /**
     * Filter the query on the student_ability column
     *
     * Example usage:
     * <code>
     * $query->filterByStudentAbility(true); // WHERE student_ability = true
     * $query->filterByStudentAbility('yes'); // WHERE student_ability = true
     * </code>
     *
     * @param     boolean|string $studentAbility The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByStudentAbility($studentAbility = null, $comparison = null)
    {
        if (is_string($studentAbility)) {
            $studentAbility = in_array(strtolower($studentAbility), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SchoolHealthPeer::STUDENT_ABILITY, $studentAbility, $comparison);
    }

    /**
     * Filter the query on the student_medicine column
     *
     * Example usage:
     * <code>
     * $query->filterByStudentMedicine('fooValue');   // WHERE student_medicine = 'fooValue'
     * $query->filterByStudentMedicine('%fooValue%'); // WHERE student_medicine LIKE '%fooValue%'
     * </code>
     *
     * @param     string $studentMedicine The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByStudentMedicine($studentMedicine = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($studentMedicine)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $studentMedicine)) {
                $studentMedicine = str_replace('*', '%', $studentMedicine);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolHealthPeer::STUDENT_MEDICINE, $studentMedicine, $comparison);
    }

    /**
     * Filter the query on the medical_emergency_name column
     *
     * Example usage:
     * <code>
     * $query->filterByMedicalEmergencyName('fooValue');   // WHERE medical_emergency_name = 'fooValue'
     * $query->filterByMedicalEmergencyName('%fooValue%'); // WHERE medical_emergency_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $medicalEmergencyName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByMedicalEmergencyName($medicalEmergencyName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($medicalEmergencyName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $medicalEmergencyName)) {
                $medicalEmergencyName = str_replace('*', '%', $medicalEmergencyName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolHealthPeer::MEDICAL_EMERGENCY_NAME, $medicalEmergencyName, $comparison);
    }

    /**
     * Filter the query on the medical_emergency_phone column
     *
     * Example usage:
     * <code>
     * $query->filterByMedicalEmergencyPhone('fooValue');   // WHERE medical_emergency_phone = 'fooValue'
     * $query->filterByMedicalEmergencyPhone('%fooValue%'); // WHERE medical_emergency_phone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $medicalEmergencyPhone The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByMedicalEmergencyPhone($medicalEmergencyPhone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($medicalEmergencyPhone)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $medicalEmergencyPhone)) {
                $medicalEmergencyPhone = str_replace('*', '%', $medicalEmergencyPhone);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolHealthPeer::MEDICAL_EMERGENCY_PHONE, $medicalEmergencyPhone, $comparison);
    }

    /**
     * Filter the query on the medical_emergency_address column
     *
     * Example usage:
     * <code>
     * $query->filterByMedicalEmergencyAddress('fooValue');   // WHERE medical_emergency_address = 'fooValue'
     * $query->filterByMedicalEmergencyAddress('%fooValue%'); // WHERE medical_emergency_address LIKE '%fooValue%'
     * </code>
     *
     * @param     string $medicalEmergencyAddress The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByMedicalEmergencyAddress($medicalEmergencyAddress = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($medicalEmergencyAddress)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $medicalEmergencyAddress)) {
                $medicalEmergencyAddress = str_replace('*', '%', $medicalEmergencyAddress);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolHealthPeer::MEDICAL_EMERGENCY_ADDRESS, $medicalEmergencyAddress, $comparison);
    }

    /**
     * Filter the query on the parent_statement_name column
     *
     * Example usage:
     * <code>
     * $query->filterByParentStatementName('fooValue');   // WHERE parent_statement_name = 'fooValue'
     * $query->filterByParentStatementName('%fooValue%'); // WHERE parent_statement_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $parentStatementName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByParentStatementName($parentStatementName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($parentStatementName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $parentStatementName)) {
                $parentStatementName = str_replace('*', '%', $parentStatementName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolHealthPeer::PARENT_STATEMENT_NAME, $parentStatementName, $comparison);
    }

    /**
     * Filter the query on the student_statement_name column
     *
     * Example usage:
     * <code>
     * $query->filterByStudentStatementName('fooValue');   // WHERE student_statement_name = 'fooValue'
     * $query->filterByStudentStatementName('%fooValue%'); // WHERE student_statement_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $studentStatementName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByStudentStatementName($studentStatementName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($studentStatementName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $studentStatementName)) {
                $studentStatementName = str_replace('*', '%', $studentStatementName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolHealthPeer::STUDENT_STATEMENT_NAME, $studentStatementName, $comparison);
    }

    /**
     * Filter the query on the parent_signature column
     *
     * Example usage:
     * <code>
     * $query->filterByParentSignature('fooValue');   // WHERE parent_signature = 'fooValue'
     * $query->filterByParentSignature('%fooValue%'); // WHERE parent_signature LIKE '%fooValue%'
     * </code>
     *
     * @param     string $parentSignature The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByParentSignature($parentSignature = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($parentSignature)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $parentSignature)) {
                $parentSignature = str_replace('*', '%', $parentSignature);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SchoolHealthPeer::PARENT_SIGNATURE, $parentSignature, $comparison);
    }

    /**
     * Filter the query on the date_of_signature column
     *
     * Example usage:
     * <code>
     * $query->filterByDateOfSignature('2011-03-14'); // WHERE date_of_signature = '2011-03-14'
     * $query->filterByDateOfSignature('now'); // WHERE date_of_signature = '2011-03-14'
     * $query->filterByDateOfSignature(array('max' => 'yesterday')); // WHERE date_of_signature < '2011-03-13'
     * </code>
     *
     * @param     mixed $dateOfSignature The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByDateOfSignature($dateOfSignature = null, $comparison = null)
    {
        if (is_array($dateOfSignature)) {
            $useMinMax = false;
            if (isset($dateOfSignature['min'])) {
                $this->addUsingAlias(SchoolHealthPeer::DATE_OF_SIGNATURE, $dateOfSignature['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateOfSignature['max'])) {
                $this->addUsingAlias(SchoolHealthPeer::DATE_OF_SIGNATURE, $dateOfSignature['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolHealthPeer::DATE_OF_SIGNATURE, $dateOfSignature, $comparison);
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
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(SchoolHealthPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(SchoolHealthPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolHealthPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(SchoolHealthPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(SchoolHealthPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SchoolHealthPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Application object
     *
     * @param   Application|PropelObjectCollection $application The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolHealthQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByApplication($application, $comparison = null)
    {
        if ($application instanceof Application) {
            return $this
                ->addUsingAlias(SchoolHealthPeer::APPLICATION_ID, $application->getId(), $comparison);
        } elseif ($application instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SchoolHealthPeer::APPLICATION_ID, $application->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByApplication() only accepts arguments of type Application or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Application relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function joinApplication($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Application');

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
            $this->addJoinObject($join, 'Application');
        }

        return $this;
    }

    /**
     * Use the Application relation Application object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\Application\ApplicationQuery A secondary query class using the current class as primary query
     */
    public function useApplicationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinApplication($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Application', '\PGS\CoreDomainBundle\Model\Application\ApplicationQuery');
    }

    /**
     * Filter the query by a related Student object
     *
     * @param   Student|PropelObjectCollection $student  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolHealthQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStudent($student, $comparison = null)
    {
        if ($student instanceof Student) {
            return $this
                ->addUsingAlias(SchoolHealthPeer::ID, $student->getHealthId(), $comparison);
        } elseif ($student instanceof PropelObjectCollection) {
            return $this
                ->useStudentQuery()
                ->filterByPrimaryKeys($student->getPrimaryKeys())
                ->endUse();
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
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function joinStudent($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useStudentQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinStudent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Student', '\PGS\CoreDomainBundle\Model\Student\StudentQuery');
    }

    /**
     * Filter the query by a related StudentCondition object
     *
     * @param   StudentCondition|PropelObjectCollection $studentCondition  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolHealthQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStudentCondition($studentCondition, $comparison = null)
    {
        if ($studentCondition instanceof StudentCondition) {
            return $this
                ->addUsingAlias(SchoolHealthPeer::ID, $studentCondition->getSchoolHealthId(), $comparison);
        } elseif ($studentCondition instanceof PropelObjectCollection) {
            return $this
                ->useStudentConditionQuery()
                ->filterByPrimaryKeys($studentCondition->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByStudentCondition() only accepts arguments of type StudentCondition or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the StudentCondition relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function joinStudentCondition($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('StudentCondition');

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
            $this->addJoinObject($join, 'StudentCondition');
        }

        return $this;
    }

    /**
     * Use the StudentCondition relation StudentCondition object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\StudentCondition\StudentConditionQuery A secondary query class using the current class as primary query
     */
    public function useStudentConditionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinStudentCondition($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'StudentCondition', '\PGS\CoreDomainBundle\Model\StudentCondition\StudentConditionQuery');
    }

    /**
     * Filter the query by a related StudentMedical object
     *
     * @param   StudentMedical|PropelObjectCollection $studentMedical  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SchoolHealthQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStudentMedical($studentMedical, $comparison = null)
    {
        if ($studentMedical instanceof StudentMedical) {
            return $this
                ->addUsingAlias(SchoolHealthPeer::ID, $studentMedical->getSchoolHealthId(), $comparison);
        } elseif ($studentMedical instanceof PropelObjectCollection) {
            return $this
                ->useStudentMedicalQuery()
                ->filterByPrimaryKeys($studentMedical->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByStudentMedical() only accepts arguments of type StudentMedical or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the StudentMedical relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function joinStudentMedical($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('StudentMedical');

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
            $this->addJoinObject($join, 'StudentMedical');
        }

        return $this;
    }

    /**
     * Use the StudentMedical relation StudentMedical object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\StudentMedical\StudentMedicalQuery A secondary query class using the current class as primary query
     */
    public function useStudentMedicalQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinStudentMedical($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'StudentMedical', '\PGS\CoreDomainBundle\Model\StudentMedical\StudentMedicalQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   SchoolHealth $schoolHealth Object to remove from the list of results
     *
     * @return SchoolHealthQuery The current query, for fluid interface
     */
    public function prune($schoolHealth = null)
    {
        if ($schoolHealth) {
            $this->addUsingAlias(SchoolHealthPeer::ID, $schoolHealth->getId(), Criteria::NOT_EQUAL);
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
     * @return     SchoolHealthQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(SchoolHealthPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     SchoolHealthQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(SchoolHealthPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     SchoolHealthQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(SchoolHealthPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     SchoolHealthQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(SchoolHealthPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     SchoolHealthQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(SchoolHealthPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     SchoolHealthQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(SchoolHealthPeer::CREATED_AT);
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
