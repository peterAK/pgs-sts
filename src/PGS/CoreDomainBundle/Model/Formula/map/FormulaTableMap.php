<?php

namespace PGS\CoreDomainBundle\Model\Formula\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'formula' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.Formula.map
 */
class FormulaTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.Formula.map.FormulaTableMap';

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
        $this->setName('formula');
        $this->setPhpName('Formula');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\Formula\\Formula');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.Formula');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 50, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', true, null, null);
        $this->addColumn('final_exam_point', 'FinalExamPoint', 'DECIMAL', false, null, null);
        $this->addColumn('daily_exam_point', 'DailyExamPoint', 'DECIMAL', false, null, null);
        $this->addColumn('mid_exam_point', 'MidExamPoint', 'DECIMAL', false, null, null);
        $this->addColumn('activity_point', 'ActivityPoint', 'DECIMAL', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('SchoolClassCourse', 'PGS\\CoreDomainBundle\\Model\\SchoolClassCourse\\SchoolClassCourse', RelationMap::ONE_TO_MANY, array('id' => 'formula_id', ), 'CASCADE', 'CASCADE', 'SchoolClassCourses');
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

} // FormulaTableMap
