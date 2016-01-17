<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Security;

use PGS\CoreDomainBundle\Model\UserLog;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AuthenticationHandler extends ContainerAware implements AuthenticationSuccessHandlerInterface
{
    /**
     * @param Request $request
     * @param TokenInterface $token
     *
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $token->getUser()->setLastlogin(new \DateTime());
        $token->getUser()->save();

        $log = new UserLog();
        $log->setIpSource($request->getClientIp());
        $token->getUser()->addUserLog($log);
        $token->getUser()->save();

        /** @var SecurityContext $securityContext */
        $securityContext = $this->container->get('security.context');

        if ($securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            return new RedirectResponse($this->container->get('router')->generate('sonata_admin_dashboard'));
        }

        if ($securityContext->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($this->container->get('router')->generate('pgs_admin_dashboard'));
        }

        if ($securityContext->isGranted('ROLE_SCHOOL')) {
            return new RedirectResponse($this->container->get('router')->generate('pgs_school_dashboard'));
        }

        if ($securityContext->isGranted('ROLE_PRINCIPAL')) {
            return new RedirectResponse($this->container->get('router')->generate('pgs_principal_dashboard'));
        }

        if ($securityContext->isGranted('ROLE_STUDENT')) {
            return new RedirectResponse($this->container->get('router')->generate('pgs_student_dashboard'));
        }

        if ($securityContext->isGranted('ROLE_PARENT')) {
            return new RedirectResponse($this->container->get('router')->generate('pgs_parent_dashboard'));
        }

        if ($securityContext->isGranted('ROLE_TEACHER')) {
            return new RedirectResponse($this->container->get('router')->generate('pgs_teacher_dashboard'));
        }

        if ($securityContext->isGranted('ROLE_COUNSELOR')) {
            return new RedirectResponse($this->container->get('router')->generate('pgs_counselor_dashboard'));
        }

        if ($securityContext->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->container->get('router')->generate('homepage'));
        }
    }
}
