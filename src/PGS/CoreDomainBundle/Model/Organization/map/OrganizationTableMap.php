<?php

namespace PGS\CoreDomainBundle\Model\Organization\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'organization' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.Organization.map
 */
class OrganizationTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.Organization.map.OrganizationTableMap';

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
        $this->setName('organization');
        $this->setPhpName('Organization');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\Organization\\Organization');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.Organization');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'fos_user', 'id', false, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 100, null);
        $this->addColumn('url', 'Url', 'VARCHAR', false, 100, null);
        $this->addColumn('goverment_license', 'GovermentLicense', 'VARCHAR', false, 30, null);
        $this->addColumn('establish_at', 'EstablishAt', 'DATE', false, null, null);
        $this->addColumn('address1', 'Address1', 'VARCHAR', true, 100, null);
        $this->addColumn('address2', 'Address2', 'VARCHAR', false, 100, null);
        $this->addColumn('city', 'City', 'VARCHAR', false, 100, null);
        $this->addForeignKey('state_id', 'StateId', 'INTEGER', 'state', 'id', true, null, null);
        $this->addColumn('zipcode', 'Zipcode', 'VARCHAR', false, 5, null);
        $this->addForeignKey('country_id', 'CountryId', 'INTEGER', 'country', 'id', true, null, null);
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
  2 => 'letters',
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
        $this->addRelation('User', 'PGS\\CoreDomainBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), 'SET NULL', null);
        $this->addRelation('State', 'PGS\\CoreDomainBundle\\Model\\State', RelationMap::MANY_TO_ONE, array('state_id' => 'id', ), null, null);
        $this->addRelation('Country', 'PGS\\CoreDomainBundle\\Model\\Country', RelationMap::MANY_TO_ONE, array('country_id' => 'id', ), null, null);
        $this->addRelation('UserProfile', 'PGS\\CoreDomainBundle\\Model\\UserProfile', RelationMap::ONE_TO_MANY, array('id' => 'organization_id', ), 'SET NULL', 'CASCADE', 'UserProfiles');
        $this->addRelation('Employee', 'PGS\\CoreDomainBundle\\Model\\Employee\\Employee', RelationMap::ONE_TO_MANY, array('id' => 'organization_id', ), 'CASCADE', 'CASCADE', 'Employees');
        $this->addRelation('School', 'PGS\\CoreDomainBundle\\Model\\School\\School', RelationMap::ONE_TO_MANY, array('id' => 'organization_id', ), null, null, 'Schools');
        $this->addRelation('OrganizationI18n', 'PGS\\CoreDomainBundle\\Model\\Organization\\OrganizationI18n', RelationMap::ONE_TO_MANY, array('id' => 'id', ), 'CASCADE', null, 'OrganizationI18ns');
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
            'archivable' =>  array (
  'archive_table' => '',
  'archive_phpname' => NULL,
  'archive_class' => '',
  'log_archived_at' => 'true',
  'archived_at_column' => 'archived_at',
  'archive_on_insert' => 'false',
  'archive_on_update' => 'false',
  'archive_on_delete' => 'true',
),
            'event' =>  array (
),
            'extend' =>  array (
),
        );
    } // getBehaviors()

} // OrganizationTableMap
