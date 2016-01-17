<?php

/**
 * This file is part of the PGS/AdminBundle package.
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
use PGS\CoreDomainBundle\Manager\StudentHistoryManager;
use PGS\CoreDomainBundle\Model\StudentHistory\StudentHistory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

class StudentHistoryController extends AbstractCoreBaseController
{
    /**
     * @var StudentHistoryManager
     *
     * @Inject("pgs.core.manager.student.history")
     */
    protected $studentHistoryManager;

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:Level:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $levels = $this->studentHistoryManager->findAll();

        return [
            'model'  => 'Level',
            'levels' => $levels
        ];
    }


}
