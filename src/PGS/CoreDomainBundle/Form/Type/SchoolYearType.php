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
use PGS\CoreDomainBundle\Model\AcademicYear\AcademicYearQuery;
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Model\School\SchoolQuery;
use PGS\CoreDomainBundle\Manager\SchoolManager;
use PGS\CoreDomainBundle\Manager\AcademicYearManager;
use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Service("pgs.core.form.type.school_year")
 * @Tag("form.type", attributes = {"alias" = "school_year"})
 */
class SchoolYearType extends BaseAbstractType
{
    protected $options = array(
        'data_class'         => 'PGS\CoreDomainBundle\Model\SchoolYear\SchoolYear',
        'name'               => 'school_year',
        'translation_domain' => 'forms'
    );

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var SchoolQuery
     */
    protected $schoolQuery;

    /**
     * @var AcademicYearQuery
     */
    protected $academicYearQuery;

    /**
     * @var ActivePreferenceContainer
     */
    protected $activePreference;

    /**
     * @InjectParams({
     *      "securityContext"   = @Inject("security.context"),
     *      "activePreference"  = @Inject("pgs.core.container.active_preference"),
     *      "schoolQuery"       = @Inject("pgs.core.query.school"),
     *      "academicYearQuery" = @Inject("pgs.core.query.academic_year")
     * })
     */
    public function __construct(
        AcademicYearQuery $academicYearQuery,
        SecurityContext $securityContext,
        ActivePreferenceContainer $activePreference,
        SchoolQuery $schoolQuery
    ) {
        $this->securityContext  = $securityContext;
        $this->schoolQuery       = $schoolQuery;
        $this->academicYearQuery = $academicYearQuery;

    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'active',
            'checkbox',
            [ 'label' => 'Active?' ]
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
            'academicYear',
            'model',
            [
                'label'       => 'form.academic.year',
                'class'       => 'PGS\CoreDomainBundle\Model\AcademicYear\AcademicYear',
                'empty_value' => 'form.academic.year.select',
                'query'       => $this->academicYearQuery,
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

        $builder->add(
            'schoolYearI18ns',
            'collection',
            [
                'type'         => new SchoolYearI18nType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label'        => false,
            ]
        );
    }

    protected function getAcademicYearChoices()
    {
        return $this->getAcademicYearManager()->getChoices();
    }

    /**
     * @return AcademicYearManager
     */
    protected function getAcademicYearManager()
    {
        return new AcademicYearManager($this->getAcademicYearQuery(), $this->activePreference, $this->securityContext);
    }

    /**
     * @return AcademicYearQuery
     */
    protected function getAcademicYearQuery()
    {
        return new AcademicYearQuery();
    }

    protected function getSchoolChoices()
    {
        return $this->getSchoolManager()->getSchoolChoices();
    }

    /**
     * @return SchoolManager
     */
    protected function getSchoolManager()
    {
        return new SchoolManager($this->schoolQuery, $this->activePreference, $this->securityContext );
    }


}
