<?php

namespace App\Form;

use App\Entity\Skill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SkillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, ['attr' => ['class' => 'form-control text-dark my-2', 'placeholder' => 'Nom']])
        ->add('img', FileType::class, [
            'required' => false,
            'data_class' => null,
            'attr' => [
                'class' => 'mt-3, mx-3'
            ]
        ])        
        ->add('submit', SubmitType::class, ['attr' => ['class' => 'btn btn-primary mt-3 my-4'], 'label' => 'Envoyez le message', ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Skill::class,
        ]);
    }
}
