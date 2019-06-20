<?php

namespace AppBundle\Form;

use AppBundle\DTO\TaskDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'label' => 'Titre'
            ))
            ->add('content', TextareaType::class, array(
                'label' => 'Contenu',
                'attr' => array(
                   'rows' => 5
                )
            ))
            //->add('author') ===> must be the user authenticated
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TaskDTO::class,
        ]);
    }
}
