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
use PGS\CoreDomainBundle\Manager\StudentMedicalManager;
use PGS\CoreDomainBundle\Manager\SchoolHealthManager;
use PGS\CoreDomainBundle\Manager\MedicalManager;
use PGS\CoreDomainBundle\Model\StudentMedical\StudentMedical;
use PGS\CoreDomainBundle\Model\Medical\Medical;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Intl\Intl;

class StudentMedicalController extends AbstractCoreBaseController
{
    /**
     * @var StudentMedicalManager
     *
     * @Inject("pgs.core.manager.student_medical")
     */
    protected $studentMedicalManager;

    /**
     * @var MedicalManager
     *
     * @Inject("pgs.core.manager.medical")
     */
    protected $medicalManager;

    /**
     * @var SchoolHealthManager
     *
     * @Inject("pgs.core.manager.school_health")
     */
    protected $schoolHealthManager;

    /**
     * @Template("PGSCoreDomainBundle:StudentMedical:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $schoolHealth = $request->get('schoolHealthId');
        $medical      = $this->medicalManager->findAll();

        return [
            'model'         => 'StudentMedical',
            'medicals'      => $medical,
            'schoolHealth'  => $schoolHealth
        ];

    }

    /**
     *
     * @Template("PGSCoreDomainBundle:StudentMedical:list_by_school_health.html.twig")
     */
    public function viewAction(Request $request)
    {
        $schoolHealth = $request->get('schoolHealthId');

        if (!$studentMedical = $this->studentMedicalManager->findAllBySchoolHealth($schoolHealth)) {
            throw $this->createNotFoundException('Invalid `student medical` given');
        }

        return [
            'model'            => 'Student Medical',
            'studentMedical'   => $studentMedical,
            'schoolHealth'     => $schoolHealth

        ];
    }

//    /**
//     * @Template("PGSCoreDomainBundle:StudentMedical:new.html.twig")
//     */
//    public function newAction(Request $request)
//    {
//
//        $schoolHealthId = $request->get('schoolHealthId');
//        $studentMedical = new StudentMedical();
//        $studentMedical->setSchoolHealthId($schoolHealthId);
//
//        $form = $this->createForm($this->get('pgs.core.form.type.applicationStep3'), $studentMedical);
////        echo "hello";die;
//
//        if ($request->getMethod() == "POST") {
//            $form->submit($request);
//
//            if ($form->isValid()) {
//                $this->processForm($form);
//
//                return new RedirectResponse($this->generateUrl('pgs_core_student_condition_new',[ 'schoolHealthId' => $schoolHealthId]));
//            }
//        }
//
//        return [
//            'model'            => 'Student Medical',
//            'studentMedical'   => $studentMedical,
//            'form'             => $form->createView()
//        ];
//    }
//
//    /**
//     *
//     * @Template("PGSCoreDomainBundle:StudentMedical:form.html.twig")
//     */
//    public function editAction(Request $request)
//    {
//        $id = $request->get('id');
//
//        if (!$studentMedical= $this->studentMedicalManager->findOne($id)) {
//            throw $this->createNotFoundException('Invalid `student medical` given');
//        }
//
//        $form = $this->createForm($this->get('pgs.core.form.type.applicationStep3'), $studentMedical);
//
//        if ($request->getMethod() == "POST") {
//            $form->submit($request);
//
//            if ($form->isValid()) {
//                $this->processForm($form);
//
//                return new RedirectResponse($this->generateUrl('pgs_core_student_medical_list'));
//            }
//        }
//
//        return [
//            'model'            => 'Student Medical',
//            'studentMedical' => $studentMedical,
//            'form'             => $form->createView()
//        ];
//    }
//
//    /**
//     * @PreAuthorize("hasRole('ROLE_ADMIN')")
//     */
//    public function deleteAction(Request $request)
//    {
//        $id = $request->get('id');
//
//        if (!$studentMedical = $this->studentMedicalManager->findOne($id)) {
//            throw $this->createNotFoundException('Invalid `student medical` given');
//        }
//
//        $studentMedical->delete();
//
//        return new RedirectResponse($this->generateUrl('pgs_core_student_medical_list'));
//    }
//
//    /**
//     * @param Form $form
//     */
//    public function processForm(Form $form)
//    {
//        /** @var StudentMedical $studentMedical*/
//        $studentMedical = $form->getData();
//        $studentMedical->save();
//    }

    public function fetchRecordAction(Request $request)
    {
        $schoolHealth = $request->get('schoolHealthId');


        if ($request->getMethod() == "POST") {
            $data      = $request->get('data');
            $medicalId = $data['medical_id'];

            $this->toggleMedical($schoolHealth, $medicalId);
        }

        $output = [
            "data"            => [],
            "options"         => [],
            "recordsTotal"    => "0",
            "recordsFiltered" => "0"
        ];

        if ($schoolHealth    = $this->schoolHealthManager->findOne($schoolHealth)) {
            $medicals        = $this->medicalManager->findAll();
            $studentMedicals = $this->studentMedicalManager->findAllBySchoolHealth($schoolHealth);

            $total = $this->medicalManager->findAll()->count();
            $data  = [];
            $i     = 0;

            /** @var Medical $medical */
            foreach ($medicals as $medical) {
                $i++;
                $medicalFound = 0;

                /** @var StudentMedical $studentMedical */
                foreach ($studentMedicals as $studentMedical) {
                    if ($medical->getId() == $studentMedical->getMedicalId()) {
                        $medicalFound = 1;
                    }
                }

                $data[] = [
                    "DT_RowId"         => "row_" . $i,
                    "school_health_id" => $schoolHealth,
                    "medical_id"       => $medical->getId(),
                    "available"        => $medicalFound,
                    "medical"          => $medical->getCurrentTranslation()->getName()
                ];
            }
            $output = [
                "data"            => $data,
                "recordsTotal"    => $total,
                "recordsFiltered" => $total
            ];
        }

        return $this->jsonResponse($output);
    }

    /**
     * @param $schoolHealth
     * @param $medical
     */
    protected function toggleMedical($schoolHealth, $medical)
    {
        if ($studentMedical = $this->studentMedicalManager->findOne($schoolHealth,$medical )) {
            $studentMedical->delete();
        } else {
            $studentMedical = new StudentMedical();
            $studentMedical->setSchoolHealthId($schoolHealth)->setMedicalId($medical)->save();
        }

        return;
    }

}
