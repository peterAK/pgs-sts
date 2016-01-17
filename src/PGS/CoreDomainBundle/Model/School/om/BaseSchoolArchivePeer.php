<?php

namespace PGS\CoreDomainBundle\Model\School\om;

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
use PGS\CoreDomainBundle\Model\School\SchoolArchive;
use PGS\CoreDomainBundle\Model\School\SchoolArchivePeer;
use PGS\CoreDomainBundle\Model\School\map\SchoolArchiveTableMap;

abstract class BaseSchoolArchivePeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'school_archive';

    /** the related Propel class for this table */
    const OM_CLASS = 'PGS\\CoreDomainBundle\\Model\\School\\SchoolArchive';

    /** the related TableMap class for this table */
    const TM_CLASS = 'PGS\\CoreDomainBundle\\Model\\School\\map\\SchoolArchiveTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 25;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 25;

    /** the column name for the id field */
    const ID = 'school_archive.id';

    /** the column name for the code field */
    const CODE = 'school_archive.code';

    /** the column name for the name field */
    const NAME = 'school_archive.name';

    /** the column name for the level_id field */
    const LEVEL_ID = 'school_archive.level_id';

    /** the column name for the nick_name field */
    const NICK_NAME = 'school_archive.nick_name';

    /** the column name for the address field */
    const ADDRESS = 'school_archive.address';

    /** the column name for the city field */
    const CITY = 'school_archive.city';

    /** the column name for the state_id field */
    const STATE_ID = 'school_archive.state_id';

    /** the column name for the zip field */
    const ZIP = 'school_archive.zip';

    /** the column name for the country_id field */
    const COUNTRY_ID = 'school_archive.country_id';

    /** the column name for the organization_id field */
    const ORGANIZATION_ID = 'school_archive.organization_id';

    /** the column name for the phone field */
    const PHONE = 'school_archive.phone';

    /** the column name for the fax field */
    const FAX = 'school_archive.fax';

    /** the column name for the mobile field */
    const MOBILE = 'school_archive.mobile';

    /** the column name for the email field */
    const EMAIL = 'school_archive.email';

    /** the column name for the website field */
    const WEBSITE = 'school_archive.website';

    /** the column name for the logo field */
    const LOGO = 'school_archive.logo';

    /** the column name for the description field */
    const DESCRIPTION = 'school_archive.description';

    /** the column name for the excerpt field */
    const EXCERPT = 'school_archive.excerpt';

    /** the column name for the status field */
    const STATUS = 'school_archive.status';

    /** the column name for the confirmation field */
    const CONFIRMATION = 'school_archive.confirmation';

    /** the column name for the sortable_rank field */
    const SORTABLE_RANK = 'school_archive.sortable_rank';

    /** the column name for the archived_at field */
    const ARCHIVED_AT = 'school_archive.archived_at';

    /** the column name for the created_at field */
    const CREATED_AT = 'school_archive.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'school_archive.updated_at';

    /** The enumerated values for the status field */
    const STATUS_NEW = 'new';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_BANNED = 'banned';

    /** The enumerated values for the confirmation field */
    const CONFIRMATION_NEW = 'new';
    const CONFIRMATION_PHONE = 'phone';
    const CONFIRMATION_LETTERS = 'letters';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of SchoolArchive objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array SchoolArchive[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. SchoolArchivePeer::$fieldNames[SchoolArchivePeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Code', 'Name', 'LevelId', 'NickName', 'Address', 'City', 'StateId', 'Zip', 'CountryId', 'OrganizationId', 'Phone', 'Fax', 'Mobile', 'Email', 'Website', 'Logo', 'Description', 'Excerpt', 'Status', 'Confirmation', 'SortableRank', 'ArchivedAt', 'CreatedAt', 'UpdatedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'code', 'name', 'levelId', 'nickName', 'address', 'city', 'stateId', 'zip', 'countryId', 'organizationId', 'phone', 'fax', 'mobile', 'email', 'website', 'logo', 'description', 'excerpt', 'status', 'confirmation', 'sortableRank', 'archivedAt', 'createdAt', 'updatedAt', ),
        BasePeer::TYPE_COLNAME => array (SchoolArchivePeer::ID, SchoolArchivePeer::CODE, SchoolArchivePeer::NAME, SchoolArchivePeer::LEVEL_ID, SchoolArchivePeer::NICK_NAME, SchoolArchivePeer::ADDRESS, SchoolArchivePeer::CITY, SchoolArchivePeer::STATE_ID, SchoolArchivePeer::ZIP, SchoolArchivePeer::COUNTRY_ID, SchoolArchivePeer::ORGANIZATION_ID, SchoolArchivePeer::PHONE, SchoolArchivePeer::FAX, SchoolArchivePeer::MOBILE, SchoolArchivePeer::EMAIL, SchoolArchivePeer::WEBSITE, SchoolArchivePeer::LOGO, SchoolArchivePeer::DESCRIPTION, SchoolArchivePeer::EXCERPT, SchoolArchivePeer::STATUS, SchoolArchivePeer::CONFIRMATION, SchoolArchivePeer::SORTABLE_RANK, SchoolArchivePeer::ARCHIVED_AT, SchoolArchivePeer::CREATED_AT, SchoolArchivePeer::UPDATED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'CODE', 'NAME', 'LEVEL_ID', 'NICK_NAME', 'ADDRESS', 'CITY', 'STATE_ID', 'ZIP', 'COUNTRY_ID', 'ORGANIZATION_ID', 'PHONE', 'FAX', 'MOBILE', 'EMAIL', 'WEBSITE', 'LOGO', 'DESCRIPTION', 'EXCERPT', 'STATUS', 'CONFIRMATION', 'SORTABLE_RANK', 'ARCHIVED_AT', 'CREATED_AT', 'UPDATED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'code', 'name', 'level_id', 'nick_name', 'address', 'city', 'state_id', 'zip', 'country_id', 'organization_id', 'phone', 'fax', 'mobile', 'email', 'website', 'logo', 'description', 'excerpt', 'status', 'confirmation', 'sortable_rank', 'archived_at', 'created_at', 'updated_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. SchoolArchivePeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Code' => 1, 'Name' => 2, 'LevelId' => 3, 'NickName' => 4, 'Address' => 5, 'City' => 6, 'StateId' => 7, 'Zip' => 8, 'CountryId' => 9, 'OrganizationId' => 10, 'Phone' => 11, 'Fax' => 12, 'Mobile' => 13, 'Email' => 14, 'Website' => 15, 'Logo' => 16, 'Description' => 17, 'Excerpt' => 18, 'Status' => 19, 'Confirmation' => 20, 'SortableRank' => 21, 'ArchivedAt' => 22, 'CreatedAt' => 23, 'UpdatedAt' => 24, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'code' => 1, 'name' => 2, 'levelId' => 3, 'nickName' => 4, 'address' => 5, 'city' => 6, 'stateId' => 7, 'zip' => 8, 'countryId' => 9, 'organizationId' => 10, 'phone' => 11, 'fax' => 12, 'mobile' => 13, 'email' => 14, 'website' => 15, 'logo' => 16, 'description' => 17, 'excerpt' => 18, 'status' => 19, 'confirmation' => 20, 'sortableRank' => 21, 'archivedAt' => 22, 'createdAt' => 23, 'updatedAt' => 24, ),
        BasePeer::TYPE_COLNAME => array (SchoolArchivePeer::ID => 0, SchoolArchivePeer::CODE => 1, SchoolArchivePeer::NAME => 2, SchoolArchivePeer::LEVEL_ID => 3, SchoolArchivePeer::NICK_NAME => 4, SchoolArchivePeer::ADDRESS => 5, SchoolArchivePeer::CITY => 6, SchoolArchivePeer::STATE_ID => 7, SchoolArchivePeer::ZIP => 8, SchoolArchivePeer::COUNTRY_ID => 9, SchoolArchivePeer::ORGANIZATION_ID => 10, SchoolArchivePeer::PHONE => 11, SchoolArchivePeer::FAX => 12, SchoolArchivePeer::MOBILE => 13, SchoolArchivePeer::EMAIL => 14, SchoolArchivePeer::WEBSITE => 15, SchoolArchivePeer::LOGO => 16, SchoolArchivePeer::DESCRIPTION => 17, SchoolArchivePeer::EXCERPT => 18, SchoolArchivePeer::STATUS => 19, SchoolArchivePeer::CONFIRMATION => 20, SchoolArchivePeer::SORTABLE_RANK => 21, SchoolArchivePeer::ARCHIVED_AT => 22, SchoolArchivePeer::CREATED_AT => 23, SchoolArchivePeer::UPDATED_AT => 24, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'CODE' => 1, 'NAME' => 2, 'LEVEL_ID' => 3, 'NICK_NAME' => 4, 'ADDRESS' => 5, 'CITY' => 6, 'STATE_ID' => 7, 'ZIP' => 8, 'COUNTRY_ID' => 9, 'ORGANIZATION_ID' => 10, 'PHONE' => 11, 'FAX' => 12, 'MOBILE' => 13, 'EMAIL' => 14, 'WEBSITE' => 15, 'LOGO' => 16, 'DESCRIPTION' => 17, 'EXCERPT' => 18, 'STATUS' => 19, 'CONFIRMATION' => 20, 'SORTABLE_RANK' => 21, 'ARCHIVED_AT' => 22, 'CREATED_AT' => 23, 'UPDATED_AT' => 24, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'code' => 1, 'name' => 2, 'level_id' => 3, 'nick_name' => 4, 'address' => 5, 'city' => 6, 'state_id' => 7, 'zip' => 8, 'country_id' => 9, 'organization_id' => 10, 'phone' => 11, 'fax' => 12, 'mobile' => 13, 'email' => 14, 'website' => 15, 'logo' => 16, 'description' => 17, 'excerpt' => 18, 'status' => 19, 'confirmation' => 20, 'sortable_rank' => 21, 'archived_at' => 22, 'created_at' => 23, 'updated_at' => 24, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
        SchoolArchivePeer::STATUS => array(
            SchoolArchivePeer::STATUS_NEW,
            SchoolArchivePeer::STATUS_ACTIVE,
            SchoolArchivePeer::STATUS_INACTIVE,
            SchoolArchivePeer::STATUS_BANNED,
        ),
        SchoolArchivePeer::CONFIRMATION => array(
            SchoolArchivePeer::CONFIRMATION_NEW,
            SchoolArchivePeer::CONFIRMATION_PHONE,
            SchoolArchivePeer::CONFIRMATION_LETTERS,
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
        $toNames = SchoolArchivePeer::getFieldNames($toType);
        $key = isset(SchoolArchivePeer::$fieldKeys[$fromType][$name]) ? SchoolArchivePeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(SchoolArchivePeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, SchoolArchivePeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return SchoolArchivePeer::$fieldNames[$type];
    }

    /**
     * Gets the list of values for all ENUM columns
     * @return array
     */
    public static function getValueSets()
    {
      return SchoolArchivePeer::$enumValueSets;
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
        $valueSets = SchoolArchivePeer::getValueSets();

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
        $values = SchoolArchivePeer::getValueSet($colname);
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
     * @param      string $column The column name for current table. (i.e. SchoolArchivePeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(SchoolArchivePeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(SchoolArchivePeer::ID);
            $criteria->addSelectColumn(SchoolArchivePeer::CODE);
            $criteria->addSelectColumn(SchoolArchivePeer::NAME);
            $criteria->addSelectColumn(SchoolArchivePeer::LEVEL_ID);
            $criteria->addSelectColumn(SchoolArchivePeer::NICK_NAME);
            $criteria->addSelectColumn(SchoolArchivePeer::ADDRESS);
            $criteria->addSelectColumn(SchoolArchivePeer::CITY);
            $criteria->addSelectColumn(SchoolArchivePeer::STATE_ID);
            $criteria->addSelectColumn(SchoolArchivePeer::ZIP);
            $criteria->addSelectColumn(SchoolArchivePeer::COUNTRY_ID);
            $criteria->addSelectColumn(SchoolArchivePeer::ORGANIZATION_ID);
            $criteria->addSelectColumn(SchoolArchivePeer::PHONE);
            $criteria->addSelectColumn(SchoolArchivePeer::FAX);
            $criteria->addSelectColumn(SchoolArchivePeer::MOBILE);
            $criteria->addSelectColumn(SchoolArchivePeer::EMAIL);
            $criteria->addSelectColumn(SchoolArchivePeer::WEBSITE);
            $criteria->addSelectColumn(SchoolArchivePeer::LOGO);
            $criteria->addSelectColumn(SchoolArchivePeer::DESCRIPTION);
            $criteria->addSelectColumn(SchoolArchivePeer::EXCERPT);
            $criteria->addSelectColumn(SchoolArchivePeer::STATUS);
            $criteria->addSelectColumn(SchoolArchivePeer::CONFIRMATION);
            $criteria->addSelectColumn(SchoolArchivePeer::SORTABLE_RANK);
            $criteria->addSelectColumn(SchoolArchivePeer::ARCHIVED_AT);
            $criteria->addSelectColumn(SchoolArchivePeer::CREATED_AT);
            $criteria->addSelectColumn(SchoolArchivePeer::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.code');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.level_id');
            $criteria->addSelectColumn($alias . '.nick_name');
            $criteria->addSelectColumn($alias . '.address');
            $criteria->addSelectColumn($alias . '.city');
            $criteria->addSelectColumn($alias . '.state_id');
            $criteria->addSelectColumn($alias . '.zip');
            $criteria->addSelectColumn($alias . '.country_id');
            $criteria->addSelectColumn($alias . '.organization_id');
            $criteria->addSelectColumn($alias . '.phone');
            $criteria->addSelectColumn($alias . '.fax');
            $criteria->addSelectColumn($alias . '.mobile');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.website');
            $criteria->addSelectColumn($alias . '.logo');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.excerpt');
            $criteria->addSelectColumn($alias . '.status');
            $criteria->addSelectColumn($alias . '.confirmation');
            $criteria->addSelectColumn($alias . '.sortable_rank');
            $criteria->addSelectColumn($alias . '.archived_at');
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
        $criteria->setPrimaryTableName(SchoolArchivePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolArchivePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(SchoolArchivePeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(SchoolArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return SchoolArchive
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = SchoolArchivePeer::doSelect($critcopy, $con);
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
        return SchoolArchivePeer::populateObjects(SchoolArchivePeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(SchoolArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            SchoolArchivePeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(SchoolArchivePeer::DATABASE_NAME);

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
     * @param SchoolArchive $obj A SchoolArchive object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            SchoolArchivePeer::$instances[$key] = $obj;
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
     * @param      mixed $value A SchoolArchive object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof SchoolArchive) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or SchoolArchive object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(SchoolArchivePeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return SchoolArchive Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(SchoolArchivePeer::$instances[$key])) {
                return SchoolArchivePeer::$instances[$key];
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
        foreach (SchoolArchivePeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        SchoolArchivePeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to school_archive
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
        $cls = SchoolArchivePeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = SchoolArchivePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = SchoolArchivePeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SchoolArchivePeer::addInstanceToPool($obj, $key);
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
     * @return array (SchoolArchive object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = SchoolArchivePeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = SchoolArchivePeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + SchoolArchivePeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SchoolArchivePeer::getOMClass($row, $startcol);
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            SchoolArchivePeer::addInstanceToPool($obj, $key);
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
        return SchoolArchivePeer::getSqlValueForEnum(SchoolArchivePeer::STATUS, $enumVal);
    }

    /**
     * Gets the SQL value for Confirmation ENUM value
     *
     * @param  string $enumVal ENUM value to get SQL value for
     * @return int SQL value
     */
    public static function getConfirmationSqlValue($enumVal)
    {
        return SchoolArchivePeer::getSqlValueForEnum(SchoolArchivePeer::CONFIRMATION, $enumVal);
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
        return Propel::getDatabaseMap(SchoolArchivePeer::DATABASE_NAME)->getTable(SchoolArchivePeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseSchoolArchivePeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseSchoolArchivePeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \PGS\CoreDomainBundle\Model\School\map\SchoolArchiveTableMap());
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

        $event = new DetectOMClassEvent(SchoolArchivePeer::OM_CLASS, $row, $colnum);
        EventDispatcherProxy::trigger('om.detect', $event);
        if($event->isDetected()){
            return $event->getDetectedClass();
        }

        return SchoolArchivePeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a SchoolArchive or Criteria object.
     *
     * @param      mixed $values Criteria or SchoolArchive object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from SchoolArchive object
        }


        // Set the correct dbName
        $criteria->setDbName(SchoolArchivePeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a SchoolArchive or Criteria object.
     *
     * @param      mixed $values Criteria or SchoolArchive object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(SchoolArchivePeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(SchoolArchivePeer::ID);
            $value = $criteria->remove(SchoolArchivePeer::ID);
            if ($value) {
                $selectCriteria->add(SchoolArchivePeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(SchoolArchivePeer::TABLE_NAME);
            }

        } else { // $values is SchoolArchive object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(SchoolArchivePeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the school_archive table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(SchoolArchivePeer::TABLE_NAME, $con, SchoolArchivePeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SchoolArchivePeer::clearInstancePool();
            SchoolArchivePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a SchoolArchive or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or SchoolArchive object or primary key or array of primary keys
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
            $con = Propel::getConnection(SchoolArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            SchoolArchivePeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof SchoolArchive) { // it's a model object
            // invalidate the cache for this single object
            SchoolArchivePeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SchoolArchivePeer::DATABASE_NAME);
            $criteria->add(SchoolArchivePeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                SchoolArchivePeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(SchoolArchivePeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            SchoolArchivePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given SchoolArchive object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param SchoolArchive $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(SchoolArchivePeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(SchoolArchivePeer::TABLE_NAME);

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

        return BasePeer::doValidate(SchoolArchivePeer::DATABASE_NAME, SchoolArchivePeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return SchoolArchive
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = SchoolArchivePeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(SchoolArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(SchoolArchivePeer::DATABASE_NAME);
        $criteria->add(SchoolArchivePeer::ID, $pk);

        $v = SchoolArchivePeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return SchoolArchive[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(SchoolArchivePeer::DATABASE_NAME);
            $criteria->add(SchoolArchivePeer::ID, $pks, Criteria::IN);
            $objs = SchoolArchivePeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseSchoolArchivePeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseSchoolArchivePeer::buildTableMap();

EventDispatcherProxy::trigger(array('construct','peer.construct'), new PeerEvent('PGS\CoreDomainBundle\Model\School\om\BaseSchoolArchivePeer'));
