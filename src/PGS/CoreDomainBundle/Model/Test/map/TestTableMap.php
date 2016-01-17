<?php

namespace PGS\CoreDomainBundle\Model\Test\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'test' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.Test.map
 */
class TestTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.Test.map.TestTableMap';

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
        $this->setName('test');
        $this->setPhpName('Test');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\Test\\Test');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.Test');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('status', 'Status', 'ENUM', false, null, 'new');
        $this->getColumn('status', false)->setValueSet(array (
  0 => 'new',
  1 => 'publish',
  2 => 'unpublished',
));
        $this->addForeignKey('author_id', 'AuthorId', 'INTEGER', 'fos_user', 'id', true, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', 'PGS\\CoreDomainBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('author_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('SchoolTest', 'PGS\\CoreDomainBundle\\Model\\SchoolTest\\SchoolTest', RelationMap::ONE_TO_MANY, array('id' => 'test_id', ), 'CASCADE', 'CASCADE', 'SchoolTests');
        $this->addRelation('TestI18n', 'PGS\\CoreDomainBundle\\Model\\Test\\TestI18n', RelationMap::ONE_TO_MANY, array('id' => 'id', ), 'CASCADE', null, 'TestI18ns');
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

} // TestTableMap
