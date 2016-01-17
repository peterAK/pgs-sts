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

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseUserType;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;

/**
 * @Service("pgs.core.form.type.registration.principal" )
 * @Tag("form.type", attributes = {"alias" = "user"})
 */
class PrincipalRegistrationType extends BaseUserType
{
    private $class;

    /**
     * @InjectParams({
     *      "class" = @Inject("pgs.core.model.user")
     * })
     */
    public function __construct($class)
    {
        $this->class = $class;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add(
            'userprofile',
            new UserProfileType(),
            [ 'label' => false ]
        );
        // custom fields
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'         => 'PGS\CoreDomainBundle\Model\User',
                'intention'          => 'registration',
                'translation_domain' => 'forms'
            ]
        );
    }

    public function getName()
    {
        return 'user';
    }
}
