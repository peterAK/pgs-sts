<?php

namespace PGS\CoreDomainBundle\Model\Score\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'score' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.Score.map
 */
class ScoreTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.Score.map.ScoreTableMap';

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
        $this->setName('score');
        $this->setPhpName('Score');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\Score\\Score');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.Score');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('homework', 'Homework', 'DECIMAL', true, null, null);
        $this->addColumn('daily_exam', 'DailyExam', 'DECIMAL', true, null, null);
        $this->addColumn('mid_exam', 'MidExam', 'DECIMAL', true, null, null);
        $this->addColumn('final_exam', 'FinalExam', 'DECIMAL', true, null, null);
        $this->addForeignKey('school_class_student_id', 'SchoolClassStudentId', 'INTEGER', 'school_class_student', 'id', true, null, null);
        $this->addForeignKey('school_class_course_id', 'SchoolClassCourseId', 'INTEGER', 'school_class_course', 'id', true, null, null);
        $this->addColumn('student_id', 'StudentId', 'INTEGER', true, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('SchoolClassStudent', 'PGS\\CoreDomainBundle\\Model\\SchoolClassStudent\\SchoolClassStudent', RelationMap::MANY_TO_ONE, array('school_class_student_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('SchoolClassCourse', 'PGS\\CoreDomainBundle\\Model\\SchoolClassCourse\\SchoolClassCourse', RelationMap::MANY_TO_ONE, array('school_class_course_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('StudentReport', 'PGS\\CoreDomainBundle\\Model\\StudentReport\\StudentReport', RelationMap::ONE_TO_MANY, array('id' => 'score_id', ), 'CASCADE', 'CASCADE', 'StudentReports');
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

} // ScoreTableMap
