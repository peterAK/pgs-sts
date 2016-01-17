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
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealth;
use PGS\CoreDomainBundle\Model\Grade\GradeQuery;
use PGS\CoreDomainBundle\Model\Application\ApplicationQuery;
use Propel\PropelBundle\Form\BaseAbstractType;
use Propel\PropelBundle\Form\PropelArrayCollection;
use Propel\PropelBundle\Form\PropelObjectCollection;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Service("pgs.core.form.type.applicationStep2")
 * @Tag("form.type", attributes = {"alias" = "applicationStep2"})
 */
class ApplicationStep2Type extends BaseAbstractType
{
    protected $options = array(
        'data_class'         => 'PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealth',
        'name'               => 'applicationStep2',
        'translation_domain' => 'forms'
    );

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var ApplicationQuery
     */
    private $applicationQuery;

    /**
     * @InjectParams({
     *      "applicationQuery" = @Inject("pgs.core.query.application"),
     *      "securityContext"  = @Inject("security.context"),
     * })
     */
    public function __construct(
        ApplicationQuery $applicationQuery,
        SecurityContext $securityContext
    ) {
        $this->applicationQuery = $applicationQuery;
        $this->securityContext  = $securityContext;

    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add(
            'application_id',
            'hidden',
            [
                'label' => 'form.application.id',
                'required' => false

            ]
        );



        $builder->add(
            'student_name',
            'hidden',
            [
                'label' => 'form.student.name',
                'required' => false
            ]
        );


        $builder->add(
            'emergency_physician_name',
            'text',
            [
                'label'    => 'form.emergency.physician.name',
                'required' => false
            ]
        );

        $builder->add(
            'emergency_relationship',
            'text',
            [
                'label'    => 'form.emergency.relationship',
                'required' => false
            ]
        );

        $builder->add(
            'emergency_phone',
            'text',
            [
                'label'    => 'form.emergency.phone',
                'required' => false
            ]
        );

//        $builder->add(
//            'allergies',
//            null,
//            [
//                'label'      => 'form.allergies',
//            ]
//        );

        $builder->add(
            'allergies',
            'choice',
            [
                'label'      => 'form.allergies',
                'expanded'   => true,
                'choices'    => [
                    true     => 'Yes',
                    false    => 'No',
                ],
                'data'       => false
            ]
        );

        $builder->add(
            'allergies_yes',
            'text',
            [
                'label'    => 'form.allergies.yes',
                'required' => false
            ]
        );

        $builder->add(
            'allergies_action',
            'text',
            [
                'label'    => 'form.allergies.action',
                'required' => false,
            ]
        );

//        $builder->add(
//            'student_psychological',
//            null,
//            [
//                'label'      => 'form.student.psychological',
//            ]
//        );


        $builder->add(
            'student_psychological',
            'choice',
            [
                'label'      => 'form.student.psychological',
                'expanded'   => true,
                'choices'    => [
                    true     => 'Yes',
                    false    => 'No',
                ],
                'data'       => false
            ]
        );

        $builder->add(
            'psychological_exp',
            'text',
            [
                'label'    => 'form.psychological.exp',
                'required' => false
            ]
        );

//        $builder->add(
//            'student_aware',
//            null,
//            [
//                'label'      => 'form.student.aware',
//            ]
//        );

        $builder->add(
            'student_aware',
            'choice',
            [
                'label'      => 'form.student.aware',
                'expanded'   => true,
                'choices'    => [
                    true     => 'Yes',
                    false    => 'No',
                ],
                'data'       => false
            ]
        );

        $builder->add(
            'aware_exp',
            'text',
            [
                'label'    => 'form.aware.exp',
                'required' => false
            ]
        );

//        $builder->add(
//            'student_ability',
//            null ,
//            [
//                'label'      => 'form.student.ability',
//            ]
//        );

        $builder->add(
            'student_ability',
            'choice',
            [
                'label'      => 'form.student.ability',
                'expanded'   => true,
                'choices'    => [
                    true     => 'Yes',
                    false    => 'No',
                ],
                'data'       => false
            ]
        );

        $builder->add(
            'medical_emergency_name',
            'text',
            [
                'label'    => 'form.medical.emergency.name',
                'required' => false
            ]
        );

        $builder->add(
            'medical_emergency_phone',
            'text',
            [
                'label'    => 'form.medical.emergency.phone',
                'required' => false
            ]
        );

        $builder->add(
            'medical_emergency_address',
            'text',
            [
                'label'    => 'form.medical.emergency.address',
                'required' => false
            ]
        );

        $builder->add(
            'parent_statement_name',
            'text',
            [
                'label'    => 'form.parent.statement.name',
                'required' => false
            ]
        );

        $builder->add(
            'student_statement_name',
            'text',
            [
                'label'    => 'form.student.statement.name',
                'required' => false
            ]
        );


        $builder->add(
            'parent_signature',
            'text',
            [
                'label'    => 'form.parent.signature',
                'required' => true
            ]
        );

        $builder->add(
            'date_of_signature',
            'date',
            [
                'label'  => 'form.date.of.signature',
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr'   => [ 'style' => 'width:150px' ]
            ]
        );

    }

}
