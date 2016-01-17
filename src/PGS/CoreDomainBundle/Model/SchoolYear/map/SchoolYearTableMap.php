<?php

namespace PGS\CoreDomainBundle\Model\SchoolYear\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'school_year' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.SchoolYear.map
 */
class SchoolYearTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.SchoolYear.map.SchoolYearTableMap';

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
        $this->setName('school_year');
        $this->setPhpName('SchoolYear');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\SchoolYear\\SchoolYear');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.SchoolYear');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('academic_year_id', 'AcademicYearId', 'INTEGER', 'academic_year', 'id', true, null, null);
        $this->addForeignKey('school_id', 'SchoolId', 'INTEGER', 'school', 'id', true, null, null);
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
        $this->addRelation('AcademicYear', 'PGS\\CoreDomainBundle\\Model\\AcademicYear\\AcademicYear', RelationMap::MANY_TO_ONE, array('academic_year_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('School', 'PGS\\CoreDomainBundle\\Model\\School\\School', RelationMap::MANY_TO_ONE, array('school_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('Application', 'PGS\\CoreDomainBundle\\Model\\Application\\Application', RelationMap::ONE_TO_MANY, array('id' => 'school_year_id', ), 'CASCADE', 'CASCADE', 'Applications');
        $this->addRelation('SchoolEmployment', 'PGS\\CoreDomainBundle\\Model\\SchoolEmployment\\SchoolEmployment', RelationMap::ONE_TO_MANY, array('id' => 'school_year_id', ), 'SET NULL', 'CASCADE', 'SchoolEmployments');
        $this->addRelation('SchoolEnrollment', 'PGS\\CoreDomainBundle\\Model\\SchoolEnrollment\\SchoolEnrollment', RelationMap::ONE_TO_MANY, array('id' => 'school_year_id', ), 'SET NULL', 'CASCADE', 'SchoolEnrollments');
        $this->addRelation('SchoolTerm', 'PGS\\CoreDomainBundle\\Model\\SchoolTerm\\SchoolTerm', RelationMap::ONE_TO_MANY, array('id' => 'school_year_id', ), null, 'CASCADE', 'SchoolTerms');
        $this->addRelation('SchoolYearI18n', 'PGS\\CoreDomainBundle\\Model\\SchoolYear\\SchoolYearI18n', RelationMap::ONE_TO_MANY, array('id' => 'id', ), 'CASCADE', null, 'SchoolYearI18ns');
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
            'i18n' =>  array (
  'i18n_table' => '%TABLE%_i18n',
  'i18n_phpname' => '%PHPNAME%I18n',
  'i18n_columns' => 'description',
  'i18n_pk_name' => NULL,
  'locale_column' => 'locale',
  'default_locale' => NULL,
  'locale_alias' => '',
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

} // SchoolYearTableMap
