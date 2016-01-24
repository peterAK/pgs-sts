<?php

namespace PGS\CoreDomainBundle\Model\Organization\om;

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
use PGS\CoreDomainBundle\Model\Region;
use PGS\CoreDomainBundle\Model\RegionQuery;
use PGS\CoreDomainBundle\Model\State;
use PGS\CoreDomainBundle\Model\StateQuery;
use PGS\CoreDomainBundle\Model\User;
use PGS\CoreDomainBundle\Model\UserQuery;
use PGS\CoreDomainBundle\Model\Organization\Organization;
use PGS\CoreDomainBundle\Model\Organization\OrganizationArchive;
use PGS\CoreDomainBundle\Model\Organization\OrganizationArchiveQuery;
use PGS\CoreDomainBundle\Model\Organization\OrganizationI18n;
use PGS\CoreDomainBundle\Model\Organization\OrganizationI18nQuery;
use PGS\CoreDomainBundle\Model\Organization\OrganizationPeer;
use PGS\CoreDomainBundle\Model\Organization\OrganizationQuery;

abstract class BaseOrganization extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'PGS\\CoreDomainBundle\\Model\\Organization\\OrganizationPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        OrganizationPeer
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
     * The value for the goverment_license field.
     * @var        string
     */
    protected $goverment_license;

    /**
     * The value for the join_at field.
     * @var        string
     */
    protected $join_at;

    /**
     * The value for the address1 field.
     * @var        string
     */
    protected $address1;

    /**
     * The value for the address2 field.
     * @var        string
     */
    protected $address2;

    /**
     * The value for the city field.
     * @var        string
     */
    protected $city;

    /**
     * The value for the zipcode field.
     * @var        string
     */
    protected $zipcode;

    /**
     * The value for the country_id field.
     * @var        int
     */
    protected $country_id;

    /**
     * The value for the state_id field.
     * @var        int
     */
    protected $state_id;

    /**
     * The value for the region_id field.
     * @var        int
     */
    protected $region_id;

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
     * The value for the is_principal field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_principal;

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
     * @var        User
     */
    protected $aUser;

    /**
     * @var        State
     */
    protected $aState;

    /**
     * @var        Country
     */
    protected $aCountry;

    /**
     * @var        Region
     */
    protected $aRegion;

    /**
     * @var        PropelObjectCollection|OrganizationI18n[] Collection to store aggregation of OrganizationI18n objects.
     */
    protected $collOrganizationI18ns;
    protected $collOrganizationI18nsPartial;

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
     * @var        array[OrganizationI18n]
     */
    protected $currentTranslations;

    // sortable behavior

    /**
     * Queries to be executed in the save transaction
     * @var        array
     */
    protected $sortableQueries = array();

    // archivable behavior
    protected $archiveOnDelete = true;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $organizationI18nsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->status = 0;
        $this->is_principal = false;
        $this->confirmation = 0;
    }

    /**
     * Initializes internal state of BaseOrganization object.
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
     * Get the [goverment_license] column value.
     *
     * @return string
     */
    public function getGovermentLicense()
    {

        return $this->goverment_license;
    }

    /**
     * Get the [optionally formatted] temporal [join_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getJoinAt($format = null)
    {
        if ($this->join_at === null) {
            return null;
        }

        if ($this->join_at === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->join_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->join_at, true), $x);
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
     * Get the [address1] column value.
     *
     * @return string
     */
    public function getAddress1()
    {

        return $this->address1;
    }

    /**
     * Get the [address2] column value.
     *
     * @return string
     */
    public function getAddress2()
    {

        return $this->address2;
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
     * Get the [zipcode] column value.
     *
     * @return string
     */
    public function getZipcode()
    {

        return $this->zipcode;
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
     * Get the [state_id] column value.
     *
     * @return int
     */
    public function getStateId()
    {

        return $this->state_id;
    }

    /**
     * Get the [region_id] column value.
     *
     * @return int
     */
    public function getRegionId()
    {

        return $this->region_id;
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
        $valueSet = OrganizationPeer::getValueSet(OrganizationPeer::STATUS);
        if (!isset($valueSet[$this->status])) {
            throw new PropelException('Unknown stored enum key: ' . $this->status);
        }

        return $valueSet[$this->status];
    }

    /**
     * Get the [is_principal] column value.
     *
     * @return boolean
     */
    public function getIsPrincipal()
    {

        return $this->is_principal;
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
        $valueSet = OrganizationPeer::getValueSet(OrganizationPeer::CONFIRMATION);
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
     * @return Organization The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = OrganizationPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [user_id] column.
     *
     * @param  int $v new value
     * @return Organization The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[] = OrganizationPeer::USER_ID;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }


        return $this;
    } // setUserId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return Organization The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = OrganizationPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [url] column.
     *
     * @param  string $v new value
     * @return Organization The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->url !== $v) {
            $this->url = $v;
            $this->modifiedColumns[] = OrganizationPeer::URL;
        }


        return $this;
    } // setUrl()

    /**
     * Set the value of [goverment_license] column.
     *
     * @param  string $v new value
     * @return Organization The current object (for fluent API support)
     */
    public function setGovermentLicense($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->goverment_license !== $v) {
            $this->goverment_license = $v;
            $this->modifiedColumns[] = OrganizationPeer::GOVERMENT_LICENSE;
        }


        return $this;
    } // setGovermentLicense()

    /**
     * Sets the value of [join_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Organization The current object (for fluent API support)
     */
    public function setJoinAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->join_at !== null || $dt !== null) {
            $currentDateAsString = ($this->join_at !== null && $tmpDt = new DateTime($this->join_at)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->join_at = $newDateAsString;
                $this->modifiedColumns[] = OrganizationPeer::JOIN_AT;
            }
        } // if either are not null


        return $this;
    } // setJoinAt()

    /**
     * Set the value of [address1] column.
     *
     * @param  string $v new value
     * @return Organization The current object (for fluent API support)
     */
    public function setAddress1($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->address1 !== $v) {
            $this->address1 = $v;
            $this->modifiedColumns[] = OrganizationPeer::ADDRESS1;
        }


        return $this;
    } // setAddress1()

    /**
     * Set the value of [address2] column.
     *
     * @param  string $v new value
     * @return Organization The current object (for fluent API support)
     */
    public function setAddress2($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->address2 !== $v) {
            $this->address2 = $v;
            $this->modifiedColumns[] = OrganizationPeer::ADDRESS2;
        }


        return $this;
    } // setAddress2()

    /**
     * Set the value of [city] column.
     *
     * @param  string $v new value
     * @return Organization The current object (for fluent API support)
     */
    public function setCity($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->city !== $v) {
            $this->city = $v;
            $this->modifiedColumns[] = OrganizationPeer::CITY;
        }


        return $this;
    } // setCity()

    /**
     * Set the value of [zipcode] column.
     *
     * @param  string $v new value
     * @return Organization The current object (for fluent API support)
     */
    public function setZipcode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->zipcode !== $v) {
            $this->zipcode = $v;
            $this->modifiedColumns[] = OrganizationPeer::ZIPCODE;
        }


        return $this;
    } // setZipcode()

    /**
     * Set the value of [country_id] column.
     *
     * @param  int $v new value
     * @return Organization The current object (for fluent API support)
     */
    public function setCountryId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->country_id !== $v) {
            $this->country_id = $v;
            $this->modifiedColumns[] = OrganizationPeer::COUNTRY_ID;
        }

        if ($this->aCountry !== null && $this->aCountry->getId() !== $v) {
            $this->aCountry = null;
        }


        return $this;
    } // setCountryId()

    /**
     * Set the value of [state_id] column.
     *
     * @param  int $v new value
     * @return Organization The current object (for fluent API support)
     */
    public function setStateId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->state_id !== $v) {
            $this->state_id = $v;
            $this->modifiedColumns[] = OrganizationPeer::STATE_ID;
        }

        if ($this->aState !== null && $this->aState->getId() !== $v) {
            $this->aState = null;
        }


        return $this;
    } // setStateId()

    /**
     * Set the value of [region_id] column.
     *
     * @param  int $v new value
     * @return Organization The current object (for fluent API support)
     */
    public function setRegionId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->region_id !== $v) {
            $this->region_id = $v;
            $this->modifiedColumns[] = OrganizationPeer::REGION_ID;
        }

        if ($this->aRegion !== null && $this->aRegion->getId() !== $v) {
            $this->aRegion = null;
        }


        return $this;
    } // setRegionId()

    /**
     * Set the value of [phone] column.
     *
     * @param  string $v new value
     * @return Organization The current object (for fluent API support)
     */
    public function setPhone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->phone !== $v) {
            $this->phone = $v;
            $this->modifiedColumns[] = OrganizationPeer::PHONE;
        }


        return $this;
    } // setPhone()

    /**
     * Set the value of [fax] column.
     *
     * @param  string $v new value
     * @return Organization The current object (for fluent API support)
     */
    public function setFax($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->fax !== $v) {
            $this->fax = $v;
            $this->modifiedColumns[] = OrganizationPeer::FAX;
        }


        return $this;
    } // setFax()

    /**
     * Set the value of [mobile] column.
     *
     * @param  string $v new value
     * @return Organization The current object (for fluent API support)
     */
    public function setMobile($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->mobile !== $v) {
            $this->mobile = $v;
            $this->modifiedColumns[] = OrganizationPeer::MOBILE;
        }


        return $this;
    } // setMobile()

    /**
     * Set the value of [email] column.
     *
     * @param  string $v new value
     * @return Organization The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[] = OrganizationPeer::EMAIL;
        }


        return $this;
    } // setEmail()

    /**
     * Set the value of [website] column.
     *
     * @param  string $v new value
     * @return Organization The current object (for fluent API support)
     */
    public function setWebsite($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->website !== $v) {
            $this->website = $v;
            $this->modifiedColumns[] = OrganizationPeer::WEBSITE;
        }


        return $this;
    } // setWebsite()

    /**
     * Set the value of [logo] column.
     *
     * @param  string $v new value
     * @return Organization The current object (for fluent API support)
     */
    public function setLogo($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->logo !== $v) {
            $this->logo = $v;
            $this->modifiedColumns[] = OrganizationPeer::LOGO;
        }


        return $this;
    } // setLogo()

    /**
     * Set the value of [status] column.
     *
     * @param  int $v new value
     * @return Organization The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            $valueSet = OrganizationPeer::getValueSet(OrganizationPeer::STATUS);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[] = OrganizationPeer::STATUS;
        }


        return $this;
    } // setStatus()

    /**
     * Sets the value of the [is_principal] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Organization The current object (for fluent API support)
     */
    public function setIsPrincipal($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_principal !== $v) {
            $this->is_principal = $v;
            $this->modifiedColumns[] = OrganizationPeer::IS_PRINCIPAL;
        }


        return $this;
    } // setIsPrincipal()

    /**
     * Set the value of [confirmation] column.
     *
     * @param  int $v new value
     * @return Organization The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setConfirmation($v)
    {
        if ($v !== null) {
            $valueSet = OrganizationPeer::getValueSet(OrganizationPeer::CONFIRMATION);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->confirmation !== $v) {
            $this->confirmation = $v;
            $this->modifiedColumns[] = OrganizationPeer::CONFIRMATION;
        }


        return $this;
    } // setConfirmation()

    /**
     * Set the value of [sortable_rank] column.
     *
     * @param  int $v new value
     * @return Organization The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[] = OrganizationPeer::SORTABLE_RANK;
        }


        return $this;
    } // setSortableRank()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Organization The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = OrganizationPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Organization The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = OrganizationPeer::UPDATED_AT;
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

            if ($this->is_principal !== false) {
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
            $this->user_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->url = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->goverment_license = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->join_at = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->address1 = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->address2 = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->city = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->zipcode = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->country_id = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
            $this->state_id = ($row[$startcol + 11] !== null) ? (int) $row[$startcol + 11] : null;
            $this->region_id = ($row[$startcol + 12] !== null) ? (int) $row[$startcol + 12] : null;
            $this->phone = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
            $this->fax = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
            $this->mobile = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
            $this->email = ($row[$startcol + 16] !== null) ? (string) $row[$startcol + 16] : null;
            $this->website = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
            $this->logo = ($row[$startcol + 18] !== null) ? (string) $row[$startcol + 18] : null;
            $this->status = ($row[$startcol + 19] !== null) ? (int) $row[$startcol + 19] : null;
            $this->is_principal = ($row[$startcol + 20] !== null) ? (boolean) $row[$startcol + 20] : null;
            $this->confirmation = ($row[$startcol + 21] !== null) ? (int) $row[$startcol + 21] : null;
            $this->sortable_rank = ($row[$startcol + 22] !== null) ? (int) $row[$startcol + 22] : null;
            $this->created_at = ($row[$startcol + 23] !== null) ? (string) $row[$startcol + 23] : null;
            $this->updated_at = ($row[$startcol + 24] !== null) ? (string) $row[$startcol + 24] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 25; // 25 = OrganizationPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Organization object", $e);
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
        if ($this->aCountry !== null && $this->country_id !== $this->aCountry->getId()) {
            $this->aCountry = null;
        }
        if ($this->aState !== null && $this->state_id !== $this->aState->getId()) {
            $this->aState = null;
        }
        if ($this->aRegion !== null && $this->region_id !== $this->aRegion->getId()) {
            $this->aRegion = null;
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
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = OrganizationPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->aState = null;
            $this->aCountry = null;
            $this->aRegion = null;
            $this->collOrganizationI18ns = null;

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
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
            $deleteQuery = OrganizationQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // sortable behavior

            OrganizationPeer::shiftRank(-1, $this->getSortableRank() + 1, null, $con);
            OrganizationPeer::clearInstancePool();

            // archivable behavior
            if ($ret) {
                if ($this->archiveOnDelete) {
                    // do nothing yet. The object will be archived later when calling OrganizationQuery::delete().
                } else {
                    $deleteQuery->setArchiveOnDelete(false);
                    $this->archiveOnDelete = true;
                }
            }

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
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // sluggable behavior

            if ($this->isColumnModified(OrganizationPeer::URL) && $this->getUrl()) {
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
                if (!$this->isColumnModified(OrganizationPeer::RANK_COL)) {
                    $this->setSortableRank(OrganizationQuery::create()->getMaxRankArray($con) + 1);
                }

                // timestampable behavior
                if (!$this->isColumnModified(OrganizationPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(OrganizationPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(OrganizationPeer::UPDATED_AT)) {
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
                OrganizationPeer::addInstanceToPool($this);
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

            if ($this->aRegion !== null) {
                if ($this->aRegion->isModified() || $this->aRegion->isNew()) {
                    $affectedRows += $this->aRegion->save($con);
                }
                $this->setRegion($this->aRegion);
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

            if ($this->organizationI18nsScheduledForDeletion !== null) {
                if (!$this->organizationI18nsScheduledForDeletion->isEmpty()) {
                    OrganizationI18nQuery::create()
                        ->filterByPrimaryKeys($this->organizationI18nsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->organizationI18nsScheduledForDeletion = null;
                }
            }

            if ($this->collOrganizationI18ns !== null) {
                foreach ($this->collOrganizationI18ns as $referrerFK) {
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

        $this->modifiedColumns[] = OrganizationPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . OrganizationPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(OrganizationPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(OrganizationPeer::USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`user_id`';
        }
        if ($this->isColumnModified(OrganizationPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(OrganizationPeer::URL)) {
            $modifiedColumns[':p' . $index++]  = '`url`';
        }
        if ($this->isColumnModified(OrganizationPeer::GOVERMENT_LICENSE)) {
            $modifiedColumns[':p' . $index++]  = '`goverment_license`';
        }
        if ($this->isColumnModified(OrganizationPeer::JOIN_AT)) {
            $modifiedColumns[':p' . $index++]  = '`join_at`';
        }
        if ($this->isColumnModified(OrganizationPeer::ADDRESS1)) {
            $modifiedColumns[':p' . $index++]  = '`address1`';
        }
        if ($this->isColumnModified(OrganizationPeer::ADDRESS2)) {
            $modifiedColumns[':p' . $index++]  = '`address2`';
        }
        if ($this->isColumnModified(OrganizationPeer::CITY)) {
            $modifiedColumns[':p' . $index++]  = '`city`';
        }
        if ($this->isColumnModified(OrganizationPeer::ZIPCODE)) {
            $modifiedColumns[':p' . $index++]  = '`zipcode`';
        }
        if ($this->isColumnModified(OrganizationPeer::COUNTRY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`country_id`';
        }
        if ($this->isColumnModified(OrganizationPeer::STATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`state_id`';
        }
        if ($this->isColumnModified(OrganizationPeer::REGION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`region_id`';
        }
        if ($this->isColumnModified(OrganizationPeer::PHONE)) {
            $modifiedColumns[':p' . $index++]  = '`phone`';
        }
        if ($this->isColumnModified(OrganizationPeer::FAX)) {
            $modifiedColumns[':p' . $index++]  = '`fax`';
        }
        if ($this->isColumnModified(OrganizationPeer::MOBILE)) {
            $modifiedColumns[':p' . $index++]  = '`mobile`';
        }
        if ($this->isColumnModified(OrganizationPeer::EMAIL)) {
            $modifiedColumns[':p' . $index++]  = '`email`';
        }
        if ($this->isColumnModified(OrganizationPeer::WEBSITE)) {
            $modifiedColumns[':p' . $index++]  = '`website`';
        }
        if ($this->isColumnModified(OrganizationPeer::LOGO)) {
            $modifiedColumns[':p' . $index++]  = '`logo`';
        }
        if ($this->isColumnModified(OrganizationPeer::STATUS)) {
            $modifiedColumns[':p' . $index++]  = '`status`';
        }
        if ($this->isColumnModified(OrganizationPeer::IS_PRINCIPAL)) {
            $modifiedColumns[':p' . $index++]  = '`is_principal`';
        }
        if ($this->isColumnModified(OrganizationPeer::CONFIRMATION)) {
            $modifiedColumns[':p' . $index++]  = '`confirmation`';
        }
        if ($this->isColumnModified(OrganizationPeer::SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = '`sortable_rank`';
        }
        if ($this->isColumnModified(OrganizationPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(OrganizationPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `organization` (%s) VALUES (%s)',
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
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`url`':
                        $stmt->bindValue($identifier, $this->url, PDO::PARAM_STR);
                        break;
                    case '`goverment_license`':
                        $stmt->bindValue($identifier, $this->goverment_license, PDO::PARAM_STR);
                        break;
                    case '`join_at`':
                        $stmt->bindValue($identifier, $this->join_at, PDO::PARAM_STR);
                        break;
                    case '`address1`':
                        $stmt->bindValue($identifier, $this->address1, PDO::PARAM_STR);
                        break;
                    case '`address2`':
                        $stmt->bindValue($identifier, $this->address2, PDO::PARAM_STR);
                        break;
                    case '`city`':
                        $stmt->bindValue($identifier, $this->city, PDO::PARAM_STR);
                        break;
                    case '`zipcode`':
                        $stmt->bindValue($identifier, $this->zipcode, PDO::PARAM_STR);
                        break;
                    case '`country_id`':
                        $stmt->bindValue($identifier, $this->country_id, PDO::PARAM_INT);
                        break;
                    case '`state_id`':
                        $stmt->bindValue($identifier, $this->state_id, PDO::PARAM_INT);
                        break;
                    case '`region_id`':
                        $stmt->bindValue($identifier, $this->region_id, PDO::PARAM_INT);
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
                    case '`is_principal`':
                        $stmt->bindValue($identifier, (int) $this->is_principal, PDO::PARAM_INT);
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

            if ($this->aUser !== null) {
                if (!$this->aUser->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUser->getValidationFailures());
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

            if ($this->aRegion !== null) {
                if (!$this->aRegion->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aRegion->getValidationFailures());
                }
            }


            if (($retval = OrganizationPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collOrganizationI18ns !== null) {
                    foreach ($this->collOrganizationI18ns as $referrerFK) {
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
        $pos = OrganizationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getName();
                break;
            case 3:
                return $this->getUrl();
                break;
            case 4:
                return $this->getGovermentLicense();
                break;
            case 5:
                return $this->getJoinAt();
                break;
            case 6:
                return $this->getAddress1();
                break;
            case 7:
                return $this->getAddress2();
                break;
            case 8:
                return $this->getCity();
                break;
            case 9:
                return $this->getZipcode();
                break;
            case 10:
                return $this->getCountryId();
                break;
            case 11:
                return $this->getStateId();
                break;
            case 12:
                return $this->getRegionId();
                break;
            case 13:
                return $this->getPhone();
                break;
            case 14:
                return $this->getFax();
                break;
            case 15:
                return $this->getMobile();
                break;
            case 16:
                return $this->getEmail();
                break;
            case 17:
                return $this->getWebsite();
                break;
            case 18:
                return $this->getLogo();
                break;
            case 19:
                return $this->getStatus();
                break;
            case 20:
                return $this->getIsPrincipal();
                break;
            case 21:
                return $this->getConfirmation();
                break;
            case 22:
                return $this->getSortableRank();
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
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['Organization'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Organization'][$this->getPrimaryKey()] = true;
        $keys = OrganizationPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUserId(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getUrl(),
            $keys[4] => $this->getGovermentLicense(),
            $keys[5] => $this->getJoinAt(),
            $keys[6] => $this->getAddress1(),
            $keys[7] => $this->getAddress2(),
            $keys[8] => $this->getCity(),
            $keys[9] => $this->getZipcode(),
            $keys[10] => $this->getCountryId(),
            $keys[11] => $this->getStateId(),
            $keys[12] => $this->getRegionId(),
            $keys[13] => $this->getPhone(),
            $keys[14] => $this->getFax(),
            $keys[15] => $this->getMobile(),
            $keys[16] => $this->getEmail(),
            $keys[17] => $this->getWebsite(),
            $keys[18] => $this->getLogo(),
            $keys[19] => $this->getStatus(),
            $keys[20] => $this->getIsPrincipal(),
            $keys[21] => $this->getConfirmation(),
            $keys[22] => $this->getSortableRank(),
            $keys[23] => $this->getCreatedAt(),
            $keys[24] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aUser) {
                $result['User'] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aState) {
                $result['State'] = $this->aState->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCountry) {
                $result['Country'] = $this->aCountry->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aRegion) {
                $result['Region'] = $this->aRegion->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collOrganizationI18ns) {
                $result['OrganizationI18ns'] = $this->collOrganizationI18ns->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = OrganizationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setName($value);
                break;
            case 3:
                $this->setUrl($value);
                break;
            case 4:
                $this->setGovermentLicense($value);
                break;
            case 5:
                $this->setJoinAt($value);
                break;
            case 6:
                $this->setAddress1($value);
                break;
            case 7:
                $this->setAddress2($value);
                break;
            case 8:
                $this->setCity($value);
                break;
            case 9:
                $this->setZipcode($value);
                break;
            case 10:
                $this->setCountryId($value);
                break;
            case 11:
                $this->setStateId($value);
                break;
            case 12:
                $this->setRegionId($value);
                break;
            case 13:
                $this->setPhone($value);
                break;
            case 14:
                $this->setFax($value);
                break;
            case 15:
                $this->setMobile($value);
                break;
            case 16:
                $this->setEmail($value);
                break;
            case 17:
                $this->setWebsite($value);
                break;
            case 18:
                $this->setLogo($value);
                break;
            case 19:
                $valueSet = OrganizationPeer::getValueSet(OrganizationPeer::STATUS);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setStatus($value);
                break;
            case 20:
                $this->setIsPrincipal($value);
                break;
            case 21:
                $valueSet = OrganizationPeer::getValueSet(OrganizationPeer::CONFIRMATION);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setConfirmation($value);
                break;
            case 22:
                $this->setSortableRank($value);
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
        $keys = OrganizationPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUserId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setName($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setUrl($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setGovermentLicense($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setJoinAt($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setAddress1($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setAddress2($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setCity($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setZipcode($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setCountryId($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setStateId($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setRegionId($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setPhone($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setFax($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setMobile($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setEmail($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setWebsite($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setLogo($arr[$keys[18]]);
        if (array_key_exists($keys[19], $arr)) $this->setStatus($arr[$keys[19]]);
        if (array_key_exists($keys[20], $arr)) $this->setIsPrincipal($arr[$keys[20]]);
        if (array_key_exists($keys[21], $arr)) $this->setConfirmation($arr[$keys[21]]);
        if (array_key_exists($keys[22], $arr)) $this->setSortableRank($arr[$keys[22]]);
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
        $criteria = new Criteria(OrganizationPeer::DATABASE_NAME);

        if ($this->isColumnModified(OrganizationPeer::ID)) $criteria->add(OrganizationPeer::ID, $this->id);
        if ($this->isColumnModified(OrganizationPeer::USER_ID)) $criteria->add(OrganizationPeer::USER_ID, $this->user_id);
        if ($this->isColumnModified(OrganizationPeer::NAME)) $criteria->add(OrganizationPeer::NAME, $this->name);
        if ($this->isColumnModified(OrganizationPeer::URL)) $criteria->add(OrganizationPeer::URL, $this->url);
        if ($this->isColumnModified(OrganizationPeer::GOVERMENT_LICENSE)) $criteria->add(OrganizationPeer::GOVERMENT_LICENSE, $this->goverment_license);
        if ($this->isColumnModified(OrganizationPeer::JOIN_AT)) $criteria->add(OrganizationPeer::JOIN_AT, $this->join_at);
        if ($this->isColumnModified(OrganizationPeer::ADDRESS1)) $criteria->add(OrganizationPeer::ADDRESS1, $this->address1);
        if ($this->isColumnModified(OrganizationPeer::ADDRESS2)) $criteria->add(OrganizationPeer::ADDRESS2, $this->address2);
        if ($this->isColumnModified(OrganizationPeer::CITY)) $criteria->add(OrganizationPeer::CITY, $this->city);
        if ($this->isColumnModified(OrganizationPeer::ZIPCODE)) $criteria->add(OrganizationPeer::ZIPCODE, $this->zipcode);
        if ($this->isColumnModified(OrganizationPeer::COUNTRY_ID)) $criteria->add(OrganizationPeer::COUNTRY_ID, $this->country_id);
        if ($this->isColumnModified(OrganizationPeer::STATE_ID)) $criteria->add(OrganizationPeer::STATE_ID, $this->state_id);
        if ($this->isColumnModified(OrganizationPeer::REGION_ID)) $criteria->add(OrganizationPeer::REGION_ID, $this->region_id);
        if ($this->isColumnModified(OrganizationPeer::PHONE)) $criteria->add(OrganizationPeer::PHONE, $this->phone);
        if ($this->isColumnModified(OrganizationPeer::FAX)) $criteria->add(OrganizationPeer::FAX, $this->fax);
        if ($this->isColumnModified(OrganizationPeer::MOBILE)) $criteria->add(OrganizationPeer::MOBILE, $this->mobile);
        if ($this->isColumnModified(OrganizationPeer::EMAIL)) $criteria->add(OrganizationPeer::EMAIL, $this->email);
        if ($this->isColumnModified(OrganizationPeer::WEBSITE)) $criteria->add(OrganizationPeer::WEBSITE, $this->website);
        if ($this->isColumnModified(OrganizationPeer::LOGO)) $criteria->add(OrganizationPeer::LOGO, $this->logo);
        if ($this->isColumnModified(OrganizationPeer::STATUS)) $criteria->add(OrganizationPeer::STATUS, $this->status);
        if ($this->isColumnModified(OrganizationPeer::IS_PRINCIPAL)) $criteria->add(OrganizationPeer::IS_PRINCIPAL, $this->is_principal);
        if ($this->isColumnModified(OrganizationPeer::CONFIRMATION)) $criteria->add(OrganizationPeer::CONFIRMATION, $this->confirmation);
        if ($this->isColumnModified(OrganizationPeer::SORTABLE_RANK)) $criteria->add(OrganizationPeer::SORTABLE_RANK, $this->sortable_rank);
        if ($this->isColumnModified(OrganizationPeer::CREATED_AT)) $criteria->add(OrganizationPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(OrganizationPeer::UPDATED_AT)) $criteria->add(OrganizationPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(OrganizationPeer::DATABASE_NAME);
        $criteria->add(OrganizationPeer::ID, $this->id);

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
     * @param object $copyObj An object of Organization (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUserId($this->getUserId());
        $copyObj->setName($this->getName());
        $copyObj->setUrl($this->getUrl());
        $copyObj->setGovermentLicense($this->getGovermentLicense());
        $copyObj->setJoinAt($this->getJoinAt());
        $copyObj->setAddress1($this->getAddress1());
        $copyObj->setAddress2($this->getAddress2());
        $copyObj->setCity($this->getCity());
        $copyObj->setZipcode($this->getZipcode());
        $copyObj->setCountryId($this->getCountryId());
        $copyObj->setStateId($this->getStateId());
        $copyObj->setRegionId($this->getRegionId());
        $copyObj->setPhone($this->getPhone());
        $copyObj->setFax($this->getFax());
        $copyObj->setMobile($this->getMobile());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setWebsite($this->getWebsite());
        $copyObj->setLogo($this->getLogo());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setIsPrincipal($this->getIsPrincipal());
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

            foreach ($this->getOrganizationI18ns() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrganizationI18n($relObj->copy($deepCopy));
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
     * @return Organization Clone of current object.
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
     * @return OrganizationPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new OrganizationPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param                  User $v
     * @return Organization The current object (for fluent API support)
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
            $v->addOrganization($this);
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
                $this->aUser->addOrganizations($this);
             */
        }

        return $this->aUser;
    }

    /**
     * Declares an association between this object and a State object.
     *
     * @param                  State $v
     * @return Organization The current object (for fluent API support)
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
            $v->addOrganization($this);
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
                $this->aState->addOrganizations($this);
             */
        }

        return $this->aState;
    }

    /**
     * Declares an association between this object and a Country object.
     *
     * @param                  Country $v
     * @return Organization The current object (for fluent API support)
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
            $v->addOrganization($this);
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
                $this->aCountry->addOrganizations($this);
             */
        }

        return $this->aCountry;
    }

    /**
     * Declares an association between this object and a Region object.
     *
     * @param                  Region $v
     * @return Organization The current object (for fluent API support)
     * @throws PropelException
     */
    public function setRegion(Region $v = null)
    {
        if ($v === null) {
            $this->setRegionId(NULL);
        } else {
            $this->setRegionId($v->getId());
        }

        $this->aRegion = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Region object, it will not be re-added.
        if ($v !== null) {
            $v->addOrganization($this);
        }


        return $this;
    }


    /**
     * Get the associated Region object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Region The associated Region object.
     * @throws PropelException
     */
    public function getRegion(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aRegion === null && ($this->region_id !== null) && $doQuery) {
            $this->aRegion = RegionQuery::create()->findPk($this->region_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aRegion->addOrganizations($this);
             */
        }

        return $this->aRegion;
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
        if ('OrganizationI18n' == $relationName) {
            $this->initOrganizationI18ns();
        }
    }

    /**
     * Clears out the collOrganizationI18ns collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Organization The current object (for fluent API support)
     * @see        addOrganizationI18ns()
     */
    public function clearOrganizationI18ns()
    {
        $this->collOrganizationI18ns = null; // important to set this to null since that means it is uninitialized
        $this->collOrganizationI18nsPartial = null;

        return $this;
    }

    /**
     * reset is the collOrganizationI18ns collection loaded partially
     *
     * @return void
     */
    public function resetPartialOrganizationI18ns($v = true)
    {
        $this->collOrganizationI18nsPartial = $v;
    }

    /**
     * Initializes the collOrganizationI18ns collection.
     *
     * By default this just sets the collOrganizationI18ns collection to an empty array (like clearcollOrganizationI18ns());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOrganizationI18ns($overrideExisting = true)
    {
        if (null !== $this->collOrganizationI18ns && !$overrideExisting) {
            return;
        }
        $this->collOrganizationI18ns = new PropelObjectCollection();
        $this->collOrganizationI18ns->setModel('OrganizationI18n');
    }

    /**
     * Gets an array of OrganizationI18n objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Organization is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|OrganizationI18n[] List of OrganizationI18n objects
     * @throws PropelException
     */
    public function getOrganizationI18ns($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collOrganizationI18nsPartial && !$this->isNew();
        if (null === $this->collOrganizationI18ns || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collOrganizationI18ns) {
                // return empty collection
                $this->initOrganizationI18ns();
            } else {
                $collOrganizationI18ns = OrganizationI18nQuery::create(null, $criteria)
                    ->filterByOrganization($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collOrganizationI18nsPartial && count($collOrganizationI18ns)) {
                      $this->initOrganizationI18ns(false);

                      foreach ($collOrganizationI18ns as $obj) {
                        if (false == $this->collOrganizationI18ns->contains($obj)) {
                          $this->collOrganizationI18ns->append($obj);
                        }
                      }

                      $this->collOrganizationI18nsPartial = true;
                    }

                    $collOrganizationI18ns->getInternalIterator()->rewind();

                    return $collOrganizationI18ns;
                }

                if ($partial && $this->collOrganizationI18ns) {
                    foreach ($this->collOrganizationI18ns as $obj) {
                        if ($obj->isNew()) {
                            $collOrganizationI18ns[] = $obj;
                        }
                    }
                }

                $this->collOrganizationI18ns = $collOrganizationI18ns;
                $this->collOrganizationI18nsPartial = false;
            }
        }

        return $this->collOrganizationI18ns;
    }

    /**
     * Sets a collection of OrganizationI18n objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $organizationI18ns A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Organization The current object (for fluent API support)
     */
    public function setOrganizationI18ns(PropelCollection $organizationI18ns, PropelPDO $con = null)
    {
        $organizationI18nsToDelete = $this->getOrganizationI18ns(new Criteria(), $con)->diff($organizationI18ns);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->organizationI18nsScheduledForDeletion = clone $organizationI18nsToDelete;

        foreach ($organizationI18nsToDelete as $organizationI18nRemoved) {
            $organizationI18nRemoved->setOrganization(null);
        }

        $this->collOrganizationI18ns = null;
        foreach ($organizationI18ns as $organizationI18n) {
            $this->addOrganizationI18n($organizationI18n);
        }

        $this->collOrganizationI18ns = $organizationI18ns;
        $this->collOrganizationI18nsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related OrganizationI18n objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related OrganizationI18n objects.
     * @throws PropelException
     */
    public function countOrganizationI18ns(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collOrganizationI18nsPartial && !$this->isNew();
        if (null === $this->collOrganizationI18ns || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOrganizationI18ns) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOrganizationI18ns());
            }
            $query = OrganizationI18nQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOrganization($this)
                ->count($con);
        }

        return count($this->collOrganizationI18ns);
    }

    /**
     * Method called to associate a OrganizationI18n object to this object
     * through the OrganizationI18n foreign key attribute.
     *
     * @param    OrganizationI18n $l OrganizationI18n
     * @return Organization The current object (for fluent API support)
     */
    public function addOrganizationI18n(OrganizationI18n $l)
    {
        if ($l && $locale = $l->getLocale()) {
            $this->setLocale($locale);
            $this->currentTranslations[$locale] = $l;
        }
        if ($this->collOrganizationI18ns === null) {
            $this->initOrganizationI18ns();
            $this->collOrganizationI18nsPartial = true;
        }

        if (!in_array($l, $this->collOrganizationI18ns->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddOrganizationI18n($l);

            if ($this->organizationI18nsScheduledForDeletion and $this->organizationI18nsScheduledForDeletion->contains($l)) {
                $this->organizationI18nsScheduledForDeletion->remove($this->organizationI18nsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	OrganizationI18n $organizationI18n The organizationI18n object to add.
     */
    protected function doAddOrganizationI18n($organizationI18n)
    {
        $this->collOrganizationI18ns[]= $organizationI18n;
        $organizationI18n->setOrganization($this);
    }

    /**
     * @param	OrganizationI18n $organizationI18n The organizationI18n object to remove.
     * @return Organization The current object (for fluent API support)
     */
    public function removeOrganizationI18n($organizationI18n)
    {
        if ($this->getOrganizationI18ns()->contains($organizationI18n)) {
            $this->collOrganizationI18ns->remove($this->collOrganizationI18ns->search($organizationI18n));
            if (null === $this->organizationI18nsScheduledForDeletion) {
                $this->organizationI18nsScheduledForDeletion = clone $this->collOrganizationI18ns;
                $this->organizationI18nsScheduledForDeletion->clear();
            }
            $this->organizationI18nsScheduledForDeletion[]= clone $organizationI18n;
            $organizationI18n->setOrganization(null);
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
        $this->name = null;
        $this->url = null;
        $this->goverment_license = null;
        $this->join_at = null;
        $this->address1 = null;
        $this->address2 = null;
        $this->city = null;
        $this->zipcode = null;
        $this->country_id = null;
        $this->state_id = null;
        $this->region_id = null;
        $this->phone = null;
        $this->fax = null;
        $this->mobile = null;
        $this->email = null;
        $this->website = null;
        $this->logo = null;
        $this->status = null;
        $this->is_principal = null;
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
            if ($this->collOrganizationI18ns) {
                foreach ($this->collOrganizationI18ns as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aUser instanceof Persistent) {
              $this->aUser->clearAllReferences($deep);
            }
            if ($this->aState instanceof Persistent) {
              $this->aState->clearAllReferences($deep);
            }
            if ($this->aCountry instanceof Persistent) {
              $this->aCountry->clearAllReferences($deep);
            }
            if ($this->aRegion instanceof Persistent) {
              $this->aRegion->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        // i18n behavior
        $this->currentLocale = 'en_US';
        $this->currentTranslations = null;

        if ($this->collOrganizationI18ns instanceof PropelCollection) {
            $this->collOrganizationI18ns->clearIterator();
        }
        $this->collOrganizationI18ns = null;
        $this->aUser = null;
        $this->aState = null;
        $this->aCountry = null;
        $this->aRegion = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(OrganizationPeer::DEFAULT_STRING_FORMAT);
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
     * @return    Organization The current object (for fluent API support)
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
     * @return OrganizationI18n */
    public function getTranslation($locale = 'en_US', PropelPDO $con = null)
    {
        if (!isset($this->currentTranslations[$locale])) {
            if (null !== $this->collOrganizationI18ns) {
                foreach ($this->collOrganizationI18ns as $translation) {
                    if ($translation->getLocale() == $locale) {
                        $this->currentTranslations[$locale] = $translation;

                        return $translation;
                    }
                }
            }
            if ($this->isNew()) {
                $translation = new OrganizationI18n();
                $translation->setLocale($locale);
            } else {
                $translation = OrganizationI18nQuery::create()
                    ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                    ->findOneOrCreate($con);
                $this->currentTranslations[$locale] = $translation;
            }
            $this->addOrganizationI18n($translation);
        }

        return $this->currentTranslations[$locale];
    }

    /**
     * Remove the translation for a given locale
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     * @param     PropelPDO $con an optional connection object
     *
     * @return    Organization The current object (for fluent API support)
     */
    public function removeTranslation($locale = 'en_US', PropelPDO $con = null)
    {
        if (!$this->isNew()) {
            OrganizationI18nQuery::create()
                ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                ->delete($con);
        }
        if (isset($this->currentTranslations[$locale])) {
            unset($this->currentTranslations[$locale]);
        }
        foreach ($this->collOrganizationI18ns as $key => $translation) {
            if ($translation->getLocale() == $locale) {
                unset($this->collOrganizationI18ns[$key]);
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
     * @return OrganizationI18n */
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
         * @return OrganizationI18n The current object (for fluent API support)
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
         * @return OrganizationI18n The current object (for fluent API support)
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
     * @return  Organization
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

         $query = OrganizationQuery::create('q')
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
     * @return    Organization
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
        return $this->getSortableRank() == OrganizationQuery::create()->getMaxRankArray($con);
    }

    /**
     * Get the next item in the list, i.e. the one for which rank is immediately higher
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Organization
     */
    public function getNext(PropelPDO $con = null)
    {

        $query = OrganizationQuery::create();

        $query->filterByRank($this->getSortableRank() + 1);


        return $query->findOne($con);
    }

    /**
     * Get the previous item in the list, i.e. the one for which rank is immediately lower
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Organization
     */
    public function getPrevious(PropelPDO $con = null)
    {

        $query = OrganizationQuery::create();

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
     * @return    Organization the current object
     *
     * @throws    PropelException
     */
    public function insertAtRank($rank, PropelPDO $con = null)
    {
        $maxRank = OrganizationQuery::create()->getMaxRankArray($con);
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
     * @return    Organization the current object
     *
     * @throws    PropelException
     */
    public function insertAtBottom(PropelPDO $con = null)
    {
        $this->setSortableRank(OrganizationQuery::create()->getMaxRankArray($con) + 1);

        return $this;
    }

    /**
     * Insert in the first rank
     * The modifications are not persisted until the object is saved.
     *
     * @return    Organization the current object
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
     * @return    Organization the current object
     *
     * @throws    PropelException
     */
    public function moveToRank($newRank, PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be moved. Please use insertAtRank() instead');
        }
        if ($con === null) {
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME);
        }
        if ($newRank < 1 || $newRank > OrganizationQuery::create()->getMaxRankArray($con)) {
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
            OrganizationPeer::shiftRank($delta, min($oldRank, $newRank), max($oldRank, $newRank), $con);

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
     * @param     Organization $object
     * @param     PropelPDO $con optional connection
     *
     * @return    Organization the current object
     *
     * @throws Exception if the database cannot execute the two updates
     */
    public function swapWith($object, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME);
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
     * @return    Organization the current object
     */
    public function moveUp(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME);
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
     * @return    Organization the current object
     */
    public function moveDown(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME);
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
     * @return    Organization the current object
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
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $bottom = OrganizationQuery::create()->getMaxRankArray($con);
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
     * @return    Organization the current object
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
     * @return     Organization The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = OrganizationPeer::UPDATED_AT;

        return $this;
    }

    // archivable behavior

    /**
     * Get an archived version of the current object.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @return     OrganizationArchive An archive object, or null if the current object was never archived
     */
    public function getArchive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            return null;
        }
        $archive = OrganizationArchiveQuery::create()
            ->filterByPrimaryKey($this->getPrimaryKey())
            ->findOne($con);

        return $archive;
    }
    /**
     * Copy the data of the current object into a $archiveTablePhpName archive object.
     * The archived object is then saved.
     * If the current object has already been archived, the archived object
     * is updated and not duplicated.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @throws PropelException If the object is new
     *
     * @return     OrganizationArchive The archive object based on this object
     */
    public function archive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be archived. You must save the current object before calling archive().');
        }
        if (!$archive = $this->getArchive($con)) {
            $archive = new OrganizationArchive();
            $archive->setPrimaryKey($this->getPrimaryKey());
        }
        $this->copyInto($archive, $deepCopy = false, $makeNew = false);
        $archive->setArchivedAt(time());
        $archive->save($con);

        return $archive;
    }

    /**
     * Revert the the current object to the state it had when it was last archived.
     * The object must be saved afterwards if the changes must persist.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @throws PropelException If the object has no corresponding archive.
     *
     * @return Organization The current object (for fluent API support)
     */
    public function restoreFromArchive(PropelPDO $con = null)
    {
        if (!$archive = $this->getArchive($con)) {
            throw new PropelException('The current object has never been archived and cannot be restored');
        }
        $this->populateFromArchive($archive);

        return $this;
    }

    /**
     * Populates the the current object based on a $archiveTablePhpName archive object.
     *
     * @param      OrganizationArchive $archive An archived object based on the same class
      * @param      Boolean $populateAutoIncrementPrimaryKeys
     *               If true, autoincrement columns are copied from the archive object.
     *               If false, autoincrement columns are left intact.
      *
     * @return     Organization The current object (for fluent API support)
     */
    public function populateFromArchive($archive, $populateAutoIncrementPrimaryKeys = false) {
        if ($populateAutoIncrementPrimaryKeys) {
            $this->setId($archive->getId());
        }
        $this->setUserId($archive->getUserId());
        $this->setName($archive->getName());
        $this->setUrl($archive->getUrl());
        $this->setGovermentLicense($archive->getGovermentLicense());
        $this->setJoinAt($archive->getJoinAt());
        $this->setAddress1($archive->getAddress1());
        $this->setAddress2($archive->getAddress2());
        $this->setCity($archive->getCity());
        $this->setZipcode($archive->getZipcode());
        $this->setCountryId($archive->getCountryId());
        $this->setStateId($archive->getStateId());
        $this->setRegionId($archive->getRegionId());
        $this->setPhone($archive->getPhone());
        $this->setFax($archive->getFax());
        $this->setMobile($archive->getMobile());
        $this->setEmail($archive->getEmail());
        $this->setWebsite($archive->getWebsite());
        $this->setLogo($archive->getLogo());
        $this->setStatus($archive->getStatus());
        $this->setIsPrincipal($archive->getIsPrincipal());
        $this->setConfirmation($archive->getConfirmation());
        $this->setSortableRank($archive->getSortableRank());
        $this->setCreatedAt($archive->getCreatedAt());
        $this->setUpdatedAt($archive->getUpdatedAt());

        return $this;
    }

    /**
     * Removes the object from the database without archiving it.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @return     Organization The current object (for fluent API support)
     */
    public function deleteWithoutArchive(PropelPDO $con = null)
    {
        $this->archiveOnDelete = false;

        return $this->delete($con);
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
