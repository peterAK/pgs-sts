<?php

namespace PGS\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CountryAdmin extends Admin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('code', 'text', array('label' => 'Country Code'))
            ->add('name', 'text', array('label' => 'Country Name'));
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('name');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('code')
            ->add('name');
    }
}
