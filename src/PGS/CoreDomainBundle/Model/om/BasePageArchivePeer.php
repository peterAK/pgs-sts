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
use PGS\CoreDomainBundle\Model\PageArchive;
use PGS\CoreDomainBundle\Model\PageArchivePeer;
use PGS\CoreDomainBundle\Model\map\PageArchiveTableMap;

abstract class BasePageArchivePeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'page_archive';

    /** the related Propel class for this table */
    const OM_CLASS = 'PGS\\CoreDomainBundle\\Model\\PageArchive';

    /** the related TableMap class for this table */
    const TM_CLASS = 'PGS\\CoreDomainBundle\\Model\\map\\PageArchiveTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 21;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 21;

    /** the column name for the id field */
    const ID = 'page_archive.id';

    /** the column name for the author_id field */
    const AUTHOR_ID = 'page_archive.author_id';

    /** the column name for the school_id field */
    const SCHOOL_ID = 'page_archive.school_id';

    /** the column name for the title field */
    const TITLE = 'page_archive.title';

    /** the column name for the title_canonical field */
    const TITLE_CANONICAL = 'page_archive.title_canonical';

    /** the column name for the content field */
    const CONTENT = 'page_archive.content';

    /** the column name for the excerpt field */
    const EXCERPT = 'page_archive.excerpt';

    /** the column name for the start_publish field */
    const START_PUBLISH = 'page_archive.start_publish';

    /** the column name for the end_publish field */
    const END_PUBLISH = 'page_archive.end_publish';

    /** the column name for the seo_keyword field */
    const SEO_KEYWORD = 'page_archive.seo_keyword';

    /** the column name for the seo_description field */
    const SEO_DESCRIPTION = 'page_archive.seo_description';

    /** the column name for the status field */
    const STATUS = 'page_archive.status';

    /** the column name for the access field */
    const ACCESS = 'page_archive.access';

    /** the column name for the tree_left field */
    const TREE_LEFT = 'page_archive.tree_left';

    /** the column name for the tree_right field */
    const TREE_RIGHT = 'page_archive.tree_right';

    /** the column name for the tree_level field */
    const TREE_LEVEL = 'page_archive.tree_level';

    /** the column name for the topic_id field */
    const TOPIC_ID = 'page_archive.topic_id';

    /** the column name for the sortable_rank field */
    const SORTABLE_RANK = 'page_archive.sortable_rank';

    /** the column name for the archived_at field */
    const ARCHIVED_AT = 'page_archive.archived_at';

    /** the column name for the created_at field */
    const CREATED_AT = 'page_archive.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'page_archive.updated_at';

    /** The enumerated values for the status field */
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISH = 'publish';
    const STATUS_CLOSED = 'closed';

    /** The enumerated values for the access field */
    const ACCESS_ADMIN = 'admin';
    const ACCESS_SCHOOL = 'school';
    const ACCESS_STAFF = 'staff';
    const ACCESS_PARENT = 'parent';
    const ACCESS_STUDENT = 'student';
    const ACCESS_PUBLIC = 'public';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of PageArchive objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array PageArchive[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. PageArchivePeer::$fieldNames[PageArchivePeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'AuthorId', 'SchoolId', 'Title', 'TitleCanonical', 'Content', 'Excerpt', 'StartPublish', 'EndPublish', 'SeoKeyword', 'SeoDescription', 'Status', 'Access', 'TreeLeft', 'TreeRight', 'TreeLevel', 'TopicId', 'SortableRank', 'ArchivedAt', 'CreatedAt', 'UpdatedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'authorId', 'schoolId', 'title', 'titleCanonical', 'content', 'excerpt', 'startPublish', 'endPublish', 'seoKeyword', 'seoDescription', 'status', 'access', 'treeLeft', 'treeRight', 'treeLevel', 'topicId', 'sortableRank', 'archivedAt', 'createdAt', 'updatedAt', ),
        BasePeer::TYPE_COLNAME => array (PageArchivePeer::ID, PageArchivePeer::AUTHOR_ID, PageArchivePeer::SCHOOL_ID, PageArchivePeer::TITLE, PageArchivePeer::TITLE_CANONICAL, PageArchivePeer::CONTENT, PageArchivePeer::EXCERPT, PageArchivePeer::START_PUBLISH, PageArchivePeer::END_PUBLISH, PageArchivePeer::SEO_KEYWORD, PageArchivePeer::SEO_DESCRIPTION, PageArchivePeer::STATUS, PageArchivePeer::ACCESS, PageArchivePeer::TREE_LEFT, PageArchivePeer::TREE_RIGHT, PageArchivePeer::TREE_LEVEL, PageArchivePeer::TOPIC_ID, PageArchivePeer::SORTABLE_RANK, PageArchivePeer::ARCHIVED_AT, PageArchivePeer::CREATED_AT, PageArchivePeer::UPDATED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'AUTHOR_ID', 'SCHOOL_ID', 'TITLE', 'TITLE_CANONICAL', 'CONTENT', 'EXCERPT', 'START_PUBLISH', 'END_PUBLISH', 'SEO_KEYWORD', 'SEO_DESCRIPTION', 'STATUS', 'ACCESS', 'TREE_LEFT', 'TREE_RIGHT', 'TREE_LEVEL', 'TOPIC_ID', 'SORTABLE_RANK', 'ARCHIVED_AT', 'CREATED_AT', 'UPDATED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'author_id', 'school_id', 'title', 'title_canonical', 'content', 'excerpt', 'start_publish', 'end_publish', 'seo_keyword', 'seo_description', 'status', 'access', 'tree_left', 'tree_right', 'tree_level', 'topic_id', 'sortable_rank', 'archived_at', 'created_at', 'updated_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. PageArchivePeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'AuthorId' => 1, 'SchoolId' => 2, 'Title' => 3, 'TitleCanonical' => 4, 'Content' => 5, 'Excerpt' => 6, 'StartPublish' => 7, 'EndPublish' => 8, 'SeoKeyword' => 9, 'SeoDescription' => 10, 'Status' => 11, 'Access' => 12, 'TreeLeft' => 13, 'TreeRight' => 14, 'TreeLevel' => 15, 'TopicId' => 16, 'SortableRank' => 17, 'ArchivedAt' => 18, 'CreatedAt' => 19, 'UpdatedAt' => 20, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'authorId' => 1, 'schoolId' => 2, 'title' => 3, 'titleCanonical' => 4, 'content' => 5, 'excerpt' => 6, 'startPublish' => 7, 'endPublish' => 8, 'seoKeyword' => 9, 'seoDescription' => 10, 'status' => 11, 'access' => 12, 'treeLeft' => 13, 'treeRight' => 14, 'treeLevel' => 15, 'topicId' => 16, 'sortableRank' => 17, 'archivedAt' => 18, 'createdAt' => 19, 'updatedAt' => 20, ),
        BasePeer::TYPE_COLNAME => array (PageArchivePeer::ID => 0, PageArchivePeer::AUTHOR_ID => 1, PageArchivePeer::SCHOOL_ID => 2, PageArchivePeer::TITLE => 3, PageArchivePeer::TITLE_CANONICAL => 4, PageArchivePeer::CONTENT => 5, PageArchivePeer::EXCERPT => 6, PageArchivePeer::START_PUBLISH => 7, PageArchivePeer::END_PUBLISH => 8, PageArchivePeer::SEO_KEYWORD => 9, PageArchivePeer::SEO_DESCRIPTION => 10, PageArchivePeer::STATUS => 11, PageArchivePeer::ACCESS => 12, PageArchivePeer::TREE_LEFT => 13, PageArchivePeer::TREE_RIGHT => 14, PageArchivePeer::TREE_LEVEL => 15, PageArchivePeer::TOPIC_ID => 16, PageArchivePeer::SORTABLE_RANK => 17, PageArchivePeer::ARCHIVED_AT => 18, PageArchivePeer::CREATED_AT => 19, PageArchivePeer::UPDATED_AT => 20, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'AUTHOR_ID' => 1, 'SCHOOL_ID' => 2, 'TITLE' => 3, 'TITLE_CANONICAL' => 4, 'CONTENT' => 5, 'EXCERPT' => 6, 'START_PUBLISH' => 7, 'END_PUBLISH' => 8, 'SEO_KEYWORD' => 9, 'SEO_DESCRIPTION' => 10, 'STATUS' => 11, 'ACCESS' => 12, 'TREE_LEFT' => 13, 'TREE_RIGHT' => 14, 'TREE_LEVEL' => 15, 'TOPIC_ID' => 16, 'SORTABLE_RANK' => 17, 'ARCHIVED_AT' => 18, 'CREATED_AT' => 19, 'UPDATED_AT' => 20, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'author_id' => 1, 'school_id' => 2, 'title' => 3, 'title_canonical' => 4, 'content' => 5, 'excerpt' => 6, 'start_publish' => 7, 'end_publish' => 8, 'seo_keyword' => 9, 'seo_description' => 10, 'status' => 11, 'access' => 12, 'tree_left' => 13, 'tree_right' => 14, 'tree_level' => 15, 'topic_id' => 16, 'sortable_rank' => 17, 'archived_at' => 18, 'created_at' => 19, 'updated_at' => 20, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
        PageArchivePeer::STATUS => array(
            PageArchivePeer::STATUS_DRAFT,
            PageArchivePeer::STATUS_PUBLISH,
            PageArchivePeer::STATUS_CLOSED,
        ),
        PageArchivePeer::ACCESS => array(
            PageArchivePeer::ACCESS_ADMIN,
            PageArchivePeer::ACCESS_SCHOOL,
            PageArchivePeer::ACCESS_STAFF,
            PageArchivePeer::ACCESS_PARENT,
            PageArchivePeer::ACCESS_STUDENT,
            PageArchivePeer::ACCESS_PUBLIC,
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
        $toNames = PageArchivePeer::getFieldNames($toType);
        $key = isset(PageArchivePeer::$fieldKeys[$fromType][$name]) ? PageArchivePeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(PageArchivePeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, PageArchivePeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return PageArchivePeer::$fieldNames[$type];
    }

    /**
     * Gets the list of values for all ENUM columns
     * @return array
     */
    public static function getValueSets()
    {
      return PageArchivePeer::$enumValueSets;
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
        $valueSets = PageArchivePeer::getValueSets();

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
        $values = PageArchivePeer::getValueSet($colname);
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
     * @param      string $column The column name for current table. (i.e. PageArchivePeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(PageArchivePeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(PageArchivePeer::ID);
            $criteria->addSelectColumn(PageArchivePeer::AUTHOR_ID);
            $criteria->addSelectColumn(PageArchivePeer::SCHOOL_ID);
            $criteria->addSelectColumn(PageArchivePeer::TITLE);
            $criteria->addSelectColumn(PageArchivePeer::TITLE_CANONICAL);
            $criteria->addSelectColumn(PageArchivePeer::CONTENT);
            $criteria->addSelectColumn(PageArchivePeer::EXCERPT);
            $criteria->addSelectColumn(PageArchivePeer::START_PUBLISH);
            $criteria->addSelectColumn(PageArchivePeer::END_PUBLISH);
            $criteria->addSelectColumn(PageArchivePeer::SEO_KEYWORD);
            $criteria->addSelectColumn(PageArchivePeer::SEO_DESCRIPTION);
            $criteria->addSelectColumn(PageArchivePeer::STATUS);
            $criteria->addSelectColumn(PageArchivePeer::ACCESS);
            $criteria->addSelectColumn(PageArchivePeer::TREE_LEFT);
            $criteria->addSelectColumn(PageArchivePeer::TREE_RIGHT);
            $criteria->addSelectColumn(PageArchivePeer::TREE_LEVEL);
            $criteria->addSelectColumn(PageArchivePeer::TOPIC_ID);
            $criteria->addSelectColumn(PageArchivePeer::SORTABLE_RANK);
            $criteria->addSelectColumn(PageArchivePeer::ARCHIVED_AT);
            $criteria->addSelectColumn(PageArchivePeer::CREATED_AT);
            $criteria->addSelectColumn(PageArchivePeer::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.author_id');
            $criteria->addSelectColumn($alias . '.school_id');
            $criteria->addSelectColumn($alias . '.title');
            $criteria->addSelectColumn($alias . '.title_canonical');
            $criteria->addSelectColumn($alias . '.content');
            $criteria->addSelectColumn($alias . '.excerpt');
            $criteria->addSelectColumn($alias . '.start_publish');
            $criteria->addSelectColumn($alias . '.end_publish');
            $criteria->addSelectColumn($alias . '.seo_keyword');
            $criteria->addSelectColumn($alias . '.seo_description');
            $criteria->addSelectColumn($alias . '.status');
            $criteria->addSelectColumn($alias . '.access');
            $criteria->addSelectColumn($alias . '.tree_left');
            $criteria->addSelectColumn($alias . '.tree_right');
            $criteria->addSelectColumn($alias . '.tree_level');
            $criteria->addSelectColumn($alias . '.topic_id');
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
        $criteria->setPrimaryTableName(PageArchivePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PageArchivePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(PageArchivePeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(PageArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return PageArchive
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = PageArchivePeer::doSelect($critcopy, $con);
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
        return PageArchivePeer::populateObjects(PageArchivePeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(PageArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            PageArchivePeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(PageArchivePeer::DATABASE_NAME);

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
     * @param PageArchive $obj A PageArchive object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            PageArchivePeer::$instances[$key] = $obj;
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
     * @param      mixed $value A PageArchive object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof PageArchive) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or PageArchive object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(PageArchivePeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return PageArchive Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(PageArchivePeer::$instances[$key])) {
                return PageArchivePeer::$instances[$key];
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
        foreach (PageArchivePeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        PageArchivePeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to page_archive
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
        $cls = PageArchivePeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = PageArchivePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = PageArchivePeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PageArchivePeer::addInstanceToPool($obj, $key);
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
     * @return array (PageArchive object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = PageArchivePeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = PageArchivePeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + PageArchivePeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PageArchivePeer::getOMClass($row, $startcol);
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            PageArchivePeer::addInstanceToPool($obj, $key);
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
        return PageArchivePeer::getSqlValueForEnum(PageArchivePeer::STATUS, $enumVal);
    }

    /**
     * Gets the SQL value for Access ENUM value
     *
     * @param  string $enumVal ENUM value to get SQL value for
     * @return int SQL value
     */
    public static function getAccessSqlValue($enumVal)
    {
        return PageArchivePeer::getSqlValueForEnum(PageArchivePeer::ACCESS, $enumVal);
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
        return Propel::getDatabaseMap(PageArchivePeer::DATABASE_NAME)->getTable(PageArchivePeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BasePageArchivePeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BasePageArchivePeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \PGS\CoreDomainBundle\Model\map\PageArchiveTableMap());
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

        $event = new DetectOMClassEvent(PageArchivePeer::OM_CLASS, $row, $colnum);
        EventDispatcherProxy::trigger('om.detect', $event);
        if($event->isDetected()){
            return $event->getDetectedClass();
        }

        return PageArchivePeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a PageArchive or Criteria object.
     *
     * @param      mixed $values Criteria or PageArchive object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PageArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from PageArchive object
        }


        // Set the correct dbName
        $criteria->setDbName(PageArchivePeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a PageArchive or Criteria object.
     *
     * @param      mixed $values Criteria or PageArchive object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PageArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(PageArchivePeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(PageArchivePeer::ID);
            $value = $criteria->remove(PageArchivePeer::ID);
            if ($value) {
                $selectCriteria->add(PageArchivePeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(PageArchivePeer::TABLE_NAME);
            }

        } else { // $values is PageArchive object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(PageArchivePeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the page_archive table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PageArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(PageArchivePeer::TABLE_NAME, $con, PageArchivePeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PageArchivePeer::clearInstancePool();
            PageArchivePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a PageArchive or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or PageArchive object or primary key or array of primary keys
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
            $con = Propel::getConnection(PageArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            PageArchivePeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof PageArchive) { // it's a model object
            // invalidate the cache for this single object
            PageArchivePeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PageArchivePeer::DATABASE_NAME);
            $criteria->add(PageArchivePeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                PageArchivePeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(PageArchivePeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            PageArchivePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given PageArchive object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param PageArchive $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(PageArchivePeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(PageArchivePeer::TABLE_NAME);

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

        return BasePeer::doValidate(PageArchivePeer::DATABASE_NAME, PageArchivePeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return PageArchive
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = PageArchivePeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(PageArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(PageArchivePeer::DATABASE_NAME);
        $criteria->add(PageArchivePeer::ID, $pk);

        $v = PageArchivePeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return PageArchive[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PageArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(PageArchivePeer::DATABASE_NAME);
            $criteria->add(PageArchivePeer::ID, $pks, Criteria::IN);
            $objs = PageArchivePeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BasePageArchivePeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BasePageArchivePeer::buildTableMap();

EventDispatcherProxy::trigger(array('construct','peer.construct'), new PeerEvent('PGS\CoreDomainBundle\Model\om\BasePageArchivePeer'));
