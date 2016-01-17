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
use PGS\CoreDomainBundle\Model\AcademicYear\AcademicYear;
use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Service("pgs.core.form.type.academic_year")
 * @Tag("form.type", attributes = {"alias" = "academic_year"})
 */
class AcademicYearType extends BaseAbstractType
{
    protected $options = array(
        'data_class'         => 'PGS\CoreDomainBundle\Model\AcademicYear\AcademicYear',
        'name'               => 'academic_year',
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
            'yearStart',
            'integer',
            [
                'label' => 'form.year.start',
                'attr'  =>
                    [
                        'max'   => 2050,
                        'min'   => 2010,
                        'style' => 'width:100px'
                    ]
            ]
        );

        $builder->add(
            'yearEnd',
            'integer',
            [
                'label' => 'form.year.end',
                'attr'  =>
                    [
                        'max'   => 2050,
                        'min'   => 2010,
                        'style' => 'width:100px'
                    ]
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

        if ($this->securityContext->isGranted('ROLE_ADMIN')) {
            $builder->add(
                'active',
                'checkbox',
                [ 'label' => 'form.status' ]
            );
        }
    }
}
