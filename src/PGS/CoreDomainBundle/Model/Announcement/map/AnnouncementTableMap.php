<?php

namespace PGS\CoreDomainBundle\Model\Announcement\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'announcement' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.Announcement.map
 */
class AnnouncementTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.Announcement.map.AnnouncementTableMap';

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
        $this->setName('announcement');
        $this->setPhpName('Announcement');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\Announcement\\Announcement');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.Announcement');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('subject', 'Subject', 'VARCHAR', true, 50, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', true, null, null);
        $this->addForeignKey('posted_by', 'PostedBy', 'INTEGER', 'fos_user', 'id', true, null, null);
        $this->addColumn('recipient', 'Recipient', 'VARCHAR', true, 20, null);
        $this->addColumn('file', 'File', 'LONGVARCHAR', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', 'PGS\\CoreDomainBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('posted_by' => 'id', ), 'CASCADE', 'CASCADE');
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

} // AnnouncementTableMap
