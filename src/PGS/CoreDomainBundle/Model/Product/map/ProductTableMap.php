<?php

namespace PGS\CoreDomainBundle\Model\Product\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'product' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.Product.map
 */
class ProductTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.Product.map.ProductTableMap';

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
        $this->setName('product');
        $this->setPhpName('Product');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\Product\\Product');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.Product');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('principal_id', 'PrincipalId', 'INTEGER', 'principal', 'id', false, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 100, null);
        $this->addColumn('name_slug', 'NameSlug', 'VARCHAR', false, 100, null);
        $this->addColumn('category', 'Category', 'VARCHAR', false, 100, null);
        $this->addColumn('unit', 'Unit', 'ENUM', false, null, 'renceng');
        $this->getColumn('unit', false)->setValueSet(array (
  0 => 'piece',
  1 => 'kaleng',
  2 => 'kotak',
  3 => 'renceng',
  4 => 'karton',
));
        $this->addColumn('price', 'Price', 'DECIMAL', false, 12, 0);
        $this->addColumn('description', 'Description', 'CLOB', false, null, null);
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
        $this->addRelation('Principal', 'PGS\\CoreDomainBundle\\Model\\Principal\\Principal', RelationMap::MANY_TO_ONE, array('principal_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('ProductAssignment', 'PGS\\CoreDomainBundle\\Model\\ProductAssignment\\ProductAssignment', RelationMap::ONE_TO_MANY, array('id' => 'product_id', ), 'SET NULL', 'CASCADE', 'ProductAssignments');
        $this->addRelation('ProductSurvey', 'PGS\\CoreDomainBundle\\Model\\ProductSurvey\\ProductSurvey', RelationMap::ONE_TO_MANY, array('id' => 'product_id', ), 'SET NULL', 'CASCADE', 'ProductSurveys');
        $this->addRelation('Transaction', 'PGS\\CoreDomainBundle\\Model\\Transaction\\Transaction', RelationMap::ONE_TO_MANY, array('id' => 'product_id', ), null, 'CASCADE', 'Transactions');
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
            'sluggable' =>  array (
  'add_cleanup' => 'true',
  'slug_column' => 'name_slug',
  'slug_pattern' => '{Name}',
  'replace_pattern' => '/[^\\w\\/]+/u',
  'replacement' => '-',
  'separator' => '_',
  'permanent' => 'true',
  'scope_column' => '',
),
            'sortable' =>  array (
  'rank_column' => 'sortable_rank',
  'use_scope' => 'false',
  'scope_column' => '',
),
            'timestampable' =>  array (
  'create_column' => 'created_at',
  'update_column' => 'updated_at',
  'disable_updated_at' => 'false',
),
            'archivable' =>  array (
  'archive_table' => '',
  'archive_phpname' => NULL,
  'archive_class' => '',
  'log_archived_at' => 'true',
  'archived_at_column' => 'archived_at',
  'archive_on_insert' => 'false',
  'archive_on_update' => 'false',
  'archive_on_delete' => 'true',
),
            'event' =>  array (
),
            'extend' =>  array (
),
        );
    } // getBehaviors()

} // ProductTableMap
