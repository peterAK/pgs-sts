<?php

namespace PGS\CoreDomainBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelException;
use \PropelPDO;
use Glorpen\Propel\PropelBundle\Dispatcher\EventDispatcherProxy;
use Glorpen\Propel\PropelBundle\Events\ModelEvent;
use PGS\CoreDomainBundle\Model\Country;
use PGS\CoreDomainBundle\Model\CountryQuery;
use PGS\CoreDomainBundle\Model\State;
use PGS\CoreDomainBundle\Model\StateQuery;
use PGS\CoreDomainBundle\Model\User;
use PGS\CoreDomainBundle\Model\UserProfile;
use PGS\CoreDomainBundle\Model\UserProfilePeer;
use PGS\CoreDomainBundle\Model\UserProfileQuery;
use PGS\CoreDomainBundle\Model\UserQuery;
use PGS\CoreDomainBundle\Model\Principal\Principal;
use PGS\CoreDomainBundle\Model\Principal\PrincipalQuery;

abstract class BaseUserProfile extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'PGS\\CoreDomainBundle\\Model\\UserProfilePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        UserProfilePeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the prefix field.
     * @var        string
     */
    protected $prefix;

    /**
     * The value for the principal_id field.
     * @var        int
     */
    protected $principal_id;

    /**
     * The value for the nick_name field.
     * @var        string
     */
    protected $nick_name;

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
     * The value for the phone field.
     * @var        string
     */
    protected $phone;

    /**
     * The value for the mobile field.
     * @var        string
     */
    protected $mobile;

    /**
     * The value for the address field.
     * @var        string
     */
    protected $address;

    /**
     * The value for the business_address field.
     * @var        string
     */
    protected $business_address;

    /**
     * The value for the occupation field.
     * @var        string
     */
    protected $occupation;

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
     * The value for the active_preferences field.
     * @var        string
     */
    protected $active_preferences;

    /**
     * The value for the complete field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $complete;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * @var        State
     */
    protected $aState;

    /**
     * @var        Country
     */
    protected $aCountry;

    /**
     * @var        Principal
     */
    protected $aPrincipal;

    /**
     * @var        User
     */
    protected $aUser;

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
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->country_id = 105;
        $this->complete = false;
    }

    /**
     * Initializes internal state of BaseUserProfile object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
        EventDispatcherProxy::trigger(array('construct','model.construct'), new ModelEvent($this));
    }

    /**
     * Get the [prefix] column value.
     *
     * @return string
     */
    public function getPrefix()
    {

        return $this->prefix;
    }

    /**
     * Get the [principal_id] column value.
     *
     * @return int
     */
    public function getPrincipalId()
    {

        return $this->principal_id;
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
     * Get the [phone] column value.
     *
     * @return string
     */
    public function getPhone()
    {

        return $this->phone;
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
     * Get the [address] column value.
     *
     * @return string
     */
    public function getAddress()
    {

        return $this->address;
    }

    /**
     * Get the [business_address] column value.
     *
     * @return string
     */
    public function getBusinessAddress()
    {

        return $this->business_address;
    }

    /**
     * Get the [occupation] column value.
     *
     * @return string
     */
    public function getOccupation()
    {

        return $this->occupation;
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
     * Get the [active_preferences] column value.
     *
     * @return string
     */
    public function getActivePreferences()
    {

        return $this->active_preferences;
    }

    /**
     * Get the [complete] column value.
     *
     * @return boolean
     */
    public function getComplete()
    {

        return $this->complete;
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
     * Set the value of [prefix] column.
     *
     * @param  string $v new value
     * @return UserProfile The current object (for fluent API support)
     */
    public function setPrefix($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->prefix !== $v) {
            $this->prefix = $v;
            $this->modifiedColumns[] = UserProfilePeer::PREFIX;
        }


        return $this;
    } // setPrefix()

    /**
     * Set the value of [principal_id] column.
     *
     * @param  int $v new value
     * @return UserProfile The current object (for fluent API support)
     */
    public function setPrincipalId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->principal_id !== $v) {
            $this->principal_id = $v;
            $this->modifiedColumns[] = UserProfilePeer::PRINCIPAL_ID;
        }

        if ($this->aPrincipal !== null && $this->aPrincipal->getId() !== $v) {
            $this->aPrincipal = null;
        }


        return $this;
    } // setPrincipalId()

    /**
     * Set the value of [nick_name] column.
     *
     * @param  string $v new value
     * @return UserProfile The current object (for fluent API support)
     */
    public function setNickName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->nick_name !== $v) {
            $this->nick_name = $v;
            $this->modifiedColumns[] = UserProfilePeer::NICK_NAME;
        }


        return $this;
    } // setNickName()

    /**
     * Set the value of [first_name] column.
     *
     * @param  string $v new value
     * @return UserProfile The current object (for fluent API support)
     */
    public function setFirstName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->first_name !== $v) {
            $this->first_name = $v;
            $this->modifiedColumns[] = UserProfilePeer::FIRST_NAME;
        }


        return $this;
    } // setFirstName()

    /**
     * Set the value of [middle_name] column.
     *
     * @param  string $v new value
     * @return UserProfile The current object (for fluent API support)
     */
    public function setMiddleName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->middle_name !== $v) {
            $this->middle_name = $v;
            $this->modifiedColumns[] = UserProfilePeer::MIDDLE_NAME;
        }


        return $this;
    } // setMiddleName()

    /**
     * Set the value of [last_name] column.
     *
     * @param  string $v new value
     * @return UserProfile The current object (for fluent API support)
     */
    public function setLastName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->last_name !== $v) {
            $this->last_name = $v;
            $this->modifiedColumns[] = UserProfilePeer::LAST_NAME;
        }


        return $this;
    } // setLastName()

    /**
     * Set the value of [phone] column.
     *
     * @param  string $v new value
     * @return UserProfile The current object (for fluent API support)
     */
    public function setPhone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->phone !== $v) {
            $this->phone = $v;
            $this->modifiedColumns[] = UserProfilePeer::PHONE;
        }


        return $this;
    } // setPhone()

    /**
     * Set the value of [mobile] column.
     *
     * @param  string $v new value
     * @return UserProfile The current object (for fluent API support)
     */
    public function setMobile($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->mobile !== $v) {
            $this->mobile = $v;
            $this->modifiedColumns[] = UserProfilePeer::MOBILE;
        }


        return $this;
    } // setMobile()

    /**
     * Set the value of [address] column.
     *
     * @param  string $v new value
     * @return UserProfile The current object (for fluent API support)
     */
    public function setAddress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->address !== $v) {
            $this->address = $v;
            $this->modifiedColumns[] = UserProfilePeer::ADDRESS;
        }


        return $this;
    } // setAddress()

    /**
     * Set the value of [business_address] column.
     *
     * @param  string $v new value
     * @return UserProfile The current object (for fluent API support)
     */
    public function setBusinessAddress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->business_address !== $v) {
            $this->business_address = $v;
            $this->modifiedColumns[] = UserProfilePeer::BUSINESS_ADDRESS;
        }


        return $this;
    } // setBusinessAddress()

    /**
     * Set the value of [occupation] column.
     *
     * @param  string $v new value
     * @return UserProfile The current object (for fluent API support)
     */
    public function setOccupation($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->occupation !== $v) {
            $this->occupation = $v;
            $this->modifiedColumns[] = UserProfilePeer::OCCUPATION;
        }


        return $this;
    } // setOccupation()

    /**
     * Set the value of [city] column.
     *
     * @param  string $v new value
     * @return UserProfile The current object (for fluent API support)
     */
    public function setCity($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->city !== $v) {
            $this->city = $v;
            $this->modifiedColumns[] = UserProfilePeer::CITY;
        }


        return $this;
    } // setCity()

    /**
     * Set the value of [state_id] column.
     *
     * @param  int $v new value
     * @return UserProfile The current object (for fluent API support)
     */
    public function setStateId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->state_id !== $v) {
            $this->state_id = $v;
            $this->modifiedColumns[] = UserProfilePeer::STATE_ID;
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
     * @return UserProfile The current object (for fluent API support)
     */
    public function setZip($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->zip !== $v) {
            $this->zip = $v;
            $this->modifiedColumns[] = UserProfilePeer::ZIP;
        }


        return $this;
    } // setZip()

    /**
     * Set the value of [country_id] column.
     *
     * @param  int $v new value
     * @return UserProfile The current object (for fluent API support)
     */
    public function setCountryId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->country_id !== $v) {
            $this->country_id = $v;
            $this->modifiedColumns[] = UserProfilePeer::COUNTRY_ID;
        }

        if ($this->aCountry !== null && $this->aCountry->getId() !== $v) {
            $this->aCountry = null;
        }


        return $this;
    } // setCountryId()

    /**
     * Set the value of [active_preferences] column.
     *
     * @param  string $v new value
     * @return UserProfile The current object (for fluent API support)
     */
    public function setActivePreferences($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->active_preferences !== $v) {
            $this->active_preferences = $v;
            $this->modifiedColumns[] = UserProfilePeer::ACTIVE_PREFERENCES;
        }


        return $this;
    } // setActivePreferences()

    /**
     * Sets the value of the [complete] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return UserProfile The current object (for fluent API support)
     */
    public function setComplete($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->complete !== $v) {
            $this->complete = $v;
            $this->modifiedColumns[] = UserProfilePeer::COMPLETE;
        }


        return $this;
    } // setComplete()

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return UserProfile The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = UserProfilePeer::ID;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }


        return $this;
    } // setId()

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
            if ($this->country_id !== 105) {
                return false;
            }

            if ($this->complete !== false) {
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

            $this->prefix = ($row[$startcol + 0] !== null) ? (string) $row[$startcol + 0] : null;
            $this->principal_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->nick_name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->first_name = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->middle_name = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->last_name = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->phone = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->mobile = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->address = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->business_address = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->occupation = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->city = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->state_id = ($row[$startcol + 12] !== null) ? (int) $row[$startcol + 12] : null;
            $this->zip = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
            $this->country_id = ($row[$startcol + 14] !== null) ? (int) $row[$startcol + 14] : null;
            $this->active_preferences = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
            $this->complete = ($row[$startcol + 16] !== null) ? (boolean) $row[$startcol + 16] : null;
            $this->id = ($row[$startcol + 17] !== null) ? (int) $row[$startcol + 17] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 18; // 18 = UserProfilePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating UserProfile object", $e);
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

        if ($this->aPrincipal !== null && $this->principal_id !== $this->aPrincipal->getId()) {
            $this->aPrincipal = null;
        }
        if ($this->aState !== null && $this->state_id !== $this->aState->getId()) {
            $this->aState = null;
        }
        if ($this->aCountry !== null && $this->country_id !== $this->aCountry->getId()) {
            $this->aCountry = null;
        }
        if ($this->aUser !== null && $this->id !== $this->aUser->getId()) {
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
            $con = Propel::getConnection(UserProfilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = UserProfilePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aState = null;
            $this->aCountry = null;
            $this->aPrincipal = null;
            $this->aUser = null;
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
            $con = Propel::getConnection(UserProfilePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
            $deleteQuery = UserProfileQuery::create()
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
            $con = Propel::getConnection(UserProfilePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // event behavior
            EventDispatcherProxy::trigger('model.save.pre', new ModelEvent($this));
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
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
                UserProfilePeer::addInstanceToPool($this);
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

            if ($this->aPrincipal !== null) {
                if ($this->aPrincipal->isModified() || $this->aPrincipal->isNew()) {
                    $affectedRows += $this->aPrincipal->save($con);
                }
                $this->setPrincipal($this->aPrincipal);
            }

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
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


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserProfilePeer::PREFIX)) {
            $modifiedColumns[':p' . $index++]  = '`prefix`';
        }
        if ($this->isColumnModified(UserProfilePeer::PRINCIPAL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`principal_id`';
        }
        if ($this->isColumnModified(UserProfilePeer::NICK_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`nick_name`';
        }
        if ($this->isColumnModified(UserProfilePeer::FIRST_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`first_name`';
        }
        if ($this->isColumnModified(UserProfilePeer::MIDDLE_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`middle_name`';
        }
        if ($this->isColumnModified(UserProfilePeer::LAST_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`last_name`';
        }
        if ($this->isColumnModified(UserProfilePeer::PHONE)) {
            $modifiedColumns[':p' . $index++]  = '`phone`';
        }
        if ($this->isColumnModified(UserProfilePeer::MOBILE)) {
            $modifiedColumns[':p' . $index++]  = '`mobile`';
        }
        if ($this->isColumnModified(UserProfilePeer::ADDRESS)) {
            $modifiedColumns[':p' . $index++]  = '`address`';
        }
        if ($this->isColumnModified(UserProfilePeer::BUSINESS_ADDRESS)) {
            $modifiedColumns[':p' . $index++]  = '`business_address`';
        }
        if ($this->isColumnModified(UserProfilePeer::OCCUPATION)) {
            $modifiedColumns[':p' . $index++]  = '`occupation`';
        }
        if ($this->isColumnModified(UserProfilePeer::CITY)) {
            $modifiedColumns[':p' . $index++]  = '`city`';
        }
        if ($this->isColumnModified(UserProfilePeer::STATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`state_id`';
        }
        if ($this->isColumnModified(UserProfilePeer::ZIP)) {
            $modifiedColumns[':p' . $index++]  = '`zip`';
        }
        if ($this->isColumnModified(UserProfilePeer::COUNTRY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`country_id`';
        }
        if ($this->isColumnModified(UserProfilePeer::ACTIVE_PREFERENCES)) {
            $modifiedColumns[':p' . $index++]  = '`active_preferences`';
        }
        if ($this->isColumnModified(UserProfilePeer::COMPLETE)) {
            $modifiedColumns[':p' . $index++]  = '`complete`';
        }
        if ($this->isColumnModified(UserProfilePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }

        $sql = sprintf(
            'INSERT INTO `user_profile` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`prefix`':
                        $stmt->bindValue($identifier, $this->prefix, PDO::PARAM_STR);
                        break;
                    case '`principal_id`':
                        $stmt->bindValue($identifier, $this->principal_id, PDO::PARAM_INT);
                        break;
                    case '`nick_name`':
                        $stmt->bindValue($identifier, $this->nick_name, PDO::PARAM_STR);
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
                    case '`phone`':
                        $stmt->bindValue($identifier, $this->phone, PDO::PARAM_STR);
                        break;
                    case '`mobile`':
                        $stmt->bindValue($identifier, $this->mobile, PDO::PARAM_STR);
                        break;
                    case '`address`':
                        $stmt->bindValue($identifier, $this->address, PDO::PARAM_STR);
                        break;
                    case '`business_address`':
                        $stmt->bindValue($identifier, $this->business_address, PDO::PARAM_STR);
                        break;
                    case '`occupation`':
                        $stmt->bindValue($identifier, $this->occupation, PDO::PARAM_STR);
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
                    case '`active_preferences`':
                        $stmt->bindValue($identifier, $this->active_preferences, PDO::PARAM_STR);
                        break;
                    case '`complete`':
                        $stmt->bindValue($identifier, (int) $this->complete, PDO::PARAM_INT);
                        break;
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

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

            if ($this->aPrincipal !== null) {
                if (!$this->aPrincipal->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPrincipal->getValidationFailures());
                }
            }

            if ($this->aUser !== null) {
                if (!$this->aUser->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUser->getValidationFailures());
                }
            }


            if (($retval = UserProfilePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
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
        $pos = UserProfilePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getPrefix();
                break;
            case 1:
                return $this->getPrincipalId();
                break;
            case 2:
                return $this->getNickName();
                break;
            case 3:
                return $this->getFirstName();
                break;
            case 4:
                return $this->getMiddleName();
                break;
            case 5:
                return $this->getLastName();
                break;
            case 6:
                return $this->getPhone();
                break;
            case 7:
                return $this->getMobile();
                break;
            case 8:
                return $this->getAddress();
                break;
            case 9:
                return $this->getBusinessAddress();
                break;
            case 10:
                return $this->getOccupation();
                break;
            case 11:
                return $this->getCity();
                break;
            case 12:
                return $this->getStateId();
                break;
            case 13:
                return $this->getZip();
                break;
            case 14:
                return $this->getCountryId();
                break;
            case 15:
                return $this->getActivePreferences();
                break;
            case 16:
                return $this->getComplete();
                break;
            case 17:
                return $this->getId();
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
        if (isset($alreadyDumpedObjects['UserProfile'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['UserProfile'][$this->getPrimaryKey()] = true;
        $keys = UserProfilePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getPrefix(),
            $keys[1] => $this->getPrincipalId(),
            $keys[2] => $this->getNickName(),
            $keys[3] => $this->getFirstName(),
            $keys[4] => $this->getMiddleName(),
            $keys[5] => $this->getLastName(),
            $keys[6] => $this->getPhone(),
            $keys[7] => $this->getMobile(),
            $keys[8] => $this->getAddress(),
            $keys[9] => $this->getBusinessAddress(),
            $keys[10] => $this->getOccupation(),
            $keys[11] => $this->getCity(),
            $keys[12] => $this->getStateId(),
            $keys[13] => $this->getZip(),
            $keys[14] => $this->getCountryId(),
            $keys[15] => $this->getActivePreferences(),
            $keys[16] => $this->getComplete(),
            $keys[17] => $this->getId(),
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
            if (null !== $this->aPrincipal) {
                $result['Principal'] = $this->aPrincipal->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUser) {
                $result['User'] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = UserProfilePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setPrefix($value);
                break;
            case 1:
                $this->setPrincipalId($value);
                break;
            case 2:
                $this->setNickName($value);
                break;
            case 3:
                $this->setFirstName($value);
                break;
            case 4:
                $this->setMiddleName($value);
                break;
            case 5:
                $this->setLastName($value);
                break;
            case 6:
                $this->setPhone($value);
                break;
            case 7:
                $this->setMobile($value);
                break;
            case 8:
                $this->setAddress($value);
                break;
            case 9:
                $this->setBusinessAddress($value);
                break;
            case 10:
                $this->setOccupation($value);
                break;
            case 11:
                $this->setCity($value);
                break;
            case 12:
                $this->setStateId($value);
                break;
            case 13:
                $this->setZip($value);
                break;
            case 14:
                $this->setCountryId($value);
                break;
            case 15:
                $this->setActivePreferences($value);
                break;
            case 16:
                $this->setComplete($value);
                break;
            case 17:
                $this->setId($value);
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
        $keys = UserProfilePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setPrefix($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setPrincipalId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setNickName($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setFirstName($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setMiddleName($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setLastName($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setPhone($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setMobile($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setAddress($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setBusinessAddress($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setOccupation($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setCity($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setStateId($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setZip($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setCountryId($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setActivePreferences($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setComplete($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setId($arr[$keys[17]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(UserProfilePeer::DATABASE_NAME);

        if ($this->isColumnModified(UserProfilePeer::PREFIX)) $criteria->add(UserProfilePeer::PREFIX, $this->prefix);
        if ($this->isColumnModified(UserProfilePeer::PRINCIPAL_ID)) $criteria->add(UserProfilePeer::PRINCIPAL_ID, $this->principal_id);
        if ($this->isColumnModified(UserProfilePeer::NICK_NAME)) $criteria->add(UserProfilePeer::NICK_NAME, $this->nick_name);
        if ($this->isColumnModified(UserProfilePeer::FIRST_NAME)) $criteria->add(UserProfilePeer::FIRST_NAME, $this->first_name);
        if ($this->isColumnModified(UserProfilePeer::MIDDLE_NAME)) $criteria->add(UserProfilePeer::MIDDLE_NAME, $this->middle_name);
        if ($this->isColumnModified(UserProfilePeer::LAST_NAME)) $criteria->add(UserProfilePeer::LAST_NAME, $this->last_name);
        if ($this->isColumnModified(UserProfilePeer::PHONE)) $criteria->add(UserProfilePeer::PHONE, $this->phone);
        if ($this->isColumnModified(UserProfilePeer::MOBILE)) $criteria->add(UserProfilePeer::MOBILE, $this->mobile);
        if ($this->isColumnModified(UserProfilePeer::ADDRESS)) $criteria->add(UserProfilePeer::ADDRESS, $this->address);
        if ($this->isColumnModified(UserProfilePeer::BUSINESS_ADDRESS)) $criteria->add(UserProfilePeer::BUSINESS_ADDRESS, $this->business_address);
        if ($this->isColumnModified(UserProfilePeer::OCCUPATION)) $criteria->add(UserProfilePeer::OCCUPATION, $this->occupation);
        if ($this->isColumnModified(UserProfilePeer::CITY)) $criteria->add(UserProfilePeer::CITY, $this->city);
        if ($this->isColumnModified(UserProfilePeer::STATE_ID)) $criteria->add(UserProfilePeer::STATE_ID, $this->state_id);
        if ($this->isColumnModified(UserProfilePeer::ZIP)) $criteria->add(UserProfilePeer::ZIP, $this->zip);
        if ($this->isColumnModified(UserProfilePeer::COUNTRY_ID)) $criteria->add(UserProfilePeer::COUNTRY_ID, $this->country_id);
        if ($this->isColumnModified(UserProfilePeer::ACTIVE_PREFERENCES)) $criteria->add(UserProfilePeer::ACTIVE_PREFERENCES, $this->active_preferences);
        if ($this->isColumnModified(UserProfilePeer::COMPLETE)) $criteria->add(UserProfilePeer::COMPLETE, $this->complete);
        if ($this->isColumnModified(UserProfilePeer::ID)) $criteria->add(UserProfilePeer::ID, $this->id);

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
        $criteria = new Criteria(UserProfilePeer::DATABASE_NAME);
        $criteria->add(UserProfilePeer::ID, $this->id);

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
     * @param object $copyObj An object of UserProfile (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setPrefix($this->getPrefix());
        $copyObj->setPrincipalId($this->getPrincipalId());
        $copyObj->setNickName($this->getNickName());
        $copyObj->setFirstName($this->getFirstName());
        $copyObj->setMiddleName($this->getMiddleName());
        $copyObj->setLastName($this->getLastName());
        $copyObj->setPhone($this->getPhone());
        $copyObj->setMobile($this->getMobile());
        $copyObj->setAddress($this->getAddress());
        $copyObj->setBusinessAddress($this->getBusinessAddress());
        $copyObj->setOccupation($this->getOccupation());
        $copyObj->setCity($this->getCity());
        $copyObj->setStateId($this->getStateId());
        $copyObj->setZip($this->getZip());
        $copyObj->setCountryId($this->getCountryId());
        $copyObj->setActivePreferences($this->getActivePreferences());
        $copyObj->setComplete($this->getComplete());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            $relObj = $this->getUser();
            if ($relObj) {
                $copyObj->setUser($relObj->copy($deepCopy));
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
     * @return UserProfile Clone of current object.
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
     * @return UserProfilePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new UserProfilePeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a State object.
     *
     * @param                  State $v
     * @return UserProfile The current object (for fluent API support)
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
            $v->addUserProfile($this);
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
                $this->aState->addUserProfiles($this);
             */
        }

        return $this->aState;
    }

    /**
     * Declares an association between this object and a Country object.
     *
     * @param                  Country $v
     * @return UserProfile The current object (for fluent API support)
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
            $v->addUserProfile($this);
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
                $this->aCountry->addUserProfiles($this);
             */
        }

        return $this->aCountry;
    }

    /**
     * Declares an association between this object and a Principal object.
     *
     * @param                  Principal $v
     * @return UserProfile The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPrincipal(Principal $v = null)
    {
        if ($v === null) {
            $this->setPrincipalId(NULL);
        } else {
            $this->setPrincipalId($v->getId());
        }

        $this->aPrincipal = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Principal object, it will not be re-added.
        if ($v !== null) {
            $v->addUserProfile($this);
        }


        return $this;
    }


    /**
     * Get the associated Principal object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Principal The associated Principal object.
     * @throws PropelException
     */
    public function getPrincipal(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPrincipal === null && ($this->principal_id !== null) && $doQuery) {
            $this->aPrincipal = PrincipalQuery::create()->findPk($this->principal_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPrincipal->addUserProfiles($this);
             */
        }

        return $this->aPrincipal;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param                  User $v
     * @return UserProfile The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(User $v = null)
    {
        if ($v === null) {
            $this->setId(NULL);
        } else {
            $this->setId($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this 1:1 relationship.
        if ($v !== null) {
            $v->setUserProfile($this);
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
        if ($this->aUser === null && ($this->id !== null) && $doQuery) {
            $this->aUser = UserQuery::create()->findPk($this->id, $con);
            // Because this foreign key represents a one-to-one relationship, we will create a bi-directional association.
            $this->aUser->setUserProfile($this);
        }

        return $this->aUser;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->prefix = null;
        $this->principal_id = null;
        $this->nick_name = null;
        $this->first_name = null;
        $this->middle_name = null;
        $this->last_name = null;
        $this->phone = null;
        $this->mobile = null;
        $this->address = null;
        $this->business_address = null;
        $this->occupation = null;
        $this->city = null;
        $this->state_id = null;
        $this->zip = null;
        $this->country_id = null;
        $this->active_preferences = null;
        $this->complete = null;
        $this->id = null;
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
            if ($this->aState instanceof Persistent) {
              $this->aState->clearAllReferences($deep);
            }
            if ($this->aCountry instanceof Persistent) {
              $this->aCountry->clearAllReferences($deep);
            }
            if ($this->aPrincipal instanceof Persistent) {
              $this->aPrincipal->clearAllReferences($deep);
            }
            if ($this->aUser instanceof Persistent) {
              $this->aUser->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        $this->aState = null;
        $this->aCountry = null;
        $this->aPrincipal = null;
        $this->aUser = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserProfilePeer::DEFAULT_STRING_FORMAT);
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
