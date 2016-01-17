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
use PGS\CoreDomainBundle\Manager\TermManager;
use PGS\CoreDomainBundle\Model\Term\Term;
use PGS\CoreDomainBundle\Model\Term\TermI18n;
use PGS\CoreDomainBundle\Model\Term\TermQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

class TermController extends AbstractCoreBaseController
{
    /**
     * @var TermManager
     *
     * @Inject("pgs.core.manager.term")
     */
    protected $termManager;

    /**
     * @Template("PGSCoreDomainBundle:Term:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $terms = $this->termManager->findAll();

        return [
            'model' => 'Term',
            'terms' => $terms
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:Term:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$term = $this->termManager->findOne($id))
        {
            throw $this->createNotFoundException('Invalid `term` given');
        }

        return [
            'model'=> 'Term',
            'term' => $term
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:Term:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $term = new Term();

        if (!$term = $this->termManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_term_list'));
        }

        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            $termI18n = new TermI18n();
            $termI18n->setLocale($locale);

            $term->addTermI18n($termI18n);
        }

        $form = $this->createForm($this->get('pgs.core.form.type.term'), $term);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_admin_term_list'));
            }
        }

        return [
            'model' => 'Term',
            'term'  => $term,
            'form'  => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:Term:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$term = $this->termManager->canEdit($id)) {
            throw $this->createNotFoundException('Invalid `term` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.term'), $term);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_term_list'));
            }
        }

        return [
            'model' => 'Term',
            'term'  => $term,
            'form'  => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$term = $this->termManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `term` given');
        }
        $term->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_term_list'));
    }


    /**
     * @param Form $form
     */
    public function processForm(Form $form)
    {
        /** @var Term $term*/
        $term = $form->getData();
        $term->save();
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
            return new RedirectResponse($this->generateUrl('pgs_core_term_list'));
        }

        if (!$term = $this->termManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `term` given');
        }

        $this->termManager->moveTerm($term, $direction);

        return new RedirectResponse($this->generateUrl('pgs_core_term_list'));
    }
}
