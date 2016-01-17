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
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Repository\CountryRepository;
use PGS\CoreDomainBundle\Repository\StateRepository;
use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Service("pgs.core.form.type.school" )
 * @Tag("form.type", attributes = {"alias" = "school"})
 */
class SchoolType extends BaseAbstractType
{
    protected $options = array(
        'data_class'         => 'PGS\CoreDomainBundle\Model\School\School',
        'name'               => 'school',
        'translation_domain' => 'forms'
    );

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @InjectParams({
     *      "securityContext" = @Inject("security.context")
     * })
     */
    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->securityContext->isGranted('ROLE_ADMIN')) {
            $builder->add(
                'status',
                null,
                [ 'label'       => 'form.status' ]
            );
        }

        $builder->add('logo',
            'hidden',
            [ 'required' => false ]
        );

        $builder->add(
            'code',
            'text',
            [
                'label' => 'form.code',
                'constraints' => [ new NotBlank()]
            ]
        );

        $builder->add(
            'name',
            'text',
            [
                'label' => 'form.name',
                'constraints' => [ new NotBlank()]
            ]
        );

        $builder->add(
            'level',
            'model',
            [
                'label'       => 'form.level',
                'class'       => 'PGS\CoreDomainBundle\Model\Level\Level',
                'empty_value' => 'form.level.select',
                'required'    => false
            ]
        );

        $builder->add(
            'schoolI18ns',
            'collection',
            [
                'type'         => new SchoolI18nType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label'        => false,
            ]
        );

        $builder->add(
            'address',
            'text',
            [
                'label'    => 'form.address'
            ]
        );

        $builder->add(
            'city',
            'text',
            [
                'label' => 'form.city'
            ]
        );

        $builder->add(
            'country',
            'model',
            [
                'label'       => 'form.country',
                'class'       => 'PGS\CoreDomainBundle\Model\Country',
                'empty_value' => 'form.country.select',
                'query'       => $this->getCountryRepository()->getCountryChoices(),
                'constraints' => [ new NotBlank() ],
                'required'    => false
            ]
        );

        $builder->add(
            'state',
            'model',
            [
                'label'       => 'form.state',
                'class'       => 'PGS\CoreDomainBundle\Model\State',
                'empty_value' => 'form.state.select',
                'constraints' => [ new NotBlank() ],
                'required'    => false
            ]
        );


        $builder->add(
            'zip',
            'text',
            [
                'label'    => 'form.zipcode',
                'required' => false,
                'attr'     => [ 'style' => 'width:150px' ]
            ]
        );

        $builder->add(
            'phone',
            'text',
            [
                'label'    => 'form.phone',
                'attr'     => [ 'style' => 'width:150px' ]
            ]
        );

        $builder->add(
            'fax',
            'text',
            [
                'label'    => 'form.fax',
                'required' => false,
                'attr'     => [ 'style' => 'width:150px' ]
            ]
        );

        $builder->add(
            'mobile',
            'text',
            [
                'label'    => 'form.mobile',
                'required' => false,
                'attr'     => [ 'style' => 'width:150px' ]
            ]
        );

        $builder->add(
            'email',
            'email',
            [
                'label'    => 'form.email',
                'required' => false
            ]
        );

        $builder->add(
            'website',
            'url',
            [
                'label'    => 'form.website',
                'required' => false
            ]
        );

        $builder->add('logo',
            'hidden',
            [ 'required' => false ]
        );

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                /** @var School $school*/
                $school = $event->getData();
                $form = $event->getForm();

                $query = $this->getStateRepository()->getNoStateChoice();

                if ($school->getCountry()) {
                    $query = $this->getStateRepository()->getStatesByCountryChoices($school->getCountry());
                }

                $form->add(
                    'state',
                    'model',
                    [
                        'class'       => 'PGS\CoreDomainBundle\Model\State',
                        'label'       => 'form.state',
                        'query'       => $query,
                        'empty_value' => 'form.state.select',
                    ]
                );
            }
        );

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                /** @var School $school */
                $school = $event->getData();
                $form = $event->getForm();

                $query = $this->getStateRepository()->getNoStateChoice();

                if ($country = $this->getCountryRepository()->findOneById($school['country'])) {
                    $query = $this->getStateRepository()->getStatesByCountryChoices($country);
                }

                $form->add(
                    'state',
                    'model',
                    [
                        'class'       => 'PGS\CoreDomainBundle\Model\State',
                        'label'       => 'form.state',
                        'empty_value' => 'form.state.select',
                        'query'       => $query,
                        'constraints' => [ new NotBlank() ]
                    ]
                );
            }
        );
    }

    protected function getCountryRepository()
    {
        return new CountryRepository();
    }

    protected function getStateRepository()
    {
        return new StateRepository();
    }
}
