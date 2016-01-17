<?php

namespace PGS\CoreDomainBundle\Model\Behavior\om;

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
use PGS\CoreDomainBundle\Model\Behavior\Behavior;
use PGS\CoreDomainBundle\Model\Behavior\BehaviorI18n;
use PGS\CoreDomainBundle\Model\Behavior\BehaviorI18nQuery;
use PGS\CoreDomainBundle\Model\Behavior\BehaviorPeer;
use PGS\CoreDomainBundle\Model\Behavior\BehaviorQuery;
use PGS\CoreDomainBundle\Model\Icon\Icon;
use PGS\CoreDomainBundle\Model\Icon\IconQuery;
use PGS\CoreDomainBundle\Model\SchoolClassCourseStudentBehavior\SchoolClassCourseStudentBehavior;
use PGS\CoreDomainBundle\Model\SchoolClassCourseStudentBehavior\SchoolClassCourseStudentBehaviorQuery;

abstract class BaseBehavior extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'PGS\\CoreDomainBundle\\Model\\Behavior\\BehaviorPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        BehaviorPeer
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
     * The value for the type field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $type;

    /**
     * The value for the point field.
     * Note: this column has a database default value of: 1
     * @var        int
     */
    protected $point;

    /**
     * The value for the icon_id field.
     * @var        int
     */
    protected $icon_id;

    /**
     * The value for the user_id field.
     * @var        int
     */
    protected $user_id;

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
     * @var        Icon
     */
    protected $aIcon;

    /**
     * @var        PropelObjectCollection|SchoolClassCourseStudentBehavior[] Collection to store aggregation of SchoolClassCourseStudentBehavior objects.
     */
    protected $collSchoolClassCourseStudentBehaviors;
    protected $collSchoolClassCourseStudentBehaviorsPartial;

    /**
     * @var        PropelObjectCollection|BehaviorI18n[] Collection to store aggregation of BehaviorI18n objects.
     */
    protected $collBehaviorI18ns;
    protected $collBehaviorI18nsPartial;

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
     * @var        array[BehaviorI18n]
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
    protected $schoolClassCourseStudentBehaviorsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $behaviorI18nsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->type = 0;
        $this->point = 1;
    }

    /**
     * Initializes internal state of BaseBehavior object.
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
        $valueSet = BehaviorPeer::getValueSet(BehaviorPeer::TYPE);
        if (!isset($valueSet[$this->type])) {
            throw new PropelException('Unknown stored enum key: ' . $this->type);
        }

        return $valueSet[$this->type];
    }

    /**
     * Get the [point] column value.
     *
     * @return int
     */
    public function getPoint()
    {

        return $this->point;
    }

    /**
     * Get the [icon_id] column value.
     *
     * @return int
     */
    public function getIconId()
    {

        return $this->icon_id;
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
     * @return Behavior The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = BehaviorPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [type] column.
     *
     * @param  int $v new value
     * @return Behavior The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setType($v)
    {
        if ($v !== null) {
            $valueSet = BehaviorPeer::getValueSet(BehaviorPeer::TYPE);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->type !== $v) {
            $this->type = $v;
            $this->modifiedColumns[] = BehaviorPeer::TYPE;
        }


        return $this;
    } // setType()

    /**
     * Set the value of [point] column.
     *
     * @param  int $v new value
     * @return Behavior The current object (for fluent API support)
     */
    public function setPoint($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->point !== $v) {
            $this->point = $v;
            $this->modifiedColumns[] = BehaviorPeer::POINT;
        }


        return $this;
    } // setPoint()

    /**
     * Set the value of [icon_id] column.
     *
     * @param  int $v new value
     * @return Behavior The current object (for fluent API support)
     */
    public function setIconId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->icon_id !== $v) {
            $this->icon_id = $v;
            $this->modifiedColumns[] = BehaviorPeer::ICON_ID;
        }

        if ($this->aIcon !== null && $this->aIcon->getId() !== $v) {
            $this->aIcon = null;
        }


        return $this;
    } // setIconId()

    /**
     * Set the value of [user_id] column.
     *
     * @param  int $v new value
     * @return Behavior The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[] = BehaviorPeer::USER_ID;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }


        return $this;
    } // setUserId()

    /**
     * Set the value of [sortable_rank] column.
     *
     * @param  int $v new value
     * @return Behavior The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[] = BehaviorPeer::SORTABLE_RANK;
        }


        return $this;
    } // setSortableRank()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Behavior The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = BehaviorPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Behavior The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = BehaviorPeer::UPDATED_AT;
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
            if ($this->type !== 0) {
                return false;
            }

            if ($this->point !== 1) {
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
            $this->type = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->point = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->icon_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->user_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->sortable_rank = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->created_at = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->updated_at = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 8; // 8 = BehaviorPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Behavior object", $e);
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

        if ($this->aIcon !== null && $this->icon_id !== $this->aIcon->getId()) {
            $this->aIcon = null;
        }
        if ($this->aUser !== null && $this->user_id !== $this->aUser->getId()) {
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
            $con = Propel::getConnection(BehaviorPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = BehaviorPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->aIcon = null;
            $this->collSchoolClassCourseStudentBehaviors = null;

            $this->collBehaviorI18ns = null;

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
            $con = Propel::getConnection(BehaviorPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
            $deleteQuery = BehaviorQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // sortable behavior

            BehaviorPeer::shiftRank(-1, $this->getSortableRank() + 1, null, $con);
            BehaviorPeer::clearInstancePool();

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
            $con = Propel::getConnection(BehaviorPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // sortable behavior
            $this->processSortableQueries($con);
            // event behavior
            EventDispatcherProxy::trigger('model.save.pre', new ModelEvent($this));
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // sortable behavior
                if (!$this->isColumnModified(BehaviorPeer::RANK_COL)) {
                    $this->setSortableRank(BehaviorQuery::create()->getMaxRankArray($con) + 1);
                }

                // timestampable behavior
                if (!$this->isColumnModified(BehaviorPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(BehaviorPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(BehaviorPeer::UPDATED_AT)) {
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
                BehaviorPeer::addInstanceToPool($this);
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

            if ($this->aIcon !== null) {
                if ($this->aIcon->isModified() || $this->aIcon->isNew()) {
                    $affectedRows += $this->aIcon->save($con);
                }
                $this->setIcon($this->aIcon);
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

            if ($this->behaviorI18nsScheduledForDeletion !== null) {
                if (!$this->behaviorI18nsScheduledForDeletion->isEmpty()) {
                    BehaviorI18nQuery::create()
                        ->filterByPrimaryKeys($this->behaviorI18nsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->behaviorI18nsScheduledForDeletion = null;
                }
            }

            if ($this->collBehaviorI18ns !== null) {
                foreach ($this->collBehaviorI18ns as $referrerFK) {
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

        $this->modifiedColumns[] = BehaviorPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . BehaviorPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(BehaviorPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(BehaviorPeer::TYPE)) {
            $modifiedColumns[':p' . $index++]  = '`type`';
        }
        if ($this->isColumnModified(BehaviorPeer::POINT)) {
            $modifiedColumns[':p' . $index++]  = '`point`';
        }
        if ($this->isColumnModified(BehaviorPeer::ICON_ID)) {
            $modifiedColumns[':p' . $index++]  = '`icon_id`';
        }
        if ($this->isColumnModified(BehaviorPeer::USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`user_id`';
        }
        if ($this->isColumnModified(BehaviorPeer::SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = '`sortable_rank`';
        }
        if ($this->isColumnModified(BehaviorPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(BehaviorPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `behavior` (%s) VALUES (%s)',
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
                    case '`type`':
                        $stmt->bindValue($identifier, $this->type, PDO::PARAM_INT);
                        break;
                    case '`point`':
                        $stmt->bindValue($identifier, $this->point, PDO::PARAM_INT);
                        break;
                    case '`icon_id`':
                        $stmt->bindValue($identifier, $this->icon_id, PDO::PARAM_INT);
                        break;
                    case '`user_id`':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);
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

            if ($this->aIcon !== null) {
                if (!$this->aIcon->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aIcon->getValidationFailures());
                }
            }


            if (($retval = BehaviorPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collSchoolClassCourseStudentBehaviors !== null) {
                    foreach ($this->collSchoolClassCourseStudentBehaviors as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBehaviorI18ns !== null) {
                    foreach ($this->collBehaviorI18ns as $referrerFK) {
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
        $pos = BehaviorPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getType();
                break;
            case 2:
                return $this->getPoint();
                break;
            case 3:
                return $this->getIconId();
                break;
            case 4:
                return $this->getUserId();
                break;
            case 5:
                return $this->getSortableRank();
                break;
            case 6:
                return $this->getCreatedAt();
                break;
            case 7:
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
        if (isset($alreadyDumpedObjects['Behavior'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Behavior'][$this->getPrimaryKey()] = true;
        $keys = BehaviorPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getType(),
            $keys[2] => $this->getPoint(),
            $keys[3] => $this->getIconId(),
            $keys[4] => $this->getUserId(),
            $keys[5] => $this->getSortableRank(),
            $keys[6] => $this->getCreatedAt(),
            $keys[7] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aUser) {
                $result['User'] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aIcon) {
                $result['Icon'] = $this->aIcon->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collSchoolClassCourseStudentBehaviors) {
                $result['SchoolClassCourseStudentBehaviors'] = $this->collSchoolClassCourseStudentBehaviors->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collBehaviorI18ns) {
                $result['BehaviorI18ns'] = $this->collBehaviorI18ns->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = BehaviorPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $valueSet = BehaviorPeer::getValueSet(BehaviorPeer::TYPE);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setType($value);
                break;
            case 2:
                $this->setPoint($value);
                break;
            case 3:
                $this->setIconId($value);
                break;
            case 4:
                $this->setUserId($value);
                break;
            case 5:
                $this->setSortableRank($value);
                break;
            case 6:
                $this->setCreatedAt($value);
                break;
            case 7:
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
        $keys = BehaviorPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setType($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPoint($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setIconId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setUserId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setSortableRank($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setCreatedAt($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setUpdatedAt($arr[$keys[7]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(BehaviorPeer::DATABASE_NAME);

        if ($this->isColumnModified(BehaviorPeer::ID)) $criteria->add(BehaviorPeer::ID, $this->id);
        if ($this->isColumnModified(BehaviorPeer::TYPE)) $criteria->add(BehaviorPeer::TYPE, $this->type);
        if ($this->isColumnModified(BehaviorPeer::POINT)) $criteria->add(BehaviorPeer::POINT, $this->point);
        if ($this->isColumnModified(BehaviorPeer::ICON_ID)) $criteria->add(BehaviorPeer::ICON_ID, $this->icon_id);
        if ($this->isColumnModified(BehaviorPeer::USER_ID)) $criteria->add(BehaviorPeer::USER_ID, $this->user_id);
        if ($this->isColumnModified(BehaviorPeer::SORTABLE_RANK)) $criteria->add(BehaviorPeer::SORTABLE_RANK, $this->sortable_rank);
        if ($this->isColumnModified(BehaviorPeer::CREATED_AT)) $criteria->add(BehaviorPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(BehaviorPeer::UPDATED_AT)) $criteria->add(BehaviorPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(BehaviorPeer::DATABASE_NAME);
        $criteria->add(BehaviorPeer::ID, $this->id);

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
     * @param object $copyObj An object of Behavior (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setType($this->getType());
        $copyObj->setPoint($this->getPoint());
        $copyObj->setIconId($this->getIconId());
        $copyObj->setUserId($this->getUserId());
        $copyObj->setSortableRank($this->getSortableRank());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getSchoolClassCourseStudentBehaviors() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSchoolClassCourseStudentBehavior($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getBehaviorI18ns() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addBehaviorI18n($relObj->copy($deepCopy));
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
     * @return Behavior Clone of current object.
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
     * @return BehaviorPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new BehaviorPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param                  User $v
     * @return Behavior The current object (for fluent API support)
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
            $v->addBehavior($this);
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
                $this->aUser->addBehaviors($this);
             */
        }

        return $this->aUser;
    }

    /**
     * Declares an association between this object and a Icon object.
     *
     * @param                  Icon $v
     * @return Behavior The current object (for fluent API support)
     * @throws PropelException
     */
    public function setIcon(Icon $v = null)
    {
        if ($v === null) {
            $this->setIconId(NULL);
        } else {
            $this->setIconId($v->getId());
        }

        $this->aIcon = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Icon object, it will not be re-added.
        if ($v !== null) {
            $v->addBehavior($this);
        }


        return $this;
    }


    /**
     * Get the associated Icon object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Icon The associated Icon object.
     * @throws PropelException
     */
    public function getIcon(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aIcon === null && ($this->icon_id !== null) && $doQuery) {
            $this->aIcon = IconQuery::create()->findPk($this->icon_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aIcon->addBehaviors($this);
             */
        }

        return $this->aIcon;
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
        if ('SchoolClassCourseStudentBehavior' == $relationName) {
            $this->initSchoolClassCourseStudentBehaviors();
        }
        if ('BehaviorI18n' == $relationName) {
            $this->initBehaviorI18ns();
        }
    }

    /**
     * Clears out the collSchoolClassCourseStudentBehaviors collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Behavior The current object (for fluent API support)
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
     * If this Behavior is new, it will return
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
                    ->filterByBehavior($this)
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
     * @return Behavior The current object (for fluent API support)
     */
    public function setSchoolClassCourseStudentBehaviors(PropelCollection $schoolClassCourseStudentBehaviors, PropelPDO $con = null)
    {
        $schoolClassCourseStudentBehaviorsToDelete = $this->getSchoolClassCourseStudentBehaviors(new Criteria(), $con)->diff($schoolClassCourseStudentBehaviors);


        $this->schoolClassCourseStudentBehaviorsScheduledForDeletion = $schoolClassCourseStudentBehaviorsToDelete;

        foreach ($schoolClassCourseStudentBehaviorsToDelete as $schoolClassCourseStudentBehaviorRemoved) {
            $schoolClassCourseStudentBehaviorRemoved->setBehavior(null);
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
                ->filterByBehavior($this)
                ->count($con);
        }

        return count($this->collSchoolClassCourseStudentBehaviors);
    }

    /**
     * Method called to associate a SchoolClassCourseStudentBehavior object to this object
     * through the SchoolClassCourseStudentBehavior foreign key attribute.
     *
     * @param    SchoolClassCourseStudentBehavior $l SchoolClassCourseStudentBehavior
     * @return Behavior The current object (for fluent API support)
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
        $schoolClassCourseStudentBehavior->setBehavior($this);
    }

    /**
     * @param	SchoolClassCourseStudentBehavior $schoolClassCourseStudentBehavior The schoolClassCourseStudentBehavior object to remove.
     * @return Behavior The current object (for fluent API support)
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
            $schoolClassCourseStudentBehavior->setBehavior(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Behavior is new, it will return
     * an empty collection; or if this Behavior has previously
     * been saved, it will retrieve related SchoolClassCourseStudentBehaviors from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Behavior.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassCourseStudentBehavior[] List of SchoolClassCourseStudentBehavior objects
     */
    public function getSchoolClassCourseStudentBehaviorsJoinStudent($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassCourseStudentBehaviorQuery::create(null, $criteria);
        $query->joinWith('Student', $join_behavior);

        return $this->getSchoolClassCourseStudentBehaviors($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Behavior is new, it will return
     * an empty collection; or if this Behavior has previously
     * been saved, it will retrieve related SchoolClassCourseStudentBehaviors from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Behavior.
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
     * Clears out the collBehaviorI18ns collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Behavior The current object (for fluent API support)
     * @see        addBehaviorI18ns()
     */
    public function clearBehaviorI18ns()
    {
        $this->collBehaviorI18ns = null; // important to set this to null since that means it is uninitialized
        $this->collBehaviorI18nsPartial = null;

        return $this;
    }

    /**
     * reset is the collBehaviorI18ns collection loaded partially
     *
     * @return void
     */
    public function resetPartialBehaviorI18ns($v = true)
    {
        $this->collBehaviorI18nsPartial = $v;
    }

    /**
     * Initializes the collBehaviorI18ns collection.
     *
     * By default this just sets the collBehaviorI18ns collection to an empty array (like clearcollBehaviorI18ns());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initBehaviorI18ns($overrideExisting = true)
    {
        if (null !== $this->collBehaviorI18ns && !$overrideExisting) {
            return;
        }
        $this->collBehaviorI18ns = new PropelObjectCollection();
        $this->collBehaviorI18ns->setModel('BehaviorI18n');
    }

    /**
     * Gets an array of BehaviorI18n objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Behavior is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|BehaviorI18n[] List of BehaviorI18n objects
     * @throws PropelException
     */
    public function getBehaviorI18ns($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collBehaviorI18nsPartial && !$this->isNew();
        if (null === $this->collBehaviorI18ns || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collBehaviorI18ns) {
                // return empty collection
                $this->initBehaviorI18ns();
            } else {
                $collBehaviorI18ns = BehaviorI18nQuery::create(null, $criteria)
                    ->filterByBehavior($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collBehaviorI18nsPartial && count($collBehaviorI18ns)) {
                      $this->initBehaviorI18ns(false);

                      foreach ($collBehaviorI18ns as $obj) {
                        if (false == $this->collBehaviorI18ns->contains($obj)) {
                          $this->collBehaviorI18ns->append($obj);
                        }
                      }

                      $this->collBehaviorI18nsPartial = true;
                    }

                    $collBehaviorI18ns->getInternalIterator()->rewind();

                    return $collBehaviorI18ns;
                }

                if ($partial && $this->collBehaviorI18ns) {
                    foreach ($this->collBehaviorI18ns as $obj) {
                        if ($obj->isNew()) {
                            $collBehaviorI18ns[] = $obj;
                        }
                    }
                }

                $this->collBehaviorI18ns = $collBehaviorI18ns;
                $this->collBehaviorI18nsPartial = false;
            }
        }

        return $this->collBehaviorI18ns;
    }

    /**
     * Sets a collection of BehaviorI18n objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $behaviorI18ns A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Behavior The current object (for fluent API support)
     */
    public function setBehaviorI18ns(PropelCollection $behaviorI18ns, PropelPDO $con = null)
    {
        $behaviorI18nsToDelete = $this->getBehaviorI18ns(new Criteria(), $con)->diff($behaviorI18ns);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->behaviorI18nsScheduledForDeletion = clone $behaviorI18nsToDelete;

        foreach ($behaviorI18nsToDelete as $behaviorI18nRemoved) {
            $behaviorI18nRemoved->setBehavior(null);
        }

        $this->collBehaviorI18ns = null;
        foreach ($behaviorI18ns as $behaviorI18n) {
            $this->addBehaviorI18n($behaviorI18n);
        }

        $this->collBehaviorI18ns = $behaviorI18ns;
        $this->collBehaviorI18nsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BehaviorI18n objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related BehaviorI18n objects.
     * @throws PropelException
     */
    public function countBehaviorI18ns(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collBehaviorI18nsPartial && !$this->isNew();
        if (null === $this->collBehaviorI18ns || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collBehaviorI18ns) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getBehaviorI18ns());
            }
            $query = BehaviorI18nQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByBehavior($this)
                ->count($con);
        }

        return count($this->collBehaviorI18ns);
    }

    /**
     * Method called to associate a BehaviorI18n object to this object
     * through the BehaviorI18n foreign key attribute.
     *
     * @param    BehaviorI18n $l BehaviorI18n
     * @return Behavior The current object (for fluent API support)
     */
    public function addBehaviorI18n(BehaviorI18n $l)
    {
        if ($l && $locale = $l->getLocale()) {
            $this->setLocale($locale);
            $this->currentTranslations[$locale] = $l;
        }
        if ($this->collBehaviorI18ns === null) {
            $this->initBehaviorI18ns();
            $this->collBehaviorI18nsPartial = true;
        }

        if (!in_array($l, $this->collBehaviorI18ns->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddBehaviorI18n($l);

            if ($this->behaviorI18nsScheduledForDeletion and $this->behaviorI18nsScheduledForDeletion->contains($l)) {
                $this->behaviorI18nsScheduledForDeletion->remove($this->behaviorI18nsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	BehaviorI18n $behaviorI18n The behaviorI18n object to add.
     */
    protected function doAddBehaviorI18n($behaviorI18n)
    {
        $this->collBehaviorI18ns[]= $behaviorI18n;
        $behaviorI18n->setBehavior($this);
    }

    /**
     * @param	BehaviorI18n $behaviorI18n The behaviorI18n object to remove.
     * @return Behavior The current object (for fluent API support)
     */
    public function removeBehaviorI18n($behaviorI18n)
    {
        if ($this->getBehaviorI18ns()->contains($behaviorI18n)) {
            $this->collBehaviorI18ns->remove($this->collBehaviorI18ns->search($behaviorI18n));
            if (null === $this->behaviorI18nsScheduledForDeletion) {
                $this->behaviorI18nsScheduledForDeletion = clone $this->collBehaviorI18ns;
                $this->behaviorI18nsScheduledForDeletion->clear();
            }
            $this->behaviorI18nsScheduledForDeletion[]= clone $behaviorI18n;
            $behaviorI18n->setBehavior(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->type = null;
        $this->point = null;
        $this->icon_id = null;
        $this->user_id = null;
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
            if ($this->collSchoolClassCourseStudentBehaviors) {
                foreach ($this->collSchoolClassCourseStudentBehaviors as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collBehaviorI18ns) {
                foreach ($this->collBehaviorI18ns as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aUser instanceof Persistent) {
              $this->aUser->clearAllReferences($deep);
            }
            if ($this->aIcon instanceof Persistent) {
              $this->aIcon->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        // i18n behavior
        $this->currentLocale = 'en_US';
        $this->currentTranslations = null;

        if ($this->collSchoolClassCourseStudentBehaviors instanceof PropelCollection) {
            $this->collSchoolClassCourseStudentBehaviors->clearIterator();
        }
        $this->collSchoolClassCourseStudentBehaviors = null;
        if ($this->collBehaviorI18ns instanceof PropelCollection) {
            $this->collBehaviorI18ns->clearIterator();
        }
        $this->collBehaviorI18ns = null;
        $this->aUser = null;
        $this->aIcon = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(BehaviorPeer::DEFAULT_STRING_FORMAT);
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
     * @return    Behavior The current object (for fluent API support)
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
     * @return BehaviorI18n */
    public function getTranslation($locale = 'en_US', PropelPDO $con = null)
    {
        if (!isset($this->currentTranslations[$locale])) {
            if (null !== $this->collBehaviorI18ns) {
                foreach ($this->collBehaviorI18ns as $translation) {
                    if ($translation->getLocale() == $locale) {
                        $this->currentTranslations[$locale] = $translation;

                        return $translation;
                    }
                }
            }
            if ($this->isNew()) {
                $translation = new BehaviorI18n();
                $translation->setLocale($locale);
            } else {
                $translation = BehaviorI18nQuery::create()
                    ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                    ->findOneOrCreate($con);
                $this->currentTranslations[$locale] = $translation;
            }
            $this->addBehaviorI18n($translation);
        }

        return $this->currentTranslations[$locale];
    }

    /**
     * Remove the translation for a given locale
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     * @param     PropelPDO $con an optional connection object
     *
     * @return    Behavior The current object (for fluent API support)
     */
    public function removeTranslation($locale = 'en_US', PropelPDO $con = null)
    {
        if (!$this->isNew()) {
            BehaviorI18nQuery::create()
                ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                ->delete($con);
        }
        if (isset($this->currentTranslations[$locale])) {
            unset($this->currentTranslations[$locale]);
        }
        foreach ($this->collBehaviorI18ns as $key => $translation) {
            if ($translation->getLocale() == $locale) {
                unset($this->collBehaviorI18ns[$key]);
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
     * @return BehaviorI18n */
    public function getCurrentTranslation(PropelPDO $con = null)
    {
        return $this->getTranslation($this->getLocale(), $con);
    }


        /**
         * Get the [name] column value.
         *
         * @return string
         */
        public function getName()
        {
        return $this->getCurrentTranslation()->getName();
    }


        /**
         * Set the value of [name] column.
         *
         * @param  string $v new value
         * @return BehaviorI18n The current object (for fluent API support)
         */
        public function setName($v)
        {    $this->getCurrentTranslation()->setName($v);

        return $this;
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
         * @return BehaviorI18n The current object (for fluent API support)
         */
        public function setDescription($v)
        {    $this->getCurrentTranslation()->setDescription($v);

        return $this;
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
     * @return    Behavior
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
        return $this->getSortableRank() == BehaviorQuery::create()->getMaxRankArray($con);
    }

    /**
     * Get the next item in the list, i.e. the one for which rank is immediately higher
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Behavior
     */
    public function getNext(PropelPDO $con = null)
    {

        $query = BehaviorQuery::create();

        $query->filterByRank($this->getSortableRank() + 1);


        return $query->findOne($con);
    }

    /**
     * Get the previous item in the list, i.e. the one for which rank is immediately lower
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Behavior
     */
    public function getPrevious(PropelPDO $con = null)
    {

        $query = BehaviorQuery::create();

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
     * @return    Behavior the current object
     *
     * @throws    PropelException
     */
    public function insertAtRank($rank, PropelPDO $con = null)
    {
        $maxRank = BehaviorQuery::create()->getMaxRankArray($con);
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
     * @return    Behavior the current object
     *
     * @throws    PropelException
     */
    public function insertAtBottom(PropelPDO $con = null)
    {
        $this->setSortableRank(BehaviorQuery::create()->getMaxRankArray($con) + 1);

        return $this;
    }

    /**
     * Insert in the first rank
     * The modifications are not persisted until the object is saved.
     *
     * @return    Behavior the current object
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
     * @return    Behavior the current object
     *
     * @throws    PropelException
     */
    public function moveToRank($newRank, PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be moved. Please use insertAtRank() instead');
        }
        if ($con === null) {
            $con = Propel::getConnection(BehaviorPeer::DATABASE_NAME);
        }
        if ($newRank < 1 || $newRank > BehaviorQuery::create()->getMaxRankArray($con)) {
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
            BehaviorPeer::shiftRank($delta, min($oldRank, $newRank), max($oldRank, $newRank), $con);

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
     * @param     Behavior $object
     * @param     PropelPDO $con optional connection
     *
     * @return    Behavior the current object
     *
     * @throws Exception if the database cannot execute the two updates
     */
    public function swapWith($object, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(BehaviorPeer::DATABASE_NAME);
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
     * @return    Behavior the current object
     */
    public function moveUp(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(BehaviorPeer::DATABASE_NAME);
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
     * @return    Behavior the current object
     */
    public function moveDown(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(BehaviorPeer::DATABASE_NAME);
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
     * @return    Behavior the current object
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
            $con = Propel::getConnection(BehaviorPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $bottom = BehaviorQuery::create()->getMaxRankArray($con);
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
     * @return    Behavior the current object
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
     * @return     Behavior The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = BehaviorPeer::UPDATED_AT;

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
