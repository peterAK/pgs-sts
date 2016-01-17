<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Controller;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use JMS\SecurityExtraBundle\Security\Authorization\Expression\Expression;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Intl\Intl;

class PreferenceController extends AbstractBaseController
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @InjectParams({
     *     "router" = @Inject("router")
     * })
     *
     * @param RouterInterface $router
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @Secure(roles="ROLE_ADMIN, ROLE_PRINCIPAL, ROLE_SCHOOL")
     */
    public function resetAction(Request $request)
    {
        $entity = $request->get('entity',null);

        if( $entity !== null ) {
            $this->getActivePreference()->unsetPreference($entity);
        }

        return new RedirectResponse($this->getRedirectUrlFromReferrer($request->headers));
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function organizationAction(Request $request)
    {
        $this->getActivePreference()->setOrganizationPreference($request->get('organizationId'));

        return new RedirectResponse($this->getRedirectUrlFromReferrer($request->headers));
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function schoolAction(Request $request)
    {
        $this->getActivePreference()->setSchoolPreference($request->get('schoolId'));

        return new RedirectResponse($this->getRedirectUrlFromReferrer($request->headers));
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function schoolYearAction(Request $request)
    {
        $this->getActivePreference()->setSchoolYearPreference($request->get('schoolYearId'));

        return new RedirectResponse($this->getRedirectUrlFromReferrer($request->headers));
    }

    /**
     * @param HeaderBag $headers
     *
     * @return string
     */
    private function getRedirectUrlFromReferrer(HeaderBag $headers)
    {
        if ($headers->has('referer') && $headers->get('referer')) {
            $url = $headers->get('referer');
        } else {
            $url = $this->router->generate('homepage');
        }

        return $url;
    }
}
