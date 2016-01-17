<?php

namespace PGS\CoreDomainBundle\Model\om;

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
use FOS\UserBundle\Model\GroupInterface;
use Glorpen\Propel\PropelBundle\Dispatcher\EventDispatcherProxy;
use Glorpen\Propel\PropelBundle\Events\ModelEvent;
use PGS\CoreDomainBundle\Model\Group;
use PGS\CoreDomainBundle\Model\GroupQuery;
use PGS\CoreDomainBundle\Model\LicensePayment;
use PGS\CoreDomainBundle\Model\LicensePaymentQuery;
use PGS\CoreDomainBundle\Model\Page;
use PGS\CoreDomainBundle\Model\PageQuery;
use PGS\CoreDomainBundle\Model\User;
use PGS\CoreDomainBundle\Model\UserGroup;
use PGS\CoreDomainBundle\Model\UserGroupQuery;
use PGS\CoreDomainBundle\Model\UserLicense;
use PGS\CoreDomainBundle\Model\UserLicenseQuery;
use PGS\CoreDomainBundle\Model\UserLog;
use PGS\CoreDomainBundle\Model\UserLogQuery;
use PGS\CoreDomainBundle\Model\UserPeer;
use PGS\CoreDomainBundle\Model\UserProfile;
use PGS\CoreDomainBundle\Model\UserProfileQuery;
use PGS\CoreDomainBundle\Model\UserQuery;
use PGS\CoreDomainBundle\Model\Announcement\Announcement;
use PGS\CoreDomainBundle\Model\Announcement\AnnouncementQuery;
use PGS\CoreDomainBundle\Model\Application\Application;
use PGS\CoreDomainBundle\Model\Application\ApplicationQuery;
use PGS\CoreDomainBundle\Model\Behavior\Behavior;
use PGS\CoreDomainBundle\Model\Behavior\BehaviorQuery;
use PGS\CoreDomainBundle\Model\Category\Category;
use PGS\CoreDomainBundle\Model\Category\CategoryQuery;
use PGS\CoreDomainBundle\Model\Employee\Employee;
use PGS\CoreDomainBundle\Model\Employee\EmployeeQuery;
use PGS\CoreDomainBundle\Model\Ethnicity\Ethnicity;
use PGS\CoreDomainBundle\Model\Ethnicity\EthnicityQuery;
use PGS\CoreDomainBundle\Model\Message\Message;
use PGS\CoreDomainBundle\Model\Message\MessageQuery;
use PGS\CoreDomainBundle\Model\Organization\Organization;
use PGS\CoreDomainBundle\Model\Organization\OrganizationQuery;
use PGS\CoreDomainBundle\Model\ParentStudent\ParentStudent;
use PGS\CoreDomainBundle\Model\ParentStudent\ParentStudentQuery;
use PGS\CoreDomainBundle\Model\Religion\Religion;
use PGS\CoreDomainBundle\Model\Religion\ReligionQuery;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourse;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourseQuery;
use PGS\CoreDomainBundle\Model\Student\Student;
use PGS\CoreDomainBundle\Model\Student\StudentQuery;
use PGS\CoreDomainBundle\Model\StudentAvatar\StudentAvatar;
use PGS\CoreDomainBundle\Model\StudentAvatar\StudentAvatarQuery;
use PGS\CoreDomainBundle\Model\Test\Test;
use PGS\CoreDomainBundle\Model\Test\TestQuery;

abstract class BaseUser extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'PGS\\CoreDomainBundle\\Model\\UserPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        UserPeer
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
     * The value for the username field.
     * @var        string
     */
    protected $username;

    /**
     * The value for the username_canonical field.
     * @var        string
     */
    protected $username_canonical;

    /**
     * The value for the email field.
     * @var        string
     */
    protected $email;

    /**
     * The value for the email_canonical field.
     * @var        string
     */
    protected $email_canonical;

    /**
     * The value for the enabled field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $enabled;

    /**
     * The value for the salt field.
     * @var        string
     */
    protected $salt;

    /**
     * The value for the password field.
     * @var        string
     */
    protected $password;

    /**
     * The value for the last_login field.
     * @var        string
     */
    protected $last_login;

    /**
     * The value for the locked field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $locked;

    /**
     * The value for the expired field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $expired;

    /**
     * The value for the expires_at field.
     * @var        string
     */
    protected $expires_at;

    /**
     * The value for the confirmation_token field.
     * @var        string
     */
    protected $confirmation_token;

    /**
     * The value for the password_requested_at field.
     * @var        string
     */
    protected $password_requested_at;

    /**
     * The value for the credentials_expired field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $credentials_expired;

    /**
     * The value for the credentials_expire_at field.
     * @var        string
     */
    protected $credentials_expire_at;

    /**
     * The value for the type field.
     * Note: this column has a database default value of: 5
     * @var        int
     */
    protected $type;

    /**
     * The value for the status field.
     * Note: this column has a database default value of: 1
     * @var        int
     */
    protected $status;

    /**
     * The value for the roles field.
     * @var        array
     */
    protected $roles;

    /**
     * The unserialized $roles value - i.e. the persisted object.
     * This is necessary to avoid repeated calls to unserialize() at runtime.
     * @var        object
     */
    protected $roles_unserialized;

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
     * @var        PropelObjectCollection|Announcement[] Collection to store aggregation of Announcement objects.
     */
    protected $collAnnouncements;
    protected $collAnnouncementsPartial;

    /**
     * @var        PropelObjectCollection|Application[] Collection to store aggregation of Application objects.
     */
    protected $collApplications;
    protected $collApplicationsPartial;

    /**
     * @var        PropelObjectCollection|Behavior[] Collection to store aggregation of Behavior objects.
     */
    protected $collBehaviors;
    protected $collBehaviorsPartial;

    /**
     * @var        PropelObjectCollection|Category[] Collection to store aggregation of Category objects.
     */
    protected $collCategories;
    protected $collCategoriesPartial;

    /**
     * @var        PropelObjectCollection|UserGroup[] Collection to store aggregation of UserGroup objects.
     */
    protected $collUserGroups;
    protected $collUserGroupsPartial;

    /**
     * @var        PropelObjectCollection|UserLog[] Collection to store aggregation of UserLog objects.
     */
    protected $collUserLogs;
    protected $collUserLogsPartial;

    /**
     * @var        PropelObjectCollection|LicensePayment[] Collection to store aggregation of LicensePayment objects.
     */
    protected $collLicensePayments;
    protected $collLicensePaymentsPartial;

    /**
     * @var        PropelObjectCollection|UserLicense[] Collection to store aggregation of UserLicense objects.
     */
    protected $collUserLicenses;
    protected $collUserLicensesPartial;

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
     * @var        PropelObjectCollection|Ethnicity[] Collection to store aggregation of Ethnicity objects.
     */
    protected $collEthnicities;
    protected $collEthnicitiesPartial;

    /**
     * @var        PropelObjectCollection|Message[] Collection to store aggregation of Message objects.
     */
    protected $collMessagesRelatedByFromId;
    protected $collMessagesRelatedByFromIdPartial;

    /**
     * @var        PropelObjectCollection|Message[] Collection to store aggregation of Message objects.
     */
    protected $collMessagesRelatedByToId;
    protected $collMessagesRelatedByToIdPartial;

    /**
     * @var        PropelObjectCollection|Organization[] Collection to store aggregation of Organization objects.
     */
    protected $collOrganizations;
    protected $collOrganizationsPartial;

    /**
     * @var        PropelObjectCollection|ParentStudent[] Collection to store aggregation of ParentStudent objects.
     */
    protected $collParentStudents;
    protected $collParentStudentsPartial;

    /**
     * @var        PropelObjectCollection|Religion[] Collection to store aggregation of Religion objects.
     */
    protected $collReligions;
    protected $collReligionsPartial;

    /**
     * @var        PropelObjectCollection|SchoolClassCourse[] Collection to store aggregation of SchoolClassCourse objects.
     */
    protected $collSchoolClassCoursesRelatedByPrimaryTeacherId;
    protected $collSchoolClassCoursesRelatedByPrimaryTeacherIdPartial;

    /**
     * @var        PropelObjectCollection|SchoolClassCourse[] Collection to store aggregation of SchoolClassCourse objects.
     */
    protected $collSchoolClassCoursesRelatedBySecondaryTeacherId;
    protected $collSchoolClassCoursesRelatedBySecondaryTeacherIdPartial;

    /**
     * @var        PropelObjectCollection|Student[] Collection to store aggregation of Student objects.
     */
    protected $collStudents;
    protected $collStudentsPartial;

    /**
     * @var        PropelObjectCollection|StudentAvatar[] Collection to store aggregation of StudentAvatar objects.
     */
    protected $collStudentAvatars;
    protected $collStudentAvatarsPartial;

    /**
     * @var        PropelObjectCollection|Test[] Collection to store aggregation of Test objects.
     */
    protected $collTests;
    protected $collTestsPartial;

    /**
     * @var        UserProfile one-to-one related UserProfile object
     */
    protected $singleUserProfile;

    /**
     * @var        PropelObjectCollection|Group[] Collection to store aggregation of Group objects.
     */
    protected $collGroups;

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
    protected $groupsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $announcementsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $applicationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $behaviorsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $categoriesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $userGroupsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $userLogsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $licensePaymentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $userLicensesScheduledForDeletion = null;

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
    protected $ethnicitiesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $messagesRelatedByFromIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $messagesRelatedByToIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $organizationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $parentStudentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $religionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $schoolClassCoursesRelatedByPrimaryTeacherIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $schoolClassCoursesRelatedBySecondaryTeacherIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $studentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $studentAvatarsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $testsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->enabled = false;
        $this->locked = false;
        $this->expired = false;
        $this->credentials_expired = false;
        $this->type = 5;
        $this->status = 1;
    }

    /**
     * Initializes internal state of BaseUser object.
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
     * Get the [username] column value.
     *
     * @return string
     */
    public function getUsername()
    {

        return $this->username;
    }

    /**
     * Get the [username_canonical] column value.
     *
     * @return string
     */
    public function getUsernameCanonical()
    {

        return $this->username_canonical;
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
     * Get the [email_canonical] column value.
     *
     * @return string
     */
    public function getEmailCanonical()
    {

        return $this->email_canonical;
    }

    /**
     * Get the [enabled] column value.
     *
     * @return boolean
     */
    public function getEnabled()
    {

        return $this->enabled;
    }

    /**
     * Get the [salt] column value.
     *
     * @return string
     */
    public function getSalt()
    {

        return $this->salt;
    }

    /**
     * Get the [password] column value.
     *
     * @return string
     */
    public function getPassword()
    {

        return $this->password;
    }

    /**
     * Get the [optionally formatted] temporal [last_login] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getLastLogin($format = null)
    {
        if ($this->last_login === null) {
            return null;
        }

        if ($this->last_login === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->last_login);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->last_login, true), $x);
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
     * Get the [locked] column value.
     *
     * @return boolean
     */
    public function getLocked()
    {

        return $this->locked;
    }

    /**
     * Get the [expired] column value.
     *
     * @return boolean
     */
    public function getExpired()
    {

        return $this->expired;
    }

    /**
     * Get the [optionally formatted] temporal [expires_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getExpiresAt($format = null)
    {
        if ($this->expires_at === null) {
            return null;
        }

        if ($this->expires_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->expires_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->expires_at, true), $x);
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
     * Get the [confirmation_token] column value.
     *
     * @return string
     */
    public function getConfirmationToken()
    {

        return $this->confirmation_token;
    }

    /**
     * Get the [optionally formatted] temporal [password_requested_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getPasswordRequestedAt($format = null)
    {
        if ($this->password_requested_at === null) {
            return null;
        }

        if ($this->password_requested_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->password_requested_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->password_requested_at, true), $x);
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
     * Get the [credentials_expired] column value.
     *
     * @return boolean
     */
    public function getCredentialsExpired()
    {

        return $this->credentials_expired;
    }

    /**
     * Get the [optionally formatted] temporal [credentials_expire_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCredentialsExpireAt($format = null)
    {
        if ($this->credentials_expire_at === null) {
            return null;
        }

        if ($this->credentials_expire_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->credentials_expire_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->credentials_expire_at, true), $x);
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
     * Get the [type] column value.
     *
     * @return int
     * @throws PropelException - if the stored enum key is unknown.
     */
    public function getType()
    {
        if (null === $this->type) {
            return null;
        }
        $valueSet = UserPeer::getValueSet(UserPeer::TYPE);
        if (!isset($valueSet[$this->type])) {
            throw new PropelException('Unknown stored enum key: ' . $this->type);
        }

        return $valueSet[$this->type];
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
        $valueSet = UserPeer::getValueSet(UserPeer::STATUS);
        if (!isset($valueSet[$this->status])) {
            throw new PropelException('Unknown stored enum key: ' . $this->status);
        }

        return $valueSet[$this->status];
    }

    /**
     * Get the [roles] column value.
     *
     * @return array
     */
    public function getRoles()
    {
        if (null === $this->roles_unserialized) {
            $this->roles_unserialized = array();
        }
        if (!$this->roles_unserialized && null !== $this->roles) {
            $roles_unserialized = substr($this->roles, 2, -2);
            $this->roles_unserialized = $roles_unserialized ? explode(' | ', $roles_unserialized) : array();
        }

        return $this->roles_unserialized;
    }

    /**
     * Test the presence of a value in the [roles] array column value.
     * @param mixed $value
     *
     * @return boolean
     */
    public function hasRole($value)
    {
        return in_array($value, $this->getRoles());
    } // hasRole()

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
     * @return User The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = UserPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [username] column.
     *
     * @param  string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->username !== $v) {
            $this->username = $v;
            $this->modifiedColumns[] = UserPeer::USERNAME;
        }


        return $this;
    } // setUsername()

    /**
     * Set the value of [username_canonical] column.
     *
     * @param  string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setUsernameCanonical($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->username_canonical !== $v) {
            $this->username_canonical = $v;
            $this->modifiedColumns[] = UserPeer::USERNAME_CANONICAL;
        }


        return $this;
    } // setUsernameCanonical()

    /**
     * Set the value of [email] column.
     *
     * @param  string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[] = UserPeer::EMAIL;
        }


        return $this;
    } // setEmail()

    /**
     * Set the value of [email_canonical] column.
     *
     * @param  string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setEmailCanonical($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email_canonical !== $v) {
            $this->email_canonical = $v;
            $this->modifiedColumns[] = UserPeer::EMAIL_CANONICAL;
        }


        return $this;
    } // setEmailCanonical()

    /**
     * Sets the value of the [enabled] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return User The current object (for fluent API support)
     */
    public function setEnabled($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->enabled !== $v) {
            $this->enabled = $v;
            $this->modifiedColumns[] = UserPeer::ENABLED;
        }


        return $this;
    } // setEnabled()

    /**
     * Set the value of [salt] column.
     *
     * @param  string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setSalt($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->salt !== $v) {
            $this->salt = $v;
            $this->modifiedColumns[] = UserPeer::SALT;
        }


        return $this;
    } // setSalt()

    /**
     * Set the value of [password] column.
     *
     * @param  string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[] = UserPeer::PASSWORD;
        }


        return $this;
    } // setPassword()

    /**
     * Sets the value of [last_login] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return User The current object (for fluent API support)
     */
    public function setLastLogin(DateTime $v = null)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->last_login !== null || $dt !== null) {
            $currentDateAsString = ($this->last_login !== null && $tmpDt = new DateTime($this->last_login)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->last_login = $newDateAsString;
                $this->modifiedColumns[] = UserPeer::LAST_LOGIN;
            }
        } // if either are not null


        return $this;
    } // setLastLogin()

    /**
     * Sets the value of the [locked] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return User The current object (for fluent API support)
     */
    public function setLocked($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->locked !== $v) {
            $this->locked = $v;
            $this->modifiedColumns[] = UserPeer::LOCKED;
        }


        return $this;
    } // setLocked()

    /**
     * Sets the value of the [expired] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return User The current object (for fluent API support)
     */
    public function setExpired($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->expired !== $v) {
            $this->expired = $v;
            $this->modifiedColumns[] = UserPeer::EXPIRED;
        }


        return $this;
    } // setExpired()

    /**
     * Sets the value of [expires_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return User The current object (for fluent API support)
     */
    public function setExpiresAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->expires_at !== null || $dt !== null) {
            $currentDateAsString = ($this->expires_at !== null && $tmpDt = new DateTime($this->expires_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->expires_at = $newDateAsString;
                $this->modifiedColumns[] = UserPeer::EXPIRES_AT;
            }
        } // if either are not null


        return $this;
    } // setExpiresAt()

    /**
     * Set the value of [confirmation_token] column.
     *
     * @param  string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setConfirmationToken($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->confirmation_token !== $v) {
            $this->confirmation_token = $v;
            $this->modifiedColumns[] = UserPeer::CONFIRMATION_TOKEN;
        }


        return $this;
    } // setConfirmationToken()

    /**
     * Sets the value of [password_requested_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return User The current object (for fluent API support)
     */
    public function setPasswordRequestedAt(DateTime $v = null)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->password_requested_at !== null || $dt !== null) {
            $currentDateAsString = ($this->password_requested_at !== null && $tmpDt = new DateTime($this->password_requested_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->password_requested_at = $newDateAsString;
                $this->modifiedColumns[] = UserPeer::PASSWORD_REQUESTED_AT;
            }
        } // if either are not null


        return $this;
    } // setPasswordRequestedAt()

    /**
     * Sets the value of the [credentials_expired] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return User The current object (for fluent API support)
     */
    public function setCredentialsExpired($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->credentials_expired !== $v) {
            $this->credentials_expired = $v;
            $this->modifiedColumns[] = UserPeer::CREDENTIALS_EXPIRED;
        }


        return $this;
    } // setCredentialsExpired()

    /**
     * Sets the value of [credentials_expire_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return User The current object (for fluent API support)
     */
    public function setCredentialsExpireAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->credentials_expire_at !== null || $dt !== null) {
            $currentDateAsString = ($this->credentials_expire_at !== null && $tmpDt = new DateTime($this->credentials_expire_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->credentials_expire_at = $newDateAsString;
                $this->modifiedColumns[] = UserPeer::CREDENTIALS_EXPIRE_AT;
            }
        } // if either are not null


        return $this;
    } // setCredentialsExpireAt()

    /**
     * Set the value of [type] column.
     *
     * @param  int $v new value
     * @return User The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setType($v)
    {
        if ($v !== null) {
            $valueSet = UserPeer::getValueSet(UserPeer::TYPE);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->type !== $v) {
            $this->type = $v;
            $this->modifiedColumns[] = UserPeer::TYPE;
        }


        return $this;
    } // setType()

    /**
     * Set the value of [status] column.
     *
     * @param  int $v new value
     * @return User The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            $valueSet = UserPeer::getValueSet(UserPeer::STATUS);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[] = UserPeer::STATUS;
        }


        return $this;
    } // setStatus()

    /**
     * Set the value of [roles] column.
     *
     * @param  array $v new value
     * @return User The current object (for fluent API support)
     */
    public function setRoles(array $v)
    {
        if ($this->roles_unserialized !== $v) {
            $this->roles_unserialized = $v;
            $this->roles = '| ' . implode(' | ', (array) $v) . ' |';
            $this->modifiedColumns[] = UserPeer::ROLES;
        }


        return $this;
    } // setRoles()

    /**
     * Adds a value to the [roles] array column value.
     * @param mixed $value
     *
     * @return User The current object (for fluent API support)
     */
    public function addRole($value)
    {
        $currentArray = $this->getRoles();
        $currentArray []= $value;
        $this->setRoles($currentArray);

        return $this;
    } // addRole()

    /**
     * Removes a value from the [roles] array column value.
     * @param mixed $value
     *
     * @return User The current object (for fluent API support)
     */
    public function removeRole($value)
    {
        $targetArray = array();
        foreach ($this->getRoles() as $element) {
            if ($element != $value) {
                $targetArray []= $element;
            }
        }
        $this->setRoles($targetArray);

        return $this;
    } // removeRole()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return User The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = UserPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return User The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = UserPeer::UPDATED_AT;
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
            if ($this->enabled !== false) {
                return false;
            }

            if ($this->locked !== false) {
                return false;
            }

            if ($this->expired !== false) {
                return false;
            }

            if ($this->credentials_expired !== false) {
                return false;
            }

            if ($this->type !== 5) {
                return false;
            }

            if ($this->status !== 1) {
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
            $this->username = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->username_canonical = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->email = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->email_canonical = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->enabled = ($row[$startcol + 5] !== null) ? (boolean) $row[$startcol + 5] : null;
            $this->salt = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->password = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->last_login = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->locked = ($row[$startcol + 9] !== null) ? (boolean) $row[$startcol + 9] : null;
            $this->expired = ($row[$startcol + 10] !== null) ? (boolean) $row[$startcol + 10] : null;
            $this->expires_at = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->confirmation_token = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
            $this->password_requested_at = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
            $this->credentials_expired = ($row[$startcol + 14] !== null) ? (boolean) $row[$startcol + 14] : null;
            $this->credentials_expire_at = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
            $this->type = ($row[$startcol + 16] !== null) ? (int) $row[$startcol + 16] : null;
            $this->status = ($row[$startcol + 17] !== null) ? (int) $row[$startcol + 17] : null;
            $this->roles = $row[$startcol + 18];
            $this->roles_unserialized = null;
            $this->created_at = ($row[$startcol + 19] !== null) ? (string) $row[$startcol + 19] : null;
            $this->updated_at = ($row[$startcol + 20] !== null) ? (string) $row[$startcol + 20] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 21; // 21 = UserPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating User object", $e);
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
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = UserPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collAnnouncements = null;

            $this->collApplications = null;

            $this->collBehaviors = null;

            $this->collCategories = null;

            $this->collUserGroups = null;

            $this->collUserLogs = null;

            $this->collLicensePayments = null;

            $this->collUserLicenses = null;

            $this->collPages = null;

            $this->collEmployees = null;

            $this->collEthnicities = null;

            $this->collMessagesRelatedByFromId = null;

            $this->collMessagesRelatedByToId = null;

            $this->collOrganizations = null;

            $this->collParentStudents = null;

            $this->collReligions = null;

            $this->collSchoolClassCoursesRelatedByPrimaryTeacherId = null;

            $this->collSchoolClassCoursesRelatedBySecondaryTeacherId = null;

            $this->collStudents = null;

            $this->collStudentAvatars = null;

            $this->collTests = null;

            $this->singleUserProfile = null;

            $this->collGroups = null;
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
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
            $deleteQuery = UserQuery::create()
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
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                if (!$this->isColumnModified(UserPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(UserPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(UserPeer::UPDATED_AT)) {
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
                UserPeer::addInstanceToPool($this);
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

            if ($this->groupsScheduledForDeletion !== null) {
                if (!$this->groupsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->groupsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    UserGroupQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->groupsScheduledForDeletion = null;
                }

                foreach ($this->getGroups() as $group) {
                    if ($group->isModified()) {
                        $group->save($con);
                    }
                }
            } elseif ($this->collGroups) {
                foreach ($this->collGroups as $group) {
                    if ($group->isModified()) {
                        $group->save($con);
                    }
                }
            }

            if ($this->announcementsScheduledForDeletion !== null) {
                if (!$this->announcementsScheduledForDeletion->isEmpty()) {
                    AnnouncementQuery::create()
                        ->filterByPrimaryKeys($this->announcementsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->announcementsScheduledForDeletion = null;
                }
            }

            if ($this->collAnnouncements !== null) {
                foreach ($this->collAnnouncements as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->applicationsScheduledForDeletion !== null) {
                if (!$this->applicationsScheduledForDeletion->isEmpty()) {
                    foreach ($this->applicationsScheduledForDeletion as $application) {
                        // need to save related object because we set the relation to null
                        $application->save($con);
                    }
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

            if ($this->behaviorsScheduledForDeletion !== null) {
                if (!$this->behaviorsScheduledForDeletion->isEmpty()) {
                    foreach ($this->behaviorsScheduledForDeletion as $behavior) {
                        // need to save related object because we set the relation to null
                        $behavior->save($con);
                    }
                    $this->behaviorsScheduledForDeletion = null;
                }
            }

            if ($this->collBehaviors !== null) {
                foreach ($this->collBehaviors as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->categoriesScheduledForDeletion !== null) {
                if (!$this->categoriesScheduledForDeletion->isEmpty()) {
                    CategoryQuery::create()
                        ->filterByPrimaryKeys($this->categoriesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->categoriesScheduledForDeletion = null;
                }
            }

            if ($this->collCategories !== null) {
                foreach ($this->collCategories as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userGroupsScheduledForDeletion !== null) {
                if (!$this->userGroupsScheduledForDeletion->isEmpty()) {
                    UserGroupQuery::create()
                        ->filterByPrimaryKeys($this->userGroupsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userGroupsScheduledForDeletion = null;
                }
            }

            if ($this->collUserGroups !== null) {
                foreach ($this->collUserGroups as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userLogsScheduledForDeletion !== null) {
                if (!$this->userLogsScheduledForDeletion->isEmpty()) {
                    foreach ($this->userLogsScheduledForDeletion as $userLog) {
                        // need to save related object because we set the relation to null
                        $userLog->save($con);
                    }
                    $this->userLogsScheduledForDeletion = null;
                }
            }

            if ($this->collUserLogs !== null) {
                foreach ($this->collUserLogs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->licensePaymentsScheduledForDeletion !== null) {
                if (!$this->licensePaymentsScheduledForDeletion->isEmpty()) {
                    LicensePaymentQuery::create()
                        ->filterByPrimaryKeys($this->licensePaymentsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->licensePaymentsScheduledForDeletion = null;
                }
            }

            if ($this->collLicensePayments !== null) {
                foreach ($this->collLicensePayments as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userLicensesScheduledForDeletion !== null) {
                if (!$this->userLicensesScheduledForDeletion->isEmpty()) {
                    UserLicenseQuery::create()
                        ->filterByPrimaryKeys($this->userLicensesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userLicensesScheduledForDeletion = null;
                }
            }

            if ($this->collUserLicenses !== null) {
                foreach ($this->collUserLicenses as $referrerFK) {
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
                    foreach ($this->employeesScheduledForDeletion as $employee) {
                        // need to save related object because we set the relation to null
                        $employee->save($con);
                    }
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

            if ($this->ethnicitiesScheduledForDeletion !== null) {
                if (!$this->ethnicitiesScheduledForDeletion->isEmpty()) {
                    EthnicityQuery::create()
                        ->filterByPrimaryKeys($this->ethnicitiesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->ethnicitiesScheduledForDeletion = null;
                }
            }

            if ($this->collEthnicities !== null) {
                foreach ($this->collEthnicities as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->messagesRelatedByFromIdScheduledForDeletion !== null) {
                if (!$this->messagesRelatedByFromIdScheduledForDeletion->isEmpty()) {
                    MessageQuery::create()
                        ->filterByPrimaryKeys($this->messagesRelatedByFromIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->messagesRelatedByFromIdScheduledForDeletion = null;
                }
            }

            if ($this->collMessagesRelatedByFromId !== null) {
                foreach ($this->collMessagesRelatedByFromId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->messagesRelatedByToIdScheduledForDeletion !== null) {
                if (!$this->messagesRelatedByToIdScheduledForDeletion->isEmpty()) {
                    MessageQuery::create()
                        ->filterByPrimaryKeys($this->messagesRelatedByToIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->messagesRelatedByToIdScheduledForDeletion = null;
                }
            }

            if ($this->collMessagesRelatedByToId !== null) {
                foreach ($this->collMessagesRelatedByToId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->organizationsScheduledForDeletion !== null) {
                if (!$this->organizationsScheduledForDeletion->isEmpty()) {
                    foreach ($this->organizationsScheduledForDeletion as $organization) {
                        // need to save related object because we set the relation to null
                        $organization->save($con);
                    }
                    $this->organizationsScheduledForDeletion = null;
                }
            }

            if ($this->collOrganizations !== null) {
                foreach ($this->collOrganizations as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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

            if ($this->religionsScheduledForDeletion !== null) {
                if (!$this->religionsScheduledForDeletion->isEmpty()) {
                    ReligionQuery::create()
                        ->filterByPrimaryKeys($this->religionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->religionsScheduledForDeletion = null;
                }
            }

            if ($this->collReligions !== null) {
                foreach ($this->collReligions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->schoolClassCoursesRelatedByPrimaryTeacherIdScheduledForDeletion !== null) {
                if (!$this->schoolClassCoursesRelatedByPrimaryTeacherIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->schoolClassCoursesRelatedByPrimaryTeacherIdScheduledForDeletion as $schoolClassCourseRelatedByPrimaryTeacherId) {
                        // need to save related object because we set the relation to null
                        $schoolClassCourseRelatedByPrimaryTeacherId->save($con);
                    }
                    $this->schoolClassCoursesRelatedByPrimaryTeacherIdScheduledForDeletion = null;
                }
            }

            if ($this->collSchoolClassCoursesRelatedByPrimaryTeacherId !== null) {
                foreach ($this->collSchoolClassCoursesRelatedByPrimaryTeacherId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->schoolClassCoursesRelatedBySecondaryTeacherIdScheduledForDeletion !== null) {
                if (!$this->schoolClassCoursesRelatedBySecondaryTeacherIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->schoolClassCoursesRelatedBySecondaryTeacherIdScheduledForDeletion as $schoolClassCourseRelatedBySecondaryTeacherId) {
                        // need to save related object because we set the relation to null
                        $schoolClassCourseRelatedBySecondaryTeacherId->save($con);
                    }
                    $this->schoolClassCoursesRelatedBySecondaryTeacherIdScheduledForDeletion = null;
                }
            }

            if ($this->collSchoolClassCoursesRelatedBySecondaryTeacherId !== null) {
                foreach ($this->collSchoolClassCoursesRelatedBySecondaryTeacherId as $referrerFK) {
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

            if ($this->studentAvatarsScheduledForDeletion !== null) {
                if (!$this->studentAvatarsScheduledForDeletion->isEmpty()) {
                    StudentAvatarQuery::create()
                        ->filterByPrimaryKeys($this->studentAvatarsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->studentAvatarsScheduledForDeletion = null;
                }
            }

            if ($this->collStudentAvatars !== null) {
                foreach ($this->collStudentAvatars as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->testsScheduledForDeletion !== null) {
                if (!$this->testsScheduledForDeletion->isEmpty()) {
                    TestQuery::create()
                        ->filterByPrimaryKeys($this->testsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->testsScheduledForDeletion = null;
                }
            }

            if ($this->collTests !== null) {
                foreach ($this->collTests as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->singleUserProfile !== null) {
                if (!$this->singleUserProfile->isDeleted() && ($this->singleUserProfile->isNew() || $this->singleUserProfile->isModified())) {
                        $affectedRows += $this->singleUserProfile->save($con);
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

        $this->modifiedColumns[] = UserPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(UserPeer::USERNAME)) {
            $modifiedColumns[':p' . $index++]  = '`username`';
        }
        if ($this->isColumnModified(UserPeer::USERNAME_CANONICAL)) {
            $modifiedColumns[':p' . $index++]  = '`username_canonical`';
        }
        if ($this->isColumnModified(UserPeer::EMAIL)) {
            $modifiedColumns[':p' . $index++]  = '`email`';
        }
        if ($this->isColumnModified(UserPeer::EMAIL_CANONICAL)) {
            $modifiedColumns[':p' . $index++]  = '`email_canonical`';
        }
        if ($this->isColumnModified(UserPeer::ENABLED)) {
            $modifiedColumns[':p' . $index++]  = '`enabled`';
        }
        if ($this->isColumnModified(UserPeer::SALT)) {
            $modifiedColumns[':p' . $index++]  = '`salt`';
        }
        if ($this->isColumnModified(UserPeer::PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = '`password`';
        }
        if ($this->isColumnModified(UserPeer::LAST_LOGIN)) {
            $modifiedColumns[':p' . $index++]  = '`last_login`';
        }
        if ($this->isColumnModified(UserPeer::LOCKED)) {
            $modifiedColumns[':p' . $index++]  = '`locked`';
        }
        if ($this->isColumnModified(UserPeer::EXPIRED)) {
            $modifiedColumns[':p' . $index++]  = '`expired`';
        }
        if ($this->isColumnModified(UserPeer::EXPIRES_AT)) {
            $modifiedColumns[':p' . $index++]  = '`expires_at`';
        }
        if ($this->isColumnModified(UserPeer::CONFIRMATION_TOKEN)) {
            $modifiedColumns[':p' . $index++]  = '`confirmation_token`';
        }
        if ($this->isColumnModified(UserPeer::PASSWORD_REQUESTED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`password_requested_at`';
        }
        if ($this->isColumnModified(UserPeer::CREDENTIALS_EXPIRED)) {
            $modifiedColumns[':p' . $index++]  = '`credentials_expired`';
        }
        if ($this->isColumnModified(UserPeer::CREDENTIALS_EXPIRE_AT)) {
            $modifiedColumns[':p' . $index++]  = '`credentials_expire_at`';
        }
        if ($this->isColumnModified(UserPeer::TYPE)) {
            $modifiedColumns[':p' . $index++]  = '`type`';
        }
        if ($this->isColumnModified(UserPeer::STATUS)) {
            $modifiedColumns[':p' . $index++]  = '`status`';
        }
        if ($this->isColumnModified(UserPeer::ROLES)) {
            $modifiedColumns[':p' . $index++]  = '`roles`';
        }
        if ($this->isColumnModified(UserPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(UserPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `fos_user` (%s) VALUES (%s)',
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
                    case '`username`':
                        $stmt->bindValue($identifier, $this->username, PDO::PARAM_STR);
                        break;
                    case '`username_canonical`':
                        $stmt->bindValue($identifier, $this->username_canonical, PDO::PARAM_STR);
                        break;
                    case '`email`':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case '`email_canonical`':
                        $stmt->bindValue($identifier, $this->email_canonical, PDO::PARAM_STR);
                        break;
                    case '`enabled`':
                        $stmt->bindValue($identifier, (int) $this->enabled, PDO::PARAM_INT);
                        break;
                    case '`salt`':
                        $stmt->bindValue($identifier, $this->salt, PDO::PARAM_STR);
                        break;
                    case '`password`':
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
                        break;
                    case '`last_login`':
                        $stmt->bindValue($identifier, $this->last_login, PDO::PARAM_STR);
                        break;
                    case '`locked`':
                        $stmt->bindValue($identifier, (int) $this->locked, PDO::PARAM_INT);
                        break;
                    case '`expired`':
                        $stmt->bindValue($identifier, (int) $this->expired, PDO::PARAM_INT);
                        break;
                    case '`expires_at`':
                        $stmt->bindValue($identifier, $this->expires_at, PDO::PARAM_STR);
                        break;
                    case '`confirmation_token`':
                        $stmt->bindValue($identifier, $this->confirmation_token, PDO::PARAM_STR);
                        break;
                    case '`password_requested_at`':
                        $stmt->bindValue($identifier, $this->password_requested_at, PDO::PARAM_STR);
                        break;
                    case '`credentials_expired`':
                        $stmt->bindValue($identifier, (int) $this->credentials_expired, PDO::PARAM_INT);
                        break;
                    case '`credentials_expire_at`':
                        $stmt->bindValue($identifier, $this->credentials_expire_at, PDO::PARAM_STR);
                        break;
                    case '`type`':
                        $stmt->bindValue($identifier, $this->type, PDO::PARAM_INT);
                        break;
                    case '`status`':
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_INT);
                        break;
                    case '`roles`':
                        $stmt->bindValue($identifier, $this->roles, PDO::PARAM_STR);
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


            if (($retval = UserPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collAnnouncements !== null) {
                    foreach ($this->collAnnouncements as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collApplications !== null) {
                    foreach ($this->collApplications as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBehaviors !== null) {
                    foreach ($this->collBehaviors as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCategories !== null) {
                    foreach ($this->collCategories as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collUserGroups !== null) {
                    foreach ($this->collUserGroups as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collUserLogs !== null) {
                    foreach ($this->collUserLogs as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collLicensePayments !== null) {
                    foreach ($this->collLicensePayments as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collUserLicenses !== null) {
                    foreach ($this->collUserLicenses as $referrerFK) {
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

                if ($this->collEthnicities !== null) {
                    foreach ($this->collEthnicities as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collMessagesRelatedByFromId !== null) {
                    foreach ($this->collMessagesRelatedByFromId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collMessagesRelatedByToId !== null) {
                    foreach ($this->collMessagesRelatedByToId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collOrganizations !== null) {
                    foreach ($this->collOrganizations as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collParentStudents !== null) {
                    foreach ($this->collParentStudents as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collReligions !== null) {
                    foreach ($this->collReligions as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSchoolClassCoursesRelatedByPrimaryTeacherId !== null) {
                    foreach ($this->collSchoolClassCoursesRelatedByPrimaryTeacherId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSchoolClassCoursesRelatedBySecondaryTeacherId !== null) {
                    foreach ($this->collSchoolClassCoursesRelatedBySecondaryTeacherId as $referrerFK) {
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

                if ($this->collStudentAvatars !== null) {
                    foreach ($this->collStudentAvatars as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collTests !== null) {
                    foreach ($this->collTests as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->singleUserProfile !== null) {
                    if (!$this->singleUserProfile->validate($columns)) {
                        $failureMap = array_merge($failureMap, $this->singleUserProfile->getValidationFailures());
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
        $pos = UserPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getUsername();
                break;
            case 2:
                return $this->getUsernameCanonical();
                break;
            case 3:
                return $this->getEmail();
                break;
            case 4:
                return $this->getEmailCanonical();
                break;
            case 5:
                return $this->getEnabled();
                break;
            case 6:
                return $this->getSalt();
                break;
            case 7:
                return $this->getPassword();
                break;
            case 8:
                return $this->getLastLogin();
                break;
            case 9:
                return $this->getLocked();
                break;
            case 10:
                return $this->getExpired();
                break;
            case 11:
                return $this->getExpiresAt();
                break;
            case 12:
                return $this->getConfirmationToken();
                break;
            case 13:
                return $this->getPasswordRequestedAt();
                break;
            case 14:
                return $this->getCredentialsExpired();
                break;
            case 15:
                return $this->getCredentialsExpireAt();
                break;
            case 16:
                return $this->getType();
                break;
            case 17:
                return $this->getStatus();
                break;
            case 18:
                return $this->getRoles();
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
        if (isset($alreadyDumpedObjects['User'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->getPrimaryKey()] = true;
        $keys = UserPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUsername(),
            $keys[2] => $this->getUsernameCanonical(),
            $keys[3] => $this->getEmail(),
            $keys[4] => $this->getEmailCanonical(),
            $keys[5] => $this->getEnabled(),
            $keys[6] => $this->getSalt(),
            $keys[7] => $this->getPassword(),
            $keys[8] => $this->getLastLogin(),
            $keys[9] => $this->getLocked(),
            $keys[10] => $this->getExpired(),
            $keys[11] => $this->getExpiresAt(),
            $keys[12] => $this->getConfirmationToken(),
            $keys[13] => $this->getPasswordRequestedAt(),
            $keys[14] => $this->getCredentialsExpired(),
            $keys[15] => $this->getCredentialsExpireAt(),
            $keys[16] => $this->getType(),
            $keys[17] => $this->getStatus(),
            $keys[18] => $this->getRoles(),
            $keys[19] => $this->getCreatedAt(),
            $keys[20] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collAnnouncements) {
                $result['Announcements'] = $this->collAnnouncements->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collApplications) {
                $result['Applications'] = $this->collApplications->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collBehaviors) {
                $result['Behaviors'] = $this->collBehaviors->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCategories) {
                $result['Categories'] = $this->collCategories->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserGroups) {
                $result['UserGroups'] = $this->collUserGroups->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserLogs) {
                $result['UserLogs'] = $this->collUserLogs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collLicensePayments) {
                $result['LicensePayments'] = $this->collLicensePayments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserLicenses) {
                $result['UserLicenses'] = $this->collUserLicenses->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPages) {
                $result['Pages'] = $this->collPages->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEmployees) {
                $result['Employees'] = $this->collEmployees->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEthnicities) {
                $result['Ethnicities'] = $this->collEthnicities->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMessagesRelatedByFromId) {
                $result['MessagesRelatedByFromId'] = $this->collMessagesRelatedByFromId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMessagesRelatedByToId) {
                $result['MessagesRelatedByToId'] = $this->collMessagesRelatedByToId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collOrganizations) {
                $result['Organizations'] = $this->collOrganizations->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collParentStudents) {
                $result['ParentStudents'] = $this->collParentStudents->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collReligions) {
                $result['Religions'] = $this->collReligions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSchoolClassCoursesRelatedByPrimaryTeacherId) {
                $result['SchoolClassCoursesRelatedByPrimaryTeacherId'] = $this->collSchoolClassCoursesRelatedByPrimaryTeacherId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSchoolClassCoursesRelatedBySecondaryTeacherId) {
                $result['SchoolClassCoursesRelatedBySecondaryTeacherId'] = $this->collSchoolClassCoursesRelatedBySecondaryTeacherId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStudents) {
                $result['Students'] = $this->collStudents->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStudentAvatars) {
                $result['StudentAvatars'] = $this->collStudentAvatars->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collTests) {
                $result['Tests'] = $this->collTests->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->singleUserProfile) {
                $result['UserProfile'] = $this->singleUserProfile->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
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
        $pos = UserPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setUsername($value);
                break;
            case 2:
                $this->setUsernameCanonical($value);
                break;
            case 3:
                $this->setEmail($value);
                break;
            case 4:
                $this->setEmailCanonical($value);
                break;
            case 5:
                $this->setEnabled($value);
                break;
            case 6:
                $this->setSalt($value);
                break;
            case 7:
                $this->setPassword($value);
                break;
            case 8:
                $this->setLastLogin($value);
                break;
            case 9:
                $this->setLocked($value);
                break;
            case 10:
                $this->setExpired($value);
                break;
            case 11:
                $this->setExpiresAt($value);
                break;
            case 12:
                $this->setConfirmationToken($value);
                break;
            case 13:
                $this->setPasswordRequestedAt($value);
                break;
            case 14:
                $this->setCredentialsExpired($value);
                break;
            case 15:
                $this->setCredentialsExpireAt($value);
                break;
            case 16:
                $valueSet = UserPeer::getValueSet(UserPeer::TYPE);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setType($value);
                break;
            case 17:
                $valueSet = UserPeer::getValueSet(UserPeer::STATUS);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setStatus($value);
                break;
            case 18:
                if (!is_array($value)) {
                    $v = trim(substr($value, 2, -2));
                    $value = $v ? explode(' | ', $v) : array();
                }
                $this->setRoles($value);
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
        $keys = UserPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUsername($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setUsernameCanonical($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setEmail($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setEmailCanonical($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setEnabled($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setSalt($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setPassword($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setLastLogin($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setLocked($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setExpired($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setExpiresAt($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setConfirmationToken($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setPasswordRequestedAt($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setCredentialsExpired($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setCredentialsExpireAt($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setType($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setStatus($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setRoles($arr[$keys[18]]);
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
        $criteria = new Criteria(UserPeer::DATABASE_NAME);

        if ($this->isColumnModified(UserPeer::ID)) $criteria->add(UserPeer::ID, $this->id);
        if ($this->isColumnModified(UserPeer::USERNAME)) $criteria->add(UserPeer::USERNAME, $this->username);
        if ($this->isColumnModified(UserPeer::USERNAME_CANONICAL)) $criteria->add(UserPeer::USERNAME_CANONICAL, $this->username_canonical);
        if ($this->isColumnModified(UserPeer::EMAIL)) $criteria->add(UserPeer::EMAIL, $this->email);
        if ($this->isColumnModified(UserPeer::EMAIL_CANONICAL)) $criteria->add(UserPeer::EMAIL_CANONICAL, $this->email_canonical);
        if ($this->isColumnModified(UserPeer::ENABLED)) $criteria->add(UserPeer::ENABLED, $this->enabled);
        if ($this->isColumnModified(UserPeer::SALT)) $criteria->add(UserPeer::SALT, $this->salt);
        if ($this->isColumnModified(UserPeer::PASSWORD)) $criteria->add(UserPeer::PASSWORD, $this->password);
        if ($this->isColumnModified(UserPeer::LAST_LOGIN)) $criteria->add(UserPeer::LAST_LOGIN, $this->last_login);
        if ($this->isColumnModified(UserPeer::LOCKED)) $criteria->add(UserPeer::LOCKED, $this->locked);
        if ($this->isColumnModified(UserPeer::EXPIRED)) $criteria->add(UserPeer::EXPIRED, $this->expired);
        if ($this->isColumnModified(UserPeer::EXPIRES_AT)) $criteria->add(UserPeer::EXPIRES_AT, $this->expires_at);
        if ($this->isColumnModified(UserPeer::CONFIRMATION_TOKEN)) $criteria->add(UserPeer::CONFIRMATION_TOKEN, $this->confirmation_token);
        if ($this->isColumnModified(UserPeer::PASSWORD_REQUESTED_AT)) $criteria->add(UserPeer::PASSWORD_REQUESTED_AT, $this->password_requested_at);
        if ($this->isColumnModified(UserPeer::CREDENTIALS_EXPIRED)) $criteria->add(UserPeer::CREDENTIALS_EXPIRED, $this->credentials_expired);
        if ($this->isColumnModified(UserPeer::CREDENTIALS_EXPIRE_AT)) $criteria->add(UserPeer::CREDENTIALS_EXPIRE_AT, $this->credentials_expire_at);
        if ($this->isColumnModified(UserPeer::TYPE)) $criteria->add(UserPeer::TYPE, $this->type);
        if ($this->isColumnModified(UserPeer::STATUS)) $criteria->add(UserPeer::STATUS, $this->status);
        if ($this->isColumnModified(UserPeer::ROLES)) $criteria->add(UserPeer::ROLES, $this->roles);
        if ($this->isColumnModified(UserPeer::CREATED_AT)) $criteria->add(UserPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(UserPeer::UPDATED_AT)) $criteria->add(UserPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(UserPeer::DATABASE_NAME);
        $criteria->add(UserPeer::ID, $this->id);

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
     * @param object $copyObj An object of User (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUsername($this->getUsername());
        $copyObj->setUsernameCanonical($this->getUsernameCanonical());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setEmailCanonical($this->getEmailCanonical());
        $copyObj->setEnabled($this->getEnabled());
        $copyObj->setSalt($this->getSalt());
        $copyObj->setPassword($this->getPassword());
        $copyObj->setLastLogin($this->getLastLogin());
        $copyObj->setLocked($this->getLocked());
        $copyObj->setExpired($this->getExpired());
        $copyObj->setExpiresAt($this->getExpiresAt());
        $copyObj->setConfirmationToken($this->getConfirmationToken());
        $copyObj->setPasswordRequestedAt($this->getPasswordRequestedAt());
        $copyObj->setCredentialsExpired($this->getCredentialsExpired());
        $copyObj->setCredentialsExpireAt($this->getCredentialsExpireAt());
        $copyObj->setType($this->getType());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setRoles($this->getRoles());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getAnnouncements() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAnnouncement($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getApplications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addApplication($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getBehaviors() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addBehavior($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCategories() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCategory($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserGroups() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserGroup($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserLogs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserLog($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLicensePayments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLicensePayment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserLicenses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserLicense($relObj->copy($deepCopy));
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

            foreach ($this->getEthnicities() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEthnicity($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMessagesRelatedByFromId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMessageRelatedByFromId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMessagesRelatedByToId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMessageRelatedByToId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOrganizations() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrganization($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getParentStudents() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addParentStudent($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getReligions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addReligion($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSchoolClassCoursesRelatedByPrimaryTeacherId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSchoolClassCourseRelatedByPrimaryTeacherId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSchoolClassCoursesRelatedBySecondaryTeacherId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSchoolClassCourseRelatedBySecondaryTeacherId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStudents() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStudent($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStudentAvatars() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStudentAvatar($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTests() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTest($relObj->copy($deepCopy));
                }
            }

            $relObj = $this->getUserProfile();
            if ($relObj) {
                $copyObj->setUserProfile($relObj->copy($deepCopy));
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
     * @return User Clone of current object.
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
     * @return UserPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new UserPeer();
        }

        return self::$peer;
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
        if ('Announcement' == $relationName) {
            $this->initAnnouncements();
        }
        if ('Application' == $relationName) {
            $this->initApplications();
        }
        if ('Behavior' == $relationName) {
            $this->initBehaviors();
        }
        if ('Category' == $relationName) {
            $this->initCategories();
        }
        if ('UserGroup' == $relationName) {
            $this->initUserGroups();
        }
        if ('UserLog' == $relationName) {
            $this->initUserLogs();
        }
        if ('LicensePayment' == $relationName) {
            $this->initLicensePayments();
        }
        if ('UserLicense' == $relationName) {
            $this->initUserLicenses();
        }
        if ('Page' == $relationName) {
            $this->initPages();
        }
        if ('Employee' == $relationName) {
            $this->initEmployees();
        }
        if ('Ethnicity' == $relationName) {
            $this->initEthnicities();
        }
        if ('MessageRelatedByFromId' == $relationName) {
            $this->initMessagesRelatedByFromId();
        }
        if ('MessageRelatedByToId' == $relationName) {
            $this->initMessagesRelatedByToId();
        }
        if ('Organization' == $relationName) {
            $this->initOrganizations();
        }
        if ('ParentStudent' == $relationName) {
            $this->initParentStudents();
        }
        if ('Religion' == $relationName) {
            $this->initReligions();
        }
        if ('SchoolClassCourseRelatedByPrimaryTeacherId' == $relationName) {
            $this->initSchoolClassCoursesRelatedByPrimaryTeacherId();
        }
        if ('SchoolClassCourseRelatedBySecondaryTeacherId' == $relationName) {
            $this->initSchoolClassCoursesRelatedBySecondaryTeacherId();
        }
        if ('Student' == $relationName) {
            $this->initStudents();
        }
        if ('StudentAvatar' == $relationName) {
            $this->initStudentAvatars();
        }
        if ('Test' == $relationName) {
            $this->initTests();
        }
    }

    /**
     * Clears out the collAnnouncements collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addAnnouncements()
     */
    public function clearAnnouncements()
    {
        $this->collAnnouncements = null; // important to set this to null since that means it is uninitialized
        $this->collAnnouncementsPartial = null;

        return $this;
    }

    /**
     * reset is the collAnnouncements collection loaded partially
     *
     * @return void
     */
    public function resetPartialAnnouncements($v = true)
    {
        $this->collAnnouncementsPartial = $v;
    }

    /**
     * Initializes the collAnnouncements collection.
     *
     * By default this just sets the collAnnouncements collection to an empty array (like clearcollAnnouncements());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAnnouncements($overrideExisting = true)
    {
        if (null !== $this->collAnnouncements && !$overrideExisting) {
            return;
        }
        $this->collAnnouncements = new PropelObjectCollection();
        $this->collAnnouncements->setModel('Announcement');
    }

    /**
     * Gets an array of Announcement objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Announcement[] List of Announcement objects
     * @throws PropelException
     */
    public function getAnnouncements($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collAnnouncementsPartial && !$this->isNew();
        if (null === $this->collAnnouncements || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAnnouncements) {
                // return empty collection
                $this->initAnnouncements();
            } else {
                $collAnnouncements = AnnouncementQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collAnnouncementsPartial && count($collAnnouncements)) {
                      $this->initAnnouncements(false);

                      foreach ($collAnnouncements as $obj) {
                        if (false == $this->collAnnouncements->contains($obj)) {
                          $this->collAnnouncements->append($obj);
                        }
                      }

                      $this->collAnnouncementsPartial = true;
                    }

                    $collAnnouncements->getInternalIterator()->rewind();

                    return $collAnnouncements;
                }

                if ($partial && $this->collAnnouncements) {
                    foreach ($this->collAnnouncements as $obj) {
                        if ($obj->isNew()) {
                            $collAnnouncements[] = $obj;
                        }
                    }
                }

                $this->collAnnouncements = $collAnnouncements;
                $this->collAnnouncementsPartial = false;
            }
        }

        return $this->collAnnouncements;
    }

    /**
     * Sets a collection of Announcement objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $announcements A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setAnnouncements(PropelCollection $announcements, PropelPDO $con = null)
    {
        $announcementsToDelete = $this->getAnnouncements(new Criteria(), $con)->diff($announcements);


        $this->announcementsScheduledForDeletion = $announcementsToDelete;

        foreach ($announcementsToDelete as $announcementRemoved) {
            $announcementRemoved->setUser(null);
        }

        $this->collAnnouncements = null;
        foreach ($announcements as $announcement) {
            $this->addAnnouncement($announcement);
        }

        $this->collAnnouncements = $announcements;
        $this->collAnnouncementsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Announcement objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Announcement objects.
     * @throws PropelException
     */
    public function countAnnouncements(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collAnnouncementsPartial && !$this->isNew();
        if (null === $this->collAnnouncements || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAnnouncements) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAnnouncements());
            }
            $query = AnnouncementQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collAnnouncements);
    }

    /**
     * Method called to associate a Announcement object to this object
     * through the Announcement foreign key attribute.
     *
     * @param    Announcement $l Announcement
     * @return User The current object (for fluent API support)
     */
    public function addAnnouncement(Announcement $l)
    {
        if ($this->collAnnouncements === null) {
            $this->initAnnouncements();
            $this->collAnnouncementsPartial = true;
        }

        if (!in_array($l, $this->collAnnouncements->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddAnnouncement($l);

            if ($this->announcementsScheduledForDeletion and $this->announcementsScheduledForDeletion->contains($l)) {
                $this->announcementsScheduledForDeletion->remove($this->announcementsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Announcement $announcement The announcement object to add.
     */
    protected function doAddAnnouncement($announcement)
    {
        $this->collAnnouncements[]= $announcement;
        $announcement->setUser($this);
    }

    /**
     * @param	Announcement $announcement The announcement object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeAnnouncement($announcement)
    {
        if ($this->getAnnouncements()->contains($announcement)) {
            $this->collAnnouncements->remove($this->collAnnouncements->search($announcement));
            if (null === $this->announcementsScheduledForDeletion) {
                $this->announcementsScheduledForDeletion = clone $this->collAnnouncements;
                $this->announcementsScheduledForDeletion->clear();
            }
            $this->announcementsScheduledForDeletion[]= clone $announcement;
            $announcement->setUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collApplications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
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
     * If this User is new, it will return
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
                    ->filterByUser($this)
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
     * @return User The current object (for fluent API support)
     */
    public function setApplications(PropelCollection $applications, PropelPDO $con = null)
    {
        $applicationsToDelete = $this->getApplications(new Criteria(), $con)->diff($applications);


        $this->applicationsScheduledForDeletion = $applicationsToDelete;

        foreach ($applicationsToDelete as $applicationRemoved) {
            $applicationRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collApplications);
    }

    /**
     * Method called to associate a Application object to this object
     * through the Application foreign key attribute.
     *
     * @param    Application $l Application
     * @return User The current object (for fluent API support)
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
        $application->setUser($this);
    }

    /**
     * @param	Application $application The application object to remove.
     * @return User The current object (for fluent API support)
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
            $application->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Applications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Application[] List of Application objects
     */
    public function getApplicationsJoinSchool($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ApplicationQuery::create(null, $criteria);
        $query->joinWith('School', $join_behavior);

        return $this->getApplications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Applications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
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
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Applications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
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
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Applications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
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
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Applications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
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
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Applications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
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
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Applications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
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
     * Clears out the collBehaviors collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addBehaviors()
     */
    public function clearBehaviors()
    {
        $this->collBehaviors = null; // important to set this to null since that means it is uninitialized
        $this->collBehaviorsPartial = null;

        return $this;
    }

    /**
     * reset is the collBehaviors collection loaded partially
     *
     * @return void
     */
    public function resetPartialBehaviors($v = true)
    {
        $this->collBehaviorsPartial = $v;
    }

    /**
     * Initializes the collBehaviors collection.
     *
     * By default this just sets the collBehaviors collection to an empty array (like clearcollBehaviors());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initBehaviors($overrideExisting = true)
    {
        if (null !== $this->collBehaviors && !$overrideExisting) {
            return;
        }
        $this->collBehaviors = new PropelObjectCollection();
        $this->collBehaviors->setModel('Behavior');
    }

    /**
     * Gets an array of Behavior objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Behavior[] List of Behavior objects
     * @throws PropelException
     */
    public function getBehaviors($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collBehaviorsPartial && !$this->isNew();
        if (null === $this->collBehaviors || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collBehaviors) {
                // return empty collection
                $this->initBehaviors();
            } else {
                $collBehaviors = BehaviorQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collBehaviorsPartial && count($collBehaviors)) {
                      $this->initBehaviors(false);

                      foreach ($collBehaviors as $obj) {
                        if (false == $this->collBehaviors->contains($obj)) {
                          $this->collBehaviors->append($obj);
                        }
                      }

                      $this->collBehaviorsPartial = true;
                    }

                    $collBehaviors->getInternalIterator()->rewind();

                    return $collBehaviors;
                }

                if ($partial && $this->collBehaviors) {
                    foreach ($this->collBehaviors as $obj) {
                        if ($obj->isNew()) {
                            $collBehaviors[] = $obj;
                        }
                    }
                }

                $this->collBehaviors = $collBehaviors;
                $this->collBehaviorsPartial = false;
            }
        }

        return $this->collBehaviors;
    }

    /**
     * Sets a collection of Behavior objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $behaviors A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setBehaviors(PropelCollection $behaviors, PropelPDO $con = null)
    {
        $behaviorsToDelete = $this->getBehaviors(new Criteria(), $con)->diff($behaviors);


        $this->behaviorsScheduledForDeletion = $behaviorsToDelete;

        foreach ($behaviorsToDelete as $behaviorRemoved) {
            $behaviorRemoved->setUser(null);
        }

        $this->collBehaviors = null;
        foreach ($behaviors as $behavior) {
            $this->addBehavior($behavior);
        }

        $this->collBehaviors = $behaviors;
        $this->collBehaviorsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Behavior objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Behavior objects.
     * @throws PropelException
     */
    public function countBehaviors(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collBehaviorsPartial && !$this->isNew();
        if (null === $this->collBehaviors || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collBehaviors) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getBehaviors());
            }
            $query = BehaviorQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collBehaviors);
    }

    /**
     * Method called to associate a Behavior object to this object
     * through the Behavior foreign key attribute.
     *
     * @param    Behavior $l Behavior
     * @return User The current object (for fluent API support)
     */
    public function addBehavior(Behavior $l)
    {
        if ($this->collBehaviors === null) {
            $this->initBehaviors();
            $this->collBehaviorsPartial = true;
        }

        if (!in_array($l, $this->collBehaviors->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddBehavior($l);

            if ($this->behaviorsScheduledForDeletion and $this->behaviorsScheduledForDeletion->contains($l)) {
                $this->behaviorsScheduledForDeletion->remove($this->behaviorsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Behavior $behavior The behavior object to add.
     */
    protected function doAddBehavior($behavior)
    {
        $this->collBehaviors[]= $behavior;
        $behavior->setUser($this);
    }

    /**
     * @param	Behavior $behavior The behavior object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeBehavior($behavior)
    {
        if ($this->getBehaviors()->contains($behavior)) {
            $this->collBehaviors->remove($this->collBehaviors->search($behavior));
            if (null === $this->behaviorsScheduledForDeletion) {
                $this->behaviorsScheduledForDeletion = clone $this->collBehaviors;
                $this->behaviorsScheduledForDeletion->clear();
            }
            $this->behaviorsScheduledForDeletion[]= $behavior;
            $behavior->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Behaviors from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Behavior[] List of Behavior objects
     */
    public function getBehaviorsJoinIcon($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = BehaviorQuery::create(null, $criteria);
        $query->joinWith('Icon', $join_behavior);

        return $this->getBehaviors($query, $con);
    }

    /**
     * Clears out the collCategories collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addCategories()
     */
    public function clearCategories()
    {
        $this->collCategories = null; // important to set this to null since that means it is uninitialized
        $this->collCategoriesPartial = null;

        return $this;
    }

    /**
     * reset is the collCategories collection loaded partially
     *
     * @return void
     */
    public function resetPartialCategories($v = true)
    {
        $this->collCategoriesPartial = $v;
    }

    /**
     * Initializes the collCategories collection.
     *
     * By default this just sets the collCategories collection to an empty array (like clearcollCategories());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCategories($overrideExisting = true)
    {
        if (null !== $this->collCategories && !$overrideExisting) {
            return;
        }
        $this->collCategories = new PropelObjectCollection();
        $this->collCategories->setModel('Category');
    }

    /**
     * Gets an array of Category objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Category[] List of Category objects
     * @throws PropelException
     */
    public function getCategories($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCategoriesPartial && !$this->isNew();
        if (null === $this->collCategories || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCategories) {
                // return empty collection
                $this->initCategories();
            } else {
                $collCategories = CategoryQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCategoriesPartial && count($collCategories)) {
                      $this->initCategories(false);

                      foreach ($collCategories as $obj) {
                        if (false == $this->collCategories->contains($obj)) {
                          $this->collCategories->append($obj);
                        }
                      }

                      $this->collCategoriesPartial = true;
                    }

                    $collCategories->getInternalIterator()->rewind();

                    return $collCategories;
                }

                if ($partial && $this->collCategories) {
                    foreach ($this->collCategories as $obj) {
                        if ($obj->isNew()) {
                            $collCategories[] = $obj;
                        }
                    }
                }

                $this->collCategories = $collCategories;
                $this->collCategoriesPartial = false;
            }
        }

        return $this->collCategories;
    }

    /**
     * Sets a collection of Category objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $categories A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setCategories(PropelCollection $categories, PropelPDO $con = null)
    {
        $categoriesToDelete = $this->getCategories(new Criteria(), $con)->diff($categories);


        $this->categoriesScheduledForDeletion = $categoriesToDelete;

        foreach ($categoriesToDelete as $categoryRemoved) {
            $categoryRemoved->setUser(null);
        }

        $this->collCategories = null;
        foreach ($categories as $category) {
            $this->addCategory($category);
        }

        $this->collCategories = $categories;
        $this->collCategoriesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Category objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Category objects.
     * @throws PropelException
     */
    public function countCategories(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCategoriesPartial && !$this->isNew();
        if (null === $this->collCategories || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCategories) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCategories());
            }
            $query = CategoryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collCategories);
    }

    /**
     * Method called to associate a Category object to this object
     * through the Category foreign key attribute.
     *
     * @param    Category $l Category
     * @return User The current object (for fluent API support)
     */
    public function addCategory(Category $l)
    {
        if ($this->collCategories === null) {
            $this->initCategories();
            $this->collCategoriesPartial = true;
        }

        if (!in_array($l, $this->collCategories->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCategory($l);

            if ($this->categoriesScheduledForDeletion and $this->categoriesScheduledForDeletion->contains($l)) {
                $this->categoriesScheduledForDeletion->remove($this->categoriesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Category $category The category object to add.
     */
    protected function doAddCategory($category)
    {
        $this->collCategories[]= $category;
        $category->setUser($this);
    }

    /**
     * @param	Category $category The category object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeCategory($category)
    {
        if ($this->getCategories()->contains($category)) {
            $this->collCategories->remove($this->collCategories->search($category));
            if (null === $this->categoriesScheduledForDeletion) {
                $this->categoriesScheduledForDeletion = clone $this->collCategories;
                $this->categoriesScheduledForDeletion->clear();
            }
            $this->categoriesScheduledForDeletion[]= clone $category;
            $category->setUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collUserGroups collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addUserGroups()
     */
    public function clearUserGroups()
    {
        $this->collUserGroups = null; // important to set this to null since that means it is uninitialized
        $this->collUserGroupsPartial = null;

        return $this;
    }

    /**
     * reset is the collUserGroups collection loaded partially
     *
     * @return void
     */
    public function resetPartialUserGroups($v = true)
    {
        $this->collUserGroupsPartial = $v;
    }

    /**
     * Initializes the collUserGroups collection.
     *
     * By default this just sets the collUserGroups collection to an empty array (like clearcollUserGroups());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserGroups($overrideExisting = true)
    {
        if (null !== $this->collUserGroups && !$overrideExisting) {
            return;
        }
        $this->collUserGroups = new PropelObjectCollection();
        $this->collUserGroups->setModel('UserGroup');
    }

    /**
     * Gets an array of UserGroup objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|UserGroup[] List of UserGroup objects
     * @throws PropelException
     */
    public function getUserGroups($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collUserGroupsPartial && !$this->isNew();
        if (null === $this->collUserGroups || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserGroups) {
                // return empty collection
                $this->initUserGroups();
            } else {
                $collUserGroups = UserGroupQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collUserGroupsPartial && count($collUserGroups)) {
                      $this->initUserGroups(false);

                      foreach ($collUserGroups as $obj) {
                        if (false == $this->collUserGroups->contains($obj)) {
                          $this->collUserGroups->append($obj);
                        }
                      }

                      $this->collUserGroupsPartial = true;
                    }

                    $collUserGroups->getInternalIterator()->rewind();

                    return $collUserGroups;
                }

                if ($partial && $this->collUserGroups) {
                    foreach ($this->collUserGroups as $obj) {
                        if ($obj->isNew()) {
                            $collUserGroups[] = $obj;
                        }
                    }
                }

                $this->collUserGroups = $collUserGroups;
                $this->collUserGroupsPartial = false;
            }
        }

        return $this->collUserGroups;
    }

    /**
     * Sets a collection of UserGroup objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $userGroups A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setUserGroups(PropelCollection $userGroups, PropelPDO $con = null)
    {
        $userGroupsToDelete = $this->getUserGroups(new Criteria(), $con)->diff($userGroups);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userGroupsScheduledForDeletion = clone $userGroupsToDelete;

        foreach ($userGroupsToDelete as $userGroupRemoved) {
            $userGroupRemoved->setUser(null);
        }

        $this->collUserGroups = null;
        foreach ($userGroups as $userGroup) {
            $this->addUserGroup($userGroup);
        }

        $this->collUserGroups = $userGroups;
        $this->collUserGroupsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserGroup objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related UserGroup objects.
     * @throws PropelException
     */
    public function countUserGroups(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collUserGroupsPartial && !$this->isNew();
        if (null === $this->collUserGroups || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserGroups) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserGroups());
            }
            $query = UserGroupQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collUserGroups);
    }

    /**
     * Method called to associate a UserGroup object to this object
     * through the UserGroup foreign key attribute.
     *
     * @param    UserGroup $l UserGroup
     * @return User The current object (for fluent API support)
     */
    public function addUserGroup(UserGroup $l)
    {
        if ($this->collUserGroups === null) {
            $this->initUserGroups();
            $this->collUserGroupsPartial = true;
        }

        if (!in_array($l, $this->collUserGroups->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUserGroup($l);

            if ($this->userGroupsScheduledForDeletion and $this->userGroupsScheduledForDeletion->contains($l)) {
                $this->userGroupsScheduledForDeletion->remove($this->userGroupsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	UserGroup $userGroup The userGroup object to add.
     */
    protected function doAddUserGroup($userGroup)
    {
        $this->collUserGroups[]= $userGroup;
        $userGroup->setUser($this);
    }

    /**
     * @param	UserGroup $userGroup The userGroup object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeUserGroup($userGroup)
    {
        if ($this->getUserGroups()->contains($userGroup)) {
            $this->collUserGroups->remove($this->collUserGroups->search($userGroup));
            if (null === $this->userGroupsScheduledForDeletion) {
                $this->userGroupsScheduledForDeletion = clone $this->collUserGroups;
                $this->userGroupsScheduledForDeletion->clear();
            }
            $this->userGroupsScheduledForDeletion[]= clone $userGroup;
            $userGroup->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related UserGroups from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserGroup[] List of UserGroup objects
     */
    public function getUserGroupsJoinGroup($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserGroupQuery::create(null, $criteria);
        $query->joinWith('Group', $join_behavior);

        return $this->getUserGroups($query, $con);
    }

    /**
     * Clears out the collUserLogs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addUserLogs()
     */
    public function clearUserLogs()
    {
        $this->collUserLogs = null; // important to set this to null since that means it is uninitialized
        $this->collUserLogsPartial = null;

        return $this;
    }

    /**
     * reset is the collUserLogs collection loaded partially
     *
     * @return void
     */
    public function resetPartialUserLogs($v = true)
    {
        $this->collUserLogsPartial = $v;
    }

    /**
     * Initializes the collUserLogs collection.
     *
     * By default this just sets the collUserLogs collection to an empty array (like clearcollUserLogs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserLogs($overrideExisting = true)
    {
        if (null !== $this->collUserLogs && !$overrideExisting) {
            return;
        }
        $this->collUserLogs = new PropelObjectCollection();
        $this->collUserLogs->setModel('UserLog');
    }

    /**
     * Gets an array of UserLog objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|UserLog[] List of UserLog objects
     * @throws PropelException
     */
    public function getUserLogs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collUserLogsPartial && !$this->isNew();
        if (null === $this->collUserLogs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserLogs) {
                // return empty collection
                $this->initUserLogs();
            } else {
                $collUserLogs = UserLogQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collUserLogsPartial && count($collUserLogs)) {
                      $this->initUserLogs(false);

                      foreach ($collUserLogs as $obj) {
                        if (false == $this->collUserLogs->contains($obj)) {
                          $this->collUserLogs->append($obj);
                        }
                      }

                      $this->collUserLogsPartial = true;
                    }

                    $collUserLogs->getInternalIterator()->rewind();

                    return $collUserLogs;
                }

                if ($partial && $this->collUserLogs) {
                    foreach ($this->collUserLogs as $obj) {
                        if ($obj->isNew()) {
                            $collUserLogs[] = $obj;
                        }
                    }
                }

                $this->collUserLogs = $collUserLogs;
                $this->collUserLogsPartial = false;
            }
        }

        return $this->collUserLogs;
    }

    /**
     * Sets a collection of UserLog objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $userLogs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setUserLogs(PropelCollection $userLogs, PropelPDO $con = null)
    {
        $userLogsToDelete = $this->getUserLogs(new Criteria(), $con)->diff($userLogs);


        $this->userLogsScheduledForDeletion = $userLogsToDelete;

        foreach ($userLogsToDelete as $userLogRemoved) {
            $userLogRemoved->setUser(null);
        }

        $this->collUserLogs = null;
        foreach ($userLogs as $userLog) {
            $this->addUserLog($userLog);
        }

        $this->collUserLogs = $userLogs;
        $this->collUserLogsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserLog objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related UserLog objects.
     * @throws PropelException
     */
    public function countUserLogs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collUserLogsPartial && !$this->isNew();
        if (null === $this->collUserLogs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserLogs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserLogs());
            }
            $query = UserLogQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collUserLogs);
    }

    /**
     * Method called to associate a UserLog object to this object
     * through the UserLog foreign key attribute.
     *
     * @param    UserLog $l UserLog
     * @return User The current object (for fluent API support)
     */
    public function addUserLog(UserLog $l)
    {
        if ($this->collUserLogs === null) {
            $this->initUserLogs();
            $this->collUserLogsPartial = true;
        }

        if (!in_array($l, $this->collUserLogs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUserLog($l);

            if ($this->userLogsScheduledForDeletion and $this->userLogsScheduledForDeletion->contains($l)) {
                $this->userLogsScheduledForDeletion->remove($this->userLogsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	UserLog $userLog The userLog object to add.
     */
    protected function doAddUserLog($userLog)
    {
        $this->collUserLogs[]= $userLog;
        $userLog->setUser($this);
    }

    /**
     * @param	UserLog $userLog The userLog object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeUserLog($userLog)
    {
        if ($this->getUserLogs()->contains($userLog)) {
            $this->collUserLogs->remove($this->collUserLogs->search($userLog));
            if (null === $this->userLogsScheduledForDeletion) {
                $this->userLogsScheduledForDeletion = clone $this->collUserLogs;
                $this->userLogsScheduledForDeletion->clear();
            }
            $this->userLogsScheduledForDeletion[]= $userLog;
            $userLog->setUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collLicensePayments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addLicensePayments()
     */
    public function clearLicensePayments()
    {
        $this->collLicensePayments = null; // important to set this to null since that means it is uninitialized
        $this->collLicensePaymentsPartial = null;

        return $this;
    }

    /**
     * reset is the collLicensePayments collection loaded partially
     *
     * @return void
     */
    public function resetPartialLicensePayments($v = true)
    {
        $this->collLicensePaymentsPartial = $v;
    }

    /**
     * Initializes the collLicensePayments collection.
     *
     * By default this just sets the collLicensePayments collection to an empty array (like clearcollLicensePayments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLicensePayments($overrideExisting = true)
    {
        if (null !== $this->collLicensePayments && !$overrideExisting) {
            return;
        }
        $this->collLicensePayments = new PropelObjectCollection();
        $this->collLicensePayments->setModel('LicensePayment');
    }

    /**
     * Gets an array of LicensePayment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|LicensePayment[] List of LicensePayment objects
     * @throws PropelException
     */
    public function getLicensePayments($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collLicensePaymentsPartial && !$this->isNew();
        if (null === $this->collLicensePayments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLicensePayments) {
                // return empty collection
                $this->initLicensePayments();
            } else {
                $collLicensePayments = LicensePaymentQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collLicensePaymentsPartial && count($collLicensePayments)) {
                      $this->initLicensePayments(false);

                      foreach ($collLicensePayments as $obj) {
                        if (false == $this->collLicensePayments->contains($obj)) {
                          $this->collLicensePayments->append($obj);
                        }
                      }

                      $this->collLicensePaymentsPartial = true;
                    }

                    $collLicensePayments->getInternalIterator()->rewind();

                    return $collLicensePayments;
                }

                if ($partial && $this->collLicensePayments) {
                    foreach ($this->collLicensePayments as $obj) {
                        if ($obj->isNew()) {
                            $collLicensePayments[] = $obj;
                        }
                    }
                }

                $this->collLicensePayments = $collLicensePayments;
                $this->collLicensePaymentsPartial = false;
            }
        }

        return $this->collLicensePayments;
    }

    /**
     * Sets a collection of LicensePayment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $licensePayments A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setLicensePayments(PropelCollection $licensePayments, PropelPDO $con = null)
    {
        $licensePaymentsToDelete = $this->getLicensePayments(new Criteria(), $con)->diff($licensePayments);


        $this->licensePaymentsScheduledForDeletion = $licensePaymentsToDelete;

        foreach ($licensePaymentsToDelete as $licensePaymentRemoved) {
            $licensePaymentRemoved->setUser(null);
        }

        $this->collLicensePayments = null;
        foreach ($licensePayments as $licensePayment) {
            $this->addLicensePayment($licensePayment);
        }

        $this->collLicensePayments = $licensePayments;
        $this->collLicensePaymentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related LicensePayment objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related LicensePayment objects.
     * @throws PropelException
     */
    public function countLicensePayments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collLicensePaymentsPartial && !$this->isNew();
        if (null === $this->collLicensePayments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLicensePayments) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLicensePayments());
            }
            $query = LicensePaymentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collLicensePayments);
    }

    /**
     * Method called to associate a LicensePayment object to this object
     * through the LicensePayment foreign key attribute.
     *
     * @param    LicensePayment $l LicensePayment
     * @return User The current object (for fluent API support)
     */
    public function addLicensePayment(LicensePayment $l)
    {
        if ($this->collLicensePayments === null) {
            $this->initLicensePayments();
            $this->collLicensePaymentsPartial = true;
        }

        if (!in_array($l, $this->collLicensePayments->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddLicensePayment($l);

            if ($this->licensePaymentsScheduledForDeletion and $this->licensePaymentsScheduledForDeletion->contains($l)) {
                $this->licensePaymentsScheduledForDeletion->remove($this->licensePaymentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	LicensePayment $licensePayment The licensePayment object to add.
     */
    protected function doAddLicensePayment($licensePayment)
    {
        $this->collLicensePayments[]= $licensePayment;
        $licensePayment->setUser($this);
    }

    /**
     * @param	LicensePayment $licensePayment The licensePayment object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeLicensePayment($licensePayment)
    {
        if ($this->getLicensePayments()->contains($licensePayment)) {
            $this->collLicensePayments->remove($this->collLicensePayments->search($licensePayment));
            if (null === $this->licensePaymentsScheduledForDeletion) {
                $this->licensePaymentsScheduledForDeletion = clone $this->collLicensePayments;
                $this->licensePaymentsScheduledForDeletion->clear();
            }
            $this->licensePaymentsScheduledForDeletion[]= clone $licensePayment;
            $licensePayment->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related LicensePayments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|LicensePayment[] List of LicensePayment objects
     */
    public function getLicensePaymentsJoinLicense($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = LicensePaymentQuery::create(null, $criteria);
        $query->joinWith('License', $join_behavior);

        return $this->getLicensePayments($query, $con);
    }

    /**
     * Clears out the collUserLicenses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addUserLicenses()
     */
    public function clearUserLicenses()
    {
        $this->collUserLicenses = null; // important to set this to null since that means it is uninitialized
        $this->collUserLicensesPartial = null;

        return $this;
    }

    /**
     * reset is the collUserLicenses collection loaded partially
     *
     * @return void
     */
    public function resetPartialUserLicenses($v = true)
    {
        $this->collUserLicensesPartial = $v;
    }

    /**
     * Initializes the collUserLicenses collection.
     *
     * By default this just sets the collUserLicenses collection to an empty array (like clearcollUserLicenses());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserLicenses($overrideExisting = true)
    {
        if (null !== $this->collUserLicenses && !$overrideExisting) {
            return;
        }
        $this->collUserLicenses = new PropelObjectCollection();
        $this->collUserLicenses->setModel('UserLicense');
    }

    /**
     * Gets an array of UserLicense objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|UserLicense[] List of UserLicense objects
     * @throws PropelException
     */
    public function getUserLicenses($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collUserLicensesPartial && !$this->isNew();
        if (null === $this->collUserLicenses || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserLicenses) {
                // return empty collection
                $this->initUserLicenses();
            } else {
                $collUserLicenses = UserLicenseQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collUserLicensesPartial && count($collUserLicenses)) {
                      $this->initUserLicenses(false);

                      foreach ($collUserLicenses as $obj) {
                        if (false == $this->collUserLicenses->contains($obj)) {
                          $this->collUserLicenses->append($obj);
                        }
                      }

                      $this->collUserLicensesPartial = true;
                    }

                    $collUserLicenses->getInternalIterator()->rewind();

                    return $collUserLicenses;
                }

                if ($partial && $this->collUserLicenses) {
                    foreach ($this->collUserLicenses as $obj) {
                        if ($obj->isNew()) {
                            $collUserLicenses[] = $obj;
                        }
                    }
                }

                $this->collUserLicenses = $collUserLicenses;
                $this->collUserLicensesPartial = false;
            }
        }

        return $this->collUserLicenses;
    }

    /**
     * Sets a collection of UserLicense objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $userLicenses A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setUserLicenses(PropelCollection $userLicenses, PropelPDO $con = null)
    {
        $userLicensesToDelete = $this->getUserLicenses(new Criteria(), $con)->diff($userLicenses);


        $this->userLicensesScheduledForDeletion = $userLicensesToDelete;

        foreach ($userLicensesToDelete as $userLicenseRemoved) {
            $userLicenseRemoved->setUser(null);
        }

        $this->collUserLicenses = null;
        foreach ($userLicenses as $userLicense) {
            $this->addUserLicense($userLicense);
        }

        $this->collUserLicenses = $userLicenses;
        $this->collUserLicensesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserLicense objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related UserLicense objects.
     * @throws PropelException
     */
    public function countUserLicenses(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collUserLicensesPartial && !$this->isNew();
        if (null === $this->collUserLicenses || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserLicenses) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserLicenses());
            }
            $query = UserLicenseQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collUserLicenses);
    }

    /**
     * Method called to associate a UserLicense object to this object
     * through the UserLicense foreign key attribute.
     *
     * @param    UserLicense $l UserLicense
     * @return User The current object (for fluent API support)
     */
    public function addUserLicense(UserLicense $l)
    {
        if ($this->collUserLicenses === null) {
            $this->initUserLicenses();
            $this->collUserLicensesPartial = true;
        }

        if (!in_array($l, $this->collUserLicenses->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUserLicense($l);

            if ($this->userLicensesScheduledForDeletion and $this->userLicensesScheduledForDeletion->contains($l)) {
                $this->userLicensesScheduledForDeletion->remove($this->userLicensesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	UserLicense $userLicense The userLicense object to add.
     */
    protected function doAddUserLicense($userLicense)
    {
        $this->collUserLicenses[]= $userLicense;
        $userLicense->setUser($this);
    }

    /**
     * @param	UserLicense $userLicense The userLicense object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeUserLicense($userLicense)
    {
        if ($this->getUserLicenses()->contains($userLicense)) {
            $this->collUserLicenses->remove($this->collUserLicenses->search($userLicense));
            if (null === $this->userLicensesScheduledForDeletion) {
                $this->userLicensesScheduledForDeletion = clone $this->collUserLicenses;
                $this->userLicensesScheduledForDeletion->clear();
            }
            $this->userLicensesScheduledForDeletion[]= clone $userLicense;
            $userLicense->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related UserLicenses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserLicense[] List of UserLicense objects
     */
    public function getUserLicensesJoinLicense($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserLicenseQuery::create(null, $criteria);
        $query->joinWith('License', $join_behavior);

        return $this->getUserLicenses($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related UserLicenses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserLicense[] List of UserLicense objects
     */
    public function getUserLicensesJoinLicensePayment($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserLicenseQuery::create(null, $criteria);
        $query->joinWith('LicensePayment', $join_behavior);

        return $this->getUserLicenses($query, $con);
    }

    /**
     * Clears out the collPages collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
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
     * If this User is new, it will return
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
                    ->filterByUser($this)
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
     * @return User The current object (for fluent API support)
     */
    public function setPages(PropelCollection $pages, PropelPDO $con = null)
    {
        $pagesToDelete = $this->getPages(new Criteria(), $con)->diff($pages);


        $this->pagesScheduledForDeletion = $pagesToDelete;

        foreach ($pagesToDelete as $pageRemoved) {
            $pageRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collPages);
    }

    /**
     * Method called to associate a Page object to this object
     * through the Page foreign key attribute.
     *
     * @param    Page $l Page
     * @return User The current object (for fluent API support)
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
        $page->setUser($this);
    }

    /**
     * @param	Page $page The page object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removePage($page)
    {
        if ($this->getPages()->contains($page)) {
            $this->collPages->remove($this->collPages->search($page));
            if (null === $this->pagesScheduledForDeletion) {
                $this->pagesScheduledForDeletion = clone $this->collPages;
                $this->pagesScheduledForDeletion->clear();
            }
            $this->pagesScheduledForDeletion[]= clone $page;
            $page->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Pages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Page[] List of Page objects
     */
    public function getPagesJoinSchool($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PageQuery::create(null, $criteria);
        $query->joinWith('School', $join_behavior);

        return $this->getPages($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Pages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
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
     * @return User The current object (for fluent API support)
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
     * If this User is new, it will return
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
                    ->filterByUser($this)
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
     * @return User The current object (for fluent API support)
     */
    public function setEmployees(PropelCollection $employees, PropelPDO $con = null)
    {
        $employeesToDelete = $this->getEmployees(new Criteria(), $con)->diff($employees);


        $this->employeesScheduledForDeletion = $employeesToDelete;

        foreach ($employeesToDelete as $employeeRemoved) {
            $employeeRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collEmployees);
    }

    /**
     * Method called to associate a Employee object to this object
     * through the Employee foreign key attribute.
     *
     * @param    Employee $l Employee
     * @return User The current object (for fluent API support)
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
        $employee->setUser($this);
    }

    /**
     * @param	Employee $employee The employee object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeEmployee($employee)
    {
        if ($this->getEmployees()->contains($employee)) {
            $this->collEmployees->remove($this->collEmployees->search($employee));
            if (null === $this->employeesScheduledForDeletion) {
                $this->employeesScheduledForDeletion = clone $this->collEmployees;
                $this->employeesScheduledForDeletion->clear();
            }
            $this->employeesScheduledForDeletion[]= $employee;
            $employee->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Employees from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
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
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Employees from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Employee[] List of Employee objects
     */
    public function getEmployeesJoinSchool($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EmployeeQuery::create(null, $criteria);
        $query->joinWith('School', $join_behavior);

        return $this->getEmployees($query, $con);
    }

    /**
     * Clears out the collEthnicities collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addEthnicities()
     */
    public function clearEthnicities()
    {
        $this->collEthnicities = null; // important to set this to null since that means it is uninitialized
        $this->collEthnicitiesPartial = null;

        return $this;
    }

    /**
     * reset is the collEthnicities collection loaded partially
     *
     * @return void
     */
    public function resetPartialEthnicities($v = true)
    {
        $this->collEthnicitiesPartial = $v;
    }

    /**
     * Initializes the collEthnicities collection.
     *
     * By default this just sets the collEthnicities collection to an empty array (like clearcollEthnicities());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEthnicities($overrideExisting = true)
    {
        if (null !== $this->collEthnicities && !$overrideExisting) {
            return;
        }
        $this->collEthnicities = new PropelObjectCollection();
        $this->collEthnicities->setModel('Ethnicity');
    }

    /**
     * Gets an array of Ethnicity objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Ethnicity[] List of Ethnicity objects
     * @throws PropelException
     */
    public function getEthnicities($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEthnicitiesPartial && !$this->isNew();
        if (null === $this->collEthnicities || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEthnicities) {
                // return empty collection
                $this->initEthnicities();
            } else {
                $collEthnicities = EthnicityQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEthnicitiesPartial && count($collEthnicities)) {
                      $this->initEthnicities(false);

                      foreach ($collEthnicities as $obj) {
                        if (false == $this->collEthnicities->contains($obj)) {
                          $this->collEthnicities->append($obj);
                        }
                      }

                      $this->collEthnicitiesPartial = true;
                    }

                    $collEthnicities->getInternalIterator()->rewind();

                    return $collEthnicities;
                }

                if ($partial && $this->collEthnicities) {
                    foreach ($this->collEthnicities as $obj) {
                        if ($obj->isNew()) {
                            $collEthnicities[] = $obj;
                        }
                    }
                }

                $this->collEthnicities = $collEthnicities;
                $this->collEthnicitiesPartial = false;
            }
        }

        return $this->collEthnicities;
    }

    /**
     * Sets a collection of Ethnicity objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $ethnicities A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setEthnicities(PropelCollection $ethnicities, PropelPDO $con = null)
    {
        $ethnicitiesToDelete = $this->getEthnicities(new Criteria(), $con)->diff($ethnicities);


        $this->ethnicitiesScheduledForDeletion = $ethnicitiesToDelete;

        foreach ($ethnicitiesToDelete as $ethnicityRemoved) {
            $ethnicityRemoved->setUser(null);
        }

        $this->collEthnicities = null;
        foreach ($ethnicities as $ethnicity) {
            $this->addEthnicity($ethnicity);
        }

        $this->collEthnicities = $ethnicities;
        $this->collEthnicitiesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Ethnicity objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Ethnicity objects.
     * @throws PropelException
     */
    public function countEthnicities(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEthnicitiesPartial && !$this->isNew();
        if (null === $this->collEthnicities || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEthnicities) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEthnicities());
            }
            $query = EthnicityQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collEthnicities);
    }

    /**
     * Method called to associate a Ethnicity object to this object
     * through the Ethnicity foreign key attribute.
     *
     * @param    Ethnicity $l Ethnicity
     * @return User The current object (for fluent API support)
     */
    public function addEthnicity(Ethnicity $l)
    {
        if ($this->collEthnicities === null) {
            $this->initEthnicities();
            $this->collEthnicitiesPartial = true;
        }

        if (!in_array($l, $this->collEthnicities->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEthnicity($l);

            if ($this->ethnicitiesScheduledForDeletion and $this->ethnicitiesScheduledForDeletion->contains($l)) {
                $this->ethnicitiesScheduledForDeletion->remove($this->ethnicitiesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Ethnicity $ethnicity The ethnicity object to add.
     */
    protected function doAddEthnicity($ethnicity)
    {
        $this->collEthnicities[]= $ethnicity;
        $ethnicity->setUser($this);
    }

    /**
     * @param	Ethnicity $ethnicity The ethnicity object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeEthnicity($ethnicity)
    {
        if ($this->getEthnicities()->contains($ethnicity)) {
            $this->collEthnicities->remove($this->collEthnicities->search($ethnicity));
            if (null === $this->ethnicitiesScheduledForDeletion) {
                $this->ethnicitiesScheduledForDeletion = clone $this->collEthnicities;
                $this->ethnicitiesScheduledForDeletion->clear();
            }
            $this->ethnicitiesScheduledForDeletion[]= clone $ethnicity;
            $ethnicity->setUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collMessagesRelatedByFromId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addMessagesRelatedByFromId()
     */
    public function clearMessagesRelatedByFromId()
    {
        $this->collMessagesRelatedByFromId = null; // important to set this to null since that means it is uninitialized
        $this->collMessagesRelatedByFromIdPartial = null;

        return $this;
    }

    /**
     * reset is the collMessagesRelatedByFromId collection loaded partially
     *
     * @return void
     */
    public function resetPartialMessagesRelatedByFromId($v = true)
    {
        $this->collMessagesRelatedByFromIdPartial = $v;
    }

    /**
     * Initializes the collMessagesRelatedByFromId collection.
     *
     * By default this just sets the collMessagesRelatedByFromId collection to an empty array (like clearcollMessagesRelatedByFromId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMessagesRelatedByFromId($overrideExisting = true)
    {
        if (null !== $this->collMessagesRelatedByFromId && !$overrideExisting) {
            return;
        }
        $this->collMessagesRelatedByFromId = new PropelObjectCollection();
        $this->collMessagesRelatedByFromId->setModel('Message');
    }

    /**
     * Gets an array of Message objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Message[] List of Message objects
     * @throws PropelException
     */
    public function getMessagesRelatedByFromId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collMessagesRelatedByFromIdPartial && !$this->isNew();
        if (null === $this->collMessagesRelatedByFromId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMessagesRelatedByFromId) {
                // return empty collection
                $this->initMessagesRelatedByFromId();
            } else {
                $collMessagesRelatedByFromId = MessageQuery::create(null, $criteria)
                    ->filterByUserRelatedByFromId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collMessagesRelatedByFromIdPartial && count($collMessagesRelatedByFromId)) {
                      $this->initMessagesRelatedByFromId(false);

                      foreach ($collMessagesRelatedByFromId as $obj) {
                        if (false == $this->collMessagesRelatedByFromId->contains($obj)) {
                          $this->collMessagesRelatedByFromId->append($obj);
                        }
                      }

                      $this->collMessagesRelatedByFromIdPartial = true;
                    }

                    $collMessagesRelatedByFromId->getInternalIterator()->rewind();

                    return $collMessagesRelatedByFromId;
                }

                if ($partial && $this->collMessagesRelatedByFromId) {
                    foreach ($this->collMessagesRelatedByFromId as $obj) {
                        if ($obj->isNew()) {
                            $collMessagesRelatedByFromId[] = $obj;
                        }
                    }
                }

                $this->collMessagesRelatedByFromId = $collMessagesRelatedByFromId;
                $this->collMessagesRelatedByFromIdPartial = false;
            }
        }

        return $this->collMessagesRelatedByFromId;
    }

    /**
     * Sets a collection of MessageRelatedByFromId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $messagesRelatedByFromId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setMessagesRelatedByFromId(PropelCollection $messagesRelatedByFromId, PropelPDO $con = null)
    {
        $messagesRelatedByFromIdToDelete = $this->getMessagesRelatedByFromId(new Criteria(), $con)->diff($messagesRelatedByFromId);


        $this->messagesRelatedByFromIdScheduledForDeletion = $messagesRelatedByFromIdToDelete;

        foreach ($messagesRelatedByFromIdToDelete as $messageRelatedByFromIdRemoved) {
            $messageRelatedByFromIdRemoved->setUserRelatedByFromId(null);
        }

        $this->collMessagesRelatedByFromId = null;
        foreach ($messagesRelatedByFromId as $messageRelatedByFromId) {
            $this->addMessageRelatedByFromId($messageRelatedByFromId);
        }

        $this->collMessagesRelatedByFromId = $messagesRelatedByFromId;
        $this->collMessagesRelatedByFromIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Message objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Message objects.
     * @throws PropelException
     */
    public function countMessagesRelatedByFromId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collMessagesRelatedByFromIdPartial && !$this->isNew();
        if (null === $this->collMessagesRelatedByFromId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMessagesRelatedByFromId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMessagesRelatedByFromId());
            }
            $query = MessageQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUserRelatedByFromId($this)
                ->count($con);
        }

        return count($this->collMessagesRelatedByFromId);
    }

    /**
     * Method called to associate a Message object to this object
     * through the Message foreign key attribute.
     *
     * @param    Message $l Message
     * @return User The current object (for fluent API support)
     */
    public function addMessageRelatedByFromId(Message $l)
    {
        if ($this->collMessagesRelatedByFromId === null) {
            $this->initMessagesRelatedByFromId();
            $this->collMessagesRelatedByFromIdPartial = true;
        }

        if (!in_array($l, $this->collMessagesRelatedByFromId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddMessageRelatedByFromId($l);

            if ($this->messagesRelatedByFromIdScheduledForDeletion and $this->messagesRelatedByFromIdScheduledForDeletion->contains($l)) {
                $this->messagesRelatedByFromIdScheduledForDeletion->remove($this->messagesRelatedByFromIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	MessageRelatedByFromId $messageRelatedByFromId The messageRelatedByFromId object to add.
     */
    protected function doAddMessageRelatedByFromId($messageRelatedByFromId)
    {
        $this->collMessagesRelatedByFromId[]= $messageRelatedByFromId;
        $messageRelatedByFromId->setUserRelatedByFromId($this);
    }

    /**
     * @param	MessageRelatedByFromId $messageRelatedByFromId The messageRelatedByFromId object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeMessageRelatedByFromId($messageRelatedByFromId)
    {
        if ($this->getMessagesRelatedByFromId()->contains($messageRelatedByFromId)) {
            $this->collMessagesRelatedByFromId->remove($this->collMessagesRelatedByFromId->search($messageRelatedByFromId));
            if (null === $this->messagesRelatedByFromIdScheduledForDeletion) {
                $this->messagesRelatedByFromIdScheduledForDeletion = clone $this->collMessagesRelatedByFromId;
                $this->messagesRelatedByFromIdScheduledForDeletion->clear();
            }
            $this->messagesRelatedByFromIdScheduledForDeletion[]= clone $messageRelatedByFromId;
            $messageRelatedByFromId->setUserRelatedByFromId(null);
        }

        return $this;
    }

    /**
     * Clears out the collMessagesRelatedByToId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addMessagesRelatedByToId()
     */
    public function clearMessagesRelatedByToId()
    {
        $this->collMessagesRelatedByToId = null; // important to set this to null since that means it is uninitialized
        $this->collMessagesRelatedByToIdPartial = null;

        return $this;
    }

    /**
     * reset is the collMessagesRelatedByToId collection loaded partially
     *
     * @return void
     */
    public function resetPartialMessagesRelatedByToId($v = true)
    {
        $this->collMessagesRelatedByToIdPartial = $v;
    }

    /**
     * Initializes the collMessagesRelatedByToId collection.
     *
     * By default this just sets the collMessagesRelatedByToId collection to an empty array (like clearcollMessagesRelatedByToId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMessagesRelatedByToId($overrideExisting = true)
    {
        if (null !== $this->collMessagesRelatedByToId && !$overrideExisting) {
            return;
        }
        $this->collMessagesRelatedByToId = new PropelObjectCollection();
        $this->collMessagesRelatedByToId->setModel('Message');
    }

    /**
     * Gets an array of Message objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Message[] List of Message objects
     * @throws PropelException
     */
    public function getMessagesRelatedByToId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collMessagesRelatedByToIdPartial && !$this->isNew();
        if (null === $this->collMessagesRelatedByToId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMessagesRelatedByToId) {
                // return empty collection
                $this->initMessagesRelatedByToId();
            } else {
                $collMessagesRelatedByToId = MessageQuery::create(null, $criteria)
                    ->filterByUserRelatedByToId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collMessagesRelatedByToIdPartial && count($collMessagesRelatedByToId)) {
                      $this->initMessagesRelatedByToId(false);

                      foreach ($collMessagesRelatedByToId as $obj) {
                        if (false == $this->collMessagesRelatedByToId->contains($obj)) {
                          $this->collMessagesRelatedByToId->append($obj);
                        }
                      }

                      $this->collMessagesRelatedByToIdPartial = true;
                    }

                    $collMessagesRelatedByToId->getInternalIterator()->rewind();

                    return $collMessagesRelatedByToId;
                }

                if ($partial && $this->collMessagesRelatedByToId) {
                    foreach ($this->collMessagesRelatedByToId as $obj) {
                        if ($obj->isNew()) {
                            $collMessagesRelatedByToId[] = $obj;
                        }
                    }
                }

                $this->collMessagesRelatedByToId = $collMessagesRelatedByToId;
                $this->collMessagesRelatedByToIdPartial = false;
            }
        }

        return $this->collMessagesRelatedByToId;
    }

    /**
     * Sets a collection of MessageRelatedByToId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $messagesRelatedByToId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setMessagesRelatedByToId(PropelCollection $messagesRelatedByToId, PropelPDO $con = null)
    {
        $messagesRelatedByToIdToDelete = $this->getMessagesRelatedByToId(new Criteria(), $con)->diff($messagesRelatedByToId);


        $this->messagesRelatedByToIdScheduledForDeletion = $messagesRelatedByToIdToDelete;

        foreach ($messagesRelatedByToIdToDelete as $messageRelatedByToIdRemoved) {
            $messageRelatedByToIdRemoved->setUserRelatedByToId(null);
        }

        $this->collMessagesRelatedByToId = null;
        foreach ($messagesRelatedByToId as $messageRelatedByToId) {
            $this->addMessageRelatedByToId($messageRelatedByToId);
        }

        $this->collMessagesRelatedByToId = $messagesRelatedByToId;
        $this->collMessagesRelatedByToIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Message objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Message objects.
     * @throws PropelException
     */
    public function countMessagesRelatedByToId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collMessagesRelatedByToIdPartial && !$this->isNew();
        if (null === $this->collMessagesRelatedByToId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMessagesRelatedByToId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMessagesRelatedByToId());
            }
            $query = MessageQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUserRelatedByToId($this)
                ->count($con);
        }

        return count($this->collMessagesRelatedByToId);
    }

    /**
     * Method called to associate a Message object to this object
     * through the Message foreign key attribute.
     *
     * @param    Message $l Message
     * @return User The current object (for fluent API support)
     */
    public function addMessageRelatedByToId(Message $l)
    {
        if ($this->collMessagesRelatedByToId === null) {
            $this->initMessagesRelatedByToId();
            $this->collMessagesRelatedByToIdPartial = true;
        }

        if (!in_array($l, $this->collMessagesRelatedByToId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddMessageRelatedByToId($l);

            if ($this->messagesRelatedByToIdScheduledForDeletion and $this->messagesRelatedByToIdScheduledForDeletion->contains($l)) {
                $this->messagesRelatedByToIdScheduledForDeletion->remove($this->messagesRelatedByToIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	MessageRelatedByToId $messageRelatedByToId The messageRelatedByToId object to add.
     */
    protected function doAddMessageRelatedByToId($messageRelatedByToId)
    {
        $this->collMessagesRelatedByToId[]= $messageRelatedByToId;
        $messageRelatedByToId->setUserRelatedByToId($this);
    }

    /**
     * @param	MessageRelatedByToId $messageRelatedByToId The messageRelatedByToId object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeMessageRelatedByToId($messageRelatedByToId)
    {
        if ($this->getMessagesRelatedByToId()->contains($messageRelatedByToId)) {
            $this->collMessagesRelatedByToId->remove($this->collMessagesRelatedByToId->search($messageRelatedByToId));
            if (null === $this->messagesRelatedByToIdScheduledForDeletion) {
                $this->messagesRelatedByToIdScheduledForDeletion = clone $this->collMessagesRelatedByToId;
                $this->messagesRelatedByToIdScheduledForDeletion->clear();
            }
            $this->messagesRelatedByToIdScheduledForDeletion[]= clone $messageRelatedByToId;
            $messageRelatedByToId->setUserRelatedByToId(null);
        }

        return $this;
    }

    /**
     * Clears out the collOrganizations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addOrganizations()
     */
    public function clearOrganizations()
    {
        $this->collOrganizations = null; // important to set this to null since that means it is uninitialized
        $this->collOrganizationsPartial = null;

        return $this;
    }

    /**
     * reset is the collOrganizations collection loaded partially
     *
     * @return void
     */
    public function resetPartialOrganizations($v = true)
    {
        $this->collOrganizationsPartial = $v;
    }

    /**
     * Initializes the collOrganizations collection.
     *
     * By default this just sets the collOrganizations collection to an empty array (like clearcollOrganizations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOrganizations($overrideExisting = true)
    {
        if (null !== $this->collOrganizations && !$overrideExisting) {
            return;
        }
        $this->collOrganizations = new PropelObjectCollection();
        $this->collOrganizations->setModel('Organization');
    }

    /**
     * Gets an array of Organization objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Organization[] List of Organization objects
     * @throws PropelException
     */
    public function getOrganizations($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collOrganizationsPartial && !$this->isNew();
        if (null === $this->collOrganizations || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collOrganizations) {
                // return empty collection
                $this->initOrganizations();
            } else {
                $collOrganizations = OrganizationQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collOrganizationsPartial && count($collOrganizations)) {
                      $this->initOrganizations(false);

                      foreach ($collOrganizations as $obj) {
                        if (false == $this->collOrganizations->contains($obj)) {
                          $this->collOrganizations->append($obj);
                        }
                      }

                      $this->collOrganizationsPartial = true;
                    }

                    $collOrganizations->getInternalIterator()->rewind();

                    return $collOrganizations;
                }

                if ($partial && $this->collOrganizations) {
                    foreach ($this->collOrganizations as $obj) {
                        if ($obj->isNew()) {
                            $collOrganizations[] = $obj;
                        }
                    }
                }

                $this->collOrganizations = $collOrganizations;
                $this->collOrganizationsPartial = false;
            }
        }

        return $this->collOrganizations;
    }

    /**
     * Sets a collection of Organization objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $organizations A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setOrganizations(PropelCollection $organizations, PropelPDO $con = null)
    {
        $organizationsToDelete = $this->getOrganizations(new Criteria(), $con)->diff($organizations);


        $this->organizationsScheduledForDeletion = $organizationsToDelete;

        foreach ($organizationsToDelete as $organizationRemoved) {
            $organizationRemoved->setUser(null);
        }

        $this->collOrganizations = null;
        foreach ($organizations as $organization) {
            $this->addOrganization($organization);
        }

        $this->collOrganizations = $organizations;
        $this->collOrganizationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Organization objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Organization objects.
     * @throws PropelException
     */
    public function countOrganizations(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collOrganizationsPartial && !$this->isNew();
        if (null === $this->collOrganizations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOrganizations) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOrganizations());
            }
            $query = OrganizationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collOrganizations);
    }

    /**
     * Method called to associate a Organization object to this object
     * through the Organization foreign key attribute.
     *
     * @param    Organization $l Organization
     * @return User The current object (for fluent API support)
     */
    public function addOrganization(Organization $l)
    {
        if ($this->collOrganizations === null) {
            $this->initOrganizations();
            $this->collOrganizationsPartial = true;
        }

        if (!in_array($l, $this->collOrganizations->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddOrganization($l);

            if ($this->organizationsScheduledForDeletion and $this->organizationsScheduledForDeletion->contains($l)) {
                $this->organizationsScheduledForDeletion->remove($this->organizationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Organization $organization The organization object to add.
     */
    protected function doAddOrganization($organization)
    {
        $this->collOrganizations[]= $organization;
        $organization->setUser($this);
    }

    /**
     * @param	Organization $organization The organization object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeOrganization($organization)
    {
        if ($this->getOrganizations()->contains($organization)) {
            $this->collOrganizations->remove($this->collOrganizations->search($organization));
            if (null === $this->organizationsScheduledForDeletion) {
                $this->organizationsScheduledForDeletion = clone $this->collOrganizations;
                $this->organizationsScheduledForDeletion->clear();
            }
            $this->organizationsScheduledForDeletion[]= $organization;
            $organization->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Organizations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Organization[] List of Organization objects
     */
    public function getOrganizationsJoinState($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = OrganizationQuery::create(null, $criteria);
        $query->joinWith('State', $join_behavior);

        return $this->getOrganizations($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Organizations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Organization[] List of Organization objects
     */
    public function getOrganizationsJoinCountry($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = OrganizationQuery::create(null, $criteria);
        $query->joinWith('Country', $join_behavior);

        return $this->getOrganizations($query, $con);
    }

    /**
     * Clears out the collParentStudents collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
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
     * If this User is new, it will return
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
                    ->filterByUser($this)
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
     * @return User The current object (for fluent API support)
     */
    public function setParentStudents(PropelCollection $parentStudents, PropelPDO $con = null)
    {
        $parentStudentsToDelete = $this->getParentStudents(new Criteria(), $con)->diff($parentStudents);


        $this->parentStudentsScheduledForDeletion = $parentStudentsToDelete;

        foreach ($parentStudentsToDelete as $parentStudentRemoved) {
            $parentStudentRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collParentStudents);
    }

    /**
     * Method called to associate a ParentStudent object to this object
     * through the ParentStudent foreign key attribute.
     *
     * @param    ParentStudent $l ParentStudent
     * @return User The current object (for fluent API support)
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
        $parentStudent->setUser($this);
    }

    /**
     * @param	ParentStudent $parentStudent The parentStudent object to remove.
     * @return User The current object (for fluent API support)
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
            $parentStudent->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related ParentStudents from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
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
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related ParentStudents from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
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
     * Clears out the collReligions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addReligions()
     */
    public function clearReligions()
    {
        $this->collReligions = null; // important to set this to null since that means it is uninitialized
        $this->collReligionsPartial = null;

        return $this;
    }

    /**
     * reset is the collReligions collection loaded partially
     *
     * @return void
     */
    public function resetPartialReligions($v = true)
    {
        $this->collReligionsPartial = $v;
    }

    /**
     * Initializes the collReligions collection.
     *
     * By default this just sets the collReligions collection to an empty array (like clearcollReligions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initReligions($overrideExisting = true)
    {
        if (null !== $this->collReligions && !$overrideExisting) {
            return;
        }
        $this->collReligions = new PropelObjectCollection();
        $this->collReligions->setModel('Religion');
    }

    /**
     * Gets an array of Religion objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Religion[] List of Religion objects
     * @throws PropelException
     */
    public function getReligions($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collReligionsPartial && !$this->isNew();
        if (null === $this->collReligions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collReligions) {
                // return empty collection
                $this->initReligions();
            } else {
                $collReligions = ReligionQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collReligionsPartial && count($collReligions)) {
                      $this->initReligions(false);

                      foreach ($collReligions as $obj) {
                        if (false == $this->collReligions->contains($obj)) {
                          $this->collReligions->append($obj);
                        }
                      }

                      $this->collReligionsPartial = true;
                    }

                    $collReligions->getInternalIterator()->rewind();

                    return $collReligions;
                }

                if ($partial && $this->collReligions) {
                    foreach ($this->collReligions as $obj) {
                        if ($obj->isNew()) {
                            $collReligions[] = $obj;
                        }
                    }
                }

                $this->collReligions = $collReligions;
                $this->collReligionsPartial = false;
            }
        }

        return $this->collReligions;
    }

    /**
     * Sets a collection of Religion objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $religions A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setReligions(PropelCollection $religions, PropelPDO $con = null)
    {
        $religionsToDelete = $this->getReligions(new Criteria(), $con)->diff($religions);


        $this->religionsScheduledForDeletion = $religionsToDelete;

        foreach ($religionsToDelete as $religionRemoved) {
            $religionRemoved->setUser(null);
        }

        $this->collReligions = null;
        foreach ($religions as $religion) {
            $this->addReligion($religion);
        }

        $this->collReligions = $religions;
        $this->collReligionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Religion objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Religion objects.
     * @throws PropelException
     */
    public function countReligions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collReligionsPartial && !$this->isNew();
        if (null === $this->collReligions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collReligions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getReligions());
            }
            $query = ReligionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collReligions);
    }

    /**
     * Method called to associate a Religion object to this object
     * through the Religion foreign key attribute.
     *
     * @param    Religion $l Religion
     * @return User The current object (for fluent API support)
     */
    public function addReligion(Religion $l)
    {
        if ($this->collReligions === null) {
            $this->initReligions();
            $this->collReligionsPartial = true;
        }

        if (!in_array($l, $this->collReligions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddReligion($l);

            if ($this->religionsScheduledForDeletion and $this->religionsScheduledForDeletion->contains($l)) {
                $this->religionsScheduledForDeletion->remove($this->religionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Religion $religion The religion object to add.
     */
    protected function doAddReligion($religion)
    {
        $this->collReligions[]= $religion;
        $religion->setUser($this);
    }

    /**
     * @param	Religion $religion The religion object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeReligion($religion)
    {
        if ($this->getReligions()->contains($religion)) {
            $this->collReligions->remove($this->collReligions->search($religion));
            if (null === $this->religionsScheduledForDeletion) {
                $this->religionsScheduledForDeletion = clone $this->collReligions;
                $this->religionsScheduledForDeletion->clear();
            }
            $this->religionsScheduledForDeletion[]= clone $religion;
            $religion->setUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collSchoolClassCoursesRelatedByPrimaryTeacherId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addSchoolClassCoursesRelatedByPrimaryTeacherId()
     */
    public function clearSchoolClassCoursesRelatedByPrimaryTeacherId()
    {
        $this->collSchoolClassCoursesRelatedByPrimaryTeacherId = null; // important to set this to null since that means it is uninitialized
        $this->collSchoolClassCoursesRelatedByPrimaryTeacherIdPartial = null;

        return $this;
    }

    /**
     * reset is the collSchoolClassCoursesRelatedByPrimaryTeacherId collection loaded partially
     *
     * @return void
     */
    public function resetPartialSchoolClassCoursesRelatedByPrimaryTeacherId($v = true)
    {
        $this->collSchoolClassCoursesRelatedByPrimaryTeacherIdPartial = $v;
    }

    /**
     * Initializes the collSchoolClassCoursesRelatedByPrimaryTeacherId collection.
     *
     * By default this just sets the collSchoolClassCoursesRelatedByPrimaryTeacherId collection to an empty array (like clearcollSchoolClassCoursesRelatedByPrimaryTeacherId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSchoolClassCoursesRelatedByPrimaryTeacherId($overrideExisting = true)
    {
        if (null !== $this->collSchoolClassCoursesRelatedByPrimaryTeacherId && !$overrideExisting) {
            return;
        }
        $this->collSchoolClassCoursesRelatedByPrimaryTeacherId = new PropelObjectCollection();
        $this->collSchoolClassCoursesRelatedByPrimaryTeacherId->setModel('SchoolClassCourse');
    }

    /**
     * Gets an array of SchoolClassCourse objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|SchoolClassCourse[] List of SchoolClassCourse objects
     * @throws PropelException
     */
    public function getSchoolClassCoursesRelatedByPrimaryTeacherId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSchoolClassCoursesRelatedByPrimaryTeacherIdPartial && !$this->isNew();
        if (null === $this->collSchoolClassCoursesRelatedByPrimaryTeacherId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSchoolClassCoursesRelatedByPrimaryTeacherId) {
                // return empty collection
                $this->initSchoolClassCoursesRelatedByPrimaryTeacherId();
            } else {
                $collSchoolClassCoursesRelatedByPrimaryTeacherId = SchoolClassCourseQuery::create(null, $criteria)
                    ->filterByPrimaryTeacher($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSchoolClassCoursesRelatedByPrimaryTeacherIdPartial && count($collSchoolClassCoursesRelatedByPrimaryTeacherId)) {
                      $this->initSchoolClassCoursesRelatedByPrimaryTeacherId(false);

                      foreach ($collSchoolClassCoursesRelatedByPrimaryTeacherId as $obj) {
                        if (false == $this->collSchoolClassCoursesRelatedByPrimaryTeacherId->contains($obj)) {
                          $this->collSchoolClassCoursesRelatedByPrimaryTeacherId->append($obj);
                        }
                      }

                      $this->collSchoolClassCoursesRelatedByPrimaryTeacherIdPartial = true;
                    }

                    $collSchoolClassCoursesRelatedByPrimaryTeacherId->getInternalIterator()->rewind();

                    return $collSchoolClassCoursesRelatedByPrimaryTeacherId;
                }

                if ($partial && $this->collSchoolClassCoursesRelatedByPrimaryTeacherId) {
                    foreach ($this->collSchoolClassCoursesRelatedByPrimaryTeacherId as $obj) {
                        if ($obj->isNew()) {
                            $collSchoolClassCoursesRelatedByPrimaryTeacherId[] = $obj;
                        }
                    }
                }

                $this->collSchoolClassCoursesRelatedByPrimaryTeacherId = $collSchoolClassCoursesRelatedByPrimaryTeacherId;
                $this->collSchoolClassCoursesRelatedByPrimaryTeacherIdPartial = false;
            }
        }

        return $this->collSchoolClassCoursesRelatedByPrimaryTeacherId;
    }

    /**
     * Sets a collection of SchoolClassCourseRelatedByPrimaryTeacherId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $schoolClassCoursesRelatedByPrimaryTeacherId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setSchoolClassCoursesRelatedByPrimaryTeacherId(PropelCollection $schoolClassCoursesRelatedByPrimaryTeacherId, PropelPDO $con = null)
    {
        $schoolClassCoursesRelatedByPrimaryTeacherIdToDelete = $this->getSchoolClassCoursesRelatedByPrimaryTeacherId(new Criteria(), $con)->diff($schoolClassCoursesRelatedByPrimaryTeacherId);


        $this->schoolClassCoursesRelatedByPrimaryTeacherIdScheduledForDeletion = $schoolClassCoursesRelatedByPrimaryTeacherIdToDelete;

        foreach ($schoolClassCoursesRelatedByPrimaryTeacherIdToDelete as $schoolClassCourseRelatedByPrimaryTeacherIdRemoved) {
            $schoolClassCourseRelatedByPrimaryTeacherIdRemoved->setPrimaryTeacher(null);
        }

        $this->collSchoolClassCoursesRelatedByPrimaryTeacherId = null;
        foreach ($schoolClassCoursesRelatedByPrimaryTeacherId as $schoolClassCourseRelatedByPrimaryTeacherId) {
            $this->addSchoolClassCourseRelatedByPrimaryTeacherId($schoolClassCourseRelatedByPrimaryTeacherId);
        }

        $this->collSchoolClassCoursesRelatedByPrimaryTeacherId = $schoolClassCoursesRelatedByPrimaryTeacherId;
        $this->collSchoolClassCoursesRelatedByPrimaryTeacherIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SchoolClassCourse objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related SchoolClassCourse objects.
     * @throws PropelException
     */
    public function countSchoolClassCoursesRelatedByPrimaryTeacherId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSchoolClassCoursesRelatedByPrimaryTeacherIdPartial && !$this->isNew();
        if (null === $this->collSchoolClassCoursesRelatedByPrimaryTeacherId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSchoolClassCoursesRelatedByPrimaryTeacherId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSchoolClassCoursesRelatedByPrimaryTeacherId());
            }
            $query = SchoolClassCourseQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPrimaryTeacher($this)
                ->count($con);
        }

        return count($this->collSchoolClassCoursesRelatedByPrimaryTeacherId);
    }

    /**
     * Method called to associate a SchoolClassCourse object to this object
     * through the SchoolClassCourse foreign key attribute.
     *
     * @param    SchoolClassCourse $l SchoolClassCourse
     * @return User The current object (for fluent API support)
     */
    public function addSchoolClassCourseRelatedByPrimaryTeacherId(SchoolClassCourse $l)
    {
        if ($this->collSchoolClassCoursesRelatedByPrimaryTeacherId === null) {
            $this->initSchoolClassCoursesRelatedByPrimaryTeacherId();
            $this->collSchoolClassCoursesRelatedByPrimaryTeacherIdPartial = true;
        }

        if (!in_array($l, $this->collSchoolClassCoursesRelatedByPrimaryTeacherId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSchoolClassCourseRelatedByPrimaryTeacherId($l);

            if ($this->schoolClassCoursesRelatedByPrimaryTeacherIdScheduledForDeletion and $this->schoolClassCoursesRelatedByPrimaryTeacherIdScheduledForDeletion->contains($l)) {
                $this->schoolClassCoursesRelatedByPrimaryTeacherIdScheduledForDeletion->remove($this->schoolClassCoursesRelatedByPrimaryTeacherIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	SchoolClassCourseRelatedByPrimaryTeacherId $schoolClassCourseRelatedByPrimaryTeacherId The schoolClassCourseRelatedByPrimaryTeacherId object to add.
     */
    protected function doAddSchoolClassCourseRelatedByPrimaryTeacherId($schoolClassCourseRelatedByPrimaryTeacherId)
    {
        $this->collSchoolClassCoursesRelatedByPrimaryTeacherId[]= $schoolClassCourseRelatedByPrimaryTeacherId;
        $schoolClassCourseRelatedByPrimaryTeacherId->setPrimaryTeacher($this);
    }

    /**
     * @param	SchoolClassCourseRelatedByPrimaryTeacherId $schoolClassCourseRelatedByPrimaryTeacherId The schoolClassCourseRelatedByPrimaryTeacherId object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeSchoolClassCourseRelatedByPrimaryTeacherId($schoolClassCourseRelatedByPrimaryTeacherId)
    {
        if ($this->getSchoolClassCoursesRelatedByPrimaryTeacherId()->contains($schoolClassCourseRelatedByPrimaryTeacherId)) {
            $this->collSchoolClassCoursesRelatedByPrimaryTeacherId->remove($this->collSchoolClassCoursesRelatedByPrimaryTeacherId->search($schoolClassCourseRelatedByPrimaryTeacherId));
            if (null === $this->schoolClassCoursesRelatedByPrimaryTeacherIdScheduledForDeletion) {
                $this->schoolClassCoursesRelatedByPrimaryTeacherIdScheduledForDeletion = clone $this->collSchoolClassCoursesRelatedByPrimaryTeacherId;
                $this->schoolClassCoursesRelatedByPrimaryTeacherIdScheduledForDeletion->clear();
            }
            $this->schoolClassCoursesRelatedByPrimaryTeacherIdScheduledForDeletion[]= $schoolClassCourseRelatedByPrimaryTeacherId;
            $schoolClassCourseRelatedByPrimaryTeacherId->setPrimaryTeacher(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related SchoolClassCoursesRelatedByPrimaryTeacherId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassCourse[] List of SchoolClassCourse objects
     */
    public function getSchoolClassCoursesRelatedByPrimaryTeacherIdJoinCourse($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassCourseQuery::create(null, $criteria);
        $query->joinWith('Course', $join_behavior);

        return $this->getSchoolClassCoursesRelatedByPrimaryTeacherId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related SchoolClassCoursesRelatedByPrimaryTeacherId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassCourse[] List of SchoolClassCourse objects
     */
    public function getSchoolClassCoursesRelatedByPrimaryTeacherIdJoinSchoolClass($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassCourseQuery::create(null, $criteria);
        $query->joinWith('SchoolClass', $join_behavior);

        return $this->getSchoolClassCoursesRelatedByPrimaryTeacherId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related SchoolClassCoursesRelatedByPrimaryTeacherId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassCourse[] List of SchoolClassCourse objects
     */
    public function getSchoolClassCoursesRelatedByPrimaryTeacherIdJoinSchoolTerm($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassCourseQuery::create(null, $criteria);
        $query->joinWith('SchoolTerm', $join_behavior);

        return $this->getSchoolClassCoursesRelatedByPrimaryTeacherId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related SchoolClassCoursesRelatedByPrimaryTeacherId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassCourse[] List of SchoolClassCourse objects
     */
    public function getSchoolClassCoursesRelatedByPrimaryTeacherIdJoinSchoolGradeLevel($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassCourseQuery::create(null, $criteria);
        $query->joinWith('SchoolGradeLevel', $join_behavior);

        return $this->getSchoolClassCoursesRelatedByPrimaryTeacherId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related SchoolClassCoursesRelatedByPrimaryTeacherId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassCourse[] List of SchoolClassCourse objects
     */
    public function getSchoolClassCoursesRelatedByPrimaryTeacherIdJoinFormula($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassCourseQuery::create(null, $criteria);
        $query->joinWith('Formula', $join_behavior);

        return $this->getSchoolClassCoursesRelatedByPrimaryTeacherId($query, $con);
    }

    /**
     * Clears out the collSchoolClassCoursesRelatedBySecondaryTeacherId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addSchoolClassCoursesRelatedBySecondaryTeacherId()
     */
    public function clearSchoolClassCoursesRelatedBySecondaryTeacherId()
    {
        $this->collSchoolClassCoursesRelatedBySecondaryTeacherId = null; // important to set this to null since that means it is uninitialized
        $this->collSchoolClassCoursesRelatedBySecondaryTeacherIdPartial = null;

        return $this;
    }

    /**
     * reset is the collSchoolClassCoursesRelatedBySecondaryTeacherId collection loaded partially
     *
     * @return void
     */
    public function resetPartialSchoolClassCoursesRelatedBySecondaryTeacherId($v = true)
    {
        $this->collSchoolClassCoursesRelatedBySecondaryTeacherIdPartial = $v;
    }

    /**
     * Initializes the collSchoolClassCoursesRelatedBySecondaryTeacherId collection.
     *
     * By default this just sets the collSchoolClassCoursesRelatedBySecondaryTeacherId collection to an empty array (like clearcollSchoolClassCoursesRelatedBySecondaryTeacherId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSchoolClassCoursesRelatedBySecondaryTeacherId($overrideExisting = true)
    {
        if (null !== $this->collSchoolClassCoursesRelatedBySecondaryTeacherId && !$overrideExisting) {
            return;
        }
        $this->collSchoolClassCoursesRelatedBySecondaryTeacherId = new PropelObjectCollection();
        $this->collSchoolClassCoursesRelatedBySecondaryTeacherId->setModel('SchoolClassCourse');
    }

    /**
     * Gets an array of SchoolClassCourse objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|SchoolClassCourse[] List of SchoolClassCourse objects
     * @throws PropelException
     */
    public function getSchoolClassCoursesRelatedBySecondaryTeacherId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSchoolClassCoursesRelatedBySecondaryTeacherIdPartial && !$this->isNew();
        if (null === $this->collSchoolClassCoursesRelatedBySecondaryTeacherId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSchoolClassCoursesRelatedBySecondaryTeacherId) {
                // return empty collection
                $this->initSchoolClassCoursesRelatedBySecondaryTeacherId();
            } else {
                $collSchoolClassCoursesRelatedBySecondaryTeacherId = SchoolClassCourseQuery::create(null, $criteria)
                    ->filterBySecondaryTeacher($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSchoolClassCoursesRelatedBySecondaryTeacherIdPartial && count($collSchoolClassCoursesRelatedBySecondaryTeacherId)) {
                      $this->initSchoolClassCoursesRelatedBySecondaryTeacherId(false);

                      foreach ($collSchoolClassCoursesRelatedBySecondaryTeacherId as $obj) {
                        if (false == $this->collSchoolClassCoursesRelatedBySecondaryTeacherId->contains($obj)) {
                          $this->collSchoolClassCoursesRelatedBySecondaryTeacherId->append($obj);
                        }
                      }

                      $this->collSchoolClassCoursesRelatedBySecondaryTeacherIdPartial = true;
                    }

                    $collSchoolClassCoursesRelatedBySecondaryTeacherId->getInternalIterator()->rewind();

                    return $collSchoolClassCoursesRelatedBySecondaryTeacherId;
                }

                if ($partial && $this->collSchoolClassCoursesRelatedBySecondaryTeacherId) {
                    foreach ($this->collSchoolClassCoursesRelatedBySecondaryTeacherId as $obj) {
                        if ($obj->isNew()) {
                            $collSchoolClassCoursesRelatedBySecondaryTeacherId[] = $obj;
                        }
                    }
                }

                $this->collSchoolClassCoursesRelatedBySecondaryTeacherId = $collSchoolClassCoursesRelatedBySecondaryTeacherId;
                $this->collSchoolClassCoursesRelatedBySecondaryTeacherIdPartial = false;
            }
        }

        return $this->collSchoolClassCoursesRelatedBySecondaryTeacherId;
    }

    /**
     * Sets a collection of SchoolClassCourseRelatedBySecondaryTeacherId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $schoolClassCoursesRelatedBySecondaryTeacherId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setSchoolClassCoursesRelatedBySecondaryTeacherId(PropelCollection $schoolClassCoursesRelatedBySecondaryTeacherId, PropelPDO $con = null)
    {
        $schoolClassCoursesRelatedBySecondaryTeacherIdToDelete = $this->getSchoolClassCoursesRelatedBySecondaryTeacherId(new Criteria(), $con)->diff($schoolClassCoursesRelatedBySecondaryTeacherId);


        $this->schoolClassCoursesRelatedBySecondaryTeacherIdScheduledForDeletion = $schoolClassCoursesRelatedBySecondaryTeacherIdToDelete;

        foreach ($schoolClassCoursesRelatedBySecondaryTeacherIdToDelete as $schoolClassCourseRelatedBySecondaryTeacherIdRemoved) {
            $schoolClassCourseRelatedBySecondaryTeacherIdRemoved->setSecondaryTeacher(null);
        }

        $this->collSchoolClassCoursesRelatedBySecondaryTeacherId = null;
        foreach ($schoolClassCoursesRelatedBySecondaryTeacherId as $schoolClassCourseRelatedBySecondaryTeacherId) {
            $this->addSchoolClassCourseRelatedBySecondaryTeacherId($schoolClassCourseRelatedBySecondaryTeacherId);
        }

        $this->collSchoolClassCoursesRelatedBySecondaryTeacherId = $schoolClassCoursesRelatedBySecondaryTeacherId;
        $this->collSchoolClassCoursesRelatedBySecondaryTeacherIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SchoolClassCourse objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related SchoolClassCourse objects.
     * @throws PropelException
     */
    public function countSchoolClassCoursesRelatedBySecondaryTeacherId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSchoolClassCoursesRelatedBySecondaryTeacherIdPartial && !$this->isNew();
        if (null === $this->collSchoolClassCoursesRelatedBySecondaryTeacherId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSchoolClassCoursesRelatedBySecondaryTeacherId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSchoolClassCoursesRelatedBySecondaryTeacherId());
            }
            $query = SchoolClassCourseQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySecondaryTeacher($this)
                ->count($con);
        }

        return count($this->collSchoolClassCoursesRelatedBySecondaryTeacherId);
    }

    /**
     * Method called to associate a SchoolClassCourse object to this object
     * through the SchoolClassCourse foreign key attribute.
     *
     * @param    SchoolClassCourse $l SchoolClassCourse
     * @return User The current object (for fluent API support)
     */
    public function addSchoolClassCourseRelatedBySecondaryTeacherId(SchoolClassCourse $l)
    {
        if ($this->collSchoolClassCoursesRelatedBySecondaryTeacherId === null) {
            $this->initSchoolClassCoursesRelatedBySecondaryTeacherId();
            $this->collSchoolClassCoursesRelatedBySecondaryTeacherIdPartial = true;
        }

        if (!in_array($l, $this->collSchoolClassCoursesRelatedBySecondaryTeacherId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSchoolClassCourseRelatedBySecondaryTeacherId($l);

            if ($this->schoolClassCoursesRelatedBySecondaryTeacherIdScheduledForDeletion and $this->schoolClassCoursesRelatedBySecondaryTeacherIdScheduledForDeletion->contains($l)) {
                $this->schoolClassCoursesRelatedBySecondaryTeacherIdScheduledForDeletion->remove($this->schoolClassCoursesRelatedBySecondaryTeacherIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	SchoolClassCourseRelatedBySecondaryTeacherId $schoolClassCourseRelatedBySecondaryTeacherId The schoolClassCourseRelatedBySecondaryTeacherId object to add.
     */
    protected function doAddSchoolClassCourseRelatedBySecondaryTeacherId($schoolClassCourseRelatedBySecondaryTeacherId)
    {
        $this->collSchoolClassCoursesRelatedBySecondaryTeacherId[]= $schoolClassCourseRelatedBySecondaryTeacherId;
        $schoolClassCourseRelatedBySecondaryTeacherId->setSecondaryTeacher($this);
    }

    /**
     * @param	SchoolClassCourseRelatedBySecondaryTeacherId $schoolClassCourseRelatedBySecondaryTeacherId The schoolClassCourseRelatedBySecondaryTeacherId object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeSchoolClassCourseRelatedBySecondaryTeacherId($schoolClassCourseRelatedBySecondaryTeacherId)
    {
        if ($this->getSchoolClassCoursesRelatedBySecondaryTeacherId()->contains($schoolClassCourseRelatedBySecondaryTeacherId)) {
            $this->collSchoolClassCoursesRelatedBySecondaryTeacherId->remove($this->collSchoolClassCoursesRelatedBySecondaryTeacherId->search($schoolClassCourseRelatedBySecondaryTeacherId));
            if (null === $this->schoolClassCoursesRelatedBySecondaryTeacherIdScheduledForDeletion) {
                $this->schoolClassCoursesRelatedBySecondaryTeacherIdScheduledForDeletion = clone $this->collSchoolClassCoursesRelatedBySecondaryTeacherId;
                $this->schoolClassCoursesRelatedBySecondaryTeacherIdScheduledForDeletion->clear();
            }
            $this->schoolClassCoursesRelatedBySecondaryTeacherIdScheduledForDeletion[]= $schoolClassCourseRelatedBySecondaryTeacherId;
            $schoolClassCourseRelatedBySecondaryTeacherId->setSecondaryTeacher(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related SchoolClassCoursesRelatedBySecondaryTeacherId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassCourse[] List of SchoolClassCourse objects
     */
    public function getSchoolClassCoursesRelatedBySecondaryTeacherIdJoinCourse($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassCourseQuery::create(null, $criteria);
        $query->joinWith('Course', $join_behavior);

        return $this->getSchoolClassCoursesRelatedBySecondaryTeacherId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related SchoolClassCoursesRelatedBySecondaryTeacherId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassCourse[] List of SchoolClassCourse objects
     */
    public function getSchoolClassCoursesRelatedBySecondaryTeacherIdJoinSchoolClass($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassCourseQuery::create(null, $criteria);
        $query->joinWith('SchoolClass', $join_behavior);

        return $this->getSchoolClassCoursesRelatedBySecondaryTeacherId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related SchoolClassCoursesRelatedBySecondaryTeacherId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassCourse[] List of SchoolClassCourse objects
     */
    public function getSchoolClassCoursesRelatedBySecondaryTeacherIdJoinSchoolTerm($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassCourseQuery::create(null, $criteria);
        $query->joinWith('SchoolTerm', $join_behavior);

        return $this->getSchoolClassCoursesRelatedBySecondaryTeacherId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related SchoolClassCoursesRelatedBySecondaryTeacherId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassCourse[] List of SchoolClassCourse objects
     */
    public function getSchoolClassCoursesRelatedBySecondaryTeacherIdJoinSchoolGradeLevel($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassCourseQuery::create(null, $criteria);
        $query->joinWith('SchoolGradeLevel', $join_behavior);

        return $this->getSchoolClassCoursesRelatedBySecondaryTeacherId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related SchoolClassCoursesRelatedBySecondaryTeacherId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassCourse[] List of SchoolClassCourse objects
     */
    public function getSchoolClassCoursesRelatedBySecondaryTeacherIdJoinFormula($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassCourseQuery::create(null, $criteria);
        $query->joinWith('Formula', $join_behavior);

        return $this->getSchoolClassCoursesRelatedBySecondaryTeacherId($query, $con);
    }

    /**
     * Clears out the collStudents collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
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
     * If this User is new, it will return
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
                    ->filterByUser($this)
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
     * @return User The current object (for fluent API support)
     */
    public function setStudents(PropelCollection $students, PropelPDO $con = null)
    {
        $studentsToDelete = $this->getStudents(new Criteria(), $con)->diff($students);


        $this->studentsScheduledForDeletion = $studentsToDelete;

        foreach ($studentsToDelete as $studentRemoved) {
            $studentRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collStudents);
    }

    /**
     * Method called to associate a Student object to this object
     * through the Student foreign key attribute.
     *
     * @param    Student $l Student
     * @return User The current object (for fluent API support)
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
        $student->setUser($this);
    }

    /**
     * @param	Student $student The student object to remove.
     * @return User The current object (for fluent API support)
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
            $student->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Students from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Student[] List of Student objects
     */
    public function getStudentsJoinApplication($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StudentQuery::create(null, $criteria);
        $query->joinWith('Application', $join_behavior);

        return $this->getStudents($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Students from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
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
     * Clears out the collStudentAvatars collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addStudentAvatars()
     */
    public function clearStudentAvatars()
    {
        $this->collStudentAvatars = null; // important to set this to null since that means it is uninitialized
        $this->collStudentAvatarsPartial = null;

        return $this;
    }

    /**
     * reset is the collStudentAvatars collection loaded partially
     *
     * @return void
     */
    public function resetPartialStudentAvatars($v = true)
    {
        $this->collStudentAvatarsPartial = $v;
    }

    /**
     * Initializes the collStudentAvatars collection.
     *
     * By default this just sets the collStudentAvatars collection to an empty array (like clearcollStudentAvatars());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStudentAvatars($overrideExisting = true)
    {
        if (null !== $this->collStudentAvatars && !$overrideExisting) {
            return;
        }
        $this->collStudentAvatars = new PropelObjectCollection();
        $this->collStudentAvatars->setModel('StudentAvatar');
    }

    /**
     * Gets an array of StudentAvatar objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|StudentAvatar[] List of StudentAvatar objects
     * @throws PropelException
     */
    public function getStudentAvatars($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collStudentAvatarsPartial && !$this->isNew();
        if (null === $this->collStudentAvatars || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collStudentAvatars) {
                // return empty collection
                $this->initStudentAvatars();
            } else {
                $collStudentAvatars = StudentAvatarQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collStudentAvatarsPartial && count($collStudentAvatars)) {
                      $this->initStudentAvatars(false);

                      foreach ($collStudentAvatars as $obj) {
                        if (false == $this->collStudentAvatars->contains($obj)) {
                          $this->collStudentAvatars->append($obj);
                        }
                      }

                      $this->collStudentAvatarsPartial = true;
                    }

                    $collStudentAvatars->getInternalIterator()->rewind();

                    return $collStudentAvatars;
                }

                if ($partial && $this->collStudentAvatars) {
                    foreach ($this->collStudentAvatars as $obj) {
                        if ($obj->isNew()) {
                            $collStudentAvatars[] = $obj;
                        }
                    }
                }

                $this->collStudentAvatars = $collStudentAvatars;
                $this->collStudentAvatarsPartial = false;
            }
        }

        return $this->collStudentAvatars;
    }

    /**
     * Sets a collection of StudentAvatar objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $studentAvatars A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setStudentAvatars(PropelCollection $studentAvatars, PropelPDO $con = null)
    {
        $studentAvatarsToDelete = $this->getStudentAvatars(new Criteria(), $con)->diff($studentAvatars);


        $this->studentAvatarsScheduledForDeletion = $studentAvatarsToDelete;

        foreach ($studentAvatarsToDelete as $studentAvatarRemoved) {
            $studentAvatarRemoved->setUser(null);
        }

        $this->collStudentAvatars = null;
        foreach ($studentAvatars as $studentAvatar) {
            $this->addStudentAvatar($studentAvatar);
        }

        $this->collStudentAvatars = $studentAvatars;
        $this->collStudentAvatarsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related StudentAvatar objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related StudentAvatar objects.
     * @throws PropelException
     */
    public function countStudentAvatars(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collStudentAvatarsPartial && !$this->isNew();
        if (null === $this->collStudentAvatars || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStudentAvatars) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStudentAvatars());
            }
            $query = StudentAvatarQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collStudentAvatars);
    }

    /**
     * Method called to associate a StudentAvatar object to this object
     * through the StudentAvatar foreign key attribute.
     *
     * @param    StudentAvatar $l StudentAvatar
     * @return User The current object (for fluent API support)
     */
    public function addStudentAvatar(StudentAvatar $l)
    {
        if ($this->collStudentAvatars === null) {
            $this->initStudentAvatars();
            $this->collStudentAvatarsPartial = true;
        }

        if (!in_array($l, $this->collStudentAvatars->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddStudentAvatar($l);

            if ($this->studentAvatarsScheduledForDeletion and $this->studentAvatarsScheduledForDeletion->contains($l)) {
                $this->studentAvatarsScheduledForDeletion->remove($this->studentAvatarsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	StudentAvatar $studentAvatar The studentAvatar object to add.
     */
    protected function doAddStudentAvatar($studentAvatar)
    {
        $this->collStudentAvatars[]= $studentAvatar;
        $studentAvatar->setUser($this);
    }

    /**
     * @param	StudentAvatar $studentAvatar The studentAvatar object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeStudentAvatar($studentAvatar)
    {
        if ($this->getStudentAvatars()->contains($studentAvatar)) {
            $this->collStudentAvatars->remove($this->collStudentAvatars->search($studentAvatar));
            if (null === $this->studentAvatarsScheduledForDeletion) {
                $this->studentAvatarsScheduledForDeletion = clone $this->collStudentAvatars;
                $this->studentAvatarsScheduledForDeletion->clear();
            }
            $this->studentAvatarsScheduledForDeletion[]= clone $studentAvatar;
            $studentAvatar->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related StudentAvatars from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|StudentAvatar[] List of StudentAvatar objects
     */
    public function getStudentAvatarsJoinAvatar($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StudentAvatarQuery::create(null, $criteria);
        $query->joinWith('Avatar', $join_behavior);

        return $this->getStudentAvatars($query, $con);
    }

    /**
     * Clears out the collTests collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addTests()
     */
    public function clearTests()
    {
        $this->collTests = null; // important to set this to null since that means it is uninitialized
        $this->collTestsPartial = null;

        return $this;
    }

    /**
     * reset is the collTests collection loaded partially
     *
     * @return void
     */
    public function resetPartialTests($v = true)
    {
        $this->collTestsPartial = $v;
    }

    /**
     * Initializes the collTests collection.
     *
     * By default this just sets the collTests collection to an empty array (like clearcollTests());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTests($overrideExisting = true)
    {
        if (null !== $this->collTests && !$overrideExisting) {
            return;
        }
        $this->collTests = new PropelObjectCollection();
        $this->collTests->setModel('Test');
    }

    /**
     * Gets an array of Test objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Test[] List of Test objects
     * @throws PropelException
     */
    public function getTests($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collTestsPartial && !$this->isNew();
        if (null === $this->collTests || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTests) {
                // return empty collection
                $this->initTests();
            } else {
                $collTests = TestQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collTestsPartial && count($collTests)) {
                      $this->initTests(false);

                      foreach ($collTests as $obj) {
                        if (false == $this->collTests->contains($obj)) {
                          $this->collTests->append($obj);
                        }
                      }

                      $this->collTestsPartial = true;
                    }

                    $collTests->getInternalIterator()->rewind();

                    return $collTests;
                }

                if ($partial && $this->collTests) {
                    foreach ($this->collTests as $obj) {
                        if ($obj->isNew()) {
                            $collTests[] = $obj;
                        }
                    }
                }

                $this->collTests = $collTests;
                $this->collTestsPartial = false;
            }
        }

        return $this->collTests;
    }

    /**
     * Sets a collection of Test objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $tests A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setTests(PropelCollection $tests, PropelPDO $con = null)
    {
        $testsToDelete = $this->getTests(new Criteria(), $con)->diff($tests);


        $this->testsScheduledForDeletion = $testsToDelete;

        foreach ($testsToDelete as $testRemoved) {
            $testRemoved->setUser(null);
        }

        $this->collTests = null;
        foreach ($tests as $test) {
            $this->addTest($test);
        }

        $this->collTests = $tests;
        $this->collTestsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Test objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Test objects.
     * @throws PropelException
     */
    public function countTests(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collTestsPartial && !$this->isNew();
        if (null === $this->collTests || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTests) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTests());
            }
            $query = TestQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collTests);
    }

    /**
     * Method called to associate a Test object to this object
     * through the Test foreign key attribute.
     *
     * @param    Test $l Test
     * @return User The current object (for fluent API support)
     */
    public function addTest(Test $l)
    {
        if ($this->collTests === null) {
            $this->initTests();
            $this->collTestsPartial = true;
        }

        if (!in_array($l, $this->collTests->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddTest($l);

            if ($this->testsScheduledForDeletion and $this->testsScheduledForDeletion->contains($l)) {
                $this->testsScheduledForDeletion->remove($this->testsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Test $test The test object to add.
     */
    protected function doAddTest($test)
    {
        $this->collTests[]= $test;
        $test->setUser($this);
    }

    /**
     * @param	Test $test The test object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeTest($test)
    {
        if ($this->getTests()->contains($test)) {
            $this->collTests->remove($this->collTests->search($test));
            if (null === $this->testsScheduledForDeletion) {
                $this->testsScheduledForDeletion = clone $this->collTests;
                $this->testsScheduledForDeletion->clear();
            }
            $this->testsScheduledForDeletion[]= clone $test;
            $test->setUser(null);
        }

        return $this;
    }

    /**
     * Gets a single UserProfile object, which is related to this object by a one-to-one relationship.
     *
     * @param PropelPDO $con optional connection object
     * @return UserProfile
     * @throws PropelException
     */
    public function getUserProfile(PropelPDO $con = null)
    {

        if ($this->singleUserProfile === null && !$this->isNew()) {
            $this->singleUserProfile = UserProfileQuery::create()->findPk($this->getPrimaryKey(), $con);
        }

        return $this->singleUserProfile;
    }

    /**
     * Sets a single UserProfile object as related to this object by a one-to-one relationship.
     *
     * @param                  UserProfile $v UserProfile
     * @return User The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUserProfile(UserProfile $v = null)
    {
        $this->singleUserProfile = $v;

        // Make sure that that the passed-in UserProfile isn't already associated with this object
        if ($v !== null && $v->getUser(null, false) === null) {
            $v->setUser($this);
        }

        return $this;
    }

    /**
     * Clears out the collGroups collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addGroups()
     */
    public function clearGroups()
    {
        $this->collGroups = null; // important to set this to null since that means it is uninitialized
        $this->collGroupsPartial = null;

        return $this;
    }

    /**
     * Initializes the collGroups collection.
     *
     * By default this just sets the collGroups collection to an empty collection (like clearGroups());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initGroups()
    {
        $this->collGroups = new PropelObjectCollection();
        $this->collGroups->setModel('Group');
    }

    /**
     * Gets a collection of Group objects related by a many-to-many relationship
     * to the current object by way of the fos_user_group cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Group[] List of Group objects
     */
    public function getGroups($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collGroups || null !== $criteria) {
            if ($this->isNew() && null === $this->collGroups) {
                // return empty collection
                $this->initGroups();
            } else {
                $collGroups = GroupQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collGroups;
                }
                $this->collGroups = $collGroups;
            }
        }

        return $this->collGroups;
    }

    /**
     * Sets a collection of Group objects related by a many-to-many relationship
     * to the current object by way of the fos_user_group cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $groups A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setGroups(PropelCollection $groups, PropelPDO $con = null)
    {
        $this->clearGroups();
        $currentGroups = $this->getGroups(null, $con);

        $this->groupsScheduledForDeletion = $currentGroups->diff($groups);

        foreach ($groups as $group) {
            if (!$currentGroups->contains($group)) {
                $this->doAddGroup($group);
            }
        }

        $this->collGroups = $groups;

        return $this;
    }

    /**
     * Gets the number of Group objects related by a many-to-many relationship
     * to the current object by way of the fos_user_group cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Group objects
     */
    public function countGroups($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collGroups || null !== $criteria) {
            if ($this->isNew() && null === $this->collGroups) {
                return 0;
            } else {
                $query = GroupQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collGroups);
        }
    }

    /**
     * Associate a Group object to this object
     * through the fos_user_group cross reference table.
     *
     * @param  Group $group The UserGroup object to relate
     * @return User The current object (for fluent API support)
     */
    public function addGroup(GroupInterface $group)
    {
        if ($this->collGroups === null) {
            $this->initGroups();
        }

        if (!$this->collGroups->contains($group)) { // only add it if the **same** object is not already associated
            $this->doAddGroup($group);
            $this->collGroups[] = $group;

            if ($this->groupsScheduledForDeletion and $this->groupsScheduledForDeletion->contains($group)) {
                $this->groupsScheduledForDeletion->remove($this->groupsScheduledForDeletion->search($group));
            }
        }

        return $this;
    }

    /**
     * @param	Group $group The group object to add.
     */
    protected function doAddGroup(Group $group)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$group->getUsers()->contains($this)) { $userGroup = new UserGroup();
            $userGroup->setGroup($group);
            $this->addUserGroup($userGroup);

            $foreignCollection = $group->getUsers();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Group object to this object
     * through the fos_user_group cross reference table.
     *
     * @param Group $group The UserGroup object to relate
     * @return User The current object (for fluent API support)
     */
    public function removeGroup(GroupInterface $group)
    {
        if ($this->getGroups()->contains($group)) {
            $this->collGroups->remove($this->collGroups->search($group));
            if (null === $this->groupsScheduledForDeletion) {
                $this->groupsScheduledForDeletion = clone $this->collGroups;
                $this->groupsScheduledForDeletion->clear();
            }
            $this->groupsScheduledForDeletion[]= $group;
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->username = null;
        $this->username_canonical = null;
        $this->email = null;
        $this->email_canonical = null;
        $this->enabled = null;
        $this->salt = null;
        $this->password = null;
        $this->last_login = null;
        $this->locked = null;
        $this->expired = null;
        $this->expires_at = null;
        $this->confirmation_token = null;
        $this->password_requested_at = null;
        $this->credentials_expired = null;
        $this->credentials_expire_at = null;
        $this->type = null;
        $this->status = null;
        $this->roles = null;
        $this->roles_unserialized = null;
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
            if ($this->collAnnouncements) {
                foreach ($this->collAnnouncements as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collApplications) {
                foreach ($this->collApplications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collBehaviors) {
                foreach ($this->collBehaviors as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCategories) {
                foreach ($this->collCategories as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserGroups) {
                foreach ($this->collUserGroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserLogs) {
                foreach ($this->collUserLogs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLicensePayments) {
                foreach ($this->collLicensePayments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserLicenses) {
                foreach ($this->collUserLicenses as $o) {
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
            if ($this->collEthnicities) {
                foreach ($this->collEthnicities as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMessagesRelatedByFromId) {
                foreach ($this->collMessagesRelatedByFromId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMessagesRelatedByToId) {
                foreach ($this->collMessagesRelatedByToId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOrganizations) {
                foreach ($this->collOrganizations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collParentStudents) {
                foreach ($this->collParentStudents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collReligions) {
                foreach ($this->collReligions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSchoolClassCoursesRelatedByPrimaryTeacherId) {
                foreach ($this->collSchoolClassCoursesRelatedByPrimaryTeacherId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSchoolClassCoursesRelatedBySecondaryTeacherId) {
                foreach ($this->collSchoolClassCoursesRelatedBySecondaryTeacherId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStudents) {
                foreach ($this->collStudents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStudentAvatars) {
                foreach ($this->collStudentAvatars as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTests) {
                foreach ($this->collTests as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->singleUserProfile) {
                $this->singleUserProfile->clearAllReferences($deep);
            }
            if ($this->collGroups) {
                foreach ($this->collGroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collAnnouncements instanceof PropelCollection) {
            $this->collAnnouncements->clearIterator();
        }
        $this->collAnnouncements = null;
        if ($this->collApplications instanceof PropelCollection) {
            $this->collApplications->clearIterator();
        }
        $this->collApplications = null;
        if ($this->collBehaviors instanceof PropelCollection) {
            $this->collBehaviors->clearIterator();
        }
        $this->collBehaviors = null;
        if ($this->collCategories instanceof PropelCollection) {
            $this->collCategories->clearIterator();
        }
        $this->collCategories = null;
        if ($this->collUserGroups instanceof PropelCollection) {
            $this->collUserGroups->clearIterator();
        }
        $this->collUserGroups = null;
        if ($this->collUserLogs instanceof PropelCollection) {
            $this->collUserLogs->clearIterator();
        }
        $this->collUserLogs = null;
        if ($this->collLicensePayments instanceof PropelCollection) {
            $this->collLicensePayments->clearIterator();
        }
        $this->collLicensePayments = null;
        if ($this->collUserLicenses instanceof PropelCollection) {
            $this->collUserLicenses->clearIterator();
        }
        $this->collUserLicenses = null;
        if ($this->collPages instanceof PropelCollection) {
            $this->collPages->clearIterator();
        }
        $this->collPages = null;
        if ($this->collEmployees instanceof PropelCollection) {
            $this->collEmployees->clearIterator();
        }
        $this->collEmployees = null;
        if ($this->collEthnicities instanceof PropelCollection) {
            $this->collEthnicities->clearIterator();
        }
        $this->collEthnicities = null;
        if ($this->collMessagesRelatedByFromId instanceof PropelCollection) {
            $this->collMessagesRelatedByFromId->clearIterator();
        }
        $this->collMessagesRelatedByFromId = null;
        if ($this->collMessagesRelatedByToId instanceof PropelCollection) {
            $this->collMessagesRelatedByToId->clearIterator();
        }
        $this->collMessagesRelatedByToId = null;
        if ($this->collOrganizations instanceof PropelCollection) {
            $this->collOrganizations->clearIterator();
        }
        $this->collOrganizations = null;
        if ($this->collParentStudents instanceof PropelCollection) {
            $this->collParentStudents->clearIterator();
        }
        $this->collParentStudents = null;
        if ($this->collReligions instanceof PropelCollection) {
            $this->collReligions->clearIterator();
        }
        $this->collReligions = null;
        if ($this->collSchoolClassCoursesRelatedByPrimaryTeacherId instanceof PropelCollection) {
            $this->collSchoolClassCoursesRelatedByPrimaryTeacherId->clearIterator();
        }
        $this->collSchoolClassCoursesRelatedByPrimaryTeacherId = null;
        if ($this->collSchoolClassCoursesRelatedBySecondaryTeacherId instanceof PropelCollection) {
            $this->collSchoolClassCoursesRelatedBySecondaryTeacherId->clearIterator();
        }
        $this->collSchoolClassCoursesRelatedBySecondaryTeacherId = null;
        if ($this->collStudents instanceof PropelCollection) {
            $this->collStudents->clearIterator();
        }
        $this->collStudents = null;
        if ($this->collStudentAvatars instanceof PropelCollection) {
            $this->collStudentAvatars->clearIterator();
        }
        $this->collStudentAvatars = null;
        if ($this->collTests instanceof PropelCollection) {
            $this->collTests->clearIterator();
        }
        $this->collTests = null;
        if ($this->singleUserProfile instanceof PropelCollection) {
            $this->singleUserProfile->clearIterator();
        }
        $this->singleUserProfile = null;
        if ($this->collGroups instanceof PropelCollection) {
            $this->collGroups->clearIterator();
        }
        $this->collGroups = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string The value of the 'username' column
     */
    public function __toString()
    {
        return (string) $this->getUsername();
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
     * @return     User The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = UserPeer::UPDATED_AT;

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

    /**
     * Catches calls to virtual methods
     */
    public function __call($name, $params)
    {

        // delegate behavior

        if (is_callable(array('PGS\CoreDomainBundle\Model\UserProfile', $name))) {
            if (!$delegate = $this->getUserProfile()) {
                $delegate = new UserProfile();
                $this->setUserProfile($delegate);
            }

            return call_user_func_array(array($delegate, $name), $params);
        }

        return parent::__call($name, $params);
    }

}
