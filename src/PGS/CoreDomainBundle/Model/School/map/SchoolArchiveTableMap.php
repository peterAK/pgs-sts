<?php

namespace PGS\CoreDomainBundle\Model\School\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'school_archive' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.School.map
 */
class SchoolArchiveTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.School.map.SchoolArchiveTableMap';

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
        $this->setName('school_archive');
        $this->setPhpName('SchoolArchive');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\School\\SchoolArchive');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.School');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('code', 'Code', 'VARCHAR', false, 20, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 100, null);
        $this->addColumn('level_id', 'LevelId', 'INTEGER', true, null, null);
        $this->addColumn('nick_name', 'NickName', 'VARCHAR', false, 10, null);
        $this->addColumn('address', 'Address', 'VARCHAR', false, 100, null);
        $this->addColumn('city', 'City', 'VARCHAR', false, 100, null);
        $this->addColumn('state_id', 'StateId', 'INTEGER', false, null, null);
        $this->addColumn('zip', 'Zip', 'VARCHAR', false, 10, null);
        $this->addColumn('country_id', 'CountryId', 'INTEGER', true, null, null);
        $this->addColumn('organization_id', 'OrganizationId', 'INTEGER', false, null, null);
        $this->addColumn('phone', 'Phone', 'VARCHAR', false, 15, null);
        $this->addColumn('fax', 'Fax', 'VARCHAR', false, 15, null);
        $this->addColumn('mobile', 'Mobile', 'VARCHAR', false, 15, null);
        $this->addColumn('email', 'Email', 'VARCHAR', false, 100, null);
        $this->addColumn('website', 'Website', 'VARCHAR', false, 100, null);
        $this->addColumn('logo', 'Logo', 'LONGVARCHAR', false, null, null);
        $this->addColumn('description', 'Description', 'CLOB', false, null, null);
        $this->addColumn('excerpt', 'Excerpt', 'VARCHAR', false, 250, null);
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
        $this->addColumn('archived_at', 'ArchivedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
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

} // SchoolArchiveTableMap
