<?php

namespace PGS\CoreDomainBundle\Model\Employee\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'employee' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.Employee.map
 */
class EmployeeTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.Employee.map.EmployeeTableMap';

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
        $this->setName('employee');
        $this->setPhpName('Employee');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\Employee\\Employee');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.Employee');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'fos_user', 'id', false, null, null);
        $this->addForeignKey('organization_id', 'OrganizationId', 'INTEGER', 'organization', 'id', true, null, null);
        $this->addForeignKey('school_id', 'SchoolId', 'INTEGER', 'school', 'id', true, null, null);
        $this->addColumn('employee_no', 'EmployeeNo', 'VARCHAR', true, 20, null);
        $this->addColumn('gender', 'Gender', 'ENUM', false, null, 'unknown');
        $this->getColumn('gender', false)->setValueSet(array (
  0 => 'unknown',
  1 => 'male',
  2 => 'female',
));
        $this->addColumn('status', 'Status', 'ENUM', false, null, 'active');
        $this->getColumn('status', false)->setValueSet(array (
  0 => 'active',
  1 => 'inactive',
  2 => 'unknown',
));
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', 'PGS\\CoreDomainBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('Organization', 'PGS\\CoreDomainBundle\\Model\\Organization\\Organization', RelationMap::MANY_TO_ONE, array('organization_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('School', 'PGS\\CoreDomainBundle\\Model\\School\\School', RelationMap::MANY_TO_ONE, array('school_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('SchoolEmployment', 'PGS\\CoreDomainBundle\\Model\\SchoolEmployment\\SchoolEmployment', RelationMap::ONE_TO_MANY, array('id' => 'employee_id', ), 'CASCADE', 'CASCADE', 'SchoolEmployments');
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

} // EmployeeTableMap
