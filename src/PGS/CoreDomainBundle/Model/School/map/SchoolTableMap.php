<?php

namespace PGS\CoreDomainBundle\Model\School\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'school' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.School.map
 */
class SchoolTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.School.map.SchoolTableMap';

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
        $this->setName('school');
        $this->setPhpName('School');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\School\\School');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.School');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('code', 'Code', 'VARCHAR', false, 20, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 100, null);
        $this->addColumn('url', 'Url', 'VARCHAR', false, 100, null);
        $this->addForeignKey('level_id', 'LevelId', 'INTEGER', 'level', 'id', true, null, null);
        $this->addColumn('address', 'Address', 'VARCHAR', false, 100, null);
        $this->addColumn('city', 'City', 'VARCHAR', false, 100, null);
        $this->addForeignKey('state_id', 'StateId', 'INTEGER', 'state', 'id', false, null, null);
        $this->addColumn('zip', 'Zip', 'VARCHAR', false, 10, null);
        $this->addForeignKey('country_id', 'CountryId', 'INTEGER', 'country', 'id', true, null, null);
        $this->addForeignKey('organization_id', 'OrganizationId', 'INTEGER', 'organization', 'id', false, null, null);
        $this->addColumn('phone', 'Phone', 'VARCHAR', false, 15, null);
        $this->addColumn('fax', 'Fax', 'VARCHAR', false, 15, null);
        $this->addColumn('mobile', 'Mobile', 'VARCHAR', false, 15, null);
        $this->addColumn('email', 'Email', 'VARCHAR', false, 100, null);
        $this->addColumn('website', 'Website', 'VARCHAR', false, 100, null);
        $this->addColumn('logo', 'Logo', 'LONGVARCHAR', false, null, null);
        $this->addColumn('status', 'Status', 'ENUM', false, null, 'new');
        $this->getColumn('status', false)->setValueSet(array (
  0 => 'new',
  1 => 'active',
  2 => 'inactive',
  3 => 'banned',
));
        $this->addColumn('confirmation', 'Confirmation', 'ENUM', false, null, 'new');
        $this->getColumn('confirmation', false)->setValueSet(array (
  0 => 'new',
  1 => 'phone',
  2 => 'letter',
));
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
        $this->addRelation('State', 'PGS\\CoreDomainBundle\\Model\\State', RelationMap::MANY_TO_ONE, array('state_id' => 'id', ), null, null);
        $this->addRelation('Country', 'PGS\\CoreDomainBundle\\Model\\Country', RelationMap::MANY_TO_ONE, array('country_id' => 'id', ), null, null);
        $this->addRelation('Organization', 'PGS\\CoreDomainBundle\\Model\\Organization\\Organization', RelationMap::MANY_TO_ONE, array('organization_id' => 'id', ), null, null);
        $this->addRelation('Level', 'PGS\\CoreDomainBundle\\Model\\Level\\Level', RelationMap::MANY_TO_ONE, array('level_id' => 'id', ), null, 'CASCADE');
        $this->addRelation('Application', 'PGS\\CoreDomainBundle\\Model\\Application\\Application', RelationMap::ONE_TO_MANY, array('id' => 'school_id', ), 'CASCADE', 'CASCADE', 'Applications');
        $this->addRelation('Course', 'PGS\\CoreDomainBundle\\Model\\Course\\Course', RelationMap::ONE_TO_MANY, array('id' => 'school_id', ), 'SET NULL', 'CASCADE', 'Courses');
        $this->addRelation('Page', 'PGS\\CoreDomainBundle\\Model\\Page', RelationMap::ONE_TO_MANY, array('id' => 'school_id', ), 'CASCADE', 'CASCADE', 'Pages');
        $this->addRelation('Employee', 'PGS\\CoreDomainBundle\\Model\\Employee\\Employee', RelationMap::ONE_TO_MANY, array('id' => 'school_id', ), 'CASCADE', 'CASCADE', 'Employees');
        $this->addRelation('Qualification', 'PGS\\CoreDomainBundle\\Model\\Qualification\\Qualification', RelationMap::ONE_TO_MANY, array('id' => 'school_id', ), 'SET NULL', 'CASCADE', 'Qualifications');
        $this->addRelation('SchoolEmployment', 'PGS\\CoreDomainBundle\\Model\\SchoolEmployment\\SchoolEmployment', RelationMap::ONE_TO_MANY, array('id' => 'school_id', ), 'SET NULL', 'CASCADE', 'SchoolEmployments');
        $this->addRelation('SchoolEnrollment', 'PGS\\CoreDomainBundle\\Model\\SchoolEnrollment\\SchoolEnrollment', RelationMap::ONE_TO_MANY, array('id' => 'school_id', ), 'SET NULL', 'CASCADE', 'SchoolEnrollments');
        $this->addRelation('SchoolGradeLevel', 'PGS\\CoreDomainBundle\\Model\\SchoolGradeLevel\\SchoolGradeLevel', RelationMap::ONE_TO_MANY, array('id' => 'school_id', ), null, null, 'SchoolGradeLevels');
        $this->addRelation('SchoolTerm', 'PGS\\CoreDomainBundle\\Model\\SchoolTerm\\SchoolTerm', RelationMap::ONE_TO_MANY, array('id' => 'school_id', ), null, 'CASCADE', 'SchoolTerms');
        $this->addRelation('SchoolTest', 'PGS\\CoreDomainBundle\\Model\\SchoolTest\\SchoolTest', RelationMap::ONE_TO_MANY, array('id' => 'school_id', ), 'CASCADE', 'CASCADE', 'SchoolTests');
        $this->addRelation('SchoolYear', 'PGS\\CoreDomainBundle\\Model\\SchoolYear\\SchoolYear', RelationMap::ONE_TO_MANY, array('id' => 'school_id', ), 'CASCADE', 'CASCADE', 'SchoolYears');
        $this->addRelation('SchoolI18n', 'PGS\\CoreDomainBundle\\Model\\School\\SchoolI18n', RelationMap::ONE_TO_MANY, array('id' => 'id', ), 'CASCADE', null, 'SchoolI18ns');
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
            'i18n' =>  array (
  'i18n_table' => '%TABLE%_i18n',
  'i18n_phpname' => '%PHPNAME%I18n',
  'i18n_columns' => 'description, excerpt',
  'i18n_pk_name' => NULL,
  'locale_column' => 'locale',
  'default_locale' => NULL,
  'locale_alias' => '',
),
            'sluggable' =>  array (
  'add_cleanup' => 'true',
  'slug_column' => 'url',
  'slug_pattern' => '{Name}',
  'replace_pattern' => '/[^\\w\\/]+/u',
  'replacement' => '-',
  'separator' => '_',
  'permanent' => 'true',
  'scope_column' => '',
),
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
            'sortable_relation' =>  array (
  'foreign_table' => 'qualification',
  'foreign_scope_column' => 'school_id',
  'foreign_rank_column' => 'sortable_rank',
),
        );
    } // getBehaviors()

} // SchoolTableMap
