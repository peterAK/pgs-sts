<?php

namespace PGS\CoreDomainBundle\Model\FormulaDetail\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'formula_detail' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.FormulaDetail.map
 */
class FormulaDetailTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.FormulaDetail.map.FormulaDetailTableMap';

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
        $this->setName('formula_detail');
        $this->setPhpName('FormulaDetail');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\FormulaDetail\\FormulaDetail');
        $this->setPackage('src.PGS.CoreDomainBundle.Model.FormulaDetail');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('formula_id', 'FormulaId', 'INTEGER', 'formula', 'id', true, null, null);
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
        $this->addRelation('FormulaRelatedByFormulaId', 'PGS\\CoreDomainBundle\\Model\\Formula\\Formula', RelationMap::MANY_TO_ONE, array('formula_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('FormulaRelatedByFormulaDetailId', 'PGS\\CoreDomainBundle\\Model\\Formula\\Formula', RelationMap::ONE_TO_MANY, array('id' => 'formula_detail_id', ), 'CASCADE', 'CASCADE', 'FormulasRelatedByFormulaDetailId');
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

} // FormulaDetailTableMap
