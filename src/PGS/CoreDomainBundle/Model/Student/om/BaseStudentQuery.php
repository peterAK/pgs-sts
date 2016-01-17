<?php

namespace PGS\CoreDomainBundle\Model\Student\om;

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
use PGS\CoreDomainBundle\Model\Application\Application;
use PGS\CoreDomainBundle\Model\ParentStudent\ParentStudent;
use PGS\CoreDomainBundle\Model\SchoolClassCourseStudentBehavior\SchoolClassCourseStudentBehavior;
use PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudent;
use PGS\CoreDomainBundle\Model\SchoolEnrollment\SchoolEnrollment;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealth;
use PGS\CoreDomainBundle\Model\Student\Student;
use PGS\CoreDomainBundle\Model\Student\StudentPeer;
use PGS\CoreDomainBundle\Model\Student\StudentQuery;
use PGS\CoreDomainBundle\Model\StudentHistory\StudentHistory;

/**
 * @method StudentQuery orderById($order = Criteria::ASC) Order by the id column
 * @method StudentQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method StudentQuery orderByApplicationId($order = Criteria::ASC) Order by the application_id column
 * @method StudentQuery orderByHealthId($order = Criteria::ASC) Order by the health_id column
 * @method StudentQuery orderByStudentNationNo($order = Criteria::ASC) Order by the student_nation_no column
 * @method StudentQuery orderByStudentNo($order = Criteria::ASC) Order by the student_no column
 * @method StudentQuery orderByFirstName($order = Criteria::ASC) Order by the first_name column
 * @method StudentQuery orderByMiddleName($order = Criteria::ASC) Order by the middle_name column
 * @method StudentQuery orderByLastName($order = Criteria::ASC) Order by the last_name column
 * @method StudentQuery orderByNickName($order = Criteria::ASC) Order by the nick_name column
 * @method StudentQuery orderByGender($order = Criteria::ASC) Order by the gender column
 * @method StudentQuery orderByPlaceOfBirth($order = Criteria::ASC) Order by the place_of_birth column
 * @method StudentQuery orderByDateOfBirth($order = Criteria::ASC) Order by the date_of_birth column
 * @method StudentQuery orderByReligion($order = Criteria::ASC) Order by the religion column
 * @method StudentQuery orderByPicture($order = Criteria::ASC) Order by the picture column
 * @method StudentQuery orderByBirthCertificate($order = Criteria::ASC) Order by the birth_certificate column
 * @method StudentQuery orderByFamilyCard($order = Criteria::ASC) Order by the family_card column
 * @method StudentQuery orderByGraduationCertificate($order = Criteria::ASC) Order by the graduation_certificate column
 * @method StudentQuery orderByAuthorizationCode($order = Criteria::ASC) Order by the authorization_code column
 * @method StudentQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method StudentQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method StudentQuery groupById() Group by the id column
 * @method StudentQuery groupByUserId() Group by the user_id column
 * @method StudentQuery groupByApplicationId() Group by the application_id column
 * @method StudentQuery groupByHealthId() Group by the health_id column
 * @method StudentQuery groupByStudentNationNo() Group by the student_nation_no column
 * @method StudentQuery groupByStudentNo() Group by the student_no column
 * @method StudentQuery groupByFirstName() Group by the first_name column
 * @method StudentQuery groupByMiddleName() Group by the middle_name column
 * @method StudentQuery groupByLastName() Group by the last_name column
 * @method StudentQuery groupByNickName() Group by the nick_name column
 * @method StudentQuery groupByGender() Group by the gender column
 * @method StudentQuery groupByPlaceOfBirth() Group by the place_of_birth column
 * @method StudentQuery groupByDateOfBirth() Group by the date_of_birth column
 * @method StudentQuery groupByReligion() Group by the religion column
 * @method StudentQuery groupByPicture() Group by the picture column
 * @method StudentQuery groupByBirthCertificate() Group by the birth_certificate column
 * @method StudentQuery groupByFamilyCard() Group by the family_card column
 * @method StudentQuery groupByGraduationCertificate() Group by the graduation_certificate column
 * @method StudentQuery groupByAuthorizationCode() Group by the authorization_code column
 * @method StudentQuery groupByCreatedAt() Group by the created_at column
 * @method StudentQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method StudentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method StudentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method StudentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method StudentQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method StudentQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method StudentQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method StudentQuery leftJoinApplication($relationAlias = null) Adds a LEFT JOIN clause to the query using the Application relation
 * @method StudentQuery rightJoinApplication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Application relation
 * @method StudentQuery innerJoinApplication($relationAlias = null) Adds a INNER JOIN clause to the query using the Application relation
 *
 * @method StudentQuery leftJoinSchoolHealth($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolHealth relation
 * @method StudentQuery rightJoinSchoolHealth($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolHealth relation
 * @method StudentQuery innerJoinSchoolHealth($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolHealth relation
 *
 * @method StudentQuery leftJoinParentStudent($relationAlias = null) Adds a LEFT JOIN clause to the query using the ParentStudent relation
 * @method StudentQuery rightJoinParentStudent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ParentStudent relation
 * @method StudentQuery innerJoinParentStudent($relationAlias = null) Adds a INNER JOIN clause to the query using the ParentStudent relation
 *
 * @method StudentQuery leftJoinSchoolClassCourseStudentBehavior($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolClassCourseStudentBehavior relation
 * @method StudentQuery rightJoinSchoolClassCourseStudentBehavior($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolClassCourseStudentBehavior relation
 * @method StudentQuery innerJoinSchoolClassCourseStudentBehavior($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolClassCourseStudentBehavior relation
 *
 * @method StudentQuery leftJoinSchoolClassStudent($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolClassStudent relation
 * @method StudentQuery rightJoinSchoolClassStudent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolClassStudent relation
 * @method StudentQuery innerJoinSchoolClassStudent($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolClassStudent relation
 *
 * @method StudentQuery leftJoinSchoolEnrollment($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolEnrollment relation
 * @method StudentQuery rightJoinSchoolEnrollment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolEnrollment relation
 * @method StudentQuery innerJoinSchoolEnrollment($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolEnrollment relation
 *
 * @method StudentQuery leftJoinStudentHistory($relationAlias = null) Adds a LEFT JOIN clause to the query using the StudentHistory relation
 * @method StudentQuery rightJoinStudentHistory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StudentHistory relation
 * @method StudentQuery innerJoinStudentHistory($relationAlias = null) Adds a INNER JOIN clause to the query using the StudentHistory relation
 *
 * @method Student findOne(PropelPDO $con = null) Return the first Student matching the query
 * @method Student findOneOrCreate(PropelPDO $con = null) Return the first Student matching the query, or a new Student object populated from the query conditions when no match is found
 *
 * @method Student findOneByUserId(int $user_id) Return the first Student filtered by the user_id column
 * @method Student findOneByApplicationId(int $application_id) Return the first Student filtered by the application_id column
 * @method Student findOneByHealthId(int $health_id) Return the first Student filtered by the health_id column
 * @method Student findOneByStudentNationNo(string $student_nation_no) Return the first Student filtered by the student_nation_no column
 * @method Student findOneByStudentNo(string $student_no) Return the first Student filtered by the student_no column
 * @method Student findOneByFirstName(string $first_name) Return the first Student filtered by the first_name column
 * @method Student findOneByMiddleName(string $middle_name) Return the first Student filtered by the middle_name column
 * @method Student findOneByLastName(string $last_name) Return the first Student filtered by the last_name column
 * @method Student findOneByNickName(string $nick_name) Return the first Student filtered by the nick_name column
 * @method Student findOneByGender(int $gender) Return the first Student filtered by the gender column
 * @method Student findOneByPlaceOfBirth(string $place_of_birth) Return the first Student filtered by the place_of_birth column
 * @method Student findOneByDateOfBirth(string $date_of_birth) Return the first Student filtered by the date_of_birth column
 * @method Student findOneByReligion(int $religion) Return the first Student filtered by the religion column
 * @method Student findOneByPicture(string $picture) Return the first Student filtered by the picture column
 * @method Student findOneByBirthCertificate(string $birth_certificate) Return the first Student filtered by the birth_certificate column
 * @method Student findOneByFamilyCard(string $family_card) Return the first Student filtered by the family_card column
 * @method Student findOneByGraduationCertificate(string $graduation_certificate) Return the first Student filtered by the graduation_certificate column
 * @method Student findOneByAuthorizationCode(string $authorization_code) Return the first Student filtered by the authorization_code column
 * @method Student findOneByCreatedAt(string $created_at) Return the first Student filtered by the created_at column
 * @method Student findOneByUpdatedAt(string $updated_at) Return the first Student filtered by the updated_at column
 *
 * @method array findById(int $id) Return Student objects filtered by the id column
 * @method array findByUserId(int $user_id) Return Student objects filtered by the user_id column
 * @method array findByApplicationId(int $application_id) Return Student objects filtered by the application_id column
 * @method array findByHealthId(int $health_id) Return Student objects filtered by the health_id column
 * @method array findByStudentNationNo(string $student_nation_no) Return Student objects filtered by the student_nation_no column
 * @method array findByStudentNo(string $student_no) Return Student objects filtered by the student_no column
 * @method array findByFirstName(string $first_name) Return Student objects filtered by the first_name column
 * @method array findByMiddleName(string $middle_name) Return Student objects filtered by the middle_name column
 * @method array findByLastName(string $last_name) Return Student objects filtered by the last_name column
 * @method array findByNickName(string $nick_name) Return Student objects filtered by the nick_name column
 * @method array findByGender(int $gender) Return Student objects filtered by the gender column
 * @method array findByPlaceOfBirth(string $place_of_birth) Return Student objects filtered by the place_of_birth column
 * @method array findByDateOfBirth(string $date_of_birth) Return Student objects filtered by the date_of_birth column
 * @method array findByReligion(int $religion) Return Student objects filtered by the religion column
 * @method array findByPicture(string $picture) Return Student objects filtered by the picture column
 * @method array findByBirthCertificate(string $birth_certificate) Return Student objects filtered by the birth_certificate column
 * @method array findByFamilyCard(string $family_card) Return Student objects filtered by the family_card column
 * @method array findByGraduationCertificate(string $graduation_certificate) Return Student objects filtered by the graduation_certificate column
 * @method array findByAuthorizationCode(string $authorization_code) Return Student objects filtered by the authorization_code column
 * @method array findByCreatedAt(string $created_at) Return Student objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Student objects filtered by the updated_at column
 */
abstract class BaseStudentQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseStudentQuery object.
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
            $modelName = 'PGS\\CoreDomainBundle\\Model\\Student\\Student';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new StudentQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   StudentQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return StudentQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof StudentQuery) {
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
     * @return   Student|Student[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = StudentPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(StudentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Student A model object, or null if the key is not found
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
     * @return                 Student A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `user_id`, `application_id`, `health_id`, `student_nation_no`, `student_no`, `first_name`, `middle_name`, `last_name`, `nick_name`, `gender`, `place_of_birth`, `date_of_birth`, `religion`, `picture`, `birth_certificate`, `family_card`, `graduation_certificate`, `authorization_code`, `created_at`, `updated_at` FROM `student` WHERE `id` = :p0';
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
            $cls = StudentPeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            StudentPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Student|Student[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Student[]|mixed the list of results, formatted by the current formatter
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
     * @return StudentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(StudentPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return StudentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(StudentPeer::ID, $keys, Criteria::IN);
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
     * @return StudentQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(StudentPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(StudentPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id >= 12
     * $query->filterByUserId(array('max' => 12)); // WHERE user_id <= 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(StudentPeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(StudentPeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentPeer::USER_ID, $userId, $comparison);
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
     * @return StudentQuery The current query, for fluid interface
     */
    public function filterByApplicationId($applicationId = null, $comparison = null)
    {
        if (is_array($applicationId)) {
            $useMinMax = false;
            if (isset($applicationId['min'])) {
                $this->addUsingAlias(StudentPeer::APPLICATION_ID, $applicationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($applicationId['max'])) {
                $this->addUsingAlias(StudentPeer::APPLICATION_ID, $applicationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentPeer::APPLICATION_ID, $applicationId, $comparison);
    }

    /**
     * Filter the query on the health_id column
     *
     * Example usage:
     * <code>
     * $query->filterByHealthId(1234); // WHERE health_id = 1234
     * $query->filterByHealthId(array(12, 34)); // WHERE health_id IN (12, 34)
     * $query->filterByHealthId(array('min' => 12)); // WHERE health_id >= 12
     * $query->filterByHealthId(array('max' => 12)); // WHERE health_id <= 12
     * </code>
     *
     * @see       filterBySchoolHealth()
     *
     * @param     mixed $healthId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentQuery The current query, for fluid interface
     */
    public function filterByHealthId($healthId = null, $comparison = null)
    {
        if (is_array($healthId)) {
            $useMinMax = false;
            if (isset($healthId['min'])) {
                $this->addUsingAlias(StudentPeer::HEALTH_ID, $healthId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($healthId['max'])) {
                $this->addUsingAlias(StudentPeer::HEALTH_ID, $healthId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentPeer::HEALTH_ID, $healthId, $comparison);
    }

    /**
     * Filter the query on the student_nation_no column
     *
     * Example usage:
     * <code>
     * $query->filterByStudentNationNo('fooValue');   // WHERE student_nation_no = 'fooValue'
     * $query->filterByStudentNationNo('%fooValue%'); // WHERE student_nation_no LIKE '%fooValue%'
     * </code>
     *
     * @param     string $studentNationNo The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentQuery The current query, for fluid interface
     */
    public function filterByStudentNationNo($studentNationNo = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($studentNationNo)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $studentNationNo)) {
                $studentNationNo = str_replace('*', '%', $studentNationNo);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StudentPeer::STUDENT_NATION_NO, $studentNationNo, $comparison);
    }

    /**
     * Filter the query on the student_no column
     *
     * Example usage:
     * <code>
     * $query->filterByStudentNo('fooValue');   // WHERE student_no = 'fooValue'
     * $query->filterByStudentNo('%fooValue%'); // WHERE student_no LIKE '%fooValue%'
     * </code>
     *
     * @param     string $studentNo The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentQuery The current query, for fluid interface
     */
    public function filterByStudentNo($studentNo = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($studentNo)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $studentNo)) {
                $studentNo = str_replace('*', '%', $studentNo);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StudentPeer::STUDENT_NO, $studentNo, $comparison);
    }

    /**
     * Filter the query on the first_name column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstName('fooValue');   // WHERE first_name = 'fooValue'
     * $query->filterByFirstName('%fooValue%'); // WHERE first_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $firstName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentQuery The current query, for fluid interface
     */
    public function filterByFirstName($firstName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $firstName)) {
                $firstName = str_replace('*', '%', $firstName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StudentPeer::FIRST_NAME, $firstName, $comparison);
    }

    /**
     * Filter the query on the middle_name column
     *
     * Example usage:
     * <code>
     * $query->filterByMiddleName('fooValue');   // WHERE middle_name = 'fooValue'
     * $query->filterByMiddleName('%fooValue%'); // WHERE middle_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $middleName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentQuery The current query, for fluid interface
     */
    public function filterByMiddleName($middleName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($middleName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $middleName)) {
                $middleName = str_replace('*', '%', $middleName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StudentPeer::MIDDLE_NAME, $middleName, $comparison);
    }

    /**
     * Filter the query on the last_name column
     *
     * Example usage:
     * <code>
     * $query->filterByLastName('fooValue');   // WHERE last_name = 'fooValue'
     * $query->filterByLastName('%fooValue%'); // WHERE last_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentQuery The current query, for fluid interface
     */
    public function filterByLastName($lastName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $lastName)) {
                $lastName = str_replace('*', '%', $lastName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StudentPeer::LAST_NAME, $lastName, $comparison);
    }

    /**
     * Filter the query on the nick_name column
     *
     * Example usage:
     * <code>
     * $query->filterByNickName('fooValue');   // WHERE nick_name = 'fooValue'
     * $query->filterByNickName('%fooValue%'); // WHERE nick_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nickName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentQuery The current query, for fluid interface
     */
    public function filterByNickName($nickName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nickName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $nickName)) {
                $nickName = str_replace('*', '%', $nickName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StudentPeer::NICK_NAME, $nickName, $comparison);
    }

    /**
     * Filter the query on the gender column
     *
     * @param     mixed $gender The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentQuery The current query, for fluid interface
     * @throws PropelException - if the value is not accepted by the enum.
     */
    public function filterByGender($gender = null, $comparison = null)
    {
        if (is_scalar($gender)) {
            $gender = StudentPeer::getSqlValueForEnum(StudentPeer::GENDER, $gender);
        } elseif (is_array($gender)) {
            $convertedValues = array();
            foreach ($gender as $value) {
                $convertedValues[] = StudentPeer::getSqlValueForEnum(StudentPeer::GENDER, $value);
            }
            $gender = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentPeer::GENDER, $gender, $comparison);
    }

    /**
     * Filter the query on the place_of_birth column
     *
     * Example usage:
     * <code>
     * $query->filterByPlaceOfBirth('fooValue');   // WHERE place_of_birth = 'fooValue'
     * $query->filterByPlaceOfBirth('%fooValue%'); // WHERE place_of_birth LIKE '%fooValue%'
     * </code>
     *
     * @param     string $placeOfBirth The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentQuery The current query, for fluid interface
     */
    public function filterByPlaceOfBirth($placeOfBirth = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($placeOfBirth)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $placeOfBirth)) {
                $placeOfBirth = str_replace('*', '%', $placeOfBirth);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StudentPeer::PLACE_OF_BIRTH, $placeOfBirth, $comparison);
    }

    /**
     * Filter the query on the date_of_birth column
     *
     * Example usage:
     * <code>
     * $query->filterByDateOfBirth('2011-03-14'); // WHERE date_of_birth = '2011-03-14'
     * $query->filterByDateOfBirth('now'); // WHERE date_of_birth = '2011-03-14'
     * $query->filterByDateOfBirth(array('max' => 'yesterday')); // WHERE date_of_birth < '2011-03-13'
     * </code>
     *
     * @param     mixed $dateOfBirth The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentQuery The current query, for fluid interface
     */
    public function filterByDateOfBirth($dateOfBirth = null, $comparison = null)
    {
        if (is_array($dateOfBirth)) {
            $useMinMax = false;
            if (isset($dateOfBirth['min'])) {
                $this->addUsingAlias(StudentPeer::DATE_OF_BIRTH, $dateOfBirth['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateOfBirth['max'])) {
                $this->addUsingAlias(StudentPeer::DATE_OF_BIRTH, $dateOfBirth['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentPeer::DATE_OF_BIRTH, $dateOfBirth, $comparison);
    }

    /**
     * Filter the query on the religion column
     *
     * @param     mixed $religion The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentQuery The current query, for fluid interface
     * @throws PropelException - if the value is not accepted by the enum.
     */
    public function filterByReligion($religion = null, $comparison = null)
    {
        if (is_scalar($religion)) {
            $religion = StudentPeer::getSqlValueForEnum(StudentPeer::RELIGION, $religion);
        } elseif (is_array($religion)) {
            $convertedValues = array();
            foreach ($religion as $value) {
                $convertedValues[] = StudentPeer::getSqlValueForEnum(StudentPeer::RELIGION, $value);
            }
            $religion = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentPeer::RELIGION, $religion, $comparison);
    }

    /**
     * Filter the query on the picture column
     *
     * Example usage:
     * <code>
     * $query->filterByPicture('fooValue');   // WHERE picture = 'fooValue'
     * $query->filterByPicture('%fooValue%'); // WHERE picture LIKE '%fooValue%'
     * </code>
     *
     * @param     string $picture The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentQuery The current query, for fluid interface
     */
    public function filterByPicture($picture = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($picture)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $picture)) {
                $picture = str_replace('*', '%', $picture);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StudentPeer::PICTURE, $picture, $comparison);
    }

    /**
     * Filter the query on the birth_certificate column
     *
     * Example usage:
     * <code>
     * $query->filterByBirthCertificate('fooValue');   // WHERE birth_certificate = 'fooValue'
     * $query->filterByBirthCertificate('%fooValue%'); // WHERE birth_certificate LIKE '%fooValue%'
     * </code>
     *
     * @param     string $birthCertificate The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentQuery The current query, for fluid interface
     */
    public function filterByBirthCertificate($birthCertificate = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($birthCertificate)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $birthCertificate)) {
                $birthCertificate = str_replace('*', '%', $birthCertificate);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StudentPeer::BIRTH_CERTIFICATE, $birthCertificate, $comparison);
    }

    /**
     * Filter the query on the family_card column
     *
     * Example usage:
     * <code>
     * $query->filterByFamilyCard('fooValue');   // WHERE family_card = 'fooValue'
     * $query->filterByFamilyCard('%fooValue%'); // WHERE family_card LIKE '%fooValue%'
     * </code>
     *
     * @param     string $familyCard The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentQuery The current query, for fluid interface
     */
    public function filterByFamilyCard($familyCard = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($familyCard)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $familyCard)) {
                $familyCard = str_replace('*', '%', $familyCard);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StudentPeer::FAMILY_CARD, $familyCard, $comparison);
    }

    /**
     * Filter the query on the graduation_certificate column
     *
     * Example usage:
     * <code>
     * $query->filterByGraduationCertificate('fooValue');   // WHERE graduation_certificate = 'fooValue'
     * $query->filterByGraduationCertificate('%fooValue%'); // WHERE graduation_certificate LIKE '%fooValue%'
     * </code>
     *
     * @param     string $graduationCertificate The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentQuery The current query, for fluid interface
     */
    public function filterByGraduationCertificate($graduationCertificate = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($graduationCertificate)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $graduationCertificate)) {
                $graduationCertificate = str_replace('*', '%', $graduationCertificate);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StudentPeer::GRADUATION_CERTIFICATE, $graduationCertificate, $comparison);
    }

    /**
     * Filter the query on the authorization_code column
     *
     * Example usage:
     * <code>
     * $query->filterByAuthorizationCode('fooValue');   // WHERE authorization_code = 'fooValue'
     * $query->filterByAuthorizationCode('%fooValue%'); // WHERE authorization_code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $authorizationCode The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StudentQuery The current query, for fluid interface
     */
    public function filterByAuthorizationCode($authorizationCode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($authorizationCode)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $authorizationCode)) {
                $authorizationCode = str_replace('*', '%', $authorizationCode);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StudentPeer::AUTHORIZATION_CODE, $authorizationCode, $comparison);
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
     * @return StudentQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(StudentPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(StudentPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return StudentQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(StudentPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(StudentPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StudentPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StudentQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(StudentPeer::USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(StudentPeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type User or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StudentQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\PGS\CoreDomainBundle\Model\UserQuery');
    }

    /**
     * Filter the query by a related Application object
     *
     * @param   Application|PropelObjectCollection $application The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StudentQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByApplication($application, $comparison = null)
    {
        if ($application instanceof Application) {
            return $this
                ->addUsingAlias(StudentPeer::APPLICATION_ID, $application->getId(), $comparison);
        } elseif ($application instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(StudentPeer::APPLICATION_ID, $application->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return StudentQuery The current query, for fluid interface
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
     * Filter the query by a related SchoolHealth object
     *
     * @param   SchoolHealth|PropelObjectCollection $schoolHealth The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StudentQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolHealth($schoolHealth, $comparison = null)
    {
        if ($schoolHealth instanceof SchoolHealth) {
            return $this
                ->addUsingAlias(StudentPeer::HEALTH_ID, $schoolHealth->getId(), $comparison);
        } elseif ($schoolHealth instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(StudentPeer::HEALTH_ID, $schoolHealth->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return StudentQuery The current query, for fluid interface
     */
    public function joinSchoolHealth($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useSchoolHealthQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSchoolHealth($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SchoolHealth', '\PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealthQuery');
    }

    /**
     * Filter the query by a related ParentStudent object
     *
     * @param   ParentStudent|PropelObjectCollection $parentStudent  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StudentQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByParentStudent($parentStudent, $comparison = null)
    {
        if ($parentStudent instanceof ParentStudent) {
            return $this
                ->addUsingAlias(StudentPeer::ID, $parentStudent->getStudentId(), $comparison);
        } elseif ($parentStudent instanceof PropelObjectCollection) {
            return $this
                ->useParentStudentQuery()
                ->filterByPrimaryKeys($parentStudent->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByParentStudent() only accepts arguments of type ParentStudent or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ParentStudent relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StudentQuery The current query, for fluid interface
     */
    public function joinParentStudent($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ParentStudent');

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
            $this->addJoinObject($join, 'ParentStudent');
        }

        return $this;
    }

    /**
     * Use the ParentStudent relation ParentStudent object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\ParentStudent\ParentStudentQuery A secondary query class using the current class as primary query
     */
    public function useParentStudentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinParentStudent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ParentStudent', '\PGS\CoreDomainBundle\Model\ParentStudent\ParentStudentQuery');
    }

    /**
     * Filter the query by a related SchoolClassCourseStudentBehavior object
     *
     * @param   SchoolClassCourseStudentBehavior|PropelObjectCollection $schoolClassCourseStudentBehavior  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StudentQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolClassCourseStudentBehavior($schoolClassCourseStudentBehavior, $comparison = null)
    {
        if ($schoolClassCourseStudentBehavior instanceof SchoolClassCourseStudentBehavior) {
            return $this
                ->addUsingAlias(StudentPeer::ID, $schoolClassCourseStudentBehavior->getStudentId(), $comparison);
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
     * @return StudentQuery The current query, for fluid interface
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
     * Filter the query by a related SchoolClassStudent object
     *
     * @param   SchoolClassStudent|PropelObjectCollection $schoolClassStudent  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StudentQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolClassStudent($schoolClassStudent, $comparison = null)
    {
        if ($schoolClassStudent instanceof SchoolClassStudent) {
            return $this
                ->addUsingAlias(StudentPeer::ID, $schoolClassStudent->getStudentId(), $comparison);
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
     * @return StudentQuery The current query, for fluid interface
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
     * Filter the query by a related SchoolEnrollment object
     *
     * @param   SchoolEnrollment|PropelObjectCollection $schoolEnrollment  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StudentQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolEnrollment($schoolEnrollment, $comparison = null)
    {
        if ($schoolEnrollment instanceof SchoolEnrollment) {
            return $this
                ->addUsingAlias(StudentPeer::ID, $schoolEnrollment->getStudentId(), $comparison);
        } elseif ($schoolEnrollment instanceof PropelObjectCollection) {
            return $this
                ->useSchoolEnrollmentQuery()
                ->filterByPrimaryKeys($schoolEnrollment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySchoolEnrollment() only accepts arguments of type SchoolEnrollment or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SchoolEnrollment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StudentQuery The current query, for fluid interface
     */
    public function joinSchoolEnrollment($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SchoolEnrollment');

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
            $this->addJoinObject($join, 'SchoolEnrollment');
        }

        return $this;
    }

    /**
     * Use the SchoolEnrollment relation SchoolEnrollment object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\SchoolEnrollment\SchoolEnrollmentQuery A secondary query class using the current class as primary query
     */
    public function useSchoolEnrollmentQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSchoolEnrollment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SchoolEnrollment', '\PGS\CoreDomainBundle\Model\SchoolEnrollment\SchoolEnrollmentQuery');
    }

    /**
     * Filter the query by a related StudentHistory object
     *
     * @param   StudentHistory|PropelObjectCollection $studentHistory  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StudentQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStudentHistory($studentHistory, $comparison = null)
    {
        if ($studentHistory instanceof StudentHistory) {
            return $this
                ->addUsingAlias(StudentPeer::ID, $studentHistory->getStudentId(), $comparison);
        } elseif ($studentHistory instanceof PropelObjectCollection) {
            return $this
                ->useStudentHistoryQuery()
                ->filterByPrimaryKeys($studentHistory->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByStudentHistory() only accepts arguments of type StudentHistory or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the StudentHistory relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StudentQuery The current query, for fluid interface
     */
    public function joinStudentHistory($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('StudentHistory');

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
            $this->addJoinObject($join, 'StudentHistory');
        }

        return $this;
    }

    /**
     * Use the StudentHistory relation StudentHistory object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\StudentHistory\StudentHistoryQuery A secondary query class using the current class as primary query
     */
    public function useStudentHistoryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinStudentHistory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'StudentHistory', '\PGS\CoreDomainBundle\Model\StudentHistory\StudentHistoryQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Student $student Object to remove from the list of results
     *
     * @return StudentQuery The current query, for fluid interface
     */
    public function prune($student = null)
    {
        if ($student) {
            $this->addUsingAlias(StudentPeer::ID, $student->getId(), Criteria::NOT_EQUAL);
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
     * @return     StudentQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(StudentPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     StudentQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(StudentPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     StudentQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(StudentPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     StudentQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(StudentPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     StudentQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(StudentPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     StudentQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(StudentPeer::CREATED_AT);
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
