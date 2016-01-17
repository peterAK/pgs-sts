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
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use PGS\CoreDomainBundle\Model\Course\Course;
use PGS\CoreDomainBundle\Model\School\SchoolQuery;

/**
 * @Service("pgs.core.form.type.course" )
 * @Tag("form.type", attributes = {"alias" = "course"})
 */
class CourseType extends BaseAbstractType
{
    protected $options = array(
        'data_class'         => 'PGS\CoreDomainBundle\Model\Course\Course',
        'name'               => 'course',
        'translation_domain' => 'forms'
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'code',
            'text',
            [
                'label'    => 'form.course.code',
                'required' => 'required',
                'attr'     => [ 'style' => 'width: 200px' ]
            ]
        );

        $builder->add(
            'name',
            'text',
            [ 'label'       => 'form.name' ],
            [ 'constraints' => [ new NotBlank()] ]
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
            'school',
            'model',
            [
                'label'       => 'form.school',
                'class'       => 'PGS\CoreDomainBundle\Model\School\School',
                'empty_value' => 'form.school.select',
                'constraints' => [ new NotBlank() ],
                'required'    => false
            ]
        );
    }


}
