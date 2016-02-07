<?php

namespace PGS\CoreDomainBundle\Model\Visitation\om;

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
use PGS\CoreDomainBundle\Model\Store\Store;
use PGS\CoreDomainBundle\Model\Store\StoreQuery;
use PGS\CoreDomainBundle\Model\Transaction\Transaction;
use PGS\CoreDomainBundle\Model\Transaction\TransactionQuery;
use PGS\CoreDomainBundle\Model\Visitation\Visitation;
use PGS\CoreDomainBundle\Model\Visitation\VisitationPeer;
use PGS\CoreDomainBundle\Model\Visitation\VisitationQuery;

abstract class BaseVisitation extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'PGS\\CoreDomainBundle\\Model\\Visitation\\VisitationPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        VisitationPeer
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
     * The value for the employee_id field.
     * @var        int
     */
    protected $employee_id;

    /**
     * The value for the store_id field.
     * @var        int
     */
    protected $store_id;

    /**
     * The value for the remark field.
     * @var        string
     */
    protected $remark;

    /**
     * The value for the status field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $status;

    /**
     * The value for the total field.
     * Note: this column has a database default value of: '0'
     * @var        string
     */
    protected $total;

    /**
     * The value for the photo field.
     * @var        string
     */
    protected $photo;

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
     * @var        Store
     */
    protected $aStore;

    /**
     * @var        PropelObjectCollection|Transaction[] Collection to store aggregation of Transaction objects.
     */
    protected $collTransactions;
    protected $collTransactionsPartial;

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
    protected $transactionsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->status = 0;
        $this->total = '0';
    }

    /**
     * Initializes internal state of BaseVisitation object.
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
     * Get the [employee_id] column value.
     *
     * @return int
     */
    public function getEmployeeId()
    {

        return $this->employee_id;
    }

    /**
     * Get the [store_id] column value.
     *
     * @return int
     */
    public function getStoreId()
    {

        return $this->store_id;
    }

    /**
     * Get the [remark] column value.
     *
     * @return string
     */
    public function getRemark()
    {

        return $this->remark;
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
        $valueSet = VisitationPeer::getValueSet(VisitationPeer::STATUS);
        if (!isset($valueSet[$this->status])) {
            throw new PropelException('Unknown stored enum key: ' . $this->status);
        }

        return $valueSet[$this->status];
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
     * Get the [photo] column value.
     *
     * @return string
     */
    public function getPhoto()
    {

        return $this->photo;
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
     * @return Visitation The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = VisitationPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [employee_id] column.
     *
     * @param  int $v new value
     * @return Visitation The current object (for fluent API support)
     */
    public function setEmployeeId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->employee_id !== $v) {
            $this->employee_id = $v;
            $this->modifiedColumns[] = VisitationPeer::EMPLOYEE_ID;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }


        return $this;
    } // setEmployeeId()

    /**
     * Set the value of [store_id] column.
     *
     * @param  int $v new value
     * @return Visitation The current object (for fluent API support)
     */
    public function setStoreId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->store_id !== $v) {
            $this->store_id = $v;
            $this->modifiedColumns[] = VisitationPeer::STORE_ID;
        }

        if ($this->aStore !== null && $this->aStore->getId() !== $v) {
            $this->aStore = null;
        }


        return $this;
    } // setStoreId()

    /**
     * Set the value of [remark] column.
     *
     * @param  string $v new value
     * @return Visitation The current object (for fluent API support)
     */
    public function setRemark($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->remark !== $v) {
            $this->remark = $v;
            $this->modifiedColumns[] = VisitationPeer::REMARK;
        }


        return $this;
    } // setRemark()

    /**
     * Set the value of [status] column.
     *
     * @param  int $v new value
     * @return Visitation The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            $valueSet = VisitationPeer::getValueSet(VisitationPeer::STATUS);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[] = VisitationPeer::STATUS;
        }


        return $this;
    } // setStatus()

    /**
     * Set the value of [total] column.
     *
     * @param  string $v new value
     * @return Visitation The current object (for fluent API support)
     */
    public function setTotal($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->total !== $v) {
            $this->total = $v;
            $this->modifiedColumns[] = VisitationPeer::TOTAL;
        }


        return $this;
    } // setTotal()

    /**
     * Set the value of [photo] column.
     *
     * @param  string $v new value
     * @return Visitation The current object (for fluent API support)
     */
    public function setPhoto($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->photo !== $v) {
            $this->photo = $v;
            $this->modifiedColumns[] = VisitationPeer::PHOTO;
        }


        return $this;
    } // setPhoto()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Visitation The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = VisitationPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Visitation The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = VisitationPeer::UPDATED_AT;
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

            if ($this->total !== '0') {
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
            $this->employee_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->store_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->remark = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->status = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->total = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->photo = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->created_at = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->updated_at = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 9; // 9 = VisitationPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Visitation object", $e);
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

        if ($this->aUser !== null && $this->employee_id !== $this->aUser->getId()) {
            $this->aUser = null;
        }
        if ($this->aStore !== null && $this->store_id !== $this->aStore->getId()) {
            $this->aStore = null;
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
            $con = Propel::getConnection(VisitationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = VisitationPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->aStore = null;
            $this->collTransactions = null;

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
            $con = Propel::getConnection(VisitationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
            $deleteQuery = VisitationQuery::create()
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
            $con = Propel::getConnection(VisitationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                if (!$this->isColumnModified(VisitationPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(VisitationPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(VisitationPeer::UPDATED_AT)) {
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
                // aggregate_column behavior
                if (null !== $this->collTransactions) {
                    $this->setTotal($this->computeTotal($con));
                    if ($this->isModified()) {
                        $this->save($con);
                    }
                }

                // event behavior
                EventDispatcherProxy::trigger('model.save.post', new ModelEvent($this));
                VisitationPeer::addInstanceToPool($this);
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

            if ($this->aStore !== null) {
                if ($this->aStore->isModified() || $this->aStore->isNew()) {
                    $affectedRows += $this->aStore->save($con);
                }
                $this->setStore($this->aStore);
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

            if ($this->transactionsScheduledForDeletion !== null) {
                if (!$this->transactionsScheduledForDeletion->isEmpty()) {
                    TransactionQuery::create()
                        ->filterByPrimaryKeys($this->transactionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->transactionsScheduledForDeletion = null;
                }
            }

            if ($this->collTransactions !== null) {
                foreach ($this->collTransactions as $referrerFK) {
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

        $this->modifiedColumns[] = VisitationPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . VisitationPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(VisitationPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(VisitationPeer::EMPLOYEE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`employee_id`';
        }
        if ($this->isColumnModified(VisitationPeer::STORE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`store_id`';
        }
        if ($this->isColumnModified(VisitationPeer::REMARK)) {
            $modifiedColumns[':p' . $index++]  = '`remark`';
        }
        if ($this->isColumnModified(VisitationPeer::STATUS)) {
            $modifiedColumns[':p' . $index++]  = '`status`';
        }
        if ($this->isColumnModified(VisitationPeer::TOTAL)) {
            $modifiedColumns[':p' . $index++]  = '`total`';
        }
        if ($this->isColumnModified(VisitationPeer::PHOTO)) {
            $modifiedColumns[':p' . $index++]  = '`photo`';
        }
        if ($this->isColumnModified(VisitationPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(VisitationPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `visitation` (%s) VALUES (%s)',
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
                    case '`employee_id`':
                        $stmt->bindValue($identifier, $this->employee_id, PDO::PARAM_INT);
                        break;
                    case '`store_id`':
                        $stmt->bindValue($identifier, $this->store_id, PDO::PARAM_INT);
                        break;
                    case '`remark`':
                        $stmt->bindValue($identifier, $this->remark, PDO::PARAM_STR);
                        break;
                    case '`status`':
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_INT);
                        break;
                    case '`total`':
                        $stmt->bindValue($identifier, $this->total, PDO::PARAM_STR);
                        break;
                    case '`photo`':
                        $stmt->bindValue($identifier, $this->photo, PDO::PARAM_STR);
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

            if ($this->aStore !== null) {
                if (!$this->aStore->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aStore->getValidationFailures());
                }
            }


            if (($retval = VisitationPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collTransactions !== null) {
                    foreach ($this->collTransactions as $referrerFK) {
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
        $pos = VisitationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getEmployeeId();
                break;
            case 2:
                return $this->getStoreId();
                break;
            case 3:
                return $this->getRemark();
                break;
            case 4:
                return $this->getStatus();
                break;
            case 5:
                return $this->getTotal();
                break;
            case 6:
                return $this->getPhoto();
                break;
            case 7:
                return $this->getCreatedAt();
                break;
            case 8:
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
        if (isset($alreadyDumpedObjects['Visitation'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Visitation'][$this->getPrimaryKey()] = true;
        $keys = VisitationPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getEmployeeId(),
            $keys[2] => $this->getStoreId(),
            $keys[3] => $this->getRemark(),
            $keys[4] => $this->getStatus(),
            $keys[5] => $this->getTotal(),
            $keys[6] => $this->getPhoto(),
            $keys[7] => $this->getCreatedAt(),
            $keys[8] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aUser) {
                $result['User'] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aStore) {
                $result['Store'] = $this->aStore->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collTransactions) {
                $result['Transactions'] = $this->collTransactions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = VisitationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setEmployeeId($value);
                break;
            case 2:
                $this->setStoreId($value);
                break;
            case 3:
                $this->setRemark($value);
                break;
            case 4:
                $valueSet = VisitationPeer::getValueSet(VisitationPeer::STATUS);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setStatus($value);
                break;
            case 5:
                $this->setTotal($value);
                break;
            case 6:
                $this->setPhoto($value);
                break;
            case 7:
                $this->setCreatedAt($value);
                break;
            case 8:
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
        $keys = VisitationPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setEmployeeId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setStoreId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setRemark($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setStatus($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setTotal($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setPhoto($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setCreatedAt($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setUpdatedAt($arr[$keys[8]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(VisitationPeer::DATABASE_NAME);

        if ($this->isColumnModified(VisitationPeer::ID)) $criteria->add(VisitationPeer::ID, $this->id);
        if ($this->isColumnModified(VisitationPeer::EMPLOYEE_ID)) $criteria->add(VisitationPeer::EMPLOYEE_ID, $this->employee_id);
        if ($this->isColumnModified(VisitationPeer::STORE_ID)) $criteria->add(VisitationPeer::STORE_ID, $this->store_id);
        if ($this->isColumnModified(VisitationPeer::REMARK)) $criteria->add(VisitationPeer::REMARK, $this->remark);
        if ($this->isColumnModified(VisitationPeer::STATUS)) $criteria->add(VisitationPeer::STATUS, $this->status);
        if ($this->isColumnModified(VisitationPeer::TOTAL)) $criteria->add(VisitationPeer::TOTAL, $this->total);
        if ($this->isColumnModified(VisitationPeer::PHOTO)) $criteria->add(VisitationPeer::PHOTO, $this->photo);
        if ($this->isColumnModified(VisitationPeer::CREATED_AT)) $criteria->add(VisitationPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(VisitationPeer::UPDATED_AT)) $criteria->add(VisitationPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(VisitationPeer::DATABASE_NAME);
        $criteria->add(VisitationPeer::ID, $this->id);

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
     * @param object $copyObj An object of Visitation (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setEmployeeId($this->getEmployeeId());
        $copyObj->setStoreId($this->getStoreId());
        $copyObj->setRemark($this->getRemark());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setTotal($this->getTotal());
        $copyObj->setPhoto($this->getPhoto());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getTransactions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTransaction($relObj->copy($deepCopy));
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
     * @return Visitation Clone of current object.
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
     * @return VisitationPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new VisitationPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param                  User $v
     * @return Visitation The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(User $v = null)
    {
        if ($v === null) {
            $this->setEmployeeId(NULL);
        } else {
            $this->setEmployeeId($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addVisitation($this);
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
        if ($this->aUser === null && ($this->employee_id !== null) && $doQuery) {
            $this->aUser = UserQuery::create()->findPk($this->employee_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addVisitations($this);
             */
        }

        return $this->aUser;
    }

    /**
     * Declares an association between this object and a Store object.
     *
     * @param                  Store $v
     * @return Visitation The current object (for fluent API support)
     * @throws PropelException
     */
    public function setStore(Store $v = null)
    {
        if ($v === null) {
            $this->setStoreId(NULL);
        } else {
            $this->setStoreId($v->getId());
        }

        $this->aStore = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Store object, it will not be re-added.
        if ($v !== null) {
            $v->addVisitation($this);
        }


        return $this;
    }


    /**
     * Get the associated Store object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Store The associated Store object.
     * @throws PropelException
     */
    public function getStore(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aStore === null && ($this->store_id !== null) && $doQuery) {
            $this->aStore = StoreQuery::create()->findPk($this->store_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aStore->addVisitations($this);
             */
        }

        return $this->aStore;
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
        if ('Transaction' == $relationName) {
            $this->initTransactions();
        }
    }

    /**
     * Clears out the collTransactions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Visitation The current object (for fluent API support)
     * @see        addTransactions()
     */
    public function clearTransactions()
    {
        $this->collTransactions = null; // important to set this to null since that means it is uninitialized
        $this->collTransactionsPartial = null;

        return $this;
    }

    /**
     * reset is the collTransactions collection loaded partially
     *
     * @return void
     */
    public function resetPartialTransactions($v = true)
    {
        $this->collTransactionsPartial = $v;
    }

    /**
     * Initializes the collTransactions collection.
     *
     * By default this just sets the collTransactions collection to an empty array (like clearcollTransactions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTransactions($overrideExisting = true)
    {
        if (null !== $this->collTransactions && !$overrideExisting) {
            return;
        }
        $this->collTransactions = new PropelObjectCollection();
        $this->collTransactions->setModel('Transaction');
    }

    /**
     * Gets an array of Transaction objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Visitation is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Transaction[] List of Transaction objects
     * @throws PropelException
     */
    public function getTransactions($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collTransactionsPartial && !$this->isNew();
        if (null === $this->collTransactions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTransactions) {
                // return empty collection
                $this->initTransactions();
            } else {
                $collTransactions = TransactionQuery::create(null, $criteria)
                    ->filterByVisitation($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collTransactionsPartial && count($collTransactions)) {
                      $this->initTransactions(false);

                      foreach ($collTransactions as $obj) {
                        if (false == $this->collTransactions->contains($obj)) {
                          $this->collTransactions->append($obj);
                        }
                      }

                      $this->collTransactionsPartial = true;
                    }

                    $collTransactions->getInternalIterator()->rewind();

                    return $collTransactions;
                }

                if ($partial && $this->collTransactions) {
                    foreach ($this->collTransactions as $obj) {
                        if ($obj->isNew()) {
                            $collTransactions[] = $obj;
                        }
                    }
                }

                $this->collTransactions = $collTransactions;
                $this->collTransactionsPartial = false;
            }
        }

        return $this->collTransactions;
    }

    /**
     * Sets a collection of Transaction objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $transactions A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Visitation The current object (for fluent API support)
     */
    public function setTransactions(PropelCollection $transactions, PropelPDO $con = null)
    {
        $transactionsToDelete = $this->getTransactions(new Criteria(), $con)->diff($transactions);


        $this->transactionsScheduledForDeletion = $transactionsToDelete;

        foreach ($transactionsToDelete as $transactionRemoved) {
            $transactionRemoved->setVisitation(null);
        }

        $this->collTransactions = null;
        foreach ($transactions as $transaction) {
            $this->addTransaction($transaction);
        }

        $this->collTransactions = $transactions;
        $this->collTransactionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Transaction objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Transaction objects.
     * @throws PropelException
     */
    public function countTransactions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collTransactionsPartial && !$this->isNew();
        if (null === $this->collTransactions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTransactions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTransactions());
            }
            $query = TransactionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByVisitation($this)
                ->count($con);
        }

        return count($this->collTransactions);
    }

    /**
     * Method called to associate a Transaction object to this object
     * through the Transaction foreign key attribute.
     *
     * @param    Transaction $l Transaction
     * @return Visitation The current object (for fluent API support)
     */
    public function addTransaction(Transaction $l)
    {
        if ($this->collTransactions === null) {
            $this->initTransactions();
            $this->collTransactionsPartial = true;
        }

        if (!in_array($l, $this->collTransactions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddTransaction($l);

            if ($this->transactionsScheduledForDeletion and $this->transactionsScheduledForDeletion->contains($l)) {
                $this->transactionsScheduledForDeletion->remove($this->transactionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Transaction $transaction The transaction object to add.
     */
    protected function doAddTransaction($transaction)
    {
        $this->collTransactions[]= $transaction;
        $transaction->setVisitation($this);
    }

    /**
     * @param	Transaction $transaction The transaction object to remove.
     * @return Visitation The current object (for fluent API support)
     */
    public function removeTransaction($transaction)
    {
        if ($this->getTransactions()->contains($transaction)) {
            $this->collTransactions->remove($this->collTransactions->search($transaction));
            if (null === $this->transactionsScheduledForDeletion) {
                $this->transactionsScheduledForDeletion = clone $this->collTransactions;
                $this->transactionsScheduledForDeletion->clear();
            }
            $this->transactionsScheduledForDeletion[]= clone $transaction;
            $transaction->setVisitation(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Visitation is new, it will return
     * an empty collection; or if this Visitation has previously
     * been saved, it will retrieve related Transactions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Visitation.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Transaction[] List of Transaction objects
     */
    public function getTransactionsJoinProduct($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TransactionQuery::create(null, $criteria);
        $query->joinWith('Product', $join_behavior);

        return $this->getTransactions($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->employee_id = null;
        $this->store_id = null;
        $this->remark = null;
        $this->status = null;
        $this->total = null;
        $this->photo = null;
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
            if ($this->collTransactions) {
                foreach ($this->collTransactions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aUser instanceof Persistent) {
              $this->aUser->clearAllReferences($deep);
            }
            if ($this->aStore instanceof Persistent) {
              $this->aStore->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collTransactions instanceof PropelCollection) {
            $this->collTransactions->clearIterator();
        }
        $this->collTransactions = null;
        $this->aUser = null;
        $this->aStore = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(VisitationPeer::DEFAULT_STRING_FORMAT);
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
     * @return     Visitation The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = VisitationPeer::UPDATED_AT;

        return $this;
    }

    // aggregate_column behavior

    /**
     * Computes the value of the aggregate column total *
     * @param PropelPDO $con A connection object
     *
     * @return mixed The scalar result from the aggregate query
     */
    public function computeTotal(PropelPDO $con)
    {
        $stmt = $con->prepare('SELECT SUM(subtotal) FROM `transaction` WHERE transaction.visitation_id = :p1');
        $stmt->bindValue(':p1', $this->getId());
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * Updates the aggregate column total *
     * @param PropelPDO $con A connection object
     */
    public function updateTotal(PropelPDO $con)
    {
        $this->setTotal($this->computeTotal($con));
        $this->save($con);
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
