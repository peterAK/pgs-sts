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
use PGS\CoreDomainBundle\Model\Behavior\Behavior;
use PGS\CoreDomainBundle\Model\Behavior\BehaviorQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.behavior")
 */
class BehaviorManager extends Authorizer
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
     * @var BehaviorQuery
     */
    private $behaviorQuery;

    /**
     * @InjectParams({
     *      "behaviorQuery" = @Inject("pgs.core.query.behavior"),
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "securityContext"  = @Inject("security.context"),
     * })
     */
    public function __construct(
        BehaviorQuery $behaviorQuery,
        ActivePreferenceContainer $activePreference,
        SecurityContext $securityContext
    )
    {
        $this->behaviorQuery          = $behaviorQuery;
        $this->activePreference       = $activePreference;
        $this->securityContext        = $securityContext;
    }

    /**
     * @return behaviorQuery
     */
    public function getBaseQuery()
    {
        return $this->behaviorQuery->create();
    }

    /**
     * @param mixed $behavior
     *
     * @return Behavior
     */
    public function findOne($behavior)
    {
        if ($behavior instanceof Behavior) {
            $behavior = $behavior->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($behavior);
    }



    /**
     * @return Behavior[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->find();
    }

    /**
     * @return Behavior[]
     */
    public function findAllByPoint()
    {
        return $this->getBaseQuery()->orderByPoint('desc')->find();
    }

    /**
     * @param mixed $behavior
     */
    public function setActive($behavior)
    {
        if ($behavior instanceof Behavior)
        {
            $behavior = $behavior->getId();
        }

        $this->getBaseQuery()->setActive($behavior);
    }

    /**
     * @param BehaviorQuery $query
     * @return bool|BehaviorQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(Behavior $behavior)
    {
        if($this->isAdmin() || $this->isTeacher() || $this->isPrincipal()){
            return false;
        }

    }

    /**
     * @param bool|Behavior
     */
    public function canAdd()
    {
        if($this->isAdmin() || $this->isTeacher() || $this->isPrincipal()){
            return new Behavior();
        }
        return false;
    }

    /**
     * @param mixed $behavior
     * @param bool|Behavior
     */
    public function canEdit($behavior)
    {
        $behavior = $this->findOne($behavior);
        if($this->limitRole($behavior)){
            return false;
        }

        return $behavior;
    }

    /**
     * @param mixed $behavior
     * @param bool|Behavior
     */
    public function canDelete($behavior)
    {
        $behavior = $this->findOne($behavior);
        if($this->limitRole($behavior)){
            return false;
        }

        return $behavior;
    }

    /**
     * @param mixed $behavior
     * @param bool|Behavior
     */
    public function canView($behavior)
    {
        $behavior = $this->findOne($behavior);
        if($this->limitRole($behavior)){
            return false;
        }

        return $behavior;
    }

    /**
     * @return Behavior[]
     */
    public function findMyBehaviorPoint()
    {
        return $this
            ->getBaseQuery()
                ->useSchoolClassCourseStudentBehaviorQuery()
                    ->filterByStudentId($this->activePreference->getCurrentUserProfile()->getId())
                ->endUse()
            ->find();

    }
}
