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
use PGS\CoreDomainBundle\Model\Organization\Organization;
use PGS\CoreDomainBundle\Model\Organization\OrganizationQuery;
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Security\Authorizer;
use PGS\CoreDomainBundle\Security\Model\OrganizationAuthorizationStrategy;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.organization")
 */
class OrganizationManager extends Authorizer
{
    /**
     * @var OrganizationQuery
     */
    private $organizationQuery;

    /**
     * @var ActivePreferenceContainer
     */
    public $activePreference;

    /**
     * @InjectParams({
     *      "activePreference"  = @Inject("pgs.core.container.active_preference"),
     *      "organizationQuery" = @Inject("pgs.core.query.organization")
     * })
     */
    public function __construct(
        ActivePreferenceContainer $activePreference,
        OrganizationQuery $organizationQuery
    ) {
        $this->activePreference  = $activePreference;
        $this->organizationQuery = $organizationQuery;
    }

    /**
     * @return OrganizationQuery
     */
    public function getBaseQuery()
    {
        return $this->organizationQuery->create();
    }

    /**
     * @return organization
     */
    public function getDefault()
    {
        if(!$organization = $this->getCurrentUser()->getUserProfile()->getOrganizationId()){
            $organization = new Organization();
        }

        return $organization;
    }

    /**
     * @param mixed $organization
     *
     * @return Organization
     */
    public function findOneById($organization)
    {
        if ($organization instanceof Organization) {
            $organization = $organization->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($organization);
    }


    /**
     * @param mixed $organization
     *
     * @return Organization
     */
    public function findOne($organization)
    {
        if ($organization instanceof Organization) {
            $organization = $organization->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($organization);
    }

    /**
     * @return Organization[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->find();
    }

    /**
     * @param string $status
     *
     * @return Organization
     */
    public function findByStatus($status)
    {
        if (strtoupper($status) == 'ALL') {
            return $this->findAll();
        } else {
            $query = $this->limitList($this->getBaseQuery())->filterByStatus(strtolower($status));

            return $this->limit($query)->find();
        }
    }

    /**
     * @return array
     */
    public function getStatuses()
    {
        return Organization::getStatuses();
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->getBaseQuery()->create()->count();
    }

    /**
     * @return int
     */
    public function countActive()
    {
        return $this->getBaseQuery()->filterByStatus('active')->count();
    }

    /**
     * @param Organization $organization
     * @param string $direction
     *
     * @return true
     */
    public function moveOrganization(Organization $organization, $direction) {
        switch (strtoupper($direction)) {
            case 'TOP':
                $organization->moveToTop();
                break;

            case 'UP':
                $organization->moveUp();
                break;

            case 'DOWN':
                $organization->moveDown();
                break;

            case 'BOTTOM':
                $organization->moveToBottom();
                break;
        }

        return true;
    }

    /**
     * @return bool|Organization
     */
    public function canAdd()
    {
        if ($this->isAdmin()) {
            return new Organization();
        }

        return false;
    }

    /**
     * @param OrganizationQuery $query
     *
     * @return bool|OrganizationQuery
     */
    public function limitList($query)
    {
        if ($this->isAdmin()) {
            return $query;
        }

        return $query->filterById($this->getCurrentUser()->getUserProfile()->getOrganizationId());
    }

    public function limitRole(Organization $organization)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @param mixed $organization
     *
     * @return bool|Organization
     */
    public function canEdit($organization)
    {
        $organization = $this->findOneById($organization);

        if ($this->limitRole($organization)) {
            return false;
        }

        return $organization;
    }

    /**
     * @param mixed $organization
     *
     * @return bool|Organization
     */
    public function canDelete($organization)
    {
        $organization = $this->findOneById($organization);

        if ($this->limitRole($organization)) {
            return false;
        }

        return $organization;
    }

    /**
     * @param mixed $organization
     *
     * @return bool|Organization
     */
    public function canView($organization)
    {
        $organization = $this->findOneById($organization);

        if ($this->limitRole($organization)) {
            return false;
        }

        return $organization;
    }


}
