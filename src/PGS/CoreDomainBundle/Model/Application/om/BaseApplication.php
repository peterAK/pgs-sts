<?php

namespace PGS\CoreDomainBundle\Model\Application\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Glorpen\Propel\PropelBundle\Dispatcher\EventDispatcherProxy;
use Glorpen\Propel\PropelBundle\Events\ModelEvent;
use PGS\CoreDomainBundle\Model\Country;
use PGS\CoreDomainBundle\Model\CountryQuery;
use PGS\CoreDomainBundle\Model\State;
use PGS\CoreDomainBundle\Model\StateQuery;
use PGS\CoreDomainBundle\Model\User;
use PGS\CoreDomainBundle\Model\UserQuery;
use PGS\CoreDomainBundle\Model\Application\Application;
use PGS\CoreDomainBundle\Model\Application\ApplicationPeer;
use PGS\CoreDomainBundle\Model\Application\ApplicationQuery;
use PGS\CoreDomainBundle\Model\Ethnicity\Ethnicity;
use PGS\CoreDomainBundle\Model\Ethnicity\EthnicityQuery;
use PGS\CoreDomainBundle\Model\Grade\Grade;
use PGS\CoreDomainBundle\Model\Grade\GradeQuery;
use PGS\CoreDomainBundle\Model\Level\Level;
use PGS\CoreDomainBundle\Model\Level\LevelQuery;
use PGS\CoreDomainBundle\Model\ParentStudent\ParentStudent;
use PGS\CoreDomainBundle\Model\ParentStudent\ParentStudentQuery;
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Model\School\SchoolQuery;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealth;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealthQuery;
use PGS\CoreDomainBundle\Model\SchoolYear\SchoolYear;
use PGS\CoreDomainBundle\Model\SchoolYear\SchoolYearQuery;
use PGS\CoreDomainBundle\Model\Student\Student;
use PGS\CoreDomainBundle\Model\Student\StudentQuery;

abstract class BaseApplication extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'PGS\\CoreDomainBundle\\Model\\Application\\ApplicationPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        ApplicationPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the form_no field.
     * @var        string
     */
    protected $form_no;

    /**
     * The value for the student_nation_no field.
     * @var        string
     */
    protected $student_nation_no;

    /**
     * The value for the prior_test_no field.
     * @var        string
     */
    protected $prior_test_no;

    /**
     * The value for the student_no field.
     * @var        string
     */
    protected $student_no;

    /**
     * The value for the first_name field.
     * @var        string
     */
    protected $first_name;

    /**
     * The value for the last_name field.
     * @var        string
     */
    protected $last_name;

    /**
     * The value for the nick_name field.
     * @var        string
     */
    protected $nick_name;

    /**
     * The value for the phone_student field.
     * @var        int
     */
    protected $phone_student;

    /**
     * The value for the gender field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $gender;

    /**
     * The value for the place_of_birth field.
     * @var        string
     */
    protected $place_of_birth;

    /**
     * The value for the date_of_birth field.
     * @var        string
     */
    protected $date_of_birth;

    /**
     * The value for the religion field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $religion;

    /**
     * The value for the level_id field.
     * @var        int
     */
    protected $level_id;

    /**
     * The value for the grade_id field.
     * @var        int
     */
    protected $grade_id;

    /**
     * The value for the ethnicity_id field.
     * @var        int
     */
    protected $ethnicity_id;

    /**
     * The value for the child_no field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $child_no;

    /**
     * The value for the child_total field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $child_total;

    /**
     * The value for the child_status field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $child_status;

    /**
     * The value for the picture field.
     * @var        string
     */
    protected $picture;

    /**
     * The value for the birth_certificate field.
     * @var        string
     */
    protected $birth_certificate;

    /**
     * The value for the family_card field.
     * @var        string
     */
    protected $family_card;

    /**
     * The value for the graduation_certificate field.
     * @var        string
     */
    protected $graduation_certificate;

    /**
     * The value for the address field.
     * @var        string
     */
    protected $address;

    /**
     * The value for the city field.
     * @var        string
     */
    protected $city;

    /**
     * The value for the state_id field.
     * @var        int
     */
    protected $state_id;

    /**
     * The value for the zip field.
     * @var        string
     */
    protected $zip;

    /**
     * The value for the country_id field.
     * Note: this column has a database default value of: 105
     * @var        int
     */
    protected $country_id;

    /**
     * The value for the school_id field.
     * @var        int
     */
    protected $school_id;

    /**
     * The value for the school_year_id field.
     * @var        int
     */
    protected $school_year_id;

    /**
     * The value for the status field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $status;

    /**
     * The value for the mailing_address field.
     * @var        string
     */
    protected $mailing_address;

    /**
     * The value for the note field.
     * @var        string
     */
    protected $note;

    /**
     * The value for the hobby field.
     * @var        string
     */
    protected $hobby;

    /**
     * The value for the entered_by field.
     * @var        int
     */
    protected $entered_by;

    /**
     * The value for the first_name_father field.
     * @var        string
     */
    protected $first_name_father;

    /**
     * The value for the last_name_father field.
     * @var        string
     */
    protected $last_name_father;

    /**
     * The value for the business_address_father field.
     * @var        string
     */
    protected $business_address_father;

    /**
     * The value for the occupation_father field.
     * @var        string
     */
    protected $occupation_father;

    /**
     * The value for the phone_father field.
     * @var        int
     */
    protected $phone_father;

    /**
     * The value for the email_father field.
     * @var        string
     */
    protected $email_father;

    /**
     * The value for the first_name_mother field.
     * @var        string
     */
    protected $first_name_mother;

    /**
     * The value for the last_name_mother field.
     * @var        string
     */
    protected $last_name_mother;

    /**
     * The value for the business_address_mother field.
     * @var        string
     */
    protected $business_address_mother;

    /**
     * The value for the occupation_mother field.
     * @var        string
     */
    protected $occupation_mother;

    /**
     * The value for the phone_mother field.
     * @var        int
     */
    protected $phone_mother;

    /**
     * The value for the email_mother field.
     * @var        string
     */
    protected $email_mother;

    /**
     * The value for the first_name_legal_guardian field.
     * @var        string
     */
    protected $first_name_legal_guardian;

    /**
     * The value for the last_name_legal_guardian field.
     * @var        string
     */
    protected $last_name_legal_guardian;

    /**
     * The value for the occupation_legal_guardian field.
     * @var        string
     */
    protected $occupation_legal_guardian;

    /**
     * The value for the phone_legal_guardian field.
     * @var        int
     */
    protected $phone_legal_guardian;

    /**
     * The value for the email_legal_guardian field.
     * @var        string
     */
    protected $email_legal_guardian;

    /**
     * The value for the created_at field.
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * @var        User
     */
    protected $aUser;

    /**
     * @var        School
     */
    protected $aSchool;

    /**
     * @var        SchoolYear
     */
    protected $aSchoolYear;

    /**
     * @var        Ethnicity
     */
    protected $aEthnicity;

    /**
     * @var        Grade
     */
    protected $aGrade;

    /**
     * @var        Level
     */
    protected $aLevel;

    /**
     * @var        State
     */
    protected $aState;

    /**
     * @var        Country
     */
    protected $aCountry;

    /**
     * @var        PropelObjectCollection|ParentStudent[] Collection to store aggregation of ParentStudent objects.
     */
    protected $collParentStudents;
    protected $collParentStudentsPartial;

    /**
     * @var        PropelObjectCollection|SchoolHealth[] Collection to store aggregation of SchoolHealth objects.
     */
    protected $collSchoolHealths;
    protected $collSchoolHealthsPartial;

    /**
     * @var        PropelObjectCollection|Student[] Collection to store aggregation of Student objects.
     */
    protected $collStudents;
    protected $collStudentsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $parentStudentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $schoolHealthsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $studentsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->gender = 0;
        $this->religion = 0;
        $this->child_no = 0;
        $this->child_total = 0;
        $this->child_status = 0;
        $this->country_id = 105;
        $this->status = 0;
    }

    /**
     * Initializes internal state of BaseApplication object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
        EventDispatcherProxy::trigger(array('construct','model.construct'), new ModelEvent($this));
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [form_no] column value.
     *
     * @return string
     */
    public function getFormNo()
    {

        return $this->form_no;
    }

    /**
     * Get the [student_nation_no] column value.
     *
     * @return string
     */
    public function getStudentNationNo()
    {

        return $this->student_nation_no;
    }

    /**
     * Get the [prior_test_no] column value.
     *
     * @return string
     */
    public function getPriorTestNo()
    {

        return $this->prior_test_no;
    }

    /**
     * Get the [student_no] column value.
     *
     * @return string
     */
    public function getStudentNo()
    {

        return $this->student_no;
    }

    /**
     * Get the [first_name] column value.
     *
     * @return string
     */
    public function getFirstName()
    {

        return $this->first_name;
    }

    /**
     * Get the [last_name] column value.
     *
     * @return string
     */
    public function getLastName()
    {

        return $this->last_name;
    }

    /**
     * Get the [nick_name] column value.
     *
     * @return string
     */
    public function getNickName()
    {

        return $this->nick_name;
    }

    /**
     * Get the [phone_student] column value.
     *
     * @return int
     */
    public function getPhoneStudent()
    {

        return $this->phone_student;
    }

    /**
     * Get the [gender] column value.
     *
     * @return int
     * @throws PropelException - if the stored enum key is unknown.
     */
    public function getGender()
    {
        if (null === $this->gender) {
            return null;
        }
        $valueSet = ApplicationPeer::getValueSet(ApplicationPeer::GENDER);
        if (!isset($valueSet[$this->gender])) {
            throw new PropelException('Unknown stored enum key: ' . $this->gender);
        }

        return $valueSet[$this->gender];
    }

    /**
     * Get the [place_of_birth] column value.
     *
     * @return string
     */
    public function getPlaceOfBirth()
    {

        return $this->place_of_birth;
    }

    /**
     * Get the [optionally formatted] temporal [date_of_birth] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDateOfBirth($format = null)
    {
        if ($this->date_of_birth === null) {
            return null;
        }

        if ($this->date_of_birth === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->date_of_birth);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->date_of_birth, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [religion] column value.
     *
     * @return int
     * @throws PropelException - if the stored enum key is unknown.
     */
    public function getReligion()
    {
        if (null === $this->religion) {
            return null;
        }
        $valueSet = ApplicationPeer::getValueSet(ApplicationPeer::RELIGION);
        if (!isset($valueSet[$this->religion])) {
            throw new PropelException('Unknown stored enum key: ' . $this->religion);
        }

        return $valueSet[$this->religion];
    }

    /**
     * Get the [level_id] column value.
     *
     * @return int
     */
    public function getLevelId()
    {

        return $this->level_id;
    }

    /**
     * Get the [grade_id] column value.
     *
     * @return int
     */
    public function getGradeId()
    {

        return $this->grade_id;
    }

    /**
     * Get the [ethnicity_id] column value.
     *
     * @return int
     */
    public function getEthnicityId()
    {

        return $this->ethnicity_id;
    }

    /**
     * Get the [child_no] column value.
     *
     * @return int
     */
    public function getChildNo()
    {

        return $this->child_no;
    }

    /**
     * Get the [child_total] column value.
     *
     * @return int
     */
    public function getChildTotal()
    {

        return $this->child_total;
    }

    /**
     * Get the [child_status] column value.
     *
     * @return int
     * @throws PropelException - if the stored enum key is unknown.
     */
    public function getChildStatus()
    {
        if (null === $this->child_status) {
            return null;
        }
        $valueSet = ApplicationPeer::getValueSet(ApplicationPeer::CHILD_STATUS);
        if (!isset($valueSet[$this->child_status])) {
            throw new PropelException('Unknown stored enum key: ' . $this->child_status);
        }

        return $valueSet[$this->child_status];
    }

    /**
     * Get the [picture] column value.
     *
     * @return string
     */
    public function getPicture()
    {

        return $this->picture;
    }

    /**
     * Get the [birth_certificate] column value.
     *
     * @return string
     */
    public function getBirthCertificate()
    {

        return $this->birth_certificate;
    }

    /**
     * Get the [family_card] column value.
     *
     * @return string
     */
    public function getFamilyCard()
    {

        return $this->family_card;
    }

    /**
     * Get the [graduation_certificate] column value.
     *
     * @return string
     */
    public function getGraduationCertificate()
    {

        return $this->graduation_certificate;
    }

    /**
     * Get the [address] column value.
     *
     * @return string
     */
    public function getAddress()
    {

        return $this->address;
    }

    /**
     * Get the [city] column value.
     *
     * @return string
     */
    public function getCity()
    {

        return $this->city;
    }

    /**
     * Get the [state_id] column value.
     *
     * @return int
     */
    public function getStateId()
    {

        return $this->state_id;
    }

    /**
     * Get the [zip] column value.
     *
     * @return string
     */
    public function getZip()
    {

        return $this->zip;
    }

    /**
     * Get the [country_id] column value.
     *
     * @return int
     */
    public function getCountryId()
    {

        return $this->country_id;
    }

    /**
     * Get the [school_id] column value.
     *
     * @return int
     */
    public function getSchoolId()
    {

        return $this->school_id;
    }

    /**
     * Get the [school_year_id] column value.
     *
     * @return int
     */
    public function getSchoolYearId()
    {

        return $this->school_year_id;
    }

    /**
     * Get the [status] column value.
     *
     * @return int
     * @throws PropelException - if the stored enum key is unknown.
     */
    public function getStatus()
    {
        if (null === $this->status) {
            return null;
        }
        $valueSet = ApplicationPeer::getValueSet(ApplicationPeer::STATUS);
        if (!isset($valueSet[$this->status])) {
            throw new PropelException('Unknown stored enum key: ' . $this->status);
        }

        return $valueSet[$this->status];
    }

    /**
     * Get the [mailing_address] column value.
     *
     * @return string
     */
    public function getMailingAddress()
    {

        return $this->mailing_address;
    }

    /**
     * Get the [note] column value.
     *
     * @return string
     */
    public function getNote()
    {

        return $this->note;
    }

    /**
     * Get the [hobby] column value.
     *
     * @return string
     */
    public function getHobby()
    {

        return $this->hobby;
    }

    /**
     * Get the [entered_by] column value.
     *
     * @return int
     */
    public function getEnteredBy()
    {

        return $this->entered_by;
    }

    /**
     * Get the [first_name_father] column value.
     *
     * @return string
     */
    public function getFirstNameFather()
    {

        return $this->first_name_father;
    }

    /**
     * Get the [last_name_father] column value.
     *
     * @return string
     */
    public function getLastNameFather()
    {

        return $this->last_name_father;
    }

    /**
     * Get the [business_address_father] column value.
     *
     * @return string
     */
    public function getBusinessAddressFather()
    {

        return $this->business_address_father;
    }

    /**
     * Get the [occupation_father] column value.
     *
     * @return string
     */
    public function getOccupationFather()
    {

        return $this->occupation_father;
    }

    /**
     * Get the [phone_father] column value.
     *
     * @return int
     */
    public function getPhoneFather()
    {

        return $this->phone_father;
    }

    /**
     * Get the [email_father] column value.
     *
     * @return string
     */
    public function getEmailFather()
    {

        return $this->email_father;
    }

    /**
     * Get the [first_name_mother] column value.
     *
     * @return string
     */
    public function getFirstNameMother()
    {

        return $this->first_name_mother;
    }

    /**
     * Get the [last_name_mother] column value.
     *
     * @return string
     */
    public function getLastNameMother()
    {

        return $this->last_name_mother;
    }

    /**
     * Get the [business_address_mother] column value.
     *
     * @return string
     */
    public function getBusinessAddressMother()
    {

        return $this->business_address_mother;
    }

    /**
     * Get the [occupation_mother] column value.
     *
     * @return string
     */
    public function getOccupationMother()
    {

        return $this->occupation_mother;
    }

    /**
     * Get the [phone_mother] column value.
     *
     * @return int
     */
    public function getPhoneMother()
    {

        return $this->phone_mother;
    }

    /**
     * Get the [email_mother] column value.
     *
     * @return string
     */
    public function getEmailMother()
    {

        return $this->email_mother;
    }

    /**
     * Get the [first_name_legal_guardian] column value.
     *
     * @return string
     */
    public function getFirstNameLegalGuardian()
    {

        return $this->first_name_legal_guardian;
    }

    /**
     * Get the [last_name_legal_guardian] column value.
     *
     * @return string
     */
    public function getLastNameLegalGuardian()
    {

        return $this->last_name_legal_guardian;
    }

    /**
     * Get the [occupation_legal_guardian] column value.
     *
     * @return string
     */
    public function getOccupationLegalGuardian()
    {

        return $this->occupation_legal_guardian;
    }

    /**
     * Get the [phone_legal_guardian] column value.
     *
     * @return int
     */
    public function getPhoneLegalGuardian()
    {

        return $this->phone_legal_guardian;
    }

    /**
     * Get the [email_legal_guardian] column value.
     *
     * @return string
     */
    public function getEmailLegalGuardian()
    {

        return $this->email_legal_guardian;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = null)
    {
        if ($this->created_at === null) {
            return null;
        }

        if ($this->created_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->created_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = null)
    {
        if ($this->updated_at === null) {
            return null;
        }

        if ($this->updated_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->updated_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->updated_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = ApplicationPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [form_no] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setFormNo($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->form_no !== $v) {
            $this->form_no = $v;
            $this->modifiedColumns[] = ApplicationPeer::FORM_NO;
        }


        return $this;
    } // setFormNo()

    /**
     * Set the value of [student_nation_no] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setStudentNationNo($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->student_nation_no !== $v) {
            $this->student_nation_no = $v;
            $this->modifiedColumns[] = ApplicationPeer::STUDENT_NATION_NO;
        }


        return $this;
    } // setStudentNationNo()

    /**
     * Set the value of [prior_test_no] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setPriorTestNo($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->prior_test_no !== $v) {
            $this->prior_test_no = $v;
            $this->modifiedColumns[] = ApplicationPeer::PRIOR_TEST_NO;
        }


        return $this;
    } // setPriorTestNo()

    /**
     * Set the value of [student_no] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setStudentNo($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->student_no !== $v) {
            $this->student_no = $v;
            $this->modifiedColumns[] = ApplicationPeer::STUDENT_NO;
        }


        return $this;
    } // setStudentNo()

    /**
     * Set the value of [first_name] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setFirstName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->first_name !== $v) {
            $this->first_name = $v;
            $this->modifiedColumns[] = ApplicationPeer::FIRST_NAME;
        }


        return $this;
    } // setFirstName()

    /**
     * Set the value of [last_name] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setLastName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->last_name !== $v) {
            $this->last_name = $v;
            $this->modifiedColumns[] = ApplicationPeer::LAST_NAME;
        }


        return $this;
    } // setLastName()

    /**
     * Set the value of [nick_name] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setNickName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->nick_name !== $v) {
            $this->nick_name = $v;
            $this->modifiedColumns[] = ApplicationPeer::NICK_NAME;
        }


        return $this;
    } // setNickName()

    /**
     * Set the value of [phone_student] column.
     *
     * @param  int $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setPhoneStudent($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->phone_student !== $v) {
            $this->phone_student = $v;
            $this->modifiedColumns[] = ApplicationPeer::PHONE_STUDENT;
        }


        return $this;
    } // setPhoneStudent()

    /**
     * Set the value of [gender] column.
     *
     * @param  int $v new value
     * @return Application The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setGender($v)
    {
        if ($v !== null) {
            $valueSet = ApplicationPeer::getValueSet(ApplicationPeer::GENDER);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->gender !== $v) {
            $this->gender = $v;
            $this->modifiedColumns[] = ApplicationPeer::GENDER;
        }


        return $this;
    } // setGender()

    /**
     * Set the value of [place_of_birth] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setPlaceOfBirth($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->place_of_birth !== $v) {
            $this->place_of_birth = $v;
            $this->modifiedColumns[] = ApplicationPeer::PLACE_OF_BIRTH;
        }


        return $this;
    } // setPlaceOfBirth()

    /**
     * Sets the value of [date_of_birth] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Application The current object (for fluent API support)
     */
    public function setDateOfBirth($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date_of_birth !== null || $dt !== null) {
            $currentDateAsString = ($this->date_of_birth !== null && $tmpDt = new DateTime($this->date_of_birth)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->date_of_birth = $newDateAsString;
                $this->modifiedColumns[] = ApplicationPeer::DATE_OF_BIRTH;
            }
        } // if either are not null


        return $this;
    } // setDateOfBirth()

    /**
     * Set the value of [religion] column.
     *
     * @param  int $v new value
     * @return Application The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setReligion($v)
    {
        if ($v !== null) {
            $valueSet = ApplicationPeer::getValueSet(ApplicationPeer::RELIGION);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->religion !== $v) {
            $this->religion = $v;
            $this->modifiedColumns[] = ApplicationPeer::RELIGION;
        }


        return $this;
    } // setReligion()

    /**
     * Set the value of [level_id] column.
     *
     * @param  int $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setLevelId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->level_id !== $v) {
            $this->level_id = $v;
            $this->modifiedColumns[] = ApplicationPeer::LEVEL_ID;
        }

        if ($this->aLevel !== null && $this->aLevel->getId() !== $v) {
            $this->aLevel = null;
        }


        return $this;
    } // setLevelId()

    /**
     * Set the value of [grade_id] column.
     *
     * @param  int $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setGradeId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->grade_id !== $v) {
            $this->grade_id = $v;
            $this->modifiedColumns[] = ApplicationPeer::GRADE_ID;
        }

        if ($this->aGrade !== null && $this->aGrade->getId() !== $v) {
            $this->aGrade = null;
        }


        return $this;
    } // setGradeId()

    /**
     * Set the value of [ethnicity_id] column.
     *
     * @param  int $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setEthnicityId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->ethnicity_id !== $v) {
            $this->ethnicity_id = $v;
            $this->modifiedColumns[] = ApplicationPeer::ETHNICITY_ID;
        }

        if ($this->aEthnicity !== null && $this->aEthnicity->getId() !== $v) {
            $this->aEthnicity = null;
        }


        return $this;
    } // setEthnicityId()

    /**
     * Set the value of [child_no] column.
     *
     * @param  int $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setChildNo($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->child_no !== $v) {
            $this->child_no = $v;
            $this->modifiedColumns[] = ApplicationPeer::CHILD_NO;
        }


        return $this;
    } // setChildNo()

    /**
     * Set the value of [child_total] column.
     *
     * @param  int $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setChildTotal($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->child_total !== $v) {
            $this->child_total = $v;
            $this->modifiedColumns[] = ApplicationPeer::CHILD_TOTAL;
        }


        return $this;
    } // setChildTotal()

    /**
     * Set the value of [child_status] column.
     *
     * @param  int $v new value
     * @return Application The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setChildStatus($v)
    {
        if ($v !== null) {
            $valueSet = ApplicationPeer::getValueSet(ApplicationPeer::CHILD_STATUS);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->child_status !== $v) {
            $this->child_status = $v;
            $this->modifiedColumns[] = ApplicationPeer::CHILD_STATUS;
        }


        return $this;
    } // setChildStatus()

    /**
     * Set the value of [picture] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setPicture($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->picture !== $v) {
            $this->picture = $v;
            $this->modifiedColumns[] = ApplicationPeer::PICTURE;
        }


        return $this;
    } // setPicture()

    /**
     * Set the value of [birth_certificate] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setBirthCertificate($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->birth_certificate !== $v) {
            $this->birth_certificate = $v;
            $this->modifiedColumns[] = ApplicationPeer::BIRTH_CERTIFICATE;
        }


        return $this;
    } // setBirthCertificate()

    /**
     * Set the value of [family_card] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setFamilyCard($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->family_card !== $v) {
            $this->family_card = $v;
            $this->modifiedColumns[] = ApplicationPeer::FAMILY_CARD;
        }


        return $this;
    } // setFamilyCard()

    /**
     * Set the value of [graduation_certificate] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setGraduationCertificate($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->graduation_certificate !== $v) {
            $this->graduation_certificate = $v;
            $this->modifiedColumns[] = ApplicationPeer::GRADUATION_CERTIFICATE;
        }


        return $this;
    } // setGraduationCertificate()

    /**
     * Set the value of [address] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setAddress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->address !== $v) {
            $this->address = $v;
            $this->modifiedColumns[] = ApplicationPeer::ADDRESS;
        }


        return $this;
    } // setAddress()

    /**
     * Set the value of [city] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setCity($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->city !== $v) {
            $this->city = $v;
            $this->modifiedColumns[] = ApplicationPeer::CITY;
        }


        return $this;
    } // setCity()

    /**
     * Set the value of [state_id] column.
     *
     * @param  int $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setStateId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->state_id !== $v) {
            $this->state_id = $v;
            $this->modifiedColumns[] = ApplicationPeer::STATE_ID;
        }

        if ($this->aState !== null && $this->aState->getId() !== $v) {
            $this->aState = null;
        }


        return $this;
    } // setStateId()

    /**
     * Set the value of [zip] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setZip($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->zip !== $v) {
            $this->zip = $v;
            $this->modifiedColumns[] = ApplicationPeer::ZIP;
        }


        return $this;
    } // setZip()

    /**
     * Set the value of [country_id] column.
     *
     * @param  int $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setCountryId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->country_id !== $v) {
            $this->country_id = $v;
            $this->modifiedColumns[] = ApplicationPeer::COUNTRY_ID;
        }

        if ($this->aCountry !== null && $this->aCountry->getId() !== $v) {
            $this->aCountry = null;
        }


        return $this;
    } // setCountryId()

    /**
     * Set the value of [school_id] column.
     *
     * @param  int $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setSchoolId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->school_id !== $v) {
            $this->school_id = $v;
            $this->modifiedColumns[] = ApplicationPeer::SCHOOL_ID;
        }

        if ($this->aSchool !== null && $this->aSchool->getId() !== $v) {
            $this->aSchool = null;
        }


        return $this;
    } // setSchoolId()

    /**
     * Set the value of [school_year_id] column.
     *
     * @param  int $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setSchoolYearId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->school_year_id !== $v) {
            $this->school_year_id = $v;
            $this->modifiedColumns[] = ApplicationPeer::SCHOOL_YEAR_ID;
        }

        if ($this->aSchoolYear !== null && $this->aSchoolYear->getId() !== $v) {
            $this->aSchoolYear = null;
        }


        return $this;
    } // setSchoolYearId()

    /**
     * Set the value of [status] column.
     *
     * @param  int $v new value
     * @return Application The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            $valueSet = ApplicationPeer::getValueSet(ApplicationPeer::STATUS);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[] = ApplicationPeer::STATUS;
        }


        return $this;
    } // setStatus()

    /**
     * Set the value of [mailing_address] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setMailingAddress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->mailing_address !== $v) {
            $this->mailing_address = $v;
            $this->modifiedColumns[] = ApplicationPeer::MAILING_ADDRESS;
        }


        return $this;
    } // setMailingAddress()

    /**
     * Set the value of [note] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setNote($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->note !== $v) {
            $this->note = $v;
            $this->modifiedColumns[] = ApplicationPeer::NOTE;
        }


        return $this;
    } // setNote()

    /**
     * Set the value of [hobby] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setHobby($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->hobby !== $v) {
            $this->hobby = $v;
            $this->modifiedColumns[] = ApplicationPeer::HOBBY;
        }


        return $this;
    } // setHobby()

    /**
     * Set the value of [entered_by] column.
     *
     * @param  int $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setEnteredBy($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->entered_by !== $v) {
            $this->entered_by = $v;
            $this->modifiedColumns[] = ApplicationPeer::ENTERED_BY;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }


        return $this;
    } // setEnteredBy()

    /**
     * Set the value of [first_name_father] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setFirstNameFather($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->first_name_father !== $v) {
            $this->first_name_father = $v;
            $this->modifiedColumns[] = ApplicationPeer::FIRST_NAME_FATHER;
        }


        return $this;
    } // setFirstNameFather()

    /**
     * Set the value of [last_name_father] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setLastNameFather($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->last_name_father !== $v) {
            $this->last_name_father = $v;
            $this->modifiedColumns[] = ApplicationPeer::LAST_NAME_FATHER;
        }


        return $this;
    } // setLastNameFather()

    /**
     * Set the value of [business_address_father] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setBusinessAddressFather($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->business_address_father !== $v) {
            $this->business_address_father = $v;
            $this->modifiedColumns[] = ApplicationPeer::BUSINESS_ADDRESS_FATHER;
        }


        return $this;
    } // setBusinessAddressFather()

    /**
     * Set the value of [occupation_father] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setOccupationFather($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->occupation_father !== $v) {
            $this->occupation_father = $v;
            $this->modifiedColumns[] = ApplicationPeer::OCCUPATION_FATHER;
        }


        return $this;
    } // setOccupationFather()

    /**
     * Set the value of [phone_father] column.
     *
     * @param  int $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setPhoneFather($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->phone_father !== $v) {
            $this->phone_father = $v;
            $this->modifiedColumns[] = ApplicationPeer::PHONE_FATHER;
        }


        return $this;
    } // setPhoneFather()

    /**
     * Set the value of [email_father] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setEmailFather($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email_father !== $v) {
            $this->email_father = $v;
            $this->modifiedColumns[] = ApplicationPeer::EMAIL_FATHER;
        }


        return $this;
    } // setEmailFather()

    /**
     * Set the value of [first_name_mother] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setFirstNameMother($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->first_name_mother !== $v) {
            $this->first_name_mother = $v;
            $this->modifiedColumns[] = ApplicationPeer::FIRST_NAME_MOTHER;
        }


        return $this;
    } // setFirstNameMother()

    /**
     * Set the value of [last_name_mother] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setLastNameMother($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->last_name_mother !== $v) {
            $this->last_name_mother = $v;
            $this->modifiedColumns[] = ApplicationPeer::LAST_NAME_MOTHER;
        }


        return $this;
    } // setLastNameMother()

    /**
     * Set the value of [business_address_mother] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setBusinessAddressMother($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->business_address_mother !== $v) {
            $this->business_address_mother = $v;
            $this->modifiedColumns[] = ApplicationPeer::BUSINESS_ADDRESS_MOTHER;
        }


        return $this;
    } // setBusinessAddressMother()

    /**
     * Set the value of [occupation_mother] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setOccupationMother($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->occupation_mother !== $v) {
            $this->occupation_mother = $v;
            $this->modifiedColumns[] = ApplicationPeer::OCCUPATION_MOTHER;
        }


        return $this;
    } // setOccupationMother()

    /**
     * Set the value of [phone_mother] column.
     *
     * @param  int $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setPhoneMother($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->phone_mother !== $v) {
            $this->phone_mother = $v;
            $this->modifiedColumns[] = ApplicationPeer::PHONE_MOTHER;
        }


        return $this;
    } // setPhoneMother()

    /**
     * Set the value of [email_mother] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setEmailMother($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email_mother !== $v) {
            $this->email_mother = $v;
            $this->modifiedColumns[] = ApplicationPeer::EMAIL_MOTHER;
        }


        return $this;
    } // setEmailMother()

    /**
     * Set the value of [first_name_legal_guardian] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setFirstNameLegalGuardian($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->first_name_legal_guardian !== $v) {
            $this->first_name_legal_guardian = $v;
            $this->modifiedColumns[] = ApplicationPeer::FIRST_NAME_LEGAL_GUARDIAN;
        }


        return $this;
    } // setFirstNameLegalGuardian()

    /**
     * Set the value of [last_name_legal_guardian] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setLastNameLegalGuardian($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->last_name_legal_guardian !== $v) {
            $this->last_name_legal_guardian = $v;
            $this->modifiedColumns[] = ApplicationPeer::LAST_NAME_LEGAL_GUARDIAN;
        }


        return $this;
    } // setLastNameLegalGuardian()

    /**
     * Set the value of [occupation_legal_guardian] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setOccupationLegalGuardian($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->occupation_legal_guardian !== $v) {
            $this->occupation_legal_guardian = $v;
            $this->modifiedColumns[] = ApplicationPeer::OCCUPATION_LEGAL_GUARDIAN;
        }


        return $this;
    } // setOccupationLegalGuardian()

    /**
     * Set the value of [phone_legal_guardian] column.
     *
     * @param  int $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setPhoneLegalGuardian($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->phone_legal_guardian !== $v) {
            $this->phone_legal_guardian = $v;
            $this->modifiedColumns[] = ApplicationPeer::PHONE_LEGAL_GUARDIAN;
        }


        return $this;
    } // setPhoneLegalGuardian()

    /**
     * Set the value of [email_legal_guardian] column.
     *
     * @param  string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setEmailLegalGuardian($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email_legal_guardian !== $v) {
            $this->email_legal_guardian = $v;
            $this->modifiedColumns[] = ApplicationPeer::EMAIL_LEGAL_GUARDIAN;
        }


        return $this;
    } // setEmailLegalGuardian()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Application The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = ApplicationPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Application The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = ApplicationPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->gender !== 0) {
                return false;
            }

            if ($this->religion !== 0) {
                return false;
            }

            if ($this->child_no !== 0) {
                return false;
            }

            if ($this->child_total !== 0) {
                return false;
            }

            if ($this->child_status !== 0) {
                return false;
            }

            if ($this->country_id !== 105) {
                return false;
            }

            if ($this->status !== 0) {
                return false;
            }

        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->form_no = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->student_nation_no = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->prior_test_no = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->student_no = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->first_name = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->last_name = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->nick_name = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->phone_student = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->gender = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->place_of_birth = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->date_of_birth = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->religion = ($row[$startcol + 12] !== null) ? (int) $row[$startcol + 12] : null;
            $this->level_id = ($row[$startcol + 13] !== null) ? (int) $row[$startcol + 13] : null;
            $this->grade_id = ($row[$startcol + 14] !== null) ? (int) $row[$startcol + 14] : null;
            $this->ethnicity_id = ($row[$startcol + 15] !== null) ? (int) $row[$startcol + 15] : null;
            $this->child_no = ($row[$startcol + 16] !== null) ? (int) $row[$startcol + 16] : null;
            $this->child_total = ($row[$startcol + 17] !== null) ? (int) $row[$startcol + 17] : null;
            $this->child_status = ($row[$startcol + 18] !== null) ? (int) $row[$startcol + 18] : null;
            $this->picture = ($row[$startcol + 19] !== null) ? (string) $row[$startcol + 19] : null;
            $this->birth_certificate = ($row[$startcol + 20] !== null) ? (string) $row[$startcol + 20] : null;
            $this->family_card = ($row[$startcol + 21] !== null) ? (string) $row[$startcol + 21] : null;
            $this->graduation_certificate = ($row[$startcol + 22] !== null) ? (string) $row[$startcol + 22] : null;
            $this->address = ($row[$startcol + 23] !== null) ? (string) $row[$startcol + 23] : null;
            $this->city = ($row[$startcol + 24] !== null) ? (string) $row[$startcol + 24] : null;
            $this->state_id = ($row[$startcol + 25] !== null) ? (int) $row[$startcol + 25] : null;
            $this->zip = ($row[$startcol + 26] !== null) ? (string) $row[$startcol + 26] : null;
            $this->country_id = ($row[$startcol + 27] !== null) ? (int) $row[$startcol + 27] : null;
            $this->school_id = ($row[$startcol + 28] !== null) ? (int) $row[$startcol + 28] : null;
            $this->school_year_id = ($row[$startcol + 29] !== null) ? (int) $row[$startcol + 29] : null;
            $this->status = ($row[$startcol + 30] !== null) ? (int) $row[$startcol + 30] : null;
            $this->mailing_address = ($row[$startcol + 31] !== null) ? (string) $row[$startcol + 31] : null;
            $this->note = ($row[$startcol + 32] !== null) ? (string) $row[$startcol + 32] : null;
            $this->hobby = ($row[$startcol + 33] !== null) ? (string) $row[$startcol + 33] : null;
            $this->entered_by = ($row[$startcol + 34] !== null) ? (int) $row[$startcol + 34] : null;
            $this->first_name_father = ($row[$startcol + 35] !== null) ? (string) $row[$startcol + 35] : null;
            $this->last_name_father = ($row[$startcol + 36] !== null) ? (string) $row[$startcol + 36] : null;
            $this->business_address_father = ($row[$startcol + 37] !== null) ? (string) $row[$startcol + 37] : null;
            $this->occupation_father = ($row[$startcol + 38] !== null) ? (string) $row[$startcol + 38] : null;
            $this->phone_father = ($row[$startcol + 39] !== null) ? (int) $row[$startcol + 39] : null;
            $this->email_father = ($row[$startcol + 40] !== null) ? (string) $row[$startcol + 40] : null;
            $this->first_name_mother = ($row[$startcol + 41] !== null) ? (string) $row[$startcol + 41] : null;
            $this->last_name_mother = ($row[$startcol + 42] !== null) ? (string) $row[$startcol + 42] : null;
            $this->business_address_mother = ($row[$startcol + 43] !== null) ? (string) $row[$startcol + 43] : null;
            $this->occupation_mother = ($row[$startcol + 44] !== null) ? (string) $row[$startcol + 44] : null;
            $this->phone_mother = ($row[$startcol + 45] !== null) ? (int) $row[$startcol + 45] : null;
            $this->email_mother = ($row[$startcol + 46] !== null) ? (string) $row[$startcol + 46] : null;
            $this->first_name_legal_guardian = ($row[$startcol + 47] !== null) ? (string) $row[$startcol + 47] : null;
            $this->last_name_legal_guardian = ($row[$startcol + 48] !== null) ? (string) $row[$startcol + 48] : null;
            $this->occupation_legal_guardian = ($row[$startcol + 49] !== null) ? (string) $row[$startcol + 49] : null;
            $this->phone_legal_guardian = ($row[$startcol + 50] !== null) ? (int) $row[$startcol + 50] : null;
            $this->email_legal_guardian = ($row[$startcol + 51] !== null) ? (string) $row[$startcol + 51] : null;
            $this->created_at = ($row[$startcol + 52] !== null) ? (string) $row[$startcol + 52] : null;
            $this->updated_at = ($row[$startcol + 53] !== null) ? (string) $row[$startcol + 53] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 54; // 54 = ApplicationPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Application object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

        if ($this->aLevel !== null && $this->level_id !== $this->aLevel->getId()) {
            $this->aLevel = null;
        }
        if ($this->aGrade !== null && $this->grade_id !== $this->aGrade->getId()) {
            $this->aGrade = null;
        }
        if ($this->aEthnicity !== null && $this->ethnicity_id !== $this->aEthnicity->getId()) {
            $this->aEthnicity = null;
        }
        if ($this->aState !== null && $this->state_id !== $this->aState->getId()) {
            $this->aState = null;
        }
        if ($this->aCountry !== null && $this->country_id !== $this->aCountry->getId()) {
            $this->aCountry = null;
        }
        if ($this->aSchool !== null && $this->school_id !== $this->aSchool->getId()) {
            $this->aSchool = null;
        }
        if ($this->aSchoolYear !== null && $this->school_year_id !== $this->aSchoolYear->getId()) {
            $this->aSchoolYear = null;
        }
        if ($this->aUser !== null && $this->entered_by !== $this->aUser->getId()) {
            $this->aUser = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = ApplicationPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->aSchool = null;
            $this->aSchoolYear = null;
            $this->aEthnicity = null;
            $this->aGrade = null;
            $this->aLevel = null;
            $this->aState = null;
            $this->aCountry = null;
            $this->collParentStudents = null;

            $this->collSchoolHealths = null;

            $this->collStudents = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
            $deleteQuery = ApplicationQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                // event behavior
                EventDispatcherProxy::trigger(array('delete.post', 'model.delete.post'), new ModelEvent($this));
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // event behavior
            EventDispatcherProxy::trigger('model.save.pre', new ModelEvent($this));
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(ApplicationPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(ApplicationPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(ApplicationPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger(array('update.pre', 'model.update.pre'), new ModelEvent($this));
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                    // event behavior
                    EventDispatcherProxy::trigger('model.insert.post', new ModelEvent($this));
                } else {
                    $this->postUpdate($con);
                    // event behavior
                    EventDispatcherProxy::trigger(array('update.post', 'model.update.post'), new ModelEvent($this));
                }
                $this->postSave($con);
                // event behavior
                EventDispatcherProxy::trigger('model.save.post', new ModelEvent($this));
                ApplicationPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
            }

            if ($this->aSchool !== null) {
                if ($this->aSchool->isModified() || $this->aSchool->isNew()) {
                    $affectedRows += $this->aSchool->save($con);
                }
                $this->setSchool($this->aSchool);
            }

            if ($this->aSchoolYear !== null) {
                if ($this->aSchoolYear->isModified() || $this->aSchoolYear->isNew()) {
                    $affectedRows += $this->aSchoolYear->save($con);
                }
                $this->setSchoolYear($this->aSchoolYear);
            }

            if ($this->aEthnicity !== null) {
                if ($this->aEthnicity->isModified() || $this->aEthnicity->isNew()) {
                    $affectedRows += $this->aEthnicity->save($con);
                }
                $this->setEthnicity($this->aEthnicity);
            }

            if ($this->aGrade !== null) {
                if ($this->aGrade->isModified() || $this->aGrade->isNew()) {
                    $affectedRows += $this->aGrade->save($con);
                }
                $this->setGrade($this->aGrade);
            }

            if ($this->aLevel !== null) {
                if ($this->aLevel->isModified() || $this->aLevel->isNew()) {
                    $affectedRows += $this->aLevel->save($con);
                }
                $this->setLevel($this->aLevel);
            }

            if ($this->aState !== null) {
                if ($this->aState->isModified() || $this->aState->isNew()) {
                    $affectedRows += $this->aState->save($con);
                }
                $this->setState($this->aState);
            }

            if ($this->aCountry !== null) {
                if ($this->aCountry->isModified() || $this->aCountry->isNew()) {
                    $affectedRows += $this->aCountry->save($con);
                }
                $this->setCountry($this->aCountry);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->parentStudentsScheduledForDeletion !== null) {
                if (!$this->parentStudentsScheduledForDeletion->isEmpty()) {
                    ParentStudentQuery::create()
                        ->filterByPrimaryKeys($this->parentStudentsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->parentStudentsScheduledForDeletion = null;
                }
            }

            if ($this->collParentStudents !== null) {
                foreach ($this->collParentStudents as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->schoolHealthsScheduledForDeletion !== null) {
                if (!$this->schoolHealthsScheduledForDeletion->isEmpty()) {
                    foreach ($this->schoolHealthsScheduledForDeletion as $schoolHealth) {
                        // need to save related object because we set the relation to null
                        $schoolHealth->save($con);
                    }
                    $this->schoolHealthsScheduledForDeletion = null;
                }
            }

            if ($this->collSchoolHealths !== null) {
                foreach ($this->collSchoolHealths as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->studentsScheduledForDeletion !== null) {
                if (!$this->studentsScheduledForDeletion->isEmpty()) {
                    foreach ($this->studentsScheduledForDeletion as $student) {
                        // need to save related object because we set the relation to null
                        $student->save($con);
                    }
                    $this->studentsScheduledForDeletion = null;
                }
            }

            if ($this->collStudents !== null) {
                foreach ($this->collStudents as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = ApplicationPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ApplicationPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ApplicationPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(ApplicationPeer::FORM_NO)) {
            $modifiedColumns[':p' . $index++]  = '`form_no`';
        }
        if ($this->isColumnModified(ApplicationPeer::STUDENT_NATION_NO)) {
            $modifiedColumns[':p' . $index++]  = '`student_nation_no`';
        }
        if ($this->isColumnModified(ApplicationPeer::PRIOR_TEST_NO)) {
            $modifiedColumns[':p' . $index++]  = '`prior_test_no`';
        }
        if ($this->isColumnModified(ApplicationPeer::STUDENT_NO)) {
            $modifiedColumns[':p' . $index++]  = '`student_no`';
        }
        if ($this->isColumnModified(ApplicationPeer::FIRST_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`first_name`';
        }
        if ($this->isColumnModified(ApplicationPeer::LAST_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`last_name`';
        }
        if ($this->isColumnModified(ApplicationPeer::NICK_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`nick_name`';
        }
        if ($this->isColumnModified(ApplicationPeer::PHONE_STUDENT)) {
            $modifiedColumns[':p' . $index++]  = '`phone_student`';
        }
        if ($this->isColumnModified(ApplicationPeer::GENDER)) {
            $modifiedColumns[':p' . $index++]  = '`gender`';
        }
        if ($this->isColumnModified(ApplicationPeer::PLACE_OF_BIRTH)) {
            $modifiedColumns[':p' . $index++]  = '`place_of_birth`';
        }
        if ($this->isColumnModified(ApplicationPeer::DATE_OF_BIRTH)) {
            $modifiedColumns[':p' . $index++]  = '`date_of_birth`';
        }
        if ($this->isColumnModified(ApplicationPeer::RELIGION)) {
            $modifiedColumns[':p' . $index++]  = '`religion`';
        }
        if ($this->isColumnModified(ApplicationPeer::LEVEL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`level_id`';
        }
        if ($this->isColumnModified(ApplicationPeer::GRADE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`grade_id`';
        }
        if ($this->isColumnModified(ApplicationPeer::ETHNICITY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`ethnicity_id`';
        }
        if ($this->isColumnModified(ApplicationPeer::CHILD_NO)) {
            $modifiedColumns[':p' . $index++]  = '`child_no`';
        }
        if ($this->isColumnModified(ApplicationPeer::CHILD_TOTAL)) {
            $modifiedColumns[':p' . $index++]  = '`child_total`';
        }
        if ($this->isColumnModified(ApplicationPeer::CHILD_STATUS)) {
            $modifiedColumns[':p' . $index++]  = '`child_status`';
        }
        if ($this->isColumnModified(ApplicationPeer::PICTURE)) {
            $modifiedColumns[':p' . $index++]  = '`picture`';
        }
        if ($this->isColumnModified(ApplicationPeer::BIRTH_CERTIFICATE)) {
            $modifiedColumns[':p' . $index++]  = '`birth_certificate`';
        }
        if ($this->isColumnModified(ApplicationPeer::FAMILY_CARD)) {
            $modifiedColumns[':p' . $index++]  = '`family_card`';
        }
        if ($this->isColumnModified(ApplicationPeer::GRADUATION_CERTIFICATE)) {
            $modifiedColumns[':p' . $index++]  = '`graduation_certificate`';
        }
        if ($this->isColumnModified(ApplicationPeer::ADDRESS)) {
            $modifiedColumns[':p' . $index++]  = '`address`';
        }
        if ($this->isColumnModified(ApplicationPeer::CITY)) {
            $modifiedColumns[':p' . $index++]  = '`city`';
        }
        if ($this->isColumnModified(ApplicationPeer::STATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`state_id`';
        }
        if ($this->isColumnModified(ApplicationPeer::ZIP)) {
            $modifiedColumns[':p' . $index++]  = '`zip`';
        }
        if ($this->isColumnModified(ApplicationPeer::COUNTRY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`country_id`';
        }
        if ($this->isColumnModified(ApplicationPeer::SCHOOL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`school_id`';
        }
        if ($this->isColumnModified(ApplicationPeer::SCHOOL_YEAR_ID)) {
            $modifiedColumns[':p' . $index++]  = '`school_year_id`';
        }
        if ($this->isColumnModified(ApplicationPeer::STATUS)) {
            $modifiedColumns[':p' . $index++]  = '`status`';
        }
        if ($this->isColumnModified(ApplicationPeer::MAILING_ADDRESS)) {
            $modifiedColumns[':p' . $index++]  = '`mailing_address`';
        }
        if ($this->isColumnModified(ApplicationPeer::NOTE)) {
            $modifiedColumns[':p' . $index++]  = '`note`';
        }
        if ($this->isColumnModified(ApplicationPeer::HOBBY)) {
            $modifiedColumns[':p' . $index++]  = '`hobby`';
        }
        if ($this->isColumnModified(ApplicationPeer::ENTERED_BY)) {
            $modifiedColumns[':p' . $index++]  = '`entered_by`';
        }
        if ($this->isColumnModified(ApplicationPeer::FIRST_NAME_FATHER)) {
            $modifiedColumns[':p' . $index++]  = '`first_name_father`';
        }
        if ($this->isColumnModified(ApplicationPeer::LAST_NAME_FATHER)) {
            $modifiedColumns[':p' . $index++]  = '`last_name_father`';
        }
        if ($this->isColumnModified(ApplicationPeer::BUSINESS_ADDRESS_FATHER)) {
            $modifiedColumns[':p' . $index++]  = '`business_address_father`';
        }
        if ($this->isColumnModified(ApplicationPeer::OCCUPATION_FATHER)) {
            $modifiedColumns[':p' . $index++]  = '`occupation_father`';
        }
        if ($this->isColumnModified(ApplicationPeer::PHONE_FATHER)) {
            $modifiedColumns[':p' . $index++]  = '`phone_father`';
        }
        if ($this->isColumnModified(ApplicationPeer::EMAIL_FATHER)) {
            $modifiedColumns[':p' . $index++]  = '`email_father`';
        }
        if ($this->isColumnModified(ApplicationPeer::FIRST_NAME_MOTHER)) {
            $modifiedColumns[':p' . $index++]  = '`first_name_mother`';
        }
        if ($this->isColumnModified(ApplicationPeer::LAST_NAME_MOTHER)) {
            $modifiedColumns[':p' . $index++]  = '`last_name_mother`';
        }
        if ($this->isColumnModified(ApplicationPeer::BUSINESS_ADDRESS_MOTHER)) {
            $modifiedColumns[':p' . $index++]  = '`business_address_mother`';
        }
        if ($this->isColumnModified(ApplicationPeer::OCCUPATION_MOTHER)) {
            $modifiedColumns[':p' . $index++]  = '`occupation_mother`';
        }
        if ($this->isColumnModified(ApplicationPeer::PHONE_MOTHER)) {
            $modifiedColumns[':p' . $index++]  = '`phone_mother`';
        }
        if ($this->isColumnModified(ApplicationPeer::EMAIL_MOTHER)) {
            $modifiedColumns[':p' . $index++]  = '`email_mother`';
        }
        if ($this->isColumnModified(ApplicationPeer::FIRST_NAME_LEGAL_GUARDIAN)) {
            $modifiedColumns[':p' . $index++]  = '`first_name_legal_guardian`';
        }
        if ($this->isColumnModified(ApplicationPeer::LAST_NAME_LEGAL_GUARDIAN)) {
            $modifiedColumns[':p' . $index++]  = '`last_name_legal_guardian`';
        }
        if ($this->isColumnModified(ApplicationPeer::OCCUPATION_LEGAL_GUARDIAN)) {
            $modifiedColumns[':p' . $index++]  = '`occupation_legal_guardian`';
        }
        if ($this->isColumnModified(ApplicationPeer::PHONE_LEGAL_GUARDIAN)) {
            $modifiedColumns[':p' . $index++]  = '`phone_legal_guardian`';
        }
        if ($this->isColumnModified(ApplicationPeer::EMAIL_LEGAL_GUARDIAN)) {
            $modifiedColumns[':p' . $index++]  = '`email_legal_guardian`';
        }
        if ($this->isColumnModified(ApplicationPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(ApplicationPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `application` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`form_no`':
                        $stmt->bindValue($identifier, $this->form_no, PDO::PARAM_STR);
                        break;
                    case '`student_nation_no`':
                        $stmt->bindValue($identifier, $this->student_nation_no, PDO::PARAM_STR);
                        break;
                    case '`prior_test_no`':
                        $stmt->bindValue($identifier, $this->prior_test_no, PDO::PARAM_STR);
                        break;
                    case '`student_no`':
                        $stmt->bindValue($identifier, $this->student_no, PDO::PARAM_STR);
                        break;
                    case '`first_name`':
                        $stmt->bindValue($identifier, $this->first_name, PDO::PARAM_STR);
                        break;
                    case '`last_name`':
                        $stmt->bindValue($identifier, $this->last_name, PDO::PARAM_STR);
                        break;
                    case '`nick_name`':
                        $stmt->bindValue($identifier, $this->nick_name, PDO::PARAM_STR);
                        break;
                    case '`phone_student`':
                        $stmt->bindValue($identifier, $this->phone_student, PDO::PARAM_INT);
                        break;
                    case '`gender`':
                        $stmt->bindValue($identifier, $this->gender, PDO::PARAM_INT);
                        break;
                    case '`place_of_birth`':
                        $stmt->bindValue($identifier, $this->place_of_birth, PDO::PARAM_STR);
                        break;
                    case '`date_of_birth`':
                        $stmt->bindValue($identifier, $this->date_of_birth, PDO::PARAM_STR);
                        break;
                    case '`religion`':
                        $stmt->bindValue($identifier, $this->religion, PDO::PARAM_INT);
                        break;
                    case '`level_id`':
                        $stmt->bindValue($identifier, $this->level_id, PDO::PARAM_INT);
                        break;
                    case '`grade_id`':
                        $stmt->bindValue($identifier, $this->grade_id, PDO::PARAM_INT);
                        break;
                    case '`ethnicity_id`':
                        $stmt->bindValue($identifier, $this->ethnicity_id, PDO::PARAM_INT);
                        break;
                    case '`child_no`':
                        $stmt->bindValue($identifier, $this->child_no, PDO::PARAM_INT);
                        break;
                    case '`child_total`':
                        $stmt->bindValue($identifier, $this->child_total, PDO::PARAM_INT);
                        break;
                    case '`child_status`':
                        $stmt->bindValue($identifier, $this->child_status, PDO::PARAM_INT);
                        break;
                    case '`picture`':
                        $stmt->bindValue($identifier, $this->picture, PDO::PARAM_STR);
                        break;
                    case '`birth_certificate`':
                        $stmt->bindValue($identifier, $this->birth_certificate, PDO::PARAM_STR);
                        break;
                    case '`family_card`':
                        $stmt->bindValue($identifier, $this->family_card, PDO::PARAM_STR);
                        break;
                    case '`graduation_certificate`':
                        $stmt->bindValue($identifier, $this->graduation_certificate, PDO::PARAM_STR);
                        break;
                    case '`address`':
                        $stmt->bindValue($identifier, $this->address, PDO::PARAM_STR);
                        break;
                    case '`city`':
                        $stmt->bindValue($identifier, $this->city, PDO::PARAM_STR);
                        break;
                    case '`state_id`':
                        $stmt->bindValue($identifier, $this->state_id, PDO::PARAM_INT);
                        break;
                    case '`zip`':
                        $stmt->bindValue($identifier, $this->zip, PDO::PARAM_STR);
                        break;
                    case '`country_id`':
                        $stmt->bindValue($identifier, $this->country_id, PDO::PARAM_INT);
                        break;
                    case '`school_id`':
                        $stmt->bindValue($identifier, $this->school_id, PDO::PARAM_INT);
                        break;
                    case '`school_year_id`':
                        $stmt->bindValue($identifier, $this->school_year_id, PDO::PARAM_INT);
                        break;
                    case '`status`':
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_INT);
                        break;
                    case '`mailing_address`':
                        $stmt->bindValue($identifier, $this->mailing_address, PDO::PARAM_STR);
                        break;
                    case '`note`':
                        $stmt->bindValue($identifier, $this->note, PDO::PARAM_STR);
                        break;
                    case '`hobby`':
                        $stmt->bindValue($identifier, $this->hobby, PDO::PARAM_STR);
                        break;
                    case '`entered_by`':
                        $stmt->bindValue($identifier, $this->entered_by, PDO::PARAM_INT);
                        break;
                    case '`first_name_father`':
                        $stmt->bindValue($identifier, $this->first_name_father, PDO::PARAM_STR);
                        break;
                    case '`last_name_father`':
                        $stmt->bindValue($identifier, $this->last_name_father, PDO::PARAM_STR);
                        break;
                    case '`business_address_father`':
                        $stmt->bindValue($identifier, $this->business_address_father, PDO::PARAM_STR);
                        break;
                    case '`occupation_father`':
                        $stmt->bindValue($identifier, $this->occupation_father, PDO::PARAM_STR);
                        break;
                    case '`phone_father`':
                        $stmt->bindValue($identifier, $this->phone_father, PDO::PARAM_INT);
                        break;
                    case '`email_father`':
                        $stmt->bindValue($identifier, $this->email_father, PDO::PARAM_STR);
                        break;
                    case '`first_name_mother`':
                        $stmt->bindValue($identifier, $this->first_name_mother, PDO::PARAM_STR);
                        break;
                    case '`last_name_mother`':
                        $stmt->bindValue($identifier, $this->last_name_mother, PDO::PARAM_STR);
                        break;
                    case '`business_address_mother`':
                        $stmt->bindValue($identifier, $this->business_address_mother, PDO::PARAM_STR);
                        break;
                    case '`occupation_mother`':
                        $stmt->bindValue($identifier, $this->occupation_mother, PDO::PARAM_STR);
                        break;
                    case '`phone_mother`':
                        $stmt->bindValue($identifier, $this->phone_mother, PDO::PARAM_INT);
                        break;
                    case '`email_mother`':
                        $stmt->bindValue($identifier, $this->email_mother, PDO::PARAM_STR);
                        break;
                    case '`first_name_legal_guardian`':
                        $stmt->bindValue($identifier, $this->first_name_legal_guardian, PDO::PARAM_STR);
                        break;
                    case '`last_name_legal_guardian`':
                        $stmt->bindValue($identifier, $this->last_name_legal_guardian, PDO::PARAM_STR);
                        break;
                    case '`occupation_legal_guardian`':
                        $stmt->bindValue($identifier, $this->occupation_legal_guardian, PDO::PARAM_STR);
                        break;
                    case '`phone_legal_guardian`':
                        $stmt->bindValue($identifier, $this->phone_legal_guardian, PDO::PARAM_INT);
                        break;
                    case '`email_legal_guardian`':
                        $stmt->bindValue($identifier, $this->email_legal_guardian, PDO::PARAM_STR);
                        break;
                    case '`created_at`':
                        $stmt->bindValue($identifier, $this->created_at, PDO::PARAM_STR);
                        break;
                    case '`updated_at`':
                        $stmt->bindValue($identifier, $this->updated_at, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aUser !== null) {
                if (!$this->aUser->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUser->getValidationFailures());
                }
            }

            if ($this->aSchool !== null) {
                if (!$this->aSchool->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aSchool->getValidationFailures());
                }
            }

            if ($this->aSchoolYear !== null) {
                if (!$this->aSchoolYear->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aSchoolYear->getValidationFailures());
                }
            }

            if ($this->aEthnicity !== null) {
                if (!$this->aEthnicity->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aEthnicity->getValidationFailures());
                }
            }

            if ($this->aGrade !== null) {
                if (!$this->aGrade->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aGrade->getValidationFailures());
                }
            }

            if ($this->aLevel !== null) {
                if (!$this->aLevel->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aLevel->getValidationFailures());
                }
            }

            if ($this->aState !== null) {
                if (!$this->aState->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aState->getValidationFailures());
                }
            }

            if ($this->aCountry !== null) {
                if (!$this->aCountry->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCountry->getValidationFailures());
                }
            }


            if (($retval = ApplicationPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collParentStudents !== null) {
                    foreach ($this->collParentStudents as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSchoolHealths !== null) {
                    foreach ($this->collSchoolHealths as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collStudents !== null) {
                    foreach ($this->collStudents as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = ApplicationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getFormNo();
                break;
            case 2:
                return $this->getStudentNationNo();
                break;
            case 3:
                return $this->getPriorTestNo();
                break;
            case 4:
                return $this->getStudentNo();
                break;
            case 5:
                return $this->getFirstName();
                break;
            case 6:
                return $this->getLastName();
                break;
            case 7:
                return $this->getNickName();
                break;
            case 8:
                return $this->getPhoneStudent();
                break;
            case 9:
                return $this->getGender();
                break;
            case 10:
                return $this->getPlaceOfBirth();
                break;
            case 11:
                return $this->getDateOfBirth();
                break;
            case 12:
                return $this->getReligion();
                break;
            case 13:
                return $this->getLevelId();
                break;
            case 14:
                return $this->getGradeId();
                break;
            case 15:
                return $this->getEthnicityId();
                break;
            case 16:
                return $this->getChildNo();
                break;
            case 17:
                return $this->getChildTotal();
                break;
            case 18:
                return $this->getChildStatus();
                break;
            case 19:
                return $this->getPicture();
                break;
            case 20:
                return $this->getBirthCertificate();
                break;
            case 21:
                return $this->getFamilyCard();
                break;
            case 22:
                return $this->getGraduationCertificate();
                break;
            case 23:
                return $this->getAddress();
                break;
            case 24:
                return $this->getCity();
                break;
            case 25:
                return $this->getStateId();
                break;
            case 26:
                return $this->getZip();
                break;
            case 27:
                return $this->getCountryId();
                break;
            case 28:
                return $this->getSchoolId();
                break;
            case 29:
                return $this->getSchoolYearId();
                break;
            case 30:
                return $this->getStatus();
                break;
            case 31:
                return $this->getMailingAddress();
                break;
            case 32:
                return $this->getNote();
                break;
            case 33:
                return $this->getHobby();
                break;
            case 34:
                return $this->getEnteredBy();
                break;
            case 35:
                return $this->getFirstNameFather();
                break;
            case 36:
                return $this->getLastNameFather();
                break;
            case 37:
                return $this->getBusinessAddressFather();
                break;
            case 38:
                return $this->getOccupationFather();
                break;
            case 39:
                return $this->getPhoneFather();
                break;
            case 40:
                return $this->getEmailFather();
                break;
            case 41:
                return $this->getFirstNameMother();
                break;
            case 42:
                return $this->getLastNameMother();
                break;
            case 43:
                return $this->getBusinessAddressMother();
                break;
            case 44:
                return $this->getOccupationMother();
                break;
            case 45:
                return $this->getPhoneMother();
                break;
            case 46:
                return $this->getEmailMother();
                break;
            case 47:
                return $this->getFirstNameLegalGuardian();
                break;
            case 48:
                return $this->getLastNameLegalGuardian();
                break;
            case 49:
                return $this->getOccupationLegalGuardian();
                break;
            case 50:
                return $this->getPhoneLegalGuardian();
                break;
            case 51:
                return $this->getEmailLegalGuardian();
                break;
            case 52:
                return $this->getCreatedAt();
                break;
            case 53:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['Application'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Application'][$this->getPrimaryKey()] = true;
        $keys = ApplicationPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getFormNo(),
            $keys[2] => $this->getStudentNationNo(),
            $keys[3] => $this->getPriorTestNo(),
            $keys[4] => $this->getStudentNo(),
            $keys[5] => $this->getFirstName(),
            $keys[6] => $this->getLastName(),
            $keys[7] => $this->getNickName(),
            $keys[8] => $this->getPhoneStudent(),
            $keys[9] => $this->getGender(),
            $keys[10] => $this->getPlaceOfBirth(),
            $keys[11] => $this->getDateOfBirth(),
            $keys[12] => $this->getReligion(),
            $keys[13] => $this->getLevelId(),
            $keys[14] => $this->getGradeId(),
            $keys[15] => $this->getEthnicityId(),
            $keys[16] => $this->getChildNo(),
            $keys[17] => $this->getChildTotal(),
            $keys[18] => $this->getChildStatus(),
            $keys[19] => $this->getPicture(),
            $keys[20] => $this->getBirthCertificate(),
            $keys[21] => $this->getFamilyCard(),
            $keys[22] => $this->getGraduationCertificate(),
            $keys[23] => $this->getAddress(),
            $keys[24] => $this->getCity(),
            $keys[25] => $this->getStateId(),
            $keys[26] => $this->getZip(),
            $keys[27] => $this->getCountryId(),
            $keys[28] => $this->getSchoolId(),
            $keys[29] => $this->getSchoolYearId(),
            $keys[30] => $this->getStatus(),
            $keys[31] => $this->getMailingAddress(),
            $keys[32] => $this->getNote(),
            $keys[33] => $this->getHobby(),
            $keys[34] => $this->getEnteredBy(),
            $keys[35] => $this->getFirstNameFather(),
            $keys[36] => $this->getLastNameFather(),
            $keys[37] => $this->getBusinessAddressFather(),
            $keys[38] => $this->getOccupationFather(),
            $keys[39] => $this->getPhoneFather(),
            $keys[40] => $this->getEmailFather(),
            $keys[41] => $this->getFirstNameMother(),
            $keys[42] => $this->getLastNameMother(),
            $keys[43] => $this->getBusinessAddressMother(),
            $keys[44] => $this->getOccupationMother(),
            $keys[45] => $this->getPhoneMother(),
            $keys[46] => $this->getEmailMother(),
            $keys[47] => $this->getFirstNameLegalGuardian(),
            $keys[48] => $this->getLastNameLegalGuardian(),
            $keys[49] => $this->getOccupationLegalGuardian(),
            $keys[50] => $this->getPhoneLegalGuardian(),
            $keys[51] => $this->getEmailLegalGuardian(),
            $keys[52] => $this->getCreatedAt(),
            $keys[53] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aUser) {
                $result['User'] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aSchool) {
                $result['School'] = $this->aSchool->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aSchoolYear) {
                $result['SchoolYear'] = $this->aSchoolYear->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aEthnicity) {
                $result['Ethnicity'] = $this->aEthnicity->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aGrade) {
                $result['Grade'] = $this->aGrade->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aLevel) {
                $result['Level'] = $this->aLevel->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aState) {
                $result['State'] = $this->aState->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCountry) {
                $result['Country'] = $this->aCountry->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collParentStudents) {
                $result['ParentStudents'] = $this->collParentStudents->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSchoolHealths) {
                $result['SchoolHealths'] = $this->collSchoolHealths->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStudents) {
                $result['Students'] = $this->collStudents->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = ApplicationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setFormNo($value);
                break;
            case 2:
                $this->setStudentNationNo($value);
                break;
            case 3:
                $this->setPriorTestNo($value);
                break;
            case 4:
                $this->setStudentNo($value);
                break;
            case 5:
                $this->setFirstName($value);
                break;
            case 6:
                $this->setLastName($value);
                break;
            case 7:
                $this->setNickName($value);
                break;
            case 8:
                $this->setPhoneStudent($value);
                break;
            case 9:
                $valueSet = ApplicationPeer::getValueSet(ApplicationPeer::GENDER);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setGender($value);
                break;
            case 10:
                $this->setPlaceOfBirth($value);
                break;
            case 11:
                $this->setDateOfBirth($value);
                break;
            case 12:
                $valueSet = ApplicationPeer::getValueSet(ApplicationPeer::RELIGION);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setReligion($value);
                break;
            case 13:
                $this->setLevelId($value);
                break;
            case 14:
                $this->setGradeId($value);
                break;
            case 15:
                $this->setEthnicityId($value);
                break;
            case 16:
                $this->setChildNo($value);
                break;
            case 17:
                $this->setChildTotal($value);
                break;
            case 18:
                $valueSet = ApplicationPeer::getValueSet(ApplicationPeer::CHILD_STATUS);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setChildStatus($value);
                break;
            case 19:
                $this->setPicture($value);
                break;
            case 20:
                $this->setBirthCertificate($value);
                break;
            case 21:
                $this->setFamilyCard($value);
                break;
            case 22:
                $this->setGraduationCertificate($value);
                break;
            case 23:
                $this->setAddress($value);
                break;
            case 24:
                $this->setCity($value);
                break;
            case 25:
                $this->setStateId($value);
                break;
            case 26:
                $this->setZip($value);
                break;
            case 27:
                $this->setCountryId($value);
                break;
            case 28:
                $this->setSchoolId($value);
                break;
            case 29:
                $this->setSchoolYearId($value);
                break;
            case 30:
                $valueSet = ApplicationPeer::getValueSet(ApplicationPeer::STATUS);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setStatus($value);
                break;
            case 31:
                $this->setMailingAddress($value);
                break;
            case 32:
                $this->setNote($value);
                break;
            case 33:
                $this->setHobby($value);
                break;
            case 34:
                $this->setEnteredBy($value);
                break;
            case 35:
                $this->setFirstNameFather($value);
                break;
            case 36:
                $this->setLastNameFather($value);
                break;
            case 37:
                $this->setBusinessAddressFather($value);
                break;
            case 38:
                $this->setOccupationFather($value);
                break;
            case 39:
                $this->setPhoneFather($value);
                break;
            case 40:
                $this->setEmailFather($value);
                break;
            case 41:
                $this->setFirstNameMother($value);
                break;
            case 42:
                $this->setLastNameMother($value);
                break;
            case 43:
                $this->setBusinessAddressMother($value);
                break;
            case 44:
                $this->setOccupationMother($value);
                break;
            case 45:
                $this->setPhoneMother($value);
                break;
            case 46:
                $this->setEmailMother($value);
                break;
            case 47:
                $this->setFirstNameLegalGuardian($value);
                break;
            case 48:
                $this->setLastNameLegalGuardian($value);
                break;
            case 49:
                $this->setOccupationLegalGuardian($value);
                break;
            case 50:
                $this->setPhoneLegalGuardian($value);
                break;
            case 51:
                $this->setEmailLegalGuardian($value);
                break;
            case 52:
                $this->setCreatedAt($value);
                break;
            case 53:
                $this->setUpdatedAt($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = ApplicationPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setFormNo($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setStudentNationNo($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setPriorTestNo($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setStudentNo($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setFirstName($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setLastName($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setNickName($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setPhoneStudent($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setGender($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setPlaceOfBirth($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setDateOfBirth($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setReligion($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setLevelId($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setGradeId($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setEthnicityId($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setChildNo($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setChildTotal($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setChildStatus($arr[$keys[18]]);
        if (array_key_exists($keys[19], $arr)) $this->setPicture($arr[$keys[19]]);
        if (array_key_exists($keys[20], $arr)) $this->setBirthCertificate($arr[$keys[20]]);
        if (array_key_exists($keys[21], $arr)) $this->setFamilyCard($arr[$keys[21]]);
        if (array_key_exists($keys[22], $arr)) $this->setGraduationCertificate($arr[$keys[22]]);
        if (array_key_exists($keys[23], $arr)) $this->setAddress($arr[$keys[23]]);
        if (array_key_exists($keys[24], $arr)) $this->setCity($arr[$keys[24]]);
        if (array_key_exists($keys[25], $arr)) $this->setStateId($arr[$keys[25]]);
        if (array_key_exists($keys[26], $arr)) $this->setZip($arr[$keys[26]]);
        if (array_key_exists($keys[27], $arr)) $this->setCountryId($arr[$keys[27]]);
        if (array_key_exists($keys[28], $arr)) $this->setSchoolId($arr[$keys[28]]);
        if (array_key_exists($keys[29], $arr)) $this->setSchoolYearId($arr[$keys[29]]);
        if (array_key_exists($keys[30], $arr)) $this->setStatus($arr[$keys[30]]);
        if (array_key_exists($keys[31], $arr)) $this->setMailingAddress($arr[$keys[31]]);
        if (array_key_exists($keys[32], $arr)) $this->setNote($arr[$keys[32]]);
        if (array_key_exists($keys[33], $arr)) $this->setHobby($arr[$keys[33]]);
        if (array_key_exists($keys[34], $arr)) $this->setEnteredBy($arr[$keys[34]]);
        if (array_key_exists($keys[35], $arr)) $this->setFirstNameFather($arr[$keys[35]]);
        if (array_key_exists($keys[36], $arr)) $this->setLastNameFather($arr[$keys[36]]);
        if (array_key_exists($keys[37], $arr)) $this->setBusinessAddressFather($arr[$keys[37]]);
        if (array_key_exists($keys[38], $arr)) $this->setOccupationFather($arr[$keys[38]]);
        if (array_key_exists($keys[39], $arr)) $this->setPhoneFather($arr[$keys[39]]);
        if (array_key_exists($keys[40], $arr)) $this->setEmailFather($arr[$keys[40]]);
        if (array_key_exists($keys[41], $arr)) $this->setFirstNameMother($arr[$keys[41]]);
        if (array_key_exists($keys[42], $arr)) $this->setLastNameMother($arr[$keys[42]]);
        if (array_key_exists($keys[43], $arr)) $this->setBusinessAddressMother($arr[$keys[43]]);
        if (array_key_exists($keys[44], $arr)) $this->setOccupationMother($arr[$keys[44]]);
        if (array_key_exists($keys[45], $arr)) $this->setPhoneMother($arr[$keys[45]]);
        if (array_key_exists($keys[46], $arr)) $this->setEmailMother($arr[$keys[46]]);
        if (array_key_exists($keys[47], $arr)) $this->setFirstNameLegalGuardian($arr[$keys[47]]);
        if (array_key_exists($keys[48], $arr)) $this->setLastNameLegalGuardian($arr[$keys[48]]);
        if (array_key_exists($keys[49], $arr)) $this->setOccupationLegalGuardian($arr[$keys[49]]);
        if (array_key_exists($keys[50], $arr)) $this->setPhoneLegalGuardian($arr[$keys[50]]);
        if (array_key_exists($keys[51], $arr)) $this->setEmailLegalGuardian($arr[$keys[51]]);
        if (array_key_exists($keys[52], $arr)) $this->setCreatedAt($arr[$keys[52]]);
        if (array_key_exists($keys[53], $arr)) $this->setUpdatedAt($arr[$keys[53]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ApplicationPeer::DATABASE_NAME);

        if ($this->isColumnModified(ApplicationPeer::ID)) $criteria->add(ApplicationPeer::ID, $this->id);
        if ($this->isColumnModified(ApplicationPeer::FORM_NO)) $criteria->add(ApplicationPeer::FORM_NO, $this->form_no);
        if ($this->isColumnModified(ApplicationPeer::STUDENT_NATION_NO)) $criteria->add(ApplicationPeer::STUDENT_NATION_NO, $this->student_nation_no);
        if ($this->isColumnModified(ApplicationPeer::PRIOR_TEST_NO)) $criteria->add(ApplicationPeer::PRIOR_TEST_NO, $this->prior_test_no);
        if ($this->isColumnModified(ApplicationPeer::STUDENT_NO)) $criteria->add(ApplicationPeer::STUDENT_NO, $this->student_no);
        if ($this->isColumnModified(ApplicationPeer::FIRST_NAME)) $criteria->add(ApplicationPeer::FIRST_NAME, $this->first_name);
        if ($this->isColumnModified(ApplicationPeer::LAST_NAME)) $criteria->add(ApplicationPeer::LAST_NAME, $this->last_name);
        if ($this->isColumnModified(ApplicationPeer::NICK_NAME)) $criteria->add(ApplicationPeer::NICK_NAME, $this->nick_name);
        if ($this->isColumnModified(ApplicationPeer::PHONE_STUDENT)) $criteria->add(ApplicationPeer::PHONE_STUDENT, $this->phone_student);
        if ($this->isColumnModified(ApplicationPeer::GENDER)) $criteria->add(ApplicationPeer::GENDER, $this->gender);
        if ($this->isColumnModified(ApplicationPeer::PLACE_OF_BIRTH)) $criteria->add(ApplicationPeer::PLACE_OF_BIRTH, $this->place_of_birth);
        if ($this->isColumnModified(ApplicationPeer::DATE_OF_BIRTH)) $criteria->add(ApplicationPeer::DATE_OF_BIRTH, $this->date_of_birth);
        if ($this->isColumnModified(ApplicationPeer::RELIGION)) $criteria->add(ApplicationPeer::RELIGION, $this->religion);
        if ($this->isColumnModified(ApplicationPeer::LEVEL_ID)) $criteria->add(ApplicationPeer::LEVEL_ID, $this->level_id);
        if ($this->isColumnModified(ApplicationPeer::GRADE_ID)) $criteria->add(ApplicationPeer::GRADE_ID, $this->grade_id);
        if ($this->isColumnModified(ApplicationPeer::ETHNICITY_ID)) $criteria->add(ApplicationPeer::ETHNICITY_ID, $this->ethnicity_id);
        if ($this->isColumnModified(ApplicationPeer::CHILD_NO)) $criteria->add(ApplicationPeer::CHILD_NO, $this->child_no);
        if ($this->isColumnModified(ApplicationPeer::CHILD_TOTAL)) $criteria->add(ApplicationPeer::CHILD_TOTAL, $this->child_total);
        if ($this->isColumnModified(ApplicationPeer::CHILD_STATUS)) $criteria->add(ApplicationPeer::CHILD_STATUS, $this->child_status);
        if ($this->isColumnModified(ApplicationPeer::PICTURE)) $criteria->add(ApplicationPeer::PICTURE, $this->picture);
        if ($this->isColumnModified(ApplicationPeer::BIRTH_CERTIFICATE)) $criteria->add(ApplicationPeer::BIRTH_CERTIFICATE, $this->birth_certificate);
        if ($this->isColumnModified(ApplicationPeer::FAMILY_CARD)) $criteria->add(ApplicationPeer::FAMILY_CARD, $this->family_card);
        if ($this->isColumnModified(ApplicationPeer::GRADUATION_CERTIFICATE)) $criteria->add(ApplicationPeer::GRADUATION_CERTIFICATE, $this->graduation_certificate);
        if ($this->isColumnModified(ApplicationPeer::ADDRESS)) $criteria->add(ApplicationPeer::ADDRESS, $this->address);
        if ($this->isColumnModified(ApplicationPeer::CITY)) $criteria->add(ApplicationPeer::CITY, $this->city);
        if ($this->isColumnModified(ApplicationPeer::STATE_ID)) $criteria->add(ApplicationPeer::STATE_ID, $this->state_id);
        if ($this->isColumnModified(ApplicationPeer::ZIP)) $criteria->add(ApplicationPeer::ZIP, $this->zip);
        if ($this->isColumnModified(ApplicationPeer::COUNTRY_ID)) $criteria->add(ApplicationPeer::COUNTRY_ID, $this->country_id);
        if ($this->isColumnModified(ApplicationPeer::SCHOOL_ID)) $criteria->add(ApplicationPeer::SCHOOL_ID, $this->school_id);
        if ($this->isColumnModified(ApplicationPeer::SCHOOL_YEAR_ID)) $criteria->add(ApplicationPeer::SCHOOL_YEAR_ID, $this->school_year_id);
        if ($this->isColumnModified(ApplicationPeer::STATUS)) $criteria->add(ApplicationPeer::STATUS, $this->status);
        if ($this->isColumnModified(ApplicationPeer::MAILING_ADDRESS)) $criteria->add(ApplicationPeer::MAILING_ADDRESS, $this->mailing_address);
        if ($this->isColumnModified(ApplicationPeer::NOTE)) $criteria->add(ApplicationPeer::NOTE, $this->note);
        if ($this->isColumnModified(ApplicationPeer::HOBBY)) $criteria->add(ApplicationPeer::HOBBY, $this->hobby);
        if ($this->isColumnModified(ApplicationPeer::ENTERED_BY)) $criteria->add(ApplicationPeer::ENTERED_BY, $this->entered_by);
        if ($this->isColumnModified(ApplicationPeer::FIRST_NAME_FATHER)) $criteria->add(ApplicationPeer::FIRST_NAME_FATHER, $this->first_name_father);
        if ($this->isColumnModified(ApplicationPeer::LAST_NAME_FATHER)) $criteria->add(ApplicationPeer::LAST_NAME_FATHER, $this->last_name_father);
        if ($this->isColumnModified(ApplicationPeer::BUSINESS_ADDRESS_FATHER)) $criteria->add(ApplicationPeer::BUSINESS_ADDRESS_FATHER, $this->business_address_father);
        if ($this->isColumnModified(ApplicationPeer::OCCUPATION_FATHER)) $criteria->add(ApplicationPeer::OCCUPATION_FATHER, $this->occupation_father);
        if ($this->isColumnModified(ApplicationPeer::PHONE_FATHER)) $criteria->add(ApplicationPeer::PHONE_FATHER, $this->phone_father);
        if ($this->isColumnModified(ApplicationPeer::EMAIL_FATHER)) $criteria->add(ApplicationPeer::EMAIL_FATHER, $this->email_father);
        if ($this->isColumnModified(ApplicationPeer::FIRST_NAME_MOTHER)) $criteria->add(ApplicationPeer::FIRST_NAME_MOTHER, $this->first_name_mother);
        if ($this->isColumnModified(ApplicationPeer::LAST_NAME_MOTHER)) $criteria->add(ApplicationPeer::LAST_NAME_MOTHER, $this->last_name_mother);
        if ($this->isColumnModified(ApplicationPeer::BUSINESS_ADDRESS_MOTHER)) $criteria->add(ApplicationPeer::BUSINESS_ADDRESS_MOTHER, $this->business_address_mother);
        if ($this->isColumnModified(ApplicationPeer::OCCUPATION_MOTHER)) $criteria->add(ApplicationPeer::OCCUPATION_MOTHER, $this->occupation_mother);
        if ($this->isColumnModified(ApplicationPeer::PHONE_MOTHER)) $criteria->add(ApplicationPeer::PHONE_MOTHER, $this->phone_mother);
        if ($this->isColumnModified(ApplicationPeer::EMAIL_MOTHER)) $criteria->add(ApplicationPeer::EMAIL_MOTHER, $this->email_mother);
        if ($this->isColumnModified(ApplicationPeer::FIRST_NAME_LEGAL_GUARDIAN)) $criteria->add(ApplicationPeer::FIRST_NAME_LEGAL_GUARDIAN, $this->first_name_legal_guardian);
        if ($this->isColumnModified(ApplicationPeer::LAST_NAME_LEGAL_GUARDIAN)) $criteria->add(ApplicationPeer::LAST_NAME_LEGAL_GUARDIAN, $this->last_name_legal_guardian);
        if ($this->isColumnModified(ApplicationPeer::OCCUPATION_LEGAL_GUARDIAN)) $criteria->add(ApplicationPeer::OCCUPATION_LEGAL_GUARDIAN, $this->occupation_legal_guardian);
        if ($this->isColumnModified(ApplicationPeer::PHONE_LEGAL_GUARDIAN)) $criteria->add(ApplicationPeer::PHONE_LEGAL_GUARDIAN, $this->phone_legal_guardian);
        if ($this->isColumnModified(ApplicationPeer::EMAIL_LEGAL_GUARDIAN)) $criteria->add(ApplicationPeer::EMAIL_LEGAL_GUARDIAN, $this->email_legal_guardian);
        if ($this->isColumnModified(ApplicationPeer::CREATED_AT)) $criteria->add(ApplicationPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(ApplicationPeer::UPDATED_AT)) $criteria->add(ApplicationPeer::UPDATED_AT, $this->updated_at);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(ApplicationPeer::DATABASE_NAME);
        $criteria->add(ApplicationPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of Application (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setFormNo($this->getFormNo());
        $copyObj->setStudentNationNo($this->getStudentNationNo());
        $copyObj->setPriorTestNo($this->getPriorTestNo());
        $copyObj->setStudentNo($this->getStudentNo());
        $copyObj->setFirstName($this->getFirstName());
        $copyObj->setLastName($this->getLastName());
        $copyObj->setNickName($this->getNickName());
        $copyObj->setPhoneStudent($this->getPhoneStudent());
        $copyObj->setGender($this->getGender());
        $copyObj->setPlaceOfBirth($this->getPlaceOfBirth());
        $copyObj->setDateOfBirth($this->getDateOfBirth());
        $copyObj->setReligion($this->getReligion());
        $copyObj->setLevelId($this->getLevelId());
        $copyObj->setGradeId($this->getGradeId());
        $copyObj->setEthnicityId($this->getEthnicityId());
        $copyObj->setChildNo($this->getChildNo());
        $copyObj->setChildTotal($this->getChildTotal());
        $copyObj->setChildStatus($this->getChildStatus());
        $copyObj->setPicture($this->getPicture());
        $copyObj->setBirthCertificate($this->getBirthCertificate());
        $copyObj->setFamilyCard($this->getFamilyCard());
        $copyObj->setGraduationCertificate($this->getGraduationCertificate());
        $copyObj->setAddress($this->getAddress());
        $copyObj->setCity($this->getCity());
        $copyObj->setStateId($this->getStateId());
        $copyObj->setZip($this->getZip());
        $copyObj->setCountryId($this->getCountryId());
        $copyObj->setSchoolId($this->getSchoolId());
        $copyObj->setSchoolYearId($this->getSchoolYearId());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setMailingAddress($this->getMailingAddress());
        $copyObj->setNote($this->getNote());
        $copyObj->setHobby($this->getHobby());
        $copyObj->setEnteredBy($this->getEnteredBy());
        $copyObj->setFirstNameFather($this->getFirstNameFather());
        $copyObj->setLastNameFather($this->getLastNameFather());
        $copyObj->setBusinessAddressFather($this->getBusinessAddressFather());
        $copyObj->setOccupationFather($this->getOccupationFather());
        $copyObj->setPhoneFather($this->getPhoneFather());
        $copyObj->setEmailFather($this->getEmailFather());
        $copyObj->setFirstNameMother($this->getFirstNameMother());
        $copyObj->setLastNameMother($this->getLastNameMother());
        $copyObj->setBusinessAddressMother($this->getBusinessAddressMother());
        $copyObj->setOccupationMother($this->getOccupationMother());
        $copyObj->setPhoneMother($this->getPhoneMother());
        $copyObj->setEmailMother($this->getEmailMother());
        $copyObj->setFirstNameLegalGuardian($this->getFirstNameLegalGuardian());
        $copyObj->setLastNameLegalGuardian($this->getLastNameLegalGuardian());
        $copyObj->setOccupationLegalGuardian($this->getOccupationLegalGuardian());
        $copyObj->setPhoneLegalGuardian($this->getPhoneLegalGuardian());
        $copyObj->setEmailLegalGuardian($this->getEmailLegalGuardian());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getParentStudents() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addParentStudent($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSchoolHealths() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSchoolHealth($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStudents() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStudent($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return Application Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return ApplicationPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new ApplicationPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param                  User $v
     * @return Application The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(User $v = null)
    {
        if ($v === null) {
            $this->setEnteredBy(NULL);
        } else {
            $this->setEnteredBy($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addApplication($this);
        }


        return $this;
    }


    /**
     * Get the associated User object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return User The associated User object.
     * @throws PropelException
     */
    public function getUser(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aUser === null && ($this->entered_by !== null) && $doQuery) {
            $this->aUser = UserQuery::create()->findPk($this->entered_by, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addApplications($this);
             */
        }

        return $this->aUser;
    }

    /**
     * Declares an association between this object and a School object.
     *
     * @param                  School $v
     * @return Application The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSchool(School $v = null)
    {
        if ($v === null) {
            $this->setSchoolId(NULL);
        } else {
            $this->setSchoolId($v->getId());
        }

        $this->aSchool = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the School object, it will not be re-added.
        if ($v !== null) {
            $v->addApplication($this);
        }


        return $this;
    }


    /**
     * Get the associated School object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return School The associated School object.
     * @throws PropelException
     */
    public function getSchool(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aSchool === null && ($this->school_id !== null) && $doQuery) {
            $this->aSchool = SchoolQuery::create()->findPk($this->school_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSchool->addApplications($this);
             */
        }

        return $this->aSchool;
    }

    /**
     * Declares an association between this object and a SchoolYear object.
     *
     * @param                  SchoolYear $v
     * @return Application The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSchoolYear(SchoolYear $v = null)
    {
        if ($v === null) {
            $this->setSchoolYearId(NULL);
        } else {
            $this->setSchoolYearId($v->getId());
        }

        $this->aSchoolYear = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the SchoolYear object, it will not be re-added.
        if ($v !== null) {
            $v->addApplication($this);
        }


        return $this;
    }


    /**
     * Get the associated SchoolYear object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return SchoolYear The associated SchoolYear object.
     * @throws PropelException
     */
    public function getSchoolYear(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aSchoolYear === null && ($this->school_year_id !== null) && $doQuery) {
            $this->aSchoolYear = SchoolYearQuery::create()->findPk($this->school_year_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSchoolYear->addApplications($this);
             */
        }

        return $this->aSchoolYear;
    }

    /**
     * Declares an association between this object and a Ethnicity object.
     *
     * @param                  Ethnicity $v
     * @return Application The current object (for fluent API support)
     * @throws PropelException
     */
    public function setEthnicity(Ethnicity $v = null)
    {
        if ($v === null) {
            $this->setEthnicityId(NULL);
        } else {
            $this->setEthnicityId($v->getId());
        }

        $this->aEthnicity = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Ethnicity object, it will not be re-added.
        if ($v !== null) {
            $v->addApplication($this);
        }


        return $this;
    }


    /**
     * Get the associated Ethnicity object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Ethnicity The associated Ethnicity object.
     * @throws PropelException
     */
    public function getEthnicity(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aEthnicity === null && ($this->ethnicity_id !== null) && $doQuery) {
            $this->aEthnicity = EthnicityQuery::create()->findPk($this->ethnicity_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEthnicity->addApplications($this);
             */
        }

        return $this->aEthnicity;
    }

    /**
     * Declares an association between this object and a Grade object.
     *
     * @param                  Grade $v
     * @return Application The current object (for fluent API support)
     * @throws PropelException
     */
    public function setGrade(Grade $v = null)
    {
        if ($v === null) {
            $this->setGradeId(NULL);
        } else {
            $this->setGradeId($v->getId());
        }

        $this->aGrade = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Grade object, it will not be re-added.
        if ($v !== null) {
            $v->addApplication($this);
        }


        return $this;
    }


    /**
     * Get the associated Grade object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Grade The associated Grade object.
     * @throws PropelException
     */
    public function getGrade(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aGrade === null && ($this->grade_id !== null) && $doQuery) {
            $this->aGrade = GradeQuery::create()->findPk($this->grade_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aGrade->addApplications($this);
             */
        }

        return $this->aGrade;
    }

    /**
     * Declares an association between this object and a Level object.
     *
     * @param                  Level $v
     * @return Application The current object (for fluent API support)
     * @throws PropelException
     */
    public function setLevel(Level $v = null)
    {
        if ($v === null) {
            $this->setLevelId(NULL);
        } else {
            $this->setLevelId($v->getId());
        }

        $this->aLevel = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Level object, it will not be re-added.
        if ($v !== null) {
            $v->addApplication($this);
        }


        return $this;
    }


    /**
     * Get the associated Level object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Level The associated Level object.
     * @throws PropelException
     */
    public function getLevel(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aLevel === null && ($this->level_id !== null) && $doQuery) {
            $this->aLevel = LevelQuery::create()->findPk($this->level_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aLevel->addApplications($this);
             */
        }

        return $this->aLevel;
    }

    /**
     * Declares an association between this object and a State object.
     *
     * @param                  State $v
     * @return Application The current object (for fluent API support)
     * @throws PropelException
     */
    public function setState(State $v = null)
    {
        if ($v === null) {
            $this->setStateId(NULL);
        } else {
            $this->setStateId($v->getId());
        }

        $this->aState = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the State object, it will not be re-added.
        if ($v !== null) {
            $v->addApplication($this);
        }


        return $this;
    }


    /**
     * Get the associated State object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return State The associated State object.
     * @throws PropelException
     */
    public function getState(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aState === null && ($this->state_id !== null) && $doQuery) {
            $this->aState = StateQuery::create()->findPk($this->state_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aState->addApplications($this);
             */
        }

        return $this->aState;
    }

    /**
     * Declares an association between this object and a Country object.
     *
     * @param                  Country $v
     * @return Application The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCountry(Country $v = null)
    {
        if ($v === null) {
            $this->setCountryId(105);
        } else {
            $this->setCountryId($v->getId());
        }

        $this->aCountry = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Country object, it will not be re-added.
        if ($v !== null) {
            $v->addApplication($this);
        }


        return $this;
    }


    /**
     * Get the associated Country object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Country The associated Country object.
     * @throws PropelException
     */
    public function getCountry(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aCountry === null && ($this->country_id !== null) && $doQuery) {
            $this->aCountry = CountryQuery::create()->findPk($this->country_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCountry->addApplications($this);
             */
        }

        return $this->aCountry;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('ParentStudent' == $relationName) {
            $this->initParentStudents();
        }
        if ('SchoolHealth' == $relationName) {
            $this->initSchoolHealths();
        }
        if ('Student' == $relationName) {
            $this->initStudents();
        }
    }

    /**
     * Clears out the collParentStudents collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Application The current object (for fluent API support)
     * @see        addParentStudents()
     */
    public function clearParentStudents()
    {
        $this->collParentStudents = null; // important to set this to null since that means it is uninitialized
        $this->collParentStudentsPartial = null;

        return $this;
    }

    /**
     * reset is the collParentStudents collection loaded partially
     *
     * @return void
     */
    public function resetPartialParentStudents($v = true)
    {
        $this->collParentStudentsPartial = $v;
    }

    /**
     * Initializes the collParentStudents collection.
     *
     * By default this just sets the collParentStudents collection to an empty array (like clearcollParentStudents());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initParentStudents($overrideExisting = true)
    {
        if (null !== $this->collParentStudents && !$overrideExisting) {
            return;
        }
        $this->collParentStudents = new PropelObjectCollection();
        $this->collParentStudents->setModel('ParentStudent');
    }

    /**
     * Gets an array of ParentStudent objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Application is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|ParentStudent[] List of ParentStudent objects
     * @throws PropelException
     */
    public function getParentStudents($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collParentStudentsPartial && !$this->isNew();
        if (null === $this->collParentStudents || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collParentStudents) {
                // return empty collection
                $this->initParentStudents();
            } else {
                $collParentStudents = ParentStudentQuery::create(null, $criteria)
                    ->filterByApplication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collParentStudentsPartial && count($collParentStudents)) {
                      $this->initParentStudents(false);

                      foreach ($collParentStudents as $obj) {
                        if (false == $this->collParentStudents->contains($obj)) {
                          $this->collParentStudents->append($obj);
                        }
                      }

                      $this->collParentStudentsPartial = true;
                    }

                    $collParentStudents->getInternalIterator()->rewind();

                    return $collParentStudents;
                }

                if ($partial && $this->collParentStudents) {
                    foreach ($this->collParentStudents as $obj) {
                        if ($obj->isNew()) {
                            $collParentStudents[] = $obj;
                        }
                    }
                }

                $this->collParentStudents = $collParentStudents;
                $this->collParentStudentsPartial = false;
            }
        }

        return $this->collParentStudents;
    }

    /**
     * Sets a collection of ParentStudent objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $parentStudents A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Application The current object (for fluent API support)
     */
    public function setParentStudents(PropelCollection $parentStudents, PropelPDO $con = null)
    {
        $parentStudentsToDelete = $this->getParentStudents(new Criteria(), $con)->diff($parentStudents);


        $this->parentStudentsScheduledForDeletion = $parentStudentsToDelete;

        foreach ($parentStudentsToDelete as $parentStudentRemoved) {
            $parentStudentRemoved->setApplication(null);
        }

        $this->collParentStudents = null;
        foreach ($parentStudents as $parentStudent) {
            $this->addParentStudent($parentStudent);
        }

        $this->collParentStudents = $parentStudents;
        $this->collParentStudentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ParentStudent objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related ParentStudent objects.
     * @throws PropelException
     */
    public function countParentStudents(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collParentStudentsPartial && !$this->isNew();
        if (null === $this->collParentStudents || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collParentStudents) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getParentStudents());
            }
            $query = ParentStudentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByApplication($this)
                ->count($con);
        }

        return count($this->collParentStudents);
    }

    /**
     * Method called to associate a ParentStudent object to this object
     * through the ParentStudent foreign key attribute.
     *
     * @param    ParentStudent $l ParentStudent
     * @return Application The current object (for fluent API support)
     */
    public function addParentStudent(ParentStudent $l)
    {
        if ($this->collParentStudents === null) {
            $this->initParentStudents();
            $this->collParentStudentsPartial = true;
        }

        if (!in_array($l, $this->collParentStudents->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddParentStudent($l);

            if ($this->parentStudentsScheduledForDeletion and $this->parentStudentsScheduledForDeletion->contains($l)) {
                $this->parentStudentsScheduledForDeletion->remove($this->parentStudentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	ParentStudent $parentStudent The parentStudent object to add.
     */
    protected function doAddParentStudent($parentStudent)
    {
        $this->collParentStudents[]= $parentStudent;
        $parentStudent->setApplication($this);
    }

    /**
     * @param	ParentStudent $parentStudent The parentStudent object to remove.
     * @return Application The current object (for fluent API support)
     */
    public function removeParentStudent($parentStudent)
    {
        if ($this->getParentStudents()->contains($parentStudent)) {
            $this->collParentStudents->remove($this->collParentStudents->search($parentStudent));
            if (null === $this->parentStudentsScheduledForDeletion) {
                $this->parentStudentsScheduledForDeletion = clone $this->collParentStudents;
                $this->parentStudentsScheduledForDeletion->clear();
            }
            $this->parentStudentsScheduledForDeletion[]= clone $parentStudent;
            $parentStudent->setApplication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Application is new, it will return
     * an empty collection; or if this Application has previously
     * been saved, it will retrieve related ParentStudents from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Application.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|ParentStudent[] List of ParentStudent objects
     */
    public function getParentStudentsJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ParentStudentQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getParentStudents($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Application is new, it will return
     * an empty collection; or if this Application has previously
     * been saved, it will retrieve related ParentStudents from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Application.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|ParentStudent[] List of ParentStudent objects
     */
    public function getParentStudentsJoinStudent($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ParentStudentQuery::create(null, $criteria);
        $query->joinWith('Student', $join_behavior);

        return $this->getParentStudents($query, $con);
    }

    /**
     * Clears out the collSchoolHealths collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Application The current object (for fluent API support)
     * @see        addSchoolHealths()
     */
    public function clearSchoolHealths()
    {
        $this->collSchoolHealths = null; // important to set this to null since that means it is uninitialized
        $this->collSchoolHealthsPartial = null;

        return $this;
    }

    /**
     * reset is the collSchoolHealths collection loaded partially
     *
     * @return void
     */
    public function resetPartialSchoolHealths($v = true)
    {
        $this->collSchoolHealthsPartial = $v;
    }

    /**
     * Initializes the collSchoolHealths collection.
     *
     * By default this just sets the collSchoolHealths collection to an empty array (like clearcollSchoolHealths());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSchoolHealths($overrideExisting = true)
    {
        if (null !== $this->collSchoolHealths && !$overrideExisting) {
            return;
        }
        $this->collSchoolHealths = new PropelObjectCollection();
        $this->collSchoolHealths->setModel('SchoolHealth');
    }

    /**
     * Gets an array of SchoolHealth objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Application is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|SchoolHealth[] List of SchoolHealth objects
     * @throws PropelException
     */
    public function getSchoolHealths($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSchoolHealthsPartial && !$this->isNew();
        if (null === $this->collSchoolHealths || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSchoolHealths) {
                // return empty collection
                $this->initSchoolHealths();
            } else {
                $collSchoolHealths = SchoolHealthQuery::create(null, $criteria)
                    ->filterByApplication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSchoolHealthsPartial && count($collSchoolHealths)) {
                      $this->initSchoolHealths(false);

                      foreach ($collSchoolHealths as $obj) {
                        if (false == $this->collSchoolHealths->contains($obj)) {
                          $this->collSchoolHealths->append($obj);
                        }
                      }

                      $this->collSchoolHealthsPartial = true;
                    }

                    $collSchoolHealths->getInternalIterator()->rewind();

                    return $collSchoolHealths;
                }

                if ($partial && $this->collSchoolHealths) {
                    foreach ($this->collSchoolHealths as $obj) {
                        if ($obj->isNew()) {
                            $collSchoolHealths[] = $obj;
                        }
                    }
                }

                $this->collSchoolHealths = $collSchoolHealths;
                $this->collSchoolHealthsPartial = false;
            }
        }

        return $this->collSchoolHealths;
    }

    /**
     * Sets a collection of SchoolHealth objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $schoolHealths A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Application The current object (for fluent API support)
     */
    public function setSchoolHealths(PropelCollection $schoolHealths, PropelPDO $con = null)
    {
        $schoolHealthsToDelete = $this->getSchoolHealths(new Criteria(), $con)->diff($schoolHealths);


        $this->schoolHealthsScheduledForDeletion = $schoolHealthsToDelete;

        foreach ($schoolHealthsToDelete as $schoolHealthRemoved) {
            $schoolHealthRemoved->setApplication(null);
        }

        $this->collSchoolHealths = null;
        foreach ($schoolHealths as $schoolHealth) {
            $this->addSchoolHealth($schoolHealth);
        }

        $this->collSchoolHealths = $schoolHealths;
        $this->collSchoolHealthsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SchoolHealth objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related SchoolHealth objects.
     * @throws PropelException
     */
    public function countSchoolHealths(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSchoolHealthsPartial && !$this->isNew();
        if (null === $this->collSchoolHealths || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSchoolHealths) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSchoolHealths());
            }
            $query = SchoolHealthQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByApplication($this)
                ->count($con);
        }

        return count($this->collSchoolHealths);
    }

    /**
     * Method called to associate a SchoolHealth object to this object
     * through the SchoolHealth foreign key attribute.
     *
     * @param    SchoolHealth $l SchoolHealth
     * @return Application The current object (for fluent API support)
     */
    public function addSchoolHealth(SchoolHealth $l)
    {
        if ($this->collSchoolHealths === null) {
            $this->initSchoolHealths();
            $this->collSchoolHealthsPartial = true;
        }

        if (!in_array($l, $this->collSchoolHealths->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSchoolHealth($l);

            if ($this->schoolHealthsScheduledForDeletion and $this->schoolHealthsScheduledForDeletion->contains($l)) {
                $this->schoolHealthsScheduledForDeletion->remove($this->schoolHealthsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	SchoolHealth $schoolHealth The schoolHealth object to add.
     */
    protected function doAddSchoolHealth($schoolHealth)
    {
        $this->collSchoolHealths[]= $schoolHealth;
        $schoolHealth->setApplication($this);
    }

    /**
     * @param	SchoolHealth $schoolHealth The schoolHealth object to remove.
     * @return Application The current object (for fluent API support)
     */
    public function removeSchoolHealth($schoolHealth)
    {
        if ($this->getSchoolHealths()->contains($schoolHealth)) {
            $this->collSchoolHealths->remove($this->collSchoolHealths->search($schoolHealth));
            if (null === $this->schoolHealthsScheduledForDeletion) {
                $this->schoolHealthsScheduledForDeletion = clone $this->collSchoolHealths;
                $this->schoolHealthsScheduledForDeletion->clear();
            }
            $this->schoolHealthsScheduledForDeletion[]= $schoolHealth;
            $schoolHealth->setApplication(null);
        }

        return $this;
    }

    /**
     * Clears out the collStudents collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Application The current object (for fluent API support)
     * @see        addStudents()
     */
    public function clearStudents()
    {
        $this->collStudents = null; // important to set this to null since that means it is uninitialized
        $this->collStudentsPartial = null;

        return $this;
    }

    /**
     * reset is the collStudents collection loaded partially
     *
     * @return void
     */
    public function resetPartialStudents($v = true)
    {
        $this->collStudentsPartial = $v;
    }

    /**
     * Initializes the collStudents collection.
     *
     * By default this just sets the collStudents collection to an empty array (like clearcollStudents());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStudents($overrideExisting = true)
    {
        if (null !== $this->collStudents && !$overrideExisting) {
            return;
        }
        $this->collStudents = new PropelObjectCollection();
        $this->collStudents->setModel('Student');
    }

    /**
     * Gets an array of Student objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Application is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Student[] List of Student objects
     * @throws PropelException
     */
    public function getStudents($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collStudentsPartial && !$this->isNew();
        if (null === $this->collStudents || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collStudents) {
                // return empty collection
                $this->initStudents();
            } else {
                $collStudents = StudentQuery::create(null, $criteria)
                    ->filterByApplication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collStudentsPartial && count($collStudents)) {
                      $this->initStudents(false);

                      foreach ($collStudents as $obj) {
                        if (false == $this->collStudents->contains($obj)) {
                          $this->collStudents->append($obj);
                        }
                      }

                      $this->collStudentsPartial = true;
                    }

                    $collStudents->getInternalIterator()->rewind();

                    return $collStudents;
                }

                if ($partial && $this->collStudents) {
                    foreach ($this->collStudents as $obj) {
                        if ($obj->isNew()) {
                            $collStudents[] = $obj;
                        }
                    }
                }

                $this->collStudents = $collStudents;
                $this->collStudentsPartial = false;
            }
        }

        return $this->collStudents;
    }

    /**
     * Sets a collection of Student objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $students A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Application The current object (for fluent API support)
     */
    public function setStudents(PropelCollection $students, PropelPDO $con = null)
    {
        $studentsToDelete = $this->getStudents(new Criteria(), $con)->diff($students);


        $this->studentsScheduledForDeletion = $studentsToDelete;

        foreach ($studentsToDelete as $studentRemoved) {
            $studentRemoved->setApplication(null);
        }

        $this->collStudents = null;
        foreach ($students as $student) {
            $this->addStudent($student);
        }

        $this->collStudents = $students;
        $this->collStudentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Student objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Student objects.
     * @throws PropelException
     */
    public function countStudents(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collStudentsPartial && !$this->isNew();
        if (null === $this->collStudents || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStudents) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStudents());
            }
            $query = StudentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByApplication($this)
                ->count($con);
        }

        return count($this->collStudents);
    }

    /**
     * Method called to associate a Student object to this object
     * through the Student foreign key attribute.
     *
     * @param    Student $l Student
     * @return Application The current object (for fluent API support)
     */
    public function addStudent(Student $l)
    {
        if ($this->collStudents === null) {
            $this->initStudents();
            $this->collStudentsPartial = true;
        }

        if (!in_array($l, $this->collStudents->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddStudent($l);

            if ($this->studentsScheduledForDeletion and $this->studentsScheduledForDeletion->contains($l)) {
                $this->studentsScheduledForDeletion->remove($this->studentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Student $student The student object to add.
     */
    protected function doAddStudent($student)
    {
        $this->collStudents[]= $student;
        $student->setApplication($this);
    }

    /**
     * @param	Student $student The student object to remove.
     * @return Application The current object (for fluent API support)
     */
    public function removeStudent($student)
    {
        if ($this->getStudents()->contains($student)) {
            $this->collStudents->remove($this->collStudents->search($student));
            if (null === $this->studentsScheduledForDeletion) {
                $this->studentsScheduledForDeletion = clone $this->collStudents;
                $this->studentsScheduledForDeletion->clear();
            }
            $this->studentsScheduledForDeletion[]= $student;
            $student->setApplication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Application is new, it will return
     * an empty collection; or if this Application has previously
     * been saved, it will retrieve related Students from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Application.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Student[] List of Student objects
     */
    public function getStudentsJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StudentQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getStudents($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Application is new, it will return
     * an empty collection; or if this Application has previously
     * been saved, it will retrieve related Students from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Application.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Student[] List of Student objects
     */
    public function getStudentsJoinSchoolHealth($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StudentQuery::create(null, $criteria);
        $query->joinWith('SchoolHealth', $join_behavior);

        return $this->getStudents($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->form_no = null;
        $this->student_nation_no = null;
        $this->prior_test_no = null;
        $this->student_no = null;
        $this->first_name = null;
        $this->last_name = null;
        $this->nick_name = null;
        $this->phone_student = null;
        $this->gender = null;
        $this->place_of_birth = null;
        $this->date_of_birth = null;
        $this->religion = null;
        $this->level_id = null;
        $this->grade_id = null;
        $this->ethnicity_id = null;
        $this->child_no = null;
        $this->child_total = null;
        $this->child_status = null;
        $this->picture = null;
        $this->birth_certificate = null;
        $this->family_card = null;
        $this->graduation_certificate = null;
        $this->address = null;
        $this->city = null;
        $this->state_id = null;
        $this->zip = null;
        $this->country_id = null;
        $this->school_id = null;
        $this->school_year_id = null;
        $this->status = null;
        $this->mailing_address = null;
        $this->note = null;
        $this->hobby = null;
        $this->entered_by = null;
        $this->first_name_father = null;
        $this->last_name_father = null;
        $this->business_address_father = null;
        $this->occupation_father = null;
        $this->phone_father = null;
        $this->email_father = null;
        $this->first_name_mother = null;
        $this->last_name_mother = null;
        $this->business_address_mother = null;
        $this->occupation_mother = null;
        $this->phone_mother = null;
        $this->email_mother = null;
        $this->first_name_legal_guardian = null;
        $this->last_name_legal_guardian = null;
        $this->occupation_legal_guardian = null;
        $this->phone_legal_guardian = null;
        $this->email_legal_guardian = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collParentStudents) {
                foreach ($this->collParentStudents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSchoolHealths) {
                foreach ($this->collSchoolHealths as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStudents) {
                foreach ($this->collStudents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aUser instanceof Persistent) {
              $this->aUser->clearAllReferences($deep);
            }
            if ($this->aSchool instanceof Persistent) {
              $this->aSchool->clearAllReferences($deep);
            }
            if ($this->aSchoolYear instanceof Persistent) {
              $this->aSchoolYear->clearAllReferences($deep);
            }
            if ($this->aEthnicity instanceof Persistent) {
              $this->aEthnicity->clearAllReferences($deep);
            }
            if ($this->aGrade instanceof Persistent) {
              $this->aGrade->clearAllReferences($deep);
            }
            if ($this->aLevel instanceof Persistent) {
              $this->aLevel->clearAllReferences($deep);
            }
            if ($this->aState instanceof Persistent) {
              $this->aState->clearAllReferences($deep);
            }
            if ($this->aCountry instanceof Persistent) {
              $this->aCountry->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collParentStudents instanceof PropelCollection) {
            $this->collParentStudents->clearIterator();
        }
        $this->collParentStudents = null;
        if ($this->collSchoolHealths instanceof PropelCollection) {
            $this->collSchoolHealths->clearIterator();
        }
        $this->collSchoolHealths = null;
        if ($this->collStudents instanceof PropelCollection) {
            $this->collStudents->clearIterator();
        }
        $this->collStudents = null;
        $this->aUser = null;
        $this->aSchool = null;
        $this->aSchoolYear = null;
        $this->aEthnicity = null;
        $this->aGrade = null;
        $this->aLevel = null;
        $this->aState = null;
        $this->aCountry = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ApplicationPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     Application The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = ApplicationPeer::UPDATED_AT;

        return $this;
    }

    // event behavior
    public function preCommit(\PropelPDO $con = null){}
    public function preCommitSave(\PropelPDO $con = null){}
    public function preCommitDelete(\PropelPDO $con = null){}
    public function preCommitUpdate(\PropelPDO $con = null){}
    public function preCommitInsert(\PropelPDO $con = null){}
    public function preRollback(\PropelPDO $con = null){}
    public function preRollbackSave(\PropelPDO $con = null){}
    public function preRollbackDelete(\PropelPDO $con = null){}
    public function preRollbackUpdate(\PropelPDO $con = null){}
    public function preRollbackInsert(\PropelPDO $con = null){}

}
