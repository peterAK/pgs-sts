<?php

namespace PGS\CoreDomainBundle\Model\Student\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'student' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.Student.map
 */
class StudentTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.Student.map.StudentTableMap';

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
        $this->setName('student');
        $this->setPhpName('Student');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\Student\\Student');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.Student');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'fos_user', 'id', false, null, null);
        $this->addForeignKey('application_id', 'ApplicationId', 'INTEGER', 'application', 'id', false, null, null);
        $this->addForeignKey('health_id', 'HealthId', 'INTEGER', 'school_health', 'id', false, null, null);
        $this->addColumn('student_nation_no', 'StudentNationNo', 'VARCHAR', true, 20, null);
        $this->addColumn('student_no', 'StudentNo', 'VARCHAR', true, 20, null);
        $this->addColumn('first_name', 'FirstName', 'VARCHAR', true, 30, null);
        $this->addColumn('middle_name', 'MiddleName', 'VARCHAR', false, 30, null);
        $this->addColumn('last_name', 'LastName', 'VARCHAR', false, 30, null);
        $this->addColumn('nick_name', 'NickName', 'VARCHAR', false, 30, null);
        $this->addColumn('gender', 'Gender', 'ENUM', false, null, 'unknown');
        $this->getColumn('gender', false)->setValueSet(array (
  0 => 'unknown',
  1 => 'male',
  2 => 'female',
));
        $this->addColumn('place_of_birth', 'PlaceOfBirth', 'VARCHAR', false, 50, null);
        $this->addColumn('date_of_birth', 'DateOfBirth', 'DATE', false, null, null);
        $this->addColumn('religion', 'Religion', 'ENUM', false, null, 'other');
        $this->getColumn('religion', false)->setValueSet(array (
  0 => 'other',
  1 => 'islam',
  2 => 'christian',
  3 => 'catholic',
  4 => 'hindu',
  5 => 'buddhist',
  6 => 'confucian',
));
        $this->addColumn('picture', 'Picture', 'VARCHAR', false, 100, null);
        $this->addColumn('birth_certificate', 'BirthCertificate', 'VARCHAR', false, 100, null);
        $this->addColumn('family_card', 'FamilyCard', 'VARCHAR', false, 100, null);
        $this->addColumn('graduation_certificate', 'GraduationCertificate', 'VARCHAR', false, 100, null);
        $this->addColumn('authorization_code', 'AuthorizationCode', 'LONGVARCHAR', true, null, null);
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
        $this->addRelation('Application', 'PGS\\CoreDomainBundle\\Model\\Application\\Application', RelationMap::MANY_TO_ONE, array('application_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('SchoolHealth', 'PGS\\CoreDomainBundle\\Model\\SchoolHealth\\SchoolHealth', RelationMap::MANY_TO_ONE, array('health_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('ParentStudent', 'PGS\\CoreDomainBundle\\Model\\ParentStudent\\ParentStudent', RelationMap::ONE_TO_MANY, array('id' => 'student_id', ), 'CASCADE', 'CASCADE', 'ParentStudents');
        $this->addRelation('SchoolClassCourseStudentBehavior', 'PGS\\CoreDomainBundle\\Model\\SchoolClassCourseStudentBehavior\\SchoolClassCourseStudentBehavior', RelationMap::ONE_TO_MANY, array('id' => 'student_id', ), 'CASCADE', 'CASCADE', 'SchoolClassCourseStudentBehaviors');
        $this->addRelation('SchoolClassStudent', 'PGS\\CoreDomainBundle\\Model\\SchoolClassStudent\\SchoolClassStudent', RelationMap::ONE_TO_MANY, array('id' => 'student_id', ), 'CASCADE', 'CASCADE', 'SchoolClassStudents');
        $this->addRelation('SchoolEnrollment', 'PGS\\CoreDomainBundle\\Model\\SchoolEnrollment\\SchoolEnrollment', RelationMap::ONE_TO_MANY, array('id' => 'student_id', ), 'SET NULL', 'CASCADE', 'SchoolEnrollments');
        $this->addRelation('StudentHistory', 'PGS\\CoreDomainBundle\\Model\\StudentHistory\\StudentHistory', RelationMap::ONE_TO_MANY, array('id' => 'student_id', ), 'CASCADE', 'CASCADE', 'StudentHistories');
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

} // StudentTableMap
