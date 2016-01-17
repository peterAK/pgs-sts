<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use PGS\CoreDomainBundle\Model\User;
use PGS\CoreDomainBundle\Model\UserLog;
use PGS\CoreDomainBundle\Manager\UserManager;
use PGS\CoreDomainBundle\Manager\UserLogManager;

/**
 * This is disabled, sample implementation of authentication handler using listener
 * @ Service("pgs.core.listener.security_listener")
 * @ Tag("kernel.event_listener", attributes={"event" = "security.authentication.success", "method"="onAuthenticationSuccess"})
 */
class SecurityListener implements EventSubscriberInterface
{
    /**
     * @var RequestStack
     */
    protected $request;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var UserLogManager
     */
    protected $userLogManager;

    /**
     * @InjectParams({
     *      "request"        = @Inject("request_stack"),
     *      "userManager"    = @Inject("pgs.core.manager.user"),
     *      "userLogManager" = @Inject("pgs.core.manager.user_log")
     * })
     */
    public function __construct(
        RequestStack $requestStack,
        UserManager $userManager,
        UserLogManager $userLogManager
    )
    {
        $this->request        = $requestStack;
        $this->userManager    = $userManager;
        $this->userLogManager = $userLogManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            AuthenticationEvents::AUTHENTICATION_SUCCESS => 'onAuthenticationSuccess'
        ];
    }

    /**
     * @param AuthenticationEvent $event
     */
    public function onAuthenticationSuccess(AuthenticationEvent $event)
    {
        /** @var User $user */
        $user = $this->userManager->findOneById($event->getAuthenticationToken()->getUser()->getId());

        // logging
        $userLog = new UserLog();
        $userLog
            ->setUser($user)
            ->setIpSource($this->request->getCurrentRequest()->getClientIp())
            ->save()
        ;
    }
}
