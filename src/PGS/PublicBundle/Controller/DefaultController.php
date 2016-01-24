<?php

/**
 * This file is part of the PGS/PublicBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\PublicBundle\Controller;

use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use PGS\CoreDomainBundle\Manager\OrganizationManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends BaseController
{
    /**
     * @Template("PGSPublicBundle:Default:index.html.twig")
     */
    public function indexAction(Request $request)
    {
//        $activeOrganizations = $this->organizationManager->countActive();
//
//        return [
//            'activeOrganizations' => $activeOrganizations
//        ];
    }
}
