<?php

namespace PGS\CoreDomainBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'page_archive' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.map
 */
class PageArchiveTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.map.PageArchiveTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('page_archive');
        $this->setPhpName('PageArchive');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\PageArchive');
        $this->setPackage('src.PGS.CoreDomainBundle.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('author_id', 'AuthorId', 'INTEGER', true, null, null);
        $this->addColumn('school_id', 'SchoolId', 'INTEGER', false, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', true, 250, null);
        $this->addColumn('title_canonical', 'TitleCanonical', 'VARCHAR', false, 250, null);
        $this->addColumn('content', 'Content', 'LONGVARCHAR', true, null, null);
        $this->addColumn('excerpt', 'Excerpt', 'VARCHAR', false, 250, null);
        $this->addColumn('start_publish', 'StartPublish', 'TIMESTAMP', false, null, null);
        $this->addColumn('end_publish', 'EndPublish', 'TIMESTAMP', false, null, null);
        $this->addColumn('seo_keyword', 'SeoKeyword', 'LONGVARCHAR', false, null, null);
        $this->addColumn('seo_description', 'SeoDescription', 'LONGVARCHAR', false, null, null);
        $this->addColumn('status', 'Status', 'ENUM', false, null, 'draft');
        $this->getColumn('status', false)->setValueSet(array (
  0 => 'draft',
  1 => 'publish',
  2 => 'closed',
));
        $this->addColumn('access', 'Access', 'ENUM', false, null, 'school');
        $this->getColumn('access', false)->setValueSet(array (
  0 => 'admin',
  1 => 'school',
  2 => 'staff',
  3 => 'parent',
  4 => 'student',
  5 => 'public',
));
        $this->addColumn('tree_left', 'TreeLeft', 'INTEGER', false, null, null);
        $this->addColumn('tree_right', 'TreeRight', 'INTEGER', false, null, null);
        $this->addColumn('tree_level', 'TreeLevel', 'INTEGER', false, null, null);
        $this->addColumn('topic_id', 'TopicId', 'INTEGER', false, null, null);
        $this->addColumn('sortable_rank', 'SortableRank', 'INTEGER', false, null, null);
        $this->addColumn('archived_at', 'ArchivedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' =>  array (
  'create_column' => 'created_at',
  'update_column' => 'updated_at',
  'disable_updated_at' => 'false',
),
            'event' =>  array (
),
            'extend' =>  array (
),
        );
    } // getBehaviors()

} // PageArchiveTableMap
