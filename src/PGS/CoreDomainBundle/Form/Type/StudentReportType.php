<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Form\Type;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Propel\PropelBundle\Form\BaseAbstractType;
use PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudentQuery;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClassQuery;
use PGS\CoreDomainBundle\Model\Student\StudentQuery;
use PGS\CoreDomainBundle\Model\StudentReport\StudentReport;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Service("pgs.core.form.type.studentReport")
 * @Tag("form.type", attributes = {"alias" = "studentReport"})
 */
class StudentReportType extends BaseAbstractType
{
    protected $options = array(
        'data_class'         => 'PGS\CoreDomainBundle\Model\StudentReport\StudentReport',
        'name'               => 'studentReport',
        'translation_domain' => 'forms'
    );

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var SchoolClassStudentQuery
     */
    private $schoolClassStudentQuery;

    /**
     * @var StudentQuery
     */
    private $studentQuery;

    /**
     * @var SchoolClassQuery
     */
    private $schoolClassQuery;

    /**
     * @InjectParams({
     *      "schoolClassStudentQuery"   = @Inject("pgs.core.query.school_class_student"),
     *      "schoolClassQuery"          = @Inject("pgs.core.query.school_class"),
     *      "studentQuery"              = @Inject("pgs.core.query.student"),
     *      "securityContext"           = @Inject("security.context"),
     * })
     */
    public function __construct(
        SchoolClassStudentQuery $schoolClassStudentQuery,
        SchoolClassQuery $schoolClassQuery,
        StudentQuery $studentQuery,
        SecurityContext $securityContext
    ) {
        $this->schoolClassStudentQuery  = $schoolClassStudentQuery;
        $this->schoolClassQuery         = $schoolClassQuery;
        $this->studentQuery             = $studentQuery;
        $this->securityContext          = $securityContext;

    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add(
            'schoolClass',
            'model',
            [
                'label'       => 'form.school.class',
                'class'       => 'PGS\CoreDomainBundle\Model\SchoolClass\SchoolClass',
                'empty_value' => 'form.school.class.select',
                'query'       => $this->schoolClassQuery,
                'constraints' => [ new NotBlank() ],
                'required'    => false
            ]
        );

        $builder->add(
            'schoolClassStudent',
            'model',
            [
                'label'       => 'form.student',
                'class'       => 'PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudent',
                'empty_value' => 'form.student.select',
                'query'       => $this->schoolClassStudentQuery,
                'constraints' => [ new NotBlank() ],
                'required'    => false
            ]
        );

//        $builder->add(
//            'term1',
//            'number',
//            [
//                'precision'=> 2,
//                'label'    => 'form.report.term',
//                'required' => 'required',
//                'attr'     => [ 'style' => 'width: 200px' ]
//            ]
//        );
//
//         $builder->add(
//            'term2',
//            'number',
//            [
//                'precision'=> 2,
//                'label'    => 'form.report.term',
//                'required' => 'required',
//                'attr'     => [ 'style' => 'width: 200px' ]
//            ]
//        );
//
//        $builder->add(
//            'term3',
//            'number',
//            [
//                'precision'=> 2,
//                'label'    => 'form.report.term',
//                'required' => 'required',
//                'attr'     => [ 'style' => 'width: 200px' ]
//            ]
//        );
//
//        $builder->add(
//            'term4',
//            'number',
//            [
//                'precision'=> 2,
//                'label'    => 'form.report.term',
//                'required' => 'required',
//                'attr'     => [ 'style' => 'width: 200px' ]
//            ]
//        );
//
//        $builder->add(
//            'mid_report',
//            'number',
//            [
//                'precision'=> 2,
//                'label'    => 'form.report.mid.report',
//                'required' => 'required',
//                'attr'     => [ 'style' => 'width: 200px' ]
//            ]
//        );
//
//        $builder->add(
//            'final_report',
//            'number',
//            [
//                'precision'=> 2,
//                'label'    => 'form.score.final.report',
//                'required' => 'required',
//                'attr'     => [ 'style' => 'width: 200px' ]
//            ]
//        );

//        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
//                /** @var StudentReport $studentReport*/
//                $studentReport = $event->getData();
//                $form         = $event->getForm();
//
//                $query = $this->schoolClassStudentQuery->getNoSchoolClassStudentChoice();
//                if ($studentReport->getSchoolClass()) {
//                    $query = $this->schoolClassStudentQuery->getSchoolClassStudentsBySchoolClassChoices($studentReport->getSchoolClass());
//                }
//
//                $form->add(
//                    'schoolClassStudent',
//                    'model',
//                    [
//                        'label'       => 'form.school.class.student',
//                        'class'       => 'PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudent',
//                        'query'       => $query,
//
//                    ]
//                );
//            }
//        );
//
//        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
//                /** @var StudentReport $studentReport*/
//                $studentReport = $event->getData();
//                $form         = $event->getForm();
//
//                $query = $this->schoolClassStudentQuery->getNoSchoolClassStudentChoice();
//
//                if ($schoolClass = $this->schoolClassQuery->findOneById($studentReport['school_class_student'])) {
//                    $query = $this->schoolClassStudentQuery->getSchoolClassStudentsBySchoolClassChoices($schoolClass);
//                }
//
//                $form->add(
//                    'schoolClassStudent',
//                    'model',
//                    [
//                        'label'       => 'form.school.class.student',
//                        'class'       => 'PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudent',
//                        'empty_value' => 'form.school.class.student.select',
//                        'query'       => $query,
//                        'constraints' => [ new NotBlank() ],
//                        'required'    => false
//                    ]
//                );
//            }
//        );

    }
}
