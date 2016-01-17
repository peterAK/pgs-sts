<?php

namespace PGS\CoreDomainBundle\Model\Qualification\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'qualification' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.Qualification.map
 */
class QualificationTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.Qualification.map.QualificationTableMap';

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
        $this->setName('qualification');
        $this->setPhpName('Qualification');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\Qualification\\Qualification');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.Qualification');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('school_id', 'SchoolId', 'INTEGER', 'school', 'id', false, null, null);
        $this->addForeignKey('course_id', 'CourseId', 'INTEGER', 'course', 'id', false, null, null);
        $this->addColumn('status', 'Status', 'ENUM', false, null, 'active');
        $this->getColumn('status', false)->setValueSet(array (
  0 => 'active',
  1 => 'inactive',
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
        $this->addRelation('School', 'PGS\\CoreDomainBundle\\Model\\School\\School', RelationMap::MANY_TO_ONE, array('school_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('Course', 'PGS\\CoreDomainBundle\\Model\\Course\\Course', RelationMap::MANY_TO_ONE, array('course_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('QualificationI18n', 'PGS\\CoreDomainBundle\\Model\\Qualification\\QualificationI18n', RelationMap::ONE_TO_MANY, array('id' => 'id', ), 'CASCADE', null, 'QualificationI18ns');
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
  'i18n_columns' => 'name, description',
  'i18n_pk_name' => NULL,
  'locale_column' => 'locale',
  'default_locale' => NULL,
  'locale_alias' => '',
),
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

} // QualificationTableMap
