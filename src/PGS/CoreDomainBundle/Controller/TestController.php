<?php

/**
 * This file is part of the PGS/PGSCoreDomainBundle package.
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
use PGS\CoreDomainBundle\Manager\TestManager;
use PGS\CoreDomainBundle\Model\Test\Test;
use PGS\CoreDomainBundle\Model\Test\TestQuery;
use PGS\CoreDomainBundle\Model\Test\TestI18n;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

class TestController extends AbstractCoreBaseController
{
    /**
     * @var TestManager
     *
     * @Inject("pgs.core.manager.test")
     */
    protected $testManager;

    /**
     * @Template("PGSCoreDomainBundle:Test:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $tests    = $this->testManager->findAll();
        $statuses = $this->testManager->getStatuses();
        $active   = $request->get('active','All');

        return [
            'model'    => 'Test',
            'tests'    => $tests,
            'statuses' => $statuses,
            'active'   => $active
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:Test:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$test = $this->testManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `test` given');
        }

        return [
            'model' => 'Test',
            'test' => $test
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:Test:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $test = new Test();

        if (!$grade = $this->testManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_test_list'));
        }
        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            $testI18n = new TestI18n();
            $testI18n->setLocale($locale);

            $test->addTestI18n($testI18n);
        }

        $form = $this->createForm($this->get('pgs.core.form.type.test'), $test);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_test_list'));
            }
        }

        return [
            'model' => 'Test',
            'test' => $test,
            'form'  => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:Test:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$test = $this->testManager->canEdit($id)) {
            throw $this->createNotFoundException('Invalid `test` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.test'), $test);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);
                return new RedirectResponse($this->generateUrl('pgs_core_test_list'));
            }
        }

        return [
            'model' => 'Test',
            'test' => $test,
            'form'  => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$test = $this->testManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `test` given');
        }

        $test->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_test_list'));
    }

    /**
     * @param Form $form
     */
    public function processForm(Form $form)
    {
        /** @var Test $test*/
        $test = $form->getData();

        if ($test->isNew()) {
            $test->setUser($this->getUser());
        }

        $test->save();
    }

    /**
     * @Template("PGSCoreDomainBundle:Test:list_by_status.html.twig")
     * @param Request $request
     *
     * @return Response
     */
    public function viewTestByStatusAction(Request $request)
    {
        $status = $request->get('status', 'All');

        $tests = [];
        $tests = $this->testManager->findByStatus($status);

        return [ 'tests' => $tests ] ;
    }
}
