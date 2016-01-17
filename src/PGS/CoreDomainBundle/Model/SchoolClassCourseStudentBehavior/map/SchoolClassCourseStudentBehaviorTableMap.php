<?php

namespace PGS\CoreDomainBundle\Model\SchoolClassCourseStudentBehavior\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'school_class_course_student_behavior' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.SchoolClassCourseStudentBehavior.map
 */
class SchoolClassCourseStudentBehaviorTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.SchoolClassCourseStudentBehavior.map.SchoolClassCourseStudentBehaviorTableMap';

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
        $this->setName('school_class_course_student_behavior');
        $this->setPhpName('SchoolClassCourseStudentBehavior');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\SchoolClassCourseStudentBehavior\\SchoolClassCourseStudentBehavior');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.SchoolClassCourseStudentBehavior');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('student_id', 'StudentId', 'INTEGER', 'student', 'id', true, null, null);
        $this->addForeignKey('behavior_id', 'BehaviorId', 'INTEGER', 'behavior', 'id', true, null, null);
        $this->addForeignKey('school_class_course_id', 'SchoolClassCourseId', 'INTEGER', 'school_class_course', 'id', true, null, null);
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
        $this->addRelation('Behavior', 'PGS\\CoreDomainBundle\\Model\\Behavior\\Behavior', RelationMap::MANY_TO_ONE, array('behavior_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('SchoolClassCourse', 'PGS\\CoreDomainBundle\\Model\\SchoolClassCourse\\SchoolClassCourse', RelationMap::MANY_TO_ONE, array('school_class_course_id' => 'id', ), 'CASCADE', 'CASCADE');
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

} // SchoolClassCourseStudentBehaviorTableMap