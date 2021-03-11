<?php


namespace App\Admin;


use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class UserAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form->add('roles', ChoiceType::class, [
            'multiple' => true,
            'choices' => [
                'Utilisateur normal' => User::$roleUser,
                'Modérateur' => User::$roleModerator,
                'Administrateur' => User::$roleAdmin,
                'Super Administrateur' => User::$roleSuperAdmin
            ]
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('pseudo')
            ->add('email')
            ->add('isVerified');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->add('pseudo')
            ->add('email')
            ->add('Vérifier ?')
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                ]
            ]);
    }
}