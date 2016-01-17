<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Manager;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Container\ActivePreferenceContainer;
use PGS\CoreDomainBundle\Model\Condition\Condition;
use PGS\CoreDomainBundle\Model\Condition\ConditionQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.condition")
 */
class ConditionManager extends Authorizer
{
    /**
     * @var ActivePreferenceContainer
     */
    public $activePreference;

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var ConditionQuery
     */
    private $conditionQuery;


    /**
     * @InjectParams({
     *      "conditionQuery"   = @Inject("pgs.core.query.condition"),
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "securityContext"  = @Inject("security.context"),
     * })
     */
    public function __construct(
        ConditionQuery $conditionQuery,
        ActivePreferenceContainer $activePreference,
        SecurityContext $securityContext
    ){
        $this->activePreference = $activePreference;
        $this->conditionQuery   = $conditionQuery;
        $this->securityContext  = $securityContext;
    }

    /**
     * @return ConditionQuery
     */
    public function getBaseQuery()
    {
        return $this->conditionQuery->create();
    }

    /**
     * @return Condition[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->orderBySortableRank()->find();
    }

    /**
     * @param mixed $condition
     *
     * @return Condition
     */
    public function findOne($condition)
    {
        if ($condition instanceof Condition) {
            $condition = $condition->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($condition);
    }

    /**
     * @param ConditionQuery $query
     * @return bool|ConditionQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(Condition $condition)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @return bool|Condition
     */
    public function canAdd()
    {
        if($this->isAdmin()){
            return new Condition;
        }
    }

    /**
     * @param mixed $condition
     * @return bool|Condition
     */
    public function canEdit($condition)
    {
        $condition = $this->findOne($condition);

         if(!$this->limitRole($condition)){
             return false;
         }

        return $condition;
    }

    /**
     * @param mixed $condition
     * @return bool|Condition
     */
    public function canView($condition)
    {
        $condition = $this->findOne($condition);

        if(!$this->limitRole($condition)){
            return false;
        }

        return $condition;
    }

    /**
     * @param mixed $condition
     * @return bool|Condition
     */
    public function canDelete($condition)
    {
        $condition = $this->findOne($condition);

        if(!$this->limitRole($condition)){
            return false;
        }

        return $condition;
    }


}
