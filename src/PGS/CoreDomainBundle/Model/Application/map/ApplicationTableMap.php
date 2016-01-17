<?php

namespace PGS\CoreDomainBundle\Model\Application\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'application' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.Application.map
 */
class ApplicationTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.Application.map.ApplicationTableMap';

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
        $this->setName('application');
        $this->setPhpName('Application');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\Application\\Application');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.Application');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('form_no', 'FormNo', 'VARCHAR', false, 20, null);
        $this->addColumn('student_nation_no', 'StudentNationNo', 'VARCHAR', false, 20, null);
        $this->addColumn('prior_test_no', 'PriorTestNo', 'VARCHAR', false, 20, null);
        $this->addColumn('student_no', 'StudentNo', 'VARCHAR', false, 20, null);
        $this->addColumn('first_name', 'FirstName', 'VARCHAR', true, 50, null);
        $this->addColumn('last_name', 'LastName', 'VARCHAR', true, 30, null);
        $this->addColumn('nick_name', 'NickName', 'VARCHAR', false, 30, null);
        $this->addColumn('phone_student', 'PhoneStudent', 'INTEGER', false, null, null);
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
        $this->addForeignKey('level_id', 'LevelId', 'INTEGER', 'level', 'id', false, null, null);
        $this->addForeignKey('grade_id', 'GradeId', 'INTEGER', 'grade', 'id', false, null, null);
        $this->addForeignKey('ethnicity_id', 'EthnicityId', 'INTEGER', 'ethnicity', 'id', true, null, null);
        $this->addColumn('child_no', 'ChildNo', 'SMALLINT', true, null, 0);
        $this->addColumn('child_total', 'ChildTotal', 'SMALLINT', true, null, 0);
        $this->addColumn('child_status', 'ChildStatus', 'ENUM', false, null, 'biological');
        $this->getColumn('child_status', false)->setValueSet(array (
  0 => 'biological',
  1 => 'step',
  2 => 'adopted',
  3 => 'other',
));
        $this->addColumn('picture', 'Picture', 'VARCHAR', false, 100, null);
        $this->addColumn('birth_certificate', 'BirthCertificate', 'VARCHAR', false, 100, null);
        $this->addColumn('family_card', 'FamilyCard', 'VARCHAR', false, 100, null);
        $this->addColumn('graduation_certificate', 'GraduationCertificate', 'VARCHAR', false, 100, null);
        $this->addColumn('address', 'Address', 'VARCHAR', false, 100, null);
        $this->addColumn('city', 'City', 'VARCHAR', false, 100, null);
        $this->addForeignKey('state_id', 'StateId', 'INTEGER', 'state', 'id', true, null, null);
        $this->addColumn('zip', 'Zip', 'VARCHAR', false, 10, null);
        $this->addForeignKey('country_id', 'CountryId', 'INTEGER', 'country', 'id', true, null, 105);
        $this->addForeignKey('school_id', 'SchoolId', 'INTEGER', 'school', 'id', false, null, null);
        $this->addForeignKey('school_year_id', 'SchoolYearId', 'INTEGER', 'school_year', 'id', false, null, null);
        $this->addColumn('status', 'Status', 'ENUM', false, null, 'partial');
        $this->getColumn('status', false)->setValueSet(array (
  0 => 'partial',
  1 => 'ready',
  2 => 'processing',
  3 => 'accepted',
  4 => 'declined',
));
        $this->addColumn('mailing_address', 'MailingAddress', 'LONGVARCHAR', false, null, null);
        $this->addColumn('note', 'Note', 'LONGVARCHAR', false, null, null);
        $this->addColumn('hobby', 'Hobby', 'LONGVARCHAR', false, null, null);
        $this->addForeignKey('entered_by', 'EnteredBy', 'INTEGER', 'fos_user', 'id', false, null, null);
        $this->addColumn('first_name_father', 'FirstNameFather', 'VARCHAR', false, 30, null);
        $this->addColumn('last_name_father', 'LastNameFather', 'VARCHAR', false, 30, null);
        $this->addColumn('business_address_father', 'BusinessAddressFather', 'VARCHAR', false, 100, null);
        $this->addColumn('occupation_father', 'OccupationFather', 'VARCHAR', false, 30, null);
        $this->addColumn('phone_father', 'PhoneFather', 'INTEGER', false, null, null);
        $this->addColumn('email_father', 'EmailFather', 'LONGVARCHAR', false, null, null);
        $this->addColumn('first_name_mother', 'FirstNameMother', 'VARCHAR', false, 30, null);
        $this->addColumn('last_name_mother', 'LastNameMother', 'VARCHAR', false, 30, null);
        $this->addColumn('business_address_mother', 'BusinessAddressMother', 'VARCHAR', false, 100, null);
        $this->addColumn('occupation_mother', 'OccupationMother', 'VARCHAR', false, 30, null);
        $this->addColumn('phone_mother', 'PhoneMother', 'INTEGER', false, null, null);
        $this->addColumn('email_mother', 'EmailMother', 'LONGVARCHAR', false, null, null);
        $this->addColumn('first_name_legal_guardian', 'FirstNameLegalGuardian', 'VARCHAR', false, 30, null);
        $this->addColumn('last_name_legal_guardian', 'LastNameLegalGuardian', 'VARCHAR', false, 30, null);
        $this->addColumn('occupation_legal_guardian', 'OccupationLegalGuardian', 'VARCHAR', false, 30, null);
        $this->addColumn('phone_legal_guardian', 'PhoneLegalGuardian', 'INTEGER', false, null, null);
        $this->addColumn('email_legal_guardian', 'EmailLegalGuardian', 'LONGVARCHAR', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', 'PGS\\CoreDomainBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('entered_by' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('School', 'PGS\\CoreDomainBundle\\Model\\School\\School', RelationMap::MANY_TO_ONE, array('school_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('SchoolYear', 'PGS\\CoreDomainBundle\\Model\\SchoolYear\\SchoolYear', RelationMap::MANY_TO_ONE, array('school_year_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('Ethnicity', 'PGS\\CoreDomainBundle\\Model\\Ethnicity\\Ethnicity', RelationMap::MANY_TO_ONE, array('ethnicity_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('Grade', 'PGS\\CoreDomainBundle\\Model\\Grade\\Grade', RelationMap::MANY_TO_ONE, array('grade_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('Level', 'PGS\\CoreDomainBundle\\Model\\Level\\Level', RelationMap::MANY_TO_ONE, array('level_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('State', 'PGS\\CoreDomainBundle\\Model\\State', RelationMap::MANY_TO_ONE, array('state_id' => 'id', ), null, null);
        $this->addRelation('Country', 'PGS\\CoreDomainBundle\\Model\\Country', RelationMap::MANY_TO_ONE, array('country_id' => 'id', ), null, null);
        $this->addRelation('ParentStudent', 'PGS\\CoreDomainBundle\\Model\\ParentStudent\\ParentStudent', RelationMap::ONE_TO_MANY, array('id' => 'application_id', ), 'CASCADE', 'CASCADE', 'ParentStudents');
        $this->addRelation('SchoolHealth', 'PGS\\CoreDomainBundle\\Model\\SchoolHealth\\SchoolHealth', RelationMap::ONE_TO_MANY, array('id' => 'application_id', ), 'SET NULL', 'CASCADE', 'SchoolHealths');
        $this->addRelation('Student', 'PGS\\CoreDomainBundle\\Model\\Student\\Student', RelationMap::ONE_TO_MANY, array('id' => 'application_id', ), 'SET NULL', 'CASCADE', 'Students');
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

} // ApplicationTableMap
