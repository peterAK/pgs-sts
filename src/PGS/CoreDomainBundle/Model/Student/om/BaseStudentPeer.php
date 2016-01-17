<?php

namespace PGS\CoreDomainBundle\Model\Student\om;

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
use PGS\CoreDomainBundle\Model\UserPeer;
use PGS\CoreDomainBundle\Model\Application\ApplicationPeer;
use PGS\CoreDomainBundle\Model\ParentStudent\ParentStudentPeer;
use PGS\CoreDomainBundle\Model\SchoolClassCourseStudentBehavior\SchoolClassCourseStudentBehaviorPeer;
use PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudentPeer;
use PGS\CoreDomainBundle\Model\SchoolEnrollment\SchoolEnrollmentPeer;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealthPeer;
use PGS\CoreDomainBundle\Model\Student\Student;
use PGS\CoreDomainBundle\Model\Student\StudentPeer;
use PGS\CoreDomainBundle\Model\StudentHistory\StudentHistoryPeer;
use PGS\CoreDomainBundle\Model\Student\map\StudentTableMap;

abstract class BaseStudentPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'student';

    /** the related Propel class for this table */
    const OM_CLASS = 'PGS\\CoreDomainBundle\\Model\\Student\\Student';

    /** the related TableMap class for this table */
    const TM_CLASS = 'PGS\\CoreDomainBundle\\Model\\Student\\map\\StudentTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 21;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 21;

    /** the column name for the id field */
    const ID = 'student.id';

    /** the column name for the user_id field */
    const USER_ID = 'student.user_id';

    /** the column name for the application_id field */
    const APPLICATION_ID = 'student.application_id';

    /** the column name for the health_id field */
    const HEALTH_ID = 'student.health_id';

    /** the column name for the student_nation_no field */
    const STUDENT_NATION_NO = 'student.student_nation_no';

    /** the column name for the student_no field */
    const STUDENT_NO = 'student.student_no';

    /** the column name for the first_name field */
    const FIRST_NAME = 'student.first_name';

    /** the column name for the middle_name field */
    const MIDDLE_NAME = 'student.middle_name';

    /** the column name for the last_name field */
    const LAST_NAME = 'student.last_name';

    /** the column name for the nick_name field */
    const NICK_NAME = 'student.nick_name';

    /** the column name for the gender field */
    const GENDER = 'student.gender';

    /** the column name for the place_of_birth field */
    const PLACE_OF_BIRTH = 'student.place_of_birth';

    /** the column name for the date_of_birth field */
    const DATE_OF_BIRTH = 'student.date_of_birth';

    /** the column name for the religion field */
    const RELIGION = 'student.religion';

    /** the column name for the picture field */
    const PICTURE = 'student.picture';

    /** the column name for the birth_certificate field */
    const BIRTH_CERTIFICATE = 'student.birth_certificate';

    /** the column name for the family_card field */
    const FAMILY_CARD = 'student.family_card';

    /** the column name for the graduation_certificate field */
    const GRADUATION_CERTIFICATE = 'student.graduation_certificate';

    /** the column name for the authorization_code field */
    const AUTHORIZATION_CODE = 'student.authorization_code';

    /** the column name for the created_at field */
    const CREATED_AT = 'student.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'student.updated_at';

    /** The enumerated values for the gender field */
    const GENDER_UNKNOWN = 'unknown';
    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';

    /** The enumerated values for the religion field */
    const RELIGION_OTHER = 'other';
    const RELIGION_ISLAM = 'islam';
    const RELIGION_CHRISTIAN = 'christian';
    const RELIGION_CATHOLIC = 'catholic';
    const RELIGION_HINDU = 'hindu';
    const RELIGION_BUDDHIST = 'buddhist';
    const RELIGION_CONFUCIAN = 'confucian';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of Student objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array Student[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. StudentPeer::$fieldNames[StudentPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'UserId', 'ApplicationId', 'HealthId', 'StudentNationNo', 'StudentNo', 'FirstName', 'MiddleName', 'LastName', 'NickName', 'Gender', 'PlaceOfBirth', 'DateOfBirth', 'Religion', 'Picture', 'BirthCertificate', 'FamilyCard', 'GraduationCertificate', 'AuthorizationCode', 'CreatedAt', 'UpdatedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'userId', 'applicationId', 'healthId', 'studentNationNo', 'studentNo', 'firstName', 'middleName', 'lastName', 'nickName', 'gender', 'placeOfBirth', 'dateOfBirth', 'religion', 'picture', 'birthCertificate', 'familyCard', 'graduationCertificate', 'authorizationCode', 'createdAt', 'updatedAt', ),
        BasePeer::TYPE_COLNAME => array (StudentPeer::ID, StudentPeer::USER_ID, StudentPeer::APPLICATION_ID, StudentPeer::HEALTH_ID, StudentPeer::STUDENT_NATION_NO, StudentPeer::STUDENT_NO, StudentPeer::FIRST_NAME, StudentPeer::MIDDLE_NAME, StudentPeer::LAST_NAME, StudentPeer::NICK_NAME, StudentPeer::GENDER, StudentPeer::PLACE_OF_BIRTH, StudentPeer::DATE_OF_BIRTH, StudentPeer::RELIGION, StudentPeer::PICTURE, StudentPeer::BIRTH_CERTIFICATE, StudentPeer::FAMILY_CARD, StudentPeer::GRADUATION_CERTIFICATE, StudentPeer::AUTHORIZATION_CODE, StudentPeer::CREATED_AT, StudentPeer::UPDATED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'USER_ID', 'APPLICATION_ID', 'HEALTH_ID', 'STUDENT_NATION_NO', 'STUDENT_NO', 'FIRST_NAME', 'MIDDLE_NAME', 'LAST_NAME', 'NICK_NAME', 'GENDER', 'PLACE_OF_BIRTH', 'DATE_OF_BIRTH', 'RELIGION', 'PICTURE', 'BIRTH_CERTIFICATE', 'FAMILY_CARD', 'GRADUATION_CERTIFICATE', 'AUTHORIZATION_CODE', 'CREATED_AT', 'UPDATED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'user_id', 'application_id', 'health_id', 'student_nation_no', 'student_no', 'first_name', 'middle_name', 'last_name', 'nick_name', 'gender', 'place_of_birth', 'date_of_birth', 'religion', 'picture', 'birth_certificate', 'family_card', 'graduation_certificate', 'authorization_code', 'created_at', 'updated_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. StudentPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'UserId' => 1, 'ApplicationId' => 2, 'HealthId' => 3, 'StudentNationNo' => 4, 'StudentNo' => 5, 'FirstName' => 6, 'MiddleName' => 7, 'LastName' => 8, 'NickName' => 9, 'Gender' => 10, 'PlaceOfBirth' => 11, 'DateOfBirth' => 12, 'Religion' => 13, 'Picture' => 14, 'BirthCertificate' => 15, 'FamilyCard' => 16, 'GraduationCertificate' => 17, 'AuthorizationCode' => 18, 'CreatedAt' => 19, 'UpdatedAt' => 20, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'userId' => 1, 'applicationId' => 2, 'healthId' => 3, 'studentNationNo' => 4, 'studentNo' => 5, 'firstName' => 6, 'middleName' => 7, 'lastName' => 8, 'nickName' => 9, 'gender' => 10, 'placeOfBirth' => 11, 'dateOfBirth' => 12, 'religion' => 13, 'picture' => 14, 'birthCertificate' => 15, 'familyCard' => 16, 'graduationCertificate' => 17, 'authorizationCode' => 18, 'createdAt' => 19, 'updatedAt' => 20, ),
        BasePeer::TYPE_COLNAME => array (StudentPeer::ID => 0, StudentPeer::USER_ID => 1, StudentPeer::APPLICATION_ID => 2, StudentPeer::HEALTH_ID => 3, StudentPeer::STUDENT_NATION_NO => 4, StudentPeer::STUDENT_NO => 5, StudentPeer::FIRST_NAME => 6, StudentPeer::MIDDLE_NAME => 7, StudentPeer::LAST_NAME => 8, StudentPeer::NICK_NAME => 9, StudentPeer::GENDER => 10, StudentPeer::PLACE_OF_BIRTH => 11, StudentPeer::DATE_OF_BIRTH => 12, StudentPeer::RELIGION => 13, StudentPeer::PICTURE => 14, StudentPeer::BIRTH_CERTIFICATE => 15, StudentPeer::FAMILY_CARD => 16, StudentPeer::GRADUATION_CERTIFICATE => 17, StudentPeer::AUTHORIZATION_CODE => 18, StudentPeer::CREATED_AT => 19, StudentPeer::UPDATED_AT => 20, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'USER_ID' => 1, 'APPLICATION_ID' => 2, 'HEALTH_ID' => 3, 'STUDENT_NATION_NO' => 4, 'STUDENT_NO' => 5, 'FIRST_NAME' => 6, 'MIDDLE_NAME' => 7, 'LAST_NAME' => 8, 'NICK_NAME' => 9, 'GENDER' => 10, 'PLACE_OF_BIRTH' => 11, 'DATE_OF_BIRTH' => 12, 'RELIGION' => 13, 'PICTURE' => 14, 'BIRTH_CERTIFICATE' => 15, 'FAMILY_CARD' => 16, 'GRADUATION_CERTIFICATE' => 17, 'AUTHORIZATION_CODE' => 18, 'CREATED_AT' => 19, 'UPDATED_AT' => 20, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'user_id' => 1, 'application_id' => 2, 'health_id' => 3, 'student_nation_no' => 4, 'student_no' => 5, 'first_name' => 6, 'middle_name' => 7, 'last_name' => 8, 'nick_name' => 9, 'gender' => 10, 'place_of_birth' => 11, 'date_of_birth' => 12, 'religion' => 13, 'picture' => 14, 'birth_certificate' => 15, 'family_card' => 16, 'graduation_certificate' => 17, 'authorization_code' => 18, 'created_at' => 19, 'updated_at' => 20, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
        StudentPeer::GENDER => array(
            StudentPeer::GENDER_UNKNOWN,
            StudentPeer::GENDER_MALE,
            StudentPeer::GENDER_FEMALE,
        ),
        StudentPeer::RELIGION => array(
            StudentPeer::RELIGION_OTHER,
            StudentPeer::RELIGION_ISLAM,
            StudentPeer::RELIGION_CHRISTIAN,
            StudentPeer::RELIGION_CATHOLIC,
            StudentPeer::RELIGION_HINDU,
            StudentPeer::RELIGION_BUDDHIST,
            StudentPeer::RELIGION_CONFUCIAN,
        ),
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
        $toNames = StudentPeer::getFieldNames($toType);
        $key = isset(StudentPeer::$fieldKeys[$fromType][$name]) ? StudentPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(StudentPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, StudentPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return StudentPeer::$fieldNames[$type];
    }

    /**
     * Gets the list of values for all ENUM columns
     * @return array
     */
    public static function getValueSets()
    {
      return StudentPeer::$enumValueSets;
    }

    /**
     * Gets the list of values for an ENUM column
     *
     * @param string $colname The ENUM column name.
     *
     * @return array list of possible values for the column
     */
    public static function getValueSet($colname)
    {
        $valueSets = StudentPeer::getValueSets();

        if (!isset($valueSets[$colname])) {
            throw new PropelException(sprintf('Column "%s" has no ValueSet.', $colname));
        }

        return $valueSets[$colname];
    }

    /**
     * Gets the SQL value for the ENUM column value
     *
     * @param string $colname ENUM column name.
     * @param string $enumVal ENUM value.
     *
     * @return int SQL value
     */
    public static function getSqlValueForEnum($colname, $enumVal)
    {
        $values = StudentPeer::getValueSet($colname);
        if (!in_array($enumVal, $values)) {
            throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $colname));
        }

        return array_search($enumVal, $values);
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
     * @param      string $column The column name for current table. (i.e. StudentPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(StudentPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(StudentPeer::ID);
            $criteria->addSelectColumn(StudentPeer::USER_ID);
            $criteria->addSelectColumn(StudentPeer::APPLICATION_ID);
            $criteria->addSelectColumn(StudentPeer::HEALTH_ID);
            $criteria->addSelectColumn(StudentPeer::STUDENT_NATION_NO);
            $criteria->addSelectColumn(StudentPeer::STUDENT_NO);
            $criteria->addSelectColumn(StudentPeer::FIRST_NAME);
            $criteria->addSelectColumn(StudentPeer::MIDDLE_NAME);
            $criteria->addSelectColumn(StudentPeer::LAST_NAME);
            $criteria->addSelectColumn(StudentPeer::NICK_NAME);
            $criteria->addSelectColumn(StudentPeer::GENDER);
            $criteria->addSelectColumn(StudentPeer::PLACE_OF_BIRTH);
            $criteria->addSelectColumn(StudentPeer::DATE_OF_BIRTH);
            $criteria->addSelectColumn(StudentPeer::RELIGION);
            $criteria->addSelectColumn(StudentPeer::PICTURE);
            $criteria->addSelectColumn(StudentPeer::BIRTH_CERTIFICATE);
            $criteria->addSelectColumn(StudentPeer::FAMILY_CARD);
            $criteria->addSelectColumn(StudentPeer::GRADUATION_CERTIFICATE);
            $criteria->addSelectColumn(StudentPeer::AUTHORIZATION_CODE);
            $criteria->addSelectColumn(StudentPeer::CREATED_AT);
            $criteria->addSelectColumn(StudentPeer::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.application_id');
            $criteria->addSelectColumn($alias . '.health_id');
            $criteria->addSelectColumn($alias . '.student_nation_no');
            $criteria->addSelectColumn($alias . '.student_no');
            $criteria->addSelectColumn($alias . '.first_name');
            $criteria->addSelectColumn($alias . '.middle_name');
            $criteria->addSelectColumn($alias . '.last_name');
            $criteria->addSelectColumn($alias . '.nick_name');
            $criteria->addSelectColumn($alias . '.gender');
            $criteria->addSelectColumn($alias . '.place_of_birth');
            $criteria->addSelectColumn($alias . '.date_of_birth');
            $criteria->addSelectColumn($alias . '.religion');
            $criteria->addSelectColumn($alias . '.picture');
            $criteria->addSelectColumn($alias . '.birth_certificate');
            $criteria->addSelectColumn($alias . '.family_card');
            $criteria->addSelectColumn($alias . '.graduation_certificate');
            $criteria->addSelectColumn($alias . '.authorization_code');
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
        $criteria->setPrimaryTableName(StudentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StudentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(StudentPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(StudentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return Student
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = StudentPeer::doSelect($critcopy, $con);
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
        return StudentPeer::populateObjects(StudentPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(StudentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            StudentPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(StudentPeer::DATABASE_NAME);

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
     * @param Student $obj A Student object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            StudentPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A Student object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof Student) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or Student object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(StudentPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return Student Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(StudentPeer::$instances[$key])) {
                return StudentPeer::$instances[$key];
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
        foreach (StudentPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        StudentPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to student
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in ParentStudentPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        ParentStudentPeer::clearInstancePool();
        // Invalidate objects in SchoolClassCourseStudentBehaviorPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        SchoolClassCourseStudentBehaviorPeer::clearInstancePool();
        // Invalidate objects in SchoolClassStudentPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        SchoolClassStudentPeer::clearInstancePool();
        // Invalidate objects in SchoolEnrollmentPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        SchoolEnrollmentPeer::clearInstancePool();
        // Invalidate objects in StudentHistoryPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        StudentHistoryPeer::clearInstancePool();
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
        $cls = StudentPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = StudentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = StudentPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                StudentPeer::addInstanceToPool($obj, $key);
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
     * @return array (Student object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = StudentPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = StudentPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + StudentPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = StudentPeer::getOMClass($row, $startcol);
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            StudentPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * Gets the SQL value for Gender ENUM value
     *
     * @param  string $enumVal ENUM value to get SQL value for
     * @return int SQL value
     */
    public static function getGenderSqlValue($enumVal)
    {
        return StudentPeer::getSqlValueForEnum(StudentPeer::GENDER, $enumVal);
    }

    /**
     * Gets the SQL value for Religion ENUM value
     *
     * @param  string $enumVal ENUM value to get SQL value for
     * @return int SQL value
     */
    public static function getReligionSqlValue($enumVal)
    {
        return StudentPeer::getSqlValueForEnum(StudentPeer::RELIGION, $enumVal);
    }


    /**
     * Returns the number of rows matching criteria, joining the related User table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinUser(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(StudentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StudentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(StudentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StudentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StudentPeer::USER_ID, UserPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(StudentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StudentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(StudentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StudentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StudentPeer::APPLICATION_ID, ApplicationPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related SchoolHealth table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinSchoolHealth(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(StudentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StudentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(StudentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StudentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StudentPeer::HEALTH_ID, SchoolHealthPeer::ID, $join_behavior);

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
     * Selects a collection of Student objects pre-filled with their User objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Student objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StudentPeer::DATABASE_NAME);
        }

        StudentPeer::addSelectColumns($criteria);
        $startcol = StudentPeer::NUM_HYDRATE_COLUMNS;
        UserPeer::addSelectColumns($criteria);

        $criteria->addJoin(StudentPeer::USER_ID, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StudentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StudentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = StudentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StudentPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = UserPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Student) to $obj2 (User)
                $obj2->addStudent($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Student objects pre-filled with their Application objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Student objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinApplication(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StudentPeer::DATABASE_NAME);
        }

        StudentPeer::addSelectColumns($criteria);
        $startcol = StudentPeer::NUM_HYDRATE_COLUMNS;
        ApplicationPeer::addSelectColumns($criteria);

        $criteria->addJoin(StudentPeer::APPLICATION_ID, ApplicationPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StudentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StudentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = StudentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StudentPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Student) to $obj2 (Application)
                $obj2->addStudent($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Student objects pre-filled with their SchoolHealth objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Student objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinSchoolHealth(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StudentPeer::DATABASE_NAME);
        }

        StudentPeer::addSelectColumns($criteria);
        $startcol = StudentPeer::NUM_HYDRATE_COLUMNS;
        SchoolHealthPeer::addSelectColumns($criteria);

        $criteria->addJoin(StudentPeer::HEALTH_ID, SchoolHealthPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StudentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StudentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = StudentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StudentPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = SchoolHealthPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = SchoolHealthPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = SchoolHealthPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    SchoolHealthPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Student) to $obj2 (SchoolHealth)
                $obj2->addStudent($obj1);

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
        $criteria->setPrimaryTableName(StudentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StudentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(StudentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StudentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StudentPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(StudentPeer::APPLICATION_ID, ApplicationPeer::ID, $join_behavior);

        $criteria->addJoin(StudentPeer::HEALTH_ID, SchoolHealthPeer::ID, $join_behavior);

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
     * Selects a collection of Student objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Student objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StudentPeer::DATABASE_NAME);
        }

        StudentPeer::addSelectColumns($criteria);
        $startcol2 = StudentPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserPeer::NUM_HYDRATE_COLUMNS;

        ApplicationPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + ApplicationPeer::NUM_HYDRATE_COLUMNS;

        SchoolHealthPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + SchoolHealthPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(StudentPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(StudentPeer::APPLICATION_ID, ApplicationPeer::ID, $join_behavior);

        $criteria->addJoin(StudentPeer::HEALTH_ID, SchoolHealthPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StudentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StudentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = StudentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StudentPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined User rows

            $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = UserPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (Student) to the collection in $obj2 (User)
                $obj2->addStudent($obj1);
            } // if joined row not null

            // Add objects for joined Application rows

            $key3 = ApplicationPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = ApplicationPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = ApplicationPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    ApplicationPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (Student) to the collection in $obj3 (Application)
                $obj3->addStudent($obj1);
            } // if joined row not null

            // Add objects for joined SchoolHealth rows

            $key4 = SchoolHealthPeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = SchoolHealthPeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = SchoolHealthPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    SchoolHealthPeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (Student) to the collection in $obj4 (SchoolHealth)
                $obj4->addStudent($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related User table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptUser(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(StudentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StudentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(StudentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StudentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StudentPeer::APPLICATION_ID, ApplicationPeer::ID, $join_behavior);

        $criteria->addJoin(StudentPeer::HEALTH_ID, SchoolHealthPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Application table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptApplication(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(StudentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StudentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(StudentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StudentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StudentPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(StudentPeer::HEALTH_ID, SchoolHealthPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related SchoolHealth table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptSchoolHealth(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(StudentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StudentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(StudentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StudentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StudentPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(StudentPeer::APPLICATION_ID, ApplicationPeer::ID, $join_behavior);

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
     * Selects a collection of Student objects pre-filled with all related objects except User.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Student objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StudentPeer::DATABASE_NAME);
        }

        StudentPeer::addSelectColumns($criteria);
        $startcol2 = StudentPeer::NUM_HYDRATE_COLUMNS;

        ApplicationPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + ApplicationPeer::NUM_HYDRATE_COLUMNS;

        SchoolHealthPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SchoolHealthPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(StudentPeer::APPLICATION_ID, ApplicationPeer::ID, $join_behavior);

        $criteria->addJoin(StudentPeer::HEALTH_ID, SchoolHealthPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StudentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StudentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = StudentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StudentPeer::addInstanceToPool($obj1, $key1);
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
                } // if $obj2 already loaded

                // Add the $obj1 (Student) to the collection in $obj2 (Application)
                $obj2->addStudent($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolHealth rows

                $key3 = SchoolHealthPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = SchoolHealthPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = SchoolHealthPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SchoolHealthPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Student) to the collection in $obj3 (SchoolHealth)
                $obj3->addStudent($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Student objects pre-filled with all related objects except Application.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Student objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptApplication(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StudentPeer::DATABASE_NAME);
        }

        StudentPeer::addSelectColumns($criteria);
        $startcol2 = StudentPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserPeer::NUM_HYDRATE_COLUMNS;

        SchoolHealthPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SchoolHealthPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(StudentPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(StudentPeer::HEALTH_ID, SchoolHealthPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StudentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StudentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = StudentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StudentPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined User rows

                $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = UserPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Student) to the collection in $obj2 (User)
                $obj2->addStudent($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolHealth rows

                $key3 = SchoolHealthPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = SchoolHealthPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = SchoolHealthPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SchoolHealthPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Student) to the collection in $obj3 (SchoolHealth)
                $obj3->addStudent($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Student objects pre-filled with all related objects except SchoolHealth.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Student objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptSchoolHealth(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StudentPeer::DATABASE_NAME);
        }

        StudentPeer::addSelectColumns($criteria);
        $startcol2 = StudentPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserPeer::NUM_HYDRATE_COLUMNS;

        ApplicationPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + ApplicationPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(StudentPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(StudentPeer::APPLICATION_ID, ApplicationPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StudentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StudentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = StudentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StudentPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined User rows

                $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = UserPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Student) to the collection in $obj2 (User)
                $obj2->addStudent($obj1);

            } // if joined row is not null

                // Add objects for joined Application rows

                $key3 = ApplicationPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = ApplicationPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = ApplicationPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    ApplicationPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Student) to the collection in $obj3 (Application)
                $obj3->addStudent($obj1);

            } // if joined row is not null

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
        return Propel::getDatabaseMap(StudentPeer::DATABASE_NAME)->getTable(StudentPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseStudentPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseStudentPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \PGS\CoreDomainBundle\Model\Student\map\StudentTableMap());
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

        $event = new DetectOMClassEvent(StudentPeer::OM_CLASS, $row, $colnum);
        EventDispatcherProxy::trigger('om.detect', $event);
        if($event->isDetected()){
            return $event->getDetectedClass();
        }

        return StudentPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a Student or Criteria object.
     *
     * @param      mixed $values Criteria or Student object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(StudentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from Student object
        }

        if ($criteria->containsKey(StudentPeer::ID) && $criteria->keyContainsValue(StudentPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.StudentPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(StudentPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a Student or Criteria object.
     *
     * @param      mixed $values Criteria or Student object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(StudentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(StudentPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(StudentPeer::ID);
            $value = $criteria->remove(StudentPeer::ID);
            if ($value) {
                $selectCriteria->add(StudentPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(StudentPeer::TABLE_NAME);
            }

        } else { // $values is Student object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(StudentPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the student table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(StudentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(StudentPeer::TABLE_NAME, $con, StudentPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            StudentPeer::clearInstancePool();
            StudentPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a Student or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or Student object or primary key or array of primary keys
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
            $con = Propel::getConnection(StudentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            StudentPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof Student) { // it's a model object
            // invalidate the cache for this single object
            StudentPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(StudentPeer::DATABASE_NAME);
            $criteria->add(StudentPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                StudentPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(StudentPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            StudentPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given Student object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param Student $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(StudentPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(StudentPeer::TABLE_NAME);

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

        return BasePeer::doValidate(StudentPeer::DATABASE_NAME, StudentPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return Student
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = StudentPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(StudentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(StudentPeer::DATABASE_NAME);
        $criteria->add(StudentPeer::ID, $pk);

        $v = StudentPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return Student[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(StudentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(StudentPeer::DATABASE_NAME);
            $criteria->add(StudentPeer::ID, $pks, Criteria::IN);
            $objs = StudentPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseStudentPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseStudentPeer::buildTableMap();

EventDispatcherProxy::trigger(array('construct','peer.construct'), new PeerEvent('PGS\CoreDomainBundle\Model\Student\om\BaseStudentPeer'));
