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
use PGS\CoreDomainBundle\Model\Level\LevelQuery;
use PGS\CoreDomainBundle\Model\Grade\GradeQuery;
use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Service("pgs.core.form.type.grade_level")
 * @Tag("form.type", attributes = {"alias" = "gradeLevel"})
 */
class GradeLevelType extends BaseAbstractType
{


    protected $options = array(
        'data_class'         => 'PGS\CoreDomainBundle\Model\GradeLevel\GradeLevel',
        'name'               => 'gradeLevel',
        'translation_domain' => 'forms'
    );

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var LevelQuery
     */
    private $levelQuery;

    /**
     * @var GradeQuery
     */
    private $gradeQuery;

    /**
     * @InjectParams({
     *      "levelQuery"       = @Inject("pgs.core.query.level"),
     *      "gradeQuery"       = @Inject("pgs.core.query.grade"),
     *      "securityContext"  = @Inject("security.context"),
     * })
     */
    public function __construct(
        LevelQuery $levelQuery,
        GradeQuery $gradeQuery,
        SecurityContext $securityContext
    ) {
        $this->levelQuery             = $levelQuery;
        $this->gradeQuery             = $gradeQuery;
        $this->securityContext        = $securityContext;

    }


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'level',
            'model',
            [
                'label'       => 'form.level',
                'class'       => 'PGS\CoreDomainBundle\Model\Level\Level',
                'empty_value' => 'form.level.select',
                'query'       => $this->levelQuery->orderBySortableRank(),
                'constraints' => [ new NotBlank() ]
            ]
        );

        $builder->add(
            'grade',
            'model',
            [
                'label'       => 'form.grade',
                'class'       => 'PGS\CoreDomainBundle\Model\Grade\Grade',
                'empty_value' => 'form.grade.select',
                'query'       => $this->gradeQuery->orderBySortableRank(),
                'constraints' => [ new NotBlank() ]
            ]
        );
    }


}
