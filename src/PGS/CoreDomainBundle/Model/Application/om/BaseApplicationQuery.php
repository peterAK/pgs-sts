<?php

namespace PGS\CoreDomainBundle\Model\Application\om;

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
use PGS\CoreDomainBundle\Model\Country;
use PGS\CoreDomainBundle\Model\State;
use PGS\CoreDomainBundle\Model\User;
use PGS\CoreDomainBundle\Model\Application\Application;
use PGS\CoreDomainBundle\Model\Application\ApplicationPeer;
use PGS\CoreDomainBundle\Model\Application\ApplicationQuery;
use PGS\CoreDomainBundle\Model\Ethnicity\Ethnicity;
use PGS\CoreDomainBundle\Model\Grade\Grade;
use PGS\CoreDomainBundle\Model\Level\Level;
use PGS\CoreDomainBundle\Model\ParentStudent\ParentStudent;
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealth;
use PGS\CoreDomainBundle\Model\SchoolYear\SchoolYear;
use PGS\CoreDomainBundle\Model\Student\Student;

/**
 * @method ApplicationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method ApplicationQuery orderByFormNo($order = Criteria::ASC) Order by the form_no column
 * @method ApplicationQuery orderByStudentNationNo($order = Criteria::ASC) Order by the student_nation_no column
 * @method ApplicationQuery orderByPriorTestNo($order = Criteria::ASC) Order by the prior_test_no column
 * @method ApplicationQuery orderByStudentNo($order = Criteria::ASC) Order by the student_no column
 * @method ApplicationQuery orderByFirstName($order = Criteria::ASC) Order by the first_name column
 * @method ApplicationQuery orderByLastName($order = Criteria::ASC) Order by the last_name column
 * @method ApplicationQuery orderByNickName($order = Criteria::ASC) Order by the nick_name column
 * @method ApplicationQuery orderByPhoneStudent($order = Criteria::ASC) Order by the phone_student column
 * @method ApplicationQuery orderByGender($order = Criteria::ASC) Order by the gender column
 * @method ApplicationQuery orderByPlaceOfBirth($order = Criteria::ASC) Order by the place_of_birth column
 * @method ApplicationQuery orderByDateOfBirth($order = Criteria::ASC) Order by the date_of_birth column
 * @method ApplicationQuery orderByReligion($order = Criteria::ASC) Order by the religion column
 * @method ApplicationQuery orderByLevelId($order = Criteria::ASC) Order by the level_id column
 * @method ApplicationQuery orderByGradeId($order = Criteria::ASC) Order by the grade_id column
 * @method ApplicationQuery orderByEthnicityId($order = Criteria::ASC) Order by the ethnicity_id column
 * @method ApplicationQuery orderByChildNo($order = Criteria::ASC) Order by the child_no column
 * @method ApplicationQuery orderByChildTotal($order = Criteria::ASC) Order by the child_total column
 * @method ApplicationQuery orderByChildStatus($order = Criteria::ASC) Order by the child_status column
 * @method ApplicationQuery orderByPicture($order = Criteria::ASC) Order by the picture column
 * @method ApplicationQuery orderByBirthCertificate($order = Criteria::ASC) Order by the birth_certificate column
 * @method ApplicationQuery orderByFamilyCard($order = Criteria::ASC) Order by the family_card column
 * @method ApplicationQuery orderByGraduationCertificate($order = Criteria::ASC) Order by the graduation_certificate column
 * @method ApplicationQuery orderByAddress($order = Criteria::ASC) Order by the address column
 * @method ApplicationQuery orderByCity($order = Criteria::ASC) Order by the city column
 * @method ApplicationQuery orderByStateId($order = Criteria::ASC) Order by the state_id column
 * @method ApplicationQuery orderByZip($order = Criteria::ASC) Order by the zip column
 * @method ApplicationQuery orderByCountryId($order = Criteria::ASC) Order by the country_id column
 * @method ApplicationQuery orderBySchoolId($order = Criteria::ASC) Order by the school_id column
 * @method ApplicationQuery orderBySchoolYearId($order = Criteria::ASC) Order by the school_year_id column
 * @method ApplicationQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method ApplicationQuery orderByMailingAddress($order = Criteria::ASC) Order by the mailing_address column
 * @method ApplicationQuery orderByNote($order = Criteria::ASC) Order by the note column
 * @method ApplicationQuery orderByHobby($order = Criteria::ASC) Order by the hobby column
 * @method ApplicationQuery orderByEnteredBy($order = Criteria::ASC) Order by the entered_by column
 * @method ApplicationQuery orderByFirstNameFather($order = Criteria::ASC) Order by the first_name_father column
 * @method ApplicationQuery orderByLastNameFather($order = Criteria::ASC) Order by the last_name_father column
 * @method ApplicationQuery orderByBusinessAddressFather($order = Criteria::ASC) Order by the business_address_father column
 * @method ApplicationQuery orderByOccupationFather($order = Criteria::ASC) Order by the occupation_father column
 * @method ApplicationQuery orderByPhoneFather($order = Criteria::ASC) Order by the phone_father column
 * @method ApplicationQuery orderByEmailFather($order = Criteria::ASC) Order by the email_father column
 * @method ApplicationQuery orderByFirstNameMother($order = Criteria::ASC) Order by the first_name_mother column
 * @method ApplicationQuery orderByLastNameMother($order = Criteria::ASC) Order by the last_name_mother column
 * @method ApplicationQuery orderByBusinessAddressMother($order = Criteria::ASC) Order by the business_address_mother column
 * @method ApplicationQuery orderByOccupationMother($order = Criteria::ASC) Order by the occupation_mother column
 * @method ApplicationQuery orderByPhoneMother($order = Criteria::ASC) Order by the phone_mother column
 * @method ApplicationQuery orderByEmailMother($order = Criteria::ASC) Order by the email_mother column
 * @method ApplicationQuery orderByFirstNameLegalGuardian($order = Criteria::ASC) Order by the first_name_legal_guardian column
 * @method ApplicationQuery orderByLastNameLegalGuardian($order = Criteria::ASC) Order by the last_name_legal_guardian column
 * @method ApplicationQuery orderByOccupationLegalGuardian($order = Criteria::ASC) Order by the occupation_legal_guardian column
 * @method ApplicationQuery orderByPhoneLegalGuardian($order = Criteria::ASC) Order by the phone_legal_guardian column
 * @method ApplicationQuery orderByEmailLegalGuardian($order = Criteria::ASC) Order by the email_legal_guardian column
 * @method ApplicationQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method ApplicationQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method ApplicationQuery groupById() Group by the id column
 * @method ApplicationQuery groupByFormNo() Group by the form_no column
 * @method ApplicationQuery groupByStudentNationNo() Group by the student_nation_no column
 * @method ApplicationQuery groupByPriorTestNo() Group by the prior_test_no column
 * @method ApplicationQuery groupByStudentNo() Group by the student_no column
 * @method ApplicationQuery groupByFirstName() Group by the first_name column
 * @method ApplicationQuery groupByLastName() Group by the last_name column
 * @method ApplicationQuery groupByNickName() Group by the nick_name column
 * @method ApplicationQuery groupByPhoneStudent() Group by the phone_student column
 * @method ApplicationQuery groupByGender() Group by the gender column
 * @method ApplicationQuery groupByPlaceOfBirth() Group by the place_of_birth column
 * @method ApplicationQuery groupByDateOfBirth() Group by the date_of_birth column
 * @method ApplicationQuery groupByReligion() Group by the religion column
 * @method ApplicationQuery groupByLevelId() Group by the level_id column
 * @method ApplicationQuery groupByGradeId() Group by the grade_id column
 * @method ApplicationQuery groupByEthnicityId() Group by the ethnicity_id column
 * @method ApplicationQuery groupByChildNo() Group by the child_no column
 * @method ApplicationQuery groupByChildTotal() Group by the child_total column
 * @method ApplicationQuery groupByChildStatus() Group by the child_status column
 * @method ApplicationQuery groupByPicture() Group by the picture column
 * @method ApplicationQuery groupByBirthCertificate() Group by the birth_certificate column
 * @method ApplicationQuery groupByFamilyCard() Group by the family_card column
 * @method ApplicationQuery groupByGraduationCertificate() Group by the graduation_certificate column
 * @method ApplicationQuery groupByAddress() Group by the address column
 * @method ApplicationQuery groupByCity() Group by the city column
 * @method ApplicationQuery groupByStateId() Group by the state_id column
 * @method ApplicationQuery groupByZip() Group by the zip column
 * @method ApplicationQuery groupByCountryId() Group by the country_id column
 * @method ApplicationQuery groupBySchoolId() Group by the school_id column
 * @method ApplicationQuery groupBySchoolYearId() Group by the school_year_id column
 * @method ApplicationQuery groupByStatus() Group by the status column
 * @method ApplicationQuery groupByMailingAddress() Group by the mailing_address column
 * @method ApplicationQuery groupByNote() Group by the note column
 * @method ApplicationQuery groupByHobby() Group by the hobby column
 * @method ApplicationQuery groupByEnteredBy() Group by the entered_by column
 * @method ApplicationQuery groupByFirstNameFather() Group by the first_name_father column
 * @method ApplicationQuery groupByLastNameFather() Group by the last_name_father column
 * @method ApplicationQuery groupByBusinessAddressFather() Group by the business_address_father column
 * @method ApplicationQuery groupByOccupationFather() Group by the occupation_father column
 * @method ApplicationQuery groupByPhoneFather() Group by the phone_father column
 * @method ApplicationQuery groupByEmailFather() Group by the email_father column
 * @method ApplicationQuery groupByFirstNameMother() Group by the first_name_mother column
 * @method ApplicationQuery groupByLastNameMother() Group by the last_name_mother column
 * @method ApplicationQuery groupByBusinessAddressMother() Group by the business_address_mother column
 * @method ApplicationQuery groupByOccupationMother() Group by the occupation_mother column
 * @method ApplicationQuery groupByPhoneMother() Group by the phone_mother column
 * @method ApplicationQuery groupByEmailMother() Group by the email_mother column
 * @method ApplicationQuery groupByFirstNameLegalGuardian() Group by the first_name_legal_guardian column
 * @method ApplicationQuery groupByLastNameLegalGuardian() Group by the last_name_legal_guardian column
 * @method ApplicationQuery groupByOccupationLegalGuardian() Group by the occupation_legal_guardian column
 * @method ApplicationQuery groupByPhoneLegalGuardian() Group by the phone_legal_guardian column
 * @method ApplicationQuery groupByEmailLegalGuardian() Group by the email_legal_guardian column
 * @method ApplicationQuery groupByCreatedAt() Group by the created_at column
 * @method ApplicationQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method ApplicationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ApplicationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ApplicationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ApplicationQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method ApplicationQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method ApplicationQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method ApplicationQuery leftJoinSchool($relationAlias = null) Adds a LEFT JOIN clause to the query using the School relation
 * @method ApplicationQuery rightJoinSchool($relationAlias = null) Adds a RIGHT JOIN clause to the query using the School relation
 * @method ApplicationQuery innerJoinSchool($relationAlias = null) Adds a INNER JOIN clause to the query using the School relation
 *
 * @method ApplicationQuery leftJoinSchoolYear($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolYear relation
 * @method ApplicationQuery rightJoinSchoolYear($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolYear relation
 * @method ApplicationQuery innerJoinSchoolYear($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolYear relation
 *
 * @method ApplicationQuery leftJoinEthnicity($relationAlias = null) Adds a LEFT JOIN clause to the query using the Ethnicity relation
 * @method ApplicationQuery rightJoinEthnicity($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Ethnicity relation
 * @method ApplicationQuery innerJoinEthnicity($relationAlias = null) Adds a INNER JOIN clause to the query using the Ethnicity relation
 *
 * @method ApplicationQuery leftJoinGrade($relationAlias = null) Adds a LEFT JOIN clause to the query using the Grade relation
 * @method ApplicationQuery rightJoinGrade($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Grade relation
 * @method ApplicationQuery innerJoinGrade($relationAlias = null) Adds a INNER JOIN clause to the query using the Grade relation
 *
 * @method ApplicationQuery leftJoinLevel($relationAlias = null) Adds a LEFT JOIN clause to the query using the Level relation
 * @method ApplicationQuery rightJoinLevel($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Level relation
 * @method ApplicationQuery innerJoinLevel($relationAlias = null) Adds a INNER JOIN clause to the query using the Level relation
 *
 * @method ApplicationQuery leftJoinState($relationAlias = null) Adds a LEFT JOIN clause to the query using the State relation
 * @method ApplicationQuery rightJoinState($relationAlias = null) Adds a RIGHT JOIN clause to the query using the State relation
 * @method ApplicationQuery innerJoinState($relationAlias = null) Adds a INNER JOIN clause to the query using the State relation
 *
 * @method ApplicationQuery leftJoinCountry($relationAlias = null) Adds a LEFT JOIN clause to the query using the Country relation
 * @method ApplicationQuery rightJoinCountry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Country relation
 * @method ApplicationQuery innerJoinCountry($relationAlias = null) Adds a INNER JOIN clause to the query using the Country relation
 *
 * @method ApplicationQuery leftJoinParentStudent($relationAlias = null) Adds a LEFT JOIN clause to the query using the ParentStudent relation
 * @method ApplicationQuery rightJoinParentStudent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ParentStudent relation
 * @method ApplicationQuery innerJoinParentStudent($relationAlias = null) Adds a INNER JOIN clause to the query using the ParentStudent relation
 *
 * @method ApplicationQuery leftJoinSchoolHealth($relationAlias = null) Adds a LEFT JOIN clause to the query using the SchoolHealth relation
 * @method ApplicationQuery rightJoinSchoolHealth($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SchoolHealth relation
 * @method ApplicationQuery innerJoinSchoolHealth($relationAlias = null) Adds a INNER JOIN clause to the query using the SchoolHealth relation
 *
 * @method ApplicationQuery leftJoinStudent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Student relation
 * @method ApplicationQuery rightJoinStudent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Student relation
 * @method ApplicationQuery innerJoinStudent($relationAlias = null) Adds a INNER JOIN clause to the query using the Student relation
 *
 * @method Application findOne(PropelPDO $con = null) Return the first Application matching the query
 * @method Application findOneOrCreate(PropelPDO $con = null) Return the first Application matching the query, or a new Application object populated from the query conditions when no match is found
 *
 * @method Application findOneByFormNo(string $form_no) Return the first Application filtered by the form_no column
 * @method Application findOneByStudentNationNo(string $student_nation_no) Return the first Application filtered by the student_nation_no column
 * @method Application findOneByPriorTestNo(string $prior_test_no) Return the first Application filtered by the prior_test_no column
 * @method Application findOneByStudentNo(string $student_no) Return the first Application filtered by the student_no column
 * @method Application findOneByFirstName(string $first_name) Return the first Application filtered by the first_name column
 * @method Application findOneByLastName(string $last_name) Return the first Application filtered by the last_name column
 * @method Application findOneByNickName(string $nick_name) Return the first Application filtered by the nick_name column
 * @method Application findOneByPhoneStudent(int $phone_student) Return the first Application filtered by the phone_student column
 * @method Application findOneByGender(int $gender) Return the first Application filtered by the gender column
 * @method Application findOneByPlaceOfBirth(string $place_of_birth) Return the first Application filtered by the place_of_birth column
 * @method Application findOneByDateOfBirth(string $date_of_birth) Return the first Application filtered by the date_of_birth column
 * @method Application findOneByReligion(int $religion) Return the first Application filtered by the religion column
 * @method Application findOneByLevelId(int $level_id) Return the first Application filtered by the level_id column
 * @method Application findOneByGradeId(int $grade_id) Return the first Application filtered by the grade_id column
 * @method Application findOneByEthnicityId(int $ethnicity_id) Return the first Application filtered by the ethnicity_id column
 * @method Application findOneByChildNo(int $child_no) Return the first Application filtered by the child_no column
 * @method Application findOneByChildTotal(int $child_total) Return the first Application filtered by the child_total column
 * @method Application findOneByChildStatus(int $child_status) Return the first Application filtered by the child_status column
 * @method Application findOneByPicture(string $picture) Return the first Application filtered by the picture column
 * @method Application findOneByBirthCertificate(string $birth_certificate) Return the first Application filtered by the birth_certificate column
 * @method Application findOneByFamilyCard(string $family_card) Return the first Application filtered by the family_card column
 * @method Application findOneByGraduationCertificate(string $graduation_certificate) Return the first Application filtered by the graduation_certificate column
 * @method Application findOneByAddress(string $address) Return the first Application filtered by the address column
 * @method Application findOneByCity(string $city) Return the first Application filtered by the city column
 * @method Application findOneByStateId(int $state_id) Return the first Application filtered by the state_id column
 * @method Application findOneByZip(string $zip) Return the first Application filtered by the zip column
 * @method Application findOneByCountryId(int $country_id) Return the first Application filtered by the country_id column
 * @method Application findOneBySchoolId(int $school_id) Return the first Application filtered by the school_id column
 * @method Application findOneBySchoolYearId(int $school_year_id) Return the first Application filtered by the school_year_id column
 * @method Application findOneByStatus(int $status) Return the first Application filtered by the status column
 * @method Application findOneByMailingAddress(string $mailing_address) Return the first Application filtered by the mailing_address column
 * @method Application findOneByNote(string $note) Return the first Application filtered by the note column
 * @method Application findOneByHobby(string $hobby) Return the first Application filtered by the hobby column
 * @method Application findOneByEnteredBy(int $entered_by) Return the first Application filtered by the entered_by column
 * @method Application findOneByFirstNameFather(string $first_name_father) Return the first Application filtered by the first_name_father column
 * @method Application findOneByLastNameFather(string $last_name_father) Return the first Application filtered by the last_name_father column
 * @method Application findOneByBusinessAddressFather(string $business_address_father) Return the first Application filtered by the business_address_father column
 * @method Application findOneByOccupationFather(string $occupation_father) Return the first Application filtered by the occupation_father column
 * @method Application findOneByPhoneFather(int $phone_father) Return the first Application filtered by the phone_father column
 * @method Application findOneByEmailFather(string $email_father) Return the first Application filtered by the email_father column
 * @method Application findOneByFirstNameMother(string $first_name_mother) Return the first Application filtered by the first_name_mother column
 * @method Application findOneByLastNameMother(string $last_name_mother) Return the first Application filtered by the last_name_mother column
 * @method Application findOneByBusinessAddressMother(string $business_address_mother) Return the first Application filtered by the business_address_mother column
 * @method Application findOneByOccupationMother(string $occupation_mother) Return the first Application filtered by the occupation_mother column
 * @method Application findOneByPhoneMother(int $phone_mother) Return the first Application filtered by the phone_mother column
 * @method Application findOneByEmailMother(string $email_mother) Return the first Application filtered by the email_mother column
 * @method Application findOneByFirstNameLegalGuardian(string $first_name_legal_guardian) Return the first Application filtered by the first_name_legal_guardian column
 * @method Application findOneByLastNameLegalGuardian(string $last_name_legal_guardian) Return the first Application filtered by the last_name_legal_guardian column
 * @method Application findOneByOccupationLegalGuardian(string $occupation_legal_guardian) Return the first Application filtered by the occupation_legal_guardian column
 * @method Application findOneByPhoneLegalGuardian(int $phone_legal_guardian) Return the first Application filtered by the phone_legal_guardian column
 * @method Application findOneByEmailLegalGuardian(string $email_legal_guardian) Return the first Application filtered by the email_legal_guardian column
 * @method Application findOneByCreatedAt(string $created_at) Return the first Application filtered by the created_at column
 * @method Application findOneByUpdatedAt(string $updated_at) Return the first Application filtered by the updated_at column
 *
 * @method array findById(int $id) Return Application objects filtered by the id column
 * @method array findByFormNo(string $form_no) Return Application objects filtered by the form_no column
 * @method array findByStudentNationNo(string $student_nation_no) Return Application objects filtered by the student_nation_no column
 * @method array findByPriorTestNo(string $prior_test_no) Return Application objects filtered by the prior_test_no column
 * @method array findByStudentNo(string $student_no) Return Application objects filtered by the student_no column
 * @method array findByFirstName(string $first_name) Return Application objects filtered by the first_name column
 * @method array findByLastName(string $last_name) Return Application objects filtered by the last_name column
 * @method array findByNickName(string $nick_name) Return Application objects filtered by the nick_name column
 * @method array findByPhoneStudent(int $phone_student) Return Application objects filtered by the phone_student column
 * @method array findByGender(int $gender) Return Application objects filtered by the gender column
 * @method array findByPlaceOfBirth(string $place_of_birth) Return Application objects filtered by the place_of_birth column
 * @method array findByDateOfBirth(string $date_of_birth) Return Application objects filtered by the date_of_birth column
 * @method array findByReligion(int $religion) Return Application objects filtered by the religion column
 * @method array findByLevelId(int $level_id) Return Application objects filtered by the level_id column
 * @method array findByGradeId(int $grade_id) Return Application objects filtered by the grade_id column
 * @method array findByEthnicityId(int $ethnicity_id) Return Application objects filtered by the ethnicity_id column
 * @method array findByChildNo(int $child_no) Return Application objects filtered by the child_no column
 * @method array findByChildTotal(int $child_total) Return Application objects filtered by the child_total column
 * @method array findByChildStatus(int $child_status) Return Application objects filtered by the child_status column
 * @method array findByPicture(string $picture) Return Application objects filtered by the picture column
 * @method array findByBirthCertificate(string $birth_certificate) Return Application objects filtered by the birth_certificate column
 * @method array findByFamilyCard(string $family_card) Return Application objects filtered by the family_card column
 * @method array findByGraduationCertificate(string $graduation_certificate) Return Application objects filtered by the graduation_certificate column
 * @method array findByAddress(string $address) Return Application objects filtered by the address column
 * @method array findByCity(string $city) Return Application objects filtered by the city column
 * @method array findByStateId(int $state_id) Return Application objects filtered by the state_id column
 * @method array findByZip(string $zip) Return Application objects filtered by the zip column
 * @method array findByCountryId(int $country_id) Return Application objects filtered by the country_id column
 * @method array findBySchoolId(int $school_id) Return Application objects filtered by the school_id column
 * @method array findBySchoolYearId(int $school_year_id) Return Application objects filtered by the school_year_id column
 * @method array findByStatus(int $status) Return Application objects filtered by the status column
 * @method array findByMailingAddress(string $mailing_address) Return Application objects filtered by the mailing_address column
 * @method array findByNote(string $note) Return Application objects filtered by the note column
 * @method array findByHobby(string $hobby) Return Application objects filtered by the hobby column
 * @method array findByEnteredBy(int $entered_by) Return Application objects filtered by the entered_by column
 * @method array findByFirstNameFather(string $first_name_father) Return Application objects filtered by the first_name_father column
 * @method array findByLastNameFather(string $last_name_father) Return Application objects filtered by the last_name_father column
 * @method array findByBusinessAddressFather(string $business_address_father) Return Application objects filtered by the business_address_father column
 * @method array findByOccupationFather(string $occupation_father) Return Application objects filtered by the occupation_father column
 * @method array findByPhoneFather(int $phone_father) Return Application objects filtered by the phone_father column
 * @method array findByEmailFather(string $email_father) Return Application objects filtered by the email_father column
 * @method array findByFirstNameMother(string $first_name_mother) Return Application objects filtered by the first_name_mother column
 * @method array findByLastNameMother(string $last_name_mother) Return Application objects filtered by the last_name_mother column
 * @method array findByBusinessAddressMother(string $business_address_mother) Return Application objects filtered by the business_address_mother column
 * @method array findByOccupationMother(string $occupation_mother) Return Application objects filtered by the occupation_mother column
 * @method array findByPhoneMother(int $phone_mother) Return Application objects filtered by the phone_mother column
 * @method array findByEmailMother(string $email_mother) Return Application objects filtered by the email_mother column
 * @method array findByFirstNameLegalGuardian(string $first_name_legal_guardian) Return Application objects filtered by the first_name_legal_guardian column
 * @method array findByLastNameLegalGuardian(string $last_name_legal_guardian) Return Application objects filtered by the last_name_legal_guardian column
 * @method array findByOccupationLegalGuardian(string $occupation_legal_guardian) Return Application objects filtered by the occupation_legal_guardian column
 * @method array findByPhoneLegalGuardian(int $phone_legal_guardian) Return Application objects filtered by the phone_legal_guardian column
 * @method array findByEmailLegalGuardian(string $email_legal_guardian) Return Application objects filtered by the email_legal_guardian column
 * @method array findByCreatedAt(string $created_at) Return Application objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Application objects filtered by the updated_at column
 */
abstract class BaseApplicationQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseApplicationQuery object.
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
            $modelName = 'PGS\\CoreDomainBundle\\Model\\Application\\Application';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new ApplicationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   ApplicationQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ApplicationQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ApplicationQuery) {
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
     * @return   Application|Application[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ApplicationPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Application A model object, or null if the key is not found
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
     * @return                 Application A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `form_no`, `student_nation_no`, `prior_test_no`, `student_no`, `first_name`, `last_name`, `nick_name`, `phone_student`, `gender`, `place_of_birth`, `date_of_birth`, `religion`, `level_id`, `grade_id`, `ethnicity_id`, `child_no`, `child_total`, `child_status`, `picture`, `birth_certificate`, `family_card`, `graduation_certificate`, `address`, `city`, `state_id`, `zip`, `country_id`, `school_id`, `school_year_id`, `status`, `mailing_address`, `note`, `hobby`, `entered_by`, `first_name_father`, `last_name_father`, `business_address_father`, `occupation_father`, `phone_father`, `email_father`, `first_name_mother`, `last_name_mother`, `business_address_mother`, `occupation_mother`, `phone_mother`, `email_mother`, `first_name_legal_guardian`, `last_name_legal_guardian`, `occupation_legal_guardian`, `phone_legal_guardian`, `email_legal_guardian`, `created_at`, `updated_at` FROM `application` WHERE `id` = :p0';
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
            $cls = ApplicationPeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            ApplicationPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Application|Application[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Application[]|mixed the list of results, formatted by the current formatter
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
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ApplicationPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ApplicationPeer::ID, $keys, Criteria::IN);
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
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ApplicationPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ApplicationPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the form_no column
     *
     * Example usage:
     * <code>
     * $query->filterByFormNo('fooValue');   // WHERE form_no = 'fooValue'
     * $query->filterByFormNo('%fooValue%'); // WHERE form_no LIKE '%fooValue%'
     * </code>
     *
     * @param     string $formNo The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByFormNo($formNo = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($formNo)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $formNo)) {
                $formNo = str_replace('*', '%', $formNo);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::FORM_NO, $formNo, $comparison);
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
     * @return ApplicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ApplicationPeer::STUDENT_NATION_NO, $studentNationNo, $comparison);
    }

    /**
     * Filter the query on the prior_test_no column
     *
     * Example usage:
     * <code>
     * $query->filterByPriorTestNo('fooValue');   // WHERE prior_test_no = 'fooValue'
     * $query->filterByPriorTestNo('%fooValue%'); // WHERE prior_test_no LIKE '%fooValue%'
     * </code>
     *
     * @param     string $priorTestNo The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByPriorTestNo($priorTestNo = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($priorTestNo)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $priorTestNo)) {
                $priorTestNo = str_replace('*', '%', $priorTestNo);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::PRIOR_TEST_NO, $priorTestNo, $comparison);
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
     * @return ApplicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ApplicationPeer::STUDENT_NO, $studentNo, $comparison);
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
     * @return ApplicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ApplicationPeer::FIRST_NAME, $firstName, $comparison);
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
     * @return ApplicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ApplicationPeer::LAST_NAME, $lastName, $comparison);
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
     * @return ApplicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ApplicationPeer::NICK_NAME, $nickName, $comparison);
    }

    /**
     * Filter the query on the phone_student column
     *
     * Example usage:
     * <code>
     * $query->filterByPhoneStudent(1234); // WHERE phone_student = 1234
     * $query->filterByPhoneStudent(array(12, 34)); // WHERE phone_student IN (12, 34)
     * $query->filterByPhoneStudent(array('min' => 12)); // WHERE phone_student >= 12
     * $query->filterByPhoneStudent(array('max' => 12)); // WHERE phone_student <= 12
     * </code>
     *
     * @param     mixed $phoneStudent The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByPhoneStudent($phoneStudent = null, $comparison = null)
    {
        if (is_array($phoneStudent)) {
            $useMinMax = false;
            if (isset($phoneStudent['min'])) {
                $this->addUsingAlias(ApplicationPeer::PHONE_STUDENT, $phoneStudent['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($phoneStudent['max'])) {
                $this->addUsingAlias(ApplicationPeer::PHONE_STUDENT, $phoneStudent['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::PHONE_STUDENT, $phoneStudent, $comparison);
    }

    /**
     * Filter the query on the gender column
     *
     * @param     mixed $gender The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     * @throws PropelException - if the value is not accepted by the enum.
     */
    public function filterByGender($gender = null, $comparison = null)
    {
        if (is_scalar($gender)) {
            $gender = ApplicationPeer::getSqlValueForEnum(ApplicationPeer::GENDER, $gender);
        } elseif (is_array($gender)) {
            $convertedValues = array();
            foreach ($gender as $value) {
                $convertedValues[] = ApplicationPeer::getSqlValueForEnum(ApplicationPeer::GENDER, $value);
            }
            $gender = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::GENDER, $gender, $comparison);
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
     * @return ApplicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ApplicationPeer::PLACE_OF_BIRTH, $placeOfBirth, $comparison);
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
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByDateOfBirth($dateOfBirth = null, $comparison = null)
    {
        if (is_array($dateOfBirth)) {
            $useMinMax = false;
            if (isset($dateOfBirth['min'])) {
                $this->addUsingAlias(ApplicationPeer::DATE_OF_BIRTH, $dateOfBirth['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateOfBirth['max'])) {
                $this->addUsingAlias(ApplicationPeer::DATE_OF_BIRTH, $dateOfBirth['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::DATE_OF_BIRTH, $dateOfBirth, $comparison);
    }

    /**
     * Filter the query on the religion column
     *
     * @param     mixed $religion The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     * @throws PropelException - if the value is not accepted by the enum.
     */
    public function filterByReligion($religion = null, $comparison = null)
    {
        if (is_scalar($religion)) {
            $religion = ApplicationPeer::getSqlValueForEnum(ApplicationPeer::RELIGION, $religion);
        } elseif (is_array($religion)) {
            $convertedValues = array();
            foreach ($religion as $value) {
                $convertedValues[] = ApplicationPeer::getSqlValueForEnum(ApplicationPeer::RELIGION, $value);
            }
            $religion = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::RELIGION, $religion, $comparison);
    }

    /**
     * Filter the query on the level_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLevelId(1234); // WHERE level_id = 1234
     * $query->filterByLevelId(array(12, 34)); // WHERE level_id IN (12, 34)
     * $query->filterByLevelId(array('min' => 12)); // WHERE level_id >= 12
     * $query->filterByLevelId(array('max' => 12)); // WHERE level_id <= 12
     * </code>
     *
     * @see       filterByLevel()
     *
     * @param     mixed $levelId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByLevelId($levelId = null, $comparison = null)
    {
        if (is_array($levelId)) {
            $useMinMax = false;
            if (isset($levelId['min'])) {
                $this->addUsingAlias(ApplicationPeer::LEVEL_ID, $levelId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($levelId['max'])) {
                $this->addUsingAlias(ApplicationPeer::LEVEL_ID, $levelId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::LEVEL_ID, $levelId, $comparison);
    }

    /**
     * Filter the query on the grade_id column
     *
     * Example usage:
     * <code>
     * $query->filterByGradeId(1234); // WHERE grade_id = 1234
     * $query->filterByGradeId(array(12, 34)); // WHERE grade_id IN (12, 34)
     * $query->filterByGradeId(array('min' => 12)); // WHERE grade_id >= 12
     * $query->filterByGradeId(array('max' => 12)); // WHERE grade_id <= 12
     * </code>
     *
     * @see       filterByGrade()
     *
     * @param     mixed $gradeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByGradeId($gradeId = null, $comparison = null)
    {
        if (is_array($gradeId)) {
            $useMinMax = false;
            if (isset($gradeId['min'])) {
                $this->addUsingAlias(ApplicationPeer::GRADE_ID, $gradeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($gradeId['max'])) {
                $this->addUsingAlias(ApplicationPeer::GRADE_ID, $gradeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::GRADE_ID, $gradeId, $comparison);
    }

    /**
     * Filter the query on the ethnicity_id column
     *
     * Example usage:
     * <code>
     * $query->filterByEthnicityId(1234); // WHERE ethnicity_id = 1234
     * $query->filterByEthnicityId(array(12, 34)); // WHERE ethnicity_id IN (12, 34)
     * $query->filterByEthnicityId(array('min' => 12)); // WHERE ethnicity_id >= 12
     * $query->filterByEthnicityId(array('max' => 12)); // WHERE ethnicity_id <= 12
     * </code>
     *
     * @see       filterByEthnicity()
     *
     * @param     mixed $ethnicityId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByEthnicityId($ethnicityId = null, $comparison = null)
    {
        if (is_array($ethnicityId)) {
            $useMinMax = false;
            if (isset($ethnicityId['min'])) {
                $this->addUsingAlias(ApplicationPeer::ETHNICITY_ID, $ethnicityId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ethnicityId['max'])) {
                $this->addUsingAlias(ApplicationPeer::ETHNICITY_ID, $ethnicityId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::ETHNICITY_ID, $ethnicityId, $comparison);
    }

    /**
     * Filter the query on the child_no column
     *
     * Example usage:
     * <code>
     * $query->filterByChildNo(1234); // WHERE child_no = 1234
     * $query->filterByChildNo(array(12, 34)); // WHERE child_no IN (12, 34)
     * $query->filterByChildNo(array('min' => 12)); // WHERE child_no >= 12
     * $query->filterByChildNo(array('max' => 12)); // WHERE child_no <= 12
     * </code>
     *
     * @param     mixed $childNo The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByChildNo($childNo = null, $comparison = null)
    {
        if (is_array($childNo)) {
            $useMinMax = false;
            if (isset($childNo['min'])) {
                $this->addUsingAlias(ApplicationPeer::CHILD_NO, $childNo['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($childNo['max'])) {
                $this->addUsingAlias(ApplicationPeer::CHILD_NO, $childNo['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::CHILD_NO, $childNo, $comparison);
    }

    /**
     * Filter the query on the child_total column
     *
     * Example usage:
     * <code>
     * $query->filterByChildTotal(1234); // WHERE child_total = 1234
     * $query->filterByChildTotal(array(12, 34)); // WHERE child_total IN (12, 34)
     * $query->filterByChildTotal(array('min' => 12)); // WHERE child_total >= 12
     * $query->filterByChildTotal(array('max' => 12)); // WHERE child_total <= 12
     * </code>
     *
     * @param     mixed $childTotal The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByChildTotal($childTotal = null, $comparison = null)
    {
        if (is_array($childTotal)) {
            $useMinMax = false;
            if (isset($childTotal['min'])) {
                $this->addUsingAlias(ApplicationPeer::CHILD_TOTAL, $childTotal['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($childTotal['max'])) {
                $this->addUsingAlias(ApplicationPeer::CHILD_TOTAL, $childTotal['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::CHILD_TOTAL, $childTotal, $comparison);
    }

    /**
     * Filter the query on the child_status column
     *
     * @param     mixed $childStatus The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     * @throws PropelException - if the value is not accepted by the enum.
     */
    public function filterByChildStatus($childStatus = null, $comparison = null)
    {
        if (is_scalar($childStatus)) {
            $childStatus = ApplicationPeer::getSqlValueForEnum(ApplicationPeer::CHILD_STATUS, $childStatus);
        } elseif (is_array($childStatus)) {
            $convertedValues = array();
            foreach ($childStatus as $value) {
                $convertedValues[] = ApplicationPeer::getSqlValueForEnum(ApplicationPeer::CHILD_STATUS, $value);
            }
            $childStatus = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::CHILD_STATUS, $childStatus, $comparison);
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
     * @return ApplicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ApplicationPeer::PICTURE, $picture, $comparison);
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
     * @return ApplicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ApplicationPeer::BIRTH_CERTIFICATE, $birthCertificate, $comparison);
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
     * @return ApplicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ApplicationPeer::FAMILY_CARD, $familyCard, $comparison);
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
     * @return ApplicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ApplicationPeer::GRADUATION_CERTIFICATE, $graduationCertificate, $comparison);
    }

    /**
     * Filter the query on the address column
     *
     * Example usage:
     * <code>
     * $query->filterByAddress('fooValue');   // WHERE address = 'fooValue'
     * $query->filterByAddress('%fooValue%'); // WHERE address LIKE '%fooValue%'
     * </code>
     *
     * @param     string $address The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByAddress($address = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($address)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $address)) {
                $address = str_replace('*', '%', $address);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::ADDRESS, $address, $comparison);
    }

    /**
     * Filter the query on the city column
     *
     * Example usage:
     * <code>
     * $query->filterByCity('fooValue');   // WHERE city = 'fooValue'
     * $query->filterByCity('%fooValue%'); // WHERE city LIKE '%fooValue%'
     * </code>
     *
     * @param     string $city The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByCity($city = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($city)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $city)) {
                $city = str_replace('*', '%', $city);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::CITY, $city, $comparison);
    }

    /**
     * Filter the query on the state_id column
     *
     * Example usage:
     * <code>
     * $query->filterByStateId(1234); // WHERE state_id = 1234
     * $query->filterByStateId(array(12, 34)); // WHERE state_id IN (12, 34)
     * $query->filterByStateId(array('min' => 12)); // WHERE state_id >= 12
     * $query->filterByStateId(array('max' => 12)); // WHERE state_id <= 12
     * </code>
     *
     * @see       filterByState()
     *
     * @param     mixed $stateId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByStateId($stateId = null, $comparison = null)
    {
        if (is_array($stateId)) {
            $useMinMax = false;
            if (isset($stateId['min'])) {
                $this->addUsingAlias(ApplicationPeer::STATE_ID, $stateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($stateId['max'])) {
                $this->addUsingAlias(ApplicationPeer::STATE_ID, $stateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::STATE_ID, $stateId, $comparison);
    }

    /**
     * Filter the query on the zip column
     *
     * Example usage:
     * <code>
     * $query->filterByZip('fooValue');   // WHERE zip = 'fooValue'
     * $query->filterByZip('%fooValue%'); // WHERE zip LIKE '%fooValue%'
     * </code>
     *
     * @param     string $zip The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByZip($zip = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($zip)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $zip)) {
                $zip = str_replace('*', '%', $zip);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::ZIP, $zip, $comparison);
    }

    /**
     * Filter the query on the country_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCountryId(1234); // WHERE country_id = 1234
     * $query->filterByCountryId(array(12, 34)); // WHERE country_id IN (12, 34)
     * $query->filterByCountryId(array('min' => 12)); // WHERE country_id >= 12
     * $query->filterByCountryId(array('max' => 12)); // WHERE country_id <= 12
     * </code>
     *
     * @see       filterByCountry()
     *
     * @param     mixed $countryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByCountryId($countryId = null, $comparison = null)
    {
        if (is_array($countryId)) {
            $useMinMax = false;
            if (isset($countryId['min'])) {
                $this->addUsingAlias(ApplicationPeer::COUNTRY_ID, $countryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($countryId['max'])) {
                $this->addUsingAlias(ApplicationPeer::COUNTRY_ID, $countryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::COUNTRY_ID, $countryId, $comparison);
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
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterBySchoolId($schoolId = null, $comparison = null)
    {
        if (is_array($schoolId)) {
            $useMinMax = false;
            if (isset($schoolId['min'])) {
                $this->addUsingAlias(ApplicationPeer::SCHOOL_ID, $schoolId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($schoolId['max'])) {
                $this->addUsingAlias(ApplicationPeer::SCHOOL_ID, $schoolId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::SCHOOL_ID, $schoolId, $comparison);
    }

    /**
     * Filter the query on the school_year_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySchoolYearId(1234); // WHERE school_year_id = 1234
     * $query->filterBySchoolYearId(array(12, 34)); // WHERE school_year_id IN (12, 34)
     * $query->filterBySchoolYearId(array('min' => 12)); // WHERE school_year_id >= 12
     * $query->filterBySchoolYearId(array('max' => 12)); // WHERE school_year_id <= 12
     * </code>
     *
     * @see       filterBySchoolYear()
     *
     * @param     mixed $schoolYearId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterBySchoolYearId($schoolYearId = null, $comparison = null)
    {
        if (is_array($schoolYearId)) {
            $useMinMax = false;
            if (isset($schoolYearId['min'])) {
                $this->addUsingAlias(ApplicationPeer::SCHOOL_YEAR_ID, $schoolYearId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($schoolYearId['max'])) {
                $this->addUsingAlias(ApplicationPeer::SCHOOL_YEAR_ID, $schoolYearId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::SCHOOL_YEAR_ID, $schoolYearId, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * @param     mixed $status The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     * @throws PropelException - if the value is not accepted by the enum.
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_scalar($status)) {
            $status = ApplicationPeer::getSqlValueForEnum(ApplicationPeer::STATUS, $status);
        } elseif (is_array($status)) {
            $convertedValues = array();
            foreach ($status as $value) {
                $convertedValues[] = ApplicationPeer::getSqlValueForEnum(ApplicationPeer::STATUS, $value);
            }
            $status = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the mailing_address column
     *
     * Example usage:
     * <code>
     * $query->filterByMailingAddress('fooValue');   // WHERE mailing_address = 'fooValue'
     * $query->filterByMailingAddress('%fooValue%'); // WHERE mailing_address LIKE '%fooValue%'
     * </code>
     *
     * @param     string $mailingAddress The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByMailingAddress($mailingAddress = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mailingAddress)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $mailingAddress)) {
                $mailingAddress = str_replace('*', '%', $mailingAddress);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::MAILING_ADDRESS, $mailingAddress, $comparison);
    }

    /**
     * Filter the query on the note column
     *
     * Example usage:
     * <code>
     * $query->filterByNote('fooValue');   // WHERE note = 'fooValue'
     * $query->filterByNote('%fooValue%'); // WHERE note LIKE '%fooValue%'
     * </code>
     *
     * @param     string $note The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByNote($note = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($note)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $note)) {
                $note = str_replace('*', '%', $note);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::NOTE, $note, $comparison);
    }

    /**
     * Filter the query on the hobby column
     *
     * Example usage:
     * <code>
     * $query->filterByHobby('fooValue');   // WHERE hobby = 'fooValue'
     * $query->filterByHobby('%fooValue%'); // WHERE hobby LIKE '%fooValue%'
     * </code>
     *
     * @param     string $hobby The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByHobby($hobby = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($hobby)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $hobby)) {
                $hobby = str_replace('*', '%', $hobby);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::HOBBY, $hobby, $comparison);
    }

    /**
     * Filter the query on the entered_by column
     *
     * Example usage:
     * <code>
     * $query->filterByEnteredBy(1234); // WHERE entered_by = 1234
     * $query->filterByEnteredBy(array(12, 34)); // WHERE entered_by IN (12, 34)
     * $query->filterByEnteredBy(array('min' => 12)); // WHERE entered_by >= 12
     * $query->filterByEnteredBy(array('max' => 12)); // WHERE entered_by <= 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $enteredBy The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByEnteredBy($enteredBy = null, $comparison = null)
    {
        if (is_array($enteredBy)) {
            $useMinMax = false;
            if (isset($enteredBy['min'])) {
                $this->addUsingAlias(ApplicationPeer::ENTERED_BY, $enteredBy['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($enteredBy['max'])) {
                $this->addUsingAlias(ApplicationPeer::ENTERED_BY, $enteredBy['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::ENTERED_BY, $enteredBy, $comparison);
    }

    /**
     * Filter the query on the first_name_father column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstNameFather('fooValue');   // WHERE first_name_father = 'fooValue'
     * $query->filterByFirstNameFather('%fooValue%'); // WHERE first_name_father LIKE '%fooValue%'
     * </code>
     *
     * @param     string $firstNameFather The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByFirstNameFather($firstNameFather = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstNameFather)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $firstNameFather)) {
                $firstNameFather = str_replace('*', '%', $firstNameFather);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::FIRST_NAME_FATHER, $firstNameFather, $comparison);
    }

    /**
     * Filter the query on the last_name_father column
     *
     * Example usage:
     * <code>
     * $query->filterByLastNameFather('fooValue');   // WHERE last_name_father = 'fooValue'
     * $query->filterByLastNameFather('%fooValue%'); // WHERE last_name_father LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastNameFather The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByLastNameFather($lastNameFather = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastNameFather)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $lastNameFather)) {
                $lastNameFather = str_replace('*', '%', $lastNameFather);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::LAST_NAME_FATHER, $lastNameFather, $comparison);
    }

    /**
     * Filter the query on the business_address_father column
     *
     * Example usage:
     * <code>
     * $query->filterByBusinessAddressFather('fooValue');   // WHERE business_address_father = 'fooValue'
     * $query->filterByBusinessAddressFather('%fooValue%'); // WHERE business_address_father LIKE '%fooValue%'
     * </code>
     *
     * @param     string $businessAddressFather The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByBusinessAddressFather($businessAddressFather = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($businessAddressFather)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $businessAddressFather)) {
                $businessAddressFather = str_replace('*', '%', $businessAddressFather);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::BUSINESS_ADDRESS_FATHER, $businessAddressFather, $comparison);
    }

    /**
     * Filter the query on the occupation_father column
     *
     * Example usage:
     * <code>
     * $query->filterByOccupationFather('fooValue');   // WHERE occupation_father = 'fooValue'
     * $query->filterByOccupationFather('%fooValue%'); // WHERE occupation_father LIKE '%fooValue%'
     * </code>
     *
     * @param     string $occupationFather The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByOccupationFather($occupationFather = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($occupationFather)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $occupationFather)) {
                $occupationFather = str_replace('*', '%', $occupationFather);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::OCCUPATION_FATHER, $occupationFather, $comparison);
    }

    /**
     * Filter the query on the phone_father column
     *
     * Example usage:
     * <code>
     * $query->filterByPhoneFather(1234); // WHERE phone_father = 1234
     * $query->filterByPhoneFather(array(12, 34)); // WHERE phone_father IN (12, 34)
     * $query->filterByPhoneFather(array('min' => 12)); // WHERE phone_father >= 12
     * $query->filterByPhoneFather(array('max' => 12)); // WHERE phone_father <= 12
     * </code>
     *
     * @param     mixed $phoneFather The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByPhoneFather($phoneFather = null, $comparison = null)
    {
        if (is_array($phoneFather)) {
            $useMinMax = false;
            if (isset($phoneFather['min'])) {
                $this->addUsingAlias(ApplicationPeer::PHONE_FATHER, $phoneFather['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($phoneFather['max'])) {
                $this->addUsingAlias(ApplicationPeer::PHONE_FATHER, $phoneFather['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::PHONE_FATHER, $phoneFather, $comparison);
    }

    /**
     * Filter the query on the email_father column
     *
     * Example usage:
     * <code>
     * $query->filterByEmailFather('fooValue');   // WHERE email_father = 'fooValue'
     * $query->filterByEmailFather('%fooValue%'); // WHERE email_father LIKE '%fooValue%'
     * </code>
     *
     * @param     string $emailFather The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByEmailFather($emailFather = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($emailFather)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $emailFather)) {
                $emailFather = str_replace('*', '%', $emailFather);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::EMAIL_FATHER, $emailFather, $comparison);
    }

    /**
     * Filter the query on the first_name_mother column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstNameMother('fooValue');   // WHERE first_name_mother = 'fooValue'
     * $query->filterByFirstNameMother('%fooValue%'); // WHERE first_name_mother LIKE '%fooValue%'
     * </code>
     *
     * @param     string $firstNameMother The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByFirstNameMother($firstNameMother = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstNameMother)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $firstNameMother)) {
                $firstNameMother = str_replace('*', '%', $firstNameMother);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::FIRST_NAME_MOTHER, $firstNameMother, $comparison);
    }

    /**
     * Filter the query on the last_name_mother column
     *
     * Example usage:
     * <code>
     * $query->filterByLastNameMother('fooValue');   // WHERE last_name_mother = 'fooValue'
     * $query->filterByLastNameMother('%fooValue%'); // WHERE last_name_mother LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastNameMother The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByLastNameMother($lastNameMother = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastNameMother)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $lastNameMother)) {
                $lastNameMother = str_replace('*', '%', $lastNameMother);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::LAST_NAME_MOTHER, $lastNameMother, $comparison);
    }

    /**
     * Filter the query on the business_address_mother column
     *
     * Example usage:
     * <code>
     * $query->filterByBusinessAddressMother('fooValue');   // WHERE business_address_mother = 'fooValue'
     * $query->filterByBusinessAddressMother('%fooValue%'); // WHERE business_address_mother LIKE '%fooValue%'
     * </code>
     *
     * @param     string $businessAddressMother The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByBusinessAddressMother($businessAddressMother = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($businessAddressMother)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $businessAddressMother)) {
                $businessAddressMother = str_replace('*', '%', $businessAddressMother);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::BUSINESS_ADDRESS_MOTHER, $businessAddressMother, $comparison);
    }

    /**
     * Filter the query on the occupation_mother column
     *
     * Example usage:
     * <code>
     * $query->filterByOccupationMother('fooValue');   // WHERE occupation_mother = 'fooValue'
     * $query->filterByOccupationMother('%fooValue%'); // WHERE occupation_mother LIKE '%fooValue%'
     * </code>
     *
     * @param     string $occupationMother The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByOccupationMother($occupationMother = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($occupationMother)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $occupationMother)) {
                $occupationMother = str_replace('*', '%', $occupationMother);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::OCCUPATION_MOTHER, $occupationMother, $comparison);
    }

    /**
     * Filter the query on the phone_mother column
     *
     * Example usage:
     * <code>
     * $query->filterByPhoneMother(1234); // WHERE phone_mother = 1234
     * $query->filterByPhoneMother(array(12, 34)); // WHERE phone_mother IN (12, 34)
     * $query->filterByPhoneMother(array('min' => 12)); // WHERE phone_mother >= 12
     * $query->filterByPhoneMother(array('max' => 12)); // WHERE phone_mother <= 12
     * </code>
     *
     * @param     mixed $phoneMother The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByPhoneMother($phoneMother = null, $comparison = null)
    {
        if (is_array($phoneMother)) {
            $useMinMax = false;
            if (isset($phoneMother['min'])) {
                $this->addUsingAlias(ApplicationPeer::PHONE_MOTHER, $phoneMother['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($phoneMother['max'])) {
                $this->addUsingAlias(ApplicationPeer::PHONE_MOTHER, $phoneMother['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::PHONE_MOTHER, $phoneMother, $comparison);
    }

    /**
     * Filter the query on the email_mother column
     *
     * Example usage:
     * <code>
     * $query->filterByEmailMother('fooValue');   // WHERE email_mother = 'fooValue'
     * $query->filterByEmailMother('%fooValue%'); // WHERE email_mother LIKE '%fooValue%'
     * </code>
     *
     * @param     string $emailMother The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByEmailMother($emailMother = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($emailMother)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $emailMother)) {
                $emailMother = str_replace('*', '%', $emailMother);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::EMAIL_MOTHER, $emailMother, $comparison);
    }

    /**
     * Filter the query on the first_name_legal_guardian column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstNameLegalGuardian('fooValue');   // WHERE first_name_legal_guardian = 'fooValue'
     * $query->filterByFirstNameLegalGuardian('%fooValue%'); // WHERE first_name_legal_guardian LIKE '%fooValue%'
     * </code>
     *
     * @param     string $firstNameLegalGuardian The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByFirstNameLegalGuardian($firstNameLegalGuardian = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstNameLegalGuardian)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $firstNameLegalGuardian)) {
                $firstNameLegalGuardian = str_replace('*', '%', $firstNameLegalGuardian);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::FIRST_NAME_LEGAL_GUARDIAN, $firstNameLegalGuardian, $comparison);
    }

    /**
     * Filter the query on the last_name_legal_guardian column
     *
     * Example usage:
     * <code>
     * $query->filterByLastNameLegalGuardian('fooValue');   // WHERE last_name_legal_guardian = 'fooValue'
     * $query->filterByLastNameLegalGuardian('%fooValue%'); // WHERE last_name_legal_guardian LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastNameLegalGuardian The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByLastNameLegalGuardian($lastNameLegalGuardian = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastNameLegalGuardian)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $lastNameLegalGuardian)) {
                $lastNameLegalGuardian = str_replace('*', '%', $lastNameLegalGuardian);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::LAST_NAME_LEGAL_GUARDIAN, $lastNameLegalGuardian, $comparison);
    }

    /**
     * Filter the query on the occupation_legal_guardian column
     *
     * Example usage:
     * <code>
     * $query->filterByOccupationLegalGuardian('fooValue');   // WHERE occupation_legal_guardian = 'fooValue'
     * $query->filterByOccupationLegalGuardian('%fooValue%'); // WHERE occupation_legal_guardian LIKE '%fooValue%'
     * </code>
     *
     * @param     string $occupationLegalGuardian The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByOccupationLegalGuardian($occupationLegalGuardian = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($occupationLegalGuardian)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $occupationLegalGuardian)) {
                $occupationLegalGuardian = str_replace('*', '%', $occupationLegalGuardian);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::OCCUPATION_LEGAL_GUARDIAN, $occupationLegalGuardian, $comparison);
    }

    /**
     * Filter the query on the phone_legal_guardian column
     *
     * Example usage:
     * <code>
     * $query->filterByPhoneLegalGuardian(1234); // WHERE phone_legal_guardian = 1234
     * $query->filterByPhoneLegalGuardian(array(12, 34)); // WHERE phone_legal_guardian IN (12, 34)
     * $query->filterByPhoneLegalGuardian(array('min' => 12)); // WHERE phone_legal_guardian >= 12
     * $query->filterByPhoneLegalGuardian(array('max' => 12)); // WHERE phone_legal_guardian <= 12
     * </code>
     *
     * @param     mixed $phoneLegalGuardian The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByPhoneLegalGuardian($phoneLegalGuardian = null, $comparison = null)
    {
        if (is_array($phoneLegalGuardian)) {
            $useMinMax = false;
            if (isset($phoneLegalGuardian['min'])) {
                $this->addUsingAlias(ApplicationPeer::PHONE_LEGAL_GUARDIAN, $phoneLegalGuardian['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($phoneLegalGuardian['max'])) {
                $this->addUsingAlias(ApplicationPeer::PHONE_LEGAL_GUARDIAN, $phoneLegalGuardian['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::PHONE_LEGAL_GUARDIAN, $phoneLegalGuardian, $comparison);
    }

    /**
     * Filter the query on the email_legal_guardian column
     *
     * Example usage:
     * <code>
     * $query->filterByEmailLegalGuardian('fooValue');   // WHERE email_legal_guardian = 'fooValue'
     * $query->filterByEmailLegalGuardian('%fooValue%'); // WHERE email_legal_guardian LIKE '%fooValue%'
     * </code>
     *
     * @param     string $emailLegalGuardian The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByEmailLegalGuardian($emailLegalGuardian = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($emailLegalGuardian)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $emailLegalGuardian)) {
                $emailLegalGuardian = str_replace('*', '%', $emailLegalGuardian);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::EMAIL_LEGAL_GUARDIAN, $emailLegalGuardian, $comparison);
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
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(ApplicationPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ApplicationPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(ApplicationPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ApplicationPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ApplicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(ApplicationPeer::ENTERED_BY, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ApplicationPeer::ENTERED_BY, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return ApplicationQuery The current query, for fluid interface
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
     * Filter the query by a related School object
     *
     * @param   School|PropelObjectCollection $school The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ApplicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchool($school, $comparison = null)
    {
        if ($school instanceof School) {
            return $this
                ->addUsingAlias(ApplicationPeer::SCHOOL_ID, $school->getId(), $comparison);
        } elseif ($school instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ApplicationPeer::SCHOOL_ID, $school->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return ApplicationQuery The current query, for fluid interface
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
     * Filter the query by a related SchoolYear object
     *
     * @param   SchoolYear|PropelObjectCollection $schoolYear The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ApplicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolYear($schoolYear, $comparison = null)
    {
        if ($schoolYear instanceof SchoolYear) {
            return $this
                ->addUsingAlias(ApplicationPeer::SCHOOL_YEAR_ID, $schoolYear->getId(), $comparison);
        } elseif ($schoolYear instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ApplicationPeer::SCHOOL_YEAR_ID, $schoolYear->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function joinSchoolYear($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useSchoolYearQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSchoolYear($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SchoolYear', '\PGS\CoreDomainBundle\Model\SchoolYear\SchoolYearQuery');
    }

    /**
     * Filter the query by a related Ethnicity object
     *
     * @param   Ethnicity|PropelObjectCollection $ethnicity The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ApplicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEthnicity($ethnicity, $comparison = null)
    {
        if ($ethnicity instanceof Ethnicity) {
            return $this
                ->addUsingAlias(ApplicationPeer::ETHNICITY_ID, $ethnicity->getId(), $comparison);
        } elseif ($ethnicity instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ApplicationPeer::ETHNICITY_ID, $ethnicity->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByEthnicity() only accepts arguments of type Ethnicity or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Ethnicity relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function joinEthnicity($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Ethnicity');

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
            $this->addJoinObject($join, 'Ethnicity');
        }

        return $this;
    }

    /**
     * Use the Ethnicity relation Ethnicity object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\Ethnicity\EthnicityQuery A secondary query class using the current class as primary query
     */
    public function useEthnicityQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEthnicity($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Ethnicity', '\PGS\CoreDomainBundle\Model\Ethnicity\EthnicityQuery');
    }

    /**
     * Filter the query by a related Grade object
     *
     * @param   Grade|PropelObjectCollection $grade The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ApplicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByGrade($grade, $comparison = null)
    {
        if ($grade instanceof Grade) {
            return $this
                ->addUsingAlias(ApplicationPeer::GRADE_ID, $grade->getId(), $comparison);
        } elseif ($grade instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ApplicationPeer::GRADE_ID, $grade->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByGrade() only accepts arguments of type Grade or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Grade relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function joinGrade($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Grade');

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
            $this->addJoinObject($join, 'Grade');
        }

        return $this;
    }

    /**
     * Use the Grade relation Grade object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\Grade\GradeQuery A secondary query class using the current class as primary query
     */
    public function useGradeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinGrade($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Grade', '\PGS\CoreDomainBundle\Model\Grade\GradeQuery');
    }

    /**
     * Filter the query by a related Level object
     *
     * @param   Level|PropelObjectCollection $level The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ApplicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByLevel($level, $comparison = null)
    {
        if ($level instanceof Level) {
            return $this
                ->addUsingAlias(ApplicationPeer::LEVEL_ID, $level->getId(), $comparison);
        } elseif ($level instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ApplicationPeer::LEVEL_ID, $level->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByLevel() only accepts arguments of type Level or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Level relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function joinLevel($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Level');

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
            $this->addJoinObject($join, 'Level');
        }

        return $this;
    }

    /**
     * Use the Level relation Level object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\Level\LevelQuery A secondary query class using the current class as primary query
     */
    public function useLevelQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLevel($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Level', '\PGS\CoreDomainBundle\Model\Level\LevelQuery');
    }

    /**
     * Filter the query by a related State object
     *
     * @param   State|PropelObjectCollection $state The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ApplicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByState($state, $comparison = null)
    {
        if ($state instanceof State) {
            return $this
                ->addUsingAlias(ApplicationPeer::STATE_ID, $state->getId(), $comparison);
        } elseif ($state instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ApplicationPeer::STATE_ID, $state->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByState() only accepts arguments of type State or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the State relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function joinState($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('State');

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
            $this->addJoinObject($join, 'State');
        }

        return $this;
    }

    /**
     * Use the State relation State object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\StateQuery A secondary query class using the current class as primary query
     */
    public function useStateQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinState($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'State', '\PGS\CoreDomainBundle\Model\StateQuery');
    }

    /**
     * Filter the query by a related Country object
     *
     * @param   Country|PropelObjectCollection $country The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ApplicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCountry($country, $comparison = null)
    {
        if ($country instanceof Country) {
            return $this
                ->addUsingAlias(ApplicationPeer::COUNTRY_ID, $country->getId(), $comparison);
        } elseif ($country instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ApplicationPeer::COUNTRY_ID, $country->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCountry() only accepts arguments of type Country or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Country relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function joinCountry($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Country');

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
            $this->addJoinObject($join, 'Country');
        }

        return $this;
    }

    /**
     * Use the Country relation Country object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PGS\CoreDomainBundle\Model\CountryQuery A secondary query class using the current class as primary query
     */
    public function useCountryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCountry($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Country', '\PGS\CoreDomainBundle\Model\CountryQuery');
    }

    /**
     * Filter the query by a related ParentStudent object
     *
     * @param   ParentStudent|PropelObjectCollection $parentStudent  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ApplicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByParentStudent($parentStudent, $comparison = null)
    {
        if ($parentStudent instanceof ParentStudent) {
            return $this
                ->addUsingAlias(ApplicationPeer::ID, $parentStudent->getApplicationId(), $comparison);
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
     * @return ApplicationQuery The current query, for fluid interface
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
     * Filter the query by a related SchoolHealth object
     *
     * @param   SchoolHealth|PropelObjectCollection $schoolHealth  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ApplicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySchoolHealth($schoolHealth, $comparison = null)
    {
        if ($schoolHealth instanceof SchoolHealth) {
            return $this
                ->addUsingAlias(ApplicationPeer::ID, $schoolHealth->getApplicationId(), $comparison);
        } elseif ($schoolHealth instanceof PropelObjectCollection) {
            return $this
                ->useSchoolHealthQuery()
                ->filterByPrimaryKeys($schoolHealth->getPrimaryKeys())
                ->endUse();
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
     * @return ApplicationQuery The current query, for fluid interface
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
     * Filter the query by a related Student object
     *
     * @param   Student|PropelObjectCollection $student  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ApplicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStudent($student, $comparison = null)
    {
        if ($student instanceof Student) {
            return $this
                ->addUsingAlias(ApplicationPeer::ID, $student->getApplicationId(), $comparison);
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
     * @return ApplicationQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   Application $application Object to remove from the list of results
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function prune($application = null)
    {
        if ($application) {
            $this->addUsingAlias(ApplicationPeer::ID, $application->getId(), Criteria::NOT_EQUAL);
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
     * @return     ApplicationQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(ApplicationPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ApplicationQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(ApplicationPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ApplicationQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(ApplicationPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ApplicationQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(ApplicationPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     ApplicationQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(ApplicationPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ApplicationQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(ApplicationPeer::CREATED_AT);
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
