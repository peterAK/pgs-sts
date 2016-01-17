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

use Symfony\Component\Intl\Intl;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;

/**
 * @Service("pgs.core.extension.country")
 * @Tag("twig.extension")
 */
class CountryExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('country', array($this, 'countryFilter')),
        );
    }

    public function countryFilter($countryCode) {
        return Intl::getRegionBundle()->getCountryName(strtoupper($countryCode));
    }

    public function getName()
    {
        return 'country_extension';
    }
}
