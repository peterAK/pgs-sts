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

use PGS\CoreDomainBundle\Model\om\BaseTopic;

class Topic extends BaseTopic
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @return array
     */
    public static function getAccesses()
    {
        $accesses = TopicPeer::getValueSets();

        return $accesses['topic.access'];
    }
}
