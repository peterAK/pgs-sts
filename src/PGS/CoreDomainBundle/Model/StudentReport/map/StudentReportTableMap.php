<?php

namespace PGS\CoreDomainBundle\Model\StudentReport\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'student_report' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.StudentReport.map
 */
class StudentReportTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.StudentReport.map.StudentReportTableMap';

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
        $this->setName('student_report');
        $this->setPhpName('StudentReport');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\StudentReport\\StudentReport');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.StudentReport');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('school_class_id', 'SchoolClassId', 'INTEGER', 'school_class', 'id', true, null, null);
        $this->addForeignKey('school_class_student_id', 'SchoolClassStudentId', 'INTEGER', 'school_class_student', 'id', true, null, null);
        $this->addForeignKey('score_id', 'ScoreId', 'INTEGER', 'score', 'id', true, null, null);
        $this->addColumn('term1', 'Term1', 'INTEGER', false, null, null);
        $this->addColumn('term2', 'Term2', 'INTEGER', false, null, null);
        $this->addColumn('term3', 'Term3', 'INTEGER', false, null, null);
        $this->addColumn('term4', 'Term4', 'INTEGER', false, null, null);
        $this->addColumn('mid_report', 'MidReport', 'INTEGER', false, null, null);
        $this->addColumn('final_report', 'FinalReport', 'INTEGER', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('SchoolClass', 'PGS\\CoreDomainBundle\\Model\\SchoolClass\\SchoolClass', RelationMap::MANY_TO_ONE, array('school_class_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('SchoolClassStudent', 'PGS\\CoreDomainBundle\\Model\\SchoolClassStudent\\SchoolClassStudent', RelationMap::MANY_TO_ONE, array('school_class_student_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('Score', 'PGS\\CoreDomainBundle\\Model\\Score\\Score', RelationMap::MANY_TO_ONE, array('score_id' => 'id', ), 'CASCADE', 'CASCADE');
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

} // StudentReportTableMap
