<?php

/**
 * This file is part of the PGS/PublicBundle package.
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
use PGS\CoreDomainBundle\Manager\SchoolHealthManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealth;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

class SchoolHealthController extends AbstractCoreBaseController
{
    /**
     * @var SchoolHealthManager
     * @Inject("pgs.core.manager.school_health")
     */
    protected $schoolHealthManager;

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:SchoolHealth:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $schoolHealth = $this->schoolHealthManager->findAll();

        return [
            'model'        => 'School Health',
            'schoolHealth' => $schoolHealth
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:SchoolHealth:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('applicationId');

        if (!$schoolHealth = $this->schoolHealthManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `student health` given');
        }

        return [
            'model'        => 'School Health',
            'schoolHealth' => $schoolHealth
        ];
    }

    /**
     *
     * @Template("PGSCoreDomainBundle:SchoolHealth:new.html.twig")
     */
    public function newAction(Request $request)
    {
        $applicationId = $request->get('applicationId');

        $schoolHealth = new SchoolHealth();
        $schoolHealth->setApplicationId($applicationId);

        $form = $this->createForm($this->get('pgs.core.form.type.applicationStep2'), $schoolHealth);

        if ($request->getMethod() == "POST") {

            $form->submit($request);

            if ($form->isValid()) {
                $schoolHealthId = $this->processForm($form);
                return new RedirectResponse($this->generateUrl('pgs_core_student_medical_list',['schoolHealthId' => $schoolHealthId]));
            }
        }

        return [
            'model'        => 'School Health',
            'schoolHealth' => $schoolHealth,
            'form'         => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:SchoolHealth:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('applicationId');

        if (!$schoolHealth = $this->schoolHealthManager->findOneByApplicationId($id)) {
            throw $this->createNotFoundException('Invalid `student health` given');
        }
        $form = $this->createForm($this->get('pgs.core.form.type.applicationStep2'), $schoolHealth);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $schoolHealthId = $this->processForm($form);
                return new RedirectResponse($this->generateUrl('pgs_core_student_medical_view',['schoolHealthId' => $schoolHealthId]));
            }
        }

        return [
            'model'        => 'School Health',
            'schoolHealth' => $schoolHealth,
            'form'         => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolHealth = $this->schoolHealthManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `student health` given');
        }

        $schoolHealth->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_school_health_list'));
    }

    /**
     * @param Form $form
     * @return int
     */
    public function processForm(Form $form)
    {
        /** @var SchoolHealth $schoolHealth*/
        $schoolHealth = $form->getData();
        $schoolHealth->save();

        return $schoolHealth->getId();
    }
}
