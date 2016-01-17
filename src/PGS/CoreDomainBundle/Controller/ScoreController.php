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
use PGS\CoreDomainBundle\Manager\ScoreManager;
use PGS\CoreDomainBundle\Manager\SchoolClassManager;
use PGS\CoreDomainBundle\Manager\StudentManager;
use PGS\CoreDomainBundle\Manager\SchoolClassStudentManager;
use PGS\CoreDomainBundle\Manager\SchoolClassCourseManager;
use PGS\CoreDomainBundle\Model\Score\Score;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourse;
use PGS\CoreDomainBundle\Controller\AbstractBaseController as CoreAbstractBaseController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Intl\Intl;

class ScoreController extends AbstractCoreBaseController
{
    /**
     * @var ScoreManager
     *
     * @Inject("pgs.core.manager.score")
     */
    protected $scoreManager;

    /**
     * @var StudentManager
     *
     * @Inject("pgs.core.manager.student")
     */
    protected $studentManager;

    /**
     * @var SchoolClassManager
     *
     * @Inject("pgs.core.manager.school_class")
     */
    protected $schoolClassManager;

    /**
     * @var SchoolClassStudentManager
     *
     * @Inject("pgs.core.manager.school_class_student")
     */
    protected $schoolClassStudentManager;

    /**
     * @var SchoolClassCourseManager
     *
     * @Inject("pgs.core.manager.school_class_course")
     */
    protected $schoolClassCourseManager;

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:Score:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $scores = $this->scoreManager->findAll();

        return [
            'title'   => 'Score',
            'model'   => 'Score',
            'scores' => $scores
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:Score:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $score = new Score();
        $schoolClassStudentId = $request->get('schoolClassStudent');
        $schoolClassCourseId  = $request->get('schoolClassCourse');
        $studentId  = $request->get('student');

        if (!$score = $this->scoreManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_score_list'));
        }

        $score->setSchoolClassCourseId($schoolClassCourseId);
        $score->setSchoolClassStudentId($schoolClassStudentId);
        $score->setStudentId($studentId);
        $form = $this->createForm($this->get('pgs.core.form.type.score'), $score);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_score_list_by_class', ['schoolClassCourseId' => $schoolClassCourseId ] ));
            }
        }

        return [
            'title'  => 'New Score',
            'model'  => 'Score',
            'score' => $score,
            'course' => $this->schoolClassCourseManager->findOne($schoolClassCourseId)->getName(),
            'student' => $this->schoolClassStudentManager->findOne($schoolClassStudentId)->getStudent()->getFirstName(),
            'form'   => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:Score:list_by_student.html.twig")
     */
    public function listByClassAction(Request $request)
    {


        $schoolClassCourseId = $request->get('schoolClassCourseId');
        $schoolClassCourse = $this->schoolClassCourseManager->findOne($schoolClassCourseId);
        $schoolClassId = $this->schoolClassCourseManager->findOne($schoolClassCourseId)->getSchoolClassId();
        $schoolClassStudents= $this->schoolClassStudentManager->findAllBySchoolClass($schoolClassId);

        return [
            'title'  => 'New Score',
            'model'  => 'Student list',
            'schoolClassStudents' => $schoolClassStudents,
            'schoolClassCourse' => $schoolClassCourse,
//            'form'   => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:Score:select_class.html.twig")
     */
    public function selectByClassAction(Request $request)
    {

//        $schoolClass   = $this->schoolClassManager->findAllBySchool()->find();
        $schoolClassCourse = $this->schoolClassCourseManager->findAllBySchool()->find();

        if ($request->getMethod() == "POST") {

            $schoolClassCourseId=$_POST['selectedCourse'];
//            return new RedirectResponse($this->generateUrl('pgs_core_score_list_by_class', ['schoolClassId' => $schoolClassId] ));
            return new RedirectResponse($this->generateUrl('pgs_core_score_list_by_class', ['schoolClassCourseId' => $schoolClassCourseId] ));

        }

        return [

            'title'  => 'New Score',
            'model'  => 'Score',
//            'schoolClasses' => $schoolClass,
            'schoolCourses' => $schoolClassCourse,
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:Score:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$score = $this->scoreManager->canEdit($id)) {
            throw $this->createNotFoundException('Invalid `score` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.score'), $score);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_score_list'));
            }
        }

        return [
            'title'  => 'Edit Score',
            'model'  => 'Score',
            'Score' => $score,
            'form'   => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$score = $this->scoreManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `score` given');
        }

        return new RedirectResponse($this->generateUrl('pgs_core_score_list'));
    }

    /**
     * @param Form $form
     */
    private function processForm(Form $form)
    {
        /** @var Score $score*/
        $score = $form->getData();

        $score->save();
    }


}
