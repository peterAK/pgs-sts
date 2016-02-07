<?php

namespace PGS\CoreDomainBundle\Model\Store\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'store' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.Store.map
 */
class StoreTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.Store.map.StoreTableMap';

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
        $this->setName('store');
        $this->setPhpName('Store');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\Store\\Store');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.Store');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 100, null);
        $this->addColumn('owner', 'Owner', 'VARCHAR', true, 100, null);
        $this->addColumn('addresss', 'Address', 'VARCHAR', true, 200, null);
        $this->addForeignKey('country_id', 'CountryId', 'INTEGER', 'country', 'id', false, null, null);
        $this->addForeignKey('state_id', 'StateId', 'INTEGER', 'state', 'id', false, null, null);
        $this->addForeignKey('region_id', 'RegionId', 'INTEGER', 'region', 'id', false, null, null);
        $this->addForeignKey('city_id', 'CityId', 'INTEGER', 'city', 'id', false, null, null);
        $this->addForeignKey('area_id', 'AreaId', 'INTEGER', 'area', 'id', false, null, null);
        $this->addColumn('zipcode', 'Zipcode', 'VARCHAR', false, 5, null);
        $this->addColumn('phone', 'Phone', 'VARCHAR', false, 15, null);
        $this->addColumn('store_type', 'StoreType', 'ENUM', false, null, 'warung');
        $this->getColumn('store_type', false)->setValueSet(array (
  0 => 'warung',
  1 => 'toko',
  2 => 'grosir',
  3 => 'lapak',
));
        $this->addColumn('description', 'Description', 'CLOB', false, null, null);
        $this->addColumn('is_active', 'IsActive', 'BOOLEAN', false, 1, true);
        $this->addColumn('url', 'Url', 'VARCHAR', false, 255, null);
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
        $this->addRelation('Country', 'PGS\\CoreDomainBundle\\Model\\Country', RelationMap::MANY_TO_ONE, array('country_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('State', 'PGS\\CoreDomainBundle\\Model\\State', RelationMap::MANY_TO_ONE, array('state_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('Region', 'PGS\\CoreDomainBundle\\Model\\Region', RelationMap::MANY_TO_ONE, array('region_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('City', 'PGS\\CoreDomainBundle\\Model\\City', RelationMap::MANY_TO_ONE, array('city_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('Area', 'PGS\\CoreDomainBundle\\Model\\Area', RelationMap::MANY_TO_ONE, array('area_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('Visitation', 'PGS\\CoreDomainBundle\\Model\\Visitation\\Visitation', RelationMap::ONE_TO_MANY, array('id' => 'store_id', ), 'SET NULL', 'CASCADE', 'Visitations');
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
            'event' =>  array (
),
            'extend' =>  array (
),
        );
    } // getBehaviors()

} // StoreTableMap
