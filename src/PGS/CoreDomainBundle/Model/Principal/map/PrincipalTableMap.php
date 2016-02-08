<?php

namespace PGS\CoreDomainBundle\Model\Principal\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'principal' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.Principal.map
 */
class PrincipalTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.Principal.map.PrincipalTableMap';

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
        $this->setName('principal');
        $this->setPhpName('Principal');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\Principal\\Principal');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.Principal');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'fos_user', 'id', false, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 100, null);
        $this->addColumn('name_slug', 'NameSlug', 'VARCHAR', false, 100, null);
        $this->addColumn('goverment_license', 'GovermentLicense', 'VARCHAR', false, 30, null);
        $this->addColumn('join_at', 'JoinAt', 'DATE', false, null, null);
        $this->addColumn('address1', 'Address1', 'VARCHAR', true, 100, null);
        $this->addColumn('address2', 'Address2', 'VARCHAR', false, 100, null);
        $this->addColumn('city', 'City', 'VARCHAR', false, 100, null);
        $this->addColumn('zipcode', 'Zipcode', 'VARCHAR', false, 5, null);
        $this->addForeignKey('country_id', 'CountryId', 'INTEGER', 'country', 'id', false, null, null);
        $this->addForeignKey('state_id', 'StateId', 'INTEGER', 'state', 'id', false, null, null);
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
        $this->addColumn('is_principal', 'IsPrincipal', 'BOOLEAN', false, 1, false);
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
        $this->addRelation('State', 'PGS\\CoreDomainBundle\\Model\\State', RelationMap::MANY_TO_ONE, array('state_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('Country', 'PGS\\CoreDomainBundle\\Model\\Country', RelationMap::MANY_TO_ONE, array('country_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('UserProfile', 'PGS\\CoreDomainBundle\\Model\\UserProfile', RelationMap::ONE_TO_MANY, array('id' => 'principal_id', ), 'SET NULL', 'CASCADE', 'UserProfiles');
        $this->addRelation('Product', 'PGS\\CoreDomainBundle\\Model\\Product\\Product', RelationMap::ONE_TO_MANY, array('id' => 'principal_id', ), 'SET NULL', 'CASCADE', 'Products');
        $this->addRelation('PrincipalI18n', 'PGS\\CoreDomainBundle\\Model\\Principal\\PrincipalI18n', RelationMap::ONE_TO_MANY, array('id' => 'id', ), 'CASCADE', null, 'PrincipalI18ns');
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
  'slug_column' => 'name_slug',
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

} // PrincipalTableMap
