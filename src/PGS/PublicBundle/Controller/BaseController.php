<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\PublicBundle\Controller;

use PGS\CoreDomainBundle\Container\OldActivePreferenceContainer;
use PGS\CoreDomainBundle\Model\User;
use PGS\CoreDomainBundle\Model\UserProfile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;

abstract class BaseController extends Controller implements ContainerAwareInterface
{
    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * @var SessionInterface
     */
    protected $session;

    const FLASH_TYPE_NOTICE   = 'notice';
    const FLASH_TYPE_ALERT    = 'alert';
    const FLASH_TYPE_SUCCESS  = 'success';

    /**
     * @var array
     */
    public static $validFlashTypes = [
        self::FLASH_TYPE_NOTICE,
        self::FLASH_TYPE_ALERT,
        self::FLASH_TYPE_SUCCESS
    ];

    /**
     * @return array
     */
    protected function getLocales()
    {
        return $this->container->getParameter('locales');
    }

    /**
     * @return SecurityContext
     */
    protected function getSecurityContext()
    {
        return $this->get('security.context');
    }

    /**
     * @param string $role
     *
     * @return bool
     */
    protected function isGranted($role)
    {
        if (!$this->securityContext) {
            $this->securityContext = $this->getSecurityContext();
        }

        return $this->securityContext->isGranted($role);
    }

    /**
     * @return Session
     */
    protected function getSession()
    {
        return $this->get('session');
    }

    /**
     * @param string $type
     * @param string $message
     */
    protected function addFlash($type, $message)
    {
        if (!$this->session) {
            $this->session = $this->getSession();
        }

        $this->session->getFlashBag()->set('notice', 'Session  saved!');
    }

    /**
     * @return OldActivePreferenceContainer
     */
    protected function getActivePreference()
    {
        return $this->get('pgs.core.container.old_active_preference');
    }

    /**
     * @param array|string $data
     * @param int          $status
     * @param array        $headers
     *
     * @return JsonResponse
     */
    protected function jsonResponse($data, $status = Response::HTTP_OK, array $headers = [])
    {
        return new JsonResponse($data, $status, $headers);
    }

    /**
     * @param string $message
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    protected function createAccessDeniedException($message)
    {
        throw new AccessDeniedException($message);
    }

    /**
     * @return string
     */
    private function getKernelRootDir()
    {
        return $this->container->getParameter('%kernel.root_dir%');
    }
}
