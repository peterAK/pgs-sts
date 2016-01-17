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
use Symfony\Component\Validator\Constraints\NotBlank;
use Propel\PropelBundle\Form\BaseAbstractType;

class SchoolYearI18nType extends BaseAbstractType
{
    protected $options = array(
        'data_class'         => 'PGS\CoreDomainBundle\Model\SchoolYear\SchoolYearI18n',
        'name'               => 'schoolYearI18n',
        'translation_domain' => 'forms'
    );

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'locale',
            'text',
            [
                'label'     => 'form.locale',
                'read_only' => true
            ]
        );

        $builder->add(
            'description',
            'textarea',
            [
                'label'    => 'form.description',
                'required' => false
            ]
        );
    }
}
