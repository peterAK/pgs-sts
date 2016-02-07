<?php

namespace PGS\CoreDomainBundle\Model\BranchCoverage\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'branch_coverage' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.BranchCoverage.map
 */
class BranchCoverageTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.BranchCoverage.map.BranchCoverageTableMap';

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
        $this->setName('branch_coverage');
        $this->setPhpName('BranchCoverage');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\BranchCoverage\\BranchCoverage');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.BranchCoverage');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('code', 'Code', 'VARCHAR', true, 10, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 100, null);
        $this->addForeignKey('state_id', 'StateId', 'INTEGER', 'state', 'id', true, null, null);
        $this->addForeignKey('region_id', 'RegionId', 'INTEGER', 'region', 'id', false, null, null);
        $this->addForeignKey('city_id', 'CityId', 'INTEGER', 'city', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('State', 'PGS\\CoreDomainBundle\\Model\\State', RelationMap::MANY_TO_ONE, array('state_id' => 'id', ), null, null);
        $this->addRelation('Region', 'PGS\\CoreDomainBundle\\Model\\Region', RelationMap::MANY_TO_ONE, array('region_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('City', 'PGS\\CoreDomainBundle\\Model\\City', RelationMap::MANY_TO_ONE, array('city_id' => 'id', ), null, null);
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
            'event' =>  array (
),
            'extend' =>  array (
),
        );
    } // getBehaviors()

} // BranchCoverageTableMap
