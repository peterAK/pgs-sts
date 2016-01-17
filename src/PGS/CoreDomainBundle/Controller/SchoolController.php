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
use PGS\CoreDomainBundle\Manager\SchoolManager;
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Model\School\SchoolI18n;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SchoolController extends AbstractCoreBaseController
{
    /**
     * @var SchoolManager
     * @Inject("pgs.core.manager.school")
     */
    protected $schoolManager;

    /**
     * @var SchoolYearManager
     * @Inject("pgs.core.manager.school_year")
     */
    protected $schoolYearManager;

    /**
     * @Template("PGSCoreDomainBundle:School:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $schools  = $this->schoolManager->findAll()->find();
        $statuses = $this->schoolManager->getStatuses();
//        $active   = $request->get('active','All');

        return [
            'model'    => 'School',
            'schools'  => $schools,
            'statuses' => $statuses,
//            'active'   => $active
        ];
    }

    /**
     * @Template("PGSCoreDomainBundle:School:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$school = $this->schoolManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `school` given');
        }

        return [
            'model'  => 'School',
            'school' => $school
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('SCHOOL_ADMIN')")
     * @Template("PGSCoreDomainBundle:School:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $organizationId = $request->get('organization', null);

        if ($organizationId === null) {
            if (!$organization = $this->getActivePreference()->getOrganizationPreference()) {
                if ($this->isGranted('ROLE_ADMIN')) {
                    $organization = $this->getOrganizationManager()->findOne(1);
                }
            }
            $organizationId = $organization->getId();
        }

        if (!$organization = $this->getOrganizationManager()->findOne($organizationId)) {
            throw $this->createNotFoundException('Invalid `organization` given');
        }

        $school = new School();

        if (!$school = $this->schoolManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_school_list'));
        }

        $school->setOrganizationId($organizationId);
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
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_school_list'));
            }
        }

        return [
            'model'  => 'School',
            'school' => $school,
            'form'   => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('SCHOOL_ADMIN')")
     * @Template("PGSCoreDomainBundle:School:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$school = $this->schoolManager->canEdit($id)) {
            throw $this->createNotFoundException('Invalid `school` given');
        }

        if (!$this->schoolManager->canEdit($school)) {
            return $this->createAccessDeniedException('Unauthorized access');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.school'), $school);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_schools_list'));
            }
        }

        return [
            'model'     => 'School',
            'school'    => $school,
            'form'      => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('SCHOOL_ADMIN')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$school = $this->schoolManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `school` given');
        }

        if ($this->schoolManager->canDelete($school)) {
            $school->delete();
        }

        return new RedirectResponse($this->generateUrl('pgs_core_schools_list'));
    }

    /**
     * @param Form $form
     */
    private function processForm(Form $form)
    {
        /** @var School $school*/
        $school = $form->getData();
        $school->save();

        if (!file_exists('uploads/school/' . $school->getId() . '/' . $school->getLogo())) {
            if (!file_exists('uploads/school/' . $school->getId() )) {
                mkdir('uploads/school/' . $school->getId(), 0777, true);
            }
            rename('uploads/school/temp/' . $school->getLogo(), 'uploads/school/' . $school->getId() . '/' . $school->getLogo());
        }

        $school->save();
    }

    /**
     * @Template("PGSCoreDomainBundle:School:list_by_status.html.twig")
     * @param int $status
     *
     * @return Response
     */
    public function viewSchoolByStatusAction($status)
    {
        $schools = [];
        $schools = $this->schoolManager->findByStatus($status);

        return [ 'schools' => $schools ];
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
        $id = $request->get('id', null);
        $direction = $request->get('direction', null);

        if ($direction === null) {
            return new RedirectResponse($this->generateUrl('pgs_core_schools_list'));
        }

        if (!$school= $this->schoolManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `school` given');
        }

        $this->schoolManager->moveSchool($school, $direction);

        return new RedirectResponse($this->generateUrl('pgs_core_schools_list'));
    }

    /**
     * @param null $id
     *
     * @return JsonResponse
     */
    public function uploadAction($id = null)
    {
        try {
            if ($id == null) {
                if (!file_exists('uploads/school/temp')) {
                    mkdir('uploads/school/temp', 0777, true);
                }
                $file_path = '/uploads/school/temp/';
            } else {
                $file_path = '/uploads/school/' . $id . '/';
            }

            $path_parts = pathinfo($_FILES["images"]["name"][0]);
            $name = $path_parts['filename'] . '_' . time() . '.' . $path_parts['extension'];

            $target = $_SERVER['DOCUMENT_ROOT'] . $file_path;
            move_uploaded_file($_FILES["images"]["tmp_name"][0], $target . $name);

            $result = array('path' => $file_path, 'filename' => $name, 'inputId' => 'school_logo');

            $result = json_encode($result);

            return new JsonResponse($result);
        } catch (Exception $e) {
            return new JsonResponse('failed');
        }
    }

}
