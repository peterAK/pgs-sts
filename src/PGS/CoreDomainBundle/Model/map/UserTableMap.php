<?php

namespace PGS\CoreDomainBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'fos_user' table.
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
class UserTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.map.UserTableMap';

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
        $this->setName('fos_user');
        $this->setPhpName('User');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\User');
        $this->setPackage('src.PGS.CoreDomainBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('username', 'Username', 'VARCHAR', false, 255, null);
        $this->getColumn('username', false)->setPrimaryString(true);
        $this->addColumn('username_canonical', 'UsernameCanonical', 'VARCHAR', false, 255, null);
        $this->addColumn('email', 'Email', 'VARCHAR', false, 255, null);
        $this->addColumn('email_canonical', 'EmailCanonical', 'VARCHAR', false, 255, null);
        $this->addColumn('enabled', 'Enabled', 'BOOLEAN', false, 1, false);
        $this->addColumn('salt', 'Salt', 'VARCHAR', true, 255, null);
        $this->addColumn('password', 'Password', 'VARCHAR', true, 255, null);
        $this->addColumn('last_login', 'LastLogin', 'TIMESTAMP', false, null, null);
        $this->addColumn('locked', 'Locked', 'BOOLEAN', false, 1, false);
        $this->addColumn('expired', 'Expired', 'BOOLEAN', false, 1, false);
        $this->addColumn('expires_at', 'ExpiresAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('confirmation_token', 'ConfirmationToken', 'VARCHAR', false, 255, null);
        $this->addColumn('password_requested_at', 'PasswordRequestedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('credentials_expired', 'CredentialsExpired', 'BOOLEAN', false, 1, false);
        $this->addColumn('credentials_expire_at', 'CredentialsExpireAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('type', 'Type', 'ENUM', false, null, 'student');
        $this->getColumn('type', false)->setValueSet(array (
  0 => 'admin',
  1 => 'principal',
  2 => 'counselor',
  3 => 'teacher',
  4 => 'parent',
  5 => 'student',
));
        $this->addColumn('status', 'Status', 'ENUM', false, null, 'approved');
        $this->getColumn('status', false)->setValueSet(array (
  0 => 'new',
  1 => 'approved',
  2 => 'dormant',
));
        $this->addColumn('roles', 'Roles', 'ARRAY', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('UserGroup', 'PGS\\CoreDomainBundle\\Model\\UserGroup', RelationMap::ONE_TO_MANY, array('id' => 'fos_user_id', ), null, null, 'UserGroups');
        $this->addRelation('UserLog', 'PGS\\CoreDomainBundle\\Model\\UserLog', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'SET NULL', 'CASCADE', 'UserLogs');
        $this->addRelation('Organization', 'PGS\\CoreDomainBundle\\Model\\Organization\\Organization', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'SET NULL', null, 'Organizations');
        $this->addRelation('UserProfile', 'PGS\\CoreDomainBundle\\Model\\UserProfile', RelationMap::ONE_TO_ONE, array('id' => 'id', ), 'CASCADE', null);
        $this->addRelation('Group', 'PGS\\CoreDomainBundle\\Model\\Group', RelationMap::MANY_TO_MANY, array(), null, null, 'Groups');
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
            'typehintable' =>  array (
  'last_login' => 'DateTime',
  'password_requested_at' => 'DateTime',
  'roles' => 'array',
  'fos_group' => 'FOS\\UserBundle\\Model\\GroupInterface',
),
            'timestampable' =>  array (
  'create_column' => 'created_at',
  'update_column' => 'updated_at',
  'disable_updated_at' => 'false',
),
            'delegate' =>  array (
  'to' => 'user_profile',
),
            'event' =>  array (
),
            'extend' =>  array (
),
        );
    } // getBehaviors()

} // UserTableMap
