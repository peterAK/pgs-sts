<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Created by Peter on 1/20/2015.
 */


namespace PGS\CoreDomainBundle\Controller;

use PGS\CoreDomainBundle\Container\ActivePreferenceContainer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use PGS\CoreDomainBundle\Model\User;


abstract class AbstractBaseController extends Controller implements ContainerAwareInterface
{
    const FLASH_TYPE_NOTICE   = 'notice';
    const FLASH_TYPE_ALERT    = 'alert';
    const FLASH_TYPE_SUCCESS  = 'success';

    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * @var SessionInterface
     */
    protected $session;

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
     * @return bool
     */
    protected function isPagesOn()
    {
        return $this->container->getParameter('site_pages');
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
     * @return ActivePreferenceContainer
     */
    protected function getActivePreference()
    {
        return $this->get('pgs.core.container.active_preference');
    }

    /**
     * This has to be a public method because for some weird
     * reason, the Symfony Base Controller implements this
     * method and is public. Changing its access to protected
     * will cause a PHP Fatal Error.
     *
     * @return User
     */
    public function getUser()
    {
        $user = $this->getSecurityContext()->getToken()->getUser();

        return $user;
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


    public function createAccessDeniedException($message = 'Access Denied', \Exception $previous = null)
    {
        return new AccessDeniedException($message, $previous);
    }

    /**
     * @return string
     */
    private function getKernelRootDir()
    {
        return $this->container->getParameter('%kernel.root_dir%');
    }

    public function roleRedirect()
    {
        $this->securityContext = $this->getSecurityContext();

        if ($this->securityContext->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($this->container->get('router')->generate('pgs_admin_dashboard'));
        }

        if ($this->securityContext->isGranted('ROLE_SCHOOL')) {
            return new RedirectResponse($this->container->get('router')->generate('pgs_school_dashboard'));
        }

        if ($this->securityContext->isGranted('ROLE_PRINCIPAL')) {
            return new RedirectResponse($this->container->get('router')->generate('pgs_principal_dashboard'));
        }

        if ($this->securityContext->isGranted('ROLE_STUDENT')) {
            return new RedirectResponse($this->container->get('router')->generate('pgs_student_dashboard'));
        }

        if ($this->securityContext->isGranted('ROLE_PARENT')) {
            return new RedirectResponse($this->container->get('router')->generate('pgs_parent_dashboard'));
        }

        if ($this->securityContext->isGranted('ROLE_TEACHER')) {
            return new RedirectResponse($this->container->get('router')->generate('pgs_teacher_dashboard'));
        }

        if ($this->securityContext->isGranted('ROLE_COUNSELOR')) {
            return new RedirectResponse($this->container->get('router')->generate('pgs_counselor_dashboard'));
        }

        if ($this->securityContext->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->container->get('router')->generate('homepage'));
        }

        return new RedirectResponse($this->container->get('router')->generate('homepage'));
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->securityContext->isGranted('IS_AUTHENTICATED_FULLY');
    }
}
