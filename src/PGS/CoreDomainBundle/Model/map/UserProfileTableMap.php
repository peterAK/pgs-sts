<?php

namespace PGS\CoreDomainBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'user_profile' table.
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
class UserProfileTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.map.UserProfileTableMap';

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
        $this->setName('user_profile');
        $this->setPhpName('UserProfile');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\UserProfile');
        $this->setPackage('src.PGS.CoreDomainBundle.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addColumn('prefix', 'Prefix', 'VARCHAR', false, 10, null);
        $this->addForeignKey('organization_id', 'OrganizationId', 'INTEGER', 'organization', 'id', false, null, null);
        $this->addColumn('nick_name', 'NickName', 'VARCHAR', false, 30, null);
        $this->addColumn('first_name', 'FirstName', 'VARCHAR', true, 30, null);
        $this->addColumn('middle_name', 'MiddleName', 'VARCHAR', false, 30, null);
        $this->addColumn('last_name', 'LastName', 'VARCHAR', false, 30, null);
        $this->addColumn('phone', 'Phone', 'VARCHAR', false, 20, null);
        $this->addColumn('mobile', 'Mobile', 'VARCHAR', false, 20, null);
        $this->addColumn('address', 'Address', 'VARCHAR', false, 100, null);
        $this->addColumn('business_address', 'BusinessAddress', 'VARCHAR', false, 100, null);
        $this->addColumn('occupation', 'Occupation', 'VARCHAR', false, 30, null);
        $this->addColumn('city', 'City', 'VARCHAR', false, 100, null);
        $this->addForeignKey('state_id', 'StateId', 'INTEGER', 'state', 'id', false, null, null);
        $this->addColumn('zip', 'Zip', 'VARCHAR', false, 10, null);
        $this->addForeignKey('country_id', 'CountryId', 'INTEGER', 'country', 'id', true, null, 105);
        $this->addColumn('active_preferences', 'ActivePreferences', 'LONGVARCHAR', false, null, null);
        $this->addColumn('complete', 'Complete', 'BOOLEAN', false, 1, false);
        $this->addForeignPrimaryKey('id', 'Id', 'INTEGER' , 'fos_user', 'id', true, null, null);
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
        $this->addRelation('Organization', 'PGS\\CoreDomainBundle\\Model\\Organization\\Organization', RelationMap::MANY_TO_ONE, array('organization_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('User', 'PGS\\CoreDomainBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('id' => 'id', ), 'CASCADE', null);
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

} // UserProfileTableMap
