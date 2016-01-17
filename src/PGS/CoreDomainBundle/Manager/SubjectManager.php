<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Manager;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Container\ActivePreferenceContainer;
use PGS\CoreDomainBundle\Model\Subject\Subject;
use PGS\CoreDomainBundle\Model\Subject\SubjectQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.subject")
 */
class SubjectManager extends Authorizer
{
    /**
     * @var ActivePreferenceContainer
     */
    public $activePreference;

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var SubjectQuery
     */
    private $subjectQuery;

    /**
     * @InjectParams({
     *      "subjectQuery"     = @Inject("pgs.core.query.subject"),
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "securityContext"  = @Inject("security.context"),
     * })
     */
    public function __construct(
        SubjectQuery $subjectQuery,
        ActivePreferenceContainer $activePreference,
        SecurityContext $securityContext)
    {
        $this->subjectQuery           = $subjectQuery;
        $this->activePreference       = $activePreference;
        $this->securityContext        = $securityContext;
    }

    /**
     * @return SubjectQuery
     */
    public function getBaseQuery()
    {
        return $this->subjectQuery->create();
    }

    /**
     * @return Subject[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->find();
    }

    /**
     * @param mixed $subject
     *
     * @return Subject
     */
    public function findOne($subject)
    {
        if ($subject instanceof Subject) {
            $subject = $subject->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($subject);
    }

    /**
     * @param SubjectQuery $query
     * @return bool|SubjectQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(Subject $subject)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @return bool|Subject
     */
    public function canAdd()
    {
        if ($this->isAdmin()) {
            return new Subject();
        }

        return false;
    }

    /**
     * @param mixed $subject
     *
     * @return bool|Subject
     */
    public function canEdit($subject)
    {
        $subject= $this->findOne($subject);

        if ($this->limitRole($subject)) {
            return false;
        }

        return $subject;
    }

    /**
     * @param mixed $subject
     *
     * @return bool|Subject
     */
    public function canDelete($subject)
    {
        $subject= $this->findOne($subject);

        if ($this->limitRole($subject)) {
            return false;
        }

        return $subject;
    }

    /**
     * @param mixed $subject
     *
     * @return bool|Subject
     */
    public function canView($subject)
    {
        $subject= $this->findOne($subject);

        if ($this->limitRole($subject)) {
            return false;
        }

        return $subject;
    }

}
