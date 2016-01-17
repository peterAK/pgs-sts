<?php

namespace PGS\CoreDomainBundle\Model\SchoolClassCourse\om;

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
use PGS\CoreDomainBundle\Model\Course\Course;
use PGS\CoreDomainBundle\Model\Course\CourseQuery;
use PGS\CoreDomainBundle\Model\Formula\Formula;
use PGS\CoreDomainBundle\Model\Formula\FormulaQuery;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClass;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClassQuery;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourse;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCoursePeer;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourseQuery;
use PGS\CoreDomainBundle\Model\SchoolClassCourseStudentBehavior\SchoolClassCourseStudentBehavior;
use PGS\CoreDomainBundle\Model\SchoolClassCourseStudentBehavior\SchoolClassCourseStudentBehaviorQuery;
use PGS\CoreDomainBundle\Model\SchoolGradeLevel\SchoolGradeLevel;
use PGS\CoreDomainBundle\Model\SchoolGradeLevel\SchoolGradeLevelQuery;
use PGS\CoreDomainBundle\Model\SchoolTerm\SchoolTerm;
use PGS\CoreDomainBundle\Model\SchoolTerm\SchoolTermQuery;
use PGS\CoreDomainBundle\Model\Score\Score;
use PGS\CoreDomainBundle\Model\Score\ScoreQuery;

abstract class BaseSchoolClassCourse extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'PGS\\CoreDomainBundle\\Model\\SchoolClassCourse\\SchoolClassCoursePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        SchoolClassCoursePeer
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
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the school_class_id field.
     * @var        int
     */
    protected $school_class_id;

    /**
     * The value for the start_time field.
     * @var        string
     */
    protected $start_time;

    /**
     * The value for the end_time field.
     * @var        string
     */
    protected $end_time;

    /**
     * The value for the course_id field.
     * @var        int
     */
    protected $course_id;

    /**
     * The value for the school_term_id field.
     * @var        int
     */
    protected $school_term_id;

    /**
     * The value for the school_grade_level_id field.
     * @var        int
     */
    protected $school_grade_level_id;

    /**
     * The value for the primary_teacher_id field.
     * @var        int
     */
    protected $primary_teacher_id;

    /**
     * The value for the secondary_teacher_id field.
     * @var        int
     */
    protected $secondary_teacher_id;

    /**
     * The value for the formula_id field.
     * @var        int
     */
    protected $formula_id;

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
     * @var        Course
     */
    protected $aCourse;

    /**
     * @var        SchoolClass
     */
    protected $aSchoolClass;

    /**
     * @var        SchoolTerm
     */
    protected $aSchoolTerm;

    /**
     * @var        SchoolGradeLevel
     */
    protected $aSchoolGradeLevel;

    /**
     * @var        User
     */
    protected $aPrimaryTeacher;

    /**
     * @var        User
     */
    protected $aSecondaryTeacher;

    /**
     * @var        Formula
     */
    protected $aFormula;

    /**
     * @var        PropelObjectCollection|SchoolClassCourseStudentBehavior[] Collection to store aggregation of SchoolClassCourseStudentBehavior objects.
     */
    protected $collSchoolClassCourseStudentBehaviors;
    protected $collSchoolClassCourseStudentBehaviorsPartial;

    /**
     * @var        PropelObjectCollection|Score[] Collection to store aggregation of Score objects.
     */
    protected $collScores;
    protected $collScoresPartial;

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
    protected $schoolClassCourseStudentBehaviorsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $scoresScheduledForDeletion = null;

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
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {

        return $this->name;
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
     * Get the [optionally formatted] temporal [start_time] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getStartTime($format = null)
    {
        if ($this->start_time === null) {
            return null;
        }


        try {
            $dt = new DateTime($this->start_time);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->start_time, true), $x);
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
     * Get the [optionally formatted] temporal [end_time] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getEndTime($format = null)
    {
        if ($this->end_time === null) {
            return null;
        }


        try {
            $dt = new DateTime($this->end_time);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->end_time, true), $x);
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
     * Get the [course_id] column value.
     *
     * @return int
     */
    public function getCourseId()
    {

        return $this->course_id;
    }

    /**
     * Get the [school_term_id] column value.
     *
     * @return int
     */
    public function getSchoolTermId()
    {

        return $this->school_term_id;
    }

    /**
     * Get the [school_grade_level_id] column value.
     *
     * @return int
     */
    public function getSchoolGradeLevelId()
    {

        return $this->school_grade_level_id;
    }

    /**
     * Get the [primary_teacher_id] column value.
     *
     * @return int
     */
    public function getPrimaryTeacherId()
    {

        return $this->primary_teacher_id;
    }

    /**
     * Get the [secondary_teacher_id] column value.
     *
     * @return int
     */
    public function getSecondaryTeacherId()
    {

        return $this->secondary_teacher_id;
    }

    /**
     * Get the [formula_id] column value.
     *
     * @return int
     */
    public function getFormulaId()
    {

        return $this->formula_id;
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
     * @return SchoolClassCourse The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = SchoolClassCoursePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return SchoolClassCourse The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = SchoolClassCoursePeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [school_class_id] column.
     *
     * @param  int $v new value
     * @return SchoolClassCourse The current object (for fluent API support)
     */
    public function setSchoolClassId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->school_class_id !== $v) {
            $this->school_class_id = $v;
            $this->modifiedColumns[] = SchoolClassCoursePeer::SCHOOL_CLASS_ID;
        }

        if ($this->aSchoolClass !== null && $this->aSchoolClass->getId() !== $v) {
            $this->aSchoolClass = null;
        }


        return $this;
    } // setSchoolClassId()

    /**
     * Sets the value of [start_time] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return SchoolClassCourse The current object (for fluent API support)
     */
    public function setStartTime($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->start_time !== null || $dt !== null) {
            $currentDateAsString = ($this->start_time !== null && $tmpDt = new DateTime($this->start_time)) ? $tmpDt->format('H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->start_time = $newDateAsString;
                $this->modifiedColumns[] = SchoolClassCoursePeer::START_TIME;
            }
        } // if either are not null


        return $this;
    } // setStartTime()

    /**
     * Sets the value of [end_time] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return SchoolClassCourse The current object (for fluent API support)
     */
    public function setEndTime($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->end_time !== null || $dt !== null) {
            $currentDateAsString = ($this->end_time !== null && $tmpDt = new DateTime($this->end_time)) ? $tmpDt->format('H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->end_time = $newDateAsString;
                $this->modifiedColumns[] = SchoolClassCoursePeer::END_TIME;
            }
        } // if either are not null


        return $this;
    } // setEndTime()

    /**
     * Set the value of [course_id] column.
     *
     * @param  int $v new value
     * @return SchoolClassCourse The current object (for fluent API support)
     */
    public function setCourseId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->course_id !== $v) {
            $this->course_id = $v;
            $this->modifiedColumns[] = SchoolClassCoursePeer::COURSE_ID;
        }

        if ($this->aCourse !== null && $this->aCourse->getId() !== $v) {
            $this->aCourse = null;
        }


        return $this;
    } // setCourseId()

    /**
     * Set the value of [school_term_id] column.
     *
     * @param  int $v new value
     * @return SchoolClassCourse The current object (for fluent API support)
     */
    public function setSchoolTermId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->school_term_id !== $v) {
            $this->school_term_id = $v;
            $this->modifiedColumns[] = SchoolClassCoursePeer::SCHOOL_TERM_ID;
        }

        if ($this->aSchoolTerm !== null && $this->aSchoolTerm->getId() !== $v) {
            $this->aSchoolTerm = null;
        }


        return $this;
    } // setSchoolTermId()

    /**
     * Set the value of [school_grade_level_id] column.
     *
     * @param  int $v new value
     * @return SchoolClassCourse The current object (for fluent API support)
     */
    public function setSchoolGradeLevelId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->school_grade_level_id !== $v) {
            $this->school_grade_level_id = $v;
            $this->modifiedColumns[] = SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID;
        }

        if ($this->aSchoolGradeLevel !== null && $this->aSchoolGradeLevel->getId() !== $v) {
            $this->aSchoolGradeLevel = null;
        }


        return $this;
    } // setSchoolGradeLevelId()

    /**
     * Set the value of [primary_teacher_id] column.
     *
     * @param  int $v new value
     * @return SchoolClassCourse The current object (for fluent API support)
     */
    public function setPrimaryTeacherId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->primary_teacher_id !== $v) {
            $this->primary_teacher_id = $v;
            $this->modifiedColumns[] = SchoolClassCoursePeer::PRIMARY_TEACHER_ID;
        }

        if ($this->aPrimaryTeacher !== null && $this->aPrimaryTeacher->getId() !== $v) {
            $this->aPrimaryTeacher = null;
        }


        return $this;
    } // setPrimaryTeacherId()

    /**
     * Set the value of [secondary_teacher_id] column.
     *
     * @param  int $v new value
     * @return SchoolClassCourse The current object (for fluent API support)
     */
    public function setSecondaryTeacherId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->secondary_teacher_id !== $v) {
            $this->secondary_teacher_id = $v;
            $this->modifiedColumns[] = SchoolClassCoursePeer::SECONDARY_TEACHER_ID;
        }

        if ($this->aSecondaryTeacher !== null && $this->aSecondaryTeacher->getId() !== $v) {
            $this->aSecondaryTeacher = null;
        }


        return $this;
    } // setSecondaryTeacherId()

    /**
     * Set the value of [formula_id] column.
     *
     * @param  int $v new value
     * @return SchoolClassCourse The current object (for fluent API support)
     */
    public function setFormulaId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->formula_id !== $v) {
            $this->formula_id = $v;
            $this->modifiedColumns[] = SchoolClassCoursePeer::FORMULA_ID;
        }

        if ($this->aFormula !== null && $this->aFormula->getId() !== $v) {
            $this->aFormula = null;
        }


        return $this;
    } // setFormulaId()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return SchoolClassCourse The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = SchoolClassCoursePeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return SchoolClassCourse The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = SchoolClassCoursePeer::UPDATED_AT;
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
            $this->name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->school_class_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->start_time = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->end_time = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->course_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->school_term_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->school_grade_level_id = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->primary_teacher_id = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->secondary_teacher_id = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->formula_id = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
            $this->created_at = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->updated_at = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 13; // 13 = SchoolClassCoursePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating SchoolClassCourse object", $e);
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
        if ($this->aCourse !== null && $this->course_id !== $this->aCourse->getId()) {
            $this->aCourse = null;
        }
        if ($this->aSchoolTerm !== null && $this->school_term_id !== $this->aSchoolTerm->getId()) {
            $this->aSchoolTerm = null;
        }
        if ($this->aSchoolGradeLevel !== null && $this->school_grade_level_id !== $this->aSchoolGradeLevel->getId()) {
            $this->aSchoolGradeLevel = null;
        }
        if ($this->aPrimaryTeacher !== null && $this->primary_teacher_id !== $this->aPrimaryTeacher->getId()) {
            $this->aPrimaryTeacher = null;
        }
        if ($this->aSecondaryTeacher !== null && $this->secondary_teacher_id !== $this->aSecondaryTeacher->getId()) {
            $this->aSecondaryTeacher = null;
        }
        if ($this->aFormula !== null && $this->formula_id !== $this->aFormula->getId()) {
            $this->aFormula = null;
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
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = SchoolClassCoursePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCourse = null;
            $this->aSchoolClass = null;
            $this->aSchoolTerm = null;
            $this->aSchoolGradeLevel = null;
            $this->aPrimaryTeacher = null;
            $this->aSecondaryTeacher = null;
            $this->aFormula = null;
            $this->collSchoolClassCourseStudentBehaviors = null;

            $this->collScores = null;

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
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
            $deleteQuery = SchoolClassCourseQuery::create()
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
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                if (!$this->isColumnModified(SchoolClassCoursePeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(SchoolClassCoursePeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(SchoolClassCoursePeer::UPDATED_AT)) {
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
                SchoolClassCoursePeer::addInstanceToPool($this);
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

            if ($this->aCourse !== null) {
                if ($this->aCourse->isModified() || $this->aCourse->isNew()) {
                    $affectedRows += $this->aCourse->save($con);
                }
                $this->setCourse($this->aCourse);
            }

            if ($this->aSchoolClass !== null) {
                if ($this->aSchoolClass->isModified() || $this->aSchoolClass->isNew()) {
                    $affectedRows += $this->aSchoolClass->save($con);
                }
                $this->setSchoolClass($this->aSchoolClass);
            }

            if ($this->aSchoolTerm !== null) {
                if ($this->aSchoolTerm->isModified() || $this->aSchoolTerm->isNew()) {
                    $affectedRows += $this->aSchoolTerm->save($con);
                }
                $this->setSchoolTerm($this->aSchoolTerm);
            }

            if ($this->aSchoolGradeLevel !== null) {
                if ($this->aSchoolGradeLevel->isModified() || $this->aSchoolGradeLevel->isNew()) {
                    $affectedRows += $this->aSchoolGradeLevel->save($con);
                }
                $this->setSchoolGradeLevel($this->aSchoolGradeLevel);
            }

            if ($this->aPrimaryTeacher !== null) {
                if ($this->aPrimaryTeacher->isModified() || $this->aPrimaryTeacher->isNew()) {
                    $affectedRows += $this->aPrimaryTeacher->save($con);
                }
                $this->setPrimaryTeacher($this->aPrimaryTeacher);
            }

            if ($this->aSecondaryTeacher !== null) {
                if ($this->aSecondaryTeacher->isModified() || $this->aSecondaryTeacher->isNew()) {
                    $affectedRows += $this->aSecondaryTeacher->save($con);
                }
                $this->setSecondaryTeacher($this->aSecondaryTeacher);
            }

            if ($this->aFormula !== null) {
                if ($this->aFormula->isModified() || $this->aFormula->isNew()) {
                    $affectedRows += $this->aFormula->save($con);
                }
                $this->setFormula($this->aFormula);
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

            if ($this->scoresScheduledForDeletion !== null) {
                if (!$this->scoresScheduledForDeletion->isEmpty()) {
                    ScoreQuery::create()
                        ->filterByPrimaryKeys($this->scoresScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->scoresScheduledForDeletion = null;
                }
            }

            if ($this->collScores !== null) {
                foreach ($this->collScores as $referrerFK) {
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

        $this->modifiedColumns[] = SchoolClassCoursePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . SchoolClassCoursePeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(SchoolClassCoursePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(SchoolClassCoursePeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(SchoolClassCoursePeer::SCHOOL_CLASS_ID)) {
            $modifiedColumns[':p' . $index++]  = '`school_class_id`';
        }
        if ($this->isColumnModified(SchoolClassCoursePeer::START_TIME)) {
            $modifiedColumns[':p' . $index++]  = '`start_time`';
        }
        if ($this->isColumnModified(SchoolClassCoursePeer::END_TIME)) {
            $modifiedColumns[':p' . $index++]  = '`end_time`';
        }
        if ($this->isColumnModified(SchoolClassCoursePeer::COURSE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`course_id`';
        }
        if ($this->isColumnModified(SchoolClassCoursePeer::SCHOOL_TERM_ID)) {
            $modifiedColumns[':p' . $index++]  = '`school_term_id`';
        }
        if ($this->isColumnModified(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`school_grade_level_id`';
        }
        if ($this->isColumnModified(SchoolClassCoursePeer::PRIMARY_TEACHER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`primary_teacher_id`';
        }
        if ($this->isColumnModified(SchoolClassCoursePeer::SECONDARY_TEACHER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`secondary_teacher_id`';
        }
        if ($this->isColumnModified(SchoolClassCoursePeer::FORMULA_ID)) {
            $modifiedColumns[':p' . $index++]  = '`formula_id`';
        }
        if ($this->isColumnModified(SchoolClassCoursePeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(SchoolClassCoursePeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `school_class_course` (%s) VALUES (%s)',
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
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`school_class_id`':
                        $stmt->bindValue($identifier, $this->school_class_id, PDO::PARAM_INT);
                        break;
                    case '`start_time`':
                        $stmt->bindValue($identifier, $this->start_time, PDO::PARAM_STR);
                        break;
                    case '`end_time`':
                        $stmt->bindValue($identifier, $this->end_time, PDO::PARAM_STR);
                        break;
                    case '`course_id`':
                        $stmt->bindValue($identifier, $this->course_id, PDO::PARAM_INT);
                        break;
                    case '`school_term_id`':
                        $stmt->bindValue($identifier, $this->school_term_id, PDO::PARAM_INT);
                        break;
                    case '`school_grade_level_id`':
                        $stmt->bindValue($identifier, $this->school_grade_level_id, PDO::PARAM_INT);
                        break;
                    case '`primary_teacher_id`':
                        $stmt->bindValue($identifier, $this->primary_teacher_id, PDO::PARAM_INT);
                        break;
                    case '`secondary_teacher_id`':
                        $stmt->bindValue($identifier, $this->secondary_teacher_id, PDO::PARAM_INT);
                        break;
                    case '`formula_id`':
                        $stmt->bindValue($identifier, $this->formula_id, PDO::PARAM_INT);
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

            if ($this->aCourse !== null) {
                if (!$this->aCourse->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCourse->getValidationFailures());
                }
            }

            if ($this->aSchoolClass !== null) {
                if (!$this->aSchoolClass->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aSchoolClass->getValidationFailures());
                }
            }

            if ($this->aSchoolTerm !== null) {
                if (!$this->aSchoolTerm->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aSchoolTerm->getValidationFailures());
                }
            }

            if ($this->aSchoolGradeLevel !== null) {
                if (!$this->aSchoolGradeLevel->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aSchoolGradeLevel->getValidationFailures());
                }
            }

            if ($this->aPrimaryTeacher !== null) {
                if (!$this->aPrimaryTeacher->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPrimaryTeacher->getValidationFailures());
                }
            }

            if ($this->aSecondaryTeacher !== null) {
                if (!$this->aSecondaryTeacher->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aSecondaryTeacher->getValidationFailures());
                }
            }

            if ($this->aFormula !== null) {
                if (!$this->aFormula->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aFormula->getValidationFailures());
                }
            }


            if (($retval = SchoolClassCoursePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collSchoolClassCourseStudentBehaviors !== null) {
                    foreach ($this->collSchoolClassCourseStudentBehaviors as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collScores !== null) {
                    foreach ($this->collScores as $referrerFK) {
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
        $pos = SchoolClassCoursePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getName();
                break;
            case 2:
                return $this->getSchoolClassId();
                break;
            case 3:
                return $this->getStartTime();
                break;
            case 4:
                return $this->getEndTime();
                break;
            case 5:
                return $this->getCourseId();
                break;
            case 6:
                return $this->getSchoolTermId();
                break;
            case 7:
                return $this->getSchoolGradeLevelId();
                break;
            case 8:
                return $this->getPrimaryTeacherId();
                break;
            case 9:
                return $this->getSecondaryTeacherId();
                break;
            case 10:
                return $this->getFormulaId();
                break;
            case 11:
                return $this->getCreatedAt();
                break;
            case 12:
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
        if (isset($alreadyDumpedObjects['SchoolClassCourse'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['SchoolClassCourse'][$this->getPrimaryKey()] = true;
        $keys = SchoolClassCoursePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getSchoolClassId(),
            $keys[3] => $this->getStartTime(),
            $keys[4] => $this->getEndTime(),
            $keys[5] => $this->getCourseId(),
            $keys[6] => $this->getSchoolTermId(),
            $keys[7] => $this->getSchoolGradeLevelId(),
            $keys[8] => $this->getPrimaryTeacherId(),
            $keys[9] => $this->getSecondaryTeacherId(),
            $keys[10] => $this->getFormulaId(),
            $keys[11] => $this->getCreatedAt(),
            $keys[12] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aCourse) {
                $result['Course'] = $this->aCourse->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aSchoolClass) {
                $result['SchoolClass'] = $this->aSchoolClass->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aSchoolTerm) {
                $result['SchoolTerm'] = $this->aSchoolTerm->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aSchoolGradeLevel) {
                $result['SchoolGradeLevel'] = $this->aSchoolGradeLevel->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPrimaryTeacher) {
                $result['PrimaryTeacher'] = $this->aPrimaryTeacher->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aSecondaryTeacher) {
                $result['SecondaryTeacher'] = $this->aSecondaryTeacher->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aFormula) {
                $result['Formula'] = $this->aFormula->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collSchoolClassCourseStudentBehaviors) {
                $result['SchoolClassCourseStudentBehaviors'] = $this->collSchoolClassCourseStudentBehaviors->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collScores) {
                $result['Scores'] = $this->collScores->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = SchoolClassCoursePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setName($value);
                break;
            case 2:
                $this->setSchoolClassId($value);
                break;
            case 3:
                $this->setStartTime($value);
                break;
            case 4:
                $this->setEndTime($value);
                break;
            case 5:
                $this->setCourseId($value);
                break;
            case 6:
                $this->setSchoolTermId($value);
                break;
            case 7:
                $this->setSchoolGradeLevelId($value);
                break;
            case 8:
                $this->setPrimaryTeacherId($value);
                break;
            case 9:
                $this->setSecondaryTeacherId($value);
                break;
            case 10:
                $this->setFormulaId($value);
                break;
            case 11:
                $this->setCreatedAt($value);
                break;
            case 12:
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
        $keys = SchoolClassCoursePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setSchoolClassId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setStartTime($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setEndTime($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setCourseId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setSchoolTermId($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setSchoolGradeLevelId($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setPrimaryTeacherId($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setSecondaryTeacherId($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setFormulaId($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setCreatedAt($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setUpdatedAt($arr[$keys[12]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(SchoolClassCoursePeer::DATABASE_NAME);

        if ($this->isColumnModified(SchoolClassCoursePeer::ID)) $criteria->add(SchoolClassCoursePeer::ID, $this->id);
        if ($this->isColumnModified(SchoolClassCoursePeer::NAME)) $criteria->add(SchoolClassCoursePeer::NAME, $this->name);
        if ($this->isColumnModified(SchoolClassCoursePeer::SCHOOL_CLASS_ID)) $criteria->add(SchoolClassCoursePeer::SCHOOL_CLASS_ID, $this->school_class_id);
        if ($this->isColumnModified(SchoolClassCoursePeer::START_TIME)) $criteria->add(SchoolClassCoursePeer::START_TIME, $this->start_time);
        if ($this->isColumnModified(SchoolClassCoursePeer::END_TIME)) $criteria->add(SchoolClassCoursePeer::END_TIME, $this->end_time);
        if ($this->isColumnModified(SchoolClassCoursePeer::COURSE_ID)) $criteria->add(SchoolClassCoursePeer::COURSE_ID, $this->course_id);
        if ($this->isColumnModified(SchoolClassCoursePeer::SCHOOL_TERM_ID)) $criteria->add(SchoolClassCoursePeer::SCHOOL_TERM_ID, $this->school_term_id);
        if ($this->isColumnModified(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID)) $criteria->add(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, $this->school_grade_level_id);
        if ($this->isColumnModified(SchoolClassCoursePeer::PRIMARY_TEACHER_ID)) $criteria->add(SchoolClassCoursePeer::PRIMARY_TEACHER_ID, $this->primary_teacher_id);
        if ($this->isColumnModified(SchoolClassCoursePeer::SECONDARY_TEACHER_ID)) $criteria->add(SchoolClassCoursePeer::SECONDARY_TEACHER_ID, $this->secondary_teacher_id);
        if ($this->isColumnModified(SchoolClassCoursePeer::FORMULA_ID)) $criteria->add(SchoolClassCoursePeer::FORMULA_ID, $this->formula_id);
        if ($this->isColumnModified(SchoolClassCoursePeer::CREATED_AT)) $criteria->add(SchoolClassCoursePeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(SchoolClassCoursePeer::UPDATED_AT)) $criteria->add(SchoolClassCoursePeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(SchoolClassCoursePeer::DATABASE_NAME);
        $criteria->add(SchoolClassCoursePeer::ID, $this->id);

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
     * @param object $copyObj An object of SchoolClassCourse (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setSchoolClassId($this->getSchoolClassId());
        $copyObj->setStartTime($this->getStartTime());
        $copyObj->setEndTime($this->getEndTime());
        $copyObj->setCourseId($this->getCourseId());
        $copyObj->setSchoolTermId($this->getSchoolTermId());
        $copyObj->setSchoolGradeLevelId($this->getSchoolGradeLevelId());
        $copyObj->setPrimaryTeacherId($this->getPrimaryTeacherId());
        $copyObj->setSecondaryTeacherId($this->getSecondaryTeacherId());
        $copyObj->setFormulaId($this->getFormulaId());
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

            foreach ($this->getScores() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addScore($relObj->copy($deepCopy));
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
     * @return SchoolClassCourse Clone of current object.
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
     * @return SchoolClassCoursePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new SchoolClassCoursePeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Course object.
     *
     * @param                  Course $v
     * @return SchoolClassCourse The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCourse(Course $v = null)
    {
        if ($v === null) {
            $this->setCourseId(NULL);
        } else {
            $this->setCourseId($v->getId());
        }

        $this->aCourse = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Course object, it will not be re-added.
        if ($v !== null) {
            $v->addSchoolClassCourse($this);
        }


        return $this;
    }


    /**
     * Get the associated Course object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Course The associated Course object.
     * @throws PropelException
     */
    public function getCourse(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aCourse === null && ($this->course_id !== null) && $doQuery) {
            $this->aCourse = CourseQuery::create()->findPk($this->course_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCourse->addSchoolClassCourses($this);
             */
        }

        return $this->aCourse;
    }

    /**
     * Declares an association between this object and a SchoolClass object.
     *
     * @param                  SchoolClass $v
     * @return SchoolClassCourse The current object (for fluent API support)
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
            $v->addSchoolClassCourse($this);
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
                $this->aSchoolClass->addSchoolClassCourses($this);
             */
        }

        return $this->aSchoolClass;
    }

    /**
     * Declares an association between this object and a SchoolTerm object.
     *
     * @param                  SchoolTerm $v
     * @return SchoolClassCourse The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSchoolTerm(SchoolTerm $v = null)
    {
        if ($v === null) {
            $this->setSchoolTermId(NULL);
        } else {
            $this->setSchoolTermId($v->getId());
        }

        $this->aSchoolTerm = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the SchoolTerm object, it will not be re-added.
        if ($v !== null) {
            $v->addSchoolClassCourse($this);
        }


        return $this;
    }


    /**
     * Get the associated SchoolTerm object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return SchoolTerm The associated SchoolTerm object.
     * @throws PropelException
     */
    public function getSchoolTerm(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aSchoolTerm === null && ($this->school_term_id !== null) && $doQuery) {
            $this->aSchoolTerm = SchoolTermQuery::create()->findPk($this->school_term_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSchoolTerm->addSchoolClassCourses($this);
             */
        }

        return $this->aSchoolTerm;
    }

    /**
     * Declares an association between this object and a SchoolGradeLevel object.
     *
     * @param                  SchoolGradeLevel $v
     * @return SchoolClassCourse The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSchoolGradeLevel(SchoolGradeLevel $v = null)
    {
        if ($v === null) {
            $this->setSchoolGradeLevelId(NULL);
        } else {
            $this->setSchoolGradeLevelId($v->getId());
        }

        $this->aSchoolGradeLevel = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the SchoolGradeLevel object, it will not be re-added.
        if ($v !== null) {
            $v->addSchoolClassCourse($this);
        }


        return $this;
    }


    /**
     * Get the associated SchoolGradeLevel object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return SchoolGradeLevel The associated SchoolGradeLevel object.
     * @throws PropelException
     */
    public function getSchoolGradeLevel(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aSchoolGradeLevel === null && ($this->school_grade_level_id !== null) && $doQuery) {
            $this->aSchoolGradeLevel = SchoolGradeLevelQuery::create()->findPk($this->school_grade_level_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSchoolGradeLevel->addSchoolClassCourses($this);
             */
        }

        return $this->aSchoolGradeLevel;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param                  User $v
     * @return SchoolClassCourse The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPrimaryTeacher(User $v = null)
    {
        if ($v === null) {
            $this->setPrimaryTeacherId(NULL);
        } else {
            $this->setPrimaryTeacherId($v->getId());
        }

        $this->aPrimaryTeacher = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addSchoolClassCourseRelatedByPrimaryTeacherId($this);
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
    public function getPrimaryTeacher(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPrimaryTeacher === null && ($this->primary_teacher_id !== null) && $doQuery) {
            $this->aPrimaryTeacher = UserQuery::create()->findPk($this->primary_teacher_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPrimaryTeacher->addSchoolClassCoursesRelatedByPrimaryTeacherId($this);
             */
        }

        return $this->aPrimaryTeacher;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param                  User $v
     * @return SchoolClassCourse The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSecondaryTeacher(User $v = null)
    {
        if ($v === null) {
            $this->setSecondaryTeacherId(NULL);
        } else {
            $this->setSecondaryTeacherId($v->getId());
        }

        $this->aSecondaryTeacher = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addSchoolClassCourseRelatedBySecondaryTeacherId($this);
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
    public function getSecondaryTeacher(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aSecondaryTeacher === null && ($this->secondary_teacher_id !== null) && $doQuery) {
            $this->aSecondaryTeacher = UserQuery::create()->findPk($this->secondary_teacher_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSecondaryTeacher->addSchoolClassCoursesRelatedBySecondaryTeacherId($this);
             */
        }

        return $this->aSecondaryTeacher;
    }

    /**
     * Declares an association between this object and a Formula object.
     *
     * @param                  Formula $v
     * @return SchoolClassCourse The current object (for fluent API support)
     * @throws PropelException
     */
    public function setFormula(Formula $v = null)
    {
        if ($v === null) {
            $this->setFormulaId(NULL);
        } else {
            $this->setFormulaId($v->getId());
        }

        $this->aFormula = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Formula object, it will not be re-added.
        if ($v !== null) {
            $v->addSchoolClassCourse($this);
        }


        return $this;
    }


    /**
     * Get the associated Formula object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Formula The associated Formula object.
     * @throws PropelException
     */
    public function getFormula(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aFormula === null && ($this->formula_id !== null) && $doQuery) {
            $this->aFormula = FormulaQuery::create()->findPk($this->formula_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aFormula->addSchoolClassCourses($this);
             */
        }

        return $this->aFormula;
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
        if ('Score' == $relationName) {
            $this->initScores();
        }
    }

    /**
     * Clears out the collSchoolClassCourseStudentBehaviors collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return SchoolClassCourse The current object (for fluent API support)
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
     * If this SchoolClassCourse is new, it will return
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
                    ->filterBySchoolClassCourse($this)
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
     * @return SchoolClassCourse The current object (for fluent API support)
     */
    public function setSchoolClassCourseStudentBehaviors(PropelCollection $schoolClassCourseStudentBehaviors, PropelPDO $con = null)
    {
        $schoolClassCourseStudentBehaviorsToDelete = $this->getSchoolClassCourseStudentBehaviors(new Criteria(), $con)->diff($schoolClassCourseStudentBehaviors);


        $this->schoolClassCourseStudentBehaviorsScheduledForDeletion = $schoolClassCourseStudentBehaviorsToDelete;

        foreach ($schoolClassCourseStudentBehaviorsToDelete as $schoolClassCourseStudentBehaviorRemoved) {
            $schoolClassCourseStudentBehaviorRemoved->setSchoolClassCourse(null);
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
                ->filterBySchoolClassCourse($this)
                ->count($con);
        }

        return count($this->collSchoolClassCourseStudentBehaviors);
    }

    /**
     * Method called to associate a SchoolClassCourseStudentBehavior object to this object
     * through the SchoolClassCourseStudentBehavior foreign key attribute.
     *
     * @param    SchoolClassCourseStudentBehavior $l SchoolClassCourseStudentBehavior
     * @return SchoolClassCourse The current object (for fluent API support)
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
        $schoolClassCourseStudentBehavior->setSchoolClassCourse($this);
    }

    /**
     * @param	SchoolClassCourseStudentBehavior $schoolClassCourseStudentBehavior The schoolClassCourseStudentBehavior object to remove.
     * @return SchoolClassCourse The current object (for fluent API support)
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
            $schoolClassCourseStudentBehavior->setSchoolClassCourse(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this SchoolClassCourse is new, it will return
     * an empty collection; or if this SchoolClassCourse has previously
     * been saved, it will retrieve related SchoolClassCourseStudentBehaviors from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in SchoolClassCourse.
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
     * Otherwise if this SchoolClassCourse is new, it will return
     * an empty collection; or if this SchoolClassCourse has previously
     * been saved, it will retrieve related SchoolClassCourseStudentBehaviors from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in SchoolClassCourse.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SchoolClassCourseStudentBehavior[] List of SchoolClassCourseStudentBehavior objects
     */
    public function getSchoolClassCourseStudentBehaviorsJoinBehavior($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SchoolClassCourseStudentBehaviorQuery::create(null, $criteria);
        $query->joinWith('Behavior', $join_behavior);

        return $this->getSchoolClassCourseStudentBehaviors($query, $con);
    }

    /**
     * Clears out the collScores collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return SchoolClassCourse The current object (for fluent API support)
     * @see        addScores()
     */
    public function clearScores()
    {
        $this->collScores = null; // important to set this to null since that means it is uninitialized
        $this->collScoresPartial = null;

        return $this;
    }

    /**
     * reset is the collScores collection loaded partially
     *
     * @return void
     */
    public function resetPartialScores($v = true)
    {
        $this->collScoresPartial = $v;
    }

    /**
     * Initializes the collScores collection.
     *
     * By default this just sets the collScores collection to an empty array (like clearcollScores());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initScores($overrideExisting = true)
    {
        if (null !== $this->collScores && !$overrideExisting) {
            return;
        }
        $this->collScores = new PropelObjectCollection();
        $this->collScores->setModel('Score');
    }

    /**
     * Gets an array of Score objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this SchoolClassCourse is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Score[] List of Score objects
     * @throws PropelException
     */
    public function getScores($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collScoresPartial && !$this->isNew();
        if (null === $this->collScores || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collScores) {
                // return empty collection
                $this->initScores();
            } else {
                $collScores = ScoreQuery::create(null, $criteria)
                    ->filterBySchoolClassCourse($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collScoresPartial && count($collScores)) {
                      $this->initScores(false);

                      foreach ($collScores as $obj) {
                        if (false == $this->collScores->contains($obj)) {
                          $this->collScores->append($obj);
                        }
                      }

                      $this->collScoresPartial = true;
                    }

                    $collScores->getInternalIterator()->rewind();

                    return $collScores;
                }

                if ($partial && $this->collScores) {
                    foreach ($this->collScores as $obj) {
                        if ($obj->isNew()) {
                            $collScores[] = $obj;
                        }
                    }
                }

                $this->collScores = $collScores;
                $this->collScoresPartial = false;
            }
        }

        return $this->collScores;
    }

    /**
     * Sets a collection of Score objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $scores A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return SchoolClassCourse The current object (for fluent API support)
     */
    public function setScores(PropelCollection $scores, PropelPDO $con = null)
    {
        $scoresToDelete = $this->getScores(new Criteria(), $con)->diff($scores);


        $this->scoresScheduledForDeletion = $scoresToDelete;

        foreach ($scoresToDelete as $scoreRemoved) {
            $scoreRemoved->setSchoolClassCourse(null);
        }

        $this->collScores = null;
        foreach ($scores as $score) {
            $this->addScore($score);
        }

        $this->collScores = $scores;
        $this->collScoresPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Score objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Score objects.
     * @throws PropelException
     */
    public function countScores(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collScoresPartial && !$this->isNew();
        if (null === $this->collScores || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collScores) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getScores());
            }
            $query = ScoreQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySchoolClassCourse($this)
                ->count($con);
        }

        return count($this->collScores);
    }

    /**
     * Method called to associate a Score object to this object
     * through the Score foreign key attribute.
     *
     * @param    Score $l Score
     * @return SchoolClassCourse The current object (for fluent API support)
     */
    public function addScore(Score $l)
    {
        if ($this->collScores === null) {
            $this->initScores();
            $this->collScoresPartial = true;
        }

        if (!in_array($l, $this->collScores->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddScore($l);

            if ($this->scoresScheduledForDeletion and $this->scoresScheduledForDeletion->contains($l)) {
                $this->scoresScheduledForDeletion->remove($this->scoresScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Score $score The score object to add.
     */
    protected function doAddScore($score)
    {
        $this->collScores[]= $score;
        $score->setSchoolClassCourse($this);
    }

    /**
     * @param	Score $score The score object to remove.
     * @return SchoolClassCourse The current object (for fluent API support)
     */
    public function removeScore($score)
    {
        if ($this->getScores()->contains($score)) {
            $this->collScores->remove($this->collScores->search($score));
            if (null === $this->scoresScheduledForDeletion) {
                $this->scoresScheduledForDeletion = clone $this->collScores;
                $this->scoresScheduledForDeletion->clear();
            }
            $this->scoresScheduledForDeletion[]= clone $score;
            $score->setSchoolClassCourse(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this SchoolClassCourse is new, it will return
     * an empty collection; or if this SchoolClassCourse has previously
     * been saved, it will retrieve related Scores from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in SchoolClassCourse.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Score[] List of Score objects
     */
    public function getScoresJoinSchoolClassStudent($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ScoreQuery::create(null, $criteria);
        $query->joinWith('SchoolClassStudent', $join_behavior);

        return $this->getScores($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->school_class_id = null;
        $this->start_time = null;
        $this->end_time = null;
        $this->course_id = null;
        $this->school_term_id = null;
        $this->school_grade_level_id = null;
        $this->primary_teacher_id = null;
        $this->secondary_teacher_id = null;
        $this->formula_id = null;
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
            if ($this->collSchoolClassCourseStudentBehaviors) {
                foreach ($this->collSchoolClassCourseStudentBehaviors as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collScores) {
                foreach ($this->collScores as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aCourse instanceof Persistent) {
              $this->aCourse->clearAllReferences($deep);
            }
            if ($this->aSchoolClass instanceof Persistent) {
              $this->aSchoolClass->clearAllReferences($deep);
            }
            if ($this->aSchoolTerm instanceof Persistent) {
              $this->aSchoolTerm->clearAllReferences($deep);
            }
            if ($this->aSchoolGradeLevel instanceof Persistent) {
              $this->aSchoolGradeLevel->clearAllReferences($deep);
            }
            if ($this->aPrimaryTeacher instanceof Persistent) {
              $this->aPrimaryTeacher->clearAllReferences($deep);
            }
            if ($this->aSecondaryTeacher instanceof Persistent) {
              $this->aSecondaryTeacher->clearAllReferences($deep);
            }
            if ($this->aFormula instanceof Persistent) {
              $this->aFormula->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collSchoolClassCourseStudentBehaviors instanceof PropelCollection) {
            $this->collSchoolClassCourseStudentBehaviors->clearIterator();
        }
        $this->collSchoolClassCourseStudentBehaviors = null;
        if ($this->collScores instanceof PropelCollection) {
            $this->collScores->clearIterator();
        }
        $this->collScores = null;
        $this->aCourse = null;
        $this->aSchoolClass = null;
        $this->aSchoolTerm = null;
        $this->aSchoolGradeLevel = null;
        $this->aPrimaryTeacher = null;
        $this->aSecondaryTeacher = null;
        $this->aFormula = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(SchoolClassCoursePeer::DEFAULT_STRING_FORMAT);
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
     * @return     SchoolClassCourse The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = SchoolClassCoursePeer::UPDATED_AT;

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
