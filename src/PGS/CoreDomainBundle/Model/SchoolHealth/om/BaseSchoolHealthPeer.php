<?php

namespace PGS\CoreDomainBundle\Model\SchoolHealth\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Glorpen\Propel\PropelBundle\Dispatcher\EventDispatcherProxy;
use Glorpen\Propel\PropelBundle\Events\DetectOMClassEvent;
use Glorpen\Propel\PropelBundle\Events\PeerEvent;
use PGS\CoreDomainBundle\Model\Application\ApplicationPeer;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealth;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealthPeer;
use PGS\CoreDomainBundle\Model\SchoolHealth\map\SchoolHealthTableMap;
use PGS\CoreDomainBundle\Model\Student\StudentPeer;
use PGS\CoreDomainBundle\Model\StudentCondition\StudentConditionPeer;
use PGS\CoreDomainBundle\Model\StudentMedical\StudentMedicalPeer;

abstract class BaseSchoolHealthPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'school_health';

    /** the related Propel class for this table */
    const OM_CLASS = 'PGS\\CoreDomainBundle\\Model\\SchoolHealth\\SchoolHealth';

    /** the related TableMap class for this table */
    const TM_CLASS = 'PGS\\CoreDomainBundle\\Model\\SchoolHealth\\map\\SchoolHealthTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 26;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 26;

    /** the column name for the id field */
    const ID = 'school_health.id';

    /** the column name for the application_id field */
    const APPLICATION_ID = 'school_health.application_id';

    /** the column name for the student_name field */
    const STUDENT_NAME = 'school_health.student_name';

    /** the column name for the emergency_physician_name field */
    const EMERGENCY_PHYSICIAN_NAME = 'school_health.emergency_physician_name';

    /** the column name for the emergency_relationship field */
    const EMERGENCY_RELATIONSHIP = 'school_health.emergency_relationship';

    /** the column name for the emergency_phone field */
    const EMERGENCY_PHONE = 'school_health.emergency_phone';

    /** the column name for the allergies field */
    const ALLERGIES = 'school_health.allergies';

    /** the column name for the allergies_yes field */
    const ALLERGIES_YES = 'school_health.allergies_yes';

    /** the column name for the allergies_action field */
    const ALLERGIES_ACTION = 'school_health.allergies_action';

    /** the column name for the condition_choice field */
    const CONDITION_CHOICE = 'school_health.condition_choice';

    /** the column name for the condition_exp field */
    const CONDITION_EXP = 'school_health.condition_exp';

    /** the column name for the student_psychological field */
    const STUDENT_PSYCHOLOGICAL = 'school_health.student_psychological';

    /** the column name for the psychological_exp field */
    const PSYCHOLOGICAL_EXP = 'school_health.psychological_exp';

    /** the column name for the student_aware field */
    const STUDENT_AWARE = 'school_health.student_aware';

    /** the column name for the aware_exp field */
    const AWARE_EXP = 'school_health.aware_exp';

    /** the column name for the student_ability field */
    const STUDENT_ABILITY = 'school_health.student_ability';

    /** the column name for the student_medicine field */
    const STUDENT_MEDICINE = 'school_health.student_medicine';

    /** the column name for the medical_emergency_name field */
    const MEDICAL_EMERGENCY_NAME = 'school_health.medical_emergency_name';

    /** the column name for the medical_emergency_phone field */
    const MEDICAL_EMERGENCY_PHONE = 'school_health.medical_emergency_phone';

    /** the column name for the medical_emergency_address field */
    const MEDICAL_EMERGENCY_ADDRESS = 'school_health.medical_emergency_address';

    /** the column name for the parent_statement_name field */
    const PARENT_STATEMENT_NAME = 'school_health.parent_statement_name';

    /** the column name for the student_statement_name field */
    const STUDENT_STATEMENT_NAME = 'school_health.student_statement_name';

    /** the column name for the parent_signature field */
    const PARENT_SIGNATURE = 'school_health.parent_signature';

    /** the column name for the date_of_signature field */
    const DATE_OF_SIGNATURE = 'school_health.date_of_signature';

    /** the column name for the created_at field */
    const CREATED_AT = 'school_health.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'school_health.updated_at';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of SchoolHealth objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array SchoolHealth[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. SchoolHealthPeer::$fieldNames[SchoolHealthPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'ApplicationId', 'StudentName', 'EmergencyPhysicianName', 'EmergencyRelationship', 'EmergencyPhone', 'Allergies', 'AllergiesYes', 'AllergiesAction', 'ConditionChoice', 'ConditionExp', 'StudentPsychological', 'PsychologicalExp', 'StudentAware', 'AwareExp', 'StudentAbility', 'StudentMedicine', 'MedicalEmergencyName', 'MedicalEmergencyPhone', 'MedicalEmergencyAddress', 'ParentStatementName', 'StudentStatementName', 'ParentSignature', 'DateOfSignature', 'CreatedAt', 'UpdatedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'applicationId', 'studentName', 'emergencyPhysicianName', 'emergencyRelationship', 'emergencyPhone', 'allergies', 'allergiesYes', 'allergiesAction', 'conditionChoice', 'conditionExp', 'studentPsychological', 'psychologicalExp', 'studentAware', 'awareExp', 'studentAbility', 'studentMedicine', 'medicalEmergencyName', 'medicalEmergencyPhone', 'medicalEmergencyAddress', 'parentStatementName', 'studentStatementName', 'parentSignature', 'dateOfSignature', 'createdAt', 'updatedAt', ),
        BasePeer::TYPE_COLNAME => array (SchoolHealthPeer::ID, SchoolHealthPeer::APPLICATION_ID, SchoolHealthPeer::STUDENT_NAME, SchoolHealthPeer::EMERGENCY_PHYSICIAN_NAME, SchoolHealthPeer::EMERGENCY_RELATIONSHIP, SchoolHealthPeer::EMERGENCY_PHONE, SchoolHealthPeer::ALLERGIES, SchoolHealthPeer::ALLERGIES_YES, SchoolHealthPeer::ALLERGIES_ACTION, SchoolHealthPeer::CONDITION_CHOICE, SchoolHealthPeer::CONDITION_EXP, SchoolHealthPeer::STUDENT_PSYCHOLOGICAL, SchoolHealthPeer::PSYCHOLOGICAL_EXP, SchoolHealthPeer::STUDENT_AWARE, SchoolHealthPeer::AWARE_EXP, SchoolHealthPeer::STUDENT_ABILITY, SchoolHealthPeer::STUDENT_MEDICINE, SchoolHealthPeer::MEDICAL_EMERGENCY_NAME, SchoolHealthPeer::MEDICAL_EMERGENCY_PHONE, SchoolHealthPeer::MEDICAL_EMERGENCY_ADDRESS, SchoolHealthPeer::PARENT_STATEMENT_NAME, SchoolHealthPeer::STUDENT_STATEMENT_NAME, SchoolHealthPeer::PARENT_SIGNATURE, SchoolHealthPeer::DATE_OF_SIGNATURE, SchoolHealthPeer::CREATED_AT, SchoolHealthPeer::UPDATED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'APPLICATION_ID', 'STUDENT_NAME', 'EMERGENCY_PHYSICIAN_NAME', 'EMERGENCY_RELATIONSHIP', 'EMERGENCY_PHONE', 'ALLERGIES', 'ALLERGIES_YES', 'ALLERGIES_ACTION', 'CONDITION_CHOICE', 'CONDITION_EXP', 'STUDENT_PSYCHOLOGICAL', 'PSYCHOLOGICAL_EXP', 'STUDENT_AWARE', 'AWARE_EXP', 'STUDENT_ABILITY', 'STUDENT_MEDICINE', 'MEDICAL_EMERGENCY_NAME', 'MEDICAL_EMERGENCY_PHONE', 'MEDICAL_EMERGENCY_ADDRESS', 'PARENT_STATEMENT_NAME', 'STUDENT_STATEMENT_NAME', 'PARENT_SIGNATURE', 'DATE_OF_SIGNATURE', 'CREATED_AT', 'UPDATED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'application_id', 'student_name', 'emergency_physician_name', 'emergency_relationship', 'emergency_phone', 'allergies', 'allergies_yes', 'allergies_action', 'condition_choice', 'condition_exp', 'student_psychological', 'psychological_exp', 'student_aware', 'aware_exp', 'student_ability', 'student_medicine', 'medical_emergency_name', 'medical_emergency_phone', 'medical_emergency_address', 'parent_statement_name', 'student_statement_name', 'parent_signature', 'date_of_signature', 'created_at', 'updated_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. SchoolHealthPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'ApplicationId' => 1, 'StudentName' => 2, 'EmergencyPhysicianName' => 3, 'EmergencyRelationship' => 4, 'EmergencyPhone' => 5, 'Allergies' => 6, 'AllergiesYes' => 7, 'AllergiesAction' => 8, 'ConditionChoice' => 9, 'ConditionExp' => 10, 'StudentPsychological' => 11, 'PsychologicalExp' => 12, 'StudentAware' => 13, 'AwareExp' => 14, 'StudentAbility' => 15, 'StudentMedicine' => 16, 'MedicalEmergencyName' => 17, 'MedicalEmergencyPhone' => 18, 'MedicalEmergencyAddress' => 19, 'ParentStatementName' => 20, 'StudentStatementName' => 21, 'ParentSignature' => 22, 'DateOfSignature' => 23, 'CreatedAt' => 24, 'UpdatedAt' => 25, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'applicationId' => 1, 'studentName' => 2, 'emergencyPhysicianName' => 3, 'emergencyRelationship' => 4, 'emergencyPhone' => 5, 'allergies' => 6, 'allergiesYes' => 7, 'allergiesAction' => 8, 'conditionChoice' => 9, 'conditionExp' => 10, 'studentPsychological' => 11, 'psychologicalExp' => 12, 'studentAware' => 13, 'awareExp' => 14, 'studentAbility' => 15, 'studentMedicine' => 16, 'medicalEmergencyName' => 17, 'medicalEmergencyPhone' => 18, 'medicalEmergencyAddress' => 19, 'parentStatementName' => 20, 'studentStatementName' => 21, 'parentSignature' => 22, 'dateOfSignature' => 23, 'createdAt' => 24, 'updatedAt' => 25, ),
        BasePeer::TYPE_COLNAME => array (SchoolHealthPeer::ID => 0, SchoolHealthPeer::APPLICATION_ID => 1, SchoolHealthPeer::STUDENT_NAME => 2, SchoolHealthPeer::EMERGENCY_PHYSICIAN_NAME => 3, SchoolHealthPeer::EMERGENCY_RELATIONSHIP => 4, SchoolHealthPeer::EMERGENCY_PHONE => 5, SchoolHealthPeer::ALLERGIES => 6, SchoolHealthPeer::ALLERGIES_YES => 7, SchoolHealthPeer::ALLERGIES_ACTION => 8, SchoolHealthPeer::CONDITION_CHOICE => 9, SchoolHealthPeer::CONDITION_EXP => 10, SchoolHealthPeer::STUDENT_PSYCHOLOGICAL => 11, SchoolHealthPeer::PSYCHOLOGICAL_EXP => 12, SchoolHealthPeer::STUDENT_AWARE => 13, SchoolHealthPeer::AWARE_EXP => 14, SchoolHealthPeer::STUDENT_ABILITY => 15, SchoolHealthPeer::STUDENT_MEDICINE => 16, SchoolHealthPeer::MEDICAL_EMERGENCY_NAME => 17, SchoolHealthPeer::MEDICAL_EMERGENCY_PHONE => 18, SchoolHealthPeer::MEDICAL_EMERGENCY_ADDRESS => 19, SchoolHealthPeer::PARENT_STATEMENT_NAME => 20, SchoolHealthPeer::STUDENT_STATEMENT_NAME => 21, SchoolHealthPeer::PARENT_SIGNATURE => 22, SchoolHealthPeer::DATE_OF_SIGNATURE => 23, SchoolHealthPeer::CREATED_AT => 24, SchoolHealthPeer::UPDATED_AT => 25, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'APPLICATION_ID' => 1, 'STUDENT_NAME' => 2, 'EMERGENCY_PHYSICIAN_NAME' => 3, 'EMERGENCY_RELATIONSHIP' => 4, 'EMERGENCY_PHONE' => 5, 'ALLERGIES' => 6, 'ALLERGIES_YES' => 7, 'ALLERGIES_ACTION' => 8, 'CONDITION_CHOICE' => 9, 'CONDITION_EXP' => 10, 'STUDENT_PSYCHOLOGICAL' => 11, 'PSYCHOLOGICAL_EXP' => 12, 'STUDENT_AWARE' => 13, 'AWARE_EXP' => 14, 'STUDENT_ABILITY' => 15, 'STUDENT_MEDICINE' => 16, 'MEDICAL_EMERGENCY_NAME' => 17, 'MEDICAL_EMERGENCY_PHONE' => 18, 'MEDICAL_EMERGENCY_ADDRESS' => 19, 'PARENT_STATEMENT_NAME' => 20, 'STUDENT_STATEMENT_NAME' => 21, 'PARENT_SIGNATURE' => 22, 'DATE_OF_SIGNATURE' => 23, 'CREATED_AT' => 24, 'UPDATED_AT' => 25, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'application_id' => 1, 'student_name' => 2, 'emergency_physician_name' => 3, 'emergency_relationship' => 4, 'emergency_phone' => 5, 'allergies' => 6, 'allergies_yes' => 7, 'allergies_action' => 8, 'condition_choice' => 9, 'condition_exp' => 10, 'student_psychological' => 11, 'psychological_exp' => 12, 'student_aware' => 13, 'aware_exp' => 14, 'student_ability' => 15, 'student_medicine' => 16, 'medical_emergency_name' => 17, 'medical_emergency_phone' => 18, 'medical_emergency_address' => 19, 'parent_statement_name' => 20, 'student_statement_name' => 21, 'parent_signature' => 22, 'date_of_signature' => 23, 'created_at' => 24, 'updated_at' => 25, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, )
    );

    /**
     * Translates a fieldname to another type
     *
     * @param      string $name field name
     * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @param      string $toType   One of the class type constants
     * @return string          translated name of the field.
     * @throws PropelException - if the specified name could not be found in the fieldname mappings.
     */
    public static function translateFieldName($name, $fromType, $toType)
    {
        $toNames = SchoolHealthPeer::getFieldNames($toType);
        $key = isset(SchoolHealthPeer::$fieldKeys[$fromType][$name]) ? SchoolHealthPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(SchoolHealthPeer::$fieldKeys[$fromType], true));
        }

        return $toNames[$key];
    }

    /**
     * Returns an array of field names.
     *
     * @param      string $type The type of fieldnames to return:
     *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @return array           A list of field names
     * @throws PropelException - if the type is not valid.
     */
    public static function getFieldNames($type = BasePeer::TYPE_PHPNAME)
    {
        if (!array_key_exists($type, SchoolHealthPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return SchoolHealthPeer::$fieldNames[$type];
    }

    /**
     * Convenience method which changes table.column to alias.column.
     *
     * Using this method you can maintain SQL abstraction while using column aliases.
     * <code>
     *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
     *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
     * </code>
     * @param      string $alias The alias for the current table.
     * @param      string $column The column name for current table. (i.e. SchoolHealthPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(SchoolHealthPeer::TABLE_NAME.'.', $alias.'.', $column);
    }

    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param      Criteria $criteria object containing the columns to add.
     * @param      string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(SchoolHealthPeer::ID);
            $criteria->addSelectColumn(SchoolHealthPeer::APPLICATION_ID);
            $criteria->addSelectColumn(SchoolHealthPeer::STUDENT_NAME);
            $criteria->addSelectColumn(SchoolHealthPeer::EMERGENCY_PHYSICIAN_NAME);
            $criteria->addSelectColumn(SchoolHealthPeer::EMERGENCY_RELATIONSHIP);
            $criteria->addSelectColumn(SchoolHealthPeer::EMERGENCY_PHONE);
            $criteria->addSelectColumn(SchoolHealthPeer::ALLERGIES);
            $criteria->addSelectColumn(SchoolHealthPeer::ALLERGIES_YES);
            $criteria->addSelectColumn(SchoolHealthPeer::ALLERGIES_ACTION);
            $criteria->addSelectColumn(SchoolHealthPeer::CONDITION_CHOICE);
            $criteria->addSelectColumn(SchoolHealthPeer::CONDITION_EXP);
            $criteria->addSelectColumn(SchoolHealthPeer::STUDENT_PSYCHOLOGICAL);
            $criteria->addSelectColumn(SchoolHealthPeer::PSYCHOLOGICAL_EXP);
            $criteria->addSelectColumn(SchoolHealthPeer::STUDENT_AWARE);
            $criteria->addSelectColumn(SchoolHealthPeer::AWARE_EXP);
            $criteria->addSelectColumn(SchoolHealthPeer::STUDENT_ABILITY);
            $criteria->addSelectColumn(SchoolHealthPeer::STUDENT_MEDICINE);
            $criteria->addSelectColumn(SchoolHealthPeer::MEDICAL_EMERGENCY_NAME);
            $criteria->addSelectColumn(SchoolHealthPeer::MEDICAL_EMERGENCY_PHONE);
            $criteria->addSelectColumn(SchoolHealthPeer::MEDICAL_EMERGENCY_ADDRESS);
            $criteria->addSelectColumn(SchoolHealthPeer::PARENT_STATEMENT_NAME);
            $criteria->addSelectColumn(SchoolHealthPeer::STUDENT_STATEMENT_NAME);
            $criteria->addSelectColumn(SchoolHealthPeer::PARENT_SIGNATURE);
            $criteria->addSelectColumn(SchoolHealthPeer::DATE_OF_SIGNATURE);
            $criteria->addSelectColumn(SchoolHealthPeer::CREATED_AT);
            $criteria->addSelectColumn(SchoolHealthPeer::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.application_id');
            $criteria->addSelectColumn($alias . '.student_name');
            $criteria->addSelectColumn($alias . '.emergency_physician_name');
            $criteria->addSelectColumn($alias . '.emergency_relationship');
            $criteria->addSelectColumn($alias . '.emergency_phone');
            $criteria->addSelectColumn($alias . '.allergies');
            $criteria->addSelectColumn($alias . '.allergies_yes');
            $criteria->addSelectColumn($alias . '.allergies_action');
            $criteria->addSelectColumn($alias . '.condition_choice');
            $criteria->addSelectColumn($alias . '.condition_exp');
            $criteria->addSelectColumn($alias . '.student_psychological');
            $criteria->addSelectColumn($alias . '.psychological_exp');
            $criteria->addSelectColumn($alias . '.student_aware');
            $criteria->addSelectColumn($alias . '.aware_exp');
            $criteria->addSelectColumn($alias . '.student_ability');
            $criteria->addSelectColumn($alias . '.student_medicine');
            $criteria->addSelectColumn($alias . '.medical_emergency_name');
            $criteria->addSelectColumn($alias . '.medical_emergency_phone');
            $criteria->addSelectColumn($alias . '.medical_emergency_address');
            $criteria->addSelectColumn($alias . '.parent_statement_name');
            $criteria->addSelectColumn($alias . '.student_statement_name');
            $criteria->addSelectColumn($alias . '.parent_signature');
            $criteria->addSelectColumn($alias . '.date_of_signature');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
        }
    }

    /**
     * Returns the number of rows matching criteria.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @return int Number of matching rows.
     */
    public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
    {
        // we may modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolHealthPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolHealthPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(SchoolHealthPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(SchoolHealthPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        // BasePeer returns a PDOStatement
        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }
    /**
     * Selects one object from the DB.
     *
     * @param      Criteria $criteria object used to create the SELECT statement.
     * @param      PropelPDO $con
     * @return SchoolHealth
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = SchoolHealthPeer::doSelect($critcopy, $con);
        if ($objects) {
            return $objects[0];
        }

        return null;
    }
    /**
     * Selects several row from the DB.
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con
     * @return array           Array of selected Objects
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelect(Criteria $criteria, PropelPDO $con = null)
    {
        return SchoolHealthPeer::populateObjects(SchoolHealthPeer::doSelectStmt($criteria, $con));
    }
    /**
     * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
     *
     * Use this method directly if you want to work with an executed statement directly (for example
     * to perform your own object hydration).
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con The connection to use
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return PDOStatement The executed PDOStatement object.
     * @see        BasePeer::doSelect()
     */
    public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolHealthPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            SchoolHealthPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(SchoolHealthPeer::DATABASE_NAME);

        // BasePeer returns a PDOStatement
        return BasePeer::doSelect($criteria, $con);
    }
    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doSelect*()
     * methods in your stub classes -- you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by doSelect*()
     * and retrieveByPK*() calls.
     *
     * @param SchoolHealth $obj A SchoolHealth object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            SchoolHealthPeer::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param      mixed $value A SchoolHealth object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof SchoolHealth) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or SchoolHealth object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(SchoolHealthPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return SchoolHealth Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(SchoolHealthPeer::$instances[$key])) {
                return SchoolHealthPeer::$instances[$key];
            }
        }

        return null; // just to be explicit
    }

    /**
     * Clear the instance pool.
     *
     * @return void
     */
    public static function clearInstancePool($and_clear_all_references = false)
    {
      if ($and_clear_all_references) {
        foreach (SchoolHealthPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        SchoolHealthPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to school_health
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in StudentPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        StudentPeer::clearInstancePool();
        // Invalidate objects in StudentConditionPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        StudentConditionPeer::clearInstancePool();
        // Invalidate objects in StudentMedicalPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        StudentMedicalPeer::clearInstancePool();
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return string A string version of PK or null if the components of primary key in result array are all null.
     */
    public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
    {
        // If the PK cannot be derived from the row, return null.
        if ($row[$startcol] === null) {
            return null;
        }

        return (string) $row[$startcol];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $startcol = 0)
    {

        return (int) $row[$startcol];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function populateObjects(PDOStatement $stmt)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = SchoolHealthPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = SchoolHealthPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = SchoolHealthPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SchoolHealthPeer::addInstanceToPool($obj, $key);
            } // if key exists
        }
        $stmt->closeCursor();

        return $results;
    }
    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return array (SchoolHealth object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = SchoolHealthPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = SchoolHealthPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + SchoolHealthPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SchoolHealthPeer::getOMClass($row, $startcol);
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            SchoolHealthPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related Application table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinApplication(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolHealthPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolHealthPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SchoolHealthPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolHealthPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolHealthPeer::APPLICATION_ID, ApplicationPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of SchoolHealth objects pre-filled with their Application objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of SchoolHealth objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinApplication(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolHealthPeer::DATABASE_NAME);
        }

        SchoolHealthPeer::addSelectColumns($criteria);
        $startcol = SchoolHealthPeer::NUM_HYDRATE_COLUMNS;
        ApplicationPeer::addSelectColumns($criteria);

        $criteria->addJoin(SchoolHealthPeer::APPLICATION_ID, ApplicationPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolHealthPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolHealthPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SchoolHealthPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolHealthPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = ApplicationPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = ApplicationPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = ApplicationPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    ApplicationPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (SchoolHealth) to $obj2 (Application)
                $obj2->addSchoolHealth($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining all related tables
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolHealthPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolHealthPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SchoolHealthPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolHealthPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolHealthPeer::APPLICATION_ID, ApplicationPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }

    /**
     * Selects a collection of SchoolHealth objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of SchoolHealth objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolHealthPeer::DATABASE_NAME);
        }

        SchoolHealthPeer::addSelectColumns($criteria);
        $startcol2 = SchoolHealthPeer::NUM_HYDRATE_COLUMNS;

        ApplicationPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + ApplicationPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SchoolHealthPeer::APPLICATION_ID, ApplicationPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolHealthPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolHealthPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SchoolHealthPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolHealthPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined Application rows

            $key2 = ApplicationPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = ApplicationPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = ApplicationPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    ApplicationPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (SchoolHealth) to the collection in $obj2 (Application)
                $obj2->addSchoolHealth($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }

    /**
     * Returns the TableMap related to this peer.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getDatabaseMap(SchoolHealthPeer::DATABASE_NAME)->getTable(SchoolHealthPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseSchoolHealthPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseSchoolHealthPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \PGS\CoreDomainBundle\Model\SchoolHealth\map\SchoolHealthTableMap());
      }
    }

    /**
     * The class that the Peer will make instances of.
     *
     *
     * @return string ClassName
     */
    public static function getOMClass($row = 0, $colnum = 0)
    {

        $event = new DetectOMClassEvent(SchoolHealthPeer::OM_CLASS, $row, $colnum);
        EventDispatcherProxy::trigger('om.detect', $event);
        if($event->isDetected()){
            return $event->getDetectedClass();
        }

        return SchoolHealthPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a SchoolHealth or Criteria object.
     *
     * @param      mixed $values Criteria or SchoolHealth object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolHealthPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from SchoolHealth object
        }

        if ($criteria->containsKey(SchoolHealthPeer::ID) && $criteria->keyContainsValue(SchoolHealthPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SchoolHealthPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(SchoolHealthPeer::DATABASE_NAME);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = BasePeer::doInsert($criteria, $con);
            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

    /**
     * Performs an UPDATE on the database, given a SchoolHealth or Criteria object.
     *
     * @param      mixed $values Criteria or SchoolHealth object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolHealthPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(SchoolHealthPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(SchoolHealthPeer::ID);
            $value = $criteria->remove(SchoolHealthPeer::ID);
            if ($value) {
                $selectCriteria->add(SchoolHealthPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(SchoolHealthPeer::TABLE_NAME);
            }

        } else { // $values is SchoolHealth object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(SchoolHealthPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the school_health table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolHealthPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(SchoolHealthPeer::TABLE_NAME, $con, SchoolHealthPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SchoolHealthPeer::clearInstancePool();
            SchoolHealthPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a SchoolHealth or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or SchoolHealth object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param      PropelPDO $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *				if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, PropelPDO $con = null)
     {
        if ($con === null) {
            $con = Propel::getConnection(SchoolHealthPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            SchoolHealthPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof SchoolHealth) { // it's a model object
            // invalidate the cache for this single object
            SchoolHealthPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SchoolHealthPeer::DATABASE_NAME);
            $criteria->add(SchoolHealthPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                SchoolHealthPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(SchoolHealthPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            SchoolHealthPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given SchoolHealth object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param SchoolHealth $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(SchoolHealthPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(SchoolHealthPeer::TABLE_NAME);

            if (! is_array($cols)) {
                $cols = array($cols);
            }

            foreach ($cols as $colName) {
                if ($tableMap->hasColumn($colName)) {
                    $get = 'get' . $tableMap->getColumn($colName)->getPhpName();
                    $columns[$colName] = $obj->$get();
                }
            }
        } else {

        }

        return BasePeer::doValidate(SchoolHealthPeer::DATABASE_NAME, SchoolHealthPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return SchoolHealth
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = SchoolHealthPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(SchoolHealthPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(SchoolHealthPeer::DATABASE_NAME);
        $criteria->add(SchoolHealthPeer::ID, $pk);

        $v = SchoolHealthPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return SchoolHealth[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolHealthPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(SchoolHealthPeer::DATABASE_NAME);
            $criteria->add(SchoolHealthPeer::ID, $pks, Criteria::IN);
            $objs = SchoolHealthPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseSchoolHealthPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseSchoolHealthPeer::buildTableMap();

EventDispatcherProxy::trigger(array('construct','peer.construct'), new PeerEvent('PGS\CoreDomainBundle\Model\SchoolHealth\om\BaseSchoolHealthPeer'));
