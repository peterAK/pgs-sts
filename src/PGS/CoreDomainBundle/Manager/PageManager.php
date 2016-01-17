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
use PGS\CoreDomainBundle\Model\Page;
use PGS\CoreDomainBundle\Model\PageQuery;
use PGS\CoreDomainBundle\Security\Authorizer;

/**
 * @Service("pgs.core.manager.page")
 */
class PageManager extends Authorizer
{
    /**
     * @var ActivePreferenceContainer
     */
    public  $activePreference;

    /**
     * @var PageQuery
     */
    private $pageQuery;

    /**
     * @InjectParams({
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "pageQuery"        = @Inject("pgs.core.query.page")
     * })
     */
    public function __construct(
        ActivePreferenceContainer $activePreference,
        PageQuery $pageQuery
    ) {
            $this->activePreference = $activePreference;
            $this->pageQuery        = $pageQuery;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
    }

    /**
     * @return PageQuery
     */
    public function getBaseQuery()
    {
        return $this->pageQuery->create();
    }

    /**
     * @return Page[]
     */
    public function findAll()
    {
        return $this->limitList($this->getBaseQuery())->orderBySortableRank()->find();
    }

    /**
     * @param mixed       $page
     * @param string|null $action
     *
     * @return Page
     *
     * @throws \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException
     */
    public function findOne($page, $action = null )
    {
        if ($page instanceof Page)
        {
            $page = $page->getId();
        }

        $page = $this->limitList($this->getBaseQuery())->findOneById($page);

        if ($action === null) {
            if (!$this->isAllowedToView($page)) {
                throw new AccessDeniedException('Page');
            }
        } else {
            if (!$this->isAllowedToEND($page)) {
                throw new AccessDeniedException('Page');
            }
        }

        return $page;
    }

    /**
     * @param string $status
     *
     * @return Page
     */
    public function findByStatus($status)
    {
        if (strtoupper($status) == 'ALL') {
            return $this->findAll();
        } else {
            return $this->limitList($this->getBaseQuery())->findByStatus(strtolower($status));
        }
    }

    /**
     * @return array
     */
    public function getStatuses()
    {
        return Page::getStatuses();
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->limitList($this->getBaseQuery())->count();
    }

    /**
     * @return int
     */
    public function countPublish()
    {
        return $this->limitList($this->getBaseQuery())->filterByStatus('publish')->count();
    }

    /**
     * @param Page $page
     *
     * @return bool
     */
    public function isAllowedToView($page)
    {
        if ($this->isGuest()) {
            return false;
        }

        if ($this->isAdmin()) {
            return true;
        } else {
            if ($this->isPrincipal() || $this->isSchoolAdmin()) {
                return $page->getSchool()->getOrganization() == $this->activePreference->getOrganizationPreference();
            } else {
                return $page->getSchool() == $this->activePreference->getSchoolPreference();
            }
        }
    }

    /**
     * @param Page $page
     *
     * @return bool
     */
    public function isAllowedToEND($page)
    {
        if ($this->isAdmin()) {
            return true;
        } else {
            if ($this->isPrincipal() || $this->isSchoolAdmin()) {
                return $page->getSchool()->getOrganization() == $this->activePreference->getOrganizationPreference();
            }
        }

        return false;
    }

    /**
     * @param PageQuery $query
     * @return bool|PageQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(Page $page)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @return bool|Page
     */
    public function canAdd()
    {
        if ($this->isAdmin()) {
            return new Page();
        }

        return false;
    }

    /**
     * @param mixed $page
     *
     * @return bool|Page
     */
    public function canEdit($page)
    {
        $page = $this->findOne($page);

        if($this->limitRole($page)){
            return false;
        }
        return $page;
    }

    /**
     * @param mixed $page
     *
     * @return bool|Page
     */
    public function canDelete($page)
    {
        $page = $this->findOne($page);

        if($this->limitRole($page)){
            return false;
        }
        return $page;
    }

    /**
     * @param mixed $page
     *
     * @return bool|Page
     */
    public function canView($page)
    {
        $page = $this->findOne($page);

        if($this->limitRole($page)){
            return false;
        }
        return $page;
    }


}
