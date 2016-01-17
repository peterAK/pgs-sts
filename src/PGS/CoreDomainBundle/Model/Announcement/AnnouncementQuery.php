<?php

namespace PGS\CoreDomainBundle\Model\Announcement;

use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Model\Announcement\om\BaseAnnouncementQuery;

/**
 * @Service("pgs.core.query.announcement")
 */
class AnnouncementQuery extends BaseAnnouncementQuery
{
}
