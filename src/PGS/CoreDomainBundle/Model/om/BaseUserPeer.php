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
use PGS\CoreDomainBundle\Model\PagePeer;
use PGS\CoreDomainBundle\Model\User;
use PGS\CoreDomainBundle\Model\UserLogPeer;
use PGS\CoreDomainBundle\Model\UserPeer;
use PGS\CoreDomainBundle\Model\UserProfilePeer;
use PGS\CoreDomainBundle\Model\Announcement\AnnouncementPeer;
use PGS\CoreDomainBundle\Model\Application\ApplicationPeer;
use PGS\CoreDomainBundle\Model\Behavior\BehaviorPeer;
use PGS\CoreDomainBundle\Model\Category\CategoryPeer;
use PGS\CoreDomainBundle\Model\Employee\EmployeePeer;
use PGS\CoreDomainBundle\Model\Ethnicity\EthnicityPeer;
use PGS\CoreDomainBundle\Model\Message\MessagePeer;
use PGS\CoreDomainBundle\Model\Organization\OrganizationPeer;
use PGS\CoreDomainBundle\Model\ParentStudent\ParentStudentPeer;
use PGS\CoreDomainBundle\Model\Religion\ReligionPeer;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCoursePeer;
use PGS\CoreDomainBundle\Model\Student\StudentPeer;
use PGS\CoreDomainBundle\Model\StudentAvatar\StudentAvatarPeer;
use PGS\CoreDomainBundle\Model\Test\TestPeer;
use PGS\CoreDomainBundle\Model\map\UserTableMap;

abstract class BaseUserPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'fos_user';

    /** the related Propel class for this table */
    const OM_CLASS = 'PGS\\CoreDomainBundle\\Model\\User';

    /** the related TableMap class for this table */
    const TM_CLASS = 'PGS\\CoreDomainBundle\\Model\\map\\UserTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 21;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 21;

    /** the column name for the id field */
    const ID = 'fos_user.id';

    /** the column name for the username field */
    const USERNAME = 'fos_user.username';

    /** the column name for the username_canonical field */
    const USERNAME_CANONICAL = 'fos_user.username_canonical';

    /** the column name for the email field */
    const EMAIL = 'fos_user.email';

    /** the column name for the email_canonical field */
    const EMAIL_CANONICAL = 'fos_user.email_canonical';

    /** the column name for the enabled field */
    const ENABLED = 'fos_user.enabled';

    /** the column name for the salt field */
    const SALT = 'fos_user.salt';

    /** the column name for the password field */
    const PASSWORD = 'fos_user.password';

    /** the column name for the last_login field */
    const LAST_LOGIN = 'fos_user.last_login';

    /** the column name for the locked field */
    const LOCKED = 'fos_user.locked';

    /** the column name for the expired field */
    const EXPIRED = 'fos_user.expired';

    /** the column name for the expires_at field */
    const EXPIRES_AT = 'fos_user.expires_at';

    /** the column name for the confirmation_token field */
    const CONFIRMATION_TOKEN = 'fos_user.confirmation_token';

    /** the column name for the password_requested_at field */
    const PASSWORD_REQUESTED_AT = 'fos_user.password_requested_at';

    /** the column name for the credentials_expired field */
    const CREDENTIALS_EXPIRED = 'fos_user.credentials_expired';

    /** the column name for the credentials_expire_at field */
    const CREDENTIALS_EXPIRE_AT = 'fos_user.credentials_expire_at';

    /** the column name for the type field */
    const TYPE = 'fos_user.type';

    /** the column name for the status field */
    const STATUS = 'fos_user.status';

    /** the column name for the roles field */
    const ROLES = 'fos_user.roles';

    /** the column name for the created_at field */
    const CREATED_AT = 'fos_user.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'fos_user.updated_at';

    /** The enumerated values for the type field */
    const TYPE_ADMIN = 'admin';
    const TYPE_PRINCIPAL = 'principal';
    const TYPE_COUNSELOR = 'counselor';
    const TYPE_TEACHER = 'teacher';
    const TYPE_PARENT = 'parent';
    const TYPE_STUDENT = 'student';

    /** The enumerated values for the status field */
    const STATUS_NEW = 'new';
    const STATUS_APPROVED = 'approved';
    const STATUS_DORMANT = 'dormant';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of User objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array User[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. UserPeer::$fieldNames[UserPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Username', 'UsernameCanonical', 'Email', 'EmailCanonical', 'Enabled', 'Salt', 'Password', 'LastLogin', 'Locked', 'Expired', 'ExpiresAt', 'ConfirmationToken', 'PasswordRequestedAt', 'CredentialsExpired', 'CredentialsExpireAt', 'Type', 'Status', 'Roles', 'CreatedAt', 'UpdatedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'username', 'usernameCanonical', 'email', 'emailCanonical', 'enabled', 'salt', 'password', 'lastLogin', 'locked', 'expired', 'expiresAt', 'confirmationToken', 'passwordRequestedAt', 'credentialsExpired', 'credentialsExpireAt', 'type', 'status', 'roles', 'createdAt', 'updatedAt', ),
        BasePeer::TYPE_COLNAME => array (UserPeer::ID, UserPeer::USERNAME, UserPeer::USERNAME_CANONICAL, UserPeer::EMAIL, UserPeer::EMAIL_CANONICAL, UserPeer::ENABLED, UserPeer::SALT, UserPeer::PASSWORD, UserPeer::LAST_LOGIN, UserPeer::LOCKED, UserPeer::EXPIRED, UserPeer::EXPIRES_AT, UserPeer::CONFIRMATION_TOKEN, UserPeer::PASSWORD_REQUESTED_AT, UserPeer::CREDENTIALS_EXPIRED, UserPeer::CREDENTIALS_EXPIRE_AT, UserPeer::TYPE, UserPeer::STATUS, UserPeer::ROLES, UserPeer::CREATED_AT, UserPeer::UPDATED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'USERNAME', 'USERNAME_CANONICAL', 'EMAIL', 'EMAIL_CANONICAL', 'ENABLED', 'SALT', 'PASSWORD', 'LAST_LOGIN', 'LOCKED', 'EXPIRED', 'EXPIRES_AT', 'CONFIRMATION_TOKEN', 'PASSWORD_REQUESTED_AT', 'CREDENTIALS_EXPIRED', 'CREDENTIALS_EXPIRE_AT', 'TYPE', 'STATUS', 'ROLES', 'CREATED_AT', 'UPDATED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'username', 'username_canonical', 'email', 'email_canonical', 'enabled', 'salt', 'password', 'last_login', 'locked', 'expired', 'expires_at', 'confirmation_token', 'password_requested_at', 'credentials_expired', 'credentials_expire_at', 'type', 'status', 'roles', 'created_at', 'updated_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. UserPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Username' => 1, 'UsernameCanonical' => 2, 'Email' => 3, 'EmailCanonical' => 4, 'Enabled' => 5, 'Salt' => 6, 'Password' => 7, 'LastLogin' => 8, 'Locked' => 9, 'Expired' => 10, 'ExpiresAt' => 11, 'ConfirmationToken' => 12, 'PasswordRequestedAt' => 13, 'CredentialsExpired' => 14, 'CredentialsExpireAt' => 15, 'Type' => 16, 'Status' => 17, 'Roles' => 18, 'CreatedAt' => 19, 'UpdatedAt' => 20, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'username' => 1, 'usernameCanonical' => 2, 'email' => 3, 'emailCanonical' => 4, 'enabled' => 5, 'salt' => 6, 'password' => 7, 'lastLogin' => 8, 'locked' => 9, 'expired' => 10, 'expiresAt' => 11, 'confirmationToken' => 12, 'passwordRequestedAt' => 13, 'credentialsExpired' => 14, 'credentialsExpireAt' => 15, 'type' => 16, 'status' => 17, 'roles' => 18, 'createdAt' => 19, 'updatedAt' => 20, ),
        BasePeer::TYPE_COLNAME => array (UserPeer::ID => 0, UserPeer::USERNAME => 1, UserPeer::USERNAME_CANONICAL => 2, UserPeer::EMAIL => 3, UserPeer::EMAIL_CANONICAL => 4, UserPeer::ENABLED => 5, UserPeer::SALT => 6, UserPeer::PASSWORD => 7, UserPeer::LAST_LOGIN => 8, UserPeer::LOCKED => 9, UserPeer::EXPIRED => 10, UserPeer::EXPIRES_AT => 11, UserPeer::CONFIRMATION_TOKEN => 12, UserPeer::PASSWORD_REQUESTED_AT => 13, UserPeer::CREDENTIALS_EXPIRED => 14, UserPeer::CREDENTIALS_EXPIRE_AT => 15, UserPeer::TYPE => 16, UserPeer::STATUS => 17, UserPeer::ROLES => 18, UserPeer::CREATED_AT => 19, UserPeer::UPDATED_AT => 20, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'USERNAME' => 1, 'USERNAME_CANONICAL' => 2, 'EMAIL' => 3, 'EMAIL_CANONICAL' => 4, 'ENABLED' => 5, 'SALT' => 6, 'PASSWORD' => 7, 'LAST_LOGIN' => 8, 'LOCKED' => 9, 'EXPIRED' => 10, 'EXPIRES_AT' => 11, 'CONFIRMATION_TOKEN' => 12, 'PASSWORD_REQUESTED_AT' => 13, 'CREDENTIALS_EXPIRED' => 14, 'CREDENTIALS_EXPIRE_AT' => 15, 'TYPE' => 16, 'STATUS' => 17, 'ROLES' => 18, 'CREATED_AT' => 19, 'UPDATED_AT' => 20, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'username' => 1, 'username_canonical' => 2, 'email' => 3, 'email_canonical' => 4, 'enabled' => 5, 'salt' => 6, 'password' => 7, 'last_login' => 8, 'locked' => 9, 'expired' => 10, 'expires_at' => 11, 'confirmation_token' => 12, 'password_requested_at' => 13, 'credentials_expired' => 14, 'credentials_expire_at' => 15, 'type' => 16, 'status' => 17, 'roles' => 18, 'created_at' => 19, 'updated_at' => 20, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
        UserPeer::TYPE => array(
            UserPeer::TYPE_ADMIN,
            UserPeer::TYPE_PRINCIPAL,
            UserPeer::TYPE_COUNSELOR,
            UserPeer::TYPE_TEACHER,
            UserPeer::TYPE_PARENT,
            UserPeer::TYPE_STUDENT,
        ),
        UserPeer::STATUS => array(
            UserPeer::STATUS_NEW,
            UserPeer::STATUS_APPROVED,
            UserPeer::STATUS_DORMANT,
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
        $toNames = UserPeer::getFieldNames($toType);
        $key = isset(UserPeer::$fieldKeys[$fromType][$name]) ? UserPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(UserPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, UserPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return UserPeer::$fieldNames[$type];
    }

    /**
     * Gets the list of values for all ENUM columns
     * @return array
     */
    public static function getValueSets()
    {
      return UserPeer::$enumValueSets;
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
        $valueSets = UserPeer::getValueSets();

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
        $values = UserPeer::getValueSet($colname);
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
     * @param      string $column The column name for current table. (i.e. UserPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(UserPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(UserPeer::ID);
            $criteria->addSelectColumn(UserPeer::USERNAME);
            $criteria->addSelectColumn(UserPeer::USERNAME_CANONICAL);
            $criteria->addSelectColumn(UserPeer::EMAIL);
            $criteria->addSelectColumn(UserPeer::EMAIL_CANONICAL);
            $criteria->addSelectColumn(UserPeer::ENABLED);
            $criteria->addSelectColumn(UserPeer::SALT);
            $criteria->addSelectColumn(UserPeer::PASSWORD);
            $criteria->addSelectColumn(UserPeer::LAST_LOGIN);
            $criteria->addSelectColumn(UserPeer::LOCKED);
            $criteria->addSelectColumn(UserPeer::EXPIRED);
            $criteria->addSelectColumn(UserPeer::EXPIRES_AT);
            $criteria->addSelectColumn(UserPeer::CONFIRMATION_TOKEN);
            $criteria->addSelectColumn(UserPeer::PASSWORD_REQUESTED_AT);
            $criteria->addSelectColumn(UserPeer::CREDENTIALS_EXPIRED);
            $criteria->addSelectColumn(UserPeer::CREDENTIALS_EXPIRE_AT);
            $criteria->addSelectColumn(UserPeer::TYPE);
            $criteria->addSelectColumn(UserPeer::STATUS);
            $criteria->addSelectColumn(UserPeer::ROLES);
            $criteria->addSelectColumn(UserPeer::CREATED_AT);
            $criteria->addSelectColumn(UserPeer::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.username');
            $criteria->addSelectColumn($alias . '.username_canonical');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.email_canonical');
            $criteria->addSelectColumn($alias . '.enabled');
            $criteria->addSelectColumn($alias . '.salt');
            $criteria->addSelectColumn($alias . '.password');
            $criteria->addSelectColumn($alias . '.last_login');
            $criteria->addSelectColumn($alias . '.locked');
            $criteria->addSelectColumn($alias . '.expired');
            $criteria->addSelectColumn($alias . '.expires_at');
            $criteria->addSelectColumn($alias . '.confirmation_token');
            $criteria->addSelectColumn($alias . '.password_requested_at');
            $criteria->addSelectColumn($alias . '.credentials_expired');
            $criteria->addSelectColumn($alias . '.credentials_expire_at');
            $criteria->addSelectColumn($alias . '.type');
            $criteria->addSelectColumn($alias . '.status');
            $criteria->addSelectColumn($alias . '.roles');
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
        $criteria->setPrimaryTableName(UserPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            UserPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(UserPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return User
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = UserPeer::doSelect($critcopy, $con);
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
        return UserPeer::populateObjects(UserPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            UserPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(UserPeer::DATABASE_NAME);

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
     * @param User $obj A User object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            UserPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A User object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof User) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or User object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(UserPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return User Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(UserPeer::$instances[$key])) {
                return UserPeer::$instances[$key];
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
        foreach (UserPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        UserPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to fos_user
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in AnnouncementPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        AnnouncementPeer::clearInstancePool();
        // Invalidate objects in ApplicationPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        ApplicationPeer::clearInstancePool();
        // Invalidate objects in BehaviorPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        BehaviorPeer::clearInstancePool();
        // Invalidate objects in CategoryPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        CategoryPeer::clearInstancePool();
        // Invalidate objects in UserLogPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        UserLogPeer::clearInstancePool();
        // Invalidate objects in PagePeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PagePeer::clearInstancePool();
        // Invalidate objects in EmployeePeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        EmployeePeer::clearInstancePool();
        // Invalidate objects in EthnicityPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        EthnicityPeer::clearInstancePool();
        // Invalidate objects in MessagePeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        MessagePeer::clearInstancePool();
        // Invalidate objects in MessagePeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        MessagePeer::clearInstancePool();
        // Invalidate objects in OrganizationPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        OrganizationPeer::clearInstancePool();
        // Invalidate objects in ParentStudentPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        ParentStudentPeer::clearInstancePool();
        // Invalidate objects in ReligionPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        ReligionPeer::clearInstancePool();
        // Invalidate objects in SchoolClassCoursePeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        SchoolClassCoursePeer::clearInstancePool();
        // Invalidate objects in SchoolClassCoursePeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        SchoolClassCoursePeer::clearInstancePool();
        // Invalidate objects in StudentPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        StudentPeer::clearInstancePool();
        // Invalidate objects in StudentAvatarPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        StudentAvatarPeer::clearInstancePool();
        // Invalidate objects in TestPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        TestPeer::clearInstancePool();
        // Invalidate objects in UserProfilePeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        UserProfilePeer::clearInstancePool();
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
        $cls = UserPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = UserPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = UserPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                UserPeer::addInstanceToPool($obj, $key);
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
     * @return array (User object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = UserPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = UserPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + UserPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = UserPeer::getOMClass($row, $startcol);
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            UserPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * Gets the SQL value for Type ENUM value
     *
     * @param  string $enumVal ENUM value to get SQL value for
     * @return int SQL value
     */
    public static function getTypeSqlValue($enumVal)
    {
        return UserPeer::getSqlValueForEnum(UserPeer::TYPE, $enumVal);
    }

    /**
     * Gets the SQL value for Status ENUM value
     *
     * @param  string $enumVal ENUM value to get SQL value for
     * @return int SQL value
     */
    public static function getStatusSqlValue($enumVal)
    {
        return UserPeer::getSqlValueForEnum(UserPeer::STATUS, $enumVal);
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
        return Propel::getDatabaseMap(UserPeer::DATABASE_NAME)->getTable(UserPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseUserPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseUserPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \PGS\CoreDomainBundle\Model\map\UserTableMap());
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

        $event = new DetectOMClassEvent(UserPeer::OM_CLASS, $row, $colnum);
        EventDispatcherProxy::trigger('om.detect', $event);
        if($event->isDetected()){
            return $event->getDetectedClass();
        }

        return UserPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a User or Criteria object.
     *
     * @param      mixed $values Criteria or User object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from User object
        }

        if ($criteria->containsKey(UserPeer::ID) && $criteria->keyContainsValue(UserPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.UserPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(UserPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a User or Criteria object.
     *
     * @param      mixed $values Criteria or User object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(UserPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(UserPeer::ID);
            $value = $criteria->remove(UserPeer::ID);
            if ($value) {
                $selectCriteria->add(UserPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(UserPeer::TABLE_NAME);
            }

        } else { // $values is User object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(UserPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the fos_user table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(UserPeer::TABLE_NAME, $con, UserPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserPeer::clearInstancePool();
            UserPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a User or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or User object or primary key or array of primary keys
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
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            UserPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof User) { // it's a model object
            // invalidate the cache for this single object
            UserPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(UserPeer::DATABASE_NAME);
            $criteria->add(UserPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                UserPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(UserPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            UserPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given User object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param User $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(UserPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(UserPeer::TABLE_NAME);

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

        return BasePeer::doValidate(UserPeer::DATABASE_NAME, UserPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return User
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = UserPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(UserPeer::DATABASE_NAME);
        $criteria->add(UserPeer::ID, $pk);

        $v = UserPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return User[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(UserPeer::DATABASE_NAME);
            $criteria->add(UserPeer::ID, $pks, Criteria::IN);
            $objs = UserPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseUserPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseUserPeer::buildTableMap();

EventDispatcherProxy::trigger(array('construct','peer.construct'), new PeerEvent('PGS\CoreDomainBundle\Model\om\BaseUserPeer'));