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
use PGS\CoreDomainBundle\Model\Behavior\Behavior;
use PGS\CoreDomainBundle\Model\IconQuery;
use PGS\CoreDomainBundle\Model\UserQuery;
use PGS\CoreDomainBundle\Model\Icon\Icon;
use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Service("pgs.core.form.type.behavior")
 * @Tag("form.type", attributes = {"alias" = "behavior"})
 */
class BehaviorType extends BaseAbstractType
{
    /**
     * @var IconQuery
     *
     */
    protected $iconQuery;

    protected $options = array(
        'data_class'         => 'PGS\CoreDomainBundle\Model\Behavior\Behavior',
        'name'               => 'behavior',
        'translation_domain' => 'forms'
    );


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'behaviorI18ns',
            'collection',
            [
                'type'         => new BehaviorI18nType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label'        => false,
            ]
        );

        $builder->add(
            'type',
            null,
            [
                'label' => 'form.type'
            ]
        );

        $builder->add(
            'point',
            'text',
            [
                'label'       => 'form.point' ,
                'required'    => true,
                'constraints' => [ new NotBlank()]
            ]
        );

        $builder->add(
            'icon',
            'model',
            [
                'label'       => 'form.iconFile',
                'class'       => 'PGS\CoreDomainBundle\Model\Icon\Icon',
                'query'       => $this->iconQuery,
                'constraints' => [ new NotBlank() ],
                'required' => false

             ]
        );
    }
}
