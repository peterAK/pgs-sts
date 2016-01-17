<?php

namespace PGS\CoreDomainBundle\Model\Course\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'course' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.Course.map
 */
class CourseTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.Course.map.CourseTableMap';

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
        $this->setName('course');
        $this->setPhpName('Course');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\Course\\Course');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.Course');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('code', 'Code', 'VARCHAR', true, 15, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 100, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addForeignKey('school_id', 'SchoolId', 'INTEGER', 'school', 'id', false, null, null);
        $this->addForeignKey('subject_id', 'SubjectId', 'INTEGER', 'subject', 'id', false, null, null);
        $this->addForeignKey('grade_level_id', 'GradeLevelId', 'INTEGER', 'grade_level', 'id', false, null, null);
        $this->addColumn('sortable_rank', 'SortableRank', 'INTEGER', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('School', 'PGS\\CoreDomainBundle\\Model\\School\\School', RelationMap::MANY_TO_ONE, array('school_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('Subject', 'PGS\\CoreDomainBundle\\Model\\Subject\\Subject', RelationMap::MANY_TO_ONE, array('subject_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('GradeLevel', 'PGS\\CoreDomainBundle\\Model\\GradeLevel\\GradeLevel', RelationMap::MANY_TO_ONE, array('grade_level_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('CourseObjective', 'PGS\\CoreDomainBundle\\Model\\CourseObjective\\CourseObjective', RelationMap::ONE_TO_MANY, array('id' => 'course_id', ), 'CASCADE', 'CASCADE', 'CourseObjectives');
        $this->addRelation('Qualification', 'PGS\\CoreDomainBundle\\Model\\Qualification\\Qualification', RelationMap::ONE_TO_MANY, array('id' => 'course_id', ), 'SET NULL', 'CASCADE', 'Qualifications');
        $this->addRelation('SchoolClassCourse', 'PGS\\CoreDomainBundle\\Model\\SchoolClassCourse\\SchoolClassCourse', RelationMap::ONE_TO_MANY, array('id' => 'course_id', ), 'CASCADE', 'CASCADE', 'SchoolClassCourses');
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
            'sortable' =>  array (
  'rank_column' => 'sortable_rank',
  'use_scope' => 'true',
  'scope_column' => 'school_id',
),
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

} // CourseTableMap
