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

use Symfony\Component\Security\Core\SecurityContext;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Model\User;
use PGS\CoreDomainBundle\Model\UserLog;
use PGS\CoreDomainBundle\Repository\UserLogRepository;

/**
 * @Service("pgs.core.manager.user_log")
 */
class UserLogManager
{
    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var UserLogRepository
     */
    protected $userLogRepository;

    /**
     * @InjectParams({
     *      "securityContext"    = @Inject("security.context"),
     *      "userLogRepository"  = @Inject("pgs.core.repository.user_log")
     * })
     */
    public function __construct(
        SecurityContext $securityContext,
        UserLogRepository $userLogRepository
    ) {
        $this->securityContext   = $securityContext;
        $this->userLogRepository = $userLogRepository;
    }
}
