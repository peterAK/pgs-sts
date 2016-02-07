<?php

namespace PGS\CoreDomainBundle\Model\Visitation\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'visitation' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.Visitation.map
 */
class VisitationTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.Visitation.map.VisitationTableMap';

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
        $this->setName('visitation');
        $this->setPhpName('Visitation');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\Visitation\\Visitation');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.Visitation');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('employee_id', 'EmployeeId', 'INTEGER', 'fos_user', 'id', false, null, null);
        $this->addForeignKey('store_id', 'StoreId', 'INTEGER', 'store', 'id', false, null, null);
        $this->addColumn('remark', 'Remark', 'LONGVARCHAR', false, null, null);
        $this->addColumn('status', 'Status', 'ENUM', false, null, 'new');
        $this->getColumn('status', false)->setValueSet(array (
  0 => 'new',
  1 => 'partial',
  2 => 'paid',
  3 => 'closed',
  4 => 'cancelled',
));
        $this->addColumn('total', 'Total', 'DECIMAL', false, 12, 0);
        $this->addColumn('photo', 'Photo', 'LONGVARCHAR', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', 'PGS\\CoreDomainBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('employee_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('Store', 'PGS\\CoreDomainBundle\\Model\\Store\\Store', RelationMap::MANY_TO_ONE, array('store_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('Transaction', 'PGS\\CoreDomainBundle\\Model\\Transaction\\Transaction', RelationMap::ONE_TO_MANY, array('id' => 'visitation_id', ), null, null, 'Transactions');
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
            'aggregate_column' =>  array (
  'name' => 'total',
  'expression' => 'SUM(subtotal)',
  'condition' => NULL,
  'foreign_table' => 'transaction',
  'foreign_schema' => NULL,
),
            'event' =>  array (
),
            'extend' =>  array (
),
        );
    } // getBehaviors()

} // VisitationTableMap
