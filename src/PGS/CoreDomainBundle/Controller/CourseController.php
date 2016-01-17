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
use PGS\CoreDomainBundle\Manager\CourseManager;
use PGS\CoreDomainBundle\Model\Course\Course;
use PGS\CoreDomainBundle\Controller\AbstractBaseController as CoreAbstractBaseController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Intl\Intl;

class CourseController extends AbstractCoreBaseController
{
    /**
     * @var CourseManager
     *
     * @Inject("pgs.core.manager.course")
     */
    protected $courseManager;

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:Course:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $courses = $this->courseManager->findAll();

        return [
            'title'   => 'courses',
            'model'   => 'Courses',
            'courses' => $courses
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:Course:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $course = new Course();

        if (!$course = $this->courseManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_course_list'));
        }

        $form = $this->createForm($this->get('pgs.core.form.type.course'), $course);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_course_list'));
            }
        }

        return [
            'title'  => 'New Course',
            'model'  => 'Course',
            'course' => $course,
            'form'   => $form->createView()
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

        if (!$course = $this->courseManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `course` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.course'), $course);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_course_list'));
            }
        }

        return [
            'title'  => 'Edit Course',
            'model'  => 'Course',
            'course' => $course,
            'form'   => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$course = $this->courseManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `course` given');
        }

        return new RedirectResponse($this->generateUrl('pgs_core_course_list'));
    }

    /**
     * @param Form $form
     */
    private function processForm(Form $form)
    {
        /** @var Course $course*/
        $course = $form->getData();

        $course->save();
    }
}
