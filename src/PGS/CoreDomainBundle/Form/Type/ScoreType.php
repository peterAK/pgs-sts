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
use PGS\CoreDomainBundle\Model\Score\Score;
use PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudentQuery;
use PGS\CoreDomainBundle\Model\Term\TermQuery;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.form.type.score" )
 * @Tag("form.type", attributes = {"alias" = "score"})
 */


class ScoreType extends BaseAbstractType
{
    protected $options = array(
        'data_class'         => 'PGS\CoreDomainBundle\Model\Score\Score',
        'name'               => 'score',
        'translation_domain' => 'forms'
    );

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var SchoolClassStudentQuery
     */
    private $schoolClassStudentQuery;

    /**
     * @var TermQuery
     */
    private $termQuery;

    /**
     * @InjectParams({
     *      "schoolClassStudentQuery"   = @Inject("pgs.core.query.school_class_student"),
     *      "termQuery"     = @Inject("pgs.core.query.term"),
     *      "securityContext"  = @Inject("security.context"),
     * })
     */
    public function __construct(
        SchoolClassStudentQuery $schoolClassStudentQuery,
        TermQuery $termQuery,
        SecurityContext $securityContext
    ) {
        $this->schoolClassStudentQuery  = $schoolClassStudentQuery;
        $this->termQuery     = $termQuery;
        $this->securityContext  = $securityContext;

    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add(
            'homework',
            'number',
            [
                'precision'=> 2,
                'label'    => 'form.score.homework',
                'required' => 'required',
                'attr'     => [ 'style' => 'width: 200px' ]
            ]
        );

        $builder->add(
            'daily_exam',
            'number',
            [
                'precision'=> 2,
                'label'    => 'form.score.daily',
                'required' => 'required',
                'attr'     => [ 'style' => 'width: 200px' ]
            ]
        );
        $builder->add(
            'mid_exam',
            'number',
            [
                'precision'=> 2,
                'label'    => 'form.score.mid',
                'required' => 'required',
                'attr'     => [ 'style' => 'width: 200px' ]
            ]
        );
        $builder->add(
            'final_exam',
            'number',
            [
                'precision'=> 2,
                'label'    => 'form.score.final',
                'required' => 'required',
                'attr'     => [ 'style' => 'width: 200px' ]
            ]
        );

    }
}
