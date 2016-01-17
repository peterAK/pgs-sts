<?php

namespace PGS\CoreDomainBundle\Model\ParentStudent\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'parent_student' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.ParentStudent.map
 */
class ParentStudentTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.ParentStudent.map.ParentStudentTableMap';

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
        $this->setName('parent_student');
        $this->setPhpName('ParentStudent');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\ParentStudent\\ParentStudent');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.ParentStudent');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('application_id', 'ApplicationId', 'INTEGER', 'application', 'id', true, null, null);
        $this->addForeignKey('student_id', 'StudentId', 'INTEGER', 'student', 'id', true, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'fos_user', 'id', true, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', 'PGS\\CoreDomainBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('Application', 'PGS\\CoreDomainBundle\\Model\\Application\\Application', RelationMap::MANY_TO_ONE, array('application_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('Student', 'PGS\\CoreDomainBundle\\Model\\Student\\Student', RelationMap::MANY_TO_ONE, array('student_id' => 'id', ), 'CASCADE', 'CASCADE');
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

} // ParentStudentTableMap
