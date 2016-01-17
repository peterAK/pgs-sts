<?php

namespace PGS\CoreDomainBundle\Model\Avatar\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'avatar' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.Avatar.map
 */
class AvatarTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.Avatar.map.AvatarTableMap';

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
        $this->setName('avatar');
        $this->setPhpName('Avatar');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\Avatar\\Avatar');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.Avatar');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('pic_file', 'PicFile', 'LONGVARCHAR', true, null, null);
        $this->addColumn('type', 'Type', 'ENUM', true, null, 'normal');
        $this->getColumn('type', false)->setValueSet(array (
  0 => 'normal',
  1 => 'special',
  2 => 'secret',
));
        $this->addColumn('min_point', 'MinPoint', 'INTEGER', false, null, 0);
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
        $this->addRelation('StudentAvatar', 'PGS\\CoreDomainBundle\\Model\\StudentAvatar\\StudentAvatar', RelationMap::ONE_TO_MANY, array('id' => 'avatar_id', ), 'CASCADE', 'CASCADE', 'StudentAvatars');
        $this->addRelation('AvatarI18n', 'PGS\\CoreDomainBundle\\Model\\Avatar\\AvatarI18n', RelationMap::ONE_TO_MANY, array('id' => 'id', ), 'CASCADE', null, 'AvatarI18ns');
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

} // AvatarTableMap
