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
use PGS\CoreDomainBundle\Model\PageI18n;
use PGS\CoreDomainBundle\Model\Topic;
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Manager\SchoolManager;
use PGS\CoreDomainBundle\Repository\SchoolRepository;
use PGS\CoreDomainBundle\Manager\TopicManager;
use PGS\CoreDomainBundle\Repository\TopicRepository;
use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Service("pgs.core.form.type.page" )
 * @Tag("form.type", attributes = {"alias" = "page"})
 */
class PageType extends BaseAbstractType
{
    /**
     * @var TopicManager
     *
     * @Inject("pgs.core.manager.topic")
     */
    public $topicManager;

    /**
     * @var SchoolManager
     *
     * @Inject("pgs.core.manager.school")
     */
    public $schoolManager;

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var ActivePreferenceContainer
     */
    protected $activePreference;

    protected $options =
        [
            'data_class'         => 'PGS\CoreDomainBundle\Model\Page',
            'name'               => 'page',
            'translation_domain' => 'forms'
        ];

    /**
     * @InjectParams({
     *      "activePreference" = @Inject("pgs.core.container.active_preference")
     * })
     */
    public function __construct(
        SecurityContext $securityContext,
        ActivePreferenceContainer $activePreference
    ) {
        $this->securityContext  = $securityContext;
        $this->activePreference = $activePreference;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->securityContext->isGranted('ROLE_ADMIN')) {
            $builder->add('status');
        }

        if ($this->securityContext->isGranted('ROLE_ADMIN')) {
            $builder->add(
                'user',
                'model',
                [
                    'label'       => 'form.username',
                    'class'       => 'PGS\CoreDomainBundle\Model\User',
                    'empty_value' => 'form.user.select',
                    'constraints' => [ new NotBlank() ]
                ]
            );
        }

        $builder->add(
            'school',
            'model',
            [
                'label'       => 'form.school',
                'class'       => 'PGS\CoreDomainBundle\Model\School\School',
                'empty_value' => 'form.school.select',
                'query'       => $this->getSchoolChoices(),
                'required'    => false
            ]
        );

        $builder->add(
            'topic',
            'model',
            [
                'label'       => 'form.topic',
                'class'       => 'PGS\CoreDomainBundle\Model\Topic',
                'empty_value' => 'form.page.topic.select',
                'query'       => $this->getTopicChoices(),
                'constraints' => [ new NotBlank() ],
                'required'    => false
            ]
        );

        $builder->add(
            'pageI18ns',
            'collection',
            [
                'type'         => new PageI18nType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label'        => false,
            ]
        );

        $builder->add(
            'startPublish',
            'date',
            [
                'label'  => 'form.publish.start',
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr'   => [ 'style' => 'width:100px' ],
                'required'    => false
            ]
        );

        $builder->add(
            'endPublish',
            'date',
            [
                'label'  => 'form.publish.end',
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr'   => [ 'style' => 'width:100px' ],
                'required'    => false
            ]
        );
    }

    protected function getSchoolChoices()
    {
        return $this->getSchoolManager()->getRestrictedSchoolChoices();
    }

    /**
     * @return SchoolManager
     */
    protected function getSchoolManager()
    {
        return new SchoolManager($this->getSchoolRepository(), $this->activePreference, $this->securityContext );
    }

    /**
     * @return SchoolRepository
     */
    protected function getSchoolRepository()
    {
        return new SchoolRepository();
    }

    public function getTopicChoices()
    {
        return $this->getTopicManager()->getTopicChoices();
    }

    /**
     * @return TopicManager
     */
    public function getTopicManager()
    {
        return new TopicManager($this->getTopicRepository());
    }

    /**
     * @return TopicRepository
     */
    public function getTopicRepository()
    {
        return new TopicRepository;
    }
}
