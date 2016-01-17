<?php

namespace PGS\CoreDomainBundle\Model\StudentHistory;

use PGS\CoreDomainBundle\Model\StudentHistory\om\BaseStudentHistory;

class StudentHistory extends BaseStudentHistory
{
    public static function getStatuses()
    {
        $statuses = StudentHistoryPeer::getValueSets();

        return $statuses['student_history.status'];
    }
}
