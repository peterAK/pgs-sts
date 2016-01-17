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
use PGS\CoreDomainBundle\Container\ActivePreferenceContainer;
use PGS\CoreDomainBundle\Model\School\SchoolQuery;
use PGS\CoreDomainBundle\Model\SchoolTest\SchoolTestI18n;
use PGS\CoreDomainBundle\Manager\SchoolManager;
use PGS\CoreDomainBundle\Manager\TestManager;
use PGS\CoreDomainBundle\Model\Test\TestQuery;
use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Service("pgs.core.form.type.school_test")
 * @Tag("form.type", attributes = {"alias" = "school_test"})
 */
class SchoolTestType extends BaseAbstractType
{
    protected $options = array(
        'data_class'         => 'PGS\CoreDomainBundle\Model\SchoolTest\SchoolTest',
        'name'               => 'school_test',
        'translation_domain' => 'forms'
    );

    /**
     * @var SchoolQuery
     */
    protected $schoolQuery;

    /**
     * @var TestQuery
     */
    protected $testQuery;

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var ActivePreferenceContainer
     */
    protected $activePreference;

    /**
     * @InjectParams({
     *      "securityContext"   = @Inject("security.context"),
     *      "activePreference"  = @Inject("pgs.core.container.active_preference"),
     *      "schoolQuery"       = @Inject("pgs.core.query.school"),
     *      "testQuery"         = @Inject("pgs.core.query.test")
     * })
     */
    public function __construct(
        ActivePreferenceContainer $activePreference,
        SchoolQuery $schoolQuery,
        SecurityContext $securityContext,
        TestQuery $testQuery

    ) {
        $this->activePreference = $activePreference;
        $this->securityContext  = $securityContext;
        $this->schoolQuery      = $schoolQuery;
        $this->testQuery        = $testQuery;

    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'status',
            null,
            [ 'label' => 'form.status' ]
        );

        $builder->add(
            'school',
            'model',
            [
                'label'       => 'form.school',
                'class'       => 'PGS\CoreDomainBundle\Model\School\School',
                'empty_value' => 'form.school.select',
                'query'       => $this->schoolQuery,
                'constraints' => [ new NotBlank() ],
                'required'    => false
            ]
        );

        $builder->add(
            'test',
            'model',
            [
                'label'       => 'form.test',
                'class'       => 'PGS\CoreDomainBundle\Model\Test\Test',
                'empty_value' => 'form.test.select',
                'query'       => $this->testQuery->filterByStatus('publish'),
                'constraints' => [ new NotBlank() ],
                'required'    => false
            ]
        );

        $builder->add(
            'schoolTestI18ns',
            'collection',
            [
                'type'         => new SchoolTestI18nType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label'        => false,
            ]
        );
    }

}

