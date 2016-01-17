<?php

namespace PGS\CoreDomainBundle\Model\SchoolClassStudent\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'school_class_student' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.SchoolClassStudent.map
 */
class SchoolClassStudentTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.SchoolClassStudent.map.SchoolClassStudentTableMap';

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
        $this->setName('school_class_student');
        $this->setPhpName('SchoolClassStudent');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\SchoolClassStudent\\SchoolClassStudent');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.SchoolClassStudent');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('student_id', 'StudentId', 'INTEGER', 'student', 'id', true, null, null);
        $this->addForeignKey('school_class_id', 'SchoolClassId', 'INTEGER', 'school_class', 'id', true, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Student', 'PGS\\CoreDomainBundle\\Model\\Student\\Student', RelationMap::MANY_TO_ONE, array('student_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('SchoolClass', 'PGS\\CoreDomainBundle\\Model\\SchoolClass\\SchoolClass', RelationMap::MANY_TO_ONE, array('school_class_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('Score', 'PGS\\CoreDomainBundle\\Model\\Score\\Score', RelationMap::ONE_TO_MANY, array('id' => 'school_class_student_id', ), 'CASCADE', 'CASCADE', 'Scores');
        $this->addRelation('StudentReport', 'PGS\\CoreDomainBundle\\Model\\StudentReport\\StudentReport', RelationMap::ONE_TO_MANY, array('id' => 'school_class_student_id', ), 'CASCADE', 'CASCADE', 'StudentReports');
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

} // SchoolClassStudentTableMap
