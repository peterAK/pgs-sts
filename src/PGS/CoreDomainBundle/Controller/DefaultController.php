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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use PGS\CoreDomainBundle\Model\User;

class DefaultController extends AbstractBaseController
{

    public function indexAction($name)
    {
        return $this->render('PGSCoreDomainBundle:Default:index.html.twig', array('name' => $name));
    }

    public function testAction()
    {
        $user = new User();
        $user->createQuery()->findById(1);
    }
}
