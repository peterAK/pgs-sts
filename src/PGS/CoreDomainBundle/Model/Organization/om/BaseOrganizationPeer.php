<?php

namespace PGS\CoreDomainBundle\Model\Organization\om;

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
use PGS\CoreDomainBundle\Model\RegionPeer;
use PGS\CoreDomainBundle\Model\StatePeer;
use PGS\CoreDomainBundle\Model\UserPeer;
use PGS\CoreDomainBundle\Model\Organization\Organization;
use PGS\CoreDomainBundle\Model\Organization\OrganizationI18nPeer;
use PGS\CoreDomainBundle\Model\Organization\OrganizationPeer;
use PGS\CoreDomainBundle\Model\Organization\OrganizationQuery;
use PGS\CoreDomainBundle\Model\Organization\map\OrganizationTableMap;

abstract class BaseOrganizationPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'organization';

    /** the related Propel class for this table */
    const OM_CLASS = 'PGS\\CoreDomainBundle\\Model\\Organization\\Organization';

    /** the related TableMap class for this table */
    const TM_CLASS = 'PGS\\CoreDomainBundle\\Model\\Organization\\map\\OrganizationTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 25;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 25;

    /** the column name for the id field */
    const ID = 'organization.id';

    /** the column name for the user_id field */
    const USER_ID = 'organization.user_id';

    /** the column name for the name field */
    const NAME = 'organization.name';

    /** the column name for the url field */
    const URL = 'organization.url';

    /** the column name for the goverment_license field */
    const GOVERMENT_LICENSE = 'organization.goverment_license';

    /** the column name for the join_at field */
    const JOIN_AT = 'organization.join_at';

    /** the column name for the address1 field */
    const ADDRESS1 = 'organization.address1';

    /** the column name for the address2 field */
    const ADDRESS2 = 'organization.address2';

    /** the column name for the city field */
    const CITY = 'organization.city';

    /** the column name for the zipcode field */
    const ZIPCODE = 'organization.zipcode';

    /** the column name for the country_id field */
    const COUNTRY_ID = 'organization.country_id';

    /** the column name for the state_id field */
    const STATE_ID = 'organization.state_id';

    /** the column name for the region_id field */
    const REGION_ID = 'organization.region_id';

    /** the column name for the phone field */
    const PHONE = 'organization.phone';

    /** the column name for the fax field */
    const FAX = 'organization.fax';

    /** the column name for the mobile field */
    const MOBILE = 'organization.mobile';

    /** the column name for the email field */
    const EMAIL = 'organization.email';

    /** the column name for the website field */
    const WEBSITE = 'organization.website';

    /** the column name for the logo field */
    const LOGO = 'organization.logo';

    /** the column name for the status field */
    const STATUS = 'organization.status';

    /** the column name for the is_principal field */
    const IS_PRINCIPAL = 'organization.is_principal';

    /** the column name for the confirmation field */
    const CONFIRMATION = 'organization.confirmation';

    /** the column name for the sortable_rank field */
    const SORTABLE_RANK = 'organization.sortable_rank';

    /** the column name for the created_at field */
    const CREATED_AT = 'organization.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'organization.updated_at';

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
     * An identity map to hold any loaded instances of Organization objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array Organization[]
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
    const RANK_COL = 'organization.sortable_rank';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. OrganizationPeer::$fieldNames[OrganizationPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'UserId', 'Name', 'Url', 'GovermentLicense', 'JoinAt', 'Address1', 'Address2', 'City', 'Zipcode', 'CountryId', 'StateId', 'RegionId', 'Phone', 'Fax', 'Mobile', 'Email', 'Website', 'Logo', 'Status', 'IsPrincipal', 'Confirmation', 'SortableRank', 'CreatedAt', 'UpdatedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'userId', 'name', 'url', 'govermentLicense', 'joinAt', 'address1', 'address2', 'city', 'zipcode', 'countryId', 'stateId', 'regionId', 'phone', 'fax', 'mobile', 'email', 'website', 'logo', 'status', 'isPrincipal', 'confirmation', 'sortableRank', 'createdAt', 'updatedAt', ),
        BasePeer::TYPE_COLNAME => array (OrganizationPeer::ID, OrganizationPeer::USER_ID, OrganizationPeer::NAME, OrganizationPeer::URL, OrganizationPeer::GOVERMENT_LICENSE, OrganizationPeer::JOIN_AT, OrganizationPeer::ADDRESS1, OrganizationPeer::ADDRESS2, OrganizationPeer::CITY, OrganizationPeer::ZIPCODE, OrganizationPeer::COUNTRY_ID, OrganizationPeer::STATE_ID, OrganizationPeer::REGION_ID, OrganizationPeer::PHONE, OrganizationPeer::FAX, OrganizationPeer::MOBILE, OrganizationPeer::EMAIL, OrganizationPeer::WEBSITE, OrganizationPeer::LOGO, OrganizationPeer::STATUS, OrganizationPeer::IS_PRINCIPAL, OrganizationPeer::CONFIRMATION, OrganizationPeer::SORTABLE_RANK, OrganizationPeer::CREATED_AT, OrganizationPeer::UPDATED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'USER_ID', 'NAME', 'URL', 'GOVERMENT_LICENSE', 'JOIN_AT', 'ADDRESS1', 'ADDRESS2', 'CITY', 'ZIPCODE', 'COUNTRY_ID', 'STATE_ID', 'REGION_ID', 'PHONE', 'FAX', 'MOBILE', 'EMAIL', 'WEBSITE', 'LOGO', 'STATUS', 'IS_PRINCIPAL', 'CONFIRMATION', 'SORTABLE_RANK', 'CREATED_AT', 'UPDATED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'user_id', 'name', 'url', 'goverment_license', 'join_at', 'address1', 'address2', 'city', 'zipcode', 'country_id', 'state_id', 'region_id', 'phone', 'fax', 'mobile', 'email', 'website', 'logo', 'status', 'is_principal', 'confirmation', 'sortable_rank', 'created_at', 'updated_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. OrganizationPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'UserId' => 1, 'Name' => 2, 'Url' => 3, 'GovermentLicense' => 4, 'JoinAt' => 5, 'Address1' => 6, 'Address2' => 7, 'City' => 8, 'Zipcode' => 9, 'CountryId' => 10, 'StateId' => 11, 'RegionId' => 12, 'Phone' => 13, 'Fax' => 14, 'Mobile' => 15, 'Email' => 16, 'Website' => 17, 'Logo' => 18, 'Status' => 19, 'IsPrincipal' => 20, 'Confirmation' => 21, 'SortableRank' => 22, 'CreatedAt' => 23, 'UpdatedAt' => 24, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'userId' => 1, 'name' => 2, 'url' => 3, 'govermentLicense' => 4, 'joinAt' => 5, 'address1' => 6, 'address2' => 7, 'city' => 8, 'zipcode' => 9, 'countryId' => 10, 'stateId' => 11, 'regionId' => 12, 'phone' => 13, 'fax' => 14, 'mobile' => 15, 'email' => 16, 'website' => 17, 'logo' => 18, 'status' => 19, 'isPrincipal' => 20, 'confirmation' => 21, 'sortableRank' => 22, 'createdAt' => 23, 'updatedAt' => 24, ),
        BasePeer::TYPE_COLNAME => array (OrganizationPeer::ID => 0, OrganizationPeer::USER_ID => 1, OrganizationPeer::NAME => 2, OrganizationPeer::URL => 3, OrganizationPeer::GOVERMENT_LICENSE => 4, OrganizationPeer::JOIN_AT => 5, OrganizationPeer::ADDRESS1 => 6, OrganizationPeer::ADDRESS2 => 7, OrganizationPeer::CITY => 8, OrganizationPeer::ZIPCODE => 9, OrganizationPeer::COUNTRY_ID => 10, OrganizationPeer::STATE_ID => 11, OrganizationPeer::REGION_ID => 12, OrganizationPeer::PHONE => 13, OrganizationPeer::FAX => 14, OrganizationPeer::MOBILE => 15, OrganizationPeer::EMAIL => 16, OrganizationPeer::WEBSITE => 17, OrganizationPeer::LOGO => 18, OrganizationPeer::STATUS => 19, OrganizationPeer::IS_PRINCIPAL => 20, OrganizationPeer::CONFIRMATION => 21, OrganizationPeer::SORTABLE_RANK => 22, OrganizationPeer::CREATED_AT => 23, OrganizationPeer::UPDATED_AT => 24, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'USER_ID' => 1, 'NAME' => 2, 'URL' => 3, 'GOVERMENT_LICENSE' => 4, 'JOIN_AT' => 5, 'ADDRESS1' => 6, 'ADDRESS2' => 7, 'CITY' => 8, 'ZIPCODE' => 9, 'COUNTRY_ID' => 10, 'STATE_ID' => 11, 'REGION_ID' => 12, 'PHONE' => 13, 'FAX' => 14, 'MOBILE' => 15, 'EMAIL' => 16, 'WEBSITE' => 17, 'LOGO' => 18, 'STATUS' => 19, 'IS_PRINCIPAL' => 20, 'CONFIRMATION' => 21, 'SORTABLE_RANK' => 22, 'CREATED_AT' => 23, 'UPDATED_AT' => 24, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'user_id' => 1, 'name' => 2, 'url' => 3, 'goverment_license' => 4, 'join_at' => 5, 'address1' => 6, 'address2' => 7, 'city' => 8, 'zipcode' => 9, 'country_id' => 10, 'state_id' => 11, 'region_id' => 12, 'phone' => 13, 'fax' => 14, 'mobile' => 15, 'email' => 16, 'website' => 17, 'logo' => 18, 'status' => 19, 'is_principal' => 20, 'confirmation' => 21, 'sortable_rank' => 22, 'created_at' => 23, 'updated_at' => 24, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
        OrganizationPeer::STATUS => array(
            OrganizationPeer::STATUS_NEW,
            OrganizationPeer::STATUS_ACTIVE,
            OrganizationPeer::STATUS_INACTIVE,
            OrganizationPeer::STATUS_BANNED,
        ),
        OrganizationPeer::CONFIRMATION => array(
            OrganizationPeer::CONFIRMATION_NEW,
            OrganizationPeer::CONFIRMATION_PHONE,
            OrganizationPeer::CONFIRMATION_LETTERS,
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
        $toNames = OrganizationPeer::getFieldNames($toType);
        $key = isset(OrganizationPeer::$fieldKeys[$fromType][$name]) ? OrganizationPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(OrganizationPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, OrganizationPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return OrganizationPeer::$fieldNames[$type];
    }

    /**
     * Gets the list of values for all ENUM columns
     * @return array
     */
    public static function getValueSets()
    {
      return OrganizationPeer::$enumValueSets;
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
        $valueSets = OrganizationPeer::getValueSets();

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
        $values = OrganizationPeer::getValueSet($colname);
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
     * @param      string $column The column name for current table. (i.e. OrganizationPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(OrganizationPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(OrganizationPeer::ID);
            $criteria->addSelectColumn(OrganizationPeer::USER_ID);
            $criteria->addSelectColumn(OrganizationPeer::NAME);
            $criteria->addSelectColumn(OrganizationPeer::URL);
            $criteria->addSelectColumn(OrganizationPeer::GOVERMENT_LICENSE);
            $criteria->addSelectColumn(OrganizationPeer::JOIN_AT);
            $criteria->addSelectColumn(OrganizationPeer::ADDRESS1);
            $criteria->addSelectColumn(OrganizationPeer::ADDRESS2);
            $criteria->addSelectColumn(OrganizationPeer::CITY);
            $criteria->addSelectColumn(OrganizationPeer::ZIPCODE);
            $criteria->addSelectColumn(OrganizationPeer::COUNTRY_ID);
            $criteria->addSelectColumn(OrganizationPeer::STATE_ID);
            $criteria->addSelectColumn(OrganizationPeer::REGION_ID);
            $criteria->addSelectColumn(OrganizationPeer::PHONE);
            $criteria->addSelectColumn(OrganizationPeer::FAX);
            $criteria->addSelectColumn(OrganizationPeer::MOBILE);
            $criteria->addSelectColumn(OrganizationPeer::EMAIL);
            $criteria->addSelectColumn(OrganizationPeer::WEBSITE);
            $criteria->addSelectColumn(OrganizationPeer::LOGO);
            $criteria->addSelectColumn(OrganizationPeer::STATUS);
            $criteria->addSelectColumn(OrganizationPeer::IS_PRINCIPAL);
            $criteria->addSelectColumn(OrganizationPeer::CONFIRMATION);
            $criteria->addSelectColumn(OrganizationPeer::SORTABLE_RANK);
            $criteria->addSelectColumn(OrganizationPeer::CREATED_AT);
            $criteria->addSelectColumn(OrganizationPeer::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.url');
            $criteria->addSelectColumn($alias . '.goverment_license');
            $criteria->addSelectColumn($alias . '.join_at');
            $criteria->addSelectColumn($alias . '.address1');
            $criteria->addSelectColumn($alias . '.address2');
            $criteria->addSelectColumn($alias . '.city');
            $criteria->addSelectColumn($alias . '.zipcode');
            $criteria->addSelectColumn($alias . '.country_id');
            $criteria->addSelectColumn($alias . '.state_id');
            $criteria->addSelectColumn($alias . '.region_id');
            $criteria->addSelectColumn($alias . '.phone');
            $criteria->addSelectColumn($alias . '.fax');
            $criteria->addSelectColumn($alias . '.mobile');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.website');
            $criteria->addSelectColumn($alias . '.logo');
            $criteria->addSelectColumn($alias . '.status');
            $criteria->addSelectColumn($alias . '.is_principal');
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
        $criteria->setPrimaryTableName(OrganizationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            OrganizationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(OrganizationPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return Organization
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = OrganizationPeer::doSelect($critcopy, $con);
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
        return OrganizationPeer::populateObjects(OrganizationPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            OrganizationPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(OrganizationPeer::DATABASE_NAME);

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
     * @param Organization $obj A Organization object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            OrganizationPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A Organization object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof Organization) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or Organization object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(OrganizationPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return Organization Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(OrganizationPeer::$instances[$key])) {
                return OrganizationPeer::$instances[$key];
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
        foreach (OrganizationPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        OrganizationPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to organization
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in OrganizationI18nPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        OrganizationI18nPeer::clearInstancePool();
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
        $cls = OrganizationPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = OrganizationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = OrganizationPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                OrganizationPeer::addInstanceToPool($obj, $key);
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
     * @return array (Organization object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = OrganizationPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = OrganizationPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + OrganizationPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = OrganizationPeer::getOMClass($row, $startcol);
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            OrganizationPeer::addInstanceToPool($obj, $key);
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
        return OrganizationPeer::getSqlValueForEnum(OrganizationPeer::STATUS, $enumVal);
    }

    /**
     * Gets the SQL value for Confirmation ENUM value
     *
     * @param  string $enumVal ENUM value to get SQL value for
     * @return int SQL value
     */
    public static function getConfirmationSqlValue($enumVal)
    {
        return OrganizationPeer::getSqlValueForEnum(OrganizationPeer::CONFIRMATION, $enumVal);
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
        $criteria->setPrimaryTableName(OrganizationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            OrganizationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(OrganizationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(OrganizationPeer::USER_ID, UserPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(OrganizationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            OrganizationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(OrganizationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(OrganizationPeer::STATE_ID, StatePeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(OrganizationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            OrganizationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(OrganizationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(OrganizationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Region table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinRegion(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(OrganizationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            OrganizationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(OrganizationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(OrganizationPeer::REGION_ID, RegionPeer::ID, $join_behavior);

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
     * Selects a collection of Organization objects pre-filled with their User objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Organization objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(OrganizationPeer::DATABASE_NAME);
        }

        OrganizationPeer::addSelectColumns($criteria);
        $startcol = OrganizationPeer::NUM_HYDRATE_COLUMNS;
        UserPeer::addSelectColumns($criteria);

        $criteria->addJoin(OrganizationPeer::USER_ID, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = OrganizationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = OrganizationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = OrganizationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                OrganizationPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Organization) to $obj2 (User)
                $obj2->addOrganization($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Organization objects pre-filled with their State objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Organization objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinState(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(OrganizationPeer::DATABASE_NAME);
        }

        OrganizationPeer::addSelectColumns($criteria);
        $startcol = OrganizationPeer::NUM_HYDRATE_COLUMNS;
        StatePeer::addSelectColumns($criteria);

        $criteria->addJoin(OrganizationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = OrganizationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = OrganizationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = OrganizationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                OrganizationPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Organization) to $obj2 (State)
                $obj2->addOrganization($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Organization objects pre-filled with their Country objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Organization objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinCountry(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(OrganizationPeer::DATABASE_NAME);
        }

        OrganizationPeer::addSelectColumns($criteria);
        $startcol = OrganizationPeer::NUM_HYDRATE_COLUMNS;
        CountryPeer::addSelectColumns($criteria);

        $criteria->addJoin(OrganizationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = OrganizationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = OrganizationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = OrganizationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                OrganizationPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Organization) to $obj2 (Country)
                $obj2->addOrganization($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Organization objects pre-filled with their Region objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Organization objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinRegion(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(OrganizationPeer::DATABASE_NAME);
        }

        OrganizationPeer::addSelectColumns($criteria);
        $startcol = OrganizationPeer::NUM_HYDRATE_COLUMNS;
        RegionPeer::addSelectColumns($criteria);

        $criteria->addJoin(OrganizationPeer::REGION_ID, RegionPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = OrganizationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = OrganizationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = OrganizationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                OrganizationPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = RegionPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = RegionPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = RegionPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    RegionPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Organization) to $obj2 (Region)
                $obj2->addOrganization($obj1);

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
        $criteria->setPrimaryTableName(OrganizationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            OrganizationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(OrganizationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(OrganizationPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(OrganizationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(OrganizationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $criteria->addJoin(OrganizationPeer::REGION_ID, RegionPeer::ID, $join_behavior);

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
     * Selects a collection of Organization objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Organization objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(OrganizationPeer::DATABASE_NAME);
        }

        OrganizationPeer::addSelectColumns($criteria);
        $startcol2 = OrganizationPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserPeer::NUM_HYDRATE_COLUMNS;

        StatePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + StatePeer::NUM_HYDRATE_COLUMNS;

        CountryPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + CountryPeer::NUM_HYDRATE_COLUMNS;

        RegionPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + RegionPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(OrganizationPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(OrganizationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(OrganizationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $criteria->addJoin(OrganizationPeer::REGION_ID, RegionPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = OrganizationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = OrganizationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = OrganizationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                OrganizationPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Organization) to the collection in $obj2 (User)
                $obj2->addOrganization($obj1);
            } // if joined row not null

            // Add objects for joined State rows

            $key3 = StatePeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = StatePeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = StatePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    StatePeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (Organization) to the collection in $obj3 (State)
                $obj3->addOrganization($obj1);
            } // if joined row not null

            // Add objects for joined Country rows

            $key4 = CountryPeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = CountryPeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = CountryPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    CountryPeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (Organization) to the collection in $obj4 (Country)
                $obj4->addOrganization($obj1);
            } // if joined row not null

            // Add objects for joined Region rows

            $key5 = RegionPeer::getPrimaryKeyHashFromRow($row, $startcol5);
            if ($key5 !== null) {
                $obj5 = RegionPeer::getInstanceFromPool($key5);
                if (!$obj5) {

                    $cls = RegionPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    RegionPeer::addInstanceToPool($obj5, $key5);
                } // if obj5 loaded

                // Add the $obj1 (Organization) to the collection in $obj5 (Region)
                $obj5->addOrganization($obj1);
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
        $criteria->setPrimaryTableName(OrganizationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            OrganizationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(OrganizationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(OrganizationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(OrganizationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $criteria->addJoin(OrganizationPeer::REGION_ID, RegionPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(OrganizationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            OrganizationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(OrganizationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(OrganizationPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(OrganizationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $criteria->addJoin(OrganizationPeer::REGION_ID, RegionPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(OrganizationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            OrganizationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(OrganizationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(OrganizationPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(OrganizationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(OrganizationPeer::REGION_ID, RegionPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Region table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptRegion(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(OrganizationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            OrganizationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(OrganizationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(OrganizationPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(OrganizationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(OrganizationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

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
     * Selects a collection of Organization objects pre-filled with all related objects except User.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Organization objects.
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
            $criteria->setDbName(OrganizationPeer::DATABASE_NAME);
        }

        OrganizationPeer::addSelectColumns($criteria);
        $startcol2 = OrganizationPeer::NUM_HYDRATE_COLUMNS;

        StatePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + StatePeer::NUM_HYDRATE_COLUMNS;

        CountryPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + CountryPeer::NUM_HYDRATE_COLUMNS;

        RegionPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + RegionPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(OrganizationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(OrganizationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $criteria->addJoin(OrganizationPeer::REGION_ID, RegionPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = OrganizationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = OrganizationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = OrganizationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                OrganizationPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Organization) to the collection in $obj2 (State)
                $obj2->addOrganization($obj1);

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

                // Add the $obj1 (Organization) to the collection in $obj3 (Country)
                $obj3->addOrganization($obj1);

            } // if joined row is not null

                // Add objects for joined Region rows

                $key4 = RegionPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = RegionPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = RegionPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    RegionPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Organization) to the collection in $obj4 (Region)
                $obj4->addOrganization($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Organization objects pre-filled with all related objects except State.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Organization objects.
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
            $criteria->setDbName(OrganizationPeer::DATABASE_NAME);
        }

        OrganizationPeer::addSelectColumns($criteria);
        $startcol2 = OrganizationPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserPeer::NUM_HYDRATE_COLUMNS;

        CountryPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + CountryPeer::NUM_HYDRATE_COLUMNS;

        RegionPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + RegionPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(OrganizationPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(OrganizationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $criteria->addJoin(OrganizationPeer::REGION_ID, RegionPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = OrganizationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = OrganizationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = OrganizationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                OrganizationPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Organization) to the collection in $obj2 (User)
                $obj2->addOrganization($obj1);

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

                // Add the $obj1 (Organization) to the collection in $obj3 (Country)
                $obj3->addOrganization($obj1);

            } // if joined row is not null

                // Add objects for joined Region rows

                $key4 = RegionPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = RegionPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = RegionPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    RegionPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Organization) to the collection in $obj4 (Region)
                $obj4->addOrganization($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Organization objects pre-filled with all related objects except Country.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Organization objects.
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
            $criteria->setDbName(OrganizationPeer::DATABASE_NAME);
        }

        OrganizationPeer::addSelectColumns($criteria);
        $startcol2 = OrganizationPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserPeer::NUM_HYDRATE_COLUMNS;

        StatePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + StatePeer::NUM_HYDRATE_COLUMNS;

        RegionPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + RegionPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(OrganizationPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(OrganizationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(OrganizationPeer::REGION_ID, RegionPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = OrganizationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = OrganizationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = OrganizationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                OrganizationPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Organization) to the collection in $obj2 (User)
                $obj2->addOrganization($obj1);

            } // if joined row is not null

                // Add objects for joined State rows

                $key3 = StatePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = StatePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = StatePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    StatePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Organization) to the collection in $obj3 (State)
                $obj3->addOrganization($obj1);

            } // if joined row is not null

                // Add objects for joined Region rows

                $key4 = RegionPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = RegionPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = RegionPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    RegionPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Organization) to the collection in $obj4 (Region)
                $obj4->addOrganization($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Organization objects pre-filled with all related objects except Region.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Organization objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptRegion(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(OrganizationPeer::DATABASE_NAME);
        }

        OrganizationPeer::addSelectColumns($criteria);
        $startcol2 = OrganizationPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserPeer::NUM_HYDRATE_COLUMNS;

        StatePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + StatePeer::NUM_HYDRATE_COLUMNS;

        CountryPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + CountryPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(OrganizationPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(OrganizationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(OrganizationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = OrganizationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = OrganizationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = OrganizationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                OrganizationPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Organization) to the collection in $obj2 (User)
                $obj2->addOrganization($obj1);

            } // if joined row is not null

                // Add objects for joined State rows

                $key3 = StatePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = StatePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = StatePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    StatePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Organization) to the collection in $obj3 (State)
                $obj3->addOrganization($obj1);

            } // if joined row is not null

                // Add objects for joined Country rows

                $key4 = CountryPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = CountryPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = CountryPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    CountryPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Organization) to the collection in $obj4 (Country)
                $obj4->addOrganization($obj1);

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
        return Propel::getDatabaseMap(OrganizationPeer::DATABASE_NAME)->getTable(OrganizationPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseOrganizationPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseOrganizationPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \PGS\CoreDomainBundle\Model\Organization\map\OrganizationTableMap());
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

        $event = new DetectOMClassEvent(OrganizationPeer::OM_CLASS, $row, $colnum);
        EventDispatcherProxy::trigger('om.detect', $event);
        if($event->isDetected()){
            return $event->getDetectedClass();
        }

        return OrganizationPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a Organization or Criteria object.
     *
     * @param      mixed $values Criteria or Organization object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from Organization object
        }

        if ($criteria->containsKey(OrganizationPeer::ID) && $criteria->keyContainsValue(OrganizationPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.OrganizationPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(OrganizationPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a Organization or Criteria object.
     *
     * @param      mixed $values Criteria or Organization object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(OrganizationPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(OrganizationPeer::ID);
            $value = $criteria->remove(OrganizationPeer::ID);
            if ($value) {
                $selectCriteria->add(OrganizationPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(OrganizationPeer::TABLE_NAME);
            }

        } else { // $values is Organization object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(OrganizationPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the organization table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(OrganizationPeer::TABLE_NAME, $con, OrganizationPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            OrganizationPeer::clearInstancePool();
            OrganizationPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a Organization or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or Organization object or primary key or array of primary keys
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
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            OrganizationPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof Organization) { // it's a model object
            // invalidate the cache for this single object
            OrganizationPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(OrganizationPeer::DATABASE_NAME);
            $criteria->add(OrganizationPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                OrganizationPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(OrganizationPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            OrganizationPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given Organization object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param Organization $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(OrganizationPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(OrganizationPeer::TABLE_NAME);

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

        return BasePeer::doValidate(OrganizationPeer::DATABASE_NAME, OrganizationPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return Organization
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = OrganizationPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(OrganizationPeer::DATABASE_NAME);
        $criteria->add(OrganizationPeer::ID, $pk);

        $v = OrganizationPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return Organization[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(OrganizationPeer::DATABASE_NAME);
            $criteria->add(OrganizationPeer::ID, $pks, Criteria::IN);
            $objs = OrganizationPeer::doSelect($criteria, $con);
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
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $c = new Criteria();
        $c->addSelectColumn('MAX(' . OrganizationPeer::RANK_COL . ')');
        $stmt = OrganizationPeer::doSelectStmt($c, $con);

        return $stmt->fetchColumn();
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     PropelPDO $con optional connection
     *
     * @return Organization
     */
    public static function retrieveByRank($rank, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME);
        }

        $c = new Criteria;
        $c->add(OrganizationPeer::RANK_COL, $rank);

        return OrganizationPeer::doSelectOne($c, $con);
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
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $ids = array_keys($order);
            $objects = OrganizationPeer::retrieveByPKs($ids);
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
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME);
        }

        if ($criteria === null) {
            $criteria = new Criteria();
        } elseif ($criteria instanceof Criteria) {
            $criteria = clone $criteria;
        }

        $criteria->clearOrderByColumns();

        if ($order == Criteria::ASC) {
            $criteria->addAscendingOrderByColumn(OrganizationPeer::RANK_COL);
        } else {
            $criteria->addDescendingOrderByColumn(OrganizationPeer::RANK_COL);
        }

        return OrganizationPeer::doSelect($criteria, $con);
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
            $con = Propel::getConnection(OrganizationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $whereCriteria = OrganizationQuery::create();
        if (null !== $first) {
            $whereCriteria->add(OrganizationPeer::RANK_COL, $first, Criteria::GREATER_EQUAL);
        }
        if (null !== $last) {
            $whereCriteria->addAnd(OrganizationPeer::RANK_COL, $last, Criteria::LESS_EQUAL);
        }

        $valuesCriteria = new Criteria(OrganizationPeer::DATABASE_NAME);
        $valuesCriteria->add(OrganizationPeer::RANK_COL, array('raw' => OrganizationPeer::RANK_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
        OrganizationPeer::clearInstancePool();
    }

} // BaseOrganizationPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseOrganizationPeer::buildTableMap();

EventDispatcherProxy::trigger(array('construct','peer.construct'), new PeerEvent('PGS\CoreDomainBundle\Model\Organization\om\BaseOrganizationPeer'));
