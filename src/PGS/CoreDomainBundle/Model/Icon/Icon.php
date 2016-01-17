<?php

namespace PGS\CoreDomainBundle\Model\Icon;

use PGS\CoreDomainBundle\Model\Icon\om\BaseIcon;

class Icon extends BaseIcon
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return array
     */
    public static function getStatuses()
    {
        $statuses = IconPeer::getValueSets();

        return $statuses['icon.status'];
    }
}
