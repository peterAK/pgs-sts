<?php

namespace PGS\CoreDomainBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Glorpen\Propel\PropelBundle\Dispatcher\EventDispatcherProxy;
use Glorpen\Propel\PropelBundle\Events\ModelEvent;
use PGS\CoreDomainBundle\Model\Area;
use PGS\CoreDomainBundle\Model\AreaQuery;
use PGS\CoreDomainBundle\Model\City;
use PGS\CoreDomainBundle\Model\CityPeer;
use PGS\CoreDomainBundle\Model\CityQuery;
use PGS\CoreDomainBundle\Model\State;
use PGS\CoreDomainBundle\Model\StateQuery;
use PGS\CoreDomainBundle\Model\BranchCoverage\BranchCoverage;
use PGS\CoreDomainBundle\Model\BranchCoverage\BranchCoverageQuery;
use PGS\CoreDomainBundle\Model\Store\Store;
use PGS\CoreDomainBundle\Model\Store\StoreQuery;

abstract class BaseCity extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'PGS\\CoreDomainBundle\\Model\\CityPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        CityPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the code field.
     * @var        string
     */
    protected $code;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the state_id field.
     * @var        int
     */
    protected $state_id;

    /**
     * @var        State
     */
    protected $aState;

    /**
     * @var        PropelObjectCollection|BranchCoverage[] Collection to store aggregation of BranchCoverage objects.
     */
    protected $collBranchCoverages;
    protected $collBranchCoveragesPartial;

    /**
     * @var        PropelObjectCollection|Area[] Collection to store aggregation of Area objects.
     */
    protected $collAreas;
    protected $collAreasPartial;

    /**
     * @var        PropelObjectCollection|Store[] Collection to store aggregation of Store objects.
     */
    protected $collStores;
    protected $collStoresPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $branchCoveragesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $areasScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $storesScheduledForDeletion = null;

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    public function __construct(){
        parent::__construct();
        EventDispatcherProxy::trigger(array('construct','model.construct'), new ModelEvent($this));
    }

    /**
     * Get the [code] column value.
     *
     * @return string
     */
    public function getCode()
    {

        return $this->code;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {

        return $this->name;
    }

    /**
     * Get the [state_id] column value.
     *
     * @return int
     */
    public function getStateId()
    {

        return $this->state_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return City The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = CityPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [code] column.
     *
     * @param  string $v new value
     * @return City The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[] = CityPeer::CODE;
        }


        return $this;
    } // setCode()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return City The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = CityPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [state_id] column.
     *
     * @param  int $v new value
     * @return City The current object (for fluent API support)
     */
    public function setStateId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->state_id !== $v) {
            $this->state_id = $v;
            $this->modifiedColumns[] = CityPeer::STATE_ID;
        }

        if ($this->aState !== null && $this->aState->getId() !== $v) {
            $this->aState = null;
        }


        return $this;
    } // setStateId()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->code = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->state_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 4; // 4 = CityPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating City object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

        if ($this->aState !== null && $this->state_id !== $this->aState->getId()) {
            $this->aState = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CityPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = CityPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aState = null;
            $this->collBranchCoverages = null;

            $this->collAreas = null;

            $this->collStores = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CityPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
            $deleteQuery = CityQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                // event behavior
                EventDispatcherProxy::trigger(array('delete.post', 'model.delete.post'), new ModelEvent($this));
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CityPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // event behavior
            EventDispatcherProxy::trigger('model.save.pre', new ModelEvent($this));
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
                // event behavior
                EventDispatcherProxy::trigger(array('update.pre', 'model.update.pre'), new ModelEvent($this));
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                    // event behavior
                    EventDispatcherProxy::trigger('model.insert.post', new ModelEvent($this));
                } else {
                    $this->postUpdate($con);
                    // event behavior
                    EventDispatcherProxy::trigger(array('update.post', 'model.update.post'), new ModelEvent($this));
                }
                $this->postSave($con);
                // event behavior
                EventDispatcherProxy::trigger('model.save.post', new ModelEvent($this));
                CityPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aState !== null) {
                if ($this->aState->isModified() || $this->aState->isNew()) {
                    $affectedRows += $this->aState->save($con);
                }
                $this->setState($this->aState);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->branchCoveragesScheduledForDeletion !== null) {
                if (!$this->branchCoveragesScheduledForDeletion->isEmpty()) {
                    foreach ($this->branchCoveragesScheduledForDeletion as $branchCoverage) {
                        // need to save related object because we set the relation to null
                        $branchCoverage->save($con);
                    }
                    $this->branchCoveragesScheduledForDeletion = null;
                }
            }

            if ($this->collBranchCoverages !== null) {
                foreach ($this->collBranchCoverages as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->areasScheduledForDeletion !== null) {
                if (!$this->areasScheduledForDeletion->isEmpty()) {
                    foreach ($this->areasScheduledForDeletion as $area) {
                        // need to save related object because we set the relation to null
                        $area->save($con);
                    }
                    $this->areasScheduledForDeletion = null;
                }
            }

            if ($this->collAreas !== null) {
                foreach ($this->collAreas as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->storesScheduledForDeletion !== null) {
                if (!$this->storesScheduledForDeletion->isEmpty()) {
                    foreach ($this->storesScheduledForDeletion as $store) {
                        // need to save related object because we set the relation to null
                        $store->save($con);
                    }
                    $this->storesScheduledForDeletion = null;
                }
            }

            if ($this->collStores !== null) {
                foreach ($this->collStores as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = CityPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CityPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CityPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(CityPeer::CODE)) {
            $modifiedColumns[':p' . $index++]  = '`code`';
        }
        if ($this->isColumnModified(CityPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(CityPeer::STATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`state_id`';
        }

        $sql = sprintf(
            'INSERT INTO `city` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`code`':
                        $stmt->bindValue($identifier, $this->code, PDO::PARAM_STR);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`state_id`':
                        $stmt->bindValue($identifier, $this->state_id, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aState !== null) {
                if (!$this->aState->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aState->getValidationFailures());
                }
            }


            if (($retval = CityPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collBranchCoverages !== null) {
                    foreach ($this->collBranchCoverages as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collAreas !== null) {
                    foreach ($this->collAreas as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collStores !== null) {
                    foreach ($this->collStores as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = CityPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getCode();
                break;
            case 2:
                return $this->getName();
                break;
            case 3:
                return $this->getStateId();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['City'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['City'][$this->getPrimaryKey()] = true;
        $keys = CityPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getCode(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getStateId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aState) {
                $result['State'] = $this->aState->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collBranchCoverages) {
                $result['BranchCoverages'] = $this->collBranchCoverages->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAreas) {
                $result['Areas'] = $this->collAreas->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStores) {
                $result['Stores'] = $this->collStores->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = CityPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setCode($value);
                break;
            case 2:
                $this->setName($value);
                break;
            case 3:
                $this->setStateId($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = CityPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setCode($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setName($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setStateId($arr[$keys[3]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(CityPeer::DATABASE_NAME);

        if ($this->isColumnModified(CityPeer::ID)) $criteria->add(CityPeer::ID, $this->id);
        if ($this->isColumnModified(CityPeer::CODE)) $criteria->add(CityPeer::CODE, $this->code);
        if ($this->isColumnModified(CityPeer::NAME)) $criteria->add(CityPeer::NAME, $this->name);
        if ($this->isColumnModified(CityPeer::STATE_ID)) $criteria->add(CityPeer::STATE_ID, $this->state_id);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(CityPeer::DATABASE_NAME);
        $criteria->add(CityPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of City (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setCode($this->getCode());
        $copyObj->setName($this->getName());
        $copyObj->setStateId($this->getStateId());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getBranchCoverages() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addBranchCoverage($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAreas() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addArea($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStores() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStore($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return City Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return CityPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new CityPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a State object.
     *
     * @param                  State $v
     * @return City The current object (for fluent API support)
     * @throws PropelException
     */
    public function setState(State $v = null)
    {
        if ($v === null) {
            $this->setStateId(NULL);
        } else {
            $this->setStateId($v->getId());
        }

        $this->aState = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the State object, it will not be re-added.
        if ($v !== null) {
            $v->addCity($this);
        }


        return $this;
    }


    /**
     * Get the associated State object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return State The associated State object.
     * @throws PropelException
     */
    public function getState(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aState === null && ($this->state_id !== null) && $doQuery) {
            $this->aState = StateQuery::create()->findPk($this->state_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aState->addCities($this);
             */
        }

        return $this->aState;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('BranchCoverage' == $relationName) {
            $this->initBranchCoverages();
        }
        if ('Area' == $relationName) {
            $this->initAreas();
        }
        if ('Store' == $relationName) {
            $this->initStores();
        }
    }

    /**
     * Clears out the collBranchCoverages collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return City The current object (for fluent API support)
     * @see        addBranchCoverages()
     */
    public function clearBranchCoverages()
    {
        $this->collBranchCoverages = null; // important to set this to null since that means it is uninitialized
        $this->collBranchCoveragesPartial = null;

        return $this;
    }

    /**
     * reset is the collBranchCoverages collection loaded partially
     *
     * @return void
     */
    public function resetPartialBranchCoverages($v = true)
    {
        $this->collBranchCoveragesPartial = $v;
    }

    /**
     * Initializes the collBranchCoverages collection.
     *
     * By default this just sets the collBranchCoverages collection to an empty array (like clearcollBranchCoverages());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initBranchCoverages($overrideExisting = true)
    {
        if (null !== $this->collBranchCoverages && !$overrideExisting) {
            return;
        }
        $this->collBranchCoverages = new PropelObjectCollection();
        $this->collBranchCoverages->setModel('BranchCoverage');
    }

    /**
     * Gets an array of BranchCoverage objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this City is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|BranchCoverage[] List of BranchCoverage objects
     * @throws PropelException
     */
    public function getBranchCoverages($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collBranchCoveragesPartial && !$this->isNew();
        if (null === $this->collBranchCoverages || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collBranchCoverages) {
                // return empty collection
                $this->initBranchCoverages();
            } else {
                $collBranchCoverages = BranchCoverageQuery::create(null, $criteria)
                    ->filterByCity($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collBranchCoveragesPartial && count($collBranchCoverages)) {
                      $this->initBranchCoverages(false);

                      foreach ($collBranchCoverages as $obj) {
                        if (false == $this->collBranchCoverages->contains($obj)) {
                          $this->collBranchCoverages->append($obj);
                        }
                      }

                      $this->collBranchCoveragesPartial = true;
                    }

                    $collBranchCoverages->getInternalIterator()->rewind();

                    return $collBranchCoverages;
                }

                if ($partial && $this->collBranchCoverages) {
                    foreach ($this->collBranchCoverages as $obj) {
                        if ($obj->isNew()) {
                            $collBranchCoverages[] = $obj;
                        }
                    }
                }

                $this->collBranchCoverages = $collBranchCoverages;
                $this->collBranchCoveragesPartial = false;
            }
        }

        return $this->collBranchCoverages;
    }

    /**
     * Sets a collection of BranchCoverage objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $branchCoverages A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return City The current object (for fluent API support)
     */
    public function setBranchCoverages(PropelCollection $branchCoverages, PropelPDO $con = null)
    {
        $branchCoveragesToDelete = $this->getBranchCoverages(new Criteria(), $con)->diff($branchCoverages);


        $this->branchCoveragesScheduledForDeletion = $branchCoveragesToDelete;

        foreach ($branchCoveragesToDelete as $branchCoverageRemoved) {
            $branchCoverageRemoved->setCity(null);
        }

        $this->collBranchCoverages = null;
        foreach ($branchCoverages as $branchCoverage) {
            $this->addBranchCoverage($branchCoverage);
        }

        $this->collBranchCoverages = $branchCoverages;
        $this->collBranchCoveragesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BranchCoverage objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related BranchCoverage objects.
     * @throws PropelException
     */
    public function countBranchCoverages(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collBranchCoveragesPartial && !$this->isNew();
        if (null === $this->collBranchCoverages || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collBranchCoverages) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getBranchCoverages());
            }
            $query = BranchCoverageQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCity($this)
                ->count($con);
        }

        return count($this->collBranchCoverages);
    }

    /**
     * Method called to associate a BranchCoverage object to this object
     * through the BranchCoverage foreign key attribute.
     *
     * @param    BranchCoverage $l BranchCoverage
     * @return City The current object (for fluent API support)
     */
    public function addBranchCoverage(BranchCoverage $l)
    {
        if ($this->collBranchCoverages === null) {
            $this->initBranchCoverages();
            $this->collBranchCoveragesPartial = true;
        }

        if (!in_array($l, $this->collBranchCoverages->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddBranchCoverage($l);

            if ($this->branchCoveragesScheduledForDeletion and $this->branchCoveragesScheduledForDeletion->contains($l)) {
                $this->branchCoveragesScheduledForDeletion->remove($this->branchCoveragesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	BranchCoverage $branchCoverage The branchCoverage object to add.
     */
    protected function doAddBranchCoverage($branchCoverage)
    {
        $this->collBranchCoverages[]= $branchCoverage;
        $branchCoverage->setCity($this);
    }

    /**
     * @param	BranchCoverage $branchCoverage The branchCoverage object to remove.
     * @return City The current object (for fluent API support)
     */
    public function removeBranchCoverage($branchCoverage)
    {
        if ($this->getBranchCoverages()->contains($branchCoverage)) {
            $this->collBranchCoverages->remove($this->collBranchCoverages->search($branchCoverage));
            if (null === $this->branchCoveragesScheduledForDeletion) {
                $this->branchCoveragesScheduledForDeletion = clone $this->collBranchCoverages;
                $this->branchCoveragesScheduledForDeletion->clear();
            }
            $this->branchCoveragesScheduledForDeletion[]= $branchCoverage;
            $branchCoverage->setCity(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this City is new, it will return
     * an empty collection; or if this City has previously
     * been saved, it will retrieve related BranchCoverages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in City.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|BranchCoverage[] List of BranchCoverage objects
     */
    public function getBranchCoveragesJoinState($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = BranchCoverageQuery::create(null, $criteria);
        $query->joinWith('State', $join_behavior);

        return $this->getBranchCoverages($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this City is new, it will return
     * an empty collection; or if this City has previously
     * been saved, it will retrieve related BranchCoverages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in City.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|BranchCoverage[] List of BranchCoverage objects
     */
    public function getBranchCoveragesJoinRegion($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = BranchCoverageQuery::create(null, $criteria);
        $query->joinWith('Region', $join_behavior);

        return $this->getBranchCoverages($query, $con);
    }

    /**
     * Clears out the collAreas collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return City The current object (for fluent API support)
     * @see        addAreas()
     */
    public function clearAreas()
    {
        $this->collAreas = null; // important to set this to null since that means it is uninitialized
        $this->collAreasPartial = null;

        return $this;
    }

    /**
     * reset is the collAreas collection loaded partially
     *
     * @return void
     */
    public function resetPartialAreas($v = true)
    {
        $this->collAreasPartial = $v;
    }

    /**
     * Initializes the collAreas collection.
     *
     * By default this just sets the collAreas collection to an empty array (like clearcollAreas());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAreas($overrideExisting = true)
    {
        if (null !== $this->collAreas && !$overrideExisting) {
            return;
        }
        $this->collAreas = new PropelObjectCollection();
        $this->collAreas->setModel('Area');
    }

    /**
     * Gets an array of Area objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this City is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Area[] List of Area objects
     * @throws PropelException
     */
    public function getAreas($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collAreasPartial && !$this->isNew();
        if (null === $this->collAreas || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAreas) {
                // return empty collection
                $this->initAreas();
            } else {
                $collAreas = AreaQuery::create(null, $criteria)
                    ->filterByCity($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collAreasPartial && count($collAreas)) {
                      $this->initAreas(false);

                      foreach ($collAreas as $obj) {
                        if (false == $this->collAreas->contains($obj)) {
                          $this->collAreas->append($obj);
                        }
                      }

                      $this->collAreasPartial = true;
                    }

                    $collAreas->getInternalIterator()->rewind();

                    return $collAreas;
                }

                if ($partial && $this->collAreas) {
                    foreach ($this->collAreas as $obj) {
                        if ($obj->isNew()) {
                            $collAreas[] = $obj;
                        }
                    }
                }

                $this->collAreas = $collAreas;
                $this->collAreasPartial = false;
            }
        }

        return $this->collAreas;
    }

    /**
     * Sets a collection of Area objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $areas A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return City The current object (for fluent API support)
     */
    public function setAreas(PropelCollection $areas, PropelPDO $con = null)
    {
        $areasToDelete = $this->getAreas(new Criteria(), $con)->diff($areas);


        $this->areasScheduledForDeletion = $areasToDelete;

        foreach ($areasToDelete as $areaRemoved) {
            $areaRemoved->setCity(null);
        }

        $this->collAreas = null;
        foreach ($areas as $area) {
            $this->addArea($area);
        }

        $this->collAreas = $areas;
        $this->collAreasPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Area objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Area objects.
     * @throws PropelException
     */
    public function countAreas(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collAreasPartial && !$this->isNew();
        if (null === $this->collAreas || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAreas) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAreas());
            }
            $query = AreaQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCity($this)
                ->count($con);
        }

        return count($this->collAreas);
    }

    /**
     * Method called to associate a Area object to this object
     * through the Area foreign key attribute.
     *
     * @param    Area $l Area
     * @return City The current object (for fluent API support)
     */
    public function addArea(Area $l)
    {
        if ($this->collAreas === null) {
            $this->initAreas();
            $this->collAreasPartial = true;
        }

        if (!in_array($l, $this->collAreas->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddArea($l);

            if ($this->areasScheduledForDeletion and $this->areasScheduledForDeletion->contains($l)) {
                $this->areasScheduledForDeletion->remove($this->areasScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Area $area The area object to add.
     */
    protected function doAddArea($area)
    {
        $this->collAreas[]= $area;
        $area->setCity($this);
    }

    /**
     * @param	Area $area The area object to remove.
     * @return City The current object (for fluent API support)
     */
    public function removeArea($area)
    {
        if ($this->getAreas()->contains($area)) {
            $this->collAreas->remove($this->collAreas->search($area));
            if (null === $this->areasScheduledForDeletion) {
                $this->areasScheduledForDeletion = clone $this->collAreas;
                $this->areasScheduledForDeletion->clear();
            }
            $this->areasScheduledForDeletion[]= $area;
            $area->setCity(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this City is new, it will return
     * an empty collection; or if this City has previously
     * been saved, it will retrieve related Areas from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in City.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Area[] List of Area objects
     */
    public function getAreasJoinState($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = AreaQuery::create(null, $criteria);
        $query->joinWith('State', $join_behavior);

        return $this->getAreas($query, $con);
    }

    /**
     * Clears out the collStores collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return City The current object (for fluent API support)
     * @see        addStores()
     */
    public function clearStores()
    {
        $this->collStores = null; // important to set this to null since that means it is uninitialized
        $this->collStoresPartial = null;

        return $this;
    }

    /**
     * reset is the collStores collection loaded partially
     *
     * @return void
     */
    public function resetPartialStores($v = true)
    {
        $this->collStoresPartial = $v;
    }

    /**
     * Initializes the collStores collection.
     *
     * By default this just sets the collStores collection to an empty array (like clearcollStores());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStores($overrideExisting = true)
    {
        if (null !== $this->collStores && !$overrideExisting) {
            return;
        }
        $this->collStores = new PropelObjectCollection();
        $this->collStores->setModel('Store');
    }

    /**
     * Gets an array of Store objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this City is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Store[] List of Store objects
     * @throws PropelException
     */
    public function getStores($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collStoresPartial && !$this->isNew();
        if (null === $this->collStores || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collStores) {
                // return empty collection
                $this->initStores();
            } else {
                $collStores = StoreQuery::create(null, $criteria)
                    ->filterByCity($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collStoresPartial && count($collStores)) {
                      $this->initStores(false);

                      foreach ($collStores as $obj) {
                        if (false == $this->collStores->contains($obj)) {
                          $this->collStores->append($obj);
                        }
                      }

                      $this->collStoresPartial = true;
                    }

                    $collStores->getInternalIterator()->rewind();

                    return $collStores;
                }

                if ($partial && $this->collStores) {
                    foreach ($this->collStores as $obj) {
                        if ($obj->isNew()) {
                            $collStores[] = $obj;
                        }
                    }
                }

                $this->collStores = $collStores;
                $this->collStoresPartial = false;
            }
        }

        return $this->collStores;
    }

    /**
     * Sets a collection of Store objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $stores A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return City The current object (for fluent API support)
     */
    public function setStores(PropelCollection $stores, PropelPDO $con = null)
    {
        $storesToDelete = $this->getStores(new Criteria(), $con)->diff($stores);


        $this->storesScheduledForDeletion = $storesToDelete;

        foreach ($storesToDelete as $storeRemoved) {
            $storeRemoved->setCity(null);
        }

        $this->collStores = null;
        foreach ($stores as $store) {
            $this->addStore($store);
        }

        $this->collStores = $stores;
        $this->collStoresPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Store objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Store objects.
     * @throws PropelException
     */
    public function countStores(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collStoresPartial && !$this->isNew();
        if (null === $this->collStores || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStores) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStores());
            }
            $query = StoreQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCity($this)
                ->count($con);
        }

        return count($this->collStores);
    }

    /**
     * Method called to associate a Store object to this object
     * through the Store foreign key attribute.
     *
     * @param    Store $l Store
     * @return City The current object (for fluent API support)
     */
    public function addStore(Store $l)
    {
        if ($this->collStores === null) {
            $this->initStores();
            $this->collStoresPartial = true;
        }

        if (!in_array($l, $this->collStores->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddStore($l);

            if ($this->storesScheduledForDeletion and $this->storesScheduledForDeletion->contains($l)) {
                $this->storesScheduledForDeletion->remove($this->storesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Store $store The store object to add.
     */
    protected function doAddStore($store)
    {
        $this->collStores[]= $store;
        $store->setCity($this);
    }

    /**
     * @param	Store $store The store object to remove.
     * @return City The current object (for fluent API support)
     */
    public function removeStore($store)
    {
        if ($this->getStores()->contains($store)) {
            $this->collStores->remove($this->collStores->search($store));
            if (null === $this->storesScheduledForDeletion) {
                $this->storesScheduledForDeletion = clone $this->collStores;
                $this->storesScheduledForDeletion->clear();
            }
            $this->storesScheduledForDeletion[]= $store;
            $store->setCity(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this City is new, it will return
     * an empty collection; or if this City has previously
     * been saved, it will retrieve related Stores from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in City.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Store[] List of Store objects
     */
    public function getStoresJoinCountry($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StoreQuery::create(null, $criteria);
        $query->joinWith('Country', $join_behavior);

        return $this->getStores($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this City is new, it will return
     * an empty collection; or if this City has previously
     * been saved, it will retrieve related Stores from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in City.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Store[] List of Store objects
     */
    public function getStoresJoinState($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StoreQuery::create(null, $criteria);
        $query->joinWith('State', $join_behavior);

        return $this->getStores($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this City is new, it will return
     * an empty collection; or if this City has previously
     * been saved, it will retrieve related Stores from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in City.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Store[] List of Store objects
     */
    public function getStoresJoinRegion($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StoreQuery::create(null, $criteria);
        $query->joinWith('Region', $join_behavior);

        return $this->getStores($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this City is new, it will return
     * an empty collection; or if this City has previously
     * been saved, it will retrieve related Stores from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in City.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Store[] List of Store objects
     */
    public function getStoresJoinArea($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StoreQuery::create(null, $criteria);
        $query->joinWith('Area', $join_behavior);

        return $this->getStores($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->code = null;
        $this->name = null;
        $this->state_id = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collBranchCoverages) {
                foreach ($this->collBranchCoverages as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAreas) {
                foreach ($this->collAreas as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStores) {
                foreach ($this->collStores as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aState instanceof Persistent) {
              $this->aState->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collBranchCoverages instanceof PropelCollection) {
            $this->collBranchCoverages->clearIterator();
        }
        $this->collBranchCoverages = null;
        if ($this->collAreas instanceof PropelCollection) {
            $this->collAreas->clearIterator();
        }
        $this->collAreas = null;
        if ($this->collStores instanceof PropelCollection) {
            $this->collStores->clearIterator();
        }
        $this->collStores = null;
        $this->aState = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CityPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

    // event behavior
    public function preCommit(\PropelPDO $con = null){}
    public function preCommitSave(\PropelPDO $con = null){}
    public function preCommitDelete(\PropelPDO $con = null){}
    public function preCommitUpdate(\PropelPDO $con = null){}
    public function preCommitInsert(\PropelPDO $con = null){}
    public function preRollback(\PropelPDO $con = null){}
    public function preRollbackSave(\PropelPDO $con = null){}
    public function preRollbackDelete(\PropelPDO $con = null){}
    public function preRollbackUpdate(\PropelPDO $con = null){}
    public function preRollbackInsert(\PropelPDO $con = null){}

    /**
     * Catches calls to virtual methods
     */
    public function __call($name, $params)
    {

        // delegate behavior

        if (is_callable(array('PGS\CoreDomainBundle\Model\State', $name))) {
            if (!$delegate = $this->getState()) {
                $delegate = new State();
                $this->setState($delegate);
            }

            return call_user_func_array(array($delegate, $name), $params);
        }

        return parent::__call($name, $params);
    }

}
