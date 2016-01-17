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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Propel\PropelBundle\Form\BaseAbstractType;
use PGS\CoreDomainBundle\Model\UserProfile;
use PGS\CoreDomainBundle\Model\StateQuery;
use PGS\CoreDomainBundle\Model\CountryQuery;
/**
 * @Service("pgs.core.form.type.user_profile" )
 * @Tag("form.type", attributes = {"alias" = "userProfile"})
 */
class UserProfileType extends BaseAbstractType
{
    protected $options = array(
        'data_class'         => 'PGS\CoreDomainBundle\Model\UserProfile',
        'name'               => 'userProfile',
        'translation_domain' => 'forms'
    );

    /**
     * @var StateQuery
     */
    private $stateQuery;

    /**
     * @var CountryQuery
     */
    private $countryQuery;

    /**
     * @InjectParams({
     *      "countryQuery"     = @Inject("pgs.core.query.country"),
     *      "stateQuery"       = @Inject("pgs.core.query.state"),
     * })
     */
    public function __construct(
        CountryQuery $countryQuery,
        StateQuery $stateQuery
    ) {
        $this->countryQuery     = $countryQuery;
        $this->stateQuery       = $stateQuery;

    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'prefix',
            'choice',
            [
                'label'    => 'Select a title',
                'multiple' => false,
                'choices'  => ['Mr' => 'Mr', 'Mrs' => 'Mrs', 'Ms' => 'Ms'],
                'attr'     => ['style' => 'width:75px'],
                'required' => false
            ]
        );

        $builder->add(
            'nickName',
            'text',
            [
                'label' => 'form.name.nick',
                'required' => false
            ]
        );

        $builder->add(
            'firstName',
            'text',
            [
                'label'       => 'form.name.first',
                'required'    => false,
                'constraints' => [ new NotBlank()]
            ]
        );

        $builder->add(
            'middleName',
            'text',
            [
                'label'       => 'form.name.middle',
                'required'    => false,
            ]
        );

        $builder->add(
            'lastName',
            'text',
            [
                'label'       => 'form.name.last',
                'required'    => false,
           ]
        );

        $builder->add(
            'phone',
            'text',
            [
                'label'    => 'form.phone',
                'required' => false,
                'attr'     => ['style' => 'width:150px']
            ]
        );

        $builder->add(
            'mobile',
            'text',
            [
                'label' => 'form.mobile',
                'required' => false,
                'attr'  => ['style' => 'width:150px']
            ]
        );

        $builder->add(
            'address',
            'text',
            [
                'label' => 'form.address',
                'required' => false
            ]
        );

        $builder->add(
            'city',
            'text',
            [
                'label' => 'form.city',
                'required' => false,
                'constraints' => [ new NotBlank()]
            ]
        );

        $builder->add(
            'zip',
            'text',
            [
                'label' => 'form.zipcode',
                'required' => false,
                'attr'  => ['style' => 'width:100px']
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
                'constraints' => [ new NotBlank() ],
                'required'    => false
            ]
        );

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                /** @var UserProfile $profile*/
                $profile = new UserProfile();
                $form = $event->getForm();

                $query = $this->stateQuery->getNoStateChoice();

                if ($profile->getCountry()) {
                    $query = $this->stateQuery->getStatesByCountryChoices($profile->getCountry());
                }

                $form->add(
                    'state',
                    'model',
                    [
                        'label'       => 'form.state',
                        'class'       => 'PGS\CoreDomainBundle\Model\State',
                        'empty_value' => 'form.state.select',
                        'query'       => $query,
                        'constraints' => [ new NotBlank() ]
                    ]
                );
            }
        );

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                /** @var UserProfile $profile */
                $profile = $event->getData();
                $form = $event->getForm();

                $query = $this->stateQuery->getNoStateChoice();

                $country = $this->countryQuery->findOneById($profile['country']);
                if (count($country)) {
                    $query = $this->stateQuery->getStatesByCountryChoices($country);
                }

                $form->add(
                    'state',
                    'model',
                    [
                        'label'       => 'form.state',
                        'class'       => 'PGS\CoreDomainBundle\Model\State',
                        'empty_value' => 'form.state.select',
                        'query'       => $query,
                        'constraints' => [ new NotBlank() ],
                        'required'    => false
                    ]
                );
            }
        );
    }

}
