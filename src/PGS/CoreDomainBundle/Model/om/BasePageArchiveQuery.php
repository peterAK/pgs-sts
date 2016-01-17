<?php

namespace PGS\CoreDomainBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \PDO;
use \Propel;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Glorpen\Propel\PropelBundle\Dispatcher\EventDispatcherProxy;
use Glorpen\Propel\PropelBundle\Events\QueryEvent;
use PGS\CoreDomainBundle\Model\PageArchive;
use PGS\CoreDomainBundle\Model\PageArchivePeer;
use PGS\CoreDomainBundle\Model\PageArchiveQuery;

/**
 * @method PageArchiveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PageArchiveQuery orderByAuthorId($order = Criteria::ASC) Order by the author_id column
 * @method PageArchiveQuery orderBySchoolId($order = Criteria::ASC) Order by the school_id column
 * @method PageArchiveQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method PageArchiveQuery orderByTitleCanonical($order = Criteria::ASC) Order by the title_canonical column
 * @method PageArchiveQuery orderByContent($order = Criteria::ASC) Order by the content column
 * @method PageArchiveQuery orderByExcerpt($order = Criteria::ASC) Order by the excerpt column
 * @method PageArchiveQuery orderByStartPublish($order = Criteria::ASC) Order by the start_publish column
 * @method PageArchiveQuery orderByEndPublish($order = Criteria::ASC) Order by the end_publish column
 * @method PageArchiveQuery orderBySeoKeyword($order = Criteria::ASC) Order by the seo_keyword column
 * @method PageArchiveQuery orderBySeoDescription($order = Criteria::ASC) Order by the seo_description column
 * @method PageArchiveQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method PageArchiveQuery orderByAccess($order = Criteria::ASC) Order by the access column
 * @method PageArchiveQuery orderByTreeLeft($order = Criteria::ASC) Order by the tree_left column
 * @method PageArchiveQuery orderByTreeRight($order = Criteria::ASC) Order by the tree_right column
 * @method PageArchiveQuery orderByTreeLevel($order = Criteria::ASC) Order by the tree_level column
 * @method PageArchiveQuery orderByTopicId($order = Criteria::ASC) Order by the topic_id column
 * @method PageArchiveQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 * @method PageArchiveQuery orderByArchivedAt($order = Criteria::ASC) Order by the archived_at column
 * @method PageArchiveQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PageArchiveQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method PageArchiveQuery groupById() Group by the id column
 * @method PageArchiveQuery groupByAuthorId() Group by the author_id column
 * @method PageArchiveQuery groupBySchoolId() Group by the school_id column
 * @method PageArchiveQuery groupByTitle() Group by the title column
 * @method PageArchiveQuery groupByTitleCanonical() Group by the title_canonical column
 * @method PageArchiveQuery groupByContent() Group by the content column
 * @method PageArchiveQuery groupByExcerpt() Group by the excerpt column
 * @method PageArchiveQuery groupByStartPublish() Group by the start_publish column
 * @method PageArchiveQuery groupByEndPublish() Group by the end_publish column
 * @method PageArchiveQuery groupBySeoKeyword() Group by the seo_keyword column
 * @method PageArchiveQuery groupBySeoDescription() Group by the seo_description column
 * @method PageArchiveQuery groupByStatus() Group by the status column
 * @method PageArchiveQuery groupByAccess() Group by the access column
 * @method PageArchiveQuery groupByTreeLeft() Group by the tree_left column
 * @method PageArchiveQuery groupByTreeRight() Group by the tree_right column
 * @method PageArchiveQuery groupByTreeLevel() Group by the tree_level column
 * @method PageArchiveQuery groupByTopicId() Group by the topic_id column
 * @method PageArchiveQuery groupBySortableRank() Group by the sortable_rank column
 * @method PageArchiveQuery groupByArchivedAt() Group by the archived_at column
 * @method PageArchiveQuery groupByCreatedAt() Group by the created_at column
 * @method PageArchiveQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method PageArchiveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PageArchiveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PageArchiveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PageArchive findOne(PropelPDO $con = null) Return the first PageArchive matching the query
 * @method PageArchive findOneOrCreate(PropelPDO $con = null) Return the first PageArchive matching the query, or a new PageArchive object populated from the query conditions when no match is found
 *
 * @method PageArchive findOneByAuthorId(int $author_id) Return the first PageArchive filtered by the author_id column
 * @method PageArchive findOneBySchoolId(int $school_id) Return the first PageArchive filtered by the school_id column
 * @method PageArchive findOneByTitle(string $title) Return the first PageArchive filtered by the title column
 * @method PageArchive findOneByTitleCanonical(string $title_canonical) Return the first PageArchive filtered by the title_canonical column
 * @method PageArchive findOneByContent(string $content) Return the first PageArchive filtered by the content column
 * @method PageArchive findOneByExcerpt(string $excerpt) Return the first PageArchive filtered by the excerpt column
 * @method PageArchive findOneByStartPublish(string $start_publish) Return the first PageArchive filtered by the start_publish column
 * @method PageArchive findOneByEndPublish(string $end_publish) Return the first PageArchive filtered by the end_publish column
 * @method PageArchive findOneBySeoKeyword(string $seo_keyword) Return the first PageArchive filtered by the seo_keyword column
 * @method PageArchive findOneBySeoDescription(string $seo_description) Return the first PageArchive filtered by the seo_description column
 * @method PageArchive findOneByStatus(int $status) Return the first PageArchive filtered by the status column
 * @method PageArchive findOneByAccess(int $access) Return the first PageArchive filtered by the access column
 * @method PageArchive findOneByTreeLeft(int $tree_left) Return the first PageArchive filtered by the tree_left column
 * @method PageArchive findOneByTreeRight(int $tree_right) Return the first PageArchive filtered by the tree_right column
 * @method PageArchive findOneByTreeLevel(int $tree_level) Return the first PageArchive filtered by the tree_level column
 * @method PageArchive findOneByTopicId(int $topic_id) Return the first PageArchive filtered by the topic_id column
 * @method PageArchive findOneBySortableRank(int $sortable_rank) Return the first PageArchive filtered by the sortable_rank column
 * @method PageArchive findOneByArchivedAt(string $archived_at) Return the first PageArchive filtered by the archived_at column
 * @method PageArchive findOneByCreatedAt(string $created_at) Return the first PageArchive filtered by the created_at column
 * @method PageArchive findOneByUpdatedAt(string $updated_at) Return the first PageArchive filtered by the updated_at column
 *
 * @method array findById(int $id) Return PageArchive objects filtered by the id column
 * @method array findByAuthorId(int $author_id) Return PageArchive objects filtered by the author_id column
 * @method array findBySchoolId(int $school_id) Return PageArchive objects filtered by the school_id column
 * @method array findByTitle(string $title) Return PageArchive objects filtered by the title column
 * @method array findByTitleCanonical(string $title_canonical) Return PageArchive objects filtered by the title_canonical column
 * @method array findByContent(string $content) Return PageArchive objects filtered by the content column
 * @method array findByExcerpt(string $excerpt) Return PageArchive objects filtered by the excerpt column
 * @method array findByStartPublish(string $start_publish) Return PageArchive objects filtered by the start_publish column
 * @method array findByEndPublish(string $end_publish) Return PageArchive objects filtered by the end_publish column
 * @method array findBySeoKeyword(string $seo_keyword) Return PageArchive objects filtered by the seo_keyword column
 * @method array findBySeoDescription(string $seo_description) Return PageArchive objects filtered by the seo_description column
 * @method array findByStatus(int $status) Return PageArchive objects filtered by the status column
 * @method array findByAccess(int $access) Return PageArchive objects filtered by the access column
 * @method array findByTreeLeft(int $tree_left) Return PageArchive objects filtered by the tree_left column
 * @method array findByTreeRight(int $tree_right) Return PageArchive objects filtered by the tree_right column
 * @method array findByTreeLevel(int $tree_level) Return PageArchive objects filtered by the tree_level column
 * @method array findByTopicId(int $topic_id) Return PageArchive objects filtered by the topic_id column
 * @method array findBySortableRank(int $sortable_rank) Return PageArchive objects filtered by the sortable_rank column
 * @method array findByArchivedAt(string $archived_at) Return PageArchive objects filtered by the archived_at column
 * @method array findByCreatedAt(string $created_at) Return PageArchive objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PageArchive objects filtered by the updated_at column
 */
abstract class BasePageArchiveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePageArchiveQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'default';
        }
        if (null === $modelName) {
            $modelName = 'PGS\\CoreDomainBundle\\Model\\PageArchive';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new PageArchiveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PageArchiveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PageArchiveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PageArchiveQuery) {
            return $criteria;
        }
        $query = new static(null, null, $modelAlias);

        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   PageArchive|PageArchive[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PageArchivePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PageArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 PageArchive A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 PageArchive A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `author_id`, `school_id`, `title`, `title_canonical`, `content`, `excerpt`, `start_publish`, `end_publish`, `seo_keyword`, `seo_description`, `status`, `access`, `tree_left`, `tree_right`, `tree_level`, `topic_id`, `sortable_rank`, `archived_at`, `created_at`, `updated_at` FROM `page_archive` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $cls = PageArchivePeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            PageArchivePeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return PageArchive|PageArchive[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|PageArchive[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return PageArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PageArchivePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PageArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PageArchivePeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageArchiveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PageArchivePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PageArchivePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageArchivePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the author_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAuthorId(1234); // WHERE author_id = 1234
     * $query->filterByAuthorId(array(12, 34)); // WHERE author_id IN (12, 34)
     * $query->filterByAuthorId(array('min' => 12)); // WHERE author_id >= 12
     * $query->filterByAuthorId(array('max' => 12)); // WHERE author_id <= 12
     * </code>
     *
     * @param     mixed $authorId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageArchiveQuery The current query, for fluid interface
     */
    public function filterByAuthorId($authorId = null, $comparison = null)
    {
        if (is_array($authorId)) {
            $useMinMax = false;
            if (isset($authorId['min'])) {
                $this->addUsingAlias(PageArchivePeer::AUTHOR_ID, $authorId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($authorId['max'])) {
                $this->addUsingAlias(PageArchivePeer::AUTHOR_ID, $authorId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageArchivePeer::AUTHOR_ID, $authorId, $comparison);
    }

    /**
     * Filter the query on the school_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySchoolId(1234); // WHERE school_id = 1234
     * $query->filterBySchoolId(array(12, 34)); // WHERE school_id IN (12, 34)
     * $query->filterBySchoolId(array('min' => 12)); // WHERE school_id >= 12
     * $query->filterBySchoolId(array('max' => 12)); // WHERE school_id <= 12
     * </code>
     *
     * @param     mixed $schoolId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageArchiveQuery The current query, for fluid interface
     */
    public function filterBySchoolId($schoolId = null, $comparison = null)
    {
        if (is_array($schoolId)) {
            $useMinMax = false;
            if (isset($schoolId['min'])) {
                $this->addUsingAlias(PageArchivePeer::SCHOOL_ID, $schoolId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($schoolId['max'])) {
                $this->addUsingAlias(PageArchivePeer::SCHOOL_ID, $schoolId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageArchivePeer::SCHOOL_ID, $schoolId, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageArchiveQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PageArchivePeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the title_canonical column
     *
     * Example usage:
     * <code>
     * $query->filterByTitleCanonical('fooValue');   // WHERE title_canonical = 'fooValue'
     * $query->filterByTitleCanonical('%fooValue%'); // WHERE title_canonical LIKE '%fooValue%'
     * </code>
     *
     * @param     string $titleCanonical The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageArchiveQuery The current query, for fluid interface
     */
    public function filterByTitleCanonical($titleCanonical = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($titleCanonical)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $titleCanonical)) {
                $titleCanonical = str_replace('*', '%', $titleCanonical);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PageArchivePeer::TITLE_CANONICAL, $titleCanonical, $comparison);
    }

    /**
     * Filter the query on the content column
     *
     * Example usage:
     * <code>
     * $query->filterByContent('fooValue');   // WHERE content = 'fooValue'
     * $query->filterByContent('%fooValue%'); // WHERE content LIKE '%fooValue%'
     * </code>
     *
     * @param     string $content The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageArchiveQuery The current query, for fluid interface
     */
    public function filterByContent($content = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($content)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $content)) {
                $content = str_replace('*', '%', $content);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PageArchivePeer::CONTENT, $content, $comparison);
    }

    /**
     * Filter the query on the excerpt column
     *
     * Example usage:
     * <code>
     * $query->filterByExcerpt('fooValue');   // WHERE excerpt = 'fooValue'
     * $query->filterByExcerpt('%fooValue%'); // WHERE excerpt LIKE '%fooValue%'
     * </code>
     *
     * @param     string $excerpt The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageArchiveQuery The current query, for fluid interface
     */
    public function filterByExcerpt($excerpt = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($excerpt)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $excerpt)) {
                $excerpt = str_replace('*', '%', $excerpt);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PageArchivePeer::EXCERPT, $excerpt, $comparison);
    }

    /**
     * Filter the query on the start_publish column
     *
     * Example usage:
     * <code>
     * $query->filterByStartPublish('2011-03-14'); // WHERE start_publish = '2011-03-14'
     * $query->filterByStartPublish('now'); // WHERE start_publish = '2011-03-14'
     * $query->filterByStartPublish(array('max' => 'yesterday')); // WHERE start_publish < '2011-03-13'
     * </code>
     *
     * @param     mixed $startPublish The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageArchiveQuery The current query, for fluid interface
     */
    public function filterByStartPublish($startPublish = null, $comparison = null)
    {
        if (is_array($startPublish)) {
            $useMinMax = false;
            if (isset($startPublish['min'])) {
                $this->addUsingAlias(PageArchivePeer::START_PUBLISH, $startPublish['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($startPublish['max'])) {
                $this->addUsingAlias(PageArchivePeer::START_PUBLISH, $startPublish['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageArchivePeer::START_PUBLISH, $startPublish, $comparison);
    }

    /**
     * Filter the query on the end_publish column
     *
     * Example usage:
     * <code>
     * $query->filterByEndPublish('2011-03-14'); // WHERE end_publish = '2011-03-14'
     * $query->filterByEndPublish('now'); // WHERE end_publish = '2011-03-14'
     * $query->filterByEndPublish(array('max' => 'yesterday')); // WHERE end_publish < '2011-03-13'
     * </code>
     *
     * @param     mixed $endPublish The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageArchiveQuery The current query, for fluid interface
     */
    public function filterByEndPublish($endPublish = null, $comparison = null)
    {
        if (is_array($endPublish)) {
            $useMinMax = false;
            if (isset($endPublish['min'])) {
                $this->addUsingAlias(PageArchivePeer::END_PUBLISH, $endPublish['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($endPublish['max'])) {
                $this->addUsingAlias(PageArchivePeer::END_PUBLISH, $endPublish['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageArchivePeer::END_PUBLISH, $endPublish, $comparison);
    }

    /**
     * Filter the query on the seo_keyword column
     *
     * Example usage:
     * <code>
     * $query->filterBySeoKeyword('fooValue');   // WHERE seo_keyword = 'fooValue'
     * $query->filterBySeoKeyword('%fooValue%'); // WHERE seo_keyword LIKE '%fooValue%'
     * </code>
     *
     * @param     string $seoKeyword The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageArchiveQuery The current query, for fluid interface
     */
    public function filterBySeoKeyword($seoKeyword = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($seoKeyword)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $seoKeyword)) {
                $seoKeyword = str_replace('*', '%', $seoKeyword);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PageArchivePeer::SEO_KEYWORD, $seoKeyword, $comparison);
    }

    /**
     * Filter the query on the seo_description column
     *
     * Example usage:
     * <code>
     * $query->filterBySeoDescription('fooValue');   // WHERE seo_description = 'fooValue'
     * $query->filterBySeoDescription('%fooValue%'); // WHERE seo_description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $seoDescription The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageArchiveQuery The current query, for fluid interface
     */
    public function filterBySeoDescription($seoDescription = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($seoDescription)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $seoDescription)) {
                $seoDescription = str_replace('*', '%', $seoDescription);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PageArchivePeer::SEO_DESCRIPTION, $seoDescription, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * @param     mixed $status The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageArchiveQuery The current query, for fluid interface
     * @throws PropelException - if the value is not accepted by the enum.
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_scalar($status)) {
            $status = PageArchivePeer::getSqlValueForEnum(PageArchivePeer::STATUS, $status);
        } elseif (is_array($status)) {
            $convertedValues = array();
            foreach ($status as $value) {
                $convertedValues[] = PageArchivePeer::getSqlValueForEnum(PageArchivePeer::STATUS, $value);
            }
            $status = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageArchivePeer::STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the access column
     *
     * @param     mixed $access The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageArchiveQuery The current query, for fluid interface
     * @throws PropelException - if the value is not accepted by the enum.
     */
    public function filterByAccess($access = null, $comparison = null)
    {
        if (is_scalar($access)) {
            $access = PageArchivePeer::getSqlValueForEnum(PageArchivePeer::ACCESS, $access);
        } elseif (is_array($access)) {
            $convertedValues = array();
            foreach ($access as $value) {
                $convertedValues[] = PageArchivePeer::getSqlValueForEnum(PageArchivePeer::ACCESS, $value);
            }
            $access = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageArchivePeer::ACCESS, $access, $comparison);
    }

    /**
     * Filter the query on the tree_left column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeLeft(1234); // WHERE tree_left = 1234
     * $query->filterByTreeLeft(array(12, 34)); // WHERE tree_left IN (12, 34)
     * $query->filterByTreeLeft(array('min' => 12)); // WHERE tree_left >= 12
     * $query->filterByTreeLeft(array('max' => 12)); // WHERE tree_left <= 12
     * </code>
     *
     * @param     mixed $treeLeft The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageArchiveQuery The current query, for fluid interface
     */
    public function filterByTreeLeft($treeLeft = null, $comparison = null)
    {
        if (is_array($treeLeft)) {
            $useMinMax = false;
            if (isset($treeLeft['min'])) {
                $this->addUsingAlias(PageArchivePeer::TREE_LEFT, $treeLeft['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeLeft['max'])) {
                $this->addUsingAlias(PageArchivePeer::TREE_LEFT, $treeLeft['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageArchivePeer::TREE_LEFT, $treeLeft, $comparison);
    }

    /**
     * Filter the query on the tree_right column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeRight(1234); // WHERE tree_right = 1234
     * $query->filterByTreeRight(array(12, 34)); // WHERE tree_right IN (12, 34)
     * $query->filterByTreeRight(array('min' => 12)); // WHERE tree_right >= 12
     * $query->filterByTreeRight(array('max' => 12)); // WHERE tree_right <= 12
     * </code>
     *
     * @param     mixed $treeRight The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageArchiveQuery The current query, for fluid interface
     */
    public function filterByTreeRight($treeRight = null, $comparison = null)
    {
        if (is_array($treeRight)) {
            $useMinMax = false;
            if (isset($treeRight['min'])) {
                $this->addUsingAlias(PageArchivePeer::TREE_RIGHT, $treeRight['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeRight['max'])) {
                $this->addUsingAlias(PageArchivePeer::TREE_RIGHT, $treeRight['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageArchivePeer::TREE_RIGHT, $treeRight, $comparison);
    }

    /**
     * Filter the query on the tree_level column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeLevel(1234); // WHERE tree_level = 1234
     * $query->filterByTreeLevel(array(12, 34)); // WHERE tree_level IN (12, 34)
     * $query->filterByTreeLevel(array('min' => 12)); // WHERE tree_level >= 12
     * $query->filterByTreeLevel(array('max' => 12)); // WHERE tree_level <= 12
     * </code>
     *
     * @param     mixed $treeLevel The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageArchiveQuery The current query, for fluid interface
     */
    public function filterByTreeLevel($treeLevel = null, $comparison = null)
    {
        if (is_array($treeLevel)) {
            $useMinMax = false;
            if (isset($treeLevel['min'])) {
                $this->addUsingAlias(PageArchivePeer::TREE_LEVEL, $treeLevel['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeLevel['max'])) {
                $this->addUsingAlias(PageArchivePeer::TREE_LEVEL, $treeLevel['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageArchivePeer::TREE_LEVEL, $treeLevel, $comparison);
    }

    /**
     * Filter the query on the topic_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTopicId(1234); // WHERE topic_id = 1234
     * $query->filterByTopicId(array(12, 34)); // WHERE topic_id IN (12, 34)
     * $query->filterByTopicId(array('min' => 12)); // WHERE topic_id >= 12
     * $query->filterByTopicId(array('max' => 12)); // WHERE topic_id <= 12
     * </code>
     *
     * @param     mixed $topicId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageArchiveQuery The current query, for fluid interface
     */
    public function filterByTopicId($topicId = null, $comparison = null)
    {
        if (is_array($topicId)) {
            $useMinMax = false;
            if (isset($topicId['min'])) {
                $this->addUsingAlias(PageArchivePeer::TOPIC_ID, $topicId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($topicId['max'])) {
                $this->addUsingAlias(PageArchivePeer::TOPIC_ID, $topicId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageArchivePeer::TOPIC_ID, $topicId, $comparison);
    }

    /**
     * Filter the query on the sortable_rank column
     *
     * Example usage:
     * <code>
     * $query->filterBySortableRank(1234); // WHERE sortable_rank = 1234
     * $query->filterBySortableRank(array(12, 34)); // WHERE sortable_rank IN (12, 34)
     * $query->filterBySortableRank(array('min' => 12)); // WHERE sortable_rank >= 12
     * $query->filterBySortableRank(array('max' => 12)); // WHERE sortable_rank <= 12
     * </code>
     *
     * @param     mixed $sortableRank The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageArchiveQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(PageArchivePeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(PageArchivePeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageArchivePeer::SORTABLE_RANK, $sortableRank, $comparison);
    }

    /**
     * Filter the query on the archived_at column
     *
     * Example usage:
     * <code>
     * $query->filterByArchivedAt('2011-03-14'); // WHERE archived_at = '2011-03-14'
     * $query->filterByArchivedAt('now'); // WHERE archived_at = '2011-03-14'
     * $query->filterByArchivedAt(array('max' => 'yesterday')); // WHERE archived_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $archivedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageArchiveQuery The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(PageArchivePeer::ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(PageArchivePeer::ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageArchivePeer::ARCHIVED_AT, $archivedAt, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageArchiveQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PageArchivePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PageArchivePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageArchivePeer::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageArchiveQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PageArchivePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PageArchivePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageArchivePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   PageArchive $pageArchive Object to remove from the list of results
     *
     * @return PageArchiveQuery The current query, for fluid interface
     */
    public function prune($pageArchive = null)
    {
        if ($pageArchive) {
            $this->addUsingAlias(PageArchivePeer::ID, $pageArchive->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Code to execute before every SELECT statement
     *
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePreSelect(PropelPDO $con)
    {
        // event behavior
        EventDispatcherProxy::trigger('query.select.pre', new QueryEvent($this));

        return $this->preSelect($con);
    }

    /**
     * Code to execute before every DELETE statement
     *
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePreDelete(PropelPDO $con)
    {
        EventDispatcherProxy::trigger(array('delete.pre','query.delete.pre'), new QueryEvent($this));
        // event behavior
        // placeholder, issue #5

        return $this->preDelete($con);
    }

    /**
     * Code to execute after every DELETE statement
     *
     * @param     int $affectedRows the number of deleted rows
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePostDelete($affectedRows, PropelPDO $con)
    {
        // event behavior
        EventDispatcherProxy::trigger(array('delete.post','query.delete.post'), new QueryEvent($this));

        return $this->postDelete($affectedRows, $con);
    }

    /**
     * Code to execute before every UPDATE statement
     *
     * @param     array $values The associative array of columns and values for the update
     * @param     PropelPDO $con The connection object used by the query
     * @param     boolean $forceIndividualSaves If false (default), the resulting call is a BasePeer::doUpdate(), otherwise it is a series of save() calls on all the found objects
     */
    protected function basePreUpdate(&$values, PropelPDO $con, $forceIndividualSaves = false)
    {
        // event behavior
        EventDispatcherProxy::trigger(array('update.pre', 'query.update.pre'), new QueryEvent($this));

        return $this->preUpdate($values, $con, $forceIndividualSaves);
    }

    /**
     * Code to execute after every UPDATE statement
     *
     * @param     int $affectedRows the number of updated rows
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePostUpdate($affectedRows, PropelPDO $con)
    {
        // event behavior
        EventDispatcherProxy::trigger(array('update.post', 'query.update.post'), new QueryEvent($this));

        return $this->postUpdate($affectedRows, $con);
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PageArchiveQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PageArchivePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PageArchiveQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PageArchivePeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PageArchiveQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PageArchivePeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PageArchiveQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PageArchivePeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PageArchiveQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PageArchivePeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PageArchiveQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PageArchivePeer::CREATED_AT);
    }
    // extend behavior
    public function setFormatter($formatter)
    {
        if (is_string($formatter) && $formatter === \ModelCriteria::FORMAT_ON_DEMAND) {
            $formatter = '\Glorpen\Propel\PropelBundle\Formatter\PropelOnDemandFormatter';
        }

        return parent::setFormatter($formatter);
    }
}
