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
use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Service("pgs.core.form.type.help" )
 * @Tag("form.type", attributes = {"alias" = "help"})
 */
class HelpType extends BaseAbstractType
{
    protected $options =
        [
            'data_class'         => 'PGS\CoreDomainBundle\Model\Help',
            'name'               => 'help',
            'translation_domain' => 'forms'
        ];

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
            'helpI18ns',
            'collection',
            [
                'type'         => new HelpI18nType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label'        => false,
            ]
        );
    }
}
