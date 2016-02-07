<?php

namespace PGS\CoreDomainBundle\Model\Transaction\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'transaction' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.Transaction.map
 */
class TransactionTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.Transaction.map.TransactionTableMap';

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
        $this->setName('transaction');
        $this->setPhpName('Transaction');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\Transaction\\Transaction');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.Transaction');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('visitation_id', 'VisitationId', 'INTEGER', 'visitation', 'id', true, null, null);
        $this->addForeignKey('product_id', 'ProductId', 'INTEGER', 'product', 'id', true, null, null);
        $this->addColumn('quantity', 'Quantity', 'INTEGER', true, null, 1);
        $this->addColumn('price', 'Price', 'DECIMAL', false, 12, 0);
        $this->addColumn('subtotal', 'Subtotal', 'DECIMAL', false, 12, 0);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Visitation', 'PGS\\CoreDomainBundle\\Model\\Visitation\\Visitation', RelationMap::MANY_TO_ONE, array('visitation_id' => 'id', ), null, null);
        $this->addRelation('Product', 'PGS\\CoreDomainBundle\\Model\\Product\\Product', RelationMap::MANY_TO_ONE, array('product_id' => 'id', ), null, 'CASCADE');
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
            'aggregate_column_relation' =>  array (
  'foreign_table' => 'visitation',
  'update_method' => 'updateTotal',
),
        );
    } // getBehaviors()

} // TransactionTableMap
