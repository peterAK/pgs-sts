<?php

namespace PGS\CoreDomainBundle\Model\School\om;

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
use PGS\CoreDomainBundle\Model\Page;
use PGS\CoreDomainBundle\Model\PageQuery;
use PGS\CoreDomainBundle\Model\State;
use PGS\CoreDomainBundle\Model\StateQuery;
use PGS\CoreDomainBundle\Model\Application\Application;
use PGS\CoreDomainBundle\Model\Application\ApplicationQuery;
use PGS\CoreDomainBundle\Model\Course\Course;
use PGS\CoreDomainBundle\Model\Course\CourseQuery;
use PGS\CoreDomainBundle\Model\Employee\Employee;
use PGS\CoreDomainBundle\Model\Employee\EmployeeQuery;
use PGS\CoreDomainBundle\Model\Level\Level;
use PGS\CoreDomainBundle\Model\Level\LevelQuery;
use PGS\CoreDomainBundle\Model\Organization\Organization;
use PGS\CoreDomainBundle\Model\Organization\OrganizationQuery;
use PGS\CoreDomainBundle\Model\Qualification\Qualification;
use PGS\CoreDomainBundle\Model\Qualification\QualificationQuery;
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Model\School\SchoolI18n;
use PGS\CoreDomainBundle\Model\School\SchoolI18nQuery;
use PGS\CoreDomainBundle\Model\School\SchoolPeer;
use PGS\CoreDomainBundle\Model\School\SchoolQuery;
use PGS\CoreDomainBundle\Model\SchoolEmployment\SchoolEmployment;
use PGS\CoreDomainBundle\Model\SchoolEmployment\SchoolEmploymentQuery;
use PGS\CoreDomainBundle\Model\SchoolEnrollment\SchoolEnrollment;
use PGS\CoreDomainBundle\Model\SchoolEnrollment\SchoolEnrollmentQuery;
use PGS\CoreDomainBundle\Model\SchoolGradeLevel\SchoolGradeLevel;
use PGS\CoreDomainBundle\Model\SchoolGradeLevel\SchoolGradeLevelQuery;
use PGS\CoreDomainBundle\Model\SchoolTerm\SchoolTerm;
use PGS\CoreDomainBundle\Model\SchoolTerm\SchoolTermQuery;
use PGS\CoreDomainBundle\Model\SchoolTest\SchoolTest;
use PGS\CoreDomainBundle\Model\SchoolTest\SchoolTestQuery;
use PGS\CoreDomainBundle\Model\SchoolYear\SchoolYear;
use PGS\CoreDomainBundle\Model\SchoolYear\SchoolYearQuery;

abstract class BaseSchool extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'PGS\\CoreDomainBundle\\Model\\School\\SchoolPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        SchoolPeer
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
     * The value for the code field.
     * @var        string
     */
    protected $code;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the url field.
     * @var        string
     */
    protected $url;

    /**
     * The value for the level_id field.
     * @var        int
     */
    protected $level_id;

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
     * @var        int
     */
    protected $country_id;

    /**
     * The value for the organization_id field.
     * @var        int
     */
    protected $organization_id;

    /**
     * The value for the phone field.
     * @var        string
     */
    protected $phone;

    /**
     * The value for the fax field.
     * @var        string
     */
    protected $fax;

    /**
     * The value for the mobile field.
     * @var        string
     */
    protected $mobile;

    /**
     * The value for the email field.
     * @var        string
     */
    protected $email;

    /**
     * The value for the website field.
     * @var        string
     */
    protected $website;

    /**
     * The value for the logo field.
     * @var        string
     */
    protected $logo;

    /**
     * The value for the status field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $status;

    /**
     * The value for the confirmation field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $confirmation;

    /**
     * The value for the sortable_rank field.
     * @var        int
     */
    protected $sortable_rank;

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
     * @var        State
     */
    protected $aState;

    /**
     * @var        Country
     */
    protected $aCountry;

    /**
     * @var        Organization
     */
    protected $aOrganization;

    /**
     * @var        Level
     */
    protected $aLevel;

    /**
     * @var        PropelObjectCollection|Application[] Collection to store aggregation of Application objects.
     */
    protected $collApplications;
    protected $collApplicationsPartial;

    /**
     * @var        PropelObjectCollection|Course[] Collection to store aggregation of Course objects.
     */
    protected $collCourses;
    protected $collCoursesPartial;

    /**
     * @var        PropelObjectCollection|Page[] Collection to store aggregation of Page objects.
     */
    protected $collPages;
    protected $collPagesPartial;

    /**
     * @var        PropelObjectCollection|Employee[] Collection to store aggregation of Employee objects.
     */
    protected $collEmployees;
    protected $collEmployeesPartial;

    /**
     * @var        PropelObjectCollection|Qualification[] Collection to store aggregation of Qualification objects.
     */
    protected $collQualifications;
    protected $collQualificationsPartial;

    /**
     * @var        PropelObjectCollection|SchoolEmployment[] Collection to store aggregation of SchoolEmployment objects.
     */
    protected $collSchoolEmployments;
    protected $collSchoolEmploymentsPartial;

    /**
     * @var        PropelObjectCollection|SchoolEnrollment[] Collection to store aggregation of SchoolEnrollment objects.
     */
    protected $collSchoolEnrollments;
    protected $collSchoolEnrollmentsPartial;

    /**
     * @var        PropelObjectCollection|SchoolGradeLevel[] Collection to store aggregation of SchoolGradeLevel objects.
     */
    protected $collSchoolGradeLevels;
    protected $collSchoolGradeLevelsPartial;

    /**
     * @var        PropelObjectCollection|SchoolTerm[] Collection to store aggregation of SchoolTerm objects.
     */
    protected $collSchoolTerms;
    protected $collSchoolTermsPartial;

    /**
     * @var        PropelObjectCollection|SchoolTest[] Collection to store aggregation of SchoolTest objects.
     */
    protected $collSchoolTests;
    protected $collSchoolTestsPartial;

    /**
     * @var        PropelObjectCollection|SchoolYear[] Collection to store aggregation of SchoolYear objects.
     */
    protected $collSchoolYears;
    protected $collSchoolYearsPartial;

    /**
     * @var        PropelObjectCollection|SchoolI18n[] Collection to store aggregation of SchoolI18n objects.
     */
    protected $collSchoolI18ns;
    protected $collSchoolI18nsPartial;

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

    // i18n behavior

    /**
     * Current locale
     * @var        string
     */
    protected $currentLocale = 'en_US';

    /**
     * Current translation objects
     * @var        array[SchoolI18n]
     */
    protected $currentTranslations;

    // sortable behavior

    /**
     * Queries to be executed in the save transaction
     * @var        array
     */
    protected $sortableQueries = array();

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $applicationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $coursesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pagesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $employeesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $qualificationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $schoolEmploymentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $schoolEnrollmentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $schoolGradeLevelsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $schoolTermsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $schoolTestsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $schoolYearsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $schoolI18nsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->status = 0;
        $this->confirmation = 0;
    }

    /**
     * Initializes internal state of BaseSchool object.
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
     * Get the [code] column value.
     *
     * @return string
     */
    public function getCode()
    {

        return $this->code;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {

        return $this->name;
    }

    /**
     * Get the [url] column value.
     *
     * @return string
     */
    public function getUrl()
    {

        return $this->url;
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
     * Get the [organization_id] column value.
     *
     * @return int
     */
    public function getOrganizationId()
    {

        return $this->organization_id;
    }

    /**
     * Get the [phone] column value.
     *
     * @return string
     */
    public function getPhone()
    {

        return $this->phone;
    }

    /**
     * Get the [fax] column value.
     *
     * @return string
     */
    public function getFax()
    {

        return $this->fax;
    }

    /**
     * Get the [mobile] column value.
     *
     * @return string
     */
    public function getMobile()
    {

        return $this->mobile;
    }

    /**
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {

        return $this->email;
    }

    /**
     * Get the [website] column value.
     *
     * @return string
     */
    public function getWebsite()
    {

        return $this->website;
    }

    /**
     * Get the [logo] column value.
     *
     * @return string
     */
    public function getLogo()
    {

        return $this->logo;
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
        $valueSet = SchoolPeer::getValueSet(SchoolPeer::STATUS);
        if (!isset($valueSet[$this->status])) {
            throw new PropelException('Unknown stored enum key: ' . $this->status);
        }

        return $valueSet[$this->status];
    }

    /**
     * Get the [confirmation] column value.
     *
     * @return int
     * @throws PropelException - if the stored enum key is unknown.
     */
    public function getConfirmation()
    {
        if (null === $this->confirmation) {
            return null;
        }
        $valueSet = SchoolPeer::getValueSet(SchoolPeer::CONFIRMATION);
        if (!isset($valueSet[$this->confirmation])) {
            throw new PropelException('Unknown stored enum key: ' . $this->confirmation);
        }

        return $valueSet[$this->confirmation];
    }

    /**
     * Get the [sortable_rank] column value.
     *
     * @return int
     */
    public function getSortableRank()
    {

        return $this->sortable_rank;
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
     * @return School The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = SchoolPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [code] column.
     *
     * @param  string $v new value
     * @return School The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[] = SchoolPeer::CODE;
        }


        return $this;
    } // setCode()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return School The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = SchoolPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [url] column.
     *
     * @param  string $v new value
     * @return School The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->url !== $v) {
            $this->url = $v;
            $this->modifiedColumns[] = SchoolPeer::URL;
        }


        return $this;
    } // setUrl()

    /**
     * Set the value of [level_id] column.
     *
     * @param  int $v new value
     * @return School The current object (for fluent API support)
     */
    public function setLevelId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->level_id !== $v) {
            $this->level_id = $v;
            $this->modifiedColumns[] = SchoolPeer::LEVEL_ID;
        }

        if ($this->aLevel !== null && $this->aLevel->getId() !== $v) {
            $this->aLevel = null;
        }


        return $this;
    } // setLevelId()

    /**
     * Set the value of [address] column.
     *
     * @param  string $v new value
     * @return School The current object (for fluent API support)
     */
    public function setAddress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->address !== $v) {
            $this->address = $v;
            $this->modifiedColumns[] = SchoolPeer::ADDRESS;
        }


        return $this;
    } // setAddress()

    /**
     * Set the value of [city] column.
     *
     * @param  string $v new value
     * @return School The current object (for fluent API support)
     */
    public function setCity($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->city !== $v) {
            $this->city = $v;
            $this->modifiedColumns[] = SchoolPeer::CITY;
        }


        return $this;
    } // setCity()

    /**
     * Set the value of [state_id] column.
     *
     * @param  int $v new value
     * @return School The current object (for fluent API support)
     */
    public function setStateId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->state_id !== $v) {
            $this->state_id = $v;
            $this->modifiedColumns[] = SchoolPeer::STATE_ID;
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
     * @return School The current object (for fluent API support)
     */
    public function setZip($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->zip !== $v) {
            $this->zip = $v;
            $this->modifiedColumns[] = SchoolPeer::ZIP;
        }


        return $this;
    } // setZip()

    /**
     * Set the value of [country_id] column.
     *
     * @param  int $v new value
     * @return School The current object (for fluent API support)
     */
    public function setCountryId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->country_id !== $v) {
            $this->country_id = $v;
            $this->modifiedColumns[] = SchoolPeer::COUNTRY_ID;
        }

        if ($this->aCountry !== null && $this->aCountry->getId() !== $v) {
            $this->aCountry = null;
        }


        return $this;
    } // setCountryId()

    /**
     * Set the value of [organization_id] column.
     *
     * @param  int $v new value
     * @return School The current object (for fluent API support)
     */
    public function setOrganizationId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->organization_id !== $v) {
            $this->organization_id = $v;
            $this->modifiedColumns[] = SchoolPeer::ORGANIZATION_ID;
        }

        if ($this->aOrganization !== null && $this->aOrganization->getId() !== $v) {
            $this->aOrganization = null;
        }


        return $this;
    } // setOrganizationId()

    /**
     * Set the value of [phone] column.
     *
     * @param  string $v new value
     * @return School The current object (for fluent API support)
     */
    public function setPhone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->phone !== $v) {
            $this->phone = $v;
            $this->modifiedColumns[] = SchoolPeer::PHONE;
        }


        return $this;
    } // setPhone()

    /**
     * Set the value of [fax] column.
     *
     * @param  string $v new value
     * @return School The current object (for fluent API support)
     */
    public function setFax($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->fax !== $v) {
            $this->fax = $v;
            $this->modifiedColumns[] = SchoolPeer::FAX;
        }


        return $this;
    } // setFax()

    /**
     * Set the value of [mobile] column.
     *
     * @param  string $v new value
     * @return School The current object (for fluent API support)
     */
    public function setMobile($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->mobile !== $v) {
            $this->mobile = $v;
            $this->modifiedColumns[] = SchoolPeer::MOBILE;
        }


        return $this;
    } // setMobile()

    /**
     * Set the value of [email] column.
     *
     * @param  string $v new value
     * @return School The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[] = SchoolPeer::EMAIL;
        }


        return $this;
    } // setEmail()

    /**
     * Set the value of [website] column.
     *
     * @param  string $v new value
     * @return School The current object (for fluent API support)
     */
    public function setWebsite($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->website !== $v) {
            $this->website = $v;
            $this->modifiedColumns[] = SchoolPeer::WEBSITE;
        }


        return $this;
    } // setWebsite()

    /**
     * Set the value of [logo] column.
     *
     * @param  string $v new value
     * @return School The current object (for fluent API support)
     */
    public function setLogo($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->logo !== $v) {
            $this->logo = $v;
            $this->modifiedColumns[] = SchoolPeer::LOGO;
        }


        return $this;
    } // setLogo()

    /**
     * Set the value of [status] column.
     *
     * @param  int $v new value
     * @return School The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            $valueSet = SchoolPeer::getValueSet(SchoolPeer::STATUS);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[] = SchoolPeer::STATUS;
        }


        return $this;
    } // setStatus()

    /**
     * Set the value of [confirmation] column.
     *
     * @param  int $v new value
     * @return School The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setConfirmation($v)
    {
        if ($v !== null) {
            $valueSet = SchoolPeer::getValueSet(SchoolPeer::CONFIRMATION);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->confirmation !== $v) {
            $this->confirmation = $v;
            $this->modifiedColumns[] = SchoolPeer::CONFIRMATION;
        }


        return $this;
    } // setConfirmation()

    /**
     * Set the value of [sortable_rank] column.
     *
     * @param  int $v new value
     * @return School The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[] = SchoolPeer::SORTABLE_RANK;
        }


        return $this;
    } // setSortableRank()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return School The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = SchoolPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return School The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = SchoolPeer::UPDATED_AT;
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
            if ($this->status !== 0) {
                return false;
            }

            if ($this->confirmation !== 0) {
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
            $this->code = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->url = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->level_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->address = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->city = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->state_id = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->zip = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->country_id = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->organization_id = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
            $this->phone = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->fax = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
            $this->mobile = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
            $this->email = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
            $this->website = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
            $this->logo = ($row[$startcol + 16] !== null) ? (string) $row[$startcol + 16] : null;
            $this->status = ($row[$startcol + 17] !== null) ? (int) $row[$startcol + 17] : null;
            $this->confirmation = ($row[$startcol + 18] !== null) ? (int) $row[$startcol + 18] : null;
            $this->sortable_rank = ($row[$startcol + 19] !== null) ? (int) $row[$startcol + 19] : null;
            $this->created_at = ($row[$startcol + 20] !== null) ? (string) $row[$startcol + 20] : null;
            $this->updated_at = ($row[$startcol + 21] !== null) ? (string) $row[$startcol + 21] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 22; // 22 = SchoolPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating School object", $e);
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
        if ($this->aState !== null && $this->state_id !== $this->aState->getId()) {
            $this->aState = null;
        }
        if ($this->aCountry !== null && $this->country_id !== $this->aCountry->getId()) {
            $this->aCountry = null;
        }
        if ($this->aOrganization !== null && $this->organization_id !== $this->aOrganization->getId()) {
            $this->aOrganization = null;
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
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = SchoolPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aState = null;
            $this->aCountry = null;
            $this->aOrganization = null;
            $this->aLevel = null;
            $this->collApplications = null;

            $this->collCourses = null;

            $this->collPages = null;

            $this->collEmployees = null;

            $this->collQualifications = null;

            $this->collSchoolEmployments = null;

            $this->collSchoolEnrollments = null;

            $this->collSchoolGradeLevels = null;

            $this->collSchoolTerms = null;

            $this->collSchoolTests = null;

            $this->collSchoolYears = null;

            $this->collSchoolI18ns = null;

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
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
            $deleteQuery = SchoolQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // sortable behavior

            SchoolPeer::shiftRank(-1, $this->getSortableRank() + 1, null, $con);
            SchoolPeer::clearInstancePool();

            // sortable_relation behavior
            $this->moveRelatedQualificationsToNullScope($con);

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
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // sluggable behavior

            if ($this->isColumnModified(SchoolPeer::URL) && $this->getUrl()) {
                $this->setUrl($this->makeSlugUnique($this->getUrl()));
            } elseif (!$this->getUrl()) {
                $this->setUrl($this->createSlug());
            }
            // sortable behavior
            $this->processSortableQueries($con);
            // event behavior
            EventDispatcherProxy::trigger('model.save.pre', new ModelEvent($this));
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // sortable behavior
                if (!$this->isColumnModified(SchoolPeer::RANK_COL)) {
                    $this->setSortableRank(SchoolQuery::create()->getMaxRankArray($con) + 1);
                }

                // timestampable behavior
                if (!$this->isColumnModified(SchoolPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(SchoolPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(SchoolPeer::UPDATED_AT)) {
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
                SchoolPeer::addInstanceToPool($this);
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

            if ($this->aOrganization !== null) {
                if ($this->aOrganization->isModified() || $this->aOrganization->isNew()) {
                    $affectedRows += $this->aOrganization->save($con);
                }
                $this->setOrganization($this->aOrganization);
            }

            if ($this->aLevel !== null) {
                if ($this->aLevel->isModified() || $this->aLevel->isNew()) {
                    $affectedRows += $this->aLevel->save($con);
                }
                $this->setLevel($this->aLevel);
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

            if ($this->applicationsScheduledForDeletion !== null) {
                if (!$this->applicationsScheduledForDeletion->isEmpty()) {
                    ApplicationQuery::create()
                        ->filterByPrimaryKeys($this->applicationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->applicationsScheduledForDeletion = null;
                }
            }

            if ($this->collApplications !== null) {
                foreach ($this->collApplications as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->coursesScheduledForDeletion !== null) {
                if (!$this->coursesScheduledForDeletion->isEmpty()) {
                    foreach ($this->coursesScheduledForDeletion as $course) {
                        // need to save related object because we set the relation to null
                        $course->save($con);
                    }
                    $this->coursesScheduledForDeletion = null;
                }
            }

            if ($this->collCourses !== null) {
                foreach ($this->collCourses as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pagesScheduledForDeletion !== null) {
                if (!$this->pagesScheduledForDeletion->isEmpty()) {
                    PageQuery::create()
                        ->filterByPrimaryKeys($this->pagesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pagesScheduledForDeletion = null;
                }
            }

            if ($this->collPages !== null) {
                foreach ($this->collPages as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->employeesScheduledForDeletion !== null) {
                if (!$this->employeesScheduledForDeletion->isEmpty()) {
                    EmployeeQuery::create()
                        ->filterByPrimaryKeys($this->employeesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->employeesScheduledForDeletion = null;
                }
            }

            if ($this->collEmployees !== null) {
                foreach ($this->collEmployees as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->qualificationsScheduledForDeletion !== null) {
                if (!$this->qualificationsScheduledForDeletion->isEmpty()) {
                    foreach ($this->qualificationsScheduledForDeletion as $qualification) {
                        // need to save related object because we set the relation to null
                        $qualification->save($con);
                    }
                    $this->qualificationsScheduledForDeletion = null;
                }
            }

            if ($this->collQualifications !== null) {
                foreach ($this->collQualifications as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->schoolEmploymentsScheduledForDeletion !== null) {
                if (!$this->schoolEmploymentsScheduledForDeletion->isEmpty()) {
                    foreach ($this->schoolEmploymentsScheduledForDeletion as $schoolEmployment) {
                        // need to save related object because we set the relation to null
                        $schoolEmployment->save($con);
                    }
                    $this->schoolEmploymentsScheduledForDeletion = null;
                }
            }

            if ($this->collSchoolEmployments !== null) {
                foreach ($this->collSchoolEmployments as $referrerFK) {
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

            if ($this->schoolGradeLevelsScheduledForDeletion !== null) {
                if (!$this->schoolGradeLevelsScheduledForDeletion->isEmpty()) {
                    SchoolGradeLevelQuery::create()
                        ->filterByPrimaryKeys($this->schoolGradeLevelsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->schoolGradeLevelsScheduledForDeletion = null;
                }
            }

            if ($this->collSchoolGradeLevels !== null) {
                foreach ($this->collSchoolGradeLevels as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->schoolTermsScheduledForDeletion !== null) {
                if (!$this->schoolTermsScheduledForDeletion->isEmpty()) {
                    SchoolTermQuery::create()
                        ->filterByPrimaryKeys($this->schoolTermsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->schoolTermsScheduledForDeletion = null;
                }
            }

            if ($this->collSchoolTerms !== null) {
                foreach ($this->collSchoolTerms as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->schoolTestsScheduledForDeletion !== null) {
                if (!$this->schoolTestsScheduledForDeletion->isEmpty()) {
                    SchoolTestQuery::create()
                        ->filterByPrimaryKeys($this->schoolTestsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->schoolTestsScheduledForDeletion = null;
                }
            }

            if ($this->collSchoolTests !== null) {
                foreach ($this->collSchoolTests as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->schoolYearsScheduledForDeletion !== null) {
                if (!$this->schoolYearsScheduledForDeletion->isEmpty()) {
                    SchoolYearQuery::create()
                        ->filterByPrimaryKeys($this->schoolYearsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->schoolYearsScheduledForDeletion = null;
                }
            }

            if ($this->collSchoolYears !== null) {
                foreach ($this->collSchoolYears as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->schoolI18nsScheduledForDeletion !== null) {
                if (!$this->schoolI18nsScheduledForDeletion->isEmpty()) {
                    SchoolI18nQuery::create()
                        ->filterByPrimaryKeys($this->schoolI18nsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->schoolI18nsScheduledForDeletion = null;
                }
            }

            if ($this->collSchoolI18ns !== null) {
                foreach ($this->collSchoolI18ns as $referrerFK) {
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

        $this->modifiedColumns[] = SchoolPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . SchoolPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(SchoolPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(SchoolPeer::CODE)) {
            $modifiedColumns[':p' . $index++]  = '`code`';
        }
        if ($this->isColumnModified(SchoolPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(SchoolPeer::URL)) {
            $modifiedColumns[':p' . $index++]  = '`url`';
        }
        if ($this->isColumnModified(SchoolPeer::LEVEL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`level_id`';
        }
        if ($this->isColumnModified(SchoolPeer::ADDRESS)) {
            $modifiedColumns[':p' . $index++]  = '`address`';
        }
        if ($this->isColumnModified(SchoolPeer::CITY)) {
            $modifiedColumns[':p' . $index++]  = '`city`';
        }
        if ($this->isColumnModified(SchoolPeer::STATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`state_id`';
        }
        if ($this->isColumnModified(SchoolPeer::ZIP)) {
            $modifiedColumns[':p' . $index++]  = '`zip`';
        }
        if ($this->isColumnModified(SchoolPeer::COUNTRY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`country_id`';
        }
        if ($this->isColumnModified(SchoolPeer::ORGANIZATION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`organization_id`';
        }
        if ($this->isColumnModified(SchoolPeer::PHONE)) {
            $modifiedColumns[':p' . $index++]  = '`phone`';
        }
        if ($this->isColumnModified(SchoolPeer::FAX)) {
            $modifiedColumns[':p' . $index++]  = '`fax`';
        }
        if ($this->isColumnModified(SchoolPeer::MOBILE)) {
            $modifiedColumns[':p' . $index++]  = '`mobile`';
        }
        if ($this->isColumnModified(SchoolPeer::EMAIL)) {
            $modifiedColumns[':p' . $index++]  = '`email`';
        }
        if ($this->isColumnModified(SchoolPeer::WEBSITE)) {
            $modifiedColumns[':p' . $index++]  = '`website`';
        }
        if ($this->isColumnModified(SchoolPeer::LOGO)) {
            $modifiedColumns[':p' . $index++]  = '`logo`';
        }
        if ($this->isColumnModified(SchoolPeer::STATUS)) {
            $modifiedColumns[':p' . $index++]  = '`status`';
        }
        if ($this->isColumnModified(SchoolPeer::CONFIRMATION)) {
            $modifiedColumns[':p' . $index++]  = '`confirmation`';
        }
        if ($this->isColumnModified(SchoolPeer::SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = '`sortable_rank`';
        }
        if ($this->isColumnModified(SchoolPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(SchoolPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `school` (%s) VALUES (%s)',
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
                    case '`code`':
                        $stmt->bindValue($identifier, $this->code, PDO::PARAM_STR);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`url`':
                        $stmt->bindValue($identifier, $this->url, PDO::PARAM_STR);
                        break;
                    case '`level_id`':
                        $stmt->bindValue($identifier, $this->level_id, PDO::PARAM_INT);
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
                    case '`organization_id`':
                        $stmt->bindValue($identifier, $this->organization_id, PDO::PARAM_INT);
                        break;
                    case '`phone`':
                        $stmt->bindValue($identifier, $this->phone, PDO::PARAM_STR);
                        break;
                    case '`fax`':
                        $stmt->bindValue($identifier, $this->fax, PDO::PARAM_STR);
                        break;
                    case '`mobile`':
                        $stmt->bindValue($identifier, $this->mobile, PDO::PARAM_STR);
                        break;
                    case '`email`':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case '`website`':
                        $stmt->bindValue($identifier, $this->website, PDO::PARAM_STR);
                        break;
                    case '`logo`':
                        $stmt->bindValue($identifier, $this->logo, PDO::PARAM_STR);
                        break;
                    case '`status`':
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_INT);
                        break;
                    case '`confirmation`':
                        $stmt->bindValue($identifier, $this->confirmation, PDO::PARAM_INT);
                        break;
                    case '`sortable_rank`':
                        $stmt->bindValue($identifier, $this->sortable_rank, PDO::PARAM_INT);
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

            if ($this->aOrganization !== null) {
                if (!$this->aOrganization->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aOrganization->getValidationFailures());
                }
            }

            if ($this->aLevel !== null) {
                if (!$this->aLevel->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aLevel->getValidationFailures());
                }
            }


            if (($retval = SchoolPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collApplications !== null) {
                    foreach ($this->collApplications as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCourses !== null) {
                    foreach ($this->collCourses as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPages !== null) {
                    foreach ($this->collPages as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collEmployees !== null) {
                    foreach ($this->collEmployees as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collQualifications !== null) {
                    foreach ($this->collQualifications as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSchoolEmployments !== null) {
                    foreach ($this->collSchoolEmployments as $referrerFK) {
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

                if ($this->collSchoolGradeLevels !== null) {
                    foreach ($this->collSchoolGradeLevels as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSchoolTerms !== null) {
                    foreach ($this->collSchoolTerms as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSchoolTests !== null) {
                    foreach ($this->collSchoolTests as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSchoolYears !== null) {
                    foreach ($this->collSchoolYears as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSchoolI18ns !== null) {
                    foreach ($this->collSchoolI18ns as $referrerFK) {
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
        $pos = SchoolPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getCode();
                break;
            case 2:
                return $this->getName();
                break;
            case 3:
                return $this->getUrl();
                break;
            case 4:
                return $this->getLevelId();
                break;
            case 5:
                return $this->getAddress();
                break;
            case 6:
                return $this->getCity();
                break;
            case 7:
                return $this->getStateId();
                break;
            case 8:
                return $this->getZip();
                break;
            case 9:
                return $this->getCountryId();
                break;
            case 10:
                return $this->getOrganizationId();
                break;
            case 11:
                return $this->getPhone();
                break;
            case 12:
                return $this->getFax();
                break;
            case 13:
                return $this->getMobile();
                break;
            case 14:
                return $this->getEmail();
                break;
            case 15:
                return $this->getWebsite();
                break;
            case 16:
                return $this->getLogo();
                break;
            case 17:
                return $this->getStatus();
                break;
            case 18:
                return $this->getConfirmation();
                break;
            case 19:
                return $this->getSortableRank();
                break;
            case 20:
                return $this->getCreatedAt();
                break;
            case 21:
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
        if (isset($alreadyDumpedObjects['School'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['School'][$this->getPrimaryKey()] = true;
        $keys = SchoolPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getCode(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getUrl(),
            $keys[4] => $this->getLevelId(),
            $keys[5] => $this->getAddress(),
            $keys[6] => $this->getCity(),
            $keys[7] => $this->getStateId(),
            $keys[8] => $this->getZip(),
            $keys[9] => $this->getCountryId(),
            $keys[10] => $this->getOrganizationId(),
            $keys[11] => $this->getPhone(),
            $keys[12] => $this->getFax(),
            $keys[13] => $this->getMobile(),
            $keys[14] => $this->getEmail(),
            $keys[15] => $this->getWebsite(),
            $keys[16] => $this->getLogo(),
            $keys[17] => $this->getStatus(),
            $keys[18] => $this->getConfirmation(),
            $keys[19] => $this->getSortableRank(),
            $keys[20] => $this->getCreatedAt(),
            $keys[21] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aState) {
                $result['State'] = $this->aState->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCountry) {
                $result['Country'] = $this->aCountry->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aOrganization) {
                $result['Organization'] = $this->aOrganization->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aLevel) {
                $result['Level'] = $this->aLevel->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collApplications) {
                $result['Applications'] = $this->collApplications->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCourses) {
                $result['Courses'] = $this->collCourses->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPages) {
                $result['Pages'] = $this->collPages->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEmployees) {
                $result['Employees'] = $this->collEmployees->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collQualifications) {
                $result['Qualifications'] = $this->collQualifications->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSchoolEmployments) {
                $result['SchoolEmployments'] = $this->collSchoolEmployments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSchoolEnrollments) {
                $result['SchoolEnrollments'] = $this->collSchoolEnrollments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSchoolGradeLevels) {
                $result['SchoolGradeLevels'] = $this->collSchoolGradeLevels->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSchoolTerms) {
                $result['SchoolTerms'] = $this->collSchoolTerms->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSchoolTests) {
                $result['SchoolTests'] = $this->collSchoolTests->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSchoolYears) {
                $result['SchoolYears'] = $this->collSchoolYears->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSchoolI18ns) {
                $result['SchoolI18ns'] = $this->collSchoolI18ns->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = SchoolPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setCode($value);
                break;
            case 2:
                $this->setName($value);
                break;
            case 3:
                $this->setUrl($value);
                break;
            case 4:
                $this->setLevelId($value);
                break;
            case 5:
                $this->setAddress($value);
                break;
            case 6:
                $this->setCity($value);
                break;
            case 7:
                $this->setStateId($value);
                break;
            case 8:
                $this->setZip($value);
                break;
            case 9:
                $this->setCountryId($value);
                break;
            case 10:
                $this->setOrganizationId($value);
                break;
            case 11:
                $this->setPhone($value);
                break;
            case 12:
                $this->setFax($value);
                break;
            case 13:
                $this->setMobile($value);
                break;
            case 14:
                $this->setEmail($value);
                break;
            case 15:
                $this->setWebsite($value);
                break;
            case 16:
                $this->setLogo($value);
                break;
            case 17:
                $valueSet = SchoolPeer::getValueSet(SchoolPeer::STATUS);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setStatus($value);
                break;
            case 18:
                $valueSet = SchoolPeer::getValueSet(SchoolPeer::CONFIRMATION);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setConfirmation($value);
                break;
            case 19:
                $this->setSortableRank($value);
                break;
            case 20:
                $this->setCreatedAt($value);
                break;
            case 21:
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
        $keys = SchoolPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setCode($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setName($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setUrl($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setLevelId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setAddress($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setCity($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setStateId($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setZip($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setCountryId($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setOrganizationId($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setPhone($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setFax($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setMobile($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setEmail($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setWebsite($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setLogo($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setStatus($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setConfirmation($arr[$keys[18]]);
        if (array_key_exists($keys[19], $arr)) $this->setSortableRank($arr[$keys[19]]);
        if (array_key_exists($keys[20], $arr)) $this->setCreatedAt($arr[$keys[20]]);
        if (array_key_exists($keys[21], $arr)) $this->setUpdatedAt($arr[$keys[21]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(SchoolPeer::DATABASE_NAME);

        if ($this->isColumnModified(SchoolPeer::ID)) $criteria->add(SchoolPeer::ID, $this->id);
        if ($this->isColumnModified(SchoolPeer::CODE)) $criteria->add(SchoolPeer::CODE, $this->code);
        if ($this->isColumnModified(SchoolPeer::NAME)) $criteria->add(SchoolPeer::NAME, $this->name);
        if ($this->isColumnModified(SchoolPeer::URL)) $criteria->add(SchoolPeer::URL, $this->url);
        if ($this->isColumnModified(SchoolPeer::LEVEL_ID)) $criteria->add(SchoolPeer::LEVEL_ID, $this->level_id);
        if ($this->isColumnModified(SchoolPeer::ADDRESS)) $criteria->add(SchoolPeer::ADDRESS, $this->address);
        if ($this->isColumnModified(SchoolPeer::CITY)) $criteria->add(SchoolPeer::CITY, $this->city);
        if ($this->isColumnModified(SchoolPeer::STATE_ID)) $criteria->add(SchoolPeer::STATE_ID, $this->state_id);
        if ($this->isColumnModified(SchoolPeer::ZIP)) $criteria->add(SchoolPeer::ZIP, $this->zip);
        if ($this->isColumnModified(SchoolPeer::COUNTRY_ID)) $criteria->add(SchoolPeer::COUNTRY_ID, $this->country_id);
        if ($this->isColumnModified(SchoolPeer::ORGANIZATION_ID)) $criteria->add(SchoolPeer::ORGANIZATION_ID, $this->organization_id);
        if ($this->isColumnModified(SchoolPeer::PHONE)) $criteria->add(SchoolPeer::PHONE, $this->phone);
        if ($this->isColumnModified(SchoolPeer::FAX)) $criteria->add(SchoolPeer::FAX, $this->fax);
        if ($this->isColumnModified(SchoolPeer::MOBILE)) $criteria->add(SchoolPeer::MOBILE, $this->mobile);
        if ($this->isColumnModified(SchoolPeer::EMAIL)) $criteria->add(SchoolPeer::EMAIL, $this->email);
        if ($this->isColumnModified(SchoolPeer::WEBSITE)) $criteria->add(SchoolPeer::WEBSITE, $this->website);
        if ($this->isColumnModified(SchoolPeer::LOGO)) $criteria->add(SchoolPeer::LOGO, $this->logo);
        if ($this->isColumnModified(SchoolPeer::STATUS)) $criteria->add(SchoolPeer::STATUS, $this->status);
        if ($this->isColumnModified(SchoolPeer::CONFIRMATION)) $criteria->add(SchoolPeer::CONFIRMATION, $this->confirmation);
        if ($this->isColumnModified(SchoolPeer::SORTABLE_RANK)) $criteria->add(SchoolPeer::SORTABLE_RANK, $this->sortable_rank);
        if ($this->isColumnModified(SchoolPeer::CREATED_AT)) $criteria->add(SchoolPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(SchoolPeer::UPDATED_AT)) $criteria->add(SchoolPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(SchoolPeer::DATABASE_NAME);
        $criteria->add(SchoolPeer::ID, $this->id);

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
     * @param object $copyObj An object of School (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setCode($this->getCode());
        $copyObj->setName($this->getName());
        $copyObj->setUrl($this->getUrl());
        $copyObj->setLevelId($this->getLevelId());
        $copyObj->setAddress($this->getAddress());
        $copyObj->setCity($this->getCity());
        $copyObj->setStateId($this->getStateId());
        $copyObj->setZip($this->getZip());
        $copyObj->setCountryId($this->getCountryId());
        $copyObj->setOrganizationId($this->getOrganizationId());
        $copyObj->setPhone($this->getPhone());
        $copyObj->setFax($this->getFax());
        $copyObj->setMobile($this->getMobile());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setWebsite($this->getWebsite());
        $copyObj->setLogo($this->getLogo());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setConfirmation($this->getConfirmation());
        $copyObj->setSortableRank($this->getSortableRank());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getApplications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addApplication($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCourses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCourse($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPages() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPage($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEmployees() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEmployee($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getQualifications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addQualification($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSchoolEmployments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSchoolEmployment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSchoolEnrollments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSchoolEnrollment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSchoolGradeLevels() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSchoolGradeLevel($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSchoolTerms() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSchoolTerm($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSchoolTests() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSchoolTest($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSchoolYears() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSchoolYear($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSchoolI18ns() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSchoolI18n($relObj->copy($deepCopy));
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
     * @return School Clone of current object.
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
     * @return SchoolPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new SchoolPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a State object.
     *
     * @param                  State $v
     * @return School The current object (for fluent API support)
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
            $v->addSchool($this);
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
                $this->aState->addSchools($this);
             */
        }

        return $this->aState;
    }

    /**
     * Declares an association between this object and a Country object.
     *
     * @param                  Country $v
     * @return School The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCountry(Country $v = null)
    {
        if ($v === null) {
            $this->setCountryId(NULL);
        } else {
            $this->setCountryId($v->getId());
        }

        $this->aCountry = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Country object, it will not be re-added.
        if ($v !== null) {
            $v->addSchool($this);
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
                $this->aCountry->addSchools($this);
             */
        }

        return $this->aCountry;
    }

    /**
     * Declares an association between this object and a Organization object.
     *
     * @param                  Organization $v
     * @return School The current object (for fluent API support)
     * @throws PropelException
     */
    public function setOrganization(Organization $v = null)
    {
        if ($v === null) {
            $this->setOrganizationId(NULL);
        } else {
            $this->setOrganizationId($v->getId());
        }

        $this->aOrganization = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Organization object, it will not be re-added.
        if ($v !== null) {
            $v->addSchool($this);
        }


        return $this;
    }


    /**
     * Get the associated Organization object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Organization The associated Organization object.
     * @throws PropelException
     */
    public function getOrganization(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aOrganization === null && ($this->organization_id !== null) && $doQuery) {
            $this->aOrganization = OrganizationQuery::create()->findPk($this->organization_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aOrganization->addSchools($this);
             */
        }

        return $this->aOrganization;
    }

    /**
     * Declares an association between this object and a Level object.
     *
     * @param                  Level $v
     * @return School The current object (for fluent API support)
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
            $v->addSchool($this);
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
                $this->aLevel->addSchools($this);
             */
        }

        return $this->aLevel;
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
        if ('Application' == $relationName) {
            $this->initApplications();
        }
        if ('Course' == $relationName) {
            $this->initCourses();
        }
        if ('Page' == $relationName) {
            $this->initPages();
        }
        if ('Employee' == $relationName) {
            $this->initEmployees();
        }
        if ('Qualification' == $relationName) {
            $this->initQualifications();
        }
        if ('SchoolEmployment' == $relationName) {
            $this->initSchoolEmployments();
        }
        if ('SchoolEnrollment' == $relationName) {
            $this->initSchoolEnrollments();
        }
        if ('SchoolGradeLevel' == $relationName) {
            $this->initSchoolGradeLevels();
        }
        if ('SchoolTerm' == $relationName) {
            $this->initSchoolTerms();
        }
        if ('SchoolTest' == $relationName) {
            $this->initSchoolTests();
        }
        if ('SchoolYear' == $relationName) {
            $this->initSchoolYears();
        }
        if ('SchoolI18n' == $relationName) {
            $this->initSchoolI18ns();
        }
    }

    /**
     * Clears out the collApplications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return School The current object (for fluent API support)
     * @see        addApplications()
     */
    public function clearApplications()
    {
        $this->collApplications = null; // important to set this to null since that means it is uninitialized
        $this->collApplicationsPartial = null;

        return $this;
    }

    /**
     * reset is the collApplications collection loaded partially
     *
     * @return void
     */
    public function resetPartialApplications($v = true)
    {
        $this->collApplicationsPartial = $v;
    }

    /**
     * Initializes the collApplications collection.
     *
     * By default this just sets the collApplications collection to an empty array (like clearcollApplications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initApplications($overrideExisting = true)
    {
        if (null !== $this->collApplications && !$overrideExisting) {
            return;
        }
        $this->collApplications = new PropelObjectCollection();
        $this->collApplications->setModel('Application');
    }

    /**
     * Gets an array of Application objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this School is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Application[] List of Application objects
     * @throws PropelException
     */
    public function getApplications($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collApplicationsPartial && !$this->isNew();
        if (null === $this->collApplications || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collApplications) {
                // return empty collection
                $this->initApplications();
            } else {
                $collApplications = ApplicationQuery::create(null, $criteria)
                    ->filterBySchool($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collApplicationsPartial && count($collApplications)) {
                      $this->initApplications(false);

                      foreach ($collApplications as $obj) {
                        if (false == $this->collApplications->contains($obj)) {
                          $this->collApplications->append($obj);
                        }
                      }

                      $this->collApplicationsPartial = true;
                    }

                    $collApplications->getInternalIterator()->rewind();

                    return $collApplications;
                }

                if ($partial && $this->collApplications) {
                    foreach ($this->collApplications as $obj) {
                        if ($obj->isNew()) {
                            $collApplications[] = $obj;
                        }
                    }
                }

                $this->collApplications = $collApplications;
                $this->collApplicationsPartial = false;
            }
        }

        return $this->collApplications;
    }

    /**
     * Sets a collection of Application objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $applications A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return School The current object (for fluent API support)
     */
    public function setApplications(PropelCollection $applications, PropelPDO $con = null)
    {
        $applicationsToDelete = $this->getApplications(new Criteria(), $con)->diff($applications);


        $this->applicationsScheduledForDeletion = $applicationsToDelete;

        foreach ($applicationsToDelete as $applicationRemoved) {
            $applicationRemoved->setSchool(null);
        }

        $this->collApplications = null;
        foreach ($applications as $application) {
            $this->addApplication($application);
        }

        $this->collApplications = $applications;
        $this->collApplicationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Application objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Application objects.
     * @throws PropelException
     */
    public function countApplications(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collApplicationsPartial && !$this->isNew();
        if (null === $this->collApplications || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collApplications) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getApplications());
            }
            $query = ApplicationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySchool($this)
                ->count($con);
        }

        return count($this->collApplications);
    }

    /**
     * Method called to associate a Application object to this object
     * through the Application foreign key attribute.
     *
     * @param    Application $l Application
     * @return School The current object (for fluent API support)
     */
    public function addApplication(Application $l)
    {
        if ($this->collApplications === null) {
            $this->initApplications();
            $this->collApplicationsPartial = true;
        }

        if (!in_array($l, $this->collApplications->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddApplication($l);

            if ($this->applicationsScheduledForDeletion and $this->applicationsScheduledForDeletion->contains($l)) {
                $this->applicationsScheduledForDeletion->remove($this->applicationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Application $application The application object to add.
     */
    protected function doAddApplication($application)
    {
        $this->collApplications[]= $application;
        $application->setSchool($this);
    }

    /**
     * @param	Application $application The application object to remove.
     * @return School The current object (for fluent API support)
     */
    public function removeApplication($application)
    {
        if ($this->getApplications()->contains($application)) {
            $this->collApplications->remove($this->collApplications->search($application));
            if (null === $this->applicationsScheduledForDeletion) {
                $this->applicationsScheduledForDeletion = clone $this->collApplications;
                $this->applicationsScheduledForDeletion->clear();
            }
            $this->applicationsScheduledForDeletion[]= $application;
            $application->setSchool(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related Applications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Application[] List of Application objects
     */
    public function getApplicationsJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ApplicationQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getApplications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related Applications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Application[] List of Application objects
     */
    public function getApplicationsJoinSchoolYear($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ApplicationQuery::create(null, $criteria);
        $query->joinWith('SchoolYear', $join_behavior);

        return $this->getApplications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related Applications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Application[] List of Application objects
     */
    public function getApplicationsJoinEthnicity($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ApplicationQuery::create(null, $criteria);
        $query->joinWith('Ethnicity', $join_behavior);

        return $this->getApplications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related Applications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Application[] List of Application objects
     */
    public function getApplicationsJoinGrade($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ApplicationQuery::create(null, $criteria);
        $query->joinWith('Grade', $join_behavior);

        return $this->getApplications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related Applications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Application[] List of Application objects
     */
    public function getApplicationsJoinLevel($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ApplicationQuery::create(null, $criteria);
        $query->joinWith('Level', $join_behavior);

        return $this->getApplications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related Applications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Application[] List of Application objects
     */
    public function getApplicationsJoinState($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ApplicationQuery::create(null, $criteria);
        $query->joinWith('State', $join_behavior);

        return $this->getApplications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related Applications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Application[] List of Application objects
     */
    public function getApplicationsJoinCountry($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ApplicationQuery::create(null, $criteria);
        $query->joinWith('Country', $join_behavior);

        return $this->getApplications($query, $con);
    }

    /**
     * Clears out the collCourses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return School The current object (for fluent API support)
     * @see        addCourses()
     */
    public function clearCourses()
    {
        $this->collCourses = null; // important to set this to null since that means it is uninitialized
        $this->collCoursesPartial = null;

        return $this;
    }

    /**
     * reset is the collCourses collection loaded partially
     *
     * @return void
     */
    public function resetPartialCourses($v = true)
    {
        $this->collCoursesPartial = $v;
    }

    /**
     * Initializes the collCourses collection.
     *
     * By default this just sets the collCourses collection to an empty array (like clearcollCourses());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCourses($overrideExisting = true)
    {
        if (null !== $this->collCourses && !$overrideExisting) {
            return;
        }
        $this->collCourses = new PropelObjectCollection();
        $this->collCourses->setModel('Course');
    }

    /**
     * Gets an array of Course objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this School is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Course[] List of Course objects
     * @throws PropelException
     */
    public function getCourses($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCoursesPartial && !$this->isNew();
        if (null === $this->collCourses || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCourses) {
                // return empty collection
                $this->initCourses();
            } else {
                $collCourses = CourseQuery::create(null, $criteria)
                    ->filterBySchool($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCoursesPartial && count($collCourses)) {
                      $this->initCourses(false);

                      foreach ($collCourses as $obj) {
                        if (false == $this->collCourses->contains($obj)) {
                          $this->collCourses->append($obj);
                        }
                      }

                      $this->collCoursesPartial = true;
                    }

                    $collCourses->getInternalIterator()->rewind();

                    return $collCourses;
                }

                if ($partial && $this->collCourses) {
                    foreach ($this->collCourses as $obj) {
                        if ($obj->isNew()) {
                            $collCourses[] = $obj;
                        }
                    }
                }

                $this->collCourses = $collCourses;
                $this->collCoursesPartial = false;
            }
        }

        return $this->collCourses;
    }

    /**
     * Sets a collection of Course objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $courses A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return School The current object (for fluent API support)
     */
    public function setCourses(PropelCollection $courses, PropelPDO $con = null)
    {
        $coursesToDelete = $this->getCourses(new Criteria(), $con)->diff($courses);


        $this->coursesScheduledForDeletion = $coursesToDelete;

        foreach ($coursesToDelete as $courseRemoved) {
            $courseRemoved->setSchool(null);
        }

        $this->collCourses = null;
        foreach ($courses as $course) {
            $this->addCourse($course);
        }

        $this->collCourses = $courses;
        $this->collCoursesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Course objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Course objects.
     * @throws PropelException
     */
    public function countCourses(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCoursesPartial && !$this->isNew();
        if (null === $this->collCourses || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCourses) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCourses());
            }
            $query = CourseQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySchool($this)
                ->count($con);
        }

        return count($this->collCourses);
    }

    /**
     * Method called to associate a Course object to this object
     * through the Course foreign key attribute.
     *
     * @param    Course $l Course
     * @return School The current object (for fluent API support)
     */
    public function addCourse(Course $l)
    {
        if ($this->collCourses === null) {
            $this->initCourses();
            $this->collCoursesPartial = true;
        }

        if (!in_array($l, $this->collCourses->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCourse($l);

            if ($this->coursesScheduledForDeletion and $this->coursesScheduledForDeletion->contains($l)) {
                $this->coursesScheduledForDeletion->remove($this->coursesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Course $course The course object to add.
     */
    protected function doAddCourse($course)
    {
        $this->collCourses[]= $course;
        $course->setSchool($this);
    }

    /**
     * @param	Course $course The course object to remove.
     * @return School The current object (for fluent API support)
     */
    public function removeCourse($course)
    {
        if ($this->getCourses()->contains($course)) {
            $this->collCourses->remove($this->collCourses->search($course));
            if (null === $this->coursesScheduledForDeletion) {
                $this->coursesScheduledForDeletion = clone $this->collCourses;
                $this->coursesScheduledForDeletion->clear();
            }
            $this->coursesScheduledForDeletion[]= $course;
            $course->setSchool(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related Courses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Course[] List of Course objects
     */
    public function getCoursesJoinSubject($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CourseQuery::create(null, $criteria);
        $query->joinWith('Subject', $join_behavior);

        return $this->getCourses($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related Courses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Course[] List of Course objects
     */
    public function getCoursesJoinGradeLevel($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CourseQuery::create(null, $criteria);
        $query->joinWith('GradeLevel', $join_behavior);

        return $this->getCourses($query, $con);
    }

    /**
     * Clears out the collPages collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return School The current object (for fluent API support)
     * @see        addPages()
     */
    public function clearPages()
    {
        $this->collPages = null; // important to set this to null since that means it is uninitialized
        $this->collPagesPartial = null;

        return $this;
    }

    /**
     * reset is the collPages collection loaded partially
     *
     * @return void
     */
    public function resetPartialPages($v = true)
    {
        $this->collPagesPartial = $v;
    }

    /**
     * Initializes the collPages collection.
     *
     * By default this just sets the collPages collection to an empty array (like clearcollPages());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPages($overrideExisting = true)
    {
        if (null !== $this->collPages && !$overrideExisting) {
            return;
        }
        $this->collPages = new PropelObjectCollection();
        $this->collPages->setModel('Page');
    }

    /**
     * Gets an array of Page objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this School is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Page[] List of Page objects
     * @throws PropelException
     */
    public function getPages($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPagesPartial && !$this->isNew();
        if (null === $this->collPages || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPages) {
                // return empty collection
                $this->initPages();
            } else {
                $collPages = PageQuery::create(null, $criteria)
                    ->filterBySchool($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPagesPartial && count($collPages)) {
                      $this->initPages(false);

                      foreach ($collPages as $obj) {
                        if (false == $this->collPages->contains($obj)) {
                          $this->collPages->append($obj);
                        }
                      }

                      $this->collPagesPartial = true;
                    }

                    $collPages->getInternalIterator()->rewind();

                    return $collPages;
                }

                if ($partial && $this->collPages) {
                    foreach ($this->collPages as $obj) {
                        if ($obj->isNew()) {
                            $collPages[] = $obj;
                        }
                    }
                }

                $this->collPages = $collPages;
                $this->collPagesPartial = false;
            }
        }

        return $this->collPages;
    }

    /**
     * Sets a collection of Page objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pages A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return School The current object (for fluent API support)
     */
    public function setPages(PropelCollection $pages, PropelPDO $con = null)
    {
        $pagesToDelete = $this->getPages(new Criteria(), $con)->diff($pages);


        $this->pagesScheduledForDeletion = $pagesToDelete;

        foreach ($pagesToDelete as $pageRemoved) {
            $pageRemoved->setSchool(null);
        }

        $this->collPages = null;
        foreach ($pages as $page) {
            $this->addPage($page);
        }

        $this->collPages = $pages;
        $this->collPagesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Page objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Page objects.
     * @throws PropelException
     */
    public function countPages(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPagesPartial && !$this->isNew();
        if (null === $this->collPages || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPages) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPages());
            }
            $query = PageQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySchool($this)
                ->count($con);
        }

        return count($this->collPages);
    }

    /**
     * Method called to associate a Page object to this object
     * through the Page foreign key attribute.
     *
     * @param    Page $l Page
     * @return School The current object (for fluent API support)
     */
    public function addPage(Page $l)
    {
        if ($this->collPages === null) {
            $this->initPages();
            $this->collPagesPartial = true;
        }

        if (!in_array($l, $this->collPages->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPage($l);

            if ($this->pagesScheduledForDeletion and $this->pagesScheduledForDeletion->contains($l)) {
                $this->pagesScheduledForDeletion->remove($this->pagesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Page $page The page object to add.
     */
    protected function doAddPage($page)
    {
        $this->collPages[]= $page;
        $page->setSchool($this);
    }

    /**
     * @param	Page $page The page object to remove.
     * @return School The current object (for fluent API support)
     */
    public function removePage($page)
    {
        if ($this->getPages()->contains($page)) {
            $this->collPages->remove($this->collPages->search($page));
            if (null === $this->pagesScheduledForDeletion) {
                $this->pagesScheduledForDeletion = clone $this->collPages;
                $this->pagesScheduledForDeletion->clear();
            }
            $this->pagesScheduledForDeletion[]= $page;
            $page->setSchool(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related Pages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Page[] List of Page objects
     */
    public function getPagesJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PageQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getPages($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related Pages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Page[] List of Page objects
     */
    public function getPagesJoinTopic($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PageQuery::create(null, $criteria);
        $query->joinWith('Topic', $join_behavior);

        return $this->getPages($query, $con);
    }

    /**
     * Clears out the collEmployees collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return School The current object (for fluent API support)
     * @see        addEmployees()
     */
    public function clearEmployees()
    {
        $this->collEmployees = null; // important to set this to null since that means it is uninitialized
        $this->collEmployeesPartial = null;

        return $this;
    }

    /**
     * reset is the collEmployees collection loaded partially
     *
     * @return void
     */
    public function resetPartialEmployees($v = true)
    {
        $this->collEmployeesPartial = $v;
    }

    /**
     * Initializes the collEmployees collection.
     *
     * By default this just sets the collEmployees collection to an empty array (like clearcollEmployees());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEmployees($overrideExisting = true)
    {
        if (null !== $this->collEmployees && !$overrideExisting) {
            return;
        }
        $this->collEmployees = new PropelObjectCollection();
        $this->collEmployees->setModel('Employee');
    }

    /**
     * Gets an array of Employee objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this School is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Employee[] List of Employee objects
     * @throws PropelException
     */
    public function getEmployees($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEmployeesPartial && !$this->isNew();
        if (null === $this->collEmployees || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEmployees) {
                // return empty collection
                $this->initEmployees();
            } else {
                $collEmployees = EmployeeQuery::create(null, $criteria)
                    ->filterBySchool($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEmployeesPartial && count($collEmployees)) {
                      $this->initEmployees(false);

                      foreach ($collEmployees as $obj) {
                        if (false == $this->collEmployees->contains($obj)) {
                          $this->collEmployees->append($obj);
                        }
                      }

                      $this->collEmployeesPartial = true;
                    }

                    $collEmployees->getInternalIterator()->rewind();

                    return $collEmployees;
                }

                if ($partial && $this->collEmployees) {
                    foreach ($this->collEmployees as $obj) {
                        if ($obj->isNew()) {
                            $collEmployees[] = $obj;
                        }
                    }
                }

                $this->collEmployees = $collEmployees;
                $this->collEmployeesPartial = false;
            }
        }

        return $this->collEmployees;
    }

    /**
     * Sets a collection of Employee objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $employees A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return School The current object (for fluent API support)
     */
    public function setEmployees(PropelCollection $employees, PropelPDO $con = null)
    {
        $employeesToDelete = $this->getEmployees(new Criteria(), $con)->diff($employees);


        $this->employeesScheduledForDeletion = $employeesToDelete;

        foreach ($employeesToDelete as $employeeRemoved) {
            $employeeRemoved->setSchool(null);
        }

        $this->collEmployees = null;
        foreach ($employees as $employee) {
            $this->addEmployee($employee);
        }

        $this->collEmployees = $employees;
        $this->collEmployeesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Employee objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Employee objects.
     * @throws PropelException
     */
    public function countEmployees(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEmployeesPartial && !$this->isNew();
        if (null === $this->collEmployees || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEmployees) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEmployees());
            }
            $query = EmployeeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySchool($this)
                ->count($con);
        }

        return count($this->collEmployees);
    }

    /**
     * Method called to associate a Employee object to this object
     * through the Employee foreign key attribute.
     *
     * @param    Employee $l Employee
     * @return School The current object (for fluent API support)
     */
    public function addEmployee(Employee $l)
    {
        if ($this->collEmployees === null) {
            $this->initEmployees();
            $this->collEmployeesPartial = true;
        }

        if (!in_array($l, $this->collEmployees->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEmployee($l);

            if ($this->employeesScheduledForDeletion and $this->employeesScheduledForDeletion->contains($l)) {
                $this->employeesScheduledForDeletion->remove($this->employeesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Employee $employee The employee object to add.
     */
    protected function doAddEmployee($employee)
    {
        $this->collEmployees[]= $employee;
        $employee->setSchool($this);
    }

    /**
     * @param	Employee $employee The employee object to remove.
     * @return School The current object (for fluent API support)
     */
    public function removeEmployee($employee)
    {
        if ($this->getEmployees()->contains($employee)) {
            $this->collEmployees->remove($this->collEmployees->search($employee));
            if (null === $this->employeesScheduledForDeletion) {
                $this->employeesScheduledForDeletion = clone $this->collEmployees;
                $this->employeesScheduledForDeletion->clear();
            }
            $this->employeesScheduledForDeletion[]= clone $employee;
            $employee->setSchool(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related Employees from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Employee[] List of Employee objects
     */
    public function getEmployeesJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EmployeeQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getEmployees($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related Employees from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Employee[] List of Employee objects
     */
    public function getEmployeesJoinOrganization($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EmployeeQuery::create(null, $criteria);
        $query->joinWith('Organization', $join_behavior);

        return $this->getEmployees($query, $con);
    }

    /**
     * Clears out the collQualifications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return School The current object (for fluent API support)
     * @see        addQualifications()
     */
    public function clearQualifications()
    {
        $this->collQualifications = null; // important to set this to null since that means it is uninitialized
        $this->collQualificationsPartial = null;

        return $this;
    }

    /**
     * reset is the collQualifications collection loaded partially
     *
     * @return void
     */
    public function resetPartialQualifications($v = true)
    {
        $this->collQualificationsPartial = $v;
    }

    /**
     * Initializes the collQualifications collection.
     *
     * By default this just sets the collQualifications collection to an empty array (like clearcollQualifications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initQualifications($overrideExisting = true)
    {
        if (null !== $this->collQualifications && !$overrideExisting) {
            return;
        }
        $this->collQualifications = new PropelObjectCollection();
        $this->collQualifications->setModel('Qualification');
    }

    /**
     * Gets an array of Qualification objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this School is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Qualification[] List of Qualification objects
     * @throws PropelException
     */
    public function getQualifications($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collQualificationsPartial && !$this->isNew();
        if (null === $this->collQualifications || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collQualifications) {
                // return empty collection
                $this->initQualifications();
            } else {
                $collQualifications = QualificationQuery::create(null, $criteria)
                    ->filterBySchool($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collQualificationsPartial && count($collQualifications)) {
                      $this->initQualifications(false);

                      foreach ($collQualifications as $obj) {
                        if (false == $this->collQualifications->contains($obj)) {
                          $this->collQualifications->append($obj);
                        }
                      }

                      $this->collQualificationsPartial = true;
                    }

                    $collQualifications->getInternalIterator()->rewind();

                    return $collQualifications;
                }

                if ($partial && $this->collQualifications) {
                    foreach ($this->collQualifications as $obj) {
                        if ($obj->isNew()) {
                            $collQualifications[] = $obj;
                        }
                    }
                }

                $this->collQualifications = $collQualifications;
                $this->collQualificationsPartial = false;
            }
        }

        return $this->collQualifications;
    }

    /**
     * Sets a collection of Qualification objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $qualifications A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return School The current object (for fluent API support)
     */
    public function setQualifications(PropelCollection $qualifications, PropelPDO $con = null)
    {
        $qualificationsToDelete = $this->getQualifications(new Criteria(), $con)->diff($qualifications);


        $this->qualificationsScheduledForDeletion = $qualificationsToDelete;

        foreach ($qualificationsToDelete as $qualificationRemoved) {
            $qualificationRemoved->setSchool(null);
        }

        $this->collQualifications = null;
        foreach ($qualifications as $qualification) {
            $this->addQualification($qualification);
        }

        $this->collQualifications = $qualifications;
        $this->collQualificationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Qualification objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Qualification objects.
     * @throws PropelException
     */
    public function countQualifications(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collQualificationsPartial && !$this->isNew();
        if (null === $this->collQualifications || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collQualifications) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getQualifications());
            }
            $query = QualificationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySchool($this)
                ->count($con);
        }

        return count($this->collQualifications);
    }

    /**
     * Method called to associate a Qualification object to this object
     * through the Qualification foreign key attribute.
     *
     * @param    Qualification $l Qualification
     * @return School The current object (for fluent API support)
     */
    public function addQualification(Qualification $l)
    {
        if ($this->collQualifications === null) {
            $this->initQualifications();
            $this->collQualificationsPartial = true;
        }

        if (!in_array($l, $this->collQualifications->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddQualification($l);

            if ($this->qualificationsScheduledForDeletion and $this->qualificationsScheduledForDeletion->contains($l)) {
                $this->qualificationsScheduledForDeletion->remove($this->qualificationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Qualification $qualification The qualification object to add.
     */
    protected function doAddQualification($qualification)
    {
        $this->collQualifications[]= $qualification;
        $qualification->setSchool($this);
    }

    /**
     * @param	Qualification $qualification The qualification object to remove.
     * @return School The current object (for fluent API support)
     */
    public function removeQualification($qualification)
    {
        if ($this->getQualifications()->contains($qualification)) {
            $this->collQualifications->remove($this->collQualifications->search($qualification));
            if (null === $this->qualificationsScheduledForDeletion) {
                $this->qualificationsScheduledForDeletion = clone $this->collQualifications;
                $this->qualificationsScheduledForDeletion->clear();
            }
            $this->qualificationsScheduledForDeletion[]= $qualification;
            $qualification->setSchool(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related Qualifications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Qualification[] List of Qualification objects
     */
    public function getQualificationsJoinCourse($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = QualificationQuery::create(null, $criteria);
        $query->joinWith('Course', $join_behavior);

        return $this->getQualifications($query, $con);
    }

    /**
     * Clears out the collSchoolEmployments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return School The current object (for fluent API support)
     * @see        addSchoolEmployments()
     */
    public function clearSchoolEmployments()
    {
        $this->collSchoolEmployments = null; // important to set this to null since that means it is uninitialized
        $this->collSchoolEmploymentsPartial = null;

        return $this;
    }

    /**
     * reset is the collSchoolEmployments collection loaded partially
     *
     * @return void
     */
    public function resetPartialSchoolEmployments($v = true)
    {
        $this->collSchoolEmploymentsPartial = $v;
    }

    /**
     * Initializes the collSchoolEmployments collection.
     *
     * By default this just sets the collSchoolEmployments collection to an empty array (like clearcollSchoolEmployments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSchoolEmployments($overrideExisting = true)
    {
        if (null !== $this->collSchoolEmployments && !$overrideExisting) {
            return;
        }
        $this->collSchoolEmployments = new PropelObjectCollection();
        $this->collSchoolEmployments->setModel('SchoolEmployment');
    }

    /**
     * Gets an array of SchoolEmployment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this School is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|SchoolEmployment[] List of SchoolEmployment objects
     * @throws PropelException
     */
    public function getSchoolEmployments($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSchoolEmploymentsPartial && !$this->isNew();
        if (null === $this->collSchoolEmployments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSchoolEmployments) {
                // return empty collection
                $this->initSchoolEmployments();
            } else {
                $collSchoolEmployments = SchoolEmploymentQuery::create(null, $criteria)
                    ->filterBySchool($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSchoolEmploymentsPartial && count($collSchoolEmployments)) {
                      $this->initSchoolEmployments(false);

                      foreach ($collSchoolEmployments as $obj) {
                        if (false == $this->collSchoolEmployments->contains($obj)) {
                          $this->collSchoolEmployments->append($obj);
                        }
                      }

                      $this->collSchoolEmploymentsPartial = true;
                    }

                    $collSchoolEmployments->getInternalIterator()->rewind();

                    return $collSchoolEmployments;
                }

                if ($partial && $this->collSchoolEmployments) {
                    foreach ($this->collSchoolEmployments as $obj) {
                        if ($obj->isNew()) {
                            $collSchoolEmployments[] = $obj;
                        }
                    }
                }

                $this->collSchoolEmployments = $collSchoolEmployments;
                $this->collSchoolEmploymentsPartial = false;
            }
        }

        return $this->collSchoolEmployments;
    }

    /**
     * Sets a collection of SchoolEmployment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $schoolEmployments A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return School The current object (for fluent API support)
     */
    public function setSchoolEmployments(PropelCollection $schoolEmployments, PropelPDO $con = null)
    {
        $schoolEmploymentsToDelete = $this->getSchoolEmployments(new Criteria(), $con)->diff($schoolEmployments);


        $this->schoolEmploymentsScheduledForDeletion = $schoolEmploymentsToDelete;

        foreach ($schoolEmploymentsToDelete as $schoolEmploymentRemoved) {
            $schoolEmploymentRemoved->setSchool(null);
        }

        $this->collSchoolEmployments = null;
        foreach ($schoolEmployments as $schoolEmployment) {
            $this->addSchoolEmployment($schoolEmployment);
        }

        $this->collSchoolEmployments = $schoolEmployments;
        $this->collSchoolEmploymentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SchoolEmployment objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related SchoolEmployment objects.
     * @throws PropelException
     */
    public function countSchoolEmployments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSchoolEmploymentsPartial && !$this->isNew();
        if (null === $this->collSchoolEmployments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSchoolEmployments) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSchoolEmployments());
            }
            $query = SchoolEmploymentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySchool($this)
                ->count($con);
        }

        return count($this->collSchoolEmployments);
    }

    /**
     * Method called to associate a SchoolEmployment object to this object
     * through the SchoolEmployment foreign key attribute.
     *
     * @param    SchoolEmployment $l SchoolEmployment
     * @return School The current object (for fluent API support)
     */
    public function addSchoolEmployment(SchoolEmployment $l)
    {
        if ($this->collSchoolEmployments === null) {
            $this->initSchoolEmployments();
            $this->collSchoolEmploymentsPartial = true;
        }

        if (!in_array($l, $this->collSchoolEmployments->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSchoolEmployment($l);

            if ($this->schoolEmploymentsScheduledForDeletion and $this->schoolEmploymentsScheduledForDeletion->contains($l)) {
                $this->schoolEmploymentsScheduledForDeletion->remove($this->schoolEmploymentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	SchoolEmployment $schoolEmployment The schoolEmployment object to add.
     */
    protected function doAddSchoolEmployment($schoolEmployment)
    {
        $this->collSchoolEmployments[]= $schoolEmployment;
        $schoolEmployment->setSchool($this);
    }

    /**
     * @param	SchoolEmployment $schoolEmployment The schoolEmployment object to remove.
     * @return School The current object (for fluent API support)
     */
    public function removeSchoolEmployment($schoolEmployment)
    {
        if ($this->getSchoolEmployments()->contains($schoolEmployment)) {
            $this->collSchoolEmployments->remove($this->collSchoolEmployments->search($schoolEmployment));
            if (null === $this->schoolEmploymentsScheduledForDeletion) {
                $this->schoolEmploymentsScheduledForDeletion = clone $this->collSchoolEmployments;
                $this->schoolEmploymentsScheduledForDeletion->clear();
            }
            $this->schoolEmploymentsScheduledForDeletion[]= $schoolEmployment;
            $schoolEmployment->setSchool(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related SchoolEmployments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolEmployment[] List of SchoolEmployment objects
     */
    public function getSchoolEmploymentsJoinEmployee($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolEmploymentQuery::create(null, $criteria);
        $query->joinWith('Employee', $join_behavior);

        return $this->getSchoolEmployments($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related SchoolEmployments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolEmployment[] List of SchoolEmployment objects
     */
    public function getSchoolEmploymentsJoinSchoolYear($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolEmploymentQuery::create(null, $criteria);
        $query->joinWith('SchoolYear', $join_behavior);

        return $this->getSchoolEmployments($query, $con);
    }

    /**
     * Clears out the collSchoolEnrollments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return School The current object (for fluent API support)
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
     * If this School is new, it will return
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
                    ->filterBySchool($this)
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
     * @return School The current object (for fluent API support)
     */
    public function setSchoolEnrollments(PropelCollection $schoolEnrollments, PropelPDO $con = null)
    {
        $schoolEnrollmentsToDelete = $this->getSchoolEnrollments(new Criteria(), $con)->diff($schoolEnrollments);


        $this->schoolEnrollmentsScheduledForDeletion = $schoolEnrollmentsToDelete;

        foreach ($schoolEnrollmentsToDelete as $schoolEnrollmentRemoved) {
            $schoolEnrollmentRemoved->setSchool(null);
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
                ->filterBySchool($this)
                ->count($con);
        }

        return count($this->collSchoolEnrollments);
    }

    /**
     * Method called to associate a SchoolEnrollment object to this object
     * through the SchoolEnrollment foreign key attribute.
     *
     * @param    SchoolEnrollment $l SchoolEnrollment
     * @return School The current object (for fluent API support)
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
        $schoolEnrollment->setSchool($this);
    }

    /**
     * @param	SchoolEnrollment $schoolEnrollment The schoolEnrollment object to remove.
     * @return School The current object (for fluent API support)
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
            $schoolEnrollment->setSchool(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related SchoolEnrollments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolEnrollment[] List of SchoolEnrollment objects
     */
    public function getSchoolEnrollmentsJoinStudent($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolEnrollmentQuery::create(null, $criteria);
        $query->joinWith('Student', $join_behavior);

        return $this->getSchoolEnrollments($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related SchoolEnrollments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
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
     * Clears out the collSchoolGradeLevels collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return School The current object (for fluent API support)
     * @see        addSchoolGradeLevels()
     */
    public function clearSchoolGradeLevels()
    {
        $this->collSchoolGradeLevels = null; // important to set this to null since that means it is uninitialized
        $this->collSchoolGradeLevelsPartial = null;

        return $this;
    }

    /**
     * reset is the collSchoolGradeLevels collection loaded partially
     *
     * @return void
     */
    public function resetPartialSchoolGradeLevels($v = true)
    {
        $this->collSchoolGradeLevelsPartial = $v;
    }

    /**
     * Initializes the collSchoolGradeLevels collection.
     *
     * By default this just sets the collSchoolGradeLevels collection to an empty array (like clearcollSchoolGradeLevels());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSchoolGradeLevels($overrideExisting = true)
    {
        if (null !== $this->collSchoolGradeLevels && !$overrideExisting) {
            return;
        }
        $this->collSchoolGradeLevels = new PropelObjectCollection();
        $this->collSchoolGradeLevels->setModel('SchoolGradeLevel');
    }

    /**
     * Gets an array of SchoolGradeLevel objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this School is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|SchoolGradeLevel[] List of SchoolGradeLevel objects
     * @throws PropelException
     */
    public function getSchoolGradeLevels($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSchoolGradeLevelsPartial && !$this->isNew();
        if (null === $this->collSchoolGradeLevels || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSchoolGradeLevels) {
                // return empty collection
                $this->initSchoolGradeLevels();
            } else {
                $collSchoolGradeLevels = SchoolGradeLevelQuery::create(null, $criteria)
                    ->filterBySchool($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSchoolGradeLevelsPartial && count($collSchoolGradeLevels)) {
                      $this->initSchoolGradeLevels(false);

                      foreach ($collSchoolGradeLevels as $obj) {
                        if (false == $this->collSchoolGradeLevels->contains($obj)) {
                          $this->collSchoolGradeLevels->append($obj);
                        }
                      }

                      $this->collSchoolGradeLevelsPartial = true;
                    }

                    $collSchoolGradeLevels->getInternalIterator()->rewind();

                    return $collSchoolGradeLevels;
                }

                if ($partial && $this->collSchoolGradeLevels) {
                    foreach ($this->collSchoolGradeLevels as $obj) {
                        if ($obj->isNew()) {
                            $collSchoolGradeLevels[] = $obj;
                        }
                    }
                }

                $this->collSchoolGradeLevels = $collSchoolGradeLevels;
                $this->collSchoolGradeLevelsPartial = false;
            }
        }

        return $this->collSchoolGradeLevels;
    }

    /**
     * Sets a collection of SchoolGradeLevel objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $schoolGradeLevels A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return School The current object (for fluent API support)
     */
    public function setSchoolGradeLevels(PropelCollection $schoolGradeLevels, PropelPDO $con = null)
    {
        $schoolGradeLevelsToDelete = $this->getSchoolGradeLevels(new Criteria(), $con)->diff($schoolGradeLevels);


        $this->schoolGradeLevelsScheduledForDeletion = $schoolGradeLevelsToDelete;

        foreach ($schoolGradeLevelsToDelete as $schoolGradeLevelRemoved) {
            $schoolGradeLevelRemoved->setSchool(null);
        }

        $this->collSchoolGradeLevels = null;
        foreach ($schoolGradeLevels as $schoolGradeLevel) {
            $this->addSchoolGradeLevel($schoolGradeLevel);
        }

        $this->collSchoolGradeLevels = $schoolGradeLevels;
        $this->collSchoolGradeLevelsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SchoolGradeLevel objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related SchoolGradeLevel objects.
     * @throws PropelException
     */
    public function countSchoolGradeLevels(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSchoolGradeLevelsPartial && !$this->isNew();
        if (null === $this->collSchoolGradeLevels || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSchoolGradeLevels) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSchoolGradeLevels());
            }
            $query = SchoolGradeLevelQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySchool($this)
                ->count($con);
        }

        return count($this->collSchoolGradeLevels);
    }

    /**
     * Method called to associate a SchoolGradeLevel object to this object
     * through the SchoolGradeLevel foreign key attribute.
     *
     * @param    SchoolGradeLevel $l SchoolGradeLevel
     * @return School The current object (for fluent API support)
     */
    public function addSchoolGradeLevel(SchoolGradeLevel $l)
    {
        if ($this->collSchoolGradeLevels === null) {
            $this->initSchoolGradeLevels();
            $this->collSchoolGradeLevelsPartial = true;
        }

        if (!in_array($l, $this->collSchoolGradeLevels->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSchoolGradeLevel($l);

            if ($this->schoolGradeLevelsScheduledForDeletion and $this->schoolGradeLevelsScheduledForDeletion->contains($l)) {
                $this->schoolGradeLevelsScheduledForDeletion->remove($this->schoolGradeLevelsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	SchoolGradeLevel $schoolGradeLevel The schoolGradeLevel object to add.
     */
    protected function doAddSchoolGradeLevel($schoolGradeLevel)
    {
        $this->collSchoolGradeLevels[]= $schoolGradeLevel;
        $schoolGradeLevel->setSchool($this);
    }

    /**
     * @param	SchoolGradeLevel $schoolGradeLevel The schoolGradeLevel object to remove.
     * @return School The current object (for fluent API support)
     */
    public function removeSchoolGradeLevel($schoolGradeLevel)
    {
        if ($this->getSchoolGradeLevels()->contains($schoolGradeLevel)) {
            $this->collSchoolGradeLevels->remove($this->collSchoolGradeLevels->search($schoolGradeLevel));
            if (null === $this->schoolGradeLevelsScheduledForDeletion) {
                $this->schoolGradeLevelsScheduledForDeletion = clone $this->collSchoolGradeLevels;
                $this->schoolGradeLevelsScheduledForDeletion->clear();
            }
            $this->schoolGradeLevelsScheduledForDeletion[]= clone $schoolGradeLevel;
            $schoolGradeLevel->setSchool(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related SchoolGradeLevels from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolGradeLevel[] List of SchoolGradeLevel objects
     */
    public function getSchoolGradeLevelsJoinGradeLevel($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolGradeLevelQuery::create(null, $criteria);
        $query->joinWith('GradeLevel', $join_behavior);

        return $this->getSchoolGradeLevels($query, $con);
    }

    /**
     * Clears out the collSchoolTerms collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return School The current object (for fluent API support)
     * @see        addSchoolTerms()
     */
    public function clearSchoolTerms()
    {
        $this->collSchoolTerms = null; // important to set this to null since that means it is uninitialized
        $this->collSchoolTermsPartial = null;

        return $this;
    }

    /**
     * reset is the collSchoolTerms collection loaded partially
     *
     * @return void
     */
    public function resetPartialSchoolTerms($v = true)
    {
        $this->collSchoolTermsPartial = $v;
    }

    /**
     * Initializes the collSchoolTerms collection.
     *
     * By default this just sets the collSchoolTerms collection to an empty array (like clearcollSchoolTerms());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSchoolTerms($overrideExisting = true)
    {
        if (null !== $this->collSchoolTerms && !$overrideExisting) {
            return;
        }
        $this->collSchoolTerms = new PropelObjectCollection();
        $this->collSchoolTerms->setModel('SchoolTerm');
    }

    /**
     * Gets an array of SchoolTerm objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this School is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|SchoolTerm[] List of SchoolTerm objects
     * @throws PropelException
     */
    public function getSchoolTerms($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSchoolTermsPartial && !$this->isNew();
        if (null === $this->collSchoolTerms || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSchoolTerms) {
                // return empty collection
                $this->initSchoolTerms();
            } else {
                $collSchoolTerms = SchoolTermQuery::create(null, $criteria)
                    ->filterBySchool($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSchoolTermsPartial && count($collSchoolTerms)) {
                      $this->initSchoolTerms(false);

                      foreach ($collSchoolTerms as $obj) {
                        if (false == $this->collSchoolTerms->contains($obj)) {
                          $this->collSchoolTerms->append($obj);
                        }
                      }

                      $this->collSchoolTermsPartial = true;
                    }

                    $collSchoolTerms->getInternalIterator()->rewind();

                    return $collSchoolTerms;
                }

                if ($partial && $this->collSchoolTerms) {
                    foreach ($this->collSchoolTerms as $obj) {
                        if ($obj->isNew()) {
                            $collSchoolTerms[] = $obj;
                        }
                    }
                }

                $this->collSchoolTerms = $collSchoolTerms;
                $this->collSchoolTermsPartial = false;
            }
        }

        return $this->collSchoolTerms;
    }

    /**
     * Sets a collection of SchoolTerm objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $schoolTerms A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return School The current object (for fluent API support)
     */
    public function setSchoolTerms(PropelCollection $schoolTerms, PropelPDO $con = null)
    {
        $schoolTermsToDelete = $this->getSchoolTerms(new Criteria(), $con)->diff($schoolTerms);


        $this->schoolTermsScheduledForDeletion = $schoolTermsToDelete;

        foreach ($schoolTermsToDelete as $schoolTermRemoved) {
            $schoolTermRemoved->setSchool(null);
        }

        $this->collSchoolTerms = null;
        foreach ($schoolTerms as $schoolTerm) {
            $this->addSchoolTerm($schoolTerm);
        }

        $this->collSchoolTerms = $schoolTerms;
        $this->collSchoolTermsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SchoolTerm objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related SchoolTerm objects.
     * @throws PropelException
     */
    public function countSchoolTerms(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSchoolTermsPartial && !$this->isNew();
        if (null === $this->collSchoolTerms || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSchoolTerms) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSchoolTerms());
            }
            $query = SchoolTermQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySchool($this)
                ->count($con);
        }

        return count($this->collSchoolTerms);
    }

    /**
     * Method called to associate a SchoolTerm object to this object
     * through the SchoolTerm foreign key attribute.
     *
     * @param    SchoolTerm $l SchoolTerm
     * @return School The current object (for fluent API support)
     */
    public function addSchoolTerm(SchoolTerm $l)
    {
        if ($this->collSchoolTerms === null) {
            $this->initSchoolTerms();
            $this->collSchoolTermsPartial = true;
        }

        if (!in_array($l, $this->collSchoolTerms->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSchoolTerm($l);

            if ($this->schoolTermsScheduledForDeletion and $this->schoolTermsScheduledForDeletion->contains($l)) {
                $this->schoolTermsScheduledForDeletion->remove($this->schoolTermsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	SchoolTerm $schoolTerm The schoolTerm object to add.
     */
    protected function doAddSchoolTerm($schoolTerm)
    {
        $this->collSchoolTerms[]= $schoolTerm;
        $schoolTerm->setSchool($this);
    }

    /**
     * @param	SchoolTerm $schoolTerm The schoolTerm object to remove.
     * @return School The current object (for fluent API support)
     */
    public function removeSchoolTerm($schoolTerm)
    {
        if ($this->getSchoolTerms()->contains($schoolTerm)) {
            $this->collSchoolTerms->remove($this->collSchoolTerms->search($schoolTerm));
            if (null === $this->schoolTermsScheduledForDeletion) {
                $this->schoolTermsScheduledForDeletion = clone $this->collSchoolTerms;
                $this->schoolTermsScheduledForDeletion->clear();
            }
            $this->schoolTermsScheduledForDeletion[]= clone $schoolTerm;
            $schoolTerm->setSchool(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related SchoolTerms from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolTerm[] List of SchoolTerm objects
     */
    public function getSchoolTermsJoinTerm($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolTermQuery::create(null, $criteria);
        $query->joinWith('Term', $join_behavior);

        return $this->getSchoolTerms($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related SchoolTerms from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolTerm[] List of SchoolTerm objects
     */
    public function getSchoolTermsJoinSchoolYear($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolTermQuery::create(null, $criteria);
        $query->joinWith('SchoolYear', $join_behavior);

        return $this->getSchoolTerms($query, $con);
    }

    /**
     * Clears out the collSchoolTests collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return School The current object (for fluent API support)
     * @see        addSchoolTests()
     */
    public function clearSchoolTests()
    {
        $this->collSchoolTests = null; // important to set this to null since that means it is uninitialized
        $this->collSchoolTestsPartial = null;

        return $this;
    }

    /**
     * reset is the collSchoolTests collection loaded partially
     *
     * @return void
     */
    public function resetPartialSchoolTests($v = true)
    {
        $this->collSchoolTestsPartial = $v;
    }

    /**
     * Initializes the collSchoolTests collection.
     *
     * By default this just sets the collSchoolTests collection to an empty array (like clearcollSchoolTests());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSchoolTests($overrideExisting = true)
    {
        if (null !== $this->collSchoolTests && !$overrideExisting) {
            return;
        }
        $this->collSchoolTests = new PropelObjectCollection();
        $this->collSchoolTests->setModel('SchoolTest');
    }

    /**
     * Gets an array of SchoolTest objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this School is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|SchoolTest[] List of SchoolTest objects
     * @throws PropelException
     */
    public function getSchoolTests($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSchoolTestsPartial && !$this->isNew();
        if (null === $this->collSchoolTests || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSchoolTests) {
                // return empty collection
                $this->initSchoolTests();
            } else {
                $collSchoolTests = SchoolTestQuery::create(null, $criteria)
                    ->filterBySchool($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSchoolTestsPartial && count($collSchoolTests)) {
                      $this->initSchoolTests(false);

                      foreach ($collSchoolTests as $obj) {
                        if (false == $this->collSchoolTests->contains($obj)) {
                          $this->collSchoolTests->append($obj);
                        }
                      }

                      $this->collSchoolTestsPartial = true;
                    }

                    $collSchoolTests->getInternalIterator()->rewind();

                    return $collSchoolTests;
                }

                if ($partial && $this->collSchoolTests) {
                    foreach ($this->collSchoolTests as $obj) {
                        if ($obj->isNew()) {
                            $collSchoolTests[] = $obj;
                        }
                    }
                }

                $this->collSchoolTests = $collSchoolTests;
                $this->collSchoolTestsPartial = false;
            }
        }

        return $this->collSchoolTests;
    }

    /**
     * Sets a collection of SchoolTest objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $schoolTests A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return School The current object (for fluent API support)
     */
    public function setSchoolTests(PropelCollection $schoolTests, PropelPDO $con = null)
    {
        $schoolTestsToDelete = $this->getSchoolTests(new Criteria(), $con)->diff($schoolTests);


        $this->schoolTestsScheduledForDeletion = $schoolTestsToDelete;

        foreach ($schoolTestsToDelete as $schoolTestRemoved) {
            $schoolTestRemoved->setSchool(null);
        }

        $this->collSchoolTests = null;
        foreach ($schoolTests as $schoolTest) {
            $this->addSchoolTest($schoolTest);
        }

        $this->collSchoolTests = $schoolTests;
        $this->collSchoolTestsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SchoolTest objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related SchoolTest objects.
     * @throws PropelException
     */
    public function countSchoolTests(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSchoolTestsPartial && !$this->isNew();
        if (null === $this->collSchoolTests || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSchoolTests) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSchoolTests());
            }
            $query = SchoolTestQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySchool($this)
                ->count($con);
        }

        return count($this->collSchoolTests);
    }

    /**
     * Method called to associate a SchoolTest object to this object
     * through the SchoolTest foreign key attribute.
     *
     * @param    SchoolTest $l SchoolTest
     * @return School The current object (for fluent API support)
     */
    public function addSchoolTest(SchoolTest $l)
    {
        if ($this->collSchoolTests === null) {
            $this->initSchoolTests();
            $this->collSchoolTestsPartial = true;
        }

        if (!in_array($l, $this->collSchoolTests->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSchoolTest($l);

            if ($this->schoolTestsScheduledForDeletion and $this->schoolTestsScheduledForDeletion->contains($l)) {
                $this->schoolTestsScheduledForDeletion->remove($this->schoolTestsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	SchoolTest $schoolTest The schoolTest object to add.
     */
    protected function doAddSchoolTest($schoolTest)
    {
        $this->collSchoolTests[]= $schoolTest;
        $schoolTest->setSchool($this);
    }

    /**
     * @param	SchoolTest $schoolTest The schoolTest object to remove.
     * @return School The current object (for fluent API support)
     */
    public function removeSchoolTest($schoolTest)
    {
        if ($this->getSchoolTests()->contains($schoolTest)) {
            $this->collSchoolTests->remove($this->collSchoolTests->search($schoolTest));
            if (null === $this->schoolTestsScheduledForDeletion) {
                $this->schoolTestsScheduledForDeletion = clone $this->collSchoolTests;
                $this->schoolTestsScheduledForDeletion->clear();
            }
            $this->schoolTestsScheduledForDeletion[]= clone $schoolTest;
            $schoolTest->setSchool(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related SchoolTests from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolTest[] List of SchoolTest objects
     */
    public function getSchoolTestsJoinTest($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolTestQuery::create(null, $criteria);
        $query->joinWith('Test', $join_behavior);

        return $this->getSchoolTests($query, $con);
    }

    /**
     * Clears out the collSchoolYears collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return School The current object (for fluent API support)
     * @see        addSchoolYears()
     */
    public function clearSchoolYears()
    {
        $this->collSchoolYears = null; // important to set this to null since that means it is uninitialized
        $this->collSchoolYearsPartial = null;

        return $this;
    }

    /**
     * reset is the collSchoolYears collection loaded partially
     *
     * @return void
     */
    public function resetPartialSchoolYears($v = true)
    {
        $this->collSchoolYearsPartial = $v;
    }

    /**
     * Initializes the collSchoolYears collection.
     *
     * By default this just sets the collSchoolYears collection to an empty array (like clearcollSchoolYears());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSchoolYears($overrideExisting = true)
    {
        if (null !== $this->collSchoolYears && !$overrideExisting) {
            return;
        }
        $this->collSchoolYears = new PropelObjectCollection();
        $this->collSchoolYears->setModel('SchoolYear');
    }

    /**
     * Gets an array of SchoolYear objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this School is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|SchoolYear[] List of SchoolYear objects
     * @throws PropelException
     */
    public function getSchoolYears($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSchoolYearsPartial && !$this->isNew();
        if (null === $this->collSchoolYears || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSchoolYears) {
                // return empty collection
                $this->initSchoolYears();
            } else {
                $collSchoolYears = SchoolYearQuery::create(null, $criteria)
                    ->filterBySchool($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSchoolYearsPartial && count($collSchoolYears)) {
                      $this->initSchoolYears(false);

                      foreach ($collSchoolYears as $obj) {
                        if (false == $this->collSchoolYears->contains($obj)) {
                          $this->collSchoolYears->append($obj);
                        }
                      }

                      $this->collSchoolYearsPartial = true;
                    }

                    $collSchoolYears->getInternalIterator()->rewind();

                    return $collSchoolYears;
                }

                if ($partial && $this->collSchoolYears) {
                    foreach ($this->collSchoolYears as $obj) {
                        if ($obj->isNew()) {
                            $collSchoolYears[] = $obj;
                        }
                    }
                }

                $this->collSchoolYears = $collSchoolYears;
                $this->collSchoolYearsPartial = false;
            }
        }

        return $this->collSchoolYears;
    }

    /**
     * Sets a collection of SchoolYear objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $schoolYears A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return School The current object (for fluent API support)
     */
    public function setSchoolYears(PropelCollection $schoolYears, PropelPDO $con = null)
    {
        $schoolYearsToDelete = $this->getSchoolYears(new Criteria(), $con)->diff($schoolYears);


        $this->schoolYearsScheduledForDeletion = $schoolYearsToDelete;

        foreach ($schoolYearsToDelete as $schoolYearRemoved) {
            $schoolYearRemoved->setSchool(null);
        }

        $this->collSchoolYears = null;
        foreach ($schoolYears as $schoolYear) {
            $this->addSchoolYear($schoolYear);
        }

        $this->collSchoolYears = $schoolYears;
        $this->collSchoolYearsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SchoolYear objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related SchoolYear objects.
     * @throws PropelException
     */
    public function countSchoolYears(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSchoolYearsPartial && !$this->isNew();
        if (null === $this->collSchoolYears || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSchoolYears) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSchoolYears());
            }
            $query = SchoolYearQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySchool($this)
                ->count($con);
        }

        return count($this->collSchoolYears);
    }

    /**
     * Method called to associate a SchoolYear object to this object
     * through the SchoolYear foreign key attribute.
     *
     * @param    SchoolYear $l SchoolYear
     * @return School The current object (for fluent API support)
     */
    public function addSchoolYear(SchoolYear $l)
    {
        if ($this->collSchoolYears === null) {
            $this->initSchoolYears();
            $this->collSchoolYearsPartial = true;
        }

        if (!in_array($l, $this->collSchoolYears->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSchoolYear($l);

            if ($this->schoolYearsScheduledForDeletion and $this->schoolYearsScheduledForDeletion->contains($l)) {
                $this->schoolYearsScheduledForDeletion->remove($this->schoolYearsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	SchoolYear $schoolYear The schoolYear object to add.
     */
    protected function doAddSchoolYear($schoolYear)
    {
        $this->collSchoolYears[]= $schoolYear;
        $schoolYear->setSchool($this);
    }

    /**
     * @param	SchoolYear $schoolYear The schoolYear object to remove.
     * @return School The current object (for fluent API support)
     */
    public function removeSchoolYear($schoolYear)
    {
        if ($this->getSchoolYears()->contains($schoolYear)) {
            $this->collSchoolYears->remove($this->collSchoolYears->search($schoolYear));
            if (null === $this->schoolYearsScheduledForDeletion) {
                $this->schoolYearsScheduledForDeletion = clone $this->collSchoolYears;
                $this->schoolYearsScheduledForDeletion->clear();
            }
            $this->schoolYearsScheduledForDeletion[]= clone $schoolYear;
            $schoolYear->setSchool(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this School is new, it will return
     * an empty collection; or if this School has previously
     * been saved, it will retrieve related SchoolYears from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in School.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolYear[] List of SchoolYear objects
     */
    public function getSchoolYearsJoinAcademicYear($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolYearQuery::create(null, $criteria);
        $query->joinWith('AcademicYear', $join_behavior);

        return $this->getSchoolYears($query, $con);
    }

    /**
     * Clears out the collSchoolI18ns collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return School The current object (for fluent API support)
     * @see        addSchoolI18ns()
     */
    public function clearSchoolI18ns()
    {
        $this->collSchoolI18ns = null; // important to set this to null since that means it is uninitialized
        $this->collSchoolI18nsPartial = null;

        return $this;
    }

    /**
     * reset is the collSchoolI18ns collection loaded partially
     *
     * @return void
     */
    public function resetPartialSchoolI18ns($v = true)
    {
        $this->collSchoolI18nsPartial = $v;
    }

    /**
     * Initializes the collSchoolI18ns collection.
     *
     * By default this just sets the collSchoolI18ns collection to an empty array (like clearcollSchoolI18ns());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSchoolI18ns($overrideExisting = true)
    {
        if (null !== $this->collSchoolI18ns && !$overrideExisting) {
            return;
        }
        $this->collSchoolI18ns = new PropelObjectCollection();
        $this->collSchoolI18ns->setModel('SchoolI18n');
    }

    /**
     * Gets an array of SchoolI18n objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this School is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|SchoolI18n[] List of SchoolI18n objects
     * @throws PropelException
     */
    public function getSchoolI18ns($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSchoolI18nsPartial && !$this->isNew();
        if (null === $this->collSchoolI18ns || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSchoolI18ns) {
                // return empty collection
                $this->initSchoolI18ns();
            } else {
                $collSchoolI18ns = SchoolI18nQuery::create(null, $criteria)
                    ->filterBySchool($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSchoolI18nsPartial && count($collSchoolI18ns)) {
                      $this->initSchoolI18ns(false);

                      foreach ($collSchoolI18ns as $obj) {
                        if (false == $this->collSchoolI18ns->contains($obj)) {
                          $this->collSchoolI18ns->append($obj);
                        }
                      }

                      $this->collSchoolI18nsPartial = true;
                    }

                    $collSchoolI18ns->getInternalIterator()->rewind();

                    return $collSchoolI18ns;
                }

                if ($partial && $this->collSchoolI18ns) {
                    foreach ($this->collSchoolI18ns as $obj) {
                        if ($obj->isNew()) {
                            $collSchoolI18ns[] = $obj;
                        }
                    }
                }

                $this->collSchoolI18ns = $collSchoolI18ns;
                $this->collSchoolI18nsPartial = false;
            }
        }

        return $this->collSchoolI18ns;
    }

    /**
     * Sets a collection of SchoolI18n objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $schoolI18ns A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return School The current object (for fluent API support)
     */
    public function setSchoolI18ns(PropelCollection $schoolI18ns, PropelPDO $con = null)
    {
        $schoolI18nsToDelete = $this->getSchoolI18ns(new Criteria(), $con)->diff($schoolI18ns);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->schoolI18nsScheduledForDeletion = clone $schoolI18nsToDelete;

        foreach ($schoolI18nsToDelete as $schoolI18nRemoved) {
            $schoolI18nRemoved->setSchool(null);
        }

        $this->collSchoolI18ns = null;
        foreach ($schoolI18ns as $schoolI18n) {
            $this->addSchoolI18n($schoolI18n);
        }

        $this->collSchoolI18ns = $schoolI18ns;
        $this->collSchoolI18nsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SchoolI18n objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related SchoolI18n objects.
     * @throws PropelException
     */
    public function countSchoolI18ns(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSchoolI18nsPartial && !$this->isNew();
        if (null === $this->collSchoolI18ns || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSchoolI18ns) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSchoolI18ns());
            }
            $query = SchoolI18nQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySchool($this)
                ->count($con);
        }

        return count($this->collSchoolI18ns);
    }

    /**
     * Method called to associate a SchoolI18n object to this object
     * through the SchoolI18n foreign key attribute.
     *
     * @param    SchoolI18n $l SchoolI18n
     * @return School The current object (for fluent API support)
     */
    public function addSchoolI18n(SchoolI18n $l)
    {
        if ($l && $locale = $l->getLocale()) {
            $this->setLocale($locale);
            $this->currentTranslations[$locale] = $l;
        }
        if ($this->collSchoolI18ns === null) {
            $this->initSchoolI18ns();
            $this->collSchoolI18nsPartial = true;
        }

        if (!in_array($l, $this->collSchoolI18ns->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSchoolI18n($l);

            if ($this->schoolI18nsScheduledForDeletion and $this->schoolI18nsScheduledForDeletion->contains($l)) {
                $this->schoolI18nsScheduledForDeletion->remove($this->schoolI18nsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	SchoolI18n $schoolI18n The schoolI18n object to add.
     */
    protected function doAddSchoolI18n($schoolI18n)
    {
        $this->collSchoolI18ns[]= $schoolI18n;
        $schoolI18n->setSchool($this);
    }

    /**
     * @param	SchoolI18n $schoolI18n The schoolI18n object to remove.
     * @return School The current object (for fluent API support)
     */
    public function removeSchoolI18n($schoolI18n)
    {
        if ($this->getSchoolI18ns()->contains($schoolI18n)) {
            $this->collSchoolI18ns->remove($this->collSchoolI18ns->search($schoolI18n));
            if (null === $this->schoolI18nsScheduledForDeletion) {
                $this->schoolI18nsScheduledForDeletion = clone $this->collSchoolI18ns;
                $this->schoolI18nsScheduledForDeletion->clear();
            }
            $this->schoolI18nsScheduledForDeletion[]= clone $schoolI18n;
            $schoolI18n->setSchool(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->code = null;
        $this->name = null;
        $this->url = null;
        $this->level_id = null;
        $this->address = null;
        $this->city = null;
        $this->state_id = null;
        $this->zip = null;
        $this->country_id = null;
        $this->organization_id = null;
        $this->phone = null;
        $this->fax = null;
        $this->mobile = null;
        $this->email = null;
        $this->website = null;
        $this->logo = null;
        $this->status = null;
        $this->confirmation = null;
        $this->sortable_rank = null;
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
            if ($this->collApplications) {
                foreach ($this->collApplications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCourses) {
                foreach ($this->collCourses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPages) {
                foreach ($this->collPages as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEmployees) {
                foreach ($this->collEmployees as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collQualifications) {
                foreach ($this->collQualifications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSchoolEmployments) {
                foreach ($this->collSchoolEmployments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSchoolEnrollments) {
                foreach ($this->collSchoolEnrollments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSchoolGradeLevels) {
                foreach ($this->collSchoolGradeLevels as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSchoolTerms) {
                foreach ($this->collSchoolTerms as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSchoolTests) {
                foreach ($this->collSchoolTests as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSchoolYears) {
                foreach ($this->collSchoolYears as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSchoolI18ns) {
                foreach ($this->collSchoolI18ns as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aState instanceof Persistent) {
              $this->aState->clearAllReferences($deep);
            }
            if ($this->aCountry instanceof Persistent) {
              $this->aCountry->clearAllReferences($deep);
            }
            if ($this->aOrganization instanceof Persistent) {
              $this->aOrganization->clearAllReferences($deep);
            }
            if ($this->aLevel instanceof Persistent) {
              $this->aLevel->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        // i18n behavior
        $this->currentLocale = 'en_US';
        $this->currentTranslations = null;

        if ($this->collApplications instanceof PropelCollection) {
            $this->collApplications->clearIterator();
        }
        $this->collApplications = null;
        if ($this->collCourses instanceof PropelCollection) {
            $this->collCourses->clearIterator();
        }
        $this->collCourses = null;
        if ($this->collPages instanceof PropelCollection) {
            $this->collPages->clearIterator();
        }
        $this->collPages = null;
        if ($this->collEmployees instanceof PropelCollection) {
            $this->collEmployees->clearIterator();
        }
        $this->collEmployees = null;
        if ($this->collQualifications instanceof PropelCollection) {
            $this->collQualifications->clearIterator();
        }
        $this->collQualifications = null;
        if ($this->collSchoolEmployments instanceof PropelCollection) {
            $this->collSchoolEmployments->clearIterator();
        }
        $this->collSchoolEmployments = null;
        if ($this->collSchoolEnrollments instanceof PropelCollection) {
            $this->collSchoolEnrollments->clearIterator();
        }
        $this->collSchoolEnrollments = null;
        if ($this->collSchoolGradeLevels instanceof PropelCollection) {
            $this->collSchoolGradeLevels->clearIterator();
        }
        $this->collSchoolGradeLevels = null;
        if ($this->collSchoolTerms instanceof PropelCollection) {
            $this->collSchoolTerms->clearIterator();
        }
        $this->collSchoolTerms = null;
        if ($this->collSchoolTests instanceof PropelCollection) {
            $this->collSchoolTests->clearIterator();
        }
        $this->collSchoolTests = null;
        if ($this->collSchoolYears instanceof PropelCollection) {
            $this->collSchoolYears->clearIterator();
        }
        $this->collSchoolYears = null;
        if ($this->collSchoolI18ns instanceof PropelCollection) {
            $this->collSchoolI18ns->clearIterator();
        }
        $this->collSchoolI18ns = null;
        $this->aState = null;
        $this->aCountry = null;
        $this->aOrganization = null;
        $this->aLevel = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(SchoolPeer::DEFAULT_STRING_FORMAT);
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

    // i18n behavior

    /**
     * Sets the locale for translations
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     *
     * @return    School The current object (for fluent API support)
     */
    public function setLocale($locale = 'en_US')
    {
        $this->currentLocale = $locale;

        return $this;
    }

    /**
     * Gets the locale for translations
     *
     * @return    string $locale Locale to use for the translation, e.g. 'fr_FR'
     */
    public function getLocale()
    {
        return $this->currentLocale;
    }

    /**
     * Returns the current translation for a given locale
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     * @param     PropelPDO $con an optional connection object
     *
     * @return SchoolI18n */
    public function getTranslation($locale = 'en_US', PropelPDO $con = null)
    {
        if (!isset($this->currentTranslations[$locale])) {
            if (null !== $this->collSchoolI18ns) {
                foreach ($this->collSchoolI18ns as $translation) {
                    if ($translation->getLocale() == $locale) {
                        $this->currentTranslations[$locale] = $translation;

                        return $translation;
                    }
                }
            }
            if ($this->isNew()) {
                $translation = new SchoolI18n();
                $translation->setLocale($locale);
            } else {
                $translation = SchoolI18nQuery::create()
                    ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                    ->findOneOrCreate($con);
                $this->currentTranslations[$locale] = $translation;
            }
            $this->addSchoolI18n($translation);
        }

        return $this->currentTranslations[$locale];
    }

    /**
     * Remove the translation for a given locale
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     * @param     PropelPDO $con an optional connection object
     *
     * @return    School The current object (for fluent API support)
     */
    public function removeTranslation($locale = 'en_US', PropelPDO $con = null)
    {
        if (!$this->isNew()) {
            SchoolI18nQuery::create()
                ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                ->delete($con);
        }
        if (isset($this->currentTranslations[$locale])) {
            unset($this->currentTranslations[$locale]);
        }
        foreach ($this->collSchoolI18ns as $key => $translation) {
            if ($translation->getLocale() == $locale) {
                unset($this->collSchoolI18ns[$key]);
                break;
            }
        }

        return $this;
    }

    /**
     * Returns the current translation
     *
     * @param     PropelPDO $con an optional connection object
     *
     * @return SchoolI18n */
    public function getCurrentTranslation(PropelPDO $con = null)
    {
        return $this->getTranslation($this->getLocale(), $con);
    }


        /**
         * Get the [description] column value.
         *
         * @return string
         */
        public function getDescription()
        {
        return $this->getCurrentTranslation()->getDescription();
    }


        /**
         * Set the value of [description] column.
         *
         * @param  string $v new value
         * @return SchoolI18n The current object (for fluent API support)
         */
        public function setDescription($v)
        {    $this->getCurrentTranslation()->setDescription($v);

        return $this;
    }


        /**
         * Get the [excerpt] column value.
         *
         * @return string
         */
        public function getExcerpt()
        {
        return $this->getCurrentTranslation()->getExcerpt();
    }


        /**
         * Set the value of [excerpt] column.
         *
         * @param  string $v new value
         * @return SchoolI18n The current object (for fluent API support)
         */
        public function setExcerpt($v)
        {    $this->getCurrentTranslation()->setExcerpt($v);

        return $this;
    }

    // sluggable behavior

    /**
     * Wrap the setter for slug value
     *
     * @param   string
     * @return  School
     */
    public function setSlug($v)
    {
        return $this->setUrl($v);
    }

    /**
     * Wrap the getter for slug value
     *
     * @return  string
     */
    public function getSlug()
    {
        return $this->getUrl();
    }

    /**
     * Create a unique slug based on the object
     *
     * @return string The object slug
     */
    protected function createSlug()
    {
        $slug = $this->createRawSlug();
        $slug = $this->limitSlugSize($slug);
        $slug = $this->makeSlugUnique($slug);

        return $slug;
    }

    /**
     * Create the slug from the appropriate columns
     *
     * @return string
     */
    protected function createRawSlug()
    {
        return '' . $this->cleanupSlugPart($this->getName()) . '';
    }

    /**
     * Cleanup a string to make a slug of it
     * Removes special characters, replaces blanks with a separator, and trim it
     *
     * @param     string $slug        the text to slugify
     * @param     string $replacement the separator used by slug
     * @return    string               the slugified text
     */
    protected static function cleanupSlugPart($slug, $replacement = '-')
    {
        // transliterate
        if (function_exists('iconv')) {
            $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
        }

        // lowercase
        if (function_exists('mb_strtolower')) {
            $slug = mb_strtolower($slug);
        } else {
            $slug = strtolower($slug);
        }

        // remove accents resulting from OSX's iconv
        $slug = str_replace(array('\'', '`', '^'), '', $slug);

        // replace non letter or digits with separator
        $slug = preg_replace('/[^\w\/]+/u', $replacement, $slug);

        // trim
        $slug = trim($slug, $replacement);

        if (empty($slug)) {
            return 'n-a';
        }

        return $slug;
    }


    /**
     * Make sure the slug is short enough to accommodate the column size
     *
     * @param    string $slug                   the slug to check
     * @param    int    $incrementReservedSpace the number of characters to keep empty
     *
     * @return string                            the truncated slug
     */
    protected static function limitSlugSize($slug, $incrementReservedSpace = 3)
    {
        // check length, as suffix could put it over maximum
        if (strlen($slug) > (100 - $incrementReservedSpace)) {
            $slug = substr($slug, 0, 100 - $incrementReservedSpace);
        }

        return $slug;
    }


    /**
     * Get the slug, ensuring its uniqueness
     *
     * @param    string $slug            the slug to check
     * @param    string $separator       the separator used by slug
     * @param    int    $alreadyExists   false for the first try, true for the second, and take the high count + 1
     * @return   string                   the unique slug
     */
    protected function makeSlugUnique($slug, $separator = '_', $alreadyExists = false)
    {
        if (!$alreadyExists) {
            $slug2 = $slug;
        } else {
            $slug2 = $slug . $separator;
        }

         $query = SchoolQuery::create('q')
        ->where('q.Url ' . ($alreadyExists ? 'REGEXP' : '=') . ' ?', $alreadyExists ? '^' . $slug2 . '[0-9]+$' : $slug2)->prune($this)
        ;

        if (!$alreadyExists) {
            $count = $query->count();
            if ($count > 0) {
                return $this->makeSlugUnique($slug, $separator, true);
            }

            return $slug2;
        }

        // Already exists
        $object = $query
            ->addDescendingOrderByColumn('LENGTH(url)')
            ->addDescendingOrderByColumn('url')
        ->findOne();

        // First duplicate slug
        if (null == $object) {
            return $slug2 . '1';
        }

        $slugNum = substr($object->getUrl(), strlen($slug) + 1);
        if ('0' === $slugNum[0]) {
            $slugNum[0] = 1;
        }

        return $slug2 . ($slugNum + 1);
    }

    // sortable behavior

    /**
     * Wrap the getter for rank value
     *
     * @return    int
     */
    public function getRank()
    {
        return $this->sortable_rank;
    }

    /**
     * Wrap the setter for rank value
     *
     * @param     int
     * @return    School
     */
    public function setRank($v)
    {
        return $this->setSortableRank($v);
    }

    /**
     * Check if the object is first in the list, i.e. if it has 1 for rank
     *
     * @return    boolean
     */
    public function isFirst()
    {
        return $this->getSortableRank() == 1;
    }

    /**
     * Check if the object is last in the list, i.e. if its rank is the highest rank
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    boolean
     */
    public function isLast(PropelPDO $con = null)
    {
        return $this->getSortableRank() == SchoolQuery::create()->getMaxRankArray($con);
    }

    /**
     * Get the next item in the list, i.e. the one for which rank is immediately higher
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    School
     */
    public function getNext(PropelPDO $con = null)
    {

        $query = SchoolQuery::create();

        $query->filterByRank($this->getSortableRank() + 1);


        return $query->findOne($con);
    }

    /**
     * Get the previous item in the list, i.e. the one for which rank is immediately lower
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    School
     */
    public function getPrevious(PropelPDO $con = null)
    {

        $query = SchoolQuery::create();

        $query->filterByRank($this->getSortableRank() - 1);


        return $query->findOne($con);
    }

    /**
     * Insert at specified rank
     * The modifications are not persisted until the object is saved.
     *
     * @param     integer    $rank rank value
     * @param     PropelPDO  $con      optional connection
     *
     * @return    School the current object
     *
     * @throws    PropelException
     */
    public function insertAtRank($rank, PropelPDO $con = null)
    {
        $maxRank = SchoolQuery::create()->getMaxRankArray($con);
        if ($rank < 1 || $rank > $maxRank + 1) {
            throw new PropelException('Invalid rank ' . $rank);
        }
        // move the object in the list, at the given rank
        $this->setSortableRank($rank);
        if ($rank != $maxRank + 1) {
            // Keep the list modification query for the save() transaction
            $this->sortableQueries []= array(
                'callable'  => array(self::PEER, 'shiftRank'),
                'arguments' => array(1, $rank, null, )
            );
        }

        return $this;
    }

    /**
     * Insert in the last rank
     * The modifications are not persisted until the object is saved.
     *
     * @param PropelPDO $con optional connection
     *
     * @return    School the current object
     *
     * @throws    PropelException
     */
    public function insertAtBottom(PropelPDO $con = null)
    {
        $this->setSortableRank(SchoolQuery::create()->getMaxRankArray($con) + 1);

        return $this;
    }

    /**
     * Insert in the first rank
     * The modifications are not persisted until the object is saved.
     *
     * @return    School the current object
     */
    public function insertAtTop()
    {
        return $this->insertAtRank(1);
    }

    /**
     * Move the object to a new rank, and shifts the rank
     * Of the objects inbetween the old and new rank accordingly
     *
     * @param     integer   $newRank rank value
     * @param     PropelPDO $con optional connection
     *
     * @return    School the current object
     *
     * @throws    PropelException
     */
    public function moveToRank($newRank, PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be moved. Please use insertAtRank() instead');
        }
        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME);
        }
        if ($newRank < 1 || $newRank > SchoolQuery::create()->getMaxRankArray($con)) {
            throw new PropelException('Invalid rank ' . $newRank);
        }

        $oldRank = $this->getSortableRank();
        if ($oldRank == $newRank) {
            return $this;
        }

        $con->beginTransaction();
        try {
            // shift the objects between the old and the new rank
            $delta = ($oldRank < $newRank) ? -1 : 1;
            SchoolPeer::shiftRank($delta, min($oldRank, $newRank), max($oldRank, $newRank), $con);

            // move the object to its new rank
            $this->setSortableRank($newRank);
            $this->save($con);

            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Exchange the rank of the object with the one passed as argument, and saves both objects
     *
     * @param     School $object
     * @param     PropelPDO $con optional connection
     *
     * @return    School the current object
     *
     * @throws Exception if the database cannot execute the two updates
     */
    public function swapWith($object, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $oldRank = $this->getSortableRank();
            $newRank = $object->getSortableRank();
            $this->setSortableRank($newRank);
            $this->save($con);
            $object->setSortableRank($oldRank);
            $object->save($con);
            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Move the object higher in the list, i.e. exchanges its rank with the one of the previous object
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    School the current object
     */
    public function moveUp(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $prev = $this->getPrevious($con);
            $this->swapWith($prev, $con);
            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Move the object higher in the list, i.e. exchanges its rank with the one of the next object
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    School the current object
     */
    public function moveDown(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $next = $this->getNext($con);
            $this->swapWith($next, $con);
            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Move the object to the top of the list
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    School the current object
     */
    public function moveToTop(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }

        return $this->moveToRank(1, $con);
    }

    /**
     * Move the object to the bottom of the list
     *
     * @param     PropelPDO $con optional connection
     *
     * @return integer the old object's rank
     */
    public function moveToBottom(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return false;
        }
        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $bottom = SchoolQuery::create()->getMaxRankArray($con);
            $res = $this->moveToRank($bottom, $con);
            $con->commit();

            return $res;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Removes the current object from the list.
     * The modifications are not persisted until the object is saved.
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    School the current object
     */
    public function removeFromList(PropelPDO $con = null)
    {
        // Keep the list modification query for the save() transaction
        $this->sortableQueries []= array(
            'callable'  => array(self::PEER, 'shiftRank'),
            'arguments' => array(-1, $this->getSortableRank() + 1, null)
        );
        // remove the object from the list
        $this->setSortableRank(null);

        return $this;
    }

    /**
     * Execute queries that were saved to be run inside the save transaction
     */
    protected function processSortableQueries($con)
    {
        foreach ($this->sortableQueries as $query) {
            $query['arguments'][]= $con;
            call_user_func_array($query['callable'], $query['arguments']);
        }
        $this->sortableQueries = array();
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     School The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = SchoolPeer::UPDATED_AT;

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

    // sortable_relation behavior

    /**
     * Moves related Qualifications to null scope
     * @param PropelPDO $con A connection object
     */
    public function moveRelatedQualificationsToNullScope(PropelPDO $con = null)
    {
        $maxRank = QualificationQuery::create()->getMaxRank($this->getPrimaryKey(), $con);
        if (null !== $maxRank) { // getMaxRank() returns null for empty tables
            QualificationPeer::shiftRank($maxRank, null, null, $this->getPrimaryKey(), $con);
        }
    }

}
