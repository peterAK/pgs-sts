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
use PGS\CoreDomainBundle\Manager\EthnicityManager;
use PGS\CoreDomainBundle\Model\Ethnicity\Ethnicity;
use PGS\CoreDomainBundle\Model\Ethnicity\EthnicityI18n;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

class EthnicityController extends AbstractCoreBaseController
{
    /**
     * @var EthnicityManager
     *
     * @Inject("pgs.core.manager.ethnicity")
     */
    protected $ethnicityManager;

    /**
     * @Template("PGSCoreDomainBundle:Ethnicity:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $ethnicities = $this->ethnicityManager->findAll();

        return [
            'model'       => 'Ethnicity',
            'ethnicities' => $ethnicities
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:Ethnicity:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$ethnicity = $this->ethnicityManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `ethnicity` given');
        }

        return [
            'model'     => 'Ethnicity',
            'ethnicity' => $ethnicity
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:Ethnicity:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $ethnicity = new Ethnicity();

        if (!$ethnicity = $this->ethnicityManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_ethnicity_list'));
        }

        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            $ethnicityI18n = new EthnicityI18n();
            $ethnicityI18n->setLocale($locale);

            $ethnicity->addEthnicityI18n($ethnicityI18n);
        }

        $form = $this->createForm($this->get('pgs.core.form.type.ethnicity'), $ethnicity);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_ethnicity_list'));
            }
        }

        return [
            'model'     => 'ethnicity',
            'ethnicity' => $ethnicity,
            'form'      => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:Course:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$ethnicity = $this->ethnicityManager->canEdit($id)) {
            throw $this->createNotFoundException('Invalid `ethnicity` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.ethnicity'), $ethnicity);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_ethnicity_list'));
            }
        }

        return [
            'model'     => 'Ethnicity',
            'ethnicity' => $ethnicity,
            'form'      => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$ethnicity = $this->ethnicityManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `ethnicity` given');
        }

        $ethnicity->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_ethnicity_list'));
    }

    /**
     * @param Form $form
     */
    private function processForm(Form $form)
    {
        /** @var Ethnicity $ethnicity */
        $ethnicity = $form->getData();
        if ($ethnicity->isNew()) {
            $ethnicity->setAuthorId($this->getUser()->getId());
        }

        $ethnicity->save();
    }
}
