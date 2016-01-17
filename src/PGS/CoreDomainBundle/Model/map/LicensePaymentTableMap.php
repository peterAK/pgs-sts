<?php

namespace PGS\CoreDomainBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'license_payment' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PGS.CoreDomainBundle.Model.map
 */
class LicensePaymentTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PGS.CoreDomainBundle.Model.map.LicensePaymentTableMap';

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
        $this->setName('license_payment');
        $this->setPhpName('LicensePayment');
        $this->setClassname('PGS\\CoreDomainBundle\\Model\\LicensePayment');
        $this->setPackage('src.PGS.CoreDomainBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'fos_user', 'id', true, null, null);
        $this->addForeignKey('license_id', 'LicenseId', 'INTEGER', 'license', 'id', false, null, null);
        $this->addColumn('payment_date', 'PaymentDate', 'DATE', false, null, null);
        $this->addColumn('quantity', 'Quantity', 'TINYINT', false, null, 1);
        $this->addColumn('price', 'Price', 'DECIMAL', false, 12, null);
        $this->addColumn('subtotal', 'Subtotal', 'DECIMAL', false, 12, null);
        $this->addColumn('discount', 'Discount', 'DECIMAL', false, 12, null);
        $this->addColumn('tax', 'Tax', 'DECIMAL', false, 12, null);
        $this->addColumn('total', 'Total', 'DECIMAL', false, 12, null);
        $this->addColumn('transaction_code', 'TransactionCode', 'VARCHAR', false, 255, null);
        $this->addColumn('status', 'Status', 'ENUM', false, null, 'new');
        $this->getColumn('status', false)->setValueSet(array (
  0 => 'new',
  1 => 'active',
  2 => 'inactive',
  3 => 'expired',
));
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', 'PGS\\CoreDomainBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), null, null);
        $this->addRelation('License', 'PGS\\CoreDomainBundle\\Model\\License', RelationMap::MANY_TO_ONE, array('license_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('UserLicense', 'PGS\\CoreDomainBundle\\Model\\UserLicense', RelationMap::ONE_TO_MANY, array('id' => 'license_payment_id', ), null, null, 'UserLicenses');
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
            'archivable' =>  array (
  'archive_table' => '',
  'archive_phpname' => NULL,
  'archive_class' => '',
  'log_archived_at' => 'true',
  'archived_at_column' => 'archived_at',
  'archive_on_insert' => 'false',
  'archive_on_update' => 'false',
  'archive_on_delete' => 'true',
),
            'delegate' =>  array (
  'to' => 'license',
),
            'event' =>  array (
),
            'extend' =>  array (
),
        );
    } // getBehaviors()

} // LicensePaymentTableMap
