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
use PGS\CoreDomainBundle\Container\ActivePreferenceContainer;
use PGS\CoreDomainBundle\Model\SchoolTerm\SchoolTermQuery;
use PGS\CoreDomainBundle\Model\SchoolGradeLevel\SchoolGradeLevelQuery;
use PGS\CoreDomainBundle\Model\Course\CourseQuery;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClassQuery;
use PGS\CoreDomainBundle\Model\Formula\FormulaQuery;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Service("pgs.core.form.type.schoolClassCourse")
 * @Tag("form.type", attributes = {"alias" = "schoolClassCourse"})
 */
class SchoolClassCourseType extends BaseAbstractType
{
    protected $options = array(
        'data_class'         => 'PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourse',
        'name'               => 'schoolClassCourse',
        'translation_domain' => 'forms'
    );

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var SchoolTermQuery
     */
    private $schoolTermQuery;

    /**
     * @var SchoolGradeLevelQuery
     */
    private $schoolGradeLevelQuery;

    /**
     * @var CourseQuery
     */
    private $courseQuery;

    /**
     * @var FormulaQuery
     */
    private $formulaQuery;

    /**
     * @var SchoolClassQuery
     */
    private $schoolClassQuery;

    /**
     * @var ActivePreferenceContainer
     */
    protected $activePreference;

    /**
     * @InjectParams({
     *      "securityContext"       = @Inject("security.context"),
     *      "activePreference"      = @Inject("pgs.core.container.active_preference"),
     *      "schoolClassQuery"       = @Inject("pgs.core.query.school_class"),
     *      "schoolTermQuery"       = @Inject("pgs.core.query.school_term"),
     *      "schoolGradeLevelQuery" = @Inject("pgs.core.query.school_grade_level"),
     *      "courseQuery"           = @Inject("pgs.core.query.course"),
     *      "formulaQuery"          = @Inject("pgs.core.query.formula"),
     * })
     */
    public function __construct(
        SecurityContext $securityContext,
        ActivePreferenceContainer $activePreference,
        SchoolClassQuery $schoolClassQuery,
        SchoolTermQuery $schoolTermQuery,
        SchoolGradeLevelQuery $schoolGradeLevelQuery,
        CourseQuery $courseQuery,
        FormulaQuery $formulaQuery
    ){
        $this->securityContext       = $securityContext;
        $this->activePreference      = $activePreference;
        $this->schoolClassQuery      = $schoolClassQuery;
        $this->schoolTermQuery       = $schoolTermQuery;
        $this->schoolGradeLevelQuery = $schoolGradeLevelQuery;
        $this->courseQuery           = $courseQuery;
        $this->formulaQuery          = $formulaQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            'text',
            [ 'label'       => 'form.name' ],
            [ 'constraints' => [ new NotBlank()] ]
        );

        $builder->add(
            'school_class',
            'model',
            [
                'label'       => 'form.school.class',
                'class'       => 'PGS\CoreDomainBundle\Model\SchoolClass\SchoolClass',
                'empty_value' => 'form.school.class.select',
                'query'       => $this->schoolClassQuery->filterBySchool($this->activePreference->getSchoolPreference()),
                'constraints' => [ new NotBlank() ],
                'required'    => false
            ]
        );

        $builder->add(
            'startTime',
            'time',
            [
                'input'  => 'datetime',
//                'widget' => 'choice',
                'label'  => 'form.time.start',
                'widget' => 'single_text',
//                'format' => 'dd-MM-yyyy',
                'attr'   => [ 'style' => 'width:150px' ]
            ]
        );

        $builder->add(
            'endTime',
            'time',
            [
                'input'  => 'datetime',
//                'widget' => 'choice',
                'label'  => 'form.time.end',
                'widget' => 'single_text',
//                'format' => 'dd-MM-yyyy',
                'attr'   => [ 'style' => 'width:150px' ]
            ]
        );

        $builder->add(
            'course',
            'model',
            [
                'label'       => 'form.course',
                'class'       => 'PGS\CoreDomainBundle\Model\Course\Course',
                'empty_value' => 'form.course.select',
                'query'       => $this->courseQuery,
                'constraints' => [ new NotBlank() ],
                'required'    => false
            ]
        );

        $builder->add(
            'school_term',
            'model',
            [
                'label'       => 'form.school.term',
                'class'       => 'PGS\CoreDomainBundle\Model\SchoolTerm\SchoolTerm',
                'empty_value' => 'form.school.term.select',
                'query'       => $this->schoolTermQuery->filterBySchool($this->activePreference->getSchoolPreference()),
                'constraints' => [ new NotBlank() ],
                'required'    => false
            ]
        );

        $builder->add(
            'school_grade_level',
            'model',
            [
                'label'       => 'form.school.grade.level',
                'class'       => 'PGS\CoreDomainBundle\Model\SchoolGradeLevel\SchoolGradeLevel',
                'empty_value' => 'form.school.grade.level.select',
                'query'       => $this->schoolGradeLevelQuery->filterBySchool($this->activePreference->getSchoolPreference()),
                'constraints' => [ new NotBlank() ],
                'required'    => false
            ]
        );

        $builder->add(
            'formula',
            'model',
            [
                'label'       => 'form.formula',
                'class'       => 'PGS\CoreDomainBundle\Model\Formula\Formula',
                'empty_value' => 'form.formula.select',
                'query'       => $this->formulaQuery,
                'constraints' => [ new NotBlank() ]
            ]
        );



//        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
//            /** @var SchoolClass $schoolClass*/
//            $schoolClass = $event->getData();
//            $form         = $event->getForm();
//
//            $querySchoolYear = $this->schoolYearQuery->getNoSchoolYearChoice();
//            if ($schoolClass->getCourse()) {
//                $querySchoolYear = $this->schoolYearQuery->getSchoolYearByCourse($schoolClass->getCourse());
//            }
//
//            $form->add(
//                'school_year',
//                'model',
//                [
//                    'label'       => 'form.school.year',
//                    'class'       => 'PGS\CoreDomainBundle\Model\SchoolYear\SchoolYear',
//                    'query'       => $querySchoolYear
//                ]
//            );
//        });
//
//        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
//            /** @var SchoolClass $schoolClass*/
//            $schoolClass = $event->getData();
//            $form         = $event->getForm();
//
//            $querySchoolYear = $this->schoolYearQuery->getNoSchoolYearChoice();
//            if ($course = $this->courseQuery->findOneById($schoolClass['school'])) {
//                $querySchoolYear = $this->schoolYearQuery->getSchoolYearBySchoolChoices($course);
//            }
//
//            $form->add(
//                'school_year',
//                'model',
//                [
//                    'label'       => 'form.school.year',
//                    'class'       => 'PGS\CoreDomainBundle\Model\SchoolYear\SchoolYear',
//                    'empty_value' => 'form.school.year.select',
//                    'query'       => $querySchoolYear,
//                    'constraints' => [ new NotBlank() ]
//                ]
//            );
//        });
    }
}
