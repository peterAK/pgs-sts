<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Model;

use PGS\CoreDomainBundle\Model\om\BasePage;
use PGS\CoreDomainBundle\Utility\PGSUtilities;

class Page extends BasePage
{
    public function preSave(\PropelPDO $con = null){
        $this->setTitleCanonicalValue();

        return parent::preSave($con);
    }

    public function setTitleCanonicalValue()
    {
        $this->setTitleCanonical(PGSUtilities::canonicalizer($this->getTitle()));
    }

    /**
     * @return array
     */
    public static function getStatuses()
    {
        $statuses = PagePeer::getValueSets();

        return $statuses['page.status'];
    }
}
