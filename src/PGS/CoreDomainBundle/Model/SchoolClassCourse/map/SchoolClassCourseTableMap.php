<?php

namespace PGS\CoreDomainBundle\Model\SchoolClassCourse\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'school_class_course' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.SchoolClassCourse.map
 */
class SchoolClassCourseTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.SchoolClassCourse.map.SchoolClassCourseTableMap';

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
        $this->setName('school_class_course');
        $this->setPhpName('SchoolClassCourse');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\SchoolClassCourse\\SchoolClassCourse');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.SchoolClassCourse');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 100, null);
        $this->addForeignKey('school_class_id', 'SchoolClassId', 'INTEGER', 'school_class', 'id', true, null, null);
        $this->addColumn('start_time', 'StartTime', 'TIME', false, null, null);
        $this->addColumn('end_time', 'EndTime', 'TIME', false, null, null);
        $this->addForeignKey('course_id', 'CourseId', 'INTEGER', 'course', 'id', true, null, null);
        $this->addForeignKey('school_term_id', 'SchoolTermId', 'INTEGER', 'school_term', 'id', true, null, null);
        $this->addForeignKey('school_grade_level_id', 'SchoolGradeLevelId', 'INTEGER', 'school_grade_level', 'id', true, null, null);
        $this->addForeignKey('primary_teacher_id', 'PrimaryTeacherId', 'INTEGER', 'fos_user', 'id', false, null, null);
        $this->addForeignKey('secondary_teacher_id', 'SecondaryTeacherId', 'INTEGER', 'fos_user', 'id', false, null, null);
        $this->addForeignKey('formula_id', 'FormulaId', 'INTEGER', 'formula', 'id', true, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Course', 'PGS\\CoreDomainBundle\\Model\\Course\\Course', RelationMap::MANY_TO_ONE, array('course_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('SchoolClass', 'PGS\\CoreDomainBundle\\Model\\SchoolClass\\SchoolClass', RelationMap::MANY_TO_ONE, array('school_class_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('SchoolTerm', 'PGS\\CoreDomainBundle\\Model\\SchoolTerm\\SchoolTerm', RelationMap::MANY_TO_ONE, array('school_term_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('SchoolGradeLevel', 'PGS\\CoreDomainBundle\\Model\\SchoolGradeLevel\\SchoolGradeLevel', RelationMap::MANY_TO_ONE, array('school_grade_level_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('PrimaryTeacher', 'PGS\\CoreDomainBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('primary_teacher_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('SecondaryTeacher', 'PGS\\CoreDomainBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('secondary_teacher_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('Formula', 'PGS\\CoreDomainBundle\\Model\\Formula\\Formula', RelationMap::MANY_TO_ONE, array('formula_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('SchoolClassCourseStudentBehavior', 'PGS\\CoreDomainBundle\\Model\\SchoolClassCourseStudentBehavior\\SchoolClassCourseStudentBehavior', RelationMap::ONE_TO_MANY, array('id' => 'school_class_course_id', ), 'CASCADE', 'CASCADE', 'SchoolClassCourseStudentBehaviors');
        $this->addRelation('Score', 'PGS\\CoreDomainBundle\\Model\\Score\\Score', RelationMap::ONE_TO_MANY, array('id' => 'school_class_course_id', ), 'CASCADE', 'CASCADE', 'Scores');
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

} // SchoolClassCourseTableMap
