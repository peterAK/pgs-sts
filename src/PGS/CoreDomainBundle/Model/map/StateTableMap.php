<?php

namespace PGS\CoreDomainBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'state' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.map
 */
class StateTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.map.StateTableMap';

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
        $this->setName('state');
        $this->setPhpName('State');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\State');
        $this->setPackage('src.PGS.CoreDomainBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('code', 'Code', 'VARCHAR', true, 10, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 100, null);
        $this->addForeignKey('country_id', 'CountryId', 'INTEGER', 'country', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Country', 'PGS\\CoreDomainBundle\\Model\\Country', RelationMap::MANY_TO_ONE, array('country_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('BranchCoverage', 'PGS\\CoreDomainBundle\\Model\\BranchCoverage\\BranchCoverage', RelationMap::ONE_TO_MANY, array('id' => 'state_id', ), null, null, 'BranchCoverages');
        $this->addRelation('UserProfile', 'PGS\\CoreDomainBundle\\Model\\UserProfile', RelationMap::ONE_TO_MANY, array('id' => 'state_id', ), null, null, 'UserProfiles');
        $this->addRelation('City', 'PGS\\CoreDomainBundle\\Model\\City', RelationMap::ONE_TO_MANY, array('id' => 'state_id', ), 'SET NULL', 'CASCADE', 'Cities');
        $this->addRelation('Area', 'PGS\\CoreDomainBundle\\Model\\Area', RelationMap::ONE_TO_MANY, array('id' => 'state_id', ), 'SET NULL', 'CASCADE', 'Areas');
        $this->addRelation('Principal', 'PGS\\CoreDomainBundle\\Model\\Principal\\Principal', RelationMap::ONE_TO_MANY, array('id' => 'state_id', ), 'SET NULL', 'CASCADE', 'Principals');
        $this->addRelation('Store', 'PGS\\CoreDomainBundle\\Model\\Store\\Store', RelationMap::ONE_TO_MANY, array('id' => 'state_id', ), 'SET NULL', 'CASCADE', 'Stores');
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
            'delegate' =>  array (
  'to' => 'country',
),
            'event' =>  array (
),
            'extend' =>  array (
),
        );
    } // getBehaviors()

} // StateTableMap
