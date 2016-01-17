<?php

namespace PGS\CoreDomainBundle\Model\Student\om;

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
use PGS\CoreDomainBundle\Model\User;
use PGS\CoreDomainBundle\Model\UserQuery;
use PGS\CoreDomainBundle\Model\Application\Application;
use PGS\CoreDomainBundle\Model\Application\ApplicationQuery;
use PGS\CoreDomainBundle\Model\ParentStudent\ParentStudent;
use PGS\CoreDomainBundle\Model\ParentStudent\ParentStudentQuery;
use PGS\CoreDomainBundle\Model\SchoolClassCourseStudentBehavior\SchoolClassCourseStudentBehavior;
use PGS\CoreDomainBundle\Model\SchoolClassCourseStudentBehavior\SchoolClassCourseStudentBehaviorQuery;
use PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudent;
use PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudentQuery;
use PGS\CoreDomainBundle\Model\SchoolEnrollment\SchoolEnrollment;
use PGS\CoreDomainBundle\Model\SchoolEnrollment\SchoolEnrollmentQuery;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealth;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealthQuery;
use PGS\CoreDomainBundle\Model\Student\Student;
use PGS\CoreDomainBundle\Model\Student\StudentPeer;
use PGS\CoreDomainBundle\Model\Student\StudentQuery;
use PGS\CoreDomainBundle\Model\StudentHistory\StudentHistory;
use PGS\CoreDomainBundle\Model\StudentHistory\StudentHistoryQuery;

abstract class BaseStudent extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'PGS\\CoreDomainBundle\\Model\\Student\\StudentPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        StudentPeer
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
     * The value for the user_id field.
     * @var        int
     */
    protected $user_id;

    /**
     * The value for the application_id field.
     * @var        int
     */
    protected $application_id;

    /**
     * The value for the health_id field.
     * @var        int
     */
    protected $health_id;

    /**
     * The value for the student_nation_no field.
     * @var        string
     */
    protected $student_nation_no;

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
     * The value for the middle_name field.
     * @var        string
     */
    protected $middle_name;

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
     * The value for the authorization_code field.
     * @var        string
     */
    protected $authorization_code;

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
     * @var        Application
     */
    protected $aApplication;

    /**
     * @var        SchoolHealth
     */
    protected $aSchoolHealth;

    /**
     * @var        PropelObjectCollection|ParentStudent[] Collection to store aggregation of ParentStudent objects.
     */
    protected $collParentStudents;
    protected $collParentStudentsPartial;

    /**
     * @var        PropelObjectCollection|SchoolClassCourseStudentBehavior[] Collection to store aggregation of SchoolClassCourseStudentBehavior objects.
     */
    protected $collSchoolClassCourseStudentBehaviors;
    protected $collSchoolClassCourseStudentBehaviorsPartial;

    /**
     * @var        PropelObjectCollection|SchoolClassStudent[] Collection to store aggregation of SchoolClassStudent objects.
     */
    protected $collSchoolClassStudents;
    protected $collSchoolClassStudentsPartial;

    /**
     * @var        PropelObjectCollection|SchoolEnrollment[] Collection to store aggregation of SchoolEnrollment objects.
     */
    protected $collSchoolEnrollments;
    protected $collSchoolEnrollmentsPartial;

    /**
     * @var        PropelObjectCollection|StudentHistory[] Collection to store aggregation of StudentHistory objects.
     */
    protected $collStudentHistories;
    protected $collStudentHistoriesPartial;

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
    protected $schoolClassCourseStudentBehaviorsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $schoolClassStudentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $schoolEnrollmentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $studentHistoriesScheduledForDeletion = null;

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
    }

    /**
     * Initializes internal state of BaseStudent object.
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
     * Get the [user_id] column value.
     *
     * @return int
     */
    public function getUserId()
    {

        return $this->user_id;
    }

    /**
     * Get the [application_id] column value.
     *
     * @return int
     */
    public function getApplicationId()
    {

        return $this->application_id;
    }

    /**
     * Get the [health_id] column value.
     *
     * @return int
     */
    public function getHealthId()
    {

        return $this->health_id;
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
     * Get the [middle_name] column value.
     *
     * @return string
     */
    public function getMiddleName()
    {

        return $this->middle_name;
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
        $valueSet = StudentPeer::getValueSet(StudentPeer::GENDER);
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
        $valueSet = StudentPeer::getValueSet(StudentPeer::RELIGION);
        if (!isset($valueSet[$this->religion])) {
            throw new PropelException('Unknown stored enum key: ' . $this->religion);
        }

        return $valueSet[$this->religion];
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
     * Get the [authorization_code] column value.
     *
     * @return string
     */
    public function getAuthorizationCode()
    {

        return $this->authorization_code;
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
     * @return Student The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = StudentPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [user_id] column.
     *
     * @param  int $v new value
     * @return Student The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[] = StudentPeer::USER_ID;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }


        return $this;
    } // setUserId()

    /**
     * Set the value of [application_id] column.
     *
     * @param  int $v new value
     * @return Student The current object (for fluent API support)
     */
    public function setApplicationId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->application_id !== $v) {
            $this->application_id = $v;
            $this->modifiedColumns[] = StudentPeer::APPLICATION_ID;
        }

        if ($this->aApplication !== null && $this->aApplication->getId() !== $v) {
            $this->aApplication = null;
        }


        return $this;
    } // setApplicationId()

    /**
     * Set the value of [health_id] column.
     *
     * @param  int $v new value
     * @return Student The current object (for fluent API support)
     */
    public function setHealthId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->health_id !== $v) {
            $this->health_id = $v;
            $this->modifiedColumns[] = StudentPeer::HEALTH_ID;
        }

        if ($this->aSchoolHealth !== null && $this->aSchoolHealth->getId() !== $v) {
            $this->aSchoolHealth = null;
        }


        return $this;
    } // setHealthId()

    /**
     * Set the value of [student_nation_no] column.
     *
     * @param  string $v new value
     * @return Student The current object (for fluent API support)
     */
    public function setStudentNationNo($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->student_nation_no !== $v) {
            $this->student_nation_no = $v;
            $this->modifiedColumns[] = StudentPeer::STUDENT_NATION_NO;
        }


        return $this;
    } // setStudentNationNo()

    /**
     * Set the value of [student_no] column.
     *
     * @param  string $v new value
     * @return Student The current object (for fluent API support)
     */
    public function setStudentNo($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->student_no !== $v) {
            $this->student_no = $v;
            $this->modifiedColumns[] = StudentPeer::STUDENT_NO;
        }


        return $this;
    } // setStudentNo()

    /**
     * Set the value of [first_name] column.
     *
     * @param  string $v new value
     * @return Student The current object (for fluent API support)
     */
    public function setFirstName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->first_name !== $v) {
            $this->first_name = $v;
            $this->modifiedColumns[] = StudentPeer::FIRST_NAME;
        }


        return $this;
    } // setFirstName()

    /**
     * Set the value of [middle_name] column.
     *
     * @param  string $v new value
     * @return Student The current object (for fluent API support)
     */
    public function setMiddleName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->middle_name !== $v) {
            $this->middle_name = $v;
            $this->modifiedColumns[] = StudentPeer::MIDDLE_NAME;
        }


        return $this;
    } // setMiddleName()

    /**
     * Set the value of [last_name] column.
     *
     * @param  string $v new value
     * @return Student The current object (for fluent API support)
     */
    public function setLastName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->last_name !== $v) {
            $this->last_name = $v;
            $this->modifiedColumns[] = StudentPeer::LAST_NAME;
        }


        return $this;
    } // setLastName()

    /**
     * Set the value of [nick_name] column.
     *
     * @param  string $v new value
     * @return Student The current object (for fluent API support)
     */
    public function setNickName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->nick_name !== $v) {
            $this->nick_name = $v;
            $this->modifiedColumns[] = StudentPeer::NICK_NAME;
        }


        return $this;
    } // setNickName()

    /**
     * Set the value of [gender] column.
     *
     * @param  int $v new value
     * @return Student The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setGender($v)
    {
        if ($v !== null) {
            $valueSet = StudentPeer::getValueSet(StudentPeer::GENDER);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->gender !== $v) {
            $this->gender = $v;
            $this->modifiedColumns[] = StudentPeer::GENDER;
        }


        return $this;
    } // setGender()

    /**
     * Set the value of [place_of_birth] column.
     *
     * @param  string $v new value
     * @return Student The current object (for fluent API support)
     */
    public function setPlaceOfBirth($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->place_of_birth !== $v) {
            $this->place_of_birth = $v;
            $this->modifiedColumns[] = StudentPeer::PLACE_OF_BIRTH;
        }


        return $this;
    } // setPlaceOfBirth()

    /**
     * Sets the value of [date_of_birth] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Student The current object (for fluent API support)
     */
    public function setDateOfBirth($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date_of_birth !== null || $dt !== null) {
            $currentDateAsString = ($this->date_of_birth !== null && $tmpDt = new DateTime($this->date_of_birth)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->date_of_birth = $newDateAsString;
                $this->modifiedColumns[] = StudentPeer::DATE_OF_BIRTH;
            }
        } // if either are not null


        return $this;
    } // setDateOfBirth()

    /**
     * Set the value of [religion] column.
     *
     * @param  int $v new value
     * @return Student The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setReligion($v)
    {
        if ($v !== null) {
            $valueSet = StudentPeer::getValueSet(StudentPeer::RELIGION);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->religion !== $v) {
            $this->religion = $v;
            $this->modifiedColumns[] = StudentPeer::RELIGION;
        }


        return $this;
    } // setReligion()

    /**
     * Set the value of [picture] column.
     *
     * @param  string $v new value
     * @return Student The current object (for fluent API support)
     */
    public function setPicture($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->picture !== $v) {
            $this->picture = $v;
            $this->modifiedColumns[] = StudentPeer::PICTURE;
        }


        return $this;
    } // setPicture()

    /**
     * Set the value of [birth_certificate] column.
     *
     * @param  string $v new value
     * @return Student The current object (for fluent API support)
     */
    public function setBirthCertificate($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->birth_certificate !== $v) {
            $this->birth_certificate = $v;
            $this->modifiedColumns[] = StudentPeer::BIRTH_CERTIFICATE;
        }


        return $this;
    } // setBirthCertificate()

    /**
     * Set the value of [family_card] column.
     *
     * @param  string $v new value
     * @return Student The current object (for fluent API support)
     */
    public function setFamilyCard($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->family_card !== $v) {
            $this->family_card = $v;
            $this->modifiedColumns[] = StudentPeer::FAMILY_CARD;
        }


        return $this;
    } // setFamilyCard()

    /**
     * Set the value of [graduation_certificate] column.
     *
     * @param  string $v new value
     * @return Student The current object (for fluent API support)
     */
    public function setGraduationCertificate($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->graduation_certificate !== $v) {
            $this->graduation_certificate = $v;
            $this->modifiedColumns[] = StudentPeer::GRADUATION_CERTIFICATE;
        }


        return $this;
    } // setGraduationCertificate()

    /**
     * Set the value of [authorization_code] column.
     *
     * @param  string $v new value
     * @return Student The current object (for fluent API support)
     */
    public function setAuthorizationCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->authorization_code !== $v) {
            $this->authorization_code = $v;
            $this->modifiedColumns[] = StudentPeer::AUTHORIZATION_CODE;
        }


        return $this;
    } // setAuthorizationCode()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Student The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = StudentPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Student The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = StudentPeer::UPDATED_AT;
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
            $this->user_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->application_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->health_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->student_nation_no = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->student_no = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->first_name = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->middle_name = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->last_name = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->nick_name = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->gender = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
            $this->place_of_birth = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->date_of_birth = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
            $this->religion = ($row[$startcol + 13] !== null) ? (int) $row[$startcol + 13] : null;
            $this->picture = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
            $this->birth_certificate = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
            $this->family_card = ($row[$startcol + 16] !== null) ? (string) $row[$startcol + 16] : null;
            $this->graduation_certificate = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
            $this->authorization_code = ($row[$startcol + 18] !== null) ? (string) $row[$startcol + 18] : null;
            $this->created_at = ($row[$startcol + 19] !== null) ? (string) $row[$startcol + 19] : null;
            $this->updated_at = ($row[$startcol + 20] !== null) ? (string) $row[$startcol + 20] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 21; // 21 = StudentPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Student object", $e);
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

        if ($this->aUser !== null && $this->user_id !== $this->aUser->getId()) {
            $this->aUser = null;
        }
        if ($this->aApplication !== null && $this->application_id !== $this->aApplication->getId()) {
            $this->aApplication = null;
        }
        if ($this->aSchoolHealth !== null && $this->health_id !== $this->aSchoolHealth->getId()) {
            $this->aSchoolHealth = null;
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
            $con = Propel::getConnection(StudentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = StudentPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->aApplication = null;
            $this->aSchoolHealth = null;
            $this->collParentStudents = null;

            $this->collSchoolClassCourseStudentBehaviors = null;

            $this->collSchoolClassStudents = null;

            $this->collSchoolEnrollments = null;

            $this->collStudentHistories = null;

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
            $con = Propel::getConnection(StudentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
            $deleteQuery = StudentQuery::create()
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
            $con = Propel::getConnection(StudentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                if (!$this->isColumnModified(StudentPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(StudentPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(StudentPeer::UPDATED_AT)) {
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
                StudentPeer::addInstanceToPool($this);
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

            if ($this->aApplication !== null) {
                if ($this->aApplication->isModified() || $this->aApplication->isNew()) {
                    $affectedRows += $this->aApplication->save($con);
                }
                $this->setApplication($this->aApplication);
            }

            if ($this->aSchoolHealth !== null) {
                if ($this->aSchoolHealth->isModified() || $this->aSchoolHealth->isNew()) {
                    $affectedRows += $this->aSchoolHealth->save($con);
                }
                $this->setSchoolHealth($this->aSchoolHealth);
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

            if ($this->schoolClassCourseStudentBehaviorsScheduledForDeletion !== null) {
                if (!$this->schoolClassCourseStudentBehaviorsScheduledForDeletion->isEmpty()) {
                    SchoolClassCourseStudentBehaviorQuery::create()
                        ->filterByPrimaryKeys($this->schoolClassCourseStudentBehaviorsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->schoolClassCourseStudentBehaviorsScheduledForDeletion = null;
                }
            }

            if ($this->collSchoolClassCourseStudentBehaviors !== null) {
                foreach ($this->collSchoolClassCourseStudentBehaviors as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->schoolClassStudentsScheduledForDeletion !== null) {
                if (!$this->schoolClassStudentsScheduledForDeletion->isEmpty()) {
                    SchoolClassStudentQuery::create()
                        ->filterByPrimaryKeys($this->schoolClassStudentsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->schoolClassStudentsScheduledForDeletion = null;
                }
            }

            if ($this->collSchoolClassStudents !== null) {
                foreach ($this->collSchoolClassStudents as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->schoolEnrollmentsScheduledForDeletion !== null) {
                if (!$this->schoolEnrollmentsScheduledForDeletion->isEmpty()) {
                    foreach ($this->schoolEnrollmentsScheduledForDeletion as $schoolEnrollment) {
                        // need to save related object because we set the relation to null
                        $schoolEnrollment->save($con);
                    }
                    $this->schoolEnrollmentsScheduledForDeletion = null;
                }
            }

            if ($this->collSchoolEnrollments !== null) {
                foreach ($this->collSchoolEnrollments as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->studentHistoriesScheduledForDeletion !== null) {
                if (!$this->studentHistoriesScheduledForDeletion->isEmpty()) {
                    StudentHistoryQuery::create()
                        ->filterByPrimaryKeys($this->studentHistoriesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->studentHistoriesScheduledForDeletion = null;
                }
            }

            if ($this->collStudentHistories !== null) {
                foreach ($this->collStudentHistories as $referrerFK) {
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

        $this->modifiedColumns[] = StudentPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . StudentPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(StudentPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(StudentPeer::USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`user_id`';
        }
        if ($this->isColumnModified(StudentPeer::APPLICATION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`application_id`';
        }
        if ($this->isColumnModified(StudentPeer::HEALTH_ID)) {
            $modifiedColumns[':p' . $index++]  = '`health_id`';
        }
        if ($this->isColumnModified(StudentPeer::STUDENT_NATION_NO)) {
            $modifiedColumns[':p' . $index++]  = '`student_nation_no`';
        }
        if ($this->isColumnModified(StudentPeer::STUDENT_NO)) {
            $modifiedColumns[':p' . $index++]  = '`student_no`';
        }
        if ($this->isColumnModified(StudentPeer::FIRST_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`first_name`';
        }
        if ($this->isColumnModified(StudentPeer::MIDDLE_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`middle_name`';
        }
        if ($this->isColumnModified(StudentPeer::LAST_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`last_name`';
        }
        if ($this->isColumnModified(StudentPeer::NICK_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`nick_name`';
        }
        if ($this->isColumnModified(StudentPeer::GENDER)) {
            $modifiedColumns[':p' . $index++]  = '`gender`';
        }
        if ($this->isColumnModified(StudentPeer::PLACE_OF_BIRTH)) {
            $modifiedColumns[':p' . $index++]  = '`place_of_birth`';
        }
        if ($this->isColumnModified(StudentPeer::DATE_OF_BIRTH)) {
            $modifiedColumns[':p' . $index++]  = '`date_of_birth`';
        }
        if ($this->isColumnModified(StudentPeer::RELIGION)) {
            $modifiedColumns[':p' . $index++]  = '`religion`';
        }
        if ($this->isColumnModified(StudentPeer::PICTURE)) {
            $modifiedColumns[':p' . $index++]  = '`picture`';
        }
        if ($this->isColumnModified(StudentPeer::BIRTH_CERTIFICATE)) {
            $modifiedColumns[':p' . $index++]  = '`birth_certificate`';
        }
        if ($this->isColumnModified(StudentPeer::FAMILY_CARD)) {
            $modifiedColumns[':p' . $index++]  = '`family_card`';
        }
        if ($this->isColumnModified(StudentPeer::GRADUATION_CERTIFICATE)) {
            $modifiedColumns[':p' . $index++]  = '`graduation_certificate`';
        }
        if ($this->isColumnModified(StudentPeer::AUTHORIZATION_CODE)) {
            $modifiedColumns[':p' . $index++]  = '`authorization_code`';
        }
        if ($this->isColumnModified(StudentPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(StudentPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `student` (%s) VALUES (%s)',
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
                    case '`user_id`':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);
                        break;
                    case '`application_id`':
                        $stmt->bindValue($identifier, $this->application_id, PDO::PARAM_INT);
                        break;
                    case '`health_id`':
                        $stmt->bindValue($identifier, $this->health_id, PDO::PARAM_INT);
                        break;
                    case '`student_nation_no`':
                        $stmt->bindValue($identifier, $this->student_nation_no, PDO::PARAM_STR);
                        break;
                    case '`student_no`':
                        $stmt->bindValue($identifier, $this->student_no, PDO::PARAM_STR);
                        break;
                    case '`first_name`':
                        $stmt->bindValue($identifier, $this->first_name, PDO::PARAM_STR);
                        break;
                    case '`middle_name`':
                        $stmt->bindValue($identifier, $this->middle_name, PDO::PARAM_STR);
                        break;
                    case '`last_name`':
                        $stmt->bindValue($identifier, $this->last_name, PDO::PARAM_STR);
                        break;
                    case '`nick_name`':
                        $stmt->bindValue($identifier, $this->nick_name, PDO::PARAM_STR);
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
                    case '`authorization_code`':
                        $stmt->bindValue($identifier, $this->authorization_code, PDO::PARAM_STR);
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

            if ($this->aApplication !== null) {
                if (!$this->aApplication->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aApplication->getValidationFailures());
                }
            }

            if ($this->aSchoolHealth !== null) {
                if (!$this->aSchoolHealth->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aSchoolHealth->getValidationFailures());
                }
            }


            if (($retval = StudentPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collParentStudents !== null) {
                    foreach ($this->collParentStudents as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSchoolClassCourseStudentBehaviors !== null) {
                    foreach ($this->collSchoolClassCourseStudentBehaviors as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSchoolClassStudents !== null) {
                    foreach ($this->collSchoolClassStudents as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSchoolEnrollments !== null) {
                    foreach ($this->collSchoolEnrollments as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collStudentHistories !== null) {
                    foreach ($this->collStudentHistories as $referrerFK) {
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
        $pos = StudentPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getUserId();
                break;
            case 2:
                return $this->getApplicationId();
                break;
            case 3:
                return $this->getHealthId();
                break;
            case 4:
                return $this->getStudentNationNo();
                break;
            case 5:
                return $this->getStudentNo();
                break;
            case 6:
                return $this->getFirstName();
                break;
            case 7:
                return $this->getMiddleName();
                break;
            case 8:
                return $this->getLastName();
                break;
            case 9:
                return $this->getNickName();
                break;
            case 10:
                return $this->getGender();
                break;
            case 11:
                return $this->getPlaceOfBirth();
                break;
            case 12:
                return $this->getDateOfBirth();
                break;
            case 13:
                return $this->getReligion();
                break;
            case 14:
                return $this->getPicture();
                break;
            case 15:
                return $this->getBirthCertificate();
                break;
            case 16:
                return $this->getFamilyCard();
                break;
            case 17:
                return $this->getGraduationCertificate();
                break;
            case 18:
                return $this->getAuthorizationCode();
                break;
            case 19:
                return $this->getCreatedAt();
                break;
            case 20:
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
        if (isset($alreadyDumpedObjects['Student'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Student'][$this->getPrimaryKey()] = true;
        $keys = StudentPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUserId(),
            $keys[2] => $this->getApplicationId(),
            $keys[3] => $this->getHealthId(),
            $keys[4] => $this->getStudentNationNo(),
            $keys[5] => $this->getStudentNo(),
            $keys[6] => $this->getFirstName(),
            $keys[7] => $this->getMiddleName(),
            $keys[8] => $this->getLastName(),
            $keys[9] => $this->getNickName(),
            $keys[10] => $this->getGender(),
            $keys[11] => $this->getPlaceOfBirth(),
            $keys[12] => $this->getDateOfBirth(),
            $keys[13] => $this->getReligion(),
            $keys[14] => $this->getPicture(),
            $keys[15] => $this->getBirthCertificate(),
            $keys[16] => $this->getFamilyCard(),
            $keys[17] => $this->getGraduationCertificate(),
            $keys[18] => $this->getAuthorizationCode(),
            $keys[19] => $this->getCreatedAt(),
            $keys[20] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aUser) {
                $result['User'] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aApplication) {
                $result['Application'] = $this->aApplication->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aSchoolHealth) {
                $result['SchoolHealth'] = $this->aSchoolHealth->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collParentStudents) {
                $result['ParentStudents'] = $this->collParentStudents->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSchoolClassCourseStudentBehaviors) {
                $result['SchoolClassCourseStudentBehaviors'] = $this->collSchoolClassCourseStudentBehaviors->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSchoolClassStudents) {
                $result['SchoolClassStudents'] = $this->collSchoolClassStudents->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSchoolEnrollments) {
                $result['SchoolEnrollments'] = $this->collSchoolEnrollments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStudentHistories) {
                $result['StudentHistories'] = $this->collStudentHistories->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = StudentPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setUserId($value);
                break;
            case 2:
                $this->setApplicationId($value);
                break;
            case 3:
                $this->setHealthId($value);
                break;
            case 4:
                $this->setStudentNationNo($value);
                break;
            case 5:
                $this->setStudentNo($value);
                break;
            case 6:
                $this->setFirstName($value);
                break;
            case 7:
                $this->setMiddleName($value);
                break;
            case 8:
                $this->setLastName($value);
                break;
            case 9:
                $this->setNickName($value);
                break;
            case 10:
                $valueSet = StudentPeer::getValueSet(StudentPeer::GENDER);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setGender($value);
                break;
            case 11:
                $this->setPlaceOfBirth($value);
                break;
            case 12:
                $this->setDateOfBirth($value);
                break;
            case 13:
                $valueSet = StudentPeer::getValueSet(StudentPeer::RELIGION);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setReligion($value);
                break;
            case 14:
                $this->setPicture($value);
                break;
            case 15:
                $this->setBirthCertificate($value);
                break;
            case 16:
                $this->setFamilyCard($value);
                break;
            case 17:
                $this->setGraduationCertificate($value);
                break;
            case 18:
                $this->setAuthorizationCode($value);
                break;
            case 19:
                $this->setCreatedAt($value);
                break;
            case 20:
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
        $keys = StudentPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUserId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setApplicationId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setHealthId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setStudentNationNo($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setStudentNo($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setFirstName($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setMiddleName($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setLastName($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setNickName($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setGender($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setPlaceOfBirth($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setDateOfBirth($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setReligion($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setPicture($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setBirthCertificate($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setFamilyCard($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setGraduationCertificate($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setAuthorizationCode($arr[$keys[18]]);
        if (array_key_exists($keys[19], $arr)) $this->setCreatedAt($arr[$keys[19]]);
        if (array_key_exists($keys[20], $arr)) $this->setUpdatedAt($arr[$keys[20]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(StudentPeer::DATABASE_NAME);

        if ($this->isColumnModified(StudentPeer::ID)) $criteria->add(StudentPeer::ID, $this->id);
        if ($this->isColumnModified(StudentPeer::USER_ID)) $criteria->add(StudentPeer::USER_ID, $this->user_id);
        if ($this->isColumnModified(StudentPeer::APPLICATION_ID)) $criteria->add(StudentPeer::APPLICATION_ID, $this->application_id);
        if ($this->isColumnModified(StudentPeer::HEALTH_ID)) $criteria->add(StudentPeer::HEALTH_ID, $this->health_id);
        if ($this->isColumnModified(StudentPeer::STUDENT_NATION_NO)) $criteria->add(StudentPeer::STUDENT_NATION_NO, $this->student_nation_no);
        if ($this->isColumnModified(StudentPeer::STUDENT_NO)) $criteria->add(StudentPeer::STUDENT_NO, $this->student_no);
        if ($this->isColumnModified(StudentPeer::FIRST_NAME)) $criteria->add(StudentPeer::FIRST_NAME, $this->first_name);
        if ($this->isColumnModified(StudentPeer::MIDDLE_NAME)) $criteria->add(StudentPeer::MIDDLE_NAME, $this->middle_name);
        if ($this->isColumnModified(StudentPeer::LAST_NAME)) $criteria->add(StudentPeer::LAST_NAME, $this->last_name);
        if ($this->isColumnModified(StudentPeer::NICK_NAME)) $criteria->add(StudentPeer::NICK_NAME, $this->nick_name);
        if ($this->isColumnModified(StudentPeer::GENDER)) $criteria->add(StudentPeer::GENDER, $this->gender);
        if ($this->isColumnModified(StudentPeer::PLACE_OF_BIRTH)) $criteria->add(StudentPeer::PLACE_OF_BIRTH, $this->place_of_birth);
        if ($this->isColumnModified(StudentPeer::DATE_OF_BIRTH)) $criteria->add(StudentPeer::DATE_OF_BIRTH, $this->date_of_birth);
        if ($this->isColumnModified(StudentPeer::RELIGION)) $criteria->add(StudentPeer::RELIGION, $this->religion);
        if ($this->isColumnModified(StudentPeer::PICTURE)) $criteria->add(StudentPeer::PICTURE, $this->picture);
        if ($this->isColumnModified(StudentPeer::BIRTH_CERTIFICATE)) $criteria->add(StudentPeer::BIRTH_CERTIFICATE, $this->birth_certificate);
        if ($this->isColumnModified(StudentPeer::FAMILY_CARD)) $criteria->add(StudentPeer::FAMILY_CARD, $this->family_card);
        if ($this->isColumnModified(StudentPeer::GRADUATION_CERTIFICATE)) $criteria->add(StudentPeer::GRADUATION_CERTIFICATE, $this->graduation_certificate);
        if ($this->isColumnModified(StudentPeer::AUTHORIZATION_CODE)) $criteria->add(StudentPeer::AUTHORIZATION_CODE, $this->authorization_code);
        if ($this->isColumnModified(StudentPeer::CREATED_AT)) $criteria->add(StudentPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(StudentPeer::UPDATED_AT)) $criteria->add(StudentPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(StudentPeer::DATABASE_NAME);
        $criteria->add(StudentPeer::ID, $this->id);

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
     * @param object $copyObj An object of Student (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUserId($this->getUserId());
        $copyObj->setApplicationId($this->getApplicationId());
        $copyObj->setHealthId($this->getHealthId());
        $copyObj->setStudentNationNo($this->getStudentNationNo());
        $copyObj->setStudentNo($this->getStudentNo());
        $copyObj->setFirstName($this->getFirstName());
        $copyObj->setMiddleName($this->getMiddleName());
        $copyObj->setLastName($this->getLastName());
        $copyObj->setNickName($this->getNickName());
        $copyObj->setGender($this->getGender());
        $copyObj->setPlaceOfBirth($this->getPlaceOfBirth());
        $copyObj->setDateOfBirth($this->getDateOfBirth());
        $copyObj->setReligion($this->getReligion());
        $copyObj->setPicture($this->getPicture());
        $copyObj->setBirthCertificate($this->getBirthCertificate());
        $copyObj->setFamilyCard($this->getFamilyCard());
        $copyObj->setGraduationCertificate($this->getGraduationCertificate());
        $copyObj->setAuthorizationCode($this->getAuthorizationCode());
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

            foreach ($this->getSchoolClassCourseStudentBehaviors() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSchoolClassCourseStudentBehavior($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSchoolClassStudents() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSchoolClassStudent($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSchoolEnrollments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSchoolEnrollment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStudentHistories() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStudentHistory($relObj->copy($deepCopy));
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
     * @return Student Clone of current object.
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
     * @return StudentPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new StudentPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param                  User $v
     * @return Student The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(User $v = null)
    {
        if ($v === null) {
            $this->setUserId(NULL);
        } else {
            $this->setUserId($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addStudent($this);
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
        if ($this->aUser === null && ($this->user_id !== null) && $doQuery) {
            $this->aUser = UserQuery::create()->findPk($this->user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addStudents($this);
             */
        }

        return $this->aUser;
    }

    /**
     * Declares an association between this object and a Application object.
     *
     * @param                  Application $v
     * @return Student The current object (for fluent API support)
     * @throws PropelException
     */
    public function setApplication(Application $v = null)
    {
        if ($v === null) {
            $this->setApplicationId(NULL);
        } else {
            $this->setApplicationId($v->getId());
        }

        $this->aApplication = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Application object, it will not be re-added.
        if ($v !== null) {
            $v->addStudent($this);
        }


        return $this;
    }


    /**
     * Get the associated Application object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Application The associated Application object.
     * @throws PropelException
     */
    public function getApplication(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aApplication === null && ($this->application_id !== null) && $doQuery) {
            $this->aApplication = ApplicationQuery::create()->findPk($this->application_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aApplication->addStudents($this);
             */
        }

        return $this->aApplication;
    }

    /**
     * Declares an association between this object and a SchoolHealth object.
     *
     * @param                  SchoolHealth $v
     * @return Student The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSchoolHealth(SchoolHealth $v = null)
    {
        if ($v === null) {
            $this->setHealthId(NULL);
        } else {
            $this->setHealthId($v->getId());
        }

        $this->aSchoolHealth = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the SchoolHealth object, it will not be re-added.
        if ($v !== null) {
            $v->addStudent($this);
        }


        return $this;
    }


    /**
     * Get the associated SchoolHealth object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return SchoolHealth The associated SchoolHealth object.
     * @throws PropelException
     */
    public function getSchoolHealth(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aSchoolHealth === null && ($this->health_id !== null) && $doQuery) {
            $this->aSchoolHealth = SchoolHealthQuery::create()->findPk($this->health_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSchoolHealth->addStudents($this);
             */
        }

        return $this->aSchoolHealth;
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
        if ('SchoolClassCourseStudentBehavior' == $relationName) {
            $this->initSchoolClassCourseStudentBehaviors();
        }
        if ('SchoolClassStudent' == $relationName) {
            $this->initSchoolClassStudents();
        }
        if ('SchoolEnrollment' == $relationName) {
            $this->initSchoolEnrollments();
        }
        if ('StudentHistory' == $relationName) {
            $this->initStudentHistories();
        }
    }

    /**
     * Clears out the collParentStudents collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Student The current object (for fluent API support)
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
     * If this Student is new, it will return
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
                    ->filterByStudent($this)
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
     * @return Student The current object (for fluent API support)
     */
    public function setParentStudents(PropelCollection $parentStudents, PropelPDO $con = null)
    {
        $parentStudentsToDelete = $this->getParentStudents(new Criteria(), $con)->diff($parentStudents);


        $this->parentStudentsScheduledForDeletion = $parentStudentsToDelete;

        foreach ($parentStudentsToDelete as $parentStudentRemoved) {
            $parentStudentRemoved->setStudent(null);
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
                ->filterByStudent($this)
                ->count($con);
        }

        return count($this->collParentStudents);
    }

    /**
     * Method called to associate a ParentStudent object to this object
     * through the ParentStudent foreign key attribute.
     *
     * @param    ParentStudent $l ParentStudent
     * @return Student The current object (for fluent API support)
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
        $parentStudent->setStudent($this);
    }

    /**
     * @param	ParentStudent $parentStudent The parentStudent object to remove.
     * @return Student The current object (for fluent API support)
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
            $parentStudent->setStudent(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Student is new, it will return
     * an empty collection; or if this Student has previously
     * been saved, it will retrieve related ParentStudents from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Student.
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
     * Otherwise if this Student is new, it will return
     * an empty collection; or if this Student has previously
     * been saved, it will retrieve related ParentStudents from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Student.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|ParentStudent[] List of ParentStudent objects
     */
    public function getParentStudentsJoinApplication($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ParentStudentQuery::create(null, $criteria);
        $query->joinWith('Application', $join_behavior);

        return $this->getParentStudents($query, $con);
    }

    /**
     * Clears out the collSchoolClassCourseStudentBehaviors collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Student The current object (for fluent API support)
     * @see        addSchoolClassCourseStudentBehaviors()
     */
    public function clearSchoolClassCourseStudentBehaviors()
    {
        $this->collSchoolClassCourseStudentBehaviors = null; // important to set this to null since that means it is uninitialized
        $this->collSchoolClassCourseStudentBehaviorsPartial = null;

        return $this;
    }

    /**
     * reset is the collSchoolClassCourseStudentBehaviors collection loaded partially
     *
     * @return void
     */
    public function resetPartialSchoolClassCourseStudentBehaviors($v = true)
    {
        $this->collSchoolClassCourseStudentBehaviorsPartial = $v;
    }

    /**
     * Initializes the collSchoolClassCourseStudentBehaviors collection.
     *
     * By default this just sets the collSchoolClassCourseStudentBehaviors collection to an empty array (like clearcollSchoolClassCourseStudentBehaviors());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSchoolClassCourseStudentBehaviors($overrideExisting = true)
    {
        if (null !== $this->collSchoolClassCourseStudentBehaviors && !$overrideExisting) {
            return;
        }
        $this->collSchoolClassCourseStudentBehaviors = new PropelObjectCollection();
        $this->collSchoolClassCourseStudentBehaviors->setModel('SchoolClassCourseStudentBehavior');
    }

    /**
     * Gets an array of SchoolClassCourseStudentBehavior objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Student is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|SchoolClassCourseStudentBehavior[] List of SchoolClassCourseStudentBehavior objects
     * @throws PropelException
     */
    public function getSchoolClassCourseStudentBehaviors($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSchoolClassCourseStudentBehaviorsPartial && !$this->isNew();
        if (null === $this->collSchoolClassCourseStudentBehaviors || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSchoolClassCourseStudentBehaviors) {
                // return empty collection
                $this->initSchoolClassCourseStudentBehaviors();
            } else {
                $collSchoolClassCourseStudentBehaviors = SchoolClassCourseStudentBehaviorQuery::create(null, $criteria)
                    ->filterByStudent($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSchoolClassCourseStudentBehaviorsPartial && count($collSchoolClassCourseStudentBehaviors)) {
                      $this->initSchoolClassCourseStudentBehaviors(false);

                      foreach ($collSchoolClassCourseStudentBehaviors as $obj) {
                        if (false == $this->collSchoolClassCourseStudentBehaviors->contains($obj)) {
                          $this->collSchoolClassCourseStudentBehaviors->append($obj);
                        }
                      }

                      $this->collSchoolClassCourseStudentBehaviorsPartial = true;
                    }

                    $collSchoolClassCourseStudentBehaviors->getInternalIterator()->rewind();

                    return $collSchoolClassCourseStudentBehaviors;
                }

                if ($partial && $this->collSchoolClassCourseStudentBehaviors) {
                    foreach ($this->collSchoolClassCourseStudentBehaviors as $obj) {
                        if ($obj->isNew()) {
                            $collSchoolClassCourseStudentBehaviors[] = $obj;
                        }
                    }
                }

                $this->collSchoolClassCourseStudentBehaviors = $collSchoolClassCourseStudentBehaviors;
                $this->collSchoolClassCourseStudentBehaviorsPartial = false;
            }
        }

        return $this->collSchoolClassCourseStudentBehaviors;
    }

    /**
     * Sets a collection of SchoolClassCourseStudentBehavior objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $schoolClassCourseStudentBehaviors A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Student The current object (for fluent API support)
     */
    public function setSchoolClassCourseStudentBehaviors(PropelCollection $schoolClassCourseStudentBehaviors, PropelPDO $con = null)
    {
        $schoolClassCourseStudentBehaviorsToDelete = $this->getSchoolClassCourseStudentBehaviors(new Criteria(), $con)->diff($schoolClassCourseStudentBehaviors);


        $this->schoolClassCourseStudentBehaviorsScheduledForDeletion = $schoolClassCourseStudentBehaviorsToDelete;

        foreach ($schoolClassCourseStudentBehaviorsToDelete as $schoolClassCourseStudentBehaviorRemoved) {
            $schoolClassCourseStudentBehaviorRemoved->setStudent(null);
        }

        $this->collSchoolClassCourseStudentBehaviors = null;
        foreach ($schoolClassCourseStudentBehaviors as $schoolClassCourseStudentBehavior) {
            $this->addSchoolClassCourseStudentBehavior($schoolClassCourseStudentBehavior);
        }

        $this->collSchoolClassCourseStudentBehaviors = $schoolClassCourseStudentBehaviors;
        $this->collSchoolClassCourseStudentBehaviorsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SchoolClassCourseStudentBehavior objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related SchoolClassCourseStudentBehavior objects.
     * @throws PropelException
     */
    public function countSchoolClassCourseStudentBehaviors(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSchoolClassCourseStudentBehaviorsPartial && !$this->isNew();
        if (null === $this->collSchoolClassCourseStudentBehaviors || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSchoolClassCourseStudentBehaviors) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSchoolClassCourseStudentBehaviors());
            }
            $query = SchoolClassCourseStudentBehaviorQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByStudent($this)
                ->count($con);
        }

        return count($this->collSchoolClassCourseStudentBehaviors);
    }

    /**
     * Method called to associate a SchoolClassCourseStudentBehavior object to this object
     * through the SchoolClassCourseStudentBehavior foreign key attribute.
     *
     * @param    SchoolClassCourseStudentBehavior $l SchoolClassCourseStudentBehavior
     * @return Student The current object (for fluent API support)
     */
    public function addSchoolClassCourseStudentBehavior(SchoolClassCourseStudentBehavior $l)
    {
        if ($this->collSchoolClassCourseStudentBehaviors === null) {
            $this->initSchoolClassCourseStudentBehaviors();
            $this->collSchoolClassCourseStudentBehaviorsPartial = true;
        }

        if (!in_array($l, $this->collSchoolClassCourseStudentBehaviors->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSchoolClassCourseStudentBehavior($l);

            if ($this->schoolClassCourseStudentBehaviorsScheduledForDeletion and $this->schoolClassCourseStudentBehaviorsScheduledForDeletion->contains($l)) {
                $this->schoolClassCourseStudentBehaviorsScheduledForDeletion->remove($this->schoolClassCourseStudentBehaviorsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	SchoolClassCourseStudentBehavior $schoolClassCourseStudentBehavior The schoolClassCourseStudentBehavior object to add.
     */
    protected function doAddSchoolClassCourseStudentBehavior($schoolClassCourseStudentBehavior)
    {
        $this->collSchoolClassCourseStudentBehaviors[]= $schoolClassCourseStudentBehavior;
        $schoolClassCourseStudentBehavior->setStudent($this);
    }

    /**
     * @param	SchoolClassCourseStudentBehavior $schoolClassCourseStudentBehavior The schoolClassCourseStudentBehavior object to remove.
     * @return Student The current object (for fluent API support)
     */
    public function removeSchoolClassCourseStudentBehavior($schoolClassCourseStudentBehavior)
    {
        if ($this->getSchoolClassCourseStudentBehaviors()->contains($schoolClassCourseStudentBehavior)) {
            $this->collSchoolClassCourseStudentBehaviors->remove($this->collSchoolClassCourseStudentBehaviors->search($schoolClassCourseStudentBehavior));
            if (null === $this->schoolClassCourseStudentBehaviorsScheduledForDeletion) {
                $this->schoolClassCourseStudentBehaviorsScheduledForDeletion = clone $this->collSchoolClassCourseStudentBehaviors;
                $this->schoolClassCourseStudentBehaviorsScheduledForDeletion->clear();
            }
            $this->schoolClassCourseStudentBehaviorsScheduledForDeletion[]= clone $schoolClassCourseStudentBehavior;
            $schoolClassCourseStudentBehavior->setStudent(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Student is new, it will return
     * an empty collection; or if this Student has previously
     * been saved, it will retrieve related SchoolClassCourseStudentBehaviors from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Student.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassCourseStudentBehavior[] List of SchoolClassCourseStudentBehavior objects
     */
    public function getSchoolClassCourseStudentBehaviorsJoinBehavior($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassCourseStudentBehaviorQuery::create(null, $criteria);
        $query->joinWith('Behavior', $join_behavior);

        return $this->getSchoolClassCourseStudentBehaviors($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Student is new, it will return
     * an empty collection; or if this Student has previously
     * been saved, it will retrieve related SchoolClassCourseStudentBehaviors from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Student.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassCourseStudentBehavior[] List of SchoolClassCourseStudentBehavior objects
     */
    public function getSchoolClassCourseStudentBehaviorsJoinSchoolClassCourse($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassCourseStudentBehaviorQuery::create(null, $criteria);
        $query->joinWith('SchoolClassCourse', $join_behavior);

        return $this->getSchoolClassCourseStudentBehaviors($query, $con);
    }

    /**
     * Clears out the collSchoolClassStudents collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Student The current object (for fluent API support)
     * @see        addSchoolClassStudents()
     */
    public function clearSchoolClassStudents()
    {
        $this->collSchoolClassStudents = null; // important to set this to null since that means it is uninitialized
        $this->collSchoolClassStudentsPartial = null;

        return $this;
    }

    /**
     * reset is the collSchoolClassStudents collection loaded partially
     *
     * @return void
     */
    public function resetPartialSchoolClassStudents($v = true)
    {
        $this->collSchoolClassStudentsPartial = $v;
    }

    /**
     * Initializes the collSchoolClassStudents collection.
     *
     * By default this just sets the collSchoolClassStudents collection to an empty array (like clearcollSchoolClassStudents());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSchoolClassStudents($overrideExisting = true)
    {
        if (null !== $this->collSchoolClassStudents && !$overrideExisting) {
            return;
        }
        $this->collSchoolClassStudents = new PropelObjectCollection();
        $this->collSchoolClassStudents->setModel('SchoolClassStudent');
    }

    /**
     * Gets an array of SchoolClassStudent objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Student is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|SchoolClassStudent[] List of SchoolClassStudent objects
     * @throws PropelException
     */
    public function getSchoolClassStudents($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSchoolClassStudentsPartial && !$this->isNew();
        if (null === $this->collSchoolClassStudents || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSchoolClassStudents) {
                // return empty collection
                $this->initSchoolClassStudents();
            } else {
                $collSchoolClassStudents = SchoolClassStudentQuery::create(null, $criteria)
                    ->filterByStudent($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSchoolClassStudentsPartial && count($collSchoolClassStudents)) {
                      $this->initSchoolClassStudents(false);

                      foreach ($collSchoolClassStudents as $obj) {
                        if (false == $this->collSchoolClassStudents->contains($obj)) {
                          $this->collSchoolClassStudents->append($obj);
                        }
                      }

                      $this->collSchoolClassStudentsPartial = true;
                    }

                    $collSchoolClassStudents->getInternalIterator()->rewind();

                    return $collSchoolClassStudents;
                }

                if ($partial && $this->collSchoolClassStudents) {
                    foreach ($this->collSchoolClassStudents as $obj) {
                        if ($obj->isNew()) {
                            $collSchoolClassStudents[] = $obj;
                        }
                    }
                }

                $this->collSchoolClassStudents = $collSchoolClassStudents;
                $this->collSchoolClassStudentsPartial = false;
            }
        }

        return $this->collSchoolClassStudents;
    }

    /**
     * Sets a collection of SchoolClassStudent objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $schoolClassStudents A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Student The current object (for fluent API support)
     */
    public function setSchoolClassStudents(PropelCollection $schoolClassStudents, PropelPDO $con = null)
    {
        $schoolClassStudentsToDelete = $this->getSchoolClassStudents(new Criteria(), $con)->diff($schoolClassStudents);


        $this->schoolClassStudentsScheduledForDeletion = $schoolClassStudentsToDelete;

        foreach ($schoolClassStudentsToDelete as $schoolClassStudentRemoved) {
            $schoolClassStudentRemoved->setStudent(null);
        }

        $this->collSchoolClassStudents = null;
        foreach ($schoolClassStudents as $schoolClassStudent) {
            $this->addSchoolClassStudent($schoolClassStudent);
        }

        $this->collSchoolClassStudents = $schoolClassStudents;
        $this->collSchoolClassStudentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SchoolClassStudent objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related SchoolClassStudent objects.
     * @throws PropelException
     */
    public function countSchoolClassStudents(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSchoolClassStudentsPartial && !$this->isNew();
        if (null === $this->collSchoolClassStudents || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSchoolClassStudents) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSchoolClassStudents());
            }
            $query = SchoolClassStudentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByStudent($this)
                ->count($con);
        }

        return count($this->collSchoolClassStudents);
    }

    /**
     * Method called to associate a SchoolClassStudent object to this object
     * through the SchoolClassStudent foreign key attribute.
     *
     * @param    SchoolClassStudent $l SchoolClassStudent
     * @return Student The current object (for fluent API support)
     */
    public function addSchoolClassStudent(SchoolClassStudent $l)
    {
        if ($this->collSchoolClassStudents === null) {
            $this->initSchoolClassStudents();
            $this->collSchoolClassStudentsPartial = true;
        }

        if (!in_array($l, $this->collSchoolClassStudents->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSchoolClassStudent($l);

            if ($this->schoolClassStudentsScheduledForDeletion and $this->schoolClassStudentsScheduledForDeletion->contains($l)) {
                $this->schoolClassStudentsScheduledForDeletion->remove($this->schoolClassStudentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	SchoolClassStudent $schoolClassStudent The schoolClassStudent object to add.
     */
    protected function doAddSchoolClassStudent($schoolClassStudent)
    {
        $this->collSchoolClassStudents[]= $schoolClassStudent;
        $schoolClassStudent->setStudent($this);
    }

    /**
     * @param	SchoolClassStudent $schoolClassStudent The schoolClassStudent object to remove.
     * @return Student The current object (for fluent API support)
     */
    public function removeSchoolClassStudent($schoolClassStudent)
    {
        if ($this->getSchoolClassStudents()->contains($schoolClassStudent)) {
            $this->collSchoolClassStudents->remove($this->collSchoolClassStudents->search($schoolClassStudent));
            if (null === $this->schoolClassStudentsScheduledForDeletion) {
                $this->schoolClassStudentsScheduledForDeletion = clone $this->collSchoolClassStudents;
                $this->schoolClassStudentsScheduledForDeletion->clear();
            }
            $this->schoolClassStudentsScheduledForDeletion[]= clone $schoolClassStudent;
            $schoolClassStudent->setStudent(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Student is new, it will return
     * an empty collection; or if this Student has previously
     * been saved, it will retrieve related SchoolClassStudents from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Student.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassStudent[] List of SchoolClassStudent objects
     */
    public function getSchoolClassStudentsJoinSchoolClass($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassStudentQuery::create(null, $criteria);
        $query->joinWith('SchoolClass', $join_behavior);

        return $this->getSchoolClassStudents($query, $con);
    }

    /**
     * Clears out the collSchoolEnrollments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Student The current object (for fluent API support)
     * @see        addSchoolEnrollments()
     */
    public function clearSchoolEnrollments()
    {
        $this->collSchoolEnrollments = null; // important to set this to null since that means it is uninitialized
        $this->collSchoolEnrollmentsPartial = null;

        return $this;
    }

    /**
     * reset is the collSchoolEnrollments collection loaded partially
     *
     * @return void
     */
    public function resetPartialSchoolEnrollments($v = true)
    {
        $this->collSchoolEnrollmentsPartial = $v;
    }

    /**
     * Initializes the collSchoolEnrollments collection.
     *
     * By default this just sets the collSchoolEnrollments collection to an empty array (like clearcollSchoolEnrollments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSchoolEnrollments($overrideExisting = true)
    {
        if (null !== $this->collSchoolEnrollments && !$overrideExisting) {
            return;
        }
        $this->collSchoolEnrollments = new PropelObjectCollection();
        $this->collSchoolEnrollments->setModel('SchoolEnrollment');
    }

    /**
     * Gets an array of SchoolEnrollment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Student is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|SchoolEnrollment[] List of SchoolEnrollment objects
     * @throws PropelException
     */
    public function getSchoolEnrollments($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSchoolEnrollmentsPartial && !$this->isNew();
        if (null === $this->collSchoolEnrollments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSchoolEnrollments) {
                // return empty collection
                $this->initSchoolEnrollments();
            } else {
                $collSchoolEnrollments = SchoolEnrollmentQuery::create(null, $criteria)
                    ->filterByStudent($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSchoolEnrollmentsPartial && count($collSchoolEnrollments)) {
                      $this->initSchoolEnrollments(false);

                      foreach ($collSchoolEnrollments as $obj) {
                        if (false == $this->collSchoolEnrollments->contains($obj)) {
                          $this->collSchoolEnrollments->append($obj);
                        }
                      }

                      $this->collSchoolEnrollmentsPartial = true;
                    }

                    $collSchoolEnrollments->getInternalIterator()->rewind();

                    return $collSchoolEnrollments;
                }

                if ($partial && $this->collSchoolEnrollments) {
                    foreach ($this->collSchoolEnrollments as $obj) {
                        if ($obj->isNew()) {
                            $collSchoolEnrollments[] = $obj;
                        }
                    }
                }

                $this->collSchoolEnrollments = $collSchoolEnrollments;
                $this->collSchoolEnrollmentsPartial = false;
            }
        }

        return $this->collSchoolEnrollments;
    }

    /**
     * Sets a collection of SchoolEnrollment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $schoolEnrollments A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Student The current object (for fluent API support)
     */
    public function setSchoolEnrollments(PropelCollection $schoolEnrollments, PropelPDO $con = null)
    {
        $schoolEnrollmentsToDelete = $this->getSchoolEnrollments(new Criteria(), $con)->diff($schoolEnrollments);


        $this->schoolEnrollmentsScheduledForDeletion = $schoolEnrollmentsToDelete;

        foreach ($schoolEnrollmentsToDelete as $schoolEnrollmentRemoved) {
            $schoolEnrollmentRemoved->setStudent(null);
        }

        $this->collSchoolEnrollments = null;
        foreach ($schoolEnrollments as $schoolEnrollment) {
            $this->addSchoolEnrollment($schoolEnrollment);
        }

        $this->collSchoolEnrollments = $schoolEnrollments;
        $this->collSchoolEnrollmentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SchoolEnrollment objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related SchoolEnrollment objects.
     * @throws PropelException
     */
    public function countSchoolEnrollments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSchoolEnrollmentsPartial && !$this->isNew();
        if (null === $this->collSchoolEnrollments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSchoolEnrollments) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSchoolEnrollments());
            }
            $query = SchoolEnrollmentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByStudent($this)
                ->count($con);
        }

        return count($this->collSchoolEnrollments);
    }

    /**
     * Method called to associate a SchoolEnrollment object to this object
     * through the SchoolEnrollment foreign key attribute.
     *
     * @param    SchoolEnrollment $l SchoolEnrollment
     * @return Student The current object (for fluent API support)
     */
    public function addSchoolEnrollment(SchoolEnrollment $l)
    {
        if ($this->collSchoolEnrollments === null) {
            $this->initSchoolEnrollments();
            $this->collSchoolEnrollmentsPartial = true;
        }

        if (!in_array($l, $this->collSchoolEnrollments->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSchoolEnrollment($l);

            if ($this->schoolEnrollmentsScheduledForDeletion and $this->schoolEnrollmentsScheduledForDeletion->contains($l)) {
                $this->schoolEnrollmentsScheduledForDeletion->remove($this->schoolEnrollmentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	SchoolEnrollment $schoolEnrollment The schoolEnrollment object to add.
     */
    protected function doAddSchoolEnrollment($schoolEnrollment)
    {
        $this->collSchoolEnrollments[]= $schoolEnrollment;
        $schoolEnrollment->setStudent($this);
    }

    /**
     * @param	SchoolEnrollment $schoolEnrollment The schoolEnrollment object to remove.
     * @return Student The current object (for fluent API support)
     */
    public function removeSchoolEnrollment($schoolEnrollment)
    {
        if ($this->getSchoolEnrollments()->contains($schoolEnrollment)) {
            $this->collSchoolEnrollments->remove($this->collSchoolEnrollments->search($schoolEnrollment));
            if (null === $this->schoolEnrollmentsScheduledForDeletion) {
                $this->schoolEnrollmentsScheduledForDeletion = clone $this->collSchoolEnrollments;
                $this->schoolEnrollmentsScheduledForDeletion->clear();
            }
            $this->schoolEnrollmentsScheduledForDeletion[]= $schoolEnrollment;
            $schoolEnrollment->setStudent(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Student is new, it will return
     * an empty collection; or if this Student has previously
     * been saved, it will retrieve related SchoolEnrollments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Student.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolEnrollment[] List of SchoolEnrollment objects
     */
    public function getSchoolEnrollmentsJoinSchool($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolEnrollmentQuery::create(null, $criteria);
        $query->joinWith('School', $join_behavior);

        return $this->getSchoolEnrollments($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Student is new, it will return
     * an empty collection; or if this Student has previously
     * been saved, it will retrieve related SchoolEnrollments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Student.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolEnrollment[] List of SchoolEnrollment objects
     */
    public function getSchoolEnrollmentsJoinSchoolYear($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolEnrollmentQuery::create(null, $criteria);
        $query->joinWith('SchoolYear', $join_behavior);

        return $this->getSchoolEnrollments($query, $con);
    }

    /**
     * Clears out the collStudentHistories collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Student The current object (for fluent API support)
     * @see        addStudentHistories()
     */
    public function clearStudentHistories()
    {
        $this->collStudentHistories = null; // important to set this to null since that means it is uninitialized
        $this->collStudentHistoriesPartial = null;

        return $this;
    }

    /**
     * reset is the collStudentHistories collection loaded partially
     *
     * @return void
     */
    public function resetPartialStudentHistories($v = true)
    {
        $this->collStudentHistoriesPartial = $v;
    }

    /**
     * Initializes the collStudentHistories collection.
     *
     * By default this just sets the collStudentHistories collection to an empty array (like clearcollStudentHistories());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStudentHistories($overrideExisting = true)
    {
        if (null !== $this->collStudentHistories && !$overrideExisting) {
            return;
        }
        $this->collStudentHistories = new PropelObjectCollection();
        $this->collStudentHistories->setModel('StudentHistory');
    }

    /**
     * Gets an array of StudentHistory objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Student is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|StudentHistory[] List of StudentHistory objects
     * @throws PropelException
     */
    public function getStudentHistories($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collStudentHistoriesPartial && !$this->isNew();
        if (null === $this->collStudentHistories || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collStudentHistories) {
                // return empty collection
                $this->initStudentHistories();
            } else {
                $collStudentHistories = StudentHistoryQuery::create(null, $criteria)
                    ->filterByStudent($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collStudentHistoriesPartial && count($collStudentHistories)) {
                      $this->initStudentHistories(false);

                      foreach ($collStudentHistories as $obj) {
                        if (false == $this->collStudentHistories->contains($obj)) {
                          $this->collStudentHistories->append($obj);
                        }
                      }

                      $this->collStudentHistoriesPartial = true;
                    }

                    $collStudentHistories->getInternalIterator()->rewind();

                    return $collStudentHistories;
                }

                if ($partial && $this->collStudentHistories) {
                    foreach ($this->collStudentHistories as $obj) {
                        if ($obj->isNew()) {
                            $collStudentHistories[] = $obj;
                        }
                    }
                }

                $this->collStudentHistories = $collStudentHistories;
                $this->collStudentHistoriesPartial = false;
            }
        }

        return $this->collStudentHistories;
    }

    /**
     * Sets a collection of StudentHistory objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $studentHistories A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Student The current object (for fluent API support)
     */
    public function setStudentHistories(PropelCollection $studentHistories, PropelPDO $con = null)
    {
        $studentHistoriesToDelete = $this->getStudentHistories(new Criteria(), $con)->diff($studentHistories);


        $this->studentHistoriesScheduledForDeletion = $studentHistoriesToDelete;

        foreach ($studentHistoriesToDelete as $studentHistoryRemoved) {
            $studentHistoryRemoved->setStudent(null);
        }

        $this->collStudentHistories = null;
        foreach ($studentHistories as $studentHistory) {
            $this->addStudentHistory($studentHistory);
        }

        $this->collStudentHistories = $studentHistories;
        $this->collStudentHistoriesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related StudentHistory objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related StudentHistory objects.
     * @throws PropelException
     */
    public function countStudentHistories(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collStudentHistoriesPartial && !$this->isNew();
        if (null === $this->collStudentHistories || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStudentHistories) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStudentHistories());
            }
            $query = StudentHistoryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByStudent($this)
                ->count($con);
        }

        return count($this->collStudentHistories);
    }

    /**
     * Method called to associate a StudentHistory object to this object
     * through the StudentHistory foreign key attribute.
     *
     * @param    StudentHistory $l StudentHistory
     * @return Student The current object (for fluent API support)
     */
    public function addStudentHistory(StudentHistory $l)
    {
        if ($this->collStudentHistories === null) {
            $this->initStudentHistories();
            $this->collStudentHistoriesPartial = true;
        }

        if (!in_array($l, $this->collStudentHistories->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddStudentHistory($l);

            if ($this->studentHistoriesScheduledForDeletion and $this->studentHistoriesScheduledForDeletion->contains($l)) {
                $this->studentHistoriesScheduledForDeletion->remove($this->studentHistoriesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	StudentHistory $studentHistory The studentHistory object to add.
     */
    protected function doAddStudentHistory($studentHistory)
    {
        $this->collStudentHistories[]= $studentHistory;
        $studentHistory->setStudent($this);
    }

    /**
     * @param	StudentHistory $studentHistory The studentHistory object to remove.
     * @return Student The current object (for fluent API support)
     */
    public function removeStudentHistory($studentHistory)
    {
        if ($this->getStudentHistories()->contains($studentHistory)) {
            $this->collStudentHistories->remove($this->collStudentHistories->search($studentHistory));
            if (null === $this->studentHistoriesScheduledForDeletion) {
                $this->studentHistoriesScheduledForDeletion = clone $this->collStudentHistories;
                $this->studentHistoriesScheduledForDeletion->clear();
            }
            $this->studentHistoriesScheduledForDeletion[]= clone $studentHistory;
            $studentHistory->setStudent(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->user_id = null;
        $this->application_id = null;
        $this->health_id = null;
        $this->student_nation_no = null;
        $this->student_no = null;
        $this->first_name = null;
        $this->middle_name = null;
        $this->last_name = null;
        $this->nick_name = null;
        $this->gender = null;
        $this->place_of_birth = null;
        $this->date_of_birth = null;
        $this->religion = null;
        $this->picture = null;
        $this->birth_certificate = null;
        $this->family_card = null;
        $this->graduation_certificate = null;
        $this->authorization_code = null;
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
            if ($this->collSchoolClassCourseStudentBehaviors) {
                foreach ($this->collSchoolClassCourseStudentBehaviors as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSchoolClassStudents) {
                foreach ($this->collSchoolClassStudents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSchoolEnrollments) {
                foreach ($this->collSchoolEnrollments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStudentHistories) {
                foreach ($this->collStudentHistories as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aUser instanceof Persistent) {
              $this->aUser->clearAllReferences($deep);
            }
            if ($this->aApplication instanceof Persistent) {
              $this->aApplication->clearAllReferences($deep);
            }
            if ($this->aSchoolHealth instanceof Persistent) {
              $this->aSchoolHealth->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collParentStudents instanceof PropelCollection) {
            $this->collParentStudents->clearIterator();
        }
        $this->collParentStudents = null;
        if ($this->collSchoolClassCourseStudentBehaviors instanceof PropelCollection) {
            $this->collSchoolClassCourseStudentBehaviors->clearIterator();
        }
        $this->collSchoolClassCourseStudentBehaviors = null;
        if ($this->collSchoolClassStudents instanceof PropelCollection) {
            $this->collSchoolClassStudents->clearIterator();
        }
        $this->collSchoolClassStudents = null;
        if ($this->collSchoolEnrollments instanceof PropelCollection) {
            $this->collSchoolEnrollments->clearIterator();
        }
        $this->collSchoolEnrollments = null;
        if ($this->collStudentHistories instanceof PropelCollection) {
            $this->collStudentHistories->clearIterator();
        }
        $this->collStudentHistories = null;
        $this->aUser = null;
        $this->aApplication = null;
        $this->aSchoolHealth = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(StudentPeer::DEFAULT_STRING_FORMAT);
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
     * @return     Student The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = StudentPeer::UPDATED_AT;

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
