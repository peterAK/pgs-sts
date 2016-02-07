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
use PGS\CoreDomainBundle\Model\User;
use PGS\CoreDomainBundle\Model\UserGroup;
use PGS\CoreDomainBundle\Model\UserGroupQuery;
use PGS\CoreDomainBundle\Model\UserLog;
use PGS\CoreDomainBundle\Model\UserLogQuery;
use PGS\CoreDomainBundle\Model\UserPeer;
use PGS\CoreDomainBundle\Model\UserProfile;
use PGS\CoreDomainBundle\Model\UserProfileQuery;
use PGS\CoreDomainBundle\Model\UserQuery;
use PGS\CoreDomainBundle\Model\AreaAssignment\AreaAssignment;
use PGS\CoreDomainBundle\Model\AreaAssignment\AreaAssignmentQuery;
use PGS\CoreDomainBundle\Model\Principal\Principal;
use PGS\CoreDomainBundle\Model\Principal\PrincipalQuery;
use PGS\CoreDomainBundle\Model\ProductAssignment\ProductAssignment;
use PGS\CoreDomainBundle\Model\ProductAssignment\ProductAssignmentQuery;
use PGS\CoreDomainBundle\Model\Visitation\Visitation;
use PGS\CoreDomainBundle\Model\Visitation\VisitationQuery;

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
     * @var        PropelObjectCollection|AreaAssignment[] Collection to store aggregation of AreaAssignment objects.
     */
    protected $collAreaAssignments;
    protected $collAreaAssignmentsPartial;

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
     * @var        PropelObjectCollection|Principal[] Collection to store aggregation of Principal objects.
     */
    protected $collPrincipals;
    protected $collPrincipalsPartial;

    /**
     * @var        PropelObjectCollection|ProductAssignment[] Collection to store aggregation of ProductAssignment objects.
     */
    protected $collProductAssignments;
    protected $collProductAssignmentsPartial;

    /**
     * @var        PropelObjectCollection|Visitation[] Collection to store aggregation of Visitation objects.
     */
    protected $collVisitations;
    protected $collVisitationsPartial;

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
    protected $areaAssignmentsScheduledForDeletion = null;

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
    protected $principalsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $productAssignmentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $visitationsScheduledForDeletion = null;

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

            $this->collAreaAssignments = null;

            $this->collUserGroups = null;

            $this->collUserLogs = null;

            $this->collPrincipals = null;

            $this->collProductAssignments = null;

            $this->collVisitations = null;

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

            if ($this->areaAssignmentsScheduledForDeletion !== null) {
                if (!$this->areaAssignmentsScheduledForDeletion->isEmpty()) {
                    foreach ($this->areaAssignmentsScheduledForDeletion as $areaAssignment) {
                        // need to save related object because we set the relation to null
                        $areaAssignment->save($con);
                    }
                    $this->areaAssignmentsScheduledForDeletion = null;
                }
            }

            if ($this->collAreaAssignments !== null) {
                foreach ($this->collAreaAssignments as $referrerFK) {
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

            if ($this->principalsScheduledForDeletion !== null) {
                if (!$this->principalsScheduledForDeletion->isEmpty()) {
                    foreach ($this->principalsScheduledForDeletion as $principal) {
                        // need to save related object because we set the relation to null
                        $principal->save($con);
                    }
                    $this->principalsScheduledForDeletion = null;
                }
            }

            if ($this->collPrincipals !== null) {
                foreach ($this->collPrincipals as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->productAssignmentsScheduledForDeletion !== null) {
                if (!$this->productAssignmentsScheduledForDeletion->isEmpty()) {
                    foreach ($this->productAssignmentsScheduledForDeletion as $productAssignment) {
                        // need to save related object because we set the relation to null
                        $productAssignment->save($con);
                    }
                    $this->productAssignmentsScheduledForDeletion = null;
                }
            }

            if ($this->collProductAssignments !== null) {
                foreach ($this->collProductAssignments as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->visitationsScheduledForDeletion !== null) {
                if (!$this->visitationsScheduledForDeletion->isEmpty()) {
                    foreach ($this->visitationsScheduledForDeletion as $visitation) {
                        // need to save related object because we set the relation to null
                        $visitation->save($con);
                    }
                    $this->visitationsScheduledForDeletion = null;
                }
            }

            if ($this->collVisitations !== null) {
                foreach ($this->collVisitations as $referrerFK) {
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


                if ($this->collAreaAssignments !== null) {
                    foreach ($this->collAreaAssignments as $referrerFK) {
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

                if ($this->collPrincipals !== null) {
                    foreach ($this->collPrincipals as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collProductAssignments !== null) {
                    foreach ($this->collProductAssignments as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collVisitations !== null) {
                    foreach ($this->collVisitations as $referrerFK) {
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
            if (null !== $this->collAreaAssignments) {
                $result['AreaAssignments'] = $this->collAreaAssignments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserGroups) {
                $result['UserGroups'] = $this->collUserGroups->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserLogs) {
                $result['UserLogs'] = $this->collUserLogs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPrincipals) {
                $result['Principals'] = $this->collPrincipals->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collProductAssignments) {
                $result['ProductAssignments'] = $this->collProductAssignments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collVisitations) {
                $result['Visitations'] = $this->collVisitations->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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

            foreach ($this->getAreaAssignments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAreaAssignment($relObj->copy($deepCopy));
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

            foreach ($this->getPrincipals() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPrincipal($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getProductAssignments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addProductAssignment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getVisitations() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addVisitation($relObj->copy($deepCopy));
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
        if ('AreaAssignment' == $relationName) {
            $this->initAreaAssignments();
        }
        if ('UserGroup' == $relationName) {
            $this->initUserGroups();
        }
        if ('UserLog' == $relationName) {
            $this->initUserLogs();
        }
        if ('Principal' == $relationName) {
            $this->initPrincipals();
        }
        if ('ProductAssignment' == $relationName) {
            $this->initProductAssignments();
        }
        if ('Visitation' == $relationName) {
            $this->initVisitations();
        }
    }

    /**
     * Clears out the collAreaAssignments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addAreaAssignments()
     */
    public function clearAreaAssignments()
    {
        $this->collAreaAssignments = null; // important to set this to null since that means it is uninitialized
        $this->collAreaAssignmentsPartial = null;

        return $this;
    }

    /**
     * reset is the collAreaAssignments collection loaded partially
     *
     * @return void
     */
    public function resetPartialAreaAssignments($v = true)
    {
        $this->collAreaAssignmentsPartial = $v;
    }

    /**
     * Initializes the collAreaAssignments collection.
     *
     * By default this just sets the collAreaAssignments collection to an empty array (like clearcollAreaAssignments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAreaAssignments($overrideExisting = true)
    {
        if (null !== $this->collAreaAssignments && !$overrideExisting) {
            return;
        }
        $this->collAreaAssignments = new PropelObjectCollection();
        $this->collAreaAssignments->setModel('AreaAssignment');
    }

    /**
     * Gets an array of AreaAssignment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|AreaAssignment[] List of AreaAssignment objects
     * @throws PropelException
     */
    public function getAreaAssignments($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collAreaAssignmentsPartial && !$this->isNew();
        if (null === $this->collAreaAssignments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAreaAssignments) {
                // return empty collection
                $this->initAreaAssignments();
            } else {
                $collAreaAssignments = AreaAssignmentQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collAreaAssignmentsPartial && count($collAreaAssignments)) {
                      $this->initAreaAssignments(false);

                      foreach ($collAreaAssignments as $obj) {
                        if (false == $this->collAreaAssignments->contains($obj)) {
                          $this->collAreaAssignments->append($obj);
                        }
                      }

                      $this->collAreaAssignmentsPartial = true;
                    }

                    $collAreaAssignments->getInternalIterator()->rewind();

                    return $collAreaAssignments;
                }

                if ($partial && $this->collAreaAssignments) {
                    foreach ($this->collAreaAssignments as $obj) {
                        if ($obj->isNew()) {
                            $collAreaAssignments[] = $obj;
                        }
                    }
                }

                $this->collAreaAssignments = $collAreaAssignments;
                $this->collAreaAssignmentsPartial = false;
            }
        }

        return $this->collAreaAssignments;
    }

    /**
     * Sets a collection of AreaAssignment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $areaAssignments A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setAreaAssignments(PropelCollection $areaAssignments, PropelPDO $con = null)
    {
        $areaAssignmentsToDelete = $this->getAreaAssignments(new Criteria(), $con)->diff($areaAssignments);


        $this->areaAssignmentsScheduledForDeletion = $areaAssignmentsToDelete;

        foreach ($areaAssignmentsToDelete as $areaAssignmentRemoved) {
            $areaAssignmentRemoved->setUser(null);
        }

        $this->collAreaAssignments = null;
        foreach ($areaAssignments as $areaAssignment) {
            $this->addAreaAssignment($areaAssignment);
        }

        $this->collAreaAssignments = $areaAssignments;
        $this->collAreaAssignmentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related AreaAssignment objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related AreaAssignment objects.
     * @throws PropelException
     */
    public function countAreaAssignments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collAreaAssignmentsPartial && !$this->isNew();
        if (null === $this->collAreaAssignments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAreaAssignments) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAreaAssignments());
            }
            $query = AreaAssignmentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collAreaAssignments);
    }

    /**
     * Method called to associate a AreaAssignment object to this object
     * through the AreaAssignment foreign key attribute.
     *
     * @param    AreaAssignment $l AreaAssignment
     * @return User The current object (for fluent API support)
     */
    public function addAreaAssignment(AreaAssignment $l)
    {
        if ($this->collAreaAssignments === null) {
            $this->initAreaAssignments();
            $this->collAreaAssignmentsPartial = true;
        }

        if (!in_array($l, $this->collAreaAssignments->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddAreaAssignment($l);

            if ($this->areaAssignmentsScheduledForDeletion and $this->areaAssignmentsScheduledForDeletion->contains($l)) {
                $this->areaAssignmentsScheduledForDeletion->remove($this->areaAssignmentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	AreaAssignment $areaAssignment The areaAssignment object to add.
     */
    protected function doAddAreaAssignment($areaAssignment)
    {
        $this->collAreaAssignments[]= $areaAssignment;
        $areaAssignment->setUser($this);
    }

    /**
     * @param	AreaAssignment $areaAssignment The areaAssignment object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeAreaAssignment($areaAssignment)
    {
        if ($this->getAreaAssignments()->contains($areaAssignment)) {
            $this->collAreaAssignments->remove($this->collAreaAssignments->search($areaAssignment));
            if (null === $this->areaAssignmentsScheduledForDeletion) {
                $this->areaAssignmentsScheduledForDeletion = clone $this->collAreaAssignments;
                $this->areaAssignmentsScheduledForDeletion->clear();
            }
            $this->areaAssignmentsScheduledForDeletion[]= $areaAssignment;
            $areaAssignment->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related AreaAssignments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|AreaAssignment[] List of AreaAssignment objects
     */
    public function getAreaAssignmentsJoinArea($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = AreaAssignmentQuery::create(null, $criteria);
        $query->joinWith('Area', $join_behavior);

        return $this->getAreaAssignments($query, $con);
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
     * Clears out the collPrincipals collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addPrincipals()
     */
    public function clearPrincipals()
    {
        $this->collPrincipals = null; // important to set this to null since that means it is uninitialized
        $this->collPrincipalsPartial = null;

        return $this;
    }

    /**
     * reset is the collPrincipals collection loaded partially
     *
     * @return void
     */
    public function resetPartialPrincipals($v = true)
    {
        $this->collPrincipalsPartial = $v;
    }

    /**
     * Initializes the collPrincipals collection.
     *
     * By default this just sets the collPrincipals collection to an empty array (like clearcollPrincipals());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPrincipals($overrideExisting = true)
    {
        if (null !== $this->collPrincipals && !$overrideExisting) {
            return;
        }
        $this->collPrincipals = new PropelObjectCollection();
        $this->collPrincipals->setModel('Principal');
    }

    /**
     * Gets an array of Principal objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Principal[] List of Principal objects
     * @throws PropelException
     */
    public function getPrincipals($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPrincipalsPartial && !$this->isNew();
        if (null === $this->collPrincipals || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPrincipals) {
                // return empty collection
                $this->initPrincipals();
            } else {
                $collPrincipals = PrincipalQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPrincipalsPartial && count($collPrincipals)) {
                      $this->initPrincipals(false);

                      foreach ($collPrincipals as $obj) {
                        if (false == $this->collPrincipals->contains($obj)) {
                          $this->collPrincipals->append($obj);
                        }
                      }

                      $this->collPrincipalsPartial = true;
                    }

                    $collPrincipals->getInternalIterator()->rewind();

                    return $collPrincipals;
                }

                if ($partial && $this->collPrincipals) {
                    foreach ($this->collPrincipals as $obj) {
                        if ($obj->isNew()) {
                            $collPrincipals[] = $obj;
                        }
                    }
                }

                $this->collPrincipals = $collPrincipals;
                $this->collPrincipalsPartial = false;
            }
        }

        return $this->collPrincipals;
    }

    /**
     * Sets a collection of Principal objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $principals A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setPrincipals(PropelCollection $principals, PropelPDO $con = null)
    {
        $principalsToDelete = $this->getPrincipals(new Criteria(), $con)->diff($principals);


        $this->principalsScheduledForDeletion = $principalsToDelete;

        foreach ($principalsToDelete as $principalRemoved) {
            $principalRemoved->setUser(null);
        }

        $this->collPrincipals = null;
        foreach ($principals as $principal) {
            $this->addPrincipal($principal);
        }

        $this->collPrincipals = $principals;
        $this->collPrincipalsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Principal objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Principal objects.
     * @throws PropelException
     */
    public function countPrincipals(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPrincipalsPartial && !$this->isNew();
        if (null === $this->collPrincipals || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPrincipals) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPrincipals());
            }
            $query = PrincipalQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collPrincipals);
    }

    /**
     * Method called to associate a Principal object to this object
     * through the Principal foreign key attribute.
     *
     * @param    Principal $l Principal
     * @return User The current object (for fluent API support)
     */
    public function addPrincipal(Principal $l)
    {
        if ($this->collPrincipals === null) {
            $this->initPrincipals();
            $this->collPrincipalsPartial = true;
        }

        if (!in_array($l, $this->collPrincipals->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPrincipal($l);

            if ($this->principalsScheduledForDeletion and $this->principalsScheduledForDeletion->contains($l)) {
                $this->principalsScheduledForDeletion->remove($this->principalsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Principal $principal The principal object to add.
     */
    protected function doAddPrincipal($principal)
    {
        $this->collPrincipals[]= $principal;
        $principal->setUser($this);
    }

    /**
     * @param	Principal $principal The principal object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removePrincipal($principal)
    {
        if ($this->getPrincipals()->contains($principal)) {
            $this->collPrincipals->remove($this->collPrincipals->search($principal));
            if (null === $this->principalsScheduledForDeletion) {
                $this->principalsScheduledForDeletion = clone $this->collPrincipals;
                $this->principalsScheduledForDeletion->clear();
            }
            $this->principalsScheduledForDeletion[]= $principal;
            $principal->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Principals from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Principal[] List of Principal objects
     */
    public function getPrincipalsJoinState($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PrincipalQuery::create(null, $criteria);
        $query->joinWith('State', $join_behavior);

        return $this->getPrincipals($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Principals from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Principal[] List of Principal objects
     */
    public function getPrincipalsJoinCountry($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PrincipalQuery::create(null, $criteria);
        $query->joinWith('Country', $join_behavior);

        return $this->getPrincipals($query, $con);
    }

    /**
     * Clears out the collProductAssignments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addProductAssignments()
     */
    public function clearProductAssignments()
    {
        $this->collProductAssignments = null; // important to set this to null since that means it is uninitialized
        $this->collProductAssignmentsPartial = null;

        return $this;
    }

    /**
     * reset is the collProductAssignments collection loaded partially
     *
     * @return void
     */
    public function resetPartialProductAssignments($v = true)
    {
        $this->collProductAssignmentsPartial = $v;
    }

    /**
     * Initializes the collProductAssignments collection.
     *
     * By default this just sets the collProductAssignments collection to an empty array (like clearcollProductAssignments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initProductAssignments($overrideExisting = true)
    {
        if (null !== $this->collProductAssignments && !$overrideExisting) {
            return;
        }
        $this->collProductAssignments = new PropelObjectCollection();
        $this->collProductAssignments->setModel('ProductAssignment');
    }

    /**
     * Gets an array of ProductAssignment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|ProductAssignment[] List of ProductAssignment objects
     * @throws PropelException
     */
    public function getProductAssignments($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collProductAssignmentsPartial && !$this->isNew();
        if (null === $this->collProductAssignments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collProductAssignments) {
                // return empty collection
                $this->initProductAssignments();
            } else {
                $collProductAssignments = ProductAssignmentQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collProductAssignmentsPartial && count($collProductAssignments)) {
                      $this->initProductAssignments(false);

                      foreach ($collProductAssignments as $obj) {
                        if (false == $this->collProductAssignments->contains($obj)) {
                          $this->collProductAssignments->append($obj);
                        }
                      }

                      $this->collProductAssignmentsPartial = true;
                    }

                    $collProductAssignments->getInternalIterator()->rewind();

                    return $collProductAssignments;
                }

                if ($partial && $this->collProductAssignments) {
                    foreach ($this->collProductAssignments as $obj) {
                        if ($obj->isNew()) {
                            $collProductAssignments[] = $obj;
                        }
                    }
                }

                $this->collProductAssignments = $collProductAssignments;
                $this->collProductAssignmentsPartial = false;
            }
        }

        return $this->collProductAssignments;
    }

    /**
     * Sets a collection of ProductAssignment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $productAssignments A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setProductAssignments(PropelCollection $productAssignments, PropelPDO $con = null)
    {
        $productAssignmentsToDelete = $this->getProductAssignments(new Criteria(), $con)->diff($productAssignments);


        $this->productAssignmentsScheduledForDeletion = $productAssignmentsToDelete;

        foreach ($productAssignmentsToDelete as $productAssignmentRemoved) {
            $productAssignmentRemoved->setUser(null);
        }

        $this->collProductAssignments = null;
        foreach ($productAssignments as $productAssignment) {
            $this->addProductAssignment($productAssignment);
        }

        $this->collProductAssignments = $productAssignments;
        $this->collProductAssignmentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ProductAssignment objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related ProductAssignment objects.
     * @throws PropelException
     */
    public function countProductAssignments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collProductAssignmentsPartial && !$this->isNew();
        if (null === $this->collProductAssignments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collProductAssignments) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getProductAssignments());
            }
            $query = ProductAssignmentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collProductAssignments);
    }

    /**
     * Method called to associate a ProductAssignment object to this object
     * through the ProductAssignment foreign key attribute.
     *
     * @param    ProductAssignment $l ProductAssignment
     * @return User The current object (for fluent API support)
     */
    public function addProductAssignment(ProductAssignment $l)
    {
        if ($this->collProductAssignments === null) {
            $this->initProductAssignments();
            $this->collProductAssignmentsPartial = true;
        }

        if (!in_array($l, $this->collProductAssignments->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddProductAssignment($l);

            if ($this->productAssignmentsScheduledForDeletion and $this->productAssignmentsScheduledForDeletion->contains($l)) {
                $this->productAssignmentsScheduledForDeletion->remove($this->productAssignmentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	ProductAssignment $productAssignment The productAssignment object to add.
     */
    protected function doAddProductAssignment($productAssignment)
    {
        $this->collProductAssignments[]= $productAssignment;
        $productAssignment->setUser($this);
    }

    /**
     * @param	ProductAssignment $productAssignment The productAssignment object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeProductAssignment($productAssignment)
    {
        if ($this->getProductAssignments()->contains($productAssignment)) {
            $this->collProductAssignments->remove($this->collProductAssignments->search($productAssignment));
            if (null === $this->productAssignmentsScheduledForDeletion) {
                $this->productAssignmentsScheduledForDeletion = clone $this->collProductAssignments;
                $this->productAssignmentsScheduledForDeletion->clear();
            }
            $this->productAssignmentsScheduledForDeletion[]= $productAssignment;
            $productAssignment->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related ProductAssignments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|ProductAssignment[] List of ProductAssignment objects
     */
    public function getProductAssignmentsJoinProduct($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProductAssignmentQuery::create(null, $criteria);
        $query->joinWith('Product', $join_behavior);

        return $this->getProductAssignments($query, $con);
    }

    /**
     * Clears out the collVisitations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addVisitations()
     */
    public function clearVisitations()
    {
        $this->collVisitations = null; // important to set this to null since that means it is uninitialized
        $this->collVisitationsPartial = null;

        return $this;
    }

    /**
     * reset is the collVisitations collection loaded partially
     *
     * @return void
     */
    public function resetPartialVisitations($v = true)
    {
        $this->collVisitationsPartial = $v;
    }

    /**
     * Initializes the collVisitations collection.
     *
     * By default this just sets the collVisitations collection to an empty array (like clearcollVisitations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initVisitations($overrideExisting = true)
    {
        if (null !== $this->collVisitations && !$overrideExisting) {
            return;
        }
        $this->collVisitations = new PropelObjectCollection();
        $this->collVisitations->setModel('Visitation');
    }

    /**
     * Gets an array of Visitation objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Visitation[] List of Visitation objects
     * @throws PropelException
     */
    public function getVisitations($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collVisitationsPartial && !$this->isNew();
        if (null === $this->collVisitations || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collVisitations) {
                // return empty collection
                $this->initVisitations();
            } else {
                $collVisitations = VisitationQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collVisitationsPartial && count($collVisitations)) {
                      $this->initVisitations(false);

                      foreach ($collVisitations as $obj) {
                        if (false == $this->collVisitations->contains($obj)) {
                          $this->collVisitations->append($obj);
                        }
                      }

                      $this->collVisitationsPartial = true;
                    }

                    $collVisitations->getInternalIterator()->rewind();

                    return $collVisitations;
                }

                if ($partial && $this->collVisitations) {
                    foreach ($this->collVisitations as $obj) {
                        if ($obj->isNew()) {
                            $collVisitations[] = $obj;
                        }
                    }
                }

                $this->collVisitations = $collVisitations;
                $this->collVisitationsPartial = false;
            }
        }

        return $this->collVisitations;
    }

    /**
     * Sets a collection of Visitation objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $visitations A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setVisitations(PropelCollection $visitations, PropelPDO $con = null)
    {
        $visitationsToDelete = $this->getVisitations(new Criteria(), $con)->diff($visitations);


        $this->visitationsScheduledForDeletion = $visitationsToDelete;

        foreach ($visitationsToDelete as $visitationRemoved) {
            $visitationRemoved->setUser(null);
        }

        $this->collVisitations = null;
        foreach ($visitations as $visitation) {
            $this->addVisitation($visitation);
        }

        $this->collVisitations = $visitations;
        $this->collVisitationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Visitation objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Visitation objects.
     * @throws PropelException
     */
    public function countVisitations(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collVisitationsPartial && !$this->isNew();
        if (null === $this->collVisitations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collVisitations) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getVisitations());
            }
            $query = VisitationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collVisitations);
    }

    /**
     * Method called to associate a Visitation object to this object
     * through the Visitation foreign key attribute.
     *
     * @param    Visitation $l Visitation
     * @return User The current object (for fluent API support)
     */
    public function addVisitation(Visitation $l)
    {
        if ($this->collVisitations === null) {
            $this->initVisitations();
            $this->collVisitationsPartial = true;
        }

        if (!in_array($l, $this->collVisitations->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddVisitation($l);

            if ($this->visitationsScheduledForDeletion and $this->visitationsScheduledForDeletion->contains($l)) {
                $this->visitationsScheduledForDeletion->remove($this->visitationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Visitation $visitation The visitation object to add.
     */
    protected function doAddVisitation($visitation)
    {
        $this->collVisitations[]= $visitation;
        $visitation->setUser($this);
    }

    /**
     * @param	Visitation $visitation The visitation object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeVisitation($visitation)
    {
        if ($this->getVisitations()->contains($visitation)) {
            $this->collVisitations->remove($this->collVisitations->search($visitation));
            if (null === $this->visitationsScheduledForDeletion) {
                $this->visitationsScheduledForDeletion = clone $this->collVisitations;
                $this->visitationsScheduledForDeletion->clear();
            }
            $this->visitationsScheduledForDeletion[]= $visitation;
            $visitation->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Visitations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Visitation[] List of Visitation objects
     */
    public function getVisitationsJoinStore($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = VisitationQuery::create(null, $criteria);
        $query->joinWith('Store', $join_behavior);

        return $this->getVisitations($query, $con);
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
            if ($this->collAreaAssignments) {
                foreach ($this->collAreaAssignments as $o) {
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
            if ($this->collPrincipals) {
                foreach ($this->collPrincipals as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collProductAssignments) {
                foreach ($this->collProductAssignments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collVisitations) {
                foreach ($this->collVisitations as $o) {
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

        if ($this->collAreaAssignments instanceof PropelCollection) {
            $this->collAreaAssignments->clearIterator();
        }
        $this->collAreaAssignments = null;
        if ($this->collUserGroups instanceof PropelCollection) {
            $this->collUserGroups->clearIterator();
        }
        $this->collUserGroups = null;
        if ($this->collUserLogs instanceof PropelCollection) {
            $this->collUserLogs->clearIterator();
        }
        $this->collUserLogs = null;
        if ($this->collPrincipals instanceof PropelCollection) {
            $this->collPrincipals->clearIterator();
        }
        $this->collPrincipals = null;
        if ($this->collProductAssignments instanceof PropelCollection) {
            $this->collProductAssignments->clearIterator();
        }
        $this->collProductAssignments = null;
        if ($this->collVisitations instanceof PropelCollection) {
            $this->collVisitations->clearIterator();
        }
        $this->collVisitations = null;
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
