<?php

namespace PGS\CoreDomainBundle\Model\FormulaDetail\om;

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
use PGS\CoreDomainBundle\Model\Formula\FormulaPeer;
use PGS\CoreDomainBundle\Model\FormulaDetail\FormulaDetail;
use PGS\CoreDomainBundle\Model\FormulaDetail\FormulaDetailPeer;
use PGS\CoreDomainBundle\Model\FormulaDetail\map\FormulaDetailTableMap;

abstract class BaseFormulaDetailPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'formula_detail';

    /** the related Propel class for this table */
    const OM_CLASS = 'PGS\\CoreDomainBundle\\Model\\FormulaDetail\\FormulaDetail';

    /** the related TableMap class for this table */
    const TM_CLASS = 'PGS\\CoreDomainBundle\\Model\\FormulaDetail\\map\\FormulaDetailTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 8;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 8;

    /** the column name for the id field */
    const ID = 'formula_detail.id';

    /** the column name for the formula_id field */
    const FORMULA_ID = 'formula_detail.formula_id';

    /** the column name for the final_exam_point field */
    const FINAL_EXAM_POINT = 'formula_detail.final_exam_point';

    /** the column name for the daily_exam_point field */
    const DAILY_EXAM_POINT = 'formula_detail.daily_exam_point';

    /** the column name for the mid_exam_point field */
    const MID_EXAM_POINT = 'formula_detail.mid_exam_point';

    /** the column name for the activity_point field */
    const ACTIVITY_POINT = 'formula_detail.activity_point';

    /** the column name for the created_at field */
    const CREATED_AT = 'formula_detail.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'formula_detail.updated_at';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of FormulaDetail objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array FormulaDetail[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. FormulaDetailPeer::$fieldNames[FormulaDetailPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'FormulaId', 'FinalExamPoint', 'DailyExamPoint', 'MidExamPoint', 'ActivityPoint', 'CreatedAt', 'UpdatedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'formulaId', 'finalExamPoint', 'dailyExamPoint', 'midExamPoint', 'activityPoint', 'createdAt', 'updatedAt', ),
        BasePeer::TYPE_COLNAME => array (FormulaDetailPeer::ID, FormulaDetailPeer::FORMULA_ID, FormulaDetailPeer::FINAL_EXAM_POINT, FormulaDetailPeer::DAILY_EXAM_POINT, FormulaDetailPeer::MID_EXAM_POINT, FormulaDetailPeer::ACTIVITY_POINT, FormulaDetailPeer::CREATED_AT, FormulaDetailPeer::UPDATED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'FORMULA_ID', 'FINAL_EXAM_POINT', 'DAILY_EXAM_POINT', 'MID_EXAM_POINT', 'ACTIVITY_POINT', 'CREATED_AT', 'UPDATED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'formula_id', 'final_exam_point', 'daily_exam_point', 'mid_exam_point', 'activity_point', 'created_at', 'updated_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. FormulaDetailPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'FormulaId' => 1, 'FinalExamPoint' => 2, 'DailyExamPoint' => 3, 'MidExamPoint' => 4, 'ActivityPoint' => 5, 'CreatedAt' => 6, 'UpdatedAt' => 7, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'formulaId' => 1, 'finalExamPoint' => 2, 'dailyExamPoint' => 3, 'midExamPoint' => 4, 'activityPoint' => 5, 'createdAt' => 6, 'updatedAt' => 7, ),
        BasePeer::TYPE_COLNAME => array (FormulaDetailPeer::ID => 0, FormulaDetailPeer::FORMULA_ID => 1, FormulaDetailPeer::FINAL_EXAM_POINT => 2, FormulaDetailPeer::DAILY_EXAM_POINT => 3, FormulaDetailPeer::MID_EXAM_POINT => 4, FormulaDetailPeer::ACTIVITY_POINT => 5, FormulaDetailPeer::CREATED_AT => 6, FormulaDetailPeer::UPDATED_AT => 7, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'FORMULA_ID' => 1, 'FINAL_EXAM_POINT' => 2, 'DAILY_EXAM_POINT' => 3, 'MID_EXAM_POINT' => 4, 'ACTIVITY_POINT' => 5, 'CREATED_AT' => 6, 'UPDATED_AT' => 7, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'formula_id' => 1, 'final_exam_point' => 2, 'daily_exam_point' => 3, 'mid_exam_point' => 4, 'activity_point' => 5, 'created_at' => 6, 'updated_at' => 7, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, )
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
        $toNames = FormulaDetailPeer::getFieldNames($toType);
        $key = isset(FormulaDetailPeer::$fieldKeys[$fromType][$name]) ? FormulaDetailPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(FormulaDetailPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, FormulaDetailPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return FormulaDetailPeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. FormulaDetailPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(FormulaDetailPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(FormulaDetailPeer::ID);
            $criteria->addSelectColumn(FormulaDetailPeer::FORMULA_ID);
            $criteria->addSelectColumn(FormulaDetailPeer::FINAL_EXAM_POINT);
            $criteria->addSelectColumn(FormulaDetailPeer::DAILY_EXAM_POINT);
            $criteria->addSelectColumn(FormulaDetailPeer::MID_EXAM_POINT);
            $criteria->addSelectColumn(FormulaDetailPeer::ACTIVITY_POINT);
            $criteria->addSelectColumn(FormulaDetailPeer::CREATED_AT);
            $criteria->addSelectColumn(FormulaDetailPeer::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.formula_id');
            $criteria->addSelectColumn($alias . '.final_exam_point');
            $criteria->addSelectColumn($alias . '.daily_exam_point');
            $criteria->addSelectColumn($alias . '.mid_exam_point');
            $criteria->addSelectColumn($alias . '.activity_point');
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
        $criteria->setPrimaryTableName(FormulaDetailPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            FormulaDetailPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(FormulaDetailPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(FormulaDetailPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return FormulaDetail
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = FormulaDetailPeer::doSelect($critcopy, $con);
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
        return FormulaDetailPeer::populateObjects(FormulaDetailPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(FormulaDetailPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            FormulaDetailPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(FormulaDetailPeer::DATABASE_NAME);

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
     * @param FormulaDetail $obj A FormulaDetail object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            FormulaDetailPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A FormulaDetail object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof FormulaDetail) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or FormulaDetail object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(FormulaDetailPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return FormulaDetail Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(FormulaDetailPeer::$instances[$key])) {
                return FormulaDetailPeer::$instances[$key];
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
        foreach (FormulaDetailPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        FormulaDetailPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to formula_detail
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in FormulaPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        FormulaPeer::clearInstancePool();
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
        $cls = FormulaDetailPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = FormulaDetailPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = FormulaDetailPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                FormulaDetailPeer::addInstanceToPool($obj, $key);
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
     * @return array (FormulaDetail object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = FormulaDetailPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = FormulaDetailPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + FormulaDetailPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = FormulaDetailPeer::getOMClass($row, $startcol);
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            FormulaDetailPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related FormulaRelatedByFormulaId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinFormulaRelatedByFormulaId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(FormulaDetailPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            FormulaDetailPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(FormulaDetailPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(FormulaDetailPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(FormulaDetailPeer::FORMULA_ID, FormulaPeer::ID, $join_behavior);

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
     * Selects a collection of FormulaDetail objects pre-filled with their Formula objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of FormulaDetail objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinFormulaRelatedByFormulaId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(FormulaDetailPeer::DATABASE_NAME);
        }

        FormulaDetailPeer::addSelectColumns($criteria);
        $startcol = FormulaDetailPeer::NUM_HYDRATE_COLUMNS;
        FormulaPeer::addSelectColumns($criteria);

        $criteria->addJoin(FormulaDetailPeer::FORMULA_ID, FormulaPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = FormulaDetailPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = FormulaDetailPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = FormulaDetailPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                FormulaDetailPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (FormulaDetail) to $obj2 (Formula)
                $obj2->addFormulaDetailRelatedByFormulaId($obj1);

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
        $criteria->setPrimaryTableName(FormulaDetailPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            FormulaDetailPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(FormulaDetailPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(FormulaDetailPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(FormulaDetailPeer::FORMULA_ID, FormulaPeer::ID, $join_behavior);

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
     * Selects a collection of FormulaDetail objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of FormulaDetail objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(FormulaDetailPeer::DATABASE_NAME);
        }

        FormulaDetailPeer::addSelectColumns($criteria);
        $startcol2 = FormulaDetailPeer::NUM_HYDRATE_COLUMNS;

        FormulaPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + FormulaPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(FormulaDetailPeer::FORMULA_ID, FormulaPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = FormulaDetailPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = FormulaDetailPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = FormulaDetailPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                FormulaDetailPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined Formula rows

            $key2 = FormulaPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = FormulaPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = FormulaPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    FormulaPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (FormulaDetail) to the collection in $obj2 (Formula)
                $obj2->addFormulaDetailRelatedByFormulaId($obj1);
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
        return Propel::getDatabaseMap(FormulaDetailPeer::DATABASE_NAME)->getTable(FormulaDetailPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseFormulaDetailPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseFormulaDetailPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \PGS\CoreDomainBundle\Model\FormulaDetail\map\FormulaDetailTableMap());
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

        $event = new DetectOMClassEvent(FormulaDetailPeer::OM_CLASS, $row, $colnum);
        EventDispatcherProxy::trigger('om.detect', $event);
        if($event->isDetected()){
            return $event->getDetectedClass();
        }

        return FormulaDetailPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a FormulaDetail or Criteria object.
     *
     * @param      mixed $values Criteria or FormulaDetail object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(FormulaDetailPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from FormulaDetail object
        }

        if ($criteria->containsKey(FormulaDetailPeer::ID) && $criteria->keyContainsValue(FormulaDetailPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.FormulaDetailPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(FormulaDetailPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a FormulaDetail or Criteria object.
     *
     * @param      mixed $values Criteria or FormulaDetail object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(FormulaDetailPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(FormulaDetailPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(FormulaDetailPeer::ID);
            $value = $criteria->remove(FormulaDetailPeer::ID);
            if ($value) {
                $selectCriteria->add(FormulaDetailPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(FormulaDetailPeer::TABLE_NAME);
            }

        } else { // $values is FormulaDetail object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(FormulaDetailPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the formula_detail table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(FormulaDetailPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(FormulaDetailPeer::TABLE_NAME, $con, FormulaDetailPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            FormulaDetailPeer::clearInstancePool();
            FormulaDetailPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a FormulaDetail or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or FormulaDetail object or primary key or array of primary keys
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
            $con = Propel::getConnection(FormulaDetailPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            FormulaDetailPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof FormulaDetail) { // it's a model object
            // invalidate the cache for this single object
            FormulaDetailPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(FormulaDetailPeer::DATABASE_NAME);
            $criteria->add(FormulaDetailPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                FormulaDetailPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(FormulaDetailPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            FormulaDetailPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given FormulaDetail object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param FormulaDetail $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(FormulaDetailPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(FormulaDetailPeer::TABLE_NAME);

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

        return BasePeer::doValidate(FormulaDetailPeer::DATABASE_NAME, FormulaDetailPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return FormulaDetail
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = FormulaDetailPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(FormulaDetailPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(FormulaDetailPeer::DATABASE_NAME);
        $criteria->add(FormulaDetailPeer::ID, $pk);

        $v = FormulaDetailPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return FormulaDetail[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(FormulaDetailPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(FormulaDetailPeer::DATABASE_NAME);
            $criteria->add(FormulaDetailPeer::ID, $pks, Criteria::IN);
            $objs = FormulaDetailPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseFormulaDetailPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseFormulaDetailPeer::buildTableMap();

EventDispatcherProxy::trigger(array('construct','peer.construct'), new PeerEvent('PGS\CoreDomainBundle\Model\FormulaDetail\om\BaseFormulaDetailPeer'));