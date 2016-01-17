<?php

namespace PGS\CoreDomainBundle\Model\SchoolClassStudent;

use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Model\SchoolClassStudent\om\BaseSchoolClassStudentQuery;

/**
 * @Service("pgs.core.query.school_class_student")
 */

class SchoolClassStudentQuery extends BaseSchoolClassStudentQuery
{
    /**
     * @return SchoolClassStudentQuery
     */
    public function getNoSchoolClassStudentChoice()
    {
        return $this->create()->filterById();
    }

    /**
     * @param SchoolClass $schoolClass
     *
     * @return SchoolClassStudentQuery
     */
    public function getSchoolClassStudentsBySchoolClassChoices(SchoolClass $schoolClass)
    {
        return $this->create()->filterBySchoolClass($schoolClass);
    }
}
