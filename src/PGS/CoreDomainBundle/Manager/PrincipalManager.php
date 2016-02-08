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
use PGS\CoreDomainBundle\Model\Principal\Principal;
use PGS\CoreDomainBundle\Model\Principal\PrincipalQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.principal")
 */
class PrincipalManager extends Authorizer
{
    /**
     * @var ActivePreferenceContainer
     */
    public $activePreference;

    /**
     * @var PrincipalQuery
     */
    private $principalQuery;

    /**
     * @InjectParams({
     *      "activePreference"  = @Inject("pgs.core.container.active_preference"),
     *      "principalQuery" = @Inject("pgs.core.query.principal")
     * })
     */
    public function __construct(
        ActivePreferenceContainer $activePreference,
        PrincipalQuery $principalQuery
    ) {
        $this->activePreference  = $activePreference;
        $this->principalQuery = $principalQuery;
    }

    /**
     * @return PrincipalQuery
     */
    public function getBaseQuery()
    {
        return $this->principalQuery->create();
    }

    /**
     * @param mixed $principal
     *
     * @return Principal
     */
    public function findOneById($principal)
    {
        if ($principal instanceof Principal) {
            $principal = $principal->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($principal);
    }

    /**
     * @param PrincipalQuery $query
     *
     * @return bool|PrincipalQuery
     */
    public function limitList($query)
    {
        if ($this->isAdmin()) {
            return $query;
        }

        return $query->filterById($this->getCurrentUser()->getUserProfile()->getPrincipalId());
    }

    /**
     * @return bool|Principal
     */
    public function canAdd()
    {
        if ($this->isAdmin() || $this->isOffice()) {
            return new Principal();
        }

        return false;
    }

    /**
     * @param mixed $principal
     *
     * @return bool|Principal
     */
    public function canEdit($principal)
    {
        $principal = $this->findOneById($principal);

        if ($this->isAdmin() || $this->isOffice()) {
            return $principal;
        }

        return false;
    }

    /**
     * @param mixed $principal
     *
     * @return bool|Principal
     */
    public function canDelete($principal)
    {
        $principal = $this->findOneById($principal);

        if ($this->isAdmin() || $this->isOffice()) {
            return $principal;
        }

        return false;
    }

    /**
     * @param mixed $principal
     *
     * @return bool|Principal
     */
    public function canView($principal)
    {
        return $this->findOneById($principal);
    }
}
