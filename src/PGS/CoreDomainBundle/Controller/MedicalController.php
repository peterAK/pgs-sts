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
use PGS\CoreDomainBundle\Manager\MedicalManager;
use PGS\CoreDomainBundle\Model\Medical\Medical;
use PGS\CoreDomainBundle\Model\Medical\MedicalI18n;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Intl\Intl;

class MedicalController extends AbstractCoreBaseController
{
    /**
     * @var MedicalManager
     *
     * @Inject("pgs.core.manager.medical")
     */
    protected $medicalManager;

    /**
     * @Template("PGSCoreDomainBundle:Medical:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $medicals = $this->medicalManager->findAll();

        return [
            'model'    => 'Medical',
            'medicals' => $medicals
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:Medical:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$medical = $this->medicalManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `medical` given');
        }

        return [
            'model'   => 'Medical',
            'medical' => $medical
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:Medical:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $medical = new Medical();

        if (!$medical = $this->medicalManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_medical_list'));
        }

        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            $medicalI18n = new MedicalI18n();
            $medicalI18n->setLocale($locale);
            $medical->addMedicalI18n($medicalI18n);
        }

        $form = $this->createForm($this->get('pgs.core.form.type.medical'), $medical);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_medical_list'));
            }
        }

        return [
            'model'   => 'Medical',
            'medical' => $medical,
            'form'    => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     *
     * @Template("PGSCoreDomainBundle:Medical:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$medical = $this->medicalManager->canEdit($id)) {
            throw $this->createNotFoundException('Invalid `medical` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.medical'), $medical);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_medical_list'));
            }
        }

        return [
            'model'   => 'Medical',
            'medical' => $medical,
            'form'    => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$medical = $this->medicalManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `medical` given');
        }

        $medical->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_medical_list'));
    }

    /**
     * @param Form $form
     */
    public function processForm(Form $form)
    {
        /** @var Medical $medical*/
        $medical = $form->getData();
        $medical->save();
    }
}
