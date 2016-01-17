<?php

namespace PGS\CoreDomainBundle\Model\Behavior\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'behavior' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.Behavior.map
 */
class BehaviorTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.Behavior.map.BehaviorTableMap';

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
        $this->setName('behavior');
        $this->setPhpName('Behavior');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\Behavior\\Behavior');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.Behavior');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('type', 'Type', 'ENUM', true, null, 'positive');
        $this->getColumn('type', false)->setValueSet(array (
  0 => 'positive',
  1 => 'negative',
));
        $this->addColumn('point', 'Point', 'INTEGER', true, null, 1);
        $this->addForeignKey('icon_id', 'IconId', 'INTEGER', 'icon', 'id', false, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'fos_user', 'id', false, null, null);
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
        $this->addRelation('User', 'PGS\\CoreDomainBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('Icon', 'PGS\\CoreDomainBundle\\Model\\Icon\\Icon', RelationMap::MANY_TO_ONE, array('icon_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('SchoolClassCourseStudentBehavior', 'PGS\\CoreDomainBundle\\Model\\SchoolClassCourseStudentBehavior\\SchoolClassCourseStudentBehavior', RelationMap::ONE_TO_MANY, array('id' => 'behavior_id', ), 'CASCADE', 'CASCADE', 'SchoolClassCourseStudentBehaviors');
        $this->addRelation('BehaviorI18n', 'PGS\\CoreDomainBundle\\Model\\Behavior\\BehaviorI18n', RelationMap::ONE_TO_MANY, array('id' => 'id', ), 'CASCADE', null, 'BehaviorI18ns');
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
  'i18n_columns' => 'name, description',
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

} // BehaviorTableMap
