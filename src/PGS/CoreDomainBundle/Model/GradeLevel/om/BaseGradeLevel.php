<?php

namespace PGS\CoreDomainBundle\Model\GradeLevel\om;

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
use PGS\CoreDomainBundle\Model\Course\Course;
use PGS\CoreDomainBundle\Model\Course\CourseQuery;
use PGS\CoreDomainBundle\Model\Grade\Grade;
use PGS\CoreDomainBundle\Model\Grade\GradeQuery;
use PGS\CoreDomainBundle\Model\GradeLevel\GradeLevel;
use PGS\CoreDomainBundle\Model\GradeLevel\GradeLevelPeer;
use PGS\CoreDomainBundle\Model\GradeLevel\GradeLevelQuery;
use PGS\CoreDomainBundle\Model\Level\Level;
use PGS\CoreDomainBundle\Model\Level\LevelQuery;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClass;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClassQuery;
use PGS\CoreDomainBundle\Model\SchoolGradeLevel\SchoolGradeLevel;
use PGS\CoreDomainBundle\Model\SchoolGradeLevel\SchoolGradeLevelQuery;

abstract class BaseGradeLevel extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'PGS\\CoreDomainBundle\\Model\\GradeLevel\\GradeLevelPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        GradeLevelPeer
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
     * The value for the level_id field.
     * @var        int
     */
    protected $level_id;

    /**
     * The value for the grade_id field.
     * @var        int
     */
    protected $grade_id;

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
     * @var        Grade
     */
    protected $aGrade;

    /**
     * @var        Level
     */
    protected $aLevel;

    /**
     * @var        PropelObjectCollection|Course[] Collection to store aggregation of Course objects.
     */
    protected $collCourses;
    protected $collCoursesPartial;

    /**
     * @var        PropelObjectCollection|SchoolClass[] Collection to store aggregation of SchoolClass objects.
     */
    protected $collSchoolClasses;
    protected $collSchoolClassesPartial;

    /**
     * @var        PropelObjectCollection|SchoolGradeLevel[] Collection to store aggregation of SchoolGradeLevel objects.
     */
    protected $collSchoolGradeLevels;
    protected $collSchoolGradeLevelsPartial;

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
    protected $coursesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $schoolClassesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $schoolGradeLevelsScheduledForDeletion = null;

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    public function __construct(){
        parent::__construct();
        EventDispatcherProxy::trigger(array('construct','model.construct'), new ModelEvent($this));
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
     * Get the [grade_id] column value.
     *
     * @return int
     */
    public function getGradeId()
    {

        return $this->grade_id;
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
     * @return GradeLevel The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = GradeLevelPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [level_id] column.
     *
     * @param  int $v new value
     * @return GradeLevel The current object (for fluent API support)
     */
    public function setLevelId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->level_id !== $v) {
            $this->level_id = $v;
            $this->modifiedColumns[] = GradeLevelPeer::LEVEL_ID;
        }

        if ($this->aLevel !== null && $this->aLevel->getId() !== $v) {
            $this->aLevel = null;
        }


        return $this;
    } // setLevelId()

    /**
     * Set the value of [grade_id] column.
     *
     * @param  int $v new value
     * @return GradeLevel The current object (for fluent API support)
     */
    public function setGradeId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->grade_id !== $v) {
            $this->grade_id = $v;
            $this->modifiedColumns[] = GradeLevelPeer::GRADE_ID;
        }

        if ($this->aGrade !== null && $this->aGrade->getId() !== $v) {
            $this->aGrade = null;
        }


        return $this;
    } // setGradeId()

    /**
     * Set the value of [sortable_rank] column.
     *
     * @param  int $v new value
     * @return GradeLevel The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[] = GradeLevelPeer::SORTABLE_RANK;
        }


        return $this;
    } // setSortableRank()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return GradeLevel The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = GradeLevelPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return GradeLevel The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = GradeLevelPeer::UPDATED_AT;
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
            $this->level_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->grade_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->sortable_rank = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->created_at = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->updated_at = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 6; // 6 = GradeLevelPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating GradeLevel object", $e);
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
        if ($this->aGrade !== null && $this->grade_id !== $this->aGrade->getId()) {
            $this->aGrade = null;
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
            $con = Propel::getConnection(GradeLevelPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = GradeLevelPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aGrade = null;
            $this->aLevel = null;
            $this->collCourses = null;

            $this->collSchoolClasses = null;

            $this->collSchoolGradeLevels = null;

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
            $con = Propel::getConnection(GradeLevelPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
            $deleteQuery = GradeLevelQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // sortable behavior

            GradeLevelPeer::shiftRank(-1, $this->getSortableRank() + 1, null, $con);
            GradeLevelPeer::clearInstancePool();

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
            $con = Propel::getConnection(GradeLevelPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                if (!$this->isColumnModified(GradeLevelPeer::RANK_COL)) {
                    $this->setSortableRank(GradeLevelQuery::create()->getMaxRankArray($con) + 1);
                }

                // timestampable behavior
                if (!$this->isColumnModified(GradeLevelPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(GradeLevelPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(GradeLevelPeer::UPDATED_AT)) {
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
                GradeLevelPeer::addInstanceToPool($this);
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

            if ($this->aGrade !== null) {
                if ($this->aGrade->isModified() || $this->aGrade->isNew()) {
                    $affectedRows += $this->aGrade->save($con);
                }
                $this->setGrade($this->aGrade);
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

            if ($this->schoolClassesScheduledForDeletion !== null) {
                if (!$this->schoolClassesScheduledForDeletion->isEmpty()) {
                    SchoolClassQuery::create()
                        ->filterByPrimaryKeys($this->schoolClassesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->schoolClassesScheduledForDeletion = null;
                }
            }

            if ($this->collSchoolClasses !== null) {
                foreach ($this->collSchoolClasses as $referrerFK) {
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

        $this->modifiedColumns[] = GradeLevelPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . GradeLevelPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(GradeLevelPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(GradeLevelPeer::LEVEL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`level_id`';
        }
        if ($this->isColumnModified(GradeLevelPeer::GRADE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`grade_id`';
        }
        if ($this->isColumnModified(GradeLevelPeer::SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = '`sortable_rank`';
        }
        if ($this->isColumnModified(GradeLevelPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(GradeLevelPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `grade_level` (%s) VALUES (%s)',
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
                    case '`level_id`':
                        $stmt->bindValue($identifier, $this->level_id, PDO::PARAM_INT);
                        break;
                    case '`grade_id`':
                        $stmt->bindValue($identifier, $this->grade_id, PDO::PARAM_INT);
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

            if ($this->aGrade !== null) {
                if (!$this->aGrade->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aGrade->getValidationFailures());
                }
            }

            if ($this->aLevel !== null) {
                if (!$this->aLevel->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aLevel->getValidationFailures());
                }
            }


            if (($retval = GradeLevelPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collCourses !== null) {
                    foreach ($this->collCourses as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSchoolClasses !== null) {
                    foreach ($this->collSchoolClasses as $referrerFK) {
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
        $pos = GradeLevelPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getLevelId();
                break;
            case 2:
                return $this->getGradeId();
                break;
            case 3:
                return $this->getSortableRank();
                break;
            case 4:
                return $this->getCreatedAt();
                break;
            case 5:
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
        if (isset($alreadyDumpedObjects['GradeLevel'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['GradeLevel'][$this->getPrimaryKey()] = true;
        $keys = GradeLevelPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getLevelId(),
            $keys[2] => $this->getGradeId(),
            $keys[3] => $this->getSortableRank(),
            $keys[4] => $this->getCreatedAt(),
            $keys[5] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aGrade) {
                $result['Grade'] = $this->aGrade->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aLevel) {
                $result['Level'] = $this->aLevel->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collCourses) {
                $result['Courses'] = $this->collCourses->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSchoolClasses) {
                $result['SchoolClasses'] = $this->collSchoolClasses->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSchoolGradeLevels) {
                $result['SchoolGradeLevels'] = $this->collSchoolGradeLevels->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = GradeLevelPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setLevelId($value);
                break;
            case 2:
                $this->setGradeId($value);
                break;
            case 3:
                $this->setSortableRank($value);
                break;
            case 4:
                $this->setCreatedAt($value);
                break;
            case 5:
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
        $keys = GradeLevelPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setLevelId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setGradeId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setSortableRank($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setCreatedAt($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setUpdatedAt($arr[$keys[5]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(GradeLevelPeer::DATABASE_NAME);

        if ($this->isColumnModified(GradeLevelPeer::ID)) $criteria->add(GradeLevelPeer::ID, $this->id);
        if ($this->isColumnModified(GradeLevelPeer::LEVEL_ID)) $criteria->add(GradeLevelPeer::LEVEL_ID, $this->level_id);
        if ($this->isColumnModified(GradeLevelPeer::GRADE_ID)) $criteria->add(GradeLevelPeer::GRADE_ID, $this->grade_id);
        if ($this->isColumnModified(GradeLevelPeer::SORTABLE_RANK)) $criteria->add(GradeLevelPeer::SORTABLE_RANK, $this->sortable_rank);
        if ($this->isColumnModified(GradeLevelPeer::CREATED_AT)) $criteria->add(GradeLevelPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(GradeLevelPeer::UPDATED_AT)) $criteria->add(GradeLevelPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(GradeLevelPeer::DATABASE_NAME);
        $criteria->add(GradeLevelPeer::ID, $this->id);

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
     * @param object $copyObj An object of GradeLevel (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setLevelId($this->getLevelId());
        $copyObj->setGradeId($this->getGradeId());
        $copyObj->setSortableRank($this->getSortableRank());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getCourses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCourse($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSchoolClasses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSchoolClass($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSchoolGradeLevels() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSchoolGradeLevel($relObj->copy($deepCopy));
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
     * @return GradeLevel Clone of current object.
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
     * @return GradeLevelPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new GradeLevelPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Grade object.
     *
     * @param                  Grade $v
     * @return GradeLevel The current object (for fluent API support)
     * @throws PropelException
     */
    public function setGrade(Grade $v = null)
    {
        if ($v === null) {
            $this->setGradeId(NULL);
        } else {
            $this->setGradeId($v->getId());
        }

        $this->aGrade = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Grade object, it will not be re-added.
        if ($v !== null) {
            $v->addGradeLevel($this);
        }


        return $this;
    }


    /**
     * Get the associated Grade object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Grade The associated Grade object.
     * @throws PropelException
     */
    public function getGrade(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aGrade === null && ($this->grade_id !== null) && $doQuery) {
            $this->aGrade = GradeQuery::create()->findPk($this->grade_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aGrade->addGradeLevels($this);
             */
        }

        return $this->aGrade;
    }

    /**
     * Declares an association between this object and a Level object.
     *
     * @param                  Level $v
     * @return GradeLevel The current object (for fluent API support)
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
            $v->addGradeLevel($this);
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
                $this->aLevel->addGradeLevels($this);
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
        if ('Course' == $relationName) {
            $this->initCourses();
        }
        if ('SchoolClass' == $relationName) {
            $this->initSchoolClasses();
        }
        if ('SchoolGradeLevel' == $relationName) {
            $this->initSchoolGradeLevels();
        }
    }

    /**
     * Clears out the collCourses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return GradeLevel The current object (for fluent API support)
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
     * If this GradeLevel is new, it will return
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
                    ->filterByGradeLevel($this)
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
     * @return GradeLevel The current object (for fluent API support)
     */
    public function setCourses(PropelCollection $courses, PropelPDO $con = null)
    {
        $coursesToDelete = $this->getCourses(new Criteria(), $con)->diff($courses);


        $this->coursesScheduledForDeletion = $coursesToDelete;

        foreach ($coursesToDelete as $courseRemoved) {
            $courseRemoved->setGradeLevel(null);
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
                ->filterByGradeLevel($this)
                ->count($con);
        }

        return count($this->collCourses);
    }

    /**
     * Method called to associate a Course object to this object
     * through the Course foreign key attribute.
     *
     * @param    Course $l Course
     * @return GradeLevel The current object (for fluent API support)
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
        $course->setGradeLevel($this);
    }

    /**
     * @param	Course $course The course object to remove.
     * @return GradeLevel The current object (for fluent API support)
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
            $course->setGradeLevel(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this GradeLevel is new, it will return
     * an empty collection; or if this GradeLevel has previously
     * been saved, it will retrieve related Courses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in GradeLevel.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Course[] List of Course objects
     */
    public function getCoursesJoinSchool($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CourseQuery::create(null, $criteria);
        $query->joinWith('School', $join_behavior);

        return $this->getCourses($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this GradeLevel is new, it will return
     * an empty collection; or if this GradeLevel has previously
     * been saved, it will retrieve related Courses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in GradeLevel.
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
     * Clears out the collSchoolClasses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return GradeLevel The current object (for fluent API support)
     * @see        addSchoolClasses()
     */
    public function clearSchoolClasses()
    {
        $this->collSchoolClasses = null; // important to set this to null since that means it is uninitialized
        $this->collSchoolClassesPartial = null;

        return $this;
    }

    /**
     * reset is the collSchoolClasses collection loaded partially
     *
     * @return void
     */
    public function resetPartialSchoolClasses($v = true)
    {
        $this->collSchoolClassesPartial = $v;
    }

    /**
     * Initializes the collSchoolClasses collection.
     *
     * By default this just sets the collSchoolClasses collection to an empty array (like clearcollSchoolClasses());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSchoolClasses($overrideExisting = true)
    {
        if (null !== $this->collSchoolClasses && !$overrideExisting) {
            return;
        }
        $this->collSchoolClasses = new PropelObjectCollection();
        $this->collSchoolClasses->setModel('SchoolClass');
    }

    /**
     * Gets an array of SchoolClass objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this GradeLevel is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|SchoolClass[] List of SchoolClass objects
     * @throws PropelException
     */
    public function getSchoolClasses($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSchoolClassesPartial && !$this->isNew();
        if (null === $this->collSchoolClasses || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSchoolClasses) {
                // return empty collection
                $this->initSchoolClasses();
            } else {
                $collSchoolClasses = SchoolClassQuery::create(null, $criteria)
                    ->filterByGradeLevel($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSchoolClassesPartial && count($collSchoolClasses)) {
                      $this->initSchoolClasses(false);

                      foreach ($collSchoolClasses as $obj) {
                        if (false == $this->collSchoolClasses->contains($obj)) {
                          $this->collSchoolClasses->append($obj);
                        }
                      }

                      $this->collSchoolClassesPartial = true;
                    }

                    $collSchoolClasses->getInternalIterator()->rewind();

                    return $collSchoolClasses;
                }

                if ($partial && $this->collSchoolClasses) {
                    foreach ($this->collSchoolClasses as $obj) {
                        if ($obj->isNew()) {
                            $collSchoolClasses[] = $obj;
                        }
                    }
                }

                $this->collSchoolClasses = $collSchoolClasses;
                $this->collSchoolClassesPartial = false;
            }
        }

        return $this->collSchoolClasses;
    }

    /**
     * Sets a collection of SchoolClass objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $schoolClasses A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return GradeLevel The current object (for fluent API support)
     */
    public function setSchoolClasses(PropelCollection $schoolClasses, PropelPDO $con = null)
    {
        $schoolClassesToDelete = $this->getSchoolClasses(new Criteria(), $con)->diff($schoolClasses);


        $this->schoolClassesScheduledForDeletion = $schoolClassesToDelete;

        foreach ($schoolClassesToDelete as $schoolClassRemoved) {
            $schoolClassRemoved->setGradeLevel(null);
        }

        $this->collSchoolClasses = null;
        foreach ($schoolClasses as $schoolClass) {
            $this->addSchoolClass($schoolClass);
        }

        $this->collSchoolClasses = $schoolClasses;
        $this->collSchoolClassesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SchoolClass objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related SchoolClass objects.
     * @throws PropelException
     */
    public function countSchoolClasses(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSchoolClassesPartial && !$this->isNew();
        if (null === $this->collSchoolClasses || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSchoolClasses) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSchoolClasses());
            }
            $query = SchoolClassQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGradeLevel($this)
                ->count($con);
        }

        return count($this->collSchoolClasses);
    }

    /**
     * Method called to associate a SchoolClass object to this object
     * through the SchoolClass foreign key attribute.
     *
     * @param    SchoolClass $l SchoolClass
     * @return GradeLevel The current object (for fluent API support)
     */
    public function addSchoolClass(SchoolClass $l)
    {
        if ($this->collSchoolClasses === null) {
            $this->initSchoolClasses();
            $this->collSchoolClassesPartial = true;
        }

        if (!in_array($l, $this->collSchoolClasses->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSchoolClass($l);

            if ($this->schoolClassesScheduledForDeletion and $this->schoolClassesScheduledForDeletion->contains($l)) {
                $this->schoolClassesScheduledForDeletion->remove($this->schoolClassesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	SchoolClass $schoolClass The schoolClass object to add.
     */
    protected function doAddSchoolClass($schoolClass)
    {
        $this->collSchoolClasses[]= $schoolClass;
        $schoolClass->setGradeLevel($this);
    }

    /**
     * @param	SchoolClass $schoolClass The schoolClass object to remove.
     * @return GradeLevel The current object (for fluent API support)
     */
    public function removeSchoolClass($schoolClass)
    {
        if ($this->getSchoolClasses()->contains($schoolClass)) {
            $this->collSchoolClasses->remove($this->collSchoolClasses->search($schoolClass));
            if (null === $this->schoolClassesScheduledForDeletion) {
                $this->schoolClassesScheduledForDeletion = clone $this->collSchoolClasses;
                $this->schoolClassesScheduledForDeletion->clear();
            }
            $this->schoolClassesScheduledForDeletion[]= clone $schoolClass;
            $schoolClass->setGradeLevel(null);
        }

        return $this;
    }

    /**
     * Clears out the collSchoolGradeLevels collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return GradeLevel The current object (for fluent API support)
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
     * If this GradeLevel is new, it will return
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
                    ->filterByGradeLevel($this)
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
     * @return GradeLevel The current object (for fluent API support)
     */
    public function setSchoolGradeLevels(PropelCollection $schoolGradeLevels, PropelPDO $con = null)
    {
        $schoolGradeLevelsToDelete = $this->getSchoolGradeLevels(new Criteria(), $con)->diff($schoolGradeLevels);


        $this->schoolGradeLevelsScheduledForDeletion = $schoolGradeLevelsToDelete;

        foreach ($schoolGradeLevelsToDelete as $schoolGradeLevelRemoved) {
            $schoolGradeLevelRemoved->setGradeLevel(null);
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
                ->filterByGradeLevel($this)
                ->count($con);
        }

        return count($this->collSchoolGradeLevels);
    }

    /**
     * Method called to associate a SchoolGradeLevel object to this object
     * through the SchoolGradeLevel foreign key attribute.
     *
     * @param    SchoolGradeLevel $l SchoolGradeLevel
     * @return GradeLevel The current object (for fluent API support)
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
        $schoolGradeLevel->setGradeLevel($this);
    }

    /**
     * @param	SchoolGradeLevel $schoolGradeLevel The schoolGradeLevel object to remove.
     * @return GradeLevel The current object (for fluent API support)
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
            $schoolGradeLevel->setGradeLevel(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this GradeLevel is new, it will return
     * an empty collection; or if this GradeLevel has previously
     * been saved, it will retrieve related SchoolGradeLevels from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in GradeLevel.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolGradeLevel[] List of SchoolGradeLevel objects
     */
    public function getSchoolGradeLevelsJoinSchool($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolGradeLevelQuery::create(null, $criteria);
        $query->joinWith('School', $join_behavior);

        return $this->getSchoolGradeLevels($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->level_id = null;
        $this->grade_id = null;
        $this->sortable_rank = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
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
            if ($this->collCourses) {
                foreach ($this->collCourses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSchoolClasses) {
                foreach ($this->collSchoolClasses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSchoolGradeLevels) {
                foreach ($this->collSchoolGradeLevels as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aGrade instanceof Persistent) {
              $this->aGrade->clearAllReferences($deep);
            }
            if ($this->aLevel instanceof Persistent) {
              $this->aLevel->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collCourses instanceof PropelCollection) {
            $this->collCourses->clearIterator();
        }
        $this->collCourses = null;
        if ($this->collSchoolClasses instanceof PropelCollection) {
            $this->collSchoolClasses->clearIterator();
        }
        $this->collSchoolClasses = null;
        if ($this->collSchoolGradeLevels instanceof PropelCollection) {
            $this->collSchoolGradeLevels->clearIterator();
        }
        $this->collSchoolGradeLevels = null;
        $this->aGrade = null;
        $this->aLevel = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(GradeLevelPeer::DEFAULT_STRING_FORMAT);
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
     * @return    GradeLevel
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
        return $this->getSortableRank() == GradeLevelQuery::create()->getMaxRankArray($con);
    }

    /**
     * Get the next item in the list, i.e. the one for which rank is immediately higher
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    GradeLevel
     */
    public function getNext(PropelPDO $con = null)
    {

        $query = GradeLevelQuery::create();

        $query->filterByRank($this->getSortableRank() + 1);


        return $query->findOne($con);
    }

    /**
     * Get the previous item in the list, i.e. the one for which rank is immediately lower
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    GradeLevel
     */
    public function getPrevious(PropelPDO $con = null)
    {

        $query = GradeLevelQuery::create();

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
     * @return    GradeLevel the current object
     *
     * @throws    PropelException
     */
    public function insertAtRank($rank, PropelPDO $con = null)
    {
        $maxRank = GradeLevelQuery::create()->getMaxRankArray($con);
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
     * @return    GradeLevel the current object
     *
     * @throws    PropelException
     */
    public function insertAtBottom(PropelPDO $con = null)
    {
        $this->setSortableRank(GradeLevelQuery::create()->getMaxRankArray($con) + 1);

        return $this;
    }

    /**
     * Insert in the first rank
     * The modifications are not persisted until the object is saved.
     *
     * @return    GradeLevel the current object
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
     * @return    GradeLevel the current object
     *
     * @throws    PropelException
     */
    public function moveToRank($newRank, PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be moved. Please use insertAtRank() instead');
        }
        if ($con === null) {
            $con = Propel::getConnection(GradeLevelPeer::DATABASE_NAME);
        }
        if ($newRank < 1 || $newRank > GradeLevelQuery::create()->getMaxRankArray($con)) {
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
            GradeLevelPeer::shiftRank($delta, min($oldRank, $newRank), max($oldRank, $newRank), $con);

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
     * @param     GradeLevel $object
     * @param     PropelPDO $con optional connection
     *
     * @return    GradeLevel the current object
     *
     * @throws Exception if the database cannot execute the two updates
     */
    public function swapWith($object, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(GradeLevelPeer::DATABASE_NAME);
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
     * @return    GradeLevel the current object
     */
    public function moveUp(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(GradeLevelPeer::DATABASE_NAME);
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
     * @return    GradeLevel the current object
     */
    public function moveDown(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(GradeLevelPeer::DATABASE_NAME);
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
     * @return    GradeLevel the current object
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
            $con = Propel::getConnection(GradeLevelPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $bottom = GradeLevelQuery::create()->getMaxRankArray($con);
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
     * @return    GradeLevel the current object
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
     * @return     GradeLevel The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = GradeLevelPeer::UPDATED_AT;

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
