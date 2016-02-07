<?php

namespace PGS\CoreDomainBundle\Model\Product\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'product_archive' table.
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
class ProductArchiveTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.Product.map.ProductArchiveTableMap';

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
        $this->setName('product_archive');
        $this->setPhpName('ProductArchive');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\Product\\ProductArchive');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.Product');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('principal_id', 'PrincipalId', 'INTEGER', false, null, null);
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
        $this->addColumn('archived_at', 'ArchivedAt', 'TIMESTAMP', false, null, null);
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
            'event' =>  array (
),
            'extend' =>  array (
),
        );
    } // getBehaviors()

} // ProductArchiveTableMap
