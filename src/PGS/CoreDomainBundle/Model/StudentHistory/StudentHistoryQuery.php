<?php

namespace PGS\CoreDomainBundle\Model\StudentHistory;

use PGS\CoreDomainBundle\Model\StudentHistory\om\BaseStudentHistoryQuery;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * @Service("pgs.core.query.student_history")
 */
class StudentHistoryQuery extends BaseStudentHistoryQuery
{
}
