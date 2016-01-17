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
use PGS\CoreDomainBundle\Manager\LevelManager;
use PGS\CoreDomainBundle\Model\Level\Level;
use PGS\CoreDomainBundle\Model\Level\LevelI18n;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

class LevelController extends AbstractCoreBaseController
{
    /**
     * @var LevelManager
     *
     * @Inject("pgs.core.manager.level")
     */
    protected $levelManager;

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:Level:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $levels = $this->levelManager->findAll();

        return [
            'model'  => 'Level',
            'levels' => $levels
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:Level:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$level = $this->levelManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `level` given');
        }

        return [
            'model' => 'Level',
            'level' => $level
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:Level:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $level = new Level();

        if(!$level=$this->levelManager->canAdd()){
            return new RedirectResponse($this->generateUrl('pgs_core_level_list'));
        }
        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            $levelI18n = new LevelI18n();
            $levelI18n->setLocale($locale);

            $level->addLevelI18n($levelI18n);
        }

        $form = $this->createForm($this->get('pgs.core.form.type.level'), $level);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_level_level_list'));
            }
        }

        return [
            'model' => 'Level',
            'level' => $level,
            'form'  => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:Level:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$level = $this->levelManager->canEdit($id)) {
            throw $this->createNotFoundException('Invalid `level` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.level'), $level);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);
                return new RedirectResponse($this->generateUrl('pgs_core_level_list'));
            }
        }

        return [
            'model' => 'Level',
            'level' => $level,
            'form'  => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$level = $this->levelManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `level` given');
        }

        $level->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_level_list'));
    }

    /**
     * @param Form $form
     */
    public function processForm(Form $form)
    {
        /** @var Level $level*/
        $level = $form->getData();
        $level->save();
    }
}
