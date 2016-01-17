<?php

namespace PGS\CoreDomainBundle\Model\SchoolHealth\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'school_health' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.SchoolHealth.map
 */
class SchoolHealthTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.SchoolHealth.map.SchoolHealthTableMap';

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
        $this->setName('school_health');
        $this->setPhpName('SchoolHealth');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\SchoolHealth\\SchoolHealth');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.SchoolHealth');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('application_id', 'ApplicationId', 'INTEGER', 'application', 'id', false, null, null);
        $this->addColumn('student_name', 'StudentName', 'VARCHAR', false, 50, null);
        $this->addColumn('emergency_physician_name', 'EmergencyPhysicianName', 'VARCHAR', false, 50, null);
        $this->addColumn('emergency_relationship', 'EmergencyRelationship', 'VARCHAR', false, 10, null);
        $this->addColumn('emergency_phone', 'EmergencyPhone', 'VARCHAR', false, 20, null);
        $this->addColumn('allergies', 'Allergies', 'BOOLEAN', false, 1, false);
        $this->addColumn('allergies_yes', 'AllergiesYes', 'VARCHAR', false, 100, null);
        $this->addColumn('allergies_action', 'AllergiesAction', 'VARCHAR', false, 100, null);
        $this->addColumn('condition_choice', 'ConditionChoice', 'VARCHAR', false, 100, null);
        $this->addColumn('condition_exp', 'ConditionExp', 'VARCHAR', false, 100, null);
        $this->addColumn('student_psychological', 'StudentPsychological', 'BOOLEAN', false, 1, null);
        $this->addColumn('psychological_exp', 'PsychologicalExp', 'VARCHAR', false, 100, null);
        $this->addColumn('student_aware', 'StudentAware', 'BOOLEAN', false, 1, null);
        $this->addColumn('aware_exp', 'AwareExp', 'VARCHAR', false, 100, null);
        $this->addColumn('student_ability', 'StudentAbility', 'BOOLEAN', false, 1, null);
        $this->addColumn('student_medicine', 'StudentMedicine', 'VARCHAR', false, 100, null);
        $this->addColumn('medical_emergency_name', 'MedicalEmergencyName', 'VARCHAR', false, 50, null);
        $this->addColumn('medical_emergency_phone', 'MedicalEmergencyPhone', 'VARCHAR', false, 20, null);
        $this->addColumn('medical_emergency_address', 'MedicalEmergencyAddress', 'VARCHAR', false, 100, null);
        $this->addColumn('parent_statement_name', 'ParentStatementName', 'VARCHAR', false, 50, null);
        $this->addColumn('student_statement_name', 'StudentStatementName', 'VARCHAR', false, 50, null);
        $this->addColumn('parent_signature', 'ParentSignature', 'VARCHAR', true, 50, null);
        $this->addColumn('date_of_signature', 'DateOfSignature', 'DATE', true, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Application', 'PGS\\CoreDomainBundle\\Model\\Application\\Application', RelationMap::MANY_TO_ONE, array('application_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('Student', 'PGS\\CoreDomainBundle\\Model\\Student\\Student', RelationMap::ONE_TO_MANY, array('id' => 'health_id', ), 'SET NULL', 'CASCADE', 'Students');
        $this->addRelation('StudentCondition', 'PGS\\CoreDomainBundle\\Model\\StudentCondition\\StudentCondition', RelationMap::ONE_TO_MANY, array('id' => 'school_health_id', ), 'CASCADE', 'CASCADE', 'StudentConditions');
        $this->addRelation('StudentMedical', 'PGS\\CoreDomainBundle\\Model\\StudentMedical\\StudentMedical', RelationMap::ONE_TO_MANY, array('id' => 'school_health_id', ), 'CASCADE', 'CASCADE', 'StudentMedicals');
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

} // SchoolHealthTableMap
