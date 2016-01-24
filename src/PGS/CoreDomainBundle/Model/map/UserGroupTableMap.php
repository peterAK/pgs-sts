<?php

namespace PGS\CoreDomainBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'fos_user_group' table.
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
class UserGroupTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.map.UserGroupTableMap';

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
        $this->setName('fos_user_group');
        $this->setPhpName('UserGroup');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\UserGroup');
        $this->setPackage('src.PGS.CoreDomainBundle.Model');
        $this->setUseIdGenerator(false);
        $this->setIsCrossRef(true);
        // columns
        $this->addForeignPrimaryKey('fos_user_id', 'FosUserId', 'INTEGER' , 'fos_user', 'id', true, null, null);
        $this->addForeignPrimaryKey('fos_group_id', 'FosGroupId', 'INTEGER' , 'fos_group', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', 'PGS\\CoreDomainBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('fos_user_id' => 'id', ), null, null);
        $this->addRelation('Group', 'PGS\\CoreDomainBundle\\Model\\Group', RelationMap::MANY_TO_ONE, array('fos_group_id' => 'id', ), null, null);
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

} // UserGroupTableMap
