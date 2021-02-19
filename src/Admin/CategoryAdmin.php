<?php


namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class CategoryAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form->add('libelle', TextType::class);
        $form->add('description', TextareaType::class);
        $form->add('categoryParent');
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('libelle');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->add('libelle');
    }

}