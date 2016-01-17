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
use Glorpen\Propel\PropelBundle\Dispatcher\EventDispatcherProxy;
use Glorpen\Propel\PropelBundle\Events\ModelEvent;
use PGS\CoreDomainBundle\Model\License;
use PGS\CoreDomainBundle\Model\LicensePayment;
use PGS\CoreDomainBundle\Model\LicensePaymentArchive;
use PGS\CoreDomainBundle\Model\LicensePaymentArchiveQuery;
use PGS\CoreDomainBundle\Model\LicensePaymentPeer;
use PGS\CoreDomainBundle\Model\LicensePaymentQuery;
use PGS\CoreDomainBundle\Model\LicenseQuery;
use PGS\CoreDomainBundle\Model\User;
use PGS\CoreDomainBundle\Model\UserLicense;
use PGS\CoreDomainBundle\Model\UserLicenseQuery;
use PGS\CoreDomainBundle\Model\UserQuery;

abstract class BaseLicensePayment extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'PGS\\CoreDomainBundle\\Model\\LicensePaymentPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        LicensePaymentPeer
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
     * The value for the license_id field.
     * @var        int
     */
    protected $license_id;

    /**
     * The value for the payment_date field.
     * @var        string
     */
    protected $payment_date;

    /**
     * The value for the quantity field.
     * Note: this column has a database default value of: 1
     * @var        int
     */
    protected $quantity;

    /**
     * The value for the price field.
     * @var        string
     */
    protected $price;

    /**
     * The value for the subtotal field.
     * @var        string
     */
    protected $subtotal;

    /**
     * The value for the discount field.
     * @var        string
     */
    protected $discount;

    /**
     * The value for the tax field.
     * @var        string
     */
    protected $tax;

    /**
     * The value for the total field.
     * @var        string
     */
    protected $total;

    /**
     * The value for the transaction_code field.
     * @var        string
     */
    protected $transaction_code;

    /**
     * The value for the status field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $status;

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
     * @var        License
     */
    protected $aLicense;

    /**
     * @var        PropelObjectCollection|UserLicense[] Collection to store aggregation of UserLicense objects.
     */
    protected $collUserLicenses;
    protected $collUserLicensesPartial;

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

    // archivable behavior
    protected $archiveOnDelete = true;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $userLicensesScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->quantity = 1;
        $this->status = 0;
    }

    /**
     * Initializes internal state of BaseLicensePayment object.
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
     * Get the [license_id] column value.
     *
     * @return int
     */
    public function getLicenseId()
    {

        return $this->license_id;
    }

    /**
     * Get the [optionally formatted] temporal [payment_date] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getPaymentDate($format = null)
    {
        if ($this->payment_date === null) {
            return null;
        }

        if ($this->payment_date === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->payment_date);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->payment_date, true), $x);
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
     * Get the [quantity] column value.
     *
     * @return int
     */
    public function getQuantity()
    {

        return $this->quantity;
    }

    /**
     * Get the [price] column value.
     *
     * @return string
     */
    public function getPrice()
    {

        return $this->price;
    }

    /**
     * Get the [subtotal] column value.
     *
     * @return string
     */
    public function getSubtotal()
    {

        return $this->subtotal;
    }

    /**
     * Get the [discount] column value.
     *
     * @return string
     */
    public function getDiscount()
    {

        return $this->discount;
    }

    /**
     * Get the [tax] column value.
     *
     * @return string
     */
    public function getTax()
    {

        return $this->tax;
    }

    /**
     * Get the [total] column value.
     *
     * @return string
     */
    public function getTotal()
    {

        return $this->total;
    }

    /**
     * Get the [transaction_code] column value.
     *
     * @return string
     */
    public function getTransactionCode()
    {

        return $this->transaction_code;
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
        $valueSet = LicensePaymentPeer::getValueSet(LicensePaymentPeer::STATUS);
        if (!isset($valueSet[$this->status])) {
            throw new PropelException('Unknown stored enum key: ' . $this->status);
        }

        return $valueSet[$this->status];
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
     * @return LicensePayment The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = LicensePaymentPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [user_id] column.
     *
     * @param  int $v new value
     * @return LicensePayment The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[] = LicensePaymentPeer::USER_ID;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }


        return $this;
    } // setUserId()

    /**
     * Set the value of [license_id] column.
     *
     * @param  int $v new value
     * @return LicensePayment The current object (for fluent API support)
     */
    public function setLicenseId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->license_id !== $v) {
            $this->license_id = $v;
            $this->modifiedColumns[] = LicensePaymentPeer::LICENSE_ID;
        }

        if ($this->aLicense !== null && $this->aLicense->getId() !== $v) {
            $this->aLicense = null;
        }


        return $this;
    } // setLicenseId()

    /**
     * Sets the value of [payment_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return LicensePayment The current object (for fluent API support)
     */
    public function setPaymentDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->payment_date !== null || $dt !== null) {
            $currentDateAsString = ($this->payment_date !== null && $tmpDt = new DateTime($this->payment_date)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->payment_date = $newDateAsString;
                $this->modifiedColumns[] = LicensePaymentPeer::PAYMENT_DATE;
            }
        } // if either are not null


        return $this;
    } // setPaymentDate()

    /**
     * Set the value of [quantity] column.
     *
     * @param  int $v new value
     * @return LicensePayment The current object (for fluent API support)
     */
    public function setQuantity($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->quantity !== $v) {
            $this->quantity = $v;
            $this->modifiedColumns[] = LicensePaymentPeer::QUANTITY;
        }


        return $this;
    } // setQuantity()

    /**
     * Set the value of [price] column.
     *
     * @param  string $v new value
     * @return LicensePayment The current object (for fluent API support)
     */
    public function setPrice($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->price !== $v) {
            $this->price = $v;
            $this->modifiedColumns[] = LicensePaymentPeer::PRICE;
        }


        return $this;
    } // setPrice()

    /**
     * Set the value of [subtotal] column.
     *
     * @param  string $v new value
     * @return LicensePayment The current object (for fluent API support)
     */
    public function setSubtotal($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->subtotal !== $v) {
            $this->subtotal = $v;
            $this->modifiedColumns[] = LicensePaymentPeer::SUBTOTAL;
        }


        return $this;
    } // setSubtotal()

    /**
     * Set the value of [discount] column.
     *
     * @param  string $v new value
     * @return LicensePayment The current object (for fluent API support)
     */
    public function setDiscount($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->discount !== $v) {
            $this->discount = $v;
            $this->modifiedColumns[] = LicensePaymentPeer::DISCOUNT;
        }


        return $this;
    } // setDiscount()

    /**
     * Set the value of [tax] column.
     *
     * @param  string $v new value
     * @return LicensePayment The current object (for fluent API support)
     */
    public function setTax($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->tax !== $v) {
            $this->tax = $v;
            $this->modifiedColumns[] = LicensePaymentPeer::TAX;
        }


        return $this;
    } // setTax()

    /**
     * Set the value of [total] column.
     *
     * @param  string $v new value
     * @return LicensePayment The current object (for fluent API support)
     */
    public function setTotal($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->total !== $v) {
            $this->total = $v;
            $this->modifiedColumns[] = LicensePaymentPeer::TOTAL;
        }


        return $this;
    } // setTotal()

    /**
     * Set the value of [transaction_code] column.
     *
     * @param  string $v new value
     * @return LicensePayment The current object (for fluent API support)
     */
    public function setTransactionCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->transaction_code !== $v) {
            $this->transaction_code = $v;
            $this->modifiedColumns[] = LicensePaymentPeer::TRANSACTION_CODE;
        }


        return $this;
    } // setTransactionCode()

    /**
     * Set the value of [status] column.
     *
     * @param  int $v new value
     * @return LicensePayment The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            $valueSet = LicensePaymentPeer::getValueSet(LicensePaymentPeer::STATUS);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[] = LicensePaymentPeer::STATUS;
        }


        return $this;
    } // setStatus()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return LicensePayment The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = LicensePaymentPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return LicensePayment The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = LicensePaymentPeer::UPDATED_AT;
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
            if ($this->quantity !== 1) {
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
            $this->user_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->license_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->payment_date = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->quantity = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->price = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->subtotal = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->discount = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->tax = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->total = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->transaction_code = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->status = ($row[$startcol + 11] !== null) ? (int) $row[$startcol + 11] : null;
            $this->created_at = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
            $this->updated_at = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 14; // 14 = LicensePaymentPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating LicensePayment object", $e);
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
        if ($this->aLicense !== null && $this->license_id !== $this->aLicense->getId()) {
            $this->aLicense = null;
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
            $con = Propel::getConnection(LicensePaymentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = LicensePaymentPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->aLicense = null;
            $this->collUserLicenses = null;

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
            $con = Propel::getConnection(LicensePaymentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
            $deleteQuery = LicensePaymentQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // archivable behavior
            if ($ret) {
                if ($this->archiveOnDelete) {
                    // do nothing yet. The object will be archived later when calling LicensePaymentQuery::delete().
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
            $con = Propel::getConnection(LicensePaymentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                if (!$this->isColumnModified(LicensePaymentPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(LicensePaymentPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(LicensePaymentPeer::UPDATED_AT)) {
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
                LicensePaymentPeer::addInstanceToPool($this);
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

            if ($this->aLicense !== null) {
                if ($this->aLicense->isModified() || $this->aLicense->isNew()) {
                    $affectedRows += $this->aLicense->save($con);
                }
                $this->setLicense($this->aLicense);
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

            if ($this->userLicensesScheduledForDeletion !== null) {
                if (!$this->userLicensesScheduledForDeletion->isEmpty()) {
                    foreach ($this->userLicensesScheduledForDeletion as $userLicense) {
                        // need to save related object because we set the relation to null
                        $userLicense->save($con);
                    }
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

        $this->modifiedColumns[] = LicensePaymentPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . LicensePaymentPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(LicensePaymentPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(LicensePaymentPeer::USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`user_id`';
        }
        if ($this->isColumnModified(LicensePaymentPeer::LICENSE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`license_id`';
        }
        if ($this->isColumnModified(LicensePaymentPeer::PAYMENT_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`payment_date`';
        }
        if ($this->isColumnModified(LicensePaymentPeer::QUANTITY)) {
            $modifiedColumns[':p' . $index++]  = '`quantity`';
        }
        if ($this->isColumnModified(LicensePaymentPeer::PRICE)) {
            $modifiedColumns[':p' . $index++]  = '`price`';
        }
        if ($this->isColumnModified(LicensePaymentPeer::SUBTOTAL)) {
            $modifiedColumns[':p' . $index++]  = '`subtotal`';
        }
        if ($this->isColumnModified(LicensePaymentPeer::DISCOUNT)) {
            $modifiedColumns[':p' . $index++]  = '`discount`';
        }
        if ($this->isColumnModified(LicensePaymentPeer::TAX)) {
            $modifiedColumns[':p' . $index++]  = '`tax`';
        }
        if ($this->isColumnModified(LicensePaymentPeer::TOTAL)) {
            $modifiedColumns[':p' . $index++]  = '`total`';
        }
        if ($this->isColumnModified(LicensePaymentPeer::TRANSACTION_CODE)) {
            $modifiedColumns[':p' . $index++]  = '`transaction_code`';
        }
        if ($this->isColumnModified(LicensePaymentPeer::STATUS)) {
            $modifiedColumns[':p' . $index++]  = '`status`';
        }
        if ($this->isColumnModified(LicensePaymentPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(LicensePaymentPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `license_payment` (%s) VALUES (%s)',
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
                    case '`license_id`':
                        $stmt->bindValue($identifier, $this->license_id, PDO::PARAM_INT);
                        break;
                    case '`payment_date`':
                        $stmt->bindValue($identifier, $this->payment_date, PDO::PARAM_STR);
                        break;
                    case '`quantity`':
                        $stmt->bindValue($identifier, $this->quantity, PDO::PARAM_INT);
                        break;
                    case '`price`':
                        $stmt->bindValue($identifier, $this->price, PDO::PARAM_STR);
                        break;
                    case '`subtotal`':
                        $stmt->bindValue($identifier, $this->subtotal, PDO::PARAM_STR);
                        break;
                    case '`discount`':
                        $stmt->bindValue($identifier, $this->discount, PDO::PARAM_STR);
                        break;
                    case '`tax`':
                        $stmt->bindValue($identifier, $this->tax, PDO::PARAM_STR);
                        break;
                    case '`total`':
                        $stmt->bindValue($identifier, $this->total, PDO::PARAM_STR);
                        break;
                    case '`transaction_code`':
                        $stmt->bindValue($identifier, $this->transaction_code, PDO::PARAM_STR);
                        break;
                    case '`status`':
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_INT);
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

            if ($this->aLicense !== null) {
                if (!$this->aLicense->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aLicense->getValidationFailures());
                }
            }


            if (($retval = LicensePaymentPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collUserLicenses !== null) {
                    foreach ($this->collUserLicenses as $referrerFK) {
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
        $pos = LicensePaymentPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getLicenseId();
                break;
            case 3:
                return $this->getPaymentDate();
                break;
            case 4:
                return $this->getQuantity();
                break;
            case 5:
                return $this->getPrice();
                break;
            case 6:
                return $this->getSubtotal();
                break;
            case 7:
                return $this->getDiscount();
                break;
            case 8:
                return $this->getTax();
                break;
            case 9:
                return $this->getTotal();
                break;
            case 10:
                return $this->getTransactionCode();
                break;
            case 11:
                return $this->getStatus();
                break;
            case 12:
                return $this->getCreatedAt();
                break;
            case 13:
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
        if (isset($alreadyDumpedObjects['LicensePayment'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['LicensePayment'][$this->getPrimaryKey()] = true;
        $keys = LicensePaymentPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUserId(),
            $keys[2] => $this->getLicenseId(),
            $keys[3] => $this->getPaymentDate(),
            $keys[4] => $this->getQuantity(),
            $keys[5] => $this->getPrice(),
            $keys[6] => $this->getSubtotal(),
            $keys[7] => $this->getDiscount(),
            $keys[8] => $this->getTax(),
            $keys[9] => $this->getTotal(),
            $keys[10] => $this->getTransactionCode(),
            $keys[11] => $this->getStatus(),
            $keys[12] => $this->getCreatedAt(),
            $keys[13] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aUser) {
                $result['User'] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aLicense) {
                $result['License'] = $this->aLicense->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collUserLicenses) {
                $result['UserLicenses'] = $this->collUserLicenses->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = LicensePaymentPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setLicenseId($value);
                break;
            case 3:
                $this->setPaymentDate($value);
                break;
            case 4:
                $this->setQuantity($value);
                break;
            case 5:
                $this->setPrice($value);
                break;
            case 6:
                $this->setSubtotal($value);
                break;
            case 7:
                $this->setDiscount($value);
                break;
            case 8:
                $this->setTax($value);
                break;
            case 9:
                $this->setTotal($value);
                break;
            case 10:
                $this->setTransactionCode($value);
                break;
            case 11:
                $valueSet = LicensePaymentPeer::getValueSet(LicensePaymentPeer::STATUS);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setStatus($value);
                break;
            case 12:
                $this->setCreatedAt($value);
                break;
            case 13:
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
        $keys = LicensePaymentPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUserId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setLicenseId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setPaymentDate($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setQuantity($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setPrice($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setSubtotal($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setDiscount($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setTax($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setTotal($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setTransactionCode($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setStatus($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setCreatedAt($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setUpdatedAt($arr[$keys[13]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(LicensePaymentPeer::DATABASE_NAME);

        if ($this->isColumnModified(LicensePaymentPeer::ID)) $criteria->add(LicensePaymentPeer::ID, $this->id);
        if ($this->isColumnModified(LicensePaymentPeer::USER_ID)) $criteria->add(LicensePaymentPeer::USER_ID, $this->user_id);
        if ($this->isColumnModified(LicensePaymentPeer::LICENSE_ID)) $criteria->add(LicensePaymentPeer::LICENSE_ID, $this->license_id);
        if ($this->isColumnModified(LicensePaymentPeer::PAYMENT_DATE)) $criteria->add(LicensePaymentPeer::PAYMENT_DATE, $this->payment_date);
        if ($this->isColumnModified(LicensePaymentPeer::QUANTITY)) $criteria->add(LicensePaymentPeer::QUANTITY, $this->quantity);
        if ($this->isColumnModified(LicensePaymentPeer::PRICE)) $criteria->add(LicensePaymentPeer::PRICE, $this->price);
        if ($this->isColumnModified(LicensePaymentPeer::SUBTOTAL)) $criteria->add(LicensePaymentPeer::SUBTOTAL, $this->subtotal);
        if ($this->isColumnModified(LicensePaymentPeer::DISCOUNT)) $criteria->add(LicensePaymentPeer::DISCOUNT, $this->discount);
        if ($this->isColumnModified(LicensePaymentPeer::TAX)) $criteria->add(LicensePaymentPeer::TAX, $this->tax);
        if ($this->isColumnModified(LicensePaymentPeer::TOTAL)) $criteria->add(LicensePaymentPeer::TOTAL, $this->total);
        if ($this->isColumnModified(LicensePaymentPeer::TRANSACTION_CODE)) $criteria->add(LicensePaymentPeer::TRANSACTION_CODE, $this->transaction_code);
        if ($this->isColumnModified(LicensePaymentPeer::STATUS)) $criteria->add(LicensePaymentPeer::STATUS, $this->status);
        if ($this->isColumnModified(LicensePaymentPeer::CREATED_AT)) $criteria->add(LicensePaymentPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(LicensePaymentPeer::UPDATED_AT)) $criteria->add(LicensePaymentPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(LicensePaymentPeer::DATABASE_NAME);
        $criteria->add(LicensePaymentPeer::ID, $this->id);

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
     * @param object $copyObj An object of LicensePayment (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUserId($this->getUserId());
        $copyObj->setLicenseId($this->getLicenseId());
        $copyObj->setPaymentDate($this->getPaymentDate());
        $copyObj->setQuantity($this->getQuantity());
        $copyObj->setPrice($this->getPrice());
        $copyObj->setSubtotal($this->getSubtotal());
        $copyObj->setDiscount($this->getDiscount());
        $copyObj->setTax($this->getTax());
        $copyObj->setTotal($this->getTotal());
        $copyObj->setTransactionCode($this->getTransactionCode());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getUserLicenses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserLicense($relObj->copy($deepCopy));
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
     * @return LicensePayment Clone of current object.
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
     * @return LicensePaymentPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new LicensePaymentPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param                  User $v
     * @return LicensePayment The current object (for fluent API support)
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
            $v->addLicensePayment($this);
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
                $this->aUser->addLicensePayments($this);
             */
        }

        return $this->aUser;
    }

    /**
     * Declares an association between this object and a License object.
     *
     * @param                  License $v
     * @return LicensePayment The current object (for fluent API support)
     * @throws PropelException
     */
    public function setLicense(License $v = null)
    {
        if ($v === null) {
            $this->setLicenseId(NULL);
        } else {
            $this->setLicenseId($v->getId());
        }

        $this->aLicense = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the License object, it will not be re-added.
        if ($v !== null) {
            $v->addLicensePayment($this);
        }


        return $this;
    }


    /**
     * Get the associated License object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return License The associated License object.
     * @throws PropelException
     */
    public function getLicense(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aLicense === null && ($this->license_id !== null) && $doQuery) {
            $this->aLicense = LicenseQuery::create()->findPk($this->license_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aLicense->addLicensePayments($this);
             */
        }

        return $this->aLicense;
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
        if ('UserLicense' == $relationName) {
            $this->initUserLicenses();
        }
    }

    /**
     * Clears out the collUserLicenses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return LicensePayment The current object (for fluent API support)
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
     * If this LicensePayment is new, it will return
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
                    ->filterByLicensePayment($this)
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
     * @return LicensePayment The current object (for fluent API support)
     */
    public function setUserLicenses(PropelCollection $userLicenses, PropelPDO $con = null)
    {
        $userLicensesToDelete = $this->getUserLicenses(new Criteria(), $con)->diff($userLicenses);


        $this->userLicensesScheduledForDeletion = $userLicensesToDelete;

        foreach ($userLicensesToDelete as $userLicenseRemoved) {
            $userLicenseRemoved->setLicensePayment(null);
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
                ->filterByLicensePayment($this)
                ->count($con);
        }

        return count($this->collUserLicenses);
    }

    /**
     * Method called to associate a UserLicense object to this object
     * through the UserLicense foreign key attribute.
     *
     * @param    UserLicense $l UserLicense
     * @return LicensePayment The current object (for fluent API support)
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
        $userLicense->setLicensePayment($this);
    }

    /**
     * @param	UserLicense $userLicense The userLicense object to remove.
     * @return LicensePayment The current object (for fluent API support)
     */
    public function removeUserLicense($userLicense)
    {
        if ($this->getUserLicenses()->contains($userLicense)) {
            $this->collUserLicenses->remove($this->collUserLicenses->search($userLicense));
            if (null === $this->userLicensesScheduledForDeletion) {
                $this->userLicensesScheduledForDeletion = clone $this->collUserLicenses;
                $this->userLicensesScheduledForDeletion->clear();
            }
            $this->userLicensesScheduledForDeletion[]= $userLicense;
            $userLicense->setLicensePayment(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this LicensePayment is new, it will return
     * an empty collection; or if this LicensePayment has previously
     * been saved, it will retrieve related UserLicenses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in LicensePayment.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserLicense[] List of UserLicense objects
     */
    public function getUserLicensesJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserLicenseQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getUserLicenses($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this LicensePayment is new, it will return
     * an empty collection; or if this LicensePayment has previously
     * been saved, it will retrieve related UserLicenses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in LicensePayment.
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
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->user_id = null;
        $this->license_id = null;
        $this->payment_date = null;
        $this->quantity = null;
        $this->price = null;
        $this->subtotal = null;
        $this->discount = null;
        $this->tax = null;
        $this->total = null;
        $this->transaction_code = null;
        $this->status = null;
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
            if ($this->collUserLicenses) {
                foreach ($this->collUserLicenses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aUser instanceof Persistent) {
              $this->aUser->clearAllReferences($deep);
            }
            if ($this->aLicense instanceof Persistent) {
              $this->aLicense->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collUserLicenses instanceof PropelCollection) {
            $this->collUserLicenses->clearIterator();
        }
        $this->collUserLicenses = null;
        $this->aUser = null;
        $this->aLicense = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(LicensePaymentPeer::DEFAULT_STRING_FORMAT);
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
     * @return     LicensePayment The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = LicensePaymentPeer::UPDATED_AT;

        return $this;
    }

    // archivable behavior

    /**
     * Get an archived version of the current object.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @return     LicensePaymentArchive An archive object, or null if the current object was never archived
     */
    public function getArchive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            return null;
        }
        $archive = LicensePaymentArchiveQuery::create()
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
     * @return     LicensePaymentArchive The archive object based on this object
     */
    public function archive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be archived. You must save the current object before calling archive().');
        }
        if (!$archive = $this->getArchive($con)) {
            $archive = new LicensePaymentArchive();
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
     * @return LicensePayment The current object (for fluent API support)
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
     * @param      LicensePaymentArchive $archive An archived object based on the same class
      * @param      Boolean $populateAutoIncrementPrimaryKeys
     *               If true, autoincrement columns are copied from the archive object.
     *               If false, autoincrement columns are left intact.
      *
     * @return     LicensePayment The current object (for fluent API support)
     */
    public function populateFromArchive($archive, $populateAutoIncrementPrimaryKeys = false) {
        if ($populateAutoIncrementPrimaryKeys) {
            $this->setId($archive->getId());
        }
        $this->setUserId($archive->getUserId());
        $this->setLicenseId($archive->getLicenseId());
        $this->setPaymentDate($archive->getPaymentDate());
        $this->setQuantity($archive->getQuantity());
        $this->setPrice($archive->getPrice());
        $this->setSubtotal($archive->getSubtotal());
        $this->setDiscount($archive->getDiscount());
        $this->setTax($archive->getTax());
        $this->setTotal($archive->getTotal());
        $this->setTransactionCode($archive->getTransactionCode());
        $this->setStatus($archive->getStatus());
        $this->setCreatedAt($archive->getCreatedAt());
        $this->setUpdatedAt($archive->getUpdatedAt());

        return $this;
    }

    /**
     * Removes the object from the database without archiving it.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @return     LicensePayment The current object (for fluent API support)
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

    /**
     * Catches calls to virtual methods
     */
    public function __call($name, $params)
    {

        // delegate behavior

        if (is_callable(array('PGS\CoreDomainBundle\Model\License', $name))) {
            if (!$delegate = $this->getLicense()) {
                $delegate = new License();
                $this->setLicense($delegate);
            }

            return call_user_func_array(array($delegate, $name), $params);
        }

        return parent::__call($name, $params);
    }

}