<?php

namespace PGS\CoreDomainBundle\Model\SchoolHealth\om;

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
use PGS\CoreDomainBundle\Model\Application\Application;
use PGS\CoreDomainBundle\Model\Application\ApplicationQuery;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealth;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealthPeer;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealthQuery;
use PGS\CoreDomainBundle\Model\Student\Student;
use PGS\CoreDomainBundle\Model\Student\StudentQuery;
use PGS\CoreDomainBundle\Model\StudentCondition\StudentCondition;
use PGS\CoreDomainBundle\Model\StudentCondition\StudentConditionQuery;
use PGS\CoreDomainBundle\Model\StudentMedical\StudentMedical;
use PGS\CoreDomainBundle\Model\StudentMedical\StudentMedicalQuery;

abstract class BaseSchoolHealth extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'PGS\\CoreDomainBundle\\Model\\SchoolHealth\\SchoolHealthPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        SchoolHealthPeer
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
     * The value for the application_id field.
     * @var        int
     */
    protected $application_id;

    /**
     * The value for the student_name field.
     * @var        string
     */
    protected $student_name;

    /**
     * The value for the emergency_physician_name field.
     * @var        string
     */
    protected $emergency_physician_name;

    /**
     * The value for the emergency_relationship field.
     * @var        string
     */
    protected $emergency_relationship;

    /**
     * The value for the emergency_phone field.
     * @var        string
     */
    protected $emergency_phone;

    /**
     * The value for the allergies field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $allergies;

    /**
     * The value for the allergies_yes field.
     * @var        string
     */
    protected $allergies_yes;

    /**
     * The value for the allergies_action field.
     * @var        string
     */
    protected $allergies_action;

    /**
     * The value for the condition_choice field.
     * @var        string
     */
    protected $condition_choice;

    /**
     * The value for the condition_exp field.
     * @var        string
     */
    protected $condition_exp;

    /**
     * The value for the student_psychological field.
     * @var        boolean
     */
    protected $student_psychological;

    /**
     * The value for the psychological_exp field.
     * @var        string
     */
    protected $psychological_exp;

    /**
     * The value for the student_aware field.
     * @var        boolean
     */
    protected $student_aware;

    /**
     * The value for the aware_exp field.
     * @var        string
     */
    protected $aware_exp;

    /**
     * The value for the student_ability field.
     * @var        boolean
     */
    protected $student_ability;

    /**
     * The value for the student_medicine field.
     * @var        string
     */
    protected $student_medicine;

    /**
     * The value for the medical_emergency_name field.
     * @var        string
     */
    protected $medical_emergency_name;

    /**
     * The value for the medical_emergency_phone field.
     * @var        string
     */
    protected $medical_emergency_phone;

    /**
     * The value for the medical_emergency_address field.
     * @var        string
     */
    protected $medical_emergency_address;

    /**
     * The value for the parent_statement_name field.
     * @var        string
     */
    protected $parent_statement_name;

    /**
     * The value for the student_statement_name field.
     * @var        string
     */
    protected $student_statement_name;

    /**
     * The value for the parent_signature field.
     * @var        string
     */
    protected $parent_signature;

    /**
     * The value for the date_of_signature field.
     * @var        string
     */
    protected $date_of_signature;

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
     * @var        Application
     */
    protected $aApplication;

    /**
     * @var        PropelObjectCollection|Student[] Collection to store aggregation of Student objects.
     */
    protected $collStudents;
    protected $collStudentsPartial;

    /**
     * @var        PropelObjectCollection|StudentCondition[] Collection to store aggregation of StudentCondition objects.
     */
    protected $collStudentConditions;
    protected $collStudentConditionsPartial;

    /**
     * @var        PropelObjectCollection|StudentMedical[] Collection to store aggregation of StudentMedical objects.
     */
    protected $collStudentMedicals;
    protected $collStudentMedicalsPartial;

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
    protected $studentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $studentConditionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $studentMedicalsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->allergies = false;
    }

    /**
     * Initializes internal state of BaseSchoolHealth object.
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
     * Get the [application_id] column value.
     *
     * @return int
     */
    public function getApplicationId()
    {

        return $this->application_id;
    }

    /**
     * Get the [student_name] column value.
     *
     * @return string
     */
    public function getStudentName()
    {

        return $this->student_name;
    }

    /**
     * Get the [emergency_physician_name] column value.
     *
     * @return string
     */
    public function getEmergencyPhysicianName()
    {

        return $this->emergency_physician_name;
    }

    /**
     * Get the [emergency_relationship] column value.
     *
     * @return string
     */
    public function getEmergencyRelationship()
    {

        return $this->emergency_relationship;
    }

    /**
     * Get the [emergency_phone] column value.
     *
     * @return string
     */
    public function getEmergencyPhone()
    {

        return $this->emergency_phone;
    }

    /**
     * Get the [allergies] column value.
     *
     * @return boolean
     */
    public function getAllergies()
    {

        return $this->allergies;
    }

    /**
     * Get the [allergies_yes] column value.
     *
     * @return string
     */
    public function getAllergiesYes()
    {

        return $this->allergies_yes;
    }

    /**
     * Get the [allergies_action] column value.
     *
     * @return string
     */
    public function getAllergiesAction()
    {

        return $this->allergies_action;
    }

    /**
     * Get the [condition_choice] column value.
     *
     * @return string
     */
    public function getConditionChoice()
    {

        return $this->condition_choice;
    }

    /**
     * Get the [condition_exp] column value.
     *
     * @return string
     */
    public function getConditionExp()
    {

        return $this->condition_exp;
    }

    /**
     * Get the [student_psychological] column value.
     *
     * @return boolean
     */
    public function getStudentPsychological()
    {

        return $this->student_psychological;
    }

    /**
     * Get the [psychological_exp] column value.
     *
     * @return string
     */
    public function getPsychologicalExp()
    {

        return $this->psychological_exp;
    }

    /**
     * Get the [student_aware] column value.
     *
     * @return boolean
     */
    public function getStudentAware()
    {

        return $this->student_aware;
    }

    /**
     * Get the [aware_exp] column value.
     *
     * @return string
     */
    public function getAwareExp()
    {

        return $this->aware_exp;
    }

    /**
     * Get the [student_ability] column value.
     *
     * @return boolean
     */
    public function getStudentAbility()
    {

        return $this->student_ability;
    }

    /**
     * Get the [student_medicine] column value.
     *
     * @return string
     */
    public function getStudentMedicine()
    {

        return $this->student_medicine;
    }

    /**
     * Get the [medical_emergency_name] column value.
     *
     * @return string
     */
    public function getMedicalEmergencyName()
    {

        return $this->medical_emergency_name;
    }

    /**
     * Get the [medical_emergency_phone] column value.
     *
     * @return string
     */
    public function getMedicalEmergencyPhone()
    {

        return $this->medical_emergency_phone;
    }

    /**
     * Get the [medical_emergency_address] column value.
     *
     * @return string
     */
    public function getMedicalEmergencyAddress()
    {

        return $this->medical_emergency_address;
    }

    /**
     * Get the [parent_statement_name] column value.
     *
     * @return string
     */
    public function getParentStatementName()
    {

        return $this->parent_statement_name;
    }

    /**
     * Get the [student_statement_name] column value.
     *
     * @return string
     */
    public function getStudentStatementName()
    {

        return $this->student_statement_name;
    }

    /**
     * Get the [parent_signature] column value.
     *
     * @return string
     */
    public function getParentSignature()
    {

        return $this->parent_signature;
    }

    /**
     * Get the [optionally formatted] temporal [date_of_signature] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDateOfSignature($format = null)
    {
        if ($this->date_of_signature === null) {
            return null;
        }

        if ($this->date_of_signature === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->date_of_signature);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->date_of_signature, true), $x);
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
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [application_id] column.
     *
     * @param  int $v new value
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setApplicationId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->application_id !== $v) {
            $this->application_id = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::APPLICATION_ID;
        }

        if ($this->aApplication !== null && $this->aApplication->getId() !== $v) {
            $this->aApplication = null;
        }


        return $this;
    } // setApplicationId()

    /**
     * Set the value of [student_name] column.
     *
     * @param  string $v new value
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setStudentName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->student_name !== $v) {
            $this->student_name = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::STUDENT_NAME;
        }


        return $this;
    } // setStudentName()

    /**
     * Set the value of [emergency_physician_name] column.
     *
     * @param  string $v new value
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setEmergencyPhysicianName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->emergency_physician_name !== $v) {
            $this->emergency_physician_name = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::EMERGENCY_PHYSICIAN_NAME;
        }


        return $this;
    } // setEmergencyPhysicianName()

    /**
     * Set the value of [emergency_relationship] column.
     *
     * @param  string $v new value
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setEmergencyRelationship($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->emergency_relationship !== $v) {
            $this->emergency_relationship = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::EMERGENCY_RELATIONSHIP;
        }


        return $this;
    } // setEmergencyRelationship()

    /**
     * Set the value of [emergency_phone] column.
     *
     * @param  string $v new value
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setEmergencyPhone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->emergency_phone !== $v) {
            $this->emergency_phone = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::EMERGENCY_PHONE;
        }


        return $this;
    } // setEmergencyPhone()

    /**
     * Sets the value of the [allergies] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setAllergies($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->allergies !== $v) {
            $this->allergies = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::ALLERGIES;
        }


        return $this;
    } // setAllergies()

    /**
     * Set the value of [allergies_yes] column.
     *
     * @param  string $v new value
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setAllergiesYes($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->allergies_yes !== $v) {
            $this->allergies_yes = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::ALLERGIES_YES;
        }


        return $this;
    } // setAllergiesYes()

    /**
     * Set the value of [allergies_action] column.
     *
     * @param  string $v new value
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setAllergiesAction($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->allergies_action !== $v) {
            $this->allergies_action = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::ALLERGIES_ACTION;
        }


        return $this;
    } // setAllergiesAction()

    /**
     * Set the value of [condition_choice] column.
     *
     * @param  string $v new value
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setConditionChoice($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->condition_choice !== $v) {
            $this->condition_choice = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::CONDITION_CHOICE;
        }


        return $this;
    } // setConditionChoice()

    /**
     * Set the value of [condition_exp] column.
     *
     * @param  string $v new value
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setConditionExp($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->condition_exp !== $v) {
            $this->condition_exp = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::CONDITION_EXP;
        }


        return $this;
    } // setConditionExp()

    /**
     * Sets the value of the [student_psychological] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setStudentPsychological($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->student_psychological !== $v) {
            $this->student_psychological = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::STUDENT_PSYCHOLOGICAL;
        }


        return $this;
    } // setStudentPsychological()

    /**
     * Set the value of [psychological_exp] column.
     *
     * @param  string $v new value
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setPsychologicalExp($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->psychological_exp !== $v) {
            $this->psychological_exp = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::PSYCHOLOGICAL_EXP;
        }


        return $this;
    } // setPsychologicalExp()

    /**
     * Sets the value of the [student_aware] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setStudentAware($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->student_aware !== $v) {
            $this->student_aware = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::STUDENT_AWARE;
        }


        return $this;
    } // setStudentAware()

    /**
     * Set the value of [aware_exp] column.
     *
     * @param  string $v new value
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setAwareExp($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->aware_exp !== $v) {
            $this->aware_exp = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::AWARE_EXP;
        }


        return $this;
    } // setAwareExp()

    /**
     * Sets the value of the [student_ability] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setStudentAbility($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->student_ability !== $v) {
            $this->student_ability = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::STUDENT_ABILITY;
        }


        return $this;
    } // setStudentAbility()

    /**
     * Set the value of [student_medicine] column.
     *
     * @param  string $v new value
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setStudentMedicine($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->student_medicine !== $v) {
            $this->student_medicine = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::STUDENT_MEDICINE;
        }


        return $this;
    } // setStudentMedicine()

    /**
     * Set the value of [medical_emergency_name] column.
     *
     * @param  string $v new value
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setMedicalEmergencyName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->medical_emergency_name !== $v) {
            $this->medical_emergency_name = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::MEDICAL_EMERGENCY_NAME;
        }


        return $this;
    } // setMedicalEmergencyName()

    /**
     * Set the value of [medical_emergency_phone] column.
     *
     * @param  string $v new value
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setMedicalEmergencyPhone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->medical_emergency_phone !== $v) {
            $this->medical_emergency_phone = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::MEDICAL_EMERGENCY_PHONE;
        }


        return $this;
    } // setMedicalEmergencyPhone()

    /**
     * Set the value of [medical_emergency_address] column.
     *
     * @param  string $v new value
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setMedicalEmergencyAddress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->medical_emergency_address !== $v) {
            $this->medical_emergency_address = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::MEDICAL_EMERGENCY_ADDRESS;
        }


        return $this;
    } // setMedicalEmergencyAddress()

    /**
     * Set the value of [parent_statement_name] column.
     *
     * @param  string $v new value
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setParentStatementName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->parent_statement_name !== $v) {
            $this->parent_statement_name = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::PARENT_STATEMENT_NAME;
        }


        return $this;
    } // setParentStatementName()

    /**
     * Set the value of [student_statement_name] column.
     *
     * @param  string $v new value
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setStudentStatementName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->student_statement_name !== $v) {
            $this->student_statement_name = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::STUDENT_STATEMENT_NAME;
        }


        return $this;
    } // setStudentStatementName()

    /**
     * Set the value of [parent_signature] column.
     *
     * @param  string $v new value
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setParentSignature($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->parent_signature !== $v) {
            $this->parent_signature = $v;
            $this->modifiedColumns[] = SchoolHealthPeer::PARENT_SIGNATURE;
        }


        return $this;
    } // setParentSignature()

    /**
     * Sets the value of [date_of_signature] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setDateOfSignature($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date_of_signature !== null || $dt !== null) {
            $currentDateAsString = ($this->date_of_signature !== null && $tmpDt = new DateTime($this->date_of_signature)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->date_of_signature = $newDateAsString;
                $this->modifiedColumns[] = SchoolHealthPeer::DATE_OF_SIGNATURE;
            }
        } // if either are not null


        return $this;
    } // setDateOfSignature()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = SchoolHealthPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = SchoolHealthPeer::UPDATED_AT;
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
            if ($this->allergies !== false) {
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
            $this->application_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->student_name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->emergency_physician_name = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->emergency_relationship = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->emergency_phone = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->allergies = ($row[$startcol + 6] !== null) ? (boolean) $row[$startcol + 6] : null;
            $this->allergies_yes = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->allergies_action = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->condition_choice = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->condition_exp = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->student_psychological = ($row[$startcol + 11] !== null) ? (boolean) $row[$startcol + 11] : null;
            $this->psychological_exp = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
            $this->student_aware = ($row[$startcol + 13] !== null) ? (boolean) $row[$startcol + 13] : null;
            $this->aware_exp = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
            $this->student_ability = ($row[$startcol + 15] !== null) ? (boolean) $row[$startcol + 15] : null;
            $this->student_medicine = ($row[$startcol + 16] !== null) ? (string) $row[$startcol + 16] : null;
            $this->medical_emergency_name = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
            $this->medical_emergency_phone = ($row[$startcol + 18] !== null) ? (string) $row[$startcol + 18] : null;
            $this->medical_emergency_address = ($row[$startcol + 19] !== null) ? (string) $row[$startcol + 19] : null;
            $this->parent_statement_name = ($row[$startcol + 20] !== null) ? (string) $row[$startcol + 20] : null;
            $this->student_statement_name = ($row[$startcol + 21] !== null) ? (string) $row[$startcol + 21] : null;
            $this->parent_signature = ($row[$startcol + 22] !== null) ? (string) $row[$startcol + 22] : null;
            $this->date_of_signature = ($row[$startcol + 23] !== null) ? (string) $row[$startcol + 23] : null;
            $this->created_at = ($row[$startcol + 24] !== null) ? (string) $row[$startcol + 24] : null;
            $this->updated_at = ($row[$startcol + 25] !== null) ? (string) $row[$startcol + 25] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 26; // 26 = SchoolHealthPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating SchoolHealth object", $e);
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

        if ($this->aApplication !== null && $this->application_id !== $this->aApplication->getId()) {
            $this->aApplication = null;
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
            $con = Propel::getConnection(SchoolHealthPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = SchoolHealthPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aApplication = null;
            $this->collStudents = null;

            $this->collStudentConditions = null;

            $this->collStudentMedicals = null;

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
            $con = Propel::getConnection(SchoolHealthPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
            $deleteQuery = SchoolHealthQuery::create()
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
            $con = Propel::getConnection(SchoolHealthPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                if (!$this->isColumnModified(SchoolHealthPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(SchoolHealthPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(SchoolHealthPeer::UPDATED_AT)) {
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
                SchoolHealthPeer::addInstanceToPool($this);
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

            if ($this->aApplication !== null) {
                if ($this->aApplication->isModified() || $this->aApplication->isNew()) {
                    $affectedRows += $this->aApplication->save($con);
                }
                $this->setApplication($this->aApplication);
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

            if ($this->studentsScheduledForDeletion !== null) {
                if (!$this->studentsScheduledForDeletion->isEmpty()) {
                    foreach ($this->studentsScheduledForDeletion as $student) {
                        // need to save related object because we set the relation to null
                        $student->save($con);
                    }
                    $this->studentsScheduledForDeletion = null;
                }
            }

            if ($this->collStudents !== null) {
                foreach ($this->collStudents as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->studentConditionsScheduledForDeletion !== null) {
                if (!$this->studentConditionsScheduledForDeletion->isEmpty()) {
                    StudentConditionQuery::create()
                        ->filterByPrimaryKeys($this->studentConditionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->studentConditionsScheduledForDeletion = null;
                }
            }

            if ($this->collStudentConditions !== null) {
                foreach ($this->collStudentConditions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->studentMedicalsScheduledForDeletion !== null) {
                if (!$this->studentMedicalsScheduledForDeletion->isEmpty()) {
                    StudentMedicalQuery::create()
                        ->filterByPrimaryKeys($this->studentMedicalsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->studentMedicalsScheduledForDeletion = null;
                }
            }

            if ($this->collStudentMedicals !== null) {
                foreach ($this->collStudentMedicals as $referrerFK) {
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

        $this->modifiedColumns[] = SchoolHealthPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . SchoolHealthPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(SchoolHealthPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::APPLICATION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`application_id`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::STUDENT_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`student_name`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::EMERGENCY_PHYSICIAN_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`emergency_physician_name`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::EMERGENCY_RELATIONSHIP)) {
            $modifiedColumns[':p' . $index++]  = '`emergency_relationship`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::EMERGENCY_PHONE)) {
            $modifiedColumns[':p' . $index++]  = '`emergency_phone`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::ALLERGIES)) {
            $modifiedColumns[':p' . $index++]  = '`allergies`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::ALLERGIES_YES)) {
            $modifiedColumns[':p' . $index++]  = '`allergies_yes`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::ALLERGIES_ACTION)) {
            $modifiedColumns[':p' . $index++]  = '`allergies_action`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::CONDITION_CHOICE)) {
            $modifiedColumns[':p' . $index++]  = '`condition_choice`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::CONDITION_EXP)) {
            $modifiedColumns[':p' . $index++]  = '`condition_exp`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::STUDENT_PSYCHOLOGICAL)) {
            $modifiedColumns[':p' . $index++]  = '`student_psychological`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::PSYCHOLOGICAL_EXP)) {
            $modifiedColumns[':p' . $index++]  = '`psychological_exp`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::STUDENT_AWARE)) {
            $modifiedColumns[':p' . $index++]  = '`student_aware`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::AWARE_EXP)) {
            $modifiedColumns[':p' . $index++]  = '`aware_exp`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::STUDENT_ABILITY)) {
            $modifiedColumns[':p' . $index++]  = '`student_ability`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::STUDENT_MEDICINE)) {
            $modifiedColumns[':p' . $index++]  = '`student_medicine`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::MEDICAL_EMERGENCY_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`medical_emergency_name`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::MEDICAL_EMERGENCY_PHONE)) {
            $modifiedColumns[':p' . $index++]  = '`medical_emergency_phone`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::MEDICAL_EMERGENCY_ADDRESS)) {
            $modifiedColumns[':p' . $index++]  = '`medical_emergency_address`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::PARENT_STATEMENT_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`parent_statement_name`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::STUDENT_STATEMENT_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`student_statement_name`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::PARENT_SIGNATURE)) {
            $modifiedColumns[':p' . $index++]  = '`parent_signature`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::DATE_OF_SIGNATURE)) {
            $modifiedColumns[':p' . $index++]  = '`date_of_signature`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(SchoolHealthPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `school_health` (%s) VALUES (%s)',
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
                    case '`application_id`':
                        $stmt->bindValue($identifier, $this->application_id, PDO::PARAM_INT);
                        break;
                    case '`student_name`':
                        $stmt->bindValue($identifier, $this->student_name, PDO::PARAM_STR);
                        break;
                    case '`emergency_physician_name`':
                        $stmt->bindValue($identifier, $this->emergency_physician_name, PDO::PARAM_STR);
                        break;
                    case '`emergency_relationship`':
                        $stmt->bindValue($identifier, $this->emergency_relationship, PDO::PARAM_STR);
                        break;
                    case '`emergency_phone`':
                        $stmt->bindValue($identifier, $this->emergency_phone, PDO::PARAM_STR);
                        break;
                    case '`allergies`':
                        $stmt->bindValue($identifier, (int) $this->allergies, PDO::PARAM_INT);
                        break;
                    case '`allergies_yes`':
                        $stmt->bindValue($identifier, $this->allergies_yes, PDO::PARAM_STR);
                        break;
                    case '`allergies_action`':
                        $stmt->bindValue($identifier, $this->allergies_action, PDO::PARAM_STR);
                        break;
                    case '`condition_choice`':
                        $stmt->bindValue($identifier, $this->condition_choice, PDO::PARAM_STR);
                        break;
                    case '`condition_exp`':
                        $stmt->bindValue($identifier, $this->condition_exp, PDO::PARAM_STR);
                        break;
                    case '`student_psychological`':
                        $stmt->bindValue($identifier, (int) $this->student_psychological, PDO::PARAM_INT);
                        break;
                    case '`psychological_exp`':
                        $stmt->bindValue($identifier, $this->psychological_exp, PDO::PARAM_STR);
                        break;
                    case '`student_aware`':
                        $stmt->bindValue($identifier, (int) $this->student_aware, PDO::PARAM_INT);
                        break;
                    case '`aware_exp`':
                        $stmt->bindValue($identifier, $this->aware_exp, PDO::PARAM_STR);
                        break;
                    case '`student_ability`':
                        $stmt->bindValue($identifier, (int) $this->student_ability, PDO::PARAM_INT);
                        break;
                    case '`student_medicine`':
                        $stmt->bindValue($identifier, $this->student_medicine, PDO::PARAM_STR);
                        break;
                    case '`medical_emergency_name`':
                        $stmt->bindValue($identifier, $this->medical_emergency_name, PDO::PARAM_STR);
                        break;
                    case '`medical_emergency_phone`':
                        $stmt->bindValue($identifier, $this->medical_emergency_phone, PDO::PARAM_STR);
                        break;
                    case '`medical_emergency_address`':
                        $stmt->bindValue($identifier, $this->medical_emergency_address, PDO::PARAM_STR);
                        break;
                    case '`parent_statement_name`':
                        $stmt->bindValue($identifier, $this->parent_statement_name, PDO::PARAM_STR);
                        break;
                    case '`student_statement_name`':
                        $stmt->bindValue($identifier, $this->student_statement_name, PDO::PARAM_STR);
                        break;
                    case '`parent_signature`':
                        $stmt->bindValue($identifier, $this->parent_signature, PDO::PARAM_STR);
                        break;
                    case '`date_of_signature`':
                        $stmt->bindValue($identifier, $this->date_of_signature, PDO::PARAM_STR);
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

            if ($this->aApplication !== null) {
                if (!$this->aApplication->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aApplication->getValidationFailures());
                }
            }


            if (($retval = SchoolHealthPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collStudents !== null) {
                    foreach ($this->collStudents as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collStudentConditions !== null) {
                    foreach ($this->collStudentConditions as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collStudentMedicals !== null) {
                    foreach ($this->collStudentMedicals as $referrerFK) {
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
        $pos = SchoolHealthPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getApplicationId();
                break;
            case 2:
                return $this->getStudentName();
                break;
            case 3:
                return $this->getEmergencyPhysicianName();
                break;
            case 4:
                return $this->getEmergencyRelationship();
                break;
            case 5:
                return $this->getEmergencyPhone();
                break;
            case 6:
                return $this->getAllergies();
                break;
            case 7:
                return $this->getAllergiesYes();
                break;
            case 8:
                return $this->getAllergiesAction();
                break;
            case 9:
                return $this->getConditionChoice();
                break;
            case 10:
                return $this->getConditionExp();
                break;
            case 11:
                return $this->getStudentPsychological();
                break;
            case 12:
                return $this->getPsychologicalExp();
                break;
            case 13:
                return $this->getStudentAware();
                break;
            case 14:
                return $this->getAwareExp();
                break;
            case 15:
                return $this->getStudentAbility();
                break;
            case 16:
                return $this->getStudentMedicine();
                break;
            case 17:
                return $this->getMedicalEmergencyName();
                break;
            case 18:
                return $this->getMedicalEmergencyPhone();
                break;
            case 19:
                return $this->getMedicalEmergencyAddress();
                break;
            case 20:
                return $this->getParentStatementName();
                break;
            case 21:
                return $this->getStudentStatementName();
                break;
            case 22:
                return $this->getParentSignature();
                break;
            case 23:
                return $this->getDateOfSignature();
                break;
            case 24:
                return $this->getCreatedAt();
                break;
            case 25:
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
        if (isset($alreadyDumpedObjects['SchoolHealth'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['SchoolHealth'][$this->getPrimaryKey()] = true;
        $keys = SchoolHealthPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getApplicationId(),
            $keys[2] => $this->getStudentName(),
            $keys[3] => $this->getEmergencyPhysicianName(),
            $keys[4] => $this->getEmergencyRelationship(),
            $keys[5] => $this->getEmergencyPhone(),
            $keys[6] => $this->getAllergies(),
            $keys[7] => $this->getAllergiesYes(),
            $keys[8] => $this->getAllergiesAction(),
            $keys[9] => $this->getConditionChoice(),
            $keys[10] => $this->getConditionExp(),
            $keys[11] => $this->getStudentPsychological(),
            $keys[12] => $this->getPsychologicalExp(),
            $keys[13] => $this->getStudentAware(),
            $keys[14] => $this->getAwareExp(),
            $keys[15] => $this->getStudentAbility(),
            $keys[16] => $this->getStudentMedicine(),
            $keys[17] => $this->getMedicalEmergencyName(),
            $keys[18] => $this->getMedicalEmergencyPhone(),
            $keys[19] => $this->getMedicalEmergencyAddress(),
            $keys[20] => $this->getParentStatementName(),
            $keys[21] => $this->getStudentStatementName(),
            $keys[22] => $this->getParentSignature(),
            $keys[23] => $this->getDateOfSignature(),
            $keys[24] => $this->getCreatedAt(),
            $keys[25] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aApplication) {
                $result['Application'] = $this->aApplication->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collStudents) {
                $result['Students'] = $this->collStudents->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStudentConditions) {
                $result['StudentConditions'] = $this->collStudentConditions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStudentMedicals) {
                $result['StudentMedicals'] = $this->collStudentMedicals->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = SchoolHealthPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setApplicationId($value);
                break;
            case 2:
                $this->setStudentName($value);
                break;
            case 3:
                $this->setEmergencyPhysicianName($value);
                break;
            case 4:
                $this->setEmergencyRelationship($value);
                break;
            case 5:
                $this->setEmergencyPhone($value);
                break;
            case 6:
                $this->setAllergies($value);
                break;
            case 7:
                $this->setAllergiesYes($value);
                break;
            case 8:
                $this->setAllergiesAction($value);
                break;
            case 9:
                $this->setConditionChoice($value);
                break;
            case 10:
                $this->setConditionExp($value);
                break;
            case 11:
                $this->setStudentPsychological($value);
                break;
            case 12:
                $this->setPsychologicalExp($value);
                break;
            case 13:
                $this->setStudentAware($value);
                break;
            case 14:
                $this->setAwareExp($value);
                break;
            case 15:
                $this->setStudentAbility($value);
                break;
            case 16:
                $this->setStudentMedicine($value);
                break;
            case 17:
                $this->setMedicalEmergencyName($value);
                break;
            case 18:
                $this->setMedicalEmergencyPhone($value);
                break;
            case 19:
                $this->setMedicalEmergencyAddress($value);
                break;
            case 20:
                $this->setParentStatementName($value);
                break;
            case 21:
                $this->setStudentStatementName($value);
                break;
            case 22:
                $this->setParentSignature($value);
                break;
            case 23:
                $this->setDateOfSignature($value);
                break;
            case 24:
                $this->setCreatedAt($value);
                break;
            case 25:
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
        $keys = SchoolHealthPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setApplicationId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setStudentName($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setEmergencyPhysicianName($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setEmergencyRelationship($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setEmergencyPhone($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setAllergies($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setAllergiesYes($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setAllergiesAction($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setConditionChoice($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setConditionExp($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setStudentPsychological($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setPsychologicalExp($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setStudentAware($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setAwareExp($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setStudentAbility($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setStudentMedicine($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setMedicalEmergencyName($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setMedicalEmergencyPhone($arr[$keys[18]]);
        if (array_key_exists($keys[19], $arr)) $this->setMedicalEmergencyAddress($arr[$keys[19]]);
        if (array_key_exists($keys[20], $arr)) $this->setParentStatementName($arr[$keys[20]]);
        if (array_key_exists($keys[21], $arr)) $this->setStudentStatementName($arr[$keys[21]]);
        if (array_key_exists($keys[22], $arr)) $this->setParentSignature($arr[$keys[22]]);
        if (array_key_exists($keys[23], $arr)) $this->setDateOfSignature($arr[$keys[23]]);
        if (array_key_exists($keys[24], $arr)) $this->setCreatedAt($arr[$keys[24]]);
        if (array_key_exists($keys[25], $arr)) $this->setUpdatedAt($arr[$keys[25]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(SchoolHealthPeer::DATABASE_NAME);

        if ($this->isColumnModified(SchoolHealthPeer::ID)) $criteria->add(SchoolHealthPeer::ID, $this->id);
        if ($this->isColumnModified(SchoolHealthPeer::APPLICATION_ID)) $criteria->add(SchoolHealthPeer::APPLICATION_ID, $this->application_id);
        if ($this->isColumnModified(SchoolHealthPeer::STUDENT_NAME)) $criteria->add(SchoolHealthPeer::STUDENT_NAME, $this->student_name);
        if ($this->isColumnModified(SchoolHealthPeer::EMERGENCY_PHYSICIAN_NAME)) $criteria->add(SchoolHealthPeer::EMERGENCY_PHYSICIAN_NAME, $this->emergency_physician_name);
        if ($this->isColumnModified(SchoolHealthPeer::EMERGENCY_RELATIONSHIP)) $criteria->add(SchoolHealthPeer::EMERGENCY_RELATIONSHIP, $this->emergency_relationship);
        if ($this->isColumnModified(SchoolHealthPeer::EMERGENCY_PHONE)) $criteria->add(SchoolHealthPeer::EMERGENCY_PHONE, $this->emergency_phone);
        if ($this->isColumnModified(SchoolHealthPeer::ALLERGIES)) $criteria->add(SchoolHealthPeer::ALLERGIES, $this->allergies);
        if ($this->isColumnModified(SchoolHealthPeer::ALLERGIES_YES)) $criteria->add(SchoolHealthPeer::ALLERGIES_YES, $this->allergies_yes);
        if ($this->isColumnModified(SchoolHealthPeer::ALLERGIES_ACTION)) $criteria->add(SchoolHealthPeer::ALLERGIES_ACTION, $this->allergies_action);
        if ($this->isColumnModified(SchoolHealthPeer::CONDITION_CHOICE)) $criteria->add(SchoolHealthPeer::CONDITION_CHOICE, $this->condition_choice);
        if ($this->isColumnModified(SchoolHealthPeer::CONDITION_EXP)) $criteria->add(SchoolHealthPeer::CONDITION_EXP, $this->condition_exp);
        if ($this->isColumnModified(SchoolHealthPeer::STUDENT_PSYCHOLOGICAL)) $criteria->add(SchoolHealthPeer::STUDENT_PSYCHOLOGICAL, $this->student_psychological);
        if ($this->isColumnModified(SchoolHealthPeer::PSYCHOLOGICAL_EXP)) $criteria->add(SchoolHealthPeer::PSYCHOLOGICAL_EXP, $this->psychological_exp);
        if ($this->isColumnModified(SchoolHealthPeer::STUDENT_AWARE)) $criteria->add(SchoolHealthPeer::STUDENT_AWARE, $this->student_aware);
        if ($this->isColumnModified(SchoolHealthPeer::AWARE_EXP)) $criteria->add(SchoolHealthPeer::AWARE_EXP, $this->aware_exp);
        if ($this->isColumnModified(SchoolHealthPeer::STUDENT_ABILITY)) $criteria->add(SchoolHealthPeer::STUDENT_ABILITY, $this->student_ability);
        if ($this->isColumnModified(SchoolHealthPeer::STUDENT_MEDICINE)) $criteria->add(SchoolHealthPeer::STUDENT_MEDICINE, $this->student_medicine);
        if ($this->isColumnModified(SchoolHealthPeer::MEDICAL_EMERGENCY_NAME)) $criteria->add(SchoolHealthPeer::MEDICAL_EMERGENCY_NAME, $this->medical_emergency_name);
        if ($this->isColumnModified(SchoolHealthPeer::MEDICAL_EMERGENCY_PHONE)) $criteria->add(SchoolHealthPeer::MEDICAL_EMERGENCY_PHONE, $this->medical_emergency_phone);
        if ($this->isColumnModified(SchoolHealthPeer::MEDICAL_EMERGENCY_ADDRESS)) $criteria->add(SchoolHealthPeer::MEDICAL_EMERGENCY_ADDRESS, $this->medical_emergency_address);
        if ($this->isColumnModified(SchoolHealthPeer::PARENT_STATEMENT_NAME)) $criteria->add(SchoolHealthPeer::PARENT_STATEMENT_NAME, $this->parent_statement_name);
        if ($this->isColumnModified(SchoolHealthPeer::STUDENT_STATEMENT_NAME)) $criteria->add(SchoolHealthPeer::STUDENT_STATEMENT_NAME, $this->student_statement_name);
        if ($this->isColumnModified(SchoolHealthPeer::PARENT_SIGNATURE)) $criteria->add(SchoolHealthPeer::PARENT_SIGNATURE, $this->parent_signature);
        if ($this->isColumnModified(SchoolHealthPeer::DATE_OF_SIGNATURE)) $criteria->add(SchoolHealthPeer::DATE_OF_SIGNATURE, $this->date_of_signature);
        if ($this->isColumnModified(SchoolHealthPeer::CREATED_AT)) $criteria->add(SchoolHealthPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(SchoolHealthPeer::UPDATED_AT)) $criteria->add(SchoolHealthPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(SchoolHealthPeer::DATABASE_NAME);
        $criteria->add(SchoolHealthPeer::ID, $this->id);

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
     * @param object $copyObj An object of SchoolHealth (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setApplicationId($this->getApplicationId());
        $copyObj->setStudentName($this->getStudentName());
        $copyObj->setEmergencyPhysicianName($this->getEmergencyPhysicianName());
        $copyObj->setEmergencyRelationship($this->getEmergencyRelationship());
        $copyObj->setEmergencyPhone($this->getEmergencyPhone());
        $copyObj->setAllergies($this->getAllergies());
        $copyObj->setAllergiesYes($this->getAllergiesYes());
        $copyObj->setAllergiesAction($this->getAllergiesAction());
        $copyObj->setConditionChoice($this->getConditionChoice());
        $copyObj->setConditionExp($this->getConditionExp());
        $copyObj->setStudentPsychological($this->getStudentPsychological());
        $copyObj->setPsychologicalExp($this->getPsychologicalExp());
        $copyObj->setStudentAware($this->getStudentAware());
        $copyObj->setAwareExp($this->getAwareExp());
        $copyObj->setStudentAbility($this->getStudentAbility());
        $copyObj->setStudentMedicine($this->getStudentMedicine());
        $copyObj->setMedicalEmergencyName($this->getMedicalEmergencyName());
        $copyObj->setMedicalEmergencyPhone($this->getMedicalEmergencyPhone());
        $copyObj->setMedicalEmergencyAddress($this->getMedicalEmergencyAddress());
        $copyObj->setParentStatementName($this->getParentStatementName());
        $copyObj->setStudentStatementName($this->getStudentStatementName());
        $copyObj->setParentSignature($this->getParentSignature());
        $copyObj->setDateOfSignature($this->getDateOfSignature());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getStudents() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStudent($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStudentConditions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStudentCondition($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStudentMedicals() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStudentMedical($relObj->copy($deepCopy));
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
     * @return SchoolHealth Clone of current object.
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
     * @return SchoolHealthPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new SchoolHealthPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Application object.
     *
     * @param                  Application $v
     * @return SchoolHealth The current object (for fluent API support)
     * @throws PropelException
     */
    public function setApplication(Application $v = null)
    {
        if ($v === null) {
            $this->setApplicationId(NULL);
        } else {
            $this->setApplicationId($v->getId());
        }

        $this->aApplication = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Application object, it will not be re-added.
        if ($v !== null) {
            $v->addSchoolHealth($this);
        }


        return $this;
    }


    /**
     * Get the associated Application object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Application The associated Application object.
     * @throws PropelException
     */
    public function getApplication(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aApplication === null && ($this->application_id !== null) && $doQuery) {
            $this->aApplication = ApplicationQuery::create()->findPk($this->application_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aApplication->addSchoolHealths($this);
             */
        }

        return $this->aApplication;
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
        if ('Student' == $relationName) {
            $this->initStudents();
        }
        if ('StudentCondition' == $relationName) {
            $this->initStudentConditions();
        }
        if ('StudentMedical' == $relationName) {
            $this->initStudentMedicals();
        }
    }

    /**
     * Clears out the collStudents collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return SchoolHealth The current object (for fluent API support)
     * @see        addStudents()
     */
    public function clearStudents()
    {
        $this->collStudents = null; // important to set this to null since that means it is uninitialized
        $this->collStudentsPartial = null;

        return $this;
    }

    /**
     * reset is the collStudents collection loaded partially
     *
     * @return void
     */
    public function resetPartialStudents($v = true)
    {
        $this->collStudentsPartial = $v;
    }

    /**
     * Initializes the collStudents collection.
     *
     * By default this just sets the collStudents collection to an empty array (like clearcollStudents());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStudents($overrideExisting = true)
    {
        if (null !== $this->collStudents && !$overrideExisting) {
            return;
        }
        $this->collStudents = new PropelObjectCollection();
        $this->collStudents->setModel('Student');
    }

    /**
     * Gets an array of Student objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this SchoolHealth is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Student[] List of Student objects
     * @throws PropelException
     */
    public function getStudents($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collStudentsPartial && !$this->isNew();
        if (null === $this->collStudents || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collStudents) {
                // return empty collection
                $this->initStudents();
            } else {
                $collStudents = StudentQuery::create(null, $criteria)
                    ->filterBySchoolHealth($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collStudentsPartial && count($collStudents)) {
                      $this->initStudents(false);

                      foreach ($collStudents as $obj) {
                        if (false == $this->collStudents->contains($obj)) {
                          $this->collStudents->append($obj);
                        }
                      }

                      $this->collStudentsPartial = true;
                    }

                    $collStudents->getInternalIterator()->rewind();

                    return $collStudents;
                }

                if ($partial && $this->collStudents) {
                    foreach ($this->collStudents as $obj) {
                        if ($obj->isNew()) {
                            $collStudents[] = $obj;
                        }
                    }
                }

                $this->collStudents = $collStudents;
                $this->collStudentsPartial = false;
            }
        }

        return $this->collStudents;
    }

    /**
     * Sets a collection of Student objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $students A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setStudents(PropelCollection $students, PropelPDO $con = null)
    {
        $studentsToDelete = $this->getStudents(new Criteria(), $con)->diff($students);


        $this->studentsScheduledForDeletion = $studentsToDelete;

        foreach ($studentsToDelete as $studentRemoved) {
            $studentRemoved->setSchoolHealth(null);
        }

        $this->collStudents = null;
        foreach ($students as $student) {
            $this->addStudent($student);
        }

        $this->collStudents = $students;
        $this->collStudentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Student objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Student objects.
     * @throws PropelException
     */
    public function countStudents(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collStudentsPartial && !$this->isNew();
        if (null === $this->collStudents || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStudents) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStudents());
            }
            $query = StudentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySchoolHealth($this)
                ->count($con);
        }

        return count($this->collStudents);
    }

    /**
     * Method called to associate a Student object to this object
     * through the Student foreign key attribute.
     *
     * @param    Student $l Student
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function addStudent(Student $l)
    {
        if ($this->collStudents === null) {
            $this->initStudents();
            $this->collStudentsPartial = true;
        }

        if (!in_array($l, $this->collStudents->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddStudent($l);

            if ($this->studentsScheduledForDeletion and $this->studentsScheduledForDeletion->contains($l)) {
                $this->studentsScheduledForDeletion->remove($this->studentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Student $student The student object to add.
     */
    protected function doAddStudent($student)
    {
        $this->collStudents[]= $student;
        $student->setSchoolHealth($this);
    }

    /**
     * @param	Student $student The student object to remove.
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function removeStudent($student)
    {
        if ($this->getStudents()->contains($student)) {
            $this->collStudents->remove($this->collStudents->search($student));
            if (null === $this->studentsScheduledForDeletion) {
                $this->studentsScheduledForDeletion = clone $this->collStudents;
                $this->studentsScheduledForDeletion->clear();
            }
            $this->studentsScheduledForDeletion[]= $student;
            $student->setSchoolHealth(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this SchoolHealth is new, it will return
     * an empty collection; or if this SchoolHealth has previously
     * been saved, it will retrieve related Students from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in SchoolHealth.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Student[] List of Student objects
     */
    public function getStudentsJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StudentQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getStudents($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this SchoolHealth is new, it will return
     * an empty collection; or if this SchoolHealth has previously
     * been saved, it will retrieve related Students from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in SchoolHealth.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Student[] List of Student objects
     */
    public function getStudentsJoinApplication($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StudentQuery::create(null, $criteria);
        $query->joinWith('Application', $join_behavior);

        return $this->getStudents($query, $con);
    }

    /**
     * Clears out the collStudentConditions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return SchoolHealth The current object (for fluent API support)
     * @see        addStudentConditions()
     */
    public function clearStudentConditions()
    {
        $this->collStudentConditions = null; // important to set this to null since that means it is uninitialized
        $this->collStudentConditionsPartial = null;

        return $this;
    }

    /**
     * reset is the collStudentConditions collection loaded partially
     *
     * @return void
     */
    public function resetPartialStudentConditions($v = true)
    {
        $this->collStudentConditionsPartial = $v;
    }

    /**
     * Initializes the collStudentConditions collection.
     *
     * By default this just sets the collStudentConditions collection to an empty array (like clearcollStudentConditions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStudentConditions($overrideExisting = true)
    {
        if (null !== $this->collStudentConditions && !$overrideExisting) {
            return;
        }
        $this->collStudentConditions = new PropelObjectCollection();
        $this->collStudentConditions->setModel('StudentCondition');
    }

    /**
     * Gets an array of StudentCondition objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this SchoolHealth is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|StudentCondition[] List of StudentCondition objects
     * @throws PropelException
     */
    public function getStudentConditions($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collStudentConditionsPartial && !$this->isNew();
        if (null === $this->collStudentConditions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collStudentConditions) {
                // return empty collection
                $this->initStudentConditions();
            } else {
                $collStudentConditions = StudentConditionQuery::create(null, $criteria)
                    ->filterBySchoolHealth($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collStudentConditionsPartial && count($collStudentConditions)) {
                      $this->initStudentConditions(false);

                      foreach ($collStudentConditions as $obj) {
                        if (false == $this->collStudentConditions->contains($obj)) {
                          $this->collStudentConditions->append($obj);
                        }
                      }

                      $this->collStudentConditionsPartial = true;
                    }

                    $collStudentConditions->getInternalIterator()->rewind();

                    return $collStudentConditions;
                }

                if ($partial && $this->collStudentConditions) {
                    foreach ($this->collStudentConditions as $obj) {
                        if ($obj->isNew()) {
                            $collStudentConditions[] = $obj;
                        }
                    }
                }

                $this->collStudentConditions = $collStudentConditions;
                $this->collStudentConditionsPartial = false;
            }
        }

        return $this->collStudentConditions;
    }

    /**
     * Sets a collection of StudentCondition objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $studentConditions A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setStudentConditions(PropelCollection $studentConditions, PropelPDO $con = null)
    {
        $studentConditionsToDelete = $this->getStudentConditions(new Criteria(), $con)->diff($studentConditions);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->studentConditionsScheduledForDeletion = clone $studentConditionsToDelete;

        foreach ($studentConditionsToDelete as $studentConditionRemoved) {
            $studentConditionRemoved->setSchoolHealth(null);
        }

        $this->collStudentConditions = null;
        foreach ($studentConditions as $studentCondition) {
            $this->addStudentCondition($studentCondition);
        }

        $this->collStudentConditions = $studentConditions;
        $this->collStudentConditionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related StudentCondition objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related StudentCondition objects.
     * @throws PropelException
     */
    public function countStudentConditions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collStudentConditionsPartial && !$this->isNew();
        if (null === $this->collStudentConditions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStudentConditions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStudentConditions());
            }
            $query = StudentConditionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySchoolHealth($this)
                ->count($con);
        }

        return count($this->collStudentConditions);
    }

    /**
     * Method called to associate a StudentCondition object to this object
     * through the StudentCondition foreign key attribute.
     *
     * @param    StudentCondition $l StudentCondition
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function addStudentCondition(StudentCondition $l)
    {
        if ($this->collStudentConditions === null) {
            $this->initStudentConditions();
            $this->collStudentConditionsPartial = true;
        }

        if (!in_array($l, $this->collStudentConditions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddStudentCondition($l);

            if ($this->studentConditionsScheduledForDeletion and $this->studentConditionsScheduledForDeletion->contains($l)) {
                $this->studentConditionsScheduledForDeletion->remove($this->studentConditionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	StudentCondition $studentCondition The studentCondition object to add.
     */
    protected function doAddStudentCondition($studentCondition)
    {
        $this->collStudentConditions[]= $studentCondition;
        $studentCondition->setSchoolHealth($this);
    }

    /**
     * @param	StudentCondition $studentCondition The studentCondition object to remove.
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function removeStudentCondition($studentCondition)
    {
        if ($this->getStudentConditions()->contains($studentCondition)) {
            $this->collStudentConditions->remove($this->collStudentConditions->search($studentCondition));
            if (null === $this->studentConditionsScheduledForDeletion) {
                $this->studentConditionsScheduledForDeletion = clone $this->collStudentConditions;
                $this->studentConditionsScheduledForDeletion->clear();
            }
            $this->studentConditionsScheduledForDeletion[]= clone $studentCondition;
            $studentCondition->setSchoolHealth(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this SchoolHealth is new, it will return
     * an empty collection; or if this SchoolHealth has previously
     * been saved, it will retrieve related StudentConditions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in SchoolHealth.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|StudentCondition[] List of StudentCondition objects
     */
    public function getStudentConditionsJoinCondition($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StudentConditionQuery::create(null, $criteria);
        $query->joinWith('Condition', $join_behavior);

        return $this->getStudentConditions($query, $con);
    }

    /**
     * Clears out the collStudentMedicals collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return SchoolHealth The current object (for fluent API support)
     * @see        addStudentMedicals()
     */
    public function clearStudentMedicals()
    {
        $this->collStudentMedicals = null; // important to set this to null since that means it is uninitialized
        $this->collStudentMedicalsPartial = null;

        return $this;
    }

    /**
     * reset is the collStudentMedicals collection loaded partially
     *
     * @return void
     */
    public function resetPartialStudentMedicals($v = true)
    {
        $this->collStudentMedicalsPartial = $v;
    }

    /**
     * Initializes the collStudentMedicals collection.
     *
     * By default this just sets the collStudentMedicals collection to an empty array (like clearcollStudentMedicals());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStudentMedicals($overrideExisting = true)
    {
        if (null !== $this->collStudentMedicals && !$overrideExisting) {
            return;
        }
        $this->collStudentMedicals = new PropelObjectCollection();
        $this->collStudentMedicals->setModel('StudentMedical');
    }

    /**
     * Gets an array of StudentMedical objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this SchoolHealth is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|StudentMedical[] List of StudentMedical objects
     * @throws PropelException
     */
    public function getStudentMedicals($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collStudentMedicalsPartial && !$this->isNew();
        if (null === $this->collStudentMedicals || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collStudentMedicals) {
                // return empty collection
                $this->initStudentMedicals();
            } else {
                $collStudentMedicals = StudentMedicalQuery::create(null, $criteria)
                    ->filterBySchoolHealth($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collStudentMedicalsPartial && count($collStudentMedicals)) {
                      $this->initStudentMedicals(false);

                      foreach ($collStudentMedicals as $obj) {
                        if (false == $this->collStudentMedicals->contains($obj)) {
                          $this->collStudentMedicals->append($obj);
                        }
                      }

                      $this->collStudentMedicalsPartial = true;
                    }

                    $collStudentMedicals->getInternalIterator()->rewind();

                    return $collStudentMedicals;
                }

                if ($partial && $this->collStudentMedicals) {
                    foreach ($this->collStudentMedicals as $obj) {
                        if ($obj->isNew()) {
                            $collStudentMedicals[] = $obj;
                        }
                    }
                }

                $this->collStudentMedicals = $collStudentMedicals;
                $this->collStudentMedicalsPartial = false;
            }
        }

        return $this->collStudentMedicals;
    }

    /**
     * Sets a collection of StudentMedical objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $studentMedicals A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function setStudentMedicals(PropelCollection $studentMedicals, PropelPDO $con = null)
    {
        $studentMedicalsToDelete = $this->getStudentMedicals(new Criteria(), $con)->diff($studentMedicals);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->studentMedicalsScheduledForDeletion = clone $studentMedicalsToDelete;

        foreach ($studentMedicalsToDelete as $studentMedicalRemoved) {
            $studentMedicalRemoved->setSchoolHealth(null);
        }

        $this->collStudentMedicals = null;
        foreach ($studentMedicals as $studentMedical) {
            $this->addStudentMedical($studentMedical);
        }

        $this->collStudentMedicals = $studentMedicals;
        $this->collStudentMedicalsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related StudentMedical objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related StudentMedical objects.
     * @throws PropelException
     */
    public function countStudentMedicals(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collStudentMedicalsPartial && !$this->isNew();
        if (null === $this->collStudentMedicals || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStudentMedicals) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStudentMedicals());
            }
            $query = StudentMedicalQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySchoolHealth($this)
                ->count($con);
        }

        return count($this->collStudentMedicals);
    }

    /**
     * Method called to associate a StudentMedical object to this object
     * through the StudentMedical foreign key attribute.
     *
     * @param    StudentMedical $l StudentMedical
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function addStudentMedical(StudentMedical $l)
    {
        if ($this->collStudentMedicals === null) {
            $this->initStudentMedicals();
            $this->collStudentMedicalsPartial = true;
        }

        if (!in_array($l, $this->collStudentMedicals->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddStudentMedical($l);

            if ($this->studentMedicalsScheduledForDeletion and $this->studentMedicalsScheduledForDeletion->contains($l)) {
                $this->studentMedicalsScheduledForDeletion->remove($this->studentMedicalsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	StudentMedical $studentMedical The studentMedical object to add.
     */
    protected function doAddStudentMedical($studentMedical)
    {
        $this->collStudentMedicals[]= $studentMedical;
        $studentMedical->setSchoolHealth($this);
    }

    /**
     * @param	StudentMedical $studentMedical The studentMedical object to remove.
     * @return SchoolHealth The current object (for fluent API support)
     */
    public function removeStudentMedical($studentMedical)
    {
        if ($this->getStudentMedicals()->contains($studentMedical)) {
            $this->collStudentMedicals->remove($this->collStudentMedicals->search($studentMedical));
            if (null === $this->studentMedicalsScheduledForDeletion) {
                $this->studentMedicalsScheduledForDeletion = clone $this->collStudentMedicals;
                $this->studentMedicalsScheduledForDeletion->clear();
            }
            $this->studentMedicalsScheduledForDeletion[]= clone $studentMedical;
            $studentMedical->setSchoolHealth(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this SchoolHealth is new, it will return
     * an empty collection; or if this SchoolHealth has previously
     * been saved, it will retrieve related StudentMedicals from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in SchoolHealth.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|StudentMedical[] List of StudentMedical objects
     */
    public function getStudentMedicalsJoinMedical($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StudentMedicalQuery::create(null, $criteria);
        $query->joinWith('Medical', $join_behavior);

        return $this->getStudentMedicals($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->application_id = null;
        $this->student_name = null;
        $this->emergency_physician_name = null;
        $this->emergency_relationship = null;
        $this->emergency_phone = null;
        $this->allergies = null;
        $this->allergies_yes = null;
        $this->allergies_action = null;
        $this->condition_choice = null;
        $this->condition_exp = null;
        $this->student_psychological = null;
        $this->psychological_exp = null;
        $this->student_aware = null;
        $this->aware_exp = null;
        $this->student_ability = null;
        $this->student_medicine = null;
        $this->medical_emergency_name = null;
        $this->medical_emergency_phone = null;
        $this->medical_emergency_address = null;
        $this->parent_statement_name = null;
        $this->student_statement_name = null;
        $this->parent_signature = null;
        $this->date_of_signature = null;
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
            if ($this->collStudents) {
                foreach ($this->collStudents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStudentConditions) {
                foreach ($this->collStudentConditions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStudentMedicals) {
                foreach ($this->collStudentMedicals as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aApplication instanceof Persistent) {
              $this->aApplication->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collStudents instanceof PropelCollection) {
            $this->collStudents->clearIterator();
        }
        $this->collStudents = null;
        if ($this->collStudentConditions instanceof PropelCollection) {
            $this->collStudentConditions->clearIterator();
        }
        $this->collStudentConditions = null;
        if ($this->collStudentMedicals instanceof PropelCollection) {
            $this->collStudentMedicals->clearIterator();
        }
        $this->collStudentMedicals = null;
        $this->aApplication = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(SchoolHealthPeer::DEFAULT_STRING_FORMAT);
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
     * @return     SchoolHealth The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = SchoolHealthPeer::UPDATED_AT;

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
