<?php

namespace PGS\CoreDomainBundle\Model\Icon\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'icon' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.Icon.map
 */
class IconTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.Icon.map.IconTableMap';

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
        $this->setName('icon');
        $this->setPhpName('Icon');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\Icon\\Icon');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.Icon');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('icon_file', 'IconFile', 'LONGVARCHAR', true, null, null);
        $this->addColumn('type', 'Type', 'ENUM', true, null, 'positive');
        $this->getColumn('type', false)->setValueSet(array (
  0 => 'positive',
  1 => 'negative',
));
        $this->addColumn('status', 'Status', 'ENUM', false, null, 'publish');
        $this->getColumn('status', false)->setValueSet(array (
  0 => 'publish',
  1 => 'unpublish',
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
        $this->addRelation('Behavior', 'PGS\\CoreDomainBundle\\Model\\Behavior\\Behavior', RelationMap::ONE_TO_MANY, array('id' => 'icon_id', ), 'SET NULL', 'CASCADE', 'Behaviors');
        $this->addRelation('IconI18n', 'PGS\\CoreDomainBundle\\Model\\Icon\\IconI18n', RelationMap::ONE_TO_MANY, array('id' => 'id', ), 'CASCADE', null, 'IconI18ns');
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
  'i18n_columns' => 'name',
  'i18n_pk_name' => NULL,
  'locale_column' => 'locale',
  'default_locale' => NULL,
  'locale_alias' => '',
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

} // IconTableMap
