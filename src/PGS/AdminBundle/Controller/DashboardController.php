<?php

/**
 * This file is part of the PGS/AdminBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\AdminBundle\Controller;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use PGS\CoreDomainBundle\Controller\AbstractBaseController As CoreAbstractBaseController;
use PGS\CoreDomainBundle\Repository\SchoolYearRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DashboardController extends CoreAbstractBaseController
{
    /**
     * @Template("PGSAdminBundle:Dashboard:index.html.twig")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
    }
}
