<?php

namespace PGS\CoreDomainBundle\Model\Score\om;

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
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourse;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourseQuery;
use PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudent;
use PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudentQuery;
use PGS\CoreDomainBundle\Model\Score\Score;
use PGS\CoreDomainBundle\Model\Score\ScorePeer;
use PGS\CoreDomainBundle\Model\Score\ScoreQuery;
use PGS\CoreDomainBundle\Model\StudentReport\StudentReport;
use PGS\CoreDomainBundle\Model\StudentReport\StudentReportQuery;

abstract class BaseScore extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'PGS\\CoreDomainBundle\\Model\\Score\\ScorePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        ScorePeer
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
     * The value for the homework field.
     * @var        string
     */
    protected $homework;

    /**
     * The value for the daily_exam field.
     * @var        string
     */
    protected $daily_exam;

    /**
     * The value for the mid_exam field.
     * @var        string
     */
    protected $mid_exam;

    /**
     * The value for the final_exam field.
     * @var        string
     */
    protected $final_exam;

    /**
     * The value for the school_class_student_id field.
     * @var        int
     */
    protected $school_class_student_id;

    /**
     * The value for the school_class_course_id field.
     * @var        int
     */
    protected $school_class_course_id;

    /**
     * The value for the student_id field.
     * @var        int
     */
    protected $student_id;

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
     * @var        SchoolClassStudent
     */
    protected $aSchoolClassStudent;

    /**
     * @var        SchoolClassCourse
     */
    protected $aSchoolClassCourse;

    /**
     * @var        PropelObjectCollection|StudentReport[] Collection to store aggregation of StudentReport objects.
     */
    protected $collStudentReports;
    protected $collStudentReportsPartial;

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
    protected $studentReportsScheduledForDeletion = null;

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
     * Get the [homework] column value.
     *
     * @return string
     */
    public function getHomework()
    {

        return $this->homework;
    }

    /**
     * Get the [daily_exam] column value.
     *
     * @return string
     */
    public function getDailyExam()
    {

        return $this->daily_exam;
    }

    /**
     * Get the [mid_exam] column value.
     *
     * @return string
     */
    public function getMidExam()
    {

        return $this->mid_exam;
    }

    /**
     * Get the [final_exam] column value.
     *
     * @return string
     */
    public function getFinalExam()
    {

        return $this->final_exam;
    }

    /**
     * Get the [school_class_student_id] column value.
     *
     * @return int
     */
    public function getSchoolClassStudentId()
    {

        return $this->school_class_student_id;
    }

    /**
     * Get the [school_class_course_id] column value.
     *
     * @return int
     */
    public function getSchoolClassCourseId()
    {

        return $this->school_class_course_id;
    }

    /**
     * Get the [student_id] column value.
     *
     * @return int
     */
    public function getStudentId()
    {

        return $this->student_id;
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
     * @return Score The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = ScorePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [homework] column.
     *
     * @param  string $v new value
     * @return Score The current object (for fluent API support)
     */
    public function setHomework($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->homework !== $v) {
            $this->homework = $v;
            $this->modifiedColumns[] = ScorePeer::HOMEWORK;
        }


        return $this;
    } // setHomework()

    /**
     * Set the value of [daily_exam] column.
     *
     * @param  string $v new value
     * @return Score The current object (for fluent API support)
     */
    public function setDailyExam($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->daily_exam !== $v) {
            $this->daily_exam = $v;
            $this->modifiedColumns[] = ScorePeer::DAILY_EXAM;
        }


        return $this;
    } // setDailyExam()

    /**
     * Set the value of [mid_exam] column.
     *
     * @param  string $v new value
     * @return Score The current object (for fluent API support)
     */
    public function setMidExam($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->mid_exam !== $v) {
            $this->mid_exam = $v;
            $this->modifiedColumns[] = ScorePeer::MID_EXAM;
        }


        return $this;
    } // setMidExam()

    /**
     * Set the value of [final_exam] column.
     *
     * @param  string $v new value
     * @return Score The current object (for fluent API support)
     */
    public function setFinalExam($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->final_exam !== $v) {
            $this->final_exam = $v;
            $this->modifiedColumns[] = ScorePeer::FINAL_EXAM;
        }


        return $this;
    } // setFinalExam()

    /**
     * Set the value of [school_class_student_id] column.
     *
     * @param  int $v new value
     * @return Score The current object (for fluent API support)
     */
    public function setSchoolClassStudentId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->school_class_student_id !== $v) {
            $this->school_class_student_id = $v;
            $this->modifiedColumns[] = ScorePeer::SCHOOL_CLASS_STUDENT_ID;
        }

        if ($this->aSchoolClassStudent !== null && $this->aSchoolClassStudent->getId() !== $v) {
            $this->aSchoolClassStudent = null;
        }


        return $this;
    } // setSchoolClassStudentId()

    /**
     * Set the value of [school_class_course_id] column.
     *
     * @param  int $v new value
     * @return Score The current object (for fluent API support)
     */
    public function setSchoolClassCourseId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->school_class_course_id !== $v) {
            $this->school_class_course_id = $v;
            $this->modifiedColumns[] = ScorePeer::SCHOOL_CLASS_COURSE_ID;
        }

        if ($this->aSchoolClassCourse !== null && $this->aSchoolClassCourse->getId() !== $v) {
            $this->aSchoolClassCourse = null;
        }


        return $this;
    } // setSchoolClassCourseId()

    /**
     * Set the value of [student_id] column.
     *
     * @param  int $v new value
     * @return Score The current object (for fluent API support)
     */
    public function setStudentId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->student_id !== $v) {
            $this->student_id = $v;
            $this->modifiedColumns[] = ScorePeer::STUDENT_ID;
        }


        return $this;
    } // setStudentId()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Score The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = ScorePeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Score The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = ScorePeer::UPDATED_AT;
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
            $this->homework = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->daily_exam = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->mid_exam = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->final_exam = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->school_class_student_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->school_class_course_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->student_id = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->created_at = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->updated_at = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 10; // 10 = ScorePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Score object", $e);
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

        if ($this->aSchoolClassStudent !== null && $this->school_class_student_id !== $this->aSchoolClassStudent->getId()) {
            $this->aSchoolClassStudent = null;
        }
        if ($this->aSchoolClassCourse !== null && $this->school_class_course_id !== $this->aSchoolClassCourse->getId()) {
            $this->aSchoolClassCourse = null;
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
            $con = Propel::getConnection(ScorePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = ScorePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aSchoolClassStudent = null;
            $this->aSchoolClassCourse = null;
            $this->collStudentReports = null;

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
            $con = Propel::getConnection(ScorePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
            $deleteQuery = ScoreQuery::create()
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
            $con = Propel::getConnection(ScorePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                if (!$this->isColumnModified(ScorePeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(ScorePeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(ScorePeer::UPDATED_AT)) {
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
                ScorePeer::addInstanceToPool($this);
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

            if ($this->aSchoolClassStudent !== null) {
                if ($this->aSchoolClassStudent->isModified() || $this->aSchoolClassStudent->isNew()) {
                    $affectedRows += $this->aSchoolClassStudent->save($con);
                }
                $this->setSchoolClassStudent($this->aSchoolClassStudent);
            }

            if ($this->aSchoolClassCourse !== null) {
                if ($this->aSchoolClassCourse->isModified() || $this->aSchoolClassCourse->isNew()) {
                    $affectedRows += $this->aSchoolClassCourse->save($con);
                }
                $this->setSchoolClassCourse($this->aSchoolClassCourse);
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

            if ($this->studentReportsScheduledForDeletion !== null) {
                if (!$this->studentReportsScheduledForDeletion->isEmpty()) {
                    StudentReportQuery::create()
                        ->filterByPrimaryKeys($this->studentReportsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->studentReportsScheduledForDeletion = null;
                }
            }

            if ($this->collStudentReports !== null) {
                foreach ($this->collStudentReports as $referrerFK) {
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

        $this->modifiedColumns[] = ScorePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ScorePeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ScorePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(ScorePeer::HOMEWORK)) {
            $modifiedColumns[':p' . $index++]  = '`homework`';
        }
        if ($this->isColumnModified(ScorePeer::DAILY_EXAM)) {
            $modifiedColumns[':p' . $index++]  = '`daily_exam`';
        }
        if ($this->isColumnModified(ScorePeer::MID_EXAM)) {
            $modifiedColumns[':p' . $index++]  = '`mid_exam`';
        }
        if ($this->isColumnModified(ScorePeer::FINAL_EXAM)) {
            $modifiedColumns[':p' . $index++]  = '`final_exam`';
        }
        if ($this->isColumnModified(ScorePeer::SCHOOL_CLASS_STUDENT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`school_class_student_id`';
        }
        if ($this->isColumnModified(ScorePeer::SCHOOL_CLASS_COURSE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`school_class_course_id`';
        }
        if ($this->isColumnModified(ScorePeer::STUDENT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`student_id`';
        }
        if ($this->isColumnModified(ScorePeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(ScorePeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `score` (%s) VALUES (%s)',
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
                    case '`homework`':
                        $stmt->bindValue($identifier, $this->homework, PDO::PARAM_STR);
                        break;
                    case '`daily_exam`':
                        $stmt->bindValue($identifier, $this->daily_exam, PDO::PARAM_STR);
                        break;
                    case '`mid_exam`':
                        $stmt->bindValue($identifier, $this->mid_exam, PDO::PARAM_STR);
                        break;
                    case '`final_exam`':
                        $stmt->bindValue($identifier, $this->final_exam, PDO::PARAM_STR);
                        break;
                    case '`school_class_student_id`':
                        $stmt->bindValue($identifier, $this->school_class_student_id, PDO::PARAM_INT);
                        break;
                    case '`school_class_course_id`':
                        $stmt->bindValue($identifier, $this->school_class_course_id, PDO::PARAM_INT);
                        break;
                    case '`student_id`':
                        $stmt->bindValue($identifier, $this->student_id, PDO::PARAM_INT);
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

            if ($this->aSchoolClassStudent !== null) {
                if (!$this->aSchoolClassStudent->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aSchoolClassStudent->getValidationFailures());
                }
            }

            if ($this->aSchoolClassCourse !== null) {
                if (!$this->aSchoolClassCourse->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aSchoolClassCourse->getValidationFailures());
                }
            }


            if (($retval = ScorePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collStudentReports !== null) {
                    foreach ($this->collStudentReports as $referrerFK) {
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
        $pos = ScorePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getHomework();
                break;
            case 2:
                return $this->getDailyExam();
                break;
            case 3:
                return $this->getMidExam();
                break;
            case 4:
                return $this->getFinalExam();
                break;
            case 5:
                return $this->getSchoolClassStudentId();
                break;
            case 6:
                return $this->getSchoolClassCourseId();
                break;
            case 7:
                return $this->getStudentId();
                break;
            case 8:
                return $this->getCreatedAt();
                break;
            case 9:
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
        if (isset($alreadyDumpedObjects['Score'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Score'][$this->getPrimaryKey()] = true;
        $keys = ScorePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getHomework(),
            $keys[2] => $this->getDailyExam(),
            $keys[3] => $this->getMidExam(),
            $keys[4] => $this->getFinalExam(),
            $keys[5] => $this->getSchoolClassStudentId(),
            $keys[6] => $this->getSchoolClassCourseId(),
            $keys[7] => $this->getStudentId(),
            $keys[8] => $this->getCreatedAt(),
            $keys[9] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aSchoolClassStudent) {
                $result['SchoolClassStudent'] = $this->aSchoolClassStudent->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aSchoolClassCourse) {
                $result['SchoolClassCourse'] = $this->aSchoolClassCourse->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collStudentReports) {
                $result['StudentReports'] = $this->collStudentReports->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = ScorePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setHomework($value);
                break;
            case 2:
                $this->setDailyExam($value);
                break;
            case 3:
                $this->setMidExam($value);
                break;
            case 4:
                $this->setFinalExam($value);
                break;
            case 5:
                $this->setSchoolClassStudentId($value);
                break;
            case 6:
                $this->setSchoolClassCourseId($value);
                break;
            case 7:
                $this->setStudentId($value);
                break;
            case 8:
                $this->setCreatedAt($value);
                break;
            case 9:
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
        $keys = ScorePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setHomework($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setDailyExam($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setMidExam($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setFinalExam($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setSchoolClassStudentId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setSchoolClassCourseId($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setStudentId($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setCreatedAt($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setUpdatedAt($arr[$keys[9]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ScorePeer::DATABASE_NAME);

        if ($this->isColumnModified(ScorePeer::ID)) $criteria->add(ScorePeer::ID, $this->id);
        if ($this->isColumnModified(ScorePeer::HOMEWORK)) $criteria->add(ScorePeer::HOMEWORK, $this->homework);
        if ($this->isColumnModified(ScorePeer::DAILY_EXAM)) $criteria->add(ScorePeer::DAILY_EXAM, $this->daily_exam);
        if ($this->isColumnModified(ScorePeer::MID_EXAM)) $criteria->add(ScorePeer::MID_EXAM, $this->mid_exam);
        if ($this->isColumnModified(ScorePeer::FINAL_EXAM)) $criteria->add(ScorePeer::FINAL_EXAM, $this->final_exam);
        if ($this->isColumnModified(ScorePeer::SCHOOL_CLASS_STUDENT_ID)) $criteria->add(ScorePeer::SCHOOL_CLASS_STUDENT_ID, $this->school_class_student_id);
        if ($this->isColumnModified(ScorePeer::SCHOOL_CLASS_COURSE_ID)) $criteria->add(ScorePeer::SCHOOL_CLASS_COURSE_ID, $this->school_class_course_id);
        if ($this->isColumnModified(ScorePeer::STUDENT_ID)) $criteria->add(ScorePeer::STUDENT_ID, $this->student_id);
        if ($this->isColumnModified(ScorePeer::CREATED_AT)) $criteria->add(ScorePeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(ScorePeer::UPDATED_AT)) $criteria->add(ScorePeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(ScorePeer::DATABASE_NAME);
        $criteria->add(ScorePeer::ID, $this->id);

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
     * @param object $copyObj An object of Score (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setHomework($this->getHomework());
        $copyObj->setDailyExam($this->getDailyExam());
        $copyObj->setMidExam($this->getMidExam());
        $copyObj->setFinalExam($this->getFinalExam());
        $copyObj->setSchoolClassStudentId($this->getSchoolClassStudentId());
        $copyObj->setSchoolClassCourseId($this->getSchoolClassCourseId());
        $copyObj->setStudentId($this->getStudentId());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getStudentReports() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStudentReport($relObj->copy($deepCopy));
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
     * @return Score Clone of current object.
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
     * @return ScorePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new ScorePeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a SchoolClassStudent object.
     *
     * @param                  SchoolClassStudent $v
     * @return Score The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSchoolClassStudent(SchoolClassStudent $v = null)
    {
        if ($v === null) {
            $this->setSchoolClassStudentId(NULL);
        } else {
            $this->setSchoolClassStudentId($v->getId());
        }

        $this->aSchoolClassStudent = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the SchoolClassStudent object, it will not be re-added.
        if ($v !== null) {
            $v->addScore($this);
        }


        return $this;
    }


    /**
     * Get the associated SchoolClassStudent object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return SchoolClassStudent The associated SchoolClassStudent object.
     * @throws PropelException
     */
    public function getSchoolClassStudent(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aSchoolClassStudent === null && ($this->school_class_student_id !== null) && $doQuery) {
            $this->aSchoolClassStudent = SchoolClassStudentQuery::create()->findPk($this->school_class_student_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSchoolClassStudent->addScores($this);
             */
        }

        return $this->aSchoolClassStudent;
    }

    /**
     * Declares an association between this object and a SchoolClassCourse object.
     *
     * @param                  SchoolClassCourse $v
     * @return Score The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSchoolClassCourse(SchoolClassCourse $v = null)
    {
        if ($v === null) {
            $this->setSchoolClassCourseId(NULL);
        } else {
            $this->setSchoolClassCourseId($v->getId());
        }

        $this->aSchoolClassCourse = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the SchoolClassCourse object, it will not be re-added.
        if ($v !== null) {
            $v->addScore($this);
        }


        return $this;
    }


    /**
     * Get the associated SchoolClassCourse object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return SchoolClassCourse The associated SchoolClassCourse object.
     * @throws PropelException
     */
    public function getSchoolClassCourse(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aSchoolClassCourse === null && ($this->school_class_course_id !== null) && $doQuery) {
            $this->aSchoolClassCourse = SchoolClassCourseQuery::create()->findPk($this->school_class_course_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSchoolClassCourse->addScores($this);
             */
        }

        return $this->aSchoolClassCourse;
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
        if ('StudentReport' == $relationName) {
            $this->initStudentReports();
        }
    }

    /**
     * Clears out the collStudentReports collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Score The current object (for fluent API support)
     * @see        addStudentReports()
     */
    public function clearStudentReports()
    {
        $this->collStudentReports = null; // important to set this to null since that means it is uninitialized
        $this->collStudentReportsPartial = null;

        return $this;
    }

    /**
     * reset is the collStudentReports collection loaded partially
     *
     * @return void
     */
    public function resetPartialStudentReports($v = true)
    {
        $this->collStudentReportsPartial = $v;
    }

    /**
     * Initializes the collStudentReports collection.
     *
     * By default this just sets the collStudentReports collection to an empty array (like clearcollStudentReports());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStudentReports($overrideExisting = true)
    {
        if (null !== $this->collStudentReports && !$overrideExisting) {
            return;
        }
        $this->collStudentReports = new PropelObjectCollection();
        $this->collStudentReports->setModel('StudentReport');
    }

    /**
     * Gets an array of StudentReport objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Score is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|StudentReport[] List of StudentReport objects
     * @throws PropelException
     */
    public function getStudentReports($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collStudentReportsPartial && !$this->isNew();
        if (null === $this->collStudentReports || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collStudentReports) {
                // return empty collection
                $this->initStudentReports();
            } else {
                $collStudentReports = StudentReportQuery::create(null, $criteria)
                    ->filterByScore($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collStudentReportsPartial && count($collStudentReports)) {
                      $this->initStudentReports(false);

                      foreach ($collStudentReports as $obj) {
                        if (false == $this->collStudentReports->contains($obj)) {
                          $this->collStudentReports->append($obj);
                        }
                      }

                      $this->collStudentReportsPartial = true;
                    }

                    $collStudentReports->getInternalIterator()->rewind();

                    return $collStudentReports;
                }

                if ($partial && $this->collStudentReports) {
                    foreach ($this->collStudentReports as $obj) {
                        if ($obj->isNew()) {
                            $collStudentReports[] = $obj;
                        }
                    }
                }

                $this->collStudentReports = $collStudentReports;
                $this->collStudentReportsPartial = false;
            }
        }

        return $this->collStudentReports;
    }

    /**
     * Sets a collection of StudentReport objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $studentReports A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Score The current object (for fluent API support)
     */
    public function setStudentReports(PropelCollection $studentReports, PropelPDO $con = null)
    {
        $studentReportsToDelete = $this->getStudentReports(new Criteria(), $con)->diff($studentReports);


        $this->studentReportsScheduledForDeletion = $studentReportsToDelete;

        foreach ($studentReportsToDelete as $studentReportRemoved) {
            $studentReportRemoved->setScore(null);
        }

        $this->collStudentReports = null;
        foreach ($studentReports as $studentReport) {
            $this->addStudentReport($studentReport);
        }

        $this->collStudentReports = $studentReports;
        $this->collStudentReportsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related StudentReport objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related StudentReport objects.
     * @throws PropelException
     */
    public function countStudentReports(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collStudentReportsPartial && !$this->isNew();
        if (null === $this->collStudentReports || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStudentReports) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStudentReports());
            }
            $query = StudentReportQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByScore($this)
                ->count($con);
        }

        return count($this->collStudentReports);
    }

    /**
     * Method called to associate a StudentReport object to this object
     * through the StudentReport foreign key attribute.
     *
     * @param    StudentReport $l StudentReport
     * @return Score The current object (for fluent API support)
     */
    public function addStudentReport(StudentReport $l)
    {
        if ($this->collStudentReports === null) {
            $this->initStudentReports();
            $this->collStudentReportsPartial = true;
        }

        if (!in_array($l, $this->collStudentReports->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddStudentReport($l);

            if ($this->studentReportsScheduledForDeletion and $this->studentReportsScheduledForDeletion->contains($l)) {
                $this->studentReportsScheduledForDeletion->remove($this->studentReportsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	StudentReport $studentReport The studentReport object to add.
     */
    protected function doAddStudentReport($studentReport)
    {
        $this->collStudentReports[]= $studentReport;
        $studentReport->setScore($this);
    }

    /**
     * @param	StudentReport $studentReport The studentReport object to remove.
     * @return Score The current object (for fluent API support)
     */
    public function removeStudentReport($studentReport)
    {
        if ($this->getStudentReports()->contains($studentReport)) {
            $this->collStudentReports->remove($this->collStudentReports->search($studentReport));
            if (null === $this->studentReportsScheduledForDeletion) {
                $this->studentReportsScheduledForDeletion = clone $this->collStudentReports;
                $this->studentReportsScheduledForDeletion->clear();
            }
            $this->studentReportsScheduledForDeletion[]= clone $studentReport;
            $studentReport->setScore(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Score is new, it will return
     * an empty collection; or if this Score has previously
     * been saved, it will retrieve related StudentReports from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Score.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|StudentReport[] List of StudentReport objects
     */
    public function getStudentReportsJoinSchoolClass($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StudentReportQuery::create(null, $criteria);
        $query->joinWith('SchoolClass', $join_behavior);

        return $this->getStudentReports($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Score is new, it will return
     * an empty collection; or if this Score has previously
     * been saved, it will retrieve related StudentReports from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Score.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|StudentReport[] List of StudentReport objects
     */
    public function getStudentReportsJoinSchoolClassStudent($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StudentReportQuery::create(null, $criteria);
        $query->joinWith('SchoolClassStudent', $join_behavior);

        return $this->getStudentReports($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->homework = null;
        $this->daily_exam = null;
        $this->mid_exam = null;
        $this->final_exam = null;
        $this->school_class_student_id = null;
        $this->school_class_course_id = null;
        $this->student_id = null;
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
            if ($this->collStudentReports) {
                foreach ($this->collStudentReports as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aSchoolClassStudent instanceof Persistent) {
              $this->aSchoolClassStudent->clearAllReferences($deep);
            }
            if ($this->aSchoolClassCourse instanceof Persistent) {
              $this->aSchoolClassCourse->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collStudentReports instanceof PropelCollection) {
            $this->collStudentReports->clearIterator();
        }
        $this->collStudentReports = null;
        $this->aSchoolClassStudent = null;
        $this->aSchoolClassCourse = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ScorePeer::DEFAULT_STRING_FORMAT);
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
     * @return     Score The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = ScorePeer::UPDATED_AT;

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
