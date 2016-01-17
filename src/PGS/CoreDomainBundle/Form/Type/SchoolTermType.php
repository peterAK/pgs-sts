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
use PGS\CoreDomainBundle\Container\ActivePreferenceContainer;
use PGS\CoreDomainBundle\Model\SchoolTerm\SchoolTerm;
use PGS\CoreDomainBundle\Model\School\SchoolQuery;
use PGS\CoreDomainBundle\Model\SchoolYear\SchoolYearQuery;
use PGS\CoreDomainBundle\Model\Term\TermQuery;
use PGS\CoreDomainBundle\Manager\SchoolManager;
use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Service("pgs.core.form.type.school_term")
 * @Tag("form.type", attributes = {"alias" = "school_term"})
 */
class SchoolTermType extends BaseAbstractType
{
    protected $options = array(
        'data_class'         => 'PGS\CoreDomainBundle\Model\SchoolTerm\SchoolTerm',
        'name'               => 'school_term',
        'translation_domain' => 'forms'
    );

    /**
     * @var SchoolQuery
     */
    protected $schoolQuery;

    /**
     * @var SchoolYearQuery
     */
    protected $schoolYearQuery;

    /**
     * @var TermQuery
     */
    protected $termQuery;

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var ActivePreferenceContainer
     */
    protected $activePreference;

    /**
 * @InjectParams({
 *      "securityContext"   = @Inject("security.context"),
 *      "activePreference"  = @Inject("pgs.core.container.active_preference"),
 *      "schoolQuery"       = @Inject("pgs.core.query.school"),
 *      "schoolYearQuery"   = @Inject("pgs.core.query.school_year"),
 *      "termQuery"         = @Inject("pgs.core.query.term")
 * })
 */
    public function __construct(
        ActivePreferenceContainer $activePreference,
        SchoolYearQuery $schoolYearQuery,
        SchoolQuery $schoolQuery,
        SecurityContext $securityContext,
        TermQuery $termQuery

    ) {
        $this->activePreference = $activePreference;
        $this->securityContext  = $securityContext;
        $this->schoolQuery      = $schoolQuery;
        $this->schoolYearQuery  = $schoolYearQuery;
        $this->termQuery        = $termQuery;

    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

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
            'term',
            'model',
            [
                'label'       => 'form.term',
                'class'       => 'PGS\CoreDomainBundle\Model\Term\Term',
                'empty_value' => 'form.term.select',
                'query'       => $this->termQuery,
                'constraints' => [ new NotBlank() ],
                'required'    => false
            ]
        );

        $builder->add(
            'dateStart',
            'date',
            [
                'label'  => 'form.school.year.start',
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr'   => [ 'style' => 'width:100px' ]
            ]
        );

        $builder->add(
            'dateEnd',
            'date',
            [
                'label'  => 'form.school.year.end',
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr'   => [ 'style' => 'width:100px' ]
            ]
        );

        if ($this->securityContext->isGranted('ROLE_ADMIN')) {
            $builder->add(
                'active',
                'checkbox',
                [
                    'label' => 'form.active'
                ]

            );
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                /** @var SchoolTerm $schoolTerm*/
                $schoolTerm = $event->getData();
                $form         = $event->getForm();

                $query = $this->schoolYearQuery->getNoSchoolYearChoice();
                if ($schoolTerm->getSchool()) {
                    $query = $this->schoolYearQuery->getSchoolYearBySchoolChoices($schoolTerm->getSchool());
                }

                $form->add(
                    'school_year',
                    'model',
                    [
                        'label'       => 'form.school.year',
                        'class'       => 'PGS\CoreDomainBundle\Model\SchoolYear\SchoolYear',
                        'query'       => $query
                    ]
                );
            }
        );

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                /** @var SchoolTerm $schoolTerm*/
                $schoolTerm = $event->getData();
                $form       = $event->getForm();

                $query      = $this->schoolYearQuery->getNoSchoolYearChoice();
                if ($school = $this->schoolQuery->findOneById($schoolTerm['school'])) {
                    $queryr = $this->schoolYearQuery->getSchoolYearBySchoolChoices($school);
                }

                $form->add(
                    'school_year',
                    'model',
                    [
                        'label'       => 'form.school.year',
                        'class'       => 'PGS\CoreDomainBundle\Model\SchoolYear\SchoolYear',
                        'empty_value' => 'form.school.year.select',
                        'query'       => $query,
                        'constraints' => [ new NotBlank() ]
                    ]
                );

            }
        );
    }
}
