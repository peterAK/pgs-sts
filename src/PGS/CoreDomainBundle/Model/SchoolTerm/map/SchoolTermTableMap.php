<?php

namespace PGS\CoreDomainBundle\Model\SchoolTerm\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'school_term' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.SchoolTerm.map
 */
class SchoolTermTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.SchoolTerm.map.SchoolTermTableMap';

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
        $this->setName('school_term');
        $this->setPhpName('SchoolTerm');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\SchoolTerm\\SchoolTerm');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.SchoolTerm');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('code', 'Code', 'VARCHAR', true, 5, null);
        $this->addForeignKey('school_id', 'SchoolId', 'INTEGER', 'school', 'id', true, null, null);
        $this->addForeignKey('school_year_id', 'SchoolYearId', 'INTEGER', 'school_year', 'id', true, null, null);
        $this->addForeignKey('term_id', 'TermId', 'INTEGER', 'term', 'id', true, null, null);
        $this->addColumn('date_start', 'DateStart', 'DATE', false, null, null);
        $this->addColumn('date_end', 'DateEnd', 'DATE', false, null, null);
        $this->addColumn('active', 'Active', 'BOOLEAN', false, 1, false);
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
        $this->addRelation('School', 'PGS\\CoreDomainBundle\\Model\\School\\School', RelationMap::MANY_TO_ONE, array('school_id' => 'id', ), null, 'CASCADE');
        $this->addRelation('Term', 'PGS\\CoreDomainBundle\\Model\\Term\\Term', RelationMap::MANY_TO_ONE, array('term_id' => 'id', ), null, 'CASCADE');
        $this->addRelation('SchoolYear', 'PGS\\CoreDomainBundle\\Model\\SchoolYear\\SchoolYear', RelationMap::MANY_TO_ONE, array('school_year_id' => 'id', ), null, 'CASCADE');
        $this->addRelation('SchoolClassCourse', 'PGS\\CoreDomainBundle\\Model\\SchoolClassCourse\\SchoolClassCourse', RelationMap::ONE_TO_MANY, array('id' => 'school_term_id', ), 'CASCADE', 'CASCADE', 'SchoolClassCourses');
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

} // SchoolTermTableMap
