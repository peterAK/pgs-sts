<?php

namespace PGS\CoreDomainBundle\Model\Message\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'message' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.Message.map
 */
class MessageTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.Message.map.MessageTableMap';

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
        $this->setName('message');
        $this->setPhpName('Message');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\Message\\Message');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.Message');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('subject', 'Subject', 'VARCHAR', false, 50, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', true, null, null);
        $this->addColumn('original_id', 'OriginalId', 'INTEGER', false, null, null);
        $this->addColumn('read', 'Read', 'BOOLEAN', true, 1, null);
        $this->addForeignKey('from_id', 'FromId', 'INTEGER', 'fos_user', 'id', true, null, null);
        $this->addForeignKey('to_id', 'ToId', 'INTEGER', 'fos_user', 'id', true, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('UserRelatedByFromId', 'PGS\\CoreDomainBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('from_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('UserRelatedByToId', 'PGS\\CoreDomainBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('to_id' => 'id', ), 'CASCADE', 'CASCADE');
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

} // MessageTableMap
