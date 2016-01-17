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
use PGS\CoreDomainBundle\Manager\ApplicationManager;
use PGS\CoreDomainBundle\Manager\SchoolHealthManager;
use PGS\CoreDomainBundle\Manager\SchoolManager;
use PGS\CoreDomainBundle\Manager\SchoolYearManager;
use PGS\CoreDomainBundle\Model\ParentStudent\ParentStudent;
use PGS\CoreDomainBundle\Model\Student\Student;
use PGS\CoreDomainBundle\Model\StudentHistory\StudentHistory;
use PGS\CoreDomainBundle\Model\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PGS\CoreDomainBundle\Model\Application\Application;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealth;
use PGS\CoreDomainBundle\Model\UserProfile;
use PGS\CoreDomainBundle\Utility\PGSUtilities;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

class ApplicationController extends AbstractCoreBaseController
{
    /**
     * @var ApplicationManager
     * @Inject("pgs.core.manager.application")
     */
    protected $applicationManager;

    /**
     * @var SchoolHealthManager
     * @Inject("pgs.core.manager.school_health")
     */
    protected $schoolHealthManager;

    /**
     * @var SchoolYearManager
     * @Inject("pgs.core.manager.school_year")
     */
    protected $schoolYearManager;

    /**
     * @var SchoolManager
     * @Inject("pgs.core.manager.school")
     */
    protected $schoolManager;

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:Application:list.html.twig")
     */
    public function listAction(Request $request)
    {

        // if use paginate
        $query       = $this->applicationManager->findAll();
        $application = $this->get('knp_paginator');
        $application = $application->paginate(
            $query,
            $request->query->get('pg',1) /*page number*/,
            5
        );

//        $application = $this->applicationManager->findAll();
        $status      = $this->applicationManager->getStatuses();

        return [
            'model'         => 'Application',
            'application'  => $application,
            'statuses'        => $status

        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:Application:view.html.twig")
     */
    public function viewAction(Request $request)
    {

        $id = $request->get('id');

        if (!$application = $this->applicationManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `application` given');
        }
        $form = $this->createForm($this->get('pgs.core.form.type.applicationStep1'), $application);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
//                $this->processForm($form);

//                return new RedirectResponse($this->generateUrl('pgs_core_school_health_edit',['applicationId' => $id]));
            }
        }

        return [
            'model'       => 'Application',
            'application' => $application,
            'form'        => $form->createView()
        ];
    }

    /**
     * @Template("PGSCoreDomainBundle:Application:new.html.twig")
     */
    public function newAction(Request $request)
    {
        $application = new Application();

        $form = $this->createForm($this->get('pgs.core.form.type.applicationStep1'), $application);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $applicationId = $this->processForm($form);
                //$this->setApplicationStep1Paramater($request);
//                 $this->redirect('pgs_core_school_health_new',['applicationId' => $applicationId]);
                return new RedirectResponse($this->generateUrl('pgs_core_school_health_new',['applicationId' => $applicationId]));

            }
        }
            return [
            'title'       => 'New Application',
            'model'       => 'Application',
            'application' => $application,
            'form'        => $form->createView()
        ];
    
    }

    /**
     * @param Form $form
     * @return int
     */
    private function processForm(Form $form)
    {
        /** @var Application $application */
        $application = $form->getData();
        $application->save();

        return $application->getId();
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     *
     * @Template("PGSCoreDomainBundle:Application:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');
        if (!$application = $this->applicationManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `application` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.applicationStep1'), $application);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                 $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_school_health_edit',['applicationId' => $id]));
            }
        }

        return [
            'model'       => 'Application',
            'application' => $application,
            'form'        => $form->createView()
        ];
    }


    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     *
     * @Template("PGSCoreDomainBundle:Application:confirmation.mail.txt.twig")
     *
     */
    public function approveAction(Request $request)
    {
        $fosUserManager     = $this->container->get('fos_user.user_manager');
        $id                 = $request->get('id');
        $status             = $request->get('status');
        $application        = $this->applicationManager->findOne($id);
        $application->setStatus($status)->save();
        $health             = $this->schoolHealthManager->findOneByApplicationId($id);
        $school             = $this->schoolManager->findOneById($application->getSchoolId());
        $organization       = $school->getOrganizationId();
        $schoolYear         = $this->schoolYearManager->findOne($application->getSchoolId());
        $academic           = $schoolYear->getAcademicYearId();
        $schoolId           = $application->getSchoolId();
        $schoolYearId       = $application->getSchoolYearId();
        $userStudent        = null;
        $passStudent        = null;
        $userProfileStudent = null;
        $userFather         = null;
        $passFather         = null;
        $userMother         = null;
        $passMother         = null;
        $userGuardian       = null;
        $passGuardian       = null;
//        $this->sendingEmail($application);


        if($status == 'accepted'){

                $userStudent = new User();
                $userStudent->setUsername($application->getLastName().'0'.$application->getId());
                $userStudent->setUsernameCanonical($application->getLastName().'0'.$application->getId());
                $userStudent->setEmail($application->getMailingAddress());
                $userStudent->setEmailCanonical($application->getMailingAddress());
                $passStudent = PGSUtilities::randomizer('PGS', false);
                $userStudent->setPlainPassword($passStudent);
                $userStudent->setEnabled(1);
                $userStudent->setRoles(["ROLE_USER | ROLE_STUDENT"]);
                $fosUserManager->updateUser($userStudent);
                $userStudentId = $userStudent->getId();

                $userProfileStudent = new UserProfile();
                $userProfileStudent->setId($userStudentId);
                $userProfileStudent->setFirstName($application->getFirstName());
                $userProfileStudent->setLastName($application->getLastName());
                $userProfileStudent->setNickName($application->getNickName());
                $userProfileStudent->setPhone($application->getPhoneStudent());
                $userProfileStudent->setAddress($application->getAddress());
                $userProfileStudent->setCity($application->getCity());
                $userProfileStudent->setStateId($application->getStateId());
                $userProfileStudent->setZip($application->getZip());
                $userProfileStudent->setComplete(1);
                $userProfileStudent->setOrganizationId($organization);
                $this->setApplicationActivePreference($userProfileStudent,$organization,$academic,$schoolId,$schoolYearId);
//                $userProfileStudent->save();

                $student = new Student();
                $student->setUserId($userStudentId);
                $student->setApplicationId($application->getId());
                $student->setHealthId($health->getId());
                $student->setStudentNationNo($application->getStudentNationNo());
                $student->setFirstName($application->getFirstName());
                $student->setLastName($application->getLastName());
                $student->setMiddleName($application->getNickName());
                $student->setGender($application->getGender());
                $student->setPlaceOfBirth($application->getPlaceOfBirth());
                $student->setDateOfBirth($application->getDateOfBirth());
                $student->setReligion($application->getReligion());
                $student->setPicture($application->getPicture() );
                $student->setFamilyCard($application->getFamilyCard());
                $student->setBirthCertificate($application->getBirthCertificate());
                $student->setGraduationCertificate($application->getGraduationCertificate());
                $student->save();

                $studentHistory = new StudentHistory();
                $studentHistory->setStudentId($student->getId());
                $studentHistory->save();

            if(trim($application->getFirstNameFather())!= '' ){
                $userFather = new User();
                $userFather->setUsername($application->getLastNameFather().'1'.$application->getId());
                $userFather->setUsernameCanonical($application->getLastNameFather().'1'.$application->getId());
                $userFather->setEmail($application->getEmailFather());
                $userFather->setEmailCanonical($application->getEmailFather());
                $passFather = PGSUtilities::randomizer('PGS', false);
                $userFather->setPlainPassword($passFather);
                $userFather->setEnabled(1);
                $userFather->setRoles(["ROLE_USER | ROLE_PARENT"]);
                $userFather->setType("parent");
//                $userFather->setStatus(1);
                $fosUserManager->updateUser($userFather);
                $userFatherId = $userFather->getId();


                $userProfileFather = new UserProfile();
                $userProfileFather->setId($userFatherId);
                $userProfileFather->setFirstName($application->getFirstNameFather());
                $userProfileFather->setLastName($application->getLastNameFather());
//                $userProfileFather->setNickName('');
                $userProfileFather->setPhone($application->getPhoneFather());
//                $userProfileFather->setAddress('');
//                $userProfileFather->setCity('');
                $userProfileFather->setStateId($application->getStateId());
//                $userProfileFather->setZip('');
                $userProfileFather->setComplete(1);
                $userProfileFather->setOrganizationId($organization);
                $this->setApplicationActivePreference($userProfileFather,$organization,$academic,$schoolId,$schoolYearId);
                $userProfileFather->save();

                $father = new ParentStudent();
                $father->setApplicationId($application->getId());
                $father->setUserId($userFatherId);
                $father->setStudentId($student->getId());
                $father->save();
            }

            if(trim($application->getFirstNameMother())!= '' ){
                $userMother = new User();
                $userMother->setUsername($application->getLastNameMother().'2'.$application->getId());
                $userMother->setUsernameCanonical($application->getLastNameMother().'2'.$application->getId());
                $userMother->setEmail($application->getEmailMother());
                $userMother->setEmailCanonical($application->getEmailMother());
                $passMother = PGSUtilities::randomizer('PGS', false);
                $userMother->setPlainPassword($passMother);
                $userMother->setEnabled(1);
                $userMother->setRoles(["ROLE_USER | ROLE_PARENT"]);
                $userMother->setType("parent");
//                $userMother->setStatus(3);
                $fosUserManager->updateUser($userMother);
                $userMotherId = $userMother->getId();

                $userProfileMother = new UserProfile();
                $userProfileMother->setId($userMotherId);
                $userProfileMother->setFirstName($application->getFirstNameMother());
                $userProfileMother->setLastName($application->getLastNameMother());
//                $userProfileMother->setNickName('');
                $userProfileMother->setPhone($application->getPhoneMother());
//                $userProfileMother->setAddress('');
//                $userProfileMother->setCity('');
                $userProfileMother->setStateId($application->getStateId());
//                $userProfileMother->setZip('');
                $userProfileMother->setComplete(1);
                $userProfileMother->setOrganizationId($organization);
                $this->setApplicationActivePreference($userProfileMother,$organization,$academic,$schoolId,$schoolYearId);
                $userProfileMother->save();

                $mother = new ParentStudent();
                $mother->setApplicationId($application->getId());
                $mother->setUserId($userMotherId);
                $mother->setStudentId($student->getId());
                $mother->save();
            }
            if(trim($application->getFirstNameLegalGuardian())!= '' ){
                $userGuardian = new User();
                $userGuardian->setUsername($application->getLastNameLegalGuardian().'3'.$application->getId());
                $userGuardian->setUsernameCanonical($application->getLastNameLegalGuardian().'3'.$application->getId());
                $userGuardian->setEmail($application->getEmailLegalGuardian());
                $userGuardian->setEmailCanonical($application->getEmailLegalGuardian());
                $passGuardian = PGSUtilities::randomizer('PGS', false);
                $userGuardian->setPlainPassword($passGuardian);
                $userGuardian->setEnabled(1);
                $userGuardian->setRoles(["ROLE_USER | ROLE_PARENT"]);
                $userGuardian->setType('parent');
//                $userGuardian->setStatus(3);
                $fosUserManager->updateUser($userGuardian);
                $userGuardianId = $userGuardian->getId();
//
                $userProfileGuardian = new UserProfile();
                $userProfileGuardian->setId($userGuardianId);
                $userProfileGuardian->setFirstName($application->getFirstNameLegalGuardian());
                $userProfileGuardian->setLastName($application->getLastNameLegalGuardian());
//                $userProfileGuardian->setNickName('');
                $userProfileGuardian->setPhone($application->getPhoneLegalGuardian());
                $userProfileGuardian->setOccupation($application->getOccupationLegalGuardian());
//                $userProfileGuardian->setAddress('');
//                $userProfileGuardian->setCity('');
                $userProfileGuardian->setStateId($application->getStateId());
//                $userProfileGuardian->setZip('');
                $userProfileGuardian->setComplete(1);
                $userProfileGuardian->setOrganizationId($organization);
                $this->setApplicationActivePreference($userProfileGuardian,$organization,$academic,$schoolId,$schoolYearId);
                $userProfileGuardian->save();

                $guardian = new ParentStudent();
                $guardian->setApplicationId($application->getId());
                $guardian->setUserId($userGuardianId);
                $guardian->setStudentId($student->getId());
                $guardian->save();
            }
//            $this->sendingEmail();
            return [
                'student'        => $userStudent,
                'studentPass'    => $passStudent,
                'studentProfile' => $userProfileStudent,

                'history' => $studentHistory,

                'father'         => $userFather,
                'fatherPass'     => $passFather,
                'mother'         => $userMother,
                'motherPass'     => $passMother,
                'guardian'       => $userGuardian,
                'guardianPass'   => $passGuardian
            ];
        }else{
                return new RedirectResponse($this->generateUrl('pgs_core_application_list'));
            }

        }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$application = $this->applicationManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `application` given');
        }

        $application->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_application_list'));
    }


    /**
     * @Template("PGSCoreDomainBundle:Application:list_by_status.html.twig")
     * @param Request $request
     *
     * @return Response
     */
    public function viewApplicationByStatusAction(Request $request)
    {
        $status = $request->get('status', 'All');

        $application = [];
        $application = $this->applicationManager->findByStatus($status);

        return [ 'application' => $application ];
    }

    /**
     * @Template("PGSCoreDomainBundle:Application:picture.html.twig")
     */
    public function pictureAction(Request $request)
    {
        $applicationId = $request->get('applicationId');
        $application   = $this->applicationManager->findOne($applicationId);

        $form = $this->createForm($this->get('pgs.core.form.type.applicationStep3'), $application);

        if ($request->getMethod() == "POST") {
            $form->submit($request);
            if ($form->isValid()) {
                $this->processPicture($form);

                return new RedirectResponse($this->generateUrl('pgs_core_application_home_files',['applicationId' => $applicationId]));
            }
        }
        return [
            'title'       => 'New Application',
            'model'       => 'Application',
            'application' => $application,
            'form'        => $form->createView()
        ];

    }

    /**
     * @param Form $form
     */
    private function processPicture(Form $form)
    {
        /** @var Application $application */

        $application = $form->getData();
        $application->save();

        if (!file_exists('uploads/application/' . $application->getId() . '/' . $application->getPicture())) {
            if (!file_exists('uploads/application/' . $application->getId() )) {
                mkdir('uploads/application/' . $application->getId(), 0777, true);
            }
            rename(
                'uploads/application/temp/' . $application->getPicture(),
                'uploads/application/' . $application->getId() . '/' . $application->getPicture()
            );
        }

        $application->save();


    }


    public function setApplicationStep1Paramater(Request $request){
       return true;
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
                  if (!file_exists('uploads/application/temp')) {
                    mkdir('uploads/application/temp', 0777, true);
                }
                $file_path = '/uploads/application/temp/';
            } else {
                $file_path = '/uploads/application/' . $id . '/';
            }

            $path_parts = pathinfo($_FILES["images"]["name"][0]);
            $name       = $path_parts['filename'] . '_' . time() . '.' . $path_parts['extension'];
            $target     = $_SERVER['DOCUMENT_ROOT'] . $file_path;
            move_uploaded_file($_FILES["images"]["tmp_name"][0], $target . $name);

            $result     = array('path' => $file_path, 'filename' => $name, 'inputId' => 'applicationStep3_picture');
            $result     = json_encode($result);

            return new JsonResponse($result);

        } catch (Exception $e) {
            return new JsonResponse('failed');
        }
    }

    /**
     * @Template("PGSCoreDomainBundle:Application:birthCertificate.html.twig")
     */
    public function birthCertificateAction(Request $request)
    {
        $applicationId = $request->get('applicationId');
        $application   = $this->applicationManager->findOne($applicationId);

        $form = $this->createForm($this->get('pgs.core.form.type.applicationStep4'), $application);

        if ($request->getMethod() == "POST") {
            $form->submit($request);
            if ($form->isValid()) {
                $this->processBirthCertificate($form);

                return new RedirectResponse($this->generateUrl('pgs_core_application_home_files',['applicationId' => $applicationId]));
            }
        }
        return [
            'title'       => 'New Application',
            'model'       => 'Application',
            'application' => $application,
            'form'        => $form->createView()
        ];

    }

    /**
     * @param Form $form
     */
    private function processBirthCertificate(Form $form)
    {
        /** @var Application $application */

        $application = $form->getData();
        $application->save();

        if (!file_exists('uploads/application/' . $application->getId() . '/' . $application->getBirthCertificate())) {
            if (!file_exists('uploads/application/' . $application->getId() )) {
                mkdir('uploads/application/' . $application->getId(), 0777, true);
            }
            rename(
                'uploads/application/temp/' . $application->getBirthCertificate(),
                'uploads/application/' . $application->getId() . '/' . $application->getBirthCertificate()
            );
        }
        $application->save();


    }

    /**
     * @param null $id
     *
     * @return JsonResponse
     */
    public function uploadBirthCertificateAction($id = null)
    {
        try {
            if ($id == null) {
                if (!file_exists('uploads/application/temp')) {
                    mkdir('uploads/application/temp', 0777, true);
                }
                $file_path = '/uploads/application/temp/';
            } else {
                $file_path = '/uploads/application/' . $id . '/';
            }

            $path_parts = pathinfo($_FILES["images"]["name"][0]);
            $name       = $path_parts['filename'] . '_' . time() . '.' . $path_parts['extension'];
            $target     = $_SERVER['DOCUMENT_ROOT'] . $file_path;
            move_uploaded_file($_FILES["images"]["tmp_name"][0], $target . $name);
            $result     = array('path' => $file_path, 'filename' => $name, 'inputId' => 'applicationStep4_birth_certificate');
            $result     = json_encode($result);

            return new JsonResponse($result);

        } catch (Exception $e) {
            return new JsonResponse('failed');
        }
    }

    /**
     * @Template("PGSCoreDomainBundle:Application:familyCard.html.twig")
     */
    public function familyCardAction(Request $request)
    {
        $applicationId = $request->get('applicationId');
        $application   = $this->applicationManager->findOne($applicationId);

        $form = $this->createForm($this->get('pgs.core.form.type.applicationStep5'), $application);

        if ($request->getMethod() == "POST") {
            $form->submit($request);
            if ($form->isValid()) {
                $this->processFamilyCard($form);

                return new RedirectResponse($this->generateUrl('pgs_core_application_home_files',['applicationId' => $applicationId]));
            }
        }
        return [
            'title'       => 'New Application',
            'model'       => 'Application',
            'application' => $application,
            'form'        => $form->createView()
        ];

    }

    /**
     * @param Form $form
     */
    private function processFamilyCard(Form $form)
    {
        /** @var Application $application */

        $application = $form->getData();
        $application->save();

        if (!file_exists('uploads/application/' . $application->getId() . '/' . $application->getFamilyCard())) {
            if (!file_exists('uploads/application/' . $application->getId() )) {
                mkdir('uploads/application/' . $application->getId(), 0777, true);
            }
            rename(
                'uploads/application/temp/' . $application->getFamilyCard(),
                'uploads/application/' . $application->getId() . '/' . $application->getFamilyCard()
            );
        }
        $application->save();


    }

    /**
     * @param null $id
     *
     * @return JsonResponse
     */
    public function uploadFamilyCardAction($id = null)
    {
        try {
            if ($id == null) {
                if (!file_exists('uploads/application/temp')) {
                    mkdir('uploads/application/temp', 0777, true);
                }
                $file_path = '/uploads/application/temp/';
            } else {
                $file_path = '/uploads/application/' . $id . '/';
            }

            $path_parts = pathinfo($_FILES["images"]["name"][0]);
            $name       = $path_parts['filename'] . '_' . time() . '.' . $path_parts['extension'];
            $target     = $_SERVER['DOCUMENT_ROOT'] . $file_path;
            move_uploaded_file($_FILES["images"]["tmp_name"][0], $target . $name);

            $result     = array('path' => $file_path, 'filename' => $name, 'inputId' => 'applicationStep5_family_card');
            $result     = json_encode($result);

            return new JsonResponse($result);

        } catch (Exception $e) {
            return new JsonResponse('failed');
        }
    }

    /**
     * @Template("PGSCoreDomainBundle:Application:graduationCertificate.html.twig")
     */
    public function graduationCertificateAction(Request $request)
    {
        $applicationId = $request->get('applicationId');
        $application   = $this->applicationManager->findOne($applicationId);

        $form = $this->createForm($this->get('pgs.core.form.type.applicationStep6'), $application);

        if ($request->getMethod() == "POST") {
            $form->submit($request);
            if ($form->isValid()) {
                $this->processGraduationCertificate($form);

                return new RedirectResponse($this->generateUrl('pgs_core_application_home_files',['applicationId' => $applicationId]));
            }
        }
        return [
            'title'       => 'New Application',
            'model'       => 'Application',
            'application' => $application,
            'form'        => $form->createView()
        ];

    }

    /**
     * @param Form $form
     */
    private function processGraduationCertificate(Form $form)
    {
        /** @var Application $application */

        $application = $form->getData();
        $application->save();

        if (!file_exists('uploads/application/' . $application->getId() . '/' . $application->getGraduationCertificate())) {
            if (!file_exists('uploads/application/' . $application->getId() )) {
                mkdir('uploads/application/' . $application->getId(), 0777, true);
            }
            rename(
                'uploads/application/temp/' . $application->getGraduationCertificate(),
                'uploads/application/' . $application->getId() . '/' . $application->getGraduationCertificate()
            );
        }
        $application->save();

    }


    /**
     * @param null $id
     *
     * @return JsonResponse
     */
    public function uploadGraduationCertificateAction($id = null)
    {
        try {
            if ($id == null) {
                if (!file_exists('uploads/application/temp')) {
                    mkdir('uploads/application/temp', 0777, true);
                }
                $file_path = '/uploads/application/temp/';
            } else {
                $file_path = '/uploads/application/' . $id . '/';
            }

            $path_parts = pathinfo($_FILES["images"]["name"][0]);
            $name       = $path_parts['filename'] . '_' . time() . '.' . $path_parts['extension'];
            $target     = $_SERVER['DOCUMENT_ROOT'] . $file_path;
            move_uploaded_file($_FILES["images"]["tmp_name"][0], $target . $name);

            $result     = array('path' => $file_path, 'filename' => $name, 'inputId' => 'applicationStep6_graduation_certificate');
            $result     = json_encode($result);

            return new JsonResponse($result);

        } catch (Exception $e) {
            return new JsonResponse('failed');
        }
    }

    /**
     * @Template("PGSCoreDomainBundle:Application:homeFilesUpload.html.twig")
     */
    public function homeFilesAction(Request $request)
    {
        $applicationId = $request->get('applicationId');
        $application   = $this->applicationManager->findOne($applicationId);
        return [
            'applicationId' => $applicationId,
            'model'         => 'Application',
            'application'   => $application
        ];
    }

    /**
     * @Template("PGSCoreDomainBundle:Application:viewHomeFiles.html.twig")
     */
    public function viewHomeFilesAction(Request $request)
    {
        $applicationId = $request->get('applicationId');
        $application   = $this->applicationManager->findOne($applicationId);
        return [
            'applicationId' => $applicationId,
            'model'         => 'Application',
            'application'   => $application
        ];
    }

    protected function setApplicationActivePreference(UserProfile $user, $organization, $academic, $school, $schoolYear){
        $user->setActivePreference("organization_id", $organization)->save();
        $user->setActivePreference("academicYear_id", $academic)->save();
        $user->setActivePreference("school_id", $school)->save();
        $user->setActivePreference("schoolYear_id", $schoolYear)->save();
    }


    public function sendingEmail()
    {
        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
            // Isi dengan Email Gmail anda
            ->setUsername('gizmoaja13@gmail.com')
            // Isi dengan Password Gmail anda
            ->setPassword('20073o1o');
        $mailer = \Swift_Mailer::newInstance($transport);
        $content = "Terima kasih";
        $message = \Swift_Message::newInstance('PGS Gizmo')
            ->setSubject('Hello Email')
            ->setFrom(array('gizmo@gmail.com' => 'PGS Gizmo'))
            ->setTo(array('tryhusky@gmail.com' => 'student'))
//            ->setBody(
//                $this->renderView('PGSCoreDomainBundle:Application:confirmation.mail.txt.twig',['applications' => $application] ),'text/html'
//            )
            ->setBody($content, 'text/html');
        /*
         * If you also want to include a plaintext version of the message
        ->addPart(
            $this->renderView(
                'Emails/registration.txt.twig',
                array('name' => $name)
            ),
            'text/plain'
        )
        */
        ;
        $this->get('mailer')->send($message);

    }
}
