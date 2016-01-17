<?php

namespace PGS\CoreDomainBundle\Model\SchoolClass\om;

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
use PGS\CoreDomainBundle\Model\GradeLevel\GradeLevel;
use PGS\CoreDomainBundle\Model\GradeLevel\GradeLevelQuery;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClass;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClassI18n;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClassI18nQuery;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClassPeer;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClassQuery;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourse;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourseQuery;
use PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudent;
use PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudentQuery;
use PGS\CoreDomainBundle\Model\StudentReport\StudentReport;
use PGS\CoreDomainBundle\Model\StudentReport\StudentReportQuery;

abstract class BaseSchoolClass extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'PGS\\CoreDomainBundle\\Model\\SchoolClass\\SchoolClassPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        SchoolClassPeer
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
     * The value for the grade_level_id field.
     * @var        int
     */
    protected $grade_level_id;

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
     * @var        GradeLevel
     */
    protected $aGradeLevel;

    /**
     * @var        PropelObjectCollection|SchoolClassCourse[] Collection to store aggregation of SchoolClassCourse objects.
     */
    protected $collSchoolClassCourses;
    protected $collSchoolClassCoursesPartial;

    /**
     * @var        PropelObjectCollection|SchoolClassStudent[] Collection to store aggregation of SchoolClassStudent objects.
     */
    protected $collSchoolClassStudents;
    protected $collSchoolClassStudentsPartial;

    /**
     * @var        PropelObjectCollection|StudentReport[] Collection to store aggregation of StudentReport objects.
     */
    protected $collStudentReports;
    protected $collStudentReportsPartial;

    /**
     * @var        PropelObjectCollection|SchoolClassI18n[] Collection to store aggregation of SchoolClassI18n objects.
     */
    protected $collSchoolClassI18ns;
    protected $collSchoolClassI18nsPartial;

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
     * @var        array[SchoolClassI18n]
     */
    protected $currentTranslations;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $schoolClassCoursesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $schoolClassStudentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $studentReportsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $schoolClassI18nsScheduledForDeletion = null;

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
     * Get the [grade_level_id] column value.
     *
     * @return int
     */
    public function getGradeLevelId()
    {

        return $this->grade_level_id;
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
     * @return SchoolClass The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = SchoolClassPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [grade_level_id] column.
     *
     * @param  int $v new value
     * @return SchoolClass The current object (for fluent API support)
     */
    public function setGradeLevelId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->grade_level_id !== $v) {
            $this->grade_level_id = $v;
            $this->modifiedColumns[] = SchoolClassPeer::GRADE_LEVEL_ID;
        }

        if ($this->aGradeLevel !== null && $this->aGradeLevel->getId() !== $v) {
            $this->aGradeLevel = null;
        }


        return $this;
    } // setGradeLevelId()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return SchoolClass The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = SchoolClassPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return SchoolClass The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = SchoolClassPeer::UPDATED_AT;
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
            $this->grade_level_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->created_at = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->updated_at = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 4; // 4 = SchoolClassPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating SchoolClass object", $e);
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

        if ($this->aGradeLevel !== null && $this->grade_level_id !== $this->aGradeLevel->getId()) {
            $this->aGradeLevel = null;
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
            $con = Propel::getConnection(SchoolClassPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = SchoolClassPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aGradeLevel = null;
            $this->collSchoolClassCourses = null;

            $this->collSchoolClassStudents = null;

            $this->collStudentReports = null;

            $this->collSchoolClassI18ns = null;

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
            $con = Propel::getConnection(SchoolClassPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
            $deleteQuery = SchoolClassQuery::create()
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
            $con = Propel::getConnection(SchoolClassPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                if (!$this->isColumnModified(SchoolClassPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(SchoolClassPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(SchoolClassPeer::UPDATED_AT)) {
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
                SchoolClassPeer::addInstanceToPool($this);
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

            if ($this->aGradeLevel !== null) {
                if ($this->aGradeLevel->isModified() || $this->aGradeLevel->isNew()) {
                    $affectedRows += $this->aGradeLevel->save($con);
                }
                $this->setGradeLevel($this->aGradeLevel);
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

            if ($this->schoolClassCoursesScheduledForDeletion !== null) {
                if (!$this->schoolClassCoursesScheduledForDeletion->isEmpty()) {
                    SchoolClassCourseQuery::create()
                        ->filterByPrimaryKeys($this->schoolClassCoursesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->schoolClassCoursesScheduledForDeletion = null;
                }
            }

            if ($this->collSchoolClassCourses !== null) {
                foreach ($this->collSchoolClassCourses as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->schoolClassStudentsScheduledForDeletion !== null) {
                if (!$this->schoolClassStudentsScheduledForDeletion->isEmpty()) {
                    SchoolClassStudentQuery::create()
                        ->filterByPrimaryKeys($this->schoolClassStudentsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->schoolClassStudentsScheduledForDeletion = null;
                }
            }

            if ($this->collSchoolClassStudents !== null) {
                foreach ($this->collSchoolClassStudents as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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

            if ($this->schoolClassI18nsScheduledForDeletion !== null) {
                if (!$this->schoolClassI18nsScheduledForDeletion->isEmpty()) {
                    SchoolClassI18nQuery::create()
                        ->filterByPrimaryKeys($this->schoolClassI18nsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->schoolClassI18nsScheduledForDeletion = null;
                }
            }

            if ($this->collSchoolClassI18ns !== null) {
                foreach ($this->collSchoolClassI18ns as $referrerFK) {
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

        $this->modifiedColumns[] = SchoolClassPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . SchoolClassPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(SchoolClassPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(SchoolClassPeer::GRADE_LEVEL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`grade_level_id`';
        }
        if ($this->isColumnModified(SchoolClassPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(SchoolClassPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `school_class` (%s) VALUES (%s)',
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
                    case '`grade_level_id`':
                        $stmt->bindValue($identifier, $this->grade_level_id, PDO::PARAM_INT);
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

            if ($this->aGradeLevel !== null) {
                if (!$this->aGradeLevel->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aGradeLevel->getValidationFailures());
                }
            }


            if (($retval = SchoolClassPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collSchoolClassCourses !== null) {
                    foreach ($this->collSchoolClassCourses as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSchoolClassStudents !== null) {
                    foreach ($this->collSchoolClassStudents as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collStudentReports !== null) {
                    foreach ($this->collStudentReports as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSchoolClassI18ns !== null) {
                    foreach ($this->collSchoolClassI18ns as $referrerFK) {
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
        $pos = SchoolClassPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getGradeLevelId();
                break;
            case 2:
                return $this->getCreatedAt();
                break;
            case 3:
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
        if (isset($alreadyDumpedObjects['SchoolClass'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['SchoolClass'][$this->getPrimaryKey()] = true;
        $keys = SchoolClassPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getGradeLevelId(),
            $keys[2] => $this->getCreatedAt(),
            $keys[3] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aGradeLevel) {
                $result['GradeLevel'] = $this->aGradeLevel->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collSchoolClassCourses) {
                $result['SchoolClassCourses'] = $this->collSchoolClassCourses->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSchoolClassStudents) {
                $result['SchoolClassStudents'] = $this->collSchoolClassStudents->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStudentReports) {
                $result['StudentReports'] = $this->collStudentReports->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSchoolClassI18ns) {
                $result['SchoolClassI18ns'] = $this->collSchoolClassI18ns->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = SchoolClassPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setGradeLevelId($value);
                break;
            case 2:
                $this->setCreatedAt($value);
                break;
            case 3:
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
        $keys = SchoolClassPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setGradeLevelId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setCreatedAt($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setUpdatedAt($arr[$keys[3]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(SchoolClassPeer::DATABASE_NAME);

        if ($this->isColumnModified(SchoolClassPeer::ID)) $criteria->add(SchoolClassPeer::ID, $this->id);
        if ($this->isColumnModified(SchoolClassPeer::GRADE_LEVEL_ID)) $criteria->add(SchoolClassPeer::GRADE_LEVEL_ID, $this->grade_level_id);
        if ($this->isColumnModified(SchoolClassPeer::CREATED_AT)) $criteria->add(SchoolClassPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(SchoolClassPeer::UPDATED_AT)) $criteria->add(SchoolClassPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(SchoolClassPeer::DATABASE_NAME);
        $criteria->add(SchoolClassPeer::ID, $this->id);

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
     * @param object $copyObj An object of SchoolClass (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setGradeLevelId($this->getGradeLevelId());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getSchoolClassCourses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSchoolClassCourse($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSchoolClassStudents() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSchoolClassStudent($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStudentReports() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStudentReport($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSchoolClassI18ns() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSchoolClassI18n($relObj->copy($deepCopy));
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
     * @return SchoolClass Clone of current object.
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
     * @return SchoolClassPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new SchoolClassPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a GradeLevel object.
     *
     * @param                  GradeLevel $v
     * @return SchoolClass The current object (for fluent API support)
     * @throws PropelException
     */
    public function setGradeLevel(GradeLevel $v = null)
    {
        if ($v === null) {
            $this->setGradeLevelId(NULL);
        } else {
            $this->setGradeLevelId($v->getId());
        }

        $this->aGradeLevel = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the GradeLevel object, it will not be re-added.
        if ($v !== null) {
            $v->addSchoolClass($this);
        }


        return $this;
    }


    /**
     * Get the associated GradeLevel object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return GradeLevel The associated GradeLevel object.
     * @throws PropelException
     */
    public function getGradeLevel(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aGradeLevel === null && ($this->grade_level_id !== null) && $doQuery) {
            $this->aGradeLevel = GradeLevelQuery::create()->findPk($this->grade_level_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aGradeLevel->addSchoolClasses($this);
             */
        }

        return $this->aGradeLevel;
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
        if ('SchoolClassCourse' == $relationName) {
            $this->initSchoolClassCourses();
        }
        if ('SchoolClassStudent' == $relationName) {
            $this->initSchoolClassStudents();
        }
        if ('StudentReport' == $relationName) {
            $this->initStudentReports();
        }
        if ('SchoolClassI18n' == $relationName) {
            $this->initSchoolClassI18ns();
        }
    }

    /**
     * Clears out the collSchoolClassCourses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return SchoolClass The current object (for fluent API support)
     * @see        addSchoolClassCourses()
     */
    public function clearSchoolClassCourses()
    {
        $this->collSchoolClassCourses = null; // important to set this to null since that means it is uninitialized
        $this->collSchoolClassCoursesPartial = null;

        return $this;
    }

    /**
     * reset is the collSchoolClassCourses collection loaded partially
     *
     * @return void
     */
    public function resetPartialSchoolClassCourses($v = true)
    {
        $this->collSchoolClassCoursesPartial = $v;
    }

    /**
     * Initializes the collSchoolClassCourses collection.
     *
     * By default this just sets the collSchoolClassCourses collection to an empty array (like clearcollSchoolClassCourses());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSchoolClassCourses($overrideExisting = true)
    {
        if (null !== $this->collSchoolClassCourses && !$overrideExisting) {
            return;
        }
        $this->collSchoolClassCourses = new PropelObjectCollection();
        $this->collSchoolClassCourses->setModel('SchoolClassCourse');
    }

    /**
     * Gets an array of SchoolClassCourse objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this SchoolClass is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|SchoolClassCourse[] List of SchoolClassCourse objects
     * @throws PropelException
     */
    public function getSchoolClassCourses($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSchoolClassCoursesPartial && !$this->isNew();
        if (null === $this->collSchoolClassCourses || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSchoolClassCourses) {
                // return empty collection
                $this->initSchoolClassCourses();
            } else {
                $collSchoolClassCourses = SchoolClassCourseQuery::create(null, $criteria)
                    ->filterBySchoolClass($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSchoolClassCoursesPartial && count($collSchoolClassCourses)) {
                      $this->initSchoolClassCourses(false);

                      foreach ($collSchoolClassCourses as $obj) {
                        if (false == $this->collSchoolClassCourses->contains($obj)) {
                          $this->collSchoolClassCourses->append($obj);
                        }
                      }

                      $this->collSchoolClassCoursesPartial = true;
                    }

                    $collSchoolClassCourses->getInternalIterator()->rewind();

                    return $collSchoolClassCourses;
                }

                if ($partial && $this->collSchoolClassCourses) {
                    foreach ($this->collSchoolClassCourses as $obj) {
                        if ($obj->isNew()) {
                            $collSchoolClassCourses[] = $obj;
                        }
                    }
                }

                $this->collSchoolClassCourses = $collSchoolClassCourses;
                $this->collSchoolClassCoursesPartial = false;
            }
        }

        return $this->collSchoolClassCourses;
    }

    /**
     * Sets a collection of SchoolClassCourse objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $schoolClassCourses A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return SchoolClass The current object (for fluent API support)
     */
    public function setSchoolClassCourses(PropelCollection $schoolClassCourses, PropelPDO $con = null)
    {
        $schoolClassCoursesToDelete = $this->getSchoolClassCourses(new Criteria(), $con)->diff($schoolClassCourses);


        $this->schoolClassCoursesScheduledForDeletion = $schoolClassCoursesToDelete;

        foreach ($schoolClassCoursesToDelete as $schoolClassCourseRemoved) {
            $schoolClassCourseRemoved->setSchoolClass(null);
        }

        $this->collSchoolClassCourses = null;
        foreach ($schoolClassCourses as $schoolClassCourse) {
            $this->addSchoolClassCourse($schoolClassCourse);
        }

        $this->collSchoolClassCourses = $schoolClassCourses;
        $this->collSchoolClassCoursesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SchoolClassCourse objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related SchoolClassCourse objects.
     * @throws PropelException
     */
    public function countSchoolClassCourses(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSchoolClassCoursesPartial && !$this->isNew();
        if (null === $this->collSchoolClassCourses || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSchoolClassCourses) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSchoolClassCourses());
            }
            $query = SchoolClassCourseQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySchoolClass($this)
                ->count($con);
        }

        return count($this->collSchoolClassCourses);
    }

    /**
     * Method called to associate a SchoolClassCourse object to this object
     * through the SchoolClassCourse foreign key attribute.
     *
     * @param    SchoolClassCourse $l SchoolClassCourse
     * @return SchoolClass The current object (for fluent API support)
     */
    public function addSchoolClassCourse(SchoolClassCourse $l)
    {
        if ($this->collSchoolClassCourses === null) {
            $this->initSchoolClassCourses();
            $this->collSchoolClassCoursesPartial = true;
        }

        if (!in_array($l, $this->collSchoolClassCourses->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSchoolClassCourse($l);

            if ($this->schoolClassCoursesScheduledForDeletion and $this->schoolClassCoursesScheduledForDeletion->contains($l)) {
                $this->schoolClassCoursesScheduledForDeletion->remove($this->schoolClassCoursesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	SchoolClassCourse $schoolClassCourse The schoolClassCourse object to add.
     */
    protected function doAddSchoolClassCourse($schoolClassCourse)
    {
        $this->collSchoolClassCourses[]= $schoolClassCourse;
        $schoolClassCourse->setSchoolClass($this);
    }

    /**
     * @param	SchoolClassCourse $schoolClassCourse The schoolClassCourse object to remove.
     * @return SchoolClass The current object (for fluent API support)
     */
    public function removeSchoolClassCourse($schoolClassCourse)
    {
        if ($this->getSchoolClassCourses()->contains($schoolClassCourse)) {
            $this->collSchoolClassCourses->remove($this->collSchoolClassCourses->search($schoolClassCourse));
            if (null === $this->schoolClassCoursesScheduledForDeletion) {
                $this->schoolClassCoursesScheduledForDeletion = clone $this->collSchoolClassCourses;
                $this->schoolClassCoursesScheduledForDeletion->clear();
            }
            $this->schoolClassCoursesScheduledForDeletion[]= clone $schoolClassCourse;
            $schoolClassCourse->setSchoolClass(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this SchoolClass is new, it will return
     * an empty collection; or if this SchoolClass has previously
     * been saved, it will retrieve related SchoolClassCourses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in SchoolClass.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassCourse[] List of SchoolClassCourse objects
     */
    public function getSchoolClassCoursesJoinCourse($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassCourseQuery::create(null, $criteria);
        $query->joinWith('Course', $join_behavior);

        return $this->getSchoolClassCourses($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this SchoolClass is new, it will return
     * an empty collection; or if this SchoolClass has previously
     * been saved, it will retrieve related SchoolClassCourses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in SchoolClass.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassCourse[] List of SchoolClassCourse objects
     */
    public function getSchoolClassCoursesJoinSchoolTerm($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassCourseQuery::create(null, $criteria);
        $query->joinWith('SchoolTerm', $join_behavior);

        return $this->getSchoolClassCourses($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this SchoolClass is new, it will return
     * an empty collection; or if this SchoolClass has previously
     * been saved, it will retrieve related SchoolClassCourses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in SchoolClass.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassCourse[] List of SchoolClassCourse objects
     */
    public function getSchoolClassCoursesJoinSchoolGradeLevel($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassCourseQuery::create(null, $criteria);
        $query->joinWith('SchoolGradeLevel', $join_behavior);

        return $this->getSchoolClassCourses($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this SchoolClass is new, it will return
     * an empty collection; or if this SchoolClass has previously
     * been saved, it will retrieve related SchoolClassCourses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in SchoolClass.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassCourse[] List of SchoolClassCourse objects
     */
    public function getSchoolClassCoursesJoinPrimaryTeacher($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassCourseQuery::create(null, $criteria);
        $query->joinWith('PrimaryTeacher', $join_behavior);

        return $this->getSchoolClassCourses($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this SchoolClass is new, it will return
     * an empty collection; or if this SchoolClass has previously
     * been saved, it will retrieve related SchoolClassCourses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in SchoolClass.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassCourse[] List of SchoolClassCourse objects
     */
    public function getSchoolClassCoursesJoinSecondaryTeacher($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassCourseQuery::create(null, $criteria);
        $query->joinWith('SecondaryTeacher', $join_behavior);

        return $this->getSchoolClassCourses($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this SchoolClass is new, it will return
     * an empty collection; or if this SchoolClass has previously
     * been saved, it will retrieve related SchoolClassCourses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in SchoolClass.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassCourse[] List of SchoolClassCourse objects
     */
    public function getSchoolClassCoursesJoinFormula($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassCourseQuery::create(null, $criteria);
        $query->joinWith('Formula', $join_behavior);

        return $this->getSchoolClassCourses($query, $con);
    }

    /**
     * Clears out the collSchoolClassStudents collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return SchoolClass The current object (for fluent API support)
     * @see        addSchoolClassStudents()
     */
    public function clearSchoolClassStudents()
    {
        $this->collSchoolClassStudents = null; // important to set this to null since that means it is uninitialized
        $this->collSchoolClassStudentsPartial = null;

        return $this;
    }

    /**
     * reset is the collSchoolClassStudents collection loaded partially
     *
     * @return void
     */
    public function resetPartialSchoolClassStudents($v = true)
    {
        $this->collSchoolClassStudentsPartial = $v;
    }

    /**
     * Initializes the collSchoolClassStudents collection.
     *
     * By default this just sets the collSchoolClassStudents collection to an empty array (like clearcollSchoolClassStudents());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSchoolClassStudents($overrideExisting = true)
    {
        if (null !== $this->collSchoolClassStudents && !$overrideExisting) {
            return;
        }
        $this->collSchoolClassStudents = new PropelObjectCollection();
        $this->collSchoolClassStudents->setModel('SchoolClassStudent');
    }

    /**
     * Gets an array of SchoolClassStudent objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this SchoolClass is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|SchoolClassStudent[] List of SchoolClassStudent objects
     * @throws PropelException
     */
    public function getSchoolClassStudents($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSchoolClassStudentsPartial && !$this->isNew();
        if (null === $this->collSchoolClassStudents || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSchoolClassStudents) {
                // return empty collection
                $this->initSchoolClassStudents();
            } else {
                $collSchoolClassStudents = SchoolClassStudentQuery::create(null, $criteria)
                    ->filterBySchoolClass($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSchoolClassStudentsPartial && count($collSchoolClassStudents)) {
                      $this->initSchoolClassStudents(false);

                      foreach ($collSchoolClassStudents as $obj) {
                        if (false == $this->collSchoolClassStudents->contains($obj)) {
                          $this->collSchoolClassStudents->append($obj);
                        }
                      }

                      $this->collSchoolClassStudentsPartial = true;
                    }

                    $collSchoolClassStudents->getInternalIterator()->rewind();

                    return $collSchoolClassStudents;
                }

                if ($partial && $this->collSchoolClassStudents) {
                    foreach ($this->collSchoolClassStudents as $obj) {
                        if ($obj->isNew()) {
                            $collSchoolClassStudents[] = $obj;
                        }
                    }
                }

                $this->collSchoolClassStudents = $collSchoolClassStudents;
                $this->collSchoolClassStudentsPartial = false;
            }
        }

        return $this->collSchoolClassStudents;
    }

    /**
     * Sets a collection of SchoolClassStudent objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $schoolClassStudents A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return SchoolClass The current object (for fluent API support)
     */
    public function setSchoolClassStudents(PropelCollection $schoolClassStudents, PropelPDO $con = null)
    {
        $schoolClassStudentsToDelete = $this->getSchoolClassStudents(new Criteria(), $con)->diff($schoolClassStudents);


        $this->schoolClassStudentsScheduledForDeletion = $schoolClassStudentsToDelete;

        foreach ($schoolClassStudentsToDelete as $schoolClassStudentRemoved) {
            $schoolClassStudentRemoved->setSchoolClass(null);
        }

        $this->collSchoolClassStudents = null;
        foreach ($schoolClassStudents as $schoolClassStudent) {
            $this->addSchoolClassStudent($schoolClassStudent);
        }

        $this->collSchoolClassStudents = $schoolClassStudents;
        $this->collSchoolClassStudentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SchoolClassStudent objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related SchoolClassStudent objects.
     * @throws PropelException
     */
    public function countSchoolClassStudents(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSchoolClassStudentsPartial && !$this->isNew();
        if (null === $this->collSchoolClassStudents || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSchoolClassStudents) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSchoolClassStudents());
            }
            $query = SchoolClassStudentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySchoolClass($this)
                ->count($con);
        }

        return count($this->collSchoolClassStudents);
    }

    /**
     * Method called to associate a SchoolClassStudent object to this object
     * through the SchoolClassStudent foreign key attribute.
     *
     * @param    SchoolClassStudent $l SchoolClassStudent
     * @return SchoolClass The current object (for fluent API support)
     */
    public function addSchoolClassStudent(SchoolClassStudent $l)
    {
        if ($this->collSchoolClassStudents === null) {
            $this->initSchoolClassStudents();
            $this->collSchoolClassStudentsPartial = true;
        }

        if (!in_array($l, $this->collSchoolClassStudents->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSchoolClassStudent($l);

            if ($this->schoolClassStudentsScheduledForDeletion and $this->schoolClassStudentsScheduledForDeletion->contains($l)) {
                $this->schoolClassStudentsScheduledForDeletion->remove($this->schoolClassStudentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	SchoolClassStudent $schoolClassStudent The schoolClassStudent object to add.
     */
    protected function doAddSchoolClassStudent($schoolClassStudent)
    {
        $this->collSchoolClassStudents[]= $schoolClassStudent;
        $schoolClassStudent->setSchoolClass($this);
    }

    /**
     * @param	SchoolClassStudent $schoolClassStudent The schoolClassStudent object to remove.
     * @return SchoolClass The current object (for fluent API support)
     */
    public function removeSchoolClassStudent($schoolClassStudent)
    {
        if ($this->getSchoolClassStudents()->contains($schoolClassStudent)) {
            $this->collSchoolClassStudents->remove($this->collSchoolClassStudents->search($schoolClassStudent));
            if (null === $this->schoolClassStudentsScheduledForDeletion) {
                $this->schoolClassStudentsScheduledForDeletion = clone $this->collSchoolClassStudents;
                $this->schoolClassStudentsScheduledForDeletion->clear();
            }
            $this->schoolClassStudentsScheduledForDeletion[]= clone $schoolClassStudent;
            $schoolClassStudent->setSchoolClass(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this SchoolClass is new, it will return
     * an empty collection; or if this SchoolClass has previously
     * been saved, it will retrieve related SchoolClassStudents from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in SchoolClass.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassStudent[] List of SchoolClassStudent objects
     */
    public function getSchoolClassStudentsJoinStudent($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassStudentQuery::create(null, $criteria);
        $query->joinWith('Student', $join_behavior);

        return $this->getSchoolClassStudents($query, $con);
    }

    /**
     * Clears out the collStudentReports collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return SchoolClass The current object (for fluent API support)
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
     * If this SchoolClass is new, it will return
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
                    ->filterBySchoolClass($this)
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
     * @return SchoolClass The current object (for fluent API support)
     */
    public function setStudentReports(PropelCollection $studentReports, PropelPDO $con = null)
    {
        $studentReportsToDelete = $this->getStudentReports(new Criteria(), $con)->diff($studentReports);


        $this->studentReportsScheduledForDeletion = $studentReportsToDelete;

        foreach ($studentReportsToDelete as $studentReportRemoved) {
            $studentReportRemoved->setSchoolClass(null);
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
                ->filterBySchoolClass($this)
                ->count($con);
        }

        return count($this->collStudentReports);
    }

    /**
     * Method called to associate a StudentReport object to this object
     * through the StudentReport foreign key attribute.
     *
     * @param    StudentReport $l StudentReport
     * @return SchoolClass The current object (for fluent API support)
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
        $studentReport->setSchoolClass($this);
    }

    /**
     * @param	StudentReport $studentReport The studentReport object to remove.
     * @return SchoolClass The current object (for fluent API support)
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
            $studentReport->setSchoolClass(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this SchoolClass is new, it will return
     * an empty collection; or if this SchoolClass has previously
     * been saved, it will retrieve related StudentReports from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in SchoolClass.
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
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this SchoolClass is new, it will return
     * an empty collection; or if this SchoolClass has previously
     * been saved, it will retrieve related StudentReports from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in SchoolClass.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|StudentReport[] List of StudentReport objects
     */
    public function getStudentReportsJoinScore($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StudentReportQuery::create(null, $criteria);
        $query->joinWith('Score', $join_behavior);

        return $this->getStudentReports($query, $con);
    }

    /**
     * Clears out the collSchoolClassI18ns collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return SchoolClass The current object (for fluent API support)
     * @see        addSchoolClassI18ns()
     */
    public function clearSchoolClassI18ns()
    {
        $this->collSchoolClassI18ns = null; // important to set this to null since that means it is uninitialized
        $this->collSchoolClassI18nsPartial = null;

        return $this;
    }

    /**
     * reset is the collSchoolClassI18ns collection loaded partially
     *
     * @return void
     */
    public function resetPartialSchoolClassI18ns($v = true)
    {
        $this->collSchoolClassI18nsPartial = $v;
    }

    /**
     * Initializes the collSchoolClassI18ns collection.
     *
     * By default this just sets the collSchoolClassI18ns collection to an empty array (like clearcollSchoolClassI18ns());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSchoolClassI18ns($overrideExisting = true)
    {
        if (null !== $this->collSchoolClassI18ns && !$overrideExisting) {
            return;
        }
        $this->collSchoolClassI18ns = new PropelObjectCollection();
        $this->collSchoolClassI18ns->setModel('SchoolClassI18n');
    }

    /**
     * Gets an array of SchoolClassI18n objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this SchoolClass is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|SchoolClassI18n[] List of SchoolClassI18n objects
     * @throws PropelException
     */
    public function getSchoolClassI18ns($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSchoolClassI18nsPartial && !$this->isNew();
        if (null === $this->collSchoolClassI18ns || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSchoolClassI18ns) {
                // return empty collection
                $this->initSchoolClassI18ns();
            } else {
                $collSchoolClassI18ns = SchoolClassI18nQuery::create(null, $criteria)
                    ->filterBySchoolClass($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSchoolClassI18nsPartial && count($collSchoolClassI18ns)) {
                      $this->initSchoolClassI18ns(false);

                      foreach ($collSchoolClassI18ns as $obj) {
                        if (false == $this->collSchoolClassI18ns->contains($obj)) {
                          $this->collSchoolClassI18ns->append($obj);
                        }
                      }

                      $this->collSchoolClassI18nsPartial = true;
                    }

                    $collSchoolClassI18ns->getInternalIterator()->rewind();

                    return $collSchoolClassI18ns;
                }

                if ($partial && $this->collSchoolClassI18ns) {
                    foreach ($this->collSchoolClassI18ns as $obj) {
                        if ($obj->isNew()) {
                            $collSchoolClassI18ns[] = $obj;
                        }
                    }
                }

                $this->collSchoolClassI18ns = $collSchoolClassI18ns;
                $this->collSchoolClassI18nsPartial = false;
            }
        }

        return $this->collSchoolClassI18ns;
    }

    /**
     * Sets a collection of SchoolClassI18n objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $schoolClassI18ns A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return SchoolClass The current object (for fluent API support)
     */
    public function setSchoolClassI18ns(PropelCollection $schoolClassI18ns, PropelPDO $con = null)
    {
        $schoolClassI18nsToDelete = $this->getSchoolClassI18ns(new Criteria(), $con)->diff($schoolClassI18ns);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->schoolClassI18nsScheduledForDeletion = clone $schoolClassI18nsToDelete;

        foreach ($schoolClassI18nsToDelete as $schoolClassI18nRemoved) {
            $schoolClassI18nRemoved->setSchoolClass(null);
        }

        $this->collSchoolClassI18ns = null;
        foreach ($schoolClassI18ns as $schoolClassI18n) {
            $this->addSchoolClassI18n($schoolClassI18n);
        }

        $this->collSchoolClassI18ns = $schoolClassI18ns;
        $this->collSchoolClassI18nsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SchoolClassI18n objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related SchoolClassI18n objects.
     * @throws PropelException
     */
    public function countSchoolClassI18ns(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSchoolClassI18nsPartial && !$this->isNew();
        if (null === $this->collSchoolClassI18ns || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSchoolClassI18ns) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSchoolClassI18ns());
            }
            $query = SchoolClassI18nQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySchoolClass($this)
                ->count($con);
        }

        return count($this->collSchoolClassI18ns);
    }

    /**
     * Method called to associate a SchoolClassI18n object to this object
     * through the SchoolClassI18n foreign key attribute.
     *
     * @param    SchoolClassI18n $l SchoolClassI18n
     * @return SchoolClass The current object (for fluent API support)
     */
    public function addSchoolClassI18n(SchoolClassI18n $l)
    {
        if ($l && $locale = $l->getLocale()) {
            $this->setLocale($locale);
            $this->currentTranslations[$locale] = $l;
        }
        if ($this->collSchoolClassI18ns === null) {
            $this->initSchoolClassI18ns();
            $this->collSchoolClassI18nsPartial = true;
        }

        if (!in_array($l, $this->collSchoolClassI18ns->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSchoolClassI18n($l);

            if ($this->schoolClassI18nsScheduledForDeletion and $this->schoolClassI18nsScheduledForDeletion->contains($l)) {
                $this->schoolClassI18nsScheduledForDeletion->remove($this->schoolClassI18nsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	SchoolClassI18n $schoolClassI18n The schoolClassI18n object to add.
     */
    protected function doAddSchoolClassI18n($schoolClassI18n)
    {
        $this->collSchoolClassI18ns[]= $schoolClassI18n;
        $schoolClassI18n->setSchoolClass($this);
    }

    /**
     * @param	SchoolClassI18n $schoolClassI18n The schoolClassI18n object to remove.
     * @return SchoolClass The current object (for fluent API support)
     */
    public function removeSchoolClassI18n($schoolClassI18n)
    {
        if ($this->getSchoolClassI18ns()->contains($schoolClassI18n)) {
            $this->collSchoolClassI18ns->remove($this->collSchoolClassI18ns->search($schoolClassI18n));
            if (null === $this->schoolClassI18nsScheduledForDeletion) {
                $this->schoolClassI18nsScheduledForDeletion = clone $this->collSchoolClassI18ns;
                $this->schoolClassI18nsScheduledForDeletion->clear();
            }
            $this->schoolClassI18nsScheduledForDeletion[]= clone $schoolClassI18n;
            $schoolClassI18n->setSchoolClass(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->grade_level_id = null;
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
            if ($this->collSchoolClassCourses) {
                foreach ($this->collSchoolClassCourses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSchoolClassStudents) {
                foreach ($this->collSchoolClassStudents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStudentReports) {
                foreach ($this->collStudentReports as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSchoolClassI18ns) {
                foreach ($this->collSchoolClassI18ns as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aGradeLevel instanceof Persistent) {
              $this->aGradeLevel->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        // i18n behavior
        $this->currentLocale = 'en_US';
        $this->currentTranslations = null;

        if ($this->collSchoolClassCourses instanceof PropelCollection) {
            $this->collSchoolClassCourses->clearIterator();
        }
        $this->collSchoolClassCourses = null;
        if ($this->collSchoolClassStudents instanceof PropelCollection) {
            $this->collSchoolClassStudents->clearIterator();
        }
        $this->collSchoolClassStudents = null;
        if ($this->collStudentReports instanceof PropelCollection) {
            $this->collStudentReports->clearIterator();
        }
        $this->collStudentReports = null;
        if ($this->collSchoolClassI18ns instanceof PropelCollection) {
            $this->collSchoolClassI18ns->clearIterator();
        }
        $this->collSchoolClassI18ns = null;
        $this->aGradeLevel = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(SchoolClassPeer::DEFAULT_STRING_FORMAT);
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
     * @return    SchoolClass The current object (for fluent API support)
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
     * @return SchoolClassI18n */
    public function getTranslation($locale = 'en_US', PropelPDO $con = null)
    {
        if (!isset($this->currentTranslations[$locale])) {
            if (null !== $this->collSchoolClassI18ns) {
                foreach ($this->collSchoolClassI18ns as $translation) {
                    if ($translation->getLocale() == $locale) {
                        $this->currentTranslations[$locale] = $translation;

                        return $translation;
                    }
                }
            }
            if ($this->isNew()) {
                $translation = new SchoolClassI18n();
                $translation->setLocale($locale);
            } else {
                $translation = SchoolClassI18nQuery::create()
                    ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                    ->findOneOrCreate($con);
                $this->currentTranslations[$locale] = $translation;
            }
            $this->addSchoolClassI18n($translation);
        }

        return $this->currentTranslations[$locale];
    }

    /**
     * Remove the translation for a given locale
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     * @param     PropelPDO $con an optional connection object
     *
     * @return    SchoolClass The current object (for fluent API support)
     */
    public function removeTranslation($locale = 'en_US', PropelPDO $con = null)
    {
        if (!$this->isNew()) {
            SchoolClassI18nQuery::create()
                ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                ->delete($con);
        }
        if (isset($this->currentTranslations[$locale])) {
            unset($this->currentTranslations[$locale]);
        }
        foreach ($this->collSchoolClassI18ns as $key => $translation) {
            if ($translation->getLocale() == $locale) {
                unset($this->collSchoolClassI18ns[$key]);
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
     * @return SchoolClassI18n */
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
         * @return SchoolClassI18n The current object (for fluent API support)
         */
        public function setName($v)
        {    $this->getCurrentTranslation()->setName($v);

        return $this;
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     SchoolClass The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = SchoolClassPeer::UPDATED_AT;

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
