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

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use PGS\CoreDomainBundle\Container\ActivePreferenceContainer;

/**
 * @Service("pgs.core.extension.active_preference")
 * @Tag("twig.extension")
 */
class ActivePreferenceExtension extends \Twig_Extension
{
    /**
     * @var ActivePreferenceContainer
     */
    protected $activePreferenceContainer;

    /**
     * @InjectParams({
     *      "activePreferenceContainer" = @Inject("pgs.core.container.active_preference")
     * })
     */
    public function __construct(ActivePreferenceContainer $activePreferenceContainer)
    {
        $this->activePreferenceContainer = $activePreferenceContainer;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            'active_academic_year_preference' => new \Twig_Function_Method($this, 'getAcademicYear'),
            'active_organization_preference'  => new \Twig_Function_Method($this, 'getOrganization'),
            'active_school_preference'        => new \Twig_Function_Method($this, 'getSchool'),
            'active_school_year_preference'   => new \Twig_Function_Method($this, 'getSchoolYear')
        ];
    }

    /**
     * @return \PGS\CoreDomainBundle\Model\Organization\Organization|null
     */
    public function getOrganization()
    {
        return $this->activePreferenceContainer->getOrganizationPreference();
    }

    /**
     * @return \PGS\CoreDomainBundle\Model\School\School|null
     */
    public function getSchool()
    {
        return $this->activePreferenceContainer->getSchoolPreference();
    }

    /**
     * @return \PGS\CoreDomainBundle\Model\SchoolYear\SchoolYear|null
     */
    public function getSchoolYear()
    {
        return $this->activePreferenceContainer->getSchoolYearPreference();
    }

    /**
     * @return \PGS\CoreDomainBundle\Model\AcademicYear\AcademicYear|null
     */
    public function getAcademicYear()
    {
        return $this->activePreferenceContainer->getAcademicYearPreference();
    }

    public function getName()
    {
        return 'pgs.core.extension.active_preference';
    }
}
