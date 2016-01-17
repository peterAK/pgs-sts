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
use PGS\CoreDomainBundle\Model\Application\Application;
use PGS\CoreDomainBundle\Model\Ethnicity\EthnicityQuery;
use PGS\CoreDomainBundle\Model\CountryQuery;
use PGS\CoreDomainBundle\Model\Grade\GradeQuery;
use PGS\CoreDomainBundle\Model\Level\LevelQuery;
use PGS\CoreDomainBundle\Model\StateQuery;
use PGS\CoreDomainBundle\Model\School\SchoolQuery;
use PGS\CoreDomainBundle\Model\SchoolYear\SchoolYearQuery;
use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraints\NotBlank;
/**
 * @Service("pgs.core.form.type.applicationStep1")
 * @Tag("form.type", attributes = {"alias" = "applicationStep1"})
 */
class ApplicationStep1Type extends BaseAbstractType
{
    protected $options = array(
        'data_class'         => 'PGS\CoreDomainBundle\Model\Application\Application',
        'name'               => 'applicationStep1',
        'translation_domain' => 'forms'
    );

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var EthnicityQuery
     */
    private $ethnicityQuery;

    /**
     * @var CountryQuery
     */
    private $countryQuery;

    /**
     * @var GradeQuery
     */
    private $gradeQuery;

    /**
     * @var LevelQuery
     */
    private $levelQuery;

    /**
     * @var StateQuery
     */
    private $stateQuery;

    /**
     * @var SchoolQuery
     */
    private $schoolQuery;

    /**
     * @var SchoolYearQuery
     */
    private $schoolYearQuery;

    /**
     * @InjectParams({
     *      "ethnicityQuery"   = @Inject("pgs.core.query.ethnicity"),
     *      "countryQuery"     = @Inject("pgs.core.query.country"),
     *      "gradeQuery"       = @Inject("pgs.core.query.grade"),
     *      "levelQuery"       = @Inject("pgs.core.query.level"),
     *      "stateQuery"       = @Inject("pgs.core.query.state"),
     *      "schoolQuery"      = @Inject("pgs.core.query.school"),
     *      "schoolYearQuery"  = @Inject("pgs.core.query.school_year"),
     *      "securityContext"  = @Inject("security.context"),
     * })
     */
    public function __construct(
        EthnicityQuery $ethnicityQuery,
        CountryQuery $countryQuery,
        GradeQuery $gradeQuery,
        LevelQuery $levelQuery,
        StateQuery $stateQuery,
        SchoolQuery $schoolQuery,
        SchoolYearQuery $schoolYearQuery,
        SecurityContext $securityContext
    ) {
        $this->ethnictityQuery  = $ethnicityQuery;
        $this->countryQuery     = $countryQuery;
        $this->gradeQuery       = $gradeQuery;
        $this->levelQuery       = $levelQuery;
        $this->stateQuery       = $stateQuery;
        $this->schoolQuery      = $schoolQuery;
        $this->schoolYearQuery  = $schoolYearQuery;
        $this->securityContext  = $securityContext;

    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->securityContext->isGranted('ROLE_ADMIN') ||
            $this->securityContext->isGranted('ROLE_PRINCIPAL') ||
            $this->securityContext->isGranted('ROLE_SCHOOL')
        ) {
            $builder->add(
                'status',
                null,
                [
                    'empty_value' => 'form.status.select',
                    'label'       => 'form.status'
                ]
            );
        } else {
            $builder->add(
                'status',
                'hidden',
                [
                    'label'     => 'form.status',
                    'read_only' => true
                ]
            );
        }

        $builder->add(
            'form_no',
            'text',
            [
                'label' => 'form.application1.no'
            ]
        );

        $builder->add(
            'student_nation_no',
            'hidden',
            [
                'label'    => 'form.student.nation.no',
                'required' => false
            ]
        );

        $builder->add(
            'prior_test_no',
            'hidden',
            [
                'label'    => 'form.student.prior.test.no',
                'required' => false
            ]
        );

        $builder->add(
            'student_no',
            'hidden',
            [
                'label'    => 'form.student.no',
                'required' => false
            ]
        );

        $builder->add(
            'first_name',
            'text',
            [
                'label'    => 'form.name.first',
                'required' => true
            ]
        );

        $builder->add(
            'last_name',
            'text',
            [
                'label'    => 'form.name.last',
                'required' => false
            ]
        );

        $builder->add(
            'nick_name',
            'text',
            [
                'label'    => 'form.name.nick',
                'required' => false
            ]
        );

        $builder->add(
            'phone_student',
            'text',
            [
                'label'    => 'form.phone',
                'required' => false
            ]
        );

        $builder->add(
            'gender',
             null,
            [
                'empty_value' => 'form.gender.select',
                'label'       => 'form.gender'
            ]
        );

        $builder->add(
            'place_of_birth',
            'text',
            [
                'label' => 'form.place.of.birth'
            ]
        );

        $builder->add(
            'date_of_birth',
            'date',
            [
                'label'  => 'form.date.of.birth',
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr'   => [ 'style' => 'width:150px' ]
            ]
        );

        $builder->add(
            'religion',
            null,
            [
                'label' => 'form.religion'
            ]
        );

        $builder->add(
            'level',
            'model',
            [
                'label'       => 'form.register.for',
                'class'       => 'PGS\CoreDomainBundle\Model\Level\Level',
                'empty_value' => 'form.level.select',
                'query'       => $this->levelQuery,
                'constraints' => [ new NotBlank() ]
            ]
        );

        $builder->add(
            'grade',
            'model',
            [
                'label'       => 'form.register.for.grade',
                'class'       => 'PGS\CoreDomainBundle\Model\Grade\Grade',
                'empty_value' => 'form.grade.select',
                'query'       => $this->gradeQuery,
                'constraints' => [ new NotBlank() ]
            ]
        );

        $builder->add(
            'ethnicity',
            'model',
            [
                'label'       => 'form.ethnicity',
                'class'       => 'PGS\CoreDomainBundle\Model\Ethnicity\Ethnicity',
                'empty_value' => 'form.ethnicity.select',
                'query'       => $this->ethnictityQuery,
                'constraints' => [ new NotBlank() ]
            ]
        );



        $builder->add(
            'child_no',
            'text',
            [
                'label'    => 'form.child.no',
                'required' => true
            ]
        );

        $builder->add(
            'child_total',
            'text',
            [
                'label'    => 'form.child.total',
                'required' => true
            ]
        );

        $builder->add(
            'child_status',
            null,
            [
                'empty_value' => 'form.child.status.select',
                'label'       => 'form.child.status'
            ]
        );



        $builder->add(
            'address',
            'text',
            [
                'label'    => 'form.address',
                'required' => false
            ]
        );

        $builder->add(
            'country',
            'model',
            [
                'label'       => 'form.country',
                'class'       => 'PGS\CoreDomainBundle\Model\Country',
                'empty_value' => 'form.country.select',
                'query'       => $this->countryQuery,
                'constraints' => [ new NotBlank() ]
            ]
        );

        $builder->add(
            'state',
            'model',
            [
                'label'       => 'form.state',
                'class'       => 'PGS\CoreDomainBundle\Model\State',
                'empty_value' => 'form.state.select',
                'constraints' => [ new NotBlank() ]
            ]
        );

        $builder->add(
            'city',
            'text',
            [
                'label'    => 'form.city',
                'required' => false
            ]
        );

        $builder->add(
            'zip',
            'text',
            [
                'label'    => 'form.zipcode',
                'required' => false
            ]
        );


        $builder->add(
            'school',
            'model',
            [
                'label'       => 'form.school',
                'class'       => 'PGS\CoreDomainBundle\Model\School\School',
                'empty_value' => 'form.school.select',
                'query'       => $this->schoolQuery,
                'constraints' => [ new NotBlank() ],
                'required'    => false
            ]
        );

        $builder->add(
            'school_year',
            'model',
            [
                'label'       => 'form.school.year',
                'class'       => 'PGS\CoreDomainBundle\Model\SchoolYear\SchoolYear',
                'empty_value' => 'form.school.year.select',
                'constraints' => [ new NotBlank() ],
                'required'    => false
            ]
        );

        $builder->add(
            'mailing_address',
            'text',
            [
                'label' => 'form.mailing.address'
            ]
        );

        $builder->add(
            'note',
            'text',
            [
                'label' => 'form.note',
                'required' => false,

            ]
        );

        $builder->add(
            'hobby',
            'text',
            [
                'label' => 'form.hobby',
                'required' => false,

            ]
        );

        $builder->add(
            'entered_by',
            'hidden',
            [
                'label'    => 'form.entered.by',
                'required' => false,
                'data'     => 1
            ]
        );

        $builder->add(
            'first_name_father',
            'text',
            [
                'label'    => 'form.name.first.father',
                'required' => false
            ]
        );

        $builder->add(
            'last_name_father',
            'text',
            [
                'label'    => 'form.name.last.father',
                'required' => false
            ]
        );

        $builder->add(
            'occupation_father',
            'text',
            [
                'label'    => 'form.occupation.father',
                'required' => false
            ]
        );

        $builder->add(
            'business_address_father',
            'text',
            [
                'label'    => 'form.business.address.father',
                'required' => false
            ]
        );

        $builder->add(
            'phone_father',
            'text',
            [
                'label'    => 'form.phone.father',
                'required' => false
            ]
        );

        $builder->add(
            'email_father',
            'text',
            [
                'label'    => 'form.mailing.father',
                'required' => false
            ]
        );

        $builder->add(
            'first_name_mother',
            'text',
            [
                'label'    => 'form.name.first.mother',
                'required' => false
            ]
        );

        $builder->add(
            'last_name_mother',
            'text',
            [
                'label'    => 'form.name.last.mother',
                'required' => false
            ]
        );

        $builder->add(
            'occupation_mother',
            'text',
            [
                'label'    => 'form.occupation.mother',
                'required' => false
            ]
        );

        $builder->add(
            'business_address_mother',
            'text',
            [
                'label'    => 'form.business.address.mother',
                'required' => false
            ]
        );

        $builder->add(
            'phone_mother',
            'text',
            [
                'label'    => 'form.phone.mother',
                'required' => false
            ]
        );

        $builder->add(
            'email_mother',
            'text',
            [
                'label'    => 'form.mailing.mother',
                'required' => false
            ]
        );

        $builder->add(
            'first_name_legal_guardian',
            'text',
            [
                'label'    => 'form.name.first.guardian',
                'required' => false
            ]
        );

        $builder->add(
            'last_name_legal_guardian',
            'text',
            [
                'label'    => 'form.name.last.guardian',
                'required' => false
            ]
        );

        $builder->add(
            'occupation_legal_guardian',
            'text',
            [
                'label'    => 'form.occupation.guardian',
                'required' => false
            ]
        );

        $builder->add(
            'phone_legal_guardian',
            'text',
            [
                'label'    => 'form.phone.guardian',
                'required' => false
            ]
        );

        $builder->add(
            'email_legal_guardian',
            'text',
            [
                'label'    => 'form.mailing.guardian',
                'required' => false
            ]
        );

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                /** @var Application $application*/
                $application = $event->getData();
                $form         = $event->getForm();

                $queryState = $this->stateQuery->getNoStateChoice();
                if ($application->getCountry()) {
                    $queryState = $this->stateQuery->getStatesByCountryChoices($application->getCountry());
                }

                $form->add(
                    'state',
                    'model',
                    [
                        'class'       => 'PGS\CoreDomainBundle\Model\State',
                        'label'       => 'form.state',
                        'query'       => $queryState
                    ]
                );

                $querySchoolYear = $this->schoolYearQuery->getNoSchoolYearChoice();
                if ($application->getSchool()) {
                    $querySchoolYear = $this->schoolYearQuery->getSchoolYearBySchoolChoices($application->getSchool());
                }

                $form->add(
                    'school_year',
                    'model',
                    [
                        'label'       => 'form.school.year',
                        'class'       => 'PGS\CoreDomainBundle\Model\SchoolYear\SchoolYear',
                        'query'       => $querySchoolYear
                    ]
                );
            }
        );

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                /** @var Application $application*/
                $application = $event->getData();
                $form         = $event->getForm();

                $queryState = $this->stateQuery->getNoStateChoice();
                if ($country = $this->countryQuery->findOneById($application['country'])) {
                    $queryState = $this->stateQuery->getStatesByCountryChoices($country);
                }

                $form->add(
                    'state',
                    'model',
                    [
                        'class'       => 'PGS\CoreDomainBundle\Model\State',
                        'label'       => 'form.state',
                        'empty_value' => 'form.state.select',
                        'query'       => $queryState,
                        'constraints' => [ new NotBlank() ]
                    ]
                );

                $querySchoolYear = $this->schoolYearQuery->getNoSchoolYearChoice();
                if ($school = $this->schoolQuery->findOneById($application['school'])) {
                    $querySchoolYear = $this->schoolYearQuery->getSchoolYearBySchoolChoices($school);
                }

                $form->add(
                    'school_year',
                    'model',
                    [
                        'label'       => 'form.school.year',
                        'class'       => 'PGS\CoreDomainBundle\Model\SchoolYear\SchoolYear',
                        'empty_value' => 'form.school.year.select',
                        'query'       => $querySchoolYear,
                        'constraints' => [ new NotBlank() ]
                    ]
                );

            }
        );

    }

}
