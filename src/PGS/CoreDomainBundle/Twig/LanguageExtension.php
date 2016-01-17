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
 * @Service("pgs.core.extension.language")
 * @Tag("twig.extension")
  */
class LanguageExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('language', array($this, 'languageFilter')),
        );
    }

    public function languageFilter($locale) {
        return Intl::getLanguageBundle()->getLanguageName($locale);
    }

    public function getName()
    {
        return 'language_extension';
    }
}
