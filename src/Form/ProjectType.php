<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('img', FileType::class, [
                'required' => false,
                'data_class' => null,
                'attr' => [
                    'class' => 'mt-3, mx-3'
                ]
            ])
            ->add('github', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('envoyer', SubmitType::class, ['attr' => ['class' => 'btn mt-3']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
