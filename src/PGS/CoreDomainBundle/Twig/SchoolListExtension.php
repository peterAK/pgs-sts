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
use PGS\CoreDomainBundle\Manager\SchoolManager;

class SchoolListExtension extends \Twig_Extension
{
    /**
     * @var ActivePreferenceContainer
     */
    private $activePreferenceContainer;

    /**
     * @var SchoolManager
     */
    private $schoolManager;

    /**
     * @param ActivePreferenceContainer $activePreferenceContainer
     * @param SchoolManager             $schoolManager
     */
    public function __construct(
        ActivePreferenceContainer $activePreferenceContainer,
        SchoolManager               $schoolManager
    ) {
        $this->activePreferenceContainer = $activePreferenceContainer;
        $this->schoolManager             = $schoolManager;
    }

    public function getFunctions()
    {
        return [
            'list_schools'           => new \Twig_Function_Method($this, 'listSchools'),
            'list_confirmed_schools' => new \Twig_Function_Method($this, 'listConfirmedSchools'),
        ];
    }

    public function listSchools()
    {
        $organization = $this->activePreferenceContainer->getOrganizationPreference();

        return $this->schoolManager->findByOrganization($organization);
    }

    public function listConfirmedSchools()
    {
        return $this->schoolManager->findByStatusAndConfirmation('active','letter');
    }
    public function getName()
    {
        return 'pgs.core.extension.list_schools';
    }
}
