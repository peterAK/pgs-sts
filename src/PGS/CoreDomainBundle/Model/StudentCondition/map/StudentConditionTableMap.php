<?php

namespace PGS\CoreDomainBundle\Model\StudentCondition\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'student_condition' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.StudentCondition.map
 */
class StudentConditionTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.StudentCondition.map.StudentConditionTableMap';

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
        $this->setName('student_condition');
        $this->setPhpName('StudentCondition');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\StudentCondition\\StudentCondition');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.StudentCondition');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('school_health_id', 'SchoolHealthId', 'INTEGER' , 'school_health', 'id', true, null, null);
        $this->addForeignPrimaryKey('condition_id', 'ConditionId', 'INTEGER' , 'condition', 'id', true, 5, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('SchoolHealth', 'PGS\\CoreDomainBundle\\Model\\SchoolHealth\\SchoolHealth', RelationMap::MANY_TO_ONE, array('school_health_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('Condition', 'PGS\\CoreDomainBundle\\Model\\Condition\\Condition', RelationMap::MANY_TO_ONE, array('condition_id' => 'id', ), 'CASCADE', 'CASCADE');
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

} // StudentConditionTableMap
