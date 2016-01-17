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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Service("pgs.core.form.type.topic")
 * @Tag("form.type", attributes = {"alias" = "topic"})
 */
class TopicType extends BaseAbstractType
{
    protected $options = array(
        'data_class'         => 'PGS\CoreDomainBundle\Model\Topic',
        'name'               => 'topic',
        'translation_domain' => 'forms',
        'parentItemId'       => null
    );


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'parentItem',
            'hidden',
            [
                'mapped' => false,
                'data'   => $options['parentItemId']
            ]
        );

        $builder->add(
            'key',
            'text',
            [
                'label'    => 'form.key',
                'required' => true,
                'constraints' => [ new NotBlank() ]
            ]
        );

        $builder->add(
            'title',
            'text',
            [ 'label' => 'form.title' ],
            [ 'constraints' => [ new NotBlank()] ]
        );

        $builder
            ->add(
                'access',
                null,
                [
                    'label'    => 'form.access',
                    'multiple' => false,
                    'attr'     => ['style' => 'width:100px']
                ]
            );
    }
}
