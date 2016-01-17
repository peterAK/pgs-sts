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
use PGS\CoreDomainBundle\Container\OldActivePreferenceContainer;
use PGS\CoreDomainBundle\Container\ActivePreferenceContainer;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealth;
use PGS\CoreDomainBundle\Security\Authorizer;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealthQuery;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.school_health")
 */
class SchoolHealthManager extends Authorizer
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
     * @var SchoolHealthQuery
     */
    private $schoolHealthQuery;

    /**
     * @InjectParams({
     *      "schoolHealthQuery" = @Inject("pgs.core.query.school_health"),
     *      "activePreference"  = @Inject("pgs.core.container.active_preference"),
     *      "securityContext"   = @Inject("security.context")
     * })
     */

    public function __construct(
        SchoolHealthQuery $schoolHealthQuery,
        ActivePreferenceContainer $activePreference,
        SecurityContext $securityContext
    )
    {
        $this->schoolHealthQuery     = $schoolHealthQuery;
        $this->activePreference      = $activePreference;
        $this->securityContext       = $securityContext;
    }

    /**
     * @return schoolHealthQuery
     */
    public function getBaseQuery()
    {
        return $this->schoolHealthQuery->create();
    }

    /**
     * @return SchoolHealth[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->orderByApplicationId()->find();

    }

    /**
     * @param mixed $schoolHealth
     *
     * @return SchoolHealth
     */
    public function findOne($schoolHealth)
    {
        if ($schoolHealth instanceof SchoolHealth)
        {
            $schoolHealth = $schoolHealth->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($schoolHealth);
    }

    /**
     * @param $id
     * @return SchoolHealth
     */
    public function findOneByApplicationId($id)
    {
//        if ($id instanceof SchoolHealth)
//        {
//            $id = $id->getApplicationId();
//        }

        return $this->limitList($this->getBaseQuery())->findOneByApplicationId($id);
    }


    /**
     * @param SchoolHealthQuery $query
     * @return bool| SchoolHealthQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(SchoolHealth $schoolHealth)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    public function canAdd()
    {
        // TODO: Implement canAdd() method.
    }

    /** @param mixed $modelIdentifier */
    public function canEdit($modelIdentifier)
    {
        // TODO: Implement canEdit() method.
    }

    /** @param mixed $modelIdentifier */
    public function canDelete($modelIdentifier)
    {
        // TODO: Implement canDelete() method.
    }

    /** @param mixed $modelIdentifier */
    public function canView($modelIdentifier)
    {
        // TODO: Implement canView() method.
    }


}
