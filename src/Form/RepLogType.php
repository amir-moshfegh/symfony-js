<?php

namespace App\Form;

use App\Entity\RepLog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepLogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('item', ChoiceType::class, [
                'choices' => RepLog::getThingsYouCanLiftChoises(),
                'placeholder' => 'What did you lift?',
            ])
            ->add('item')
            ->add('reps')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RepLog::class,
        ]);
    }
}
