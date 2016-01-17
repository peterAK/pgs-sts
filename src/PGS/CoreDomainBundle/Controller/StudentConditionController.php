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
use PGS\CoreDomainBundle\Manager\StudentConditionManager;
use PGS\CoreDomainBundle\Manager\SchoolHealthManager;
use PGS\CoreDomainBundle\Manager\ConditionManager;
use PGS\CoreDomainBundle\Model\StudentCondition\StudentCondition;
use PGS\CoreDomainBundle\Model\Condition\Condition;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealth;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Intl\Intl;

class StudentConditionController extends AbstractCoreBaseController
{
    /**
     * @var StudentConditionManager
     *
     * @Inject("pgs.core.manager.student_condition")
     */
    protected $studentConditionManager;

    /**
     * @var ConditionManager
     *
     * @Inject("pgs.core.manager.condition")
     */
    protected $conditionManager;

    /**
     * @var SchoolHealthManager
     *
     * @Inject("pgs.core.manager.school_health")
     */
    protected $schoolHealthManager;

    /**
     * @Template("PGSCoreDomainBundle:StudentCondition:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $schoolHealth  = $request->get('schoolHealthId');
        $condition     = $this->studentConditionManager->findAll();
        $applicationId = $this->schoolHealthManager->findOne($schoolHealth)->getApplicationId();

        if ($request->getMethod() == "POST") {
            if(trim($request->get('condition_exp')) != ''){
                $conditionExp = $request->get('condition_exp');
                $health       = $this->schoolHealthManager->findOne($schoolHealth);
                $health->setConditionExp($conditionExp)->save();

                return new RedirectResponse($this->generateUrl('pgs_core_application_home_files',['applicationId' => $applicationId]));

            }
            else{
                return new RedirectResponse($this->generateUrl('pgs_core_application_home_files',['applicationId' => $applicationId]));
            }
        }

        return [
            'model'         => 'StudentCondition',
            'conditions'    => $condition,
            'schoolHealth'  => $schoolHealth,
            'applicationId' => $applicationId,

        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:StudentCondition:list_by_school_health.html.twig")
     */
    public function viewAction(Request $request)
    {
        $schoolHealth  = $request->get('schoolHealthId');
        $applicationId = $this->schoolHealthManager->findOne($schoolHealth)->getApplicationId();
        $condition        = $this->schoolHealthManager->findOne($schoolHealth)->getConditionExp();

        if (!$studentCondition = $this->studentConditionManager->findAllBySchoolHealth($schoolHealth)) {
            throw $this->createNotFoundException('Invalid `student condition` given');
        }

        if ($request->getMethod() == "POST") {

            if(trim($request->get('condition_exp')) != $condition){
                $conditionExp = $request->get('condition_exp');
                $health       = $this->schoolHealthManager->findOne($schoolHealth);
                $health->setConditionExp($conditionExp)->save();

                return new RedirectResponse($this->generateUrl('pgs_core_application_view_home_files',['applicationId' => $applicationId]));

            }
            else{
                return new RedirectResponse($this->generateUrl('pgs_core_application_view_home_files',['applicationId' => $applicationId]));
            }
        }

        return [
            'model'            => 'Student Condition',
            'studentCondition' => $studentCondition,
            'schoolHealth'     => $schoolHealth,
            'applicationId'    => $applicationId,
            'condition'        => $condition,

        ];
    }
//
//    /**
//     * @Template("PGSCoreDomainBundle:StudentCondition:new.html.twig")
//     */
//    public function newAction(Request $request)
//    {
//        $schoolHealthId = $request->get('schoolHealthId');
//        $studentCondition = new StudentCondition();
//        $studentCondition->setSchoolHealthId($schoolHealthId);
//
//
//        $form = $this->createForm($this->get('pgs.core.form.type.applicationStep4'), $studentCondition);
//
//        if ($request->getMethod() == "POST") {
//            $form->submit($request);
//            echo "condition";die;
//            if ($form->isValid()) {
//                $applicationId=$this->processForm($form);
//
//                return new RedirectResponse($this->generateUrl('pgs_core_application_picture',['applicationId' => $applicationId]));
//            }
//        }
//
//        return [
//            'model'            => 'Student Condition',
//            'studentCondition' => $studentCondition,
//            'form'             => $form->createView()
//        ];
//    }
//
//    /**
//     *
//     *
//     * @Template("PGSCoreDomainBundle:StudentCondition:form.html.twig")
//     */
//    public function editAction(Request $request)
//    {
//        $id = $request->get('id');
//
//        if (!$studentCondition= $this->studentConditionManager->findOne($id)) {
//            throw $this->createNotFoundException('Invalid `student condition` given');
//        }
//
//        $form = $this->createForm($this->get('pgs.core.form.type.applicationStep4'), $studentCondition);
//
//        if ($request->getMethod() == "POST") {
//            $form->submit($request);
//
//            if ($form->isValid()) {
//                $this->processForm($form);
//
//                return new RedirectResponse($this->generateUrl('pgs_core_student_condition_list'));            }
//        }
//
//        return [
//            'model'            => 'Student Condition',
//            'studentCondition' => $studentCondition,
//            'form'             => $form->createView()
//        ];
//    }
//
//    /**
//     *
//     */
//    public function deleteAction(Request $request)
//    {
//        $id = $request->get('id');
//
//        if (!$studentCondition = $this->studentConditionManager->findOne($id)) {
//            throw $this->createNotFoundException('Invalid `student condition` given');
//        }
//
//        $studentCondition->delete();
//
//        return new RedirectResponse($this->generateUrl('pgs_core_student_condition_list'));
//    }
//
//    /**
//     * @param Form $form
//     * @return int
//     */
//    public function processForm(Form $form)
//    {
//        /** @var StudentCondition $studentCondition*/
//        $studentCondition = $form->getData();
//        $studentCondition->save();
//        return $studentCondition->getSchoolHealth()->getApplicationId();
//    }

    public function fetchRecordAction(Request $request)
    {
        $schoolHealth = $request->get('schoolHealthId');


        if ($request->getMethod() == "POST") {
            $data        = $request->get('data');
            $conditionId = $data['condition_id'];

            $this->toggleCondition($schoolHealth, $conditionId);
        }

        $output = [
            "data"            => [],
            "options"         => [],
            "recordsTotal"    => "0",
            "recordsFiltered" => "0"
        ];

        if ($schoolHealth       = $this->schoolHealthManager->findOne($schoolHealth)) {
            $conditions         = $this->conditionManager->findAll();
            $studentConditions  = $this->studentConditionManager->findAllBySchoolHealth($schoolHealth);

            $total = $this->conditionManager->findAll()->count();
            $data  = [];
            $i     = 0;

            /** @var Condition $condition */
            foreach ($conditions as $condition) {
                $i++;
                $conditionFound = 0;

                /** @var StudentCondition $studentCondition */
                foreach ($studentConditions as $studentCondition) {
                    if ($condition->getId() == $studentCondition->getConditionId()) {
                        $conditionFound = 1;
                    }
                }

                $data[] = [
                    "DT_RowId"         => "row_" . $i,
                    "school_health_id" => $schoolHealth,
                    "condition_id"     => $condition->getId(),
                    "available"        => $conditionFound,
                    "condition"        => $condition->getCurrentTranslation()->getName()
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
     * @param $condition
     */
    protected function toggleCondition($schoolHealth, $condition)
    {
        if ($studentCondition = $this->studentConditionManager->findOne($schoolHealth,$condition )) {
            $studentCondition->delete();
        } else {
            $studentCondition = new StudentCondition();
            $studentCondition->setSchoolHealthId($schoolHealth)->setConditionId($condition)->save();
        }

        return;
    }
}
