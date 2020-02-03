<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Имя',
                'label_attr' => ['class' => 'h5']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Описание',
                'label_attr' => ['class' => 'h5']
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'TODO' => Task::STATUS_TODO,
                    'DOING' => Task::STATUS_DOING,
                    'DONE' => Task::STATUS_DONE,
                ],
                'label' => 'Статус',
                'label_attr' => ['class' => 'h5']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
