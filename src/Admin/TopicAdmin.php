<?php


namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class TopicAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form->add('libelle', TextType::class);
        $form->add('content', TextareaType::class);
        $form->add('category');
        $form->add('isAnnouncement');
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('libelle');
        $filter->add('createdAt');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->add('libelle')
            ->add('createdAt')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => []
                ]
            ]);
    }

}