<?php

namespace PGS\CoreDomainBundle\Model\SchoolClass\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'school_class' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.SchoolClass.map
 */
class SchoolClassTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.SchoolClass.map.SchoolClassTableMap';

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
        $this->setName('school_class');
        $this->setPhpName('SchoolClass');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\SchoolClass\\SchoolClass');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.SchoolClass');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('grade_level_id', 'GradeLevelId', 'INTEGER', 'grade_level', 'id', true, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('GradeLevel', 'PGS\\CoreDomainBundle\\Model\\GradeLevel\\GradeLevel', RelationMap::MANY_TO_ONE, array('grade_level_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('SchoolClassCourse', 'PGS\\CoreDomainBundle\\Model\\SchoolClassCourse\\SchoolClassCourse', RelationMap::ONE_TO_MANY, array('id' => 'school_class_id', ), 'CASCADE', 'CASCADE', 'SchoolClassCourses');
        $this->addRelation('SchoolClassStudent', 'PGS\\CoreDomainBundle\\Model\\SchoolClassStudent\\SchoolClassStudent', RelationMap::ONE_TO_MANY, array('id' => 'school_class_id', ), 'CASCADE', 'CASCADE', 'SchoolClassStudents');
        $this->addRelation('StudentReport', 'PGS\\CoreDomainBundle\\Model\\StudentReport\\StudentReport', RelationMap::ONE_TO_MANY, array('id' => 'school_class_id', ), 'CASCADE', 'CASCADE', 'StudentReports');
        $this->addRelation('SchoolClassI18n', 'PGS\\CoreDomainBundle\\Model\\SchoolClass\\SchoolClassI18n', RelationMap::ONE_TO_MANY, array('id' => 'id', ), 'CASCADE', null, 'SchoolClassI18ns');
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
  'i18n_columns' => 'name',
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

} // SchoolClassTableMap
