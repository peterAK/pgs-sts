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
use PGS\CoreDomainBundle\Model\Message\Message;
use PGS\CoreDomainBundle\Model\UserProfileQuery;
use PGS\CoreDomainBundle\Repository\CountryRepository;
use PGS\CoreDomainBundle\Repository\StateRepository;
use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Service("pgs.core.form.type.message" )
 * @Tag("form.type", attributes = {"alias" = "message"})
 */
class MessageType extends BaseAbstractType
{
    /**
     * @var UserprofileQuery
     *
     */
    protected $userProfileQuery;

    protected $options = array(
        'data_class'         => 'PGS\CoreDomainBundle\Model\Message\Message',
        'name'               => 'message',
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
            'subject',
            'text',
            [
                'label'       => 'form.subject' ,
                'required'   => true,
                'constraints' => [ new NotBlank()]
            ]
        );

        $builder->add(
            'description',
            'textarea',
            [
                'label'       => 'form.description' ,
                'required'   => true,
                'constraints' => [ new NotBlank()]
            ]
        );

    }
}
