<?php

namespace PGS\CoreDomainBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'page' table.
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
class PageTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.map.PageTableMap';

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
        $this->setName('page');
        $this->setPhpName('Page');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\Page');
        $this->setPackage('src.PGS.CoreDomainBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('author_id', 'AuthorId', 'INTEGER', 'fos_user', 'id', true, null, null);
        $this->addForeignKey('school_id', 'SchoolId', 'INTEGER', 'school', 'id', false, null, null);
        $this->addForeignKey('topic_id', 'TopicId', 'INTEGER', 'topic', 'id', true, null, null);
        $this->addColumn('title_canonical', 'TitleCanonical', 'VARCHAR', false, 250, null);
        $this->addColumn('start_publish', 'StartPublish', 'TIMESTAMP', false, null, null);
        $this->addColumn('end_publish', 'EndPublish', 'TIMESTAMP', false, null, null);
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
        $this->addColumn('sortable_rank', 'SortableRank', 'INTEGER', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', 'PGS\\CoreDomainBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('author_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('School', 'PGS\\CoreDomainBundle\\Model\\School\\School', RelationMap::MANY_TO_ONE, array('school_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('Topic', 'PGS\\CoreDomainBundle\\Model\\Topic', RelationMap::MANY_TO_ONE, array('topic_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('PageI18n', 'PGS\\CoreDomainBundle\\Model\\PageI18n', RelationMap::ONE_TO_MANY, array('id' => 'id', ), 'CASCADE', null, 'PageI18ns');
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
            'nested_set' =>  array (
  'left_column' => 'tree_left',
  'right_column' => 'tree_right',
  'level_column' => 'tree_level',
  'use_scope' => 'true',
  'scope_column' => 'topic_id',
  'method_proxies' => 'false',
),
            'sortable' =>  array (
  'rank_column' => 'sortable_rank',
  'use_scope' => 'false',
  'scope_column' => '',
),
            'i18n' =>  array (
  'i18n_table' => '%TABLE%_i18n',
  'i18n_phpname' => '%PHPNAME%I18n',
  'i18n_columns' => 'title, content, excerpt, seo_keyword, seo_description',
  'i18n_pk_name' => NULL,
  'locale_column' => 'locale',
  'default_locale' => NULL,
  'locale_alias' => '',
),
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

} // PageTableMap
