<?php

namespace PGS\CoreDomainBundle\Model\StudentReport\om;

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
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClass;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClassQuery;
use PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudent;
use PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudentQuery;
use PGS\CoreDomainBundle\Model\Score\Score;
use PGS\CoreDomainBundle\Model\Score\ScoreQuery;
use PGS\CoreDomainBundle\Model\StudentReport\StudentReport;
use PGS\CoreDomainBundle\Model\StudentReport\StudentReportPeer;
use PGS\CoreDomainBundle\Model\StudentReport\StudentReportQuery;

abstract class BaseStudentReport extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'PGS\\CoreDomainBundle\\Model\\StudentReport\\StudentReportPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        StudentReportPeer
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
     * The value for the school_class_id field.
     * @var        int
     */
    protected $school_class_id;

    /**
     * The value for the school_class_student_id field.
     * @var        int
     */
    protected $school_class_student_id;

    /**
     * The value for the score_id field.
     * @var        int
     */
    protected $score_id;

    /**
     * The value for the term1 field.
     * @var        int
     */
    protected $term1;

    /**
     * The value for the term2 field.
     * @var        int
     */
    protected $term2;

    /**
     * The value for the term3 field.
     * @var        int
     */
    protected $term3;

    /**
     * The value for the term4 field.
     * @var        int
     */
    protected $term4;

    /**
     * The value for the mid_report field.
     * @var        int
     */
    protected $mid_report;

    /**
     * The value for the final_report field.
     * @var        int
     */
    protected $final_report;

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
     * @var        SchoolClass
     */
    protected $aSchoolClass;

    /**
     * @var        SchoolClassStudent
     */
    protected $aSchoolClassStudent;

    /**
     * @var        Score
     */
    protected $aScore;

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
     * Get the [school_class_id] column value.
     *
     * @return int
     */
    public function getSchoolClassId()
    {

        return $this->school_class_id;
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
     * Get the [score_id] column value.
     *
     * @return int
     */
    public function getScoreId()
    {

        return $this->score_id;
    }

    /**
     * Get the [term1] column value.
     *
     * @return int
     */
    public function getTerm1()
    {

        return $this->term1;
    }

    /**
     * Get the [term2] column value.
     *
     * @return int
     */
    public function getTerm2()
    {

        return $this->term2;
    }

    /**
     * Get the [term3] column value.
     *
     * @return int
     */
    public function getTerm3()
    {

        return $this->term3;
    }

    /**
     * Get the [term4] column value.
     *
     * @return int
     */
    public function getTerm4()
    {

        return $this->term4;
    }

    /**
     * Get the [mid_report] column value.
     *
     * @return int
     */
    public function getMidReport()
    {

        return $this->mid_report;
    }

    /**
     * Get the [final_report] column value.
     *
     * @return int
     */
    public function getFinalReport()
    {

        return $this->final_report;
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
     * @return StudentReport The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = StudentReportPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [school_class_id] column.
     *
     * @param  int $v new value
     * @return StudentReport The current object (for fluent API support)
     */
    public function setSchoolClassId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->school_class_id !== $v) {
            $this->school_class_id = $v;
            $this->modifiedColumns[] = StudentReportPeer::SCHOOL_CLASS_ID;
        }

        if ($this->aSchoolClass !== null && $this->aSchoolClass->getId() !== $v) {
            $this->aSchoolClass = null;
        }


        return $this;
    } // setSchoolClassId()

    /**
     * Set the value of [school_class_student_id] column.
     *
     * @param  int $v new value
     * @return StudentReport The current object (for fluent API support)
     */
    public function setSchoolClassStudentId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->school_class_student_id !== $v) {
            $this->school_class_student_id = $v;
            $this->modifiedColumns[] = StudentReportPeer::SCHOOL_CLASS_STUDENT_ID;
        }

        if ($this->aSchoolClassStudent !== null && $this->aSchoolClassStudent->getId() !== $v) {
            $this->aSchoolClassStudent = null;
        }


        return $this;
    } // setSchoolClassStudentId()

    /**
     * Set the value of [score_id] column.
     *
     * @param  int $v new value
     * @return StudentReport The current object (for fluent API support)
     */
    public function setScoreId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->score_id !== $v) {
            $this->score_id = $v;
            $this->modifiedColumns[] = StudentReportPeer::SCORE_ID;
        }

        if ($this->aScore !== null && $this->aScore->getId() !== $v) {
            $this->aScore = null;
        }


        return $this;
    } // setScoreId()

    /**
     * Set the value of [term1] column.
     *
     * @param  int $v new value
     * @return StudentReport The current object (for fluent API support)
     */
    public function setTerm1($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->term1 !== $v) {
            $this->term1 = $v;
            $this->modifiedColumns[] = StudentReportPeer::TERM1;
        }


        return $this;
    } // setTerm1()

    /**
     * Set the value of [term2] column.
     *
     * @param  int $v new value
     * @return StudentReport The current object (for fluent API support)
     */
    public function setTerm2($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->term2 !== $v) {
            $this->term2 = $v;
            $this->modifiedColumns[] = StudentReportPeer::TERM2;
        }


        return $this;
    } // setTerm2()

    /**
     * Set the value of [term3] column.
     *
     * @param  int $v new value
     * @return StudentReport The current object (for fluent API support)
     */
    public function setTerm3($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->term3 !== $v) {
            $this->term3 = $v;
            $this->modifiedColumns[] = StudentReportPeer::TERM3;
        }


        return $this;
    } // setTerm3()

    /**
     * Set the value of [term4] column.
     *
     * @param  int $v new value
     * @return StudentReport The current object (for fluent API support)
     */
    public function setTerm4($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->term4 !== $v) {
            $this->term4 = $v;
            $this->modifiedColumns[] = StudentReportPeer::TERM4;
        }


        return $this;
    } // setTerm4()

    /**
     * Set the value of [mid_report] column.
     *
     * @param  int $v new value
     * @return StudentReport The current object (for fluent API support)
     */
    public function setMidReport($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->mid_report !== $v) {
            $this->mid_report = $v;
            $this->modifiedColumns[] = StudentReportPeer::MID_REPORT;
        }


        return $this;
    } // setMidReport()

    /**
     * Set the value of [final_report] column.
     *
     * @param  int $v new value
     * @return StudentReport The current object (for fluent API support)
     */
    public function setFinalReport($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->final_report !== $v) {
            $this->final_report = $v;
            $this->modifiedColumns[] = StudentReportPeer::FINAL_REPORT;
        }


        return $this;
    } // setFinalReport()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return StudentReport The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = StudentReportPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return StudentReport The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = StudentReportPeer::UPDATED_AT;
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
            $this->school_class_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->school_class_student_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->score_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->term1 = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->term2 = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->term3 = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->term4 = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->mid_report = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->final_report = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->created_at = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->updated_at = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 12; // 12 = StudentReportPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating StudentReport object", $e);
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

        if ($this->aSchoolClass !== null && $this->school_class_id !== $this->aSchoolClass->getId()) {
            $this->aSchoolClass = null;
        }
        if ($this->aSchoolClassStudent !== null && $this->school_class_student_id !== $this->aSchoolClassStudent->getId()) {
            $this->aSchoolClassStudent = null;
        }
        if ($this->aScore !== null && $this->score_id !== $this->aScore->getId()) {
            $this->aScore = null;
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
            $con = Propel::getConnection(StudentReportPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = StudentReportPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aSchoolClass = null;
            $this->aSchoolClassStudent = null;
            $this->aScore = null;
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
            $con = Propel::getConnection(StudentReportPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
            $deleteQuery = StudentReportQuery::create()
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
            $con = Propel::getConnection(StudentReportPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                if (!$this->isColumnModified(StudentReportPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(StudentReportPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(StudentReportPeer::UPDATED_AT)) {
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
                StudentReportPeer::addInstanceToPool($this);
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

            if ($this->aSchoolClass !== null) {
                if ($this->aSchoolClass->isModified() || $this->aSchoolClass->isNew()) {
                    $affectedRows += $this->aSchoolClass->save($con);
                }
                $this->setSchoolClass($this->aSchoolClass);
            }

            if ($this->aSchoolClassStudent !== null) {
                if ($this->aSchoolClassStudent->isModified() || $this->aSchoolClassStudent->isNew()) {
                    $affectedRows += $this->aSchoolClassStudent->save($con);
                }
                $this->setSchoolClassStudent($this->aSchoolClassStudent);
            }

            if ($this->aScore !== null) {
                if ($this->aScore->isModified() || $this->aScore->isNew()) {
                    $affectedRows += $this->aScore->save($con);
                }
                $this->setScore($this->aScore);
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

        $this->modifiedColumns[] = StudentReportPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . StudentReportPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(StudentReportPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(StudentReportPeer::SCHOOL_CLASS_ID)) {
            $modifiedColumns[':p' . $index++]  = '`school_class_id`';
        }
        if ($this->isColumnModified(StudentReportPeer::SCHOOL_CLASS_STUDENT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`school_class_student_id`';
        }
        if ($this->isColumnModified(StudentReportPeer::SCORE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`score_id`';
        }
        if ($this->isColumnModified(StudentReportPeer::TERM1)) {
            $modifiedColumns[':p' . $index++]  = '`term1`';
        }
        if ($this->isColumnModified(StudentReportPeer::TERM2)) {
            $modifiedColumns[':p' . $index++]  = '`term2`';
        }
        if ($this->isColumnModified(StudentReportPeer::TERM3)) {
            $modifiedColumns[':p' . $index++]  = '`term3`';
        }
        if ($this->isColumnModified(StudentReportPeer::TERM4)) {
            $modifiedColumns[':p' . $index++]  = '`term4`';
        }
        if ($this->isColumnModified(StudentReportPeer::MID_REPORT)) {
            $modifiedColumns[':p' . $index++]  = '`mid_report`';
        }
        if ($this->isColumnModified(StudentReportPeer::FINAL_REPORT)) {
            $modifiedColumns[':p' . $index++]  = '`final_report`';
        }
        if ($this->isColumnModified(StudentReportPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(StudentReportPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `student_report` (%s) VALUES (%s)',
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
                    case '`school_class_id`':
                        $stmt->bindValue($identifier, $this->school_class_id, PDO::PARAM_INT);
                        break;
                    case '`school_class_student_id`':
                        $stmt->bindValue($identifier, $this->school_class_student_id, PDO::PARAM_INT);
                        break;
                    case '`score_id`':
                        $stmt->bindValue($identifier, $this->score_id, PDO::PARAM_INT);
                        break;
                    case '`term1`':
                        $stmt->bindValue($identifier, $this->term1, PDO::PARAM_INT);
                        break;
                    case '`term2`':
                        $stmt->bindValue($identifier, $this->term2, PDO::PARAM_INT);
                        break;
                    case '`term3`':
                        $stmt->bindValue($identifier, $this->term3, PDO::PARAM_INT);
                        break;
                    case '`term4`':
                        $stmt->bindValue($identifier, $this->term4, PDO::PARAM_INT);
                        break;
                    case '`mid_report`':
                        $stmt->bindValue($identifier, $this->mid_report, PDO::PARAM_INT);
                        break;
                    case '`final_report`':
                        $stmt->bindValue($identifier, $this->final_report, PDO::PARAM_INT);
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

            if ($this->aSchoolClass !== null) {
                if (!$this->aSchoolClass->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aSchoolClass->getValidationFailures());
                }
            }

            if ($this->aSchoolClassStudent !== null) {
                if (!$this->aSchoolClassStudent->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aSchoolClassStudent->getValidationFailures());
                }
            }

            if ($this->aScore !== null) {
                if (!$this->aScore->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aScore->getValidationFailures());
                }
            }


            if (($retval = StudentReportPeer::doValidate($this, $columns)) !== true) {
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
        $pos = StudentReportPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getSchoolClassId();
                break;
            case 2:
                return $this->getSchoolClassStudentId();
                break;
            case 3:
                return $this->getScoreId();
                break;
            case 4:
                return $this->getTerm1();
                break;
            case 5:
                return $this->getTerm2();
                break;
            case 6:
                return $this->getTerm3();
                break;
            case 7:
                return $this->getTerm4();
                break;
            case 8:
                return $this->getMidReport();
                break;
            case 9:
                return $this->getFinalReport();
                break;
            case 10:
                return $this->getCreatedAt();
                break;
            case 11:
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
        if (isset($alreadyDumpedObjects['StudentReport'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['StudentReport'][$this->getPrimaryKey()] = true;
        $keys = StudentReportPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSchoolClassId(),
            $keys[2] => $this->getSchoolClassStudentId(),
            $keys[3] => $this->getScoreId(),
            $keys[4] => $this->getTerm1(),
            $keys[5] => $this->getTerm2(),
            $keys[6] => $this->getTerm3(),
            $keys[7] => $this->getTerm4(),
            $keys[8] => $this->getMidReport(),
            $keys[9] => $this->getFinalReport(),
            $keys[10] => $this->getCreatedAt(),
            $keys[11] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aSchoolClass) {
                $result['SchoolClass'] = $this->aSchoolClass->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aSchoolClassStudent) {
                $result['SchoolClassStudent'] = $this->aSchoolClassStudent->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aScore) {
                $result['Score'] = $this->aScore->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = StudentReportPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setSchoolClassId($value);
                break;
            case 2:
                $this->setSchoolClassStudentId($value);
                break;
            case 3:
                $this->setScoreId($value);
                break;
            case 4:
                $this->setTerm1($value);
                break;
            case 5:
                $this->setTerm2($value);
                break;
            case 6:
                $this->setTerm3($value);
                break;
            case 7:
                $this->setTerm4($value);
                break;
            case 8:
                $this->setMidReport($value);
                break;
            case 9:
                $this->setFinalReport($value);
                break;
            case 10:
                $this->setCreatedAt($value);
                break;
            case 11:
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
        $keys = StudentReportPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setSchoolClassId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setSchoolClassStudentId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setScoreId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setTerm1($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setTerm2($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setTerm3($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setTerm4($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setMidReport($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setFinalReport($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setCreatedAt($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setUpdatedAt($arr[$keys[11]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(StudentReportPeer::DATABASE_NAME);

        if ($this->isColumnModified(StudentReportPeer::ID)) $criteria->add(StudentReportPeer::ID, $this->id);
        if ($this->isColumnModified(StudentReportPeer::SCHOOL_CLASS_ID)) $criteria->add(StudentReportPeer::SCHOOL_CLASS_ID, $this->school_class_id);
        if ($this->isColumnModified(StudentReportPeer::SCHOOL_CLASS_STUDENT_ID)) $criteria->add(StudentReportPeer::SCHOOL_CLASS_STUDENT_ID, $this->school_class_student_id);
        if ($this->isColumnModified(StudentReportPeer::SCORE_ID)) $criteria->add(StudentReportPeer::SCORE_ID, $this->score_id);
        if ($this->isColumnModified(StudentReportPeer::TERM1)) $criteria->add(StudentReportPeer::TERM1, $this->term1);
        if ($this->isColumnModified(StudentReportPeer::TERM2)) $criteria->add(StudentReportPeer::TERM2, $this->term2);
        if ($this->isColumnModified(StudentReportPeer::TERM3)) $criteria->add(StudentReportPeer::TERM3, $this->term3);
        if ($this->isColumnModified(StudentReportPeer::TERM4)) $criteria->add(StudentReportPeer::TERM4, $this->term4);
        if ($this->isColumnModified(StudentReportPeer::MID_REPORT)) $criteria->add(StudentReportPeer::MID_REPORT, $this->mid_report);
        if ($this->isColumnModified(StudentReportPeer::FINAL_REPORT)) $criteria->add(StudentReportPeer::FINAL_REPORT, $this->final_report);
        if ($this->isColumnModified(StudentReportPeer::CREATED_AT)) $criteria->add(StudentReportPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(StudentReportPeer::UPDATED_AT)) $criteria->add(StudentReportPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(StudentReportPeer::DATABASE_NAME);
        $criteria->add(StudentReportPeer::ID, $this->id);

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
     * @param object $copyObj An object of StudentReport (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setSchoolClassId($this->getSchoolClassId());
        $copyObj->setSchoolClassStudentId($this->getSchoolClassStudentId());
        $copyObj->setScoreId($this->getScoreId());
        $copyObj->setTerm1($this->getTerm1());
        $copyObj->setTerm2($this->getTerm2());
        $copyObj->setTerm3($this->getTerm3());
        $copyObj->setTerm4($this->getTerm4());
        $copyObj->setMidReport($this->getMidReport());
        $copyObj->setFinalReport($this->getFinalReport());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

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
     * @return StudentReport Clone of current object.
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
     * @return StudentReportPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new StudentReportPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a SchoolClass object.
     *
     * @param                  SchoolClass $v
     * @return StudentReport The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSchoolClass(SchoolClass $v = null)
    {
        if ($v === null) {
            $this->setSchoolClassId(NULL);
        } else {
            $this->setSchoolClassId($v->getId());
        }

        $this->aSchoolClass = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the SchoolClass object, it will not be re-added.
        if ($v !== null) {
            $v->addStudentReport($this);
        }


        return $this;
    }


    /**
     * Get the associated SchoolClass object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return SchoolClass The associated SchoolClass object.
     * @throws PropelException
     */
    public function getSchoolClass(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aSchoolClass === null && ($this->school_class_id !== null) && $doQuery) {
            $this->aSchoolClass = SchoolClassQuery::create()->findPk($this->school_class_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSchoolClass->addStudentReports($this);
             */
        }

        return $this->aSchoolClass;
    }

    /**
     * Declares an association between this object and a SchoolClassStudent object.
     *
     * @param                  SchoolClassStudent $v
     * @return StudentReport The current object (for fluent API support)
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
            $v->addStudentReport($this);
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
                $this->aSchoolClassStudent->addStudentReports($this);
             */
        }

        return $this->aSchoolClassStudent;
    }

    /**
     * Declares an association between this object and a Score object.
     *
     * @param                  Score $v
     * @return StudentReport The current object (for fluent API support)
     * @throws PropelException
     */
    public function setScore(Score $v = null)
    {
        if ($v === null) {
            $this->setScoreId(NULL);
        } else {
            $this->setScoreId($v->getId());
        }

        $this->aScore = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Score object, it will not be re-added.
        if ($v !== null) {
            $v->addStudentReport($this);
        }


        return $this;
    }


    /**
     * Get the associated Score object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Score The associated Score object.
     * @throws PropelException
     */
    public function getScore(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aScore === null && ($this->score_id !== null) && $doQuery) {
            $this->aScore = ScoreQuery::create()->findPk($this->score_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aScore->addStudentReports($this);
             */
        }

        return $this->aScore;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->school_class_id = null;
        $this->school_class_student_id = null;
        $this->score_id = null;
        $this->term1 = null;
        $this->term2 = null;
        $this->term3 = null;
        $this->term4 = null;
        $this->mid_report = null;
        $this->final_report = null;
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
            if ($this->aSchoolClass instanceof Persistent) {
              $this->aSchoolClass->clearAllReferences($deep);
            }
            if ($this->aSchoolClassStudent instanceof Persistent) {
              $this->aSchoolClassStudent->clearAllReferences($deep);
            }
            if ($this->aScore instanceof Persistent) {
              $this->aScore->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        $this->aSchoolClass = null;
        $this->aSchoolClassStudent = null;
        $this->aScore = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(StudentReportPeer::DEFAULT_STRING_FORMAT);
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
     * @return     StudentReport The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = StudentReportPeer::UPDATED_AT;

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
