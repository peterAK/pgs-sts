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
use PGS\CoreDomainBundle\Controller\AbstractBaseController as CoreAbstractBaseController;
use PGS\CoreDomainBundle\Manager\SchoolTestManager;
use PGS\CoreDomainBundle\Model\SchoolTest\SchoolTest;
use PGS\CoreDomainBundle\Model\SchoolTest\SchoolTestI18n;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SchoolTestController extends CoreAbstractBaseController
{
    /**
     * @var SchoolTestManager
     * @Inject("pgs.core.manager.school_test")
     */
    protected $schoolTestManager;


    /**
     * @Template("PGSCoreDomainBundle:SchoolTest:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $schoolTests = $this->schoolTestManager->findAll();
        $statuses    = $this->schoolTestManager->getStatuses();

        return [
            'model'       => 'School Test',
            'schoolTests' => $schoolTests,
            'statuses'    => $statuses
        ];
    }

    /**
     * @Template("PGSCoreDomainBundle:SchoolTest:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolTest = $this->schoolTestManager->canView($id)) {
            throw $this->createNotFoundException('Invalid `school test` given');
        }

        return [
            'model'      => 'School Test',
            'schoolTest' => $schoolTest
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_SCHOOL') or hasRole('ROLE_PRINCIPAL')")
     *
     * @Template("PGSCoreDomainBundle:SchoolTest:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $schoolTest = new SchoolTest();

        if (!$schoolTest = $this->schoolTestManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_school_test_list'));
        }

        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            $schoolTestI18n = new SchoolTestI18n();
            $schoolTestI18n->setLocale($locale);

            $schoolTest->addSchoolTestI18n($schoolTestI18n);
        }

        $form = $this->createForm($this->get('pgs.core.form.type.school_test'), $schoolTest);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_school_test_list'));
            }
        }

        return [
            'model'      => 'School Test',
            'schoolTest' => $schoolTest,
            'form'       => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_SCHOOL') or hasRole('ROLE_PRINCIPAL')")
     *
     * @Template("PGSCoreDomainBundle:SchoolTest:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolTest = $this->schoolTestManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `school test` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.school_test'), $schoolTest);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_school_test_list'));
            }
        }

        return [
            'model'      => 'School Test',
            'schoolTest' => $schoolTest,
            'form'       => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_SCHOOL') or hasRole('ROLE_PRINCIPAL')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolTest = $this->schoolTestManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `school test` givern');
        }

        $schoolTest->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_school_test_list'));
    }

    /**
     * @param Form $form
     */
    private function processForm(Form $form)
    {
        /** @var SchoolTest $schoolTest*/
        $schoolTest = $form->getData();
        $schoolTest->save();
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function moveAction(Request $request)
    {
        $id        = $request->get('id',null);
        $direction = $request->get('direction', null);

        if ($direction === null) {
            return new RedirectResponse($this->generateUrl('pgs_core_school_test_list'));
        }

        if (!$schoolTest = $this->schoolTestManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `school test` given');
        }

        $this->schoolTestManager->moveSchoolTest($schoolTest, $direction);

        return new RedirectResponse($this->generateUrl('pgs_core_school_test_list'));
    }

    /**
     * @Template("PGSCoreDomainBundle:SchoolTest:list_by_status.html.twig")
     * @param int $status
     *
     * @return Response
     */
    public function viewSchoolTestByStatusAction($status)
    {
        $schoolTests = [];
        $schoolTests = $this->schoolTestManager->findByStatus($status);

        return [ 'schoolTests' => $schoolTests ];
    }

}
