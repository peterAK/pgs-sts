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
use PGS\CoreDomainBundle\Manager\SchoolYearManager;

class SchoolYearListExtension extends \Twig_Extension
{
    /**
     * @var activePreferenceContainer
     */
    private $activePreferenceContainer;

    /**
     * @var SchoolYearManager
     */
    private $schoolYearManager;

    /**
     * @param ActivePreferenceContainer $activePreferenceContainer
     * @param SchoolYearManager         $schoolYearManager
     */
    public function __construct(
        ActivePreferenceContainer $activePreferenceContainer,
        SchoolYearManager $schoolYearManager
    ) {
        $this->activePreferenceContainer = $activePreferenceContainer;
        $this->schoolYearManager         = $schoolYearManager;
    }

    public function getFunctions()
    {
        return [
            'list_school_years' => new \Twig_Function_Method($this, 'listSchoolYears'),
        ];
    }

    public function listSchoolYears()
    {
        $school = $this->activePreferenceContainer->getSchoolPreference();

        return $this->schoolYearManager->findBySchool($school);
    }

    public function getName()
    {
        return 'pgs.core.extension.list_school_years';
    }
}
