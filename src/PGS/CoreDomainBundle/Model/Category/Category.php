<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Model\Category;

use PGS\CoreDomainBundle\Model\Category\om\BaseCategory;

class Category extends BaseCategory
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
