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
use PGS\CoreDomainBundle\Model\CountryPeer;
use PGS\CoreDomainBundle\Model\PagePeer;
use PGS\CoreDomainBundle\Model\StatePeer;
use PGS\CoreDomainBundle\Model\Application\ApplicationPeer;
use PGS\CoreDomainBundle\Model\Course\CoursePeer;
use PGS\CoreDomainBundle\Model\Employee\EmployeePeer;
use PGS\CoreDomainBundle\Model\Level\LevelPeer;
use PGS\CoreDomainBundle\Model\Organization\OrganizationPeer;
use PGS\CoreDomainBundle\Model\Qualification\QualificationPeer;
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Model\School\SchoolI18nPeer;
use PGS\CoreDomainBundle\Model\School\SchoolPeer;
use PGS\CoreDomainBundle\Model\School\SchoolQuery;
use PGS\CoreDomainBundle\Model\SchoolEmployment\SchoolEmploymentPeer;
use PGS\CoreDomainBundle\Model\SchoolEnrollment\SchoolEnrollmentPeer;
use PGS\CoreDomainBundle\Model\SchoolTest\SchoolTestPeer;
use PGS\CoreDomainBundle\Model\SchoolYear\SchoolYearPeer;
use PGS\CoreDomainBundle\Model\School\map\SchoolTableMap;

abstract class BaseSchoolPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'school';

    /** the related Propel class for this table */
    const OM_CLASS = 'PGS\\CoreDomainBundle\\Model\\School\\School';

    /** the related TableMap class for this table */
    const TM_CLASS = 'PGS\\CoreDomainBundle\\Model\\School\\map\\SchoolTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 22;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 22;

    /** the column name for the id field */
    const ID = 'school.id';

    /** the column name for the code field */
    const CODE = 'school.code';

    /** the column name for the name field */
    const NAME = 'school.name';

    /** the column name for the url field */
    const URL = 'school.url';

    /** the column name for the level_id field */
    const LEVEL_ID = 'school.level_id';

    /** the column name for the address field */
    const ADDRESS = 'school.address';

    /** the column name for the city field */
    const CITY = 'school.city';

    /** the column name for the state_id field */
    const STATE_ID = 'school.state_id';

    /** the column name for the zip field */
    const ZIP = 'school.zip';

    /** the column name for the country_id field */
    const COUNTRY_ID = 'school.country_id';

    /** the column name for the organization_id field */
    const ORGANIZATION_ID = 'school.organization_id';

    /** the column name for the phone field */
    const PHONE = 'school.phone';

    /** the column name for the fax field */
    const FAX = 'school.fax';

    /** the column name for the mobile field */
    const MOBILE = 'school.mobile';

    /** the column name for the email field */
    const EMAIL = 'school.email';

    /** the column name for the website field */
    const WEBSITE = 'school.website';

    /** the column name for the logo field */
    const LOGO = 'school.logo';

    /** the column name for the status field */
    const STATUS = 'school.status';

    /** the column name for the confirmation field */
    const CONFIRMATION = 'school.confirmation';

    /** the column name for the sortable_rank field */
    const SORTABLE_RANK = 'school.sortable_rank';

    /** the column name for the created_at field */
    const CREATED_AT = 'school.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'school.updated_at';

    /** The enumerated values for the status field */
    const STATUS_NEW = 'new';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_BANNED = 'banned';

    /** The enumerated values for the confirmation field */
    const CONFIRMATION_NEW = 'new';
    const CONFIRMATION_PHONE = 'phone';
    const CONFIRMATION_LETTER = 'letter';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of School objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array School[]
     */
    public static $instances = array();


    // i18n behavior

    /**
     * The default locale to use for translations
     * @var        string
     */
    const DEFAULT_LOCALE = 'en_US';
    // sortable behavior

    /**
     * rank column
     */
    const RANK_COL = 'school.sortable_rank';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. SchoolPeer::$fieldNames[SchoolPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Code', 'Name', 'Url', 'LevelId', 'Address', 'City', 'StateId', 'Zip', 'CountryId', 'OrganizationId', 'Phone', 'Fax', 'Mobile', 'Email', 'Website', 'Logo', 'Status', 'Confirmation', 'SortableRank', 'CreatedAt', 'UpdatedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'code', 'name', 'url', 'levelId', 'address', 'city', 'stateId', 'zip', 'countryId', 'organizationId', 'phone', 'fax', 'mobile', 'email', 'website', 'logo', 'status', 'confirmation', 'sortableRank', 'createdAt', 'updatedAt', ),
        BasePeer::TYPE_COLNAME => array (SchoolPeer::ID, SchoolPeer::CODE, SchoolPeer::NAME, SchoolPeer::URL, SchoolPeer::LEVEL_ID, SchoolPeer::ADDRESS, SchoolPeer::CITY, SchoolPeer::STATE_ID, SchoolPeer::ZIP, SchoolPeer::COUNTRY_ID, SchoolPeer::ORGANIZATION_ID, SchoolPeer::PHONE, SchoolPeer::FAX, SchoolPeer::MOBILE, SchoolPeer::EMAIL, SchoolPeer::WEBSITE, SchoolPeer::LOGO, SchoolPeer::STATUS, SchoolPeer::CONFIRMATION, SchoolPeer::SORTABLE_RANK, SchoolPeer::CREATED_AT, SchoolPeer::UPDATED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'CODE', 'NAME', 'URL', 'LEVEL_ID', 'ADDRESS', 'CITY', 'STATE_ID', 'ZIP', 'COUNTRY_ID', 'ORGANIZATION_ID', 'PHONE', 'FAX', 'MOBILE', 'EMAIL', 'WEBSITE', 'LOGO', 'STATUS', 'CONFIRMATION', 'SORTABLE_RANK', 'CREATED_AT', 'UPDATED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'code', 'name', 'url', 'level_id', 'address', 'city', 'state_id', 'zip', 'country_id', 'organization_id', 'phone', 'fax', 'mobile', 'email', 'website', 'logo', 'status', 'confirmation', 'sortable_rank', 'created_at', 'updated_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. SchoolPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Code' => 1, 'Name' => 2, 'Url' => 3, 'LevelId' => 4, 'Address' => 5, 'City' => 6, 'StateId' => 7, 'Zip' => 8, 'CountryId' => 9, 'OrganizationId' => 10, 'Phone' => 11, 'Fax' => 12, 'Mobile' => 13, 'Email' => 14, 'Website' => 15, 'Logo' => 16, 'Status' => 17, 'Confirmation' => 18, 'SortableRank' => 19, 'CreatedAt' => 20, 'UpdatedAt' => 21, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'code' => 1, 'name' => 2, 'url' => 3, 'levelId' => 4, 'address' => 5, 'city' => 6, 'stateId' => 7, 'zip' => 8, 'countryId' => 9, 'organizationId' => 10, 'phone' => 11, 'fax' => 12, 'mobile' => 13, 'email' => 14, 'website' => 15, 'logo' => 16, 'status' => 17, 'confirmation' => 18, 'sortableRank' => 19, 'createdAt' => 20, 'updatedAt' => 21, ),
        BasePeer::TYPE_COLNAME => array (SchoolPeer::ID => 0, SchoolPeer::CODE => 1, SchoolPeer::NAME => 2, SchoolPeer::URL => 3, SchoolPeer::LEVEL_ID => 4, SchoolPeer::ADDRESS => 5, SchoolPeer::CITY => 6, SchoolPeer::STATE_ID => 7, SchoolPeer::ZIP => 8, SchoolPeer::COUNTRY_ID => 9, SchoolPeer::ORGANIZATION_ID => 10, SchoolPeer::PHONE => 11, SchoolPeer::FAX => 12, SchoolPeer::MOBILE => 13, SchoolPeer::EMAIL => 14, SchoolPeer::WEBSITE => 15, SchoolPeer::LOGO => 16, SchoolPeer::STATUS => 17, SchoolPeer::CONFIRMATION => 18, SchoolPeer::SORTABLE_RANK => 19, SchoolPeer::CREATED_AT => 20, SchoolPeer::UPDATED_AT => 21, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'CODE' => 1, 'NAME' => 2, 'URL' => 3, 'LEVEL_ID' => 4, 'ADDRESS' => 5, 'CITY' => 6, 'STATE_ID' => 7, 'ZIP' => 8, 'COUNTRY_ID' => 9, 'ORGANIZATION_ID' => 10, 'PHONE' => 11, 'FAX' => 12, 'MOBILE' => 13, 'EMAIL' => 14, 'WEBSITE' => 15, 'LOGO' => 16, 'STATUS' => 17, 'CONFIRMATION' => 18, 'SORTABLE_RANK' => 19, 'CREATED_AT' => 20, 'UPDATED_AT' => 21, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'code' => 1, 'name' => 2, 'url' => 3, 'level_id' => 4, 'address' => 5, 'city' => 6, 'state_id' => 7, 'zip' => 8, 'country_id' => 9, 'organization_id' => 10, 'phone' => 11, 'fax' => 12, 'mobile' => 13, 'email' => 14, 'website' => 15, 'logo' => 16, 'status' => 17, 'confirmation' => 18, 'sortable_rank' => 19, 'created_at' => 20, 'updated_at' => 21, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
        SchoolPeer::STATUS => array(
            SchoolPeer::STATUS_NEW,
            SchoolPeer::STATUS_ACTIVE,
            SchoolPeer::STATUS_INACTIVE,
            SchoolPeer::STATUS_BANNED,
        ),
        SchoolPeer::CONFIRMATION => array(
            SchoolPeer::CONFIRMATION_NEW,
            SchoolPeer::CONFIRMATION_PHONE,
            SchoolPeer::CONFIRMATION_LETTER,
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
        $toNames = SchoolPeer::getFieldNames($toType);
        $key = isset(SchoolPeer::$fieldKeys[$fromType][$name]) ? SchoolPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(SchoolPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, SchoolPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return SchoolPeer::$fieldNames[$type];
    }

    /**
     * Gets the list of values for all ENUM columns
     * @return array
     */
    public static function getValueSets()
    {
      return SchoolPeer::$enumValueSets;
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
        $valueSets = SchoolPeer::getValueSets();

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
        $values = SchoolPeer::getValueSet($colname);
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
     * @param      string $column The column name for current table. (i.e. SchoolPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(SchoolPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(SchoolPeer::ID);
            $criteria->addSelectColumn(SchoolPeer::CODE);
            $criteria->addSelectColumn(SchoolPeer::NAME);
            $criteria->addSelectColumn(SchoolPeer::URL);
            $criteria->addSelectColumn(SchoolPeer::LEVEL_ID);
            $criteria->addSelectColumn(SchoolPeer::ADDRESS);
            $criteria->addSelectColumn(SchoolPeer::CITY);
            $criteria->addSelectColumn(SchoolPeer::STATE_ID);
            $criteria->addSelectColumn(SchoolPeer::ZIP);
            $criteria->addSelectColumn(SchoolPeer::COUNTRY_ID);
            $criteria->addSelectColumn(SchoolPeer::ORGANIZATION_ID);
            $criteria->addSelectColumn(SchoolPeer::PHONE);
            $criteria->addSelectColumn(SchoolPeer::FAX);
            $criteria->addSelectColumn(SchoolPeer::MOBILE);
            $criteria->addSelectColumn(SchoolPeer::EMAIL);
            $criteria->addSelectColumn(SchoolPeer::WEBSITE);
            $criteria->addSelectColumn(SchoolPeer::LOGO);
            $criteria->addSelectColumn(SchoolPeer::STATUS);
            $criteria->addSelectColumn(SchoolPeer::CONFIRMATION);
            $criteria->addSelectColumn(SchoolPeer::SORTABLE_RANK);
            $criteria->addSelectColumn(SchoolPeer::CREATED_AT);
            $criteria->addSelectColumn(SchoolPeer::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.code');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.url');
            $criteria->addSelectColumn($alias . '.level_id');
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
            $criteria->addSelectColumn($alias . '.status');
            $criteria->addSelectColumn($alias . '.confirmation');
            $criteria->addSelectColumn($alias . '.sortable_rank');
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
        $criteria->setPrimaryTableName(SchoolPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(SchoolPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return School
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = SchoolPeer::doSelect($critcopy, $con);
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
        return SchoolPeer::populateObjects(SchoolPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            SchoolPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(SchoolPeer::DATABASE_NAME);

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
     * @param School $obj A School object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            SchoolPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A School object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof School) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or School object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(SchoolPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return School Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(SchoolPeer::$instances[$key])) {
                return SchoolPeer::$instances[$key];
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
        foreach (SchoolPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        SchoolPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to school
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in ApplicationPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        ApplicationPeer::clearInstancePool();
        // Invalidate objects in CoursePeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        CoursePeer::clearInstancePool();
        // Invalidate objects in PagePeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PagePeer::clearInstancePool();
        // Invalidate objects in EmployeePeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        EmployeePeer::clearInstancePool();
        // Invalidate objects in QualificationPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        QualificationPeer::clearInstancePool();
        // Invalidate objects in SchoolEmploymentPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        SchoolEmploymentPeer::clearInstancePool();
        // Invalidate objects in SchoolEnrollmentPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        SchoolEnrollmentPeer::clearInstancePool();
        // Invalidate objects in SchoolTestPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        SchoolTestPeer::clearInstancePool();
        // Invalidate objects in SchoolYearPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        SchoolYearPeer::clearInstancePool();
        // Invalidate objects in SchoolI18nPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        SchoolI18nPeer::clearInstancePool();
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
        $cls = SchoolPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = SchoolPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = SchoolPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SchoolPeer::addInstanceToPool($obj, $key);
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
     * @return array (School object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = SchoolPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = SchoolPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + SchoolPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SchoolPeer::getOMClass($row, $startcol);
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            SchoolPeer::addInstanceToPool($obj, $key);
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
        return SchoolPeer::getSqlValueForEnum(SchoolPeer::STATUS, $enumVal);
    }

    /**
     * Gets the SQL value for Confirmation ENUM value
     *
     * @param  string $enumVal ENUM value to get SQL value for
     * @return int SQL value
     */
    public static function getConfirmationSqlValue($enumVal)
    {
        return SchoolPeer::getSqlValueForEnum(SchoolPeer::CONFIRMATION, $enumVal);
    }


    /**
     * Returns the number of rows matching criteria, joining the related State table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinState(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SchoolPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolPeer::STATE_ID, StatePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Country table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinCountry(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SchoolPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Organization table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinOrganization(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SchoolPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolPeer::ORGANIZATION_ID, OrganizationPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Level table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinLevel(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SchoolPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

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
     * Selects a collection of School objects pre-filled with their State objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of School objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinState(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolPeer::DATABASE_NAME);
        }

        SchoolPeer::addSelectColumns($criteria);
        $startcol = SchoolPeer::NUM_HYDRATE_COLUMNS;
        StatePeer::addSelectColumns($criteria);

        $criteria->addJoin(SchoolPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SchoolPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = StatePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = StatePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = StatePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    StatePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (School) to $obj2 (State)
                $obj2->addSchool($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of School objects pre-filled with their Country objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of School objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinCountry(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolPeer::DATABASE_NAME);
        }

        SchoolPeer::addSelectColumns($criteria);
        $startcol = SchoolPeer::NUM_HYDRATE_COLUMNS;
        CountryPeer::addSelectColumns($criteria);

        $criteria->addJoin(SchoolPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SchoolPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = CountryPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = CountryPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = CountryPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    CountryPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (School) to $obj2 (Country)
                $obj2->addSchool($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of School objects pre-filled with their Organization objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of School objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinOrganization(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolPeer::DATABASE_NAME);
        }

        SchoolPeer::addSelectColumns($criteria);
        $startcol = SchoolPeer::NUM_HYDRATE_COLUMNS;
        OrganizationPeer::addSelectColumns($criteria);

        $criteria->addJoin(SchoolPeer::ORGANIZATION_ID, OrganizationPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SchoolPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = OrganizationPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = OrganizationPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = OrganizationPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    OrganizationPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (School) to $obj2 (Organization)
                $obj2->addSchool($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of School objects pre-filled with their Level objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of School objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinLevel(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolPeer::DATABASE_NAME);
        }

        SchoolPeer::addSelectColumns($criteria);
        $startcol = SchoolPeer::NUM_HYDRATE_COLUMNS;
        LevelPeer::addSelectColumns($criteria);

        $criteria->addJoin(SchoolPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SchoolPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = LevelPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = LevelPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = LevelPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    LevelPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (School) to $obj2 (Level)
                $obj2->addSchool($obj1);

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
        $criteria->setPrimaryTableName(SchoolPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SchoolPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(SchoolPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolPeer::ORGANIZATION_ID, OrganizationPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

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
     * Selects a collection of School objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of School objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolPeer::DATABASE_NAME);
        }

        SchoolPeer::addSelectColumns($criteria);
        $startcol2 = SchoolPeer::NUM_HYDRATE_COLUMNS;

        StatePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + StatePeer::NUM_HYDRATE_COLUMNS;

        CountryPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + CountryPeer::NUM_HYDRATE_COLUMNS;

        OrganizationPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + OrganizationPeer::NUM_HYDRATE_COLUMNS;

        LevelPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + LevelPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SchoolPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(SchoolPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolPeer::ORGANIZATION_ID, OrganizationPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SchoolPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined State rows

            $key2 = StatePeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = StatePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = StatePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    StatePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (School) to the collection in $obj2 (State)
                $obj2->addSchool($obj1);
            } // if joined row not null

            // Add objects for joined Country rows

            $key3 = CountryPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = CountryPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = CountryPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    CountryPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (School) to the collection in $obj3 (Country)
                $obj3->addSchool($obj1);
            } // if joined row not null

            // Add objects for joined Organization rows

            $key4 = OrganizationPeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = OrganizationPeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = OrganizationPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    OrganizationPeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (School) to the collection in $obj4 (Organization)
                $obj4->addSchool($obj1);
            } // if joined row not null

            // Add objects for joined Level rows

            $key5 = LevelPeer::getPrimaryKeyHashFromRow($row, $startcol5);
            if ($key5 !== null) {
                $obj5 = LevelPeer::getInstanceFromPool($key5);
                if (!$obj5) {

                    $cls = LevelPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    LevelPeer::addInstanceToPool($obj5, $key5);
                } // if obj5 loaded

                // Add the $obj1 (School) to the collection in $obj5 (Level)
                $obj5->addSchool($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related State table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptState(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SchoolPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolPeer::ORGANIZATION_ID, OrganizationPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Country table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptCountry(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SchoolPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(SchoolPeer::ORGANIZATION_ID, OrganizationPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Organization table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptOrganization(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SchoolPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(SchoolPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Level table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptLevel(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SchoolPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SchoolPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SchoolPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SchoolPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(SchoolPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolPeer::ORGANIZATION_ID, OrganizationPeer::ID, $join_behavior);

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
     * Selects a collection of School objects pre-filled with all related objects except State.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of School objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptState(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolPeer::DATABASE_NAME);
        }

        SchoolPeer::addSelectColumns($criteria);
        $startcol2 = SchoolPeer::NUM_HYDRATE_COLUMNS;

        CountryPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CountryPeer::NUM_HYDRATE_COLUMNS;

        OrganizationPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + OrganizationPeer::NUM_HYDRATE_COLUMNS;

        LevelPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + LevelPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SchoolPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolPeer::ORGANIZATION_ID, OrganizationPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SchoolPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Country rows

                $key2 = CountryPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CountryPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CountryPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CountryPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (School) to the collection in $obj2 (Country)
                $obj2->addSchool($obj1);

            } // if joined row is not null

                // Add objects for joined Organization rows

                $key3 = OrganizationPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = OrganizationPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = OrganizationPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    OrganizationPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (School) to the collection in $obj3 (Organization)
                $obj3->addSchool($obj1);

            } // if joined row is not null

                // Add objects for joined Level rows

                $key4 = LevelPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = LevelPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = LevelPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    LevelPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (School) to the collection in $obj4 (Level)
                $obj4->addSchool($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of School objects pre-filled with all related objects except Country.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of School objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptCountry(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolPeer::DATABASE_NAME);
        }

        SchoolPeer::addSelectColumns($criteria);
        $startcol2 = SchoolPeer::NUM_HYDRATE_COLUMNS;

        StatePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + StatePeer::NUM_HYDRATE_COLUMNS;

        OrganizationPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + OrganizationPeer::NUM_HYDRATE_COLUMNS;

        LevelPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + LevelPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SchoolPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(SchoolPeer::ORGANIZATION_ID, OrganizationPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SchoolPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined State rows

                $key2 = StatePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = StatePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = StatePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    StatePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (School) to the collection in $obj2 (State)
                $obj2->addSchool($obj1);

            } // if joined row is not null

                // Add objects for joined Organization rows

                $key3 = OrganizationPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = OrganizationPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = OrganizationPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    OrganizationPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (School) to the collection in $obj3 (Organization)
                $obj3->addSchool($obj1);

            } // if joined row is not null

                // Add objects for joined Level rows

                $key4 = LevelPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = LevelPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = LevelPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    LevelPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (School) to the collection in $obj4 (Level)
                $obj4->addSchool($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of School objects pre-filled with all related objects except Organization.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of School objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptOrganization(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolPeer::DATABASE_NAME);
        }

        SchoolPeer::addSelectColumns($criteria);
        $startcol2 = SchoolPeer::NUM_HYDRATE_COLUMNS;

        StatePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + StatePeer::NUM_HYDRATE_COLUMNS;

        CountryPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + CountryPeer::NUM_HYDRATE_COLUMNS;

        LevelPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + LevelPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SchoolPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(SchoolPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SchoolPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined State rows

                $key2 = StatePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = StatePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = StatePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    StatePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (School) to the collection in $obj2 (State)
                $obj2->addSchool($obj1);

            } // if joined row is not null

                // Add objects for joined Country rows

                $key3 = CountryPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = CountryPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = CountryPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    CountryPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (School) to the collection in $obj3 (Country)
                $obj3->addSchool($obj1);

            } // if joined row is not null

                // Add objects for joined Level rows

                $key4 = LevelPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = LevelPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = LevelPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    LevelPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (School) to the collection in $obj4 (Level)
                $obj4->addSchool($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of School objects pre-filled with all related objects except Level.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of School objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptLevel(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SchoolPeer::DATABASE_NAME);
        }

        SchoolPeer::addSelectColumns($criteria);
        $startcol2 = SchoolPeer::NUM_HYDRATE_COLUMNS;

        StatePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + StatePeer::NUM_HYDRATE_COLUMNS;

        CountryPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + CountryPeer::NUM_HYDRATE_COLUMNS;

        OrganizationPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + OrganizationPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SchoolPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(SchoolPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $criteria->addJoin(SchoolPeer::ORGANIZATION_ID, OrganizationPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SchoolPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SchoolPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SchoolPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SchoolPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined State rows

                $key2 = StatePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = StatePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = StatePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    StatePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (School) to the collection in $obj2 (State)
                $obj2->addSchool($obj1);

            } // if joined row is not null

                // Add objects for joined Country rows

                $key3 = CountryPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = CountryPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = CountryPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    CountryPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (School) to the collection in $obj3 (Country)
                $obj3->addSchool($obj1);

            } // if joined row is not null

                // Add objects for joined Organization rows

                $key4 = OrganizationPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = OrganizationPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = OrganizationPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    OrganizationPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (School) to the collection in $obj4 (Organization)
                $obj4->addSchool($obj1);

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
        return Propel::getDatabaseMap(SchoolPeer::DATABASE_NAME)->getTable(SchoolPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseSchoolPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseSchoolPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \PGS\CoreDomainBundle\Model\School\map\SchoolTableMap());
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

        $event = new DetectOMClassEvent(SchoolPeer::OM_CLASS, $row, $colnum);
        EventDispatcherProxy::trigger('om.detect', $event);
        if($event->isDetected()){
            return $event->getDetectedClass();
        }

        return SchoolPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a School or Criteria object.
     *
     * @param      mixed $values Criteria or School object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from School object
        }

        if ($criteria->containsKey(SchoolPeer::ID) && $criteria->keyContainsValue(SchoolPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SchoolPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(SchoolPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a School or Criteria object.
     *
     * @param      mixed $values Criteria or School object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(SchoolPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(SchoolPeer::ID);
            $value = $criteria->remove(SchoolPeer::ID);
            if ($value) {
                $selectCriteria->add(SchoolPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(SchoolPeer::TABLE_NAME);
            }

        } else { // $values is School object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(SchoolPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the school table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(SchoolPeer::TABLE_NAME, $con, SchoolPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SchoolPeer::clearInstancePool();
            SchoolPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a School or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or School object or primary key or array of primary keys
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
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            SchoolPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof School) { // it's a model object
            // invalidate the cache for this single object
            SchoolPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SchoolPeer::DATABASE_NAME);
            $criteria->add(SchoolPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                SchoolPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(SchoolPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            SchoolPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given School object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param School $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(SchoolPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(SchoolPeer::TABLE_NAME);

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

        return BasePeer::doValidate(SchoolPeer::DATABASE_NAME, SchoolPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return School
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = SchoolPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(SchoolPeer::DATABASE_NAME);
        $criteria->add(SchoolPeer::ID, $pk);

        $v = SchoolPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return School[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(SchoolPeer::DATABASE_NAME);
            $criteria->add(SchoolPeer::ID, $pks, Criteria::IN);
            $objs = SchoolPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

    // sortable behavior

    /**
     * Get the highest rank
     *
     * @param     PropelPDO optional connection
     *
     * @return    integer highest position
     */
    public static function getMaxRank(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $c = new Criteria();
        $c->addSelectColumn('MAX(' . SchoolPeer::RANK_COL . ')');
        $stmt = SchoolPeer::doSelectStmt($c, $con);

        return $stmt->fetchColumn();
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     PropelPDO $con optional connection
     *
     * @return School
     */
    public static function retrieveByRank($rank, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME);
        }

        $c = new Criteria;
        $c->add(SchoolPeer::RANK_COL, $rank);

        return SchoolPeer::doSelectOne($c, $con);
    }

    /**
     * Reorder a set of sortable objects based on a list of id/position
     * Beware that there is no check made on the positions passed
     * So incoherent positions will result in an incoherent list
     *
     * @param     array     $order id => rank pairs
     * @param     PropelPDO $con   optional connection
     *
     * @return    boolean true if the reordering took place, false if a database problem prevented it
     */
    public static function reorder(array $order, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $ids = array_keys($order);
            $objects = SchoolPeer::retrieveByPKs($ids);
            foreach ($objects as $object) {
                $pk = $object->getPrimaryKey();
                if ($object->getSortableRank() != $order[$pk]) {
                    $object->setSortableRank($order[$pk]);
                    $object->save($con);
                }
            }
            $con->commit();

            return true;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Return an array of sortable objects ordered by position
     *
     * @param     Criteria  $criteria  optional criteria object
     * @param     string    $order     sorting order, to be chosen between Criteria::ASC (default) and Criteria::DESC
     * @param     PropelPDO $con       optional connection
     *
     * @return    array list of sortable objects
     */
    public static function doSelectOrderByRank(Criteria $criteria = null, $order = Criteria::ASC, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME);
        }

        if ($criteria === null) {
            $criteria = new Criteria();
        } elseif ($criteria instanceof Criteria) {
            $criteria = clone $criteria;
        }

        $criteria->clearOrderByColumns();

        if ($order == Criteria::ASC) {
            $criteria->addAscendingOrderByColumn(SchoolPeer::RANK_COL);
        } else {
            $criteria->addDescendingOrderByColumn(SchoolPeer::RANK_COL);
        }

        return SchoolPeer::doSelect($criteria, $con);
    }

    /**
     * Adds $delta to all Rank values that are >= $first and <= $last.
     * '$delta' can also be negative.
     *
     * @param      int $delta Value to be shifted by, can be negative
     * @param      int $first First node to be shifted
     * @param      int $last  Last node to be shifted
     * @param      PropelPDO $con Connection to use.
     */
    public static function shiftRank($delta, $first = null, $last = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SchoolPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $whereCriteria = SchoolQuery::create();
        if (null !== $first) {
            $whereCriteria->add(SchoolPeer::RANK_COL, $first, Criteria::GREATER_EQUAL);
        }
        if (null !== $last) {
            $whereCriteria->addAnd(SchoolPeer::RANK_COL, $last, Criteria::LESS_EQUAL);
        }

        $valuesCriteria = new Criteria(SchoolPeer::DATABASE_NAME);
        $valuesCriteria->add(SchoolPeer::RANK_COL, array('raw' => SchoolPeer::RANK_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
        SchoolPeer::clearInstancePool();
    }

} // BaseSchoolPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseSchoolPeer::buildTableMap();

EventDispatcherProxy::trigger(array('construct','peer.construct'), new PeerEvent('PGS\CoreDomainBundle\Model\School\om\BaseSchoolPeer'));
