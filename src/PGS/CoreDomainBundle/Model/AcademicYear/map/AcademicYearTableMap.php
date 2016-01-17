<?php

namespace PGS\CoreDomainBundle\Model\AcademicYear\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'academic_year' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.AcademicYear.map
 */
class AcademicYearTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.AcademicYear.map.AcademicYearTableMap';

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
        $this->setName('academic_year');
        $this->setPhpName('AcademicYear');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\AcademicYear\\AcademicYear');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.AcademicYear');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('year_start', 'YearStart', 'INTEGER', true, null, 2010);
        $this->addColumn('year_end', 'YearEnd', 'INTEGER', true, null, 2011);
        $this->addColumn('description', 'Description', 'VARCHAR', true, 100, null);
        $this->addColumn('active', 'Active', 'BOOLEAN', false, 1, false);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('SchoolYear', 'PGS\\CoreDomainBundle\\Model\\SchoolYear\\SchoolYear', RelationMap::ONE_TO_MANY, array('id' => 'academic_year_id', ), 'CASCADE', 'CASCADE', 'SchoolYears');
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

} // AcademicYearTableMap
