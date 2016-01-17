<?php

namespace PGS\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class StateAdmin extends Admin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('country', 'model', array(
                'label' => 'Country',
                'class' => 'PGS\HotelManagementBundle\Model\Country'
            ))
            ->add('code', 'text', array('label' => 'State Code'))
            ->add('name', 'text', array('label' => 'State Name'));
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
            ->add('name')
            ->add('country');
    }
}
