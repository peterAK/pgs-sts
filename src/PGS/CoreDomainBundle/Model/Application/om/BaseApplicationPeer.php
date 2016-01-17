<?php

namespace PGS\CoreDomainBundle\Model\Application\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Glorpen\Propel\PropelBundle\Dispatcher\EventDispatcherProxy;
use Glorpen\Propel\PropelBundle\Events\DetectOMClassEvent;
use Glorpen\Propel\PropelBundle\Events\PeerEvent;
use PGS\CoreDomainBundle\Model\CountryPeer;
use PGS\CoreDomainBundle\Model\StatePeer;
use PGS\CoreDomainBundle\Model\UserPeer;
use PGS\CoreDomainBundle\Model\Application\Application;
use PGS\CoreDomainBundle\Model\Application\ApplicationPeer;
use PGS\CoreDomainBundle\Model\Application\map\ApplicationTableMap;
use PGS\CoreDomainBundle\Model\Ethnicity\EthnicityPeer;
use PGS\CoreDomainBundle\Model\Grade\GradePeer;
use PGS\CoreDomainBundle\Model\Level\LevelPeer;
use PGS\CoreDomainBundle\Model\ParentStudent\ParentStudentPeer;
use PGS\CoreDomainBundle\Model\School\SchoolPeer;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealthPeer;
use PGS\CoreDomainBundle\Model\SchoolYear\SchoolYearPeer;
use PGS\CoreDomainBundle\Model\Student\StudentPeer;

abstract class BaseApplicationPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'application';

    /** the related Propel class for this table */
    const OM_CLASS = 'PGS\\CoreDomainBundle\\Model\\Application\\Application';

    /** the related TableMap class for this table */
    const TM_CLASS = 'PGS\\CoreDomainBundle\\Model\\Application\\map\\ApplicationTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 54;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 54;

    /** the column name for the id field */
    const ID = 'application.id';

    /** the column name for the form_no field */
    const FORM_NO = 'application.form_no';

    /** the column name for the student_nation_no field */
    const STUDENT_NATION_NO = 'application.student_nation_no';

    /** the column name for the prior_test_no field */
    const PRIOR_TEST_NO = 'application.prior_test_no';

    /** the column name for the student_no field */
    const STUDENT_NO = 'application.student_no';

    /** the column name for the first_name field */
    const FIRST_NAME = 'application.first_name';

    /** the column name for the last_name field */
    const LAST_NAME = 'application.last_name';

    /** the column name for the nick_name field */
    const NICK_NAME = 'application.nick_name';

    /** the column name for the phone_student field */
    const PHONE_STUDENT = 'application.phone_student';

    /** the column name for the gender field */
    const GENDER = 'application.gender';

    /** the column name for the place_of_birth field */
    const PLACE_OF_BIRTH = 'application.place_of_birth';

    /** the column name for the date_of_birth field */
    const DATE_OF_BIRTH = 'application.date_of_birth';

    /** the column name for the religion field */
    const RELIGION = 'application.religion';

    /** the column name for the level_id field */
    const LEVEL_ID = 'application.level_id';

    /** the column name for the grade_id field */
    const GRADE_ID = 'application.grade_id';

    /** the column name for the ethnicity_id field */
    const ETHNICITY_ID = 'application.ethnicity_id';

    /** the column name for the child_no field */
    const CHILD_NO = 'application.child_no';

    /** the column name for the child_total field */
    const CHILD_TOTAL = 'application.child_total';

    /** the column name for the child_status field */
    const CHILD_STATUS = 'application.child_status';

    /** the column name for the picture field */
    const PICTURE = 'application.picture';

    /** the column name for the birth_certificate field */
    const BIRTH_CERTIFICATE = 'application.birth_certificate';

    /** the column name for the family_card field */
    const FAMILY_CARD = 'application.family_card';

    /** the column name for the graduation_certificate field */
    const GRADUATION_CERTIFICATE = 'application.graduation_certificate';

    /** the column name for the address field */
    const ADDRESS = 'application.address';

    /** the column name for the city field */
    const CITY = 'application.city';

    /** the column name for the state_id field */
    const STATE_ID = 'application.state_id';

    /** the column name for the zip field */
    const ZIP = 'application.zip';

    /** the column name for the country_id field */
    const COUNTRY_ID = 'application.country_id';

    /** the column name for the school_id field */
    const SCHOOL_ID = 'application.school_id';

    /** the column name for the school_year_id field */
    const SCHOOL_YEAR_ID = 'application.school_year_id';

    /** the column name for the status field */
    const STATUS = 'application.status';

    /** the column name for the mailing_address field */
    const MAILING_ADDRESS = 'application.mailing_address';

    /** the column name for the note field */
    const NOTE = 'application.note';

    /** the column name for the hobby field */
    const HOBBY = 'application.hobby';

    /** the column name for the entered_by field */
    const ENTERED_BY = 'application.entered_by';

    /** the column name for the first_name_father field */
    const FIRST_NAME_FATHER = 'application.first_name_father';

    /** the column name for the last_name_father field */
    const LAST_NAME_FATHER = 'application.last_name_father';

    /** the column name for the business_address_father field */
    const BUSINESS_ADDRESS_FATHER = 'application.business_address_father';

    /** the column name for the occupation_father field */
    const OCCUPATION_FATHER = 'application.occupation_father';

    /** the column name for the phone_father field */
    const PHONE_FATHER = 'application.phone_father';

    /** the column name for the email_father field */
    const EMAIL_FATHER = 'application.email_father';

    /** the column name for the first_name_mother field */
    const FIRST_NAME_MOTHER = 'application.first_name_mother';

    /** the column name for the last_name_mother field */
    const LAST_NAME_MOTHER = 'application.last_name_mother';

    /** the column name for the business_address_mother field */
    const BUSINESS_ADDRESS_MOTHER = 'application.business_address_mother';

    /** the column name for the occupation_mother field */
    const OCCUPATION_MOTHER = 'application.occupation_mother';

    /** the column name for the phone_mother field */
    const PHONE_MOTHER = 'application.phone_mother';

    /** the column name for the email_mother field */
    const EMAIL_MOTHER = 'application.email_mother';

    /** the column name for the first_name_legal_guardian field */
    const FIRST_NAME_LEGAL_GUARDIAN = 'application.first_name_legal_guardian';

    /** the column name for the last_name_legal_guardian field */
    const LAST_NAME_LEGAL_GUARDIAN = 'application.last_name_legal_guardian';

    /** the column name for the occupation_legal_guardian field */
    const OCCUPATION_LEGAL_GUARDIAN = 'application.occupation_legal_guardian';

    /** the column name for the phone_legal_guardian field */
    const PHONE_LEGAL_GUARDIAN = 'application.phone_legal_guardian';

    /** the column name for the email_legal_guardian field */
    const EMAIL_LEGAL_GUARDIAN = 'application.email_legal_guardian';

    /** the column name for the created_at field */
    const CREATED_AT = 'application.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'application.updated_at';

    /** The enumerated values for the gender field */
    const GENDER_UNKNOWN = 'unknown';
    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';

    /** The enumerated values for the religion field */
    const RELIGION_OTHER = 'other';
    const RELIGION_ISLAM = 'islam';
    const RELIGION_CHRISTIAN = 'christian';
    const RELIGION_CATHOLIC = 'catholic';
    const RELIGION_HINDU = 'hindu';
    const RELIGION_BUDDHIST = 'buddhist';
    const RELIGION_CONFUCIAN = 'confucian';

    /** The enumerated values for the child_status field */
    const CHILD_STATUS_BIOLOGICAL = 'biological';
    const CHILD_STATUS_STEP = 'step';
    const CHILD_STATUS_ADOPTED = 'adopted';
    const CHILD_STATUS_OTHER = 'other';

    /** The enumerated values for the status field */
    const STATUS_PARTIAL = 'partial';
    const STATUS_READY = 'ready';
    const STATUS_PROCESSING = 'processing';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of Application objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array Application[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. ApplicationPeer::$fieldNames[ApplicationPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'FormNo', 'StudentNationNo', 'PriorTestNo', 'StudentNo', 'FirstName', 'LastName', 'NickName', 'PhoneStudent', 'Gender', 'PlaceOfBirth', 'DateOfBirth', 'Religion', 'LevelId', 'GradeId', 'EthnicityId', 'ChildNo', 'ChildTotal', 'ChildStatus', 'Picture', 'BirthCertificate', 'FamilyCard', 'GraduationCertificate', 'Address', 'City', 'StateId', 'Zip', 'CountryId', 'SchoolId', 'SchoolYearId', 'Status', 'MailingAddress', 'Note', 'Hobby', 'EnteredBy', 'FirstNameFather', 'LastNameFather', 'BusinessAddressFather', 'OccupationFather', 'PhoneFather', 'EmailFather', 'FirstNameMother', 'LastNameMother', 'BusinessAddressMother', 'OccupationMother', 'PhoneMother', 'EmailMother', 'FirstNameLegalGuardian', 'LastNameLegalGuardian', 'OccupationLegalGuardian', 'PhoneLegalGuardian', 'EmailLegalGuardian', 'CreatedAt', 'UpdatedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'formNo', 'studentNationNo', 'priorTestNo', 'studentNo', 'firstName', 'lastName', 'nickName', 'phoneStudent', 'gender', 'placeOfBirth', 'dateOfBirth', 'religion', 'levelId', 'gradeId', 'ethnicityId', 'childNo', 'childTotal', 'childStatus', 'picture', 'birthCertificate', 'familyCard', 'graduationCertificate', 'address', 'city', 'stateId', 'zip', 'countryId', 'schoolId', 'schoolYearId', 'status', 'mailingAddress', 'note', 'hobby', 'enteredBy', 'firstNameFather', 'lastNameFather', 'businessAddressFather', 'occupationFather', 'phoneFather', 'emailFather', 'firstNameMother', 'lastNameMother', 'businessAddressMother', 'occupationMother', 'phoneMother', 'emailMother', 'firstNameLegalGuardian', 'lastNameLegalGuardian', 'occupationLegalGuardian', 'phoneLegalGuardian', 'emailLegalGuardian', 'createdAt', 'updatedAt', ),
        BasePeer::TYPE_COLNAME => array (ApplicationPeer::ID, ApplicationPeer::FORM_NO, ApplicationPeer::STUDENT_NATION_NO, ApplicationPeer::PRIOR_TEST_NO, ApplicationPeer::STUDENT_NO, ApplicationPeer::FIRST_NAME, ApplicationPeer::LAST_NAME, ApplicationPeer::NICK_NAME, ApplicationPeer::PHONE_STUDENT, ApplicationPeer::GENDER, ApplicationPeer::PLACE_OF_BIRTH, ApplicationPeer::DATE_OF_BIRTH, ApplicationPeer::RELIGION, ApplicationPeer::LEVEL_ID, ApplicationPeer::GRADE_ID, ApplicationPeer::ETHNICITY_ID, ApplicationPeer::CHILD_NO, ApplicationPeer::CHILD_TOTAL, ApplicationPeer::CHILD_STATUS, ApplicationPeer::PICTURE, ApplicationPeer::BIRTH_CERTIFICATE, ApplicationPeer::FAMILY_CARD, ApplicationPeer::GRADUATION_CERTIFICATE, ApplicationPeer::ADDRESS, ApplicationPeer::CITY, ApplicationPeer::STATE_ID, ApplicationPeer::ZIP, ApplicationPeer::COUNTRY_ID, ApplicationPeer::SCHOOL_ID, ApplicationPeer::SCHOOL_YEAR_ID, ApplicationPeer::STATUS, ApplicationPeer::MAILING_ADDRESS, ApplicationPeer::NOTE, ApplicationPeer::HOBBY, ApplicationPeer::ENTERED_BY, ApplicationPeer::FIRST_NAME_FATHER, ApplicationPeer::LAST_NAME_FATHER, ApplicationPeer::BUSINESS_ADDRESS_FATHER, ApplicationPeer::OCCUPATION_FATHER, ApplicationPeer::PHONE_FATHER, ApplicationPeer::EMAIL_FATHER, ApplicationPeer::FIRST_NAME_MOTHER, ApplicationPeer::LAST_NAME_MOTHER, ApplicationPeer::BUSINESS_ADDRESS_MOTHER, ApplicationPeer::OCCUPATION_MOTHER, ApplicationPeer::PHONE_MOTHER, ApplicationPeer::EMAIL_MOTHER, ApplicationPeer::FIRST_NAME_LEGAL_GUARDIAN, ApplicationPeer::LAST_NAME_LEGAL_GUARDIAN, ApplicationPeer::OCCUPATION_LEGAL_GUARDIAN, ApplicationPeer::PHONE_LEGAL_GUARDIAN, ApplicationPeer::EMAIL_LEGAL_GUARDIAN, ApplicationPeer::CREATED_AT, ApplicationPeer::UPDATED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'FORM_NO', 'STUDENT_NATION_NO', 'PRIOR_TEST_NO', 'STUDENT_NO', 'FIRST_NAME', 'LAST_NAME', 'NICK_NAME', 'PHONE_STUDENT', 'GENDER', 'PLACE_OF_BIRTH', 'DATE_OF_BIRTH', 'RELIGION', 'LEVEL_ID', 'GRADE_ID', 'ETHNICITY_ID', 'CHILD_NO', 'CHILD_TOTAL', 'CHILD_STATUS', 'PICTURE', 'BIRTH_CERTIFICATE', 'FAMILY_CARD', 'GRADUATION_CERTIFICATE', 'ADDRESS', 'CITY', 'STATE_ID', 'ZIP', 'COUNTRY_ID', 'SCHOOL_ID', 'SCHOOL_YEAR_ID', 'STATUS', 'MAILING_ADDRESS', 'NOTE', 'HOBBY', 'ENTERED_BY', 'FIRST_NAME_FATHER', 'LAST_NAME_FATHER', 'BUSINESS_ADDRESS_FATHER', 'OCCUPATION_FATHER', 'PHONE_FATHER', 'EMAIL_FATHER', 'FIRST_NAME_MOTHER', 'LAST_NAME_MOTHER', 'BUSINESS_ADDRESS_MOTHER', 'OCCUPATION_MOTHER', 'PHONE_MOTHER', 'EMAIL_MOTHER', 'FIRST_NAME_LEGAL_GUARDIAN', 'LAST_NAME_LEGAL_GUARDIAN', 'OCCUPATION_LEGAL_GUARDIAN', 'PHONE_LEGAL_GUARDIAN', 'EMAIL_LEGAL_GUARDIAN', 'CREATED_AT', 'UPDATED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'form_no', 'student_nation_no', 'prior_test_no', 'student_no', 'first_name', 'last_name', 'nick_name', 'phone_student', 'gender', 'place_of_birth', 'date_of_birth', 'religion', 'level_id', 'grade_id', 'ethnicity_id', 'child_no', 'child_total', 'child_status', 'picture', 'birth_certificate', 'family_card', 'graduation_certificate', 'address', 'city', 'state_id', 'zip', 'country_id', 'school_id', 'school_year_id', 'status', 'mailing_address', 'note', 'hobby', 'entered_by', 'first_name_father', 'last_name_father', 'business_address_father', 'occupation_father', 'phone_father', 'email_father', 'first_name_mother', 'last_name_mother', 'business_address_mother', 'occupation_mother', 'phone_mother', 'email_mother', 'first_name_legal_guardian', 'last_name_legal_guardian', 'occupation_legal_guardian', 'phone_legal_guardian', 'email_legal_guardian', 'created_at', 'updated_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. ApplicationPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'FormNo' => 1, 'StudentNationNo' => 2, 'PriorTestNo' => 3, 'StudentNo' => 4, 'FirstName' => 5, 'LastName' => 6, 'NickName' => 7, 'PhoneStudent' => 8, 'Gender' => 9, 'PlaceOfBirth' => 10, 'DateOfBirth' => 11, 'Religion' => 12, 'LevelId' => 13, 'GradeId' => 14, 'EthnicityId' => 15, 'ChildNo' => 16, 'ChildTotal' => 17, 'ChildStatus' => 18, 'Picture' => 19, 'BirthCertificate' => 20, 'FamilyCard' => 21, 'GraduationCertificate' => 22, 'Address' => 23, 'City' => 24, 'StateId' => 25, 'Zip' => 26, 'CountryId' => 27, 'SchoolId' => 28, 'SchoolYearId' => 29, 'Status' => 30, 'MailingAddress' => 31, 'Note' => 32, 'Hobby' => 33, 'EnteredBy' => 34, 'FirstNameFather' => 35, 'LastNameFather' => 36, 'BusinessAddressFather' => 37, 'OccupationFather' => 38, 'PhoneFather' => 39, 'EmailFather' => 40, 'FirstNameMother' => 41, 'LastNameMother' => 42, 'BusinessAddressMother' => 43, 'OccupationMother' => 44, 'PhoneMother' => 45, 'EmailMother' => 46, 'FirstNameLegalGuardian' => 47, 'LastNameLegalGuardian' => 48, 'OccupationLegalGuardian' => 49, 'PhoneLegalGuardian' => 50, 'EmailLegalGuardian' => 51, 'CreatedAt' => 52, 'UpdatedAt' => 53, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'formNo' => 1, 'studentNationNo' => 2, 'priorTestNo' => 3, 'studentNo' => 4, 'firstName' => 5, 'lastName' => 6, 'nickName' => 7, 'phoneStudent' => 8, 'gender' => 9, 'placeOfBirth' => 10, 'dateOfBirth' => 11, 'religion' => 12, 'levelId' => 13, 'gradeId' => 14, 'ethnicityId' => 15, 'childNo' => 16, 'childTotal' => 17, 'childStatus' => 18, 'picture' => 19, 'birthCertificate' => 20, 'familyCard' => 21, 'graduationCertificate' => 22, 'address' => 23, 'city' => 24, 'stateId' => 25, 'zip' => 26, 'countryId' => 27, 'schoolId' => 28, 'schoolYearId' => 29, 'status' => 30, 'mailingAddress' => 31, 'note' => 32, 'hobby' => 33, 'enteredBy' => 34, 'firstNameFather' => 35, 'lastNameFather' => 36, 'businessAddressFather' => 37, 'occupationFather' => 38, 'phoneFather' => 39, 'emailFather' => 40, 'firstNameMother' => 41, 'lastNameMother' => 42, 'businessAddressMother' => 43, 'occupationMother' => 44, 'phoneMother' => 45, 'emailMother' => 46, 'firstNameLegalGuardian' => 47, 'lastNameLegalGuardian' => 48, 'occupationLegalGuardian' => 49, 'phoneLegalGuardian' => 50, 'emailLegalGuardian' => 51, 'createdAt' => 52, 'updatedAt' => 53, ),
        BasePeer::TYPE_COLNAME => array (ApplicationPeer::ID => 0, ApplicationPeer::FORM_NO => 1, ApplicationPeer::STUDENT_NATION_NO => 2, ApplicationPeer::PRIOR_TEST_NO => 3, ApplicationPeer::STUDENT_NO => 4, ApplicationPeer::FIRST_NAME => 5, ApplicationPeer::LAST_NAME => 6, ApplicationPeer::NICK_NAME => 7, ApplicationPeer::PHONE_STUDENT => 8, ApplicationPeer::GENDER => 9, ApplicationPeer::PLACE_OF_BIRTH => 10, ApplicationPeer::DATE_OF_BIRTH => 11, ApplicationPeer::RELIGION => 12, ApplicationPeer::LEVEL_ID => 13, ApplicationPeer::GRADE_ID => 14, ApplicationPeer::ETHNICITY_ID => 15, ApplicationPeer::CHILD_NO => 16, ApplicationPeer::CHILD_TOTAL => 17, ApplicationPeer::CHILD_STATUS => 18, ApplicationPeer::PICTURE => 19, ApplicationPeer::BIRTH_CERTIFICATE => 20, ApplicationPeer::FAMILY_CARD => 21, ApplicationPeer::GRADUATION_CERTIFICATE => 22, ApplicationPeer::ADDRESS => 23, ApplicationPeer::CITY => 24, ApplicationPeer::STATE_ID => 25, ApplicationPeer::ZIP => 26, ApplicationPeer::COUNTRY_ID => 27, ApplicationPeer::SCHOOL_ID => 28, ApplicationPeer::SCHOOL_YEAR_ID => 29, ApplicationPeer::STATUS => 30, ApplicationPeer::MAILING_ADDRESS => 31, ApplicationPeer::NOTE => 32, ApplicationPeer::HOBBY => 33, ApplicationPeer::ENTERED_BY => 34, ApplicationPeer::FIRST_NAME_FATHER => 35, ApplicationPeer::LAST_NAME_FATHER => 36, ApplicationPeer::BUSINESS_ADDRESS_FATHER => 37, ApplicationPeer::OCCUPATION_FATHER => 38, ApplicationPeer::PHONE_FATHER => 39, ApplicationPeer::EMAIL_FATHER => 40, ApplicationPeer::FIRST_NAME_MOTHER => 41, ApplicationPeer::LAST_NAME_MOTHER => 42, ApplicationPeer::BUSINESS_ADDRESS_MOTHER => 43, ApplicationPeer::OCCUPATION_MOTHER => 44, ApplicationPeer::PHONE_MOTHER => 45, ApplicationPeer::EMAIL_MOTHER => 46, ApplicationPeer::FIRST_NAME_LEGAL_GUARDIAN => 47, ApplicationPeer::LAST_NAME_LEGAL_GUARDIAN => 48, ApplicationPeer::OCCUPATION_LEGAL_GUARDIAN => 49, ApplicationPeer::PHONE_LEGAL_GUARDIAN => 50, ApplicationPeer::EMAIL_LEGAL_GUARDIAN => 51, ApplicationPeer::CREATED_AT => 52, ApplicationPeer::UPDATED_AT => 53, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'FORM_NO' => 1, 'STUDENT_NATION_NO' => 2, 'PRIOR_TEST_NO' => 3, 'STUDENT_NO' => 4, 'FIRST_NAME' => 5, 'LAST_NAME' => 6, 'NICK_NAME' => 7, 'PHONE_STUDENT' => 8, 'GENDER' => 9, 'PLACE_OF_BIRTH' => 10, 'DATE_OF_BIRTH' => 11, 'RELIGION' => 12, 'LEVEL_ID' => 13, 'GRADE_ID' => 14, 'ETHNICITY_ID' => 15, 'CHILD_NO' => 16, 'CHILD_TOTAL' => 17, 'CHILD_STATUS' => 18, 'PICTURE' => 19, 'BIRTH_CERTIFICATE' => 20, 'FAMILY_CARD' => 21, 'GRADUATION_CERTIFICATE' => 22, 'ADDRESS' => 23, 'CITY' => 24, 'STATE_ID' => 25, 'ZIP' => 26, 'COUNTRY_ID' => 27, 'SCHOOL_ID' => 28, 'SCHOOL_YEAR_ID' => 29, 'STATUS' => 30, 'MAILING_ADDRESS' => 31, 'NOTE' => 32, 'HOBBY' => 33, 'ENTERED_BY' => 34, 'FIRST_NAME_FATHER' => 35, 'LAST_NAME_FATHER' => 36, 'BUSINESS_ADDRESS_FATHER' => 37, 'OCCUPATION_FATHER' => 38, 'PHONE_FATHER' => 39, 'EMAIL_FATHER' => 40, 'FIRST_NAME_MOTHER' => 41, 'LAST_NAME_MOTHER' => 42, 'BUSINESS_ADDRESS_MOTHER' => 43, 'OCCUPATION_MOTHER' => 44, 'PHONE_MOTHER' => 45, 'EMAIL_MOTHER' => 46, 'FIRST_NAME_LEGAL_GUARDIAN' => 47, 'LAST_NAME_LEGAL_GUARDIAN' => 48, 'OCCUPATION_LEGAL_GUARDIAN' => 49, 'PHONE_LEGAL_GUARDIAN' => 50, 'EMAIL_LEGAL_GUARDIAN' => 51, 'CREATED_AT' => 52, 'UPDATED_AT' => 53, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'form_no' => 1, 'student_nation_no' => 2, 'prior_test_no' => 3, 'student_no' => 4, 'first_name' => 5, 'last_name' => 6, 'nick_name' => 7, 'phone_student' => 8, 'gender' => 9, 'place_of_birth' => 10, 'date_of_birth' => 11, 'religion' => 12, 'level_id' => 13, 'grade_id' => 14, 'ethnicity_id' => 15, 'child_no' => 16, 'child_total' => 17, 'child_status' => 18, 'picture' => 19, 'birth_certificate' => 20, 'family_card' => 21, 'graduation_certificate' => 22, 'address' => 23, 'city' => 24, 'state_id' => 25, 'zip' => 26, 'country_id' => 27, 'school_id' => 28, 'school_year_id' => 29, 'status' => 30, 'mailing_address' => 31, 'note' => 32, 'hobby' => 33, 'entered_by' => 34, 'first_name_father' => 35, 'last_name_father' => 36, 'business_address_father' => 37, 'occupation_father' => 38, 'phone_father' => 39, 'email_father' => 40, 'first_name_mother' => 41, 'last_name_mother' => 42, 'business_address_mother' => 43, 'occupation_mother' => 44, 'phone_mother' => 45, 'email_mother' => 46, 'first_name_legal_guardian' => 47, 'last_name_legal_guardian' => 48, 'occupation_legal_guardian' => 49, 'phone_legal_guardian' => 50, 'email_legal_guardian' => 51, 'created_at' => 52, 'updated_at' => 53, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
        ApplicationPeer::GENDER => array(
            ApplicationPeer::GENDER_UNKNOWN,
            ApplicationPeer::GENDER_MALE,
            ApplicationPeer::GENDER_FEMALE,
        ),
        ApplicationPeer::RELIGION => array(
            ApplicationPeer::RELIGION_OTHER,
            ApplicationPeer::RELIGION_ISLAM,
            ApplicationPeer::RELIGION_CHRISTIAN,
            ApplicationPeer::RELIGION_CATHOLIC,
            ApplicationPeer::RELIGION_HINDU,
            ApplicationPeer::RELIGION_BUDDHIST,
            ApplicationPeer::RELIGION_CONFUCIAN,
        ),
        ApplicationPeer::CHILD_STATUS => array(
            ApplicationPeer::CHILD_STATUS_BIOLOGICAL,
            ApplicationPeer::CHILD_STATUS_STEP,
            ApplicationPeer::CHILD_STATUS_ADOPTED,
            ApplicationPeer::CHILD_STATUS_OTHER,
        ),
        ApplicationPeer::STATUS => array(
            ApplicationPeer::STATUS_PARTIAL,
            ApplicationPeer::STATUS_READY,
            ApplicationPeer::STATUS_PROCESSING,
            ApplicationPeer::STATUS_ACCEPTED,
            ApplicationPeer::STATUS_DECLINED,
        ),
    );

    /**
     * Translates a fieldname to another type
     *
     * @param      string $name field name
     * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @param      string $toType   One of the class type constants
     * @return string          translated name of the field.
     * @throws PropelException - if the specified name could not be found in the fieldname mappings.
     */
    public static function translateFieldName($name, $fromType, $toType)
    {
        $toNames = ApplicationPeer::getFieldNames($toType);
        $key = isset(ApplicationPeer::$fieldKeys[$fromType][$name]) ? ApplicationPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(ApplicationPeer::$fieldKeys[$fromType], true));
        }

        return $toNames[$key];
    }

    /**
     * Returns an array of field names.
     *
     * @param      string $type The type of fieldnames to return:
     *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @return array           A list of field names
     * @throws PropelException - if the type is not valid.
     */
    public static function getFieldNames($type = BasePeer::TYPE_PHPNAME)
    {
        if (!array_key_exists($type, ApplicationPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return ApplicationPeer::$fieldNames[$type];
    }

    /**
     * Gets the list of values for all ENUM columns
     * @return array
     */
    public static function getValueSets()
    {
      return ApplicationPeer::$enumValueSets;
    }

    /**
     * Gets the list of values for an ENUM column
     *
     * @param string $colname The ENUM column name.
     *
     * @return array list of possible values for the column
     */
    public static function getValueSet($colname)
    {
        $valueSets = ApplicationPeer::getValueSets();

        if (!isset($valueSets[$colname])) {
            throw new PropelException(sprintf('Column "%s" has no ValueSet.', $colname));
        }

        return $valueSets[$colname];
    }

    /**
     * Gets the SQL value for the ENUM column value
     *
     * @param string $colname ENUM column name.
     * @param string $enumVal ENUM value.
     *
     * @return int SQL value
     */
    public static function getSqlValueForEnum($colname, $enumVal)
    {
        $values = ApplicationPeer::getValueSet($colname);
        if (!in_array($enumVal, $values)) {
            throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $colname));
        }

        return array_search($enumVal, $values);
    }

    /**
     * Convenience method which changes table.column to alias.column.
     *
     * Using this method you can maintain SQL abstraction while using column aliases.
     * <code>
     *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
     *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
     * </code>
     * @param      string $alias The alias for the current table.
     * @param      string $column The column name for current table. (i.e. ApplicationPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(ApplicationPeer::TABLE_NAME.'.', $alias.'.', $column);
    }

    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param      Criteria $criteria object containing the columns to add.
     * @param      string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(ApplicationPeer::ID);
            $criteria->addSelectColumn(ApplicationPeer::FORM_NO);
            $criteria->addSelectColumn(ApplicationPeer::STUDENT_NATION_NO);
            $criteria->addSelectColumn(ApplicationPeer::PRIOR_TEST_NO);
            $criteria->addSelectColumn(ApplicationPeer::STUDENT_NO);
            $criteria->addSelectColumn(ApplicationPeer::FIRST_NAME);
            $criteria->addSelectColumn(ApplicationPeer::LAST_NAME);
            $criteria->addSelectColumn(ApplicationPeer::NICK_NAME);
            $criteria->addSelectColumn(ApplicationPeer::PHONE_STUDENT);
            $criteria->addSelectColumn(ApplicationPeer::GENDER);
            $criteria->addSelectColumn(ApplicationPeer::PLACE_OF_BIRTH);
            $criteria->addSelectColumn(ApplicationPeer::DATE_OF_BIRTH);
            $criteria->addSelectColumn(ApplicationPeer::RELIGION);
            $criteria->addSelectColumn(ApplicationPeer::LEVEL_ID);
            $criteria->addSelectColumn(ApplicationPeer::GRADE_ID);
            $criteria->addSelectColumn(ApplicationPeer::ETHNICITY_ID);
            $criteria->addSelectColumn(ApplicationPeer::CHILD_NO);
            $criteria->addSelectColumn(ApplicationPeer::CHILD_TOTAL);
            $criteria->addSelectColumn(ApplicationPeer::CHILD_STATUS);
            $criteria->addSelectColumn(ApplicationPeer::PICTURE);
            $criteria->addSelectColumn(ApplicationPeer::BIRTH_CERTIFICATE);
            $criteria->addSelectColumn(ApplicationPeer::FAMILY_CARD);
            $criteria->addSelectColumn(ApplicationPeer::GRADUATION_CERTIFICATE);
            $criteria->addSelectColumn(ApplicationPeer::ADDRESS);
            $criteria->addSelectColumn(ApplicationPeer::CITY);
            $criteria->addSelectColumn(ApplicationPeer::STATE_ID);
            $criteria->addSelectColumn(ApplicationPeer::ZIP);
            $criteria->addSelectColumn(ApplicationPeer::COUNTRY_ID);
            $criteria->addSelectColumn(ApplicationPeer::SCHOOL_ID);
            $criteria->addSelectColumn(ApplicationPeer::SCHOOL_YEAR_ID);
            $criteria->addSelectColumn(ApplicationPeer::STATUS);
            $criteria->addSelectColumn(ApplicationPeer::MAILING_ADDRESS);
            $criteria->addSelectColumn(ApplicationPeer::NOTE);
            $criteria->addSelectColumn(ApplicationPeer::HOBBY);
            $criteria->addSelectColumn(ApplicationPeer::ENTERED_BY);
            $criteria->addSelectColumn(ApplicationPeer::FIRST_NAME_FATHER);
            $criteria->addSelectColumn(ApplicationPeer::LAST_NAME_FATHER);
            $criteria->addSelectColumn(ApplicationPeer::BUSINESS_ADDRESS_FATHER);
            $criteria->addSelectColumn(ApplicationPeer::OCCUPATION_FATHER);
            $criteria->addSelectColumn(ApplicationPeer::PHONE_FATHER);
            $criteria->addSelectColumn(ApplicationPeer::EMAIL_FATHER);
            $criteria->addSelectColumn(ApplicationPeer::FIRST_NAME_MOTHER);
            $criteria->addSelectColumn(ApplicationPeer::LAST_NAME_MOTHER);
            $criteria->addSelectColumn(ApplicationPeer::BUSINESS_ADDRESS_MOTHER);
            $criteria->addSelectColumn(ApplicationPeer::OCCUPATION_MOTHER);
            $criteria->addSelectColumn(ApplicationPeer::PHONE_MOTHER);
            $criteria->addSelectColumn(ApplicationPeer::EMAIL_MOTHER);
            $criteria->addSelectColumn(ApplicationPeer::FIRST_NAME_LEGAL_GUARDIAN);
            $criteria->addSelectColumn(ApplicationPeer::LAST_NAME_LEGAL_GUARDIAN);
            $criteria->addSelectColumn(ApplicationPeer::OCCUPATION_LEGAL_GUARDIAN);
            $criteria->addSelectColumn(ApplicationPeer::PHONE_LEGAL_GUARDIAN);
            $criteria->addSelectColumn(ApplicationPeer::EMAIL_LEGAL_GUARDIAN);
            $criteria->addSelectColumn(ApplicationPeer::CREATED_AT);
            $criteria->addSelectColumn(ApplicationPeer::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.form_no');
            $criteria->addSelectColumn($alias . '.student_nation_no');
            $criteria->addSelectColumn($alias . '.prior_test_no');
            $criteria->addSelectColumn($alias . '.student_no');
            $criteria->addSelectColumn($alias . '.first_name');
            $criteria->addSelectColumn($alias . '.last_name');
            $criteria->addSelectColumn($alias . '.nick_name');
            $criteria->addSelectColumn($alias . '.phone_student');
            $criteria->addSelectColumn($alias . '.gender');
            $criteria->addSelectColumn($alias . '.place_of_birth');
            $criteria->addSelectColumn($alias . '.date_of_birth');
            $criteria->addSelectColumn($alias . '.religion');
            $criteria->addSelectColumn($alias . '.level_id');
            $criteria->addSelectColumn($alias . '.grade_id');
            $criteria->addSelectColumn($alias . '.ethnicity_id');
            $criteria->addSelectColumn($alias . '.child_no');
            $criteria->addSelectColumn($alias . '.child_total');
            $criteria->addSelectColumn($alias . '.child_status');
            $criteria->addSelectColumn($alias . '.picture');
            $criteria->addSelectColumn($alias . '.birth_certificate');
            $criteria->addSelectColumn($alias . '.family_card');
            $criteria->addSelectColumn($alias . '.graduation_certificate');
            $criteria->addSelectColumn($alias . '.address');
            $criteria->addSelectColumn($alias . '.city');
            $criteria->addSelectColumn($alias . '.state_id');
            $criteria->addSelectColumn($alias . '.zip');
            $criteria->addSelectColumn($alias . '.country_id');
            $criteria->addSelectColumn($alias . '.school_id');
            $criteria->addSelectColumn($alias . '.school_year_id');
            $criteria->addSelectColumn($alias . '.status');
            $criteria->addSelectColumn($alias . '.mailing_address');
            $criteria->addSelectColumn($alias . '.note');
            $criteria->addSelectColumn($alias . '.hobby');
            $criteria->addSelectColumn($alias . '.entered_by');
            $criteria->addSelectColumn($alias . '.first_name_father');
            $criteria->addSelectColumn($alias . '.last_name_father');
            $criteria->addSelectColumn($alias . '.business_address_father');
            $criteria->addSelectColumn($alias . '.occupation_father');
            $criteria->addSelectColumn($alias . '.phone_father');
            $criteria->addSelectColumn($alias . '.email_father');
            $criteria->addSelectColumn($alias . '.first_name_mother');
            $criteria->addSelectColumn($alias . '.last_name_mother');
            $criteria->addSelectColumn($alias . '.business_address_mother');
            $criteria->addSelectColumn($alias . '.occupation_mother');
            $criteria->addSelectColumn($alias . '.phone_mother');
            $criteria->addSelectColumn($alias . '.email_mother');
            $criteria->addSelectColumn($alias . '.first_name_legal_guardian');
            $criteria->addSelectColumn($alias . '.last_name_legal_guardian');
            $criteria->addSelectColumn($alias . '.occupation_legal_guardian');
            $criteria->addSelectColumn($alias . '.phone_legal_guardian');
            $criteria->addSelectColumn($alias . '.email_legal_guardian');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
        }
    }

    /**
     * Returns the number of rows matching criteria.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @return int Number of matching rows.
     */
    public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
    {
        // we may modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ApplicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ApplicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(ApplicationPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        // BasePeer returns a PDOStatement
        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }
    /**
     * Selects one object from the DB.
     *
     * @param      Criteria $criteria object used to create the SELECT statement.
     * @param      PropelPDO $con
     * @return Application
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = ApplicationPeer::doSelect($critcopy, $con);
        if ($objects) {
            return $objects[0];
        }

        return null;
    }
    /**
     * Selects several row from the DB.
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con
     * @return array           Array of selected Objects
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelect(Criteria $criteria, PropelPDO $con = null)
    {
        return ApplicationPeer::populateObjects(ApplicationPeer::doSelectStmt($criteria, $con));
    }
    /**
     * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
     *
     * Use this method directly if you want to work with an executed statement directly (for example
     * to perform your own object hydration).
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con The connection to use
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return PDOStatement The executed PDOStatement object.
     * @see        BasePeer::doSelect()
     */
    public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            ApplicationPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(ApplicationPeer::DATABASE_NAME);

        // BasePeer returns a PDOStatement
        return BasePeer::doSelect($criteria, $con);
    }
    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doSelect*()
     * methods in your stub classes -- you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by doSelect*()
     * and retrieveByPK*() calls.
     *
     * @param Application $obj A Application object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            ApplicationPeer::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param      mixed $value A Application object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof Application) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or Application object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(ApplicationPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return Application Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(ApplicationPeer::$instances[$key])) {
                return ApplicationPeer::$instances[$key];
            }
        }

        return null; // just to be explicit
    }

    /**
     * Clear the instance pool.
     *
     * @return void
     */
    public static function clearInstancePool($and_clear_all_references = false)
    {
      if ($and_clear_all_references) {
        foreach (ApplicationPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        ApplicationPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to application
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in ParentStudentPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        ParentStudentPeer::clearInstancePool();
        // Invalidate objects in SchoolHealthPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        SchoolHealthPeer::clearInstancePool();
        // Invalidate objects in StudentPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        StudentPeer::clearInstancePool();
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return string A string version of PK or null if the components of primary key in result array are all null.
     */
    public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
    {
        // If the PK cannot be derived from the row, return null.
        if ($row[$startcol] === null) {
            return null;
        }

        return (string) $row[$startcol];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $startcol = 0)
    {

        return (int) $row[$startcol];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function populateObjects(PDOStatement $stmt)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = ApplicationPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = ApplicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = ApplicationPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ApplicationPeer::addInstanceToPool($obj, $key);
            } // if key exists
        }
        $stmt->closeCursor();

        return $results;
    }
    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return array (Application object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = ApplicationPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = ApplicationPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + ApplicationPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ApplicationPeer::getOMClass($row, $startcol);
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            ApplicationPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * Gets the SQL value for Gender ENUM value
     *
     * @param  string $enumVal ENUM value to get SQL value for
     * @return int SQL value
     */
    public static function getGenderSqlValue($enumVal)
    {
        return ApplicationPeer::getSqlValueForEnum(ApplicationPeer::GENDER, $enumVal);
    }

    /**
     * Gets the SQL value for Religion ENUM value
     *
     * @param  string $enumVal ENUM value to get SQL value for
     * @return int SQL value
     */
    public static function getReligionSqlValue($enumVal)
    {
        return ApplicationPeer::getSqlValueForEnum(ApplicationPeer::RELIGION, $enumVal);
    }

    /**
     * Gets the SQL value for ChildStatus ENUM value
     *
     * @param  string $enumVal ENUM value to get SQL value for
     * @return int SQL value
     */
    public static function getChildStatusSqlValue($enumVal)
    {
        return ApplicationPeer::getSqlValueForEnum(ApplicationPeer::CHILD_STATUS, $enumVal);
    }

    /**
     * Gets the SQL value for Status ENUM value
     *
     * @param  string $enumVal ENUM value to get SQL value for
     * @return int SQL value
     */
    public static function getStatusSqlValue($enumVal)
    {
        return ApplicationPeer::getSqlValueForEnum(ApplicationPeer::STATUS, $enumVal);
    }


    /**
     * Returns the number of rows matching criteria, joining the related User table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinUser(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ApplicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ApplicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ApplicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ApplicationPeer::ENTERED_BY, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related School table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinSchool(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ApplicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ApplicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ApplicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ApplicationPeer::SCHOOL_ID, SchoolPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related SchoolYear table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinSchoolYear(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ApplicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ApplicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ApplicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ApplicationPeer::SCHOOL_YEAR_ID, SchoolYearPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Ethnicity table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinEthnicity(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ApplicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ApplicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ApplicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ApplicationPeer::ETHNICITY_ID, EthnicityPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Grade table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinGrade(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ApplicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ApplicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ApplicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ApplicationPeer::GRADE_ID, GradePeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Level table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinLevel(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ApplicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ApplicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ApplicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ApplicationPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related State table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinState(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ApplicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ApplicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ApplicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ApplicationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Country table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinCountry(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ApplicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ApplicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ApplicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ApplicationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of Application objects pre-filled with their User objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Application objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ApplicationPeer::DATABASE_NAME);
        }

        ApplicationPeer::addSelectColumns($criteria);
        $startcol = ApplicationPeer::NUM_HYDRATE_COLUMNS;
        UserPeer::addSelectColumns($criteria);

        $criteria->addJoin(ApplicationPeer::ENTERED_BY, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ApplicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ApplicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ApplicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ApplicationPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = UserPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Application) to $obj2 (User)
                $obj2->addApplication($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Application objects pre-filled with their School objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Application objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinSchool(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ApplicationPeer::DATABASE_NAME);
        }

        ApplicationPeer::addSelectColumns($criteria);
        $startcol = ApplicationPeer::NUM_HYDRATE_COLUMNS;
        SchoolPeer::addSelectColumns($criteria);

        $criteria->addJoin(ApplicationPeer::SCHOOL_ID, SchoolPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ApplicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ApplicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ApplicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ApplicationPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = SchoolPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = SchoolPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = SchoolPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    SchoolPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Application) to $obj2 (School)
                $obj2->addApplication($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Application objects pre-filled with their SchoolYear objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Application objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinSchoolYear(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ApplicationPeer::DATABASE_NAME);
        }

        ApplicationPeer::addSelectColumns($criteria);
        $startcol = ApplicationPeer::NUM_HYDRATE_COLUMNS;
        SchoolYearPeer::addSelectColumns($criteria);

        $criteria->addJoin(ApplicationPeer::SCHOOL_YEAR_ID, SchoolYearPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ApplicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ApplicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ApplicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ApplicationPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = SchoolYearPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = SchoolYearPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = SchoolYearPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    SchoolYearPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Application) to $obj2 (SchoolYear)
                $obj2->addApplication($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Application objects pre-filled with their Ethnicity objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Application objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinEthnicity(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ApplicationPeer::DATABASE_NAME);
        }

        ApplicationPeer::addSelectColumns($criteria);
        $startcol = ApplicationPeer::NUM_HYDRATE_COLUMNS;
        EthnicityPeer::addSelectColumns($criteria);

        $criteria->addJoin(ApplicationPeer::ETHNICITY_ID, EthnicityPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ApplicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ApplicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ApplicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ApplicationPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = EthnicityPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = EthnicityPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = EthnicityPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    EthnicityPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Application) to $obj2 (Ethnicity)
                $obj2->addApplication($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Application objects pre-filled with their Grade objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Application objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinGrade(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ApplicationPeer::DATABASE_NAME);
        }

        ApplicationPeer::addSelectColumns($criteria);
        $startcol = ApplicationPeer::NUM_HYDRATE_COLUMNS;
        GradePeer::addSelectColumns($criteria);

        $criteria->addJoin(ApplicationPeer::GRADE_ID, GradePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ApplicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ApplicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ApplicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ApplicationPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = GradePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = GradePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = GradePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    GradePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Application) to $obj2 (Grade)
                $obj2->addApplication($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Application objects pre-filled with their Level objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Application objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinLevel(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ApplicationPeer::DATABASE_NAME);
        }

        ApplicationPeer::addSelectColumns($criteria);
        $startcol = ApplicationPeer::NUM_HYDRATE_COLUMNS;
        LevelPeer::addSelectColumns($criteria);

        $criteria->addJoin(ApplicationPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ApplicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ApplicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ApplicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ApplicationPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = LevelPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = LevelPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = LevelPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    LevelPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Application) to $obj2 (Level)
                $obj2->addApplication($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Application objects pre-filled with their State objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Application objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinState(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ApplicationPeer::DATABASE_NAME);
        }

        ApplicationPeer::addSelectColumns($criteria);
        $startcol = ApplicationPeer::NUM_HYDRATE_COLUMNS;
        StatePeer::addSelectColumns($criteria);

        $criteria->addJoin(ApplicationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ApplicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ApplicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ApplicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ApplicationPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = StatePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = StatePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = StatePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    StatePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Application) to $obj2 (State)
                $obj2->addApplication($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Application objects pre-filled with their Country objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Application objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinCountry(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ApplicationPeer::DATABASE_NAME);
        }

        ApplicationPeer::addSelectColumns($criteria);
        $startcol = ApplicationPeer::NUM_HYDRATE_COLUMNS;
        CountryPeer::addSelectColumns($criteria);

        $criteria->addJoin(ApplicationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ApplicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ApplicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ApplicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ApplicationPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = CountryPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = CountryPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = CountryPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    CountryPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Application) to $obj2 (Country)
                $obj2->addApplication($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining all related tables
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ApplicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ApplicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ApplicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ApplicationPeer::ENTERED_BY, UserPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_ID, SchoolPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_YEAR_ID, SchoolYearPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::ETHNICITY_ID, EthnicityPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::GRADE_ID, GradePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }

    /**
     * Selects a collection of Application objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Application objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ApplicationPeer::DATABASE_NAME);
        }

        ApplicationPeer::addSelectColumns($criteria);
        $startcol2 = ApplicationPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserPeer::NUM_HYDRATE_COLUMNS;

        SchoolPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SchoolPeer::NUM_HYDRATE_COLUMNS;

        SchoolYearPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + SchoolYearPeer::NUM_HYDRATE_COLUMNS;

        EthnicityPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + EthnicityPeer::NUM_HYDRATE_COLUMNS;

        GradePeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + GradePeer::NUM_HYDRATE_COLUMNS;

        LevelPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + LevelPeer::NUM_HYDRATE_COLUMNS;

        StatePeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + StatePeer::NUM_HYDRATE_COLUMNS;

        CountryPeer::addSelectColumns($criteria);
        $startcol10 = $startcol9 + CountryPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ApplicationPeer::ENTERED_BY, UserPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_ID, SchoolPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_YEAR_ID, SchoolYearPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::ETHNICITY_ID, EthnicityPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::GRADE_ID, GradePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ApplicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ApplicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ApplicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ApplicationPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined User rows

            $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = UserPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (Application) to the collection in $obj2 (User)
                $obj2->addApplication($obj1);
            } // if joined row not null

            // Add objects for joined School rows

            $key3 = SchoolPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = SchoolPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = SchoolPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SchoolPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (Application) to the collection in $obj3 (School)
                $obj3->addApplication($obj1);
            } // if joined row not null

            // Add objects for joined SchoolYear rows

            $key4 = SchoolYearPeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = SchoolYearPeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = SchoolYearPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    SchoolYearPeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (Application) to the collection in $obj4 (SchoolYear)
                $obj4->addApplication($obj1);
            } // if joined row not null

            // Add objects for joined Ethnicity rows

            $key5 = EthnicityPeer::getPrimaryKeyHashFromRow($row, $startcol5);
            if ($key5 !== null) {
                $obj5 = EthnicityPeer::getInstanceFromPool($key5);
                if (!$obj5) {

                    $cls = EthnicityPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    EthnicityPeer::addInstanceToPool($obj5, $key5);
                } // if obj5 loaded

                // Add the $obj1 (Application) to the collection in $obj5 (Ethnicity)
                $obj5->addApplication($obj1);
            } // if joined row not null

            // Add objects for joined Grade rows

            $key6 = GradePeer::getPrimaryKeyHashFromRow($row, $startcol6);
            if ($key6 !== null) {
                $obj6 = GradePeer::getInstanceFromPool($key6);
                if (!$obj6) {

                    $cls = GradePeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    GradePeer::addInstanceToPool($obj6, $key6);
                } // if obj6 loaded

                // Add the $obj1 (Application) to the collection in $obj6 (Grade)
                $obj6->addApplication($obj1);
            } // if joined row not null

            // Add objects for joined Level rows

            $key7 = LevelPeer::getPrimaryKeyHashFromRow($row, $startcol7);
            if ($key7 !== null) {
                $obj7 = LevelPeer::getInstanceFromPool($key7);
                if (!$obj7) {

                    $cls = LevelPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    LevelPeer::addInstanceToPool($obj7, $key7);
                } // if obj7 loaded

                // Add the $obj1 (Application) to the collection in $obj7 (Level)
                $obj7->addApplication($obj1);
            } // if joined row not null

            // Add objects for joined State rows

            $key8 = StatePeer::getPrimaryKeyHashFromRow($row, $startcol8);
            if ($key8 !== null) {
                $obj8 = StatePeer::getInstanceFromPool($key8);
                if (!$obj8) {

                    $cls = StatePeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    StatePeer::addInstanceToPool($obj8, $key8);
                } // if obj8 loaded

                // Add the $obj1 (Application) to the collection in $obj8 (State)
                $obj8->addApplication($obj1);
            } // if joined row not null

            // Add objects for joined Country rows

            $key9 = CountryPeer::getPrimaryKeyHashFromRow($row, $startcol9);
            if ($key9 !== null) {
                $obj9 = CountryPeer::getInstanceFromPool($key9);
                if (!$obj9) {

                    $cls = CountryPeer::getOMClass();

                    $obj9 = new $cls();
                    $obj9->hydrate($row, $startcol9);
                    CountryPeer::addInstanceToPool($obj9, $key9);
                } // if obj9 loaded

                // Add the $obj1 (Application) to the collection in $obj9 (Country)
                $obj9->addApplication($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related User table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptUser(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ApplicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ApplicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(ApplicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ApplicationPeer::SCHOOL_ID, SchoolPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_YEAR_ID, SchoolYearPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::ETHNICITY_ID, EthnicityPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::GRADE_ID, GradePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related School table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptSchool(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ApplicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ApplicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(ApplicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ApplicationPeer::ENTERED_BY, UserPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_YEAR_ID, SchoolYearPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::ETHNICITY_ID, EthnicityPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::GRADE_ID, GradePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related SchoolYear table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptSchoolYear(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ApplicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ApplicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(ApplicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ApplicationPeer::ENTERED_BY, UserPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_ID, SchoolPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::ETHNICITY_ID, EthnicityPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::GRADE_ID, GradePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Ethnicity table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptEthnicity(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ApplicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ApplicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(ApplicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ApplicationPeer::ENTERED_BY, UserPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_ID, SchoolPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_YEAR_ID, SchoolYearPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::GRADE_ID, GradePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Grade table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptGrade(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ApplicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ApplicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(ApplicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ApplicationPeer::ENTERED_BY, UserPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_ID, SchoolPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_YEAR_ID, SchoolYearPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::ETHNICITY_ID, EthnicityPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Level table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptLevel(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ApplicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ApplicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(ApplicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ApplicationPeer::ENTERED_BY, UserPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_ID, SchoolPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_YEAR_ID, SchoolYearPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::ETHNICITY_ID, EthnicityPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::GRADE_ID, GradePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related State table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptState(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ApplicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ApplicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(ApplicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ApplicationPeer::ENTERED_BY, UserPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_ID, SchoolPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_YEAR_ID, SchoolYearPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::ETHNICITY_ID, EthnicityPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::GRADE_ID, GradePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Country table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptCountry(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ApplicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ApplicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(ApplicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ApplicationPeer::ENTERED_BY, UserPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_ID, SchoolPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_YEAR_ID, SchoolYearPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::ETHNICITY_ID, EthnicityPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::GRADE_ID, GradePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of Application objects pre-filled with all related objects except User.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Application objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ApplicationPeer::DATABASE_NAME);
        }

        ApplicationPeer::addSelectColumns($criteria);
        $startcol2 = ApplicationPeer::NUM_HYDRATE_COLUMNS;

        SchoolPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + SchoolPeer::NUM_HYDRATE_COLUMNS;

        SchoolYearPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SchoolYearPeer::NUM_HYDRATE_COLUMNS;

        EthnicityPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + EthnicityPeer::NUM_HYDRATE_COLUMNS;

        GradePeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + GradePeer::NUM_HYDRATE_COLUMNS;

        LevelPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + LevelPeer::NUM_HYDRATE_COLUMNS;

        StatePeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + StatePeer::NUM_HYDRATE_COLUMNS;

        CountryPeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + CountryPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ApplicationPeer::SCHOOL_ID, SchoolPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_YEAR_ID, SchoolYearPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::ETHNICITY_ID, EthnicityPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::GRADE_ID, GradePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ApplicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ApplicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ApplicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ApplicationPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined School rows

                $key2 = SchoolPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = SchoolPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = SchoolPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    SchoolPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Application) to the collection in $obj2 (School)
                $obj2->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolYear rows

                $key3 = SchoolYearPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = SchoolYearPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = SchoolYearPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SchoolYearPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Application) to the collection in $obj3 (SchoolYear)
                $obj3->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Ethnicity rows

                $key4 = EthnicityPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = EthnicityPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = EthnicityPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    EthnicityPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Application) to the collection in $obj4 (Ethnicity)
                $obj4->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Grade rows

                $key5 = GradePeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = GradePeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = GradePeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    GradePeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Application) to the collection in $obj5 (Grade)
                $obj5->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Level rows

                $key6 = LevelPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = LevelPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = LevelPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    LevelPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Application) to the collection in $obj6 (Level)
                $obj6->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined State rows

                $key7 = StatePeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = StatePeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = StatePeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    StatePeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Application) to the collection in $obj7 (State)
                $obj7->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Country rows

                $key8 = CountryPeer::getPrimaryKeyHashFromRow($row, $startcol8);
                if ($key8 !== null) {
                    $obj8 = CountryPeer::getInstanceFromPool($key8);
                    if (!$obj8) {

                        $cls = CountryPeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    CountryPeer::addInstanceToPool($obj8, $key8);
                } // if $obj8 already loaded

                // Add the $obj1 (Application) to the collection in $obj8 (Country)
                $obj8->addApplication($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Application objects pre-filled with all related objects except School.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Application objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptSchool(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ApplicationPeer::DATABASE_NAME);
        }

        ApplicationPeer::addSelectColumns($criteria);
        $startcol2 = ApplicationPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserPeer::NUM_HYDRATE_COLUMNS;

        SchoolYearPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SchoolYearPeer::NUM_HYDRATE_COLUMNS;

        EthnicityPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + EthnicityPeer::NUM_HYDRATE_COLUMNS;

        GradePeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + GradePeer::NUM_HYDRATE_COLUMNS;

        LevelPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + LevelPeer::NUM_HYDRATE_COLUMNS;

        StatePeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + StatePeer::NUM_HYDRATE_COLUMNS;

        CountryPeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + CountryPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ApplicationPeer::ENTERED_BY, UserPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_YEAR_ID, SchoolYearPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::ETHNICITY_ID, EthnicityPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::GRADE_ID, GradePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ApplicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ApplicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ApplicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ApplicationPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined User rows

                $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = UserPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Application) to the collection in $obj2 (User)
                $obj2->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolYear rows

                $key3 = SchoolYearPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = SchoolYearPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = SchoolYearPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SchoolYearPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Application) to the collection in $obj3 (SchoolYear)
                $obj3->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Ethnicity rows

                $key4 = EthnicityPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = EthnicityPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = EthnicityPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    EthnicityPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Application) to the collection in $obj4 (Ethnicity)
                $obj4->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Grade rows

                $key5 = GradePeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = GradePeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = GradePeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    GradePeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Application) to the collection in $obj5 (Grade)
                $obj5->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Level rows

                $key6 = LevelPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = LevelPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = LevelPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    LevelPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Application) to the collection in $obj6 (Level)
                $obj6->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined State rows

                $key7 = StatePeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = StatePeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = StatePeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    StatePeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Application) to the collection in $obj7 (State)
                $obj7->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Country rows

                $key8 = CountryPeer::getPrimaryKeyHashFromRow($row, $startcol8);
                if ($key8 !== null) {
                    $obj8 = CountryPeer::getInstanceFromPool($key8);
                    if (!$obj8) {

                        $cls = CountryPeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    CountryPeer::addInstanceToPool($obj8, $key8);
                } // if $obj8 already loaded

                // Add the $obj1 (Application) to the collection in $obj8 (Country)
                $obj8->addApplication($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Application objects pre-filled with all related objects except SchoolYear.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Application objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptSchoolYear(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ApplicationPeer::DATABASE_NAME);
        }

        ApplicationPeer::addSelectColumns($criteria);
        $startcol2 = ApplicationPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserPeer::NUM_HYDRATE_COLUMNS;

        SchoolPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SchoolPeer::NUM_HYDRATE_COLUMNS;

        EthnicityPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + EthnicityPeer::NUM_HYDRATE_COLUMNS;

        GradePeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + GradePeer::NUM_HYDRATE_COLUMNS;

        LevelPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + LevelPeer::NUM_HYDRATE_COLUMNS;

        StatePeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + StatePeer::NUM_HYDRATE_COLUMNS;

        CountryPeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + CountryPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ApplicationPeer::ENTERED_BY, UserPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_ID, SchoolPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::ETHNICITY_ID, EthnicityPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::GRADE_ID, GradePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ApplicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ApplicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ApplicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ApplicationPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined User rows

                $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = UserPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Application) to the collection in $obj2 (User)
                $obj2->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined School rows

                $key3 = SchoolPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = SchoolPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = SchoolPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SchoolPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Application) to the collection in $obj3 (School)
                $obj3->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Ethnicity rows

                $key4 = EthnicityPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = EthnicityPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = EthnicityPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    EthnicityPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Application) to the collection in $obj4 (Ethnicity)
                $obj4->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Grade rows

                $key5 = GradePeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = GradePeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = GradePeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    GradePeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Application) to the collection in $obj5 (Grade)
                $obj5->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Level rows

                $key6 = LevelPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = LevelPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = LevelPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    LevelPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Application) to the collection in $obj6 (Level)
                $obj6->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined State rows

                $key7 = StatePeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = StatePeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = StatePeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    StatePeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Application) to the collection in $obj7 (State)
                $obj7->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Country rows

                $key8 = CountryPeer::getPrimaryKeyHashFromRow($row, $startcol8);
                if ($key8 !== null) {
                    $obj8 = CountryPeer::getInstanceFromPool($key8);
                    if (!$obj8) {

                        $cls = CountryPeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    CountryPeer::addInstanceToPool($obj8, $key8);
                } // if $obj8 already loaded

                // Add the $obj1 (Application) to the collection in $obj8 (Country)
                $obj8->addApplication($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Application objects pre-filled with all related objects except Ethnicity.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Application objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptEthnicity(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ApplicationPeer::DATABASE_NAME);
        }

        ApplicationPeer::addSelectColumns($criteria);
        $startcol2 = ApplicationPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserPeer::NUM_HYDRATE_COLUMNS;

        SchoolPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SchoolPeer::NUM_HYDRATE_COLUMNS;

        SchoolYearPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + SchoolYearPeer::NUM_HYDRATE_COLUMNS;

        GradePeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + GradePeer::NUM_HYDRATE_COLUMNS;

        LevelPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + LevelPeer::NUM_HYDRATE_COLUMNS;

        StatePeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + StatePeer::NUM_HYDRATE_COLUMNS;

        CountryPeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + CountryPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ApplicationPeer::ENTERED_BY, UserPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_ID, SchoolPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_YEAR_ID, SchoolYearPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::GRADE_ID, GradePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ApplicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ApplicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ApplicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ApplicationPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined User rows

                $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = UserPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Application) to the collection in $obj2 (User)
                $obj2->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined School rows

                $key3 = SchoolPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = SchoolPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = SchoolPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SchoolPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Application) to the collection in $obj3 (School)
                $obj3->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolYear rows

                $key4 = SchoolYearPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = SchoolYearPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = SchoolYearPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    SchoolYearPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Application) to the collection in $obj4 (SchoolYear)
                $obj4->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Grade rows

                $key5 = GradePeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = GradePeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = GradePeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    GradePeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Application) to the collection in $obj5 (Grade)
                $obj5->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Level rows

                $key6 = LevelPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = LevelPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = LevelPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    LevelPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Application) to the collection in $obj6 (Level)
                $obj6->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined State rows

                $key7 = StatePeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = StatePeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = StatePeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    StatePeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Application) to the collection in $obj7 (State)
                $obj7->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Country rows

                $key8 = CountryPeer::getPrimaryKeyHashFromRow($row, $startcol8);
                if ($key8 !== null) {
                    $obj8 = CountryPeer::getInstanceFromPool($key8);
                    if (!$obj8) {

                        $cls = CountryPeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    CountryPeer::addInstanceToPool($obj8, $key8);
                } // if $obj8 already loaded

                // Add the $obj1 (Application) to the collection in $obj8 (Country)
                $obj8->addApplication($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Application objects pre-filled with all related objects except Grade.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Application objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptGrade(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ApplicationPeer::DATABASE_NAME);
        }

        ApplicationPeer::addSelectColumns($criteria);
        $startcol2 = ApplicationPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserPeer::NUM_HYDRATE_COLUMNS;

        SchoolPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SchoolPeer::NUM_HYDRATE_COLUMNS;

        SchoolYearPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + SchoolYearPeer::NUM_HYDRATE_COLUMNS;

        EthnicityPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + EthnicityPeer::NUM_HYDRATE_COLUMNS;

        LevelPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + LevelPeer::NUM_HYDRATE_COLUMNS;

        StatePeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + StatePeer::NUM_HYDRATE_COLUMNS;

        CountryPeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + CountryPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ApplicationPeer::ENTERED_BY, UserPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_ID, SchoolPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_YEAR_ID, SchoolYearPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::ETHNICITY_ID, EthnicityPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ApplicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ApplicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ApplicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ApplicationPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined User rows

                $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = UserPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Application) to the collection in $obj2 (User)
                $obj2->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined School rows

                $key3 = SchoolPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = SchoolPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = SchoolPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SchoolPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Application) to the collection in $obj3 (School)
                $obj3->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolYear rows

                $key4 = SchoolYearPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = SchoolYearPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = SchoolYearPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    SchoolYearPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Application) to the collection in $obj4 (SchoolYear)
                $obj4->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Ethnicity rows

                $key5 = EthnicityPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = EthnicityPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = EthnicityPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    EthnicityPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Application) to the collection in $obj5 (Ethnicity)
                $obj5->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Level rows

                $key6 = LevelPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = LevelPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = LevelPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    LevelPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Application) to the collection in $obj6 (Level)
                $obj6->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined State rows

                $key7 = StatePeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = StatePeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = StatePeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    StatePeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Application) to the collection in $obj7 (State)
                $obj7->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Country rows

                $key8 = CountryPeer::getPrimaryKeyHashFromRow($row, $startcol8);
                if ($key8 !== null) {
                    $obj8 = CountryPeer::getInstanceFromPool($key8);
                    if (!$obj8) {

                        $cls = CountryPeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    CountryPeer::addInstanceToPool($obj8, $key8);
                } // if $obj8 already loaded

                // Add the $obj1 (Application) to the collection in $obj8 (Country)
                $obj8->addApplication($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Application objects pre-filled with all related objects except Level.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Application objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptLevel(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ApplicationPeer::DATABASE_NAME);
        }

        ApplicationPeer::addSelectColumns($criteria);
        $startcol2 = ApplicationPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserPeer::NUM_HYDRATE_COLUMNS;

        SchoolPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SchoolPeer::NUM_HYDRATE_COLUMNS;

        SchoolYearPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + SchoolYearPeer::NUM_HYDRATE_COLUMNS;

        EthnicityPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + EthnicityPeer::NUM_HYDRATE_COLUMNS;

        GradePeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + GradePeer::NUM_HYDRATE_COLUMNS;

        StatePeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + StatePeer::NUM_HYDRATE_COLUMNS;

        CountryPeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + CountryPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ApplicationPeer::ENTERED_BY, UserPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_ID, SchoolPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_YEAR_ID, SchoolYearPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::ETHNICITY_ID, EthnicityPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::GRADE_ID, GradePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::STATE_ID, StatePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ApplicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ApplicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ApplicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ApplicationPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined User rows

                $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = UserPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Application) to the collection in $obj2 (User)
                $obj2->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined School rows

                $key3 = SchoolPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = SchoolPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = SchoolPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SchoolPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Application) to the collection in $obj3 (School)
                $obj3->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolYear rows

                $key4 = SchoolYearPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = SchoolYearPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = SchoolYearPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    SchoolYearPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Application) to the collection in $obj4 (SchoolYear)
                $obj4->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Ethnicity rows

                $key5 = EthnicityPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = EthnicityPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = EthnicityPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    EthnicityPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Application) to the collection in $obj5 (Ethnicity)
                $obj5->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Grade rows

                $key6 = GradePeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = GradePeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = GradePeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    GradePeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Application) to the collection in $obj6 (Grade)
                $obj6->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined State rows

                $key7 = StatePeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = StatePeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = StatePeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    StatePeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Application) to the collection in $obj7 (State)
                $obj7->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Country rows

                $key8 = CountryPeer::getPrimaryKeyHashFromRow($row, $startcol8);
                if ($key8 !== null) {
                    $obj8 = CountryPeer::getInstanceFromPool($key8);
                    if (!$obj8) {

                        $cls = CountryPeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    CountryPeer::addInstanceToPool($obj8, $key8);
                } // if $obj8 already loaded

                // Add the $obj1 (Application) to the collection in $obj8 (Country)
                $obj8->addApplication($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Application objects pre-filled with all related objects except State.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Application objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptState(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ApplicationPeer::DATABASE_NAME);
        }

        ApplicationPeer::addSelectColumns($criteria);
        $startcol2 = ApplicationPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserPeer::NUM_HYDRATE_COLUMNS;

        SchoolPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SchoolPeer::NUM_HYDRATE_COLUMNS;

        SchoolYearPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + SchoolYearPeer::NUM_HYDRATE_COLUMNS;

        EthnicityPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + EthnicityPeer::NUM_HYDRATE_COLUMNS;

        GradePeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + GradePeer::NUM_HYDRATE_COLUMNS;

        LevelPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + LevelPeer::NUM_HYDRATE_COLUMNS;

        CountryPeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + CountryPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ApplicationPeer::ENTERED_BY, UserPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_ID, SchoolPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_YEAR_ID, SchoolYearPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::ETHNICITY_ID, EthnicityPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::GRADE_ID, GradePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::COUNTRY_ID, CountryPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ApplicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ApplicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ApplicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ApplicationPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined User rows

                $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = UserPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Application) to the collection in $obj2 (User)
                $obj2->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined School rows

                $key3 = SchoolPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = SchoolPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = SchoolPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SchoolPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Application) to the collection in $obj3 (School)
                $obj3->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolYear rows

                $key4 = SchoolYearPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = SchoolYearPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = SchoolYearPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    SchoolYearPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Application) to the collection in $obj4 (SchoolYear)
                $obj4->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Ethnicity rows

                $key5 = EthnicityPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = EthnicityPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = EthnicityPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    EthnicityPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Application) to the collection in $obj5 (Ethnicity)
                $obj5->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Grade rows

                $key6 = GradePeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = GradePeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = GradePeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    GradePeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Application) to the collection in $obj6 (Grade)
                $obj6->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Level rows

                $key7 = LevelPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = LevelPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = LevelPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    LevelPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Application) to the collection in $obj7 (Level)
                $obj7->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Country rows

                $key8 = CountryPeer::getPrimaryKeyHashFromRow($row, $startcol8);
                if ($key8 !== null) {
                    $obj8 = CountryPeer::getInstanceFromPool($key8);
                    if (!$obj8) {

                        $cls = CountryPeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    CountryPeer::addInstanceToPool($obj8, $key8);
                } // if $obj8 already loaded

                // Add the $obj1 (Application) to the collection in $obj8 (Country)
                $obj8->addApplication($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Application objects pre-filled with all related objects except Country.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Application objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptCountry(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ApplicationPeer::DATABASE_NAME);
        }

        ApplicationPeer::addSelectColumns($criteria);
        $startcol2 = ApplicationPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserPeer::NUM_HYDRATE_COLUMNS;

        SchoolPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SchoolPeer::NUM_HYDRATE_COLUMNS;

        SchoolYearPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + SchoolYearPeer::NUM_HYDRATE_COLUMNS;

        EthnicityPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + EthnicityPeer::NUM_HYDRATE_COLUMNS;

        GradePeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + GradePeer::NUM_HYDRATE_COLUMNS;

        LevelPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + LevelPeer::NUM_HYDRATE_COLUMNS;

        StatePeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + StatePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ApplicationPeer::ENTERED_BY, UserPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_ID, SchoolPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::SCHOOL_YEAR_ID, SchoolYearPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::ETHNICITY_ID, EthnicityPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::GRADE_ID, GradePeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::LEVEL_ID, LevelPeer::ID, $join_behavior);

        $criteria->addJoin(ApplicationPeer::STATE_ID, StatePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ApplicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ApplicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ApplicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ApplicationPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined User rows

                $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = UserPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Application) to the collection in $obj2 (User)
                $obj2->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined School rows

                $key3 = SchoolPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = SchoolPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = SchoolPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SchoolPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Application) to the collection in $obj3 (School)
                $obj3->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined SchoolYear rows

                $key4 = SchoolYearPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = SchoolYearPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = SchoolYearPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    SchoolYearPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Application) to the collection in $obj4 (SchoolYear)
                $obj4->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Ethnicity rows

                $key5 = EthnicityPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = EthnicityPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = EthnicityPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    EthnicityPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Application) to the collection in $obj5 (Ethnicity)
                $obj5->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Grade rows

                $key6 = GradePeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = GradePeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = GradePeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    GradePeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Application) to the collection in $obj6 (Grade)
                $obj6->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined Level rows

                $key7 = LevelPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = LevelPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = LevelPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    LevelPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Application) to the collection in $obj7 (Level)
                $obj7->addApplication($obj1);

            } // if joined row is not null

                // Add objects for joined State rows

                $key8 = StatePeer::getPrimaryKeyHashFromRow($row, $startcol8);
                if ($key8 !== null) {
                    $obj8 = StatePeer::getInstanceFromPool($key8);
                    if (!$obj8) {

                        $cls = StatePeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    StatePeer::addInstanceToPool($obj8, $key8);
                } // if $obj8 already loaded

                // Add the $obj1 (Application) to the collection in $obj8 (State)
                $obj8->addApplication($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }

    /**
     * Returns the TableMap related to this peer.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getDatabaseMap(ApplicationPeer::DATABASE_NAME)->getTable(ApplicationPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseApplicationPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseApplicationPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \PGS\CoreDomainBundle\Model\Application\map\ApplicationTableMap());
      }
    }

    /**
     * The class that the Peer will make instances of.
     *
     *
     * @return string ClassName
     */
    public static function getOMClass($row = 0, $colnum = 0)
    {

        $event = new DetectOMClassEvent(ApplicationPeer::OM_CLASS, $row, $colnum);
        EventDispatcherProxy::trigger('om.detect', $event);
        if($event->isDetected()){
            return $event->getDetectedClass();
        }

        return ApplicationPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a Application or Criteria object.
     *
     * @param      mixed $values Criteria or Application object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from Application object
        }

        if ($criteria->containsKey(ApplicationPeer::ID) && $criteria->keyContainsValue(ApplicationPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ApplicationPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(ApplicationPeer::DATABASE_NAME);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = BasePeer::doInsert($criteria, $con);
            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

    /**
     * Performs an UPDATE on the database, given a Application or Criteria object.
     *
     * @param      mixed $values Criteria or Application object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(ApplicationPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(ApplicationPeer::ID);
            $value = $criteria->remove(ApplicationPeer::ID);
            if ($value) {
                $selectCriteria->add(ApplicationPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(ApplicationPeer::TABLE_NAME);
            }

        } else { // $values is Application object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(ApplicationPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the application table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(ApplicationPeer::TABLE_NAME, $con, ApplicationPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ApplicationPeer::clearInstancePool();
            ApplicationPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a Application or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or Application object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param      PropelPDO $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *				if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, PropelPDO $con = null)
     {
        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            ApplicationPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof Application) { // it's a model object
            // invalidate the cache for this single object
            ApplicationPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ApplicationPeer::DATABASE_NAME);
            $criteria->add(ApplicationPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                ApplicationPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(ApplicationPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            ApplicationPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given Application object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param Application $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(ApplicationPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(ApplicationPeer::TABLE_NAME);

            if (! is_array($cols)) {
                $cols = array($cols);
            }

            foreach ($cols as $colName) {
                if ($tableMap->hasColumn($colName)) {
                    $get = 'get' . $tableMap->getColumn($colName)->getPhpName();
                    $columns[$colName] = $obj->$get();
                }
            }
        } else {

        }

        return BasePeer::doValidate(ApplicationPeer::DATABASE_NAME, ApplicationPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return Application
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = ApplicationPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(ApplicationPeer::DATABASE_NAME);
        $criteria->add(ApplicationPeer::ID, $pk);

        $v = ApplicationPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return Application[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(ApplicationPeer::DATABASE_NAME);
            $criteria->add(ApplicationPeer::ID, $pks, Criteria::IN);
            $objs = ApplicationPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseApplicationPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseApplicationPeer::buildTableMap();

EventDispatcherProxy::trigger(array('construct','peer.construct'), new PeerEvent('PGS\CoreDomainBundle\Model\Application\om\BaseApplicationPeer'));
