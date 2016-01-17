<?php

namespace PGS\CoreDomainBundle\Model\Course\om;

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
use PGS\CoreDomainBundle\Model\Course\CoursePeer;
use PGS\CoreDomainBundle\Model\Course\CourseQuery;
use PGS\CoreDomainBundle\Model\CourseObjective\CourseObjective;
use PGS\CoreDomainBundle\Model\CourseObjective\CourseObjectiveQuery;
use PGS\CoreDomainBundle\Model\GradeLevel\GradeLevel;
use PGS\CoreDomainBundle\Model\GradeLevel\GradeLevelQuery;
use PGS\CoreDomainBundle\Model\Qualification\Qualification;
use PGS\CoreDomainBundle\Model\Qualification\QualificationQuery;
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Model\School\SchoolQuery;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourse;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourseQuery;
use PGS\CoreDomainBundle\Model\Subject\Subject;
use PGS\CoreDomainBundle\Model\Subject\SubjectQuery;

abstract class BaseCourse extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'PGS\\CoreDomainBundle\\Model\\Course\\CoursePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        CoursePeer
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
     * The value for the description field.
     * @var        string
     */
    protected $description;

    /**
     * The value for the school_id field.
     * @var        int
     */
    protected $school_id;

    /**
     * The value for the subject_id field.
     * @var        int
     */
    protected $subject_id;

    /**
     * The value for the grade_level_id field.
     * @var        int
     */
    protected $grade_level_id;

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
     * @var        School
     */
    protected $aSchool;

    /**
     * @var        Subject
     */
    protected $aSubject;

    /**
     * @var        GradeLevel
     */
    protected $aGradeLevel;

    /**
     * @var        PropelObjectCollection|CourseObjective[] Collection to store aggregation of CourseObjective objects.
     */
    protected $collCourseObjectives;
    protected $collCourseObjectivesPartial;

    /**
     * @var        PropelObjectCollection|Qualification[] Collection to store aggregation of Qualification objects.
     */
    protected $collQualifications;
    protected $collQualificationsPartial;

    /**
     * @var        PropelObjectCollection|SchoolClassCourse[] Collection to store aggregation of SchoolClassCourse objects.
     */
    protected $collSchoolClassCourses;
    protected $collSchoolClassCoursesPartial;

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
     * The old scope value.
     * @var        int
     */
    protected $oldScope;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $courseObjectivesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $qualificationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $schoolClassCoursesScheduledForDeletion = null;

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
     * Get the [description] column value.
     *
     * @return string
     */
    public function getDescription()
    {

        return $this->description;
    }

    /**
     * Get the [school_id] column value.
     *
     * @return int
     */
    public function getSchoolId()
    {

        return $this->school_id;
    }

    /**
     * Get the [subject_id] column value.
     *
     * @return int
     */
    public function getSubjectId()
    {

        return $this->subject_id;
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
     * @return Course The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = CoursePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [code] column.
     *
     * @param  string $v new value
     * @return Course The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[] = CoursePeer::CODE;
        }


        return $this;
    } // setCode()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return Course The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = CoursePeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return Course The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = CoursePeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [school_id] column.
     *
     * @param  int $v new value
     * @return Course The current object (for fluent API support)
     */
    public function setSchoolId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->school_id !== $v) {
            // sortable behavior
            $this->oldScope = $this->school_id;

            $this->school_id = $v;
            $this->modifiedColumns[] = CoursePeer::SCHOOL_ID;
        }

        if ($this->aSchool !== null && $this->aSchool->getId() !== $v) {
            $this->aSchool = null;
        }


        return $this;
    } // setSchoolId()

    /**
     * Set the value of [subject_id] column.
     *
     * @param  int $v new value
     * @return Course The current object (for fluent API support)
     */
    public function setSubjectId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->subject_id !== $v) {
            $this->subject_id = $v;
            $this->modifiedColumns[] = CoursePeer::SUBJECT_ID;
        }

        if ($this->aSubject !== null && $this->aSubject->getId() !== $v) {
            $this->aSubject = null;
        }


        return $this;
    } // setSubjectId()

    /**
     * Set the value of [grade_level_id] column.
     *
     * @param  int $v new value
     * @return Course The current object (for fluent API support)
     */
    public function setGradeLevelId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->grade_level_id !== $v) {
            $this->grade_level_id = $v;
            $this->modifiedColumns[] = CoursePeer::GRADE_LEVEL_ID;
        }

        if ($this->aGradeLevel !== null && $this->aGradeLevel->getId() !== $v) {
            $this->aGradeLevel = null;
        }


        return $this;
    } // setGradeLevelId()

    /**
     * Set the value of [sortable_rank] column.
     *
     * @param  int $v new value
     * @return Course The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[] = CoursePeer::SORTABLE_RANK;
        }


        return $this;
    } // setSortableRank()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Course The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = CoursePeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Course The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = CoursePeer::UPDATED_AT;
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
            $this->code = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->description = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->school_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->subject_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->grade_level_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->sortable_rank = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->created_at = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->updated_at = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 10; // 10 = CoursePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Course object", $e);
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

        if ($this->aSchool !== null && $this->school_id !== $this->aSchool->getId()) {
            $this->aSchool = null;
        }
        if ($this->aSubject !== null && $this->subject_id !== $this->aSubject->getId()) {
            $this->aSubject = null;
        }
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
            $con = Propel::getConnection(CoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = CoursePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aSchool = null;
            $this->aSubject = null;
            $this->aGradeLevel = null;
            $this->collCourseObjectives = null;

            $this->collQualifications = null;

            $this->collSchoolClassCourses = null;

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
            $con = Propel::getConnection(CoursePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
            $deleteQuery = CourseQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // sortable behavior

            CoursePeer::shiftRank(-1, $this->getSortableRank() + 1, null, $this->getScopeValue(), $con);
            CoursePeer::clearInstancePool();

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
            $con = Propel::getConnection(CoursePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                if (!$this->isColumnModified(CoursePeer::RANK_COL)) {
                    $this->setSortableRank(CourseQuery::create()->getMaxRankArray($this->getScopeValue(), $con) + 1);
                }

                // timestampable behavior
                if (!$this->isColumnModified(CoursePeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(CoursePeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
                // sortable behavior
                // if scope has changed and rank was not modified (if yes, assuming superior action)
                // insert object to the end of new scope and cleanup old one
                if (($this->isColumnModified(CoursePeer::SCHOOL_ID)) && !$this->isColumnModified(CoursePeer::RANK_COL)) { CoursePeer::shiftRank(-1, $this->getSortableRank() + 1, null, $this->oldScope, $con);
                    $this->insertAtBottom($con);
                }

                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(CoursePeer::UPDATED_AT)) {
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
                CoursePeer::addInstanceToPool($this);
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

            if ($this->aSchool !== null) {
                if ($this->aSchool->isModified() || $this->aSchool->isNew()) {
                    $affectedRows += $this->aSchool->save($con);
                }
                $this->setSchool($this->aSchool);
            }

            if ($this->aSubject !== null) {
                if ($this->aSubject->isModified() || $this->aSubject->isNew()) {
                    $affectedRows += $this->aSubject->save($con);
                }
                $this->setSubject($this->aSubject);
            }

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

            if ($this->courseObjectivesScheduledForDeletion !== null) {
                if (!$this->courseObjectivesScheduledForDeletion->isEmpty()) {
                    CourseObjectiveQuery::create()
                        ->filterByPrimaryKeys($this->courseObjectivesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->courseObjectivesScheduledForDeletion = null;
                }
            }

            if ($this->collCourseObjectives !== null) {
                foreach ($this->collCourseObjectives as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->qualificationsScheduledForDeletion !== null) {
                if (!$this->qualificationsScheduledForDeletion->isEmpty()) {
                    foreach ($this->qualificationsScheduledForDeletion as $qualification) {
                        // need to save related object because we set the relation to null
                        $qualification->save($con);
                    }
                    $this->qualificationsScheduledForDeletion = null;
                }
            }

            if ($this->collQualifications !== null) {
                foreach ($this->collQualifications as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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

        $this->modifiedColumns[] = CoursePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CoursePeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CoursePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(CoursePeer::CODE)) {
            $modifiedColumns[':p' . $index++]  = '`code`';
        }
        if ($this->isColumnModified(CoursePeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(CoursePeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(CoursePeer::SCHOOL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`school_id`';
        }
        if ($this->isColumnModified(CoursePeer::SUBJECT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`subject_id`';
        }
        if ($this->isColumnModified(CoursePeer::GRADE_LEVEL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`grade_level_id`';
        }
        if ($this->isColumnModified(CoursePeer::SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = '`sortable_rank`';
        }
        if ($this->isColumnModified(CoursePeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(CoursePeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `course` (%s) VALUES (%s)',
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
                    case '`description`':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case '`school_id`':
                        $stmt->bindValue($identifier, $this->school_id, PDO::PARAM_INT);
                        break;
                    case '`subject_id`':
                        $stmt->bindValue($identifier, $this->subject_id, PDO::PARAM_INT);
                        break;
                    case '`grade_level_id`':
                        $stmt->bindValue($identifier, $this->grade_level_id, PDO::PARAM_INT);
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

            if ($this->aSchool !== null) {
                if (!$this->aSchool->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aSchool->getValidationFailures());
                }
            }

            if ($this->aSubject !== null) {
                if (!$this->aSubject->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aSubject->getValidationFailures());
                }
            }

            if ($this->aGradeLevel !== null) {
                if (!$this->aGradeLevel->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aGradeLevel->getValidationFailures());
                }
            }


            if (($retval = CoursePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collCourseObjectives !== null) {
                    foreach ($this->collCourseObjectives as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collQualifications !== null) {
                    foreach ($this->collQualifications as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSchoolClassCourses !== null) {
                    foreach ($this->collSchoolClassCourses as $referrerFK) {
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
        $pos = CoursePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getDescription();
                break;
            case 4:
                return $this->getSchoolId();
                break;
            case 5:
                return $this->getSubjectId();
                break;
            case 6:
                return $this->getGradeLevelId();
                break;
            case 7:
                return $this->getSortableRank();
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
        if (isset($alreadyDumpedObjects['Course'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Course'][$this->getPrimaryKey()] = true;
        $keys = CoursePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getCode(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getDescription(),
            $keys[4] => $this->getSchoolId(),
            $keys[5] => $this->getSubjectId(),
            $keys[6] => $this->getGradeLevelId(),
            $keys[7] => $this->getSortableRank(),
            $keys[8] => $this->getCreatedAt(),
            $keys[9] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aSchool) {
                $result['School'] = $this->aSchool->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aSubject) {
                $result['Subject'] = $this->aSubject->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aGradeLevel) {
                $result['GradeLevel'] = $this->aGradeLevel->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collCourseObjectives) {
                $result['CourseObjectives'] = $this->collCourseObjectives->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collQualifications) {
                $result['Qualifications'] = $this->collQualifications->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSchoolClassCourses) {
                $result['SchoolClassCourses'] = $this->collSchoolClassCourses->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = CoursePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setDescription($value);
                break;
            case 4:
                $this->setSchoolId($value);
                break;
            case 5:
                $this->setSubjectId($value);
                break;
            case 6:
                $this->setGradeLevelId($value);
                break;
            case 7:
                $this->setSortableRank($value);
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
        $keys = CoursePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setCode($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setName($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setDescription($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setSchoolId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setSubjectId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setGradeLevelId($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setSortableRank($arr[$keys[7]]);
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
        $criteria = new Criteria(CoursePeer::DATABASE_NAME);

        if ($this->isColumnModified(CoursePeer::ID)) $criteria->add(CoursePeer::ID, $this->id);
        if ($this->isColumnModified(CoursePeer::CODE)) $criteria->add(CoursePeer::CODE, $this->code);
        if ($this->isColumnModified(CoursePeer::NAME)) $criteria->add(CoursePeer::NAME, $this->name);
        if ($this->isColumnModified(CoursePeer::DESCRIPTION)) $criteria->add(CoursePeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(CoursePeer::SCHOOL_ID)) $criteria->add(CoursePeer::SCHOOL_ID, $this->school_id);
        if ($this->isColumnModified(CoursePeer::SUBJECT_ID)) $criteria->add(CoursePeer::SUBJECT_ID, $this->subject_id);
        if ($this->isColumnModified(CoursePeer::GRADE_LEVEL_ID)) $criteria->add(CoursePeer::GRADE_LEVEL_ID, $this->grade_level_id);
        if ($this->isColumnModified(CoursePeer::SORTABLE_RANK)) $criteria->add(CoursePeer::SORTABLE_RANK, $this->sortable_rank);
        if ($this->isColumnModified(CoursePeer::CREATED_AT)) $criteria->add(CoursePeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(CoursePeer::UPDATED_AT)) $criteria->add(CoursePeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(CoursePeer::DATABASE_NAME);
        $criteria->add(CoursePeer::ID, $this->id);

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
     * @param object $copyObj An object of Course (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setCode($this->getCode());
        $copyObj->setName($this->getName());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setSchoolId($this->getSchoolId());
        $copyObj->setSubjectId($this->getSubjectId());
        $copyObj->setGradeLevelId($this->getGradeLevelId());
        $copyObj->setSortableRank($this->getSortableRank());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getCourseObjectives() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCourseObjective($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getQualifications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addQualification($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSchoolClassCourses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSchoolClassCourse($relObj->copy($deepCopy));
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
     * @return Course Clone of current object.
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
     * @return CoursePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new CoursePeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a School object.
     *
     * @param                  School $v
     * @return Course The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSchool(School $v = null)
    {
        if ($v === null) {
            $this->setSchoolId(NULL);
        } else {
            $this->setSchoolId($v->getId());
        }

        $this->aSchool = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the School object, it will not be re-added.
        if ($v !== null) {
            $v->addCourse($this);
        }


        return $this;
    }


    /**
     * Get the associated School object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return School The associated School object.
     * @throws PropelException
     */
    public function getSchool(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aSchool === null && ($this->school_id !== null) && $doQuery) {
            $this->aSchool = SchoolQuery::create()->findPk($this->school_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSchool->addCourses($this);
             */
        }

        return $this->aSchool;
    }

    /**
     * Declares an association between this object and a Subject object.
     *
     * @param                  Subject $v
     * @return Course The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSubject(Subject $v = null)
    {
        if ($v === null) {
            $this->setSubjectId(NULL);
        } else {
            $this->setSubjectId($v->getId());
        }

        $this->aSubject = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Subject object, it will not be re-added.
        if ($v !== null) {
            $v->addCourse($this);
        }


        return $this;
    }


    /**
     * Get the associated Subject object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Subject The associated Subject object.
     * @throws PropelException
     */
    public function getSubject(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aSubject === null && ($this->subject_id !== null) && $doQuery) {
            $this->aSubject = SubjectQuery::create()->findPk($this->subject_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSubject->addCourses($this);
             */
        }

        return $this->aSubject;
    }

    /**
     * Declares an association between this object and a GradeLevel object.
     *
     * @param                  GradeLevel $v
     * @return Course The current object (for fluent API support)
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
            $v->addCourse($this);
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
                $this->aGradeLevel->addCourses($this);
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
        if ('CourseObjective' == $relationName) {
            $this->initCourseObjectives();
        }
        if ('Qualification' == $relationName) {
            $this->initQualifications();
        }
        if ('SchoolClassCourse' == $relationName) {
            $this->initSchoolClassCourses();
        }
    }

    /**
     * Clears out the collCourseObjectives collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Course The current object (for fluent API support)
     * @see        addCourseObjectives()
     */
    public function clearCourseObjectives()
    {
        $this->collCourseObjectives = null; // important to set this to null since that means it is uninitialized
        $this->collCourseObjectivesPartial = null;

        return $this;
    }

    /**
     * reset is the collCourseObjectives collection loaded partially
     *
     * @return void
     */
    public function resetPartialCourseObjectives($v = true)
    {
        $this->collCourseObjectivesPartial = $v;
    }

    /**
     * Initializes the collCourseObjectives collection.
     *
     * By default this just sets the collCourseObjectives collection to an empty array (like clearcollCourseObjectives());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCourseObjectives($overrideExisting = true)
    {
        if (null !== $this->collCourseObjectives && !$overrideExisting) {
            return;
        }
        $this->collCourseObjectives = new PropelObjectCollection();
        $this->collCourseObjectives->setModel('CourseObjective');
    }

    /**
     * Gets an array of CourseObjective objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Course is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CourseObjective[] List of CourseObjective objects
     * @throws PropelException
     */
    public function getCourseObjectives($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCourseObjectivesPartial && !$this->isNew();
        if (null === $this->collCourseObjectives || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCourseObjectives) {
                // return empty collection
                $this->initCourseObjectives();
            } else {
                $collCourseObjectives = CourseObjectiveQuery::create(null, $criteria)
                    ->filterByCourse($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCourseObjectivesPartial && count($collCourseObjectives)) {
                      $this->initCourseObjectives(false);

                      foreach ($collCourseObjectives as $obj) {
                        if (false == $this->collCourseObjectives->contains($obj)) {
                          $this->collCourseObjectives->append($obj);
                        }
                      }

                      $this->collCourseObjectivesPartial = true;
                    }

                    $collCourseObjectives->getInternalIterator()->rewind();

                    return $collCourseObjectives;
                }

                if ($partial && $this->collCourseObjectives) {
                    foreach ($this->collCourseObjectives as $obj) {
                        if ($obj->isNew()) {
                            $collCourseObjectives[] = $obj;
                        }
                    }
                }

                $this->collCourseObjectives = $collCourseObjectives;
                $this->collCourseObjectivesPartial = false;
            }
        }

        return $this->collCourseObjectives;
    }

    /**
     * Sets a collection of CourseObjective objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $courseObjectives A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Course The current object (for fluent API support)
     */
    public function setCourseObjectives(PropelCollection $courseObjectives, PropelPDO $con = null)
    {
        $courseObjectivesToDelete = $this->getCourseObjectives(new Criteria(), $con)->diff($courseObjectives);


        $this->courseObjectivesScheduledForDeletion = $courseObjectivesToDelete;

        foreach ($courseObjectivesToDelete as $courseObjectiveRemoved) {
            $courseObjectiveRemoved->setCourse(null);
        }

        $this->collCourseObjectives = null;
        foreach ($courseObjectives as $courseObjective) {
            $this->addCourseObjective($courseObjective);
        }

        $this->collCourseObjectives = $courseObjectives;
        $this->collCourseObjectivesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CourseObjective objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CourseObjective objects.
     * @throws PropelException
     */
    public function countCourseObjectives(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCourseObjectivesPartial && !$this->isNew();
        if (null === $this->collCourseObjectives || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCourseObjectives) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCourseObjectives());
            }
            $query = CourseObjectiveQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCourse($this)
                ->count($con);
        }

        return count($this->collCourseObjectives);
    }

    /**
     * Method called to associate a CourseObjective object to this object
     * through the CourseObjective foreign key attribute.
     *
     * @param    CourseObjective $l CourseObjective
     * @return Course The current object (for fluent API support)
     */
    public function addCourseObjective(CourseObjective $l)
    {
        if ($this->collCourseObjectives === null) {
            $this->initCourseObjectives();
            $this->collCourseObjectivesPartial = true;
        }

        if (!in_array($l, $this->collCourseObjectives->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCourseObjective($l);

            if ($this->courseObjectivesScheduledForDeletion and $this->courseObjectivesScheduledForDeletion->contains($l)) {
                $this->courseObjectivesScheduledForDeletion->remove($this->courseObjectivesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CourseObjective $courseObjective The courseObjective object to add.
     */
    protected function doAddCourseObjective($courseObjective)
    {
        $this->collCourseObjectives[]= $courseObjective;
        $courseObjective->setCourse($this);
    }

    /**
     * @param	CourseObjective $courseObjective The courseObjective object to remove.
     * @return Course The current object (for fluent API support)
     */
    public function removeCourseObjective($courseObjective)
    {
        if ($this->getCourseObjectives()->contains($courseObjective)) {
            $this->collCourseObjectives->remove($this->collCourseObjectives->search($courseObjective));
            if (null === $this->courseObjectivesScheduledForDeletion) {
                $this->courseObjectivesScheduledForDeletion = clone $this->collCourseObjectives;
                $this->courseObjectivesScheduledForDeletion->clear();
            }
            $this->courseObjectivesScheduledForDeletion[]= clone $courseObjective;
            $courseObjective->setCourse(null);
        }

        return $this;
    }

    /**
     * Clears out the collQualifications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Course The current object (for fluent API support)
     * @see        addQualifications()
     */
    public function clearQualifications()
    {
        $this->collQualifications = null; // important to set this to null since that means it is uninitialized
        $this->collQualificationsPartial = null;

        return $this;
    }

    /**
     * reset is the collQualifications collection loaded partially
     *
     * @return void
     */
    public function resetPartialQualifications($v = true)
    {
        $this->collQualificationsPartial = $v;
    }

    /**
     * Initializes the collQualifications collection.
     *
     * By default this just sets the collQualifications collection to an empty array (like clearcollQualifications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initQualifications($overrideExisting = true)
    {
        if (null !== $this->collQualifications && !$overrideExisting) {
            return;
        }
        $this->collQualifications = new PropelObjectCollection();
        $this->collQualifications->setModel('Qualification');
    }

    /**
     * Gets an array of Qualification objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Course is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Qualification[] List of Qualification objects
     * @throws PropelException
     */
    public function getQualifications($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collQualificationsPartial && !$this->isNew();
        if (null === $this->collQualifications || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collQualifications) {
                // return empty collection
                $this->initQualifications();
            } else {
                $collQualifications = QualificationQuery::create(null, $criteria)
                    ->filterByCourse($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collQualificationsPartial && count($collQualifications)) {
                      $this->initQualifications(false);

                      foreach ($collQualifications as $obj) {
                        if (false == $this->collQualifications->contains($obj)) {
                          $this->collQualifications->append($obj);
                        }
                      }

                      $this->collQualificationsPartial = true;
                    }

                    $collQualifications->getInternalIterator()->rewind();

                    return $collQualifications;
                }

                if ($partial && $this->collQualifications) {
                    foreach ($this->collQualifications as $obj) {
                        if ($obj->isNew()) {
                            $collQualifications[] = $obj;
                        }
                    }
                }

                $this->collQualifications = $collQualifications;
                $this->collQualificationsPartial = false;
            }
        }

        return $this->collQualifications;
    }

    /**
     * Sets a collection of Qualification objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $qualifications A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Course The current object (for fluent API support)
     */
    public function setQualifications(PropelCollection $qualifications, PropelPDO $con = null)
    {
        $qualificationsToDelete = $this->getQualifications(new Criteria(), $con)->diff($qualifications);


        $this->qualificationsScheduledForDeletion = $qualificationsToDelete;

        foreach ($qualificationsToDelete as $qualificationRemoved) {
            $qualificationRemoved->setCourse(null);
        }

        $this->collQualifications = null;
        foreach ($qualifications as $qualification) {
            $this->addQualification($qualification);
        }

        $this->collQualifications = $qualifications;
        $this->collQualificationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Qualification objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Qualification objects.
     * @throws PropelException
     */
    public function countQualifications(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collQualificationsPartial && !$this->isNew();
        if (null === $this->collQualifications || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collQualifications) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getQualifications());
            }
            $query = QualificationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCourse($this)
                ->count($con);
        }

        return count($this->collQualifications);
    }

    /**
     * Method called to associate a Qualification object to this object
     * through the Qualification foreign key attribute.
     *
     * @param    Qualification $l Qualification
     * @return Course The current object (for fluent API support)
     */
    public function addQualification(Qualification $l)
    {
        if ($this->collQualifications === null) {
            $this->initQualifications();
            $this->collQualificationsPartial = true;
        }

        if (!in_array($l, $this->collQualifications->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddQualification($l);

            if ($this->qualificationsScheduledForDeletion and $this->qualificationsScheduledForDeletion->contains($l)) {
                $this->qualificationsScheduledForDeletion->remove($this->qualificationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Qualification $qualification The qualification object to add.
     */
    protected function doAddQualification($qualification)
    {
        $this->collQualifications[]= $qualification;
        $qualification->setCourse($this);
    }

    /**
     * @param	Qualification $qualification The qualification object to remove.
     * @return Course The current object (for fluent API support)
     */
    public function removeQualification($qualification)
    {
        if ($this->getQualifications()->contains($qualification)) {
            $this->collQualifications->remove($this->collQualifications->search($qualification));
            if (null === $this->qualificationsScheduledForDeletion) {
                $this->qualificationsScheduledForDeletion = clone $this->collQualifications;
                $this->qualificationsScheduledForDeletion->clear();
            }
            $this->qualificationsScheduledForDeletion[]= $qualification;
            $qualification->setCourse(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Course is new, it will return
     * an empty collection; or if this Course has previously
     * been saved, it will retrieve related Qualifications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Course.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Qualification[] List of Qualification objects
     */
    public function getQualificationsJoinSchool($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = QualificationQuery::create(null, $criteria);
        $query->joinWith('School', $join_behavior);

        return $this->getQualifications($query, $con);
    }

    /**
     * Clears out the collSchoolClassCourses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Course The current object (for fluent API support)
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
     * If this Course is new, it will return
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
                    ->filterByCourse($this)
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
     * @return Course The current object (for fluent API support)
     */
    public function setSchoolClassCourses(PropelCollection $schoolClassCourses, PropelPDO $con = null)
    {
        $schoolClassCoursesToDelete = $this->getSchoolClassCourses(new Criteria(), $con)->diff($schoolClassCourses);


        $this->schoolClassCoursesScheduledForDeletion = $schoolClassCoursesToDelete;

        foreach ($schoolClassCoursesToDelete as $schoolClassCourseRemoved) {
            $schoolClassCourseRemoved->setCourse(null);
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
                ->filterByCourse($this)
                ->count($con);
        }

        return count($this->collSchoolClassCourses);
    }

    /**
     * Method called to associate a SchoolClassCourse object to this object
     * through the SchoolClassCourse foreign key attribute.
     *
     * @param    SchoolClassCourse $l SchoolClassCourse
     * @return Course The current object (for fluent API support)
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
        $schoolClassCourse->setCourse($this);
    }

    /**
     * @param	SchoolClassCourse $schoolClassCourse The schoolClassCourse object to remove.
     * @return Course The current object (for fluent API support)
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
            $schoolClassCourse->setCourse(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Course is new, it will return
     * an empty collection; or if this Course has previously
     * been saved, it will retrieve related SchoolClassCourses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Course.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassCourse[] List of SchoolClassCourse objects
     */
    public function getSchoolClassCoursesJoinSchoolClass($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassCourseQuery::create(null, $criteria);
        $query->joinWith('SchoolClass', $join_behavior);

        return $this->getSchoolClassCourses($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Course is new, it will return
     * an empty collection; or if this Course has previously
     * been saved, it will retrieve related SchoolClassCourses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Course.
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
     * Otherwise if this Course is new, it will return
     * an empty collection; or if this Course has previously
     * been saved, it will retrieve related SchoolClassCourses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Course.
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
     * Otherwise if this Course is new, it will return
     * an empty collection; or if this Course has previously
     * been saved, it will retrieve related SchoolClassCourses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Course.
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
     * Otherwise if this Course is new, it will return
     * an empty collection; or if this Course has previously
     * been saved, it will retrieve related SchoolClassCourses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Course.
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
     * Otherwise if this Course is new, it will return
     * an empty collection; or if this Course has previously
     * been saved, it will retrieve related SchoolClassCourses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Course.
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
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->code = null;
        $this->name = null;
        $this->description = null;
        $this->school_id = null;
        $this->subject_id = null;
        $this->grade_level_id = null;
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
            if ($this->collCourseObjectives) {
                foreach ($this->collCourseObjectives as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collQualifications) {
                foreach ($this->collQualifications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSchoolClassCourses) {
                foreach ($this->collSchoolClassCourses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aSchool instanceof Persistent) {
              $this->aSchool->clearAllReferences($deep);
            }
            if ($this->aSubject instanceof Persistent) {
              $this->aSubject->clearAllReferences($deep);
            }
            if ($this->aGradeLevel instanceof Persistent) {
              $this->aGradeLevel->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collCourseObjectives instanceof PropelCollection) {
            $this->collCourseObjectives->clearIterator();
        }
        $this->collCourseObjectives = null;
        if ($this->collQualifications instanceof PropelCollection) {
            $this->collQualifications->clearIterator();
        }
        $this->collQualifications = null;
        if ($this->collSchoolClassCourses instanceof PropelCollection) {
            $this->collSchoolClassCourses->clearIterator();
        }
        $this->collSchoolClassCourses = null;
        $this->aSchool = null;
        $this->aSubject = null;
        $this->aGradeLevel = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CoursePeer::DEFAULT_STRING_FORMAT);
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
     * @return    Course
     */
    public function setRank($v)
    {
        return $this->setSortableRank($v);
    }


    /**
     * Wrap the getter for scope value
     *
     * @param boolean $returnNulls If true and all scope values are null, this will return null instead of a array full with nulls
     *
     * @return    mixed A array or a native type
     */
    public function getScopeValue($returnNulls = true)
    {


        return $this->getSchoolId();

    }

    /**
     * Wrap the setter for scope value
     *
     * @param     mixed A array or a native type
     * @return    Course
     */
    public function setScopeValue($v)
    {


        return $this->setSchoolId($v);

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
        return $this->getSortableRank() == CourseQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
    }

    /**
     * Get the next item in the list, i.e. the one for which rank is immediately higher
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Course
     */
    public function getNext(PropelPDO $con = null)
    {

        $query = CourseQuery::create();

        $scope = $this->getScopeValue();

        $query->filterByRank($this->getSortableRank() + 1, $scope);


        return $query->findOne($con);
    }

    /**
     * Get the previous item in the list, i.e. the one for which rank is immediately lower
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Course
     */
    public function getPrevious(PropelPDO $con = null)
    {

        $query = CourseQuery::create();

        $scope = $this->getScopeValue();

        $query->filterByRank($this->getSortableRank() - 1, $scope);


        return $query->findOne($con);
    }

    /**
     * Insert at specified rank
     * The modifications are not persisted until the object is saved.
     *
     * @param     integer    $rank rank value
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Course the current object
     *
     * @throws    PropelException
     */
    public function insertAtRank($rank, PropelPDO $con = null)
    {
        $maxRank = CourseQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
        if ($rank < 1 || $rank > $maxRank + 1) {
            throw new PropelException('Invalid rank ' . $rank);
        }
        // move the object in the list, at the given rank
        $this->setSortableRank($rank);
        if ($rank != $maxRank + 1) {
            // Keep the list modification query for the save() transaction
            $this->sortableQueries []= array(
                'callable'  => array(self::PEER, 'shiftRank'),
                'arguments' => array(1, $rank, null, $this->getScopeValue())
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
     * @return    Course the current object
     *
     * @throws    PropelException
     */
    public function insertAtBottom(PropelPDO $con = null)
    {
        $this->setSortableRank(CourseQuery::create()->getMaxRankArray($this->getScopeValue(), $con) + 1);

        return $this;
    }

    /**
     * Insert in the first rank
     * The modifications are not persisted until the object is saved.
     *
     * @return    Course the current object
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
     * @return    Course the current object
     *
     * @throws    PropelException
     */
    public function moveToRank($newRank, PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be moved. Please use insertAtRank() instead');
        }
        if ($con === null) {
            $con = Propel::getConnection(CoursePeer::DATABASE_NAME);
        }
        if ($newRank < 1 || $newRank > CourseQuery::create()->getMaxRankArray($this->getScopeValue(), $con)) {
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
            CoursePeer::shiftRank($delta, min($oldRank, $newRank), max($oldRank, $newRank), $this->getScopeValue(), $con);

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
     * @param     Course $object
     * @param     PropelPDO $con optional connection
     *
     * @return    Course the current object
     *
     * @throws Exception if the database cannot execute the two updates
     */
    public function swapWith($object, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(CoursePeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $oldScope = $this->getScopeValue();
            $newScope = $object->getScopeValue();
            if ($oldScope != $newScope) {
                $this->setScopeValue($newScope);
                $object->setScopeValue($oldScope);
            }
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
     * @return    Course the current object
     */
    public function moveUp(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(CoursePeer::DATABASE_NAME);
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
     * @return    Course the current object
     */
    public function moveDown(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(CoursePeer::DATABASE_NAME);
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
     * @return    Course the current object
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
            $con = Propel::getConnection(CoursePeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $bottom = CourseQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
            $res = $this->moveToRank($bottom, $con);
            $con->commit();

            return $res;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Removes the current object from the list (moves it to the null scope).
     * The modifications are not persisted until the object is saved.
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    Course the current object
     */
    public function removeFromList(PropelPDO $con = null)
    {
        // check if object is already removed
        if ($this->getScopeValue() === null) {
            throw new PropelException('Object is already removed (has null scope)');
        }

        // move the object to the end of null scope
        $this->setScopeValue(null);
    //    $this->insertAtBottom($con);

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
     * @return     Course The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = CoursePeer::UPDATED_AT;

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
