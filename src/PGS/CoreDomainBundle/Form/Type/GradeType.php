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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Service("pgs.core.form.type.grade")
 * @Tag("form.type", attributes = {"alias" = "grade"})
 */
class GradeType extends BaseAbstractType
{
    protected $options = array(
        'data_class'         => 'PGS\CoreDomainBundle\Model\Grade\Grade',
        'name'               => 'grade',
        'translation_domain' => 'forms'
    );


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'gradeI18ns',
            'collection',
            [
                'type'         => new GradeI18nType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label'        => false,
            ]
        );
    }
}
