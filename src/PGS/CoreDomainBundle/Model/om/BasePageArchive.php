<?php

namespace PGS\CoreDomainBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelDateTime;
use \PropelException;
use \PropelPDO;
use Glorpen\Propel\PropelBundle\Dispatcher\EventDispatcherProxy;
use Glorpen\Propel\PropelBundle\Events\ModelEvent;
use PGS\CoreDomainBundle\Model\PageArchive;
use PGS\CoreDomainBundle\Model\PageArchivePeer;
use PGS\CoreDomainBundle\Model\PageArchiveQuery;

abstract class BasePageArchive extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'PGS\\CoreDomainBundle\\Model\\PageArchivePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PageArchivePeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the author_id field.
     * @var        int
     */
    protected $author_id;

    /**
     * The value for the school_id field.
     * @var        int
     */
    protected $school_id;

    /**
     * The value for the title field.
     * @var        string
     */
    protected $title;

    /**
     * The value for the title_canonical field.
     * @var        string
     */
    protected $title_canonical;

    /**
     * The value for the content field.
     * @var        string
     */
    protected $content;

    /**
     * The value for the excerpt field.
     * @var        string
     */
    protected $excerpt;

    /**
     * The value for the start_publish field.
     * @var        string
     */
    protected $start_publish;

    /**
     * The value for the end_publish field.
     * @var        string
     */
    protected $end_publish;

    /**
     * The value for the seo_keyword field.
     * @var        string
     */
    protected $seo_keyword;

    /**
     * The value for the seo_description field.
     * @var        string
     */
    protected $seo_description;

    /**
     * The value for the status field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $status;

    /**
     * The value for the access field.
     * Note: this column has a database default value of: 1
     * @var        int
     */
    protected $access;

    /**
     * The value for the tree_left field.
     * @var        int
     */
    protected $tree_left;

    /**
     * The value for the tree_right field.
     * @var        int
     */
    protected $tree_right;

    /**
     * The value for the tree_level field.
     * @var        int
     */
    protected $tree_level;

    /**
     * The value for the topic_id field.
     * @var        int
     */
    protected $topic_id;

    /**
     * The value for the sortable_rank field.
     * @var        int
     */
    protected $sortable_rank;

    /**
     * The value for the archived_at field.
     * @var        string
     */
    protected $archived_at;

    /**
     * The value for the created_at field.
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->status = 0;
        $this->access = 1;
    }

    /**
     * Initializes internal state of BasePageArchive object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
        EventDispatcherProxy::trigger(array('construct','model.construct'), new ModelEvent($this));
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [author_id] column value.
     *
     * @return int
     */
    public function getAuthorId()
    {

        return $this->author_id;
    }

    /**
     * Get the [school_id] column value.
     *
     * @return int
     */
    public function getSchoolId()
    {

        return $this->school_id;
    }

    /**
     * Get the [title] column value.
     *
     * @return string
     */
    public function getTitle()
    {

        return $this->title;
    }

    /**
     * Get the [title_canonical] column value.
     *
     * @return string
     */
    public function getTitleCanonical()
    {

        return $this->title_canonical;
    }

    /**
     * Get the [content] column value.
     *
     * @return string
     */
    public function getContent()
    {

        return $this->content;
    }

    /**
     * Get the [excerpt] column value.
     *
     * @return string
     */
    public function getExcerpt()
    {

        return $this->excerpt;
    }

    /**
     * Get the [optionally formatted] temporal [start_publish] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getStartPublish($format = null)
    {
        if ($this->start_publish === null) {
            return null;
        }

        if ($this->start_publish === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->start_publish);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->start_publish, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [end_publish] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getEndPublish($format = null)
    {
        if ($this->end_publish === null) {
            return null;
        }

        if ($this->end_publish === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->end_publish);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->end_publish, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [seo_keyword] column value.
     *
     * @return string
     */
    public function getSeoKeyword()
    {

        return $this->seo_keyword;
    }

    /**
     * Get the [seo_description] column value.
     *
     * @return string
     */
    public function getSeoDescription()
    {

        return $this->seo_description;
    }

    /**
     * Get the [status] column value.
     *
     * @return int
     * @throws PropelException - if the stored enum key is unknown.
     */
    public function getStatus()
    {
        if (null === $this->status) {
            return null;
        }
        $valueSet = PageArchivePeer::getValueSet(PageArchivePeer::STATUS);
        if (!isset($valueSet[$this->status])) {
            throw new PropelException('Unknown stored enum key: ' . $this->status);
        }

        return $valueSet[$this->status];
    }

    /**
     * Get the [access] column value.
     *
     * @return int
     * @throws PropelException - if the stored enum key is unknown.
     */
    public function getAccess()
    {
        if (null === $this->access) {
            return null;
        }
        $valueSet = PageArchivePeer::getValueSet(PageArchivePeer::ACCESS);
        if (!isset($valueSet[$this->access])) {
            throw new PropelException('Unknown stored enum key: ' . $this->access);
        }

        return $valueSet[$this->access];
    }

    /**
     * Get the [tree_left] column value.
     *
     * @return int
     */
    public function getTreeLeft()
    {

        return $this->tree_left;
    }

    /**
     * Get the [tree_right] column value.
     *
     * @return int
     */
    public function getTreeRight()
    {

        return $this->tree_right;
    }

    /**
     * Get the [tree_level] column value.
     *
     * @return int
     */
    public function getTreeLevel()
    {

        return $this->tree_level;
    }

    /**
     * Get the [topic_id] column value.
     *
     * @return int
     */
    public function getTopicId()
    {

        return $this->topic_id;
    }

    /**
     * Get the [sortable_rank] column value.
     *
     * @return int
     */
    public function getSortableRank()
    {

        return $this->sortable_rank;
    }

    /**
     * Get the [optionally formatted] temporal [archived_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getArchivedAt($format = null)
    {
        if ($this->archived_at === null) {
            return null;
        }

        if ($this->archived_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->archived_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->archived_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = null)
    {
        if ($this->created_at === null) {
            return null;
        }

        if ($this->created_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->created_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = null)
    {
        if ($this->updated_at === null) {
            return null;
        }

        if ($this->updated_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->updated_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->updated_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return PageArchive The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PageArchivePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [author_id] column.
     *
     * @param  int $v new value
     * @return PageArchive The current object (for fluent API support)
     */
    public function setAuthorId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->author_id !== $v) {
            $this->author_id = $v;
            $this->modifiedColumns[] = PageArchivePeer::AUTHOR_ID;
        }


        return $this;
    } // setAuthorId()

    /**
     * Set the value of [school_id] column.
     *
     * @param  int $v new value
     * @return PageArchive The current object (for fluent API support)
     */
    public function setSchoolId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->school_id !== $v) {
            $this->school_id = $v;
            $this->modifiedColumns[] = PageArchivePeer::SCHOOL_ID;
        }


        return $this;
    } // setSchoolId()

    /**
     * Set the value of [title] column.
     *
     * @param  string $v new value
     * @return PageArchive The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = PageArchivePeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [title_canonical] column.
     *
     * @param  string $v new value
     * @return PageArchive The current object (for fluent API support)
     */
    public function setTitleCanonical($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title_canonical !== $v) {
            $this->title_canonical = $v;
            $this->modifiedColumns[] = PageArchivePeer::TITLE_CANONICAL;
        }


        return $this;
    } // setTitleCanonical()

    /**
     * Set the value of [content] column.
     *
     * @param  string $v new value
     * @return PageArchive The current object (for fluent API support)
     */
    public function setContent($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->content !== $v) {
            $this->content = $v;
            $this->modifiedColumns[] = PageArchivePeer::CONTENT;
        }


        return $this;
    } // setContent()

    /**
     * Set the value of [excerpt] column.
     *
     * @param  string $v new value
     * @return PageArchive The current object (for fluent API support)
     */
    public function setExcerpt($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->excerpt !== $v) {
            $this->excerpt = $v;
            $this->modifiedColumns[] = PageArchivePeer::EXCERPT;
        }


        return $this;
    } // setExcerpt()

    /**
     * Sets the value of [start_publish] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PageArchive The current object (for fluent API support)
     */
    public function setStartPublish($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->start_publish !== null || $dt !== null) {
            $currentDateAsString = ($this->start_publish !== null && $tmpDt = new DateTime($this->start_publish)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->start_publish = $newDateAsString;
                $this->modifiedColumns[] = PageArchivePeer::START_PUBLISH;
            }
        } // if either are not null


        return $this;
    } // setStartPublish()

    /**
     * Sets the value of [end_publish] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PageArchive The current object (for fluent API support)
     */
    public function setEndPublish($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->end_publish !== null || $dt !== null) {
            $currentDateAsString = ($this->end_publish !== null && $tmpDt = new DateTime($this->end_publish)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->end_publish = $newDateAsString;
                $this->modifiedColumns[] = PageArchivePeer::END_PUBLISH;
            }
        } // if either are not null


        return $this;
    } // setEndPublish()

    /**
     * Set the value of [seo_keyword] column.
     *
     * @param  string $v new value
     * @return PageArchive The current object (for fluent API support)
     */
    public function setSeoKeyword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->seo_keyword !== $v) {
            $this->seo_keyword = $v;
            $this->modifiedColumns[] = PageArchivePeer::SEO_KEYWORD;
        }


        return $this;
    } // setSeoKeyword()

    /**
     * Set the value of [seo_description] column.
     *
     * @param  string $v new value
     * @return PageArchive The current object (for fluent API support)
     */
    public function setSeoDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->seo_description !== $v) {
            $this->seo_description = $v;
            $this->modifiedColumns[] = PageArchivePeer::SEO_DESCRIPTION;
        }


        return $this;
    } // setSeoDescription()

    /**
     * Set the value of [status] column.
     *
     * @param  int $v new value
     * @return PageArchive The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            $valueSet = PageArchivePeer::getValueSet(PageArchivePeer::STATUS);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[] = PageArchivePeer::STATUS;
        }


        return $this;
    } // setStatus()

    /**
     * Set the value of [access] column.
     *
     * @param  int $v new value
     * @return PageArchive The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setAccess($v)
    {
        if ($v !== null) {
            $valueSet = PageArchivePeer::getValueSet(PageArchivePeer::ACCESS);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->access !== $v) {
            $this->access = $v;
            $this->modifiedColumns[] = PageArchivePeer::ACCESS;
        }


        return $this;
    } // setAccess()

    /**
     * Set the value of [tree_left] column.
     *
     * @param  int $v new value
     * @return PageArchive The current object (for fluent API support)
     */
    public function setTreeLeft($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->tree_left !== $v) {
            $this->tree_left = $v;
            $this->modifiedColumns[] = PageArchivePeer::TREE_LEFT;
        }


        return $this;
    } // setTreeLeft()

    /**
     * Set the value of [tree_right] column.
     *
     * @param  int $v new value
     * @return PageArchive The current object (for fluent API support)
     */
    public function setTreeRight($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->tree_right !== $v) {
            $this->tree_right = $v;
            $this->modifiedColumns[] = PageArchivePeer::TREE_RIGHT;
        }


        return $this;
    } // setTreeRight()

    /**
     * Set the value of [tree_level] column.
     *
     * @param  int $v new value
     * @return PageArchive The current object (for fluent API support)
     */
    public function setTreeLevel($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->tree_level !== $v) {
            $this->tree_level = $v;
            $this->modifiedColumns[] = PageArchivePeer::TREE_LEVEL;
        }


        return $this;
    } // setTreeLevel()

    /**
     * Set the value of [topic_id] column.
     *
     * @param  int $v new value
     * @return PageArchive The current object (for fluent API support)
     */
    public function setTopicId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->topic_id !== $v) {
            $this->topic_id = $v;
            $this->modifiedColumns[] = PageArchivePeer::TOPIC_ID;
        }


        return $this;
    } // setTopicId()

    /**
     * Set the value of [sortable_rank] column.
     *
     * @param  int $v new value
     * @return PageArchive The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[] = PageArchivePeer::SORTABLE_RANK;
        }


        return $this;
    } // setSortableRank()

    /**
     * Sets the value of [archived_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PageArchive The current object (for fluent API support)
     */
    public function setArchivedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->archived_at !== null || $dt !== null) {
            $currentDateAsString = ($this->archived_at !== null && $tmpDt = new DateTime($this->archived_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->archived_at = $newDateAsString;
                $this->modifiedColumns[] = PageArchivePeer::ARCHIVED_AT;
            }
        } // if either are not null


        return $this;
    } // setArchivedAt()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PageArchive The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = PageArchivePeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PageArchive The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = PageArchivePeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->status !== 0) {
                return false;
            }

            if ($this->access !== 1) {
                return false;
            }

        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->author_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->school_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->title = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->title_canonical = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->content = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->excerpt = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->start_publish = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->end_publish = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->seo_keyword = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->seo_description = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->status = ($row[$startcol + 11] !== null) ? (int) $row[$startcol + 11] : null;
            $this->access = ($row[$startcol + 12] !== null) ? (int) $row[$startcol + 12] : null;
            $this->tree_left = ($row[$startcol + 13] !== null) ? (int) $row[$startcol + 13] : null;
            $this->tree_right = ($row[$startcol + 14] !== null) ? (int) $row[$startcol + 14] : null;
            $this->tree_level = ($row[$startcol + 15] !== null) ? (int) $row[$startcol + 15] : null;
            $this->topic_id = ($row[$startcol + 16] !== null) ? (int) $row[$startcol + 16] : null;
            $this->sortable_rank = ($row[$startcol + 17] !== null) ? (int) $row[$startcol + 17] : null;
            $this->archived_at = ($row[$startcol + 18] !== null) ? (string) $row[$startcol + 18] : null;
            $this->created_at = ($row[$startcol + 19] !== null) ? (string) $row[$startcol + 19] : null;
            $this->updated_at = ($row[$startcol + 20] !== null) ? (string) $row[$startcol + 20] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 21; // 21 = PageArchivePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating PageArchive object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(PageArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PageArchivePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(PageArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
            $deleteQuery = PageArchiveQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                // event behavior
                EventDispatcherProxy::trigger(array('delete.post', 'model.delete.post'), new ModelEvent($this));
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(PageArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // event behavior
            EventDispatcherProxy::trigger('model.save.pre', new ModelEvent($this));
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(PageArchivePeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PageArchivePeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PageArchivePeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger(array('update.pre', 'model.update.pre'), new ModelEvent($this));
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                    // event behavior
                    EventDispatcherProxy::trigger('model.insert.post', new ModelEvent($this));
                } else {
                    $this->postUpdate($con);
                    // event behavior
                    EventDispatcherProxy::trigger(array('update.post', 'model.update.post'), new ModelEvent($this));
                }
                $this->postSave($con);
                // event behavior
                EventDispatcherProxy::trigger('model.save.post', new ModelEvent($this));
                PageArchivePeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PageArchivePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PageArchivePeer::AUTHOR_ID)) {
            $modifiedColumns[':p' . $index++]  = '`author_id`';
        }
        if ($this->isColumnModified(PageArchivePeer::SCHOOL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`school_id`';
        }
        if ($this->isColumnModified(PageArchivePeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(PageArchivePeer::TITLE_CANONICAL)) {
            $modifiedColumns[':p' . $index++]  = '`title_canonical`';
        }
        if ($this->isColumnModified(PageArchivePeer::CONTENT)) {
            $modifiedColumns[':p' . $index++]  = '`content`';
        }
        if ($this->isColumnModified(PageArchivePeer::EXCERPT)) {
            $modifiedColumns[':p' . $index++]  = '`excerpt`';
        }
        if ($this->isColumnModified(PageArchivePeer::START_PUBLISH)) {
            $modifiedColumns[':p' . $index++]  = '`start_publish`';
        }
        if ($this->isColumnModified(PageArchivePeer::END_PUBLISH)) {
            $modifiedColumns[':p' . $index++]  = '`end_publish`';
        }
        if ($this->isColumnModified(PageArchivePeer::SEO_KEYWORD)) {
            $modifiedColumns[':p' . $index++]  = '`seo_keyword`';
        }
        if ($this->isColumnModified(PageArchivePeer::SEO_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`seo_description`';
        }
        if ($this->isColumnModified(PageArchivePeer::STATUS)) {
            $modifiedColumns[':p' . $index++]  = '`status`';
        }
        if ($this->isColumnModified(PageArchivePeer::ACCESS)) {
            $modifiedColumns[':p' . $index++]  = '`access`';
        }
        if ($this->isColumnModified(PageArchivePeer::TREE_LEFT)) {
            $modifiedColumns[':p' . $index++]  = '`tree_left`';
        }
        if ($this->isColumnModified(PageArchivePeer::TREE_RIGHT)) {
            $modifiedColumns[':p' . $index++]  = '`tree_right`';
        }
        if ($this->isColumnModified(PageArchivePeer::TREE_LEVEL)) {
            $modifiedColumns[':p' . $index++]  = '`tree_level`';
        }
        if ($this->isColumnModified(PageArchivePeer::TOPIC_ID)) {
            $modifiedColumns[':p' . $index++]  = '`topic_id`';
        }
        if ($this->isColumnModified(PageArchivePeer::SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = '`sortable_rank`';
        }
        if ($this->isColumnModified(PageArchivePeer::ARCHIVED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`archived_at`';
        }
        if ($this->isColumnModified(PageArchivePeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(PageArchivePeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `page_archive` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`author_id`':
                        $stmt->bindValue($identifier, $this->author_id, PDO::PARAM_INT);
                        break;
                    case '`school_id`':
                        $stmt->bindValue($identifier, $this->school_id, PDO::PARAM_INT);
                        break;
                    case '`title`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case '`title_canonical`':
                        $stmt->bindValue($identifier, $this->title_canonical, PDO::PARAM_STR);
                        break;
                    case '`content`':
                        $stmt->bindValue($identifier, $this->content, PDO::PARAM_STR);
                        break;
                    case '`excerpt`':
                        $stmt->bindValue($identifier, $this->excerpt, PDO::PARAM_STR);
                        break;
                    case '`start_publish`':
                        $stmt->bindValue($identifier, $this->start_publish, PDO::PARAM_STR);
                        break;
                    case '`end_publish`':
                        $stmt->bindValue($identifier, $this->end_publish, PDO::PARAM_STR);
                        break;
                    case '`seo_keyword`':
                        $stmt->bindValue($identifier, $this->seo_keyword, PDO::PARAM_STR);
                        break;
                    case '`seo_description`':
                        $stmt->bindValue($identifier, $this->seo_description, PDO::PARAM_STR);
                        break;
                    case '`status`':
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_INT);
                        break;
                    case '`access`':
                        $stmt->bindValue($identifier, $this->access, PDO::PARAM_INT);
                        break;
                    case '`tree_left`':
                        $stmt->bindValue($identifier, $this->tree_left, PDO::PARAM_INT);
                        break;
                    case '`tree_right`':
                        $stmt->bindValue($identifier, $this->tree_right, PDO::PARAM_INT);
                        break;
                    case '`tree_level`':
                        $stmt->bindValue($identifier, $this->tree_level, PDO::PARAM_INT);
                        break;
                    case '`topic_id`':
                        $stmt->bindValue($identifier, $this->topic_id, PDO::PARAM_INT);
                        break;
                    case '`sortable_rank`':
                        $stmt->bindValue($identifier, $this->sortable_rank, PDO::PARAM_INT);
                        break;
                    case '`archived_at`':
                        $stmt->bindValue($identifier, $this->archived_at, PDO::PARAM_STR);
                        break;
                    case '`created_at`':
                        $stmt->bindValue($identifier, $this->created_at, PDO::PARAM_STR);
                        break;
                    case '`updated_at`':
                        $stmt->bindValue($identifier, $this->updated_at, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            if (($retval = PageArchivePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }



            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = PageArchivePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getAuthorId();
                break;
            case 2:
                return $this->getSchoolId();
                break;
            case 3:
                return $this->getTitle();
                break;
            case 4:
                return $this->getTitleCanonical();
                break;
            case 5:
                return $this->getContent();
                break;
            case 6:
                return $this->getExcerpt();
                break;
            case 7:
                return $this->getStartPublish();
                break;
            case 8:
                return $this->getEndPublish();
                break;
            case 9:
                return $this->getSeoKeyword();
                break;
            case 10:
                return $this->getSeoDescription();
                break;
            case 11:
                return $this->getStatus();
                break;
            case 12:
                return $this->getAccess();
                break;
            case 13:
                return $this->getTreeLeft();
                break;
            case 14:
                return $this->getTreeRight();
                break;
            case 15:
                return $this->getTreeLevel();
                break;
            case 16:
                return $this->getTopicId();
                break;
            case 17:
                return $this->getSortableRank();
                break;
            case 18:
                return $this->getArchivedAt();
                break;
            case 19:
                return $this->getCreatedAt();
                break;
            case 20:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array())
    {
        if (isset($alreadyDumpedObjects['PageArchive'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PageArchive'][$this->getPrimaryKey()] = true;
        $keys = PageArchivePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getAuthorId(),
            $keys[2] => $this->getSchoolId(),
            $keys[3] => $this->getTitle(),
            $keys[4] => $this->getTitleCanonical(),
            $keys[5] => $this->getContent(),
            $keys[6] => $this->getExcerpt(),
            $keys[7] => $this->getStartPublish(),
            $keys[8] => $this->getEndPublish(),
            $keys[9] => $this->getSeoKeyword(),
            $keys[10] => $this->getSeoDescription(),
            $keys[11] => $this->getStatus(),
            $keys[12] => $this->getAccess(),
            $keys[13] => $this->getTreeLeft(),
            $keys[14] => $this->getTreeRight(),
            $keys[15] => $this->getTreeLevel(),
            $keys[16] => $this->getTopicId(),
            $keys[17] => $this->getSortableRank(),
            $keys[18] => $this->getArchivedAt(),
            $keys[19] => $this->getCreatedAt(),
            $keys[20] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }


        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = PageArchivePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setAuthorId($value);
                break;
            case 2:
                $this->setSchoolId($value);
                break;
            case 3:
                $this->setTitle($value);
                break;
            case 4:
                $this->setTitleCanonical($value);
                break;
            case 5:
                $this->setContent($value);
                break;
            case 6:
                $this->setExcerpt($value);
                break;
            case 7:
                $this->setStartPublish($value);
                break;
            case 8:
                $this->setEndPublish($value);
                break;
            case 9:
                $this->setSeoKeyword($value);
                break;
            case 10:
                $this->setSeoDescription($value);
                break;
            case 11:
                $valueSet = PageArchivePeer::getValueSet(PageArchivePeer::STATUS);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setStatus($value);
                break;
            case 12:
                $valueSet = PageArchivePeer::getValueSet(PageArchivePeer::ACCESS);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setAccess($value);
                break;
            case 13:
                $this->setTreeLeft($value);
                break;
            case 14:
                $this->setTreeRight($value);
                break;
            case 15:
                $this->setTreeLevel($value);
                break;
            case 16:
                $this->setTopicId($value);
                break;
            case 17:
                $this->setSortableRank($value);
                break;
            case 18:
                $this->setArchivedAt($value);
                break;
            case 19:
                $this->setCreatedAt($value);
                break;
            case 20:
                $this->setUpdatedAt($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = PageArchivePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setAuthorId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setSchoolId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setTitle($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setTitleCanonical($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setContent($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setExcerpt($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setStartPublish($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setEndPublish($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setSeoKeyword($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setSeoDescription($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setStatus($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setAccess($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setTreeLeft($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setTreeRight($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setTreeLevel($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setTopicId($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setSortableRank($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setArchivedAt($arr[$keys[18]]);
        if (array_key_exists($keys[19], $arr)) $this->setCreatedAt($arr[$keys[19]]);
        if (array_key_exists($keys[20], $arr)) $this->setUpdatedAt($arr[$keys[20]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PageArchivePeer::DATABASE_NAME);

        if ($this->isColumnModified(PageArchivePeer::ID)) $criteria->add(PageArchivePeer::ID, $this->id);
        if ($this->isColumnModified(PageArchivePeer::AUTHOR_ID)) $criteria->add(PageArchivePeer::AUTHOR_ID, $this->author_id);
        if ($this->isColumnModified(PageArchivePeer::SCHOOL_ID)) $criteria->add(PageArchivePeer::SCHOOL_ID, $this->school_id);
        if ($this->isColumnModified(PageArchivePeer::TITLE)) $criteria->add(PageArchivePeer::TITLE, $this->title);
        if ($this->isColumnModified(PageArchivePeer::TITLE_CANONICAL)) $criteria->add(PageArchivePeer::TITLE_CANONICAL, $this->title_canonical);
        if ($this->isColumnModified(PageArchivePeer::CONTENT)) $criteria->add(PageArchivePeer::CONTENT, $this->content);
        if ($this->isColumnModified(PageArchivePeer::EXCERPT)) $criteria->add(PageArchivePeer::EXCERPT, $this->excerpt);
        if ($this->isColumnModified(PageArchivePeer::START_PUBLISH)) $criteria->add(PageArchivePeer::START_PUBLISH, $this->start_publish);
        if ($this->isColumnModified(PageArchivePeer::END_PUBLISH)) $criteria->add(PageArchivePeer::END_PUBLISH, $this->end_publish);
        if ($this->isColumnModified(PageArchivePeer::SEO_KEYWORD)) $criteria->add(PageArchivePeer::SEO_KEYWORD, $this->seo_keyword);
        if ($this->isColumnModified(PageArchivePeer::SEO_DESCRIPTION)) $criteria->add(PageArchivePeer::SEO_DESCRIPTION, $this->seo_description);
        if ($this->isColumnModified(PageArchivePeer::STATUS)) $criteria->add(PageArchivePeer::STATUS, $this->status);
        if ($this->isColumnModified(PageArchivePeer::ACCESS)) $criteria->add(PageArchivePeer::ACCESS, $this->access);
        if ($this->isColumnModified(PageArchivePeer::TREE_LEFT)) $criteria->add(PageArchivePeer::TREE_LEFT, $this->tree_left);
        if ($this->isColumnModified(PageArchivePeer::TREE_RIGHT)) $criteria->add(PageArchivePeer::TREE_RIGHT, $this->tree_right);
        if ($this->isColumnModified(PageArchivePeer::TREE_LEVEL)) $criteria->add(PageArchivePeer::TREE_LEVEL, $this->tree_level);
        if ($this->isColumnModified(PageArchivePeer::TOPIC_ID)) $criteria->add(PageArchivePeer::TOPIC_ID, $this->topic_id);
        if ($this->isColumnModified(PageArchivePeer::SORTABLE_RANK)) $criteria->add(PageArchivePeer::SORTABLE_RANK, $this->sortable_rank);
        if ($this->isColumnModified(PageArchivePeer::ARCHIVED_AT)) $criteria->add(PageArchivePeer::ARCHIVED_AT, $this->archived_at);
        if ($this->isColumnModified(PageArchivePeer::CREATED_AT)) $criteria->add(PageArchivePeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PageArchivePeer::UPDATED_AT)) $criteria->add(PageArchivePeer::UPDATED_AT, $this->updated_at);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(PageArchivePeer::DATABASE_NAME);
        $criteria->add(PageArchivePeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of PageArchive (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setAuthorId($this->getAuthorId());
        $copyObj->setSchoolId($this->getSchoolId());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setTitleCanonical($this->getTitleCanonical());
        $copyObj->setContent($this->getContent());
        $copyObj->setExcerpt($this->getExcerpt());
        $copyObj->setStartPublish($this->getStartPublish());
        $copyObj->setEndPublish($this->getEndPublish());
        $copyObj->setSeoKeyword($this->getSeoKeyword());
        $copyObj->setSeoDescription($this->getSeoDescription());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setAccess($this->getAccess());
        $copyObj->setTreeLeft($this->getTreeLeft());
        $copyObj->setTreeRight($this->getTreeRight());
        $copyObj->setTreeLevel($this->getTreeLevel());
        $copyObj->setTopicId($this->getTopicId());
        $copyObj->setSortableRank($this->getSortableRank());
        $copyObj->setArchivedAt($this->getArchivedAt());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return PageArchive Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return PageArchivePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PageArchivePeer();
        }

        return self::$peer;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->author_id = null;
        $this->school_id = null;
        $this->title = null;
        $this->title_canonical = null;
        $this->content = null;
        $this->excerpt = null;
        $this->start_publish = null;
        $this->end_publish = null;
        $this->seo_keyword = null;
        $this->seo_description = null;
        $this->status = null;
        $this->access = null;
        $this->tree_left = null;
        $this->tree_right = null;
        $this->tree_level = null;
        $this->topic_id = null;
        $this->sortable_rank = null;
        $this->archived_at = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PageArchivePeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     PageArchive The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = PageArchivePeer::UPDATED_AT;

        return $this;
    }

    // event behavior
    public function preCommit(\PropelPDO $con = null){}
    public function preCommitSave(\PropelPDO $con = null){}
    public function preCommitDelete(\PropelPDO $con = null){}
    public function preCommitUpdate(\PropelPDO $con = null){}
    public function preCommitInsert(\PropelPDO $con = null){}
    public function preRollback(\PropelPDO $con = null){}
    public function preRollbackSave(\PropelPDO $con = null){}
    public function preRollbackDelete(\PropelPDO $con = null){}
    public function preRollbackUpdate(\PropelPDO $con = null){}
    public function preRollbackInsert(\PropelPDO $con = null){}

}
