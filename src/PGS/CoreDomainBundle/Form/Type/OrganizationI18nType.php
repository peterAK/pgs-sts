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

class OrganizationI18nType extends BaseAbstractType
{
    protected $options = array(
        'data_class'         => 'PGS\CoreDomainBundle\Model\Organization\OrganizationI18n',
        'name'               => 'organizationI18n',
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
            'ckeditor',
            [
                'label'                  => 'form.description',
                'transformers'           => array('html_purifier'),
                'toolbar'                => array('document', 'basicstyles', 'insert', 'styles'),
                'ui_color'               => '#428BCA',
                'startup_outline_blocks' => false,
                'height'                 => '180',
                'language'               => 'en',
                'required'               => false
            ]
        );

        $builder->add(
            'excerpt',
            'ckeditor',
            [
                'label'                  => 'form.description.short',
                'transformers'           => array('html_purifier'),
                'toolbar'                => array('document', 'basicstyles', 'insert', 'styles'),
                'ui_color'               => '#428BCA',
                'startup_outline_blocks' => false,
                'height'                 => '180',
                'language'               => 'en',
                'required'               => false
            ]
        );
    }
}
