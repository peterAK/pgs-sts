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
use PGS\CoreDomainBundle\Manager\SubjectManager;
use PGS\CoreDomainBundle\Model\Subject\Subject;
use PGS\CoreDomainBundle\Model\Subject\SubjectI18n;
use PGS\CoreDomainBundle\Model\Subject\SubjectQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;


class SubjectController extends AbstractCoreBaseController
{
    /**
     * @var SubjectManager
     *
     * @Inject("pgs.core.manager.subject")
     */
    protected $subjectManager;

    /**
     * @Template("PGSCoreDomainBundle:Subject:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $subjects = $this->subjectManager->findAll();

        return [
            'model'    => 'Subject',
            'subjects' => $subjects
        ];
    }

    /**
     * @Template("PGSCoreDomainBundle:subject:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$subject = $this->subjectManager->findOne($id))
        {
            throw $this->createNotFoundException('Invalid `subject` given');
        }

        return [
            'model'   => 'Subject',
            'subject' => $subject
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:Subject:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $subject = new Subject();

        if (!$subject= $this->subjectManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_subject_list'));
        }

        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            $subjectI18n = new SubjectI18n();
            $subjectI18n->setLocale($locale);

            $subject->addSubjectI18n($subjectI18n);
        }

        $form = $this->createForm($this->get('pgs.core.form.type.subject'), $subject);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_subject_list'));
            }
        }

        return [
            'model'    => 'Subject',
            'subject'  => $subject,
            'form'     => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:Subject:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$subject = $this->subjectManager->canEdit($id)) {
            throw $this->createNotFoundException('Invalid `subject` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.subject'), $subject);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_subject_list'));
            }
        }

        return [
            'model'    => 'Subject',
            'subject'  => $subject,
            'form'     => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$subject = $this->subjectManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `subject` given');
        }
        $subject->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_subject_list'));
    }


    /**
     * @param Form $form
     */
    public function processForm(Form $form)
    {
        /** @var Subject $subject*/
        $subject = $form->getData();
        $subject->save();
    }
}
