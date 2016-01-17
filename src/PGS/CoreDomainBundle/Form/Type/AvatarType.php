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
use PGS\CoreDomainBundle\Model\Avatar\Avatar;
use PGS\CoreDomainBundle\Repository\CountryRepository;
use PGS\CoreDomainBundle\Repository\StateRepository;
use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Service("pgs.core.form.type.avatar" )
 * @Tag("form.type", attributes = {"alias" = "avatar"})
 */
class AvatarType extends BaseAbstractType
{
    protected $options = array(
        'data_class'         => 'PGS\CoreDomainBundle\Model\Avatar\Avatar',
        'name'               => 'avatar',
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

        $builder->add(
            'avatarI18ns',
            'collection',
            [
                'type'         => new AvatarI18nType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label'        => false,
                'required'     => true
            ]
        );

        $builder->add(
            'picFile',
            'hidden',
            [
                'required'   => true
            ]
        );

        $builder->add(
            'type',
            null,
            [
                'label'      => 'form.type',
                'required'   => true
            ]
        );

        $builder->add(
            'minPoint',
            'text',
            [
                'label'       => 'form.minimalPoint',
            ]
        );
    }
}
