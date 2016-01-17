<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Twig;

use PGS\CoreDomainBundle\Container\ActivePreferenceContainer;
use PGS\CoreDomainBundle\Manager\OrganizationManager;

class OrganizationListExtension extends \Twig_Extension
{
    /**
     * @var ActivePreferenceContainer
     */
    private $activePreferenceContainer;

    /**
     * @var OrganizationManager
     */
    private $organizationManager;

    /**
     * @param ActivePreferenceContainer $activePreferenceContainer
     * @param OrganizationManager       $organizationManager
     */
    public function __construct(
        ActivePreferenceContainer $activePreferenceContainer,
        OrganizationManager $organizationManager
    ) {
        $this->activePreferenceContainer = $activePreferenceContainer;
        $this->organizationManager       = $organizationManager;
    }

    public function getFunctions()
    {
        return [
            'list_organizations' => new \Twig_Function_Method($this, 'listOrganizations'),
        ];
    }

    public function listOrganizations()
    {
        return $this->organizationManager->findAll();
    }

    public function getName()
    {
        return 'pgs.core.extension.list_organizations';
    }
}
