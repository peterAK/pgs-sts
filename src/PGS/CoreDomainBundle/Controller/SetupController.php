<?php

/**
 * This file is part of the PGS/PrincipalBundle package.
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
use PGS\CoreDomainBundle\Manager\UserProfileManager;
use PGS\CoreDomainBundle\Model\Organization\Organization;
use PGS\CoreDomainBundle\Model\Organization\OrganizationI18n;
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Model\School\SchoolI18n;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class SetupController extends AbstractCoreBaseController
{
    /**
     * @var UserProfileManager
     * @Inject("pgs.core.manager.user_profile")
     */
    protected $userProfileManager;

    /**
     * @Template("PGSCoreDomainBundle:Registration:profile.html.twig")
     */
    public function profileAction(Request $request)
    {
        if (!$userProfile = $this->userProfileManager->findOneProfileById($this->getUser()->getId())) {
            throw $this->createNotFoundException('Invalid `user profile` given');
        }

        $form = $this->createForm($this->get('pgs.admin.form.type.user_profile'), $userProfile);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processProfileForm($form);

                return $this->roleRedirect();
            }
        }

        return [
            'model'       => 'User Profile',
            'userProfile' => $userProfile,
            'form'        => $form->createView()
        ];
    }


    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     *
     * @Template("PGSCoreDomainBundle:Registration:organization.html.twig")
     */
    public function organizationAction(Request $request)
    {
        // check if there is already an organization has been created, redirect to homepage
        if ($organization = $this->getActivePreference()->getOrganizationPreference()) {
            return $this->roleRedirect();
        }

        $organization = new Organization();

        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            $organizationI18n = new OrganizationI18n();
            $organizationI18n->setLocale($locale);

            $organization->addOrganizationI18n($organizationI18n);
        }

        $form = $this->createForm($this->get('pgs.core.form.type.organization'), $organization);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processOrganizationForm($form);

                return $this->roleRedirect();
            }
        }

        return [
            'model'        => 'Organization',
            'organization' => $organization,
            'form'         => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     *
     * @Template("PGSCoreDomainBundle:Registration:school.html.twig")
     */
    public function schoolAction(Request $request)
    {
        // check if there is at least a school has been created, redirect to homepage
        if ($school = $this->getActivePreference()->getSchoolPreference()) {
            return $this->roleRedirect();
        }

        $school = new School();

        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            $schoolI18n = new SchoolI18n();
            $schoolI18n->setLocale($locale);

            $school->addSchoolI18n($schoolI18n);
        }

        $form = $this->createForm($this->get('pgs.core.form.type.school'), $school);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processSchoolForm($form);

                return $this->roleRedirect();
            }
        }

        return [
            'model'  => 'School',
            'school' => $school,
            'form'   => $form->createView()
        ];
    }

    /**
     * @param Form $form
     */
    private function processProfileForm(Form $form)
    {
        $object = $form->getData();
        $object->setComplete(true);
        $object->save();
    }

    /**
     * @param Form $form
     */
    private function processOrganizationForm(Form $form)
    {
        $object = $form->getData();
        if ($object->isNew()) {
            $object->setUser($this->getSecurityContext()->getToken()->getUser());
        }
        $object->save();
        $this->getActivePreference()->setOrganizationPreference($object->getId());
    }

    /**
     * @param Form $form
     */
    private function processSchoolForm(Form $form)
    {
        $object = $form->getData();
        $object->save();
        $this->getActivePreference()->setSchoolPreference($object->getId());
    }
}
