<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Form;

use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use PGS\CoreDomainBundle\Form\Type\ApplicationStep1Type;
use PGS\CoreDomainBundle\Form\Type\ApplicationStep2Type;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.form.flow.application", parent="craue.form.flow", scope="request")
 */
class ApplicationFlow extends FormFlow
{
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

    public function getName() {
        return 'createApplication';
    }

    protected function loadStepsConfig() {
        return
            [
                1 => [
                    'label' => 'form.application.main',
                    'type'  => new ApplicationStep1Type($this->securityContext)
                ],

                2 => [
                    'label' => 'form.application.address',
                    'type' => new ApplicationStep2Type($this->securityContext)
                ],

                3 => [
                    'label' => 'confirmation',
                ],
            ];
    }
}
