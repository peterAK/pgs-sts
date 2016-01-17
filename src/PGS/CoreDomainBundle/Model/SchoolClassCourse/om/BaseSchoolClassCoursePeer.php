<?php

namespace PGS\CoreDomainBundle\Model\SchoolClassCourse\om;

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
use PGS\CoreDomainBundle\Model\Course\CoursePeer;
use PGS\CoreDomainBundle\Model\Formula\FormulaPeer;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClassPeer;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourse;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCoursePeer;
use PGS\CoreDomainBundle\Model\SchoolClassCourseStudentBehavior\SchoolClassCourseStudentBehaviorPeer;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\map\SchoolClassCourseTableMap;
use PGS\CoreDomainBundle\Model\SchoolGradeLevel\SchoolGradeLevelPeer;
use PGS\CoreDomainBundle\Model\SchoolTerm\SchoolTermPeer;
use PGS\CoreDomainBundle\Model\Score\ScorePeer;

abstract class BaseSchoolClassCoursePeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'school_class_course';

    /** the related Propel class for this table */
    const OM_CLASS = 'PGS\\CoreDomainBundle\\Model\\SchoolClassCourse\\SchoolClassCourse';

    /** the related TableMap class for this table */
    const TM_CLASS = 'PGS\\CoreDomainBundle\\Model\\SchoolClassCourse\\map\\SchoolClassCourseTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 13;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 13;

    /** the column name for the id field */
    const ID = 'school_class_course.id';

    /** the column name for the name field */
    const NAME = 'school_class_course.name';

    /** the column name for the school_class_id field */
    const SCHOOL_CLASS_ID = 'school_class_course.school_class_id';

    /** the column name for the start_time field */
    const START_TIME = 'school_class_course.start_time';

    /** the column name for the end_time field */
    const END_TIME = 'school_class_course.end_time';

    /** the column name for the course_id field */
    const COURSE_ID = 'school_class_course.course_id';

    /** the column name for the school_term_id field */
    const SCHOOL_TERM_ID = 'school_class_course.school_term_id';

    /** the column name for the school_grade_level_id field */
    const SCHOOL_GRADE_LEVEL_ID = 'school_class_course.school_grade_level_id';

    /** the column name for the primary_teacher_id field */
    const PRIMARY_TEACHER_ID = 'school_class_course.primary_teacher_id';

    /** the column name for the secondary_teacher_id field */
    const SECONDARY_TEACHER_ID = 'school_class_course.secondary_teacher_id';

    /** the column name for the formula_id field */
    const FORMULA_ID = 'school_class_course.formula_id';

    /** the column name for the created_at field */
    const CREATED_AT = 'school_class_course.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'school_class_course.updated_at';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of SchoolClassCourse objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array SchoolClassCourse[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. SchoolClassCoursePeer::$fieldNames[SchoolClassCoursePeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Name', 'SchoolClassId', 'StartTime', 'EndTime', 'CourseId', 'SchoolTermId', 'SchoolGradeLevelId', 'PrimaryTeacherId', 'SecondaryTeacherId', 'FormulaId', 'CreatedAt', 'UpdatedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'name', 'schoolClassId', 'startTime', 'endTime', 'courseId', 'schoolTermId', 'schoolGradeLevelId', 'primaryTeacherId', 'secondaryTeacherId', 'formulaId', 'createdAt', 'updatedAt', ),
        BasePeer::TYPE_COLNAME => array (SchoolClassCoursePeer::ID, SchoolClassCoursePeer::NAME, SchoolClassCoursePeer::SCHOOL_CLASS_ID, SchoolClassCoursePeer::START_TIME, SchoolClassCoursePeer::END_TIME, SchoolClassCoursePeer::COURSE_ID, SchoolClassCoursePeer::SCHOOL_TERM_ID, SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, SchoolClassCoursePeer::PRIMARY_TEACHER_ID, SchoolClassCoursePeer::SECONDARY_TEACHER_ID, SchoolClassCoursePeer::FORMULA_ID, SchoolClassCoursePeer::CREATED_AT, SchoolClassCoursePeer::UPDATED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'NAME', 'SCHOOL_CLASS_ID', 'START_TIME', 'END_TIME', 'COURSE_ID', 'SCHOOL_TERM_ID', 'SCHOOL_GRADE_LEVEL_ID', 'PRIMARY_TEACHER_ID', 'SECONDARY_TEACHER_ID', 'FORMULA_ID', 'CREATED_AT', 'UPDATED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'name', 'school_class_id', 'start_time', 'end_time', 'course_id', 'school_term_id', 'school_grade_level_id', 'primary_teacher_id', 'secondary_teacher_id', 'formula_id', 'created_at', 'updated_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. SchoolClassCoursePeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Name' => 1, 'SchoolClassId' => 2, 'StartTime' => 3, 'EndTime' => 4, 'CourseId' => 5, 'SchoolTermId' => 6, 'SchoolGradeLevelId' => 7, 'PrimaryTeacherId' => 8, 'SecondaryTeacherId' => 9, 'FormulaId' => 10, 'CreatedAt' => 11, 'UpdatedAt' => 12, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'name' => 1, 'schoolClassId' => 2, 'startTime' => 3, 'endTime' => 4, 'courseId' => 5, 'schoolTermId' => 6, 'schoolGradeLevelId' => 7, 'primaryTeacherId' => 8, 'secondaryTeacherId' => 9, 'formulaId' => 10, 'createdAt' => 11, 'updatedAt' => 12, ),
        BasePeer::TYPE_COLNAME => array (SchoolClassCoursePeer::ID => 0, SchoolClassCoursePeer::NAME => 1, SchoolClassCoursePeer::SCHOOL_CLASS_ID => 2, SchoolClassCoursePeer::START_TIME => 3, SchoolClassCoursePeer::END_TIME => 4, SchoolClassCoursePeer::COURSE_ID => 5, SchoolClassCoursePeer::SCHOOL_TERM_ID => 6, SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID => 7, SchoolClassCoursePeer::PRIMARY_TEACHER_ID => 8, SchoolClassCoursePeer::SECONDARY_TEACHER_ID => 9, SchoolClassCoursePeer::FORMULA_ID => 10, SchoolClassCoursePeer::CREATED_AT => 11, SchoolClassCoursePeer::UPDATED_AT => 12, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'NAME' => 1, 'SCHOOL_CLASS_ID' => 2, 'START_TIME' => 3, 'END_TIME' => 4, 'COURSE_ID' => 5, 'SCHOOL_TERM_ID' => 6, 'SCHOOL_GRADE_LEVEL_ID' => 7, 'PRIMARY_TEACHER_ID' => 8, 'SECONDARY_TEACHER_ID' => 9, 'FORMULA_ID' => 10, 'CREATED_AT' => 11, 'UPDATED_AT' => 12, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'name' => 1, 'school_class_id' => 2, 'start_time' => 3, 'end_time' => 4, 'course_id' => 5, 'school_term_id' => 6, 'school_grade_level_id' => 7, 'primary_teacher_id' => 8, 'secondary_teacher_id' => 9, 'formula_id' => 10, 'created_at' => 11, 'updated_at' => 12, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
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
        $toNames = SchoolClassCoursePeer::getFieldNames($toType);
        $key = isset(SchoolClassCoursePeer::$fieldKeys[$fromType][$name]) ? SchoolClassCoursePeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(SchoolClassCoursePeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, SchoolClassCoursePeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return SchoolClassCoursePeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. SchoolClassCoursePeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(SchoolClassCoursePeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(SchoolClassCoursePeer::ID);
            $criteria->addSelectColumn(SchoolClassCoursePeer::NAME);
            $criteria->addSelectColumn(SchoolClassCoursePeer::SCHOOL_CLASS_ID);
            $criteria->addSelectColumn(SchoolClassCoursePeer::START_TIME);
            $criteria->addSelectColumn(SchoolClassCoursePeer::END_TIME);
            $criteria->addSelectColumn(SchoolClassCoursePeer::COURSE_ID);
            $criteria->addSelectColumn(SchoolClassCoursePeer::SCHOOL_TERM_ID);
            $criteria->addSelectColumn(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID);
            $criteria->addSelectColumn(SchoolClassCoursePeer::PRIMARY_TEACHER_ID);
            $criteria->addSelectColumn(SchoolClassCoursePeer::SECONDARY_TEACHER_ID);
            $criteria->addSelectColumn(SchoolClassCoursePeer::FORMULA_ID);
            $criteria->addSelectColumn(SchoolClassCoursePeer::CREATED_AT);
            $criteria->addSelectColumn(SchoolClassCoursePeer::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.school_class_id');
            $criteria->addSelectColumn($alias . '.start_time');
            $criteria->addSelectColumn($alias . '.end_time');
            $criteria->addSelectColumn($alias . '.course_id');
            $criteria->addSelectColumn($alias . '.school_term_id');
            $criteria->addSelectColumn($alias . '.school_grade_level_id');
            $criteria->addSelectColumn($alias . '.primary_teacher_id');
            $criteria->addSelectColumn($alias . '.secondary_teacher_id');
            $criteria->addSelectColumn($alias . '.formula_id');
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
        $criteria->setPrimaryTableName(SchoolClassCoursePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolClassCoursePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return SchoolClassCourse
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = SchoolClassCoursePeer::doSelect($critcopy, $con);
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
        return SchoolClassCoursePeer::populateObjects(SchoolClassCoursePeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            SchoolClassCoursePeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);

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
     * @param SchoolClassCourse $obj A SchoolClassCourse object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            SchoolClassCoursePeer::$instances[$key] = $obj;
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
     * @param      mixed $value A SchoolClassCourse object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof SchoolClassCourse) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or SchoolClassCourse object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(SchoolClassCoursePeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return SchoolClassCourse Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(SchoolClassCoursePeer::$instances[$key])) {
                return SchoolClassCoursePeer::$instances[$key];
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
        foreach (SchoolClassCoursePeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        SchoolClassCoursePeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to school_class_course
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in SchoolClassCourseStudentBehaviorPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        SchoolClassCourseStudentBehaviorPeer::clearInstancePool();
        // Invalidate objects in ScorePeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        ScorePeer::clearInstancePool();
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
        $cls = SchoolClassCoursePeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = SchoolClassCoursePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = SchoolClassCoursePeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SchoolClassCoursePeer::addInstanceToPool($obj, $key);
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
     * @return array (SchoolClassCourse object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = SchoolClassCoursePeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = SchoolClassCoursePeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + SchoolClassCoursePeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SchoolClassCoursePeer::getOMClass($row, $startcol);
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            SchoolClassCoursePeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related Course table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinCourse(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolClassCoursePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolClassCoursePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolClassCoursePeer::COURSE_ID, CoursePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related SchoolClass table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinSchoolClass(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolClassCoursePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolClassCoursePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_CLASS_ID, SchoolClassPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related SchoolTerm table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinSchoolTerm(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolClassCoursePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolClassCoursePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_TERM_ID, SchoolTermPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related SchoolGradeLevel table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinSchoolGradeLevel(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolClassCoursePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolClassCoursePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, SchoolGradeLevelPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related PrimaryTeacher table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPrimaryTeacher(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolClassCoursePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolClassCoursePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolClassCoursePeer::PRIMARY_TEACHER_ID, UserPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related SecondaryTeacher table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinSecondaryTeacher(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolClassCoursePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolClassCoursePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolClassCoursePeer::SECONDARY_TEACHER_ID, UserPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Formula table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinFormula(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolClassCoursePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolClassCoursePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolClassCoursePeer::FORMULA_ID, FormulaPeer::ID, $join_behavior);

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
     * Selects a collection of SchoolClassCourse objects pre-filled with their Course objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of SchoolClassCourse objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinCourse(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);
        }

        SchoolClassCoursePeer::addSelectColumns($criteria);
        $startcol = SchoolClassCoursePeer::NUM_HYDRATE_COLUMNS;
        CoursePeer::addSelectColumns($criteria);

        $criteria->addJoin(SchoolClassCoursePeer::COURSE_ID, CoursePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolClassCoursePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolClassCoursePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SchoolClassCoursePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolClassCoursePeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = CoursePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = CoursePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = CoursePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    CoursePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (SchoolClassCourse) to $obj2 (Course)
                $obj2->addSchoolClassCourse($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of SchoolClassCourse objects pre-filled with their SchoolClass objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of SchoolClassCourse objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinSchoolClass(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);
        }

        SchoolClassCoursePeer::addSelectColumns($criteria);
        $startcol = SchoolClassCoursePeer::NUM_HYDRATE_COLUMNS;
        SchoolClassPeer::addSelectColumns($criteria);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_CLASS_ID, SchoolClassPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolClassCoursePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolClassCoursePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SchoolClassCoursePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolClassCoursePeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = SchoolClassPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = SchoolClassPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = SchoolClassPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    SchoolClassPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (SchoolClassCourse) to $obj2 (SchoolClass)
                $obj2->addSchoolClassCourse($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of SchoolClassCourse objects pre-filled with their SchoolTerm objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of SchoolClassCourse objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinSchoolTerm(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);
        }

        SchoolClassCoursePeer::addSelectColumns($criteria);
        $startcol = SchoolClassCoursePeer::NUM_HYDRATE_COLUMNS;
        SchoolTermPeer::addSelectColumns($criteria);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_TERM_ID, SchoolTermPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolClassCoursePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolClassCoursePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SchoolClassCoursePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolClassCoursePeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = SchoolTermPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = SchoolTermPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = SchoolTermPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    SchoolTermPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (SchoolClassCourse) to $obj2 (SchoolTerm)
                $obj2->addSchoolClassCourse($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of SchoolClassCourse objects pre-filled with their SchoolGradeLevel objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of SchoolClassCourse objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinSchoolGradeLevel(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);
        }

        SchoolClassCoursePeer::addSelectColumns($criteria);
        $startcol = SchoolClassCoursePeer::NUM_HYDRATE_COLUMNS;
        SchoolGradeLevelPeer::addSelectColumns($criteria);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, SchoolGradeLevelPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolClassCoursePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolClassCoursePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SchoolClassCoursePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolClassCoursePeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = SchoolGradeLevelPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = SchoolGradeLevelPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = SchoolGradeLevelPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    SchoolGradeLevelPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (SchoolClassCourse) to $obj2 (SchoolGradeLevel)
                $obj2->addSchoolClassCourse($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of SchoolClassCourse objects pre-filled with their User objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of SchoolClassCourse objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPrimaryTeacher(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);
        }

        SchoolClassCoursePeer::addSelectColumns($criteria);
        $startcol = SchoolClassCoursePeer::NUM_HYDRATE_COLUMNS;
        UserPeer::addSelectColumns($criteria);

        $criteria->addJoin(SchoolClassCoursePeer::PRIMARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolClassCoursePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolClassCoursePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SchoolClassCoursePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolClassCoursePeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (SchoolClassCourse) to $obj2 (User)
                $obj2->addSchoolClassCourseRelatedByPrimaryTeacherId($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of SchoolClassCourse objects pre-filled with their User objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of SchoolClassCourse objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinSecondaryTeacher(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);
        }

        SchoolClassCoursePeer::addSelectColumns($criteria);
        $startcol = SchoolClassCoursePeer::NUM_HYDRATE_COLUMNS;
        UserPeer::addSelectColumns($criteria);

        $criteria->addJoin(SchoolClassCoursePeer::SECONDARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolClassCoursePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolClassCoursePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SchoolClassCoursePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolClassCoursePeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (SchoolClassCourse) to $obj2 (User)
                $obj2->addSchoolClassCourseRelatedBySecondaryTeacherId($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of SchoolClassCourse objects pre-filled with their Formula objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of SchoolClassCourse objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinFormula(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);
        }

        SchoolClassCoursePeer::addSelectColumns($criteria);
        $startcol = SchoolClassCoursePeer::NUM_HYDRATE_COLUMNS;
        FormulaPeer::addSelectColumns($criteria);

        $criteria->addJoin(SchoolClassCoursePeer::FORMULA_ID, FormulaPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolClassCoursePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolClassCoursePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SchoolClassCoursePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolClassCoursePeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = FormulaPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = FormulaPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = FormulaPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    FormulaPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (SchoolClassCourse) to $obj2 (Formula)
                $obj2->addSchoolClassCourse($obj1);

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
        $criteria->setPrimaryTableName(SchoolClassCoursePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolClassCoursePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolClassCoursePeer::COURSE_ID, CoursePeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_CLASS_ID, SchoolClassPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_TERM_ID, SchoolTermPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, SchoolGradeLevelPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::PRIMARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SECONDARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::FORMULA_ID, FormulaPeer::ID, $join_behavior);

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
     * Selects a collection of SchoolClassCourse objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of SchoolClassCourse objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);
        }

        SchoolClassCoursePeer::addSelectColumns($criteria);
        $startcol2 = SchoolClassCoursePeer::NUM_HYDRATE_COLUMNS;

        CoursePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CoursePeer::NUM_HYDRATE_COLUMNS;

        SchoolClassPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SchoolClassPeer::NUM_HYDRATE_COLUMNS;

        SchoolTermPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + SchoolTermPeer::NUM_HYDRATE_COLUMNS;

        SchoolGradeLevelPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + SchoolGradeLevelPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + UserPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + UserPeer::NUM_HYDRATE_COLUMNS;

        FormulaPeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + FormulaPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SchoolClassCoursePeer::COURSE_ID, CoursePeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_CLASS_ID, SchoolClassPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_TERM_ID, SchoolTermPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, SchoolGradeLevelPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::PRIMARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SECONDARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::FORMULA_ID, FormulaPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolClassCoursePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolClassCoursePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SchoolClassCoursePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolClassCoursePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined Course rows

            $key2 = CoursePeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = CoursePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = CoursePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CoursePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj2 (Course)
                $obj2->addSchoolClassCourse($obj1);
            } // if joined row not null

            // Add objects for joined SchoolClass rows

            $key3 = SchoolClassPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = SchoolClassPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = SchoolClassPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SchoolClassPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj3 (SchoolClass)
                $obj3->addSchoolClassCourse($obj1);
            } // if joined row not null

            // Add objects for joined SchoolTerm rows

            $key4 = SchoolTermPeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = SchoolTermPeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = SchoolTermPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    SchoolTermPeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj4 (SchoolTerm)
                $obj4->addSchoolClassCourse($obj1);
            } // if joined row not null

            // Add objects for joined SchoolGradeLevel rows

            $key5 = SchoolGradeLevelPeer::getPrimaryKeyHashFromRow($row, $startcol5);
            if ($key5 !== null) {
                $obj5 = SchoolGradeLevelPeer::getInstanceFromPool($key5);
                if (!$obj5) {

                    $cls = SchoolGradeLevelPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    SchoolGradeLevelPeer::addInstanceToPool($obj5, $key5);
                } // if obj5 loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj5 (SchoolGradeLevel)
                $obj5->addSchoolClassCourse($obj1);
            } // if joined row not null

            // Add objects for joined User rows

            $key6 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol6);
            if ($key6 !== null) {
                $obj6 = UserPeer::getInstanceFromPool($key6);
                if (!$obj6) {

                    $cls = UserPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    UserPeer::addInstanceToPool($obj6, $key6);
                } // if obj6 loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj6 (User)
                $obj6->addSchoolClassCourseRelatedByPrimaryTeacherId($obj1);
            } // if joined row not null

            // Add objects for joined User rows

            $key7 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol7);
            if ($key7 !== null) {
                $obj7 = UserPeer::getInstanceFromPool($key7);
                if (!$obj7) {

                    $cls = UserPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    UserPeer::addInstanceToPool($obj7, $key7);
                } // if obj7 loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj7 (User)
                $obj7->addSchoolClassCourseRelatedBySecondaryTeacherId($obj1);
            } // if joined row not null

            // Add objects for joined Formula rows

            $key8 = FormulaPeer::getPrimaryKeyHashFromRow($row, $startcol8);
            if ($key8 !== null) {
                $obj8 = FormulaPeer::getInstanceFromPool($key8);
                if (!$obj8) {

                    $cls = FormulaPeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    FormulaPeer::addInstanceToPool($obj8, $key8);
                } // if obj8 loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj8 (Formula)
                $obj8->addSchoolClassCourse($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Course table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptCourse(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolClassCoursePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolClassCoursePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_CLASS_ID, SchoolClassPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_TERM_ID, SchoolTermPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, SchoolGradeLevelPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::PRIMARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SECONDARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::FORMULA_ID, FormulaPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related SchoolClass table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptSchoolClass(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolClassCoursePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolClassCoursePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolClassCoursePeer::COURSE_ID, CoursePeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_TERM_ID, SchoolTermPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, SchoolGradeLevelPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::PRIMARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SECONDARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::FORMULA_ID, FormulaPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related SchoolTerm table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptSchoolTerm(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolClassCoursePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolClassCoursePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolClassCoursePeer::COURSE_ID, CoursePeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_CLASS_ID, SchoolClassPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, SchoolGradeLevelPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::PRIMARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SECONDARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::FORMULA_ID, FormulaPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related SchoolGradeLevel table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptSchoolGradeLevel(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolClassCoursePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolClassCoursePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolClassCoursePeer::COURSE_ID, CoursePeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_CLASS_ID, SchoolClassPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_TERM_ID, SchoolTermPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::PRIMARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SECONDARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::FORMULA_ID, FormulaPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related PrimaryTeacher table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptPrimaryTeacher(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolClassCoursePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolClassCoursePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolClassCoursePeer::COURSE_ID, CoursePeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_CLASS_ID, SchoolClassPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_TERM_ID, SchoolTermPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, SchoolGradeLevelPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::FORMULA_ID, FormulaPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related SecondaryTeacher table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptSecondaryTeacher(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolClassCoursePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolClassCoursePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolClassCoursePeer::COURSE_ID, CoursePeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_CLASS_ID, SchoolClassPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_TERM_ID, SchoolTermPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, SchoolGradeLevelPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::FORMULA_ID, FormulaPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Formula table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptFormula(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolClassCoursePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolClassCoursePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolClassCoursePeer::COURSE_ID, CoursePeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_CLASS_ID, SchoolClassPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_TERM_ID, SchoolTermPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, SchoolGradeLevelPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::PRIMARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SECONDARY_TEACHER_ID, UserPeer::ID, $join_behavior);

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
     * Selects a collection of SchoolClassCourse objects pre-filled with all related objects except Course.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of SchoolClassCourse objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptCourse(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);
        }

        SchoolClassCoursePeer::addSelectColumns($criteria);
        $startcol2 = SchoolClassCoursePeer::NUM_HYDRATE_COLUMNS;

        SchoolClassPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + SchoolClassPeer::NUM_HYDRATE_COLUMNS;

        SchoolTermPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SchoolTermPeer::NUM_HYDRATE_COLUMNS;

        SchoolGradeLevelPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + SchoolGradeLevelPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + UserPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + UserPeer::NUM_HYDRATE_COLUMNS;

        FormulaPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + FormulaPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_CLASS_ID, SchoolClassPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_TERM_ID, SchoolTermPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, SchoolGradeLevelPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::PRIMARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SECONDARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::FORMULA_ID, FormulaPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolClassCoursePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolClassCoursePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SchoolClassCoursePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolClassCoursePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined SchoolClass rows

                $key2 = SchoolClassPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = SchoolClassPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = SchoolClassPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    SchoolClassPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj2 (SchoolClass)
                $obj2->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolTerm rows

                $key3 = SchoolTermPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = SchoolTermPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = SchoolTermPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SchoolTermPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj3 (SchoolTerm)
                $obj3->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolGradeLevel rows

                $key4 = SchoolGradeLevelPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = SchoolGradeLevelPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = SchoolGradeLevelPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    SchoolGradeLevelPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj4 (SchoolGradeLevel)
                $obj4->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined User rows

                $key5 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = UserPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = UserPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    UserPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj5 (User)
                $obj5->addSchoolClassCourseRelatedByPrimaryTeacherId($obj1);

            } // if joined row is not null

                // Add objects for joined User rows

                $key6 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = UserPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = UserPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    UserPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj6 (User)
                $obj6->addSchoolClassCourseRelatedBySecondaryTeacherId($obj1);

            } // if joined row is not null

                // Add objects for joined Formula rows

                $key7 = FormulaPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = FormulaPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = FormulaPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    FormulaPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj7 (Formula)
                $obj7->addSchoolClassCourse($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of SchoolClassCourse objects pre-filled with all related objects except SchoolClass.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of SchoolClassCourse objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptSchoolClass(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);
        }

        SchoolClassCoursePeer::addSelectColumns($criteria);
        $startcol2 = SchoolClassCoursePeer::NUM_HYDRATE_COLUMNS;

        CoursePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CoursePeer::NUM_HYDRATE_COLUMNS;

        SchoolTermPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SchoolTermPeer::NUM_HYDRATE_COLUMNS;

        SchoolGradeLevelPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + SchoolGradeLevelPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + UserPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + UserPeer::NUM_HYDRATE_COLUMNS;

        FormulaPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + FormulaPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SchoolClassCoursePeer::COURSE_ID, CoursePeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_TERM_ID, SchoolTermPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, SchoolGradeLevelPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::PRIMARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SECONDARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::FORMULA_ID, FormulaPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolClassCoursePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolClassCoursePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SchoolClassCoursePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolClassCoursePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Course rows

                $key2 = CoursePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CoursePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CoursePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CoursePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj2 (Course)
                $obj2->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolTerm rows

                $key3 = SchoolTermPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = SchoolTermPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = SchoolTermPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SchoolTermPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj3 (SchoolTerm)
                $obj3->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolGradeLevel rows

                $key4 = SchoolGradeLevelPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = SchoolGradeLevelPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = SchoolGradeLevelPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    SchoolGradeLevelPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj4 (SchoolGradeLevel)
                $obj4->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined User rows

                $key5 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = UserPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = UserPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    UserPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj5 (User)
                $obj5->addSchoolClassCourseRelatedByPrimaryTeacherId($obj1);

            } // if joined row is not null

                // Add objects for joined User rows

                $key6 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = UserPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = UserPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    UserPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj6 (User)
                $obj6->addSchoolClassCourseRelatedBySecondaryTeacherId($obj1);

            } // if joined row is not null

                // Add objects for joined Formula rows

                $key7 = FormulaPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = FormulaPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = FormulaPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    FormulaPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj7 (Formula)
                $obj7->addSchoolClassCourse($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of SchoolClassCourse objects pre-filled with all related objects except SchoolTerm.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of SchoolClassCourse objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptSchoolTerm(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);
        }

        SchoolClassCoursePeer::addSelectColumns($criteria);
        $startcol2 = SchoolClassCoursePeer::NUM_HYDRATE_COLUMNS;

        CoursePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CoursePeer::NUM_HYDRATE_COLUMNS;

        SchoolClassPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SchoolClassPeer::NUM_HYDRATE_COLUMNS;

        SchoolGradeLevelPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + SchoolGradeLevelPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + UserPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + UserPeer::NUM_HYDRATE_COLUMNS;

        FormulaPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + FormulaPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SchoolClassCoursePeer::COURSE_ID, CoursePeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_CLASS_ID, SchoolClassPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, SchoolGradeLevelPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::PRIMARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SECONDARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::FORMULA_ID, FormulaPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolClassCoursePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolClassCoursePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SchoolClassCoursePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolClassCoursePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Course rows

                $key2 = CoursePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CoursePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CoursePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CoursePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj2 (Course)
                $obj2->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolClass rows

                $key3 = SchoolClassPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = SchoolClassPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = SchoolClassPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SchoolClassPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj3 (SchoolClass)
                $obj3->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolGradeLevel rows

                $key4 = SchoolGradeLevelPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = SchoolGradeLevelPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = SchoolGradeLevelPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    SchoolGradeLevelPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj4 (SchoolGradeLevel)
                $obj4->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined User rows

                $key5 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = UserPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = UserPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    UserPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj5 (User)
                $obj5->addSchoolClassCourseRelatedByPrimaryTeacherId($obj1);

            } // if joined row is not null

                // Add objects for joined User rows

                $key6 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = UserPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = UserPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    UserPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj6 (User)
                $obj6->addSchoolClassCourseRelatedBySecondaryTeacherId($obj1);

            } // if joined row is not null

                // Add objects for joined Formula rows

                $key7 = FormulaPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = FormulaPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = FormulaPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    FormulaPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj7 (Formula)
                $obj7->addSchoolClassCourse($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of SchoolClassCourse objects pre-filled with all related objects except SchoolGradeLevel.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of SchoolClassCourse objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptSchoolGradeLevel(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);
        }

        SchoolClassCoursePeer::addSelectColumns($criteria);
        $startcol2 = SchoolClassCoursePeer::NUM_HYDRATE_COLUMNS;

        CoursePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CoursePeer::NUM_HYDRATE_COLUMNS;

        SchoolClassPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SchoolClassPeer::NUM_HYDRATE_COLUMNS;

        SchoolTermPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + SchoolTermPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + UserPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + UserPeer::NUM_HYDRATE_COLUMNS;

        FormulaPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + FormulaPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SchoolClassCoursePeer::COURSE_ID, CoursePeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_CLASS_ID, SchoolClassPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_TERM_ID, SchoolTermPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::PRIMARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SECONDARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::FORMULA_ID, FormulaPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolClassCoursePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolClassCoursePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SchoolClassCoursePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolClassCoursePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Course rows

                $key2 = CoursePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CoursePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CoursePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CoursePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj2 (Course)
                $obj2->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolClass rows

                $key3 = SchoolClassPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = SchoolClassPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = SchoolClassPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SchoolClassPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj3 (SchoolClass)
                $obj3->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolTerm rows

                $key4 = SchoolTermPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = SchoolTermPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = SchoolTermPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    SchoolTermPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj4 (SchoolTerm)
                $obj4->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined User rows

                $key5 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = UserPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = UserPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    UserPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj5 (User)
                $obj5->addSchoolClassCourseRelatedByPrimaryTeacherId($obj1);

            } // if joined row is not null

                // Add objects for joined User rows

                $key6 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = UserPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = UserPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    UserPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj6 (User)
                $obj6->addSchoolClassCourseRelatedBySecondaryTeacherId($obj1);

            } // if joined row is not null

                // Add objects for joined Formula rows

                $key7 = FormulaPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = FormulaPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = FormulaPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    FormulaPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj7 (Formula)
                $obj7->addSchoolClassCourse($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of SchoolClassCourse objects pre-filled with all related objects except PrimaryTeacher.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of SchoolClassCourse objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptPrimaryTeacher(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);
        }

        SchoolClassCoursePeer::addSelectColumns($criteria);
        $startcol2 = SchoolClassCoursePeer::NUM_HYDRATE_COLUMNS;

        CoursePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CoursePeer::NUM_HYDRATE_COLUMNS;

        SchoolClassPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SchoolClassPeer::NUM_HYDRATE_COLUMNS;

        SchoolTermPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + SchoolTermPeer::NUM_HYDRATE_COLUMNS;

        SchoolGradeLevelPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + SchoolGradeLevelPeer::NUM_HYDRATE_COLUMNS;

        FormulaPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + FormulaPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SchoolClassCoursePeer::COURSE_ID, CoursePeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_CLASS_ID, SchoolClassPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_TERM_ID, SchoolTermPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, SchoolGradeLevelPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::FORMULA_ID, FormulaPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolClassCoursePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolClassCoursePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SchoolClassCoursePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolClassCoursePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Course rows

                $key2 = CoursePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CoursePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CoursePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CoursePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj2 (Course)
                $obj2->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolClass rows

                $key3 = SchoolClassPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = SchoolClassPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = SchoolClassPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SchoolClassPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj3 (SchoolClass)
                $obj3->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolTerm rows

                $key4 = SchoolTermPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = SchoolTermPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = SchoolTermPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    SchoolTermPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj4 (SchoolTerm)
                $obj4->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolGradeLevel rows

                $key5 = SchoolGradeLevelPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = SchoolGradeLevelPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = SchoolGradeLevelPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    SchoolGradeLevelPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj5 (SchoolGradeLevel)
                $obj5->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined Formula rows

                $key6 = FormulaPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = FormulaPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = FormulaPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    FormulaPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj6 (Formula)
                $obj6->addSchoolClassCourse($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of SchoolClassCourse objects pre-filled with all related objects except SecondaryTeacher.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of SchoolClassCourse objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptSecondaryTeacher(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);
        }

        SchoolClassCoursePeer::addSelectColumns($criteria);
        $startcol2 = SchoolClassCoursePeer::NUM_HYDRATE_COLUMNS;

        CoursePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CoursePeer::NUM_HYDRATE_COLUMNS;

        SchoolClassPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SchoolClassPeer::NUM_HYDRATE_COLUMNS;

        SchoolTermPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + SchoolTermPeer::NUM_HYDRATE_COLUMNS;

        SchoolGradeLevelPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + SchoolGradeLevelPeer::NUM_HYDRATE_COLUMNS;

        FormulaPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + FormulaPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SchoolClassCoursePeer::COURSE_ID, CoursePeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_CLASS_ID, SchoolClassPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_TERM_ID, SchoolTermPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, SchoolGradeLevelPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::FORMULA_ID, FormulaPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolClassCoursePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolClassCoursePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SchoolClassCoursePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolClassCoursePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Course rows

                $key2 = CoursePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CoursePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CoursePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CoursePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj2 (Course)
                $obj2->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolClass rows

                $key3 = SchoolClassPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = SchoolClassPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = SchoolClassPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SchoolClassPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj3 (SchoolClass)
                $obj3->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolTerm rows

                $key4 = SchoolTermPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = SchoolTermPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = SchoolTermPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    SchoolTermPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj4 (SchoolTerm)
                $obj4->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolGradeLevel rows

                $key5 = SchoolGradeLevelPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = SchoolGradeLevelPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = SchoolGradeLevelPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    SchoolGradeLevelPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj5 (SchoolGradeLevel)
                $obj5->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined Formula rows

                $key6 = FormulaPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = FormulaPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = FormulaPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    FormulaPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj6 (Formula)
                $obj6->addSchoolClassCourse($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of SchoolClassCourse objects pre-filled with all related objects except Formula.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of SchoolClassCourse objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptFormula(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);
        }

        SchoolClassCoursePeer::addSelectColumns($criteria);
        $startcol2 = SchoolClassCoursePeer::NUM_HYDRATE_COLUMNS;

        CoursePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CoursePeer::NUM_HYDRATE_COLUMNS;

        SchoolClassPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SchoolClassPeer::NUM_HYDRATE_COLUMNS;

        SchoolTermPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + SchoolTermPeer::NUM_HYDRATE_COLUMNS;

        SchoolGradeLevelPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + SchoolGradeLevelPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + UserPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + UserPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SchoolClassCoursePeer::COURSE_ID, CoursePeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_CLASS_ID, SchoolClassPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_TERM_ID, SchoolTermPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SCHOOL_GRADE_LEVEL_ID, SchoolGradeLevelPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::PRIMARY_TEACHER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolClassCoursePeer::SECONDARY_TEACHER_ID, UserPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolClassCoursePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolClassCoursePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SchoolClassCoursePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolClassCoursePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Course rows

                $key2 = CoursePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CoursePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CoursePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CoursePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj2 (Course)
                $obj2->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolClass rows

                $key3 = SchoolClassPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = SchoolClassPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = SchoolClassPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SchoolClassPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj3 (SchoolClass)
                $obj3->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolTerm rows

                $key4 = SchoolTermPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = SchoolTermPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = SchoolTermPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    SchoolTermPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj4 (SchoolTerm)
                $obj4->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolGradeLevel rows

                $key5 = SchoolGradeLevelPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = SchoolGradeLevelPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = SchoolGradeLevelPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    SchoolGradeLevelPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj5 (SchoolGradeLevel)
                $obj5->addSchoolClassCourse($obj1);

            } // if joined row is not null

                // Add objects for joined User rows

                $key6 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = UserPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = UserPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    UserPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj6 (User)
                $obj6->addSchoolClassCourseRelatedByPrimaryTeacherId($obj1);

            } // if joined row is not null

                // Add objects for joined User rows

                $key7 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = UserPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = UserPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    UserPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (SchoolClassCourse) to the collection in $obj7 (User)
                $obj7->addSchoolClassCourseRelatedBySecondaryTeacherId($obj1);

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
        return Propel::getDatabaseMap(SchoolClassCoursePeer::DATABASE_NAME)->getTable(SchoolClassCoursePeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseSchoolClassCoursePeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseSchoolClassCoursePeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \PGS\CoreDomainBundle\Model\SchoolClassCourse\map\SchoolClassCourseTableMap());
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

        $event = new DetectOMClassEvent(SchoolClassCoursePeer::OM_CLASS, $row, $colnum);
        EventDispatcherProxy::trigger('om.detect', $event);
        if($event->isDetected()){
            return $event->getDetectedClass();
        }

        return SchoolClassCoursePeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a SchoolClassCourse or Criteria object.
     *
     * @param      mixed $values Criteria or SchoolClassCourse object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from SchoolClassCourse object
        }

        if ($criteria->containsKey(SchoolClassCoursePeer::ID) && $criteria->keyContainsValue(SchoolClassCoursePeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SchoolClassCoursePeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a SchoolClassCourse or Criteria object.
     *
     * @param      mixed $values Criteria or SchoolClassCourse object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(SchoolClassCoursePeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(SchoolClassCoursePeer::ID);
            $value = $criteria->remove(SchoolClassCoursePeer::ID);
            if ($value) {
                $selectCriteria->add(SchoolClassCoursePeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(SchoolClassCoursePeer::TABLE_NAME);
            }

        } else { // $values is SchoolClassCourse object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the school_class_course table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(SchoolClassCoursePeer::TABLE_NAME, $con, SchoolClassCoursePeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SchoolClassCoursePeer::clearInstancePool();
            SchoolClassCoursePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a SchoolClassCourse or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or SchoolClassCourse object or primary key or array of primary keys
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
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            SchoolClassCoursePeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof SchoolClassCourse) { // it's a model object
            // invalidate the cache for this single object
            SchoolClassCoursePeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SchoolClassCoursePeer::DATABASE_NAME);
            $criteria->add(SchoolClassCoursePeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                SchoolClassCoursePeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(SchoolClassCoursePeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            SchoolClassCoursePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given SchoolClassCourse object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param SchoolClassCourse $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(SchoolClassCoursePeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(SchoolClassCoursePeer::TABLE_NAME);

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

        return BasePeer::doValidate(SchoolClassCoursePeer::DATABASE_NAME, SchoolClassCoursePeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return SchoolClassCourse
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = SchoolClassCoursePeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(SchoolClassCoursePeer::DATABASE_NAME);
        $criteria->add(SchoolClassCoursePeer::ID, $pk);

        $v = SchoolClassCoursePeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return SchoolClassCourse[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolClassCoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(SchoolClassCoursePeer::DATABASE_NAME);
            $criteria->add(SchoolClassCoursePeer::ID, $pks, Criteria::IN);
            $objs = SchoolClassCoursePeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseSchoolClassCoursePeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseSchoolClassCoursePeer::buildTableMap();

EventDispatcherProxy::trigger(array('construct','peer.construct'), new PeerEvent('PGS\CoreDomainBundle\Model\SchoolClassCourse\om\BaseSchoolClassCoursePeer'));
