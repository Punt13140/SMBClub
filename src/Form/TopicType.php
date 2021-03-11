<?php

namespace App\Form;

use App\Entity\Topic;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TopicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('content', CKEditorType::class);


        if ($options['hasModeratorAuthorization']) {
            $builder->add('isPinned', CheckboxType::class, [
                'required' => false
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'hasModeratorAuthorization'
        ]);
        $resolver->setDefaults([
            'data_class' => Topic::class,
        ]);
    }
}
