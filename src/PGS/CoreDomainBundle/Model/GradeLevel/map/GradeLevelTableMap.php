<?php

namespace PGS\CoreDomainBundle\Model\GradeLevel\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'grade_level' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.GradeLevel.map
 */
class GradeLevelTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.GradeLevel.map.GradeLevelTableMap';

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
        $this->setName('grade_level');
        $this->setPhpName('GradeLevel');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\GradeLevel\\GradeLevel');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.GradeLevel');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('level_id', 'LevelId', 'INTEGER', 'level', 'id', true, null, null);
        $this->addForeignKey('grade_id', 'GradeId', 'INTEGER', 'grade', 'id', true, null, null);
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
        $this->addRelation('Grade', 'PGS\\CoreDomainBundle\\Model\\Grade\\Grade', RelationMap::MANY_TO_ONE, array('grade_id' => 'id', ), null, null);
        $this->addRelation('Level', 'PGS\\CoreDomainBundle\\Model\\Level\\Level', RelationMap::MANY_TO_ONE, array('level_id' => 'id', ), null, null);
        $this->addRelation('Course', 'PGS\\CoreDomainBundle\\Model\\Course\\Course', RelationMap::ONE_TO_MANY, array('id' => 'grade_level_id', ), 'SET NULL', 'CASCADE', 'Courses');
        $this->addRelation('SchoolClass', 'PGS\\CoreDomainBundle\\Model\\SchoolClass\\SchoolClass', RelationMap::ONE_TO_MANY, array('id' => 'grade_level_id', ), 'CASCADE', 'CASCADE', 'SchoolClasses');
        $this->addRelation('SchoolGradeLevel', 'PGS\\CoreDomainBundle\\Model\\SchoolGradeLevel\\SchoolGradeLevel', RelationMap::ONE_TO_MANY, array('id' => 'grade_level_id', ), null, null, 'SchoolGradeLevels');
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
  'use_scope' => 'false',
  'scope_column' => '',
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

} // GradeLevelTableMap
