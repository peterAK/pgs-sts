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
use PGS\CoreDomainBundle\Model\Organization\OrganizationArchive;
use PGS\CoreDomainBundle\Model\Organization\OrganizationArchivePeer;
use PGS\CoreDomainBundle\Model\Organization\map\OrganizationArchiveTableMap;

abstract class BaseOrganizationArchivePeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'organization_archive';

    /** the related Propel class for this table */
    const OM_CLASS = 'PGS\\CoreDomainBundle\\Model\\Organization\\OrganizationArchive';

    /** the related TableMap class for this table */
    const TM_CLASS = 'PGS\\CoreDomainBundle\\Model\\Organization\\map\\OrganizationArchiveTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 26;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 26;

    /** the column name for the id field */
    const ID = 'organization_archive.id';

    /** the column name for the user_id field */
    const USER_ID = 'organization_archive.user_id';

    /** the column name for the name field */
    const NAME = 'organization_archive.name';

    /** the column name for the url field */
    const URL = 'organization_archive.url';

    /** the column name for the description field */
    const DESCRIPTION = 'organization_archive.description';

    /** the column name for the excerpt field */
    const EXCERPT = 'organization_archive.excerpt';

    /** the column name for the goverment_license field */
    const GOVERMENT_LICENSE = 'organization_archive.goverment_license';

    /** the column name for the establish_at field */
    const ESTABLISH_AT = 'organization_archive.establish_at';

    /** the column name for the address1 field */
    const ADDRESS1 = 'organization_archive.address1';

    /** the column name for the address2 field */
    const ADDRESS2 = 'organization_archive.address2';

    /** the column name for the city field */
    const CITY = 'organization_archive.city';

    /** the column name for the state_id field */
    const STATE_ID = 'organization_archive.state_id';

    /** the column name for the zipcode field */
    const ZIPCODE = 'organization_archive.zipcode';

    /** the column name for the country_id field */
    const COUNTRY_ID = 'organization_archive.country_id';

    /** the column name for the phone field */
    const PHONE = 'organization_archive.phone';

    /** the column name for the fax field */
    const FAX = 'organization_archive.fax';

    /** the column name for the mobile field */
    const MOBILE = 'organization_archive.mobile';

    /** the column name for the email field */
    const EMAIL = 'organization_archive.email';

    /** the column name for the website field */
    const WEBSITE = 'organization_archive.website';

    /** the column name for the logo field */
    const LOGO = 'organization_archive.logo';

    /** the column name for the status field */
    const STATUS = 'organization_archive.status';

    /** the column name for the confirmation field */
    const CONFIRMATION = 'organization_archive.confirmation';

    /** the column name for the sortable_rank field */
    const SORTABLE_RANK = 'organization_archive.sortable_rank';

    /** the column name for the created_at field */
    const CREATED_AT = 'organization_archive.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'organization_archive.updated_at';

    /** the column name for the archived_at field */
    const ARCHIVED_AT = 'organization_archive.archived_at';

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
     * An identity map to hold any loaded instances of OrganizationArchive objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array OrganizationArchive[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. OrganizationArchivePeer::$fieldNames[OrganizationArchivePeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'UserId', 'Name', 'Url', 'Description', 'Excerpt', 'GovermentLicense', 'EstablishAt', 'Address1', 'Address2', 'City', 'StateId', 'Zipcode', 'CountryId', 'Phone', 'Fax', 'Mobile', 'Email', 'Website', 'Logo', 'Status', 'Confirmation', 'SortableRank', 'CreatedAt', 'UpdatedAt', 'ArchivedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'userId', 'name', 'url', 'description', 'excerpt', 'govermentLicense', 'establishAt', 'address1', 'address2', 'city', 'stateId', 'zipcode', 'countryId', 'phone', 'fax', 'mobile', 'email', 'website', 'logo', 'status', 'confirmation', 'sortableRank', 'createdAt', 'updatedAt', 'archivedAt', ),
        BasePeer::TYPE_COLNAME => array (OrganizationArchivePeer::ID, OrganizationArchivePeer::USER_ID, OrganizationArchivePeer::NAME, OrganizationArchivePeer::URL, OrganizationArchivePeer::DESCRIPTION, OrganizationArchivePeer::EXCERPT, OrganizationArchivePeer::GOVERMENT_LICENSE, OrganizationArchivePeer::ESTABLISH_AT, OrganizationArchivePeer::ADDRESS1, OrganizationArchivePeer::ADDRESS2, OrganizationArchivePeer::CITY, OrganizationArchivePeer::STATE_ID, OrganizationArchivePeer::ZIPCODE, OrganizationArchivePeer::COUNTRY_ID, OrganizationArchivePeer::PHONE, OrganizationArchivePeer::FAX, OrganizationArchivePeer::MOBILE, OrganizationArchivePeer::EMAIL, OrganizationArchivePeer::WEBSITE, OrganizationArchivePeer::LOGO, OrganizationArchivePeer::STATUS, OrganizationArchivePeer::CONFIRMATION, OrganizationArchivePeer::SORTABLE_RANK, OrganizationArchivePeer::CREATED_AT, OrganizationArchivePeer::UPDATED_AT, OrganizationArchivePeer::ARCHIVED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'USER_ID', 'NAME', 'URL', 'DESCRIPTION', 'EXCERPT', 'GOVERMENT_LICENSE', 'ESTABLISH_AT', 'ADDRESS1', 'ADDRESS2', 'CITY', 'STATE_ID', 'ZIPCODE', 'COUNTRY_ID', 'PHONE', 'FAX', 'MOBILE', 'EMAIL', 'WEBSITE', 'LOGO', 'STATUS', 'CONFIRMATION', 'SORTABLE_RANK', 'CREATED_AT', 'UPDATED_AT', 'ARCHIVED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'user_id', 'name', 'url', 'description', 'excerpt', 'goverment_license', 'establish_at', 'address1', 'address2', 'city', 'state_id', 'zipcode', 'country_id', 'phone', 'fax', 'mobile', 'email', 'website', 'logo', 'status', 'confirmation', 'sortable_rank', 'created_at', 'updated_at', 'archived_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. OrganizationArchivePeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'UserId' => 1, 'Name' => 2, 'Url' => 3, 'Description' => 4, 'Excerpt' => 5, 'GovermentLicense' => 6, 'EstablishAt' => 7, 'Address1' => 8, 'Address2' => 9, 'City' => 10, 'StateId' => 11, 'Zipcode' => 12, 'CountryId' => 13, 'Phone' => 14, 'Fax' => 15, 'Mobile' => 16, 'Email' => 17, 'Website' => 18, 'Logo' => 19, 'Status' => 20, 'Confirmation' => 21, 'SortableRank' => 22, 'CreatedAt' => 23, 'UpdatedAt' => 24, 'ArchivedAt' => 25, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'userId' => 1, 'name' => 2, 'url' => 3, 'description' => 4, 'excerpt' => 5, 'govermentLicense' => 6, 'establishAt' => 7, 'address1' => 8, 'address2' => 9, 'city' => 10, 'stateId' => 11, 'zipcode' => 12, 'countryId' => 13, 'phone' => 14, 'fax' => 15, 'mobile' => 16, 'email' => 17, 'website' => 18, 'logo' => 19, 'status' => 20, 'confirmation' => 21, 'sortableRank' => 22, 'createdAt' => 23, 'updatedAt' => 24, 'archivedAt' => 25, ),
        BasePeer::TYPE_COLNAME => array (OrganizationArchivePeer::ID => 0, OrganizationArchivePeer::USER_ID => 1, OrganizationArchivePeer::NAME => 2, OrganizationArchivePeer::URL => 3, OrganizationArchivePeer::DESCRIPTION => 4, OrganizationArchivePeer::EXCERPT => 5, OrganizationArchivePeer::GOVERMENT_LICENSE => 6, OrganizationArchivePeer::ESTABLISH_AT => 7, OrganizationArchivePeer::ADDRESS1 => 8, OrganizationArchivePeer::ADDRESS2 => 9, OrganizationArchivePeer::CITY => 10, OrganizationArchivePeer::STATE_ID => 11, OrganizationArchivePeer::ZIPCODE => 12, OrganizationArchivePeer::COUNTRY_ID => 13, OrganizationArchivePeer::PHONE => 14, OrganizationArchivePeer::FAX => 15, OrganizationArchivePeer::MOBILE => 16, OrganizationArchivePeer::EMAIL => 17, OrganizationArchivePeer::WEBSITE => 18, OrganizationArchivePeer::LOGO => 19, OrganizationArchivePeer::STATUS => 20, OrganizationArchivePeer::CONFIRMATION => 21, OrganizationArchivePeer::SORTABLE_RANK => 22, OrganizationArchivePeer::CREATED_AT => 23, OrganizationArchivePeer::UPDATED_AT => 24, OrganizationArchivePeer::ARCHIVED_AT => 25, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'USER_ID' => 1, 'NAME' => 2, 'URL' => 3, 'DESCRIPTION' => 4, 'EXCERPT' => 5, 'GOVERMENT_LICENSE' => 6, 'ESTABLISH_AT' => 7, 'ADDRESS1' => 8, 'ADDRESS2' => 9, 'CITY' => 10, 'STATE_ID' => 11, 'ZIPCODE' => 12, 'COUNTRY_ID' => 13, 'PHONE' => 14, 'FAX' => 15, 'MOBILE' => 16, 'EMAIL' => 17, 'WEBSITE' => 18, 'LOGO' => 19, 'STATUS' => 20, 'CONFIRMATION' => 21, 'SORTABLE_RANK' => 22, 'CREATED_AT' => 23, 'UPDATED_AT' => 24, 'ARCHIVED_AT' => 25, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'user_id' => 1, 'name' => 2, 'url' => 3, 'description' => 4, 'excerpt' => 5, 'goverment_license' => 6, 'establish_at' => 7, 'address1' => 8, 'address2' => 9, 'city' => 10, 'state_id' => 11, 'zipcode' => 12, 'country_id' => 13, 'phone' => 14, 'fax' => 15, 'mobile' => 16, 'email' => 17, 'website' => 18, 'logo' => 19, 'status' => 20, 'confirmation' => 21, 'sortable_rank' => 22, 'created_at' => 23, 'updated_at' => 24, 'archived_at' => 25, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
        OrganizationArchivePeer::STATUS => array(
            OrganizationArchivePeer::STATUS_NEW,
            OrganizationArchivePeer::STATUS_ACTIVE,
            OrganizationArchivePeer::STATUS_INACTIVE,
            OrganizationArchivePeer::STATUS_BANNED,
        ),
        OrganizationArchivePeer::CONFIRMATION => array(
            OrganizationArchivePeer::CONFIRMATION_NEW,
            OrganizationArchivePeer::CONFIRMATION_PHONE,
            OrganizationArchivePeer::CONFIRMATION_LETTERS,
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
        $toNames = OrganizationArchivePeer::getFieldNames($toType);
        $key = isset(OrganizationArchivePeer::$fieldKeys[$fromType][$name]) ? OrganizationArchivePeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(OrganizationArchivePeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, OrganizationArchivePeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return OrganizationArchivePeer::$fieldNames[$type];
    }

    /**
     * Gets the list of values for all ENUM columns
     * @return array
     */
    public static function getValueSets()
    {
      return OrganizationArchivePeer::$enumValueSets;
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
        $valueSets = OrganizationArchivePeer::getValueSets();

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
        $values = OrganizationArchivePeer::getValueSet($colname);
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
     * @param      string $column The column name for current table. (i.e. OrganizationArchivePeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(OrganizationArchivePeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(OrganizationArchivePeer::ID);
            $criteria->addSelectColumn(OrganizationArchivePeer::USER_ID);
            $criteria->addSelectColumn(OrganizationArchivePeer::NAME);
            $criteria->addSelectColumn(OrganizationArchivePeer::URL);
            $criteria->addSelectColumn(OrganizationArchivePeer::DESCRIPTION);
            $criteria->addSelectColumn(OrganizationArchivePeer::EXCERPT);
            $criteria->addSelectColumn(OrganizationArchivePeer::GOVERMENT_LICENSE);
            $criteria->addSelectColumn(OrganizationArchivePeer::ESTABLISH_AT);
            $criteria->addSelectColumn(OrganizationArchivePeer::ADDRESS1);
            $criteria->addSelectColumn(OrganizationArchivePeer::ADDRESS2);
            $criteria->addSelectColumn(OrganizationArchivePeer::CITY);
            $criteria->addSelectColumn(OrganizationArchivePeer::STATE_ID);
            $criteria->addSelectColumn(OrganizationArchivePeer::ZIPCODE);
            $criteria->addSelectColumn(OrganizationArchivePeer::COUNTRY_ID);
            $criteria->addSelectColumn(OrganizationArchivePeer::PHONE);
            $criteria->addSelectColumn(OrganizationArchivePeer::FAX);
            $criteria->addSelectColumn(OrganizationArchivePeer::MOBILE);
            $criteria->addSelectColumn(OrganizationArchivePeer::EMAIL);
            $criteria->addSelectColumn(OrganizationArchivePeer::WEBSITE);
            $criteria->addSelectColumn(OrganizationArchivePeer::LOGO);
            $criteria->addSelectColumn(OrganizationArchivePeer::STATUS);
            $criteria->addSelectColumn(OrganizationArchivePeer::CONFIRMATION);
            $criteria->addSelectColumn(OrganizationArchivePeer::SORTABLE_RANK);
            $criteria->addSelectColumn(OrganizationArchivePeer::CREATED_AT);
            $criteria->addSelectColumn(OrganizationArchivePeer::UPDATED_AT);
            $criteria->addSelectColumn(OrganizationArchivePeer::ARCHIVED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.url');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.excerpt');
            $criteria->addSelectColumn($alias . '.goverment_license');
            $criteria->addSelectColumn($alias . '.establish_at');
            $criteria->addSelectColumn($alias . '.address1');
            $criteria->addSelectColumn($alias . '.address2');
            $criteria->addSelectColumn($alias . '.city');
            $criteria->addSelectColumn($alias . '.state_id');
            $criteria->addSelectColumn($alias . '.zipcode');
            $criteria->addSelectColumn($alias . '.country_id');
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
        $criteria->setPrimaryTableName(OrganizationArchivePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            OrganizationArchivePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(OrganizationArchivePeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(OrganizationArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return OrganizationArchive
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = OrganizationArchivePeer::doSelect($critcopy, $con);
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
        return OrganizationArchivePeer::populateObjects(OrganizationArchivePeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(OrganizationArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            OrganizationArchivePeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(OrganizationArchivePeer::DATABASE_NAME);

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
     * @param OrganizationArchive $obj A OrganizationArchive object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            OrganizationArchivePeer::$instances[$key] = $obj;
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
     * @param      mixed $value A OrganizationArchive object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof OrganizationArchive) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or OrganizationArchive object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(OrganizationArchivePeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return OrganizationArchive Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(OrganizationArchivePeer::$instances[$key])) {
                return OrganizationArchivePeer::$instances[$key];
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
        foreach (OrganizationArchivePeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        OrganizationArchivePeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to organization_archive
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
        $cls = OrganizationArchivePeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = OrganizationArchivePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = OrganizationArchivePeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                OrganizationArchivePeer::addInstanceToPool($obj, $key);
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
     * @return array (OrganizationArchive object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = OrganizationArchivePeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = OrganizationArchivePeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + OrganizationArchivePeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = OrganizationArchivePeer::getOMClass($row, $startcol);
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            OrganizationArchivePeer::addInstanceToPool($obj, $key);
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
        return OrganizationArchivePeer::getSqlValueForEnum(OrganizationArchivePeer::STATUS, $enumVal);
    }

    /**
     * Gets the SQL value for Confirmation ENUM value
     *
     * @param  string $enumVal ENUM value to get SQL value for
     * @return int SQL value
     */
    public static function getConfirmationSqlValue($enumVal)
    {
        return OrganizationArchivePeer::getSqlValueForEnum(OrganizationArchivePeer::CONFIRMATION, $enumVal);
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
        return Propel::getDatabaseMap(OrganizationArchivePeer::DATABASE_NAME)->getTable(OrganizationArchivePeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseOrganizationArchivePeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseOrganizationArchivePeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \PGS\CoreDomainBundle\Model\Organization\map\OrganizationArchiveTableMap());
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

        $event = new DetectOMClassEvent(OrganizationArchivePeer::OM_CLASS, $row, $colnum);
        EventDispatcherProxy::trigger('om.detect', $event);
        if($event->isDetected()){
            return $event->getDetectedClass();
        }

        return OrganizationArchivePeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a OrganizationArchive or Criteria object.
     *
     * @param      mixed $values Criteria or OrganizationArchive object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(OrganizationArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from OrganizationArchive object
        }


        // Set the correct dbName
        $criteria->setDbName(OrganizationArchivePeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a OrganizationArchive or Criteria object.
     *
     * @param      mixed $values Criteria or OrganizationArchive object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(OrganizationArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(OrganizationArchivePeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(OrganizationArchivePeer::ID);
            $value = $criteria->remove(OrganizationArchivePeer::ID);
            if ($value) {
                $selectCriteria->add(OrganizationArchivePeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(OrganizationArchivePeer::TABLE_NAME);
            }

        } else { // $values is OrganizationArchive object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(OrganizationArchivePeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the organization_archive table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(OrganizationArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(OrganizationArchivePeer::TABLE_NAME, $con, OrganizationArchivePeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            OrganizationArchivePeer::clearInstancePool();
            OrganizationArchivePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a OrganizationArchive or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or OrganizationArchive object or primary key or array of primary keys
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
            $con = Propel::getConnection(OrganizationArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            OrganizationArchivePeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof OrganizationArchive) { // it's a model object
            // invalidate the cache for this single object
            OrganizationArchivePeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(OrganizationArchivePeer::DATABASE_NAME);
            $criteria->add(OrganizationArchivePeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                OrganizationArchivePeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(OrganizationArchivePeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            OrganizationArchivePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given OrganizationArchive object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param OrganizationArchive $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(OrganizationArchivePeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(OrganizationArchivePeer::TABLE_NAME);

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

        return BasePeer::doValidate(OrganizationArchivePeer::DATABASE_NAME, OrganizationArchivePeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return OrganizationArchive
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = OrganizationArchivePeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(OrganizationArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(OrganizationArchivePeer::DATABASE_NAME);
        $criteria->add(OrganizationArchivePeer::ID, $pk);

        $v = OrganizationArchivePeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return OrganizationArchive[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(OrganizationArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(OrganizationArchivePeer::DATABASE_NAME);
            $criteria->add(OrganizationArchivePeer::ID, $pks, Criteria::IN);
            $objs = OrganizationArchivePeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseOrganizationArchivePeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseOrganizationArchivePeer::buildTableMap();

EventDispatcherProxy::trigger(array('construct','peer.construct'), new PeerEvent('PGS\CoreDomainBundle\Model\Organization\om\BaseOrganizationArchivePeer'));
