<?php

namespace PGS\CoreDomainBundle\Model\SchoolTest;

use PGS\CoreDomainBundle\Model\SchoolTest\om\BaseSchoolTest;

class SchoolTest extends BaseSchoolTest
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
        $statuses = SchoolTestPeer::getValueSets();

        return $statuses['school_test.status'];
    }
}
