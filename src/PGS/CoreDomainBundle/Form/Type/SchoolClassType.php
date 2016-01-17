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
use PGS\CoreDomainBundle\Container\ActivePreferenceContainer;
use PGS\CoreDomainBundle\Model\School\SchoolQuery;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Service("pgs.core.form.type.schoolClass")
 * @Tag("form.type", attributes = {"alias" = "schoolClass"})
 */
class SchoolClassType extends BaseAbstractType
{
    protected $options = array(
        'data_class'         => 'PGS\CoreDomainBundle\Model\SchoolClass\SchoolClass',
        'name'               => 'SchoolClass',
        'translation_domain' => 'forms'
    );

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var SchoolQuery
     */
    private $schoolQuery;

    /**
     * @var ActivePreferenceContainer
     */
    protected $activePreference;

    /**
     * @InjectParams({
     *      "securityContext"   = @Inject("security.context"),
     *      "activePreference"  = @Inject("pgs.core.container.active_preference"),
     *      "schoolQuery"       = @Inject("pgs.core.query.school")
     * })
     */
    public function __construct(
        SecurityContext $securityContext,
        ActivePreferenceContainer $activePreference,
        SchoolQuery $schoolQuery
    ){
        $this->securityContext       = $securityContext;
        $this->activePreference      = $activePreference;
        $this->schoolQuery           = $schoolQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'school',
            'model',
            [
                'label'       => 'form.school',
                'class'       => 'PGS\CoreDomainBundle\Model\School\School',
                'empty_value' => 'form.school.select',
//                'data'       => $this->activePreference->getSchoolPreference()->getId(),
                'query'       => $this->schoolQuery,
                'constraints' => [ new NotBlank() ],
                'required'    => false
            ]
        );

        $builder->add(
            'schoolClassI18ns',
            'collection',
            [
                'type'         => new SchoolClassI18nType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label'        => false,
            ]
        );
    }
}
