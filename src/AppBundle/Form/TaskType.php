<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

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
}
