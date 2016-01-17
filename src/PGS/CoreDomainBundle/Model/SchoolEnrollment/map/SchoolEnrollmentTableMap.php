<?php

namespace PGS\CoreDomainBundle\Model\SchoolEnrollment\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'school_enrollment' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.SchoolEnrollment.map
 */
class SchoolEnrollmentTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.SchoolEnrollment.map.SchoolEnrollmentTableMap';

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
        $this->setName('school_enrollment');
        $this->setPhpName('SchoolEnrollment');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\SchoolEnrollment\\SchoolEnrollment');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.SchoolEnrollment');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('student_id', 'StudentId', 'INTEGER', 'student', 'id', false, null, null);
        $this->addForeignKey('school_id', 'SchoolId', 'INTEGER', 'school', 'id', false, null, null);
        $this->addForeignKey('school_year_id', 'SchoolYearId', 'INTEGER', 'school_year', 'id', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Student', 'PGS\\CoreDomainBundle\\Model\\Student\\Student', RelationMap::MANY_TO_ONE, array('student_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('School', 'PGS\\CoreDomainBundle\\Model\\School\\School', RelationMap::MANY_TO_ONE, array('school_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('SchoolYear', 'PGS\\CoreDomainBundle\\Model\\SchoolYear\\SchoolYear', RelationMap::MANY_TO_ONE, array('school_year_id' => 'id', ), 'SET NULL', 'CASCADE');
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

} // SchoolEnrollmentTableMap
