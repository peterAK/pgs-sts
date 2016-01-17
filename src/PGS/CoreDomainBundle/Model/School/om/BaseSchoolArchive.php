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
use \PropelDateTime;
use \PropelException;
use \PropelPDO;
use Glorpen\Propel\PropelBundle\Dispatcher\EventDispatcherProxy;
use Glorpen\Propel\PropelBundle\Events\ModelEvent;
use PGS\CoreDomainBundle\Model\School\SchoolArchive;
use PGS\CoreDomainBundle\Model\School\SchoolArchivePeer;
use PGS\CoreDomainBundle\Model\School\SchoolArchiveQuery;

abstract class BaseSchoolArchive extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'PGS\\CoreDomainBundle\\Model\\School\\SchoolArchivePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        SchoolArchivePeer
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
     * The value for the level_id field.
     * @var        int
     */
    protected $level_id;

    /**
     * The value for the nick_name field.
     * @var        string
     */
    protected $nick_name;

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
     * The value for the description field.
     * @var        string
     */
    protected $description;

    /**
     * The value for the excerpt field.
     * @var        string
     */
    protected $excerpt;

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
     * The value for the archived_at field.
     * @var        string
     */
    protected $archived_at;

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
        $this->status = 0;
        $this->confirmation = 0;
    }

    /**
     * Initializes internal state of BaseSchoolArchive object.
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
     * Get the [level_id] column value.
     *
     * @return int
     */
    public function getLevelId()
    {

        return $this->level_id;
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
     * Get the [description] column value.
     *
     * @return string
     */
    public function getDescription()
    {

        return $this->description;
    }

    /**
     * Get the [excerpt] column value.
     *
     * @return string
     */
    public function getExcerpt()
    {

        return $this->excerpt;
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
        $valueSet = SchoolArchivePeer::getValueSet(SchoolArchivePeer::STATUS);
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
        $valueSet = SchoolArchivePeer::getValueSet(SchoolArchivePeer::CONFIRMATION);
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
     * Get the [optionally formatted] temporal [archived_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getArchivedAt($format = null)
    {
        if ($this->archived_at === null) {
            return null;
        }

        if ($this->archived_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->archived_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->archived_at, true), $x);
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
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = SchoolArchivePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [code] column.
     *
     * @param  string $v new value
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[] = SchoolArchivePeer::CODE;
        }


        return $this;
    } // setCode()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = SchoolArchivePeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [level_id] column.
     *
     * @param  int $v new value
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setLevelId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->level_id !== $v) {
            $this->level_id = $v;
            $this->modifiedColumns[] = SchoolArchivePeer::LEVEL_ID;
        }


        return $this;
    } // setLevelId()

    /**
     * Set the value of [nick_name] column.
     *
     * @param  string $v new value
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setNickName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->nick_name !== $v) {
            $this->nick_name = $v;
            $this->modifiedColumns[] = SchoolArchivePeer::NICK_NAME;
        }


        return $this;
    } // setNickName()

    /**
     * Set the value of [address] column.
     *
     * @param  string $v new value
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setAddress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->address !== $v) {
            $this->address = $v;
            $this->modifiedColumns[] = SchoolArchivePeer::ADDRESS;
        }


        return $this;
    } // setAddress()

    /**
     * Set the value of [city] column.
     *
     * @param  string $v new value
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setCity($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->city !== $v) {
            $this->city = $v;
            $this->modifiedColumns[] = SchoolArchivePeer::CITY;
        }


        return $this;
    } // setCity()

    /**
     * Set the value of [state_id] column.
     *
     * @param  int $v new value
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setStateId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->state_id !== $v) {
            $this->state_id = $v;
            $this->modifiedColumns[] = SchoolArchivePeer::STATE_ID;
        }


        return $this;
    } // setStateId()

    /**
     * Set the value of [zip] column.
     *
     * @param  string $v new value
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setZip($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->zip !== $v) {
            $this->zip = $v;
            $this->modifiedColumns[] = SchoolArchivePeer::ZIP;
        }


        return $this;
    } // setZip()

    /**
     * Set the value of [country_id] column.
     *
     * @param  int $v new value
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setCountryId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->country_id !== $v) {
            $this->country_id = $v;
            $this->modifiedColumns[] = SchoolArchivePeer::COUNTRY_ID;
        }


        return $this;
    } // setCountryId()

    /**
     * Set the value of [organization_id] column.
     *
     * @param  int $v new value
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setOrganizationId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->organization_id !== $v) {
            $this->organization_id = $v;
            $this->modifiedColumns[] = SchoolArchivePeer::ORGANIZATION_ID;
        }


        return $this;
    } // setOrganizationId()

    /**
     * Set the value of [phone] column.
     *
     * @param  string $v new value
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setPhone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->phone !== $v) {
            $this->phone = $v;
            $this->modifiedColumns[] = SchoolArchivePeer::PHONE;
        }


        return $this;
    } // setPhone()

    /**
     * Set the value of [fax] column.
     *
     * @param  string $v new value
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setFax($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->fax !== $v) {
            $this->fax = $v;
            $this->modifiedColumns[] = SchoolArchivePeer::FAX;
        }


        return $this;
    } // setFax()

    /**
     * Set the value of [mobile] column.
     *
     * @param  string $v new value
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setMobile($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->mobile !== $v) {
            $this->mobile = $v;
            $this->modifiedColumns[] = SchoolArchivePeer::MOBILE;
        }


        return $this;
    } // setMobile()

    /**
     * Set the value of [email] column.
     *
     * @param  string $v new value
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[] = SchoolArchivePeer::EMAIL;
        }


        return $this;
    } // setEmail()

    /**
     * Set the value of [website] column.
     *
     * @param  string $v new value
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setWebsite($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->website !== $v) {
            $this->website = $v;
            $this->modifiedColumns[] = SchoolArchivePeer::WEBSITE;
        }


        return $this;
    } // setWebsite()

    /**
     * Set the value of [logo] column.
     *
     * @param  string $v new value
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setLogo($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->logo !== $v) {
            $this->logo = $v;
            $this->modifiedColumns[] = SchoolArchivePeer::LOGO;
        }


        return $this;
    } // setLogo()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = SchoolArchivePeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [excerpt] column.
     *
     * @param  string $v new value
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setExcerpt($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->excerpt !== $v) {
            $this->excerpt = $v;
            $this->modifiedColumns[] = SchoolArchivePeer::EXCERPT;
        }


        return $this;
    } // setExcerpt()

    /**
     * Set the value of [status] column.
     *
     * @param  int $v new value
     * @return SchoolArchive The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            $valueSet = SchoolArchivePeer::getValueSet(SchoolArchivePeer::STATUS);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[] = SchoolArchivePeer::STATUS;
        }


        return $this;
    } // setStatus()

    /**
     * Set the value of [confirmation] column.
     *
     * @param  int $v new value
     * @return SchoolArchive The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setConfirmation($v)
    {
        if ($v !== null) {
            $valueSet = SchoolArchivePeer::getValueSet(SchoolArchivePeer::CONFIRMATION);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->confirmation !== $v) {
            $this->confirmation = $v;
            $this->modifiedColumns[] = SchoolArchivePeer::CONFIRMATION;
        }


        return $this;
    } // setConfirmation()

    /**
     * Set the value of [sortable_rank] column.
     *
     * @param  int $v new value
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[] = SchoolArchivePeer::SORTABLE_RANK;
        }


        return $this;
    } // setSortableRank()

    /**
     * Sets the value of [archived_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setArchivedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->archived_at !== null || $dt !== null) {
            $currentDateAsString = ($this->archived_at !== null && $tmpDt = new DateTime($this->archived_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->archived_at = $newDateAsString;
                $this->modifiedColumns[] = SchoolArchivePeer::ARCHIVED_AT;
            }
        } // if either are not null


        return $this;
    } // setArchivedAt()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = SchoolArchivePeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return SchoolArchive The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = SchoolArchivePeer::UPDATED_AT;
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
            $this->level_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->nick_name = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
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
            $this->description = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
            $this->excerpt = ($row[$startcol + 18] !== null) ? (string) $row[$startcol + 18] : null;
            $this->status = ($row[$startcol + 19] !== null) ? (int) $row[$startcol + 19] : null;
            $this->confirmation = ($row[$startcol + 20] !== null) ? (int) $row[$startcol + 20] : null;
            $this->sortable_rank = ($row[$startcol + 21] !== null) ? (int) $row[$startcol + 21] : null;
            $this->archived_at = ($row[$startcol + 22] !== null) ? (string) $row[$startcol + 22] : null;
            $this->created_at = ($row[$startcol + 23] !== null) ? (string) $row[$startcol + 23] : null;
            $this->updated_at = ($row[$startcol + 24] !== null) ? (string) $row[$startcol + 24] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 25; // 25 = SchoolArchivePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating SchoolArchive object", $e);
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
            $con = Propel::getConnection(SchoolArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = SchoolArchivePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

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
            $con = Propel::getConnection(SchoolArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
            $deleteQuery = SchoolArchiveQuery::create()
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
            $con = Propel::getConnection(SchoolArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                if (!$this->isColumnModified(SchoolArchivePeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(SchoolArchivePeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(SchoolArchivePeer::UPDATED_AT)) {
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
                SchoolArchivePeer::addInstanceToPool($this);
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
        if ($this->isColumnModified(SchoolArchivePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::CODE)) {
            $modifiedColumns[':p' . $index++]  = '`code`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::LEVEL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`level_id`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::NICK_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`nick_name`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::ADDRESS)) {
            $modifiedColumns[':p' . $index++]  = '`address`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::CITY)) {
            $modifiedColumns[':p' . $index++]  = '`city`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::STATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`state_id`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::ZIP)) {
            $modifiedColumns[':p' . $index++]  = '`zip`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::COUNTRY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`country_id`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::ORGANIZATION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`organization_id`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::PHONE)) {
            $modifiedColumns[':p' . $index++]  = '`phone`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::FAX)) {
            $modifiedColumns[':p' . $index++]  = '`fax`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::MOBILE)) {
            $modifiedColumns[':p' . $index++]  = '`mobile`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::EMAIL)) {
            $modifiedColumns[':p' . $index++]  = '`email`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::WEBSITE)) {
            $modifiedColumns[':p' . $index++]  = '`website`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::LOGO)) {
            $modifiedColumns[':p' . $index++]  = '`logo`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::EXCERPT)) {
            $modifiedColumns[':p' . $index++]  = '`excerpt`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::STATUS)) {
            $modifiedColumns[':p' . $index++]  = '`status`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::CONFIRMATION)) {
            $modifiedColumns[':p' . $index++]  = '`confirmation`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = '`sortable_rank`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::ARCHIVED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`archived_at`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(SchoolArchivePeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `school_archive` (%s) VALUES (%s)',
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
                    case '`level_id`':
                        $stmt->bindValue($identifier, $this->level_id, PDO::PARAM_INT);
                        break;
                    case '`nick_name`':
                        $stmt->bindValue($identifier, $this->nick_name, PDO::PARAM_STR);
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
                    case '`description`':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case '`excerpt`':
                        $stmt->bindValue($identifier, $this->excerpt, PDO::PARAM_STR);
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
                    case '`archived_at`':
                        $stmt->bindValue($identifier, $this->archived_at, PDO::PARAM_STR);
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


            if (($retval = SchoolArchivePeer::doValidate($this, $columns)) !== true) {
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
        $pos = SchoolArchivePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getLevelId();
                break;
            case 4:
                return $this->getNickName();
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
                return $this->getDescription();
                break;
            case 18:
                return $this->getExcerpt();
                break;
            case 19:
                return $this->getStatus();
                break;
            case 20:
                return $this->getConfirmation();
                break;
            case 21:
                return $this->getSortableRank();
                break;
            case 22:
                return $this->getArchivedAt();
                break;
            case 23:
                return $this->getCreatedAt();
                break;
            case 24:
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
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array())
    {
        if (isset($alreadyDumpedObjects['SchoolArchive'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['SchoolArchive'][$this->getPrimaryKey()] = true;
        $keys = SchoolArchivePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getCode(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getLevelId(),
            $keys[4] => $this->getNickName(),
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
            $keys[17] => $this->getDescription(),
            $keys[18] => $this->getExcerpt(),
            $keys[19] => $this->getStatus(),
            $keys[20] => $this->getConfirmation(),
            $keys[21] => $this->getSortableRank(),
            $keys[22] => $this->getArchivedAt(),
            $keys[23] => $this->getCreatedAt(),
            $keys[24] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
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
        $pos = SchoolArchivePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setLevelId($value);
                break;
            case 4:
                $this->setNickName($value);
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
                $this->setDescription($value);
                break;
            case 18:
                $this->setExcerpt($value);
                break;
            case 19:
                $valueSet = SchoolArchivePeer::getValueSet(SchoolArchivePeer::STATUS);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setStatus($value);
                break;
            case 20:
                $valueSet = SchoolArchivePeer::getValueSet(SchoolArchivePeer::CONFIRMATION);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setConfirmation($value);
                break;
            case 21:
                $this->setSortableRank($value);
                break;
            case 22:
                $this->setArchivedAt($value);
                break;
            case 23:
                $this->setCreatedAt($value);
                break;
            case 24:
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
        $keys = SchoolArchivePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setCode($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setName($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setLevelId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setNickName($arr[$keys[4]]);
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
        if (array_key_exists($keys[17], $arr)) $this->setDescription($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setExcerpt($arr[$keys[18]]);
        if (array_key_exists($keys[19], $arr)) $this->setStatus($arr[$keys[19]]);
        if (array_key_exists($keys[20], $arr)) $this->setConfirmation($arr[$keys[20]]);
        if (array_key_exists($keys[21], $arr)) $this->setSortableRank($arr[$keys[21]]);
        if (array_key_exists($keys[22], $arr)) $this->setArchivedAt($arr[$keys[22]]);
        if (array_key_exists($keys[23], $arr)) $this->setCreatedAt($arr[$keys[23]]);
        if (array_key_exists($keys[24], $arr)) $this->setUpdatedAt($arr[$keys[24]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(SchoolArchivePeer::DATABASE_NAME);

        if ($this->isColumnModified(SchoolArchivePeer::ID)) $criteria->add(SchoolArchivePeer::ID, $this->id);
        if ($this->isColumnModified(SchoolArchivePeer::CODE)) $criteria->add(SchoolArchivePeer::CODE, $this->code);
        if ($this->isColumnModified(SchoolArchivePeer::NAME)) $criteria->add(SchoolArchivePeer::NAME, $this->name);
        if ($this->isColumnModified(SchoolArchivePeer::LEVEL_ID)) $criteria->add(SchoolArchivePeer::LEVEL_ID, $this->level_id);
        if ($this->isColumnModified(SchoolArchivePeer::NICK_NAME)) $criteria->add(SchoolArchivePeer::NICK_NAME, $this->nick_name);
        if ($this->isColumnModified(SchoolArchivePeer::ADDRESS)) $criteria->add(SchoolArchivePeer::ADDRESS, $this->address);
        if ($this->isColumnModified(SchoolArchivePeer::CITY)) $criteria->add(SchoolArchivePeer::CITY, $this->city);
        if ($this->isColumnModified(SchoolArchivePeer::STATE_ID)) $criteria->add(SchoolArchivePeer::STATE_ID, $this->state_id);
        if ($this->isColumnModified(SchoolArchivePeer::ZIP)) $criteria->add(SchoolArchivePeer::ZIP, $this->zip);
        if ($this->isColumnModified(SchoolArchivePeer::COUNTRY_ID)) $criteria->add(SchoolArchivePeer::COUNTRY_ID, $this->country_id);
        if ($this->isColumnModified(SchoolArchivePeer::ORGANIZATION_ID)) $criteria->add(SchoolArchivePeer::ORGANIZATION_ID, $this->organization_id);
        if ($this->isColumnModified(SchoolArchivePeer::PHONE)) $criteria->add(SchoolArchivePeer::PHONE, $this->phone);
        if ($this->isColumnModified(SchoolArchivePeer::FAX)) $criteria->add(SchoolArchivePeer::FAX, $this->fax);
        if ($this->isColumnModified(SchoolArchivePeer::MOBILE)) $criteria->add(SchoolArchivePeer::MOBILE, $this->mobile);
        if ($this->isColumnModified(SchoolArchivePeer::EMAIL)) $criteria->add(SchoolArchivePeer::EMAIL, $this->email);
        if ($this->isColumnModified(SchoolArchivePeer::WEBSITE)) $criteria->add(SchoolArchivePeer::WEBSITE, $this->website);
        if ($this->isColumnModified(SchoolArchivePeer::LOGO)) $criteria->add(SchoolArchivePeer::LOGO, $this->logo);
        if ($this->isColumnModified(SchoolArchivePeer::DESCRIPTION)) $criteria->add(SchoolArchivePeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(SchoolArchivePeer::EXCERPT)) $criteria->add(SchoolArchivePeer::EXCERPT, $this->excerpt);
        if ($this->isColumnModified(SchoolArchivePeer::STATUS)) $criteria->add(SchoolArchivePeer::STATUS, $this->status);
        if ($this->isColumnModified(SchoolArchivePeer::CONFIRMATION)) $criteria->add(SchoolArchivePeer::CONFIRMATION, $this->confirmation);
        if ($this->isColumnModified(SchoolArchivePeer::SORTABLE_RANK)) $criteria->add(SchoolArchivePeer::SORTABLE_RANK, $this->sortable_rank);
        if ($this->isColumnModified(SchoolArchivePeer::ARCHIVED_AT)) $criteria->add(SchoolArchivePeer::ARCHIVED_AT, $this->archived_at);
        if ($this->isColumnModified(SchoolArchivePeer::CREATED_AT)) $criteria->add(SchoolArchivePeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(SchoolArchivePeer::UPDATED_AT)) $criteria->add(SchoolArchivePeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(SchoolArchivePeer::DATABASE_NAME);
        $criteria->add(SchoolArchivePeer::ID, $this->id);

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
     * @param object $copyObj An object of SchoolArchive (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setCode($this->getCode());
        $copyObj->setName($this->getName());
        $copyObj->setLevelId($this->getLevelId());
        $copyObj->setNickName($this->getNickName());
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
        $copyObj->setDescription($this->getDescription());
        $copyObj->setExcerpt($this->getExcerpt());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setConfirmation($this->getConfirmation());
        $copyObj->setSortableRank($this->getSortableRank());
        $copyObj->setArchivedAt($this->getArchivedAt());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
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
     * @return SchoolArchive Clone of current object.
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
     * @return SchoolArchivePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new SchoolArchivePeer();
        }

        return self::$peer;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->code = null;
        $this->name = null;
        $this->level_id = null;
        $this->nick_name = null;
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
        $this->description = null;
        $this->excerpt = null;
        $this->status = null;
        $this->confirmation = null;
        $this->sortable_rank = null;
        $this->archived_at = null;
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

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(SchoolArchivePeer::DEFAULT_STRING_FORMAT);
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
     * @return     SchoolArchive The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = SchoolArchivePeer::UPDATED_AT;

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
