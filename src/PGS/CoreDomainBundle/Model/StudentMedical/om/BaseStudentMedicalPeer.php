<?php

namespace PGS\CoreDomainBundle\Model\StudentMedical\om;

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
use PGS\CoreDomainBundle\Model\Medical\MedicalPeer;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealthPeer;
use PGS\CoreDomainBundle\Model\StudentMedical\StudentMedical;
use PGS\CoreDomainBundle\Model\StudentMedical\StudentMedicalPeer;
use PGS\CoreDomainBundle\Model\StudentMedical\map\StudentMedicalTableMap;

abstract class BaseStudentMedicalPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'student_medical';

    /** the related Propel class for this table */
    const OM_CLASS = 'PGS\\CoreDomainBundle\\Model\\StudentMedical\\StudentMedical';

    /** the related TableMap class for this table */
    const TM_CLASS = 'PGS\\CoreDomainBundle\\Model\\StudentMedical\\map\\StudentMedicalTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 4;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 4;

    /** the column name for the school_health_id field */
    const SCHOOL_HEALTH_ID = 'student_medical.school_health_id';

    /** the column name for the medical_id field */
    const MEDICAL_ID = 'student_medical.medical_id';

    /** the column name for the created_at field */
    const CREATED_AT = 'student_medical.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'student_medical.updated_at';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of StudentMedical objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array StudentMedical[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. StudentMedicalPeer::$fieldNames[StudentMedicalPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('SchoolHealthId', 'MedicalId', 'CreatedAt', 'UpdatedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('schoolHealthId', 'medicalId', 'createdAt', 'updatedAt', ),
        BasePeer::TYPE_COLNAME => array (StudentMedicalPeer::SCHOOL_HEALTH_ID, StudentMedicalPeer::MEDICAL_ID, StudentMedicalPeer::CREATED_AT, StudentMedicalPeer::UPDATED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('SCHOOL_HEALTH_ID', 'MEDICAL_ID', 'CREATED_AT', 'UPDATED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('school_health_id', 'medical_id', 'created_at', 'updated_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. StudentMedicalPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('SchoolHealthId' => 0, 'MedicalId' => 1, 'CreatedAt' => 2, 'UpdatedAt' => 3, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('schoolHealthId' => 0, 'medicalId' => 1, 'createdAt' => 2, 'updatedAt' => 3, ),
        BasePeer::TYPE_COLNAME => array (StudentMedicalPeer::SCHOOL_HEALTH_ID => 0, StudentMedicalPeer::MEDICAL_ID => 1, StudentMedicalPeer::CREATED_AT => 2, StudentMedicalPeer::UPDATED_AT => 3, ),
        BasePeer::TYPE_RAW_COLNAME => array ('SCHOOL_HEALTH_ID' => 0, 'MEDICAL_ID' => 1, 'CREATED_AT' => 2, 'UPDATED_AT' => 3, ),
        BasePeer::TYPE_FIELDNAME => array ('school_health_id' => 0, 'medical_id' => 1, 'created_at' => 2, 'updated_at' => 3, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, )
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
        $toNames = StudentMedicalPeer::getFieldNames($toType);
        $key = isset(StudentMedicalPeer::$fieldKeys[$fromType][$name]) ? StudentMedicalPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(StudentMedicalPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, StudentMedicalPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return StudentMedicalPeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. StudentMedicalPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(StudentMedicalPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(StudentMedicalPeer::SCHOOL_HEALTH_ID);
            $criteria->addSelectColumn(StudentMedicalPeer::MEDICAL_ID);
            $criteria->addSelectColumn(StudentMedicalPeer::CREATED_AT);
            $criteria->addSelectColumn(StudentMedicalPeer::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.school_health_id');
            $criteria->addSelectColumn($alias . '.medical_id');
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
        $criteria->setPrimaryTableName(StudentMedicalPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StudentMedicalPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(StudentMedicalPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(StudentMedicalPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return StudentMedical
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = StudentMedicalPeer::doSelect($critcopy, $con);
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
        return StudentMedicalPeer::populateObjects(StudentMedicalPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(StudentMedicalPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            StudentMedicalPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(StudentMedicalPeer::DATABASE_NAME);

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
     * @param StudentMedical $obj A StudentMedical object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = serialize(array((string) $obj->getSchoolHealthId(), (string) $obj->getMedicalId()));
            } // if key === null
            StudentMedicalPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A StudentMedical object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof StudentMedical) {
                $key = serialize(array((string) $value->getSchoolHealthId(), (string) $value->getMedicalId()));
            } elseif (is_array($value) && count($value) === 2) {
                // assume we've been passed a primary key
                $key = serialize(array((string) $value[0], (string) $value[1]));
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or StudentMedical object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(StudentMedicalPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return StudentMedical Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(StudentMedicalPeer::$instances[$key])) {
                return StudentMedicalPeer::$instances[$key];
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
        foreach (StudentMedicalPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        StudentMedicalPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to student_medical
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
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
        if ($row[$startcol] === null && $row[$startcol + 1] === null) {
            return null;
        }

        return serialize(array((string) $row[$startcol], (string) $row[$startcol + 1]));
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

        return array((int) $row[$startcol], (int) $row[$startcol + 1]);
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
        $cls = StudentMedicalPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = StudentMedicalPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = StudentMedicalPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                StudentMedicalPeer::addInstanceToPool($obj, $key);
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
     * @return array (StudentMedical object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = StudentMedicalPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = StudentMedicalPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + StudentMedicalPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = StudentMedicalPeer::getOMClass($row, $startcol);
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            StudentMedicalPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
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
        $criteria->setPrimaryTableName(StudentMedicalPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StudentMedicalPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(StudentMedicalPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StudentMedicalPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StudentMedicalPeer::SCHOOL_HEALTH_ID, SchoolHealthPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Medical table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinMedical(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(StudentMedicalPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StudentMedicalPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(StudentMedicalPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StudentMedicalPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StudentMedicalPeer::MEDICAL_ID, MedicalPeer::ID, $join_behavior);

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
     * Selects a collection of StudentMedical objects pre-filled with their SchoolHealth objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of StudentMedical objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinSchoolHealth(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StudentMedicalPeer::DATABASE_NAME);
        }

        StudentMedicalPeer::addSelectColumns($criteria);
        $startcol = StudentMedicalPeer::NUM_HYDRATE_COLUMNS;
        SchoolHealthPeer::addSelectColumns($criteria);

        $criteria->addJoin(StudentMedicalPeer::SCHOOL_HEALTH_ID, SchoolHealthPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StudentMedicalPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StudentMedicalPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = StudentMedicalPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StudentMedicalPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (StudentMedical) to $obj2 (SchoolHealth)
                $obj2->addStudentMedical($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of StudentMedical objects pre-filled with their Medical objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of StudentMedical objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinMedical(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StudentMedicalPeer::DATABASE_NAME);
        }

        StudentMedicalPeer::addSelectColumns($criteria);
        $startcol = StudentMedicalPeer::NUM_HYDRATE_COLUMNS;
        MedicalPeer::addSelectColumns($criteria);

        $criteria->addJoin(StudentMedicalPeer::MEDICAL_ID, MedicalPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StudentMedicalPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StudentMedicalPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = StudentMedicalPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StudentMedicalPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = MedicalPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = MedicalPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = MedicalPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    MedicalPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (StudentMedical) to $obj2 (Medical)
                $obj2->addStudentMedical($obj1);

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
        $criteria->setPrimaryTableName(StudentMedicalPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StudentMedicalPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(StudentMedicalPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StudentMedicalPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StudentMedicalPeer::SCHOOL_HEALTH_ID, SchoolHealthPeer::ID, $join_behavior);

        $criteria->addJoin(StudentMedicalPeer::MEDICAL_ID, MedicalPeer::ID, $join_behavior);

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
     * Selects a collection of StudentMedical objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of StudentMedical objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StudentMedicalPeer::DATABASE_NAME);
        }

        StudentMedicalPeer::addSelectColumns($criteria);
        $startcol2 = StudentMedicalPeer::NUM_HYDRATE_COLUMNS;

        SchoolHealthPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + SchoolHealthPeer::NUM_HYDRATE_COLUMNS;

        MedicalPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + MedicalPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(StudentMedicalPeer::SCHOOL_HEALTH_ID, SchoolHealthPeer::ID, $join_behavior);

        $criteria->addJoin(StudentMedicalPeer::MEDICAL_ID, MedicalPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StudentMedicalPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StudentMedicalPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = StudentMedicalPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StudentMedicalPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined SchoolHealth rows

            $key2 = SchoolHealthPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = SchoolHealthPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = SchoolHealthPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    SchoolHealthPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (StudentMedical) to the collection in $obj2 (SchoolHealth)
                $obj2->addStudentMedical($obj1);
            } // if joined row not null

            // Add objects for joined Medical rows

            $key3 = MedicalPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = MedicalPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = MedicalPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    MedicalPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (StudentMedical) to the collection in $obj3 (Medical)
                $obj3->addStudentMedical($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
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
        $criteria->setPrimaryTableName(StudentMedicalPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StudentMedicalPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(StudentMedicalPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StudentMedicalPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StudentMedicalPeer::MEDICAL_ID, MedicalPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Medical table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptMedical(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(StudentMedicalPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StudentMedicalPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(StudentMedicalPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StudentMedicalPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StudentMedicalPeer::SCHOOL_HEALTH_ID, SchoolHealthPeer::ID, $join_behavior);

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
     * Selects a collection of StudentMedical objects pre-filled with all related objects except SchoolHealth.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of StudentMedical objects.
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
            $criteria->setDbName(StudentMedicalPeer::DATABASE_NAME);
        }

        StudentMedicalPeer::addSelectColumns($criteria);
        $startcol2 = StudentMedicalPeer::NUM_HYDRATE_COLUMNS;

        MedicalPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + MedicalPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(StudentMedicalPeer::MEDICAL_ID, MedicalPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StudentMedicalPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StudentMedicalPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = StudentMedicalPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StudentMedicalPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Medical rows

                $key2 = MedicalPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = MedicalPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = MedicalPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    MedicalPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (StudentMedical) to the collection in $obj2 (Medical)
                $obj2->addStudentMedical($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of StudentMedical objects pre-filled with all related objects except Medical.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of StudentMedical objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptMedical(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StudentMedicalPeer::DATABASE_NAME);
        }

        StudentMedicalPeer::addSelectColumns($criteria);
        $startcol2 = StudentMedicalPeer::NUM_HYDRATE_COLUMNS;

        SchoolHealthPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + SchoolHealthPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(StudentMedicalPeer::SCHOOL_HEALTH_ID, SchoolHealthPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StudentMedicalPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StudentMedicalPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = StudentMedicalPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StudentMedicalPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined SchoolHealth rows

                $key2 = SchoolHealthPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = SchoolHealthPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = SchoolHealthPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    SchoolHealthPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (StudentMedical) to the collection in $obj2 (SchoolHealth)
                $obj2->addStudentMedical($obj1);

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
        return Propel::getDatabaseMap(StudentMedicalPeer::DATABASE_NAME)->getTable(StudentMedicalPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseStudentMedicalPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseStudentMedicalPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \PGS\CoreDomainBundle\Model\StudentMedical\map\StudentMedicalTableMap());
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

        $event = new DetectOMClassEvent(StudentMedicalPeer::OM_CLASS, $row, $colnum);
        EventDispatcherProxy::trigger('om.detect', $event);
        if($event->isDetected()){
            return $event->getDetectedClass();
        }

        return StudentMedicalPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a StudentMedical or Criteria object.
     *
     * @param      mixed $values Criteria or StudentMedical object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(StudentMedicalPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from StudentMedical object
        }


        // Set the correct dbName
        $criteria->setDbName(StudentMedicalPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a StudentMedical or Criteria object.
     *
     * @param      mixed $values Criteria or StudentMedical object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(StudentMedicalPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(StudentMedicalPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(StudentMedicalPeer::SCHOOL_HEALTH_ID);
            $value = $criteria->remove(StudentMedicalPeer::SCHOOL_HEALTH_ID);
            if ($value) {
                $selectCriteria->add(StudentMedicalPeer::SCHOOL_HEALTH_ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(StudentMedicalPeer::TABLE_NAME);
            }

            $comparison = $criteria->getComparison(StudentMedicalPeer::MEDICAL_ID);
            $value = $criteria->remove(StudentMedicalPeer::MEDICAL_ID);
            if ($value) {
                $selectCriteria->add(StudentMedicalPeer::MEDICAL_ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(StudentMedicalPeer::TABLE_NAME);
            }

        } else { // $values is StudentMedical object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(StudentMedicalPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the student_medical table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(StudentMedicalPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(StudentMedicalPeer::TABLE_NAME, $con, StudentMedicalPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            StudentMedicalPeer::clearInstancePool();
            StudentMedicalPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a StudentMedical or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or StudentMedical object or primary key or array of primary keys
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
            $con = Propel::getConnection(StudentMedicalPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            StudentMedicalPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof StudentMedical) { // it's a model object
            // invalidate the cache for this single object
            StudentMedicalPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(StudentMedicalPeer::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(StudentMedicalPeer::SCHOOL_HEALTH_ID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(StudentMedicalPeer::MEDICAL_ID, $value[1]));
                $criteria->addOr($criterion);
                // we can invalidate the cache for this single PK
                StudentMedicalPeer::removeInstanceFromPool($value);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(StudentMedicalPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            StudentMedicalPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given StudentMedical object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param StudentMedical $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(StudentMedicalPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(StudentMedicalPeer::TABLE_NAME);

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

        return BasePeer::doValidate(StudentMedicalPeer::DATABASE_NAME, StudentMedicalPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve object using using composite pkey values.
     * @param   int $school_health_id
     * @param   int $medical_id
     * @param      PropelPDO $con
     * @return StudentMedical
     */
    public static function retrieveByPK($school_health_id, $medical_id, PropelPDO $con = null) {
        $_instancePoolKey = serialize(array((string) $school_health_id, (string) $medical_id));
         if (null !== ($obj = StudentMedicalPeer::getInstanceFromPool($_instancePoolKey))) {
             return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(StudentMedicalPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $criteria = new Criteria(StudentMedicalPeer::DATABASE_NAME);
        $criteria->add(StudentMedicalPeer::SCHOOL_HEALTH_ID, $school_health_id);
        $criteria->add(StudentMedicalPeer::MEDICAL_ID, $medical_id);
        $v = StudentMedicalPeer::doSelect($criteria, $con);

        return !empty($v) ? $v[0] : null;
    }
} // BaseStudentMedicalPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseStudentMedicalPeer::buildTableMap();

EventDispatcherProxy::trigger(array('construct','peer.construct'), new PeerEvent('PGS\CoreDomainBundle\Model\StudentMedical\om\BaseStudentMedicalPeer'));