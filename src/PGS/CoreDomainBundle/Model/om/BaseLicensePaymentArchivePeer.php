<?php

namespace PGS\CoreDomainBundle\Model\om;

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
use PGS\CoreDomainBundle\Model\LicensePaymentArchive;
use PGS\CoreDomainBundle\Model\LicensePaymentArchivePeer;
use PGS\CoreDomainBundle\Model\map\LicensePaymentArchiveTableMap;

abstract class BaseLicensePaymentArchivePeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'license_payment_archive';

    /** the related Propel class for this table */
    const OM_CLASS = 'PGS\\CoreDomainBundle\\Model\\LicensePaymentArchive';

    /** the related TableMap class for this table */
    const TM_CLASS = 'PGS\\CoreDomainBundle\\Model\\map\\LicensePaymentArchiveTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 15;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 15;

    /** the column name for the id field */
    const ID = 'license_payment_archive.id';

    /** the column name for the user_id field */
    const USER_ID = 'license_payment_archive.user_id';

    /** the column name for the license_id field */
    const LICENSE_ID = 'license_payment_archive.license_id';

    /** the column name for the payment_date field */
    const PAYMENT_DATE = 'license_payment_archive.payment_date';

    /** the column name for the quantity field */
    const QUANTITY = 'license_payment_archive.quantity';

    /** the column name for the price field */
    const PRICE = 'license_payment_archive.price';

    /** the column name for the subtotal field */
    const SUBTOTAL = 'license_payment_archive.subtotal';

    /** the column name for the discount field */
    const DISCOUNT = 'license_payment_archive.discount';

    /** the column name for the tax field */
    const TAX = 'license_payment_archive.tax';

    /** the column name for the total field */
    const TOTAL = 'license_payment_archive.total';

    /** the column name for the transaction_code field */
    const TRANSACTION_CODE = 'license_payment_archive.transaction_code';

    /** the column name for the status field */
    const STATUS = 'license_payment_archive.status';

    /** the column name for the created_at field */
    const CREATED_AT = 'license_payment_archive.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'license_payment_archive.updated_at';

    /** the column name for the archived_at field */
    const ARCHIVED_AT = 'license_payment_archive.archived_at';

    /** The enumerated values for the status field */
    const STATUS_NEW = 'new';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_EXPIRED = 'expired';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of LicensePaymentArchive objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array LicensePaymentArchive[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. LicensePaymentArchivePeer::$fieldNames[LicensePaymentArchivePeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'UserId', 'LicenseId', 'PaymentDate', 'Quantity', 'Price', 'Subtotal', 'Discount', 'Tax', 'Total', 'TransactionCode', 'Status', 'CreatedAt', 'UpdatedAt', 'ArchivedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'userId', 'licenseId', 'paymentDate', 'quantity', 'price', 'subtotal', 'discount', 'tax', 'total', 'transactionCode', 'status', 'createdAt', 'updatedAt', 'archivedAt', ),
        BasePeer::TYPE_COLNAME => array (LicensePaymentArchivePeer::ID, LicensePaymentArchivePeer::USER_ID, LicensePaymentArchivePeer::LICENSE_ID, LicensePaymentArchivePeer::PAYMENT_DATE, LicensePaymentArchivePeer::QUANTITY, LicensePaymentArchivePeer::PRICE, LicensePaymentArchivePeer::SUBTOTAL, LicensePaymentArchivePeer::DISCOUNT, LicensePaymentArchivePeer::TAX, LicensePaymentArchivePeer::TOTAL, LicensePaymentArchivePeer::TRANSACTION_CODE, LicensePaymentArchivePeer::STATUS, LicensePaymentArchivePeer::CREATED_AT, LicensePaymentArchivePeer::UPDATED_AT, LicensePaymentArchivePeer::ARCHIVED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'USER_ID', 'LICENSE_ID', 'PAYMENT_DATE', 'QUANTITY', 'PRICE', 'SUBTOTAL', 'DISCOUNT', 'TAX', 'TOTAL', 'TRANSACTION_CODE', 'STATUS', 'CREATED_AT', 'UPDATED_AT', 'ARCHIVED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'user_id', 'license_id', 'payment_date', 'quantity', 'price', 'subtotal', 'discount', 'tax', 'total', 'transaction_code', 'status', 'created_at', 'updated_at', 'archived_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. LicensePaymentArchivePeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'UserId' => 1, 'LicenseId' => 2, 'PaymentDate' => 3, 'Quantity' => 4, 'Price' => 5, 'Subtotal' => 6, 'Discount' => 7, 'Tax' => 8, 'Total' => 9, 'TransactionCode' => 10, 'Status' => 11, 'CreatedAt' => 12, 'UpdatedAt' => 13, 'ArchivedAt' => 14, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'userId' => 1, 'licenseId' => 2, 'paymentDate' => 3, 'quantity' => 4, 'price' => 5, 'subtotal' => 6, 'discount' => 7, 'tax' => 8, 'total' => 9, 'transactionCode' => 10, 'status' => 11, 'createdAt' => 12, 'updatedAt' => 13, 'archivedAt' => 14, ),
        BasePeer::TYPE_COLNAME => array (LicensePaymentArchivePeer::ID => 0, LicensePaymentArchivePeer::USER_ID => 1, LicensePaymentArchivePeer::LICENSE_ID => 2, LicensePaymentArchivePeer::PAYMENT_DATE => 3, LicensePaymentArchivePeer::QUANTITY => 4, LicensePaymentArchivePeer::PRICE => 5, LicensePaymentArchivePeer::SUBTOTAL => 6, LicensePaymentArchivePeer::DISCOUNT => 7, LicensePaymentArchivePeer::TAX => 8, LicensePaymentArchivePeer::TOTAL => 9, LicensePaymentArchivePeer::TRANSACTION_CODE => 10, LicensePaymentArchivePeer::STATUS => 11, LicensePaymentArchivePeer::CREATED_AT => 12, LicensePaymentArchivePeer::UPDATED_AT => 13, LicensePaymentArchivePeer::ARCHIVED_AT => 14, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'USER_ID' => 1, 'LICENSE_ID' => 2, 'PAYMENT_DATE' => 3, 'QUANTITY' => 4, 'PRICE' => 5, 'SUBTOTAL' => 6, 'DISCOUNT' => 7, 'TAX' => 8, 'TOTAL' => 9, 'TRANSACTION_CODE' => 10, 'STATUS' => 11, 'CREATED_AT' => 12, 'UPDATED_AT' => 13, 'ARCHIVED_AT' => 14, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'user_id' => 1, 'license_id' => 2, 'payment_date' => 3, 'quantity' => 4, 'price' => 5, 'subtotal' => 6, 'discount' => 7, 'tax' => 8, 'total' => 9, 'transaction_code' => 10, 'status' => 11, 'created_at' => 12, 'updated_at' => 13, 'archived_at' => 14, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
        LicensePaymentArchivePeer::STATUS => array(
            LicensePaymentArchivePeer::STATUS_NEW,
            LicensePaymentArchivePeer::STATUS_ACTIVE,
            LicensePaymentArchivePeer::STATUS_INACTIVE,
            LicensePaymentArchivePeer::STATUS_EXPIRED,
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
        $toNames = LicensePaymentArchivePeer::getFieldNames($toType);
        $key = isset(LicensePaymentArchivePeer::$fieldKeys[$fromType][$name]) ? LicensePaymentArchivePeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(LicensePaymentArchivePeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, LicensePaymentArchivePeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return LicensePaymentArchivePeer::$fieldNames[$type];
    }

    /**
     * Gets the list of values for all ENUM columns
     * @return array
     */
    public static function getValueSets()
    {
      return LicensePaymentArchivePeer::$enumValueSets;
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
        $valueSets = LicensePaymentArchivePeer::getValueSets();

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
        $values = LicensePaymentArchivePeer::getValueSet($colname);
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
     * @param      string $column The column name for current table. (i.e. LicensePaymentArchivePeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(LicensePaymentArchivePeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(LicensePaymentArchivePeer::ID);
            $criteria->addSelectColumn(LicensePaymentArchivePeer::USER_ID);
            $criteria->addSelectColumn(LicensePaymentArchivePeer::LICENSE_ID);
            $criteria->addSelectColumn(LicensePaymentArchivePeer::PAYMENT_DATE);
            $criteria->addSelectColumn(LicensePaymentArchivePeer::QUANTITY);
            $criteria->addSelectColumn(LicensePaymentArchivePeer::PRICE);
            $criteria->addSelectColumn(LicensePaymentArchivePeer::SUBTOTAL);
            $criteria->addSelectColumn(LicensePaymentArchivePeer::DISCOUNT);
            $criteria->addSelectColumn(LicensePaymentArchivePeer::TAX);
            $criteria->addSelectColumn(LicensePaymentArchivePeer::TOTAL);
            $criteria->addSelectColumn(LicensePaymentArchivePeer::TRANSACTION_CODE);
            $criteria->addSelectColumn(LicensePaymentArchivePeer::STATUS);
            $criteria->addSelectColumn(LicensePaymentArchivePeer::CREATED_AT);
            $criteria->addSelectColumn(LicensePaymentArchivePeer::UPDATED_AT);
            $criteria->addSelectColumn(LicensePaymentArchivePeer::ARCHIVED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.license_id');
            $criteria->addSelectColumn($alias . '.payment_date');
            $criteria->addSelectColumn($alias . '.quantity');
            $criteria->addSelectColumn($alias . '.price');
            $criteria->addSelectColumn($alias . '.subtotal');
            $criteria->addSelectColumn($alias . '.discount');
            $criteria->addSelectColumn($alias . '.tax');
            $criteria->addSelectColumn($alias . '.total');
            $criteria->addSelectColumn($alias . '.transaction_code');
            $criteria->addSelectColumn($alias . '.status');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
            $criteria->addSelectColumn($alias . '.archived_at');
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
        $criteria->setPrimaryTableName(LicensePaymentArchivePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LicensePaymentArchivePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(LicensePaymentArchivePeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(LicensePaymentArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return LicensePaymentArchive
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = LicensePaymentArchivePeer::doSelect($critcopy, $con);
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
        return LicensePaymentArchivePeer::populateObjects(LicensePaymentArchivePeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(LicensePaymentArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            LicensePaymentArchivePeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(LicensePaymentArchivePeer::DATABASE_NAME);

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
     * @param LicensePaymentArchive $obj A LicensePaymentArchive object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            LicensePaymentArchivePeer::$instances[$key] = $obj;
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
     * @param      mixed $value A LicensePaymentArchive object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof LicensePaymentArchive) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or LicensePaymentArchive object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(LicensePaymentArchivePeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return LicensePaymentArchive Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(LicensePaymentArchivePeer::$instances[$key])) {
                return LicensePaymentArchivePeer::$instances[$key];
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
        foreach (LicensePaymentArchivePeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        LicensePaymentArchivePeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to license_payment_archive
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
        $cls = LicensePaymentArchivePeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = LicensePaymentArchivePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = LicensePaymentArchivePeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                LicensePaymentArchivePeer::addInstanceToPool($obj, $key);
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
     * @return array (LicensePaymentArchive object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = LicensePaymentArchivePeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = LicensePaymentArchivePeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + LicensePaymentArchivePeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = LicensePaymentArchivePeer::getOMClass($row, $startcol);
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            LicensePaymentArchivePeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * Gets the SQL value for Status ENUM value
     *
     * @param  string $enumVal ENUM value to get SQL value for
     * @return int SQL value
     */
    public static function getStatusSqlValue($enumVal)
    {
        return LicensePaymentArchivePeer::getSqlValueForEnum(LicensePaymentArchivePeer::STATUS, $enumVal);
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
        return Propel::getDatabaseMap(LicensePaymentArchivePeer::DATABASE_NAME)->getTable(LicensePaymentArchivePeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseLicensePaymentArchivePeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseLicensePaymentArchivePeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \PGS\CoreDomainBundle\Model\map\LicensePaymentArchiveTableMap());
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

        $event = new DetectOMClassEvent(LicensePaymentArchivePeer::OM_CLASS, $row, $colnum);
        EventDispatcherProxy::trigger('om.detect', $event);
        if($event->isDetected()){
            return $event->getDetectedClass();
        }

        return LicensePaymentArchivePeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a LicensePaymentArchive or Criteria object.
     *
     * @param      mixed $values Criteria or LicensePaymentArchive object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LicensePaymentArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from LicensePaymentArchive object
        }


        // Set the correct dbName
        $criteria->setDbName(LicensePaymentArchivePeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a LicensePaymentArchive or Criteria object.
     *
     * @param      mixed $values Criteria or LicensePaymentArchive object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LicensePaymentArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(LicensePaymentArchivePeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(LicensePaymentArchivePeer::ID);
            $value = $criteria->remove(LicensePaymentArchivePeer::ID);
            if ($value) {
                $selectCriteria->add(LicensePaymentArchivePeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(LicensePaymentArchivePeer::TABLE_NAME);
            }

        } else { // $values is LicensePaymentArchive object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(LicensePaymentArchivePeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the license_payment_archive table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LicensePaymentArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(LicensePaymentArchivePeer::TABLE_NAME, $con, LicensePaymentArchivePeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LicensePaymentArchivePeer::clearInstancePool();
            LicensePaymentArchivePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a LicensePaymentArchive or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or LicensePaymentArchive object or primary key or array of primary keys
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
            $con = Propel::getConnection(LicensePaymentArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            LicensePaymentArchivePeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof LicensePaymentArchive) { // it's a model object
            // invalidate the cache for this single object
            LicensePaymentArchivePeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(LicensePaymentArchivePeer::DATABASE_NAME);
            $criteria->add(LicensePaymentArchivePeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                LicensePaymentArchivePeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(LicensePaymentArchivePeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            LicensePaymentArchivePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given LicensePaymentArchive object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param LicensePaymentArchive $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(LicensePaymentArchivePeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(LicensePaymentArchivePeer::TABLE_NAME);

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

        return BasePeer::doValidate(LicensePaymentArchivePeer::DATABASE_NAME, LicensePaymentArchivePeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return LicensePaymentArchive
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = LicensePaymentArchivePeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(LicensePaymentArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(LicensePaymentArchivePeer::DATABASE_NAME);
        $criteria->add(LicensePaymentArchivePeer::ID, $pk);

        $v = LicensePaymentArchivePeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return LicensePaymentArchive[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LicensePaymentArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(LicensePaymentArchivePeer::DATABASE_NAME);
            $criteria->add(LicensePaymentArchivePeer::ID, $pks, Criteria::IN);
            $objs = LicensePaymentArchivePeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseLicensePaymentArchivePeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseLicensePaymentArchivePeer::buildTableMap();

EventDispatcherProxy::trigger(array('construct','peer.construct'), new PeerEvent('PGS\CoreDomainBundle\Model\om\BaseLicensePaymentArchivePeer'));
