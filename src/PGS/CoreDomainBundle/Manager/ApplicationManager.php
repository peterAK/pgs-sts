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
use PGS\CoreDomainBundle\Model\Application\Application;
use PGS\CoreDomainBundle\Security\Authorizer;
use PGS\CoreDomainBundle\Model\Application\ApplicationQuery;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.application")
 */
class ApplicationManager extends Authorizer
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
     * @var ApplicationQuery
     */
    private $applicationQuery;

    /**
     * @InjectParams({
     *      "applicationQuery" = @Inject("pgs.core.query.application"),
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "securityContext"  = @Inject("security.context")
     * })
     */

    public function __construct(
        ApplicationQuery $applicationQuery,
        ActivePreferenceContainer $activePreference,
        SecurityContext $securityContext
    )
    {
        $this->applicationQuery = $applicationQuery;
        $this->activePreference = $activePreference;
        $this->securityContext  = $securityContext;
    }

    /**
     * @return applicationQuery
     */
    public function getBaseQuery()
    {
        return $this->applicationQuery->create();
    }

    /**
     * @return Application[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->orderBySchoolId()->find();

    }


    /**
     * @param mixed $application
     *
     * @return Application
     */
    public function findOne($application)
    {
        if ($application instanceof Application)
        {
            $application = $application->getId();
        }

        return $this->getBaseQuery()->findOneById($application);
    }

    /**
     * @param string $status
     *
     * @return Application
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
        return Application::getStatuses();
    }

    /**
     * @return array
     */
    public function getGenders()
    {
        return Application::getGenders();
    }

    /**
     * @return array
     */
    public function getChildStatuses()
    {
        return Application::getChildStatuses();
    }

    /**
     * @param ApplicationQuery $query
     * @return bool | ApplicationQuery
     */
    public function limitList($query)
    {
        if ($this->isAdmin()) {
            return $query;
        }
        if ($this->isPrincipal()) {
            return $query
                ->useSchoolQuery()
                ->filterByOrganization($this->getCurrentUser()->getUserProfile()->getOrganizationId())
                ->endUse()
                ;
        }
//        return new RedirectResponse($this->generateUrl('homepage'));
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
