<?php

namespace PGS\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class SiteAdmin extends Admin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('metaKey', 'text', array('label' => 'Meta Key'))
            ->add('metaValue', 'text', array('label' => 'Meta Value'));

    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('metaKey');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('metaKey')
            ->add('metaValue');
    }


}
